<?php

if (!function_exists('mad_ajax_reset_options')) {

	function mad_ajax_reset_options() {

		if (function_exists('check_ajax_referer')) {
			check_ajax_referer('ajax_reset_options');
		}

		global $mad_global_data;
		delete_option($mad_global_data->option_prefix);
		wp_die('reset');
	}

	add_action('wp_ajax_ajax_reset_options', 'mad_ajax_reset_options');
}

/*  Ajax Import Data Hook
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_prepare_save_options_array')) {

	function mad_prepare_save_options_array($data) {
		$result = array();

		foreach ($data as $option) {
			$option = explode("=", $option);
			$option[1] = htmlentities(urldecode(stripslashes($option[1])), ENT_COMPAT, get_bloginfo('charset'));
			if ($option[0] != "" && $option[0] != 'undefined') {
				$result[$option[0]] = $option[1];
			}
		}
		return $result;
	}
}

if (!function_exists('mad_ajax_save_options_page')) {

	function mad_ajax_save_options_page() {

		if (function_exists('check_ajax_referer')) {
			check_ajax_referer('ajax_save_options_page');
		}

		if (!isset($_REQUEST['data']) || !isset($_REQUEST['slug']) || !isset($_REQUEST['prefix'])) { return; }

		$data = explode("&", $_REQUEST['data']);

		$prefix = $_REQUEST['prefix'];
		$options = get_option($prefix);
		$save = mad_prepare_save_options_array($data);
		$options[$_REQUEST['slug']] = $save;

		update_option($prefix, $options);
		do_action('mad_ajax_after_save_options_page', $options);

		wp_die('save');
	}
	add_action('wp_ajax_ajax_save_options_page', 'mad_ajax_save_options_page');
}

/*  Ajax Import Data Hook
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_ajax_import_options_page')) {

	function mad_ajax_import_options_page() {
		if (function_exists('check_ajax_referer')) {
			check_ajax_referer('ajax_import_options_page');
		}
		require_once('config-import-export/inc-importer.php');
		wp_die('madImport');
	}
	add_action('wp_ajax_ajax_import_options_page', 'mad_ajax_import_options_page');
}

/*  Ajax Import Config Options Hook
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_ajax_import_config_options')) {

	function mad_ajax_import_config_options() {

		if (function_exists('check_ajax_referer')) {
			check_ajax_referer('ajax_import_config_options', '_wpnonce');
		}

		require_once('config-import-export/import-options.php');

		$file = $_POST['href'];

		if ( function_exists( 'file_get_contents' ) && $file != '' ) {
			$options = unserialize(base64_decode(file_get_contents( $file )));

			global $mad_global_data;

			$wp_import_options = new mad_wp_import_options();

			if (is_array($options)) {
				foreach($mad_global_data->option_pages as $page) {
					$database_option[$page['parent']] = $wp_import_options->import_values($options[$page['parent']]);
				}
			}

			if (!empty($database_option)) {
				update_option($mad_global_data->option_prefix, $database_option);
			}

			wp_die('madImportConfig');
		}
	}
	add_action('wp_ajax_ajax_import_config_options', 'mad_ajax_import_config_options');
}