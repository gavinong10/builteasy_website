<?php

/**
 * Created by PhpStorm.
 * User: Aurelian
 * Date: 14/10/2015
 * Time: 4:59 PM
 */
class Thrive_Dash_List_Connection_Mandrill extends Thrive_Dash_List_Connection_Abstract {

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
		return 'Mandrill';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mailchimp' );
		if($related_api->isConnected()) {
			$credentials = $related_api->getCredentials();
			$this->setParam('mailchimp_key', $credentials['key']);
		}
		$this->_directFormHtml( 'mandrill' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {

		$mailchimp_key = ! empty( $_POST['connection']['mailchimp_key'] ) ? $_POST['connection']['mailchimp_key'] : '';
		$email = ! empty( $_POST['connection']['email'] ) ? $_POST['connection']['email'] : '';

		if(isset($_POST['connection']['mandrill-key'])) {
			$_POST['connection']['mailchimp_key'] = $_POST['connection']['key'];
			$_POST['connection']['key'] = $_POST['connection']['mandrill-key'];
		}

		if ( empty( $_POST['connection']['key'] ) ) {
			return $this->error( __( 'You must provide a valid Mandrill key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $email ) ) {
			return $this->error( __( 'Email field must not be empty', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();
		if ( $result !== true ) {
			/**
			 * Doing this because Mandrill devs are retarded and because it's unprofessional for the end user to read 'gibberish'
			 */
			preg_match( '#"?(.+?)\{(.+)\}(")?$#', json_decode( $result ), $matches );
			if ( ! empty( $matches ) ) {
				$result  = json_decode( '{' . $matches[2] . '}', true );
				$message = false;
				foreach ( $result as $level1 ) {
					foreach ( $level1 as $level2 ) {
						if ( ! $message ) {
							$message = $level2;
						};
					}
				}
			} else {
				$message = $result;
			}

			return $this->error( sprintf( __( 'Could not connect to Mandrill using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $message ? $message : '' ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		if(!empty($mailchimp_key)) {
			/**
			 * Try to connect to the email service too
			 */
			/** @var Thrive_Dash_List_Connection_Mandrill $related_api */
			$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mailchimp' );
			$r_result = true;
			if ( ! $related_api->isConnected() ) {
				$r_result = $related_api->readCredentials();
			}

			if ( $r_result !== true ) {
				$this->disconnect();
				return $this->error( $r_result );
			}
		}

		return $this->success( __( 'Mandrill connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$mandrill = $this->getApi();

		if ( isset( $_POST['connection']['email'] ) ) {
			$from_email = $_POST['connection']['email'];
			$to         = $_POST['connection']['email'];
		} else {
			$credentials = Thrive_Dash_List_Manager::credentials( 'mandrill' );
			if ( isset( $credentials ) ) {
				$from_email = $credentials['email'];
				$to         = $credentials['email'];
			} else {
				return false;
			}
		}

		$subject      = 'API connection test';
		$html_content = 'This is a test email from Thrive Leads Mandrill API.';
		$text_content = 'This is a test email from Thrive Leads Mandrill API.';

		$message = array(
			'html'           => $html_content,
			'text'           => $text_content,
			'subject'        => $subject,
			'from_email'     => $from_email,
			'from_name'      => '',
			'to'             => array(
				array(
					'email' => $to,
					'name'  => '',
					'type'  => 'to'
				)
			),
			'headers'        => array( 'Reply-To' => $from_email ),
			'merge'          => true,
			'merge_language' => 'mailchimp'

		);
		$async   = false;
		$ip_pool = 'Main Pool';


		try {
			$result = $mandrill->messages->send( $message, $async, $ip_pool );
			if ( isset( $result['body'] ) ) {
				$body = json_decode( $result['body'] );
				$body = $body[0];
				if ( $body->status == 'rejected' ) {
					if ( $body->reject_reason == 'unsigned' ) {
						return $this->error( __( "The email filled in was not verified by Mandrill", TVE_DASH_TRANSLATE_DOMAIN ) );
					}

					return $this->error( __( "Mandrill couldn't connect", TVE_DASH_TRANSLATE_DOMAIN ) );
				}
			}
		} catch ( Thrive_Dash_Api_Mandrill_Exceptions $e ) {
			return $e->getMessage();
		}
		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'mandrill' );
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
		$mandrill = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'mandrill' );

		if ( isset( $credentials ) ) {
			$from_email = $credentials['email'];
		} else {
			return false;
		}

		try {

			$message = array(
				'html'           => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'           => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'subject'        => $data['subject'],
				'from_email'     => $from_email,
				'from_name'      => '',
				'to'             => array(
					array(
						'email' => $data['email'],
						'name'  => '',
						'type'  => 'to'
					)
				),
				'headers'        => array( 'Reply-To' => $from_email ),
				'merge'          => true,
				'merge_language' => 'mailchimp'

			);
			$async   = false;
			$ip_pool = 'Main Pool';

			$mandrill->messages->send( $message, $async, $ip_pool );

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
		$mandrill = $this->getApi();

		$credentials = Thrive_Dash_List_Manager::credentials( 'mandrill' );

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
				'type'  => 'to'
			);
			$to[] = $temp;
		}

		try {

			$message = array(
				'html'           => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'           => empty ( $data['text_content'] ) ? '' : $data['text_content'],
				'subject'        => $data['subject'],
				'from_email'     => $from_email,
				'from_name'      => '',
				'to'             => $to,
				'headers'        => array( 'Reply-To' => $from_email ),
				'merge'          => true,
				'merge_language' => 'mailchimp',
			);

			$async   = false;
			$ip_pool = 'Main Pool';

			$mandrill->messages->send( $message, $async, $ip_pool );

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
		$mandrill = $this->getApi();

		$asset   = get_post( $post_data['_asset_group'] );
		$files   = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_files', true );
		$subject = get_post_meta( $post_data['_asset_group'], 'tve_asset_group_subject', true );

		if ( $subject == "" ) {
			$subject = get_option( 'tve_leads_asset_mail_subject' );
		}

		$credentials = Thrive_Dash_List_Manager::credentials( 'mandrill' );
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
			$from_name    = $post_data['name'];
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

		$message = array(
			'html'           => $html_content,
			'text'           => $text_content,
			'subject'        => $subject,
			'from_email'     => $from_email,
			'from_name'      => '',
			'to'             => array(
				array(
					'email' => $post_data['email'],
					'name'  => $visitor_name,
					'type'  => 'to'
				)
			),
			'headers'        => array( 'Reply-To' => $from_email ),
			'merge'          => true,
			'merge_language' => 'mailchimp'

		);
		$async   = false;
		$ip_pool = 'Main Pool';


		$result = $mandrill->messages->send( $message, $async, $ip_pool );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Mandrill( $this->param( 'key' ) );
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