<?php
// Dustin Bolton 2014.
class backupbuddy_deploy {
	
	public $_state = array();		// Holds current state data. Retrieve with getState() and pass onto next run in the constructor.
	private $_errors = array();		// Hold error strings to retrieve with getErrors().
	
	
	
	/* __construct()
	 *
	 * ROLLBACK, RESTORE
	 *
	 * @param	array	$destination	Destination settings.
	 * @param	array 	$existinData	State data from a previous instantiation. Previously returned from getState().
	 * @param	string	$destinationID	Optional numeric ID number of this destination in the destinations setting array.
	 *
	 */
	public function __construct( $destinationSettings, $existingState = '', $destinationID = '' ) {
		pb_backupbuddy::status( 'details', 'Constructing deploy class.' );
		register_shutdown_function( array( &$this, 'shutdown_function' ) );
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
		
		if ( false === ( $decoded_key = backupbuddy_remote_api::key_to_array( $destinationSettings['api_key'] ) ) ) {
			die( 'Error #848349478943747. Unable to interpret API key. Corrupted?' );
		}
		
		if ( is_array( $existingState ) ) { // User passed along an existing state to resume.
			$this->_state = $existingState;
		} else { // Create new blank process & state.
			$this->_state = array(
				'apiKey' => $destinationSettings['api_key'],
				'destination' => $decoded_key,
				'destination_id' => $destinationID,
				'destinationSettings' => $destinationSettings,
				'startTime' => microtime(true),
				'backupProfile' => '',
				'sendTheme' => false, // Send active theme's differing files.
				'sendChildTheme' => false, // Send active child theme's differing files.
				'sendPlugins' => array(), // Array of pugin dirnames to send. Any not in here will be yanked out of the [push|pull]PluginFiles array in _backup-perform.php.
				'sendMedia' => false, // Send differing media files.
				
				'pushThemeFiles' => array(),
				'pushChildThemeFiles' => array(),
				'pushPluginFiles' => array(),
				'pushMediaFiles' => array(),
				
				'pullThemeFiles' => array(),
				'pullChildThemeFiles' => array(),
				'pullPluginFiles' => array(),
				'pullMediaFiles' => array(),
				
				'pullLocalArchiveFile' => '', // Location of the archive we will be restoring once file is pulled.
			);
		}
		pb_backupbuddy::status( 'details', 'Deploy class constructed.' );
	} // End __construct().
	
	
	
