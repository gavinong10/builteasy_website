<?php
/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 5/11/2015
 * Time: 5:59 PM
 */

// Include WordPress libraries to handle XML-RPC
require_once ABSPATH . '/wp-includes/class-IXR.php';
require_once ABSPATH . '/wp-includes/class-wp-http-ixr-client.php';

class Thrive_Dash_Api_Infusionsoft {
	public $api_key;
	public $error = false;
	public $subdomain;

	public function __construct( $subdomain = null, $api_key = null ) {
		$this->subdomain = $subdomain;
		$this->api_key   = $api_key;
		if ( empty( $this->subdomain ) || empty( $this->api_key ) ) {
			throw new Thrive_Dash_Api_Infusionsoft_InfusionsoftException( "You must provide a ClientID and API key for your Infusionsoft application." );
		}
	}

	public function __call( $name, $arguments ) {
		// Make sure no error already exists
		if ( $this->error ) {
			return new WP_Error( 'invalid-request', __( 'You must provide a subdomain and API key for your Infusionsoft application.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		// Get the full method name with the service and method
		$method    = ucfirst( $name ) . 'Service' . '.' . array_shift( $arguments );
		$arguments = array_merge( array( $method, $this->api_key ), $arguments );

		// Initialize the client
		$client = new WP_HTTP_IXR_Client( 'https://' . $this->subdomain . '.infusionsoft.com/api/xmlrpc' );

		// Call the function and return any error that happens
		if ( ! call_user_func_array( array( $client, 'query' ), $arguments ) ) {
			throw new Thrive_Dash_Api_Infusionsoft_InfusionsoftException( $client->getErrorMessage() );
		}

		// Pass the response directly to the user
		return $client->getResponse();
	}
}
