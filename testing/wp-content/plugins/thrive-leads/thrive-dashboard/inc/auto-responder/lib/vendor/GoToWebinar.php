<?php

/**
 * API wrapper for Citrix (GoToWebinar)
 */
class Thrive_Dash_Api_GoToWebinar {
	const API_URL = 'https://api.citrixonline.com/';

	protected $apiKey;

	protected $accessToken;

	protected $organizerKey;

	protected $accountKey;

	protected $expiresAt;

	/**
	 * @param string $apiKey always required
	 *
	 * @param string|null $accessToken if the service has been previously connected, this must be passed in
	 * @param string|null $organizerKey if the service has been previously connected, this must be passed in
	 *
	 * @throws Thrive_Dash_Api_GoToWebinar_Exception
	 */
	public function __construct( $apiKey, $accessToken = null, $organizerKey = null ) {
		if ( empty( $apiKey ) ) {
			throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API Key is required' );
		}
		$this->apiKey       = $apiKey;
		$this->accessToken  = $accessToken;
		$this->organizerKey = $organizerKey;
	}

	/**
	 * get the required credentials that will need to be stored
	 */
	public function getCredentials() {
		if ( empty( $this->accessToken ) ) {
			return array();
		}

		return array(
			'access_token'  => $this->accessToken,
			'organizer_key' => $this->organizerKey,
			'expires_at'    => $this->expiresAt
		);
	}

	/**
	 * @param string $email
	 * @param string $password
	 *
	 * @throws Thrive_Dash_Api_GoToWebinar_Exception
	 */
	public function directLogin( $email, $password ) {
		$params = array(
			'grant_type' => 'password',
			'user_id'    => $email,
			'password'   => $password,
			'client_id'  => $this->apiKey
		);

		$data = $this->_call( 'oauth/access_token', $params, 'POST', false, 'url-encoded' );

		$this->accessToken  = $data['access_token'];
		$this->organizerKey = $data['organizer_key'];
		$this->expiresAt    = time() + $data['expires_in'];
	}

	/**
	 * get the list of webinars scheduled for the future for the current organizer (specified by organizer_key
	 */
	public function getUpcomingWebinars() {
		return $this->_call( 'G2W/rest/organizers/' . $this->organizerKey . '/upcomingWebinars' );
	}

	/**
	 * register a new user to a webinar
	 *
	 * @param string $webinarKey
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $email
	 */
	public function registerToWebinar( $webinarKey, $firstName, $lastName, $email ) {
		$params = array(
			'firstName' => $firstName,
			'lastName'  => $lastName,
			'email'     => $email
		);
		$uri    = 'G2W/rest/organizers/' . $this->organizerKey . '/webinars/' . $webinarKey . '/registrants?oauth_token=' . $this->accessToken;

		$this->_call( $uri, $params, 'POST', false );

		return true;
	}

	/**
	 * perform a webservice call
	 *
	 * @param string $path api path
	 * @param array $params request parameters
	 * @param string $method GET or POST
	 * @param bool $auth whether or not to use access token when sending the request
	 * @param string $content_type for directLogin, it seems we have to use the x-www-form-urlencoded request. For others, application/json
	 */
	protected function _call( $path, $params = array(), $method = 'GET', $auth = true, $content_type = 'application/json' ) {
		if ( $auth ) {
			$params['oauth_token'] = $this->accessToken;
		}

		$url = self::API_URL . ltrim( $path, '/' );

		$args = array(
			'headers' => array(
				'Accept' => 'application/json',
			),
		);

		if ( $content_type == 'application/json' ) {
			$args['headers']['Content-type'] = $content_type;
		}

		switch ( $method ) {
			case 'POST':
				$args['body'] = $content_type == 'application/json' ? json_encode( $params ) : $params; // default to www-url-encoded
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
			throw new Thrive_Dash_Api_GoToWebinar_Exception( 'Failed connecting to GoToWebinar: ' . $result->get_error_message() );
		}

		$body = trim( wp_remote_retrieve_body( $result ) );

		$data = @json_decode( $body, true, 512, JSON_BIGINT_AS_STRING );
		if ( empty( $data ) ) {
			/**
			 * try also without the JSON_BIGINT_AS_STRING
			 */
			$data = json_decode( $body, true );
		}

		if ( ! empty( $data['int_err_code'] ) ) {
			throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API call error: ' . $data['int_err_code'] );
		}

		if ( ! empty( $data['errorCode'] ) ) {
			throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API call error: ' . $data['errorCode'] . ( ! empty( $data['description'] ) ? "Error description: " . $data["description"] : '' ) );
		}

		if ( ! empty( $data['err'] ) ) {
			if ( ! empty( $data['message'] ) ) {
				throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API call error: ' . $data["message"] );
			} else {
				throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API call error: ' . var_export( $data, true ) );
			}

		}

		/**
		 * SUP-1111 GoToWebinar cannot connect
		 */
		if ( ! empty( $data['error'] ) ) {
			throw new Thrive_Dash_Api_GoToWebinar_Exception( 'API call returned error: ' . $data['error'] );
		}

		return $data;
	}

}