	/* start()
	 *
	 * @return	bool		true on success, else false.
	 */
	public function start( $sourceInfo ) {
		$this->_before( __FUNCTION__ );
		
		$pingTimePre = microtime(true);
		if ( false === ( $this->_state['remoteInfo'] = backupbuddy_remote_api::remoteCall( $this->_state['destination'], 'getPreDeployInfo', array(), $timeout = 30 ) ) ) {
			return $this->_error( implode( ', ', backupbuddy_remote_api::getErrors() ) );
		}
		$this->_state['remoteInfo'] = $this->_state['remoteInfo']['data'];
		$pingTimePost = microtime(true);
		$this->_state['remoteInfo']['pingTime'] = $pingTimePost - $pingTimePre;
		
		// Calculate plugins that do not match.
		/*
		$this->_state['pushPluginFiles'] = $this->calculatePluginDiff( $sourceInfo['activePlugins'], $this->_state['remoteInfo']['activePlugins'] );
		$this->_state['pullPluginFiles'] = $this->calculatePluginDiff( $this->_state['remoteInfo']['activePlugins'], $sourceInfo['activePlugins'] );
		*/
		
		$this->_state['pushPluginFiles'] = $this->calculateFileDiff( $sourceInfo['pluginSignatures'], $this->_state['remoteInfo']['pluginSignatures'] );
		$this->_state['pullPluginFiles'] = $this->calculateFileDiff( $this->_state['remoteInfo']['pluginSignatures'], $sourceInfo['pluginSignatures'] );
		
		if ( $sourceInfo['activeTheme'] == $this->_state['remoteInfo']['activeTheme'] ) { // Same theme so calculate theme files that do not match.
			$this->_state['pushThemeFiles'] = $this->calculateFileDiff( $sourceInfo['themeSignatures'], $this->_state['remoteInfo']['themeSignatures'] );
			$this->_state['pullThemeFiles'] = $this->calculateFileDiff( $this->_state['remoteInfo']['themeSignatures'], $sourceInfo['themeSignatures'] );
		} else {
			$this->_state['sendTheme'] = false;
			pb_backupbuddy::status( 'details', 'Different themes. Theme data will not be sent.' );
		}
		
		// Note: child theme support added in 6.0.0.6 so must check that remote index exists.
		if ( ( isset( $this->_state['remoteInfo']['activeChildTheme'] ) ) && ( $sourceInfo['activeChildTheme'] == $this->_state['remoteInfo']['activeChildTheme'] ) ) { // Same child theme so calculate theme files that do not match.
			$this->_state['pushChildThemeFiles'] = $this->calculateFileDiff( $sourceInfo['childThemeSignatures'], $this->_state['remoteInfo']['childThemeSignatures'] );
			$this->_state['pullChildThemeFiles'] = $this->calculateFileDiff( $this->_state['remoteInfo']['childThemeSignatures'], $sourceInfo['childThemeSignatures'] );
		} else {
			$this->_state['sendChildTheme'] = false;
			pb_backupbuddy::status( 'details', 'Different child themes. Theme data will not be sent.' );
		}
		
		// Calculate media files that do not match.
		$this->_state['pushMediaFiles'] = $this->calculateFileDiff( $sourceInfo['mediaSignatures'], $this->_state['remoteInfo']['mediaSignatures'] ); // was calculateMediaDiff().
		$this->_state['pullMediaFiles'] = $this->calculateFileDiff( $this->_state['remoteInfo']['mediaSignatures'], $sourceInfo['mediaSignatures'] ); // was calculateMediaDiff().
		
		// Store count of media files.
		//$sourceInfo['mediaCount'] = count( $sourceInfo['mediaSignatures'] );
		//$this->_state['remoteInfo']['mediaCount'] = count( $this->_state['remoteInfo']['mediaSignatures'] );
		
		unset( $sourceInfo['mediaSignatures'] );
		unset( $sourceInfo['themeSignatures'] );
		unset( $sourceInfo['childThemeSignatures'] );
		unset( $sourceInfo['pluginSignatures'] );
		unset( $this->_state['remoteInfo']['mediaSignatures'] );
		unset( $this->_state['remoteInfo']['themeSignatures'] );
		unset( $this->_state['remoteInfo']['childThemeSignatures'] );
		unset( $this->_state['remoteInfo']['pluginSignatures'] );
		return true;
	} // End start().
	
	
	public function calculateFileDiff( $sourceFileSignatures, $destinationFileSignatures ) {
		$updateFiles = array(); // Files to send.
		// Loop through local files to see if they differ from anything on remote.
		foreach( $sourceFileSignatures as $file => $signature ) {
			if ( ! isset( $destinationFileSignatures[ $file ] ) ) { // File does not exist on destination.
				$updateFiles[] = $file;
			} else { // File exists on remote. See if content is the same.
				if ( isset( $signature['sha1'] ) && ( $signature['sha1'] != $destinationFileSignatures[ $file ]['sha1'] ) ) { // Hash mismatch. Needs updating.
					$updateFiles[] = $file;
				} elseif ( ( ! isset( $signature['sha1'] ) ) || ( '' == $signature['sha1'] ) ) { // sha1 not calculated. size may be too large. compare size to see if changed.
					if ( $signature['size'] != $destinationFileSignatures[ $file ]['size'] ) { // size mismatch
						$updateFiles[] = $file;
						//echo $signature['size'] . ' != ' . $destinationFileSignatures[ $file ]['size'];
					}
				}
			}
		}
		return $updateFiles;
	}
	
	
	
	public function calculateMediaDiff( $sourceFileSignatures, $destinationFileSignatures ) {
		$updateFiles = array(); // Files to send.
		// Loop through local files to see if they differ from anything on remote.
		foreach( (array)$sourceFileSignatures as $file => $signature ) {
			if ( ! isset( $destinationFileSignatures[ $file ] ) ) { // File does not exist on destination.
				$updateFiles[] = $file;
			} else { // File exists on remote. See if content is the same.
				if ( $signature['modified'] != $destinationFileSignatures[ $file ]['modified'] ) { // mismatch of modified time stored in database. Needs updating.
					$updateFiles[] = $file;
				}
			}
		}
		return $updateFiles;
	}
	
	
	
