<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/16/2015
 * Time: 10:30 AM
 */
class Thrive_Dash_Api_ConstantContact {
	CONST URL = 'https://api.constantcontact.com/v2/';

	protected $api_key;
	protected $api_token;

	/**
	 * Thrive_Dash_Api_ConstantContact constructor.
	 */
	public function __construct( $api_key, $api_token ) {
		$this->api_key   = $api_key;
		$this->api_token = $api_token;
	}

	public function getLists() {
		$response = $this->make_request( 'lists', array(), 'get' );
		$lists    = json_decode( $response, true );

		return $lists;
	}

	public function addContact( $list_id, $user ) {
		$email = $user['email'];

		$user['lists'] = array(
			array(
				'id' => $list_id
			),
		);

		$user['email_addresses'] = array(
			array(
				'email_address' => $user['email']
			)
		);

		unset( $user['email'] );

		try {

			$response = $this->make_request( 'contacts', $user, 'post', array( 'action_by' => 'ACTION_BY_VISITOR' ) );

			return json_decode( $response );

		} catch ( Thrive_Dash_Api_ConstantContact_AlreadyExistException $e ) {

			$contact           = $this->getContact( $email );
			$contact['status'] = 'ACTIVE';

			if ( ! empty( $user['first_name'] ) ) {
				$contact['first_name'] = $user['first_name'];
			}

			if ( ! empty( $user['last_name'] ) ) {
				$contact['last_name'] = $user['last_name'];
			}

			$contact['lists'][] = array(
				'id' => $list_id
			);

			return $this->updateContact( $contact );
		}
	}

	public function getContact( $email ) {
		$response = $this->make_request( 'contacts', array(), 'get', array( 'email' => $email ) );

		$result = json_decode( $response, true );

		return $result['results'][0];
	}

	public function updateContact( $contact ) {
		$results = $this->make_request( 'contacts/' . $contact['id'], $contact, 'put', array( 'action_by' => 'ACTION_BY_VISITOR' ) );

		return json_decode( $results, true );
	}

	/**
	 * @param $path
	 * @param array $params
	 * @param string $type
	 *
	 * @return mixed
	 * @throws Thrive_Dash_Api_ConstantContact_Exception
	 * @throws Thrive_Dash_Api_ConstantContact_AlreadyExistException
	 */
	protected function make_request( $path, $params = array(), $type = 'post', $extra = array() ) {
		switch ( $type ) {
			case 'get':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$url = self::URL . $path . "?" . http_build_query( array_merge( array( 'api_key' => $this->api_key ), $extra ) );

		$args = array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				"Authorization" => "Bearer " . $this->api_token,
			),
			'body'    => json_encode( $params )
		);

		if ( $type === 'put' ) {
			$args['method'] = 'put';
		}

		$response = $fn( $url, $args );

		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_ConstantContact_Exception( $response->get_error_message() );
		}

		$httpCode = $response['response']['code'];

		if ( $httpCode == '409' ) {
			throw new Thrive_Dash_Api_ConstantContact_AlreadyExistException( 'Info already exists' );
		}

		if ( ! ( $httpCode == '200' || $httpCode == '201' || $httpCode == '204' ) ) {
			throw new Thrive_Dash_Api_ConstantContact_Exception( 'API call failed. Server returned status code ' . $httpCode );
		}

		return $response['body'];
	}

}
