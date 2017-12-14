<?php

/**
 * Created by PhpStorm.
 * User: Laura
 * Date: 21.09.2015
 * Time: 11:15
 */
class Thrive_Dash_List_Connection_HubSpot extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'HubSpot';
	}

	/**
	 * @return string
	 */
	public function getListSubtitle() {
		return __( 'Choose from the following contact lists', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'hubspot' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid HubSpot key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to HubSpot using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'HubSpot connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_HubSpot $api */
		$api = $this->getApi();
		/**
		 * just try getting the static contact lists as a connection test
		 */
		try {
			$api->getContactLists(); // this will throw the exception if there is a connection problem
		} catch ( Thrive_Dash_Api_HubSpot_Exception $e ) {
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
		return new Thrive_Dash_Api_HubSpot( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_HubSpot $api */
		$api = $this->getApi();
		try {
			$lists        = array();
			$contactLists = $api->getContactLists();
			foreach ( $contactLists as $key => $item ) {
				$lists [] = array(
					'id'   => $item['listId'],
					'name' => $item['name']
				);
			}

			return $lists;
		} catch ( Thrive_Dash_Api_HubSpot_Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}
	}

	/**
	 * add a contact to a static list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		/** @var Thrive_Dash_Api_HubSpot $api */
		$api = $this->getApi();

		try {
			$name = empty( $arguments['name'] ) ? '' : $arguments['name'];
			$api->registerToContactList( $list_identifier, $name, $arguments['email'] );

			return true;
		} catch ( Thrive_Dash_Api_HubSpot_Exception $e ) {
			return $e->getMessage();
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '*|EMAIL|*';
	}
}
