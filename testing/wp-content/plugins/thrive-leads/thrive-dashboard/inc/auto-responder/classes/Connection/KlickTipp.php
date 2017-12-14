<?php

/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 29-Jul-15
 * Time: 10:58
 */
class Thrive_Dash_List_Connection_KlickTipp extends Thrive_Dash_List_Connection_Abstract {

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
		return 'KlickTipp';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'klicktipp' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$user     = ! empty( $_POST['connection']['kt_user'] ) ? $_POST['connection']['kt_user'] : '';
		$password = ! empty( $_POST['connection']['kt_password'] ) ? $_POST['connection']['kt_password'] : '';

		if ( empty( $user ) || empty( $password ) ) {
			return $this->error( __( 'Email and password are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( array(
			'user'     => $user,
			'password' => $password
		) );

		/** @var Thrive_Dash_Api_KlickTipp $api */
		$api = $this->getApi();

		try {
			$api->login();

			$result = $this->testConnection();

			if ( $result !== true ) {
				return $this->error( sprintf( __( 'Could not connect to Klick Tipp using the provided data: %s', TVE_DASH_TRANSLATE_DOMAIN ), $this->_error ) );
			}

			/**
			 * finally, save the connection details
			 */
			$this->save();

			return $this->success( __( 'Klick Tipp connected successfully!', TVE_DASH_TRANSLATE_DOMAIN ) );

		} catch ( Thrive_Dash_Api_KlickTipp_Exception $e ) {
			return $this->error( sprintf( __( 'Could not connect to Klick Tipp using the provided data (%s)', TVE_DASH_TRANSLATE_DOMAIN ), $e->getMessage() ) );
		}
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
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_KlickTipp( $this->param( 'user' ), $this->param( 'password' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_KlickTipp $api */
		$api = $this->getApi();

		try {
			$api->login();
		} catch ( Thrive_Dash_Api_KlickTipp_Exception $e ) {
			return $this->error( sprintf( __( 'Could not connect to Klick Tipp using the provided data (%s)', TVE_DASH_TRANSLATE_DOMAIN ), $e->getMessage() ) );
		}

		try {
			$all = $api->getLists();

			$lists = array();
			foreach ( $all as $id => $name ) {
				if(!empty($name)) {
					$lists[] = array(
						'id'   => $id,
						'name' => $name
					);
				}
			}

			return $lists;
		} catch ( Thrive_Dash_Api_KlickTipp_Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}
	}

	/**
	 * Subscribe an email. Requires to be logged in.
	 *
	 * @param mixed $list_identifier The id subscription process.
	 * @param mixed $arguments (optional) Additional fields of the subscriber.
	 *
	 * @return An object representing the Klicktipp subscriber object.
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		/** @var Thrive_Dash_Api_KlickTipp $api */
		$api = $this->getApi();

		try {
			$api->login();
		} catch ( Thrive_Dash_Api_KlickTipp_Exception $e ) {
			return $this->error( sprintf( __( 'Could not connect to Klick Tipp using the provided data (%s)', TVE_DASH_TRANSLATE_DOMAIN ), $e->getMessage() ) );
		}

		/**
		 * not sure if this is ok
		 */
		$arguments['tagid'] = isset( $arguments['klicktipp_tag'] ) ? $arguments['klicktipp_tag'] : 0;

		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		if ( empty( $first_name ) ) {
			$first_name = " ";
		}

		if ( empty( $last_name ) ) {
			$last_name = " ";
		}

		try {
			$api->subscribe(
				$arguments['email'],
				$list_identifier,
				$arguments['tagid'],
				array(
					'fieldFirstName' => $first_name,
					'fieldLastName'  => $last_name
				)
			);
			/**
			 * get redirect url if needed
			 */
			$return = true;
			if(isset($_POST['_submit_option']) && $_POST['_submit_option'] == 'klicktipp-redirect') {
				$return = $api->subscription_process_redirect($list_identifier, $arguments['email']);
			}

			$api->logout();

			return $return;
		} catch ( Thrive_Dash_Api_KlickTipp_Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Gets a list of tags through GET /tag API
	 *
	 * @return array
	 */
	public function getTags() {
		$tags = array();

		try {
			/** @var Thrive_Dash_Api_KlickTipp $api */
			$api  = $this->getApi();
			$tags = $api->getTags();
		} catch ( Exception $e ) {

		}

		return $tags;
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$params['tags'] = $this->getTags();
		if ( ! is_array( $params['tags'] ) ) {
			$params['tags'] = array();
		}

		$this->_directFormHtml( 'klicktipp/tags', $params );
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '%Subscriber:EmailAddress%';
	}
}
