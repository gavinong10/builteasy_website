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
 * A general result object returned from all Campaign Monitor API calls.
 * @author tobyb
 *
 */
class Thrive_Dash_Api_CampaignMonitor_Result {
	/**
	 * The deserialised result of the API call
	 * @var mixed
	 */
	var $response;

	/**
	 * The http status code of the API call
	 * @var int
	 */
	var $http_status_code;

	function __construct( $response, $code ) {
		$this->response         = $response;
		$this->http_status_code = $code;
	}

	/**
	 * Can be used to check if a call to the api resulted in a successful response.
	 * @return boolean False if the call failed. Check the response property for the failure reason.
	 * @access public
	 */
	function was_successful() {
		return $this->http_status_code >= 200 && $this->http_status_code < 300;
	}
}