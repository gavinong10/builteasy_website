<?php
/**
 * This file contains the Thrive_Dash_Api_Sendreach_Request class used in the MailWizzApi PHP-SDK.
 *
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link http://www.mailwizz.com/
 * @copyright 2013-2015 http://www.mailwizz.com/
 */


/**
 * Thrive_Dash_Api_Sendreach_Request is the request class used to send the requests to the API endpoints.
 *
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @package MailWizzApi
 * @subpackage Http
 * @since 1.0
 */
class Thrive_Dash_Api_Sendreach_Request extends Thrive_Dash_Api_Sendreach {
	/**
	 * @var Thrive_Dash_Api_Sendreach_Client the http client injected.
	 */
	public $client;

	/**
	 * @var Thrive_Dash_Api_Sendreach_Params the request params.
	 */
	public $params;

	/**
	 * Constructor.
	 *
	 * @param Thrive_Dash_Api_Sendreach_Client $client
	 */
	public function __construct( Thrive_Dash_Api_Sendreach_Client $client ) {
		$this->client = $client;
	}

	/**
	 * Send the request to the remote url.
	 *
	 * @return Thrive_Dash_Api_Sendreach_Response
	 */
	public function send() {

		foreach ($this->getEventHandlers(self::EVENT_BEFORE_SEND_REQUEST) as $callback) {
			call_user_func_array($callback, array($this));
		}

		$client         = $this->client;
		$registry       = $this->registry;
		$isCacheable    = $registry->contains('cache') && $client->isGetMethod && $client->enableCache;
		$requestUrl     = rtrim($client->url, '/'); // no trailing slash
		$scheme         = parse_url($requestUrl, PHP_URL_SCHEME);

		$getParams = (array)$client->paramsGet->toArray();
		if (!empty($getParams)) {
			ksort($getParams, SORT_STRING);
			$queryString = http_build_query($getParams, '', '&');
			if (!empty($queryString)) {
				$requestUrl .= '?'.$queryString;
			}
		}

		$this->sign($requestUrl);

		if ($isCacheable) {
			$client->getResponseHeaders = true;

			$bodyFromCache  = null;
			$etagCache      = null;
			$params         = $getParams;

			foreach (array('X-MW-PUBLIC-KEY', 'X-MW-TIMESTAMP', 'X-MW-REMOTE-ADDR') as $header) {
				$params[$header] = $client->headers->itemAt($header);
			}

			$cacheKey    = $requestUrl;
			$cache        = $this->cache->get($cacheKey);

			if (isset($cache['headers']) && is_array($cache['headers'])) {
				foreach ($cache['headers'] as $header) {
					if (preg_match('/etag:(\s+)?(.*)/ix', $header, $matches)) {
						$etagCache = trim($matches[2]);
						$client->headers->add('If-None-Match', $etagCache);
						$bodyFromCache = $cache['body'];
						break;
					}
				}
			}
		}

		if ($client->isPutMethod || $client->isDeleteMethod) {
			$client->headers->add('X-HTTP-Method-Override', strtoupper($client->method));
		}

		if ($client->headers->count > 0) {
			$headers = array();
			foreach($client->headers as $name => $value) {
				$headers[] = $name.': '.$value;
			}
		}

		if ($client->isPostMethod || $client->isPutMethod || $client->isDeleteMethod) {

			$params = new Thrive_Dash_Api_Sendreach_Params($client->paramsPost);
			$params->mergeWith($client->paramsPut);
			$params->mergeWith($client->paramsDelete);
		}

		$body = '';
		if($client->isPostMethod || $client->isPutMethod || $client->isDeleteMethod) {
			$body = $client->paramsPost->toArray();
		}

		$tve_headers                 = $client->headers->toArray();
		$tve_headers['User-Agent']   = 'MailWizzApi Client version ' . Thrive_Dash_Api_Sendreach_Client::CLIENT_VERSION;
		if($client->getIsGetMethod()) {
//			$tve_headers['Content-type'] = 'application/json';
		}

		$args = array(
			'headers' => $tve_headers,
			'body'    => $body,
		);

		switch ( $client->method ) {
			case 'POST':
				$result = tve_dash_api_remote_post( $requestUrl, $args );
				break;
			case 'GET':
			default:
				$result = tve_dash_api_remote_get( $requestUrl, $args );
				break;
		}

		/**
		 * maybe we will need the headers for something sometime
		 */
		$headers = $result['headers'];
		$body = $result['body'];
		$response_code = $result['response']['code'];


		$decodedBody = Thrive_Dash_Api_Sendreach_Json::decode($body, true);

		/**
		 * error handling
		 */
		switch ($response_code)  {
			case 400:
			case 422:
				throw new Thrive_Dash_Api_Sendreach_Exception("Invalid API request. Message: " . $decodedBody['error']);
			default:
				break;
		}

		return $decodedBody;
	}

	/**
	 * Sign the current request.
	 */
	protected function sign( $requestUrl ) {
		$client = $this->client;
		$config = $this->config;

		$publicKey  = $config->publicKey;
		$privateKey = $config->privateKey;
		$timestamp  = time();

		$specialHeaderParams = array(
			'X-MW-PUBLIC-KEY'  => $publicKey,
			'X-MW-TIMESTAMP'   => $timestamp,
			'X-MW-REMOTE-ADDR' => isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null,
		);

		foreach ( $specialHeaderParams as $key => $value ) {
			$client->headers->add( $key, $value );
		}

		$params = new Thrive_Dash_Api_Sendreach_Params( $specialHeaderParams );
		$params->mergeWith( $client->paramsPost );
		$params->mergeWith( $client->paramsPut );
		$params->mergeWith( $client->paramsDelete );

		$params = $params->toArray();
		ksort( $params, SORT_STRING );

		$separator       = $client->paramsGet->count > 0 && strpos( $requestUrl, '?' ) !== false ? '&' : '?';
		$signatureString = strtoupper( $client->method ) . ' ' . $requestUrl . $separator . http_build_query( $params, '', '&' );
		$signature       = hash_hmac( 'sha1', $signatureString, $privateKey, false );

		$client->headers->add( 'X-MW-SIGNATURE', $signature );
	}
}