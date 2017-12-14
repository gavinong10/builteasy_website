<?php

	// If uninstall is not called from WordPress, exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}

	global $wpdb;
		
	$options = get_option('EG-Attachments-Options');
	if ( isset($options) && $options['uninstall_del_options']) {

		/*
		 * Remove options
		 */
		delete_option('EG-Attachments-Options');

		/*
		 * Remove templates
		 */
		$wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'egatmpl'");

		/*
		 * Remove transients / cache
		 */
		delete_transient('eg-attachments-templates');
		
		/*
		 * Remove all entries related to EG-Attachments, including transient
		 */
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name like '%eg-attachments_cache_%' ");
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name like '%eg-attachments-shortcode-tmpl%' ");
	}
	
	if ( isset($options) && $options['uninstall_del_stats']) {
		/*
		 * Remove stats table
		 */
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}eg_attachments_clicks");
	}
?>