	/*
	public function calculateThemeDiff( $sourceThemeSignatures, $destinationThemeSignatures ) {
		$updateThemeFiles = array(); // Theme files to send.
		// Loop through local theme files to see if they differ from anything on remote.
		foreach( $sourceThemeSignatures as $file => $signature ) {
			if ( ! isset( $destinationThemeSignatures[ $file ] ) ) { // File does not exist on destination.
				$updateThemeFiles[] = $file;
			} else { // File exists on remote. See if content is the same.
				if ( $signature['sha1'] != $destinationThemeSignatures[ $file ]['sha1'] ) { // Hash mismatch. Needs updating.
					$updateThemeFiles[] = $file;
				}
			}
		}
		return $updateThemeFiles;
	}
	*/
	
	
	
	public function calculatePluginDiff( $sourcePlugins, $destinationPlugins ) {
		
		$updatePlugins = array();
		$pluginPath = wp_normalize_path( WP_PLUGIN_DIR );
		
		foreach( $sourcePlugins as $sourceSlug => $sourcePlugin ) {
			$update = false;
			if ( ! isset( $destinationPlugins[ $sourceSlug ] ) ) { // Plugin does not exist on destination.
				$update = true;
			} else { // File exists on remote. See if content is the same.
				if ( $sourcePlugins[ $sourceSlug ]['version'] != $destinationPlugins[ $sourceSlug ]['version'] ) { // Version mismatch. Needs updating.
					$update = true;
				}
			}
			
			if ( true === $update ) {
				$pluginFiles = pb_backupbuddy::$filesystem->deepglob( $pluginPath . '/' . dirname( $sourceSlug ) );
				foreach( $pluginFiles as $pluginFileIndex => &$pluginFile ) { // Strip out leading path.
					if ( ! is_dir( $pluginFile ) ) { // Don't send just directory. Only files within. Note: This will not send a blank directory but that should not be an issue.
						$pluginFile = str_replace( $pluginPath, '', $pluginFile );
					} else {
						unset( $pluginFiles[ $pluginFileIndex ] );
					}
				}
				$updatePlugins = array_merge( $updatePlugins, $pluginFiles );
			}
		}
		
		return $updatePlugins;
		
	} // End calculatePluginDiff).
	
	
	
	public function hashFileMap( $root ) {
		error_log( 'BackupBuddy Error #83837833: Not currently in use.' );
		die( 'BackupBuddy Error #83837833: Not currently in use.' );
		
		
		$generate_sha1 = true;
		
		echo 'mem:' . memory_get_usage(true) . '<br>';
		$files = (array) pb_backupbuddy::$filesystem->deepglob( $root );
		
		echo 'mem:' . memory_get_usage(true) . '<br>';
		$root_len = strlen( $root );
		$new_files = array();
		foreach( $files as $file_id => &$file ) {
			$stat = stat( $file );
			
			if ( FALSE === $stat ) {
				pb_backupbuddy::status( 'error', 'Unable to read file `' . $file . '` stat.' );
			}
			$new_file = substr( $file, $root_len );
			
			$sha1 = '';
			if ( ( true === $generate_sha1 ) && ( $stat['size'] < 1073741824 ) ) { // < 100mb
				$sha1 = sha1_file( $file );
			}
			
			$new_files[$new_file] = array(
				'scanned'	=>	time(),
				'size'		=> $stat['size'],
				'modified'	=> $stat['mtime'],
				'sha1'		=> $sha1,
				
				
				// TODO: don't render sha1 here? do it in a subsequent step(s) with cron to allow for more time? update fileoptions file every x number of tiles and a count attempts without proceeding to assume failure? max_overall attempts?
				
				
			);
			unset( $files[$file_id] ); // Better to free memory or leave out for performance?
			
		}
		unset( $files );
		echo 'mem:' . memory_get_usage(true) . '<br>';
		echo 'filecount: ' . count( $new_files ) . '<br>';
		print_r( $new_files );
	} // end hashFileMap().
	
	
	
