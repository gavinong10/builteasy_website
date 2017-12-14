<?php // This code runs whenever in the wp-admin. pb_backupbuddy::$options preloaded.



/********** MISC **********/



// Needed for retina icons in menu.
add_action( 'admin_enqueue_scripts', create_function( '',
	"wp_enqueue_style( 'pb_backupbuddy-wp-admin', '" . pb_backupbuddy::plugin_url() . "/css/wp-admin.css', array(), pb_backupbuddy::settings( 'version' ) );"
	)
);
global $wp_version;
if ( $wp_version >= 3.8 ) {
	add_action( 'admin_enqueue_scripts', create_function( '',
		"wp_enqueue_style( 'pb_backupbuddy-wp-admin-fonticon', '" . pb_backupbuddy::plugin_url() . "/css/wp-admin-fonticon.css', array(), pb_backupbuddy::settings( 'version' ) );"
		)
	);
}


// Enqueue styles for Dashboard Widget
function enqueue_dashboard_stylesheet($hook) {
	if( 'index.php' != $hook )
		return;
	wp_enqueue_style( 'bub_dashboard_widget', pb_backupbuddy::plugin_url() . '/css/dashboard_widget.css' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_dashboard_stylesheet' );




// Load backupbuddy class with helper functions.
if ( ! class_exists( 'backupbuddy_core' ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
}



/* BEGIN HANDLING DATA STRUCTURE UPGRADE */
$default_options = pb_backupbuddy::settings( 'default_options' );
if ( pb_backupbuddy::$options['data_version'] < $default_options['data_version'] ) {
	backupbuddy_core::verify_directories( $skipTempGeneration = true );
	pb_backupbuddy::status( 'details', 'Data structure version of `' . pb_backupbuddy::$options['data_version'] . '` behind current version of `' . $default_options['data_version'] . '`. Running activation upgrade.' );
	require_once( pb_backupbuddy::plugin_path() . '/controllers/activation.php' );
}
/* END HANDLING DATA STRUCTURE UPGRADE */



// Schedule daily housekeeping.
backupbuddy_core::verifyHousekeeping();



/********** ACTIONS (admin) **********/



// Set up reminders if enabled.
if ( pb_backupbuddy::$options['backup_reminders'] == '1' ) {
	pb_backupbuddy::add_action( array( 'load-update-core.php', 'wp_update_backup_reminder' ) );
	pb_backupbuddy::add_action( array( 'post_updated_messages', 'content_editor_backup_reminder_on_update' ) );
}

// Display warning to network activate if running in normal mode on a MultiSite Network.
if ( is_multisite() && !backupbuddy_core::is_network_activated() ) {
	pb_backupbuddy::add_action( array( 'all_admin_notices', 'multisite_network_warning' ) ); // BB should be network activated while on Multisite.
}



/********** AJAX (admin) **********/



pb_backupbuddy::add_ajax( 'backupbuddy' ); // New AJAX wrapper to begin passing all AJAX through this single call to reduce number of registered hooks. POST or GET the var function containing the function.php file to run within controllers/ajax.
//pb_backupbuddy::add_ajax( 'ajax_controller_callback_function' ); // Tell WordPress about this AJAX callback.

// Register BackupBuddy API. As of BackupBuddy v5.0. Access credentials will be checked within callback.
add_action( 'wp_ajax_backupbuddy_api', array( pb_backupbuddy::$_ajax, 'api' ) );
add_action( 'wp_ajax_nopriv_backupbuddy_api', array( pb_backupbuddy::$_ajax, 'api' ) );



/********** DASHBOARD (admin) **********/



// Display stats in Dashboard.
if ( ( !is_multisite() ) || ( is_multisite() && is_network_admin() ) ) { // Only show if standalon OR in main network admin.
	pb_backupbuddy::add_dashboard_widget( 'stats', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ), 'godmode' );
}



/********** FILTERS (admin) **********/



pb_backupbuddy::add_filter( 'plugin_row_meta', 10, 2 );



/********** PAGES (admin) **********/



$icon = '';

if ( is_multisite() && backupbuddy_core::is_network_activated() && !defined( 'PB_DEMO_MODE' ) ) { // Multisite installation.
	if ( defined( 'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT' ) && ( PB_BACKUPBUDDY_MULTISITE_EXPERIMENT == TRUE ) ) { // comparing with bool but loose so string is acceptable.
		
		if ( is_network_admin() ) { // Network Admin pages
			pb_backupbuddy::add_page( '', 'backup', array( pb_backupbuddy::settings( 'name' ), __( 'Backup', 'it-l10n-backupbuddy' ) ), 'manage_network', $icon );
			pb_backupbuddy::add_page( 'backup', 'migrate_restore', __( 'Migrate, Restore', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'destinations', __( 'Remote Destinations', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
			pb_backupbuddy::add_page( 'backup', 'multisite_import', __( 'MS Import (beta)', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'server_tools', __( 'Server Tools', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'scheduling', __( 'Schedules', 'it-l10n-backupbuddy' ), 'manage_network' );
			pb_backupbuddy::add_page( 'backup', 'settings', __( 'Settings', 'it-l10n-backupbuddy' ), 'manage_network' );
		} else { // Subsite pages.			
			$export_note = '';
			
			$options = get_site_option( 'pb_' . pb_backupbuddy::settings( 'slug' ) );
			$multisite_export = $options[ 'multisite_export' ];
			unset( $options );

			if ( $multisite_export == '1' ) { // Settings enable admins to export. Set capability to admin and higher only.
				$capability = pb_backupbuddy::$options['role_access'];
				$export_title = '<span title="Note: Enabled for both subsite Admins and Network Superadmins based on BackupBuddy settings">' . __( 'MS Export (experimental)', 'it-l10n-backupbuddy' ) . '</span>';
			} else { // Settings do NOT allow admins to export; set capability for superadmins only.
				$capability = 'manage_network';
				$export_title = '<span title="Note: Enabled for Network Superadmins only based on BackupBuddy settings">' . __( 'MS Export SA (experimental)', 'it-l10n-backupbuddy' ) . '</span>';
			}
			
			//pb_backupbuddy::add_page( '', 'getting_started', array( pb_backupbuddy::settings( 'name' ), 'Getting Started' . $export_note ), $capability );
			pb_backupbuddy::add_page( '', 'multisite_export', array( pb_backupbuddy::settings( 'name' ), $export_title ), $capability, $icon );
			pb_backupbuddy::add_page( 'multisite_export', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), $capability );
		}
		
	} else { // PB_BACKUPBUDDY_MULTISITE_EXPERIMENT not in wp-config / set to TRUE.
		pb_backupbuddy::status( 'error', 'Multisite detected but PB_BACKUPBUDDY_MULTISITE_EXPERIMENT definition not found in wp-config.php / not defined to boolean TRUE.' );
	}
	
} else { // Standalone site.
	
	pb_backupbuddy::add_page( '', 'backup', array( pb_backupbuddy::settings( 'name' ), __( 'Backup', 'it-l10n-backupbuddy' ) ), pb_backupbuddy::$options['role_access'], $icon );
	pb_backupbuddy::add_page( 'backup', 'migrate_restore', __( 'Restore / Migrate', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'destinations', __( 'Remote Destinations', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'server_tools', __( 'Server Tools', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'malware_scan', __( 'Malware Scan', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	pb_backupbuddy::add_page( 'backup', 'scheduling', __( 'Schedules', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	if ( defined( 'BACKUPBUDDY_DEV' ) && ( true === BACKUPBUDDY_DEV ) ) {
		pb_backupbuddy::add_page( 'backup', 'live', __( 'Live', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
	}
	pb_backupbuddy::add_page( 'backup', 'settings', __( 'Settings', 'it-l10n-backupbuddy' ), pb_backupbuddy::$options['role_access'] );
}



/********** OTHER (admin) **********/
add_filter( 'contextual_help', 'pb_backupbuddy_contextual_help', 10, 3 );
function pb_backupbuddy_contextual_help( $contextual_help, $screen_id, $screen ) { // Loads help from file in controllers/help/:PAGENAME:.php
	
	// WordPress pre-v3.3 so no contextual help.
	if ( ! method_exists( $screen, 'add_help_tab' ) ) {
		return $contextual_help;
	}
	
	// Not a backupbuddy page.
	if ( false === stristr( $screen_id, 'backupbuddy' ) ) {
		return $contextual_help;
	}
	
	// Load page-specific help.
	$page = str_replace( 'pb_backupbuddy_', '', str_replace( 'toplevel_page_', '', str_replace( 'backupbuddy_page_pb_backupbuddy_', '', $screen_id ) ) );
	$helpFile = dirname( __FILE__ ) . '/controllers/help/' . $page . '.php';
	if ( file_exists( $helpFile ) ) {
		include( $helpFile );
	}
	
	// Global help.
	$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_additionalhelp',
	'title'   => __( 'Tutorials & Support', 'it-l10n-backupbuddy' ),
	'content' => '<p>
					<a href="http://ithemes.com/publishing/getting-started-with-backupbuddy/" target="_blank">' . __( 'Getting Started eBook', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.tv/category/backupbuddy/" target="_blank">' . __( 'Getting Started Videos', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.com/codex/" target="_blank">' . __( 'Knowledge Base & Tutorials', 'it-l10n-backupbuddy' ) . '</a>
					<br>
					<a href="http://ithemes.com/support/" target="_blank"><b>' . __( 'Support', 'it-l10n-backupbuddy' ) . '</b></a>
				</p>',
	));
	
	return $contextual_help;
	
} // End pb_backupbuddy_contextual_help().
