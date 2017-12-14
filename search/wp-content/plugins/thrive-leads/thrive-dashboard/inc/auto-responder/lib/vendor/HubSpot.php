<?php

/**
 * API wrapper for HubSpot
 */
class Thrive_Dash_Api_HubSpot {
	const API_URL = 'https://api.hubapi.com/';

	protected $apiKey;

	/**
	 * @param string $apiKey always required
	 *
	 * @throws Thrive_Dash_Api_HubSpot_Exception
	 */
	public function __construct( $apiKey ) {
		if ( empty( $apiKey ) ) {
			throw new Thrive_Dash_Api_HubSpot_Exception( 'API Key is required' );
		}
		$this->apiKey = $apiKey;
	}

	/**
	 * get the static contact lists
	 * HubSpot is letting us to work only with static contact lists
	 * "Please note that you cannot manually add (via this API call) contacts to dynamic lists - they can only be updated by the contacts app."
	 *
	 * @return mixed
	 * @throws Thrive_Dash_Api_HubSpot_Exception
	 */
	public function getContactLists() {
		$params = array(
			'hapikey' => $this->apiKey
		);

		$data = $this->_call( '/contacts/v1/lists/static', $params, 'GET' );

		return is_array( $data ) && isset( $data['lists'] ) ? $data['lists'] : array();
	}

	/**
	 * register a new user to a static contact list
	 *
	 * @param $webinarKey
	 * @param $name
	 * @param $email
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_HubSpot_Exception
	 */
	public function registerToContactList( $contactListId, $name, $email ) {
		$params    = array(
			'properties' => array(
				array(
					'property' => 'email',
					'value'    => $email
				),
				array(
					'property' => 'firstname',
					'value'    => $name ? $name : ''
				)
			)
		);
		$data      = $this->_call( '/contacts/v1/contact/createOrUpdate/email/' . $email . '/?hapikey=' . $this->apiKey, $params, 'POST' );
		$contactId = $data['vid'];

		$request_body = array( 'vids' => array( $contactId ) );
		$this->_call( 'contacts/v1/lists/' . $contactListId . '/add?hapikey=' . $this->apiKey, $request_body, 'POST' );

		return true;
	}

	/**
	 * perform a webservice call
	 *
	 * @param string $path api path
	 * @param array $params request parameters
	 * @param string $method GET or POST
	 *
	 * @throws Thrive_Dash_Api_HubSpot_Exception
	 */
	protected function _call( $path, $params = array(), $method = 'GET' ) {
		$url = self::API_URL . ltrim( $path, '/' );

		$args = array(
			'headers' => array(
				'Content-type' => 'application/json',
				'Accept'       => 'application/json',
			),
			'body'    => json_encode( $params )
		);

		switch ( $method ) {
			case 'POST':
				$args['body'] = json_encode( $params );
				$result       = tve_dash_api_remote_post( $url, $args );
				break;
			case 'GET':
			default:
				$query_string = '';
				foreach ( $params as $k => $v ) {
					$query_string .= $query_string ? '&' : '';
					$query_string .= $k . '=' . $v;
				}
				if ( $query_string ) {
					$url .= ( strpos( $url, '?' ) !== false ? '&' : '?' ) . $query_string;
				}

				$result = tve_dash_api_remote_get( $url, $args );
				break;
		}

		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_HubSpot_Exception( 'Failed connecting to HubSpot: ' . $result->get_error_message() );
		}

		$body      = trim( wp_remote_retrieve_body( $result ) );
		$statusMsg = trim( wp_remote_retrieve_response_message( $result ) );
		$data      = json_decode( $body, true );

		if ( ! is_array( $data ) ) {
			throw new Thrive_Dash_Api_HubSpot_Exception( 'API call error. Response was: ' . $body );
		}

		if ( $statusMsg != 'OK' ) {
			if ( empty( $statusMsg ) ) {
				$statusMsg = 'Raw response was: ' . $body;
			}
			throw new Thrive_Dash_Api_HubSpot_Exception( 'API call error: ' . $statusMsg );
		}

		return $data;
	}
}