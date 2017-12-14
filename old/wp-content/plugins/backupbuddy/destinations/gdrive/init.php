<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

// As of BackupBuddy v5.0 by Dustin Bolton.
class pb_backupbuddy_destination_gdrive {
	
	const TIME_WIGGLE_ROOM = 5;								// Number of seconds to fudge up the time elapsed to give a little wiggle room so we don't accidently hit the edge and time out.
	
	public static $destination_info = array(
		'name'			=>		'Google Drive',
		'description'	=>		'Send files to Google Drive. <a href="https://drive.google.com" target="_blank">Learn more here.</a>',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'					=> 'gdrive',	// MUST MATCH your destination slug.
		'title'					=> '',			// Required destination field.
		'client_id'				=> '',
		'client_secret'			=> '',
		'tokens'				=> '',			// Empty string if not yet authed. base64 encoded json string of tokens once authed. Google stores tokens in a json encoded string.
		'folderID'				=> '',
		'folderTitle'			=> '', // Friend title of the folder at the time of creation.
		
		'db_archive_limit'		=> '10',		// Maximum number of db backups for this site in this directory for this account. No limit if zero 0.
		'full_archive_limit' 	=> '4',		// Maximum number of full backups for this site in this directory for this account. No limit if zero 0.
		'files_archive_limit' 	=> '4',		// Maximum number of files only backups for this site in this directory for this account. No limit if zero 0.
		
		'max_time'				=> '',	// Default max time in seconds to allow a send to run for. Set to 0 for no time limit. Aka no chunking.
		'max_burst'				=> '25',	// Max size in mb of each burst within the same page load.
		'disabled'					=>		'0',		// When 1, disable this destination.
		
		'_chunks_sent'			=> 0,			// Internal chunk counting.
		'_chunks_total'			=> 0,			// Internal chunk counting.
		'_media_resumeUri'		=> '',
		'_media_progress'		=> '',			// fseek to here
	);
	
	private static $_isConnectedClientID = false;
	private static $_client = '';
	private static $_drive = '';
	private static $_timeStart = 0;
	private static $_chunksSentThisRound = 0;
	
	
	/* _normalizeSettings()
	 *
	 * Call on any incoming settings to normalize defaults, tokens format, etc.
	 *
	 */
	private static function _normalizeSettings( $settings ) {
		$settings = array_merge( self::$default_settings, $settings ); // Apply defaults.
		//echo 'TOKEN: ' .$settings['tokens'] . "\n\n\n";
		
		if ( strlen( $settings['tokens'] ) > 0 ) {
			// Currently base64 encoded. Unencode.
			if ( 0 !== strpos( $settings['tokens'], '{' ) ) {
				pb_backupbuddy::status( 'details', 'Tokens need base64 decoded.' );
				$settings['tokens'] = base64_decode( $settings['tokens'] );
				//$settings['tokens'] = stripslashes( $settings['tokens'] );
			} else {
				pb_backupbuddy::status( 'details', 'Tokens do not need base64 decoded. Already done.' );
			}
		}
		
		// If token are set but still in string format, change them into arrays.
		/*
		if ( strlen( $settings['tokens'] ) > 0 ) {
			if ( false === ( $settings['tokens'] = base64_decode( $settings['tokens'] ) ) ) {
				$settings['tokens'] = '';
				$error = 'Error #48387943: Unable to parse token data [base64_decode].';
				echo $error;
				pb_backupbuddy::status( 'error', $error );
			}
			if ( false === ( $settings['tokens'] = unserialize( $settings['tokens'] ) ) ) {
				$settings['tokens'] = '';
				$error = 'Error #32783733: Unable to parse token data [unserialize].';
				echo $error;
				pb_backupbuddy::status( 'error', $error );
			}
		}
		*/
		
		//print_r( $settings['tokens'] );
		
		return $settings;
	} // End _normalizeSettings().
	
	
	
	/* _connect()
	 *
	 * See http://stackoverflow.com/questions/15905104/automatically-refresh-token-using-google-drive-api-with-php-script
	 *
	 * @return	false|array 		false on failure to connect. Else Array of updated settings (token may be refreshed).
	 *
	 */
	public static function _connect( $settings ) {
		if ( $settings['client_id'] === self::$_isConnectedClientID ) { // Already connected to this account.
			return $settings;
		}
		
		set_include_path( pb_backupbuddy::plugin_path() . '/destinations/gdrive/' . PATH_SEPARATOR . get_include_path() );
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Client.php' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Http/MediaFileUpload.php' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Service/Drive.php' );
		
		$client_id = $settings['client_id'];
		$client_secret = $settings['client_secret'];
		$redirect_uri = 'urn:ietf:wg:oauth:2.0:oob';
		
		pb_backupbuddy::status( 'details', 'Connecting to Google Drive.' );
		self::$_client = new Google_Client();
		self::$_client->setClientId($client_id);
		self::$_client->setClientSecret($client_secret);
		self::$_client->setRedirectUri($redirect_uri);
		self::$_client->setAccessType('offline'); // Required so that Google will use the refresh token.
		self::$_client->addScope("https://www.googleapis.com/auth/drive");
		self::$_drive = new Google_Service_Drive( self::$_client );
		
		pb_backupbuddy::status( 'details', 'Setting Google Drive Access Token.' );
		try {
			pb_backupbuddy::status( 'details', 'TOKENS: ' . print_r( $settings['tokens'], true ) );
			self::$_client->setAccessToken( $settings['tokens'] );
		} catch (Exception $e) {
			pb_backupbuddy::status( 'error', 'Error #4839484984: Unable to set access token. Access token error details: `' . $e->getMessage() . '`.' );
			pb_backupbuddy::status( 'error', 'Error #8378327:  Tokens: `' . str_replace( "\t", ';', print_r( $settings['tokens'], true ) ) . '`.' );
			//error_log( 'Message: ' . $e->getMessage() );
			//error_log( 'Tokens: ' . print_r( $settings['tokens'], true ) );
			return false;
		}
		
		/*
		try {
			$result = self::$_client->authenticate(); //  $auth_code 
		} catch (Exception $e) {
			pb_backupbuddy::alert( 'Error Authenticating: ' . $e->getMessage() . ' Please go back, check codes, and try again.' );
			return false;
		}
		*/
		
		// Update tokens in settings.
		//$oldAccessTokens = json_decode( $settings['tokens']['refreshToken'], true );
		$newAccessToken = self::$_client->getAccessToken();
		//print_r( $newAccessToken );
		
		/*
		$accessTokens = json_decode( $newAccessToken, true );
		$accessTokens['refreshToken'] = $oldAccessTokens['refreshToken'];
		$settings['tokens'] = json_encode( $settings['tokens'] ); // Re-encode in JSON
		
		
		self::$_client->setAccessToken( $settings['tokens'] );
		*/
		
		$settings['tokens'] = $newAccessToken;
		
		self::$_isConnectedClientID = $settings['client_id'];
		return $settings;
		
	} // End _connect().
	
	
	
