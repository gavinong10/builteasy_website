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
 * Created by PhpStorm.
 * User: Danut
 * Date: 7/22/2015
 * Time: 1:31 PM
 */
class Thrive_Dash_Api_SendreachV2 {
	protected $api_url = 'http://api.sendreach.com/index.php';
	protected $key;
	protected $secret;

	/**
	 * Thrive_Dash_Api_Sendreach constructor.
	 */
	public function __construct( $key, $secret ) {
		$this->key    = $key;
		$this->secret = $secret;
	}

	public function getLists() {
		return $this->_send( array( 'action' => 'lists_view' ) );
	}

	public function addSubscriber( $list_id, $first_name, $last_name, $email ) {
		$params = array(
			'action'     => 'subscriber_add',
			'list_id'    => $list_id,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'email'      => $email,
			'client_ip'  => $_SERVER['REMOTE_ADDR'],
		);

		$this->_send( $params );
	}

	protected function _send( $params = array() ) {
		$getParams = array(
			'key'    => $this->key,
			'secret' => $this->secret,
		);

		$getParams = array_merge( $getParams, $params );

		$args     = array(
			'headers' => array(
				'Content-Type' => 'application/json'
			)
		);
		$response = tve_dash_api_remote_get( $this->api_url . '?' . http_build_query( $getParams, '', '&' ), $args );

		if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
			throw new Thrive_Dash_Api_Sendreach_Exception( $response['response']['message'] );
		}

		$body = json_decode( $response['body'] );

		if ( is_object( $body ) && $body->code != 200 ) {
			throw new Thrive_Dash_Api_Sendreach_Exception( $body->message );
		}

		return $body;
	}
}
