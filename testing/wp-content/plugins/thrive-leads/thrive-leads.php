<?php

/*
Plugin Name: Thrive Leads
Plugin URI: https://thrivethemes.com
Version: 1.95.6
Author: <a href="https://thrivethemes.com">Thrive Themes</a>
Description: The ultimate lead capture solution for Wordpress
Text Domain: thrive-leads
*/

/* the base URL for the plugin */
define( 'TVE_LEADS_URL', str_replace( array(
	'http://',
	'https://'
), '//', plugin_dir_url( __FILE__ ) ) );



/**
 * bootstrap everything
 */
require_once plugin_dir_path( __FILE__ ) . 'start.php';

/* admin entry point */
if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/start.php';
}
