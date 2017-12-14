<?php

/**
 * API wrapper for WebinarJamStudio
 */
class Thrive_Dash_Api_WebinarJamStudio {
	const API_URL = 'https://app.webinarjam.com/api/v2/';

	protected $apiKey;

	/**
	 * @param string $apiKey always required
	 *
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
	 */
	public function __construct( $apiKey ) {
		if ( empty( $apiKey ) ) {
			throw new Thrive_Dash_Api_WebinarJamStudio_Exception( 'API Key is required' );
		}
		$this->apiKey = $apiKey;
	}

	/**
	 * get the list of webinars scheduled for the future
	 *
	 * @return mixed
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
	 */
	public function getUpcomingWebinars() {
		$params = array(
			'api_key' => $this->apiKey
		);
		$data   = $this->_call( 'webinars', $params, 'POST' );

		return is_array( $data ) && isset( $data['webinars'] ) ? $data['webinars'] : array();
	}

	/**
	 * register a new user to a webinar
	 *
	 * @param $webinarKey
	 * @param $name
	 * @param $email
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
	 */
	public function registerToWebinar( $webinarKey, $name, $email, $phone ) {
		$params = array(
			'api_key'    => $this->apiKey,
			'webinar_id' => $webinarKey,
			'name'       => $name ? $name : ' ',
			'email'      => $email,
			'schedule'   => 0,
			'phone'      => $phone ? $phone : ' '
		);

		$this->_call( 'register', $params, 'POST' );

		return true;
	}

	/**
	 * retrieve info for a specific webinar
	 *
	 * @param string $webinar_id
	 *
	 * @return array
	 *
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
	 */
	public function getWebinar( $webinar_id ) {
		$params = array(
			'api_key'    => $this->apiKey,
			'webinar_id' => $webinar_id
		);

		return $this->_call( 'webinar', $params, 'POST' );
	}

	/**
	 * perform a webservice call
	 *
	 * @param string $path api path
	 * @param array $params request parameters
	 * @param string $method GET or POST
	 *
	 * @throws Thrive_Dash_Api_WebinarJamStudio_Exception
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
			throw new Thrive_Dash_Api_WebinarJamStudio_Exception( 'Failed connecting to WebinarJamStudio: ' . $result->get_error_message() );
		}

		$body = trim( wp_remote_retrieve_body( $result ) );

		$data = json_decode( $body, true );

		if ( ! is_array( $data ) ) {
			throw new Thrive_Dash_Api_WebinarJamStudio_Exception( 'API call error. Response was: ' . $body );
		}

		if ( $data['status'] != 'success' ) {
			$message = isset( $data['message'] ) ? $data['message'] : '';
			if ( empty( $message ) ) {
				$message = 'Raw response was: ' . $body;
			}
			throw new Thrive_Dash_Api_WebinarJamStudio_Exception( 'API call error: ' . $message );
		}

		return $data;
	}
}