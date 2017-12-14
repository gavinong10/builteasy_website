<?php

class Thrive_Dash_Api_Drip {
	private $api_token = '';
	private $api_end_point = 'https://api.getdrip.com/v2/';

	const GET = 1;
	const POST = 2;
	const DELETE = 3;
	const PUT = 4;

	/**
	 * Accepts the token and saves it internally.
	 *
	 * @param string $api_token
	 *
	 * @throws Exception
	 */
	public function __construct( $api_token ) {
		$api_token = trim( $api_token );

		if ( empty( $api_token ) || ! preg_match( '#^[\w-]+$#si', $api_token ) ) {
			throw new Exception( "Missing or invalid Drip API token." );
		}

		$this->api_token = $api_token;
	}

	/**
	 * Requests the campaigns for the given account.
	 *
	 * @param $params
	 *
	 * @return array|bool
	 * @throws Exception
	 * @throws Thrive_Dash_Api_Drip_Exception
	 * @throws Thrive_Dash_Api_Drip_Exception_Unsubscribed
	 */
	public function get_campaigns( $params ) {
		if ( empty( $params['account_id'] ) ) {
			throw new Exception( "Account ID not specified" );
		}

		$account_id = $params['account_id'];
		unset( $params['account_id'] ); // clear it from the params

		if ( isset( $params['status'] ) ) {
			if ( ! in_array( $params['status'], array( 'active', 'draft', 'paused', 'all' ) ) ) {
				throw new Exception( "Invalid campaign status." );
			}
		} elseif ( 0 ) {
			$params['status'] = 'active'; // api defaults to all but we want active ones
		}

		$url = $this->api_end_point . "$account_id/campaigns";
		$res = $this->make_request( $url, $params );

		return empty( $res ) ? false : $res;
	}

	/**
	 * Requests the accounts for the given account.
	 *
	 * @param void
	 *
	 * @return bool/array
	 */
	public function get_accounts() {
		$url = $this->api_end_point . 'accounts';
		$res = $this->make_request( $url );

		return empty( $res ) ? false : $res;
	}

	/**
	 * Sends a request to add a subscriber and returns its record or false
	 *
	 * @param $params
	 *
	 * @return array|bool
	 * @throws Exception
	 * @throws Thrive_Dash_Api_Drip_Exception
	 * @throws Thrive_Dash_Api_Drip_Exception_Unsubscribed
	 */
	public function create_or_update_subscriber( $params ) {
		if ( empty( $params['account_id'] ) ) {
			throw new Exception( "Account ID not specified" );
		}

		$account_id = $params['account_id'];
		unset( $params['account_id'] ); // clear it from the params

		$api_action = "/$account_id/subscribers";
		$url        = $this->api_end_point . $api_action;

		// The API wants the params to be JSON encoded
		$req_params = array( 'subscribers' => array( $params ) );

		$res = $this->make_request( $url, $req_params, self::POST );

		return empty( $res ) ? false : $res;
	}


	public function delete_subscriber( $params ) {
		if ( empty( $params['account_id'] ) ) {
			throw new Exception( "Account ID not specified" );
		}

		$account_id = $params['account_id'];
		unset( $params['account_id'] ); // clear it from the params

		$api_action = "/$account_id/subscribers/" . $params['email'];
		$url        = $this->api_end_point . $api_action;

		// The API wants the params to be JSON encoded
		$req_params = array( 'subscribers' => array( $params ) );

		return $this->make_request( $url, $req_params, self::DELETE );
	}

	/**
	 * Subscribes a user to a given campaign for a given account.
	 *
	 * @param array $params
	 *
	 * @throws Exception
	 * @return mixed
	 */
	public function subscribe_subscriber( $params ) {
		if ( empty( $params['account_id'] ) ) {
			throw new Exception( "Account ID not specified" );
		}

		$account_id = $params['account_id'];
		unset( $params['account_id'] ); // clear it from the params

		if ( empty( $params['campaign_id'] ) ) {
			throw new Exception( "Campaign ID not specified" );
		}

		$campaign_id = $params['campaign_id'];
		unset( $params['campaign_id'] ); // clear it from the params

		if ( empty( $params['email'] ) ) {
			throw new Exception( "Email not specified" );
		}

		if ( ! isset( $params['double_optin'] ) ) {
			$params['double_optin'] = true;
		}

		if ( ! isset( $params['reactivate_if_removed'] ) ) {
			$params['reactivate_if_removed'] = true;
		}

		$api_action = "$account_id/campaigns/$campaign_id/subscribers";
		$url        = $this->api_end_point . $api_action;

		// The API wants the params to be JSON encoded
		$req_params = array( 'subscribers' => array( $params ) );

		$res = $this->make_request( $url, $req_params, self::POST );

		return empty( $res ) ? false : $res;
	}

	/**
	 * Posts an event specified by the user.
	 *
	 * @param array $params
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function record_event( $params ) {
		if ( empty( $params['account_id'] ) ) {
			throw new Exception( "Account ID not specified" );
		}

		if ( empty( $params['action'] ) ) {
			throw new Exception( "Action was not specified" );
		}

		$account_id = $params['account_id'];
		unset( $params['account_id'] ); // clear it from the params

		$api_action = "$account_id/events";
		$url        = $this->api_end_point . $api_action;

		// The API wants the params to be JSON encoded
		$req_params = array( 'events' => array( $params ) );
		$res        = $this->make_request( $url, $req_params, self::POST );

		return empty( $res ) ? false : $res;
	}

	/**
	 * @param $url
	 * @param array $params
	 * @param int $req_method
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_Drip_Exception
	 * @throws Thrive_Dash_Api_Drip_Exception_Unsubscribed
	 */
	public function make_request( $url, $params = array(), $req_method = self::GET ) {


		switch ( $req_method ) {
			case self::DELETE:
				$fn             = 'tve_dash_api_remote_post';
				$args['method'] = 'delete';
				break;
			case self::GET:
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$params = json_encode($params);
				$fn = 'tve_dash_api_remote_post';
		}

		$args = array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				"Authorization" => "Basic " . base64_encode( $this->api_token . ":" ),
			),
			'body'    => $params,
		);

		$result = $fn( $url, $args );

		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_Drip_Exception( $result->get_error_message() );
		}

		$httpCode = $result['response']['code'];
		$body     = json_decode( $result['body'], true );

		if ( $httpCode == '422' ) {
			throw new Thrive_Dash_Api_Drip_Exception_Unsubscribed( 'API call failed. Server returned status code ' . $httpCode . " with message: <b>" . $body['errors'][0]['message'] . "</b>" );
		}

		if ( ! ( $httpCode == '200' || $httpCode == '201' || $httpCode == '204' ) ) {
			throw new Thrive_Dash_Api_Drip_Exception( 'API call failed. Server returned status code ' . $httpCode . " with message: <b>" . $body['errors'][0]['message'] . "</b>" );
		}

		return $body;
	}
}
