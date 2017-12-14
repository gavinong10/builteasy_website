<?php
/*
Plugin Name:    Aviators Utils
Description:    Settings system used in aviators themes
Version:        1.0.0
Author:         Aviators
Author URI:     http://byaviators.com
*/

require_once 'aviators_utils.to.php'; // theme options
require_once 'aviators_utils.wl.php'; // widget logic

add_action('init', 'aviators_utils_rewrite_rules');
function aviators_utils_rewrite_rules() {
    add_rewrite_rule('^aviators-utils/(.+)/?$', 'index.php?aviators-utils=true&export=$matches[1]', 'top');
    flush_rewrite_rules();
}


add_filter('query_vars', 'aviators_utils_add_query_vars');
function aviators_utils_add_query_vars($vars) {
    $vars[] = 'aviators-utils';
    $vars[] = 'export';
    return $vars;
}

add_action('template_redirect', 'aviators_utils_catch_template');
function aviators_utils_catch_template() {

    if (get_query_var('aviators-utils') && get_query_var('export')) {
        $export = get_query_var('export');
        if($export == 'theme-options') {
            $exporter = new AviatorsUtilsTOExport();
            $exporter->export();
        }

        if($export == 'widget-logic') {
            $exporter = new AviatorsUtilsWLExport();
            $exporter->export();
        }
        die;
    }
}


add_action('admin_menu', 'aviators_utils_wl_menu');
function aviators_utils_wl_menu() {
    add_management_page(__('Widget Logic Export', 'aviators'), __('Widget Logic Export', 'aviators'), AVIATORS_SETTINGS_CAPABALITY, 'widget-logic-export', 'aviators_utils_wl_export_page');
    add_management_page(__('Theme Options Export', 'aviators'), __('Theme Options Export', 'aviators'), AVIATORS_SETTINGS_CAPABALITY, 'theme-options-export', 'aviators_utils_to_export_page');
}
