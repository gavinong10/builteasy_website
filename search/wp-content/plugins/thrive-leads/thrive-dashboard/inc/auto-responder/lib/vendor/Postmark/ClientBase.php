<?php

/*
 * Author:   Wildbit (http://wildbit.com)
 * License:  http://creativecommons.org/licenses/MIT/ MIT
 * Link:     https://github.com/wildbit/postmark-php/
 */

/**
 *
 * This is the core class that interacts with the Postmark API. All clients should
 * inherit fromt this class.
 */
abstract class Thrive_Dash_Api_Postmark_ClientBase {

	/**
	 * BASE_URL is "https://api.postmarkapp.com"
	 *
	 * You may modify this value to disable SSL support, but it is not recommended.
	 *
	 * @var string
	 */
	public static $BASE_URL = "https://api.postmarkapp.com";

	/**
	 * CERTIFICATE_PATH is NULL by default.
	 * This can be set to your own certificate chain if your PHP instance is not able to verify the SSL.
	 *
	 * Setting this value causes SSL/TLS requests to use this certificate chain for verifying Postmark requests.
	 *
	 * See: https://guzzle.readthedocs.org/en/5.3/clients.html#verify
	 *
	 * @var string
	 */
	public static $CERTIFICATE_PATH = null;

	protected $authorization_token = null;
	protected $authorization_header = null;
	protected $version = null;
	protected $os = null;
	protected $timeout = 30;

	protected function __construct( $token, $header, $timeout = 30 ) {
		$this->authorization_header = $header;
		$this->authorization_token  = $token;
		$this->version              = phpversion();
		$this->os                   = PHP_OS;
		$this->timeout              = $timeout;
	}

	/**
	 * The base request method for all API access.
	 *
	 * @param string $method The request VERB to use (GET, POST, PUT, DELETE)
	 * @param string $path The API path.
	 * @param array $body The content to be used (either as the query, or the json post/put body)
	 *
	 * @return object
	 */
	protected function processRestRequest( $method = null, $path = null, $body = null ) {

		$fn = '';
		$v  = $this->version;
		$o  = $this->os;

		switch ( $method ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$url = Thrive_Dash_Api_Postmark_ClientBase::$BASE_URL . $path;


		$response = $fn( $url, array(
			'body'      => json_encode( $body ),
			'timeout'   => 15,
			'headers'   => array(
				'User-Agent'                => "Postmark-PHP (PHP Version:$v, OS:$o)",
				'Content-Type'              => 'application/json',
				'Accept'                    => 'application/json',
				$this->authorization_header => $this->authorization_token,
			),
			'sslverify' => false,
		) );

		$result = null;

		switch ( $response['response']['code'] ) {
			case 200:
				$response_obj = json_decode( $response['body'] );
				$result       = (array) $response_obj;
				break;
			case 401:

				$ex                 = new Thrive_Dash_Api_Postmark_Exception();
				$ex->message        = 'Unauthorized: Missing or incorrect API token in header. ' .
				                      'Please verify that you used the correct token when you constructed your client.';
				$ex->httpStatusCode = 401;
				throw $ex;
				break;
			case 422:
				$ex = new Thrive_Dash_Api_Postmark_Exception();

				$response_obj = json_decode( $response['body'] );
				$body         = (array) $response_obj;

				$ex->httpStatusCode       = 422;
				$ex->postmarkApiErrorCode = $body['ErrorCode'];
				$ex->message              = $body['Message'];

				throw $ex;
				break;
			case 500:
				$ex                 = new Thrive_Dash_Api_Postmark_Exception();
				$ex->httpStatusCode = 500;
				$ex->message        = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
				                      'In most cases the message is lost during the process, ' .
				                      'and Postmark is notified so that we can investigate the issue.';
				throw $ex;
				break;
		}

		return $result;

	}
}

?>
