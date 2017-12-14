<?php
/*
Plugin Name: dsIDXpress
Plugin URI: http://www.dsidxpress.com/
Description: This plugin allows WordPress to embed live real estate data from an MLS directly into a blog. You MUST have a dsIDXpress account to use this plugin.
Author: Diverse Solutions
Author URI: http://www.diversesolutions.com/
Version: 2.1.25
*/

/*
	Copyright 2009, Diverse Solutions

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
*/
global $wp_version;

require_once(ABSPATH . "wp-admin/includes/plugin.php");
$pluginData = get_plugin_data(__FILE__);

if (!defined("DSIDXPRESS_OPTION_NAME")) define("DSIDXPRESS_OPTION_NAME", "dsidxpress");
define("DSIDXPRESS_API_OPTIONS_NAME", "dsidxpress-api-options");

define("DSIDXPRESS_MIN_VERSION_PHP", "5.2.0");
define("DSIDXPRESS_MIN_VERSION_WORDPRESS", "2.8");
define("DSIDXPRESS_PLUGIN_URL", plugins_url() . "/dsidxpress/");
define("DSIDXPRESS_PLUGIN_VERSION", $pluginData["Version"]);

define('DSIDXPRESS_INACTIVE_ACCOUNT_MESSAGE', 'We\'re sorry, but there’s nothing to display here; MLS data service is not activated for this account.');
define('DSIDXPRESS_IDX_ERROR_MESSAGE', 'We\'re sorry, but it seems that we\'re having some problems loading properties from our database. Please check back soon.');
define('DSIDXPRESS_INVALID_MLSID_MESSAGE', 'We\'re sorry, but we couldn\'t find MLS # %s in our database. This property may be a new listing or possibly taken off the market. Please check back again.');

define("DSIDXPRESS_MAPS_JS_HANDLE", "googlemaps3");

if (version_compare(phpversion(), DSIDXPRESS_MIN_VERSION_PHP) == -1 || version_compare($wp_version, DSIDXPRESS_MIN_VERSION_WORDPRESS) == -1) {
	add_action("admin_notices", "dsidxpress_DisplayVersionWarnings");
	return;
}

if (get_option("dssearchagent-wordpress-edition")) {
	$mergedOption = get_option("dssearchagent-wordpress-edition");
	if (is_array(get_option("dsidxpress-custom-options")))
		$mergedOption = array_merge($mergedOption, get_option("dsidxpress-custom-options"));
	update_option(DSIDXPRESS_OPTION_NAME, $mergedOption);
	delete_option("dssearchagent-wordpress-edition");
	delete_option("dsidxpress-custom-options");
}

// sometimes dirname( __FILE__ ) gives us a bad location, but sometimes require_once(...) doesn't require from the correct directory.
// so we're splitting the difference here and seeing if dirname( __FILE__ ) is valid by checking the existence of a well-known file,
// then falling back to an empty path name if it's invalid.
if(file_exists(dirname( __FILE__ ) . "/dsidxpress.php")){
	$require_prefix = dirname( __FILE__ ) . "/";
} else {
	$require_prefix = "";
}

require_once($require_prefix . "globals.php");
require_once($require_prefix . "widget-idx-quick-search.php");
require_once($require_prefix . "widget-idx-guided-search.php");
require_once($require_prefix . "widget-list-areas.php");
require_once($require_prefix . "widget-listings.php");
require_once($require_prefix . "widget-single-listing.php");
require_once($require_prefix . "rewrite.php");
require_once($require_prefix . "api-request.php");
require_once($require_prefix . "cron.php");
require_once($require_prefix . "xml-sitemaps.php");
require_once($require_prefix . "roles.php");
require_once($require_prefix . "footer.php");
require_once($require_prefix . "dsidxpress_seo.php");
require_once($require_prefix . "autocomplete.php");
require_once($require_prefix . "idx-listings-pages.php");

if (is_admin()) {
	// this is needed specifically for development as PHP seems to choke when 1) loading this in admin, 2) using windows, 3) using directory junctions
	include_once(str_replace("\\", "/", WP_PLUGIN_DIR) . "/dsidxpress/admin.php");
} else {
	require_once($require_prefix . "client.php");
	require_once($require_prefix . "shortcodes.php");
}

if (defined('DS_API')) {
	dsSearchAgent_ApiRequest::$ApiEndPoint = DS_API;
} else {
	define('DS_API', 'http://api-c.idx.diversesolutions.com/api/');
	dsSearchAgent_ApiRequest::$ApiEndPoint = DS_API;
}

register_activation_hook(__FILE__, "dsidxpress_FlushRewriteRules");
add_action("widgets_init", "dsidxpress_InitWidgets");

// not in a static class to prevent PHP < 5 from failing when including and interpreting this particular file
function dsidxpress_DisplayVersionWarnings() {
	global $wp_version;

	$currentVersionPhp = phpversion();
	$currentVersionWordPress = $wp_version;

	$minVersionPhp = DSIDXPRESS_MIN_VERSION_PHP;
	$minVersionWordPress = DSIDXPRESS_MIN_VERSION_WORDPRESS;

	echo <<<HTML
		<div class="error">
			In order to use the dsIDXpress plugin, your web server needs to be running at least PHP {$minVersionPhp} and WordPress {$minVersionWordPress}.
			You're currently using PHP {$currentVersionPhp} and WordPress {$currentVersionWordPress}. Please consider upgrading.
		</div>
HTML;
}
function dsidxpress_InitWidgets() {
	register_widget("dsSearchAgent_IdxQuickSearchWidget");
	register_widget("dsSearchAgent_IdxGuidedSearchWidget");
	register_widget("dsSearchAgent_ListAreasWidget");
	register_widget("dsSearchAgent_ListingsWidget");
	register_widget("dsSearchAgent_SingleListingWidget");
}
function dsidxpress_FlushRewriteRules() {
	global $wp_rewrite;

	$accountOptionsResponse = dsSearchAgent_ApiRequest::FetchData("AccountOptions");
	$remoteOptions = json_decode($accountOptionsResponse["body"]);
	$localOptions = get_option(DSIDXPRESS_OPTION_NAME);
	$localOptions["UseAlternateUrlStructure"] = strtolower($remoteOptions->UseAlternateUrlStructureInDsIdxPress) == "true";
	update_option(DSIDXPRESS_OPTION_NAME, $localOptions);
	$wp_rewrite->flush_rules();
}


require_once($require_prefix . "dsidxwidgets/dsidxwidgets.php");

?>