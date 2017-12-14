<?php

/**
 * Created by PhpStorm.
 * User: Aurelian Pop
 * Date: 06-Jan-16
 * Time: 1:19 PM
 */
class Thrive_Dash_List_Connection_SendinblueEmail extends Thrive_Dash_List_Connection_Abstract {

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
		return 'SendinBlue';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sendinblueemail' );
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
			return $ajax_call ? __( 'You must provide a valid SendinBlue key', TVE_DASH_TRANSLATE_DOMAIN ) : $this->error( __( 'You must provide a valid SendinBlue key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $ajax_call ? sprintf( __( 'Could not connect to SendinBlue using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) : $this->error( sprintf( __( 'Could not connect to SendinBlue using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/**
		 * Try to connect to the autoresponder too
		 */
		/** @var Thrive_Dash_List_Connection_Sendinblue $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'sendinblue' );

		$r_result = true;
		if ( ! $related_api->isConnected() ) {
			$_POST['connection']['new_connection'] = isset( $_POST['connection']['new_connection'] ) ? $_POST['connection']['new_connection'] : 1;

			$r_result = $related_api->readCredentials();
		}

		if ( $r_result !== true ) {
			$this->disconnect();

			return $this->error( $r_result );
		}

		$this->success( __( 'SendinBlue connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

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
		$sendinblue = $this->getApi();

		$from_email = get_option( 'admin_email' );
		$to         = $from_email;

		$subject      = 'API connection test';
		$html_content = 'This is a test email from Thrive Leads SendinBlue API.';
		$text_content = 'This is a test email from Thrive Leads SendinBlue API.';

		try {
			$data = array(
				"to"      => array( $to => "" ),
				"from"    => array( $from_email, "" ),
				"subject" => $subject,
				"html"    => $html_content,
				"text"    => $text_content
			);

			$sendinblue->send_email( $data );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		$connection = get_option( 'tve_api_delivery_service', false );

		if ( $connection == false ) {
			update_option( 'tve_api_delivery_service', 'sendinblueemail' );
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
		$sendinblue = $this->getApi();

		$from_email = get_option( 'admin_email' );

		try {
			$options = array(
				"to"      => array( $data['email'] => '' ),
				"from"    => array( $from_email, "" ),
				"subject" => $data['subject'],
				'html'    => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'    => empty ( $data['text_content'] ) ? '' : $data['text_content'],
			);
			$sendinblue->send_email( $options );
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
		$sendinblue = $this->getApi();

		$from_email = get_option( 'admin_email' );

		$to = array();
		foreach ( $data['emails'] as $email ) {
			$to[ $email ] = '';
		}

		try {
			$options = array(
				"to"      => $to,
				"from"    => array( $from_email, "" ),
				"subject" => $data['subject'],
				'html'    => empty ( $data['html_content'] ) ? '' : $data['html_content'],
				'text'    => empty ( $data['text_content'] ) ? '' : $data['text_content'],
			);
			$sendinblue->send_email( $options );
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

		$sendinblue = $this->getApi();

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

		$data = array(
			"to"      => array( $post_data['email'] => $visitor_name ),
			"from"    => array( $from_email, "" ),
			"subject" => $subject,
			"html"    => $html_content,
			"text"    => $text_content
		);

		$result = $sendinblue->send_email( $data );

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Sendinblue( "https://api.sendinblue.com/v2.0", $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		$sendinblue = $this->getApi();

		$data = array(
			"page"       => 1,
			"page_limit" => 50
		);

		try {
			$lists = array();

			$raw = $sendinblue->get_lists( $data );

			if ( empty( $raw['data'] ) ) {
				return array();
			}

			foreach ( $raw['data']['lists'] as $item ) {
				$lists [] = array(
					'id'   => $item['id'],
					'name' => $item['name']
				);
			}

			return $lists;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage() . ' ' . __( "Please re-check your API connection details.", TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
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
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		$api = $this->getApi();

		$merge_tags = array(
			'NAME'    => $first_name,
			'SURNAME' => $last_name
		);

		$data = array(
			"email"      => $arguments['email'],
			"attributes" => $merge_tags,
			"listid"     => array( $list_identifier ),
		);


		try {
			$api->create_update_user( $data );

			return true;
		} catch ( Thrive_Dash_Api_SendinBlue_Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown SendinBlue Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{EMAIL}';
	}

}