	/* extractDatabase()
	 *
	 * ROLLBACK, RESTORE
	 * Extracts database file(s) into temp dir.
	 *
	 * @param	bool		true on success, else false.
	 */
	public function extractDatabase() {
		$this->_before( __FUNCTION__ );
		
		$this->_priorRollbackCleanup();
		
		pb_backupbuddy::status( 'details', 'Loading zipbuddy.' );
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		$zipbuddy = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		pb_backupbuddy::status( 'details', 'Zipbuddy loaded.' );
		
		// Find SQL file location in archive.
		pb_backupbuddy::status( 'details', 'Calculating possible SQL file locations.' );
		$possibleSQLLocations = array();
		$possibleSQLLocations[] = trim( rtrim( str_replace( 'backupbuddy_dat.php', '', $this->_state['datLocation'] ), '\\/' ) . '/db_1.sql', '\\/' ); // SQL file most likely is in the same spot the dat file was.
		$possibleSQLLocations[] = 'db_1.sql'; // DB backup.
		$possibleSQLLocations[] = 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/db_1.sql'; // Full backup.
		pb_backupbuddy::status( 'details', 'Possible SQL file locations: `' . implode( ';', $possibleSQLLocations ) . '`.' );
		$possibleSQLLocations = array_unique( $possibleSQLLocations );
		foreach( $possibleSQLLocations as $possibleSQLLocation ) {
			if ( true === $zipbuddy->file_exists( $this->_state['archive'], $possibleSQLLocation, $leave_open = true ) ) {
				$detectedSQLLocation = $possibleSQLLocation;
				break;
			}
		} // end foreach.
		pb_backupbuddy::status( 'details', 'Confirmed SQL file location: `' . $detectedSQLLocation . '`.' );
		
		// Get SQL file.
		$files = array( $detectedSQLLocation => 'db_1.sql' );
		pb_backupbuddy::$filesystem->unlink_recursive( $this->_state['tempPath'] ); // Remove if already exists.
		mkdir( $this->_state['tempPath'] ); // Make empty directory.
		require( pb_backupbuddy::plugin_path() . '/classes/_restoreFiles.php' );
		
		// Extract SQL file.
		pb_backupbuddy::status( 'details', 'Extracting SQL file(s).' );
		if ( false === backupbuddy_restore_files::restore( $this->_state['archive'], $files, $this->_state['tempPath'], $zipbuddy ) ) {
			return $this->_error( 'Error #85384: Unable to restore one or more database files.' );
		}
		
		pb_backupbuddy::status( 'details', 'Finished database extraction function.' );
		return true;
	} // End extractDatabase().
	
	
	
	/* _error()
	 *
	 * Logs error messages for retrieval with getErrors().
	 *
	 * @param	string		$message	Error message to log.
	 * @return	null
	 */
	private function _error( $message ) {
		$this->_errors[] = $message;
		pb_backupbuddy::status( 'error', $message );
		return false;
	} // End _error().
	
	
	
	/* getErrors()
	 *
	 * Get any errors which may have occurred.
	 *
	 * @return	array 		Returns an array of string error messages.
	 */
	public function getErrors() {
		return $this->_errors;
	} // End getErrors();
	
	
	
	/* getState()
	 *
	 * Get state array data for passing to the constructor for subsequent calls.
	 *
	 * @return	array 		Returns an array of state data.
	 */
	public function getState() {
		pb_backupbuddy::status( 'details', 'Getting deploy state.' );
		return $this->_state;
	} // End getState().
	
	
	
	/* setState()
	 *
	 * Replace current state array with provided one.
	 *
	 */
	public function setState( $stateData ) {
		$this->_state = $stateData;
	} // End setState().
	
	
	
	/* _before()
	 *
	 * Runs before every function to keep track of ran functions in the state data for debugging.
	 *
	 * @return	null
	 */
	private function _before( $functionName ) {
		$this->_state['stepHistory'][] = array( 'function' => $functionName, 'start' => microtime(true) );
		pb_backupbuddy::status( 'details', 'Starting function `' . $functionName . '`.' );
		return;
	} // End _before().
	
	
	
	/*	shutdown_function()
	 *	
	 *	Used for catching fatal PHP errors during backup to write to log for debugging.
	 *	
	 *	@return		null
	 */
	public function shutdown_function() {
		
		// Get error message.
		// Error types: http://php.net/manual/en/errorfunc.constants.php
		$e = error_get_last();
		if ( $e === NULL ) { // No error of any kind.
			return;
		} else { // Some type of error.
			if ( !is_array( $e ) || ( $e['type'] != E_ERROR ) && ( $e['type'] != E_USER_ERROR ) ) { // Return if not a fatal error.
				return;
			}
		}
		
		$e_string = '';
		foreach( (array)$e as $e_line_title => $e_line ) {
			$e_string .= $e_line_title . ' => ' . $e_line . "\n";
		}
		
		pb_backupbuddy::status( 'error', 'FATAL PHP ERROR: ' . $e_string );
		
	} // End shutdown_function.
	
	
	
} // end class.