	/* _error()
	 *
	 * Logs error & places in error array. Always returns false.
	 *
	 */
	public static function _error( $error ) {
		pb_backupbuddy::status( 'error', $error );
		
		global $pb_backupbuddy_destination_errors;
		$pb_backupbuddy_destination_errors[] =  $error;
		
		return false;
	} // End _error().
	
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$files					Array of one or more files to send.
	 @	@param		bool			$delete_after			Delete LOCAL file after send.
	 @	@param		bool			$delete_remote_after	Delete REMOTE file after send.
	 *	@return		boolean									True on success, else false.
	 */
	public static function send( $settings = array(), $files = array(), $send_id = '', $delete_after = false, $delete_remote_after = false ) {
		global $pb_backupbuddy_destination_errors;
		if ( '1' == $settings['disabled'] ) {
			$pb_backupbuddy_destination_errors[] = __( 'Error #48933: This destination is currently disabled. Enable it under this destination\'s Advanced Settings.', 'it-l10n-backupbuddy' );
			return false;
		}
		if ( ! is_array( $files ) ) {
			$files = array( $files );
		}
		
		pb_backupbuddy::status( 'details', 'Google Drive send() function started. Settings: `' . print_r( $settings, true ) . '`.' );
		self::$_timeStart = microtime( true );
		
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) { // $settings = 
			return self::_error( 'Error #38923923: Unable to connect with Google Drive. See log for details.' );
		}
		
		$folderID = $settings['folderID'];
		if ( '' == $folderID ) {
			$folderID = 'root';
		}
		
		$chunkSizeBytes = $settings['max_burst'] * 1024 * 1024; // Send X mb at a time to limit memory usage.
		
