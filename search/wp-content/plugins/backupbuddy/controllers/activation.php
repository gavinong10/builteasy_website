<?php
// Any code in this file will be run upon plugin activation. NOTHING should be echo here or it will break activation.
// TODO: Set up proper data structure migration based on the structure version. This is a temporary approach. Sorry.

if ( ( ! isset( pb_backupbuddy::$options ) ) || empty( pb_backupbuddy::$options ) ) { // Make sure options are loaded if possible.
	pb_backupbuddy::load();
}



// ********** BEGIN 1.x -> 2.x DATA MIGRATION **********

$upgrade_options = get_option( 'ithemes-backupbuddy' ); // 1.x data structure storage location.
if ( $upgrade_options != false ) {
	pb_backupbuddy::$options = $upgrade_options;
	
	pb_backupbuddy::$options['email_notify_error'] = pb_backupbuddy::$options['email'];
	if ( pb_backupbuddy::$options['email_notify_manual'] == 1 ) {
		pb_backupbuddy::$options['email_notify_manual'] = pb_backupbuddy::$options['email'];
	}
	if ( pb_backupbuddy::$options['email_notify_scheduled'] == 1 ) {
		pb_backupbuddy::$options['email_notify_scheduled'] = pb_backupbuddy::$options['email'];
	}
	unset( pb_backupbuddy::$options['email'] );
	
	pb_backupbuddy::$options['archive_limit'] = pb_backupbuddy::$options['zip_limit'];
	unset( pb_backupbuddy::$options['zip_limit'] );
	
	pb_backupbuddy::$options['import_password'] = pb_backupbuddy::$options['password'];
	if ( pb_backupbuddy::$options['import_password'] == '#PASSWORD#' ) {
		pb_backupbuddy::$options['import_password'] = '';
	}
	unset( pb_backupbuddy::$options['password'] );
	
	if ( is_array( pb_backupbuddy::$options['excludes'] ) ) {
		pb_backupbuddy::$options['excludes'] = implode( "\n", pb_backupbuddy::$options['excludes'] );
	}
	
	pb_backupbuddy::$options['last_backup'] = pb_backupbuddy::$options['last_run'];
	unset( pb_backupbuddy::$options['last_run'] );
	
	// FTP.
	if ( !empty( pb_backupbuddy::$options['ftp_server'] ) ) {
		pb_backupbuddy::$options['remote_destinations'][0] = array(
														'title'			=>		'FTP',
														'address'		=>		pb_backupbuddy::$options['ftp_server'],
														'username'		=>		pb_backupbuddy::$options['ftp_user'],
														'password'		=>		pb_backupbuddy::$options['ftp_pass'],
														'path'			=>		pb_backupbuddy::$options['ftp_path'],
														'type'			=>		'ftp',
													);
		if ( pb_backupbuddy::$options['ftp_type'] == 'ftp' ) {
			pb_backupbuddy::$options['remote_destinations'][0]['ftps'] = 0;
		} else {
			pb_backupbuddy::$options['remote_destinations'][0]['ftps'] = 1;
		}
	}
	
	// Amazon S3.
	if ( !empty( pb_backupbuddy::$options['aws_bucket'] ) ) {
		pb_backupbuddy::$options['remote_destinations'][1] = array(
														'title'			=>		'S3',
														'accesskey'		=>		pb_backupbuddy::$options['aws_accesskey'],
														'secretkey'		=>		pb_backupbuddy::$options['aws_secretkey'],
														'bucket'		=>		pb_backupbuddy::$options['aws_bucket'],
														'directory'		=>		pb_backupbuddy::$options['aws_directory'],
														'ssl'			=>		pb_backupbuddy::$options['aws_ssl'],
														'type'			=>		's3',
													);
	}
	
	// Email destination.
	if ( !empty( pb_backupbuddy::$options['email'] ) ) {
		pb_backupbuddy::$options['remote_destinations'][2] = array(
														'title'			=>		'Email',
														'email'			=>		pb_backupbuddy::$options['email'],
													);
	}
	
	// Handle migrating scheduled remote destinations.
	foreach( pb_backupbuddy::$options['schedules'] as $schedule_id => $schedule ) {
		pb_backupbuddy::$options['schedules'][$schedule_id]['title'] = pb_backupbuddy::$options['schedules'][$schedule_id]['name'];
		unset( pb_backupbuddy::$options['schedules'][$schedule_id]['name'] );
		
		pb_backupbuddy::$options['schedules'][$schedule_id]['remote_destinations'] = '';
		if ( $schedule['remote_send'] == 'ftp' ) {
			pb_backupbuddy::$options['schedules'][$schedule_id]['remote_destinations'] .= '0|';
		}
		if ( $schedule['remote_send'] == 'aws' ) {
			pb_backupbuddy::$options['schedules'][$schedule_id]['remote_destinations'] .= '1|';
		}
		if ( $schedule['remote_send'] == 'email' ) {
			pb_backupbuddy::$options['schedules'][$schedule_id]['remote_destinations'] .= '2|';
		}
	}
	
	delete_option( 'ithemes-backupbuddy' );
}
unset( $upgrade_options );
pb_backupbuddy::save();

