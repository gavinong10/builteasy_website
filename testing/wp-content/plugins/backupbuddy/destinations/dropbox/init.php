<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_dropbox { // Change class name end to match destination name.
	
	public static $destination_info = array(
		'name'			=>		'Dropbox v1 (legacy)',
		'description'	=>		'Dropbox.com is a popular online storage provider offering 2 GB of free storage to start with. Premium upgrades are available.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'dropbox',	// MUST MATCH your destination slug. Required destination field.
		'title'			=>		'',		// Required destination field.
		'token'			=>		'',
		'directory'		=>		'backupbuddy',
		'archive_limit'	=>		0,
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	private static $_init_count = 0;
	
	
	public static function init() {
		if ( self::$_init_count == 0 ) {
			$memory = self::memory_guesstimate();
			self::$destination_info['description'] .= ' BackupBuddy estimates <b>you will be able to transfer backups up to ' . round( $memory['hypothesis'], 0 ) . ' MB with your current memory limit of ' . $memory['limit'] . ' MB</b>. Additionally, Dropbox limits API uploads to 150MB max. <a href="http://dropbox.com" target="_blank">Learn more here.</a>';
		}
		self::$_init_count++;
	}
	
	
	
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
		
		$token = &$settings['token'];
		$directory = '/' . ltrim( $settings['directory'], '/\\' );
		$limit = $settings['archive_limit'];
		
		
		// Normalize picky dropbox directory.
		$directory = trim( $directory, '\\/' );
		
		pb_backupbuddy::status( 'details', 'About to load Dropbuddy library...' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/dropbox/lib/dropbuddy/dropbuddy.php' );
		pb_backupbuddy::status( 'details', 'Dropbuddy loaded.' );
		//pb_backupbuddy::status( 'details', 'Authenticating to dropbox with token: `' . implode( ';', $token ) . '`.' );
		$dropbuddy = new pb_backupbuddy_dropbuddy( $token );
		pb_backupbuddy::status( 'details', 'Dropbuddy object created.' );
		if ( $dropbuddy->authenticate() !== true ) {
			pb_backupbuddy::status( 'details',  'Dropbox authentication failed in send().' );
			return false;
		} else {
			pb_backupbuddy::status( 'details', 'Authenticated to Dropbox.' );
		}
		
		$memory = pb_backupbuddy_destination_dropbox::memory_guesstimate();
		pb_backupbuddy::status( 'details', 'Dropbox limitation estimated to be max transfer size of ' . round( $memory['hypothesis'], 0 ) . 'MB based on PHP memory limit of ' . $memory['limit'] . 'MB & current loaded WordPress plugins.' );
		
		pb_backupbuddy::status( 'details', 'Looping through files to send to Dropbox.' );
		foreach( $files as $file ) {
			pb_backupbuddy::status( 'details',  'About to put file `' . basename( $file ) . '` (' . pb_backupbuddy::$format->file_size( filesize( $file ) ) . ') to Dropbox (v1).' );
			try {
				$status = $dropbuddy->put_file( $directory . '/' . basename( $file ), $file );
			} catch( Dropbox_Exception $e ) {
				pb_backupbuddy::status( 'error', 'Dropbox exception caught. Error #8954785: ' . $e->getMessage() );
				return false;
			}
			if ( $status === true ) {
				pb_backupbuddy::status( 'details',  'SUCCESS sending to Dropbox!' );
			} else {
				pb_backupbuddy::status( 'details',  'Dropbox file send FAILURE. HTTP Status: ' . $status['httpStatus'] . '; Body: ' . $status['body'], 'error' );
				return false;
			}
			
			
			// Start remote backup limit
			if ( $limit > 0 ) {
				pb_backupbuddy::status( 'details',  'Dropbox file limit in place. Proceeding with enforcement.' );
				
				$meta_data = $dropbuddy->get_meta_data( $directory );
				
				// Create array of backups and organize by date
				$bkupprefix = backupbuddy_core::backup_prefix();
				
				$backups = array();
				foreach ( (array) $meta_data['contents'] as $looping_file ) {
					if ( '1' == $looping_file['is_dir'] ) { // Additional safety layer to ignore subdirectory.
						continue;
					}
					// check if file is backup
					if ( ( strpos( $looping_file['path'], 'backup-' . $bkupprefix . '-' ) !== false ) ) {
						$backups[$looping_file['path']] = strtotime( $looping_file['modified'] );
					}
				}
				arsort($backups);
				
				if ( ( count( $backups ) ) > $limit ) {
					pb_backupbuddy::status( 'details',  'Dropbox backup file count of `' . count( $backups ) . '` exceeds limit of `' . $limit . '`.' );
					$i = 0;
					$delete_fail_count = 0;
					foreach( $backups as $buname => $butime ) {
						$i++;
						if ( $i > $limit ) {
							if ( !$dropbuddy->delete( $buname ) ) { // Try to delete backup on Dropbox. Increment failure count if unable to.
								pb_backupbuddy::status( 'details',  'Unable to delete excess Dropbox file: `' . $buname . '`' );
								$delete_fail_count++;
							}
						}
					}
					
					if ( $delete_fail_count !== 0 ) {
						backupbuddy_core::mail_error( sprintf( __('Dropbox remote limit could not delete %s backups.', 'it-l10n-backupbuddy' ), $delete_fail_count) );
					}
				}
			} else {
				pb_backupbuddy::status( 'details',  'No Dropbox file limit to enforce.' );
			}
			// End remote backup limit
		} // end foreach.
		pb_backupbuddy::status( 'details', 'All files sent.' );
		
		return true; // Success if made it this far.
			
			
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
		
		return false; // WE DO NOT HAVE A REMOTE TEST FOR THIS CURRENTLY.
		
	} // End test().
	
	
	
	/*	memory_guesstimate()
	 *	
	 *	Estimates the amount of available memory for loading a ZIP file. This guesses
	 *	the max file size that could be sent due to memory constraints.
	 *	
	 *	@param		
	 *	@return				Associate array with multiple items.
	 */
	public static function memory_guesstimate() {
		
		// CALCULATE MEMORY. **********************************************
		$this_val = ini_get( 'memory_limit' );
		if ( preg_match( '/(\d+)(\w*)/', $this_val, $matches ) ) {
			$this_val = $matches[1];
			$unit = $matches[2];
		
			if ( 'g' == strtolower( $unit ) ) {
				// Convert GB to MB.
				$this_val = $this_val = $this_val * 1024;
			}
		} else {
			$limit = 0;
		}
		
		$memory_usage = memory_get_peak_usage() / 1048576;
		$memory_limit = $this_val;
		$memory_free = $this_val - $memory_usage;
		$memory_hypothesis = ( $memory_free - 2 - ( $memory_free * .10 ) ) / 2; // Free memory minus 2MB minus a 10% free memory fudge factor/wiggle room. Underestimate.  -- July 13, 2012: Now dividing by 2 due to having to load via file_get_contents and copy into $body. Doubles mem usage sadly.
		
		if ( $memory_hypothesis > 150 ) {
			$memory_hypothesis = 150;
		}
		
		return array(
			'usage'			=> $memory_usage,
			'limit'			=> $memory_limit,
			'free'			=> $memory_free,
			'hypothesis'	=> $memory_hypothesis,
		);
		
	} // End memory_guesstimate().
	
	
} // End class.