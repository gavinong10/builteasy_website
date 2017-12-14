<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 4/9/2015
 * Time: 2:16 PM
 */
class Thrive_Dash_List_Connection_Ontraport extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Ontraport';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'ontraport' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$key    = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';
		$app_id = ! empty( $_POST['connection']['app_id'] ) ? $_POST['connection']['app_id'] : '';

		if ( empty( $key ) || empty( $app_id ) ) {
			return $this->error( __( 'You must provide a valid Ontraport AppID/APIKey', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Ontraport: %s', TVE_DASH_TRANSLATE_DOMAIN ), $this->_error ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();
		$this->success( __( 'Ontraport connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		return is_array( $this->_getLists() );
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return Thrive_Dash_Api_Ontraport
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Ontraport( $this->param( 'app_id' ), $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * Ontraport has both sequences and forms
	 *
	 * @return array|string for error
	 */
	protected function _getLists() {
		/**
		 * just try getting the lists as a connection test
		 */
		try {

			/** @var $op Thrive_Dash_Api_Ontraport */
			$op = $this->getApi();

			$lists = array();

			$data = $op->getSequences();
			if ( ! empty( $data ) ) {
				foreach ( $data as $id => $list ) {
					$lists[] = array(
						'id'   => $id,
						'name' => $list['name']
					);
				}
			}

			return $lists;

		} catch ( Thrive_Dash_Api_Ontraport_Exception $e ) {
			$this->_error = $e->getMessage();

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

		try {
			/** @var $api Thrive_Dash_Api_Ontraport */
			$api = $this->getApi();

			list( $firstname, $lastname ) = $this->_getNameParts( $arguments['name'] );

			$data = array(
				'firstname' => $firstname,
				'lastname'  => $lastname,
				'email'     => $arguments['email'],
			);

			if ( ! empty( $arguments['phone'] ) ) {
				$data['phone'] = $arguments['phone'];
			}

			$api->addContact( $list_identifier, $data );

		} catch ( Exception $e ) {

			return $e->getMessage();
		}

		return true;
	}

	public static function getEmailMergeTag() {
		return '[Email]';
	}

}