$old_log_file = WP_CONTENT_DIR . '/uploads/backupbuddy.txt';
if ( file_exists( $old_log_file ) ) {
	@unlink( $old_log_file );
}

// ********** END 1.x -> 2.x DATA MIGRATION **********






// ********** BEGIN 2.x -> 3.x DATA MIGRATION **********

// Attempt to get 2.x options.
$options = get_site_option( 'pluginbuddy_backupbuddy' );
//Try to read site-specific settings in
if ( is_multisite() ) {
	$multisite_option = get_option( 'pluginbuddy_backupbuddy' );
	if ( $multisite_option ) {
		$options = $multisite_option;
	}
	unset( $multisite_option );
}

// If options is not false then we need to upgrade.
if ( $options !== false ) {
	pb_backupbuddy::$options = array_merge( (array)pb_backupbuddy::settings( 'default_options' ), (array)$options ); // Merge defaults.
	unset( $options );
	
	if ( isset( pb_backupbuddy::$options['temporary_options']['experimental_zip'] ) ) {
		pb_backupbuddy::$options['alternative_zip'] = pb_backupbuddy::$options['temporary_options']['experimental_zip'];
	}
	
	if ( isset( pb_backupbuddy::$options['import_password'] ) ) { // Migrate import password to just hash.
		pb_backupbuddy::$options['importbuddy_pass_length'] = strlen( pb_backupbuddy::$options['import_password'] );
		pb_backupbuddy::$options['importbuddy_pass_hash'] = md5( pb_backupbuddy::$options['import_password'] );
		unset( pb_backupbuddy::$options['import_password'] );
	}
	
	// Migrate email_notify_scheduled -> email_notify_scheduled_complete
	pb_backupbuddy::$options['email_notify_scheduled_complete'] = pb_backupbuddy::$options['email_notify_scheduled'];
	
	// Migrate log file.
	$old_log_file = ABSPATH . '/wp-content/uploads/pluginbuddy_backupbuddy' . '-' . pb_backupbuddy::$options['log_serial'] . '.txt';
	if ( @file_exists ( $old_log_file ) ) {
		$new_log_file = WP_CONTENT_DIR . '/uploads/pb_' . pb_backupbuddy::settings( 'slug' ) . '/log-' . pb_backupbuddy::$options['log_serial'] . '.txt';
		@copy( $old_log_file, $new_log_file );
		if ( file_exists( $new_log_file ) ) { // If new log exists then we can delete the old.
			@unlink( $old_log_file );
		}
	}
	
	delete_option( 'pluginbuddy_backupbuddy' ); // Remove 2.x options.
	delete_site_option( 'pluginbuddy_backupbuddy' ); // Remove 2.x options.
	
	pb_backupbuddy::$options['data_version'] = '3'; // Update data structure version to 3.
	pb_backupbuddy::save(); // Save 3.0 options.
}

unset( $options );

// ********** END 2.x -> 3.x DATA MIGRATION **********



// ********** BEGIN 3.0.43 -> 3.1 DATA MIGRATION **********
$needs_saving = false;
foreach( pb_backupbuddy::$options['remote_destinations'] as $destination ) {
		if ( $destination['type'] == 'email' ) {
			if ( ( ! isset( $destination['address'] ) ) || ( $destination['address'] == '' ) ) { // If address not set OR blank.
				$destination['address'] = $destination['email'];
				unset( $destination['email'] );
				$needs_saving = true;
			}
		}
}
if ( $needs_saving === true ) {
	pb_backupbuddy::save();
}
unset( $needs_saving );
// ********** END 3.0.43 -> 3.1 DATA MIGRATION **********



