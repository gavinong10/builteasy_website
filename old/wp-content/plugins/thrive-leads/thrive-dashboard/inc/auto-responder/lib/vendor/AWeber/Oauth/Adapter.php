<?php


interface Thrive_Dash_Api_AWeber_Oauth_Adapter {
	public function request( $method, $uri, $data = array() );

	public function getRequestToken( $callbackUrl = false );
} 