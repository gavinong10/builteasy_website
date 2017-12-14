<?php

// hook into Better Wordpress Google XML Sitemaps plugin for Multi-Site http://wordpress.org/plugins/bwp-google-xml-sitemaps/
//add_action('bwp_gxs_modules_built', array('dsSearchAgent_XmlSitemaps', 'BwpGoogleXmlSitemapModule'));
add_filter('bwp_gxs_external_pages', array('dsSearchAgent_XmlSitemaps', 'BwpGoogleXmlSitemap'));

// or hook into Google XML Sitemaps Generator plugin http://wordpress.org/extend/plugins/google-sitemap-generator/
add_action('sm_buildmap', array('dsSearchAgent_XmlSitemaps', 'GoogleXmlSitemap'));

class dsSearchAgent_XmlSitemaps {

	static function GetSitemapOptions() {

		$options = get_option(DSIDXPRESS_OPTION_NAME);

		$urlBase = get_home_url();
		if (substr($urlBase, strlen($urlBase), 1) != "/") $urlBase .= "/";
		$urlBase .= dsSearchAgent_Rewrite::GetUrlSlug();

		$sitemapUrls = array();
		$idxPages     = get_pages(array('post_type' => 'ds-idx-listings-page', 'post_status'=>'publish'));
		foreach ($idxPages as $page) {
    		$sitemapUrls[] = array('location' => get_permalink($page->ID), 'frequency' => 'daily', 'priority' => 0.5);
    	}
		if (isset($options["SitemapLocations"]) && is_array($options["SitemapLocations"])) {
			$location_index = 0;
			usort($options["SitemapLocations"], array("dsSearchAgent_XmlSitemaps", "CompareListObjects"));

				foreach ($options["SitemapLocations"] as $key => $value) {
					$area = $value["value"];
					$type = $value["type"];

					if (preg_match('/^[\w\d\s\-_]+$/', $area)) {
						$location_sanitized = urlencode(strtolower(str_replace(array("-", " "), array("_", "-"), $value["value"])));
						$url = $urlBase . $value["type"] .'/'. $location_sanitized . '/';
					} else if ($type == "city") {
						$url = $urlBase . "?idx-q-Cities=" . urlencode($area);
					} else if ($type == "community") {
						$url = $urlBase . "?idx-q-Communities=" . urlencode($area);
					} else if ($type == "tract") {
						$url = $urlBase . "?idx-q-TractIdentifiers=" . urlencode($area);
					}
					// zips will always match the regex

					$sitemapUrls[] = array('location' => $url, 'frequency' => $options['SitemapFrequency'], 'priority' => floatval($value["priority"]));
					
				}
   		}
   		return empty($sitemapUrls)?false:$sitemapUrls;
	}

	static function GoogleXmlSitemap() {

		if (class_exists('GoogleSitemapGenerator') || class_exists('GoogleSitemapGeneratorStandardBuilder')) {
			$generatorObject = &GoogleSitemapGenerator::GetInstance();
			$options = self::GetSitemapOptions();

			if ($generatorObject != null && is_array($options)) {

				foreach ($options as $optionArr) {
					$generatorObject->AddUrl($optionArr['location'], time(), $optionArr['frequency'], $optionArr['priority']);
				}

			}
		}

	}

	static function BwpGoogleXmlSitemap() {

		if (class_exists('BWP_GXS_MODULE_PAGE_EXTERNAL')) {
			$options = self::GetSitemapOptions();

			if (is_array($options)) {

				for ($i=0; $i<count($options); $i++) {
					$options[$i]['lastmod'] = 'now';
				}

				return $options;
			}
		}

	}
/*
	static function BwpGoogleXmlSitemapModule() {

		global $bwp_gxs;
		$bwp_gxs->add_module('page', 'idxpress');

	}
*/
	static function CompareListObjects($a, $b)
    {
        $al = strtolower($a["value"]);
        $bl = strtolower($b["value"]);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }
}

?>