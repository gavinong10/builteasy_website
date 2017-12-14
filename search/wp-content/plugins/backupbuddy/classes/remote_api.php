<?php
require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );


class backupbuddy_remote_api {
	
	private static $_errors = array();		// Hold error strings to retrieve with getErrors().
	
	public static function localCall( $secure = false, $importbuddy = false ) {
		if ( true !== $secure ) {
			die( '<html>403 Access Denied</html>' );
		}
		
		if ( true !== self::is_call_valid() ) {
			$message = 'Error #8002: Error validating API call authenticity. Verify you are using the correct active API key.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// If here then validation was all good. API call is authorized.
		
		if ( true !== $importbuddy ) {
			$functionName = '_verb_' . pb_backupbuddy::_POST( 'verb' );
		} else {
			$functionName = '_verb_importbuddy_' . pb_backupbuddy::_POST( 'verb' );
		}
		
		// Does verb exist?
		if ( false === method_exists( 'backupbuddy_remote_api', $functionName ) ) {
			$message = 'Error #843489974: Unknown verb `' . pb_backupbuddy::_POST( 'verb' ) . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		} else {
			call_user_func_array( 'backupbuddy_remote_api::' . $functionName, array() );
		}
		
		// function: verb_[VERBHERE]
	}
	
	
	
	/* remoteCall()
	 *
	 * Send an API call to a remote server.
	 * @param	array 	$remoteAPI		Remote API state array including URL, etc. Stored in destination settings.
	 * @param	string	$verb			API verb to call on remote server.
	 * @param	array 	$moreParams		Optional: Additional parameters to append to those sent.  If needing to send a non-string this should be encoded in some manner and decoded on remote.
	 * @param	int		$timeout		Optional: How long we expect this can last before a server times out.  Typically the minimum of the local and remote timeouts.
	 * @param	string	$file			Optional: File we are sending. This is passed so that various CRC data can be calculated.
	 * @param	string	$fileData		Optional: Raw file contents to send (for this chunk if using chunking).
	 * @param	int		$seekTo			Optional: Location to fseek to in the file for writing.
	 * @param	bool	$isFileTest		Optional: When true the destination will auto-delete the file after testing.
	 * @param	bool	$isFileDone		Optional: Pass true when the last chunk (or only chunk) of the file is being sent so destination knows not to expect any other pieces.
	 * @param	int		$fileSize		Optional: Size of the file sending.
	 * @param	string	$filePath		Optional: Remote file path in relation to the root location where the file is being stored, based on file type (based on verb).
	 * @param	bool	$returnRaw		When true returns body raw text/data rather than decoding encoded data first.
	 *
	 */
	public static function remoteCall( $remoteAPI, $verb, $moreParams = array(), $timeout, $file = '', $fileData = '', $seekTo = '', $isFileTest = '', $isFileDone = false, $fileSize = '', $filePath = '', $returnRaw = false ) {
		pb_backupbuddy::status( 'details', 'Preparing remote API call verb `' . $verb . '`.' );
		$now = time();
		
		$body = array(
			'backupbuddy_api_key' => $remoteAPI['key_public'],
			'backupbuddy_version' => pb_backupbuddy::settings( 'version' ),
			'verb' => $verb,
			'now' => $now,
		);
		
		if ( ! is_numeric( $timeout ) ) {
			$timeout = backupbuddy_constants::DEPLOYMENT_REMOTE_API_DEFAULT_TIMEOUT;
		}
		pb_backupbuddy::status( 'details', 'remoteCall() HTTP wait timeout: `' . $timeout . '` seconds.' );
		
		$filecrc = '';
		if ( '' != $file ) {
			pb_backupbuddy::status( 'details', 'Remote API sending file `' . $file . '`.' );
			$fileData = base64_encode( $fileData ); // Sadly we cannot safely transmit binary data over curl without using an actual file. base64 encoding adds 37% size overhead.
			$filecrc = sprintf ( "%u", crc32( $fileData ) );
			$body['filename'] = basename( $file );
			if ( '' != $filePath ) {
				$body['filepath'] = $filePath;
			}
			$body['filedata'] = $fileData;
			$body['filedatalen'] = strlen( $fileData );
			$body['filecrc'] = $filecrc;
			if ( true === $isFileTest ) {
				$body['filetest'] = '1';
			} else {
				$body['filetest'] = '0';
			}
			if ( true === $isFileDone ) {
				$body['filedone'] = '1';
			} else {
				$body['filedone'] = '0';
			}
			$body['seekto'] = $seekTo; // Location to seek to before writing this part.
			if ( '' != $fileSize ) {
				$body['filetotalsize'] = $fileSize;
			}
		}
		if ( ! is_array( $moreParams ) ) {
			error_log( 'BackupBuddy Error #4893783447 remote_api.php; $moreParams must be passed as array.' );
		}
		$body = array_merge( $body, $moreParams );
		
		//print_r( $apiKey );
		$body['signature'] = md5( $now . $verb . $remoteAPI['key_public'] . $remoteAPI['key_secret'] . $filecrc );
		
		if ( defined( 'BACKUPBUDDY_DEV' ) && ( true === BACKUPBUDDY_DEV ) ) {
			error_log( 'BACKUPBUDDY_DEV-remote api http body SEND- ' . print_r( $body, true ) );
		}
		//error_log( 'connectTo: ' . $remoteAPI['siteurl'] );
		$response = wp_remote_post( $remoteAPI['siteurl'], array(
				'method' => 'POST',
				'timeout' => ( $timeout - 2 ),
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array( 'Referer' => $remoteAPI['siteurl'] ), // Sending referer header helps prevent security blocks.
				'body' => $body,
				'cookies' => array()
			)
		);
		
		if ( is_wp_error( $response ) ) {
			return self::_error( 'Error #9037: Unable to connect to remote server or unexpected response. Details: `' . $response->get_error_message() . '` - URL: `' . $remoteAPI['siteurl'] . '`.' );
		} else {
			if ( true === $returnRaw ) {
				return $response['body'];
			}
			//error_log( '3333Response: ' . $response['body'] );
			if ( null === ( $return = json_decode( $response['body'], true ) ) ) {
				return self::_error( 'Error #8001: Unable to decode json response. Verify remote site API URL `' . $remoteAPI['siteurl'] . '`, API key, and that the remote site has the API enabled in its wp-config.php by adding <i>define( \'BACKUPBUDDY_API_ENABLE\', true );</i> somewhere ABOVE the line "That\'s all, stop editing!". Return data: `' . htmlentities( stripslashes_deep( $response['body'] ) ) . '`.' );
			} else {
				if ( ! isset( $return['success'] ) || ( true !== $return['success'] ) ) { // Fail.
					$error = '';
					if ( isset( $return['error'] ) ) {
						$error = $return['error'];
					} else {
						$error = 'Error #838438734: No error given. Full response: "' . $return . '".';
					}
					return self::_error( 'Error #3289379: API did not report success. Error details: `' . $error . '`.' );
				} else { // Success.
					if ( isset( $return['message'] ) ) {
						pb_backupbuddy::status( 'details', 'Response message from API: ' . $return['message'] . '".' );
					}
					return $return;
				}
			}
		}
	} // End remoteCall().
	
	
	
	/* _verb_runBackup()
	 *
	 * Run a backup with a specified custom profile; eg a db backup for pulling deployment.
	 * Params: POST "profile" - Base64 encoded json encoded profile array.
	 *
	 */
	private static function _verb_runBackup() {
		$backupSerial = pb_backupbuddy::random_string( 10 );
		$profileArray = pb_backupbuddy::_POST( 'profile' );
		if ( false === ( $profileArray = base64_decode( $profileArray ) ) ) {
			$message = 'Error #8343728: Unable to base64 decode profile data.';
			pb_backupbuddy::status( 'error', $message, $backupSerial );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		if ( NULL === ( $profileArray = json_decode( $profileArray, true ) ) ) {
			$message = 'Error #3272383: Unable to json decode profile data.';
			pb_backupbuddy::status( 'error', $message, $backupSerial );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Appends session tokens from the pulling site so they wont get logged out when this database is restored there.
		if ( isset( $profileArray['sessionTokens'] ) && ( is_array( $profileArray['sessionTokens'] ) ) ) {
			pb_backupbuddy::status( 'details', 'Remote session tokens need updated.', $backupSerial );
			//error_log( 'needtoken' );
			
			if ( ! is_numeric( $profileArray['sessionID'] ) ) {
				$message = 'Error #328989893. Invalid session ID. Must be numeric.';
				pb_backupbuddy::status( 'error', $message );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
			
			// Get current session tokens.
			global $wpdb;
			$sql = "SELECT meta_value FROM `" . DB_NAME . "`.`" . $wpdb->prefix . "usermeta` WHERE `user_id` = '" . $profileArray['sessionID'] . "' AND `meta_key` = 'session_tokens';";
			$results = $wpdb->get_var( $sql );
			$oldSessionTokens = @unserialize( $results );
			
			// Add remote tokens.
			if ( ! is_array( $oldSessionTokens ) ) {
				$oldSessionTokens = array();
			}
			$newSessionTokens = array_merge( $oldSessionTokens, $profileArray['sessionTokens'] );
			
			// Re-serialize.
			$newSessionTokens = serialize( $newSessionTokens );
			
			// Save merged tokens here.
			$sql = "UPDATE `" . DB_NAME . "`.`" . $wpdb->prefix . "usermeta` SET meta_value= %s WHERE `user_id` = '" . $profileArray['sessionID'] . "' AND `meta_key` = 'session_tokens';";
			$stringedSessionTokens = serialize( $profileArray['sessionTokens'] );
			
			if ( false === $wpdb->query( $wpdb->prepare( $sql, $stringedSessionTokens ) ) ) {
				$message = 'Error #43734784: Unable to update remote session token.';
				pb_backupbuddy::status( 'error', $message, $backupSerial );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
			
			pb_backupbuddy::status( 'details', 'Updated remote session tokens.', $backupSerial );
		}
		
		if ( true !== ( $maybeMessage = backupbuddy_api::runBackup( $profileArray, $triggerTitle = 'deployment_pulling', $backupMode = '', $backupSerial ) ) ) {
			$message = 'Error #48394873: Unable to launch backup at source. Details: `' . $maybeMessage . '`.';
			pb_backupbuddy::status( 'error', $message, $backupSerial );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		} else {
			$archiveFilename = basename( backupbuddy_core::calculateArchiveFilename( $backupSerial, $profileArray['type'], $profileArray ) );
			die( json_encode( array( 'success' => true, 'backupSerial' => $backupSerial, 'backupFile' => $archiveFilename ) ) );
		}
	} // End _verb_runBackup().
	
	
	
	private static function _verb_getBackupStatus() {
		$backupSerial = pb_backupbuddy::_POST( 'serial' );
		pb_backupbuddy::status( 'details', '*** End Remote Backup Log section', $backupSerial ); // Place at end of log.
		backupbuddy_api::getBackupStatus( $backupSerial ); // echos out. Use $returnRaw = true for remote_api call for this special verb that does not return json.
		
		// Fix missing WP cron constant.
		if ( !defined( 'WP_CRON_LOCK_TIMEOUT' ) ) {
			define('WP_CRON_LOCK_TIMEOUT', 60);  // In seconds
		}
		
		// Try to force cron to run so that we can push the backup along.
		spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	} // end _verb_getBackupStatus().
	
	
	
	/* _verb_confirmDeployment()
	 *
	 * User confirmed the deployment so cleanup any remaining temporary stuff such as temp db tables. Note: importbuddy, backup files, etc should have already been cleaned up by importbuddy itself at this point.
	 *
	 */
	private static function _verb_confirmDeployment() {
		
		$serial = pb_backupbuddy::_POST( 'serial' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
		backupbuddy_housekeeping::remove_temp_tables( $serial );
		
		die( json_encode( array( 'success' => true ) ) );
		
	} // End _verb_confirmDeployment().
	
	
	// Receive backup archive.
	private static function _verb_sendFile_backup() {
		self::_sendFile( 'backup' );
	} // End _verb_sendFile_backup().
	
	
	// Receive theme file.
	private static function _verb_sendFile_theme() {
		self::_sendFile( 'theme' );
	} // End _verb_sendFile_theme().
	
	// Receive child theme file.
	private static function _verb_sendFile_childTheme() {
		self::_sendFile( 'childTheme' );
	} // End _verb_sendFile_childtheme().
	
	// Receive plugin file.
	private static function _verb_sendFile_plugin() {
		self::_sendFile( 'plugin' );
	} // End _verb_sendFile_plugin().
	
	// Receive backup archive.
	private static function _verb_sendFile_media() {
		self::_sendFile( 'media' );
	} // End _verb_sendFile_media().
	
	// Testing file send ability. File is transient; stored in temp dir momentarily.
	private static function _verb_sendFile_test() {
		self::_sendFile( 'test' );
	} // End _verb_sendFile_test().
	
	
	
	// Get backup archive.
	private static function _verb_getFile_backup() {
		self::_getFile( 'backup' );
	} // End _verb_getFile_backup().
	
	// Get theme file.
	private static function _verb_getFile_theme() {
		self::_getFile( 'theme' );
	} // End _verb_getFile_theme().
	
	// Get child theme file.
	private static function _verb_getFile_childTheme() {
		self::_getFile( 'childTheme' );
	} // End _verb_getFile_childTeme().
	
	// Get plugin file.
	private static function _verb_getFile_plugin() {
		self::_getFile( 'plugin' );
	} // End _verb_getFile_plugin().
	
	// Get backup archive.
	private static function _verb_getFile_media() {
		self::_getFile( 'media' );
	} // End _verb_getFile_media().
	
	
	
	/* _getFilePathByType()
	 *
	 * Calculates root directory to store the specified type in. Contains trailing slash. Dies if unknown file type specified in params.
	 *
	 * @param	string		$type		File type/location name to store in. Valid values: backup, media, plugin, theme.
	 *
	 */
	private static function _getFilePathByType( $type ) {
		if ( 'backup' == $type ) {
			$rootDir = backupbuddy_core::getBackupDirectory(); // Include trailing slash.
			pb_backupbuddy::anti_directory_browsing( $rootDir, $die = false );
		} elseif ( 'media' == $type ) {
			$wp_upload_dir = wp_upload_dir();
			$rootDir = $wp_upload_dir['basedir'] . '/';
			unset( $wp_upload_dir );
		} elseif ( 'plugin' == $type ) {
			$rootDir = wp_normalize_path( WP_PLUGIN_DIR ) . '/';
		} elseif ( 'theme' == $type ) {
			$rootDir = get_template_directory() . '/';
		} elseif ( 'childTheme' == $type ) {
			$rootDir = get_stylesheet_directory() . '/';
		} elseif( 'test' == $type ) {
			$rootDir = backupbuddy_core::getTempDirectory();
		} else {
			$error = 'Error #84934984. You must specify a sendfile type: Unknown file type `' . htmlentities( $type ) . '`.';
			pb_backupbuddy::status( 'error', $error );
			error_log( 'BackupBuddy API error: ' . $error );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		return $rootDir;
	} // End _getFilePathByType().
	
	
	/* _getFile()
	 *
	 * Calling site is wanting to get a file FROM this site.
	 *
	 */
	private static function _getFile( $type ) {
		$rootDir = self::_getFilePathByType( $type ); // contains trailing slash.
		$filePath = stripslashes_deep( pb_backupbuddy::_POST( 'filename' ) );
		$fullFilename = $rootDir . $filePath;
		
		$seekTo = pb_backupbuddy::_POST( 'seekto' );
		if ( ! is_numeric( $seekTo ) ) {
			$seekTo = 0;
		}
		
		$maxPayload = pb_backupbuddy::_POST( 'maxPayload' ); // Max payload in bytes.
		$maxPayloadBytes = $maxPayload * 1024 * 1024;
		$encodeReducedPayload = floor( ( pb_backupbuddy::_POST( 'maxPayload' ) - ( pb_backupbuddy::_POST( 'maxPayload' ) * 0.37 ) ) * 1024 * 1024 ); // Take into account 37% base64 encoding overhead. Convert to bytes. Remove any decimals down via floor.
		
		// File exist? (note: if utf8 then this first check will fail and inside we will check for the file after utf8 decoding.)
		if ( ! file_exists( $fullFilename ) ) {
			// Check if utf8 decoding the filename helps us find it.
			$utf_decoded_filename = utf8_decode( $filePath );
			if ( file_exists( $rootDir . $utf_decoded_filename ) ) {
				$fullFilename = $rootDir . $utf_decoded_filename;
			} else {
				$message = 'Error #83929838: Requested `' . $type . '` file with full path `' . $fullFilename . '` does not exist. Was it just deleted? See log for details.';
				pb_backupbuddy::status( 'error', $message );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
		}
		
		$size = filesize( $fullFilename );
		$encodedSize = ( $size * 0.37 ) + $size;
		pb_backupbuddy::status( 'details', 'File size of file to get: ' . pb_backupbuddy::$format->file_size( $size ) . '. After encoding overhead: ' . pb_backupbuddy::$format->file_size( $encodedSize ) );
		
		if ( $encodedSize > $maxPayloadBytes ) {
			$chunksTotal = ceil( $encodedSize / $maxPayloadBytes );
			pb_backupbuddy::status( 'details', 'This file + encoding exceeds the maximum per-chunk payload size so will be read in and sent in chunks of ' . pb_backupbuddy::_POST( 'maxPayload' ) . 'MB (' . $maxPayloadBytes . ' bytes) totaling approximately ' . $chunksTotal . ' chunks.' );
		} else {
			pb_backupbuddy::status( 'details', 'This file + encoding does not exceed per-chunk payload size of ' . pb_backupbuddy::_POST( 'maxPayload' ) . 'MB (' . $maxPayloadBytes . ' bytes) so sending in one pass.' );
		}
		$prevPointer = 0;
		
		pb_backupbuddy::status( 'details', 'Reading in `' . $encodeReducedPayload . '` bytes at a time.' );
		
		// Open for reading.
		//error_log( 'fopening the file: ' . $fullFilename . ' to seek to `' . $seekTo . '` with reduced payload `' . $encodeReducedPayload . '`.' );
		if ( false === ( $fs = fopen( $fullFilename, 'rb' ) )) {
			$message = 'Error #235532: Unable to fopen file `' . $fullFilename . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Seek to position (if applicable).
		if ( 0 != $seekTo ) {
			if ( 0 != fseek( $fs, $seekTo ) ) {
				@fclose( $fs );
				$message = 'Error #6464534229: Unable to fseek file.';
				pb_backupbuddy::status( 'error', $message );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
		}
		
		$resumePoint = 0;
		$fileDone = '0';
		$fileData = fread( $fs, $encodeReducedPayload );
		if ( feof( $fs ) ) {
			pb_backupbuddy::status( 'details', 'Read to end of file (feof true). No more chunks left after this.' );
			$fileDone = '1';
		} else {
			if ( FALSE === ( $resumePoint = ftell( $fs ) ) ) {
				pb_backupbuddy::status( 'error', 'Error #42353212: Unable to get ftell pointer of file handle.' );
				@fclose( $fs );
				return false;
			} else {
				pb_backupbuddy::status( 'details', 'File pointer resume point: `' . $resumePoint . '`.' );
			}
		}
		@fclose( $fs );
		
		$fileData = base64_encode( $fileData ); // Sadly we cannot safely transmit binary data over curl without using an actual file. base64 encoding adds 37% size overhead.
		$filecrc = sprintf ( "%u", crc32( $fileData ) );
		
		die( json_encode( array(
			'success'		=> true,
			'filedata'		=> $fileData,
			'filedatalen'	=> strlen( $fileData ),
			'filecrc'		=> $filecrc,
			'filedone'		=> $fileDone,
			'filesize'		=> $size,
			'resumepoint'	=> $resumePoint,
			'encoded'       => isset( $utf_decoded_filename ),		// only isset if utf8 was needed to find this file.
		) ) );
		
	} // End _getFile().
	
	
	
	/* _sendFile()
	 *
	 * Calling site is wanting to send a file TO this site. Called by various verbs that pass the appropriate $type that determines root path. Valid types: backup, theme, plugin, media
	 *
	 */
	private static function _sendFile( $type = '' ) {
		$rootDir = self::_getFilePathByType( $type ); // contains trailing slash.
		
		//error_log( 'API saving file to dir: `' . $rootDir . '`.' );
		
		$cleanFile = str_replace( array( '\\', '/' ), '', stripslashes_deep( pb_backupbuddy::_POST( 'filename' ) ) );
		$filePath = pb_backupbuddy::_POST( 'filepath' );
		if ( '' != $filePath ) { // Filepath specified so goes in a subdirectory under the rootDir.
			if ( $cleanFile != basename( $filePath ) ) {
				// Check if utf8 decoding the filename helps match correctly
				$utf_decoded_filePath = utf8_decode( $filePath );
				if ( $cleanFile == basename( $utf_decoded_filePath ) ) {
					$filePath = $subFilePath = $utf_decoded_filePath;
				} else {
					$message = 'Error #493844: The specified filename within the filepath parameter does not match the supplied filename parameter. | cleanfile: ' . $cleanFile . ' | filePath: | ' . $filePath;
					pb_backupbuddy::status( 'error', $message );
					die( json_encode( array( 'success' => false, 'error' => $message ) ) );
				}
			} else { // Filename with path.
				$subFilePath = $filePath;
			}
		} else { // Just the filename. No path.
			$subFilePath = $cleanFile;
		}
		$saveFile = $rootDir . $subFilePath;
		
		// Calculate seek position.
		$seekTo = pb_backupbuddy::_POST( 'seekto' );
		if ( ! is_numeric( $seekTo ) ) {
			$seekTo = 0;
		}
		
		// Check if directory exists & create if needed.
		$saveDir = dirname( $saveFile );
		
		
		// Delete existing directory for some types of transfers.
		
		if ( ( 0 == $seekTo ) && ( file_exists( $saveFile ) ) ) { // New file transfer only. Do not delete existing file if chunking.
			//error_log( 'zeroseekmoose' . $saveFile );
			if ( true !== @unlink( $saveFile ) ) {
				$message = 'Error #238722: Unable to delete existing file `' . $saveFile . '`.';
				pb_backupbuddy::status( 'error', $message );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
		}
		
		if ( ! is_dir( $saveDir ) ) {
			if ( true !== pb_backupbuddy::$filesystem->mkdir( $saveDir ) ) {
				$message = 'Error #327832: Unable to create directory `' . $saveDir . '`. Check permissions or manually create. Halting to preserve deployment integrity';
				pb_backupbuddy::status( 'error', $message );
				die( json_encode( array( 'success' => false, 'error' => $message ) ) );
			}
		}
		
		// Open/create file for write/append.
		if ( false === ( $fs = fopen( $saveFile, 'a' ) )) {
			$message = 'Error #489339848: Unable to fopen file `' . $saveFile . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Seek to position (if applicable).
		if ( 0 != fseek( $fs, $seekTo ) ) {
			@fclose( $fs );
			$message = 'Error #8584884: Unable to fseek file.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Check data length.
		$gotLength = strlen( pb_backupbuddy::_POST( 'filedata' ) );
		if ( pb_backupbuddy::_POST( 'filedatalen' ) != $gotLength ) {
			@fclose( $fs );
			$message = 'Error #4355445: Received data of length `' . $gotLength . '` did not match sent length of `' . pb_backupbuddy::_POST( 'filedatalen' ) . '`. Data may have been truncated.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Check hash.
		if ( pb_backupbuddy::_POST( 'filecrc' ) != sprintf ( "%u", crc32( pb_backupbuddy::_POST( 'filedata' ) ) ) ) {
			@fclose( $fs );
			$message = 'Error #473472: CRC of received data did not match source CRC. Data corrupted in transfer? Sent length: `' . pb_backupbuddy::_POST( 'filedatalen' ) . '`. Received length: `' . $gotLength . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		}
		
		// Write to file.
		if ( false === ( $bytesWritten = fwrite( $fs, base64_decode( pb_backupbuddy::_POST( 'filedata' ) ) ) ) ) {
			@fclose( $fs );
			@unlink( $saveFile );
			$message = 'Error #3984394: Error writing to file `' . $saveFile . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( json_encode( array( 'success' => false, 'error' => $message ) ) );
		} else {
			@fclose( $fs );
			
			$message = 'Wrote `' . $bytesWritten . '` bytes to `' . $saveFile . '`.';
			pb_backupbuddy::status( 'details', $message );
			
			if ( ( '1' == pb_backupbuddy::_POST( 'filetest' ) ) || ( 'test' == $type ) ) {
				@unlink( $saveFile );
			} else {
				if ( '1' == pb_backupbuddy::_POST( 'filedone' ) ) {
					$destFile = ABSPATH . basename( $saveFile );
					/*
					if ( false === @copy( $saveFile, $destFile ) ) {
						pb_backupbuddy::status( 'error', 'Error #948454: Unable to copy temporary file `' . $saveFile . '` to `' . $destFile . '`.' );
					}
					@unlink( $saveFile );
					*/
					
					// Media files need their thumbnails regenerated so get attachment ID.
					/* CANNOT DO THIS HERE ... because item may not be in the DB yet. need to transfer thumbnails?
					if ( 'media' == $type ) {
						global $wpdb;
						$sql = "SELECT post_id FROM `" . DB_NAME . "`.`" . $wpdb->prefix . "postmeta` WHERE `meta_value` = %s AND `meta_key` = '_wp_attached_file'";
						$sql = $wpdb->prepare( $sql, $filePath );
						error_log( $sql );
						$attachment_id = $wpdb->get_var( $sql );
						error_log( 'ID: ' . $attachment_id );
						error_log( 'savefile: ' . $saveFile );
						require ( ABSPATH . 'wp-admin/includes/image.php' );
						$attach_data = wp_generate_attachment_metadata( $attachment_id, $saveFile );
						wp_update_attachment_metadata( $attachment_id,  $attach_data );
					}
					*/
					
					die( json_encode( array( 'success' => true, 'message' => $message ) ) );
				}
			}
			
			die( json_encode( array( 'success' => true, 'message' => $message ) ) );
		}
		
	} // End _sendFile().
	
	
	
	private static function _verb_getPreDeployInfo() {
		die( json_encode( array( 'success' => true, 'data' => backupbuddy_api::getPreDeployInfo() ) ) );
	} // End _verb_getPreDeployInfo().
	
	
	private static function _verb_renderImportBuddy() {
		
		$backupFile = pb_backupbuddy::_POST( 'backupFile' );
		$password = md5( md5( pb_backupbuddy::_POST( 'backupbuddy_api_key' ) ) );
		$max_execution_time = pb_backupbuddy::_POST( 'max_execution_time' );
		
		// Store this serial in settings to cleanup any temp db tables in the future with this serial with periodic cleanup.
		$backupSerial = backupbuddy_core::get_serial_from_file( $backupFile );
		pb_backupbuddy::$options['rollback_cleanups'][ $backupSerial ] = time();
		pb_backupbuddy::save();
		
		$additionalStateInfo = array();
		if ( is_numeric( $max_execution_time ) ) {
			$additionalStateInfo['maxExecutionTime'] = $max_execution_time;
		}
		
		$importFileSerial = backupbuddy_core::deploymentImportBuddy( $password, backupbuddy_core::getBackupDirectory() . $backupFile, $additionalStateInfo );
		if ( is_array( $importFileSerial ) ) {
			die( json_encode( array( 'success' => false, 'error' => $importFileSerial[1] ) ) );
		} else {
			die( json_encode( array( 'success' => true, 'importFileSerial' => $importFileSerial ) ) );
		}
		
	} // End _verb_renderImportBuddy().
	
	
	public static function is_call_valid() {
		$key_public = pb_backupbuddy::_POST('backupbuddy_api_key');
		$verb = pb_backupbuddy::_POST('verb');
		$time = pb_backupbuddy::_POST('now');
		$filecrc = pb_backupbuddy::_POST('filecrc');
		$signature = pb_backupbuddy::_POST('signature');
		
		$maxAge = 60*60; // Time in seconds after which a signed request is deemed too old. Help prevent replays. 1hr.
		foreach( pb_backupbuddy::$options['remote_api']['keys'] as $key ) {
			$keyArr = self::key_to_array( $key );
			if ( $key_public == $keyArr['key_public'] ) { // Incoming public key matches a stored public key.
				// Has call expired?
				if ( ( ! is_numeric( $time ) ) || ( ( time() - $time ) > $maxAge ) ) {
					$message = 'Error #4845985: API call timestamp is too old. Verify the realtime clock on each server is relatively in sync.';
					pb_backupbuddy::status( 'error', $message );
					die( json_encode( array( 'success' => false, 'error' => $message ) ) );
				}
				// Verify signature.
				$calculatedSignature = md5( $time . $verb . $key_public . $keyArr['key_secret'] . $filecrc );
				if ( $calculatedSignature != $signature ) { // Key matched but signature failed. Data has been tempered with or damaged in transit.
					return false;
				} else {
					return true;
				}
			}
		}
		return false;
	}
	
	public static function key_to_array( $key ) {
		$key = trim( $key );
		$key = base64_decode( $key );
		$key = json_decode( $key, true );
		return $key;
	}
	
	
	public static function validate_api_key( $key ) {
		if ( ! defined( 'BACKUPBUDDY_API_ENABLE' ) || ( TRUE != BACKUPBUDDY_API_ENABLE ) ) {
			return false;
		}
		/*
		if ( ! defined( 'BACKUPBUDDY_API_SALT' ) || ( 'CHANGEME' == BACKUPBUDDY_API_SALT ) || ( strlen( BACKUPBUDDY_API_SALT ) < 5 ) ) {
			return false;
		}
		*/
		if ( '' == pb_backupbuddy::$options['api_key'] ) {
			return false;
		}
		
		
		$key = self::key_to_array( $key );
		if ( $key == pb_backupbuddy::$options['api_key'] ) {
			return true;
		} else {
			return false;
		}
		
	} // End validate_api_key().
	
	
	public static function generate_key() {
		if ( ! defined( 'BACKUPBUDDY_API_ENABLE' ) || ( TRUE != BACKUPBUDDY_API_ENABLE ) ) {
			return false;
		}
		/*
		if ( ! defined( 'BACKUPBUDDY_API_SALT' ) || ( 'CHANGEME' == BACKUPBUDDY_API_SALT ) || ( strlen( BACKUPBUDDY_API_SALT ) < 5 ) ) {
			return false;
		}
		*/
		
		$siteurl = site_url();
		$homeurl = home_url();
		$rand = pb_backupbuddy::random_string( 12 );
		$rand2 = pb_backupbuddy::random_string( 12 );
		
		$key = array(
			'key_version' => 1,
			'key_public' => md5( $rand . pb_backupbuddy::$options['log_serial'] . $siteurl . $homeurl . time() ),
			'key_secret' => md5( $rand2 . pb_backupbuddy::$options['log_serial'] . $siteurl . $homeurl . time() ),
			'key_created' => time(),
			'siteurl' => $siteurl,
			'homeurl' => $homeurl,
		);
		
		
		return base64_encode( json_encode( $key ) );
		
	} // End generate_api_key().
	
	
	/* _error()
	 *
	 * Logs error messages for retrieval with getErrors().
	 *
	 * @param	string		$message	Error message to log.
	 * @return	null
	 */
	private static function _error( $message ) {
		//error_log( $message );
		self::$_errors[] = $message;
		pb_backupbuddy::status( 'error', $message );
		return false;
	}
	
	
	
	/* getErrors()
	 *
	 * Get any errors which may have occurred.
	 *
	 * @return	array 		Returns an array of string error messages.
	 */
	public static function getErrors() {
		return self::$_errors;
	} // End getErrors();
	
	
	
} // End class.
