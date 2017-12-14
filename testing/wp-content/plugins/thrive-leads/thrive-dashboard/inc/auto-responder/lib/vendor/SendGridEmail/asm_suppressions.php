<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class ASMSuppressions {
	protected
		$base_endpoint,
		$endpoint,
		$client;

	public function __construct( $client, $options = null ) {
		$this->base_endpoint = "/v3/asm";
		$this->endpoint      = "/v3/asm";
		$this->client        = $client;
	}

	public function getBaseEndpoint() {
		return $this->base_endpoint;
	}

	public function getEndpoint() {
		return $this->endpoint;
	}

	public function setEndpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	public function get( $group_id = null ) {
		if ( $group_id != null ) {
			$this->endpoint = $this->base_endpoint . "/groups/" . $group_id . "/suppressions";
		}

		return $this->client->getRequest( $this );
	}

	public function post( $group_id = null, $email = null ) {
		if ( ! is_array( $email ) ) {
			$email = array( $email );
		}
		$this->endpoint = $this->base_endpoint . "/groups/" . $group_id . "/suppressions";
		$data           = array(
			'recipient_emails' => $email,
		);

		return $this->client->postRequest( $this, $data );
	}

	public function delete( $group_id = null, $email = null ) {
		$this->endpoint = $this->base_endpoint . "/groups/" . $group_id . "/suppressions/" . $email;

		return $this->client->deleteRequest( $this );
	}
}