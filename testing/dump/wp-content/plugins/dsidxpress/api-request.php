<?php
class dsSearchAgent_ApiRequest {
	public static $ApiEndPoint = '';
	// do NOT change this value or you will be automatically banned from the API. since the data is only updated every two hours, and
	// since these API calls are computationally intensive on our servers, we need to set a reasonable cache duration.
	private static $CacheSeconds = 7200;
	private static $NumericValues = array(
		"query.PriceMin",
		"query.PriceMax",
		"query.ImprovedSqFtMin",
		"query.BedsMin",
		"query.BathsMin"
	);

	static function FetchData($action, $params = array(), $echoAssetsIfNotEnqueued = true, $cacheSecondsOverride = null, $options = null, $useGET = false) {
		global $wp_query, $wp_version;

		$options = $options ? $options : get_option(DSIDXPRESS_OPTION_NAME);
		$privateApiKey = $options["PrivateApiKey"];
		$requestUri = self::$ApiEndPoint . $action;
		$compressCache = function_exists('gzdeflate') && function_exists('gzinflate');

		$params["SearchSetupID"] = $options["SearchSetupID"];
		$params["query.SearchSetupID"] = $options["SearchSetupID"];
		$params["requester.SearchSetupID"] = $options["SearchSetupID"];
		$params["requester.AccountID"] = $options["AccountID"];
		if(is_array($wp_query->query) && isset($wp_query->query['ds-idx-listings-page'])){
			$params["requester.HideIdxUrlFilters"] = 'hide';
		}
		if(!isset($params["requester.ApplicationProfile"]))
			$params["requester.ApplicationProfile"] = "WordPressIdxModule";
		$params["requester.ApplicationVersion"] = $wp_version;
		$params["requester.PluginVersion"] = DSIDXPRESS_PLUGIN_VERSION;
		$params["requester.RequesterUri"] = get_home_url();
		
		if(isset($_COOKIE['dsidx-visitor-public-id']))
			$params["requester.VisitorPublicID"] = $_COOKIE['dsidx-visitor-public-id'];
		if(isset($_COOKIE['dsidx-visitor-auth']))
			$params["requester.VisitorAuth"] = $_COOKIE['dsidx-visitor-auth'];
		if(@$options["dsIDXPressPackage"] == "lite")
			$params["requester.IsRegistered"] = current_user_can(dsSearchAgent_Roles::$Role_ViewDetails) ? "true" : "false";

		foreach (self::$NumericValues as $key) {
			if (array_key_exists($key, $params))
				$params[$key] = str_replace(",", "", $params[$key]);
		}

		ksort($params);
		$transientKey = "idx_" . sha1($action . "_" . http_build_query($params));

		if ($cacheSecondsOverride !== 0 && (!isset($options['DisableLocalCaching']) || $options['DisableLocalCaching'] != 'true')) {
			$cachedRequestData = get_transient($transientKey);

			if ($cachedRequestData) {
				$cachedRequestData = $compressCache ? unserialize(gzinflate(base64_decode($cachedRequestData))) : $cachedRequestData;
				$cachedRequestData["body"] = self::ExtractAndEnqueueStyles($cachedRequestData["body"], $echoAssetsIfNotEnqueued);
				$cachedRequestData["body"] = self::ExtractAndEnqueueScripts($cachedRequestData["body"]);
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
		$params["requester.Signature"] = hash_hmac("sha1", $stringToSign, $privateApiKey);
		$response = null;

		if ($useGET !== null && $useGET) {
			header('Location: ' . $requestUri . '?' . http_build_query($params));
		} 
		else
		{
			$response = (array)wp_remote_post($requestUri, array(
				"headers"		=> array('Accept-Encoding' => 'identity'),
				"body"			=> $params,
				"redirection"	=> "0",
				"timeout"		=> 15, // we look into anything that takes longer than 2 seconds to return
				"reject_unsafe_urls" => false
			));

			if (empty($response["errors"]) && isset($response["response"]) && substr($response["response"]["code"], 0, 1) != "5") {
				$response["body"] = self::FilterData($response["body"]);
				if ($response["body"]){
					if ($cacheSecondsOverride !== 0 && (!isset($options['DisableLocalCaching']) || $options['DisableLocalCaching'] != 'true'))
						set_transient($transientKey, $compressCache ? base64_encode(gzdeflate(serialize($response))) : $response, $cacheSecondsOverride === null ? self::$CacheSeconds : $cacheSecondsOverride);
					else
						delete_transient($transientKey);
				}
				
				$response["body"] = self::ExtractAndEnqueueStyles($response["body"], $echoAssetsIfNotEnqueued);
				$response["body"] = self::ExtractAndEnqueueScripts($response["body"], $echoAssetsIfNotEnqueued);
			}
		}

		return $response;
	}
	private static function FilterData($data) {
		global $wp_version;

		$blog_url = get_home_url();

		$blogUrlWithoutProtocol = str_replace("http://", "", $blog_url);
		$blogUrlDirIndex = strpos($blogUrlWithoutProtocol, "/");
		$blogUrlDir = "";
		if ($blogUrlDirIndex) // don't need to check for !== false here since WP prevents trailing /'s
			$blogUrlDir = substr($blogUrlWithoutProtocol, strpos($blogUrlWithoutProtocol, "/"));

		$idxActivationPath = $blogUrlDir . "/" . dsSearchAgent_Rewrite::GetUrlSlug();

		$dsidxpress_options = get_option(DSIDXPRESS_OPTION_NAME);
		$dsidxpress_option_keys_to_output = array("ResultsDefaultState", "ResultsMapDefaultState");
		$dsidxpress_options_to_output = array();

		if(!empty($dsidxpress_options)){
			foreach($dsidxpress_options as $key => $value)
			{
				if(in_array($key, $dsidxpress_option_keys_to_output))
					$dsidxpress_options_to_output[$key] = $value;
			}
		}

		$data = str_replace('{$pluginUrlPath}', self::MakePluginsUrlRelative(plugin_dir_url(__FILE__)), $data);
		$data = str_replace('{$pluginVersion}', DSIDXPRESS_PLUGIN_VERSION, $data);
		$data = str_replace('{$wordpressVersion}', $wp_version, $data);
		$data = str_replace('{$wordpressBlogUrl}', $blog_url, $data);
		$data = str_replace('{$wordpressBlogUrlEncoded}', urlencode($blog_url), $data);
		$data = str_replace('{$wpOptions}', json_encode($dsidxpress_options_to_output), $data);

		$data = str_replace('{$idxActivationPath}', $idxActivationPath, $data);
		$data = str_replace('{$idxActivationPathEncoded}', urlencode($idxActivationPath), $data);

		return $data;
	}
	public static function MakePluginsUrlRelative($url){
		preg_match('/http:\/\/[^\/]+((\/[^\/]+)*\/wp-content\/plugins\/dsidxpress\/.*)/i', $url, $matches);
		
		if (isset($matches[1]))
			return $matches[1];
		else
			return $url;
	}
	private static function ExtractAndEnqueueStyles($data, $echoAssetsIfNotEnqueued) {
		// since we 100% control the data coming from the API, we can set up a regex to look for what we need. regex
		// is never ever ideal to parse html, but since neither wordpress nor php have a HTML parser built in at the
		// time of this writing, we don't really have another choice. in other words, this is super delicate!

		preg_match_all('/<link\s*rel="stylesheet"\s*type="text\/css"\s*href="(?P<href>[^"]+)"\s*data-handle="(?P<handle>[^"]+)"\s*\/>/', $data, $styles, PREG_SET_ORDER);
		foreach ($styles as $style) {
			if (!$echoAssetsIfNotEnqueued || ($echoAssetsIfNotEnqueued && wp_style_is($style["handle"], 'registered')))
				$data = str_replace($style[0], "", $data);

			if ($echoAssetsIfNotEnqueued)
				wp_register_style($style["handle"], $style["href"], false, null);
			else
				wp_enqueue_style($style["handle"], $style["href"], false, null);
		}

		return $data;
	}
	private static function ExtractAndEnqueueScripts($data) {
		// see comment in ExtractAndEnqueueStyles

		global $wp_scripts;
		preg_match_all('/<script\s*src="(?P<src>[^"]+)"\s*data-handle="(?P<handle>[^"]+)"><\/script>/', $data, $scripts, PREG_SET_ORDER);
		foreach ($scripts as $script) {
			$alreadyIncluded = wp_script_is($script['handle']);
			if (!$alreadyIncluded) {
				wp_register_script($script["handle"], $script["src"], array('jquery'), DSIDXPRESS_PLUGIN_VERSION);
				wp_enqueue_script($script["handle"]);				
			}
			$data = str_replace($script[0], "", $data);
		}
		return $data;
	}
}
?>