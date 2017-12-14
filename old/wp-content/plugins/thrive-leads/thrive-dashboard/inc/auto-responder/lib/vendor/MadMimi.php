<?php

/**
 * API wrapper for MadMimi
 */
class Thrive_Dash_Api_MadMimi {
	const API_URL = 'https://api.madmimi.com/';

	protected $apiKey;
	private $username;

	/**
	 * @param string $apiKey always required
	 *
	 * @throws Thrive_Dash_Api_MadMimi_Exception
	 */
	public function __construct( $apiKey, $username ) {
		if ( empty( $apiKey ) ) {
			throw new Thrive_Dash_Api_MadMimi_Exception( 'API Key is required' );
		}
		$this->apiKey   = $apiKey;
		$this->username = $username;
	}


	/**
	 * get audience lists
	 *
	 * @param string $email
	 * @param string $password
	 *
	 * @throws Thrive_Dash_Api_MadMimi_Exception
	 *
	 * @return array
	 */
	public function getAudienceLists() {
		if ( empty( $this->username ) || empty( $this->apiKey ) ) {
			throw new Thrive_Dash_Api_MadMimi_Exception( 'Illegal Arguments' );
		}

		// credentials
		$data = array(
			'username' => $this->username,
			'api_key'  => $this->apiKey,
		);
		$data = $this->_call( 'audience_lists', $data, 'GET' );

		return is_array( $data ) && isset( $data ) ? $data : array();
	}

	/**
	 * check if a user is already subscribed to the selected audience list
	 *
	 * @param $audienceListName
	 * @param $email
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_MadMimi_Exception
	 */
	public function alreadySubscribed( $audienceListName, $email ) {
		$params  = array(
			'api_key'  => $this->apiKey,
			'username' => $this->username
		);
		$results = $this->_call( 'audience_members/' . $email . '/lists.json', $params, 'GET' );
		foreach ( $results as $result ) {
			if ( $result['name'] == $audienceListName ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * register a new user to the selected audience list
	 *
	 * @param $audienceListName
	 * @param $name
	 * @param $email
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
	 */
	public function registerToAudienceList( $audienceListName, $name, $email ) {
		$params = array(
			'api_key'    => $this->apiKey,
			'username'   => $this->username,
			'first_name' => $name ? $name : ' ',
			'email'      => $email,
		);
		$this->_call( 'audience_lists/' . $audienceListName . "/add", $params, 'POST' );

		return true;
	}


	/**
	 * perform a webservice call
	 *
	 * @param string $path api path
	 * @param array $params request parameters
	 * @param string $method GET or POST
	 *
	 * @throws Thrive_Dash_Api_MadMimi_Exception
	 */
	protected function _call( $path, $params = array(), $method = 'GET' ) {
		$url = self::API_URL . ltrim( $path, '/' );

		$args = array(
			'headers' => array(
				'Content-type' => 'application/json',
				'Accept'       => 'application/json',
			),
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
			throw new Thrive_Dash_Api_MadMimi_Exception( 'Failed connecting to MadMimi: ' . $result->get_error_message() );
		}

		$body      = trim( wp_remote_retrieve_body( $result ) );
		$statusMsg = trim( wp_remote_retrieve_response_message( $result ) );
		$data      = json_decode( $body, true );

		if ( $statusMsg != 'OK' ) {
			throw new Thrive_Dash_Api_MadMimi_Exception( 'API call error: ' . $statusMsg );
		}

		return $data;
	}
}