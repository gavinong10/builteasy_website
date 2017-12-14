<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 7/22/2015
 * Time: 12:55 PM
 */
class Thrive_Dash_List_Connection_Sendreach extends Thrive_Dash_List_Connection_Abstract {
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
		return 'SendReach';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sendreach' );
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
		$secret = ! empty( $_POST['connection']['secret'] ) ? $_POST['connection']['secret'] : '';

		if ( empty( $key ) || empty( $secret ) ) {
			return $this->error( 'You must provide valid credentials!' );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to SendReach (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		$this->save();

		return $this->success( __( 'SendReach connection established', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_Sendreach $api */
		$api         = $this->getApi();
		$credentials = $this->getCredentials();

		/**
		 * Backwards compatibility with Sendreach V2
		 */
		if ( ! isset( $credentials['version'] ) ) {
			$api = new Thrive_Dash_Api_SendreachV2( $credentials['key'], $credentials['secret'] );
			try {
				$api->getLists();
			} catch ( Exception $e ) {
				return $e->getMessage();
			}

			return true;
		}

		$endpoint = new Thrive_Dash_Api_Sendreach_Lists();

		try {
			$endpoint->getLists( $pageNumber = 1, $perPage = 10 );
		} catch ( Thrive_Dash_Api_Sendreach_Exception $e ) {
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
		$config = new Thrive_Dash_Api_Sendreach_Config( array(
			'apiUrl'     => 'http://dashboard.sendreach.com/api/index.php',
			'publicKey'  => $this->param( 'key' ),
			'privateKey' => $this->param( 'secret' ),
		) );

		return Thrive_Dash_Api_Sendreach::setConfig( $config );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		$lists = array();

		$credentials = $this->getCredentials();

		/**
		 * Backwards compatibility with Sendreach V2
		 */
		if ( ! isset( $credentials['version'] ) ) {
			$this->_error = "Sendreach V2 is no longer available please update your connection details for Sendreach V3. Adding Subscribers will still work with the old settings";

			return false;
		}

		try {
			/** @var Thrive_Dash_Api_Sendreach $api */
			$api      = $this->getApi();
			$endpoint = new Thrive_Dash_Api_Sendreach_Lists();
			$raw      = $endpoint->getLists( $pageNumber = 1, $perPage = 1000 );

			$api_lists = $raw['data']['records'];
			$lists     = array();

			foreach ( $api_lists as $list ) {
				$lists[] = array(
					'id'   => $list['general']['list_uid'],
					'name' => $list['general']['name'],
				);
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
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		$credentials = $this->getCredentials();

		/**
		 * Backwards compatibility with Sendreach V2
		 */
		if ( ! isset( $credentials['version'] ) ) {
			try {
				$this->addSubscriberForV2( $list_identifier, $arguments );
			} catch ( Exception $e ) {
				return $e->getMessage();
			}

			return true;
		}

		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		try {
			/** @var Thrive_Dash_Api_Sendreach $api */
			$api      = $this->getApi();
			$endpoint = new Thrive_Dash_Api_Sendreach_ListSubscribers();

			$endpoint->create( $list_identifier, array(
				'EMAIL' => $arguments['email'], // the confirmation email will be sent!!! Use valid email address
				'FNAME' => $first_name,
				'LNAME' => $last_name
			) );
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	public function addSubscriberForV2( $list_identifier, $arguments ) {

		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		$credentials = $this->getCredentials();
		$api = new Thrive_Dash_Api_SendreachV2( $credentials['key'], $credentials['secret'] );

		try {
			$api->addSubscriber( $list_identifier, $first_name, $last_name, $arguments['email'] );
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}
}
