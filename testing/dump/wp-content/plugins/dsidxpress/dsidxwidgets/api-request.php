<?php
class dsWidgetAgent_ApiRequest {
    public static $ApiEndPoint = "http://api-b.idx.diversesolutions.com/api/";
	// do NOT change this value or you will be automatically banned from the API. since the data is only updated every two hours, and
	// since these API calls are computationally intensive on our servers, we need to set a reasonable cache duration.
	private static $CacheSeconds = 7200;

	static function FetchData($action, $params = array(), $echoAssetsIfNotEnqueued = true, $cacheSecondsOverride = null, $options = null, $headers = array()) {
		global $wp_query, $wp_version;

		require_once(ABSPATH . "wp-admin/includes/plugin.php");
		$pluginData = get_plugin_data(__FILE__);
		$pluginVersion = $pluginData["Version"];

		$options = $options ? $options : get_option(DSIDXWIDGETS_OPTION_NAME);
		$requestUri = self::$ApiEndPoint . $action;
		$compressCache = function_exists('gzdeflate') && function_exists('gzinflate');

		$idxpress_options = get_option(DSIDXPRESS_OPTION_NAME);

		if(!empty($idxpress_options['AccountID']) && !empty($idxpress_options['SearchSetupID'])){
			$params["query.SearchSetupID"] = $idxpress_options["SearchSetupID"];
			$params["requester.AccountID"] = $idxpress_options["AccountID"];
		}
		else{
			$params["query.SearchSetupID"] = $options["SearchSetupID"];
			$params["requester.AccountID"] = $options["AccountID"];
		}

		if(!isset($params["requester.ApplicationProfile"]))
			$params["requester.ApplicationProfile"] = "WordPressIdxModule";
		$params["requester.ApplicationVersion"] = $wp_version;
		$params["requester.PluginVersion"] = $pluginVersion;
		$params["requester.RequesterUri"] = get_home_url();
		
		if(isset($_COOKIE['dsidx-visitor-public-id']))
			$params["requester.VisitorPublicID"] = $_COOKIE['dsidx-visitor-public-id'];
		if(isset($_COOKIE['dsidx-visitor-auth']))
			$params["requester.VisitorAuth"] = $_COOKIE['dsidx-visitor-auth'];
		
		if(isset($_COOKIE['dsidx-visitor-details-views']))
			$params["requester.VisitorDetailViews"] = $_COOKIE['dsidx-visitor-details-views'];
		if(isset($_COOKIE['dsidx-visitor-results-views']))
			$params["requester.VisitorResultsViews"] = $_COOKIE['dsidx-visitor-results-views'];

		ksort($params);
		$transientKey = "idx_" . sha1($action . "_" . http_build_query($params));

		if ($cacheSecondsOverride !== 0) {
			$cachedRequestData = get_transient($transientKey);
			if ($cachedRequestData) {
				$cachedRequestData = $compressCache ? unserialize(gzinflate(base64_decode($cachedRequestData))) : $cachedRequestData;
				return $cachedRequestData;
			}
		}

		// these params need to be beneath the caching stuff since otherwise the cache will be useless
		$params["requester.ClientIpAddress"] = $_SERVER["REMOTE_ADDR"];
		$params["requester.ClientUserAgent"] = $_SERVER["HTTP_USER_AGENT"];
		if(isset($_SERVER["HTTP_REFERER"]))
			$params["requester.UrlReferrer"] = $_SERVER["HTTP_REFERER"];
		$params["requester.UtcRequestDate"] = gmdate("c");
		
		ksort($params);
		$stringToSign = "";
		foreach ($params as $key => $value) {
			$stringToSign .= "$key:$value\n";
			if (!isset($params[$key]))
				$params[$key] = "";
		}
		$stringToSign = rtrim($stringToSign, "\n");
		$response = (array)wp_remote_post($requestUri, array(
			"body"			=> $params,
			"redirection"	=> "0",
			"headers"       => $headers,
			"timeout"		=> 15, // we look into anything that takes longer than 2 seconds to return
			"reject_unsafe_urls" => false
		));
		if (empty($response["errors"]) && substr($response["response"]["code"], 0, 1) != "5") {
			if ($cacheSecondsOverride !== 0 && $response["body"]){
				set_transient($transientKey, $compressCache ? base64_encode(gzdeflate(serialize($response))) : $response, $cacheSecondsOverride === null ? self::$CacheSeconds : $cacheSecondsOverride);
			}
		}

		return $response;
	}
}
?>