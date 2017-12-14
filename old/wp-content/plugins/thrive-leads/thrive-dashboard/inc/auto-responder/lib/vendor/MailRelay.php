<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Interface to the SendGrid Web API
 */
class Thrive_Dash_Api_MailRelay {
	/**
	 * the query string
	 */
	const QUERY_STRING = 'ccm/admin/api/version/2/&type=json';

	/**
	 * Version
	 */
	const VERSION = '2';
	/**
	 * @var $apiKey
	 */
	protected $apiKey;
	/**
	 * @var $host
	 */
	protected $domain;

	/**
	 * @var $restClient
	 */
	protected $baseUrl;

	/**
	 * @var $restClient
	 */
	protected $restClient;

	/**
	 * @var $group_id
	 */
	protected $group_id;

	/**
	 * Setup the Http Client
	 *
	 * Thrive_Dash_Api_MailRelay constructor.
	 *
	 * @param $options
	 */
	public function __construct( $options ) {

		$this->apiKey = $options['apiKey'];
		$this->domain    = untrailingslashit( $options['host'] );
	}

	/**
	 * Get all groups
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailRelay_Exception
	 */
	public function getLists() {
		$response = $this->call( array( 'function' => 'getGroups' ), 'GET' );

		if ( $response['status'] != 1 ) {
			$body = $response['error'];

			throw new Thrive_Dash_Api_MailRelay_Exception( ucwords( $body ) );
		}

		return $response['data'];
	}

	/**
	 * Get a scubscriber by email
	 *
	 * @param $email
	 *
	 * @return array
	 */
	public function getSubscriber( $email ) {

		$args = array(
			'function' => 'getSubscribers',
			'email'    => $email,
		);

		return $this->call( $args, 'GET' );
	}

	/**
	 * Add a subscriber
	 *
	 * @param $group_id
	 * @param $args
	 *
	 * @return array
	 */
	public function addSubscriber( $group_id, $args ) {

		$this->group_id = $group_id;
		/**
		 * check if email aready exists so we can update it
		 */
		$response = $this->getSubscriber( $args['email'] );

		$subscriber = $response['data'];

		if ( ! empty( $subscriber ) ) {
			return $this->updateSubscriber( $subscriber, $args );
		}

		$args['function'] = 'addSubscriber';
		$args['groups'][] = $this->group_id;

		return $this->call( $args, 'POST' );

	}

	/**
	 * Update a subscriber
	 *
	 * @param $subscriber
	 * @param $args
	 *
	 * @return array
	 */
	public function updateSubscriber( $subscriber, $args ) {
		$args['function'] = 'updateSubscriber';
		$args['id']       = $subscriber[0]['id'];
		$args['groups']   = $subscriber[0]['groups'];
		if ( is_array( $subscriber[0]['groups'] ) && ! in_array( $this->group_id, $subscriber[0]['groups'] ) ) {
			$args['groups'][] = $this->group_id;
		}

		return $this->call( $args, 'POST' );
	}

	/**
	 * Send an email
	 *
	 * @param $args
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailRelay_Exception
	 */
	public function sendEmail( $args ) {
		$args['function'] = 'sendMail';
		$args['mailboxFromId'] = 1;
		$args['mailboxReplyId'] = 1;
		$args['mailboxReportId'] = 1;

		$mailboxes = $this->getMailboxes();

		if($mailboxes['status'] == 1 ) {
			$args['mailboxFromId'] = $mailboxes['data'][0]['id'];
			$args['mailboxReplyId'] = $mailboxes['data'][0]['id'];
			$args['mailboxReportId'] = $mailboxes['data'][0]['id'];
		}

		$packages = $this->getPackages();

		$args['packageId'] = $packages['status'] == 1 ? $packages['data'][0]['id'] : 6 ;

		if ( empty( $args['emails'] ) ) {
			throw new Thrive_Dash_Api_MailRelay_Exception( 'Nor recepients found' );
		}
		$result = $this->call( $args, 'POST' );

		if($result['status'] == 0) {
			throw new Thrive_Dash_Api_MailRelay_Exception( $result['error'] );
		}
		return $result;
	}

	/**
	 * Get Mailboxes
	 * @return array
	 */
	public function getMailboxes() {
		return $this->call( array( 'function' => 'getMailboxes' ), 'GET' );
	}

	public function getPackages () {
		return $this->call( array( 'function' => 'getPackages' ), 'GET' );
	}

	/**
	 * Prepare the call CRUD data
	 *
	 * @param array $params
	 * @param string $method
	 *
	 * @return array
	 */
	public function call( $params = array(), $method = 'POST' ) {

		$params['apiKey'] = $this->apiKey;
		$this->baseUrl    = $this->getBaseUrl();

		switch ( $method ) {
			case 'GET':

				$response = $this->send( 'GET', $this->baseUrl . '&' . http_build_query( $params ) );
				break;
			default:
				$response = $this->send( 'POST', $this->baseUrl, http_build_query( $params ) );
				break;
		}

		return $response;
	}

	/**
	 * Build the baseUrl
	 *
	 * @return string
	 */
	public function getBaseUrl() {
		return $this->domain . '/' . self::QUERY_STRING;
	}

	/**
	 * Execute HTTP request
	 *
	 * @param $method
	 * @param $endpointUrl
	 * @param null $body
	 * @param array $headers
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailRelay_Exception
	 */
	protected function send( $method, $endpointUrl, $body = null, array $headers = array() ) {
		$headers = array();

		switch ( $method ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$endpointUrl = $endpointUrl . '&' .  $body ;
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$response = $fn( $endpointUrl, array(
			'body'      => $body,
			'timeout'   => 15,
			'headers'   => $headers,
			'sslverify' => false,
		) );

		return $this->handleResponse( $response );
	}

	/**
	 * Process the response we're getting
	 *
	 * @param $response
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailRelay_Exception
	 */
	protected function handleResponse( $response ) {

		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_MailRelay_Exception( 'Failed connecting: ' . $response->get_error_message() );
		}

		if ( isset( $response['response']['code'] ) ) {
			switch ( $response['response']['code'] ) {
				case 200:
					$result = json_decode( $response['body'], true );

					return $result;
					break;
				case 400:
					throw new Thrive_Dash_Api_MailRelay_Exception( 'Missing a required parameter or calling invalid method' );
					break;
				case 401:
					throw new Thrive_Dash_Api_MailRelay_Exception( 'Invalid API key provided!' );
					break;
				case 404:
					throw new Thrive_Dash_Api_MailRelay_Exception( 'Can\'t find requested items' );
					break;
			}
		}

		return json_decode( $response['body'], true );
	}


}