		foreach( $files as $file ) {
			
			// Determine backup type for limiting later.
			$backup_type = '';
			if ( stristr( $file, '-db-' ) !== false ) {
				$backup_type = 'db';
			} elseif ( stristr( $file, '-full-' ) !== false ) {
				$backup_type = 'full';
			} elseif ( stristr( $file, '-files-' ) !== false ) {
				$backup_type = 'files';
			} elseif ( stristr( $file, '-export-' ) !== false ) {
				$backup_type = 'export';
			}
			
			if ( ! file_exists( $file ) ) {
				return self::_error( 'Error #37792: File selected to send not found: `' . $file . '`.' );
			}
			
			$fileSize = filesize( $file );
			$fileinfo = pathinfo( $file );
			$fileextension = $fileinfo['extension'];
			if ( 'zip' == $fileextension ) {
				$mimeType = 'application/zip';
			} elseif ( 'php' == $fileextension ) {
				$mimeType = 'application/x-httpd-php';
			} else {
				$mimeType = '';
			}
			pb_backupbuddy::status( 'details', 'About to upload file `' . $file . '` of size `' . $fileSize . '` with mimetype `' . $mimeType . '` into folder `' . $folderID . '`. Internal chunk size of `' . $chunkSizeBytes . '` bytes.' );
			if ( $fileSize > $chunkSizeBytes ) {
				pb_backupbuddy::status( 'details', 'File size `' . pb_backupbuddy::$format->file_size( $fileSize ) . '` exceeds max burst size `' . $settings['max_burst'] . ' MB` so this will be sent in bursts. If time limit nears then send will be chunked across multiple PHP loads.' );
				$settings['_chunks_total'] = ceil( $fileSize / $chunkSizeBytes );
			}
			if ( 0 == $settings['_chunks_total'] ) {
				$settings['_chunks_total'] = 1;
			}
			//Insert a file
			$driveFile = new Google_Service_Drive_DriveFile();
			$driveFile->setTitle( basename( $file ) );
			$driveFile->setDescription( 'BackupBuddy file' );
			$driveFile->setMimeType( $mimeType );
			
			// Set the parent folder.
			if ( 'root' != $folderID ) {
				$parentsCollectionData = new Google_Service_Drive_ParentReference();
				$parentsCollectionData->setId( $folderID );
				$driveFile->setParents( array( $parentsCollectionData ) );
			}
			
			self::$_client->setDefer( true );
			try {
				$insertRequest = self::$_drive->files->insert( $driveFile );
			} catch (Exception $e) {
				pb_backupbuddy::alert( 'Error #3232783268336: initiating upload. Details: ' . $e->getMessage() );
				return false;
			}
			
			
			
			
			
			
			// Handle getting resume information to see if resuming is still an option.
			$resumable = false;
			if ( '' != $settings['_media_resumeUri'] ) {
				$headers = array( 'content-range' => 'bytes */' . $fileSize );
				$request = new Google_Http_Request(
					$settings['_media_resumeUri'],
					'PUT',
					$headers,
					''
				);
				$response = self::$_client->getIo()->makeRequest( $request );
				if ( 308 == $response->getResponseHttpCode() ) {
					$range = $response->getResponseHeader( 'range' );
					if ( ( ! empty( $range ) ) && preg_match( '/bytes=0-(\d+)$/', $range, $matches ) ) {
						$resumable = true;
						pb_backupbuddy::status( 'details', 'Last send reported next byte to be `' . $settings['_media_progress'] . '`.' );
						$settings['_media_progress'] = $matches[1] + 1;
						pb_backupbuddy::status( 'details', 'Google Drive resuming is available. Google Drive reports next byte to be `' . $settings['_media_progress'] . '`. Range: `' . $range . '`.' );
					}
				}
				if ( ! $resumable ) {
					pb_backupbuddy::status( 'details', 'Google Drive could not resume. Too much time may have passed or some other cause.' );
				}
				if ( $settings['_media_progress'] >= $fileSize ) {
					pb_backupbuddy::status( 'details', 'Google Drive resuming not needed. Remote file meets or exceeds file size. Completed.' );
					return true;
				}
			}
			
			
			
			
			
			
			
			
			// See https://developers.google.com/api-client-library/php/guide/media_upload
			try {
				$media = new Google_Http_MediaFileUpload(
					self::$_client,
					$insertRequest,
					$mimeType,
					null,
					true,
					$chunkSizeBytes
				);
			} catch (Exception $e) {
				pb_backupbuddy::alert( 'Error #3893273937: initiating upload. Details: ' . $e->getMessage() );
				return;
			}
			$media->setFileSize( $fileSize );
			
			
			// Reset these internal variables. NOTE: These are by default private. Must modify MediaFileUpload.php to make this possible by setting these vars public. Thanks Google!
			if ( '' != $settings['_media_resumeUri'] ) {
				$media->resumeUri = $settings['_media_resumeUri'];
				$media->progress = $settings['_media_progress'];
			}
			
			
			pb_backupbuddy::status( 'details', 'Opening file for sending in binary mode.' );
			$fs = fopen( $file , 'rb' );
			
			
			// If chunked resuming then seek to the correct place in the file.
			if ( ( '' != $settings['_media_progress'] ) && ( $settings['_media_progress'] > 0 ) ) { // Resuming send of a partially transferred file.
				if ( 0 !== fseek( $fs, $settings['_media_progress'] ) ) { // Go off the resume point as given by Google in case it didnt all make it. //$settings['resume_point'] ) ) { // Returns 0 on success.
					pb_backupbuddy::status( 'error', 'Error #3872733: Failed to seek file to resume point `' . $settings['_media_progress'] . '` via fseek().' );
					return false;
				}
				$prevPointer = $settings['_media_progress']; //$settings['resume_point'];
			} else { // New file send.
				$prevPointer = 0;
			}
			
			$needProcessChunking = false; // Set true if we need to spawn off resuming to a new PHP page load.
			$uploadStatus = false;
			while (!$uploadStatus && !feof($fs)) {
				$chunk = fread($fs, $chunkSizeBytes);
				pb_backupbuddy::status( 'details', 'Chunk of size `' . pb_backupbuddy::$format->file_size( $chunkSizeBytes ) . '` read into memory. Total bytes summed: `' . ( $settings['_media_progress'] + strlen( $chunk) ) . '` of filesize: `' . $fileSize . '`.' );
				pb_backupbuddy::status( 'details', 'Sending burst file data next. If next message is not "Burst file data sent" then the send likely timed out. Try reducing burst size. Sending now...' );
				
				// Send chunk of data.
				try {
					$uploadStatus = $media->nextChunk( $chunk );
				} catch (Exception $e) {
					global $pb_backupbuddy_destination_errors;
					$pb_backupbuddy_destination_errors[] =  $e->getMessage();
					$error = $e->getMessage();
					pb_backupbuddy::status( 'error', 'Error #8239832: Error sending burst data. Details: `' . $error . '`.' );
					return false;
				}
				$settings['_chunks_sent']++; 
				self::$_chunksSentThisRound++;
				pb_backupbuddy::status( 'details', 'Burst file data sent.' );
				
				$maxTime = $settings['max_time'];
				if ( ( '' == $maxTime ) || ( ! is_numeric( $maxTime ) ) ) {
					pb_backupbuddy::status( 'details', 'Max time not set in settings so detecting server max PHP runtime.' );
					$maxTime = backupbuddy_core::detectMaxExecutionTime();
				}
				
				//return;
				
				// Handle splitting up across multiple PHP page loads if needed.
				if ( !feof($fs) && ( 0 != $maxTime ) ) { // More data remains so see if we need to consider chunking to a new PHP process.
					// If we are within X second of reaching maximum PHP runtime then stop here so that it can be picked up in another PHP process...
					
					$totalSizeSent = self::$_chunksSentThisRound * $chunkSizeBytes; // Total bytes sent this PHP load.
					$bytesPerSec = $totalSizeSent / ( microtime( true ) - self::$_timeStart );
					$timeRemaining = $maxTime - ( ( microtime( true ) - self::$_timeStart ) + self::TIME_WIGGLE_ROOM );
					if ( $timeRemaining < 0 ) {
						$timeRemaining = 0;
					}
					$bytesWeCouldSendWithTimeLeft = $bytesPerSec * $timeRemaining;
					
					pb_backupbuddy::status( 'details', 'Total sent: `' . pb_backupbuddy::$format->file_size( $totalSizeSent ) .'`. Speed (per sec): `' . pb_backupbuddy::$format->file_size( $bytesPerSec ) . '`. Time Remaining (w/ wiggle): `' . $timeRemaining . '`. Size that could potentially be sent with remaining time: `' . pb_backupbuddy::$format->file_size( $bytesWeCouldSendWithTimeLeft ) . '` with chunk size of `' . pb_backupbuddy::$format->file_size( $chunkSizeBytes ) . '`.' );
					if ( $bytesWeCouldSendWithTimeLeft < $chunkSizeBytes ) { // We can send more than a whole chunk (including wiggle room) so send another bit.
						pb_backupbuddy::status( 'message', 'Not enough time left (~`' . $timeRemaining  . '`) with max time of `' . $maxTime . '` sec to send another chunk at `' . pb_backupbuddy::$format->file_size( $bytesPerSec ) . '` / sec. Ran for ' . round( microtime( true ) - self::$_timeStart, 3 ) . ' sec. Proceeding to use chunking.' );
						@fclose( $fs );
						
						// Tells next chunk where to pick up.
						if ( isset( $chunksTotal ) ) {
							$settings['_chunks_total'] = $chunksTotal;
						}
						
						// Grab these vars from the class.  Note that we changed these vars from private to public to make chunked resuming possible.
						$settings['_media_resumeUri'] = $media->resumeUri;
						$settings['_media_progress'] = $media->progress;
						
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
						
						
						return array( $prevPointer, 'Sent part ' . $settings['_chunks_sent'] . ' of ~' . $settings['_chunks_total'] . ' parts.' ); // filepointer location, elapsed time during the import
					} else { // End if.
						pb_backupbuddy::status( 'details', 'Not approaching limits.' );
					}
				} else {
					pb_backupbuddy::status( 'details', 'No more data remains (eg for chunking) so finishing up.' );
					
					if ( '' != $send_id ) {
						require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
						$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
						if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
							pb_backupbuddy::status( 'error', __('Fatal Error #9034.397237. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
							return false;
						}
						pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
						$fileoptions = &$fileoptions_obj->options;
						
						$fileoptions['_multipart_status'] = 'Sent part ' . $settings['_chunks_sent'] . ' of ~' . $settings['_chunks_total'] . ' parts.';
						$fileoptions['finish_time'] = microtime(true);
						$fileoptions['status'] = 'success';
						$fileoptions_obj->save();
						unset( $fileoptions_obj );
					}
				}
				
				
			}
			fclose($fs);
			
			self::$_client->setDefer( false );
			
			if ( false == $uploadStatus ) {
				global $pb_backupbuddy_destination_errors;
				$pb_backupbuddy_destination_errors[] = 'Error #84347474 sending. Details: ' . $uploadStatus;
				return false;
			} else { // Success.
				if ( true === $delete_remote_after ) {
					self::deleteFile( $settings, $uploadStatus->id );
				}
			}
			
		} // end foreach.
		
		
		$db_archive_limit = $settings['db_archive_limit'];
		$full_archive_limit = $settings['full_archive_limit'];
		$files_archive_limit = $settings['files_archive_limit'];
		
		
		// BEGIN FILE LIMIT PROCESSING. Enforce archive limits if applicable.
		if ( $backup_type == 'full' ) {
			$limit = $full_archive_limit;
		} elseif ( $backup_type == 'db' ) {
			$limit = $db_archive_limit;
		} elseif ( $backup_type == 'files' ) {
			$limit = $files_archive_limit;
		} else {
			$limit = 0;
			pb_backupbuddy::status( 'warning', 'Warning #34352453244. Google Drive was unable to determine backup type (reported: `' . $backup_type . '`) so archive limits NOT enforced for this backup.' );
		}
		pb_backupbuddy::status( 'details', 'Google Drive database backup archive limit of `' . $limit . '` of type `' . $backup_type . '` based on destination settings.' );
		
		if ( $limit > 0 ) {
			
			pb_backupbuddy::status( 'details', 'Google Drive archive limit enforcement beginning.' );
			
			// Get file listing.
			$remoteFiles = pb_backupbuddy_destination_gdrive::listFiles( $settings, "title contains 'backup-' AND title contains '-" . $backup_type . "-' AND '" . $folderID . "' IN parents AND trashed=false" ); //"title contains 'backup' and trashed=false" );
			
			// List backups associated with this site by date.
			$backups = array();
			$prefix = backupbuddy_core::backup_prefix();
			foreach( $remoteFiles as $remoteFile ) {
				if ( 'application/vnd.google-apps.folder' == $remoteFile->mimeType ) { // Ignore folders.
					continue;
				}
				if ( ( strpos( $remoteFile->originalFilename, 'backup-' . $prefix . '-' ) !== false ) ) { // Appears to be a backup file for this site.
					$backups[ $remoteFile->id ] = strtotime( $remoteFile->modifiedDate );
				}
			}
			arsort( $backups );
			
			
			pb_backupbuddy::status( 'details', 'Google Drive found `' . count( $backups ) . '` backups of this type when checking archive limits.' );
			if ( ( count( $backups ) ) > $limit ) {
				pb_backupbuddy::status( 'details', 'More archives (' . count( $backups ) . ') than limit (' . $limit . ') allows. Trimming...' );
				$i = 0;
				$delete_fail_count = 0;
				foreach( $backups as $buname => $butime ) {
					$i++;
					if ( $i > $limit ) {
						pb_backupbuddy::status ( 'details', 'Trimming excess file `' . $buname . '`...' );
						
						if ( true !== self::deleteFile( $settings, $buname ) ) {
							pb_backupbuddy::status( 'details',  'Unable to delete excess Google Drive file `' . $buname . '`. Details: `' . print_r( $pb_backupbuddy_destination_errors, true ) . '`.' );
							$delete_fail_count++;
						}
					}
				}
				pb_backupbuddy::status( 'details', 'Finished trimming excess backups.' );
				if ( $delete_fail_count !== 0 ) {
					$error_message = 'Google Drive remote limit could not delete ' . $delete_fail_count . ' backups.';
					pb_backupbuddy::status( 'error', $error_message );
					backupbuddy_core::mail_error( $error_message );
				}
			}
			
			pb_backupbuddy::status( 'details', 'Google Drive completed archive limiting.' );
			
		} else {
			pb_backupbuddy::status( 'details',  'No Google Drive archive file limit to enforce.' );
		} // End remote backup limit
		
		
		// Made it this far then success.
		return true;
		
	} // End send().
	
	
	
