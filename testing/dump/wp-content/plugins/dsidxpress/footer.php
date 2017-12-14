<?php

class dsidx_footer {
	static $disclaimer_queued = false;
	static $viewName = null;

	static function ensure_disclaimer_exists($view = null) {
		//empty view takes precedence since it always shows while specific views check rules first
		if (self::$disclaimer_queued && empty(self::$viewName))
			return;
		else if (self::$disclaimer_queued && !empty(self::$viewName) && !isset($view)) {
			self::$viewName = '';
			return;
		}

		add_action("wp_footer", array("dsidx_footer", "insert_disclaimer"));
		if (!empty($view)) self::$viewName = $view;
		self::$disclaimer_queued = true;
	}

	static function insert_disclaimer() {
		global $wp_query;
		
		if (is_array($wp_query->query)
		    && ((isset($wp_query->query["idx-action"]) && $wp_query->query["idx-action"] == "details")
		    || (isset($wp_query->query["idx-action"]) && $wp_query->query["idx-action"] == "results"))
		   )
			return;

		$apiParams = array();
		$apiParams["responseDirective.IncludeDsDisclaimer"] = (defined('ZPRESS_API') && ZPRESS_API != '') ? "false" : "true";
		if (!empty(self::$viewName))
			$apiParams["responseDirective.ViewName"] = self::$viewName;

		$disclaimer = dsSearchAgent_ApiRequest::FetchData("Disclaimer", $apiParams);
		if(isset($disclaimer['response']['code']) && $disclaimer['response']['code'] == '500'){
			echo $disclaimer["body"];
		}
	}
}