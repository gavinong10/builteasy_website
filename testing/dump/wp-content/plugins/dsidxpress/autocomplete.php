<?php
add_action('init', array('dsidxpress_autocomplete', 'RegisterScripts'));

class dsidxpress_autocomplete {
	public static function RegisterScripts() {
		if (defined('DOING_CRON') && DOING_CRON)
			return;
		
		// register auto-complete script for use outside the plugin
		wp_register_script('dsidx-autocomplete', plugins_url('js/autocomplete.js', __FILE__), array('jquery-ui-autocomplete'), DSIDXPRESS_PLUGIN_VERSION, true);
	}
	
	public static function AddScripts($needs_plugin_url = false) {
		wp_enqueue_script('dsidx-autocomplete');
		
		if ($needs_plugin_url) {
			$home_url   = get_home_url();
			$plugin_url = dsSearchAgent_ApiRequest::MakePluginsUrlRelative(plugin_dir_url(__FILE__));
			
			echo <<<HTML
				<script type="text/javascript">
				if (typeof localdsidx == "undefined" || !localdsidx) { var localdsidx = {}; };
				localdsidx.pluginUrl = "{$plugin_url}";
				localdsidx.homeUrl = "{$home_url}";
				</script>
HTML;
		}
	}
}
