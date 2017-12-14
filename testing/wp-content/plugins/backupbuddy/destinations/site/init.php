<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_site {
	
	const TIME_WIGGLE_ROOM = 5;								// Number of seconds to fudge up the time elapsed to give a little wiggle room so we don't accidently hit the edge and time out.
	
	public static $destination_info = array(
		'name'			=>		'BackupBuddy Deployment',
		'description'	=>		'Push to or Pull from another instance of this WordPress site running BackupBuddy. Great for rapidly copying a site to and from a development version back and forth with a live site.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'			=>		'site',	// MUST MATCH your destination slug.
		'title'			=>		'',		// Required destination field.
		'api_key'		=>		'',
		'max_payload'	=>		'10',	// Max payload in MB to send per chunk. This WILL be read into memory.
		'max_time'		=>		'30',	// Default max time in seconds to allow a send to run for. This should be set on the fly prior to calling send overriding this default.
		'resume_point'	=>		'',		// fseek resume point (via ftell).
		'chunks_total'	=>		1,
		'chunks_sent'	=>		0,
		'sendType'		=>		'',		// Set on the fly prior to calling send. Valid types: backup, media, theme, plugin. These determine the destination root location for a file.
		'sendFilePath'	=>		'',		// Location to store file on remote server relative to the root storage location based on send type. Optional.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	private static $_timeStart = 0;
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$files		Array of one or more files to send. IMPORTANT: Currently only supports ONE file.
	 *	@return		boolean						True on success, else false.
	 */
	public static function send( $settings = array(), $files = array(), $send_id = '', $delete_after = false ) {
		global $pb_backupbuddy_destination_errors;
		if ( '1' == $settings['disabled'] ) {
			$pb_backupbuddy_destination_errors[] = __( 'Error #48933: This destination is currently disabled. Enable it under this destination\'s Advanced Settings.', 'it-l10n-backupbuddy' );
			return false;
		}
		if ( ! is_array( $files ) ) {
			$files = array( $files );
		}
		
		self::$_timeStart = microtime( true );
		
		if ( count( $files ) > 1 ) {
			$message = 'Error #84545894585: This destination currently only supports one file per send.';
			pb_backupbuddy::status( 'error', $message );
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] = $message;
			return false;
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
		
		$apiSettings = backupbuddy_remote_api::key_to_array( $settings['api_key'] );
		
		if ( site_url() == $apiSettings['siteurl'] ) {
			$message = 'Error #4389843. You are trying to use this site\'s own API key. You must use the API key from the remote destination site.';
			pb_backupbuddy::status( 'error', $message );
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] = $message;
			return false;
		}
		
		$apiURL = $apiSettings['siteurl'];
		$file = $files[0];
		$filePath = '';
		if ( '' != $settings['sendFilePath'] ) {
			$filePath = $settings['sendFilePath'];
		}
		$maxPayload = $settings['max_payload'] * 1024 * 1024; // Convert to bytes.
		$encodeReducedPayload = floor( ( $settings['max_payload'] - ( $settings['max_payload'] * 0.37 ) ) * 1024 * 1024 ); // Take into account 37% base64 encoding overhead. Convert to bytes. Remove any decimals down via floor.
		
		// Open file for reading.
		if ( FALSE === ( $fs = @fopen( $file, 'r' ) ) ) {
			pb_backupbuddy::status( 'error', 'Error #438934894: Unable to open file `' . $file . '` for reading.' );
			return false;
		}
		
		// If chunked resuming then seek to the correct place in the file.
		if ( ( '' != $settings['resume_point'] ) && ( $settings['resume_point'] > 0 ) ) { // Resuming send of a partially transferred file.
			if ( 0 !== fseek( $fs, $settings['resume_point'] ) ) { // Returns 0 on success.
				pb_backupbuddy::status( 'error', 'Error #327834783: Failed to seek file to resume point `' . $settings['resume_point'] . '` via fseek().' );
				return false;
			}
			$prevPointer = $settings['resume_point'];
		} else { // New file send.
			$size = filesize( $file );
			$encodedSize = ( $size * 0.37 ) + $size;
			pb_backupbuddy::status( 'details', 'File size of file to send: ' . pb_backupbuddy::$format->file_size( $size ) . '. After encoding overhead: ' . pb_backupbuddy::$format->file_size( $encodedSize ) );
			if ( $encodedSize > $maxPayload ) {
				$settings['chunks_total'] = ceil( $encodedSize / $maxPayload ); // $maxPayload );
				pb_backupbuddy::status( 'details', 'This file + encoding exceeds the maximum per-chunk payload size so will be read in and sent in chunks of ' . $settings['max_payload'] . 'MB (' . $maxPayload . ' bytes) totaling approximately ' . $settings['chunks_total'] . ' chunks.' );
			} else {
				pb_backupbuddy::status( 'details', 'This file + encoding does not exceed per-chunk payload size of ' . $settings['max_payload'] . 'MB (' . $maxPayload . ' bytes) so sending in one pass.' );
			}
			$prevPointer = 0;
		}
		
		pb_backupbuddy::status( 'details', 'Reading in `' . $encodeReducedPayload . '` bytes at a time to send.' );
		$dataRemains = true;
		//$loopCount = 0;
		//$loopTimeSum = 0; // Used for average send time per chunk.
		while ( ( TRUE === $dataRemains ) && ( FALSE !== ( $fileData = fread( $fs, $encodeReducedPayload ) ) ) ) { // Grab one chunk of data at a time.
			pb_backupbuddy::status( 'details', 'Read in file data.' );
			if ( feof( $fs ) ) {
				pb_backupbuddy::status( 'details', 'Read to end of file (feof true). No more chunks left after this send.' );
				$dataRemains = false;
			}
			
			
			
			
			$isFileTest = false;
			if ( false !== stristr( basename( $file ), 'remote-send-test.php' ) ) {
				$isFileTest = true;
				$settings['sendType'] = 'test';
			}
			
			if ( true === $dataRemains ) {
				$isFileDone = false;
			} else {
				$isFileDone = true;
			}
			
			if ( ! isset( $size ) ) {
				$size = '';
			}
			
			
			pb_backupbuddy::status( 'details', 'Connecting to remote server to send data.' );
			$response = backupbuddy_remote_api::remoteCall( $apiSettings, 'sendFile_' . $settings['sendType'], array(), $settings['max_time'], $file, $fileData, $prevPointer, $isFileTest, $isFileDone, $size, $filePath );
			unset( $fileData ); // Free up memory.
			
			
			$settings['chunks_sent']++;
			if ( true === $dataRemains ) { // More chunks remain.
				pb_backupbuddy::status( 'details', 'Connection finished sending part ' . $settings['chunks_sent'] . ' of ~' . $settings['chunks_total'] . '.' );
			} else { // No more chunks remain.
				pb_backupbuddy::status( 'details', 'Connection finished sending final part ' . $settings['chunks_sent'] . '.' );
			}
			
			
			if ( false === $response ) {
				echo implode( ', ', backupbuddy_remote_api::getErrors() ) . ' ';
				pb_backupbuddy::status( 'error', 'Errors encountered details: ' . implode( ', ', backupbuddy_remote_api::getErrors() ) );
				global $pb_backupbuddy_destination_errors;
				$pb_backupbuddy_destination_errors[] = backupbuddy_remote_api::getErrors();
				return false; //implode( ', ', backupbuddy_remote_api::getErrors() );
			}
			
			
			if ( FALSE === ( $prevPointer = ftell( $fs ) ) ) {
				pb_backupbuddy::status( 'error', 'Error #438347844: Unable to get ftell pointer of file handle for passing to prevPointer.' );
				@fclose( $fs );
				return false;
			} else {
				pb_backupbuddy::status( 'details', 'File pointer: `' . $prevPointer . '`.' );
			}
			
			
			if ( true === $dataRemains ) { // More data remains so see if we need to consider chunking to a new PHP process.
				// If we are within X second of reaching maximum PHP runtime then stop here so that it can be picked up in another PHP process...
				if ( ( ( microtime( true ) - self::$_timeStart ) + self::TIME_WIGGLE_ROOM ) >= $settings['max_time'] ) {
					pb_backupbuddy::status( 'message', 'Approaching limit of available PHP chunking time of `' . $settings['max_time'] . '` sec. Ran for ' . round( microtime( true ) - self::$_timeStart, 3 ) . ' sec. Proceeding to use chunking.' );
					@fclose( $fs );
					
					// Tells next chunk where to pick up.
					$settings['resume_point'] = $prevPointer;
					
					// Schedule cron.
					$cronTime = time();
					$cronArgs = array( $settings, $files, $send_id, $delete_after );
					$cronHashID = md5( $cronTime . serialize( $cronArgs ) );
					$cronArgs[] = $cronHashID;
					
					$schedule_result = backupbuddy_core::schedule_single_event( $cronTime, 'destination_send', $cronArgs );
					if ( true === $schedule_result ) {
						pb_backupbuddy::status( 'details', 'Next Site chunk step cron event scheduled.' );
					} else {
						pb_backupbuddy::status( 'error', 'Next Site chunk step cron even FAILED to be scheduled.' );
					}
					spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
					update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
					
					
					return array( $prevPointer, 'Sent part ' . $settings['chunks_sent'] . ' of ~' . $settings['chunks_total'] . ' parts.' ); // filepointer location, elapsed time during the import
				} else { // End if.
					pb_backupbuddy::status( 'details', 'Not approaching time limit.' );
				}
			} else {
				pb_backupbuddy::status( 'details', 'No more data remains (eg for chunking) so finishing up.' );
			}
			
		} // end while data remains in file.
		
		// Update fileoptions stats.
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #20.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.279327. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		$fileoptions['finish_time'] = microtime(true);
		$fileoptions['status'] = 'success';
		$fileoptions['_multipart_status'] = 'Sent all parts.';
		if ( isset( $uploaded_speed ) ) {
			$fileoptions['write_speed'] = $uploaded_speed;
		}
		$fileoptions_obj->save();
		unset( $fileoptions );
		
		// Made it this far so completed!
		pb_backupbuddy::status( 'message', 'Finished sending file. Took ' . round( microtime( true ) - self::$_timeStart, 3 ) . ' seconds this round.' );
		pb_backupbuddy::status( 'deployFileSent', 'File sent.' );
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
		
		/*
		if ( ( $settings['address'] == '' ) || ( $settings['username'] == '' ) || ( $settings['password'] == '' ) ) {
			return __('Missing required input.', 'it-l10n-backupbuddy' );
		}
		*/
		
		// Try sending a file.
		return pb_backupbuddy_destinations::send( $settings, dirname( dirname( __FILE__ ) ) . '/remote-send-test.php', $send_id = 'TEST-' . pb_backupbuddy::random_string( 12 ) ); // 3rd param true forces clearing of any current uploads.
		
	} // End test().
	
	
} // End class.