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
 * Holds the response from an API call.
 */
class Thrive_Dash_Api_SendGrid_Response {
	/**
	 * Setup the response data
	 *
	 * @param int $status_code the status code.
	 * @param array $response_body the response body as an array.
	 * @param array $response_headers an array of response headers.
	 */
	function __construct( $status_code = null, $response_body = null, $response_headers = null ) {
		$this->_status_code = $status_code;
		$this->_body        = $response_body;
		$this->_headers     = $response_headers;
	}

	/**
	 * The status code
	 *
	 * @return integer
	 */
	public function statusCode() {
		return $this->_status_code;
	}

	/**
	 * The response body
	 *
	 * @return array
	 */
	public function body() {
		return $this->_body;
	}

	/**
	 * The response headers
	 *
	 * @return array
	 */
	public function headers() {
		return $this->_headers;
	}
}