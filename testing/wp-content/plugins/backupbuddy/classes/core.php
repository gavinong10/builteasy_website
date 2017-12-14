<?php
// Helper functions for BackupBuddy.
// TODO: Eventually break out of a lot of these from BB core. Migrating from old framework to new resulted in this mid-way transition but it's a bit messy...

class backupbuddy_core {

	private static $_cachedLogDirectory = ''; // Cached log dir for getLogDirectory() to prevent having to call WP to retrieve.
	
	public static $warn_plugins = array(
		'w3-total-cache.php' => 'W3 Total Cache',
		'wp-cache.php' => 'WP Super Cache',
	);
	
	
	/* prettyCronInterval()
	 *
	 * Converts an interval time period (numeric) into an array of Array( interval tag, the display title ).
	 * Returns false if no matching interval found registered with WordPress.
	 */
	public static function prettyCronInterval( $interval ) {
		$schedule_intervals = wp_get_schedules();
		foreach( $schedule_intervals as $interval_tag => $schedule_interval ) {
			if ( $interval == $schedule_interval['interval'] ) {
				return array( $interval_tag, $schedule_interval['display'] );
			}
		}
		return false; // Not found.
	}


	public static function prettyFunctionTitle( $function, $args = '' ) {

		if ( $function == 'backup_create_database_dump' ) {
			$functionTitle = 'Backing up database';
			if ( '' != $args ) {
				$subFunctionTitle = 'Tables: ' . implode( ', ', $args[0] );
			}
		} elseif ( $function == 'backup_zip_files' ) {
			$functionTitle = 'Zipping up files';
		} elseif ( $function == 'integrity_check' ) {
			$functionTitle = 'Verifying backup file integrity';
		} elseif ( $function == 'post_backup' ) {
			$functionTitle = 'Cleaning up';
		} elseif ( $function == 'ms_download_extract_wordpress' ) {
			$functionTitle = 'Downloading WordPress core files from wordpress.org';
		} elseif ( $function == 'ms_create_wp_config' ) {
			$functionTitle = 'Generating standard wp-config.php for export';
		} elseif ( $function == 'ms_copy_plugins' ) {
			$functionTitle = 'Copying plugins';
		} elseif ( $function == 'ms_copy_themes' ) {
			$functionTitle = 'Copying themes';
		} elseif ( $function == 'ms_copy_media' ) {
			$functionTitle = 'Copying media';
		} elseif ( $function == 'ms_copy_users_table' ) {
			$functionTitle = 'Copying users';
		} elseif ( $function == 'ms_cleanup' ) {
			$functionTitle = 'Cleaning up Multisite-specific temporary data';
		} else {
			$functionTitle = $function;
		}

		return $functionTitle;
	} // end prettyFunctionTitle().

	/*	is_network_activated()
	 *	
	 *	Returns a boolean indicating whether a plugin is network activated or not.
	 *	
	 *	@return		boolean			True if plugin is network activated, else false.
	 */
	public static function is_network_activated() {

		if ( !function_exists( 'is_plugin_active_for_network' ) ) { // Function is not available on all WordPress pages for some reason according to codex.
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active_for_network( basename( pb_backupbuddy::plugin_path() ) . '/' . pb_backupbuddy::settings( 'init' ) ) ) { // Path relative to wp-content\plugins\ directory.
			return true;
		} else {
			return false;
		}

	} // End is_network_activated().



	/*	backup_integrity_check()
	 *	
	 *	Scans a backup file and saves the result in data structure. Checks for key files & that .zip can be read properly. Stores results with details in data structure.
	 *	
	 *	@param		string		$file			Full pathname & filename to backup file to check.
	 *	@param		obj			$fileoptions	fileoptions object currently holding the fileoptions file open, if any.
	 *	@param		array 		$options		Array of options.
	 *	@return		array						Returns integrity data array.
	 */
	public static function backup_integrity_check( $file, $fileoptions = '', $options = array(), $skipLogRedirect = false ) {
		
		return include( '_integrityCheck.php');
		
	} // End backup_integrity_check().



	/*	get_serial_from_file()
	 *	
	 *	Returns the backup serial based on the filename.
	 *	
	 *	@param		string		$file		Filename containing a serial to extract.
	 *	@return		string					Serial found. Empty string if unable to find serial.
	 */
	public static function get_serial_from_file( $file ) {
		
		if ( false === ( $dashpos = strrpos( $file, '-' ) ) ) {
			return '';
		}
		$serial = $dashpos + 1;
		$serial = substr( $file, $serial, ( strlen( $file ) - $serial - 4 ) );
		
		return $serial;
		
	} // End get_serial_from_file().



	/**
	 * versions_confirm()
	 *
	 * Check the version of an item and compare it to the minimum requirements BackupBuddy requires.
	 *
	 * @param		string		$type		Optional. If left blank '' then all tests will be performed. Valid values: wordpress, php, ''.
	 * @param		boolean		$notify		Optional. Whether or not to alert to the screen (and throw error to log) of a version issue.\
	 * @return		boolean					True if the selected type is a bad version
	 */
	public static function versions_confirm( $type = '', $notify = false ) {

		$bad_version = false;

		if ( ( $type == 'wordpress' ) || ( $type == '' ) ) {
			global $wp_version;
			if ( version_compare( $wp_version, pb_backupbuddy::settings( 'wp_minimum' ), '<=' ) ) {
				if ( $notify === true ) {
					pb_backupbuddy::alert( sprintf( __('ERROR: BackupBuddy requires WordPress version %1$s or higher. You may experience unexpected behavior or complete failure in this environment. Please consider upgrading WordPress.', 'it-l10n-backupbuddy' ), self::_wp_minimum) );
					pb_backupbuddy::log( 'Unsupported WordPress Version: ' . $wp_version , 'error' );
				}
				$bad_version = true;
			}
		}
		if ( ( $type == 'php' ) || ( $type == '' ) ) {
			if ( version_compare( PHP_VERSION, pb_backupbuddy::settings( 'php_minimum' ), '<=' ) ) {
				if ( $notify === true ) {
					pb_backupbuddy::alert( sprintf( __('ERROR: BackupBuddy requires PHP version %1$s or higher. You may experience unexpected behavior or complete failure in this environment. Please consider upgrading PHP.', 'it-l10n-backupbuddy' ), PHP_VERSION ) );
					pb_backupbuddy::log( 'Unsupported PHP Version: ' . PHP_VERSION , 'error' );
				}
				$bad_version = true;
			}
		}

		return $bad_version;

	} // End versions_confirm().



	/* getBackupDirectory()			backupbuddy_core::getBackupDirectory()
	 *
	 * Retrieve directory for storing backups within.
	 *
	 * @return	string		Full path to directory, including trailing slash.
	 *
	 */
	public static function getBackupDirectory() {
		if ( '' == pb_backupbuddy::$options['backup_directory'] ) {
			$dir = self::_getBackupDirectoryDefault();
		} else {
			$dir = pb_backupbuddy::$options['backup_directory'];
		}
		return $dir;
	}



	/* getLogDirectory()			backupbuddy_core::getLogDirectory()
	 *
	 * Retrieve directory for storing logs within. Caches 
	 *
	 * @return	string		Full path to directory, including trailing slash.
	 *
	 */
	public static function getLogDirectory() {
		if ( '' != self::$_cachedLogDirectory ) {
			return self::$_cachedLogDirectory;
		}
		if ( defined( 'PB_STANDALONE' ) && ( true === PB_STANDALONE ) ) {
			return ABSPATH . 'importbuddy/';
		}
		
		$uploads_dirs = wp_upload_dir();
		self::$_cachedLogDirectory = $uploads_dirs['basedir'] . '/pb_backupbuddy/';
		return self::$_cachedLogDirectory;
	} // End getLogDirectory().



	/* getTempDirectory()			backupbuddy_core::getTempDirectory()
	 *
	 * Retrieve temporary directory for storing temporary files within.
	 *
	 * @return	string		Full path to directory, including trailing slash.
	 *
	 */
	// backupbuddy_core::getTempDirectory()
	public static function getTempDirectory() {
		return ABSPATH . 'wp-content/uploads/backupbuddy_temp/';
	} // End getTempDirectory().



	/* _getBackupDirectoryDefault()
	 *
	 * Default directory backups will be stored in. getBackupDirectory() uses this as the default if no path is specifically set.
	 *
	 * @return	string		Full path to directory, including trailing slash.
	 *
	 */
	public static function _getBackupDirectoryDefault() {
		if ( defined( 'PB_IMPORTBUDDY' ) && ( true === PB_IMPORTBUDDY ) ) {
			return ABSPATH;
		}
		$uploads_dirs = wp_upload_dir();
		return $uploads_dirs['basedir'] . '/backupbuddy_backups/';
	} // End _getBackupDirectoryDefault().



