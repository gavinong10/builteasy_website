<?php
class pb_backupbuddy_cron extends pb_backupbuddy_croncore {
	var $_methods_ran = 0;
	
	
	/* cron()
	 *
	 * Master cron handling function as of v6.4.0.9. Wraps all cron functions so we can limit to one cron run per PHP load.
	 * Method to call such as "process_backup" will call the method in this function prefixed with an underscore
	 * "_process_backup" (to prevent accidently directly calling).
	 *
	 */
	function cron( $method, $args, $rescheduleCount = 0 ) {
		if ( ( '1' == pb_backupbuddy::$options['limit_single_cron_per_pass'] ) && ( $this->_methods_ran > 0 ) ) { // If limiting to one cron method per PHP load AND we have already ran one method this PHP process then chunk to next run.
			$rescheduleCount++;
			$nextRun = time() + 1;
			$options = array( $args, $rescheduleCount );
			
			if ( $rescheduleCount >= backupbuddy_constants::CRON_SINGLE_PASS_RESCHEDULE_LIMIT ) { // Safety net to prevent runaway rescheduling.
				$message = 'Error #8399823: Max cron single pass reschedule limit of `' . backupbuddy_constants::CRON_SINGLE_PASS_RESCHEDULE_LIMIT . '` hit. Method: `' . $method . '`; Args: `' . print_r( $options, true ) . '`.';
				pb_backupbuddy::status( 'error', $message );
				if ( 'process_backup' == $method ) { // If backup process then log to serial log file.
					pb_backupbuddy::status( 'error', $message, $args[0] );
				}
				return false;
			}
			
			if ( false === backupbuddy_core::schedule_single_event( $nextRun, $method, $options ) ) {
				$message = 'Error #838923: Unable to reschedule cron based on setting to limit single cron per pass enabled. Method: `' . $method . '`; Args: `' . print_r( $options, true ) . '`. Reschedule count: `' . $rescheduleCount . '`.';
				pb_backupbuddy::status( 'error', $message );
				if ( 'process_backup' == $method ) { // If backup process then log to serial log file.
					pb_backupbuddy::status( 'error', $message, $args[0] );
				}
				return false;
			}
			
			$message = 'Rescheduled cron as setting to limit single cron per pass enabled. Details: `' . print_r( $options, true ) . '`. Reschedule count: `' . $rescheduleCount . '`.';
			pb_backupbuddy::status( 'details', $message );
			if ( 'process_backup' == $method ) { // If backup process then log to serial log file.
				pb_backupbuddy::status( 'details', $message, $args[0] );
			}
			
			/*
			 *		TODO:	Once PING API is available, request a ping in the future so we make sure this actually runs reasonably soon.
			 *				Because we need a delay we are not firing off the cron here immediately so there will be no chaining of PHP
			 *				which may result in large delays before the next process if there's little site traffic.
			 */
			
			//error_log( 'rescheduled_cron' );
			return true;
		}
		
		$this->_methods_ran++;
		return call_user_func_array( array( &$this, '_' . $method ), $args );
	} // End cron().
	
	
	
	/* _process_backup()
	 *
	 * Runs backup process behind the scenes. Used by both scheduled AND manual backups to actually do the bulk of the work.
	 *
	 */
	function _process_backup( $serial = 'blank' ) {
		pb_backupbuddy::set_status_serial( $serial );
		pb_backupbuddy::status( 'details', '--- New PHP process.' );
		pb_backupbuddy::set_greedy_script_limits();
		pb_backupbuddy::status( 'message', 'Running process for serial `' . $serial . '`...' );
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/backup.php' );
		$newBackup = new pb_backupbuddy_backup();
		$newBackup->process_backup( $serial );
	} // End _process_backup().
	
	
	
