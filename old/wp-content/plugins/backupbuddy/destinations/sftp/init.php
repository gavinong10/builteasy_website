<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_sftp {
	
	public static $destination_info = array(
		'name'			=>		'sFTP',
		'description'	=>		'Secure File Transport Protocol (over SSH) is a more secure way of sending files between servers than FTP by using SSH. Web hosting accounts are more frequently providing this feature for greater security. This implementation is fully in PHP so PHP memory limits may be a limiting factor on some servers.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'sftp',	// MUST MATCH your destination slug.
		'title'			=>		'',		// Required destination field.
		'address'		=>		'',
		'username'		=>		'',
		'password'		=>		'',
		'path'			=>		'',
		'archive_limit'	=>		0,
		'url'			=>		'',		// optional url for migration that corresponds to this sftp/path.
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	
	public static function _init() {
		
		//die( get_include_path() . PATH_SEPARATOR . 'phpseclib' );
		set_include_path(get_include_path() . PATH_SEPARATOR . pb_backupbuddy::plugin_path() . '/destinations/sftp/lib/phpseclib');
		require_once( pb_backupbuddy::plugin_path() . '/destinations/sftp/lib/phpseclib/Net/SFTP.php' );
		
		if ( '3' == pb_backupbuddy::$options['log_level'] ) { // Crank up logging level if in debug mode.
			define('NET_SFTP_LOGGING', NET_SFTP_LOG_COMPLEX);
		}
		
	} // end _init().
	
	
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
		
		pb_backupbuddy::status( 'details', 'FTP class send() function started.' );
		self::_init();
		
		// Connect to server.
		$server = $settings['address'];
		$port = '22'; // Default sFTP port.
		if ( strstr( $server, ':' ) ) { // Handle custom sFTP port.
			$server_params = explode( ':', $server );
			$server = $server_params[0];
			$port = $server_params[1];
		}
		pb_backupbuddy::status( 'details', 'Connecting to sFTP server...' );
		$sftp = new Net_SFTP( $server, $port );
		if ( ! $sftp->login( $settings['username'], $settings['password'] ) ) {
			pb_backupbuddy::status( 'error', 'Connection to sFTP server FAILED.' );
			pb_backupbuddy::status( 'details', 'sFTP log (if available & enabled via full logging mode): `' . $sftp->getSFTPLog() . '`.' );
			return false;
		} else {
			pb_backupbuddy::status( 'details', 'Success connecting to sFTP server.' );
		}
		
		pb_backupbuddy::status( 'details', 'Attempting to create path (if it does not exist)...' );
		if ( true === $sftp->mkdir( $settings['path'] ) ) { // Try to make directory.
			pb_backupbuddy::status( 'details', 'Directory created.' );
		} else {
			pb_backupbuddy::status( 'details', 'Directory not created.' );
		}
		
		// Change to directory.
		pb_backupbuddy::status( 'details', 'Attempting to change into directory...' );
		if ( true === $sftp->chdir( $settings['path'] ) ) {
			pb_backupbuddy::status( 'details', 'Changed into directory `' . $settings['path'] . '`. All uploads will be relative to this.' );
		} else {
			pb_backupbuddy::status( 'error', 'Unable to change into specified path. Verify the path is correct with valid permissions.' );
			pb_backupbuddy::status( 'details', 'sFTP log (if available & enabled via full logging mode): `' . $sftp->getSFTPLog() . '`.' );
			return false;
		}
		
		// Upload files.
		$total_transfer_size = 0;
		$total_transfer_time = 0;
		foreach( $files as $file ) {
			
			if ( ! file_exists( $file ) ) {
				pb_backupbuddy::status( 'error', 'Error #859485495. Could not upload local file `' . $file . '` to send to sFTP as it does not exist. Verify the file exists, permissions of file, parent directory, and that ownership is correct. You may need suphp installed on the server.' );
			}
			if ( ! is_readable( $file ) ) {
				pb_backupbuddy::status( 'error', 'Error #8594846548. Could not read local file `' . $file . '` to send to sFTP as it is not readable. Verify permissions of file, parent directory, and that ownership is correct. You may need suphp installed on the server.' );
			}
			
			$filesize = filesize( $file );
			$total_transfer_size += $filesize;
			
			$destination_file = basename( $file );
			pb_backupbuddy::status( 'details', 'About to put to sFTP local file `' . $file . '` of size `' . pb_backupbuddy::$format->file_size( $filesize ) . '` to remote file `' . $destination_file . '`.' );
			$send_time = -microtime( true );
			$upload = $sftp->put( $destination_file, $file, NET_SFTP_LOCAL_FILE );
			$send_time += microtime( true );
			$total_transfer_time += $send_time;
			if ( $upload === false ) { // Failed sending.
				$error_message = 'ERROR #9012b ( http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9012 ).  sFTP file upload failed. Check file permissions & disk quota.';
				pb_backupbuddy::status( 'error',  $error_message );
				backupbuddy_core::mail_error( $error_message );
				pb_backupbuddy::status( 'details', 'sFTP log (if available & enabled via full logging mode): `' . $sftp->getSFTPLog() . '`.' );
				return false;
			} else { // Success sending.
				pb_backupbuddy::status( 'details',  'Success completely sending `' . basename( $file ) . '` to destination.' );
				
				
				// Start remote backup limit
				if ( $settings['archive_limit'] > 0 ) {
					pb_backupbuddy::status( 'details', 'Archive limit enabled. Getting contents of backup directory.' );
					$contents = $sftp->rawlist( $settings['path'] ); // already in destination directory/path.
					
					// Create array of backups
					$bkupprefix = backupbuddy_core::backup_prefix();
					
					$backups = array();
					foreach ( $contents as $filename => $backup ) {
						// check if file is backup
						$pos = strpos( $filename, 'backup-' . $bkupprefix . '-' );
						if ( $pos !== FALSE ) {
							$backups[] = array(
								'file' => $filename,
								'modified' => $backup['mtime'],
							);
						}
					}
					
					function backupbuddy_number_sort( $a,$b ) {
						return $a['modified']<$b['modified'];
					}
					// Sort by modified using custom sort function above.
					usort( $backups, 'backupbuddy_number_sort' );
					
					
					if ( ( count( $backups ) ) > $settings['archive_limit'] ) {
						pb_backupbuddy::status( 'details', 'More backups found (' . count( $backups ) . ') than limit permits (' . $settings['archive_limit'] . ').' . print_r( $backups, true ) );
						$delete_fail_count = 0;
						$i = 0;
						foreach( $backups as $backup ) {
							$i++;
							if ( $i > $settings['archive_limit'] ) {
								if ( false === $sftp->delete( $settings['path'] . '/' . $backup['file'] ) ) {
									pb_backupbuddy::status( 'details', 'Unable to delete excess sFTP file `' . $backup['file'] . '` in path `' . $settings['path'] . '`.' );
									$delete_fail_count++;
								} else {
									pb_backupbuddy::status( 'details', 'Deleted excess sFTP file `' . $backup['file'] . '` in path `' . $settings['path'] . '`.' );
								}
							}
						}
						if ( $delete_fail_count != 0 ) {
							backupbuddy_core::mail_error( sprintf( __('sFTP remote limit could not delete %s backups. Please check and verify file permissions.', 'it-l10n-backupbuddy' ), $delete_fail_count  ) );
							pb_backupbuddy::status( 'error', 'Unable to delete one or more excess backup archives. File storage limit may be exceeded. Manually clean up backups and check permissions.' );
						} else {
							pb_backupbuddy::status( 'details', 'No problems encountered deleting excess backups.' );
						}
					} else {
						pb_backupbuddy::status( 'details', 'Not enough backups found to exceed limit. Skipping limit enforcement.' );
					}
				} else {
					pb_backupbuddy::status( 'details',  'No sFTP archive file limit to enforce.' );
				}
				// End remote backup limit
			}
			
		} // end $files loop.
		
		
		// Load destination fileoptions.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #6.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.843498. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		
		// Save stats.
		$fileoptions['write_speed'] = $total_transfer_size / $total_transfer_time;
		$fileoptions_obj->save();
		unset( $fileoptions_obj );
		
		
		return true;
		
	} // End send().
	
	
	
	/*	test()
	 *	
	 *	function description
	 *	
	 *	@param		array			$settings	Destination settings.
	 *	@return		bool|string					True on success, string error message on failure.
	 */
	public static function test( $settings ) {
		
		self::_init();
		
		// Connect to server.
		$server = $settings['address'];
		$port = '22'; // Default sFTP port.
		if ( strstr( $server, ':' ) ) { // Handle custom sFTP port.
			$server_params = explode( ':', $server );
			$server = $server_params[0];
			$port = $server_params[1];
		}
		pb_backupbuddy::status( 'details', 'Connecting to sFTP server...' );
		$sftp = new Net_SFTP( $server, $port );
		if ( ! $sftp->login( $settings['username'], $settings['password'] ) ) {
			pb_backupbuddy::status( 'error', 'Connection to sFTP server FAILED.' );
			pb_backupbuddy::status( 'details', 'sFTP log (if available & enabled via full logging mode): `' . $sftp->getSFTPLog() . '`.' );
			return __( 'Unable to connect to server using host, username, and password combination provided.', 'it-l10n-backupbuddy' );
		} else {
			pb_backupbuddy::status( 'details', 'Success connecting to sFTP server.' );
		}
		
		pb_backupbuddy::status( 'details', 'Attempting to create path (if it does not exist)...' );
		if ( true === $sftp->mkdir( $settings['path'] ) ) { // Try to make directory.
			pb_backupbuddy::status( 'details', 'Directory created.' );
		} else {
			pb_backupbuddy::status( 'details', 'Directory not created.' );
		}
		
		
		$destination_file = $settings['path'] . '/backupbuddy_test.txt';
		pb_backupbuddy::status( 'details', 'About to put to sFTP test file `backupbuddy_test.txt` to remote location `' . $destination_file . '`.' );
		$send_time = -microtime( true );
		if ( true !== $sftp->put( $destination_file, 'Upload test for BackupBuddy destination. Delete me.' ) ) {
			pb_backupbuddy::status( 'details', 'sFTP test: Failure uploading test file.' );
			$sftp->delete( $destination_file ); // Just in case it partionally made file. This has happened oddly.
			pb_backupbuddy::status( 'details', 'sFTP log (if available & enabled via full logging mode): `' . $sftp->getSFTPLog() . '`.' );
			return __('Failure uploading. Check path & permissions.', 'it-l10n-backupbuddy' );
		} else { // File uploaded.
			
			pb_backupbuddy::status( 'details', 'File uploaded.' );
			if ( $settings['url'] != '' ) {
				$response = wp_remote_get( rtrim( $settings['url'], '/\\' ) . '/backupbuddy_test.txt', array(
						'method' => 'GET',
						'timeout' => 20,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking' => true,
						'headers' => array(),
						'body' => null,
						'cookies' => array()
					)
				);
								
				if ( is_wp_error( $response ) ) {
					return __( 'Failure. Unable to connect to the provided optional URL.', 'it-l10n-backupbuddy' );
				}
				
				if ( stristr( $response['body'], 'backupbuddy' ) === false ) {
					return __('Failure. The path appears valid but the URL does not correspond to it. Leave the URL blank if not using this destination for migrations.', 'it-l10n-backupbuddy' );
				}
			}
			
			
			pb_backupbuddy::status( 'details', 'sFTP test: Deleting temp test file.' );
			$sftp->delete( $destination_file );
		}
		
		
		return true; // Success if we got this far.
	} // End test().
	
	
} // End class.