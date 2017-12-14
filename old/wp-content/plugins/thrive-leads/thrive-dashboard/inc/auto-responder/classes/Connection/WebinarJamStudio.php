<?php

/**
 * Created by PhpStorm.
 * User: Laura
 * Date: 17.07.2015
 * Time: 11:15
 */
class Thrive_Dash_List_Connection_WebinarJamStudio extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'webinar';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'WebinarJamStudio';
	}

	/**
	 * these are called webinars, not lists
	 * @return string
	 */
	public function getListSubtitle() {
		return 'Choose from the following upcoming webinars';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'webinarjamstudio' );
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
			return $this->error( __( 'You must provide a valid WebinarJamStudio key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to WebinarJamStudio using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'WebinarJamStudio connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );

	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_WebinarJamStudio $api */
		$api = $this->getApi();
		/**
		 * just try getting the list of the webinars as a connection test
		 */
		try {
			$api->getUpcomingWebinars(); // this will throw the exception if there is a connection problem
		} catch ( Thrive_Dash_Api_WebinarJamStudio_Exception $e ) {
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
		return new Thrive_Dash_Api_WebinarJamStudio( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_WebinarJamStudio $api */
		$api = $this->getApi();
		try {
			$lists    = array();
			$webinars = $api->getUpcomingWebinars();
			foreach ( $webinars as $key => $item ) {
				$lists [] = array(
					'id'   => $item['webinar_id'],
					'name' => $item['name']
				);
			}

			return $lists;
		} catch ( Thrive_Dash_Api_WebinarJamStudio_Exception $e ) {
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
		/** @var Thrive_Dash_Api_WebinarJamStudio $api */
		$api = $this->getApi();

		try {
			$name = empty( $arguments['name'] ) ? '' : $arguments['name'];
			$phone = !isset( $arguments['phone'] ) || empty( $arguments['phone'] ) ? '' : $arguments['phone'];
			$api->registerToWebinar( $list_identifier, $name, $arguments['email'], $phone );

			return true;
		} catch ( Thrive_Dash_Api_WebinarJamStudio_Exception $e ) {
			return $e->getMessage();
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}
}
