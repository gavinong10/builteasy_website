<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 07.05.2015
 * Time: 18:23
 */
class Thrive_Dash_List_Connection_ReCaptcha extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'other';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'ReCaptcha';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'recaptcha' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$site   = ! empty( $_POST['site_key'] ) ? $_POST['site_key'] : '';
		$secret = ! empty( $_POST['secret_key'] ) ? $_POST['secret_key'] : '';

		if ( empty( $site ) || empty( $secret ) ) {
			return $this->error( __( 'Both Site Key and Secret Key fields are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Incorrect Secret Key.', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'ReCaptcha connected successfully!', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if the secret key is correct and it exists.
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$CAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';

		$_capthca_params = array(
			'response' => '',
			'secret'   => $this->param( 'secret_key' )
		);

		$request  = tve_dash_api_remote_post( $CAPTCHA_URL, array( 'body' => $_capthca_params ) );
		$response = json_decode( wp_remote_retrieve_body( $request ), true );
		if ( ! empty( $response ) && isset( $response['error-codes'] ) && in_array( 'invalid-input-secret', $response['error-codes'] ) ) {
			return false;
		}

		return true;
	}


	public function getSiteKey() {
		$this->getCredentials();

		return $this->param( 'site_key' );
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