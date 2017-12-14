<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/10/2015
 * Time: 4:59 PM
 */
class Thrive_Dash_List_Connection_ArpReach extends Thrive_Dash_List_Connection_Abstract {
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
		return 'ArpReach';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'arpreach' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function readCredentials() {
		$url = ! empty( $_POST['connection']['url'] ) ? $_POST['connection']['url'] : '';

		$app_key = ! empty( $_POST['connection']['api_key'] ) ? $_POST['connection']['api_key'] : '';

		$lists = ! empty( $_POST['connection']['lists'] ) ? $_POST['connection']['lists'] : array();

		$lists = array_filter( $lists );

		if ( empty( $url ) || empty( $app_key ) ) {
			return $this->error( __( "Invalid URL or API key", TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( empty( $lists ) ) {
			return $this->error( __( 'Please provide at least one list for your subscribers', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$_POST['connection']['lists'] = $lists;

		$this->setCredentials( $_POST['connection'] );

		if ( $this->testConnection() !== true ) {
			return $this->error( __( "Invalid URL or API key", TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->save();

		return $this->success( __( 'ArpReach connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		try {
			/** @var Thrive_Dash_Api_ArpReach $api */
			$api = $this->getApi();

			return strtolower( $api->testConnection()->status ) === 'ok';

		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );

			return false;
		}
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		$api = new Thrive_Dash_Api_ArpReach( $this->param( 'url' ), $this->param( 'api_key' ) );

		return $api;
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		try {
			$lists = array();

			foreach ( $this->param( 'lists' ) as $id ) {
				$lists[] = array(
					'id'   => $id,
					'name' => "#" . $id
				);
			}

			return $lists;

		} catch ( Exception $e ) {

		}

		return null;
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

			list( $first_name, $last_name ) = explode( " ", ! empty( $arguments['name'] ) ? $arguments['name'] : ' ' );

			$params = array(
				'email'      => $arguments['email'],
				'phone'      => ! empty( $arguments['phone'] ) ? $arguments['phone'] : '',
				'first_name' => $first_name,
				'last_name'  => $last_name
			);

			/** @var Thrive_Dash_Api_ArpReach $api */
			$api = $this->getApi();

			//add contact
			$api->addContact( $params );
			//add to list
			$api->addToList( $list_identifier, $params );

			return true;
		} catch ( Thrive_Dash_Api_ArpReach_ContactException_Exists $e ) {
			// make sure the contact is updated
			$api->editContact( $params );
			// add contact to the list
			$api->addToList( $list_identifier, $params );

			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{EMAIL_ADDRESS}';
	}
}
