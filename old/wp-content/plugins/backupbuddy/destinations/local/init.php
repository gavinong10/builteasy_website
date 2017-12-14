<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_local { // Change class name end to match destination name.
	
	public static $destination_info = array(
		'name'			=>		'Local Directory',
		'description'	=>		'Send files to another directory on this server / hosting account. This is useful for storing copies locally in another location. This is also a possible destination for automated migrations.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'local',	// MUST MATCH your destination slug. Required destination field.
		'title'			=>		'',		// Required destination field.
		'path'			=>		'',		// Local file path for destination.
		'url'			=>		'',		// Corresponding web URL for this location.
		'created_at'	=>		0,
		'temporary'		=>		false,
		'archive_limit'	=>		'0',
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$files		Array of one or more files to send.
	 *	@return		boolean						True on success, else false.
	 */
	public static function send( $settings = array(), $files = array(), $send_id = '' ) {
		global $pb_backupbuddy_destination_errors;
		if ( '1' == $settings['disabled'] ) {
			$pb_backupbuddy_destination_errors[] = __( 'Error #48933: This destination is currently disabled. Enable it under this destination\'s Advanced Settings.', 'it-l10n-backupbuddy' );
			return false;
		}
		if ( ! is_array( $files ) ) {
			$files = array( $files );
		}
		
		$limit = $settings['archive_limit'];
		$path = $settings['path'];
		if ( !file_exists( $settings['path'] ) ) {
			pb_backupbuddy::$filesystem->mkdir( $settings['path'] );
		}
		
		$total_transfer_time = 0;
		$total_transfer_size = 0;
		foreach( $files as $file ) {
			pb_backupbuddy::status( 'details',  'Starting send to `' . $path . '`.' );
			
			$filesize = filesize( $file );
			$total_transfer_size += $filesize;
			
			$send_time = -(microtime( true ));
			if ( true !== @copy( $file, $path . '/' . basename( $file ) ) ) {
				pb_backupbuddy::status( 'error', 'Unable to copy file `' . $file . '` of size `' . pb_backupbuddy::$format->file_size( $filesize ) . '` to local path `' . $path . '`. Please verify the directory exists and permissions permit writing.' );
				backupbuddy_core::mail_error( $error_message );
				return false;
			} else {
				pb_backupbuddy::status( 'details', 'Send success.' );
			}
			$send_time += microtime( true );
			$total_transfer_time += $send_time;
			
			// Start remote backup limit
			if ( $limit > 0 ) {
				pb_backupbuddy::status( 'details', 'Archive limit of `' . $limit . '` in settings.' );
				
				pb_backupbuddy::status( 'details', 'path: ' . $path . '*.zip' );
				$remote_files = glob( $path . '/*.zip' );
				if ( !is_array( $remote_files ) ) {
					$remote_files = array();
				}
				usort( $remote_files, create_function('$a,$b', 'return filemtime($a) - filemtime($b);' ) );
				pb_backupbuddy::status( 'details', 'Found `' . count( $remote_files ) . '` backups.' );
				
				// Create array of backups and organize by date
				$bkupprefix = backupbuddy_core::backup_prefix();
				
				foreach( $remote_files as $file_key => $remote_file ) {
					if ( false === stripos( $remote_file, 'backup-' . $bkupprefix . '-' ) ) {
						pb_backupbuddy::status( 'details', 'backup-' . $bkupprefix . '-' . 'not in file: ' . $remote_file );
						unset( $backups[$file_key] );
					}
				}
				arsort( $remote_files );
				pb_backupbuddy::status( 'details', 'Found `' . count( $remote_files ) . '` backups.' );
				
				
				if ( ( count( $remote_files ) ) > $limit ) {
					pb_backupbuddy::status( 'details', 'More archives (' . count( $remote_files ) . ') than limit (' . $limit . ') allows. Trimming...' );
					$i = 0;
					$delete_fail_count = 0;
					foreach( $remote_files as $remote_file ) {
						$i++;
						if ( $i > $limit ) {
							pb_backupbuddy::status ( 'details', 'Trimming excess file `' . $remote_file . '`...' );
							if ( !unlink( $remote_file ) ) {
								pb_backupbuddy::status( 'details',  'Unable to delete excess local file `' . $remote_file . '`.' );
								$delete_fail_count++;
							}
						}
					}
					pb_backupbuddy::status( 'details', 'Finished trimming excess backups.' );
					if ( $delete_fail_count !== 0 ) {
						$error_message = 'Local remote limit could not delete ' . $delete_fail_count . ' backups.';
						pb_backupbuddy::status( 'error', $error_message );
						backupbuddy_core::mail_error( $error_message );
					}
				}
			} else {
				pb_backupbuddy::status( 'details',  'No local destination file limit to enforce.' );
			} // End remote backup limit
			
			
		} // end foreach.
		
		// Load fileoptions to the send.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #11.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.2344848. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		
		$fileoptions['write_speed'] = ( $total_transfer_time / $total_transfer_size );
		
		return true;
			
	} // End send().
	
	
	
	/*	test()
	 *	
	 *	Tests ability to write to this remote destination.
	 *	TODO: Should this delete the temporary test directory to clean up after itself?
	 *	
	 *	@param		array			$settings	Destination settings.
	 *	@return		bool|string					True on success, string error message on failure.
	 */
	public static function test( $settings, $files = array() ) {
		
		$path = rtrim( $settings['path'], '/\\' );
		$url = rtrim( $settings['url'], '/\\' );
		
		if ( !file_exists( $path ) ) {
			pb_backupbuddy::$filesystem->mkdir( $path );
		}
		
		if ( is_writable( $path ) !== true ) {
			return __('Failure', 'it-l10n-backupbuddy' ) . '; The path does not allow writing. Please verify write file permissions.';
		}
		
		if ( $url != '' ) {
			$test_filename = 'migrate_test_' . pb_backupbuddy::random_string( 10 ) . '.php';
			$test_file_path = $path . '/' . $test_filename;
			$test_file_url = $url . '/' . $test_filename;
			
			// Make file.
			file_put_contents( $test_file_path, "<?php die( '1' ); ?>" );
			
			pb_backupbuddy::status( 'details', 'Local test: Veryifing `' . $test_file_url . '` points to `' . $test_file_path . '`.' );
			
			// Test URL points to file.
			$response = wp_remote_get( $test_file_url, array(
					'method' => 'GET',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'headers' => array(),
					'body' => null,
					'cookies' => array()
				)
			);
			
			
			unlink( $test_file_path );
			
			if ( is_wp_error( $response ) ) {
				return __( 'Failure. Unable to connect to the provided URL.', 'it-l10n-backupbuddy' );
			}
			
			if ( trim( $response['body'] ) != '1' ) {
				return __('Failure. The path appears valid but the URL does not correspond to it. Leave the URL blank if not using this destination for migrations.', 'it-l10n-backupbuddy' );
			}
		}
		
		// Made it this far so success.
		return true;
		
	} // End test().
	
	
} // End class.