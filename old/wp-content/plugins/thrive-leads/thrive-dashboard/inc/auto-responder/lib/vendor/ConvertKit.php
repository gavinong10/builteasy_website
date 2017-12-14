<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 15/9/2015
 * Time: 10:14 AM
 */

require_once dirname( __FILE__ ) . "/ConvertKit/Exception.php";

class Thrive_Dash_Api_ConvertKit {

	protected $api_version = 2;
	protected $api_url_base = 'https://api.convertkit.com/';
	protected $resources = array();
	protected $markup = array();

	protected $key;


	public function __construct( $key ) {

		if ( empty( $key ) ) {
			throw new Thrive_Dash_Api_ConvertKit_Exception( "Invalid API credentials" );
		}

		$this->key = $key;
	}

	/**
	 * get forms from the API
	 *
	 * @return array|mixed
	 * @throws Thrive_Dash_Api_ConvertKit_Exception
	 */
	public function getForms() {
		$forms = $this->_call( 'forms' );

		if ( isset( $forms['error_message'] ) ) {
			throw new Thrive_Dash_Api_ConvertKit_Exception( $forms['error_message'] );
		}

		return $forms;
	}

	/**
	 * @param string $form_id
	 * @param $fields array
	 *
	 * @throws Thrive_Dash_Api_ConvertKit_Exception
	 * @return true
	 */
	public function subscribeForm( $form_id, $fields ) {
		$request = sprintf( 'forms/%s/subscribe', $form_id );

		$args = array(
			'email' => $fields['email'],
			'fname' => $fields['name']
		);

		$data = $this->_call( $request, $args, 'POST' );

		if ( isset( $data['status'] ) && $data['status'] == "created" ) {
			return $data;
		} elseif ( isset( $data['status'] ) ) {
			throw new Thrive_Dash_Api_ConvertKit_Exception( $data['status'] );
		}
	}

	/**
	 * Does the calls to the API
	 *
	 * @param $path
	 * @param $args
	 * @param $method
	 *
	 * @return array|WP_Error
	 * @throws Thrive_Dash_Api_ConvertKit_Exception
	 */
	protected function _call( $path, $args = array(), $method = "GET" ) {
		$url = $this->build_request_url( $path, $args );
		//build parameters depending on the send method type
		if ( $method == 'GET' ) {
			$request = tve_dash_api_remote_get( $url, $args );
		} elseif ( $method == 'POST' ) {
			$request = tve_dash_api_remote_post( $url, $args );
		} else {
			$request = null;
		}

		if ( is_wp_error( $request ) ) {
			throw new Thrive_Dash_Api_ConvertKit_Exception( 'Could not parse the response !' );
		} else {
			$data = json_decode( wp_remote_retrieve_body( $request ), true );
		}

		return $data;
	}

	/**
	 * Merge default request arguments with those of this request
	 *
	 * @param  array $args Request arguments
	 *
	 * @return array        Request arguments
	 */
	public function filter_request_arguments( $args = array() ) {
		return array_merge( $args, array( 'k' => $this->key, 'v' => $this->api_version ) );
	}

	/**
	 * Build the full request URL
	 *
	 * @param  string $request Request path
	 * @param  array $args Request arguments
	 *
	 * @return string          Request URL
	 */
	public function build_request_url( $request, $args = array() ) {
		return $this->api_url_base . $request . '?' . http_build_query( $this->filter_request_arguments( $args ) );
	}

}
