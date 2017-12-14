<?php

class Thrive_Dash_Api_MailerLite {

	/**
	 * @var null | string
	 */
	protected $apiKey;

	/**
	 * @var Thrive_Dash_Api_MailerLite_RestClient
	 */
	protected $restClient;

	/**
	 * Thrive_Dash_Api_MailerLite constructor.
	 *
	 * @param null $apiKey
	 *
	 * @throws Thrive_Dash_Api_MailerLite_MailerLiteSdkException
	 */
	public function __construct( $apiKey = null ) {
		if ( is_null( $apiKey ) ) {
			throw new Thrive_Dash_Api_MailerLite_MailerLiteSdkException( 'API key is not provided' );
		}

		$this->apiKey = $apiKey;

		$this->restClient = new Thrive_Dash_Api_MailerLite_RestClient(
			$this->getBaseUrl(),
			$apiKey
		);
	}

	/**
	 * @return Thrive_Dash_Api_MailerLite_Groups
	 */
	public function groups() {
		return new Thrive_Dash_Api_MailerLite_Groups( $this->restClient );
	}

	/**
	 * @return Thrive_Dash_Api_MailerLite_Fields
	 */
	public function fields() {
		return new Thrive_Dash_Api_MailerLite_Fields( $this->restClient );
	}

	/**
	 * @return Thrive_Dash_Api_MailerLite_Subscribers
	 */
	public function subscribers() {
		return new Thrive_Dash_Api_MailerLite_Subscribers( $this->restClient );
	}

	/**
	 * @return Thrive_Dash_Api_MailerLite_Campaigns
	 */
	public function campaigns() {
		return new Thrive_Dash_Api_MailerLite_Campaigns( $this->restClient );
	}

	/**
	 * @param  string $version
	 *
	 * @return string
	 */
	public function getBaseUrl( $version = Thrive_Dash_Api_MailerLite_ApiConstants::VERSION ) {
		return Thrive_Dash_Api_MailerLite_ApiConstants::BASE_URL . $version . '/';
	}

}