	public static function getDriveInfo( $settings ) {
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		$service = &self::$_drive;
		
		try {
			$about = $service->about->get();
		} catch (Exception $e) {
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] =  $e->getMessage();
			$error = $e->getMessage();
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		$info = array();
		$info['quotaTotal'] = $about->quotaBytesTotal;
		$info['quotaUsed'] = $about->quotaBytesUsed;
		$info['name'] = $about->name;
		
		return $info;
		
	} // End getDriveInfo().
	
	
	/*	test()
	 *	
	 *	Sends a text email with ImportBuddy.php zipped up and attached to it.
	 *	
	 *	@param		array			$settings	Destination settings.
	 *	@return		bool|string					True on success, string error message on failure.
	 */
	public static function test( $settings ) {
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		
		pb_backupbuddy::status( 'details', 'Testing Google Drive destination. Sending ImportBuddy.php.' );
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
		$files = array( dirname( dirname( __FILE__ ) ) . '/remote-send-test.php' );
		
		$results = self::send( $settings, $files, '', $delete_local = false, $delete_remote_after = true );
		
		@unlink( $importbuddy_temp );
		
		if ( true === $results ) {
			echo 'Success sending test file to Google Drive. ';
			return true;
		} else {
			echo 'Failure sending test file to Google Drive.'; // Detailed errors are send via remote_test.php response.
			return false;
		}
		
	} // End test().
	
	
	
