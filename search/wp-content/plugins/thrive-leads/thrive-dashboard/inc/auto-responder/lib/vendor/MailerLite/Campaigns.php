<?php

class Thrive_Dash_Api_MailerLite_Campaigns extends Thrive_Dash_Api_MailerLite_ApiAbstract {

	protected $endpoint = 'campaigns';

	/**
	 * Add custom html to campaign
	 *
	 * @param int $campaignId
	 * @param array $contentData
	 * @param array $params
	 *
	 * @return [type]
	 */
	public function addContent( $campaignId, $contentData = array(), $params = array() ) {
		$endpoint = $this->endpoint . '/' . $campaignId . '/content';

		$response = $this->restClient->put( $endpoint, $contentData );

		return $response['body'];
	}

	/**
	 * Trigger action: send
	 *
	 * @param  int $campaignId
	 * @param  array $settingsData
	 *
	 * @return [type]
	 */
	public function send( $campaignId, $settingsData ) {
		$endpoint = $this->endpoint . '/' . $campaignId . '/actions/send';

		$response = $this->restClient->post( $endpoint, $settingsData );

		return $response['body'];
	}

	/**
	 * Trigger action: cancel
	 *
	 * @param  int $campaignId
	 *
	 * @return [type]
	 */
	public function cancel( $campaignId ) {
		$endpoint = $this->endpoint . '/' . $campaignId . '/actions/cancel';

		$response = $this->restClient->post( $endpoint );

		return $response['body'];
	}
}