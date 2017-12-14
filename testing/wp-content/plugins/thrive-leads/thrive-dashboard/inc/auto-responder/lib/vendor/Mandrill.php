<?php


class Thrive_Dash_Api_Mandrill {

	public $apikey;
	public $ch;
	public $root = 'https://mandrillapp.com/api/1.0';
	public $debug = false;

	public static $error_map = array(
		"ValidationError"            => "Mandrill_ValidationError",
		"Invalid_Key"                => "Mandrill_Invalid_Key",
		"PaymentRequired"            => "Mandrill_PaymentRequired",
		"Unknown_Subaccount"         => "Mandrill_Unknown_Subaccount",
		"Unknown_Template"           => "Mandrill_Unknown_Template",
		"ServiceUnavailable"         => "Mandrill_ServiceUnavailable",
		"Unknown_Message"            => "Mandrill_Unknown_Message",
		"Invalid_Tag_Name"           => "Mandrill_Invalid_Tag_Name",
		"Invalid_Reject"             => "Mandrill_Invalid_Reject",
		"Unknown_Sender"             => "Mandrill_Unknown_Sender",
		"Unknown_Url"                => "Mandrill_Unknown_Url",
		"Unknown_TrackingDomain"     => "Mandrill_Unknown_TrackingDomain",
		"Invalid_Template"           => "Mandrill_Invalid_Template",
		"Unknown_Webhook"            => "Mandrill_Unknown_Webhook",
		"Unknown_InboundDomain"      => "Mandrill_Unknown_InboundDomain",
		"Unknown_InboundRoute"       => "Mandrill_Unknown_InboundRoute",
		"Unknown_Export"             => "Mandrill_Unknown_Export",
		"IP_ProvisionLimit"          => "Mandrill_IP_ProvisionLimit",
		"Unknown_Pool"               => "Mandrill_Unknown_Pool",
		"NoSendingHistory"           => "Mandrill_NoSendingHistory",
		"PoorReputation"             => "Mandrill_PoorReputation",
		"Unknown_IP"                 => "Mandrill_Unknown_IP",
		"Invalid_EmptyDefaultPool"   => "Mandrill_Invalid_EmptyDefaultPool",
		"Invalid_DeleteDefaultPool"  => "Mandrill_Invalid_DeleteDefaultPool",
		"Invalid_DeleteNonEmptyPool" => "Mandrill_Invalid_DeleteNonEmptyPool",
		"Invalid_CustomDNS"          => "Mandrill_Invalid_CustomDNS",
		"Invalid_CustomDNSPending"   => "Mandrill_Invalid_CustomDNSPending",
		"Metadata_FieldLimit"        => "Mandrill_Metadata_FieldLimit",
		"Unknown_MetadataField"      => "Mandrill_Unknown_MetadataField"
	);

	public function __construct( $apikey = null ) {
		if ( ! $apikey ) {
			$apikey = getenv( 'MANDRILL_APIKEY' );
		}
		if ( ! $apikey ) {
			$apikey = $this->readConfigs();
		}
		if ( ! $apikey ) {
			throw new Thrive_Dash_Api_Mandrill_Exceptions( 'You must provide a Mandrill API key' );
		}
		$this->apikey = $apikey;

		$this->root = rtrim( $this->root, '/' ) . '/';

		$this->templates   = new Thrive_Dash_Api_Mandrill_Template( $this );
		$this->exports     = new Thrive_Dash_Api_Mandrill_Exports( $this );
		$this->users       = new Thrive_Dash_Api_Mandrill_Users( $this );
		$this->rejects     = new Thrive_Dash_Api_Mandrill_Rejects( $this );
		$this->inbound     = new Thrive_Dash_Api_Mandrill_Inbound( $this );
		$this->tags        = new Thrive_Dash_Api_Mandrill_Tags( $this );
		$this->messages    = new Thrive_Dash_Api_Mandrill_Messages( $this );
		$this->whitelists  = new Thrive_Dash_Api_Mandrill_Whitelists( $this );
		$this->ips         = new Thrive_Dash_Api_Mandrill_Ips( $this );
		$this->internal    = new Thrive_Dash_Api_Mandrill_Internal( $this );
		$this->subaccounts = new Thrive_Dash_Api_Mandrill_Subaccounts( $this );
		$this->urls        = new Thrive_Dash_Api_Mandrill_Urls( $this );
		$this->webhooks    = new Thrive_Dash_Api_Mandrill_Webhooks( $this );
		$this->senders     = new Thrive_Dash_Api_Mandrill_Senders( $this );
		$this->metadata    = new Thrive_Dash_Api_Mandrill_Metadata( $this );
	}

	public function call( $url, $params ) {
		$post_url      = $this->root . $url . '.json';
		$params['key'] = $this->apikey;
		$params        = json_encode( $params );


		$result = tve_dash_api_remote_post( $post_url, array(
			'body'      => $params,
			'timeout'   => 15,
			'headers'   => array(
				'User-Agent'   => "Mandrill-PHP/1.0.55",
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			),
			'sslverify' => false,
		) );


		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_Mandrill_Exceptions( 'We were unable to decode the JSON response from the Mandrill API: ' . $result->errors['http_failure'][0] );
		}

		if ( $result === null ) {
			throw new Thrive_Dash_Api_Mandrill_Exceptions( 'We were unable to decode the JSON response from the Mandrill API: ' . $result['body'] );
		}

		if ( $result['response']['code'] != 200 ) {
			$error = json_decode( $result['body'] );
			throw $this->castError( $error->message );
		}

		return $result;
	}

	public function readConfigs() {
		$paths = array( '~/.mandrill.key', '/etc/mandrill.key' );
		foreach ( $paths as $path ) {
			if ( file_exists( $path ) ) {
				$apikey = trim( file_get_contents( $path ) );
				if ( $apikey ) {
					return $apikey;
				}
			}
		}

		return false;
	}

	public function castError( $result ) {
		throw new Thrive_Dash_Api_Mandrill_Exceptions( json_encode( $result ) );
	}

	public function log( $msg ) {
		if ( $this->debug ) {
			error_log( $msg );
		}
	}
}


