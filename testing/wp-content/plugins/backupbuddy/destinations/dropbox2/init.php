<?php
use \Dropbox as dbx;

class pb_backupbuddy_destination_dropbox2 { // Ends with destination slug.
	
	public static $destination_info = array(
		'name'			=>		'Dropbox v2 <small>(new)</small>',
		'description'	=>		'Dropbox.com support for servers running PHP v5.3 or newer. Supports multipart chunked uploads for larger file support, improved memory handling, and reliability.',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		// Default settings.
		'type'						=>		'dropbox2',				// Required destination slug.
		'title'						=>		'',						// Required destination field.
		'access_token'				=>		'',						// oAuth2 access token.
		'directory'					=>		'backupbuddy',			// Remote Dropbox directory to store into.
		'archive_limit'				=>		0,						// Max number of archives allowed in destination directory.
		'max_chunk_size'			=>		'80',					// Maximum chunk size in MB. Anything larger will be chunked up into pieces this size (or less for last piece). This allows larger files to be sent than would otherwise be possible. Minimum of 5mb allowed by S3.
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		// Instance variables for transfer-specific settings such as multipart/chunking.
		'_chunk_upload_id'			=>		'',						// Instance var. Internal use only for continuing a chunked upload.
		'_chunk_offset'				=>		'',						// Instance var. Internal use only for continuing a chunked upload.
		'_chunk_maxsize'			=>		'',						// Instance var. Internal use only for continuing a chunked upload.
		'_chunk_next_offset'		=>		0,						// Instance var. Internal use only for continuing a chunked upload. - Next chunk byte offset to seek to for sending.
		'_chunk_sent_count'			=>		0,						// Instance var. Internal use only for continuing a chunked upload. - Number of chunks sent.
		'_chunk_total_count'		=>		0,						// Instance var. Internal use only for continuing a chunked upload. - Total number of chunks that will be sent..
		'_chunk_transfer_speeds'	=>		array(),				// Instance var. Internal use only for continuing a chunked upload. - Array of time spent actually transferring. Used for calculating send speeds and such.
		'disabled'					=>		'0',		// When 1, disable this destination.
	);
	
	public static $appInfo;
	private static $_dbxClient;
	
	
	public static function init() {
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/dropbox2/lib/Dropbox/autoload.php' );
		self::$appInfo = dbx\AppInfo::loadFromJsonFile( pb_backupbuddy::plugin_path() . '/destinations/dropbox2/_config.json' );
		
	} // End init().
	
	
	/* _connect()
	 *
	 * Connect to dropbox with provided token. Sets Dropbox client object into: self::$_dbxClient
	 *
	 * @param	string		$accessToken		Dropbox oAuth2 access token.
	 * @return	bool							true on success, else false.
	 *
	 */
	private static function _connect( $accessToken ) {
		
		try {
			self::$_dbxClient = new dbx\Client( $accessToken, 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) );
		} catch ( \Exception $e ) {
			pb_backupbuddy::alert( 'Dropbox Error: ' . $e->getMessage() . '<br><br>' . pb_backupbuddy::$ui->button( pb_backupbuddy::page_url(), '&larr; go back & retry' ), true );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Connected to Dropbox.' );
		return true;
		
	} // End _connect().
	
	
	