	/*	get_directory_exclusions()
	 *	
	 *	Get sanitized directory exclusions. Exclusions are relative to site root (ABSPATH). See important note below!
	 *	IMPORTANT NOTE: Cannot exclude the temp directory here as this is where SQL and DAT files are stored for inclusion in the backup archive.
	 *	
	 *	@param		array 	$profile		Profile array of data.
	 *	@param		bool	$trim_suffix	True (default) if trailing slash should be trimmed from directories
	 *	@param		string	$serial			Optional serial of current backup. By default all subdirectories within the backupbuddy_temp dir are explicitly excluded. Specifying allows this serial subdirectory to not be excluded.
	 *	@return		array					Array of directories to exclude.
	 */
	public static function get_directory_exclusions( $profile, $trim_suffix = true, $serial = '' ) {
		
		$profile = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), $profile );
		
		// Get initial array.
		$exclusions = trim( $profile['excludes'] ); // Trim string.
		$exclusions = preg_split('/\n|\r|\r\n/', $exclusions ); // Break into array on any type of line ending.
		
		
		// Backup dir can be custom set and even migrated over to have weird slash mismatch issues. Sanitize it.
		$backupDir = '/' . ltrim( str_replace( ABSPATH, '', self::getBackupDirectory() ), '\\/' ); // BackupBuddy backup storage directory. Normally this should be all good.
		pb_backupbuddy::status( 'details', 'Initially calculated relative backup storage directory: `' . $backupDir . '.' );
		$normedBackupDir = str_replace( '\\', '/', $backupDir );
		$normedABSPATH = str_replace( '\\', '/', ABSPATH );
		if ( FALSE !== ( stristr( $normedBackupDir, $normedABSPATH ) ) ) { // ABSPATH still exists in the path due to some weird slash direction mismatch. Try to yank it out.
			pb_backupbuddy::status( 'details', 'ABSPATH `' . ABSPATH . '` still found backup directory path `' . $normedBackupDir . '` so it is still not relative. Using normalized ABSPATH `' . $normedABSPATH  . '` and normalized backup directory `' . $normedBackupDir . '` to make relative.' );
			$backupDir = str_replace( $normedABSPATH, $normedBackupDir, $backupDir );
		}
		$exclusions[] = $backupDir;
		
		
		$exclusions[] = '/' . ltrim( str_replace( ABSPATH, '', self::getLogDirectory() ), '\\/' ); // BackupBuddy logs & fileoptions data.
		$exclusions[] = '/importbuddy/'; // Exclude importbuddy directory in root.
		$exclusions[] = '/importbuddy.php'; // Exclude importbuddy.php script in root.
		
		// Exclude all temp directories within backupbuddy_temp, except any subdirectories containing the serial specified (if any).
		$tempDirs = glob( self::getTempDirectory() . '*', GLOB_ONLYDIR );
		if ( ! is_array( $tempDirs ) ) {
			$tempDirs = array();
		}
		foreach( $tempDirs as $tempDir ) {
			if ( ( '' == $serial ) || ( false === strstr( $tempDir, $serial ) ) ) { // If no specific serial supplied OR this dir does not contain the serial, exclude it.
				pb_backupbuddy::status( 'details', 'Excluding additional temp directory subdir: `' . $tempDir . '`.' );
				$exclusions[] = '/' . trim( str_replace( ABSPATH, '', $tempDir ), '\\/' ) . '/';
			}
		}
		
		// Clean up & sanitize array.
		if ( $trim_suffix ) {
			array_walk( $exclusions, create_function( '&$val', '$val = rtrim( trim( $val ), \'/\' );' ) ); // Apply trim to all items within.
		} else {
			array_walk( $exclusions, create_function( '&$val', '$val = trim( $val );' ) ); // Apply (whitespace-only) trim to all items within.		
		}
		$exclusions = array_filter( $exclusions, 'strlen' ); // Remove any empty / blank lines.
		
		$exclusions = apply_filters( 'backupbuddy_zip_exclusions', $exclusions );
		pb_backupbuddy::status( 'details', 'Initial zip exclusions (after filter): `' . implode( '; ', $exclusions ) . '`.' );
		
		return $exclusions;
		
	} // End get_directory_exclusions().



	/*	mail_error()
	 *	
	 *	Sends an error email to the defined email address(es) on settings page.
	 *	
	 *	@param		string			$message	Message to be included in the body of the email.
	 *	@param		string			$override_recipient	Email address(es) to send to instead of the normal recipient.
	 *	@param		string|array 	String or array of filename(s) to send as email attachments.
	 *	@return		null
	 */
	public static function mail_error( $message, $override_recipient = '', $attachments = array() ) {
		
		if ( !isset( pb_backupbuddy::$options ) ) {
			pb_backupbuddy::load();
		}

		$subject = pb_backupbuddy::$options['email_notify_error_subject'];
		$body = pb_backupbuddy::$options['email_notify_error_body'];

		$replacements = array(
			'{site_url}' => site_url(),
			'{backupbuddy_version}' => pb_backupbuddy::settings( 'version' ),
			'{current_datetime}' => date(DATE_RFC822),
			'{message}' => $message
		);

		foreach( $replacements as $replace_key => $replacement ) {
			$subject = str_replace( $replace_key, $replacement, $subject );
			$body = str_replace( $replace_key, $replacement, $body );
		}

		$email = pb_backupbuddy::$options['email_notify_error'];
		if ( $override_recipient != '' ) {
			$email = $override_recipient;
			pb_backupbuddy::status( 'details', 'Overriding email recipient to: `' . $override_recipient . '`.' );
		}
		if ( ! empty( $email ) ) {
			pb_backupbuddy::status( 'details', 'Sending email error notification with subject `' . $subject . '` to recipient(s): `' . $email . '`.' );
			if ( pb_backupbuddy::$options['email_return'] != '' ) {
				$email_return = pb_backupbuddy::$options['email_return'];
			} else {
				$email_return = get_option('admin_email');
			}
			
			if ( function_exists( 'wp_mail' ) ) {
				$result = wp_mail( $email, $subject, $body, 'From: BackupBuddy <' . $email_return . ">\r\n".'Reply-To: '.get_option('admin_email')."\r\n", $attachments );
				if ( false === $result ) {
					pb_backupbuddy::status( 'error', 'Error #45443554: Unable to send error email with WordPress wp_mail(). Verify WordPress & Server settings.' );
				}
			} else {
				pb_backupbuddy::status( 'error', 'Warning #3289239: wp_mail() unavailable. Inside WordPress?' );
			}
		} else {
			pb_backupbuddy::status( 'warning', 'No email addresses are set to receive error notifications on the Settings page. Setting a notification email is suggested.' );
		}
		
	} // End mail_error().



	/*	mail_notify_scheduled()
	 *	
	 *	Sends a message email to the defined email address(es) on settings page.
	 *	
	 *	@param		string		$start_or_complete	Whether this is the notifcation for starting or completing. Valid values: start, complete, destinationComplete
	 *	@param		string		$message			Message to be included in the body of the email.
	 *	@return		null
	 */
	public static function mail_notify_scheduled( $serial, $start_or_complete, $message, $extraReplacements = array() ) {

		if ( !isset( pb_backupbuddy::$options ) ) {
			pb_backupbuddy::load();
		}

		if ( $start_or_complete == 'start' ) {
			$email = pb_backupbuddy::$options['email_notify_scheduled_start'];

			$subject = pb_backupbuddy::$options['email_notify_scheduled_start_subject'];
			$body = pb_backupbuddy::$options['email_notify_scheduled_start_body'];

			$replacements = array(
				'{site_url}' => site_url(),
				'{backupbuddy_version}' => pb_backupbuddy::settings( 'version' ),
				'{current_datetime}' => date(DATE_RFC822),
				'{message}' => $message
			);
		} elseif ( $start_or_complete == 'complete' ) {
			$email = pb_backupbuddy::$options['email_notify_scheduled_complete'];

			$subject = pb_backupbuddy::$options['email_notify_scheduled_complete_subject'];
			$body = pb_backupbuddy::$options['email_notify_scheduled_complete_body'];

			$archive_file = '';
			require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #37.' );
			$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = true, $ignore_lock = true );
			if ( true !== ( $result = $backup_options->is_ok() ) ) {
				pb_backupbuddy::status( 'error', 'Error retrieving fileoptions file `' . backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' . '`. Err 35564332.' );
				$archive_file = '[file_unknown]';
				$backup_size = '[size_unknown]';
				$backup_type = '[type_unknown]';
			} else {
				$archive_file = $backup_options->options['archive_file'];
				$backup_size = $backup_options->options['archive_size'];
				$backup_type = $backup_options->options['type'];
			}

			$replacements = array(
				'{site_url}' => site_url(),
				'{backupbuddy_version}' => pb_backupbuddy::settings( 'version' ),
				'{current_datetime}' => date(DATE_RFC822),
				'{message}' => $message,

				'{backup_serial}' => $serial,
				'{download_link}' => pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup=' . basename( $archive_file ),
				'{backup_file}' => basename( $archive_file ),
				'{backup_size}' => $backup_size,
				'{backup_type}' => $backup_type,
			);
		} elseif ( $start_or_complete == 'destinationComplete' ) {
			$email = pb_backupbuddy::$options['email_notify_send_finish'];
			
			$subject = pb_backupbuddy::$options['email_notify_send_finish_subject'];
			$body = pb_backupbuddy::$options['email_notify_send_finish_body'];
			
			$replacements = array(
				'{site_url}' => site_url(),
				'{backupbuddy_version}' => pb_backupbuddy::settings( 'version' ),
				'{current_datetime}' => date(DATE_RFC822),
				'{message}' => $message,
			);
		} else {
			pb_backupbuddy::status( 'error', 'ERROR #54857845785: Fatally halted. Invalid schedule type. Expected `start` or `complete`. Got `' . $start_or_complete . '`.' );
		}
		
		$replacements = array_merge( $replacements, $extraReplacements );
		
		foreach( $replacements as $replace_key => $replacement ) {
			$subject = str_replace( $replace_key, $replacement, $subject );
			$body = str_replace( $replace_key, $replacement, $body );
		}

		if ( pb_backupbuddy::$options['email_return'] != '' ) {
			$email_return = pb_backupbuddy::$options['email_return'];
		} else {
			$email_return = get_option('admin_email');
		}
		
		pb_backupbuddy::status( 'details', 'Sending email schedule notification. Subject: `' . $subject . '`; body: `' . $body . '`; recipient(s): `' . $email . '`.' );
		if ( !empty( $email ) ) {
			if ( function_exists( 'wp_mail' ) ) {
				wp_mail( $email, $subject, $body, 'From: BackupBuddy <' . $email_return . ">\r\n".'Reply-To: '.get_option('admin_email')."\r\n");
			} else {
				pb_backupbuddy::status( 'error', 'Error #32892393: wp_mail() does not exist. Inside WordPress?' );
			}
		}
	} // End mail_notify_scheduled().
	
	
	
	/*	backup_prefix()
	 *	
	 *	Strips all non-file-friendly characters from the site URL. Used in making backup zip filename.
	 *	
	 *	@return		string		The filename friendly converted site URL.
	 */
	public static function backup_prefix() {
		
		$siteurl = site_url();
		$siteurl = str_replace( 'http://', '', $siteurl );
		$siteurl = str_replace( 'https://', '', $siteurl );
		$siteurl = str_replace( '/', '_', $siteurl );
		$siteurl = str_replace( '\\', '_', $siteurl );
		$siteurl = str_replace( '.', '_', $siteurl );
		$siteurl = str_replace( ':', '_', $siteurl ); // Alternative port from 80 is stored in the site url.
		$siteurl = str_replace( '~', '_', $siteurl ); // Strip ~.
		return $siteurl;
		
	} // End backup_prefix().
	
	
	
	/* remoteSendRetry()
	 *
	 * Returns true if resending or false if retry limit already met.
	 *
	 */
	public static function remoteSendRetry( &$fileoptions_obj, $send_id, $maximumRetries = 1 ) {
		// Destination settings are stored for this destination so see if we can retry sending it (if settings permit).
		if ( isset( $fileoptions_obj->options['destinationSettings'] ) && ( count( $fileoptions_obj->options['destinationSettings'] ) > 0 ) ) {
			
			/*
			echo '<pre>';
			print_r( $fileoptions_obj->options );
			echo '</pre>';
			*/
			
			$destination_settings = $fileoptions_obj->options['destinationSettings']; // these are the latest; includes info needed for chunking too.
			//$send_id = $fileoptions_obj->options['sendID'];
			$delete_after = $fileoptions_obj->options['deleteAfter'];
			$retries = $fileoptions_obj->options['retries'];
			$file = $fileoptions_obj->options['file'];
			
			if ( $retries < $maximumRetries ) {
				pb_backupbuddy::status( 'details', 'Timed out remote send has not exceeded retry limit (`' . $maximumRetries . '`). Trying to send again.' );

				//$fileoptions_obj->options['retries']++;
				//$fileoptions_obj->save(); // NOTE: Retry count now updates in bootstrap.php send() function. Only leaving this here temporarily to help get rid of failed resends from accumulating with old format.
				
				// Schedule send of this piece.
				pb_backupbuddy::status( 'details', 'Scheduling cron to send to this remote destination...' );
				$cronArgs = array(
					$destination_settings,
					$file,
					$send_id,
					$delete_after,
					$identifier = '',
					$isRetry = true
				);
				
				/*
				echo 'cronargs: ';
				echo '<pre>';
				print_r( $cronArgs );
				echo '</pre>';
				*/
				
				$schedule_result = backupbuddy_core::schedule_single_event( time(), 'destination_send', $cronArgs );
				if ( $schedule_result === FALSE ) {
					$error = 'Error scheduling file transfer. Please check your BackupBuddy error log for details. A plugin may have prevented scheduling or the database rejected it.';
					pb_backupbuddy::status( 'error', $error );
					echo $error;
				} else {
					pb_backupbuddy::status( 'details', 'Cron to send to remote destination scheduled.' );
				}
				spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
				update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
				
				return true;
			} else {
				pb_backupbuddy::status( 'details', 'Maximum remote send timeout retries (`' . $maximumRetries . '`) passed to function met. Not resending.' );
				return false;
			}
		}
	} // End remoteSendRetry().
	
	
	
	/* get_remote_send_defaults()
	 *
	 * Get default array values for the remote_sends fileoptions files.
	 * @return		array
	 *
	 */
	public static function get_remote_send_defaults() {
		return array(
			'destination'		=>	0,
			'file'				=>	'',
			'file_size'			=>	0,
			'trigger'			=>	'',						// What triggered this backup. Valid values: scheduled, manual.
			'send_importbuddy'	=>	false,
			'start_time'		=>	time(),
			'finish_time'		=>	0,
			'update_time'		=>	time(),
			'status'			=>	'running',  // success, failure, running, timeout (default assumption if this is not updated in this PHP load)
			'write_speed'		=>	0,
			'destinationSettings' => array(),
			'sendID'			=> '',
			'deleteAfter'		=> false,
			'retries'			=> 0,
		);
	} // End get_remote_send_defaults();
	
	
	
	/*	send_remote_destination()
	 *	
	 *	function description
	 *	
	 *	@param		int		$destination_id		ID number (index of the destinations array) to send it.
	 *	@param		string	$file				Full file path of file to send.
	 *	@param		string	$trigger			What triggered this backup. Valid values: scheduled, manual.
	 *	@param		bool	$send_importbuddy	Whether or not importbuddy.php should also be sent with the file to destination.
	 *	@param		bool	$delete_after		Whether or not to delete after send success after THIS send.
	 *	@param		array 	$destination_settings	If passed then this array is used instead of grabbing from settings.
	 *	@return		bool						Send status. true success, false failed.
	 */
	public static function send_remote_destination( $destination_id, $file, $trigger = '', $send_importbuddy = false, $delete_after = false, $identifier = '', $destination_settings = '' ) {
		
		if ( defined( 'PB_DEMO_MODE' ) ) {
			return false;
		}
		
		if ( ! file_exists( $file ) ) {
			// Check if utf8 decoding the filename helps us find it.
			$utf_decoded_filename = utf8_decode( $file );
			if ( file_exists( $utf_decoded_filename ) ) {
				$file = $utf_decoded_filename;
			} else {
				pb_backupbuddy::status( 'error', 'Error #8583489734: Unable to send file `' . $file . '` to remote destination as it no longer exists. It may have been deleted or permissions are invalid.' );
				return false;
			}
		}
		
		$migrationkey_transient_time = 60*60*24;
		
		if ( '' == $file ) {
			$backup_file_size = 50000; // not sure why anything current would be sending importbuddy but NOT sending a backup but just in case...
		} else {
			$backup_file_size = filesize( $file );
		}
		
		// Generate remote send ID for reference and add it as a new logging serial for better recording details.
		if ( '' == $identifier ) {
			$identifier = pb_backupbuddy::random_string( 12 );
		}
		
		// Set migration key for later determining last initiated migration.
		if ( 'migration' == $trigger ) {
			set_transient( 'pb_backupbuddy_migrationkey', $identifier, $migrationkey_transient_time );
		}
		
		pb_backupbuddy::status( 'details', 'Sending file `' . $file . '` to remote destination `' . $destination_id . '` with ID `' . $identifier . '` triggered by `' . $trigger . '`.' );
		
		//pb_backupbuddy::status( 'details', 'About to create initial fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #35.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $identifier . '.txt', $read_only = false, $ignore_lock = true, $create_file = true );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034 A. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		//pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options; // Set reference.
		
		// Record some statistics.
		$fileoptions = array_merge(
			self::get_remote_send_defaults(),
			array(
				'destination'		=>	$destination_id,
				'file'				=>	$file,
				'file_size'			=>	$backup_file_size,
				'trigger'			=>	$trigger,						// What triggered this backup. Valid values: scheduled, manual.
				'send_importbuddy'	=>	$send_importbuddy,
				'start_time'		=>	time(),
				'finish_time'		=>	0,
				'status'			=>	'running',  // success, failure, running, timeout (default assumption if this is not updated in this PHP load)
				'write_speed'		=>	0,
			)
		);
		pb_backupbuddy::save();
		
		// Destination settings were not passed so get them based on the destination ID provided.
		if ( ! is_array( $destination_settings ) ) {
			$destination_settings = &pb_backupbuddy::$options['remote_destinations'][$destination_id];
		}
		
		
		// For Stash we will check the quota prior to initiating send.
		if ( pb_backupbuddy::$options['remote_destinations'][$destination_id]['type'] == 'stash' ) {
			// Pass off to destination handler.
			require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
			$send_result = pb_backupbuddy_destinations::get_info( 'stash' ); // Used to kick the Stash destination into life.
			$stash_quota = pb_backupbuddy_destination_stash::get_quota( pb_backupbuddy::$options['remote_destinations'][$destination_id], true );
			
			if ( $file != '' ) {
				$backup_file_size = filesize( $file );
			} else {
				$backup_file_size = 50000;
			}
			if ( ( $backup_file_size + $stash_quota['quota_used'] ) > $stash_quota['quota_total'] ) {
				$message = '';
				$message .= "You do not have enough Stash storage space to send this file. Please upgrade your Stash storage at http://ithemes.com/member/panel/stash.php or delete files to make space.\n\n";
				
				$message .= 'Attempting to send file of size ' . pb_backupbuddy::$format->file_size( $backup_file_size ) . ' but you only have ' . $stash_quota['quota_available_nice'] . ' available. ';
				$message .= 'Currently using ' . $stash_quota['quota_used_nice'] . ' of ' . $stash_quota['quota_total_nice'] . ' (' . $stash_quota['quota_used_percent'] . '%).';
				
				pb_backupbuddy::status( 'error', $message );
				backupbuddy_core::mail_error( $message );
				
				$fileoptions['status'] = 'Failure. Insufficient destination space.';
				$fileoptions_obj->save();
				
				return false;
			} else {
				if ( isset( $stash_quota['quota_warning'] ) && ( $stash_quota['quota_warning'] != '' ) ) {
					
					// We log warning of usage but dont send error email.
					$message = '';
					$message .= 'WARNING: ' . $stash_quota['quota_warning'] . "\n\nPlease upgrade your Stash storage at http://ithemes.com/member/panel/stash.php or delete files to make space.\n\n";
					$message .= 'Currently using ' . $stash_quota['quota_used_nice'] . ' of ' . $stash_quota['quota_total_nice'] . ' (' . $stash_quota['quota_used_percent'] . '%).';
					
					pb_backupbuddy::status( 'details', $message );
					//backupbuddy_core::mail_error( $message );
					
				}
			}
			
		} // end if stash.
		
		/*
		if ( $send_importbuddy === true ) {
			pb_backupbuddy::status( 'details', 'Generating temporary importbuddy.php file for remote send.' );
			pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
			$importbuddy_temp = backupbuddy_core::getTempDirectory() . 'importbuddy.php'; // Full path & filename to temporary importbuddy
			self::importbuddy( $importbuddy_temp ); // Create temporary importbuddy.
			pb_backupbuddy::status( 'details', 'Generated temporary importbuddy.' );
			$files[] = $importbuddy_temp; // Add importbuddy file to the list of files to send.
			$send_importbuddy = true; // Track to delete after finished.
		} else {
			pb_backupbuddy::status( 'details', 'Not sending importbuddy.' );
		}
		*/
		
		
		// Clear fileoptions so other stuff can access it if needed.
		$fileoptions_obj->save();
		$fileoptions_obj->unlock();
		unset( $fileoptions_obj );
		
		
		// Pass off to destination handler.
		require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
		
		pb_backupbuddy::status( 'details', 'Calling destination send() function.' );
		$send_result = pb_backupbuddy_destinations::send( $destination_settings, $file, $identifier, $delete_after );
		pb_backupbuddy::status( 'details', 'Finished destination send() function.' );
		
		self::kick_db(); // Kick the database to make sure it didn't go away, preventing options saving.
		
		// Reload fileoptions.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data for saving send status.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #34.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $identifier . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034 G. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded for ID `' . $identifier . '`.' );
		$fileoptions = &$fileoptions_obj->options; // Set reference.
		
		
		// Update stats.
		$fileoptions[$identifier]['finish_time'] = microtime(true);
		if ( $send_result === true ) { // succeeded.
			$fileoptions['status'] = 'success';
			$fileoptions['finish_time'] = microtime(true);
			pb_backupbuddy::status( 'details', 'Remote send SUCCESS.' );
		} elseif ( $send_result === false ) { // failed.
			$fileoptions['status'] = 'failure';
			pb_backupbuddy::status( 'details', 'Remote send FAILURE.' );
		} elseif ( is_array( $send_result ) ) { // Array so multipart.
			$fileoptions['status'] = 'multipart';
			$fileoptions['finish_time'] = 0;
			$fileoptions['_multipart_id'] = $send_result[0];
			$fileoptions['_multipart_status'] = $send_result[1];
			pb_backupbuddy::status( 'details', 'Multipart send in progress.' );
		} else {
			pb_backupbuddy::status( 'error', 'Error #5485785576463. Invalid status send result: `' . $send_result . '`.' );
		}
		$fileoptions_obj->save();
		
		
		// If we sent importbuddy then delete the local copy to clean up.
		if ( $send_importbuddy !== false ) {
			@unlink( $importbuddy_temp ); // Delete temporary importbuddy.
		}
		
		// As of v5.0: Post-send deletion now handled within destinations/bootstrap.php send() to support chunked sends.
		
		return $send_result;
		
	} // End send_remote_destination().
	
	
	
	/*	destination_send()
	 *	
	 *	Send file(s) to a destination. Pass full array of destination settings.
	 *	
	 *	@param		array		$destination_settings		All settings for this destination for this action.
	 *	@param		array		$files						Array of files to send (full path).
	 *	@return		bool|array								Bool true = success, bool false = fail, array = multipart transfer.
	 */
	public static function destination_send( $destination_settings, $files, $send_id = '', $delete_after = false, $isRetry = false ) {
		
		// Pass off to destination handler.
		require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
		$send_result = pb_backupbuddy_destinations::send( $destination_settings, $files, $send_id, $delete_after, $isRetry );
		
		return $send_result;
		
	} // End destination_send().
	
	
	
	/*	backups_list()
	 *	
	 *	function description
	 *	
	 *	@param		string		$type			Valid options: default, migrate
	 *	@param		boolean		$subsite_mode	When in subsite mode only backups for that specific subsite will be listed.
	 *	@return		
	 */
	public static function backups_list( $type = 'default', $subsite_mode = false ) {
		
		if ( ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) && ( is_array( pb_backupbuddy::_POST( 'items' ) ) ) ) {
			$needs_save = false;
			pb_backupbuddy::verify_nonce( pb_backupbuddy::_POST( '_wpnonce' ) ); // Security check to prevent unauthorized deletions by posting from a remote place.
			$deleted_files = array();
			foreach( pb_backupbuddy::_POST( 'items' ) as $item ) {
				if ( file_exists( backupbuddy_core::getBackupDirectory() . $item ) ) {
					if ( @unlink( backupbuddy_core::getBackupDirectory() . $item ) === true ) {
						$deleted_files[] = $item;
						
						// Cleanup any related fileoptions files.
						$serial = backupbuddy_core::get_serial_from_file( $item );
						
						$backup_files = glob( backupbuddy_core::getBackupDirectory() . '*.zip' );
						if ( ! is_array( $backup_files ) ) {
							$backup_files = array();
						}
						if ( count( $backup_files ) > 5 ) { // Keep a minimum number of backups in array for stats.
							$this_serial = self::get_serial_from_file( $item );
							$fileoptions_file = backupbuddy_core::getLogDirectory() . 'fileoptions/' . $this_serial . '.txt';
							if ( file_exists( $fileoptions_file ) ) {
								@unlink( $fileoptions_file );
							}
							if ( file_exists( $fileoptions_file . '.lock' ) ) {
								@unlink( $fileoptions_file . '.lock' );
							}
							$needs_save = true;
						}
					} else {
						pb_backupbuddy::alert( 'Error: Unable to delete backup file `' . $item . '`. Please verify permissions.', true );
					}
				} // End if file exists.
			} // End foreach.
			if ( $needs_save === true ) {
				pb_backupbuddy::save();
			}
			
			pb_backupbuddy::alert( __( 'Deleted:', 'it-l10n-backupbuddy' ) . ' ' . implode( ', ', $deleted_files ) );
		} // End if deleting backup(s).
		
		
		$backups = array();
		$backup_sort_dates = array();
		$files = glob( backupbuddy_core::getBackupDirectory() . 'backup*.zip' );
		if ( is_array( $files ) && !empty( $files ) ) { // For robustness. Without open_basedir the glob() function returns an empty array for no match. With open_basedir in effect the glob() function returns a boolean false for no match.
			
			$backup_prefix = self::backup_prefix(); // Backup prefix for this site. Used for MS checking that this user can see this backup.
			foreach( $files as $file_id => $file ) {
				
				if ( ( $subsite_mode === true ) && is_multisite() ) { // If a Network and NOT the superadmin must make sure they can only see the specific subsite backups for security purposes.
					
					// Only allow viewing of their own backups.
					if ( !strstr( $file, $backup_prefix ) ) {
						unset( $files[$file_id] ); // Remove this backup from the list. This user does not have access to it.
						continue; // Skip processing to next file.
					}
				}
				
				$serial = backupbuddy_core::get_serial_from_file( $file );
				
				$options = array();
				if ( file_exists( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' ) ) {
					require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
					pb_backupbuddy::status( 'details', 'Fileoptions instance #33.' );
					$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = false, $ignore_lock = false, $create_file = true ); // Will create file to hold integrity data if nothing exists.
				} else {
					$backup_options = '';
				}
				$backup_integrity = backupbuddy_core::backup_integrity_check( $file, $backup_options, $options );
				
				// Backup status.
				$pretty_status = array(
					true	=>	'<span class="pb_label pb_label-success">Good</span>', // v4.0+ Good.
					'pass'	=>	'<span class="pb_label pb_label-success">Good</span>', // Pre-v4.0 Good.
					false	=>	'<span class="pb_label pb_label-important">Bad</span>',  // v4.0+ Bad.
					'fail'	=>	'<span class="pb_label pb_label-important">Bad</span>',  // Pre-v4.0 Bad.
				);
				
				// Backup type.
				$pretty_type = array(
					'full'	=>	'Full',
					'db'	=>	'Database',
					'files' =>	'Files',
				);
				
				
				// Defaults...
				$detected_type = '';
				$file_size = '';
				$modified = '';
				$modified_time = 0;
				$integrity = '';
				
				$main_string = 'Warn#284.';
				if ( is_array( $backup_integrity ) ) { // Data intact... put it all together.
					// Calculate time ago.
					$time_ago = '';
					if ( isset( $backup_integrity['modified'] ) ) {
						$time_ago = pb_backupbuddy::$format->time_ago( $backup_integrity['modified'] ) . ' ago';
					}
					
					$detected_type = pb_backupbuddy::$format->prettify( $backup_integrity['detected_type'], $pretty_type );
					if ( $detected_type == '' ) {
						$detected_type = 'Unknown';
					} else {
						if ( isset( $backup_options->options['profile'] ) ) {
							$detected_type = '
							<div>
								<span style="color: #AAA; float: left;">' . $detected_type . '</span>
								<span style="display: inline-block; float: left; height: 15px; border-right: 1px solid #EBEBEB; margin-left: 6px; margin-right: 6px;"></span>
								' . htmlentities( $backup_options->options['profile']['title'] ) . '
							</div>
							'
							;
						}
					}
					
					$file_size = pb_backupbuddy::$format->file_size( $backup_integrity['size'] );
					$modified = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup_integrity['modified'] ), 'l, F j, Y - g:i:s a' );
					$modified_time = $backup_integrity['modified'];
					if ( isset( $backup_integrity['status'] ) ) { // Pre-v4.0.
						$status = $backup_integrity['status'];
					} else { // v4.0+
						$status = $backup_integrity['is_ok'];
					}
					
					
					// Calculate main row string.
					if ( $type == 'default' ) { // Default backup listing.
						$main_string = '<a href="' . pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup=' . basename( $file ) . '" class="backupbuddyFileTitle" title="' . basename( $file ) . '">' . $modified . ' (' . $time_ago . ')</a>';
					} elseif ( $type == 'migrate' ) { // Migration backup listing.
						$main_string = '<a class="pb_backupbuddy_hoveraction_migrate backupbuddyFileTitle" rel="' . basename( $file ) . '" href="' . pb_backupbuddy::page_url() . '&migrate=' . basename( $file ) . '&value=' . basename( $file ) . '" title="' . basename( $file ) . '">' . $modified . ' (' . $time_ago . ')</a>';
					} else {
						$main_string = '{Unknown type.}';
					}
					// Add comment to main row string if applicable.
					if ( isset( $backup_integrity['comment'] ) && ( $backup_integrity['comment'] !== false ) && ( $backup_integrity['comment'] !== '' ) ) {
						$main_string .= '<br><span class="description">Note: <span class="pb_backupbuddy_notetext">' . htmlentities( $backup_integrity['comment'] ) . '</span></span>';
					}


					$integrity = pb_backupbuddy::$format->prettify( $status, $pretty_status ) . ' ';
					if ( isset( $backup_integrity['scan_notes'] ) && count( (array)$backup_integrity['scan_notes'] ) > 0 ) {
						foreach( (array)$backup_integrity['scan_notes'] as $scan_note ) {
							$integrity .= $scan_note . ' ';
						}
					}
					$integrity .= '<a href="' . pb_backupbuddy::page_url() . '&reset_integrity=' . $serial  . '" title="Rescan integrity. Last checked ' . pb_backupbuddy::$format->date( $backup_integrity['scan_time'] ) . '."><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"></a>';
					$integrity .= '<div class="row-actions"><a title="' . __( 'Backup Status', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'integrity_status' ) . '&serial=' . $serial . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">' . __( 'View Details', 'it-l10n-backupbuddy' ) . '</a></div>';
					
					$sumLogFile = backupbuddy_core::getLogDirectory() . 'status-' . $serial . '_sum_' . pb_backupbuddy::$options['log_serial'] . '.txt';
					if ( file_exists( $sumLogFile ) ) {
						$integrity .= '<div class="row-actions"><a title="' . __( 'View Backup Log', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'view_log' ) . '&serial=' . $serial . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">' . __( 'View Log', 'it-l10n-backupbuddy' ) . '</a></div>';
					}
					
				} // end if is_array( $backup_options ).


				$backups[basename( $file )] = array(
					array( basename( $file ), $main_string . '<br><span class="description" style="color: #AAA; display: inline-block; margin-top: 5px;">' . basename( $file ) . '</span>' ),
					$detected_type,
					$file_size,
					$integrity,
				);


				$backup_sort_dates[basename( $file)] = $modified_time;

			} // End foreach().

		} // End if.

		// Sort backup by date.
		arsort( $backup_sort_dates );
		// Re-arrange backups based on sort dates.
		$sorted_backups = array();
		foreach( $backup_sort_dates as $backup_file => $backup_sort_date ) {
			$sorted_backups[$backup_file] = $backups[$backup_file];
			unset( $backups[$backup_file] );
		}
		unset( $backups );


		return $sorted_backups;

	} // End backups_list().


	// 1128
	public static function getDatArrayFromZip( $file ) {
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		$zipbuddy = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		$serial = self::get_serial_from_file( $file );
		
		if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $find = 'wp-content/uploads/backupbuddy_temp/' . $serial . '/backupbuddy_dat.php' ) === true ) { // Post 2.0 full backup
			
		} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $find = 'wp-content/uploads/temp_' . $serial . '/backupbuddy_dat.php' ) === true ) { // Pre 2.0 full backup
			
		} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $find = 'backupbuddy_dat.php' ) === true ) { // DB backup
			
		} else { // Could not find DAt file.
			return false;
		}
		
		// Calculate temp directory & lock it down.
		$temp_dir = get_temp_dir();
		$destination = $temp_dir;
		if ( ( ( ! file_exists( $destination ) ) && ( false === mkdir( $destination ) ) ) ) {
			$error = 'Error #458485945b: Unable to create temporary location.';
			pb_backupbuddy::status( 'error', $error );
			die( $error );
		}

		// If temp directory is within webroot then lock it down.
		$temp_dir = str_replace( '\\', '/', $temp_dir ); // Normalize for Windows.
		$temp_dir = rtrim( $temp_dir, '/\\' ) . '/'; // Enforce single trailing slash.
		if ( FALSE !== stristr( $temp_dir, ABSPATH ) ) { // Temp dir is within webroot.
			pb_backupbuddy::anti_directory_browsing( $destination );
		}
		unset( $temp_dir );
		
		$destFilename = 'temp_dat_read-' . $serial . '.php';
		$extractions = array( $find => $destFilename );
		$extract_result = $zipbuddy->extract( $file, $destination, $extractions );
		if ( false === $extract_result ) { // failed.
			return array();
		} else {
			$datArray = self::get_dat_file_array( $destination . 'temp_dat_read-' . $serial . '.php' );
			@unlink( $temp_dir . $destFilename );
			if ( is_array( $datArray ) ) {
				return $datArray;
			} else {
				return array();
			}
		}
		
	} // End getDatContentsFromZip().
	
	
	
	/* importbuddy()
	 *
	 * IMPORTANT: If outputting to browser (no output file) must die() after outputting content if using AJAX. Do not output to browser anything after this function in this case.
	 * IMPORTANT: _ALWAYS_ returns FALSE if no importbuddy pass hash is passed AND no pass hash is set in the Settings page.
	 * If $output_file is blank then importbuddy will either be returned or returned to the brwoser as a downloaded file based on 3rd parameter.
	 * If $output_file is defined then returns true on success writing file, else false.
	 *
	 */
	public static function importbuddy( $output_file = '', $importbuddy_pass_hash = '', $return_not_echo = false ) {

		pb_backupbuddy::set_greedy_script_limits(); // Some people run out of PHP memory.

		if ( $importbuddy_pass_hash == '' ) {
			if ( !isset( pb_backupbuddy::$options ) ) {
				pb_backupbuddy::load();
			}
			$importbuddy_pass_hash = pb_backupbuddy::$options['importbuddy_pass_hash'];
		}

		if ( $importbuddy_pass_hash == '' ) {
			$message = 'Warning #9032 - You have not set an ImportBuddy password on the BackupBuddy Settings page. Once this password is set a copy of the importbuddy.php file needed to restore your backup will be included in Full backup zip files for convenience. It may manually be downloaded from the Restore / Migrate page.';
			pb_backupbuddy::status( 'warning', $message );
			return false;
		}

		pb_backupbuddy::status( 'details', 'Loading importbuddy core file into memory.' );
		$output = file_get_contents( pb_backupbuddy::plugin_path() . '/_importbuddy/_importbuddy.php' );
		if ( $importbuddy_pass_hash != '' ) {
			$output = preg_replace('/#PASSWORD#/', $importbuddy_pass_hash, $output, 1 ); // Only replaces first instance.
		}

		$version_string = pb_backupbuddy::settings( 'version' ) . ' (downloaded ' . date( DATE_W3C ) . ')';

		// If on DEV system (.git dir exists) then append some details on current.
		if ( defined( 'BACKUPBUDDY_DEV' ) && ( true === BACKUPBUDDY_DEV ) ) {
			if ( @file_exists( pb_backupbuddy::plugin_path() . '/.git/logs/HEAD' ) ) {
				$commit_log = escapeshellarg( pb_backupbuddy::plugin_path() . '/.git/logs/HEAD' );
				$commit_line = str_replace( '\'', '`', exec( "tail -n 1 {$commit_log}" ) );
				$version_string .= ' <span style="font-size: 8px;">[DEV: ' . $commit_line . ']</span>';
			}
		}

		$output = preg_replace('/#VERSION#/', $version_string, $output, 1 ); // Only replaces first instance.

		// PACK IMPORTBUDDY
		$_packdata = array( // NO TRAILING OR PRECEEDING SLASHES!

			'_importbuddy/importbuddy'							=>		'importbuddy',
			'classes/_migrate_database.php'						=>		'importbuddy/classes/_migrate_database.php',
			'classes/core.php'									=>		'importbuddy/classes/core.php',
			'classes/import.php'								=>		'importbuddy/classes/import.php',
			'classes/restore.php'								=>		'importbuddy/classes/restore.php',
			'classes/_restoreFiles.php'							=>		'importbuddy/classes/_restoreFiles.php',
			'classes/remote_api.php'							=>		'importbuddy/classes/remote_api.php',
			
			'js/jquery.leanModal.min.js'						=>		'importbuddy/js/jquery.leanModal.min.js',
			'js/jquery.joyride-2.0.3.js'						=>		'importbuddy/js/jquery.joyride-2.0.3.js',
			'js/modernizr.mq.js'								=>		'importbuddy/js/modernizr.mq.js',
			'css/joyride.css'									=>		'importbuddy/css/joyride.css',

			'images/working.gif'								=>		'importbuddy/images/working.gif',
			'images/bullet_go.png'								=>		'importbuddy/images/bullet_go.png',
			'images/favicon.png'								=>		'importbuddy/images/favicon.png',
			'images/sort_down.png'								=>		'importbuddy/images/sort_down.png',
			'images/icon_menu_32x32.png'						=>		'importbuddy/images/icon_menu_32x32.png',

			'lib/dbreplace'										=>		'importbuddy/lib/dbreplace',
			'lib/dbimport'										=>		'importbuddy/lib/dbimport',
			'lib/commandbuddy'									=>		'importbuddy/lib/commandbuddy',
			'lib/zipbuddy'										=>		'importbuddy/lib/zipbuddy',
			'lib/mysqlbuddy'									=>		'importbuddy/lib/mysqlbuddy',
			'lib/textreplacebuddy'								=>		'importbuddy/lib/textreplacebuddy',
			'lib/cpanel'										=>		'importbuddy/lib/cpanel',

			'pluginbuddy'										=>		'importbuddy/pluginbuddy',

			'controllers/pages/server_info'						=>		'importbuddy/controllers/pages/server_info',
			'controllers/pages/server_tools.php'				=>		'importbuddy/controllers/pages/server_tools.php',

			// Stash
			'destinations/stash/lib/class.itx_helper.php'		=>		'importbuddy/classes/class.itx_helper.php',
			'destinations/_s3lib/aws-sdk/lib/requestcore'		=>		'importbuddy/lib/requestcore',

		);

		pb_backupbuddy::status( 'details', 'Loading each file into memory for writing master importbuddy file.' );

		$output .= "\n<?php /*\n###PACKDATA,BEGIN\n";
		foreach( $_packdata as $pack_source => $pack_destination ) {
			$pack_source = '/' . $pack_source;
			if ( is_dir( pb_backupbuddy::plugin_path() . $pack_source ) ) {
				$files = pb_backupbuddy::$filesystem->deepglob( pb_backupbuddy::plugin_path() . $pack_source );
			} else {
				$files = array( pb_backupbuddy::plugin_path() . $pack_source );
			}
			foreach( $files as $file ) {
				if ( is_file( $file ) ) {
					$source = str_replace( pb_backupbuddy::plugin_path(), '', $file );
					$destination = $pack_destination . substr( $source, strlen( $pack_source ) );
					$output .= "###PACKDATA,FILE_START,{$source},{$destination}\n";
					$output .= base64_encode( file_get_contents( $file ) );
					$output .= "\n";
					$output .= "###PACKDATA,FILE_END,{$source},{$destination}\n";
				}
			}
		}
		$output .= "###PACKDATA,END\n*/";
		$output .= "\n\n\n\n\n\n\n\n\n\n";

		if ( true === $return_not_echo ) {
			return $output;
		}

		if ( $output_file == '' ) { // No file so output to browser.
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: text/plain; name=importbuddy.php' );
			header( 'Content-Disposition: attachment; filename=importbuddy.php' );
			header( 'Expires: 0' );
			header( 'Content-Length: ' . strlen( $output ) );

			pb_backupbuddy::flush();
			echo $output;
			pb_backupbuddy::flush();

			// BE SURE TO die() AFTER THIS AND NOT OUTPUT TO BROWSER!
		} else { // Write to file.
			pb_backupbuddy::status( 'details', 'Writing importbuddy master file to disk.' );
			if ( false === file_put_contents( $output_file, $output ) ) {
				pb_backupbuddy::status( 'error', 'Error #483894: Unable to write to file `' . $output_file . '`.' );
				return false;
			} else {
				return true;
			}
		}
		
	} // End importbuddy().
	
	
	
	/* pretty_backup_type()
	 *
	 * Return a nice human string for a specified backup type.
	 *
	 * @param 	string	$type	Type of backup. Eg. full, db, files.
	 * @return	string			Pretty name for type of backup. Eg. Full, Database, Files.
	 */
	public static function pretty_backup_type( $type ) {
		$types = array(
			'full' => 'Full',
			'db' => 'Database',
			'files' => 'Files',
		);
		
		if ( isset( $types[ $type ] ) ) {
			return $types[ $type ];
		} else {
			return $type;
		}
	} // End pretty_backup_type().
	
	
	
	/* pretty_destination_type()
	 *
	 * Take a destination type slug and change it into a user-friendly display of the destination type.
	 *
	 * @param	string		Internal destination slug. Eg: s3
	 * @return	string		Friendly destination title. Eg: Amazon S3
	 *
	 */
	public static function pretty_destination_type( $type ) {
		if ( $type == 'rackspace' ) {
			return 'Rackspace';
		} elseif ( $type == 'email' ) {
			return 'Email';
		} elseif ( $type == 's3' ) {
			return 'Amazon S3';
		} elseif ( $type == 's32' ) {
			return 'Amazon S3 v2';
		} elseif ( $type == 'ftp' ) {
			return 'FTP';
		} elseif ( $type == 'stash' ) {
			return 'BackupBuddy Stash';
		} elseif ( $type == 'stash2' ) {
			return 'BackupBuddy Stash v2';
		} elseif ( $type == 'sftp' ) {
			return 'sFTP';
		} elseif ( $type == 'dropbox' ) {
			return 'Dropbox';
		} elseif ( $type == 'dropbox2' ) {
			return 'Dropbox v2';
		} elseif ( $type == 'gdrive' ) {
			return 'Google Drive';
		} elseif ( $type == 'site' ) {
			return 'BackupBuddy Deployment';
		} else {
			return $type;
		}
	} // End pretty_destination_type().



	/* build_icicle()
	 *
	 * Build directory tree for use with the "icicle" javascript library for the graphical directory display on Server Tools page.
	 *
	 * @param	string	$dir			
	 * @param	?		$base			
	 * @param	?		$icicle_json	
	 * @param	int		$max_depth		Maximum depth of tree to display.  Note that deeper depths are still traversed for size calculations. Default: 10
	 * @param	int		$depth_count	Default: 0
	 * @param	bool	$is_root		Default: true
	 *
	 */
	public static function build_icicle( $dir, $base, $icicle_json, $max_depth = 10, $depth_count = 0, $is_root = true ) {
		$bg_color = '005282';

		$depth_count++;
		$bg_color = dechex( hexdec( $bg_color ) - ( $depth_count * 15 ) );

		$icicle_json = '{' . "\n";

		$dir_name = $dir;
		$dir_name = str_replace( ABSPATH, '', $dir );
		$dir_name = str_replace( '\\', '/', $dir_name );

		$dir_size = 0;
		$sub = opendir( $dir );
		$has_children = false;
		while( $file = readdir( $sub ) ) {
			if ( ( $file == '.' ) || ( $file == '..' ) ) {
				continue; // Next loop.
			} elseif ( is_dir( $dir . '/' . $file ) ) {

				$dir_array = '';
				$response = self::build_icicle( $dir . '/' . $file, $base, $dir_array, $max_depth, $depth_count, false );
				if ( ( $max_depth-1 > 0 ) || ( $max_depth == -1 ) ) { // Only adds to the visual tree if depth isnt exceeded.
					if ( $max_depth > 0 ) {
						$max_depth = $max_depth - 1;
					}

					if ( $has_children === false ) { // first loop add children section
						$icicle_json .= '"children": [' . "\n";
					} else {
						$icicle_json .= ',';
					}
					$icicle_json .= $response[0];

					$has_children = true;
				}
				$dir_size += $response[1];
				unset( $response );
				unset( $file );


			} else {
				$stats = stat( $dir . '/' . $file );
				$dir_size += $stats['size'];
				unset( $file );
			}
		}
		closedir( $sub );
		unset( $sub );

		if ( $has_children === true ) {
			$icicle_json .= ' ]' . "\n";
		}

		if ( $has_children === true ) {
			$icicle_json .= ',';
		}

		$icicle_json .= '"id": "node_' . str_replace( '/', ':', $dir_name ) . ': ^' . str_replace( ' ', '~', pb_backupbuddy::$format->file_size( $dir_size ) ) . '"' . "\n";

		$dir_name = str_replace( '/', '', strrchr( $dir_name, '/' ) );
		if ( $dir_name == '' ) { // Set root to be /.
			$dir_name = '/';
		}
		$icicle_json .= ', "name": "' . $dir_name . ' (' . pb_backupbuddy::$format->file_size( $dir_size ) . ')"' . "\n";

		$icicle_json .= ',"data": { "$dim": ' . ( $dir_size + 10 ) . ', "$color": "#' . str_pad( $bg_color, 6, '0', STR_PAD_LEFT ) . '" }' . "\n";
		$icicle_json .= '}';

		if ( $is_root !== true ) {
			//$icicle_json .= ',x';
		}

		return array( $icicle_json, $dir_size );
	} // End build_icicle().



	/* preflish_check()
	 *
	 * description
	 *
	 */
	// return array of tests and their results.
	public static function preflight_check() {
		$tests = array();
		
		// MULTISITE BETA WARNING.
		if ( is_multisite() && backupbuddy_core::is_network_activated() && !defined( 'PB_DEMO_MODE' ) ) { // Multisite installation.
			$tests[] = array(
				'test'		=>	'multisite_beta',
				'success'	=>	false,
				'message'	=>	'WARNING: BackupBuddy Multisite functionality is EXPERIMENTAL and NOT officially supported. Multiple issues are known. Usage of it is at your own risk and should not be relied upon. Standalone WordPress sites are suggested. You may use the "Export" feature to export your subsites into standalone WordPress sites. To enable experimental BackupBuddy Multisite functionality you must add the following line to your wp-config.php file: <b>define( \'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT\', true );</b>'
			);
		} // end network-activated multisite.

		// LOOPBACKS TEST.
		if ( ( $loopback_response = self::loopback_test() ) === true ) {
			$success = true;
			$message = '';
		} else { // failed
			$success = false;
			if ( defined( 'ALTERNATE_WP_CRON' ) && ( ALTERNATE_WP_CRON == true ) ) {
				$message = __('Running in Alternate WordPress Cron mode. HTTP Loopback Connections are not enabled on this server but you have overridden this in the wp-config.php file (this is a good thing).', 'it-l10n-backupbuddy' ) . ' <a href="http://ithemes.com/codex/page/BackupBuddy:_Frequent_Support_Issues#HTTP_Loopback_Connections_Disabled" target="_blank">' . __('Additional Information Here', 'it-l10n-backupbuddy' ) . '</a>.';
			} else {
				$message = __('HTTP Loopback Connections are not enabled on this server or are not functioning properly. You may encounter stalled, significantly delayed backups, or other difficulties. See details in the box below. This may be caused by a conflicting plugin such as a caching plugin.', 'it-l10n-backupbuddy' ) . ' <a href="http://ithemes.com/codex/page/BackupBuddy:_Frequent_Support_Issues#HTTP_Loopback_Connections_Disabled" target="_blank">' . __('Click for instructions on how to resolve this issue.', 'it-l10n-backupbuddy' ) . '</a>';
				$message .= ' <b>Details:</b> <textarea style="height: 50px; width: 100%;">' . $loopback_response . '</textarea>';
			}
		}
		$tests[] = array(
			'test'		=>	'loopbacks',
			'success'	=>	$success,
			'message'	=>	$message,
		);

		// POSSIBLE CACHING PLUGIN CONFLICT WARNING.
		$success = true;
		$message = '';
		$found_plugins = array();
		if ( ! is_multisite() ) {
			$active_plugins = serialize( get_option( 'active_plugins' ) );
			foreach( self::$warn_plugins as $warn_plugin => $warn_plugin_title ) {
				if ( FALSE !== strpos( $active_plugins, $warn_plugin ) ) { // Plugin active.
					$found_plugins[] = $warn_plugin_title;
					$success = false;
				}
			}
		}
		if ( count( $found_plugins ) > 0 ) {
			$message = __( 'One or more caching plugins were detected as activated. Some caching plugin configurations may possibly cache & interfere with backup processes or WordPress cron. If you encounter problems clear the caching plugin\'s cache (deactivating the plugin may help) to troubleshoot.', 'it-l10n-backupbuddy' ) . ' ';
			$message .= __( 'Activated caching plugins detected:', 'it-l10n-backupbuddy' ) . ' ';
			$message .= implode( ', ', $found_plugins );
			$message .= '.';
		}
		$tests[] = array(
			'test'		=>	'loopbacks',
			'success'	=>	$success,
			'message'	=>	$message,
		);

		// WORDPRESS IN SUBDIRECTORIES TEST.
		$wordpress_locations = self::get_wordpress_locations();
		if ( count( $wordpress_locations ) > 0 ) {
			$success = false;
			$message = __( 'WordPress may have been detected in one or more subdirectories. Backing up multiple instances of WordPress may result in server timeouts due to increased backup time. You may exclude WordPress directories via the Settings page. Detected non-excluded locations:', 'it-l10n-backupbuddy' ) . ' ' . implode( ', ', $wordpress_locations );
		} else {
			$success = true;
			$message = '';
		}
		$tests[] = array(
			'test'		=>	'wordpress_subdirectories',
			'success'	=>	$success,
			'message'	=>	$message,
		);

		// Log file directory writable for status logging.
		$status_directory = backupbuddy_core::getLogDirectory();
		if ( ! file_exists( $status_directory ) ) {
			if ( false === pb_backupbuddy::anti_directory_browsing( $status_directory, $die = false ) ) {
				$success = false;
				$message = 'The status log file directory `' . $status_directory . '` is not creatable or permissions prevent access. Verify permissions of it and/or its parent directory. Backup status information will be unavailable until this is resolved.';
			}
		}
		if ( ! is_writable( $status_directory ) ) {
			$success = false;
			$message = 'The status log file directory `' . $status_directory . '` is not writable. Please verify permissions before creating a backup. Backup status information will be unavailable until this is resolved.';
		} else {
			$success = true;
			$message = '';
		}
		$tests[] = array(
			'test'		=>	'status_directory_writable',
			'success'	=>	$success,
			'message'	=>	$message,
		);

		// CHECK ZIP AVAILABILITY.
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );

		if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
			pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		}

		/***** BEGIN LOOKING FOR UNFINISHED RECENT BACKUPS *****/
		if ( '' != pb_backupbuddy::$options['last_backup_serial'] ) {
			$lastBackupFileoptions = backupbuddy_core::getLogDirectory() . 'fileoptions/' . pb_backupbuddy::$options['last_backup_serial'] . '.txt';
			if ( file_exists( $lastBackupFileoptions ) ) {
				require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
				pb_backupbuddy::status( 'details', 'Fileoptions instance #32.' );
				$backup_options = new pb_backupbuddy_fileoptions( $lastBackupFileoptions, $read_only = true );
				if ( true !== ( $result = $backup_options->is_ok() ) || ( ! isset( $backup_options->options['updated_time'] ) ) ) {
					// NOTE: If this files during a backup it may try to read the fileoptions file too early due to the last_backup_serial being set. Suppressing errors for now.
					pb_backupbuddy::status( 'details', 'Unable to retrieve fileoptions file (this is normal if a backup is currently in process & may be ignored) `' . backupbuddy_core::getLogDirectory() . 'fileoptions/' . pb_backupbuddy::$options['last_backup_serial'] . '.txt' . '`. Err 54478236765. Details: `' . $result . '`.' );
				} else {
					if ( $backup_options->options['updated_time'] < 180 ) { // Been less than 3min since last backup.

						if ( !empty( $backup_options->options['steps'] ) ) { // Look for incomplete steps.
							$found_unfinished = false;
							foreach( (array)$backup_options->options['steps'] as $step ) {
								if ( $step['finish_time'] == '0' ) { // Found an unfinished step.
									$found_unfinished = true;
									break;
								}
							} // end foreach.

							if ( $found_unfinished === true ) {
								$tests[] = array(
									'test'		=>	'recent_backup',
									'success'	=>	false,
									'message'	=>	__('A backup was recently started and reports unfinished steps. You should wait unless you are sure the previous backup has completed or failed to avoid placing a heavy load on your server.', 'it-l10n-backupbuddy' ) .
										' Last updated: ' . pb_backupbuddy::$format->date( $backup_options->options['updated_time'] ) . '; '.
										' Serial: ' . pb_backupbuddy::$options['last_backup_serial']
								,
								);
							} // end $found_unfinished === true.

						} // end if.

					}
				}
			}
		}
		/***** END LOOKING FOR UNFINISHED RECENT BACKUPS *****/

		/***** BEGIN LOOKING FOR BACKUP FILES IN SITE ROOT *****/
		$files = glob( ABSPATH . 'backup-*.zip' );
		if ( !is_array( $files ) || empty( $files ) ) {
			$files = array();
		}
		foreach( $files as &$file ) {
			$file = basename( $file );
		}
		if ( count( $files ) > 0 ) {
			$files_string = implode( ', ', $files );
			$tests[] = array(
				'test'		=>	'root_backups-' . $files_string,
				'success'	=>	false,
				'message'	=>	'One or more backup files, `' . $files_string . '` was found in the root directory of this site. This may be leftover from a recent restore. You should usually remove backup files from the site root for security.',
			);
		}
		/***** END LOOKING FOR BACKUP FILES IN SITE ROOT *****/
		
		if ( ! is_writable( backupbuddy_core::getBackupDirectory() ) ) {
			$tests[] = array(
				'test'		=>	'backup_dir_permissions',
				'success'	=>	false,
				'message'	=>	'Invalid backup directory permissions. Verify the directory `' . backupbuddy_core::getBackupDirectory() . '` is writable.'
			);
		}
		
		/* THIS MAY NOT EXIST YET.
		if ( ! is_writable( backupbuddy_core::getTempDirectory() ) ) {
			$tests[] = array(
				'test'		=>	'temp_dir_permissions',
				'success'	=>	false,
				'message'	=>	'Invalid temp directory permissions. Verify the directory `' . backupbuddy_core::getTempDirectory() . '` is writable.'
			);
		}
		*/
		
		return $tests;

	} // End preflight_check().



	// returns true on success, error message otherwise.
	/*	loopback_test()
	 *	
	 *	Connects back to same site via AJAX call to an AJAX slug that has NOT been registered.
	 *	WordPress AJAX returns a -1 (or 0 in newer version?) for these. Also not logged into
	 *	admin when connecting back. Checks to see if body contains -1 / 0. If loopbacks are not
	 *	enabled then will fail connecting or do something else.
	 *	
	 *	
	 *	@param		
	 *	@return		boolean		True on success, string error message otherwise.
	 */
	public static function loopback_test() {
		$loopback_url = admin_url('admin-ajax.php');
		pb_backupbuddy::status( 'details', 'Testing loopback connections by connecting back to site at the URL: `' . $loopback_url . '`. It should display simply "0" or "-1" in the body.' );

		$response = wp_remote_get(
			$loopback_url,
			array(
				'method' => 'GET',
				'timeout' => 8, // X second delay. A loopback should be very fast.
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => null,
				'cookies' => array()
			)
		);
		
		global $backupbuddy_loopback_details;
		if ( is_wp_error( $response ) ) { // Loopback failed. Some kind of error.
			$error = $response->get_error_message();
			$error = 'Error #9038: Loopback test error: `' . $error . '`. URL: `' . $loopback_url . '`. If you need to contact your web host, tell them that when PHP tries to connect back to the site at the URL `' . $loopback_url . '` via curl (or other fallback connection method built into WordPress) that it gets the error `' . $error . '`. This means that WordPress\' built-in simulated cron system cannot function properly, breaking some WordPress features & subsequently some plugins. There may be a problem with the server configuration (eg local DNS problems, mod_security, etc) preventing connections from working properly.';
			pb_backupbuddy::status( 'error', $error );
			$backupbuddy_loopback_details = 'Error: ' . $error;
			return $error;
		} else {
			if ( ( $response['body'] == '-1' ) || ( $response['body'] == '0' ) ) { // Loopback succeeded.
				pb_backupbuddy::status( 'details', 'HTTP Loopback test success. Returned `' . $response['body'] . '`.' );
				$backupbuddy_loopback_details = 'Returned: `' . $response['body'] . '` with code `' . $response['response']['code'] . ' ' . $response['response']['message'] . '`.';
				return true;
			} else { // Loopback failed.
				$error = 'Warning #9038: Connected to server but unexpected output: `' . htmlentities( $response['body'] . '`. Code: `' . $response['response']['code'] . ' ' . $response['response']['message'] . ' ' . $response['response']['message'] . '`.' );
				pb_backupbuddy::status( 'warning', $error );
				$backupbuddy_loopback_details = $error;
				return $error;
			}
		}
	} // End loopback_test().
	
	
	
	/* cronback_test()
	 *
	 * description
	 *
	 */
	public static function cronback_test() {
		$gmt_time = microtime( true );
		$doing_wp_cron = sprintf( '%.22F', $gmt_time );
		$cronURL = add_query_arg( 'doing_wp_cron', $doing_wp_cron, site_url( 'wp-cron.php' ) );
		
		pb_backupbuddy::status( 'details', 'Testing wp-cron.php loopback connections by connecting back to site at the URL: `' . $cronURL . '`. Expect 200 OK response.' );
		
		$cron_request = array(
			'url'  => $cronURL,
			'key'  => $doing_wp_cron,
			'args' => array(
				'timeout'   => 4,
				'blocking'  => true,
				/** This filter is documented in wp-includes/class-http.php */
				'sslverify' => apply_filters( 'https_local_ssl_verify', false )
			)
		);
		
		$response = wp_remote_post( $cron_request['url'], $cron_request['args'] );
		
		global $backupbuddy_cronback_details;
		if ( is_wp_error( $response ) ) { // Loopback failed. Some kind of error.
			$error = $response->get_error_message();
			$error = 'wp-cron.php loopback test error: `' . $error . '`. URL: `' . $cronURL . '`. If you need to contact your web host, tell them that when PHP tries to connect back to the site at the URL `' . $cronURL . '` via curl (or other fallback connection method built into WordPress) that it gets the error `' . $error . '`. This means that WordPress\' built-in simulated cron system cannot function properly, breaking some WordPress features & subsequently some plugins. There may be a problem with the server configuration (eg local DNS problems, mod_security, etc) preventing connections from working properly.';
			pb_backupbuddy::status( 'error', $error );
			$backupbuddy_cronback_details = $error;
			return $error;
		} else {
			pb_backupbuddy::status( 'details', 'wp-cron.php loopback test success. Returned `' . $response['body'] . '`.' );
			$backupbuddy_cronback_details = 'Returned: `' . $response['body'] . '` with code `' . $response['response']['code'] . ' ' . $response['response']['message'] . '`.';
			return true;
		}
	} // End cronback_test().



	/* get_wordpress_locations()
	 *
	 * Get an array of subdirectories where potential WordPress installations have been detected.
	 *
	 * @return	array	Array of full paths, WITHOUT trailing slashes.
	 *
	 */
	public static function get_wordpress_locations() {
		$wordpress_locations = array();

		$files = glob( ABSPATH . '*/' );
		if ( !is_array( $files ) || empty( $files ) ) {
			$files = array();
		}

		foreach( $files as $file ) {
			if ( file_exists( $file . 'wp-config.php' ) ) {
				$wordpress_locations[]  = rtrim( '/' . str_replace( ABSPATH, '', $file ), '/\\' );
			}
		}

		// Remove any excluded directories from showing up in this.
		$directory_exclusions = self::get_directory_exclusions( pb_backupbuddy::$options['profiles'][0] ); // default profile.
		$wordpress_locations = array_diff( $wordpress_locations, $directory_exclusions );

		return $wordpress_locations;
	} // End get_wordpress_locations().
	
	
	
	/*	get_site_size()
	 *	
	 *	Returns an array with the site size and the site size sans exclusions. Saves updates stats in options.
	 *	
	 *	@return		array		Index 0: site size; Index 1: site size sans excluded files/dirs.; Index 2: Total number of objects (files+folders); Index 3: Total objects sans excluded files/folders.
	 */
	public static function get_site_size() {
		$exclusions = backupbuddy_core::get_directory_exclusions( pb_backupbuddy::$options['profiles'][0] );
		$dir_array = array();
		$result = pb_backupbuddy::$filesystem->dir_size_map( ABSPATH, ABSPATH, $exclusions, $dir_array );
		unset( $dir_array ); // Free this large chunk of memory.

		$total_size = pb_backupbuddy::$options['stats']['site_size'] = $result[0];
		$total_size_excluded = pb_backupbuddy::$options['stats']['site_size_excluded'] = $result[1];
		$total_objects = pb_backupbuddy::$options['stats']['site_objects'] = $result[2];
		$total_objects_excluded = pb_backupbuddy::$options['stats']['site_objects_excluded'] = $result[3];
		pb_backupbuddy::$options['stats']['site_size_updated'] = time();
		pb_backupbuddy::save();

		return array( $total_size, $total_size_excluded, $total_objects, $total_objects_excluded );
	} // End get_site_size().



	/*	get_database_size()
	 *	
	 *	Return array of database size, database sans exclusions.
	 *	
	 *	@return		array			Index 0: db size, Index 1: db size sans exclusions.
	 */
	public static function get_database_size( $profile_id = 0 ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$prefix_length = strlen( $wpdb->prefix );

		$additional_includes = backupbuddy_core::get_mysqldump_additional( 'includes', pb_backupbuddy::$options['profiles'][$profile_id] );
		$additional_excludes = backupbuddy_core::get_mysqldump_additional( 'excludes', pb_backupbuddy::$options['profiles'][$profile_id] );

		$total_size = 0;
		$total_size_with_exclusions = 0;
		$rows = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
		foreach( $rows as $row ) {
			$excluded = true; // Default.

			// TABLE STATUS.
			$rowsb = $wpdb->get_results( "CHECK TABLE `{$row['Name']}`", ARRAY_A );
			foreach( $rowsb as $rowb ) {
				if ( $rowb['Msg_type'] == 'status' ) {
					$status = $rowb['Msg_text'];
				}
			}
			unset( $rowsb );

			// TABLE SIZE.
			$size = ( $row['Data_length'] + $row['Index_length'] );
			$total_size += $size;


			// HANDLE EXCLUSIONS.
			if ( pb_backupbuddy::$options['profiles'][$profile_id]['backup_nonwp_tables'] == 0 ) { // Only matching prefix.
				if ( ( substr( $row['Name'], 0, $prefix_length ) == $prefix ) OR ( in_array( $row['Name'], $additional_includes ) ) ) {
					if ( !in_array( $row['Name'], $additional_excludes ) ) {
						$total_size_with_exclusions += $size;
						$excluded = false;
					}
				}
			} else { // All tables.
				if ( !in_array( $row['Name'], $additional_excludes ) ) {
					$total_size_with_exclusions += $size;
					$excluded = false;
				}
			}

		}

		pb_backupbuddy::$options['stats']['db_size'] = $total_size;
		pb_backupbuddy::$options['stats']['db_size_excluded'] = $total_size_with_exclusions;
		pb_backupbuddy::$options['stats']['db_size_updated'] = time();
		pb_backupbuddy::save();

		unset( $rows );

		return array( $total_size, $total_size_with_exclusions );
	} // End get_database_size().



	/* kick_db()
	 *
	 * Attempt to verify the database server is still alive and functioning.  If not, try to re-establish connection.
	 *
	 */
	public static function kick_db() {

		$kick_db = true; // Change true to false for debugging purposes to disable kicker.

		// Need to make sure the database connection is active. Sometimes it goes away during long bouts doing other things -- sigh.
		// This is not essential so use include and not require (suppress any warning)
		if ( $kick_db === true ) {
			@include_once( pb_backupbuddy::plugin_path() . '/lib/wpdbutils/wpdbutils.php' );
			if ( class_exists( 'pluginbuddy_wpdbutils' ) ) {
				// This is the database object we want to use
				global $wpdb;

				// Get our helper object and let it use us to output status messages
				$dbhelper = new pluginbuddy_wpdbutils( $wpdb );

				// If we cannot kick the database into life then signal the error and return false which will stop the backup
				// Otherwise all is ok and we can just fall through and let the function return true
				if ( !$dbhelper->kick() ) {
					pb_backupbuddy::status( 'error', __('Database Server has gone away, unable to update remote destination transfer status. This is most often caused by mysql running out of memory or timing out far too early. Please contact your host.', 'it-l10n-backupbuddy' ) );
				}
			} else {
				// Utils not available so cannot verify database connection status - just notify
				pb_backupbuddy::status( 'details', __('Database Server connection status unverified.', 'it-l10n-backupbuddy' ) );
			}
		}

	} // End kick_db().



	/* verify_directories()
	 *
	 * Verify existance and security of key directories. Result available via global $pb_backupbuddy_directory_verification with return value.
	 *
	 * @return		bool		true on success creating / verifying, else false.
	 *
	 */
	public static function verify_directories( $skipTempGeneration = false ) {
		
		$success = true;

		// Update backup directory if unable to write to the defined one.
		if ( ! @is_writable( backupbuddy_core::getBackupDirectory() ) ) {
			pb_backupbuddy::status( 'details', 'Backup directory invalid. Updating from `' . backupbuddy_core::getBackupDirectory() . '` to default.' );
			pb_backupbuddy::$options['backup_directory'] = ''; // Reset to default (blank).
			pb_backupbuddy::save();
		}
		$response = pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getBackupDirectory(), $die = false );
		if ( false === $response ) {
			$success = false;
		}

		// Update log directory if unable to write to the defined one.
		if ( ! @is_writable( backupbuddy_core::getLogDirectory() ) ) {
			pb_backupbuddy::status( 'details', 'Log directory invalid. Updating from `' . backupbuddy_core::getLogDirectory() . '` to default.' );
			pb_backupbuddy::$options['log_directory'] = ''; // Reset to default (blank).
			pb_backupbuddy::save();
		}
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getLogDirectory(), $die = false );
		if ( false === $response ) {
			$success = false;
		}

		// Update temp directory if unable to write to the defined one.
		if ( true !== $skipTempGeneration ) {
			if ( ! @is_writable( backupbuddy_core::getTempDirectory() ) ) {
				pb_backupbuddy::status( 'details', 'Temporary directory invalid. Updating from `' . backupbuddy_core::getTempDirectory() . '` to default.' );
				pb_backupbuddy::$options['temp_directory'] = '';
				pb_backupbuddy::save();
			}
			pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
			if ( false === $response ) {
				$success = false;
			}
		}
		
		// If temp directory exists (should only be transient but just in case it is hanging around) make sure it's secured. BB will try to delete this directory but if it can't it will at least be checked to be secure.
		if ( file_exists( backupbuddy_core::getTempDirectory() ) ) {
			pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
		}

		global $pb_backupbuddy_directory_verification;
		$pb_backupbuddy_directory_verification = $success;
		return $success;

	} // End verify_directories().



	/* schedule_single_event()
	 *
	 * API to wp_schedule_single_event() that also verifies that the schedule actually got created in WordPRess.
	 * Sometimes the database rejects this update so we need to do actual verification.
	 *
	 * @return	boolean			True on verified schedule success, else false.
	 */
	public static function schedule_single_event( $time, $method, $args ) {
		$tag = 'backupbuddy_cron';
		$schedule_result = wp_schedule_single_event( $time, $tag, array( $method, $args ) ); // Schedule.
		if ( FALSE === $schedule_result ) {
			pb_backupbuddy::status( 'warning', 'Warning #48393489: This may not be a fatal error. Ignore if backup proceeds without other errors. Unable to create schedule as wp_schedule_single_event() returned false. A plugin may have prevented it or it is already scheduled.' );
			return false;
		}
		$next_scheduled = wp_next_scheduled( $tag, array( $method, $args ) ); // Retrieve schedule to verify it stuck.
		if ( FALSE === $next_scheduled ) {
			pb_backupbuddy::status( 'error', 'WordPress reported success scheduling BUT wp_next_scheduled() could NOT confirm schedule existance. The database may have rejected the update.' );
			return false;
		}

		return true;
	} // End schedule_single_event().



	/* schedule_event()
	 *
	 * API to wp_schedule_event() that also verifies that the schedule actually got created in WordPRess.
	 * Sometimes the database rejects this update so we need to do actual verification.
	 *
	 * @return	boolean			True on verified schedule success, else false.
	 */
	public static function schedule_event( $time, $period, $method, $args ) {
		$tag = 'backupbuddy_cron';
		$schedule_result = wp_schedule_event( $time, $period, $tag, array( $method, $args ) );
		$next_scheduled = wp_next_scheduled( $tag, array( $method, $args ) );
		if ( FALSE === $schedule_result ) {
			pb_backupbuddy::status( 'error', 'Error #83928234: This may not be a fatal error. Ignore if backup proceeds without other errors. Unable to create schedule as wp_schedule_event() returned false. A plugin may have prevented it or it is already scheduled.' );
			return false;
		}
		if ( FALSE === $next_scheduled ) {
			pb_backupbuddy::status( 'error', 'WordPress reported success scheduling BUT wp_next_scheduled() could NOT confirm schedule existance. The database may have rejected the update.' );
			return false;
		}

		return true;
	} // End schedule_event().



	/* unschedule_event()
	 *
	 * API to wp_unschedule_event() that also verifies that the schedule actually got removed WordPRess.
	 * Sometimes the database rejects this update so we need to do actual verification.
	 *
	 * @return	boolean			True on verified schedule deletion success, else false.
	 */
	public static function unschedule_event( $time, $tag, $args ) {
		$unschedule_result = wp_unschedule_event( $time, $tag, $args );
		$next_scheduled = wp_next_scheduled( $tag, $args );
		if ( FALSE === $unschedule_result ) {
			pb_backupbuddy::status( 'error', 'Unable to remove schedule as wp_unschedule_event() returned false. A plugin may have prevented it or it is already scheduled.' );
			return false;
		}
		if ( FALSE !== $next_scheduled ) {
			pb_backupbuddy::status( 'error', 'WordPress reported success unscheduling BUT wp_next_scheduled() confirmed schedule existance. The database may have rejected the removal.' );
			return false;
		}

		return true;
	} // End unschedule_event().



	/* normalize_comment_data()
	 *
	 * Handle normalizing zip comment data, defaults, etc.
	 *
	 * @param	array	$comment	Array of meta data to normalize & apply defaults to.
	 * @return	array				Normalized array.
	 */
	public static function normalize_comment_data( $comment ) {

		$defaults = array(
			'serial'		=>	'',
			'siteurl'		=>	'',
			'type'			=>	'',
			'profile'		=>	'',
			'created'		=>	'',
			'db_prefix'		=>	'',
			'bb_version'	=>	'',
			'wp_version'	=>	'',
			'posts'			=>	'',
			'pages'			=>	'',
			'comments'		=>	'',
			'users'			=>	'',
			'dat_path'		=>	'',
			'note'			=>	'',
		);

		if ( ! is_array( $comment ) ) { // Plain text; place in note field.
			if ( is_string( $comment ) ) {
				$defaults['note'] = $comment;
			}
			return $defaults;
		} else { // Array. Merge defaults and return.
			return array_merge( $defaults, $comment );
		}

	} // End normalize_comment_data().
	
	
	
	/* pretty_meta_info()
	 *
	 * Translates meta information field names and values into nice readable forms.
	 *
	 * @param	string	$comment_line_name		Meta field name.
	 * @param	string	$comment_line_value		Value of meta item.
	 * @return	array|false 					Array with two entries: the updates comment line name and updated comment line value. false if empty.
	 *
	 */
	public static function pretty_meta_info( $comment_line_name, $comment_line_value ) {

		if ( $comment_line_name == 'serial' ) {
			$comment_line_name = 'Unique serial identifier';
		} elseif ( $comment_line_name == 'siteurl' ) {
			$comment_line_name = 'Site URL';
		} elseif ( $comment_line_name == 'type' ) {
			$comment_line_name = 'Backup Type';
			if ( $comment_line_value == 'db' ) {
				$comment_line_value = 'Database';
			} elseif ( $comment_line_value == 'full' ) {
				$comment_line_value = 'Full';
			} elseif ( $comment_line_value == 'export' ) {
				$comment_line_value = 'Multisite Subsite Export';
			}
		} elseif ( $comment_line_name == 'profile' ) {
			$comment_line_name = 'Backup Profile';
		} elseif ( $comment_line_name == 'created' ) {
			$comment_line_name = 'Creation Date';
			if ( $comment_line_value != '' ) {
				$comment_line_value = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $comment_line_value ) );
			}
		} elseif ( $comment_line_name == 'bb_version' ) {
			$comment_line_name = 'BackupBuddy version at creation';
		} elseif ( $comment_line_name == 'wp_version' ) {
			$comment_line_name = 'WordPress version at creation';
		} elseif ( $comment_line_name == 'dat_path' ) {
			$comment_line_name = 'BackupBuddy data file (relative)';
		} elseif ( $comment_line_name == 'posts' ) {
			$comment_line_name = 'Total Posts';
		} elseif ( $comment_line_name == 'pages' ) {
			$comment_line_name = 'Total Pages';
		} elseif ( $comment_line_name == 'comments' ) {
			$comment_line_name = 'Total Comments';
		} elseif ( $comment_line_name == 'users' ) {
			$comment_line_name = 'Total Users';
		} elseif ( $comment_line_name == 'note' ) {
			$comment_line_name = 'User-specified note';
			if ( $comment_line_value != '' ) {
				$comment_line_value = '"' . htmlentities( $comment_line_value ) . '"';
			}
		} else {
			$comment_line_name = $comment_line_name;
		}

		if ( $comment_line_value != '' ) {
			return array( $comment_line_name, $comment_line_value );
		} else {
			return array( $comment_line_name, '-Empty-' );
		}

	} // End pretty_meta_info().



	/* alert_core_table_excludes()
	 *
	 * Outputs an alert warning if a core db table is excluded.
	 *
	 * @param	array 		$excludes	Array of tables excluded from the backup.
	 * @return	array 					Array of message warnings about potential issues found with these exclusions, if any. Index = unique identifer, Value = message.
	 *
	 */
	public static function alert_core_table_excludes( $excludes ) {
		global $wpdb;
		$prefix = $wpdb->prefix;

		// If these tables are found excluded then warn that may be a bad idea.
		$warn_tables = array(
			$prefix . 'comments',
			$prefix . 'posts',
			$prefix . 'users',
			$prefix . 'commentmeta',
			$prefix . 'postmeta',
			$prefix . 'term_relationships',
			$prefix . 'options',
			$prefix . 'term_taxonomy',
			$prefix . 'links',
			$prefix . 'terms',
		);

		$return_array = array();
		foreach( $warn_tables as $warn_table ) {
			if ( in_array( $warn_table, $excludes ) ) {
				$return_array['excluding_coretable-' . md5( $warn_table )] = 'Warning: You are excluding one or more core WordPress tables `' . $warn_table . '` which may result in an incomplete backup. Remove exclusions or backup with another profile or method.';
			}
		}

		return $return_array;
	} // End alert_core_tables_excludes().
	
	
	
	/* alert_core_file_excludes()
	 *
	 * Outputs an alert warning if a core db table is excluded.
	 *
	 * @param	array 		$excludes		Array of paths excluded from the backup.
	 * @return	array 						Array of message warnings about potential issues found with these exclusions, if any. Index = unique identifer, Value = message.
	 *
	 */
	public static function alert_core_file_excludes( $excludes ) {

		// If these paths are found excluded then warn that may be a bad idea.
		$warn_dirs = array( // No trailing slash.
			'/wp-content',
			'/wp-content/uploads',
			'/wp-content/uploads/backupbuddy_temp',
			'/' . ltrim( str_replace( ABSPATH, '', backupbuddy_core::getBackupDirectory() ), '\\/' ),
		);

		foreach( $excludes as &$exclude ) { // Strip trailing slash(es).
			$exclude = rtrim( $exclude, '\\/' );
		}

		$return_array = array();
		foreach( $warn_dirs as $warn_dir ) {
			if ( in_array( $warn_dir, $excludes ) ) {
				$return_array['excluding_corefile-' . md5( $warn_dir )] = 'Warning: You are excluding one or more WordPress core or BackupBuddy directories `' . $warn_dir . '` which may result in an incomplete or malfunctioning backup. Remove exclusions or backup with another profile or method to avoid problems.';
			}
		}

		return $return_array;
	} // End alert_core_file_excludes().
	
	
	
	/* getZipMeta()
	 *
	 * Output meta info in a table.
	 *
	 * @param	string		$file			Backup file to get comment meta data from.
	 * @return	array|false					Array of meta data or false on failure to retrieve.
	 *
	 */
	public static function getZipMeta( $file ) {
		if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
			pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		}
		$comment_meta = array();
		if ( isset( $file ) ) {
			$comment = pb_backupbuddy::$classes['zipbuddy']->get_comment( $file );
			$comment = backupbuddy_core::normalize_comment_data( $comment );

			$comment_meta = array();
			foreach( $comment as $comment_line_name => $comment_line_value ) { // Loop through all meta fields in the comment array to display.

				if ( false !== ( $response = backupbuddy_core::pretty_meta_info( $comment_line_name, $comment_line_value ) ) ) {
					$response[0] = '<span title="' . $comment_line_name . '">' . $response[0] . '</span>';
					$comment_meta[$comment_line_name] = $response;
				}

			}
		}

		if ( count( $comment_meta ) > 0 ) {
			return $comment_meta;
		} else {
			return false;
		}
	} // End getZipMeta().
	
	
	
	/* get_dat_file_array()
	 *
	 * Get the DAT file contents as an array.
	 *
	 * @param		string		$dat_file		Full path to DAT file to decode and parse.
	 * @return		array|false					Array of DAT content. Bool false when unable to read.
	 *
	 */
	public static function get_dat_file_array( $dat_file ) {
		pb_backupbuddy::status( 'details', 'Loading backup dat file.' );

		if ( file_exists( $dat_file ) ) {
			$backupdata = file_get_contents( $dat_file );
		} else { // Missing.
			pb_backupbuddy::status( 'error', 'Error #9003: BackupBuddy data file (backupbuddy_dat.php) missing or unreadable. There may be a problem with the backup file, the files could not be extracted (you may manually extract the zip file in this directory to manually do this portion of restore), or the files were deleted before this portion of the restore was reached.  Start the import process over or try manually extracting (unzipping) the files then starting over. Restore will not continue to protect integrity of any existing data.' );
			//die( ' Halted.' );
			return false;
		}
		
		// Unserialize data; If it fails it then decodes the obscufated data then unserializes it. (new dat file method starting at 2.0).
		if ( !is_serialized( $backupdata ) || ( false === ( $return = unserialize( $backupdata ) ) ) ) {
			// Skip first line.
			$second_line_pos = strpos( $backupdata, "\n" ) + 1;
			$backupdata = substr( $backupdata, $second_line_pos );
			
			// Decode back into an array.
			$return = unserialize( base64_decode( $backupdata ) );
		}
		
		if ( ! is_array( $return ) ) { // Invalid DAT content.
			pb_backupbuddy::status( 'error', 'Error #545545. Unable to read/decode DAT file.' );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Successfully loaded backup dat file `' . $dat_file . '`.' );
		$return_censored = $return;
		$return_censored['db_password'] = '*HIDDEN*';
		$return_censored = print_r( $return_censored, true );
		$return_censored = str_replace( array( "\n", "\r" ), '; ', $return_censored );
		pb_backupbuddy::status( 'details', 'DAT contents: ' . $return_censored );
		return $return;
	} // End get_dat_file_array().
	
	
	
	/* determineLatestVersion()
	 *
	 * Latest version info. Array of latest major,minor. False on fail to get.
	 *
	 */
	public static function determineLatestVersion( $bypassCache = false ) {
		$latest_backupbuddy_version_cache_minutes = 60*12; // Define how many minutes to cache the latest backupbuddy version number.
		
		function pb_backupbuddy_split2( $string,$needle,$nth ) {
			$max = strlen($string);
			$n = 0;
			for($i=0;$i<$max;$i++){
				if ($string[$i]==$needle){
					$n++;
					if($n>=$nth){
						break;
					}
				}
			}
			$arr[] = substr($string,0,$i);
			$arr[] = substr($string,$i+1,$max);
			return $arr;
		}
		
		if ( true === $bypassCache ) {
			$latest_backupbuddy_version = false;
		} else {
			$latest_backupbuddy_version = get_transient( 'pb_backupbuddy_latest_version' );
		}
		
		if ( ( false === $latest_backupbuddy_version ) || ( ! is_array( $latest_backupbuddy_version ) ) ) {
			$response = wp_remote_get( 'http://api.ithemes.com/product/version?apikey=ixho7dk0p244n0ob&package=backupbuddy&channel=stable', array(
					'method' => 'GET',
					'timeout' => 7,
					'redirection' => 3,
					'httpversion' => '1.0',
					//'blocking' => true,
					'headers' => array(),
					'body' => null,
					'cookies' => array()
				)
			);
			if( is_wp_error( $response ) ) {
				$latest_backupbuddy_version = array( 0, 0 ); // Set to 0 for transient to prevent hitting server again for a bit since something went wrong.
			} else {
				$minorVersion = $response['body'];
				$majorVersion = pb_backupbuddy_split2( $minorVersion, '.', 3 );
				$majorVersion = $majorVersion[0];
				$latest_backupbuddy_version = array( $minorVersion, $majorVersion );
			}
			set_transient( 'pb_backupbuddy_latest_version', $latest_backupbuddy_version, 60* $latest_backupbuddy_version_cache_minutes );
		} // end not cached.

		if ( ( 0 == $latest_backupbuddy_version[0] ) && ( 0 == $latest_backupbuddy_version[1] ) ) { // Server not responding.
			return false;
		}

		return $latest_backupbuddy_version;

	} // End determineLatestVersion().
	
	
	
	/* deploymentImportBuddy()
	 *
	 * Renders importbuddy on this server.
	 *
	 * @param	string			$backupFile				Full filename with path to backup file to import.
	 * @param	string			$additionalStateInfo	Array of additional state information to merge into state array, such as session tokens.
	 * @return	string|array 							If string then importbuddy serial.  If array then an error has been encountered. Array format: array( false, 'Error message.' ).
	 *
	 */
	public static function deploymentImportBuddy( $password, $backupFile, $additionalStateInfo = '' ) {
		if ( ! file_exists( $backupFile ) ) {
			$error = 'Error #43848378: Backup file `' . $backupFile . '` not found uploaded.';
			pb_backupbuddy::status( 'error', $error);
			return array( false, $error );
		}
		
		$backupSerial = backupbuddy_core::get_serial_from_file( $backupFile );
		
		$importFileSerial = pb_backupbuddy::random_string( 15 );
		$importFilename = 'importbuddy-' . $importFileSerial . '.php';
		backupbuddy_core::importbuddy( ABSPATH . $importFilename, $password );
		
		// Render default config file overrides. Overrrides default restore.php state data.
		$state = array();
		global $wpdb;
		$state['type'] = 'deploy';
		$state['archive'] = $backupFile;
		$state['siteurl'] = preg_replace( '|/*$|', '', site_url() ); // Strip trailing slashes.
		$state['homeurl'] = preg_replace( '|/*$|', '', home_url() ); // Strip trailing slashes.
		$state['restoreFiles'] = false;
		$state['migrateHtaccess'] = false;
		$state['remote_api'] = pb_backupbuddy::$options['remote_api']; // For use by importbuddy api auth. Enables remote api in this importbuddy.
		$state['databaseSettings']['server'] = DB_HOST;
		$state['databaseSettings']['database'] = DB_NAME;
		$state['databaseSettings']['username'] = DB_USER;
		$state['databaseSettings']['password'] = DB_PASSWORD;
		$state['databaseSettings']['prefix'] = $wpdb->prefix;
		$state['databaseSettings']['renamePrefix'] = true;
		$state['cleanup']['deleteImportBuddy'] = true;
		$state['cleanup']['deleteImportLog'] = true;
		
		if ( is_array( $additionalStateInfo ) ) {
			$state = array_merge( $state, $additionalStateInfo );
		}
		
		error_log( print_r( $state, true ) );
		
		// Write default state overrides.
		$state_file = ABSPATH . 'importbuddy-' . $importFileSerial . '-state.php';
		if ( false === ( $file_handle = @fopen( $state_file, 'w' ) ) ) {
			$error = 'Error #8384784: Temp state file is not creatable/writable. Check your permissions. (' . $state_file . ')' ;
			pb_backupbuddy::status( 'error', $error);
			return array( false, $error );
		}
		fwrite( $file_handle, "<?php die('Access Denied.'); // <!-- ?>\n" . base64_encode( serialize( $state ) ) );
		fclose( $file_handle );
		
		
		$undoFile = 'backupbuddy_deploy_undo-' . $backupSerial . '.php';
		//$undoURL = rtrim( site_url(), '/\\' ) . '/' . $undoFile;
		if ( false === copy( pb_backupbuddy::plugin_path() . '/classes/_rollback_undo.php', ABSPATH . $undoFile ) ) {
			$error = 'Error #3289447: Unable to write undo file `' . ABSPATH . $undoFile . '`. Check permissions on directory.' ;
			pb_backupbuddy::status( 'error', $error);
			return array( false, $error );
		}
		
		return $importFileSerial;
		
	} // End deploymentImportBuddy().
	
	
	
	/* detectMaxExecutionTime()
	 *
	 * Attempt to detect the max execution time allowed by PHP. Defaults to 30 if unable to detect or a suspicious value is detected.
	 * IMPORTANT: This does NOT take into account user-specified override via settings page. For that, use adjustedMaxExecutionTime().
	 */
	public static function detectMaxExecutionTime() {
		$detected_max_execution_time = str_ireplace( 's', '', ini_get( 'max_execution_time' ) );
		if ( is_numeric( $detected_max_execution_time ) ) {
			$detected_max_execution_time = $detected_max_execution_time;
		} else {
			$detected_max_execution_time = 30;
		}
		if ( $detected_max_execution_time == '0' ) {
			$detected_max_execution_time = 30;
		}
		return $detected_max_execution_time;
	} // End detectMaxExecutionTime().
	
	
	
	// Same as detectedMaxExecutionTime EXCEPT takes into account user overrided value in settings (if any).
	public static function adjustedMaxExecutionTime() {
		$detected = self::detectMaxExecutionTime();
		if ( ( '' != pb_backupbuddy::$options['max_execution_time'] ) && ( is_numeric( pb_backupbuddy::$options['max_execution_time'] ) ) ) { // If set and a number, use user-specified runtime.
			return pb_backupbuddy::$options['max_execution_time'];
		} else { // Nothing user-specified so user detected value.
			return $detected;
		}
	} // End adjustedMaxExecutionTime().
	
	
	
	/* dbEscape()
	 *
	 * Escape SQL using either mysql or mysqli based on whichever WordPress is using.
	 * WP 3.9 introducing mysqli support.
	 */
	public static function dbEscape( $sql ) {
		global $wpdb;
		if ( isset( $wpdb->use_mysqli ) && ( true === $wpdb->use_mysqli ) ) { // Possible post WP 3.9
			return mysqli_real_escape_string( $wpdb->dbh, $sql );
		} else {
			return mysql_real_escape_string( $sql );
		}
	} // End dbEscape().
	
	
	
	/* verifyAjaxAccess()
	 *
	 * !! IMPORTANT FOR ANY AJAX PAGES FOR SECURITY !! Verify user is both logged into admin and has appropriate role access to BackupBuddy.
	 *
	 * @return	die()|true		On failure, dies/halts PHP script for security.  On access franted, returns true.
	 *
	 */
	public static function verifyAjaxAccess() {
		if ( ! is_admin() ) {
			die( 'Error #2389833. Access Denied.' );
		}
		if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
			die( 'Error #2373823. Access Denied.' );
		}
		
		return true;
	} // End verifyAjaxAccess().
	
	
	
	/* getNotifications()
	 *
	 * description
	 *
	 */
	public static function getNotifications() {
		$default = array();
		return get_site_option( backupbuddy_constants::NOTIFICATIONS_OPTION_SLUG, $default, false );
	} // End getNotifications().
	
	
	
	/* replaceNotifications()
	 *
	 * description
	 *
	 */
	public static function replaceNotifications( $notificationArray ) {
		// Save.
		add_site_option( backupbuddy_constants::NOTIFICATIONS_OPTION_SLUG, $notificationArray, '', 'no' );
		update_site_option( backupbuddy_constants::NOTIFICATIONS_OPTION_SLUG, $notificationArray );
	} // End replaceNotifications().
	
	
	
	/* getBackupTypeFromFile()
	 *
	 * Determine backup type from file whether by filename or embedded fileoptions.
	 *
	 * @param	string	$file	Full filename path of backup to determine type of.
	 * @return	string			Type of backup (eg full, db). If unknown, empty string '' returned.
	 *
	 */
	public static function getBackupTypeFromFile( $file, $quiet = false ) {
		
		if ( false === $quiet ) {
			pb_backupbuddy::status( 'details', 'Detecting backup type if possible.' );
		}
		
		// Try to figure out type via filename.
		if ( stristr( $file, '-db-' ) !== false ) {
			$type = 'db';
		} elseif ( stristr( $file, '-full-' ) !== false ) {
			$type = 'full';
		} elseif ( stristr( $file, '-files-' ) !== false ) {
			$type = 'files';
		}
		
		if ( isset( $type ) ) {
			if ( false === $quiet ) {
				pb_backupbuddy::status( 'details', 'Detected backup type as `' . $type . '` via filename.' );
			}
			return $type;
		}
		
		// See if we can get backup type from fileoptions data.
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		$fileoptionsFile = backupbuddy_core::getLogDirectory() . 'fileoptions/' . backupbuddy_core::get_serial_from_file( $file ) . '.txt';
		$backup_options = new pb_backupbuddy_fileoptions( $fileoptionsFile, $read_only = true, $ignore_lock = true );
		if ( true !== ( $result = $backup_options->is_ok() ) ) {
			//pb_backupbuddy::status( 'warning', 'Warning only: Unable to open fileoptions file `' . $fileoptionsFile . '`. This may be normal.' );
		} else {
			if ( isset( $backup_options->options['integrity']['detected_type'] ) ) {
				if ( false === $quiet ) {
					pb_backupbuddy::status( 'details', 'Detected backup type as `' . $backup_options->options['integrity']['detected_type'] . '` via integrity check data.' );
				}
				return $backup_options->options['integrity']['detected_type'];
			}
		}
		
		return ''; // Type unknown.
		
	} // End getBackupTypeFromFile().
	
	
	
	/* calculateArchiveFilename()
	 *
	 * Calculates the backup zip filename.
	 *
	 */
	public static function calculateArchiveFilename( $serial, $type, $profile ) {
		
		// Prepare some values for setting up the backup data.
		$siteurl_stripped = backupbuddy_core::backup_prefix();

		// Add profile to filename if set in options and exists
		if ( empty( pb_backupbuddy::$options['archive_name_profile'] ) || empty( $profile['title'] ) ) {
			$backupfile_profile = '';
		} else {
			$backupfile_profile = sanitize_file_name( strtolower( $profile['title'] ) ) . '-';
			$backupfile_profile = str_replace( '/', '_', $backupfile_profile );
			$backupfile_profile = str_replace( '\\', '_', $backupfile_profile );
			$backupfile_profile = str_replace( '.', '_', $backupfile_profile );
			$backupfile_profile = str_replace( ' ', '_', $backupfile_profile );
			$backupfile_profile = str_replace( '-', '_', $backupfile_profile );
		}
		
		// Calculate customizable section of archive filename (date vs date+time).
		if ( pb_backupbuddy::$options['archive_name_format'] == 'datetime' ) { // "datetime" = Date + time.
			$backupfile_datetime = date( backupbuddy_constants::ARCHIVE_NAME_FORMAT_DATETIME, pb_backupbuddy::$format->localize_time( time() ) );
		} elseif ( pb_backupbuddy::$options['archive_name_format'] == 'datetime24' ) { // "datetime" = Date + time in 24hr format.
			$backupfile_datetime = date( backupbuddy_constants::ARCHIVE_NAME_FORMAT_DATETIME24, pb_backupbuddy::$format->localize_time( time() ) );
		} elseif ( pb_backupbuddy::$options['archive_name_format'] == 'timestamp' ) { // "datetime" = Date + time in 24hr format.
			$backupfile_datetime = pb_backupbuddy::$format->localize_time( time() );
		} else { // "date" = date only (the default).
			$backupfile_datetime = date( backupbuddy_constants::ARCHIVE_NAME_FORMAT_DATE, pb_backupbuddy::$format->localize_time( time() ) );
		}
		$archiveFile = backupbuddy_core::getBackupDirectory() . 'backup-' . $siteurl_stripped . '-' . $backupfile_datetime . '-' . $backupfile_profile . $type . '-' . $serial . '.zip';
		
		pb_backupbuddy::status( 'details', 'Calculated archive file: `' . $archiveFile . '`.' );
		return $archiveFile;
	} // End calculateArchiveFilename().
	
	
	
	/* hashGlob()
	 *
	 * Calculate comparison data for all files within a path. Useful for tracking file changes between two locations.
	 *
	 * @param	array 	$excludes		Directories to exclude, RELATIVE to the root. Include LEADING slash for each entry.
	 * @param	array	$utf8_encode	Should we encode any file names that are in UTF-8 format?
	 * @return	array 					Nested array of file/directory structure.
	 */
	public static function hashGlob( $root, $generate_sha1 = false, $excludes = array(), $utf8_encode = false ) {
		
		$root = rtrim( $root, '/\\' ); // Make sure no trailing slash.
		$excludes = str_replace( $root, '', $excludes );
		$files = (array) pb_backupbuddy::$filesystem->deepglob( $root );
		$root_len = strlen( $root );
		$hashedFiles = array();
		foreach( $files as $file_id => &$file ) {
			$new_file = substr( $file, $root_len );
			
			// If this file/directory begins with an exclusion then jump to next file/directory.
			foreach( $excludes as $exclude ) {
				if ( backupbuddy_core::startsWith( $new_file, $exclude ) ) {
					continue 2;
				}
			}
			
			// Omit directories themselves.
			if ( is_dir( $file ) ) {
				continue;
			}
			
			$stat = stat( $file );
			if ( FALSE === $stat ) {
				pb_backupbuddy::status( 'error', 'Unable to read file `' . $file . '` stat. Skipping file.' );
				continue;
			}
			
			// If the filename is in UTF-8 and the flag is set, encode before using as an array key
			if ( $utf8_encode && ( 'UTF-8' == mb_detect_encoding( $new_file ) ) ) {
				$new_file = utf8_encode( $new_file );
			}
			
			$hashedFiles[$new_file] = array(
				'size'		=> $stat['size'],
				'modified'	=> $stat['mtime'],
			);
			if ( defined( 'BACKUPBUDDY_DEV' ) && ( true === BACKUPBUDDY_DEV ) ) {
				$hashedFiles[$new_file]['debug_filename'] = base64_encode( $file );
				$hashedFiles[$new_file]['debug_filelength'] = strlen( $file );
			}
			if ( ( true === $generate_sha1 ) && ( $stat['size'] < 1073741824 ) ) { // < 100mb
				$hashedFiles[$new_file]['sha1'] = sha1_file( $file );
			}
			unset( $files[$file_id] ); // Better to free memory or leave out for performance?
			
		}
		unset( $files );
		
		return $hashedFiles;
		
	} // End hashGlob.
	
	
	
	// search backwards starting from haystack length characters from the end
	public static function startsWith($haystack, $needle) {
		if ( '' == $needle ) { // Blank needle is invalid so always say false, it does not start with a blank.
			return false;
		}
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	} // End backupbuddy_startsWith().
	
	
	
	/* addNotification()
	 *
	 * 
	 *
	 */
	public static function addNotification( $slug, $title, $message, $data = array(), $urgent = false, $time = '' ) {
		
		if ( '' == $time ) {
			$time = time();
		}
		
		// Create this new notification data.
		$notification = array(
			'slug' => $slug,
			'time' => $time,
			'title' => $title,
			'message' => $message,
			'data' => $data,
			'urgent' => false,
		);
		$notification = array_merge( pb_backupbuddy::settings( 'notification_defaults' ), $notification ); // Apply defaults.
		
		// Load current notifications.
		$notificationArray = self::getNotifications();
		
		// Add to current notifications.
		$notificationArray[] = $notification;
		
		// Save.
		self::replaceNotifications( $notificationArray );
		
	} // End addNotification().
	
	
	
	/* get_mysqldump_additional()
	 *
	 * Get additional includes or excludes of db tables. Populates {prefix} variable and sanitizes, removes dupes, etc. Returns array.
	 *
	 */
	public static function get_mysqldump_additional( $includesOrExcludes, $profile, $override_prefix = '' ) {
		if ( ( 'includes' != $includesOrExcludes ) && ( 'excludes' != $includesOrExcludes ) ) {
			$error = 'Error #839328973: Invalid getIncludeExcludeTables() parameter in core.php.';
			error_log( 'BackupBuddy ' . $error );
			echo $error;
			return false;
		}
		
		$tables = explode( "\n", $profile[ 'mysqldump_additional_' . $includesOrExcludes ] );
		array_walk( $tables, create_function('&$val', '$val = trim($val);'));  // Trim whitespace around tables.
		
		if ( '' == $override_prefix ) {
			global $wpdb;
			$prefix = $wpdb->prefix;
		} else {
			$prefix = $override_prefix;
		}
		foreach( $tables as &$table ) {
			$table = str_replace( '{prefix}', $prefix, $table ); // Populate prefix variable.
		}
		
		$tables = array_unique( $tables ); // Remove any duplicate tables.
		return $tables;
	} // End get_mysqldump_additional().
	
	
	
	public static function verifyHousekeeping() {
		if ( false === wp_next_scheduled( 'backupbuddy_cron', array( 'housekeeping', array() ) ) ) { // if schedule does not exist...
			backupbuddy_core::schedule_event( time() + ( 60*60 * 2 ), 'daily', 'housekeeping', array() ); // Add schedule.
		}
	} // End verifyHousekeeping().
	
	
	
} // End class backupbuddy_core.
