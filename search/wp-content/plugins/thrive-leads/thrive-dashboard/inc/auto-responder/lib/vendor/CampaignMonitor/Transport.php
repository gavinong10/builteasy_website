<?php

defined( 'CS_REST_GET' ) or define( 'CS_REST_GET', 'GET' );
defined( 'CS_REST_POST' ) or define( 'CS_REST_POST', 'POST' );
defined( 'CS_REST_PUT' ) or define( 'CS_REST_PUT', 'PUT' );
defined( 'CS_REST_DELETE' ) or define( 'CS_REST_DELETE', 'DELETE' );
if ( false === defined( 'CS_REST_SOCKET_TIMEOUT' ) ) {
	define( 'CS_REST_SOCKET_TIMEOUT', 10 );
}
if ( false === defined( 'CS_REST_CALL_TIMEOUT' ) ) {
	define( 'CS_REST_CALL_TIMEOUT', 10 );
}

/**
 * Provide HTTP request functionality via cURL extensions
 *
 * @author tobyb
 * @since 1.0
 */
class Thrive_Dash_Api_CampaignMonitor_Transport extends Thrive_Dash_Api_CampaignMonitor_BaseTransport {

	var $_curl_zlib;
	var $_type;

	function __construct( $log ) {
		parent::__construct( $log );

//        $curl_version = curl_version();
//        $this->_curl_zlib = isset($curl_version['libz_version']);
	}

	/**
	 * @return string The type of transport used
	 */
	function set_type( $type ) {
		$this->_type = $type;

		return $this;
	}

	/**
	 * @return string The type of transport used
	 */
	function get_type() {
		return $this->_type;
	}

	function make_call( $call_options ) {

		$method     = $call_options['method'];
		$called_url = $call_options['route'];
		$body       = isset( $call_options['data'] ) ? $call_options['data'] : '';
		switch ( $method ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$result = $fn( $called_url, array(
			'body'      => $body,
			'headers'   => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( $call_options['authdetails']['api_key'] . ':nopass' )
			),
			'sslverify' => false,
		) );

		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_ConstantContact_Exception( 'Failed connecting: ' . $result->get_error_message() );
		}

		return $result;
	}
}