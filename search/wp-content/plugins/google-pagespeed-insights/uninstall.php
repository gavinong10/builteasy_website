<?php
// This file removes all Google Pagespeed Insights DB Tables, Options, and Scheduled tasks.
 
if(WP_UNINSTALL_PLUGIN){
global $wpdb;
$gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
$gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';
$gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';
 
$wpdb->query("DROP TABLE $gpi_page_stats");
$wpdb->query("DROP TABLE $gpi_page_reports");
$wpdb->query("DROP TABLE $gpi_page_blacklist");

delete_option('gpagespeedi_options');
delete_option('gpagespeedi_ui_options');

wp_clear_scheduled_hook('googlepagespeedinsightschecker');
remove_action('googlepagespeedinsightschecker', 'googlepagespeedinsightschecker');

remove_action('googlepagespeedinsightschecknow', 'googlepagespeedinsightsworker', 10, 3);

}
