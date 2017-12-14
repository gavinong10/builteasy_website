<?php
/**
 *
 *	Plugin Name: ImportBuddy
 *	Plugin URI: http://ithemes.com/
 *	Description: BackupBuddy Importer
 *	@since 1.0.2
 *	@author Dustin Bolton
 *
 *	Installation:
 * 
 *	1. Download and unzip the latest release zip file.
 *	2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 *	3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 *	4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 * 
 *	Usage:
 * 
 *	1. Navigate to the new plugin menu in the Wordpress Administration Panel.
 *
 *	NOTE: DO NOT EDIT THIS OR ANY OTHER PLUGIN FILES. NO USER-CONFIGURABLE OPTIONS WITHIN.
 */

error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE ); // HIGH
define( 'PB_STANDALONE', true );
define( 'PB_IMPORTBUDDY', true );

$pluginbuddy_settings = array(
				'slug'						=>		'backupbuddy',
				'php_minimum'				=>		'5.2',
				'series'					=>		'',
				'remote_api'				=>		'0', // Set to 1 by state for deployments.
				'default_state_overrides'	=>		array(), // Default state to override the main defaults. Good for automating imports. Applied over defaults during construction of restore class.
				'default_options'			=>		array(
														'bb_version'				=>	PB_BB_VERSION,	// BB version to be filled in on download.
														'backup_directory'			=>	'',
														'log_level'					=>	0, // No longer using this method for handling logging. status() method always logs all if importbuddy.
													),
				'modules'					=>		array(
														'updater'				=>	false,						// Load PluginBuddy automatic upgrades.
														'filesystem'			=>	true,						// File system helper methods.
														'format'				=>	true,						// Text / data formatting helper methods.
													)
			);



// $settings is expected to be populated prior to including PluginBuddy framework. Do not edit below.
require( dirname( __FILE__ ) . '/pluginbuddy/_pluginbuddy.php' );
