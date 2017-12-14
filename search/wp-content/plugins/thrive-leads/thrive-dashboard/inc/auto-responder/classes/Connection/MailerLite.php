<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_MailerLite extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'MailerLite';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailerlite' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid MailerLite key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to MailerLite using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'MailerLite connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_MailerLite $mailer */
		$mailer = $this->getApi();

		/**
		 * just try getting a list as a connection test
		 */

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groupsApi */
			$groupsApi = $mailer->groups();
			$groupsApi->get();
		} catch ( Thrive_Dash_Api_MailerLite_MailerLiteSdkException $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_MailerLite( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_MailerLite $api */
		$api = $this->getApi();

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groupsApi */
			$groupsApi = $api->groups();
			$lists_obj = $groupsApi->get();

			$lists = array();
			foreach ( $lists_obj as $item ) {
				$lists [] = array(
					'id'   => $item->id,
					'name' => $item->name,
				);
			}

			return $lists;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage() . ' ' . __( 'Please re-check your API connection details.', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
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
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		/** @var Thrive_Dash_Api_MailerLite $api */
		$api = $this->getApi();

		$args['email'] = $arguments['email'];

		if ( ! empty( $first_name ) ) {
			$args['fields']['name'] = $first_name;
		}
		if ( ! empty( $last_name ) ) {
			$args['fields']['last_name'] = $last_name;
		}

		if ( isset( $arguments['phone'] ) ) {
			$args['fields']['phone'] = $arguments['phone'];

		}
		$args['resubscribe'] = 1;

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groupsApi */
			$groupsApi = $api->groups();

			$groupsApi->addSubscriber( $list_identifier, $args );

			return true;
		} catch ( Thrive_Dash_Api_MailerLite_MailerLiteSdkException $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown MailerLite Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{$email}';
	}

}
