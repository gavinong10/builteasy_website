<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_Api_CampaignMonitor_BaseTransport {

	var $_log;

	function __construct( $log ) {
		$this->_log = $log;
	}

	function split_and_inflate( $response, $may_be_compressed ) {
		$ra = explode( "\r\n\r\n", $response );

		$result  = array_pop( $ra );
		$headers = array_pop( $ra );

		if ( $may_be_compressed && preg_match( '/^Content-Encoding:\s+gzip\s+$/im', $headers ) ) {
			$original_length = strlen( $response );
			$result          = gzinflate( substr( $result, 10, - 8 ) );

			$this->_log->log_message( 'Inflated gzipped response: ' . $original_length . ' bytes ->' .
			                          strlen( $result ) . ' bytes', get_class(), Thrive_Dash_Api_CampaignMonitor_Log_VERBOSE );
		}

		return array( $headers, $result );
	}

}