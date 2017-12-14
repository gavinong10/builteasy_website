<?php

/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 15/9/2015
 * Time: 10:09 AM
 */
class Thrive_Dash_List_Connection_ConvertKit extends Thrive_Dash_List_Connection_Abstract {
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
		return 'ConvertKit';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'convertkit' );
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
			return $this->error( __( 'You must provide a valid ConvertKit API Key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to ConvertKit: %s', TVE_DASH_TRANSLATE_DOMAIN ), $this->_error ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'ConvertKit connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
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
	 * @return Thrive_Dash_Api_ConvertKit
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_ConvertKit( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * ConvertKit has both sequences and forms
	 *
	 * @return array|string for error
	 */
	protected function _getLists() {
		/**
		 * just try getting the lists as a connection test
		 */
		try {

			/** @var $op Thrive_Dash_Api_ConvertKit */
			$api = $this->getApi();

			$lists = array();

			$data = $api->getForms();
			if ( ! empty( $data ) ) {
				foreach ( $data as $form ) {
					$lists[] = array(
						'id'   => $form['id'],
						'name' => $form['name']
					);
				}
			}

			return $lists;

		} catch ( Thrive_Dash_Api_ConvertKit_Exception $e ) {
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
			/** @var $api Thrive_Dash_Api_ConvertKit */
			$api = $this->getApi();

			$api->subscribeForm( $list_identifier, $arguments );

		} catch ( Exception $e ) {

			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{{ subscriber.email_address }}​';
	}

}
