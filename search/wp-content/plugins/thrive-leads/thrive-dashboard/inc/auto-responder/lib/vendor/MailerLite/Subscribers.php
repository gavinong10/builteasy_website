<?php

class Thrive_Dash_Api_MailerLite_Subscribers extends Thrive_Dash_Api_MailerLite_ApiAbstract {

	protected $endpoint = 'subscribers';

	/**
	 * Get groups subscriber belongs to
	 *
	 * @param  int $subscriberId
	 * @param  array $params
	 *
	 * @return [type]
	 */
	public function getGroups( $subscriberId, $params = array() ) {
		$this->endpoint .= $subscriberId . '/groups';

		$response = $this->restClient->get( $endpoint, $params );

		return $response['body'];
	}

	/**
	 * Get activity of subscriber
	 *
	 * @param  int $subscriberId
	 * @param  string $type
	 * @param  array $params
	 *
	 * @return [type]
	 */
	public function getActivity( $subscriberId, $type = null, $params = array() ) {
		$this->endpoint .= $subscriberId . '/activity';

		if ( $type !== null ) {
			$this->endpoint .= '/' . $type;
		}

		$response = $this->restClient->get( $endpoint, $params );

		return $response['body'];
	}

	/**
	 * Seach for a subscriber by email or custom field value
	 *
	 * @param  string $query
	 *
	 * @return [type]
	 */
	public function search( $query ) {
		$this->endpoint .= '/search';

		$response = $this->restClient->get( $this->endpoint, array( 'query' => $query ) );

		return $response['body'];
	}

}