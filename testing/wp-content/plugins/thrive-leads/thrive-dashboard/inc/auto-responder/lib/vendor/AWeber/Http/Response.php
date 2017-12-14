<?php

class Thrive_Dash_Api_AWeber_Http_Response {
	public $body = '';
	public $headers = array();

	public function __construct( $response ) {
		$this->headers                = $response['headers'];
		$this->headers['Status-Code'] = $response['response']['code'];
		$this->headers['Status']      = $response['response']['code'] . ' ' . $response['response']['message'];

		$this->body = $response['body'];
	}

	public function __toString() {
		return $this->body;
	}

	public function headers() {
		return $this->headers;
	}
}