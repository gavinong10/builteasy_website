<?php
/*
Plugin Name: dsIDXWidgets
Plugin URI: http://www.dsidxwidgets.com/
Description: This plugin allows WordPress to embed IDX related widgets
Author: Diverse Solutions
Author URI: http://www.diversesolutions.com/
Version: 1.0.0
*/

/*
	Copyright 2012, Diverse Solutions

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

define("DSIDXWIDGETS_OPTION_NAME", "dsidxwidgets-options");
define("DSIDXWIDGETS_API_OPTIONS_NAME", "dsidxwidgets-api-options");

define("DSIDXWIDGETS_MIN_VERSION_PHP", "5.2.0");
define("DSIDXWIDGETS_MIN_VERSION_WORDPRESS", "2.8");
define("DSIDXWIDGETS_PLUGIN_URL", plugins_url('/', __FILE__));

// sometimes dirname( __FILE__ ) gives us a bad location, but sometimes require_once(...) doesn't require from the correct directory.
// so we're splitting the difference here and seeing if dirname( __FILE__ ) is valid by checking the existence of a well-known file,
// then falling back to an empty path name if it's invalid.
if(file_exists(dirname( __FILE__ ) . "/dsidxwidgets.php")){
	$require_prefix = dirname( __FILE__ ) . "/";
} else {
	$require_prefix = "";
}
require_once($require_prefix . "api-request.php");
//require_once($require_prefix . "widget-service-qrcode.php");
require_once($require_prefix . "widget-service-affordability.php");
require_once($require_prefix . "widget-service-areastats.php");
require_once($require_prefix . "widget-service-recentstatus.php");
require_once($require_prefix . "widget-service-openhouse.php");
require_once($require_prefix . "widget-service-slideshow.php");
require_once($require_prefix . "widget-service-mapsearch.php");
//require_once($require_prefix . "widget-service-quicksearch.php");
require_once($require_prefix . "widget-service-base.php");


dsWidgets_Service_Base::$widgets_admin_api_stub = admin_url() . '?zpress_widget_ajax=true';


if (defined('DS_API')) {
	// this allows us to maintain plugin independence just in case we use this outside of zpress
    dsWidgets_Service_Base::$widgets_api_stub = DS_API;
}

if (defined('WIDGETS_CDN')) {
	// this allows us to maintain plugin independence just in case we use this outside of zpress
    dsWidgets_Service_Base::$widgets_cdn = WIDGETS_CDN;
}

if (is_admin()) {
	// this is needed specifically for development as PHP seems to choke when 1) loading this in admin, 2) using windows, 3) using directory junctions
    include_once(dirname( __FILE__ ) . "/admin.php");
}

if (defined('DSIDXPRESS_PLUGIN_VERSION')) {
	add_action("widgets_init", "dsidxwidgets_InitWidgets");
}

function dsidxwidgets_InitWidgets() {
	$options = get_option(DSIDXPRESS_OPTION_NAME);
	
	if (defined('ZPRESS_API') || isset($options["dsIDXPressPackage"]) && $options["dsIDXPressPackage"] == "pro") {
		//register_widget("dsIDXWidgets_QRCode");
		register_widget("dsIDXWidgets_Affordability");
		register_widget("dsIDXWidgets_AreaStats");
		register_widget("dsIDXWidgets_RecentStatus");
		register_widget("dsIDXWidgets_OpenHouse");
		register_widget("dsIDXWidgets_Slideshow");
		register_widget("dsIDXWidgets_MapSearch");
		//register_widget("dsIDXWidgets_QuickSearch");
	}
}

?>