	/* _remote_send()
	 *
	 * Advanced cron-based remote file sending.
	 *
	 * @param	int		$destination_id		Numeric array key for remote destination to send to.
	 * @param	string	$backup_file		Full file path to file to send.
	 * @param	string	$trigger			Trigger of this cron event. Valid values: scheduled, manual
	 *
	 */
	public function _remote_send( $destination_id, $backup_file, $trigger, $send_importbuddy = false, $delete_after = false ) {
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ( '' == $backup_file ) && ( $send_importbuddy ) ) {
			pb_backupbuddy::status( 'message', 'Only sending ImportBuddy to remote destination `' . $destination_id . '`.' );
		} else {
			pb_backupbuddy::status( 'message', 'Sending `' . $backup_file . '` to remote destination `' . $destination_id . '`. Importbuddy?: `' . $send_importbuddy . '`. Delete after?: `' . $delete_after . '`.' );
		}
		
		if ( !isset( pb_backupbuddy::$options ) ) {
			pb_backupbuddy::load();
		}
		
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		backupbuddy_core::send_remote_destination( $destination_id, $backup_file, $trigger, $send_importbuddy, $delete_after );
	} // End _remote_send().
	
	
	
	/*	_destination_send()
	 *	
	 *	Straight-forward send file(s) to a destination. Pass full array of destination settings. Called by chunking destination init.php's.
	 *	NOTE: DOES NOT SUPPORT MULTIPART. SEE remote_send() ABOVE!
	 *	
	 *	@param		array		$destination_settings		All settings for this destination for this action.
	 *	@param		array		$files						Array of files to send (full path).
	 *	@param		string		$send_id					Index ID of remote_sends associated with this send (if any).
	 *	@return		null
	 */
	public function _destination_send( $destination_settings, $files, $send_id = '', $delete_after = false, $identifier = '', $isRetry = false ) {
		pb_backupbuddy::status( 'details', 'Beginning cron destination_send. Unique ID: `' . $identifier . '`.' );
		if ( '' != $identifier ) {
			$lockFile = backupbuddy_core::getLogDirectory() . 'cronSend-' . $identifier . '.lock';
			pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
			
			if ( @file_exists( $lockFile ) ) { // Lock exists already. Duplicate run?
				$attempts = @file_get_contents( $lockFile );
				$attempts++;
				pb_backupbuddy::status( 'warning', 'Lock file exists and now shows ' . $attempts . ' attempts.' );
				$attempts = @file_get_contents( $lockFile, $attempts );
				return;
			} else { // No lock yet.
				if ( false === @file_put_contents( $lockFile, '1' ) ) {
					pb_backupbuddy::status( 'warning', 'Unable to create destination send lock file `' . $lockFile . '`.' );
				} else {
					pb_backupbuddy::status( 'details', 'Create destination send lock file `' . $lockFile . '`.' );
				}
			}
		}
		
		pb_backupbuddy::status( 'details', 'Launching destination send via cron.' );
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		if ( true === backupbuddy_core::destination_send( $destination_settings, $files, $send_id, $delete_after, $isRetry ) ) { // completely finished, go ahead and clean up lock file.
			/* DO not delete here as we need to keep this locked down a little longer...
			if ( '' != $identifier ) {
				if ( true === @unlink( $lockFile ) ) {
					pb_backupbuddy::status( 'details', 'Removed destination lock file.' );
				} else {
					pb_backupbuddy::status( 'warning', 'Unable to remove destination lock file `' . $lockFile . '`.' );
				}
			}
			*/
		}
		
	} // End _destination_send().
	
	
	
	/*	_process_remote_copy()
	 *	
	 *	Copy a file from a remote destination down to local.
	 *	
	 *	@param		$destination_type	string		Slug of destination type.
	 *	@param		$file				string		Remote file to copy down.
	 *	@param		$settings			array		Remote destination settings.
	 *	@return		bool							true on success, else false.
	 */
	function _process_remote_copy( $destination_type, $file, $settings ) {
		if ( FALSE !== strstr( $file, '?' ) ) {
			$url = $file;
			$file = basename( $file );
			$file = substr( $file, 0, strpos( $file, '?' ) );
		}
		
		pb_backupbuddy::status( 'details', 'Copying remote `' . $destination_type . '` file `' . $file . '` down to local.' );
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		// Determine destination filename.
		$destination_file = backupbuddy_core::getBackupDirectory() . basename( $file );
		if ( file_exists( $destination_file ) ) {
			$destination_file = str_replace( 'backup-', 'backup_copy_' . pb_backupbuddy::random_string( 5 ) . '-', $destination_file );
		}
		pb_backupbuddy::status( 'details', 'Filename of resulting local copy: `' . $destination_file . '`.' );
		
		if ( $destination_type == 'stash2' ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			
			pb_backupbuddy::status( 'details', 'About to begin downloading from URL.' );
			$download = download_url( $url );
			pb_backupbuddy::status( 'details', 'Download process complete.' );
			if ( is_wp_error( $download ) ) {
				$error = 'Error #832989323: Unable to download file `' . $file . '` from URL: `' . $url . '`. Details: `' . $download->get_error_message() . '`.';
				pb_backupbuddy::status( 'error', $error );
				pb_backupbuddy::alert( $error );
				return false;
			} else {
				if ( false === copy( $download, $destination_file ) ) {
					$error = 'Error #3329383: Unable to copy file from `' . $download . '` to `' . $destination_file . '`.';
					pb_backupbuddy::status( 'error', $error );
					pb_backupbuddy::alert( $error );
					@unlink( $download );
					return false;
				} else {
					pb_backupbuddy::status( 'details', 'File saved to `' . $destination_file . '`.' );
					@unlink( $download );
					return true;
				}
			}
		} // end stash2.
		
		if ( $destination_type == 'stash' ) {
			
			$itxapi_username = $settings['itxapi_username'];
			$itxapi_password = $settings['itxapi_password'];
			
			// Load required files.
			pb_backupbuddy::status( 'details', 'Load Stash files.' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/stash/init.php' );
			require_once( dirname( dirname( __FILE__ ) ) . '/destinations/_s3lib/aws-sdk/sdk.class.php' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/stash/lib/class.itx_helper.php' );
			
			// Talk with the Stash API to get access to do things.
			pb_backupbuddy::status( 'details', 'Authenticating Stash for remote copy to local.' );
			$stash = new ITXAPI_Helper( pb_backupbuddy_destination_stash::ITXAPI_KEY, pb_backupbuddy_destination_stash::ITXAPI_URL, $itxapi_username, $itxapi_password );
			$manage_url = $stash->get_manage_url();
			$request = new RequestCore($manage_url);
			$response = $request->send_request(true);
			
			// Validate response.
			if(!$response->isOK()) {
				$error = 'Request for management credentials failed.';
				pb_backupbuddy::status( 'error', $error );
				pb_backupbuddy::alert( $error );
				return false;
			}
			if(!$manage_data = json_decode($response->body, true)) {
				$error = 'Did not get valid JSON response.';
				pb_backupbuddy::status( 'error', $error );
				pb_backupbuddy::alert( $error );
				return false;
			}
			if(isset($manage_data['error'])) {
				$error = 'Error: ' . implode(' - ', $manage_data['error']);
				pb_backupbuddy::status( 'error', $error );
				pb_backupbuddy::alert( $error );
				return false;
			}
			
			
			// Connect to S3.
			pb_backupbuddy::status( 'details', 'Instantiating S3 object.' );
			$s3 = new AmazonS3( $manage_data['credentials'] );
			pb_backupbuddy::status( 'details', 'About to get Stash object `' . $file . '`...' );
			try {
				$response = $s3->get_object( $manage_data['bucket'], $manage_data['subkey'] . pb_backupbuddy_destination_stash::get_remote_path() . $file, array( 'fileDownload' => $destination_file ) );
			} catch (Exception $e) {
				pb_backupbuddy::status( 'error', 'Error #5443984: ' . $e->getMessage() );
				error_log( 'err:' . $e->getMessage() );
				return false;
			}
			
			if ( $response->isOK() ) {
				pb_backupbuddy::status( 'details', 'Stash copy to local success.' );
				return true;
			} else {
				pb_backupbuddy::status( 'error', 'Error #894597845. Stash copy to local FAILURE. Details: `' . print_r( $response, true ) . '`.' );
				return false;
			}
		} elseif ( $destination_type == 'gdrive' ) {
			die( 'Not implemented here.' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/init.php' );
			$settings = array_merge( pb_backupbuddy_destination_gdrive::$default_settings, $settings );
			
			if ( true === pb_backupbuddy_destination_gdrive::getFile( $settings, $file, $destination_file ) ) { // success
				pb_backupbuddy::status( 'details', 'Google Drive copy to local success.' );
				return true;
			} else { // fail
				pb_backupbuddy::status( 'details', 'Error #2332903. Google Drive copy to local FAILURE.' );
				return false;
			}
			
		} elseif ( $destination_type == 's3' ) {
			
			require_once( pb_backupbuddy::plugin_path() . '/destinations/s3/init.php' );
			if ( true === pb_backupbuddy_destination_s3::download_file( $settings, $file, $destination_file ) ) { // success
				pb_backupbuddy::status( 'details', 'S3 copy to local success.' );
				return true;
			} else { // fail
				pb_backupbuddy::status( 'details', 'Error #85448774. S3 copy to local FAILURE.' );
				return false;
			}
			
		} else {
			pb_backupbuddy::status( 'error', 'Error #859485. Unknown destination type for remote copy `' . $destination_type . '`.' );
			return false;
		}
		
		
	} // End _process_remote_copy().
	
	
	
	// TODO: Merge into v3.1 destinations system in destinations directory.
	// Copy Dropbox backup to local backup directory
	function _process_dropbox_copy( $destination_id, $file ) {
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/dropbox/lib/dropbuddy/dropbuddy.php' );
		$dropbuddy = new pb_backupbuddy_dropbuddy( pb_backupbuddy::$options['remote_destinations'][$destination_id]['token'] );
		if ( $dropbuddy->authenticate() !== true ) {
			if ( ! class_exists( 'backupbuddy_core' ) ) {
				require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
			}
			backupbuddy_core::mail_error( 'Dropbox authentication failed in cron_process_dropbox_copy.' );
			return false;
		}
		
		$destination_file = backupbuddy_core::getBackupDirectory() . basename( $file );
		if ( file_exists( $destination_file ) ) {
			$destination_file = str_replace( 'backup-', 'backup_copy_' . pb_backupbuddy::random_string( 5 ) . '-', $destination_file );
		}
		
		pb_backupbuddy::status( 'error', 'About to get file `' . $file . '` from Dropbox and save to `' . $destination_file . '`.' );
		file_put_contents( $destination_file, $dropbuddy->get_file( $file ) );
		pb_backupbuddy::status( 'error', 'Got object from Dropbox cron.' );
	}
	
	
	
	/* _process_destination_copy()
	 *
	 * Downloads a remote backup and copies it to local server.
	 *
	 * @param	$destination_settings		array 		Array of destination settings.
	 * @param	$remote_file				string		Filename of file to get. Basename only.  Remote directory / paths / buckets / etc should be passed in $destination_settings info.
	 * @param	$fileID						string		If destination uses a special file ID (eg GDrive) then pass that to destination file function instead of $remote_file. $remote_file used for calculating local filename.
	 * @return	bool									true success, else false.
	 *
	 */
	function _process_destination_copy( $destination_settings, $remote_file, $fileID = '' ) {
		
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
		
		$local_file = backupbuddy_core::getBackupDirectory() . basename( $remote_file );
		if ( file_exists( basename( $local_file ) ) ) {
			$local_file = str_replace( 'backup-', 'backup_copy_' . pb_backupbuddy::random_string( 5 ) . '-', $local_file );
		}
		
		if ( $fileID != '' ) {
			$remote_file = $fileID;
		}
		if ( true === pb_backupbuddy_destinations::getFile( $destination_settings, $remote_file, $local_file ) ) {
			pb_backupbuddy::status( 'message', 'Success copying remote file to local.' );
			return true;
		} else {
			pb_backupbuddy::status( 'error', 'Failure copying remote file to local.' );
			return false;
		}
		
	} // End _process_destination_copy().
	
	
	
	// TODO: Merge into v3.1 destinations system in destinations directory.
	// Copy Rackspace backup to local backup directory
	function _process_rackspace_copy( $rs_backup, $rs_username, $rs_api_key, $rs_container, $rs_server ) {
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/rackspace/lib/rackspace/cloudfiles.php' );
		$auth = new CF_Authentication( $rs_username, $rs_api_key, NULL, $rs_server );
		$auth->authenticate();
		$conn = new CF_Connection( $auth );

		// Set container
		$container = $conn->get_container( $rs_container );
		
		// Get file from Rackspace
		$rsfile = $container->get_object( $rs_backup );
		
		$destination_file = backupbuddy_core::getBackupDirectory() . $rs_backup;
		if ( file_exists( $destination_file ) ) {
			$destination_file = str_replace( 'backup-', 'backup_copy_' . pb_backupbuddy::random_string( 5 ) . '-', $destination_file );
		}
		
		$fso = fopen( backupbuddy_core::getBackupDirectory() . $rs_backup, 'w' );
		$rsfile->stream($fso);
		fclose($fso);
	}
	
	
	
	// TODO: Merge into v3.1 destinations system in destinations directory.
	// Copy FTP backup to local backup directory
	function _process_ftp_copy( $backup, $ftp_server, $ftp_username, $ftp_password, $ftp_directory, $port = '21', $ftps = '0' ) {
		pb_backupbuddy::set_greedy_script_limits();
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		// Connect to server.
		if ( $ftps == '1' ) { // Connect with FTPs.
			if ( function_exists( 'ftp_ssl_connect' ) ) {
				$conn_id = ftp_ssl_connect( $ftp_server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'Unable to connect to FTPS  (check address/FTPS support).', 'error' );
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
				$conn_id = ftp_connect( $ftp_server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'ERROR: Unable to connect to FTP (check address).', 'error' );
					return false;
				} else {
					pb_backupbuddy::status( 'details',  'Connected to FTP.' );
				}
			} else {
				pb_backupbuddy::status( 'details',  'Your web server doesnt support FTP in PHP.', 'error' );
				return false;
			}
		}
		
		
		// login with username and password
		$login_result = ftp_login( $conn_id, $ftp_username, $ftp_password );
	
		// try to download $server_file and save to $local_file
		$destination_file = backupbuddy_core::getBackupDirectory() . $backup;
		if ( file_exists( $destination_file ) ) {
			$destination_file = str_replace( 'backup-', 'backup_copy_' . pb_backupbuddy::random_string( 5 ) . '-', $destination_file );
		}
		if ( ftp_get( $conn_id, $destination_file, $ftp_directory . $backup, FTP_BINARY ) ) {
		    pb_backupbuddy::status( 'message', 'Successfully wrote remote file locally to `' . $destination_file . '`.' );
		} else {
		    pb_backupbuddy::status( 'error', 'Error writing remote file locally to `' . $destination_file . '`.' );
		}
		
		// close this connection
		ftp_close( $conn_id );
	} // End _process_ftp_copy().
	
	
	
	function _housekeeping() {
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
		backupbuddy_housekeeping::run_periodic();
		
	} // End _housekeeping().
	
	
	
	/* _scheduled_backup()
	 *
	 * Runs a scheduled backup.
	 *
	 */
	public static function _run_scheduled_backup( $schedule_id ) {
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		if ( !isset( pb_backupbuddy::$options ) ) {
			$this->load();
		}
		
		
		// Verify directories.
		backupbuddy_core::verify_directories( $skipTempGeneration = true );
		
		
		pb_backupbuddy::status( 'details', 'cron_process_scheduled_backup: ' . $schedule_id );
		
		
		$preflight_message = '';
		$preflight_checks = backupbuddy_core::preflight_check();
		foreach( $preflight_checks as $preflight_check ) {
			if ( $preflight_check['success'] !== true ) {
				pb_backupbuddy::status( 'warning', $preflight_check['message'] );
			}
		}
		
		if ( isset( pb_backupbuddy::$options['schedules'][$schedule_id] ) && ( is_array( pb_backupbuddy::$options['schedules'][$schedule_id] ) ) ) {
			
			// If schedule is disabled then just return. Bail out!
			if ( isset( pb_backupbuddy::$options['schedules'][$schedule_id]['on_off'] ) && ( pb_backupbuddy::$options['schedules'][$schedule_id]['on_off'] == '0' ) ) {
				pb_backupbuddy::status( 'message', 'Schedule `' . $schedule_id . '` NOT run due to being disabled based on this schedule\'s settings.' );
				return;
			}
			
			pb_backupbuddy::$options['schedules'][$schedule_id]['last_run'] = time(); // update last run time.
			pb_backupbuddy::save();
			
			
			require_once( pb_backupbuddy::plugin_path() . '/classes/backup.php' );
			$newBackup = new pb_backupbuddy_backup();
			
			
			if ( pb_backupbuddy::$options['schedules'][$schedule_id]['delete_after'] == '1' ) {
				$delete_after = true;
				pb_backupbuddy::status( 'details', 'Option to delete file after successful transfer enabled.' );
			} else {
				$delete_after = false;
				pb_backupbuddy::status( 'details', 'Option to delete file after successful transfer disabled.' );
			}
			
			// If any remote destinations are set then add these to the steps to perform after the backup.
			$post_backup_steps = array();
			$destinations = explode( '|', pb_backupbuddy::$options['schedules'][$schedule_id]['remote_destinations'] );
			$found_valid_destination = false;
			
			// Remove any invalid destinations from this run.
			foreach( $destinations as $destination_index => $destination ) {
				if ( ! isset( $destination ) || ( $destination == '' ) ) { // Remove.
					unset( $destinations[ $destination_index ] );
				}
				if ( ! isset( pb_backupbuddy::$options['remote_destinations'][$destination] ) ) { // Destination ID is invalid; remove.
					unset( $destinations[ $destination_index ] );
				}
			}
			$destination_count = count( $destinations );
			
			$i = 0;
			foreach( $destinations as $destination ) {
						$i++;
						if ( $i >= $destination_count ) { // Last destination. Delete after if enabled.
							$this_delete_after = $delete_after;
							pb_backupbuddy::status( 'details', 'Last destination set to send to so this file deletion will be determined by settings.' );
						} else { // More destinations to send to. Only delete after final send.
							$this_delete_after = false;
							pb_backupbuddy::status( 'details', 'More destinations are set to send to so this file will not be deleted after send.' );
						}
						$args = array( $destination, $this_delete_after );
						pb_backupbuddy::status( 'details', 'Adding send step with args `' . implode( ',', $args ) . '`.' );
						array_push( $post_backup_steps, array(
															'function'		=>		'send_remote_destination',
															'args'			=>		$args,
															'start_time'	=>		0,
															'finish_time'	=>		0,
															'attempts'		=>		0,
														)
									);
						
						$found_valid_destination = true;
						pb_backupbuddy::status( 'details', 'Found valid destination.' );
			}
			
			$profile_array = pb_backupbuddy::$options['profiles'][ pb_backupbuddy::$options['schedules'][$schedule_id]['profile'] ];
			
			if ( $newBackup->start_backup_process( $profile_array, 'scheduled', array(), $post_backup_steps, pb_backupbuddy::$options['schedules'][$schedule_id]['title'] ) !== true ) {
				pb_backupbuddy::status( 'error', 'Error #4564658344443: Backup failure. See earlier logging details for more information.' );
			}
		}
		pb_backupbuddy::status( 'details', 'Finished cron_process_scheduled_backup.' );
	} // End _scheduled_backup().
	
	
	
} // End class.
