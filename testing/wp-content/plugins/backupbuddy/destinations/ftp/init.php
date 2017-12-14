<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_ftp {
	
	public static $destination_info = array(
		'name'			=>		'FTP',
		'description'	=>		'File Transport Protocol. This is the most common way of sending larger files between servers. Most web hosting accounts provide FTP access. This common and well-known transfer method is tried and true.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'ftp',	// MUST MATCH your destination slug.
		'title'			=>		'',		// Required destination field.
		'address'		=>		'',
		'username'		=>		'',
		'password'		=>		'',
		'path'			=>		'',
		'active_mode'	=>		0,   // 1 = active, 0=passive mode (default > v3.1.8).
		'ftps'			=>		0,
		'archive_limit'	=>		0,
		'url'			=>		'',		// optional url for migration that corresponds to this ftp/path.
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
		
		pb_backupbuddy::status( 'details', 'FTP class send() function started.' );
		
		if ( $settings['active_mode'] == '0' ) {
			$active_mode = false;
		} else {
			$active_mode = true;
		}
		$server = $settings['address'];
		$username = $settings['username'];
		$password = $settings['password'];
		$path = $settings['path'];
		$ftps = $settings['ftps'];
		$limit = $settings['archive_limit'];
		$active = $settings['active_mode'];
		
		
		$port = '21'; // Default FTP port.
		if ( strstr( $server, ':' ) ) { // Handle custom FTP port.
			$server_params = explode( ':', $server );
			$server = $server_params[0];
			$port = $server_params[1];
		}
		
		
		// Connect to server.
		if ( $ftps == '1' ) { // Connect with FTPs.
			if ( function_exists( 'ftp_ssl_connect' ) ) {
				$conn_id = ftp_ssl_connect( $server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'Unable to connect to FTPS  `' . $server . '` on port `' . $port . '` (check address/FTPS support and that server can connect to this address via this port).', 'error' );
					return false;
				} else {
					pb_backupbuddy::status( 'details',  'Connected to FTPs.' );
				}
			} else {
				pb_backupbuddy::status( 'details',  'Your web server doesnt support FTPS in PHP.', 'error' );
				return false;
			}
		} else { // Connect with FTP (normal).
			if ( function_exists( 'ftp_connect' ) ) {
				$conn_id = ftp_connect( $server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'ERROR: Unable to connect to FTP server `' . $server . '` on port `' . $port . '` (check address and that server can connect to this address via this port).', 'error' );
					return false;
				} else {
					pb_backupbuddy::status( 'details',  'Connected to FTP.' );
				}
			} else {
				pb_backupbuddy::status( 'details',  'Your web server doesnt support FTP in PHP.', 'error' );
				return false;
			}
		}
		
		
		// Log in.
		$login_result = @ftp_login( $conn_id, $username, $password );
		if ( $login_result === false ) {
			backupbuddy_core::mail_error( 'ERROR #9011 ( http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9011 ).  FTP/FTPs login failed on scheduled FTP.' );
			return false;
		} else {
			pb_backupbuddy::status( 'details',  'Logged in. Sending backup via FTP/FTPs ...' );
		}
		
		
		if ( $active_mode === true ) {
			// do nothing, active is default.
			pb_backupbuddy::status( 'details', 'Active FTP mode based on settings.' );
		} elseif ( $active_mode === false ) {
			// Turn passive mode on.
			pb_backupbuddy::status( 'details', 'Passive FTP mode based on settings.' );
			ftp_pasv( $conn_id, true );
		} else {
			pb_backupbuddy::status( 'error', 'Unknown FTP active/passive mode: `' . $active_mode . '`.' );
		}
		
		
		// Create directory if it does not exist.
		@ftp_mkdir( $conn_id, $path );
		
		
		// Change to directory.
		pb_backupbuddy::status( 'details', 'Entering FTP directory `' . $path . '`.' );
		ftp_chdir( $conn_id, $path );
		
		// Upload files.
		$total_transfer_size = 0;
		$total_transfer_time = 0;
		foreach( $files as $file ) {
			
			if ( ! file_exists( $file ) ) {
				pb_backupbuddy::status( 'error', 'Error #859485495. Could not upload local file `' . $file . '` to send to FTP as it does not exist. Verify the file exists, permissions of file, parent directory, and that ownership is correct. You may need suphp installed on the server.' );
			}
			if ( ! is_readable( $file ) ) {
				pb_backupbuddy::status( 'error', 'Error #8594846548. Could not read local file `' . $file . '` to sendto FTP as it is not readable. Verify permissions of file, parent directory, and that ownership is correct. You may need suphp installed on the server.' );
			}
			
			$filesize = filesize( $file );
			$total_transfer_size += $filesize;
			
			$destination_file = basename( $file ); // Using chdir() so path not needed. $path . '/' . basename( $file );
			pb_backupbuddy::status( 'details', 'About to put to FTP local file `' . $file . '` of size `' . pb_backupbuddy::$format->file_size( $filesize ) . '` to remote file `' . $destination_file . '`.' );
			$send_time = -microtime( true );
			$upload = ftp_put( $conn_id, $destination_file, $file, FTP_BINARY );
			$send_time += microtime( true );
			$total_transfer_time += $send_time;
			if ( $upload === false ) {
				$error_message = 'ERROR #9012 ( http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9012 ).  FTP/FTPs file upload failed. Check file permissions & disk quota.';
				pb_backupbuddy::status( 'error',  $error_message );
				backupbuddy_core::mail_error( $error_message );
				
				return false;
			} else {
				pb_backupbuddy::status( 'details',  'Success completely sending `' . basename( $file ) . '` to destination.' );
				
				
				// Start remote backup limit
				if ( $limit > 0 ) {
					pb_backupbuddy::status( 'details', 'Getting contents of backup directory.' );
					ftp_chdir( $conn_id, $path );
					$contents = ftp_nlist( $conn_id, '' );
					
					// Create array of backups
					$bkupprefix = backupbuddy_core::backup_prefix();
					
					$backups = array();
					foreach ( $contents as $backup ) {
						// check if file is backup
						$pos = strpos( $backup, 'backup-' . $bkupprefix . '-' );
						if ( $pos !== FALSE ) {
							array_push( $backups, $backup );
						}
					}
					arsort( $backups ); // some ftp servers seem to not report in proper order so reversing insufficiently reliable. need to reverse sort by filename. array_reverse( (array)$backups );
					
					
					if ( ( count( $backups ) ) > $limit ) {
						$delete_fail_count = 0;
						$i = 0;
						foreach( $backups as $backup ) {
							$i++;
							if ( $i > $limit ) {
								if ( !ftp_delete( $conn_id, $backup ) ) {
									pb_backupbuddy::status( 'details', 'Unable to delete excess FTP file `' . $backup . '` in path `' . $path . '`.' );
									$delete_fail_count++;
								}
							}
						}
						if ( $delete_fail_count !== 0 ) {
							backupbuddy_core::mail_error( sprintf( __('FTP remote limit could not delete %s backups. Please check and verify file permissions.', 'it-l10n-backupbuddy' ), $delete_fail_count  ) );
						}
					}
				} else {
					pb_backupbuddy::status( 'details',  'No FTP file limit to enforce.' );
				}
				// End remote backup limit
			}
			
		} // end $files loop.
		
		
		// Load destination fileoptions.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #13.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.84838. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		
		// Save stats.
		$fileoptions['write_speed'] = $total_transfer_size / $total_transfer_time;
		//$fileoptions['finish_time'] = time();
		//$fileoptions['status'] = 'success';
		$fileoptions_obj->save();
		unset( $fileoptions_obj );
		
		
		ftp_close( $conn_id );
		
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
		
		if ( ( $settings['address'] == '' ) || ( $settings['username'] == '' ) || ( $settings['password'] == '' ) ) {
			return __('Missing required input.', 'it-l10n-backupbuddy' );
		}
		
		// Try sending a file.
		$send_response = pb_backupbuddy_destinations::send( $settings, dirname( dirname( __FILE__ ) ) . '/remote-send-test.php', $send_id = 'TEST-' . pb_backupbuddy::random_string( 12 ) ); // 3rd param true forces clearing of any current uploads.
		if ( false === $send_response ) {
			$send_response = 'Error sending test file to FTP.';
		} else {
			$send_response = 'Success.';
		}
		
		// Now we will need to go and cleanup this potentially uploaded file.
		$delete_response = 'Error deleting test file from FTP.'; // Default.
		
		// Settings.
		$server = $settings['address'];
		$username = $settings['username'];
		$password = $settings['password'];
		$path = $settings['path'];
		$ftps = $settings['ftps'];
		if ( $settings['active_mode'] == '0' ) {
			$active_mode = false;
		} else {
			$active_mode = true;
		}
		$url = $settings['url']; // optional url for using with migration.
		$port = '21';
		if ( strstr( $server, ':' ) ) {
			$server_params = explode( ':', $server );
			
			$server = $server_params[0];
			$port = $server_params[1];
		}
		
		// Connect.
		if ( $ftps == '0' ) {
			$conn_id = @ftp_connect( $server, $port, 10 ); // timeout of 10 seconds.
			if ( $conn_id === false ) {
				$error = __( 'Unable to connect to FTP address `' . $server . '` on port `' . $port . '`.', 'it-l10n-backupbuddy' );
				$error .= "\n" . __( 'Verify the server address and port (default 21). Verify your host allows outgoing FTP connections.', 'it-l10n-backupbuddy' );
				return $send_response . ' ' . $error;
			}
		} else {
			if ( function_exists( 'ftp_ssl_connect' ) ) {
				$conn_id = @ftp_ssl_connect( $server, $port );
				if ( $conn_id === false ) {
					return $send_response . ' ' . __('Destination server does not support FTPS?', 'it-l10n-backupbuddy' );
				}
			} else {
				return $send_response . ' ' . __('Your web server doesnt support FTPS.', 'it-l10n-backupbuddy' );
			}
		}
		
		$login_result = @ftp_login( $conn_id, $username, $password );
		
		if ( ( !$conn_id ) || ( !$login_result ) ) {
			pb_backupbuddy::status( 'details', 'FTP test: Invalid user/pass.' );
			$response = __('Unable to login. Bad user/pass.', 'it-l10n-backupbuddy' );
			if ( $ftps != '0' ) {
				$response .= "\n\nNote: You have FTPs enabled. You may get this error if your host does not support encryption at this address/port.";
			}
			return $send_response . ' ' . $response;
		}
		
		pb_backupbuddy::status( 'details', 'FTP test: Success logging in.' );
		
		// Handle active/pasive mode.
		if ( $active_mode === true ) {
			// do nothing, active is default.
			pb_backupbuddy::status( 'details', 'Active FTP mode based on settings.' );
		} elseif ( $active_mode === false ) {
			// Turn passive mode on.
			pb_backupbuddy::status( 'details', 'Passive FTP mode based on settings.' );
			ftp_pasv( $conn_id, true );
		} else {
			pb_backupbuddy::status( 'error', 'Unknown FTP active/passive mode: `' . $active_mode . '`.' );
		}
		
		// Delete test file.
		pb_backupbuddy::status( 'details', 'FTP test: Deleting temp test file.' );
		if ( true === ftp_delete( $conn_id, $path . '/remote-send-test.php' ) ) {
			$delete_response = 'Success.';
		}
		
		// Close FTP connection.
		pb_backupbuddy::status( 'details', 'FTP test: Closing FTP connection.' );
		@ftp_close($conn_id);
		
		
		// Load destination fileoptions.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #12.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.72373. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		
		if ( ( 'Success.' != $send_response ) || ( 'Success.' != $delete_response ) ) {
			$fileoptions['status'] = 'failure';
			
			$fileoptions_obj->save();
			unset( $fileoptions_obj );
			
			return 'Send details: `' . $send_response . '`. Delete details: `' . $delete_response . '`.';
		} else {
			$fileoptions['status'] = 'success';
			$fileoptions['finish_time'] = microtime(true);
		}
		
		$fileoptions_obj->save();
		unset( $fileoptions_obj );
		
		return true;
		
	} // End test().
	
	
} // End class.