// ********** BEGIN 3.1.8.2 -> 3.1.8.3 DATA MIGRATION **********
if ( pb_backupbuddy::$options['data_version'] < 4 ) {
	pb_backupbuddy::$options['data_version'] = '4'; // Update data structure version to 4.
	pb_backupbuddy::$options['role_access'] = 'activate_plugins'; // Change default role from `administrator` to `activate_plugins` capability.
	pb_backupbuddy::save();
}
// ********** END 3.1.8.2 -> 3.1.8.3 DATA MIGRATION **********



// ********** BEGIN 3.3.0 -> 3.3.0.1 BACKUP DATASTRUCTURE OPTIONS to FILEOPTIONS MIGRATION **********
if ( pb_backupbuddy::$options['data_version'] < 5 ) {
	if ( isset( pb_backupbuddy::$options['backups'] ) && ( count( pb_backupbuddy::$options['backups'] ) > 0 ) ) {
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getLogDirectory() . 'fileoptions/' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		foreach( pb_backupbuddy::$options['backups'] as $serial => $backup ) {
			pb_backupbuddy::status( 'details', 'Fileoptions instance #31.' );
			$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = false, $ignore_lock = false, $create_file = true );
			$backup_options->options = $backup;
			if ( true === $backup_options->save() ) {
				unset( pb_backupbuddy::$options['backups'][$serial] );
			}
			unset( $backup_options );
		}
	}
	pb_backupbuddy::$options['data_version'] = '5';
	pb_backupbuddy::save();
}
// ********** END 3.3.0 -> 3.3.0.1 BACKUP DATASTRUCTURE OPTIONS to FILEOPTIONS MIGRATION **********





// ********** BEGIN 4.0 UPGRADE **********
if ( pb_backupbuddy::$options['data_version'] < 6 ) {
	// Migrate profile-specific settings into 'Defaults' key profile.
	pb_backupbuddy::$options['profiles'][0]['skip_database_dump'] = pb_backupbuddy::$options['skip_database_dump'];
	unset( pb_backupbuddy::$options['skip_database_dump'] );
	pb_backupbuddy::$options['profiles'][0]['backup_nonwp_tables'] = pb_backupbuddy::$options['backup_nonwp_tables'];
	unset( pb_backupbuddy::$options['backup_nonwp_tables'] );
	pb_backupbuddy::$options['profiles'][0]['integrity_check'] = pb_backupbuddy::$options['integrity_check'];
	unset( pb_backupbuddy::$options['integrity_check'] );

	// Unset repairbuddy pass stuff as it now just uses same as importbuddy.
	if ( isset( pb_backupbuddy::$options['repairbuddy_pass_hash'] ) ) {
		unset( pb_backupbuddy::$options['repairbuddy_pass_hash'] );
	}
	if ( isset( pb_backupbuddy::$options['repairbuddy_pass_length'] ) ) {
		unset( pb_backupbuddy::$options['repairbuddy_pass_length'] );
	}

	// Changing some names.
	pb_backupbuddy::$options['last_backup_start'] = pb_backupbuddy::$options['last_backup'];
	pb_backupbuddy::$options['last_backup_finish'] = pb_backupbuddy::$options['last_backup'];
	unset( pb_backupbuddy::$options['last_backup'] );

	// Existing chedules need profiles assigned.
	foreach( pb_backupbuddy::$options['schedules'] as &$schedule ) {
		if ( !isset( $schedule['profile'] ) || ( $schedule['profile'] == '' ) ) { // No profile set.
			if ( $schedule['type'] == 'db' ) {
				$schedule['profile'] = '1';
			}
			if ( $schedule['type'] == 'full' ) {
				$schedule['profile'] = '2';
			}
			unset( $schedule['type'] );
		}
	}
	
	pb_backupbuddy::$options['data_version'] = '6';
	pb_backupbuddy::save();
}
if ( pb_backupbuddy::$options['data_version'] < 7 ) {
	pb_backupbuddy::$options['data_version'] = '7';
	if ( isset( pb_backupbuddy::$options['excludes'] ) ) {
		pb_backupbuddy::$options['profiles'][0]['excludes'] = pb_backupbuddy::$options['excludes'];
	}
	unset( pb_backupbuddy::$options['excludes'] );
	pb_backupbuddy::save();
}
// ********** END 4.0 UPGRADE **********





