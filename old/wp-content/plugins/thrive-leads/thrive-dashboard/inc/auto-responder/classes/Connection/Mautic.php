<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_Mautic extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Mautic';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mautic' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		/** @var Thrive_Dash_Api_Mautic_OAuth $mautic */
		$mautic = $this->getApi();

		try {
			$mautic->validateAccessToken();
		} catch ( Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException $e ) {
			return $e->getMessage();
		}

		if ( $mautic->accessTokenUpdated() ) {
			$data            = get_option( 'tvd_mautic_credentials' );
			$accessTokenData = $mautic->getAccessTokenData();
			$credentials     = array_merge( $accessTokenData, $data );

			$this->setCredentials( $credentials );
		}

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not test Mautic connection: %s', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return true;
	}

	/**
	 * Returns the authorize URL by appending the request
	 * token to the end of the Authorize URI, if it exists
	 *
	 * @return string The Authorization URL
	 */
	public function getAuthorizeUrl() {

		$url    = ! empty( $_POST['connection']['baseUrl'] ) ? $_POST['connection']['baseUrl'] : '';
		$key    = ! empty( $_POST['connection']['clientKey'] ) ? $_POST['connection']['clientKey'] : '';
		$secret = ! empty( $_POST['connection']['clientSecret'] ) ? $_POST['connection']['clientSecret'] : '';


		if ( empty( $url ) ) {
			return $this->error( __( 'You must provide a valid Mautic api url', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid Mautic Public Key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $secret ) ) {
			return $this->error( __( 'You must provide a valid Mautic Secret Key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}


		/** @var Thrive_Dash_Api_Mautic_OAuth $mautic */
		$mautic = $this->getApi();
		/**
		 * check for trailing slash and remove it
		 */
		if ( substr( $this->param( 'baseUrl' ), - 1 ) == '/' ) {
			$url = substr( $this->param( 'baseUrl' ), 0, - 1 );
		}

		update_option( 'tvd_mautic_credentials', array(
			'baseUrl'      => $url,
			'version'      => $this->param( 'version' ),
			'clientKey'    => $this->param( 'clientKey' ),
			'clientSecret' => $this->param( 'clientSecret' ),
			'callback'     => admin_url( 'admin.php?page=tve_dash_api_connect&api=mautic' ),
		) );

		try {
			return $mautic->validateAccessToken();
		} catch ( Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException $e ) {
			$this->error($e->getMessage());
		}

	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_Mautic_OAuth $mautic */
		$mautic = $this->getApi();

		$mautic->setAccessTokenDetails( $this->getCredentials() );

		$this->checkResetCredentials();

		$credentials = get_option( 'tvd_mautic_credentials' );

		/**
		 * just try getting a list as a connection test
		 */
		try {
			/** @var Thrive_Dash_Api_Mautic_Contacts $contactsApi */
			$contactsApi = Thrive_Dash_Api_Mautic::getContext( 'contacts', $mautic, $credentials['baseUrl'] . '/api/' );
			$contactsApi->getSegments();
		} catch ( Exception $e ) {
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
		return Thrive_Dash_Api_Mautic_ApiAuth::initiate( array(
			'baseUrl'      => $this->param( 'baseUrl' ),
			'version'      => $this->param( 'version' ),
			'clientKey'    => $this->param( 'clientKey' ),
			'clientSecret' => $this->param( 'clientSecret' ),
			'callback'     => admin_url( 'admin.php?page=tve_dash_api_connect&api=mautic' ),
		) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_Mautic_OAuth $api */
		$api = $this->getApi();
		$api->setAccessTokenDetails( $this->getCredentials() );

		$this->checkResetCredentials();

		/** @var Thrive_Dash_Api_Mautic_Contacts $contactsApi */
		$contactsApi = Thrive_Dash_Api_Mautic::getContext( 'contacts', $api, $this->param( 'baseUrl' ) . '/api/' );

		try {

			$lists = $contactsApi->getSegments();

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
		$args = array();

		if ( ! empty( $arguments['name'] ) ) {
			list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );
			$args['firstname'] = $first_name;
			$args['lastname']  = $last_name;
		}

		/** @var Thrive_Dash_Api_Mautic_OAuth $api */
		$api = $this->getApi();
		$api->setAccessTokenDetails( $this->getCredentials() );

		$this->checkResetCredentials();

		/** @var Thrive_Dash_Api_Mautic_Contacts $contacts */
		/** @var Thrive_Dash_Api_Mautic_Lists $list */
		$contacts = Thrive_Dash_Api_Mautic::getContext( 'contacts', $api, $this->param( 'baseUrl' ) . '/api/' );
		$list  = Thrive_Dash_Api_Mautic::getContext( 'lists', $api, $this->param( 'baseUrl' ) . '/api/' );


		if ( isset( $arguments['phone'] ) ) {
			$args['phone'] = $arguments['phone'];
		}

		try {
			$args['ipAddress'] = $_SERVER['REMOTE_ADDR'];
			$args['email']     = $arguments['email'];
			$lead              = $contacts->create( $args );

			$list->addLead( $list_identifier, $lead['contact']['id'] );

			return true;
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{leadfield=email}';
	}

	/**
	 * Reset the access token and expiration date
	 */
	private function checkResetCredentials() {

		/** @var Thrive_Dash_Api_Mautic_OAuth $api */
		$api = $this->getApi();
		$api->setAccessTokenDetails( $this->getCredentials() );

		$api->validateAccessToken();

		if ( $api->accessTokenUpdated() ) {
			/**
			 * It seems that, the token was expired and has been updated let's resave the data
			 */
			$accessTokenData = $api->getAccessTokenData();
			$data            = get_option( 'tvd_mautic_credentials' );
			$credentials     = array_merge( $accessTokenData, $data );

			$this->setCredentials( $credentials );

			/**
			 * re-save the connection details
			 */
			$this->save();
		}
	}

	/**
	 * get the API Connection code to use in calls
	 *
	 * @return mixed
	 */
	public function getApi() {
		if ( isset( $_REQUEST['oauth_token'] ) || isset( $_REQUEST['state'] ) ) {

			$data = get_option( 'tvd_mautic_credentials' );

			return Thrive_Dash_Api_Mautic_ApiAuth::initiate( $data );
		} elseif ( ! isset( $this->_api ) ) {
			$this->_api = $this->_apiInstance();
		}

		return $this->_api;
	}

}
