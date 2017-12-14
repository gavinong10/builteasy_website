<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_Google extends Thrive_Dash_List_Connection_Abstract {

	protected $_key = 'google';

	/**
	 * Thrive_Dash_List_Connection_Google constructor.
	 */
	public function __construct() {
		$this->setCredentials( Thrive_Dash_List_Manager::credentials( $this->_key ) );
	}

	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'social';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Google';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'google' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$client_id     = ! empty( $_POST['client_id'] ) ? $_POST['client_id'] : '';
		$client_secret = ! empty( $_POST['client_secret'] ) ? $_POST['client_secret'] : '';

		if ( empty( $client_id ) || empty( $client_secret ) ) {
			return $this->error( __( 'Both Client ID and Client Secret fields are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Incorrect Client ID.', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Google connected successfully!', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if the secret key is correct and it exists.
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		//TODO: implement testing the connection
		return true;
	}

	/**
	 * @return string
	 */
	public function customSuccessMessage() {
		return ' ';
	}

	/*
	 * Those functions do not apply
	 */
	protected function _apiInstance() {
	}

	protected function _getLists() {
	}

	public function addSubscriber( $list_identifier, $arguments ) {
	}
}
