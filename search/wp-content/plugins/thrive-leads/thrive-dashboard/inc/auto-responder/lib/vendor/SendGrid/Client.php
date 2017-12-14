<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Quickly and easily access any REST or REST-like API.
 */
class Thrive_Dash_Api_SendGrid_Client {

	private
		$host,
		$request_headers,
		$version,
		$url_path,
		$methods;

	/**
	 * Initialize the client
	 *
	 * @param string $host the base url (e.g. https://api.sendgrid.com)
	 * @param array $request_headers global request headers
	 * @param string $version api version (configurable)
	 * @param array $url_path holds the segments of the url path
	 */
	function __construct( $host, $request_headers = null, $version = null, $url_path = null ) {
		$this->host            = $host;
		$this->request_headers = ( $request_headers ? $request_headers : array() );
		$this->version         = $version;
		$this->url_path        = ( $url_path ? $url_path : array() );
		// These are the supported HTTP verbs
		$this->methods = array( 'delete', 'get', 'patch', 'post', 'put' );
	}

	/**
	 * Make a new Client object
	 *
	 * @param string $name name of the url segment
	 *
	 * @return Thrive_Dash_Api_SendGrid_Client object
	 */
	private function _buildClient( $name = null ) {
		if ( isset( $name ) ) {
			array_push( $this->url_path, $name );
		}
		$url_path       = $this->url_path;
		$this->url_path = array();

		return new Thrive_Dash_Api_SendGrid_Client( $this->host, $this->request_headers, $this->version, $url_path );
	}

	/**
	 * Subclass this function for your own needs.
	 *  Or just pass the version as part of the URL
	 *  (e.g. client._('/v3'))
	 *
	 * @param string $url URI portion of the full URL being requested
	 *
	 * @return string
	 */
	private function _buildVersionedUrl( $url ) {
		return sprintf( "%s%s%s", $this->host, $this->version, $url );
	}

	/**
	 * Build the final URL to be passed
	 *
	 * @param array $query_params an array of all the query parameters
	 *
	 * @return string
	 */
	private function _buildUrl( $query_params = null ) {
		$url = '/' . implode( '/', $this->url_path );
		if ( isset( $query_params ) ) {
			$url_values = http_build_query( $query_params );
			$url        = sprintf( '%s?%s', $url, $url_values );
		}
		if ( isset( $this->version ) ) {
			$url = $this->_buildVersionedUrl( $url );
		} else {
			$url = sprintf( '%s%s', $this->host, $url );;
		}

		return $url;
	}

	/**
	 * Make the API call and return the response. This is separated into
	 * it's own function, so we can mock it easily for testing.
	 *
	 * @param array $method the HTTP verb
	 * @param string $url the final url to call
	 * @param array $request_body request body
	 * @param array $request_headers any additional request headers
	 *
	 * @return Thrive_Dash_Api_SendGrid_Response object
	 */
	public function makeRequest( $method, $url, $request_body = null, $request_headers = null ) {
		if ( isset( $request_headers ) ) {
			$this->request_headers = array_merge( $this->request_headers, $request_headers );
		}
		if ( isset( $request_body ) ) {
			$request_body          = json_encode( $request_body );
			$content_length        = array( 'Content-Length: ' . strlen( $request_body ) );
			$content_type          = array( 'Content-Type' => 'application/json' );
			$this->request_headers = array_merge( $this->request_headers, $content_type );
		}

		$args = array(
			'headers'   => $this->request_headers,
			'timeout'   => 15,
			'body'      => $request_body,
			'sslverify' => false,
		);

		switch ( strtoupper( $method ) ) {
			case 'POST':
				$result = tve_dash_api_remote_post( $url, $args );
				break;
			case 'GET':
			default:
				if(empty($request_body)) {
					$request_body = array();
				}
				$url .= ( strpos( $url, '?' ) !== false ? '&' : '?' ) . http_build_query( $request_body );
				$result = tve_dash_api_remote_get( $url, $args );
				break;
		}

		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_SendGrid_Exception(  'Failed connecting: ' . $result->get_error_message()  );
		}

		$status_code     = $result['response']['code'];
		$response_body   = json_decode( $result['body'] );
		$response_header = $result['headers'];

		if ( ! ( $status_code == '200' || $status_code == '201'  ) ) {
			throw new Thrive_Dash_Api_SendGrid_Exception(  ucwords( $response_body->errors['0']->message ) );
		}

		return new Thrive_Dash_Api_SendGrid_Response( $status_code, $response_body, $response_header );
	}

	/**
	 * Add variable values to the url.
	 * (e.g. /your/api/{variable_value}/call)
	 * Another example: if you have a PHP reserved word, such as and,
	 * in your url, you must use this method.
	 *
	 * @param string $name name of the url segment
	 *
	 * @return Thrive_Dash_Api_SendGrid_Client object
	 */
	public function _( $name = null ) {
		return $this->_buildClient( $name );
	}

	/**
	 * Dynamically add method calls to the url, then call a method.
	 * (e.g. client.name.name.method())
	 *
	 * @param string $name name of the dynamic method call or HTTP verb
	 * @param array $args parameters passed with the method call
	 *
	 * @return Thrive_Dash_Api_SendGrid_Client or Response object
	 */
	public function __call( $name, $args ) {
		if ( $name == 'version' ) {
			$this->version = $args[0];

			return $this->_();
		}

		if ( in_array( $name, $this->methods ) ) {
			$query_params    = ( ( count( $args ) >= 2 ) ? $args[1] : null );
			$url             = $this->_buildUrl( $query_params );
			$request_body    = ( $args ? $args[0] : null );
			$request_headers = ( ( count( $args ) == 3 ) ? $args[2] : null );

			return $this->makeRequest( $name, $url, $request_body, $request_headers );
		}

		return $this->_( $name );
	}
}
