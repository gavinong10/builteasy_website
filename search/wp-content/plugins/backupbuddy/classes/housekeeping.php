<?php
/* backupbuddy_housekeeping
 *
 * Various housekeeping and cleanup methods for keeping things tidy.
 * Periodic housekeeping can be ran via run() function.
 *
 * @author Dustin Bolton
 * @since 6.4.0.12
 * @date Nov 2, 2015
 */
class backupbuddy_housekeeping {
	
	
	
	/* run_periodic()
	 *
	 * Periodic housekeeping cleanup function to clean up after BackupBuddy. Clean up orphaned files, data structure,
	 * old log files, etc. Also verifies anti-directory browsing files exist in expected locations and any potential
	 * problems are handled.
	 *
	 * @param	int		$backup_age_limit		Maximum age (in seconds) to allow logs or other transient files/structures to exist after no longer needed. Default: 172800 (48 hours).
	 * @param	bool	$die_on_fail			Whether or not to die fatally if something goes wrong (such as unable to make anti-directory browsing files).
	 *
	 */
	public static function run_periodic( $backup_age_limit = 172800, $die_on_fail = true ) {
		pb_backupbuddy::status( 'message', 'Starting periodic housekeeeping procedure for BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . '.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		if ( !isset( pb_backupbuddy::$options ) ) {
			pb_backupbuddy::load();
		}
		
		// Top priority. Security related or otherwise crucial to run first.
		self::no_recent_backup_reminder();
		backupbuddy_core::verify_directories( $skipTempGeneration = true ); // Verify directory existance and anti-directory browsing is in place everywhere.
		self::check_high_security_mode( $die_on_fail );
		self::remove_importbuddy_file();
		self::remove_importbuddy_dir();
		self::remove_rollback_files();
		
		// Potential large file/dir cleanup. May bog down site.
		self::cleanup_temp_dir( $backup_age_limit );
		self::remove_temp_zip_dirs( $backup_age_limit );
		
		// Robustness -- handle re-processing timeouts, verifying schedules, etc.
		self::process_timed_out_backups();
		self::process_timed_out_sends();
		self::validate_bb_schedules_in_wp();
		
		// More minor cleanup.
		self::remove_temp_tables();
		self::remove_wp_schedules_with_no_bb_schedule();
		self::trim_old_notifications();
		self::trim_remote_send_stats();
		self::s3_cancel_multipart_pieces();
		self::s32_cancel_multipart_pieces();
		self::cleanup_local_destination_temp();
		self::purge_logs();
		self::clear_cron_send();
		
		@clearstatcache(); // Clears file info stat cache.
		pb_backupbuddy::status( 'message', 'Finished periodic housekeeping cleanup procedure.' );
	} // End run_periodic().
	
	
	
	public static function no_recent_backup_reminder() {
		// Alert user if no new backups FINISHED within X number of days if enabled. Max 1 email notification per 24 hours period.
		if ( is_main_site() ) { // Only run for main site or standalone.
			if ( pb_backupbuddy::$options['no_new_backups_error_days'] > 0 ) {
				if ( pb_backupbuddy::$options['last_backup_finish'] > 0 ) {
					$time_since_last = time() - pb_backupbuddy::$options['last_backup_finish'];
					$days_since_last = round( $time_since_last / 60 / 60 / 24 );
					if ( $days_since_last > pb_backupbuddy::$options['no_new_backups_error_days'] ) {
						
						$last_sent = get_transient( 'pb_backupbuddy_no_new_backup_error' );
						if ( false === $last_sent ) {
							$last_sent = time();
							set_transient( 'pb_backupbuddy_no_new_backup_error', $last_sent, (60*60*24) );
						}
						if ( ( time() - $last_sent ) > (60*60*24) ) { // 24hrs+ elapsed since last email sent.
							$message = 'Warning! BackupBuddy is configured to notify you if no new backups have completed in `' . pb_backupbuddy::$options['no_new_backups_error_days'] . '` days. It has been `' . $days_since_last . '` days since your last completed backup.';
							pb_backupbuddy::status( 'warning', $message );
							backupbuddy_core::mail_error( $message );
						}
						
					}
				}
			}
		}
	}
	
	
	
	public static function remove_rollback_files() {
		// Clean up any old rollback undo files hanging around.
		$files = (array)glob( ABSPATH . 'backupbuddy_rollback*' );
		foreach( $files as $file ) {
			$file_stats = stat( $file );
			if ( ( time() - $file_stats['mtime'] ) > backupbuddy_constants::CLEANUP_MAX_STATUS_LOG_AGE ) {
				@unlink( $file );
			}
		}
	}
	
	
	
	public static function clear_cron_send() {
		// Clean up any old cron file transfer locks.
		$files = (array)glob( backupbuddy_core::getLogDirectory() . 'cronSend-*' );
		foreach( $files as $file ) {
			$file_stats = stat( $file );
			if ( ( time() - $file_stats['mtime'] ) > backupbuddy_constants::CLEANUP_MAX_STATUS_LOG_AGE ) {
				@unlink( $file );
			}
		}
	}
	
	
	
	public static function purge_logs() {
		$max_site_log_size = pb_backupbuddy::$options['max_site_log_size'] * 1024 * 1024; // in bytes.
		 
		// Purge old logs.
		pb_backupbuddy::status( 'details', 'Cleaning up old logs.' );
		$log_directory = backupbuddy_core::getLogDirectory();
		// Purge individual backup status logs unmodified in certain number of hours.
		$files = glob( $log_directory . 'status-*.txt' );
		if ( is_array( $files ) && !empty( $files ) ) { // For robustness. Without open_basedir the glob() function returns an empty array for no match. With open_basedir in effect the glob() function returns a boolean false for no match.
			foreach( $files as $file ) {
				$file_stats = stat( $file );
				if ( ( time() - $file_stats['mtime'] ) > backupbuddy_constants::CLEANUP_MAX_STATUS_LOG_AGE ) {
					@unlink( $file );
				}
			}
		}
		
		// Purge site-wide log if over certain size.
		$files = glob( $log_directory . 'log-*.txt' );
		if ( is_array( $files ) && !empty( $files ) ) { // For robustness. Without open_basedir the glob() function returns an empty array for no match. With open_basedir in effect the glob() function returns a boolean false for no match.
			foreach( $files as $file ) {
				$file_stats = stat( $file );
				if ( $file_stats['size'] > ( $max_site_log_size ) ) {
					backupbuddy_core::mail_error( 'NOTICE ONLY (not an error): A BackupBuddy log file has exceeded the size threshold of ' . pb_backupbuddy::$format->file_size( $max_site_log_size ) . ' and has been deleted to maintain performance. This is only a notice. Deleted log file: ' . $file . '.' );
					@unlink( $file );
				}
			}
		}
	} // End purge_logs().
	
	
	
	/*	trim_remote_send_stats()
	 *	
	 *	Handles trimming the number of remote sends to the most recent ones.
	 *	Recent transfer logs are kept for TRIPLE the max age as they are important for troubleshooting.
	 *	
	 *	@return		null
	 */
	public static function trim_remote_send_stats() {
		pb_backupbuddy::status( 'details', 'Cleaning up remote send stats.' );
		$limit = (int)pb_backupbuddy::$options['max_send_stats_count']; // Maximum number of remote sends to keep track of.
		$max_age = (int)pb_backupbuddy::$options['max_send_stats_days'] * 60 * 60 * 24;
		
		$send_fileoptions = pb_backupbuddy::$filesystem->glob_by_date( backupbuddy_core::getLogDirectory() . 'fileoptions/send-*.txt' );
		if ( ! is_array( $send_fileoptions ) ) {
			$send_fileoptions = array();
		}
		// Return if limit not yet met.
		if ( count( $send_fileoptions ) <= $limit ) {
			return;
		}
		
		$i = 0;
		foreach( $send_fileoptions as $send_fileoption ) {
			$i++;
			if ( $i > $limit ) {
				$modified = filemtime( $send_fileoption );
				if ( ( time() - $modified ) > $max_age ) {
					if ( false === @unlink( $send_fileoption ) ) {
						pb_backupbuddy::status( 'error', 'Unable to delete old remote send log file `' . $send_fileoption . '`. You may manually delete it. Check directory permissions for future cleanup.' );
					}
				}
			}
		}
		
		return;
		
	} // End trim_remote_send_stats().
	
	
	
	public static function process_timed_out_backups() {
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		
		// Mark any backups noted as in progress to timed out if taking too long. Send error email is scheduled and failed or timed out.
		// Also, Purge fileoptions files without matching backup file in existance that are older than 30 days.
		pb_backupbuddy::status( 'details', 'Cleaning up old backup fileoptions option files.' );
		$fileoptions_directory = backupbuddy_core::getLogDirectory() . 'fileoptions/';
		$files = glob( $fileoptions_directory . '*.txt' );
		if ( ! is_array( $files ) ) {
			$files = array();
		}
		foreach( $files as $file ) {
			pb_backupbuddy::status( 'details', 'Fileoptions instance #43.' );
			$backup_options = new pb_backupbuddy_fileoptions( $file, $read_only = false );
			if ( true !== ( $result = $backup_options->is_ok() ) ) {
				pb_backupbuddy::status( 'error', 'Error retrieving fileoptions file `' . $file . '`. Err 335353266.' );
			} else {
				if ( isset( $backup_options->options['archive_file'] ) ) {
					//error_log( print_r( $backup_options->options, true ) );
					
					
					if ( FALSE === $backup_options->options['finish_time'] ) {
						// Failed & already handled sending notification.
					} elseif ( $backup_options->options['finish_time'] == -1 ) {
						// Cancelled manually
					} elseif ( ( $backup_options->options['finish_time'] >= $backup_options->options['start_time'] ) && ( 0 != $backup_options->options['start_time'] ) ) {
						// Completed
					} else { // Timed out or in progress.
						
						$secondsAgo = time() - $backup_options->options['updated_time'];
						if ( $secondsAgo > backupbuddy_constants::TIME_BEFORE_CONSIDERED_TIMEOUT ) { // If 24hrs passed since last update to backup then mark this timeout as failed.
							
							if ( 'scheduled' == $backup_options->options['trigger'] ) { // SCHEDULED BACKUP TIMED OUT.
								// Determine the first step to not finish.
								$timeoutStep = '';
								foreach( $backup_options->options['steps'] as $step ) {
									if ( 0 == $step['finish_time'] ) {
										$timeoutStep = $step['function'];
										break;
									}
								}
								
								$backupCheckSpot = __( 'Select "View recently made backups" from the BackupBuddy Backups page to find this backup and view its log details and/or manually create a backup to test for problems.', 'it-l10n-backupbuddy' );
								$sendCheckSpot = __( 'Select "View recently sent files" on the Remote Destinations page to find this backup and view its log details and/or manually create a backup to test for problems.', 'it-l10n-backupbuddy' );
								
								$timeoutMessage = '';
								if ( '' != $timeoutStep ) {
									if ( 'backup_create_database_dump' == $timeoutStep ) {
										$timeoutMessage = 'The database dump step appears to have timed out. Make sure your database is not full of unwanted logs or clutter. ' . $backupCheckSpot;
									} elseif ( 'backup_zip_files' == $timeoutStep ) {
										$timeoutMessage = 'The zip archive creation step appears to have timed out. Try turning off zip compression to significantly speed up the process or exclude large files. ' . $backupCheckSpot;
									} elseif ( 'send_remote_destination' == $timeoutStep ) {
										$timeoutMessage = 'The remote transfer step appears to have timed out. Try turning on chunking in the destination settings to break up the file transfer into multiple steps. ' . $sendCheckSpot;
									} else {
										$timeoutMessage = 'The step function `' . $timeoutStep . '` appears to have timed out. ' . $backupCheckSpot;
									}
								}
								$error_message = 'Scheduled BackupBuddy backup `' . $backup_options->options['archive_file'] . '` started `' . pb_backupbuddy::$format->time_ago( $backup_options->options['start_time'] ) . '` ago likely timed out. ' . $timeoutMessage;
								
								pb_backupbuddy::status( 'error', $error_message );
								
								if ( $secondsAgo < backupbuddy_constants::CLEANUP_MAX_AGE_TO_NOTIFY_TIMEOUT ) { // Prevents very old timed out backups from triggering email send.
									backupbuddy_core::mail_error( $error_message );
								}
								
							} // end scheduled.
							
							$backup_options->options['finish_time'] = FALSE; // Consider officially timed out.  If scheduled backup then an error notification will have been sent.
							$backup_options->save();
						}
					}
					
					// Remove fileoptions files which do not have a corresponding local backup. NOTE: This only handles fileoptions files containing the 'archive_file' key in their array. Handle those without this elsewhere.
					// Cached integrity scans performed when there was not an existing fileoptions file will be missing the archive_file key.
					$backupDir = backupbuddy_core::getBackupDirectory();
					if ( ( ! file_exists( $backup_options->options['archive_file'] ) ) && ( ! file_exists( $backupDir . basename( $backup_options->options['archive_file'] ) ) ) ) { // No corresponding backup ZIP file.
						$modified = filemtime( $file );
						if ( ( time() - $modified ) > backupbuddy_constants::MAX_SECONDS_TO_KEEP_ORPHANED_FILEOPTIONS_FILES ) { // Too many days old so delete.
							if ( false === unlink( $file ) ) {
								pb_backupbuddy::status( 'error', 'Unable to delete orphaned fileoptions file `' . $file . '`.' );
							}
							if ( file_exists( $file . '.lock' ) ) {
								@unlink( $file . '.lock' );
							}
						} else {
							// Do not delete orphaned fileoptions because it is not old enough. Recent backups page needs these to list the backup.
						}
					} else { // Backup ZIP file exists.
						
					}
				} else { // No archive_file key in fileoptions array.
					
					// Trim any fileoptions files that are orphaned (no matching backup zip file). Note that above we trim these files that have the archive_file key in them.
					$backupDir = backupbuddy_core::getBackupDirectory();
					$serial = backupbuddy_core::get_serial_from_file( '-' . basename( $file ) ); // Dash needed to trick get_serial_from_file() into thinking this is a valid backup filename.
					$backupFiles = glob( $backupDir . '*' . $serial . '*.zip' ); // Try to find a matching backup zip with this serial in its filename.
					if ( ! is_array( $backupFiles ) ) {
						$backupFiles = array();
					}
					if ( 0 == count( $backupFiles ) ) { // No corresponding backup. Delete file.
						$modified = filemtime( $file );
						if ( ( time() - $modified ) > backupbuddy_constants::MAX_SECONDS_TO_KEEP_ORPHANED_FILEOPTIONS_FILES ) { // Too many days old so delete.
							if ( false === unlink( $file ) ) {
								pb_backupbuddy::status( 'error', 'Unable to delete orphaned fileoptions file `' . $file . '`.' );
							}
							if ( file_exists( $file . '.lock' ) ) {
								@unlink( $file . '.lock' );
							}
						} else {
							// Do not delete orphaned fileoptions because it is not old enough. Recent backups page needs these to list the backup.
						}
					} else {
						//echo 'backupfound<br>';
					}
					
				}
			}
		} // end foreach.
	}
	
	
	
	public static function process_timed_out_sends() {
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		
		// Mark any timed out remote sends as timed out. Attempt resend once.
		$remote_sends = array();
		$send_fileoptions = pb_backupbuddy::$filesystem->glob_by_date( backupbuddy_core::getLogDirectory() . 'fileoptions/send-*.txt' );
		if ( ! is_array( $send_fileoptions ) ) {
			$send_fileoptions = array();
		}
		foreach( $send_fileoptions as $send_fileoption ) {
			$send_id = str_replace( '.txt', '', str_replace( 'send-', '', basename( $send_fileoption ) ) );
			pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
			require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #23.' );
			$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
			if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
				pb_backupbuddy::status( 'error', __('Fatal Error #9034.32393. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
				return false;
			}
			
			// Don't do anything for success, failure, or already-marked as -1 finish time.
			if ( ( 'success' == $fileoptions_obj->options['status'] ) || ( 'failure' == $fileoptions_obj->options['status'] ) || ( -1 == $fileoptions_obj->options['finish_time'] ) ) {
				continue;
			}
			
			// Older format did not include updated_time.
			if ( ! isset( $fileoptions_obj->options['update_time'] ) ) {
				continue;
			}
			
			$secondsAgo = time() - $fileoptions_obj->options['update_time'];
			if ( $secondsAgo > backupbuddy_constants::TIME_BEFORE_CONSIDERED_TIMEOUT ) { // If 24hrs passed since last update to backup then mark this timeout as failed.
				
				$isResending = backupbuddy_core::remoteSendRetry( $fileoptions_obj, $send_id, pb_backupbuddy::$options['remote_send_timeout_retries'] );
				if ( true === $isResending ) { // If resending then skip sending any error email just yet...
					continue;
				}
				
				if ( 'timeout' != $fileoptions_obj->options['status'] ) { // Do not send email if status is 'timeout' since either already sent or old-style status marking (pre-v6.0).
					// Calculate destination title and type for error email.
					$destination_title = '';
					$destination_type = '';
					if ( isset( pb_backupbuddy::$options['remote_destinations'][$fileoptions_obj->options['destination'] ] ) ) {
						$destination_title = pb_backupbuddy::$options['remote_destinations'][$fileoptions_obj->options['destination'] ]['title'];
						$destination_type = backupbuddy_core::pretty_destination_type( pb_backupbuddy::$options['remote_destinations'][$fileoptions_obj->options['destination'] ]['type'] );
					}
					
					$error_message = 'A remote destination send of file `' . basename( $fileoptions_obj->options['file'] ) . '` started `' . pb_backupbuddy::$format->time_ago( $fileoptions_obj->options['start_time'] ) . '` ago sending to the destination titled `' . $destination_title . '` of type `' . $destination_type . '` likely timed out. BackupBuddy will attempt to retry this failed transfer ONCE. If the second atempt succeeds the failed attempt will be replaced in the recent sends list. Check the error log for further details and/or manually send a backup to test for problems.';
					pb_backupbuddy::status( 'error', $error_message );
					if ( $secondsAgo < backupbuddy_constants::CLEANUP_MAX_AGE_TO_NOTIFY_TIMEOUT ) { // Prevents very old timed out backups from triggering email send.
						backupbuddy_core::mail_error( $error_message );
					}
				}
				
				$fileoptions_obj->options['status'] = 'timeout';
				$fileoptions_obj->options['finish_time'] = -1;
				$fileoptions_obj->save();
			}
			
			unset( $fileoptions_obj );
		}
	}
	
	
	
	public static function check_high_security_mode( $die_on_fail = false ) {
		// Handle high security mode archives directory .htaccess system. If high security backup directory mode: Make sure backup archives are NOT downloadable by default publicly. This is only lifted for ~8 seconds during a backup download for security. Overwrites any existing .htaccess in this location.
		if ( pb_backupbuddy::$options['lock_archives_directory'] == '0' ) { // Normal security mode. Put normal .htaccess.
			pb_backupbuddy::status( 'details', 'Removing .htaccess high security mode for backups directory. Normal mode .htaccess to be added next.' );
			// Remove high security .htaccess.
			if ( file_exists( backupbuddy_core::getBackupDirectory() . '.htaccess' ) ) {
				$unlink_status = @unlink( backupbuddy_core::getBackupDirectory() . '.htaccess' );
				if ( $unlink_status === false ) {
					pb_backupbuddy::alert( 'Error #844594. Unable to temporarily remove .htaccess security protection on archives directory to allow downloading. Please verify permissions of the BackupBuddy archives directory or manually download via FTP.' );
				}
			}
			
			// Place normal .htaccess.
			pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getBackupDirectory(), $die_on_fail );

		} else { // High security mode. Make sure high security .htaccess in place.
			pb_backupbuddy::status( 'details', 'Adding .htaccess high security mode for backups directory.' );
			$htaccess_creation_status = @file_put_contents( backupbuddy_core::getBackupDirectory() . '.htaccess', 'deny from all' );
			if ( $htaccess_creation_status === false ) {
				pb_backupbuddy::alert( 'Error #344894545. Security Warning! Unable to create security file (.htaccess) in backups archive directory. This file prevents unauthorized downloading of backups should someone be able to guess the backup location and filenames. This is unlikely but for best security should be in place. Please verify permissions on the backups directory.' );
			}
			
		}
	}
	
	
	
	public static function remove_importbuddy_file() {
		// Remove any copy of importbuddy.php in root.
		$temp_dir = backupbuddy_core::getTempDirectory();
		pb_backupbuddy::status( 'details', 'Cleaning up any importbuddy scripts in site root if it exists & is not very recent.' );
		$importbuddyFiles = glob( $temp_dir . 'importbuddy*.php' );
		if ( ! is_array( $importbuddyFiles ) ) {
			$importbuddyFiles = array();
		}
		foreach( $importbuddyFiles as $importbuddyFile ) {
			$modified = filemtime( $importbuddyFile );
			if ( ( FALSE === $modified ) || ( time() > ( $modified + backupbuddy_constants::CLEANUP_MAX_IMPORTBUDDY_AGE ) ) ) { // If time modified unknown OR was modified long enough ago.
				pb_backupbuddy::status( 'details', 'Unlinked `' . basename( $importbuddyFile ) . '` in root of site.' );
				unlink( $importbuddyFile );
			} else {
				pb_backupbuddy::status( 'details', 'SKIPPED unlinking `' . basename( $importbuddyFile ) . '` in root of site as it is fresh and may still be in use.' );
			}
		}
	}
	
	
	
	public static function remove_importbuddy_dir() {
		// Remove any copy of importbuddy directory in root.
		pb_backupbuddy::status( 'details', 'Cleaning up importbuddy directory in site root if it exists & is not very recent.' );
		if ( file_exists( ABSPATH . 'importbuddy/' ) ) {
			$modified = filemtime( ABSPATH . 'importbuddy/' );
			if ( ( FALSE === $modified ) || ( time() > ( $modified + backupbuddy_constants::CLEANUP_MAX_IMPORTBUDDY_AGE ) ) ) { // If time modified unknown OR was modified long enough ago.
				pb_backupbuddy::status( 'details', 'Unlinked importbuddy directory recursively in root of site.' );
				pb_backupbuddy::$filesystem->unlink_recursive( ABSPATH . 'importbuddy/' );
			} else {
				pb_backupbuddy::status( 'details', 'SKIPPED unlinked importbuddy directory recursively in root of site as it is fresh and may still be in use.' );
			}
		}
	}
	
	
	
	/* cleanup_temp_dir()
	 *
	 * Cleans out any old temp files. Called by periodic cleanup function and post_backup in backup.php.
	 *
	 */
	public static function cleanup_temp_dir( $backup_age_limit = 172800 ) {
		// Remove any old temporary directories in wp-content/uploads/backupbuddy_temp/. Logs any directories it cannot delete.
		$temp_directory = backupbuddy_core::getTempDirectory();
		pb_backupbuddy::status( 'details', 'Cleaning up any old temporary zip directories in: `' . $temp_directory . '`. If no recent backups then the temp directory will also be purged.' );
		$recentBackupFound = false;
		$files = glob( $temp_directory . '*' );
		if ( is_array( $files ) && !empty( $files ) ) { // For robustness. Without open_basedir the glob() function returns an empty array for no match. With open_basedir in effect the glob() function returns a boolean false for no match.
			foreach( $files as $file ) {
				if ( ( strpos( $file, 'index.' ) !== false ) || ( strpos( $file, '.htaccess' ) !== false ) ) { // Index file or htaccess dont get deleted so go to next file.
					continue;
				}
				$file_stats = stat( $file );
				if ( ( 0 == $backup_age_limit ) || ( ( time() - $file_stats['mtime'] ) > $backup_age_limit ) ) { // If older than 12 hours, delete the log.
					if ( @pb_backupbuddy::$filesystem->unlink_recursive( $file ) === false ) {
						pb_backupbuddy::status( 'error', 'Unable to clean up (delete) temporary directory/file: `' . $file . '`. You should manually delete it or check permissions.' );
					}
				} else { // Not very old.
					$recentBackupFound = true;
				}
			}
			if ( false === $recentBackupFound ) {
				pb_backupbuddy::$filesystem->unlink_recursive( $temp_directory ); // Delete temp directory (as of BB v5.0). This is not critical but nice. The backup cleanup step should purge these so if all is going well this probably will not find anything.
			}
		}
		unset( $recentBackupFound );
	} // End cleanup_temp_dir().
	
	
	
	public static function remove_temp_zip_dirs( $backup_age_limit = 172800 ) {
		// Remove any old temporary zip directories: wp-content/uploads/backupbuddy_backups/temp_zip_XXXX/. Logs any directories it cannot delete.
		pb_backupbuddy::status( 'details', 'Cleaning up any old temporary zip directories in backup directory temp location `' . backupbuddy_core::getBackupDirectory() . 'temp_zip_XXXX/`.' );
		$temp_directory = backupbuddy_core::getBackupDirectory() . 'temp_zip_*';
		$files = glob( $temp_directory . '*' );
		if ( is_array( $files ) && !empty( $files ) ) { // For robustness. Without open_basedir the glob() function returns an empty array for no match. With open_basedir in effect the glob() function returns a boolean false for no match.
			foreach( $files as $file ) {
				if ( ( strpos( $file, 'index.' ) !== false ) || ( strpos( $file, '.htaccess' ) !== false ) ) { // Index file or htaccess dont get deleted so go to next file.
					continue;
				}
				$file_stats = stat( $file );
				if ( ( time() - $file_stats['mtime'] ) > $backup_age_limit ) { // If older than 12 hours, delete the log.
					if ( @pb_backupbuddy::$filesystem->unlink_recursive( $file ) === false ) {
						$message = 'BackupBuddy was unable to clean up (delete) temporary directory/file: `' . $file . '`. You should manually delete it and/or verify proper file permissions to allow BackupBuddy to clean up for you.';
						pb_backupbuddy::status( 'error', $message );
						backupbuddy_core::mail_error( $message );
					}
				}
			}
		}
	} // End remove_temp_zip_dirs().
	
	
	
	/* remove_temp_tables()
	 *
	 * Deletes any temporary BackupBuddy tables used by deployment or rollback functionality. Tables prefixed with bbold- or bbnew-.
	 *
	 * @param	string	$forceSerial	Optional. If provided then this only this serial will be cleaned up AND it will be cleaned up now regardless of its age.
	 * @return	null
	 */
	public static function remove_temp_tables( $forceSerial = '' ) {
		global $wpdb;
		
		if ( '' != $forceSerial ) {
			$cleanups = array( $forceSerial => 0 );
		} else {
			$cleanups = pb_backupbuddy::$options['rollback_cleanups'];
		}
		
		foreach( $cleanups as $cleanup_serial => $start_time ) {
			
			if ( ( time() - $start_time ) > backupbuddy_constants::CLEANUP_MAX_STATUS_LOG_AGE ) {
				
				$results = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE ( ( table_name LIKE 'bbnew-" . substr( $cleanup_serial, 0, 4 ) . "\_%' ) OR ( table_name LIKE 'bbold-" . substr( $cleanup_serial, 0, 4 ) . "\_%' ) ) AND table_schema = DATABASE()", ARRAY_A );
				if ( count( $results ) > 0 ) {
					foreach( $results as $result ) {
						if ( false === $wpdb->query( "DROP TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "`" ) ) {
							return $this->_error( 'Error #372837683: Unable to copy over BackupBuddy settings from live site to incoming database in temp table. Details: `' . $wpdb->last_error . '`.' );
							pb_backupbuddy::status( 'details', 'Error #83947944: Unable to drop temp rollback/deploy table `' . $result['table_name'] . '`. Details: `' . $wpdb->last_error . '`.' );
						}
					}
				}
				
				unset( pb_backupbuddy::$options['rollback_cleanups'][ $cleanup_serial ] );
				pb_backupbuddy::save();
			}
			
		} // end foreach.
		
		// Delete any undo PHP files.
		$undoFiles = glob( ABSPATH . 'backupbuddy_deploy_undo-*.php' );
		if ( ! is_array( $undoFiles ) ) {
			$undoFiles = array();
		}
		foreach( $undoFiles as $undoFile ) {
			@unlink( $undoFile );
		}
		
		return;
	} // End remove_temp_tables().
	
	
	
	/* validate_bb_schedules_in_wp()
	 *
	 * Verifies schedules all exist and are set up as expected with proper interverals, etc.
	 *
	 */
	public static function validate_bb_schedules_in_wp() {
		
		// Loop through each BB schedule and create WP schedule to match.
		foreach ( pb_backupbuddy::$options['schedules'] as $schedule_id => $schedule ) {
			
			// Retrieve current interval WordPress cron thinks the schedule is at.
			$cron_inverval = wp_get_schedule( 'backupbuddy_cron', array( 'run_scheduled_backup', array( (int)$schedule_id ) ) );
			$intervals = wp_get_schedules();
			
			
			if ( FALSE === $cron_inverval ) { // Schedule MISSING. Re-schedule.
				$result = backupbuddy_core::schedule_event( $schedule['first_run'], $schedule['interval'], 'run_scheduled_backup', array( (int)$schedule_id ) ); // Add new schedule.
				if ( $result === FALSE ) {
					$message = 'Error #83443784: A missing schedule was identified but unable to be re-created. Your schedule may not work properly & need manual attention.';
					pb_backupbuddy::alert( $message, true );
				} else {
					pb_backupbuddy::alert( 'Warning #2389373: A missing schedule was identified and re-created. This should have corrected any problem with this schedule.', true );
				}
				continue;
			}
			
			if ( $cron_inverval != $schedule['interval'] ) { // Schedule exists BUT interval is WRONG. Fix it.
				$cron_run = wp_next_scheduled( 'backupbuddy_cron', array( 'run_scheduled_backup', array( (int)$schedule_id ) ) );
				
				
				$result = backupbuddy_core::unschedule_event( $cron_run, 'backupbuddy_cron', array( 'run_scheduled_backup', array( (int)$schedule_id ) ) ); // Delete existing schedule.
				if ( $result === FALSE ) {
					$message = 'Error removing invalid event from WordPress. Your schedule may not work properly. Please try again. Error #38279343. Check your BackupBuddy error log for details.';
					pb_backupbuddy::alert( $message, true );
					continue;
				}
				
				// Determine when the next run time SHOULD be.
				if ( 0 == $schedule['last_run'] ) {
					$next_run = $schedule['first_run'];
				} else {
					$next_run = (int)$schedule['last_run'] + (int)$intervals[ $schedule['interval'] ]['interval'];
				}
				
				$result = backupbuddy_core::schedule_event( $next_run, $schedule['interval'], 'run_scheduled_backup', array( (int)$schedule_id ) ); // Add new schedule.
				if ( $result === FALSE ) {
					$message = 'Error #237836464: An invalid schedule with the incorrect interval was identified & deleted but unable to be re-created. Your schedule may not work properly & need manual attention.';
					pb_backupbuddy::alert( $message, true );
					continue;
				} else {
					pb_backupbuddy::alert( 'Warning #2423484: An invalid schedule with the incorrect interval was identified and updated. This should have corrected any problem with this schedule.', true );
				}
			}
			
		} // end foreach.
		
	} // End validate_bb_schedules_in_wp().
	
	
	
	/* remove_wp_schedules_with_no_bb_schedule()
	 *
	 * Loop through each WP schedule and delete any schedules without corresponding BB schedules or corrupt entries. Also handles migration from old to new tag backupbuddy_cron.
	 * NOTE: Also upgrades to new backupbuddy_cron tag from pb_backupbuddy-cron_scheduled_backup AND migrationg of housekeeping tag AND removal of faulty tags.
	 *
	 */
	public static function remove_wp_schedules_with_no_bb_schedule() {
		
		$cron = get_option('cron');
		foreach ( (array) $cron as $time => $cron_item ) { // Times
			if ( is_numeric( $time ) ) {
				// Loop through each schedule for this time
				foreach ( (array) $cron_item as $hook_name => $event ) { // Methods
					foreach ( (array) $event as $item_name => $item ) { // Full args for method
						
						if ( ( 'backupbuddy_cron' == $hook_name ) && ( 'run_scheduled_backup' == $item['args'][0] ) ) { // scheduled backup
							if ( ! empty( $item['args'] ) ) {
								if ( ! isset( pb_backupbuddy::$options['schedules'][ $item['args'][1][0] ] ) ) { // BB schedule does not exist so delete this cron item.
									if ( FALSE === backupbuddy_core::unschedule_event( $time, $hook_name, $item['args'] ) ) { // Delete the scheduled cron.
										pb_backupbuddy::status( 'error', 'Error #5657667675b. Unable to delete CRON job. Please see your BackupBuddy error log for details.' );
									} else {
										pb_backupbuddy::status( 'warning', 'Removed stale cron scheduled backup.' );
									}
								}
								
							} else { // No args, something wrong so delete it.
								
								if ( FALSE === backupbuddy_core::unschedule_event( $time, $hook_name, $item['args'] ) ) { // Delete the scheduled cron.
									pb_backupbuddy::status( 'error', 'Error #5657667675c. Unable to delete CRON job. Please see your BackupBuddy error log for details.' );
								} else {
									pb_backupbuddy::status( 'warning', 'Removed stale cron scheduled backup which had no arguments.' );
								}
								
							}
						} elseif ( 'pb_backupbuddy-cron_scheduled_backup' == $hook_name ) { // Upgrade hook name to 'backupbuddy_cron'.
							if ( FALSE === wp_unschedule_event( $time, $hook_name, $item['args'] ) ) { // Delete the scheduled cron.
								pb_backupbuddy::status( 'error', 'Error #327237. Unable to delete CRON job for migration to new tag. Please see your BackupBuddy error log for details.' );
							} else {
								pb_backupbuddy::status( 'details', 'Removed cron with old tag format.' );
							}
							if ( isset( pb_backupbuddy::$options['schedules'][ $item['args'][0] ] ) ) { // BB schedule exists so recreate.
								$result = backupbuddy_core::schedule_event( $time, pb_backupbuddy::$options['schedules'][ $item['args'][0] ]['interval'], 'run_scheduled_backup', $item['args'] );
								if ( $result === false ) {
									pb_backupbuddy::status( 'error', 'Error #8923832: Unable to reschedule with new cron tag.' );
								} else {
									pb_backupbuddy::status( 'details', 'Replaced cron with old tag format with new format.' );
								}
							} else {
								pb_backupbuddy::status( 'warning', 'Stale schedule found with WordPress without corresponding BackupBuddy schedule. Not keeping when migrating to new cron tag.' );
							}
						} elseif ( 'pb_backupbuddy_cron' == $hook_name ) { // Remove.
							wp_unschedule_event( $time, $hook_name, $item['args'] );
						} elseif ( 'pb_backupbuddy_corn' == $hook_name ) { // Remove.
							wp_unschedule_event( $time, $hook_name, $item['args'] );
						} elseif ( 'pb_backupbuddy_housekeeping' == $hook_name ) { // Remove.
							wp_unschedule_event( $time, $hook_name, $item['args'] );
						}
						
					} // End foreach.
					unset( $item );
					unset( $item_name );
				} // End foreach.
				unset( $event );
				unset( $hook_name );
			} // End if is_numeric.
		} // End foreach.
		unset( $cron_item );
		unset( $time );
		
	} // End remove_wp_schedules_with_no_bb_schedule().
	
	
	
	public static function trim_old_notifications() {
		$notifications = backupbuddy_core::getNotifications(); // Load notifications.
		$updateNotifications = false;
		$maxTimeDiff = pb_backupbuddy::$options['max_notifications_age_days'] * 24 * 60 * 60;
		foreach( $notifications as $i => $notification ) {
			if ( ( time() - $notification['time'] ) > $maxTimeDiff ) {
				unset( $notifications[ $i ] );
				$updateNotifications = true;
			}
		}
		if ( true === $updateNotifications ) {
			pb_backupbuddy::status( 'details', 'Periodic cleanup: Replacing notifications.' );
			backupbuddy_core::replaceNotifications( $notifications );
		}
	} // End trim_old_notifications().
	
	
	
	public static function s3_cancel_multipart_pieces() {
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination ) {
			if ( $destination['type'] != 's3' ) { continue; }
			if ( isset( $destination['max_chunk_size'] ) && ( $destination['max_chunk_size'] == '0' ) ) { continue; }
			
			pb_backupbuddy::status( 'details', 'Found S3 Multipart Chunking Destinations to cleanup.' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
			$cleanup_result = pb_backupbuddy_destinations::multipart_cleanup( $destination );
			if ( true === $cleanup_result ) {
				pb_backupbuddy::status( 'details', 'S3 Multipart Chunking Cleanup Success.' );
			} else {
				pb_backupbuddy::status( 'warning', 'Warning #2389383: S3 Multipart Chunking Cleanup FAILURE. Manually cleanup stalled multipart send via S3 or try again later.' );
			}
		}
	} // End s3_cancel_multipart_pieces().
	
	
	
	public static function s32_cancel_multipart_pieces() {
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination ) {
			if ( $destination['type'] != 's32' ) { continue; }
			
			pb_backupbuddy::status( 'details', 'Found S32 Multipart Chunking Destinations to cleanup.' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
			$cleanup_result = pb_backupbuddy_destinations::multipart_cleanup( $destination );
			if ( true === $cleanup_result ) {
				pb_backupbuddy::status( 'details', 'S32 Multipart Chunking Cleanup Success.' );
			} else {
				pb_backupbuddy::status( 'warning', 'Warning #2389328: S32 Multipart Chunking Cleanup FAILURE. Manually cleanup stalled multipart send via S3 or try again later.' );
			}
		}
	} // End s32_cancel_multipart_pieces().
	
	
	
	public static function cleanup_local_destination_temp() {
		// Cleanup any temporary local destinations.
		pb_backupbuddy::status( 'details', 'Cleaning up any temporary local destinations.' );
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
			if ( ( $destination['type'] == 'local' ) && ( isset( $destination['temporary'] ) && ( $destination['temporary'] === true ) ) ) { // If local and temporary.
				if ( ( time() - $destination['created'] ) > $backup_age_limit ) { // Older than 12 hours; clear out!
					pb_backupbuddy::status( 'details', 'Cleaned up stale local destination `' . $destination_id . '`.' );
					unset( pb_backupbuddy::$options['remote_destinations'][$destination_id] );
					pb_backupbuddy::save();
				}
			}
		}
	} // End cleanup_local_destination_temp().
	
	
	
} // end class.


