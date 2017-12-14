<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_MailRelayEmail extends Thrive_Dash_List_Connection_Abstract {

	/**
	 * Return if the connection is in relation with another connection so we won't show it in the API list
	 * @return bool
	 */
	public function isRelated() {
		return true;
	}

	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'email';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'MailRelay';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailrelayemail' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid MailRelay key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$_POST['connection']['domain'] = isset( $_POST['connection']['url'] ) ? $_POST['connection']['url'] : $_POST['connection']['domain'];

		$url = ! empty( $_POST['connection']['domain'] ) ? $_POST['connection']['domain'] : '';

		if ( filter_var( $url, FILTER_VALIDATE_URL ) === false || empty( $url ) ) {
			return $this->error( __( 'You must provide a valid MailRelay URL', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to MailRelay using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/**
		 * Try to connect to the autoresponder too
		 */
		/** @var Thrive_Dash_List_Connection_MailRelay $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mailrelay' );

		$r_result = true;
		if ( ! $related_api->isConnected() ) {
			$_POST['connection']['new_connection'] = isset( $_POST['connection']['new_connection'] ) ? $_POST['connection']['new_connection'] : 1;

			$r_result = $related_api->readCredentials();
		}

		if ( $r_result !== true ) {
			$this->disconnect();

			return $this->error( $r_result );
		}

		return $this->success( __( 'MailRelay connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_MailRelay $mr */

		$mr = $this->getApi();

		$email = get_option( 'admin_email' );

		$args = array(
			'subject' => 'API connection test',
			'html'    => 'This is a test email from Thrive Leads MailRelay API.',
			'emails'  => array(
					'name'  => '',
					'email' => $email,
			)
		);

		try {
			$mr->sendEmail( $args );
		} catch ( Thrive_Dash_Api_MailRelay_Exception $e ) {
			return $e->getMessage();
		}

		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'mailrelayemail' );
		}

		return true;
	}

	/**
	 * Send custom email
	 *
	 * @param $data
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function sendCustomEmail( $data ) {
		$mr = $this->getApi();

		try {

			$message = array(
				'html'    => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'subject' => $data['subject'],
				'emails'  => array(
						'email' => $data['email'],
						'name'  => '',
				),
			);

			$mr->sendEmail( $message );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Send the same email to multiple addresses
	 *
	 * @param $data
	 *
	 * @return bool|string
	 */
	public function sendMultipleEmails( $data ) {
		$mr = $this->getApi();

		/**
		 * prepare $to
		 */
		$to = array();
		foreach ( $data['emails'] as $email ) {
			$temp = array(
				'email' => $email,
				'name'  => '',
			);
			$to[] = $temp;
		}

		try {

			$message = array(
				'html'    => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'subject' => $data['subject'],
				'emails'  => $to
			);

			$mr->sendEmail( $message );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Send the email to the user
	 *
	 * @param $post_data
	 *
	 * @return bool|string
	 */
	public function sendEmail( $post_data ) {
		$mr = $this->getApi();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == "" ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}

		$credentials = Thrive_Dash_List_Manager::credentials( 'mailrelayemail' );
		if ( isset( $credentials ) ) {

			$from_email = $credentials['email'];
		} else {
			return false;
		}

		$html_content = $asset->post_content;

		if ( $html_content == "" ) {
			$html_content = get_option( 'tve_leads_asset_mail_body' );
		}

		$attached_files = '';
		foreach ( $files as $file ) {
			$attached_files[] = '<a href="' . $file['link'] . '">' . $file['link_anchor'] . '</a><br/>';
		}
		$the_files = implode( '<br/>', $attached_files );

		$html_content = str_replace( '[asset_download]', $the_files, $html_content );
		$html_content = str_replace( '[asset_name]', $asset->post_title, $html_content );
		$subject      = str_replace( '[asset_name]', $asset->post_title, $subject );

		if ( isset( $post_data['name'] ) && ! empty( $post_data['name'] ) ) {
			$html_content = str_replace( '[lead_name]', $post_data['name'], $html_content );
			$subject      = str_replace( '[lead_name]', $post_data['name'], $subject );
			$visitor_name = $post_data['name'];
		} else {
			$html_content = str_replace( '[lead_name]', '', $html_content );
			$subject      = str_replace( '[lead_name]', '', $subject );
			$visitor_name = '';
		}

		$message = array(
			'subject' => $subject,
			'html'    => $html_content,
			'emails'  => array(
				array(
					'email' => $post_data['email'],
					'name'  => $visitor_name,
					'type'  => 'to'
				)
			),

		);

		$result = $mr->sendEmail( $message );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_MailRelay( array(
				'host'   => $this->param( 'domain' ),
				'apiKey' => $this->param( 'key' ),
			)
		);
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {

	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|string true for success or string error message for failure
	 */
	public function addSubscriber( $list_identifier, $arguments ) {


	}
}