	/* _normalizeSettings()
	 *
	 * Normalize any settings. NOTE: defaults are ALREADY set by anything calling these functions.
	 *
	 * @param	array 	$settings			Array of destination settings to clean up.
	 * @return	array 						Normalized settings array.
	 *
	 */
	private static function _normalizeSettings( $settings ) {
		
		$settings['directory'] = '/' . trim( $settings['directory'], '/\\' );
		return $settings;
		
	} // End _normalizeSettings().
	
	
	
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$file		Array of one or more files to send.
	 *	@return		boolean						True on success single-process, array on multipart with remaining steps, else false (failed).
	 */
	public static function send( $settings = array(), $file, $send_id = '', $delete_after = false ) {
		global $pb_backupbuddy_destination_errors;
		if ( '1' == $settings['disabled'] ) {
			$pb_backupbuddy_destination_errors[] = __( 'Error #48933: This destination is currently disabled. Enable it under this destination\'s Advanced Settings.', 'it-l10n-backupbuddy' );
			return false;
		}
		if ( is_array( $file ) ) {
			$file = $file[0];
		}
		
		pb_backupbuddy::status( 'details', 'Dropbox2 send function started. Remote send id: `' . $send_id . '`.' );
		
		
		// Normalize settings, apply defaults, etc.
		$settings = self::_normalizeSettings( $settings );
		
		// Connect to Dropbox.
		if ( false === self::_connect( $settings['access_token'] ) ) { // Try to connect. Return false if fail.
			return false;
		}
		
		$max_chunk_size_bytes = ( $settings['max_chunk_size'] * 1024 * 1024 );
		
		
		/***** BEGIN MULTIPART CHUNKED CONTINUE *****/
		
		
		// Continue Multipart Chunked Upload
		if ( $settings['_chunk_upload_id'] != '' ) {
			
			$file = $settings['_chunk_file'];
			pb_backupbuddy::status( 'details', 'Dropbox (PHP 5.3+) preparing to send chunked multipart upload part ' . ($settings['_chunk_sent_count']+1) . ' of ' . $settings['_chunk_total_count'] . ' with set chunk size of `' . $settings['max_chunk_size'] . '` MB. Dropbox Upload ID: `' . $settings['_chunk_upload_id'] . '`.' );
			
			pb_backupbuddy::status( 'details', 'Opening file `' . basename( $file ) . '` to send.' );
			$f = @fopen( $file, 'rb' );
			if ( false === $f ) {
				pb_backupbuddy::status( 'error', 'Error #87954435. Unable to open file `' . $file . '` to send to Dropbox.' );
				return false;
			}
			
			// Seek to next chunk location.
			pb_backupbuddy::status( 'details', 'Seeking file to byte `' . $settings['_chunk_next_offset'] . '`.' );
			if ( 0 != fseek( $f, $settings['_chunk_next_offset'] ) ) { // return of 0 is success.
				pb_backupbuddy::status( 'error', 'Unable to seek file to proper location offset `' . $settings['_chunk_next_offset'] . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'Seek success.' );
			}
			
			// Read this file chunk into memory.
			pb_backupbuddy::status( 'details', 'Reading chunk into memory.' );
			if ( false === ( $data = fread( $f, $settings['_chunk_maxsize'] ) ) ) {
				pb_backupbuddy::status( 'error', 'Dropbox Error #484938376: Unable to read in chunk.' );
				return false;
			}
			
			pb_backupbuddy::status( 'details', 'About to put chunk to Dropbox for continuation.' );
			$send_time = -(microtime( true ));
			try {
				$result = self::$_dbxClient->chunkedUploadContinue( $settings['_chunk_upload_id'], $settings['_chunk_next_offset'], $data );
			} catch ( \Exception $e ) {
				pb_backupbuddy::status( 'error', 'Dropbox Error #8754646: ' . $e->getMessage() );
				return false;
			}
			
			// Examine response from Dropbox.
			if ( true === $result ) { // Upload success.
				pb_backupbuddy::status( 'details', 'Chunk upload continuation success with valid offset.' );
			} elseif ( false === $result ) { // Failed.
				pb_backupbuddy::status( 'error', 'Chunk upload continuation failed at offset `' . $settings['_chunk_next_offset'] . '`.' );
				return false;
			} elseif ( is_numeric( $result ) ) { // offset wrong. Update to use this.
				pb_backupbuddy::status( 'details', 'Chunk upload continuation received an updated offset response of `' . $result . '` when we tried `' . $settings['_chunk_next_offset'] . '`.' );
				$settings['_chunk_next_offset'] = $result;
				// Try resending with corrected offset.
				try {
					$result = self::$_dbxClient->chunkedUploadContinue( $settings['_chunk_upload_id'], $settings['_chunk_next_offset'], $data );
				} catch ( \Exception $e ) {
					pb_backupbuddy::status( 'error', 'Dropbox Error #8263836: ' . $e->getMessage() );
					return false;
				}
				
			}
			
			$send_time += microtime( true );
			$data_length = strlen( $data );
			unset( $data );
			
			// Calculate some stats to log.
			$chunk_transfer_speed = $data_length / $send_time;
			pb_backupbuddy::status( 'details', 'Dropbox chunk transfer stats - Sent: `' . pb_backupbuddy::$format->file_size( $data_length ) . '`, Transfer duration: `' . $send_time . '`, Speed: `' . pb_backupbuddy::$format->file_size( $chunk_transfer_speed ) . '`.' );
			
			// Set options for subsequent step chunks.
			$chunked_destination_settings = $settings;
			$chunked_destination_settings['_chunk_offset'] = $data_length;
			$chunked_destination_settings['_chunk_sent_count']++;
			$chunked_destination_settings['_chunk_next_offset'] = ( $data_length * $chunked_destination_settings['_chunk_sent_count'] ); // First chunk was sent initiationg multipart send.
			$chunked_destination_settings['_chunk_transfer_speeds'][] = $chunk_transfer_speed;
			
			// Load destination fileoptions.
			pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
			require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #15.' );
			$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
			if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
				pb_backupbuddy::status( 'error', __('Fatal Error #9034.84838. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
				return false;
			}
			pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
			$fileoptions = &$fileoptions_obj->options;
			
			// Multipart send completed. Send finished signal to Dropbox to seal the deal.
			if ( true === feof( $f ) ) {
				
				pb_backupbuddy::status( 'details', 'At end of file. Finishing transfer and notifying Dropbox of file transfer completion.' );
				
				$chunked_destination_settings['_chunk_upload_id'] = ''; // Unset since chunking finished.
				try {
					$result = self::$_dbxClient->chunkedUploadFinish( $settings['_chunk_upload_id'], $settings['directory'] . '/' . basename( $file ), dbx\WriteMode::add() );
				} catch ( \Exception $e ) {
					pb_backupbuddy::status( 'error', 'Dropbox Error #549838979: ' . $e->getMessage() );
					return false;
				}
				pb_backupbuddy::status( 'details', 'Chunked upload finish results: `' . print_r( $result, true ) . '`.' );
				$localSize = filesize( $settings['_chunk_file'] );
				if ( $localSize != $result['bytes'] ) {
					pb_backupbuddy::status( 'error', 'Error #8958944. Dropbox reported file size differs from local size. The file upload may have been corrupted. Local size: `' . $localSize . '`. Remote size: `' . $result['bytes'] . '`.' );
					return false;
				}
				
				$fileoptions['write_speed'] = array_sum( $chunked_destination_settings['_chunk_transfer_speeds'] ) / $chunked_destination_settings['_chunk_sent_count'];
				$fileoptions['_multipart_status'] = 'Sent part ' . $chunked_destination_settings['_chunk_sent_count'] . ' of ' . $chunked_destination_settings['_chunk_total_count'] . '.';
				$fileoptions['finish_time'] = microtime(true);
				$fileoptions['status'] = 'success';
				$fileoptions_obj->save();
				unset( $fileoptions_obj );
			}
			fclose( $f );
			
			
			pb_backupbuddy::status( 'details', 'Sent chunk number `' . $chunked_destination_settings['_chunk_sent_count'] . '` to Dropbox with upload ID: `' . $chunked_destination_settings['_chunk_upload_id'] . '`. Next offset: `' . $chunked_destination_settings['_chunk_next_offset'] . '`.' );
			
			
			// Schedule to continue if anything is left to upload for this multipart of any individual files.
			if ( $chunked_destination_settings['_chunk_upload_id'] != '' ) {
				pb_backupbuddy::status( 'details', 'Dropbox multipart upload has more parts left. Scheduling next part send.' );
				
				$cronTime = time();
				$cronArgs = array( $chunked_destination_settings, $file, $send_id, $delete_after );
				$cronHashID = md5( $cronTime . serialize( $cronArgs ) );
				$cronArgs[] = $cronHashID;
				
				$schedule_result = backupbuddy_core::schedule_single_event( $cronTime, 'destination_send', $cronArgs );
				if ( true === $schedule_result ) {
					pb_backupbuddy::status( 'details', 'Next Dropbox chunk step cron event scheduled.' );
				} else {
					pb_backupbuddy::status( 'error', 'Next Dropbox chunk step cron even FAILED to be scheduled.' );
				}
				spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
				update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
				
				return array( $chunked_destination_settings['_chunk_upload_id'], 'Sent ' . $chunked_destination_settings['_chunk_sent_count'] . ' of ' . $chunked_destination_settings['_chunk_total_count'] . ' parts.' );
			}
			
		} else { // Not continuing chunk send.
			
			
			/***** END MULTIPART CHUNKED CONTINUE *****/
			
			
			$file_size = filesize( $file );
			
			pb_backupbuddy::status( 'details', 'Opening file `' . basename( $file ) . '` to send.' );
			$f = @fopen( $file, 'rb' );
			if ( false === $f ) {
				pb_backupbuddy::status( 'error', 'Error #8457573. Unable to open file `' . $file . '` to send to Dropbox.' );
				return false;
			}
			
			if ( ( $settings['max_chunk_size'] >= 5 ) && ( ( $file_size / 1024 / 1024 ) > $settings['max_chunk_size'] ) ) { // chunked send.
				
				pb_backupbuddy::status( 'details', 'File exceeds chunking limit of `' . $settings['max_chunk_size'] . '` MB. Using chunked upload for this file transfer.' );
				
				// Read first file chunk into memory.
				pb_backupbuddy::status( 'details', 'Reading first chunk into memory.' );
				if ( false === ( $data = fread( $f, $max_chunk_size_bytes ) ) ) {
					pb_backupbuddy::status( 'error', 'Dropbox Error #328663: Unable to read in chunk.' );
					return false;
				}
				
				// Start chunk upload to get upload ID. Sends first chunk piece.
				$send_time = -(microtime( true ));
				pb_backupbuddy::status( 'details',  'About to start chunked upload & put first chunk of file `' . basename( $file ) . '` to Dropbox (PHP 5.3+).' );
				
				try {
					$result = self::$_dbxClient->chunkedUploadStart( $data );
				} catch ( \Exception $e ) {
					pb_backupbuddy::status( 'error', 'Dropbox Error: ' . $e->getMessage() );
					return false;
				}
				$send_time += microtime( true );
				@fclose( $f );
				$data_length = strlen( $data );
				unset( $data );
				
				// Calculate some stats to log.
				$chunk_transfer_speed = $data_length / $send_time;
				pb_backupbuddy::status( 'details', 'Dropbox chunk transfer stats - Sent: `' . pb_backupbuddy::$format->file_size( $data_length ) . '`, Transfer duration: `' . $send_time . '`, Speed: `' . pb_backupbuddy::$format->file_size( $chunk_transfer_speed ) . '`.' );
				
				// Set options for subsequent step chunks.
				$chunked_destination_settings = $settings;
				$chunked_destination_settings['_chunk_file'] = $file;
				$chunked_destination_settings['_chunk_maxsize'] = $max_chunk_size_bytes;
				$chunked_destination_settings['_chunk_upload_id'] = $result;
				$chunked_destination_settings['_chunk_offset'] = $data_length;
				$chunked_destination_settings['_chunk_next_offset'] = $data_length; // First chunk was sent initiationg multipart send.
				$chunked_destination_settings['_chunk_sent_count'] = 1;
				$chunked_destination_settings['_chunk_total_count'] = ceil( $file_size / $max_chunk_size_bytes );
				$chunked_destination_settings['_chunk_transfer_speeds'][] = $chunk_transfer_speed;
				pb_backupbuddy::status( 'details', 'Sent first chunk to Dropbox with upload ID: `' . $chunked_destination_settings['_chunk_upload_id'] . '`. Offset: `' . $chunked_destination_settings['_chunk_offset'] . '`.' );
				
				// Schedule next chunk to send.
				pb_backupbuddy::status( 'details', 'Dropbox (PHP 5.3+) scheduling send of next part(s).' );
				
				$cronTime = time();
				$cronArgs = array( $chunked_destination_settings, $file, $send_id, $delete_after );
				$cronHashID = md5( $cronTime . serialize( $cronArgs ) );
				$cronArgs[] = $cronHashID;
				
				if ( false === backupbuddy_core::schedule_single_event( $cronTime, 'destination_send', $cronArgs ) ) {
					pb_backupbuddy::status( 'error', 'Error #948844: Unable to schedule next Dropbox2 cron chunk.' );
					return false;
				} else {
					pb_backupbuddy::status( 'details', 'Success scheduling next cron chunk.' );
				}
				spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
				update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
				pb_backupbuddy::status( 'details', 'Dropbox (PHP 5.3+) scheduled send of next part(s). Done for this cycle.' );
				
				return array( $chunked_destination_settings['_chunk_upload_id'], 'Sent 1 of ' . $chunked_destination_settings['_chunk_total_count'] . ' parts.' );
				
			} else { // normal (non-chunked) send.
				
				pb_backupbuddy::status( 'details', 'Dropbox send not set to be chunked.' );
				pb_backupbuddy::status( 'details',  'About to put file `' . basename( $file ) . '` (' . pb_backupbuddy::$format->file_size( $file_size ) . ') to Dropbox (PHP 5.3+).' );
				$send_time = -(microtime( true ));
				try {
					$result = self::$_dbxClient->uploadFile( $settings['directory'] . '/' . basename( $file ), dbx\WriteMode::add(), $f );
				} catch ( \Exception $e ) {
					pb_backupbuddy::status( 'error', 'Dropbox Error: ' . $e->getMessage() );
					return false;
				}
				$send_time += microtime( true );
				@fclose( $f );
				
				
				pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
				require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
				pb_backupbuddy::status( 'details', 'Fileoptions instance #14.' );
				$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = false, $ignore_lock = false, $create_file = false );
				if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
					pb_backupbuddy::status( 'error', __('Fatal Error #9034.2344848. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
					return false;
				}
				pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
				$fileoptions = &$fileoptions_obj->options;
				
				// Calculate some stats to log.
				$data_length = $file_size;
				$transfer_speed = $data_length / $send_time;
				pb_backupbuddy::status( 'details', 'Dropbox (non-chunked) transfer stats - Sent: `' . pb_backupbuddy::$format->file_size( $data_length ) . '`, Transfer duration: `' . $send_time . '`, Speed: `' . pb_backupbuddy::$format->file_size( $transfer_speed ) . '/sec`.' );
				$fileoptions['write_speed'] = $transfer_speed;
				$fileoptions_obj->save();
				unset( $fileoptions_obj );
				
			} // end normal (non-chunked) send.
		} // End non-continuation send.
		
		pb_backupbuddy::status( 'message', 'Success sending `' . basename( $file ) . '` to Dropbox!' );
		
		
		// Start remote backup limit
		if ( $settings['archive_limit'] > 0 ) {
			pb_backupbuddy::status( 'details',  'Dropbox file limit in place. Proceeding with enforcement.' );
			
			$meta_data = self::$_dbxClient->getMetadataWithChildren( $settings['directory'] );
			
			// Create array of backups and organize by date
			$bkupprefix = backupbuddy_core::backup_prefix();
			
			$backups = array();
			foreach ( (array) $meta_data['contents'] as $looping_file ) {
				
				if ( $looping_file['is_dir'] == '1' ) { // JUST IN CASE. IGNORE anything that is a directory.
					continue;
				}
				
				// check if file is backup
				if ( ( strpos( $looping_file['path'], 'backup-' . $bkupprefix . '-' ) !== false ) ) { // Appears to be a backup file.
					$backups[$looping_file['path']] = strtotime( $looping_file['modified'] );
				}
			}
			arsort($backups);
			
			if ( ( count( $backups ) ) > $settings['archive_limit'] ) {
				pb_backupbuddy::status( 'details',  'Dropbox backup file count of `' . count( $backups ) . '` exceeds limit of `' . $settings['archive_limit'] . '`.' );
				$i = 0;
				$delete_fail_count = 0;
				foreach( $backups as $buname => $butime ) {
					$i++;
					if ( $i > $settings['archive_limit'] ) {
						if ( ! self::$_dbxClient->delete( $buname ) ) { // Try to delete backup on Dropbox. Increment failure count if unable to.
							pb_backupbuddy::status( 'details',  'Unable to delete excess Dropbox file: `' . $buname . '`' );
							$delete_fail_count++;
						} else {
							pb_backupbuddy::status( 'details',  'Deleted excess Dropbox file: `' . $buname . '`' );
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
	public static function test( $settings, $file ) {
		
		return false; // WE DO NOT HAVE A REMOTE TEST FOR THIS CURRENTLY.
		
	} // End test().
	
	
	
	/*	listFiles()
	 *	
	 *	List files in this destination & directory.
	 *	
	 *	@param		array			$settings		Destination settings.
	 *	@return		array|boolean					Array of items in directory OR bool FALSE on failure.
	 */
	public static function listFiles( $settings = array() ) {
		
		pb_backupbuddy::status( 'details', 'Dropbox2 List Function Started.' );
		
		// Normalize settings, apply defaults, etc.
		$settings = self::_normalizeSettings( $settings );
		
		// Connect to Dropbox.
		if ( false === self::_connect( $settings['access_token'] ) ) { // Try to connect. Return false if fail.
			return false;
		}
		
		// List directory contents.
		try {
			$listData = self::$_dbxClient->getMetadataWithChildren( $settings['directory'] );
		} catch ( \Exception $e ) {
			pb_backupbuddy::status( 'error', 'Error #58498954: Unable to list Dropbox directory. Details: `' . $e->getMessage() . '`.' );
			return false;
		}
		
		return $listData;
		
	} // End listFiles().
	
	
	
	/*	listFiles()
	 *	
	 * List files in this destination & directory.
	 *	
	 *	@param		array			$settings			Destination settings.
	 *	@param		string			$remote_file		Remote file to retrieve. Filename only. Directory, path, bucket, etc handled in $destination_settings.
	 *	@param		string			$local_file			Local file to save to.
	 *	@return		array|boolean						Array of items in directory OR bool FALSE on failure.
	 */
	public static function getFile( $settings = array(), $remote_file, $local_file ) {
		
		pb_backupbuddy::status( 'details', 'Dropbox2 List Function Started.' );
		
		// Normalize settings, apply defaults, etc.
		$settings = self::_normalizeSettings( $settings );
		
		// Connect to Dropbox.
		if ( false === self::_connect( $settings['access_token'] ) ) { // Try to connect. Return false if fail.
			return false;
		}
		
		
		// Open local file to write to.
		$f = @fopen( $local_file, 'w+b' );
		if ( false === $f ) {
			pb_backupbuddy::status( 'error', 'Error #54894985. Unable to open local file for writing `' . $local_file . '`.' );
			return false;
		}
		
		$remote_file = rtrim( $settings['directory'], '\\/' ) . '/' . $remote_file;
		
		// Get file. (dbxclient get file function writes to open passed file).
		try {
			$fileMetadata = self::$_dbxClient->getFile( $remote_file, $f );
		} catch ( \Exception $e ) {
			fclose( $f );
			@unlink( $local_file );
			pb_backupbuddy::status( 'error', 'Error #233223: Unable to get & copy Dropbox file. Details: `' . $e->getMessage() . '`.' );
			return false;
		}
		
		fclose( $f );
		
		if ( $fileMetadata === NULL ) {
			@unlink( $local_file );
			pb_backupbuddy::status( 'error', 'Invalid or unable to access. Remote Dropbox file: `' . $remote_file . '`.' );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Copied remote file `' . $remote_file . '` to local file `' . $local_file . '`. Details: `' . print_r( $fileMetadata, true ) . '`' );
		return true;
		
	} // End getFile().
	
	
	
	/*	delete()
	 *	
	 *	Delete files in this destination & directory. Path / directory provided in settings.
	 *	
	 *	@param		array			$settings		Destination settings.
	 *	@return		bool|string						Bool TRUE on success, else string error message.
	 */
	public static function delete( $settings = array(), $file_or_files ) {
		
		if ( ! is_array( $file_or_files ) ) {
			$files = array( $file_or_files );
		} else {
			$files = &$file_or_files;
		}
		
		pb_backupbuddy::status( 'details', 'Dropbox2 Delete Function Started.' );
		
		// Normalize settings, apply defaults, etc.
		$settings = self::_normalizeSettings( $settings );
		
		// Connect to Dropbox.
		if ( false === self::_connect( $settings['access_token'] ) ) { // Try to connect. Return false if fail.
			return false;
		}
		
		$directory = '/' . trim( $settings['directory'], '/\\' );
		
		foreach( $files as $file ) {
			try {
				if ( NULL === self::$_dbxClient->delete( $directory . '/' . $file ) ) {
					$error = 'Error #94349843. Unable to delete file `' . $directory . '/' . $file . '`. Details: `' . $e->getMessage() . '`.';
					return $error;
				}
			} catch ( \Exception $e ) {
				$error = 'Error #94349843. Unable to delete file `' . $directory . '/' . $file . '`. Details: `' . $e->getMessage() . '`.';
				pb_backupbuddy::status( 'error', $error );
				return $error;
			}
		}
		
		return true;
		
	} // End delete().
	
	
	
} // End class.