	/* listFiles()
	 *
	 * List files in destination. See https://developers.google.com/drive/v2/reference/files/list
	 *
	 * @param	array 	$settings	Destination settings array.
	 * @param	string	$query		Search query to find files matching. Eg $query = "title contains 'backup'";
	 *
	 */
	public static function listFiles( $settings, $query ) {
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #233233: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		$service = &self::$_drive;
		
		$result = array();
		$pageToken = NULL;

		do {
			try {
			  $parameters = array( 'q' => $query );
			  if ($pageToken) {
			    $parameters['pageToken'] = $pageToken;
			  }
			  $files = $service->files->listFiles($parameters);

			  $result = array_merge($result, $files->getItems());
			  $pageToken = $files->getNextPageToken();
			} catch (Exception $e) {
			  print "An error occurred: " . $e->getMessage();
			  $pageToken = NULL;
			}
		} while ($pageToken);
		
		return $result;
		
	} // End listFiles().
	
	
	
	/* listParents()
	 *
	 * List files in destination. See https://developers.google.com/drive/v2/reference/files/list
	 *
	 * @param	array 	$settings	Destination settings array.
	 * @param	string	$query		Search query to find files matching. Eg $query = "title contains 'backup'";
	 *
	 */
	public static function listChildren( $settings, $folderID, $query = '' ) {
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #2398322: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		if ( '' == $folderID ) {
			$folderID = 'root';
		}
		
		$service = &self::$_drive;
		
		$result = array();
		$pageToken = NULL;

		do {
			try {
			  $parameters = array( 'q' => $query );
			  if ($pageToken) {
			    $parameters['pageToken'] = $pageToken;
			  }
			  $children = $service->children->listChildren( $folderID, $parameters);

			  $result = array_merge($result, $children->getItems());
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  print "An error occurred: " . $e->getMessage();
			  $pageToken = NULL;
			}
		} while ($pageToken);
		
