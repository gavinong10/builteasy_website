<?php

add_action('init', array('dsIdxGlobals', 'enqueueGlobals'));
add_action('wp_print_scripts', array('dsIdxGlobals', 'fixGoogleMapsConflict'));

//Hooking in to wp_footer as a fallback. For some reason wp_print_footer_scripts doesn't pick this up
add_action('wp_footer', array('dsIdxGlobals', 'fixGoogleMapsConflict'));

class dsIdxGlobals {
	public static function enqueueGlobals(){
		if (defined('DOING_CRON') && DOING_CRON){
			return;
		}
		global $pagenow;
		$options = get_option(DSIDXPRESS_OPTION_NAME);

		if (!$options["Activated"])
			return;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("EnqueueGlobalAssets", array(), false, 3600);
		wp_enqueue_style('dsidxpress-icons', DSIDXPRESS_PLUGIN_URL . 'css/dsidx-icons.css');

		if( is_admin() ||  in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) ) ){
			return;
		}
		wp_enqueue_style('dsidxpress-unconditional', DSIDXPRESS_PLUGIN_URL . 'css/client.css');
		wp_enqueue_style('dsidxwidgets-unconditional', DSIDXWIDGETS_PLUGIN_URL . 'css/client.css');
	}

	public static function fixGoogleMapsConflict(){
		global $wp_scripts;
		foreach($wp_scripts->registered as $r){
			if($r->handle == DSIDXPRESS_MAPS_JS_HANDLE){
				continue;
			}
			if(preg_match('/maps.google.com/i', $r->src) || preg_match('/maps.googleapis.com/i', $r->src)){
				if(in_array($r->handle, $wp_scripts->queue) || in_array($r->handle, $wp_scripts->done)){
					wp_dequeue_script(DSIDXPRESS_MAPS_JS_HANDLE);
				}
			}
		}
	}
}