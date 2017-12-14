<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_SendGrid extends Thrive_Dash_List_Connection_Abstract {
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
		return 'SendGrid';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'sendgridemail' );
		if($related_api->isConnected()) {
			$this->setParam('new_connection', 1);
		}

		$this->_directFormHtml( 'sendgrid' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid SendGrid key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to SendGrid using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/** @var Thrive_Dash_List_Connection_SendGridEmail $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'sendgridemail' );

		if ( isset( $_POST['connection']['new_connection'] ) && intval( $_POST['connection']['new_connection'] ) === 1 ) {
			/**
			 * Try to connect to the email service too
			 */
			$r_result    = true;
			if ( ! $related_api->isConnected() ) {
				$r_result = $related_api->readCredentials();
			}

			if ( $r_result !== true ) {
				$this->disconnect();

				return $this->error( $r_result );
			}
		} else {
			/**
			 * let's make sure that the api was not edited and disconnect it
			 */
			$related_api->setCredentials( array() );
			Thrive_Dash_List_Manager::save( $related_api );
		}


		return $this->success( __( 'SendGrid connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_SendGrid $sg */

		$sg = $this->getApi();

		try {
			$sg->client->contactdb()->lists()->get();

		} catch ( Thrive_Dash_Api_SendGrid_Exception $e ) {
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
		return new Thrive_Dash_Api_SendGrid( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_SendGrid $api */
		$api = $this->getApi();

		$response = $api->client->contactdb()->lists()->get();

		if ( $response->statusCode() != 200 ) {
			$body = $response->body();

			$this->_error = ucwords( $body->errors['0']->message );

			return false;
		}

		$body = $response->body();

		$lists = array();
		foreach ( $body->lists as $item ) {
			$lists [] = array(
				'id'   => $item->id,
				'name' => $item->name,
			);
		}

		return $lists;
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

		$args = new stdClass();
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		/** @var Thrive_Dash_Api_SendGrid $api */
		$api = $this->getApi();


		$args->email = $arguments['email'];

		if ( ! empty( $first_name ) ) {
			$args->first_name = $first_name;
		}
		if ( ! empty( $last_name ) ) {
			$args->last_name = $last_name;
		}

		if ( ! empty( $arguments['phone'] ) ) {

			$custom_field_id = "phone";

			try {
				$response = $api->client->contactdb()->custom_fields()->_( $custom_field_id )->get();

			} catch ( Thrive_Dash_Api_SendGrid_Exception $e ) {
				return $e->getMessage();
			}
			$result = $response->body();

			if ( isset( $result->errors ) ) {
				$request_body = json_decode( '{
				  "name": "phone",
				  "type": "number"
				}' );
				$api->client->contactdb()->custom_fields()->post( $request_body );
			}

			$args->phone = $arguments['phone'];
		}

		try {
			$response = $api->client->contactdb()->recipients()->post( array( $args ) );
		} catch ( Thrive_Dash_Api_SendGrid_Exception $e ) {
			return $e->getMessage();
		}

		$body = $response->body();
		if ( $body->error_count == 0 ) {
			$request_body = null;
			$recipient_id = $body->persisted_recipients[0];

			try {
				$api->client->contactdb()->lists()->_( $list_identifier )->recipients()->_( $recipient_id )->post( $request_body );

			} catch ( Thrive_Dash_Api_SendGrid_Exception $e ) {
				return $e->getMessage();
			}

			return true;
		}

		return __( 'Unknown Sendgrid Error', TVE_DASH_TRANSLATE_DOMAIN );

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{$email}';
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {

		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		/**
		 * disconnect the email service too
		 */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'sendgridemail' );
		$related_api->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $related_api );

		return $this;
	}

}