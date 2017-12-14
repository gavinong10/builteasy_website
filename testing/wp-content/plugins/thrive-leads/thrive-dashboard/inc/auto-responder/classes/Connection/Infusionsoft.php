<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_Infusionsoft extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Infusionsoft';
	}

	public function getListSubtitle() {
		return __( 'Choose your Tag Name List', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'infusionsoft' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$client_id = ! empty( $_POST['connection']['client_id'] ) ? $_POST['connection']['client_id'] : '';
		$key       = ! empty( $_POST['connection']['api_key'] ) ? $_POST['connection']['api_key'] : '';

		if ( empty( $key ) || empty( $client_id ) ) {
			return $this->error( __( 'You must provide a valid Infusionsoft credentials', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Infusionsoft using the provided credentials (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( 'Infusionsoft connected successfully' );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/**
		 * just try getting a list as a connection test
		 */
		return is_array( $this->_getLists() );
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Infusionsoft( $this->param( 'client_id' ), $this->param( 'api_key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		try {
			/** @var Thrive_Dash_Api_Infusionsoft $api */
			$api = $this->getApi();

			$queryData      = array(
				'GroupName' => '%',
			);
			$selectedFields = array( 'Id', 'GroupName' );
			$response       = $api->data( 'query', 'ContactGroup', 1000, 0, $queryData, $selectedFields );

			if ( empty( $response ) ) {
				return array();
			}

			$lists = array();

			foreach ( $response as $item ) {
				$lists[] = array(
					'id'   => $item['Id'],
					'name' => $item['GroupName'],
				);
			}

			return $lists;

		} catch ( Exception $e ) {
			return $e->getMessage();
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
		try {
			/** @var Thrive_Dash_Api_Infusionsoft $api */
			$api = $this->getApi();

			list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

			$data = array(
				'FirstName' => $first_name,
				'LastName'  => $last_name,
				'Email'     => $arguments['email'],
				'Phone1'    => $arguments['phone']
			);

			$contact_id = $api->contact( 'addWithDupCheck', $data, 'Email' );

			if ( $contact_id ) {
				$api->APIEmail( 'optIn', $data['Email'], 'my reason' );

				$today         = date( 'Ymj\TG:i:s' );
				$creationNotes = "A web form was submitted with the following information:";
				if ( ! empty( $arguments['url'] ) ) {
					$creationNotes .= "\nReferring URL: " . $arguments['url'];
				}
				$creationNotes .= "\nIP Address: " . $_SERVER['REMOTE_ADDR'];
				$creationNotes .= "\ninf_field_Email: " . $arguments['email'];
				$creationNotes .= "\ninf_field_LastName: " . $last_name;
				$creationNotes .= "\ninf_field_FirstName: " . $first_name;
				$addNote = array(
					'ContactId'         => $contact_id,
					'CreationDate'      => $today,
					'CompletionDate'    => $today,
					'ActionDate'        => $today,
					'EndDate'           => $today,
					'ActionType'        => 'Other',
					'ActionDescription' => 'Thrive Leads Note',
					'CreationNotes'     => $creationNotes
				);

				$api->data( 'add', 'ContactAction', $addNote );
			}

			$api->contact( 'addToGroup', $contact_id, $list_identifier );

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
		return '~Contact.Email~';
	}

}
