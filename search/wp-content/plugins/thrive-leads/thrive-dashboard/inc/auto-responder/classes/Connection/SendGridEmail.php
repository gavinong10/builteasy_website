<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_SendGridEmail extends Thrive_Dash_List_Connection_Abstract {

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
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'SendGrid';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sendgridemail' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {
		$ajax_call = defined( 'DOING_AJAX' ) && DOING_AJAX;

		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $ajax_call ? __( 'You must provide a valid SendGrid key', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'You must provide a valid SendGrid key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $ajax_call ? sprintf( __( 'Could not connect to SendGrid using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) : $this->error( sprintf( __( 'Could not connect to SendGrid using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/**
		 * Try to connect to the autoresponder too
		 */
		/** @var Thrive_Dash_List_Connection_SendGrid $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'sendgrid' );

		$r_result = true;
		if ( ! $related_api->isConnected() ) {
			$_POST['connection']['new_connection'] = isset( $_POST['connection']['new_connection'] ) ? $_POST['connection']['new_connection'] : 1;

			$r_result = $related_api->readCredentials();
		}

		if ( $r_result !== true ) {
			$this->disconnect();

			return $this->error( $r_result );
		}

		$this->success( __( 'SendGrid connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

		if ( $ajax_call ) {
			return true;
		}

	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {

		/** @var Thrive_Dash_Api_SendGridEmail $sg */
		$sg    = $this->getApi();
		$email = new Thrive_Dash_Api_SendGridEmail_Email();

		/**
		 * Try sending the email
		 */
		try {
			$from_email = get_option( 'admin_email' );
			$to         = $from_email;

			$subject      = 'API connection test';
			$html_content = 'This is a test email from Thrive Leads SendGrid Email API.';
			$text_content = 'This is a test email from Thrive Leads SendGrid Email API.';

			$email
				->addTo( $to )
				->setFrom( $from_email )
				->setSubject( $subject )
				->setText( $text_content )
				->setHtml( $html_content );

			$sg->send( $email );

		} catch ( Thrive_Dash_Api_SendGridEmail_Exception $e ) {
			return $e->getMessage();
		}

		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'sendgridemail' );
		}

		return true;

		/**
		 * just try getting a list as a connection test
		 */
	}

	/**
	 * Send custom email
	 *
	 * @param $data
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function sendCustomEmail( $data ) {
		$sg    = $this->getApi();
		$email = new Thrive_Dash_Api_SendGridEmail_Email();

		$from_email = get_option( 'admin_email' );

		/**
		 * Try sending the email
		 */
		try {
			$email
				->addTo( $data['email'] )
				->setFrom( $from_email )
				->setSubject( $data['subject'] )
				->setText( empty ( $data['text_content'] ) ? '' : $data['text_content'] )
				->setHtml( empty ( $data['html_content'] ) ? '' : $data['html_content'] );

			$sg->send( $email );
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
		$sg    = $this->getApi();
		$email = new Thrive_Dash_Api_SendGridEmail_Email();

		$from_email = get_option( 'admin_email' );

		$to = array_shift( $data['emails'] );

		/**
		 * Try sending the email
		 */
		try {
			$email
				->addTo( $to )
				->setFrom( $from_email )
				->setSubject( $data['subject'] )
				->setText( empty ( $data['text_content'] ) ? '' : $data['text_content'] )
				->setHtml( empty ( $data['html_content'] ) ? '' : $data['html_content'] );

			foreach ( $data['emails'] as $item ) {
				$email->addCc( $item );
			}

			$sg->send( $email );
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
		if ( empty( $post_data['_asset_group'] ) ) {
			return true;
		}

		$sg    = $this->getApi();
		$email = new Thrive_Dash_Api_SendGridEmail_Email();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( empty( $subject ) ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}
		$from_email   = get_option( 'admin_email' );
		$html_content = $asset->post_content;

		if ( empty( $html_content ) ) {
			$html_content = get_option( 'tve_leads_asset_mail_body' );
		}

		$the_files = '';
		foreach ( $files as $file ) {
			$the_files .= '<a href="' . $file['link'] . '">' . $file['link_anchor'] . '</a><br/><br/>';
		}

		$html_content = str_replace( '[asset_download]', $the_files, $html_content );
		$html_content = str_replace( '[asset_name]', $asset->post_title, $html_content );
		$subject      = str_replace( '[asset_name]', $asset->post_title, $subject );

		if ( isset( $post_data['name'] ) && ! empty( $post_data['name'] ) ) {
			$html_content = str_replace( '[lead_name]', $post_data['name'], $html_content );
			$subject      = str_replace( '[lead_name]', $post_data['name'], $subject );
			$visitor_name = $post_data['name'];
		} else {
			$from_name    = "";
			$html_content = str_replace( '[lead_name]', '', $html_content );
			$subject      = str_replace( '[lead_name]', '', $subject );
			$visitor_name = '';
		}

		$text_content = strip_tags( $html_content );


		$email
			->addTo( $post_data['email'], $visitor_name )
			->setFrom( $from_email )
			->setSubject( $subject )
			->setText( $text_content )
			->setHtml( $html_content );

		/**
		 * Try sending the email
		 */
		$result = $sg->send( $email );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		$options = array(
			'host'     => 'api.sendgrid.com',
			'endpoint' => '/api/mail.send.json',
			'port'     => null,
			'url'      => null,
		);

		return new Thrive_Dash_Api_SendGridEmail( $this->param( 'key' ), $options );
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		return true;
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		return true;
	}

}