// ********** BEGIN 4.2 UPGRADE **********
if ( pb_backupbuddy::$options['data_version'] < 8 ) {
	pb_backupbuddy::$options['data_version'] = '8';
	
	// Update backup dir.
	$default_backup_dir = ABSPATH . 'wp-content/uploads/backupbuddy_backups/';
	if ( pb_backupbuddy::$options['backup_directory'] == $default_backup_dir ) { // If backup dir is in the default location, set blank.
		pb_backupbuddy::$options['backup_directory'] = '';
	}
	
	// Update temp dir.
	pb_backupbuddy::$options['temp_directory'] = ''; // Default blank. This is currently always hard-coded relative to site root.
	
	// Update log dir.
	$uploads_dirs = wp_upload_dir();
	$new_default_log_dir = $uploads_dirs['basedir'] . '/pb_backupbuddy/';
	if ( pb_backupbuddy::$options['log_directory'] == $new_default_log_dir ) { // If log dir is in the new default location, set blank.
		pb_backupbuddy::$options['log_directory'] = '';
	}
	unset( $uploads_dirs );
	unset( $new_default_log_dir );
	
	pb_backupbuddy::save();
}
// ********** END 4.2 UPGRADE **********



// ********** BEGIN 4.2.14.22 UPGRADE **********
if ( isset( pb_backupbuddy::$options['rollback_beta'] ) ) {
	unset( pb_backupbuddy::$options['rollback_beta'] );
	pb_backupbuddy::save();
}
// ********** END 4.2.14.22 UPGRADE **********



// ********** BEGIN 6.4 UPGRADE -- migrates stash v1 to stash v2 type, disabling v1 destination **********
if ( pb_backupbuddy::$options['data_version'] < 9 ) {
	pb_backupbuddy::$options['data_version'] = '9';
	pb_backupbuddy::save();
	$phpmin_file = pb_backupbuddy::plugin_path() . '/destinations/stash2/_phpmin.php';
	if ( ( count( pb_backupbuddy::$options['remote_destinations'] ) > 0 ) && file_exists( $phpmin_file ) ) { // only need to do this if we have destinations saved.
		$php_minimum = file_get_contents( $phpmin_file );
		if ( version_compare( PHP_VERSION, $php_minimum, '>=' ) ) { // Server's PHP is sufficient.
			$nextDestKey = max( array_keys( pb_backupbuddy::$options['remote_destinations'] ) ) + 1;
			
			// Find existing Stash v1 destinations.
			foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $v1_dest ) {
				if ( 'stash' != $v1_dest['type'] ) {
					continue;
				}
				
				// Calculate new token.
				require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
				require_once( pb_backupbuddy::plugin_path() . '/destinations/stash2/init.php' );
				require_once( pb_backupbuddy::plugin_path() . '/destinations/stash2/class.itx_helper2.php' );
				global $wp_version;
				
				$itxapi_username = strtolower( $v1_dest['itxapi_username'] );
				$password_hash = $v1_dest['itxapi_password'];
				$access_token = ITXAPI_Helper2::get_access_token( $itxapi_username, $password_hash, site_url(), $wp_version );
				
				$settings = array(
					'itxapi_username' => $itxapi_username,
					'itxapi_password' => $access_token,
				);
				$response = pb_backupbuddy_destination_stash2::stashAPI( $settings, 'connect' );
				
				if ( is_array( $response ) ) { // Good so far.
					if ( ! isset( $response['error'] ) ) { // No error.
						if ( isset( $response['token'] ) ) {
							$itxapi_token = $response['token'];
						}
					}
				}
				
				if ( isset( $itxapi_token ) ) {
					// Build new Stash2 destination over old v1 destination based on old settings.
					$v2_dest = array(
						'type'						=>		'stash2',
						'title'						=>		$v1_dest['title'] . ' (v2)',
						'itxapi_username'			=>		$itxapi_username,
						'itxapi_token'				=>		$itxapi_token,
						'ssl'						=>		$v1_dest['ssl'],
						'db_archive_limit'			=>		$v1_dest['db_archive_limit'],
						'full_archive_limit' 		=>		$v1_dest['full_archive_limit'],
						'files_archive_limit' 		=>		$v1_dest['files_archive_limit'],
						'manage_all_files'			=>		$v1_dest['manage_all_files'],
						'use_packaged_cert'			=>		$v1_dest['use_packaged_cert'],
						'disable_file_management'	=>		$v1_dest['disable_file_management'],
						'disabled'					=>		$v1_dest['disabled'],
					);
					$v2_dest = array_merge( pb_backupbuddy_destination_stash2::$default_settings, $v2_dest ); // Merge over defaults.
				}
				
				$v2_dest['disabled'] = '0';
				if ( true === pb_backupbuddy_destination_stash2::test( $v2_dest ) ) {
					$v2_dest['disabled'] = $v1_dest['disabled'];
					pb_backupbuddy::$options['remote_destinations'][ $destination_id ] = $v2_dest; // New stash2 replacing stash1 settings.
					$v1_dest['disabled'] = '1'; // Disable old Stash v1 destination at new index.
					$v1_dest['title'] = $v1_dest['title'] . ' (v1)';
					pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ] = $v1_dest; // New location for old stash1 settings.
					$nextDestKey++;
				}
			} // end foreach destination.
		
		} // Sufficient PHPversion.
	}
	pb_backupbuddy::save();
}
// ********** END 6.4 UPGRADE **********



