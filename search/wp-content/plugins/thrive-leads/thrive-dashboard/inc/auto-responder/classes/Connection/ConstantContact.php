<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/16/2015
 * Time: 10:16 AM
 */
class Thrive_Dash_List_Connection_ConstantContact extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * Application API Key
	 */
	const TOKEN_URL = 'https://api.constantcontact.com/mashery/account/';

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Constant Contact';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'constant-contact' );
	}

	public function getTokenUrl() {
		return self::TOKEN_URL;
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$api_key   = ! empty( $_POST['connection']['api_key'] ) ? $_POST['connection']['api_key'] : '';
		$api_token = ! empty( $_POST['connection']['api_token'] ) ? $_POST['connection']['api_token'] : '';

		if ( empty( $api_key ) || empty( $api_token ) ) {
			return $this->error( __( 'You must provide a valid Constant Contact API Key and API token', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Constant Contact using the provided API Key and API Token (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Constant Contact connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		try {
			/** @var Thrive_Dash_Api_ConstantContact $api */
			$api = $this->getApi();

			$api->getLists();

			return true;

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_ConstantContact( $this->param( 'api_key' ), $this->param( 'api_token' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		try {
			$lists = array();

			/** @var Thrive_Dash_Api_ConstantContact $api */
			$api = $this->getApi();

			foreach ( $api->getLists() as $item ) {
				$lists[] = array(
					'id'   => $item['id'],
					'name' => $item['name']
				);
			}

			return $lists;

		} catch ( Exception $e ) {
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
			/** @var Thrive_Dash_Api_ConstantContact $api */
			$api = $this->getApi();

			$user = array(
				'email' => $arguments['email']
			);

			list( $first_name, $last_name ) = explode( " ", ! empty( $arguments['name'] ) ? $arguments['name'] . " " : ' ' );

			if ( ! empty( $arguments['phone'] ) ) {
				$user['work_phone'] = $arguments['phone'];
			}

			if ( ! empty( $first_name ) ) {
				$user['first_name'] = $first_name;
			}

			if ( ! empty( $last_name ) ) {
				$user['last_name'] = $last_name;
			}

			$api->addContact( $list_identifier, $user );

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
		return '{Email Address}';
	}
}
