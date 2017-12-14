<?php
//To prevent conflicts, only one version of the plugin can be activated at any given time.
if ( defined('GPI_ACTIVE') ){
	trigger_error(
		__('Another version of Google PageSpeed Insights is already active. Please deactivate it before activating this one.', 'gpagespeedi'),
		E_USER_ERROR
	);
} else {
	
define('GPI_ACTIVE', true);

//Fail fast if the WP version is unsupported. The $wp_version variable may be obfuscated by other
//plugins, so use function detection to determine the version. wp_editor was introduced in WP 3.3
if ( !function_exists('wp_editor') ){
	trigger_error(
		__('Google PageSpeed Insights requires WordPress 3.3 or later!', 'gpagespeedi'),
		E_USER_ERROR
	);
}

if ( !function_exists('curl_init') ) {
	trigger_error(
		__('Google Pagespeed Insights requires the CURL PHP extension', 'gpagespeedi'),
		E_USER_ERROR
	);
}

if ( !function_exists('json_decode') ) {
	trigger_error(
		__('Google Pagespeed Insights requires the JSON PHP extension', 'gpagespeedi'),
		E_USER_ERROR
	);
}

if ( !function_exists('http_build_query') ) {
	trigger_error(
		__('Google Pagespeed Insights requires http_build_query()', 'gpagespeedi'),
		E_USER_ERROR
	);
}

/***********************************************
				  Get Options
************************************************/

global $gpi_options;
$gpi_options = get_option('gpagespeedi_options');

/***********************************************
			  Setup Cron Schedules
************************************************/

function gpi_cron_schedules($schedules){
	
	global $gpi_options;
 	if ( !isset($schedules['gpi_scheduled_interval']) ){
		$schedules['gpi_scheduled_interval'] = array(
	 		'interval' => $gpi_options['recheck_interval'],
	 		'display' => __('Interval set in GPI options', 'gpagespeedi')
	 	);
 	}

	$interval = $gpi_options['max_execution_time'] + 30;
 	if ( !isset($schedules['gpi_lastrun_checker']) ){
		$schedules['gpi_lastrun_checker'] = array(
	 		'interval' => $interval,
	 		'display' => __('GPI Last Run Checker', 'gpagespeedi')
	 	);
 	}

	return $schedules;

}
add_filter('cron_schedules', 'gpi_cron_schedules');

/***********************************************
		  Register our languages path
************************************************/

function gpi_register_languages_dir() {
 
    $lang_dir = 'google-pagespeed-insights/languages';
     
    load_plugin_textdomain('gpagespeedi', false, $lang_dir);
 
}
add_action('plugins_loaded', 'gpi_register_languages_dir');

/***********************************************
			Admin Pages & Functions
************************************************/

//Page under the "Tools" Menu
require GPI_DIRECTORY . '/core/admin.php';

/***********************************************
				Main functionality
************************************************/

//Execute the installation/upgrade script when the plugin is activated.
function gpi_activation_hook(){

	require GPI_DIRECTORY . '/includes/activation.php';

}
register_activation_hook(GPI_PLUGIN_FILE, 'gpi_activation_hook');

function gpi_admin_notice(){

	global $gpi_options;

	if($gpi_options['new_activation_message'] == false)
		return;
	?>
    <div id="message" class="updated">
        <p>Google Pagespeed Insights for Wordpress has been activated. It can be accessed via Tools -> <a href="<?php echo admin_url('/tools.php?page=google-pagespeed-insights&render=options'); ?>">Pagespeed Insights</a></p>
    </div>
    <?php

    $gpi_options['new_activation_message'] = false;
    update_option( 'gpagespeedi_options', $gpi_options );

}
add_action('admin_notices', 'gpi_admin_notice');

if (defined('DOING_CRON') && $gpi_options['google_developer_key'] != ''){
	require_once GPI_DIRECTORY . '/core/core.php';
	$googlePagespeedInsights = new googlePagespeedInsights($gpi_options);
}


}

