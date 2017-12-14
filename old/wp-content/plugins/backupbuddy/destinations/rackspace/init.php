<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_rackspace { // Change class name end to match destination name.
	
	public static $destination_info = array(
		'name'			=>		'Rackspace Cloud Files',
		'description'	=>		'Rackspace Cloud Files is an online cloud storage service (like Amazon S3) for storing files. <a href="http://www.rackspace.com/cloud/public/files/" target="_blank">Learn more here.</a>',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'rackspace',	// MUST MATCH your destination slug. Required destination field.
		'title'			=>		'',		// Required destination field.
		'username'		=>		'',		// Rackspace account username.
		'api_key'		=>		'',		// Rackspace API key.
		'container'		=>		'',		// Rackspace container to send into.
		'server'		=>		'https://auth.api.rackspacecloud.com', // Server address to connect to for sending. For instance the UK Rackspace cloud URL differs.
		'archive_limit'	=>		'0',
		'service_net'	=>		'0',	// Whether to use internal service net to reduce bandwidth when internal to the Rackspace network.
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	
	
	public static function connect( $settings = array() ) {
		require_once( dirname( __FILE__ ) . '/lib/rackspace/cloudfiles.php' );
		$auth = new CF_Authentication( $settings['username'], $settings['api_key'], NULL, $settings['server'] );
		try {
			$auth->authenticate();
		} Catch( Exception $e ) {
			global $pb_backupbuddy_destination_errors;
			$message = 'Error #238338: Unable to authenticate to Rackspace Cloud Files. Details: `' . $e->getMessage() . '`.';
			pb_backupbuddy::status( 'error', $message );
			$pb_backupbuddy_destination_errors[] = $message;
			return false;
		}
		//error_log( print_r( $auth, true ) );
		if ( isset( $settings['service_net'] ) && ( '1' == $settings['service_net'] ) ) {
			$sn_url = 'https://snet-' . substr( $auth->storage_url, strlen( 'https://' ) );
			$auth->storage_url = $sn_url;
		}
		$conn = new CF_Connection( $auth );
		return $conn;
	} // End connect().
	
	
	
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
		
		$rs_container = $settings['container'];
		$limit = $settings['archive_limit'];
		
		if ( false === ( $conn = self::connect( $settings ) ) ) {
			return false;
		}
		
		// Set container
		@$conn->create_container( $rs_container ); // Create container if it does not exist.
		$container = $conn->get_container( $rs_container ); // Get container.
		
		foreach( $files as $rs_file ) {
			pb_backupbuddy::status( 'details',  'About to create object on Rackspace...' );
			
			// Put file to Rackspace.
			$sendbackup = $container->create_object( basename( $rs_file ) );
			pb_backupbuddy::status( 'details',  'Object created. About to stream actual file `' . $rs_file . '`...' );
			if ( $sendbackup->load_from_filename( $rs_file ) ) {
				pb_backupbuddy::status( 'details', 'Send succeeded.' );
				
				// Start remote backup limit
				if ( $limit > 0 ) {
					pb_backupbuddy::status( 'details', 'Archive limit of `' . $limit . '` in settings.' );
					
					$bkupprefix = backupbuddy_core::backup_prefix();
					
					$results = $container->get_objects( 0, NULL, 'backup-' . $bkupprefix . '-' );
					// Create array of backups and organize by date
					$backups = array();
					foreach( $results as $backup ) {
						$backups[$backup->name] = $backup->last_modified;
					}
					arsort( $backups );
					
					if ( ( count( $backups ) ) > $limit ) {
						pb_backupbuddy::status( 'details', 'More archives (' . count( $backups ) . ') than limit (' . $limit . ') allows. Trimming...' );
						$i = 0;
						$delete_fail_count = 0;
						foreach( $backups as $buname => $butime ) {
							$i++;
							if ( $i > $limit ) {
								pb_backupbuddy::status( 'details', 'Trimming excess file `' . $buname . '`...' );
								if ( !$container->delete_object( $buname ) ) {
									pb_backupbuddy::status( 'details',  'Unable to delete excess Rackspace file `' . $buname . '`' );
									$delete_fail_count++;
								}
							}
						}
						
						if ( $delete_fail_count !== 0 ) {
							$error_message = 'Rackspace remote limit could not delete `' . $delete_fail_count . '` backups.';
							pb_backupbuddy::status( 'error', $error_message );
							backupbuddy_core::mail_error( $error_message );
						}
					}
				} else {
					pb_backupbuddy::status( 'details',  'No Rackspace file limit to enforce.' );
				}
				// End remote backup limit
				
				return true; // Success.
			} else { // Failed.
				$error_message = 'ERROR #9025: Connected to Rackspace but unable to put file. Verify Rackspace settings included Rackspace permissions.' . "\n\n" . 'http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9025';
				pb_backupbuddy::status( 'details',  $error_message, 'error' );
				backupbuddy_core::mail_error( __( $error_message, 'it-l10n-backupbuddy' ) );
				
				return false; // Failed.
			}
		} // end foreach.
			
	} // End send().
	
	
	
	/*	test()
	 *	
	 *	Tests ability to write to this remote destination.
	 *	
	 *	@param		array			$settings	Destination settings.
	 *	@return		bool|string					True on success, string error message on failure.
	 */
	public static function test( $settings, $files = array() ) {
		
		$rs_username = $settings['username'];
		$rs_api_key = $settings['api_key'];
		$rs_container = $settings['container'];
		$rs_server = $settings['server'];
		
		if ( empty( $rs_username ) || empty( $rs_api_key ) || empty( $rs_container ) ) {
			return __('Missing one or more required fields.', 'it-l10n-backupbuddy' );
		}
		
		if ( false === ( $conn = self::connect( $settings ) ) ) {
			return false;
		}
		
		// Set container
		@$conn->create_container( $rs_container ); // Create container if it does not exist.
		
		$container = @$conn->get_container( $rs_container ); // returns object on success, string error message on failure.
		if ( ! is_object( $container ) ) {
			return __( 'There was a problem selecting the container:', 'it-l10n-backupbuddy' ) . ' ' . $container;
		}
		// Create test file
		$testbackup = @$container->create_object( 'backupbuddytest.txt' );
		if ( ! $testbackup->load_from_filename( pb_backupbuddy::plugin_path() . '/readme.txt') ) {
			return __('BackupBuddy was not able to write the test file.', 'it-l10n-backupbuddy' );
		}
		
		// Delete test file from Rackspace
		if ( ! $container->delete_object( 'backupbuddytest.txt' ) ) {
			return __('Unable to delete file from container.', 'it-l10n-backupbuddy' );
		}
		
		return true; // Success
		
		
	} // End test().
	
	
} // End class.