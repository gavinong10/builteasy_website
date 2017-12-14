<?php
// Tutorial
pb_backupbuddy::load_script( 'jquery.joyride-2.0.3.js' );
pb_backupbuddy::load_script( 'modernizr.mq.js' );
pb_backupbuddy::load_style( 'joyride.css' );



if ( !defined( 'PB_IMPORTBUDDY' ) ) { // NOT IN IMPORTBUDDY:
	wp_enqueue_script( 'thickbox' );
	wp_print_scripts( 'thickbox' );
	wp_print_styles( 'thickbox' );
	?>
	<style type="text/css">
	#backupbuddy-meta-link-wrap a.show-settings {
		float: right;
		margin: 0 0 0 6px;
	}
	#screen-meta-links #backupbuddy-meta-link-wrap a {
		background: none;
	}
	#screen-meta-links #backupbuddy-meta-link-wrap a:after {
		content: '';
		margin-right: 5px;
	}
	</style>
	<script type="text/javascript">
	jQuery(document).ready( function() {
		jQuery('#screen-meta-links').append(
			'<div id="backupbuddy-meta-link-wrap" class="hide-if-no-js screen-meta-toggle">' +
				'<a href="" class="show-settings pb_backupbuddy_begintour"><?php _e( "Tour Page", "it-l10n-backupbuddy" ); ?></a>' +
			'</div>'
		);
	});
	</script>
	<ol id="pb_backupbuddy_tour" style="display: none;">
		<li data-class="nav-tab-0">View server configuration details, security information, server paths, etc.</li>
		<li data-class="nav-tab-1">View database information as well as tables excluded from backups.</li>
		<li data-class="nav-tab-2">View your site's files in either a graphical format or a listing. The listing also notes exclusions from backups.</li>
		<li data-class="nav-tab-3" data-button="Finish">Additional site tools for managing CRON schedules and database text search & replace.</li>
	</ol>
	<script>
	jQuery(window).load(function() {
		jQuery(document).on( 'click', '.pb_backupbuddy_begintour', function(e) {
			jQuery("#pb_backupbuddy_tour").joyride({
				tipLocation: 'top',
			});
			return false;
		});
	});
	</script>

	<?php

	pb_backupbuddy::load_script( 'admin.js' );
	
	
	
	pb_backupbuddy::$ui->title( __( 'Server Tools', 'it-l10n-backupbuddy' ) );
	backupbuddy_core::versions_confirm();
	
	$default_tab = 0;
	if ( is_numeric( pb_backupbuddy::_GET( 'tab' ) ) ) {
		$default_tab = pb_backupbuddy::_GET( 'tab' );
	}
	
	pb_backupbuddy::$ui->start_tabs(
		'getting_started',
		array(
			array(
				'title'		=>		__( 'Server', 'it-l10n-backupbuddy' ),
				'slug'		=>		'server',
			),
			array(
				'title'		=>		__( 'Database', 'it-l10n-backupbuddy' ),
				'slug'		=>		'database',
			),
			array(
				'title'		=>		__( 'Site Size Maps', 'it-l10n-backupbuddy' ),
				'slug'		=>		'files',
			),
			array(
				'title'		=>		__( 'WordPress Schedules (Cron)', 'it-l10n-backupbuddy' ),
				'slug'		=>		'cron',
			),
		),
		'width: 100%;',
		true,
		$default_tab
	);
	
	
	
	pb_backupbuddy::$ui->start_tab( 'server' );
		
		require_once( 'server_info/server.php' );
		
		
		require_once( 'server_info/permissions.php' );
		
		
		$wp_upload_dir = wp_upload_dir();
		$wp_settings = array();
		
		if ( isset( $wp_upload_dir['path'] ) ) {
			$wp_settings[] = array( 'Upload File Path', $wp_upload_dir['path'], 'wp_upload_dir()' );
		}
		if ( isset( $wp_upload_dir['url'] ) ) {
			$wp_settings[] = array( 'Upload URL', $wp_upload_dir['url'], 'wp_upload_dir()' );
		}
		if ( isset( $wp_upload_dir['subdir'] ) ) {
			$wp_settings[] = array( 'Upload Subdirectory', $wp_upload_dir['subdir'], 'wp_upload_dir()');
		}
		if ( isset( $wp_upload_dir['baseurl'] ) ) {
			$wp_settings[] = array( 'Upload Base URL', $wp_upload_dir['baseurl'], 'wp_upload_dir()' );
		}
		if ( isset( $wp_upload_dir['basedir'] ) ) {
			$wp_settings[] = array( 'Upload Base Directory', $wp_upload_dir['basedir'], 'wp_upload_dir()' );
		}
		$wp_settings[] = array( 'Site URL', site_url(), 'site_url()' );
		$wp_settings[] = array( 'Home URL', home_url(), 'home_url()' );
		$wp_settings[] = array( 'WordPress Root Path', ABSPATH, 'ABSPATH' );
		
		// Multisite extras:
		$wp_settings_multisite = array();
		if ( is_multisite() ) {
			$wp_settings[] = array( 'Network Site URL', network_site_url(), 'network_site_url()' );
			$wp_settings[] = array( 'Network Home URL', network_home_url(), 'network_home_url()' );
		}
		
		$wp_settings[] = array( 'BackupBuddy local storage', backupbuddy_core::getBackupDirectory(), 'BackupBuddy Settings' );
		$wp_settings[] = array( 'BackupBuddy temporary files', backupbuddy_core::getTempDirectory(), 'ABSPATH + Hardcoded location' );
		$wp_settings[] = array( 'BackupBuddy logs', backupbuddy_core::getLogDirectory(), 'Upload Base + BackupBuddy' );
		
		// Display WP settings..
		pb_backupbuddy::$ui->list_table(
			$wp_settings,
			array(
				'action'					=>	pb_backupbuddy::page_url(),
				'columns'					=>	array(
													__( 'URLs & Paths', 'it-l10n-backupbuddy' ),
													__( 'Value', 'it-l10n-backupbuddy' ),
													__( 'Obtained via', 'it-l10n-backupbuddy' ),
												),
				'css'						=>		'width: 100%;',
			)
		);
		
		
	pb_backupbuddy::$ui->end_tab();
	
	
	
	// This page can take a bit to run.
	// Runs AFTER server information is displayed so we can view the default limits for the server.
	pb_backupbuddy::set_greedy_script_limits();
	
	
	
	pb_backupbuddy::$ui->start_tab( 'database' );
		
		require_once( 'server_info/database.php' );
		echo '<br><br><a name="database_replace"></a>';
		echo '<div class="pb_htitle">' . 'Advanced: ' . __( 'Database Mass Text Replacement', 'it-l10n-backupbuddy' ) . '</div><br>';
		pb_backupbuddy::load_view( '_server_tools-database_replace' );
		
	pb_backupbuddy::$ui->end_tab();
	
	
	
	pb_backupbuddy::$ui->start_tab( 'files' );
		
		require_once( 'server_info/site_size.php' );
		
	pb_backupbuddy::$ui->end_tab();
	
	
	
	pb_backupbuddy::$ui->start_tab( 'cron' );
		
		require_once( 'server_info/cron.php' );
		
	pb_backupbuddy::$ui->end_tab();
	
	
	echo '<br style="clear: both;"><br><br>';
	pb_backupbuddy::$ui->end_tabs();
	
	
	
	// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
	if ( !wp_script_is( 'media-upload' ) ) {
		wp_enqueue_script( 'media-upload' );
		wp_print_scripts( 'media-upload' );
	}
	
} else { // INSIDE IMPORTBUDDY:
	if ( pb_backupbuddy::_GET( 'skip_serverinfo' ) == '' ) { // Give a workaround to skip this.
		require_once( 'server_info/server.php' );
	} else {
		echo '{Skipping Server Info. section based on querystring.}';
	}
}
?>