		return $result;
		
	} // End listParents().
	
	
	
	public static function getFileMeta( $settings, $fileID ) {
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #3839483: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		try {
			
			$fileMeta = self::$_drive->files->get( $fileID );
			return $fileMeta;
			
		} catch (Exception $e) {
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] =  $e->getMessage();
			$error = $e->getMessage();
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
	} // End getFileMeta();
	
	/* getFile()
	 *
	 * Download file from destination to this system.
	 *
	 * @param	array 	$settings			Destination settings array.
	 * @param	string 	$fileID				File identifier on destination.
	 * @param	string 	$parentID			Identifier of the container the file is within. Eg ID of the parent directory.
	 * @param	string	$destinationFile	Full file path & name to store returned file/data into.
	 * @return	bool						True on success, else false.
	 *
	 */
	public static function getFile( $settings, $fileID, $destinationFile ) {
		error_log( 'BackupBuddy Gdrive getFile() not yet implemented.' );
		die( 'BackupBuddy Gdrive getFile() not yet implemented.' );
		
		
		pb_backupbuddy::status( 'details', 'About to get Google Drive File with ID `' . $fileID . '` to store in `' . $destinationFile . '`.' );
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #3839483: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		/*
		if ( '' == $parentID ) {
			$parentID = 'root';
		}
		*/
		
		$optParams = array(
			'alt' => 'media', // Instruct that file contents be returned.
		);
		
		try {
			
			$fileBody = self::$_drive->files->get( $fileID, $optParams );
			if ( true !== file_put_contents( $destinationFile, $fileBody ) ) {
				$error = 'Error #54832983: Unable to save requested Google Drive file contents into file `' . $destinationFile . '`.';
				echo $error;
				pb_backupbuddy::status( 'error', $error );
				return false;
			}
			
		} catch (Exception $e) {
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] =  $e->getMessage();
			$error = $e->getMessage();
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		return true;
	} // End getFile().
	
	
	
	/* deleteFile()
	 *
	 * Deletes a file stored in destination.
	 *
	 */
	public static function deleteFile( $settings, $fileID ) {
		pb_backupbuddy::status( 'details', 'Deleting Google Drive file with ID `' . $fileID . '`.' );
		$settings = self::_normalizeSettings( $settings );
		
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #4839484: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		try {
			
			self::$_drive->files->delete( $fileID );
			
		} catch (Exception $e) {
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] =  $e->getMessage();
			$error = $e->getMessage();
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Google Drive file deleted.' );
		return true;
	} // End deleteFile().
	
	
	
	/* createFolder()
	 *
	 * Create a new folder in the user's Dropbox at specified parent location.
	 *
	 * @return	array 	Array( newItemID, newItemTitle ).
	 */
	public static function createFolder( $settings, $parentID, $folderName ) {
		if ( '' == $parentID ) {
			$parentID = 'root';
		}
		
		$settings = self::_normalizeSettings( $settings );
		if ( false === ( $settings = self::_connect( $settings ) ) ) {
			$error = 'Error #2378327: Unable to connect with Google Drive. See log for details.';
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		//Insert a folder
		$driveFile = new Google_Service_Drive_DriveFile();
		$driveFile->setTitle( $folderName );
		//$driveFile->setParents( array( $parentID ) );
		$driveFile->setMimeType( 'application/vnd.google-apps.folder' );
		
		// Set the parent folder.
		if ( 'root' != $parentID ) {
			$parentsCollectionData = new Google_Service_Drive_ParentReference();
			$parentsCollectionData->setId( $parentID );
			$driveFile->setParents( array( $parentsCollectionData ) );
		}
		
		try {
			$insertRequest = self::$_drive->files->insert( $driveFile );
			return array( $insertRequest->id, $insertRequest->title );
		} catch (Exception $e) {
			global $pb_backupbuddy_destination_errors;
			$pb_backupbuddy_destination_errors[] =  $e->getMessage();
			$error = $e->getMessage();
			echo $error;
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
	} // End createFolder().
	
	
	
	public static function printFolderSelector( $destinationID ) {
		
		// Only output into page once.
		global $backupbuddy_gdrive_folderSelector_printed;
		if ( true !== $backupbuddy_gdrive_folderSelector_printed ) {
			$backupbuddy_gdrive_folderSelector_printed = true;
		} else { // This has already been printed.
			return;
		}
		?>
		<script>
			var backupbuddy_gdrive_folderSelect_path = [];
			var backupbuddy_gdrive_folderSelect_pathNames = []; //{ 'root': '/' };
			
			
			
			function backupbuddy_gdrive_getDestinationWrap( destinationID ) {
				if ( 'NEW' != destinationID ) {
					destinationWrap = jQuery( '.backupbuddy-destination-wrap[data-destination_id="' + destinationID + '"]' );
				} else {
					destinationWrap = jQuery( '.pb_backupbuddy_destpicker_id' );
				}
				return destinationWrap;
			}
			
			
			
			function backupbuddy_gdrive_folderSelect( destinationID, loadParentID, loadParentTitle, command, finishedCallback ) {
				jQuery( '.backupbuddy-gdrive-statusText' ).text( '' );
				
				if ( 'undefined' == typeof backupbuddy_gdrive_folderSelect_path[ destinationID ] ) {
					backupbuddy_gdrive_folderSelect_path[ destinationID ] = [];
					backupbuddy_gdrive_folderSelect_pathNames[ destinationID ] = { 'root': '/' };
				}
				
				if ( ( 'undefined' == typeof loadParentID ) || ( '' == loadParentID ) ) {
					loadParentID = 'root';
					loadParentTitle = '';
				}
				
				destinationWrap = backupbuddy_gdrive_getDestinationWrap( destinationID );
				
				destinationWrap.find( '.pb_backupbuddy_loading' ).show();
				
				clientID = destinationWrap.find( '#pb_backupbuddy_client_id' ).val();
				clientSecret = destinationWrap.find( '#pb_backupbuddy_client_secret' ).val();
				tokens = destinationWrap.find( '#pb_backupbuddy_tokens' ).val();
				
				jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'gdrive_folder_select' ); ?>', { clientID: clientID, clientSecret: clientSecret, tokens: tokens, parentID: loadParentID },  backupbuddy_gdrive_folderSelect_ajaxResponse( destinationID, loadParentID, loadParentTitle, command ) );
			}
			
			
			
			// Using closure callback so the destinationID can be passed into this and not be modified by other instances.
			function backupbuddy_gdrive_folderSelect_ajaxResponse( destinationID, loadParentID, loadParentTitle, command ) {
				return function(data, textStatus, jqXHR) {
					destinationWrap = backupbuddy_gdrive_getDestinationWrap( destinationID );
					//console.log( 'Gdrive response for destination ' + destinationID );
					
					destinationWrap.find( '.pb_backupbuddy_loading' ).hide();
					data = jQuery.trim( data );
					try {
						var data = jQuery.parseJSON( data );
					} catch(e) {
						alert( 'Error #48349844: Unexpected non-json response from server: `' + data + '`.' );
						return;
					}
					if ( true !== data.success ) {
						alert( 'Error #838933: Unable to get folder data. Details: `' + data.message + '`.' );
						return;
					}
					
					if ( 'goback' == command ) {
						removed = backupbuddy_gdrive_folderSelect_path[ destinationID ].pop();
						delete backupbuddy_gdrive_folderSelect_pathNames[ removed ];
					} else if ( 'refresh' == command ) {
						// same location
					} else {
						backupbuddy_gdrive_folderSelect_path[ destinationID ].push( loadParentID );
						backupbuddy_gdrive_folderSelect_pathNames[ destinationID ][ loadParentID ] = loadParentTitle;
					}
					if ( 1 == backupbuddy_gdrive_folderSelect_path[ destinationID ].length ) {
						destinationWrap.find( '.backupbuddy-gdrive-back' ).prop( 'disabled', true );
					} else {
						destinationWrap.find( '.backupbuddy-gdrive-back' ).prop( 'disabled', false );
					}
					//console.dir( backupbuddy_gdrive_folderSelect_path[ destinationID ] );
					//console.dir( backupbuddy_gdrive_folderSelect_pathNames[ destinationID ] );
					
					destinationWrap.find( '.backupbuddy-gdrive-folderList' ).empty(); // Clear current listing.
					//console.log( 'GDrive Folders:' );
					//console.dir( data );
					
					// Update breadcrumbs.
					breadcrumbs = '';
					jQuery.each( backupbuddy_gdrive_folderSelect_path[ destinationID ], function( index, crumb ) {
						breadcrumbs = breadcrumbs + backupbuddy_gdrive_folderSelect_pathNames[ destinationID ][ crumb ] + '/';
					});
					destinationWrap.find( '.backupbuddy-gdrive-breadcrumbs' ).text( breadcrumbs );
					
					jQuery.each( data.folders, function( index, folder ) {
						destinationWrap.find( '.backupbuddy-gdrive-folderList' ).append( '<span data-id="' + folder.id + '" class="backupbuddy-gdrive-folderList-folder"><span class="backupbuddy-gdrive-folderList-selected pb_label pb_label-info" title="Select this folder to use.">Select</span> <span class="backupbuddy-gdrive-folderList-open dashicons dashicons-plus" title="Expand folder & view folders within"></span> <span class="backupbuddy-gdrive-folderList-title backupbuddy-gdrive-folderList-open">' + folder.title + '</span><span class="backupbuddy-gdrive-folderList-createdWrap"><span class="backupbuddy-gdrive-folderList-created">' + folder.created + '</span>&nbsp;&nbsp;Modified <span class="backupbuddy-gdrive-folderList-createdAgo">' + folder.createdAgo + ' ago</span></span></span>' );
					});
					if ( 0 === data.folders.length ) {
						destinationWrap.find( '.backupbuddy-gdrive-folderList' ).append( '<span class="description">No folders found at this location in your Google Drive.</span>' );
					}
					
					if ( 'function' == typeof finishedCallback ) {
						finishedCallback();
					}
				};
			} // End backupbuddy_gdrive_folderSelect_ajaxResponse().
			
			
			
			function backupbuddy_gdrive_setFolder( destinationID, id, title ) {
				destinationWrap = backupbuddy_gdrive_getDestinationWrap( destinationID );
				
				destinationWrap.find( '.backupbuddy-gdrive-folderTitleText' ).text( 'Selected folder name: "' + title + '"' );
				destinationWrap.find( '#pb_backupbuddy_folderID' ).val( id );
				destinationWrap.find( '#pb_backupbuddy_folderTitle' ).val( title );
			} // End backupbuddy_gdrive_setFolder().
			
			
			
			jQuery(document).ready(function() {
				//backupbuddy_gdrive_folderSelect();
				
				// OPEN a folder.
				jQuery( '.pb_backupbuddy_settings_form' ).on( 'click', '.backupbuddy-gdrive-folderList-open', function(event){
					destinationID = jQuery(this).closest( '.backupbuddy-gdrive-folderSelector' ).attr( 'data-destinationID' );
					
					folderObj = jQuery(this).closest( '.backupbuddy-gdrive-folderList-folder' );
					id = folderObj.attr( 'data-id' );
					title = folderObj.find('.backupbuddy-gdrive-folderList-title').text();
					backupbuddy_gdrive_folderSelect( destinationID, id, title );
				});
				
				// Go UP a folder
				jQuery( '.pb_backupbuddy_settings_form' ).on( 'click', '.backupbuddy-gdrive-back', function(e){
					e.preventDefault();
					
					destinationID = jQuery(this).closest( '.backupbuddy-gdrive-folderSelector' ).attr( 'data-destinationID' );
					
					prevFolderID = backupbuddy_gdrive_folderSelect_path[ destinationID ][ backupbuddy_gdrive_folderSelect_path[ destinationID ].length - 2 ];
					backupbuddy_gdrive_folderSelect( destinationID, prevFolderID, '', 'goback' );
				});
				
				// SELECT a folder.
				jQuery( '.pb_backupbuddy_settings_form' ).on( 'click', '.backupbuddy-gdrive-folderList-selected', function(e){
					destinationID = jQuery(this).closest( '.backupbuddy-gdrive-folderSelector' ).attr( 'data-destinationID' );
					
					folderObj = jQuery(this).closest( '.backupbuddy-gdrive-folderList-folder' );
					id = folderObj.attr( 'data-id' );
					title = folderObj.find('.backupbuddy-gdrive-folderList-title').text();
					
					backupbuddy_gdrive_setFolder( destinationID, id, title );
				});
				
				// CREATE a folder.
				jQuery( '.pb_backupbuddy_settings_form' ).on( 'click', '.backupbuddy-gdrive-createFolder', function(e){
					e.preventDefault();
					destinationID = jQuery(this).closest( '.backupbuddy-gdrive-folderSelector' ).attr( 'data-destinationID' );
					destinationWrap = backupbuddy_gdrive_getDestinationWrap( destinationID );
					
					currentFolderID = backupbuddy_gdrive_folderSelect_path[ destinationID ][ backupbuddy_gdrive_folderSelect_path[ destinationID ].length - 1 ];
					if ( null === ( newFolderName = prompt( 'What would you like the new folder to be named?' ) ) ) {
						return false; // User hit cancek.
					}
					
					jQuery( '.pb_backupbuddy_loading' ).show();
					jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'gdrive_folder_create' ); ?>', { clientID: destinationWrap.find( '#pb_backupbuddy_client_id' ).val(), clientSecret: destinationWrap.find( '#pb_backupbuddy_client_secret' ).val(), tokens: destinationWrap.find( '#pb_backupbuddy_tokens' ).val(), parentID: currentFolderID, folderName: newFolderName }, 
						function(data) {
							destinationWrap.find( '.pb_backupbuddy_loading' ).hide();
							data = jQuery.trim( data );
							try {
								var data = jQuery.parseJSON( data );
							} catch(e) {
								alert( 'Error #3298484: Unexpected non-json response from server: `' + data + '`.' );
								return;
							}
							if ( true !== data.success ) {
								alert( 'Error #32793793: Unable to create folder. Details: `' + data.message + '`.' );
								return;
							}
							
							/*
							Gets back on success:
							data.folderID
							data.folderTitle
							*/
							backupbuddy_gdrive_setFolder( destinationID, data.folderID, data.folderTitle );
							
							finishedCallback = function(){
								destinationWrap.find( '.backupbuddy-gdrive-statusText' ).text( 'Created & selected new folder.' );
							};
							
							// Refresh current folder.
							backupbuddy_gdrive_folderSelect( destinationID, currentFolderID, backupbuddy_gdrive_folderSelect_pathNames[ currentFolderID ], 'refresh', finishedCallback );
						}
					);
				});
				
				
			});
			
		</script>

		<style>
			.backupbuddy-gdrive-folderList {
				border: 1px solid #DFDFDF;
				background: #F9F9F9;
				padding: 5px;
				margin-top: 5px;
				margin-bottom: 10px;
				max-height: 175px;
				overflow: auto;
			}
			
			.backupbuddy-gdrive-folderList::-webkit-scrollbar {
				-webkit-appearance: none;
				width: 11px;
				height: 11px;
			}
			.backupbuddy-gdrive-folderList::-webkit-scrollbar-thumb {
				border-radius: 8px;
				border: 2px solid white; /* should match background, can't be transparent */
				background-color: rgba(0, 0, 0, .1);
			}
			.backupbuddy-gdrive-folderList::-webkit-scrollbar-corner{
				background-color:rgba(0,0,0,0.0);
			}
			
			.backupbuddy-gdrive-folderList > span {
				display: block;
				//padding: 0;
				//margin-left: -4px;
			}
			.backupbuddy-gdrive-folderList-selected {
				cursor: pointer;
				opacity: 0.8;
			}
			.backupbuddy-gdrive-folderList-selected:hover {
				opacity: 1;
			}
			.backupbuddy-gdrive-folderList-open {
				cursor: pointer;
			}
			.backupbuddy-gdrive-folderList-open:hover {
				opacity: 1;
			}
			.backupbuddy-gdrive-folderList > span:hover {
				background: #eaeaea;
			}
			.backupbuddy-gdrive-folderList-folder {
				border-bottom: 1px solid #EBEBEB;
				padding: 5px;
			}
			.backupbuddy-gdrive-folderList-folder:last-child {
				border-bottom: 0;
			}
			.backupbuddy-gdrive-folderList-title {
				//font-size: 1.2em;
			}
			.backupbuddy-gdrive-folderList-createdWrap {
				float: right;
				padding-right: 15px;
				color: #bebebe;
			}
			.backupbuddy-gdrive-folderList-created {
				color: #000;
			}
			.backupbuddy-gdrive-folderList-createdAgo {
				//opacity: 0.6;
			}
		</style>

		<?php
		echo '<div class="backupbuddy-gdrive-folderSelector" data-isTemplate="true" style="display: none;">';
		_e( 'Click <span class="backupbuddy-gdrive-folderList-selected pb_label pb_label-info" title="Select this folder to use.">Select</span> to choose folder for storage or <span class="dashicons dashicons-plus"></span> to expand & enter folder. Current Path: ', 'it-l10n-backupbuddy' );
		echo '<span class="backupbuddy-gdrive-breadcrumbs">/</span>';
		echo '<div class="backupbuddy-gdrive-folderList">';

		echo '</div>';
		echo '<button class="backupbuddy-gdrive-back thickbox button secondary-button">&larr; Back</button>&nbsp;&nbsp;';
		echo '<button class="backupbuddy-gdrive-createFolder thickbox button secondary-button">Create Folder</button> ';
		echo '&nbsp;<span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px; vertical-align: -5px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span>';
		echo '&nbsp;<span class="backupbuddy-gdrive-statusText" style="vertical-align: -5px; font-style: italic;"></span>';
		echo '</div>';
	} // End printFolderSelector().
	
	
	
	
} // End class.

