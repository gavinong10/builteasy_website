<?php

/**
 * Created by PhpStorm.
 * User: Aurelian Pop
 * Date: 04-Jan-16
 * Time: 5:38 PM
 */
class Thrive_Dash_List_Connection_Awsses extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Amazon SES';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'awsses' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {
		$ajax_call = defined( 'DOING_AJAX' ) && DOING_AJAX;

		$key       = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';
		$secretkey = ! empty( $_POST['connection']['secretkey'] ) ? $_POST['connection']['secretkey'] : '';
		$email     = ! empty( $_POST['connection']['email'] ) ? $_POST['connection']['email'] : '';

		if ( empty( $key ) ) {
			return $ajax_call ? __( 'You must provide a valid Amazon Web Services Simple Email Service key', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'You must provide a valid Amazon Web Services Simple Email Service key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $secretkey ) ) {
			return $ajax_call ? __( 'You must provide a valid Amazon Web Services Simple Email Service secret key', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'You must provide a valid Amazon Web Services Simple Email Service secret key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $email ) ) {
			return $ajax_call ? __( 'Email field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'Email field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();
		// $result = true;
		if ( $result !== true ) {
			return $ajax_call ? sprintf( __( 'Could not connect to Amazon Web Services Simple Email Service using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) : $this->error( sprintf( __( 'Could not connect to Amazon Web Services Simple Email Service using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();
		$this->success( __( 'Amazon Web Services Simple Email Service connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

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
		$awsses = $this->getApi();

		if ( isset( $_POST['connection']['email'] ) ) {
			$from_email = $_POST['connection']['email'];
			$to         = $_POST['connection']['email'];
		} else {
			$credentials = Thrive_Dash_List_Manager::credentials( 'awsses' );
			if ( isset( $credentials ) ) {
				$from_email = $credentials['email'];
				$to         = $credentials['email'];
			}
		}

		$subject      = 'API connection test';
		$html_content = 'This is a test email from Thrive Leads Amazon Web Services Simple Email Service API.';
		$text_content = 'This is a test email from Thrive Leads Amazon Web Services Simple Email Service API.';

		try {
			$messsage = new Thrive_Dash_Api_Awsses_SimpleEmailServiceMessage();
			$messsage->addTo( $to );
			$messsage->setFrom( $from_email );
			$messsage->setSubject( $subject );
			$messsage->setMessageFromString( $text_content, $html_content );

			$awsses->sendEmail( $messsage );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'awsses' );
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
		$awsses = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'awsses' );
		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

		try {
			$messsage = new Thrive_Dash_Api_Awsses_SimpleEmailServiceMessage();
			$messsage->addTo( $data['email'] );
			$messsage->setFrom( $from_email );
			$messsage->setSubject( $data['subject'] );
			$messsage->setMessageFromString( empty ( $data['text_content'] ) ? '' : $data['text_content'], empty ( $data['html_content'] ) ? '' : $data['html_content'] );

			$awsses->sendEmail( $messsage );

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
		$awsses = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'awsses' );
		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

		try {
			$messsage = new Thrive_Dash_Api_Awsses_SimpleEmailServiceMessage();
			$messsage->addTo( $data['emails'] );
			$messsage->setFrom( $from_email );
			$messsage->setSubject( $data['subject'] );
			$messsage->setMessageFromString( empty ( $data['text_content'] ) ? '' : $data['text_content'], empty ( $data['html_content'] ) ? '' : $data['html_content'] );

			$awsses->sendEmail( $messsage );

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

		$awsses      = $this->getApi();
		$credentials = $this->getCredentials();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == "" ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}
		$from_email   = $credentials['email'];
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
		} else {
			$html_content = str_replace( '[lead_name]', '', $html_content );
			$subject      = str_replace( '[lead_name]', '', $subject );
		}

		$text_content = strip_tags( $html_content );

		$messsage = new Thrive_Dash_Api_Awsses_SimpleEmailServiceMessage();
		$messsage->addTo( $post_data['email'] );
		$messsage->setFrom( $from_email );
		$messsage->setSubject( $subject );
		$messsage->setMessageFromString( $text_content, $html_content );

		$result = $awsses->sendEmail( $messsage );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		switch ( $this->param( 'country' ) ) {
			case 'ireland':
				$country = "email.eu-west-1.amazonaws.com";
				break;
			case 'useast':
				$country = "email.us-east-1.amazonaws.com";
				break;
			case 'uswest':
				$country = "email.us-west-2.amazonaws.com";
				break;
		}

		return new Thrive_Dash_Api_Awsses( $this->param( 'key' ), $this->param( 'secretkey' ), $country );
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