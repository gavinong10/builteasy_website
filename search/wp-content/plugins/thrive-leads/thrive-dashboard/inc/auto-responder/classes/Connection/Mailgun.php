<?php

/**
 * Created by PhpStorm.
 * User: Aurelian Pop
 * Date: 28-Dec-15
 * Time: 9:22 AM
 */
class Thrive_Dash_List_Connection_Mailgun extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Mailgun';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailgun' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {
		$ajax_call = defined( 'DOING_AJAX' ) && DOING_AJAX;

		$key    = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';
		$domain = ! empty( $_POST['connection']['domain'] ) ? $_POST['connection']['domain'] : '';

		if ( empty( $key ) ) {
			return $ajax_call ? __( 'You must provide a valid Mailgun key', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'You must provide a valid Mailgun key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $domain ) ) {
			return $ajax_call ? __( 'The domain name field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'The domain name field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $ajax_call ? sprintf( __( 'Could not connect to Mailgun using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) : $this->error( sprintf( __( 'Could not connect to Mailgun using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();
		$this->success( __( 'Mailgun connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

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
		$mailgun = $this->getApi();

		if ( isset( $_POST['connection']['domain'] ) ) {
			$domain = $_POST['connection']['domain'];
		} else {
			$credentials = Thrive_Dash_List_Manager::credentials( 'mailgun' );
			if ( isset( $credentials ) ) {
				$domain = $credentials['domain'];
			}
		}

		$from_email = get_option( 'admin_email' );
		$to         = $from_email;

		$subject      = 'API connection test';
		$html_content = 'This is a test email from Thrive Leads Mailgun API.';
		$text_content = 'This is a test email from Thrive Leads Mailgun API.';

		try {
			$mailgun->sendMessage( "$domain",
				array(
					'from'      => $from_email,
					'to'        => $to,
					'subject'   => $subject,
					'text'      => $text_content,
					'html'      => $html_content,
					'multipart' => true
				) );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'mailgun' );
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
		$mailgun = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'mailgun' );
		if ( isset( $credentials ) ) {
			$domain = $credentials['domain'];
		} else {
			return false;
		}
		$from_email = get_option( 'admin_email' );
		try {
			$messsage = array(
				'from'      => $from_email,
				'to'        => $data['email'],
				'subject'   => $data['subject'],
				'text'      => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'html'      => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'multipart' => true
			);

			$mailgun->sendMessage( "$domain", $messsage );

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
		$mailgun = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'mailgun' );
		if ( isset( $credentials ) ) {
			$domain = $credentials['domain'];
		} else {
			return false;
		}
		$from_email = get_option( 'admin_email' );
		try {
			$messsage = array(
				'from'      => $from_email,
				'to'        => $data['emails'],
				'subject'   => $data['subject'],
				'text'      => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'html'      => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'multipart' => true
			);

			$mailgun->sendMessage( "$domain", $messsage );

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

		$mailgun = $this->getApi();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == "" ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}
		$from_email   = get_option( 'admin_email' );
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
			$from_name    = '<' . $post_data['name'] . '>';
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

		$credentials = Thrive_Dash_List_Manager::credentials( 'mailgun' );
		if ( isset( $credentials ) ) {
			$domain = $credentials['domain'];
		}

		$result = $mailgun->sendMessage( "$domain",
			array(
				'from'      => $from_email,
				'to'        => $visitor_name . "<" . $post_data['email'] . ">",
				'subject'   => $subject,
				'text'      => $text_content,
				'html'      => $html_content,
				'multipart' => true
			) );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Mailgun( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {

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

	}
}