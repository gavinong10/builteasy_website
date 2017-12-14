<?php

class Thrive_Dash_List_Connection_SparkPost extends Thrive_Dash_List_Connection_Abstract {

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
		return 'SparkPost';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sparkpost' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {

		$key   = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';
		$email = ! empty( $_POST['connection']['domain'] ) ? $_POST['connection']['domain'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid SparkPost key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $email ) ) {
			return $this->error( __( 'Email field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();


		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to SparkPost using the provided key. <strong>%s</strong>', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'SparkPost connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$sparkpost = $this->getApi();

		if ( isset( $_POST['connection']['domain'] ) ) {
			$domain = $_POST['connection']['domain'];
		} else {
			$credentials = Thrive_Dash_List_Manager::credentials( 'sparkpost' );
			if ( isset( $credentials ) ) {
				$domain = $credentials['domain'];
			}
		}
		$to           = get_option( 'admin_email' );
		$subject      = 'API connection test';
		$html_content = 'This is a test email from Thrive Leads SparkPost API.';
		$text_content = 'This is a test email from Thrive Leads SparkPost API.';

		try {
			$options = array(
				'from'       => $domain,
				'html'       => $html_content,
				'text'       => $text_content,
				'subject'    => $subject,
				'recipients' => array(
					array(
						'address' => array(
							'email' => $to,
						)
					)
				)
			);
			$sparkpost->transmission->send( $options );
		} catch ( Thrive_Dash_Api_SparkPost_Exception $e ) {
			return $e->getMessage();
		}

		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'sparkpost' );
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
		$sparkpost = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'sparkpost' );
		if ( isset( $credentials ) ) {
			$domain = $credentials['domain'];
		}

		try {
			$options = array(
				'from'       => $domain,
				'html'       => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'       => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'subject'    => $data['subject'],
				'recipients' => array(
					array(
						'address' => array(
							'email' => $data['email'],
						)
					)
				)
			);
			$sparkpost->transmission->send( $options );
		} catch ( Thrive_Dash_Api_SparkPost_Exception $e ) {
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
		$sparkpost = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'sparkpost' );
		if ( isset( $credentials ) ) {
			$domain = $credentials['domain'];
		}

		$recipients = array();

		foreach ( $data['emails'] as $email ) {
			$item         = array(
				'address' => array(
					'email' => $email,
				)
			);
			$recipients[] = $item;
		}

		try {
			$options = array(
				'from'       => $domain,
				'html'       => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'       => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'subject'    => $data['subject'],
				'recipients' => $recipients,
			);
			$sparkpost->transmission->send( $options );
		} catch ( Thrive_Dash_Api_SparkPost_Exception $e ) {
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
		$sparkpost   = $this->getApi();
		$credentials = $this->getCredentials();

		if ( empty( $post_data['_asset_group'] ) ) {
			return false;
		}

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == "" ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}
		$from         = $credentials['domain'];
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

		$text_content = strip_tags( $html_content );
		$options      = array(
			'from'       => $from,
			'html'       => $html_content,
			'text'       => $text_content,
			'subject'    => $subject,
			'options'    => array(),
			'recipients' => array(
				array(
					'address' => array(
						'email' => $post_data['email'],
					)
				)
			)
		);

		$result = $sparkpost->transmission->send( $options );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_SparkPost( $this->param( 'key' ) );
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