// ********** BEGIN 6.4.0.12 UPGRADE **********
if ( pb_backupbuddy::$options['data_version'] < 11 ) {
	pb_backupbuddy::$options['data_version'] = '11';
	pb_backupbuddy::save();
	
	$crons = get_option('cron');
	if ( is_array( $crons ) ) {
		$removeCrons = array(
			'pb_backupbuddy_corn',
			'pb_backupbuddy_cron',
		);
		foreach( $removeCrons as $hook ) {
			foreach( $crons as $timestamp => &$cron ) {
				if ( isset( $cron[ $hook ] ) ) {
					unset( $cron[ $hook ] );
				}
				if ( empty( $cron ) ) {
					unset( $crons[ $timestamp ] );
				}
			}
		}
	}
	update_option( 'cron', $crons );
}
// ********** END 6.4.0.12 UPGRADE **********



// ********** BEGIN 6.4.0.13 UPGRADE **********
require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
backupbuddy_housekeeping::remove_wp_schedules_with_no_bb_schedule(); // Handles upgrading schedule tags, housekeeping tag, and removal of faulty old tags.
// ********** END 6.4.0.13 UPGRADE **********


// ********** BEGIN 6.4.0.21 UPGRADE **********
pb_backupbuddy::$options['data_version'] = '13';
pb_backupbuddy::save();
// ********** END 6.4.0.21 UPGRADE **********



// ***** MISC BELOW *****



// Remote any saved plaintext confirmation of importbuddy password.
if ( isset( pb_backupbuddy::$options['importbuddy_pass_hash_confirm'] ) ) {
	unset( pb_backupbuddy::$options['importbuddy_pass_hash_confirm'] );
	pb_backupbuddy::save();
}




// MISC SETUP:

// Set up default error email notification email address if none is set.
if ( pb_backupbuddy::$options['email_notify_error'] == '' ) {
	pb_backupbuddy::$options['email_notify_error'] = get_option( 'admin_email' );
	pb_backupbuddy::save();
}

// Migrate a previous zip Force Compatibility option setting to the new Zip Method Strategy option setting
// Leave Force Compatibility option alone for now in case site is downgraded
// If the zip method strategy is not already set non-zero then set to Force Compatibility if that option
// is already set otherwise set to Best Only
if ( '0' === pb_backupbuddy::$options[ 'zip_method_strategy' ] ) {
	if ( isset( pb_backupbuddy::$options[ 'force_compatibility' ] ) && ( '1' === pb_backupbuddy::$options[ 'force_compatibility' ] ) ) {
		pb_backupbuddy::$options[ 'zip_method_strategy' ] = '3';
	} else {
		pb_backupbuddy::$options[ 'zip_method_strategy' ] = '1';
	}	
	pb_backupbuddy::save();
}



backupbuddy_core::verifyHousekeeping();



// Verify existance of default S3 config (currently blank to fix shell_exec() warning issue. Added 3.1.8.3 Jan 29, 2013 - Dustin.
$s3_config = pb_backupbuddy::plugin_path() . '/destinations/_s3lib/aws-sdk/config.inc.php';
if ( ! @file_exists( $s3_config ) ) {
	if ( true === @touch( $s3_config ) ) {
		// Be silent as to not risk breaking activation as this is minor. Just in case of logging issues.
		//pb_backupbuddy::status( 'details', 'Created default blank destination config `' . $s3_config . '`.' );
	} else {
		// Be silent as to not risk breaking activation as this is minor. Just in case of logging issues.
		//pb_backupbuddy::status( 'error', 'Unable to create default blank destination config `' . $s3_config . '`. Check permissions.' );
	}
}
unset( $s3_config );

