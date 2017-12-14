<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_CampaignMonitorEmail extends Thrive_Dash_List_Connection_Abstract {

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
		return 'Campaign Monitor';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'campaignmonitoremail' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid Campaign Monitor key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Campaign Monitor using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/**
		 * Try to connect to the autoresponder too
		 */
		/** @var Thrive_Dash_List_Connection_MailRelay $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitor' );

		$r_result = true;
		if ( ! $related_api->isConnected() ) {
			$_POST['connection']['new_connection'] = isset( $_POST['connection']['new_connection'] ) ? $_POST['connection']['new_connection'] : 1;
			$r_result                              = $related_api->readCredentials();
		}

		if ( $r_result !== true ) {
			$this->disconnect();

			return $this->error( $r_result );
		}

		return $this->success( __( 'Campaign Monitor connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_CampaignMonitor $cm */

		$cm         = $this->getApi();
		$from_email = '';
		$to         = '';
		if ( isset( $_POST['connection']['email'] ) ) {
			$from_email = $_POST['connection']['email'];
			$to         = $_POST['connection']['email'];
		} else {
			$credentials = Thrive_Dash_List_Manager::credentials( 'campaignmonitoremail' );
			if ( isset( $credentials ) ) {
				$from_email = $credentials['email'];
				$to         = $credentials['email'];
			}
		}

		$args = array(
			'Subject'     => 'API connection test',
			'From'        => $from_email,
			'BBC'         => null,
			'Html'        => 'This is a test email from Thrive Leads Campaign Monitor API.',
			'Text'        => 'This is a test email from Thrive Leads Campaign Monitor API.',
			'To'          => array(
				$to,
			),
			"TrackOpens"  => true,
			"TrackClicks" => true,
			"InlineCSS"   => true,
		);

		try {
			$clients = $clients = $cm->get_clients();

			/** @var Thrive_Dash_Api_CampaignMonitor_ClassicEmail $email */
			$email = $cm->transactional();
			$email->set_client( $clients[0]->ClientID );
			$email->send( $args );

		} catch ( Thrive_Dash_Api_CampaignMonitor_Exception $e ) {
			return $e->getMessage();
		}

		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'campaignmonitoremail' );
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
		$cm = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'campaignmonitoremail' );

		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

		try {

			$message = array(
				'Subject'     => $data['subject'],
				'Html'        => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'Text'        => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'BBC'         => null,
				'From'        => $from_email,
				'To'          => array(
					$data['email'],
				),
				"TrackOpens"  => true,
				"TrackClicks" => true,
				"InlineCSS"   => true,
			);

			$clients = $clients = $cm->get_clients();

			/** @var Thrive_Dash_Api_CampaignMonitor_ClassicEmail $email */
			$email = $cm->transactional();
			$email->set_client( $clients[0]->ClientID );
			$email->send( $message );

		} catch ( Thrive_Dash_Api_CampaignMonitor_Exception $e ) {
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
		$cm = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'campaignmonitoremail' );

		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

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
				'Subject'     => $data['subject'],
				'Html'        => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'Text'        => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'BBC'         => null,
				'From'        => $from_email,
				'To'          => $data['emails'],
				"TrackOpens"  => true,
				"TrackClicks" => true,
				"InlineCSS"   => true,
			);

			$clients = $clients = $cm->get_clients();

			/** @var Thrive_Dash_Api_CampaignMonitor_ClassicEmail $email */
			$email = $cm->transactional();
			$email->set_client( $clients[0]->ClientID );
			$email->send( $message );

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
		$cm = $this->getApi();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == '' ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}

		$credentials = Thrive_Dash_List_Manager::credentials( 'campaignmonitoremail' );

		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

		$html_content = $asset->post_content;

		if ( $html_content == '' ) {
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

		$text_content = strip_tags( $html_content );
		$to           = $post_data['email'];
		if ( ! empty( $visitor_name ) ) {
			$to = $visitor_name . ' <' . $to . '>';
		}

		$message = array(
			'Subject'     => $subject,
			'Html'        => $html_content,
			'Text'        => $text_content,
			'BBC'         => null,
			'From'        => $from_email,
			'To'          => $to,
			"TrackOpens"  => true,
			"TrackClicks" => true,
			"InlineCSS"   => true,
		);

		$clients = $clients = $cm->get_clients();

		/** @var Thrive_Dash_Api_CampaignMonitor_ClassicEmail $email */
		$email = $cm->transactional();
		$email->set_client( $clients[0]->ClientID );
		$result = $email->send( $message );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_CampaignMonitor( array(
				'api_key' => $this->param( 'key' ),
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