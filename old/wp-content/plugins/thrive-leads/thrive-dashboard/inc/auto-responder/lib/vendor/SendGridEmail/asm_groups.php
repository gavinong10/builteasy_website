<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class ASMGroups {
	protected
		$base_endpoint,
		$endpoint,
		$client;

	public function __construct( $client, $options = null ) {
		$this->name          = null;
		$this->base_endpoint = "/v3/asm/groups";
		$this->endpoint      = "/v3/asm/groups";
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

	public function get() {
		return $this->client->getRequest( $this );
	}
}