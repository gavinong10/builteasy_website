<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 4/10/2015
 * Time: 3:16 PM
 */
class Thrive_Dash_List_Connection_iContact extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'iContact';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'iContact' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$apiId       = ! empty( $_POST['connection']['appId'] ) ? $_POST['connection']['appId'] : '';
		$apiUsername = ! empty( $_POST['connection']['apiUsername'] ) ? $_POST['connection']['apiUsername'] : '';
		$apiPassword = ! empty( $_POST['connection']['apiPassword'] ) ? $_POST['connection']['apiPassword'] : '';

		if ( empty( $apiId ) || empty( $apiUsername ) || empty( $apiPassword ) ) {
			return $this->error( __( 'You must provide a valid iContact AppID/Username/Password', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to iContact: %s', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'iContact connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$lists = $this->_getLists();
		if ( $lists === false ) {
			return $this->_error;
		}

		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return Thrive_Dash_Api_iContact
	 */
	protected function _apiInstance() {
		return Thrive_Dash_Api_iContact::getInstance()->setConfig( $this->getCredentials() );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		$api   = $this->getApi();
		$lists = array();

		try {
			$data = $api->getLists();
			if ( count( $data ) ) {
				foreach ( $data as $item ) {
					$lists[] = array(
						'id'   => $item->listId,
						'name' => $item->name
					);
				}
			}
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}

		return $lists;
	}

	/**
	 * add a contact to a list
	 *
	 * @param array $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed true -> success; string -> error;
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		$sEmail  = $arguments['email'];
		$sStatus = 'normal';
		$sPrefix = null;
		$sPhone  = null;
		list( $sFirstName, $sLastName ) = $this->_getNameParts( $arguments['name'] );
		$sSuffix     = null;
		$sStreet     = null;
		$sStreet2    = null;
		$sCity       = null;
		$sState      = null;
		$sPostalCode = null;
		$sPhone      = empty( $arguments['phone'] ) ? '' : $arguments['phone'];

		try {

			/** @var Thrive_Dash_Api_iContact $api */
			$api = $this->getApi();

			$contact = $api->addContact( $sEmail, $sStatus, $sPrefix, $sFirstName, $sLastName, $sSuffix, $sStreet, $sStreet2, $sCity, $sState, $sPostalCode, $sPhone );
			if ( empty( $contact ) || ! is_object( $contact ) || empty( $contact->contactId ) ) {
				throw new Thrive_Dash_Api_iContact_Exception( 'Unable to save contact' );
			}

			$api->subscribeContactToList( $contact->contactId, $list_identifier );

			return true;

		} catch ( Thrive_Dash_Api_iContact_Exception $e ) {

			return $e->getMessage();

		} catch ( Exception $e ) {

			return $e->getMessage();
		}

	}
}
