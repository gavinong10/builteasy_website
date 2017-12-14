<?php
// Dustin Bolton 2014.
class backupbuddy_restore {
	
	
	public $_state = array();		// Holds current state data. Retrieve with getState() and pass onto next run in the constructor.
	private $_errors = array();		// Hold error strings to retrieve with getErrors().
	
	
	
	/* __construct()
	 *
	 * ROLLBACK, RESTORE
	 *
	 * @param	string	$type			Restore type: rollback (roll back from inside WordPress), restore (importbuddy)
	 * @param	array 	$existinData	State data from a previous instantiation. Previously returned from getState().
	 *
	 */
	public function __construct( $type, $existingState = '' ) {
		pb_backupbuddy::status( 'details', 'Constructing rollback class.' );
		
		if ( ( 'rollback' != $type ) && ( 'restore' != $type ) ) {
			$this->_error( 'Invalid restore type `' . htmlentities( $type ) . '`.' );
			return false;
		}
		
		register_shutdown_function( array( &$this, 'shutdown_function' ) );
		
		
		pb_backupbuddy::status( 'details', 'Setting restore state defaults.' );
		$this->_state = array(
			'type' => $type,
			'archive' => '',					// Full archive path & filename.
			'serial' => '',						// Calculated backup serial.
			'tempPath' => '',					// Temporary path to do extractions into. Trailing path.
			'dat' => array(),					// DAT file array.
			'undoURL' => '',					// URL to the undo script, eg http://your.com/backupbuddy_rollback_undo-XXXXXXXX.php
			'forceMysqlMethods' => array(),		// mysql methods to force for importing. Default to empty array to auto-detect.
			'autoAdvance' => true,				// Whether or not to auto advance (ie for web-based rollback auto-refresh to next step).
			'maxExecutionTime' => backupbuddy_core::detectMaxExecutionTime(),			// If set then override detected max execution time.
			'dbImportPoint' => 0,				// For compat mode mysql, next row to start importo n.
			'zipMethodStrategy' => 'all',		//	Zip methods to use. Valid: all, ziparchive, pclzip
			'restoreFiles' => true,
			'restoreDatabase' => true,
			'migrateHtaccess' => true,
			'databaseSettings' => array(
									'server' => '',
									'database' => '',
									'username' => '',
									'password' => '',
									'prefix' => '',
									'tempPrefix' => '', // Used by deployment to import to a temporary prefix. Migration will migrate data to the real prefix though. Then the db will be swapped out between existing and tempPrefix.
									'wipePrefix' => false,
									'renamePrefix' => false, // Temporarily rename existing tables to another prefix for allowing undo of db import.
									'wipeDatabase' => false,
									'ignoreSqlErrors' => false,
									'sqlFiles' => array(),
									'sqlFilesLocation' => '',
									'databaseMethodStrategy' => 'php', // Defaults to php due to chunking ability.
									//'importResumeFiles'=> array(), // IMPORTANT: Leave unset in default. Once set, emptyy array means finished all files.
									'importResumePoint'=> '', // Current file pointer value (from ftell()) for chunked resumed SQL file import.
									'importedResumeRows' => 0, // Total number of rows imported thus far when chunking. [INFORMATION ONLY]
									'importedResumeFails' => 0, // Total number of SQL queries that failed executiong when chunking. [INFORMATION ONLY]
									'importedResumeTime' => 0, // Total time of actual import when chunking. [INFORMATION ONLY]
									'migrateDatabase' => true,
									'migrateDatabaseBruteForce' => true,
									'migrateResumeSteps' => '',
									'migrateResumePoint' => '',
								),
			'cleanup' => array(					// Step 6 cleanup options.
				'deleteArchive' => true,
				'deleteTempFiles' => true,
				'deleteImportBuddy' => true,
				'deleteImportLog' => true,
								),
			'potentialProblems' => array(),		// Array of potential issues encountered to show the user AFTER import is done.
			'stepHistory' => array(),			// Array of arrays of the step functions run thus far. Track start and finish times.
		);

		// Restore-specific default options.
		if ( 'restore' == $type ) {
			$this->_state['skipUnzip'] = false;
			$this->_state['restoreFiles'] = true;
			$this->_state['restoreDatabase'] = true;
			$this->_state['migrateHtaccess'] = true;
			$this->_state['tempPath'] = ABSPATH . 'importbuddy/temp_' . pb_backupbuddy::$options['log_serial'] . '/';
		} elseif ( 'rollback' == $type ) {
			$this->_state['tempPath'] = backupbuddy_core::getTempDirectory() . $this->_state['type'] . '_' . $this->_state['serial'] . '/';
		}
		
		
		if ( is_array( $existingState ) ) { // User passed along an existing state to resume.
			pb_backupbuddy::status( 'details', 'Using provided restore state data.' );
			$this->_state = $this->_array_replace_recursive( $this->_state, $existingState );
		}
		
		// Check if a default state override exists.  Used by automated restoring.
		/*
		if ( isset( pb_backupbuddy::$options['default_state_overrides'] ) && ( count( pb_backupbuddy::$options['default_state_overrides'] ) > 0 ) ) { // Default state overrides exist. Apply them.
			$this->_state = array_merge( $this->_state, pb_backupbuddy::$options['default_state_overrides'] );
		}
		*/
		
		
		pb_backupbuddy::status( 'details', 'Restore class constructed in `' . $type . '` mode.' );
		
		pb_backupbuddy::set_greedy_script_limits(); // Just always assume we need this during restores/rollback...
	} // End __construct().
	
	
	
	/* start()
	 *
	 * ROLLBACK, RESTORE
	 * Returns false on failure. Use getErrors() to get an array of errors encountered if any.
	 * Returns an array of information on success.
	 * Grab the rollback state data with getState().
	 *
	 * @return	bool		true on success, else false.
	 */
	public function start( $backupFile, $skipUnzip = false ) {
		$this->_before( __FUNCTION__ );
		
		if ( ! file_exists( $backupFile ) ) {
			return $this->_error( 'Unable to access backup file `' . $backupFile . '`. Verify it still exists and has proper read permissions.' );
		}
		
		$this->_state['archive'] = $backupFile;
		$serial = backupbuddy_core::get_serial_from_file( basename( $backupFile ) );
		$this->_state['serial'] = $serial;
		unset( $backupFile );
		unset( $serial );
		
		if ( defined( 'PB_STANDALONE' ) && ( true === PB_STANDALONE ) ) {
			$mysql_9010_log = ABSPATH . 'importbuddy/mysql_9010_log-' . pb_backupbuddy::$options['log_serial'] . '.txt';
			if ( file_exists( $mysql_9010_log ) ) {
				@unlink( $mysql_9010_log );
			}
		}
		
		if ( true !== $skipUnzip ) {
			// Get zip meta information.
			$customTitle = 'Backup Details';
			pb_backupbuddy::status( 'details', 'Attempting to retrieve zip meta data from comment.' );
			if ( false !== ( $metaInfo = backupbuddy_core::getZipMeta( $this->_state['archive'] ) ) ) {
				pb_backupbuddy::status( 'details', 'Found zip meta data.' );
			} else {
				pb_backupbuddy::status( 'details', 'Did not find zip meta data.' );
			}
			
			
			pb_backupbuddy::status( 'details', 'Loading zipbuddy.' );
			require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
			
			$zipbuddy = new pluginbuddy_zipbuddy( dirname( $this->_state['archive'] ) );
			pb_backupbuddy::status( 'details', 'Zipbuddy loaded.' );
		}
		
		// Find DAT file.
		pb_backupbuddy::status( 'details', 'Calculating possible DAT file locations.' );
		$detectedDatLocation = '';
		$possibleDatLocations = array();
		if ( isset( $metaInfo['dat_path'] ) ) {
			$possibleDatLocations[] = $metaInfo['dat_path'][1]; // DAT file location encoded in meta info. Should always be valid.
		}
		$possibleDatLocations[] = 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/backupbuddy_dat.php'; // Full backup.
		$possibleDatLocations[] = 'backupbuddy_dat.php'; // DB backup. (look for this second in case user left an old dat file in root).
		pb_backupbuddy::status( 'details', 'Possible DAT file locations: `' . implode( ';', $possibleDatLocations ) . '`.' );
		$possibleDatLocations = array_unique( $possibleDatLocations );
		if ( true === $skipUnzip ) { // Only look for DAT file in filesystem. Zip should be pre-extracted, eg by the user manually.
			pb_backupbuddy::status( 'details', 'Looking for DAT file in local filesystem (instead of in zip) since advanced skip unzip option set.' );
			foreach( $possibleDatLocations as $possibleDatLocation ) { // Look for DAT file in filesystem.
				pb_backupbuddy::status( 'details', 'Does `' . ABSPATH . $possibleDatLocation . '` exist?' );
				if ( true === file_exists( ABSPATH . $possibleDatLocation ) ) {
					pb_backupbuddy::status( 'details', 'Yes, exists.' );
					$detectedDatLocation = $possibleDatLocation;
					break;
				} else {
					pb_backupbuddy::status( 'details', 'No, does not exist.' );
				}
			}
			if ( '' == $detectedDatLocation ) {
				$message = 'Unable to find the DAT file for this backup archive pre-extracted in the filesystem. Make sure you have already unzipped this backup into the same directory as importbuddy.php.';
				return $this->_error( $message );
			}
		} else { // Look for DAT file inside of zip archive.
			pb_backupbuddy::status( 'details', 'Looking for DAT file in zip archive itself.' );
			foreach( $possibleDatLocations as $possibleDatLocation ) { // Look for DAT file in zip.
				if ( true === $zipbuddy->file_exists( $this->_state['archive'], $possibleDatLocation, $leave_open = true ) ) {
					$detectedDatLocation = $possibleDatLocation;
					break;
				}
			} // end foreach.
		}
		if ( '' == $detectedDatLocation ) {
			return $this->_error( 'Unable to determine DAT file location. It may be missing OR the backup zip file may be incomplete or corrupted. Verify the backup zip has fully uploaded or re-upload it. You can try manually unzipping then selecting the advanced option to skip unzip.' );
		}
		pb_backupbuddy::status( 'details', 'Confirmed DAT file location: `' . $detectedDatLocation . '`.' );
		$this->_state['datLocation'] = $detectedDatLocation;
		
		unset( $metaInfo ); // No longer need anything from the meta information.
		
		if ( true !== $skipUnzip ) {
			function mkdir_recursive( $path ) {
				if ( empty( $path ) ) { // prevent infinite loop on bad path
					return;
				}
				is_dir( dirname( $path ) ) || mkdir_recursive( dirname( $path ) );
				return is_dir( $path ) || mkdir( $path );
			}
			
			// Load DAT file contents.
			pb_backupbuddy::status( 'details', 'Creating temporary file directory `' . $this->_state['tempPath'] . '`.' );
			pb_backupbuddy::$filesystem->unlink_recursive( $this->_state['tempPath'] ); // Remove if already exists.
			mkdir_recursive( $this->_state['tempPath'] ); // Make empty directory.
			
			// Restore DAT file.
			pb_backupbuddy::status( 'details', 'Extracting DAT file.' );
			$files = array( $detectedDatLocation => 'backupbuddy_dat.php' );
			require( pb_backupbuddy::plugin_path() . '/classes/_restoreFiles.php' );
			$result = backupbuddy_restore_files::restore( $this->_state['archive'], $files, $this->_state['tempPath'], $zipbuddy );
			echo '<script type="text/javascript">jQuery("#pb_backupbuddy_working").hide();</script>';
			pb_backupbuddy::flush();
			if ( false === $result ) {
				$this->_error( 'Error #85484: Unable to retrieve DAT file. This is a fatal error.' );
				return false;
			}
			
			$datFile = $this->_state['tempPath'] . 'backupbuddy_dat.php';
		} else {
			$datFile = $this->_state['datLocation'];
		}
		
		if ( false === ( $datData = backupbuddy_core::get_dat_file_array( $datFile ) ) ) {
			$this->_error( 'Error #4839484: Unable to retrieve DAT file. The backup may have failed opening due to lack of memory, permissions issues, or other reason. Use ImportBuddy to restore or check the Advanced Log above for details.' );
			return false;
		}
		$this->_state['dat'] = $datData;
		pb_backupbuddy::status( 'details', 'DAT file extracted.' );
		
		if ( defined( 'PB_STANDALONE' ) && ( true === PB_STANDALONE ) ) {
			$simpleVersion = substr( pb_backupbuddy::$options['bb_version'], 0, strpos( pb_backupbuddy::$options['bb_version'], ' ' ) );
			if ( isset( $this->_state['dat']['backupbuddy_version'] ) && ( version_compare( $this->_state['dat']['backupbuddy_version'], $simpleVersion, '>' ) ) ) {
				pb_backupbuddy::status( 'error', 'Warning: You are attempting to restore an archive which was created with a newer version of BackupBuddy (' . $this->_state['dat']['backupbuddy_version'] . ') than this ImportBuddy (' . $simpleVersion . '). For best results use an ImportBuddy that is as least as up to date as the BackupBuddy which created the archive.' );
			}
		}
		
		if ( 'rollback' == $this->_state['type'] ) {
			$this_siteurl = str_replace( 'https://', 'http://', site_url() );
			$this_siteurldat = str_replace( 'https://', 'http://', $this->_state['dat']['siteurl'] );
			if ( $this_siteurl != $this_siteurldat ) {
				$this->_error( __( 'Error #5849843: Site URL does not match. Current Site URL: `' . site_url() . '`. Site URL in backup: `' . $this->_state['dat']['siteurl'] . '`. You cannot roll back the database if the URL has changed or for backups or another site. Use importbuddy.php to restore or migrate instead.', 'it-l10n-backupbuddy' ) );
				return false;
			}
			
			global $wpdb;
			if ( $this->_state['dat']['db_prefix'] != $wpdb->prefix ) {
				$this->_error( __( 'Error #2389394: Database prefix does not match. You cannot roll back the database if the database prefix has changed or for backups or another site. Use importbuddy.php to restore or migrate instead.', 'it-l10n-backupbuddy' ) );
				return false;
			}
			
			pb_backupbuddy::$options['rollback_cleanups'][ $this->_state['serial'] ] = time();
			pb_backupbuddy::save();
			
			// Generate UNDO script.
			pb_backupbuddy::status( 'details', 'Generating undo script.' );
			$this->_state['undoFile'] = 'backupbuddy_rollback_undo-' . $this->_state['serial'] . '.php';
			$undoURL = rtrim( site_url(), '/\\' ) . '/' . $this->_state['undoFile'];
			if ( false === copy( dirname( __FILE__ ) . '/_rollback_undo.php', ABSPATH . $this->_state['undoFile'] ) ) {
				$this->_error( __( 'Warning: Unable to create undo script in site root. You will not be able to automated undoing the rollback if something fails so BackupBuddy will not continue.', 'it-l10n-backupbuddy' ) );
				return false;
			}
			$this->_state['undoURL'] = $undoURL;
		}
		
		pb_backupbuddy::status( 'details', 'Finished starting function.' );
		return true;
	} // End start().
	
	
	
	/* extractDatabase()
	 *
	 * ROLLBACK, RESTORE
	 * Extracts database file(s) into temp dir.
	 *
	 * @param	bool		true on success, else false.
	 */
	public function extractDatabase() {
		$this->_before( __FUNCTION__ );
		
		die( ' ERROR #348437843784: DEPRECATED FUNCTION CALL! ' );
		
		
		$this->_priorRollbackCleanup();
		
		pb_backupbuddy::status( 'details', 'Loading zipbuddy.' );
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		$zipbuddy = new pluginbuddy_zipbuddy( dirname( $this->_state['archive'] ) );
		pb_backupbuddy::status( 'details', 'Zipbuddy loaded.' );
		
		// Find SQL file location in archive.
		pb_backupbuddy::status( 'details', 'Calculating possible SQL file locations.' );
		$detectedSQLLocation = '';
		$possibleSQLLocations = array();
		
		$possibleSQLLocations[] = trim( rtrim( str_replace( 'backupbuddy_dat.php', '', $this->_state['datLocation'] ), '\\/' ) . '/db_1.sql', '\\/' ); // SQL file most likely is in the same spot the dat file was.
		$possibleSQLLocations[] = 'db_1.sql'; // DB backup. v2.x+
		$possibleSQLLocations[] = 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/db_1.sql'; // Full backup.
		
		$possibleSQLLocations[] = 'db.sql'; // DB backup. v1.x
		$possibleSQLLocations[] = 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/db.sql'; // Full backup v1.x.
		
		pb_backupbuddy::status( 'details', 'Possible SQL file locations: `' . implode( ';', $possibleSQLLocations ) . '`.' );
		$possibleSQLLocations = array_unique( $possibleSQLLocations );
		foreach( $possibleSQLLocations as $possibleSQLLocation ) {
			if ( true === $zipbuddy->file_exists( $this->_state['archive'], $possibleSQLLocation, $leave_open = true ) ) {
				$detectedSQLLocation = $possibleSQLLocation;
				break;
			}
		} // end foreach.
		
		if ( '' == $detectedSQLLocation ) {
			$this->_error( 'Error #8483783: Unable to find SQL file(s) location.' );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Confirmed SQL file location: `' . $detectedSQLLocation . '`.' );
		$this->_state['databaseSettings']['sqlFile'] = $detectedSQLLocation;
		
		// Get SQL file.
		$files = array( $detectedSQLLocation => 'db_1.sql' );
		pb_backupbuddy::$filesystem->unlink_recursive( $this->_state['tempPath'] ); // Remove if already exists.
		mkdir( $this->_state['tempPath'] ); // Make empty directory.
		require( pb_backupbuddy::plugin_path() . '/classes/_restoreFiles.php' );
		
		// Extract SQL file.
		pb_backupbuddy::status( 'details', 'Extracting SQL file(s).' );
		if ( false === backupbuddy_restore_files::restore( $this->_state['archive'], $files, $this->_state['tempPath'], $zipbuddy ) ) {
			$this->_error( 'Error #85384: Unable to restore one or more database files.' );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Finished database extraction function.' );
		return true;
	} // End extractDatabase().
	
	
	
	public function determineDatabaseFiles() {
		$this->_before( __FUNCTION__ );
		
		// Try to find SQL file since it has not been found yet.
		pb_backupbuddy::status( 'details', 'Determining SQL file location...' );
		
		$this->_state['databaseSettings']['sqlFilesLocation'] = '';
		$this->_state['databaseSettings']['sqlFiles'] = array();
		
		$possible_sql_file_paths = array( // Possible locations of .SQL file. Look for SQL files in root LAST in case user left files there.
			$this->_state['restoreFileRoot'] . 'wp-content/uploads/temp_' . $this->_state['serial'] . '/',				// Full backup < v2.0.
			$this->_state['restoreFileRoot'] . 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/',	// Full backup >= v2.0.
			$this->_state['tempPath'],																					// Determined from detecting DAT file. Should always be the location really... As of v4.1.
			$this->_state['restoreFileRoot'],																			// Database backup < v2.0.
		);
		$foundSQL = false;
		
		foreach( $possible_sql_file_paths as $possible_sql_file_path ) { // Check each file location to see which hits.
			pb_backupbuddy::status( 'details', 'Looking for SQL files in `' . $possible_sql_file_path . '`.' );
			$possible_sql_files = glob( $possible_sql_file_path . '*.sql' );
			if ( is_array( $possible_sql_files ) && ( count( $possible_sql_files ) > 0 ) ) { // Found SQL files here.
				$this->_state['databaseSettings']['sqlFilesLocation'] = $possible_sql_file_path;
				$this->_state['databaseSettings']['sqlFiles'] = array_map( 'basename', $possible_sql_files );
				pb_backupbuddy::status( 'details', 'Found ' . count( $this->_state['databaseSettings']['sqlFiles'] ) . ' SQL files in `' . $possible_sql_file_path . '`.' );
				break;
			}
		} // End foreach().
		unset( $possible_sql_file_paths );
		
		if ( false !== $this->_state['restoreFiles'] ) {
			if ( count( $this->_state['databaseSettings']['sqlFiles'] ) == 0 ) {
				pb_backupbuddy::status( 'error', 'Unable to find db_1.sql or other expected database file in the extracted files in the expected location. Make sure you did not rename your backup ZIP file. You may manually restore your SQL file if you can find it via phpmyadmin or similar tool then on Step 1 of ImportBuddy select the advanced option to skip database import. This will allow you to proceed.' );
				return false;
			} else {
				pb_backupbuddy::status( 'details', 'SQL files found. Finished determining database files.' );
				return true;
			}
		}
	} // End determineDatabaseFiles().
	
	
	
	/* restoreDatabase()
	 *
	 * ROLLBACK, RESTORE
	 * Renames existing tables then imports the database SQL data into mysql. Turns on maintenance mode during this.
	 *
	 8 @param	string		$overridePrefix		If not empty string then use this db prefix insead of the one set in the state data.
	 * @return	bool|array						true on success, false on failure, OR array if chunking needed for DB continuation. chunks mid-db table import and/or for each individual .sql file. depends on method, runtime left, etc. see mysqlbuddy for chunking details.
	 */
	public function restoreDatabase( $overridePrefix = '' ) {
		$this->_before( __FUNCTION__ );
		global $wpdb;
		
		pb_backupbuddy::status( 'details', 'Restoring database tables.' );
		
		if ( !isset( $this->_state['databaseSettings']['sqlFilesLocation'] ) || ( '' == $this->_state['databaseSettings']['sqlFilesLocation'] ) ) {
			$this->determineDatabaseFiles();
		}
		
		if ( 'rollback' == $this->_state['type'] ) {
			$this->_state['databaseSettings']['server'] = DB_HOST;
			$this->_state['databaseSettings']['database'] = DB_NAME;
			$this->_state['databaseSettings']['username'] = DB_USER;
			$this->_state['databaseSettings']['password'] = DB_PASSWORD;
			
			$this->_state['databaseSettings']['prefix'] = 'bbnew-' . substr( $this->_state['serial'], 0, 4 ) . '_';
			
			$forceMysqlMethods = array( pb_backupbuddy::$options['database_method_strategy'] );
		}
		
		
		// Allow overriding prefix in parameters.
		if ( '' == $overridePrefix ) {
			$importPrefix = $this->_state['databaseSettings']['prefix'];
		} else {
			$importPrefix = $overridePrefix;
		}
		
		
		
		// Determine database strategy.
		if ( 'php' == $this->_state['databaseSettings']['databaseMethodStrategy'] ) {
			pb_backupbuddy::status( 'details', 'Database method set to PHP only.' );
			$forceMysqlMethods = array( 'php' );
		} elseif ( 'commandline' == $this->_state['databaseSettings']['databaseMethodStrategy'] ) {
			pb_backupbuddy::status( 'details', 'Database method set to command line only.' );
			$forceMysqlMethods = array( 'commandline' );
		} elseif ( 'all' == $this->_state['databaseSettings']['databaseMethodStrategy'] ) {
			pb_backupbuddy::status( 'details', 'Database method set to all -- using in preferred order: php, commandline.' );
			$forceMysqlMethods = array( 'php', 'commandline' );
		} else { // Not passed for some odd reason? Set default.
			pb_backupbuddy::status( 'warning', 'Database method not passed though expected. Using default of PHP only.' );
			$forceMysqlMethods = array( 'php' );
		}
		
		
		// Initialize mysqlbuddy.
		require_once( pb_backupbuddy::plugin_path() . '/lib/mysqlbuddy/mysqlbuddy.php' );
		pb_backupbuddy::$classes['mysqlbuddy'] = new pb_backupbuddy_mysqlbuddy( $this->_state['databaseSettings']['server'], $this->_state['databaseSettings']['database'], $this->_state['databaseSettings']['username'], $this->_state['databaseSettings']['password'], $importPrefix, $forceMysqlMethods, $this->_state['maxExecutionTime'] ); // $database_host, $database_name, $database_user, $database_pass, $old_prefix, $force_method = array()
		if ( isset( $this->_state['dat']['db_version'] ) ) {
			pb_backupbuddy::$classes['mysqlbuddy']->set_incoming_sql_version( $this->_state['dat']['db_version'] ); // Tell mysqlbuddy the version of the incoming SQL file's mysql.
		}
		
		// Restore each SQL file as its own page load.
		if ( !isset( $this->_state['databaseSettings']['importResumeFiles'] ) ) { // First pass so populate list of SQL files needing imported.
			$this->_state['databaseSettings']['importResumeFiles'] = $this->_state['databaseSettings']['sqlFiles'];
		}
		$filesRemaining = $this->_state['databaseSettings']['importResumeFiles'];
		pb_backupbuddy::status( 'details', 'SQL files to import: ' . count( $filesRemaining ) );
		foreach( $filesRemaining as $sql_file ) {
			$full_file = $this->_state['databaseSettings']['sqlFilesLocation'] . $sql_file;
			pb_backupbuddy::status( 'details', 'Importing SQL file `' . basename( $sql_file ) . '` (size: ' . pb_backupbuddy::$format->file_size( @filesize( $full_file ) ) . ').' );
			// Tell mysqlbuddy to IMPORT the SQL file.
			$import_result = pb_backupbuddy::$classes['mysqlbuddy']->import( $full_file, $oldPrefix = $this->_state['dat']['db_prefix'], $this->_state['databaseSettings']['importResumePoint'], $this->_state['databaseSettings']['ignoreSqlErrors'] );
			
			if ( FALSE === $import_result ) {
				$this->_error( 'Error #953834: Problem importing database. See status log above for details.' );
				return false;
			} elseif ( TRUE === $import_result ) { // Success on this SQL file.
				if ( '' != $this->_state['databaseSettings']['importResumePoint'] ) { // Chunking was used. Give some stats.
					pb_backupbuddy::status( 'details', 'Chunking imported `' . $this->_state['databaseSettings']['importedResumeRows'] . '` rows in `' . round( $this->_state['databaseSettings']['importedResumeTime'], 3 ) . '` seconds. `' . $this->_state['databaseSettings']['importedResumeFails'] . '` SQL query failures.' );
				}
				
				array_shift( $this->_state['databaseSettings']['importResumeFiles'] ); // Finished this table so take it off the stack.
				
				// Reset chunking data since this file is finished.
				$this->_state['databaseSettings']['importResumePoint'] = '';
				$this->_state['databaseSettings']['importedResumeRows'] = 0;
				$this->_state['databaseSettings']['importedResumeFails'] = 0;
				$this->_state['databaseSettings']['importedResumeTime'] = 0;
				
				pb_backupbuddy::status( 'details', 'Finished importing SQL file `' . basename( $sql_file ) . '`. Database files remaining: ' . count( $this->_state['databaseSettings']['importResumeFiles'] ) );
				return array(); // Any array returned here results in resuming using the latest state data. sqlFilesRemaning is what we care about keeping up to date on which file to do.
			} else { // Resumed chunking needed.
				if ( ! is_array( $import_result ) ) {
					pb_backupbuddy::status( 'error', 'Error #93484: Expected array. Got: `' . $import_result . '`.' );
					return false;
				} else {
					$this->_state['databaseSettings']['importResumePoint'] = $import_result[0];
					$this->_state['databaseSettings']['importedResumeRows'] += $import_result[1];
					$this->_state['databaseSettings']['importedResumeFails'] += $import_result[2];
					$this->_state['databaseSettings']['importedResumeTime'] += $import_result[3];
					pb_backupbuddy::status( 'details', 'Database import not yet finished. Resume next at `' . $this->_state['databaseSettings']['importResumePoint'] . '`.' );
					pb_backupbuddy::status( 'details', 'So far imported `' . $this->_state['databaseSettings']['importedResumeRows'] . '` rows in `' . round( $this->_state['databaseSettings']['importedResumeTime'], 3 ) . '` seconds. `' . $this->_state['databaseSettings']['importedResumeFails'] . '` SQL query failures.' );
					return $import_result;
				}
			}
			
			
		}
		
		pb_backupbuddy::status( 'details', 'Database restore finished importing all SQL files.' );
		return true;
	} // End restoreDatabase().
	
	
	
	/**
	 * Copies BUB settings from old options table over to new options table
	 * This function is not currently called if the options table was not included in the backup
	 */
	public function swapDatabaseBBSettings() {
		$this->_before( __FUNCTION__ );
		
		if ( 'deploy' != $this->_state['type'] ) {
			return $this->_error( 'This restore type `' . $this->_state['type'] . '` does not support this operation.' );
		}
		
		// Calculate temporary table prefixes.
		$newPrefix = 'bbnew-' . substr( $this->_state['serial'], 0, 4 ) . '_' . $this->_state['databaseSettings']['prefix']; // Incoming site.
		$oldPrefix = $this->_state['databaseSettings']['prefix']; // Current live site prefix.
		
		global $wpdb;
		
		// Get current BackupBuddy settings for current site.
		pb_backupbuddy::status( 'details', 'Copying BackupBuddy settings from options table prefixed with `' . $oldPrefix . '` to `' . $newPrefix . '`.' );
		$sql = "SELECT option_value FROM `{$oldPrefix}options` WHERE option_name='pb_backupbuddy';";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		if ( 0 == count( $results ) ) {
			return $this->_error( 'Error #8447347: Error getting current BackupBuddy settings. SQL Query: ' . htmlentities( $sql ) );
		}
		
		// Overwrite incoming site BackupBuddy settings in its temp table.
		if ( false === $wpdb->query( "UPDATE `{$newPrefix}options` SET option_value='" . backupbuddy_core::dbEscape( $results[0]['option_value'] ) . "' WHERE option_name='pb_backupbuddy';" ) ) {
			return $this->_error( 'Error #372837683: Unable to copy over BackupBuddy settings from live site to incoming database in temp table. Details: `' . $wpdb->last_error . '`.' );
		}
		
		// Get current iThemes Licensing for current site (if any).
		pb_backupbuddy::status( 'details', 'Copying iThemes Licensing data from options table prefixed with `' . $oldPrefix . '` to `' . $newPrefix . '`.' );
		$sql = "SELECT option_value FROM `{$oldPrefix}options` WHERE option_name='ithemes-updater-keys';";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		if ( count( $results ) > 0 ) {
			// Overwrite incoming site BackupBuddy settings in its temp table.
			if ( false === $wpdb->query( "UPDATE `{$newPrefix}options` SET option_value='" . backupbuddy_core::dbEscape( $results[0]['option_value'] ) . "' WHERE option_name='ithemes-updater-keys';" ) ) {
				pb_backupbuddy::status( 'error', 'Error #2379332: Unable to copy over iThemes Licenses from live site to incoming database in temp table. Details: `' . $wpdb->last_error . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'Maintained licensing data by copying it over incoming database.' );
			}
		}
		
		return true;
		
	} // End swapDatabaseBBSettings().
	
	
	/* swapDatabases()
	 *
	 * ROLLBACK
	 * Swap out the recently imported database tables with temp prefix for the live database.
	 * Sets maintenance mode during the swap, although it should be very brief.
	 *
	 */
	public function swapDatabases() {
		$this->_before( __FUNCTION__ );
		
		if ( ( 'rollback' != $this->_state['type'] ) && ( 'deploy' != $this->_state['type'] ) ) { // Restore mode used for restorying during deployment.
			$this->_error( 'This restore type `' . $this->_state['type'] . '` does not support this operation.' );
			return false;
		}
		
		// Turn on maintenance mode.
		if ( false === $this->maintenanceOn() ) {
			$this->_error( 'Could not enable maintenance mode.' );
			return false;
		}
		
		global $wpdb;
		
		// Calculate temporary table prefixes.
		$newPrefix = 'bbnew-' . substr( $this->_state['serial'], 0, 4 ) . '_'; // Temp prefix for holding the NEWly imported data.
		$oldPrefix = 'bbold-' . substr( $this->_state['serial'], 0, 4 ) . '_'; // Temp prefix for holding the OLD (currently live) data.
		
		// Get newly imported tables with the temp prefix.
		pb_backupbuddy::status( 'details', 'Checking for newly imported rollback tables with temp prefix `' . $newPrefix . '`.' );
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE '" . str_replace( '_', '\_', $newPrefix ) . "%' AND table_schema = DATABASE()";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		pb_backupbuddy::status( 'details', 'Found ' . count( $results ) . ' matching tables.' );
		if ( 0 == count( $results ) ) {
			$this->_error( 'Error getting tables or none found. SQL Query: ' . htmlentities( $sql ) );
			return false;
		}
		
		// Rename newly imported tables with temp prefix, renaming the existing live table first.
		pb_backupbuddy::status( 'details', 'Rename all existing tables with this temp prefix to prefix `' . $wpdb->prefix . '`.' );
		foreach( $results as $result ) {
			
			$newTableName = str_replace( $newPrefix, '', $wpdb->prefix . $result['table_name'] ); // the target new table name we are importing.
			$oldTableName = $oldPrefix . $newTableName; // the target name for the old table where we hold it in case it needs undoing.
			
			// Check if existing site already had this table. If so then we will need to rename it to a temp table name to allow for undoing.
			$sql = "SHOW TABLES LIKE '" . backupbuddy_core::dbEscape( $newTableName ) . "';";
			pb_backupbuddy::status( 'details', 'SQL: `' . $sql . '`.' );
			$results = $wpdb->get_results( $sql, ARRAY_A );
			if ( count( $results ) > 0 ) { // This table existed in the existing site so it needs to be renamed.
				pb_backupbuddy::status( 'details', 'Renaming table `' . $newTableName . '` (already exists in this site) to `' . $oldTableName . '`.' );
				if ( false === $wpdb->query( "RENAME TABLE `" . backupbuddy_core::dbEscape( $newTableName ) . "` TO `" . backupbuddy_core::dbEscape( $oldTableName ) . "`;" ) ) {
					$this->_error( 'Error #844389a: Unable to rename table `' . $newTableName . '` to `' . $oldTableName . '`. Details: `' . $wpdb->last_error . '`.' );
					return false;
				}
			} else {
				pb_backupbuddy::status( 'details', 'Table `' . $newTableName . '` did not already exist so NOT renaming to `' . $oldTableName . '`.' );
			}
			
			// Rename imported table to live prefix.
			pb_backupbuddy::status( 'details', 'Renaming incoming table `' . $result['table_name'] . '` to `' . $newTableName . '`.' );
			if ( false === $wpdb->query( "RENAME TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "` TO `" . backupbuddy_core::dbEscape( $newTableName ) . "`;" ) ) {
				$this->_error( 'Error #844389b: Unable to rename table `' . $result['table_name'] . '` to `' . $newTableName . '`. Details: `' . $wpdb->last_error . '`.' );
				return false;
			}
			
		} // end foreach.
		
		// Turn off maintenance mode.
		$this->maintenanceOff();
		
		return true;
	} // End
	
	
	
	/* finalizeRollback()
	 *
	 * ROLLBACK
	 * Finalize the rollback, deleting original tables & cleaning up temp files.
	 *
	 * @return true
	 */
	public function finalizeRollback() {
		$this->_before( __FUNCTION__ );
		
		global $wpdb;
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'bbold-" . substr( $this->_state['serial'], 0, 4 ) . "\_%' AND table_schema = DATABASE()";
		//echo $sql;
		$results = $wpdb->get_results( $sql, ARRAY_A );
		pb_backupbuddy::status( 'details', 'Found ' . count( $results ) . ' tables to drop.' );
		foreach( $results as $result ) {
			if ( false === $wpdb->query( "DROP TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "`" ) ) {
				$this->_error( 'Unable to delete old table `' . $result['table_name'] . '`.' );
			}
		}
		
		pb_backupbuddy::status( 'details', 'Deleting undo file.' );
		@unlink( ABSPATH . $this->_state['undoFile'] );
		pb_backupbuddy::status( 'details', 'Deleting temp files.' );
		pb_backupbuddy::$filesystem->unlink_recursive( $this->_state['tempPath'] );
		
		pb_backupbuddy::status( 'details', 'Finished finalize function.' );
		return true;
	} // end finalizeRollback().
	
	
	
	public function restoreFiles() {
		$this->_before( __FUNCTION__ );
		
		// Zip & Unzip library setup.
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( $this->_state['restoreFileRoot'], array(), 'unzip' );
		
		if ( ! file_exists( $this->_state['archive'] ) ) {
			pb_backupbuddy::status( 'error', 'Unable to find specified backup archive `' .  $this->_state['archive'] . '`' );
			return false;
		}
		
		pb_backupbuddy::status( 'message', 'Unzipping archive `' . $this->_state['archive'] . '` into `' . $this->_state['restoreFileRoot'] . '`' );
		
		// Set compatibility mode if defined in advanced options.
		if ( 'all' == $this->_state['zipMethodStrategy'] ) {
			$compatibilityMode = false;
		} else {
			$compatibilityMode = $this->_state['zipMethodStrategy'];
		}
		
		// Extract zip file & verify it worked.
		if ( true !== ( $result = pb_backupbuddy::$classes['zipbuddy']->unzip( $this->_state['archive'], $this->_state['restoreFileRoot'], $compatibilityMode ) ) ) {
			pb_backupbuddy::status( 'error', 'Failure extracting backup archive.' );
			return false;
		} else {
			pb_backupbuddy::status( 'details', 'Success extracting backup archive.' );
			return true;
		}
		
	} // End restoreFiles().
	
	
	
	/* _priorRollbackCleanup()
	 *
	 * ROLLBACK
	 * Cleans up any existing temp database tables that exist from a prior failed/incomplete rollback that need removed.
	 *
	 */
	private function _priorRollbackCleanup() {
		$this->_before( __FUNCTION__ );
		
		pb_backupbuddy::status( 'details', 'Checking for any prior failed rollback data to clean up.' );
		global $wpdb;
		
		$shortSerial = substr( $this->_state['serial'], 0, 4 );
		
		// NEW prefix
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'bbnew-" . $shortSerial . "\_%' AND table_schema = DATABASE()";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		pb_backupbuddy::status( 'details', 'Found ' . count( $results ) . ' tables to drop with the prefix `bbnew-' . $shortSerial . '_`.' );
		$dropCount = 0;
		foreach( $results as $result ) {
			if ( false === $wpdb->query( "DROP TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "`" ) ) {
				$this->_error( 'Unable to delete table `' . $result['table_name'] . '`.' );
			} else {
				$dropCount++;
			}
		}
		pb_backupbuddy::status( 'details', 'Dropped `' . $dropCount . '` new tables.' );
		
		// OLD prefix
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'bbold-" . $shortSerial . "\_%' AND table_schema = DATABASE()";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		pb_backupbuddy::status( 'details', 'Found ' . count( $results ) . ' tables to drop with the prefix `bbold-' . $shortSerial . '_`.' );
		$dropCount = 0;
		foreach( $results as $result ) {
			if ( false === $wpdb->query( "DROP TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "`" ) ) {
				$this->_error( 'Unable to delete table `' . $result['table_name'] . '`.' );
			} else {
				$dropCount++;
			}
		}
		pb_backupbuddy::status( 'details', 'Dropped `' . $dropCount . '` old tables.' );
		
		pb_backupbuddy::status( 'details', 'Finished prior rollback cleanup.' );
	} // end.
	
	
	
	/* maintenanceOn()
	 *
	 * Turn ON WordPress maintenance mode.
	 *
	 */
	public function maintenanceOn() {
		$this->_before( __FUNCTION__ );
		
		// Turn on maintenance mode.
		pb_backupbuddy::status( 'details', 'Turning on maintenance mode on.' );
		if ( ! file_exists( ABSPATH . '.maintenance' ) ) {
			$maintenance_result = @file_put_contents( ABSPATH . '.maintenance', "<?php header('HTTP/1.1 503 Service Temporarily Unavailable'); header('Status: 503 Service Temporarily Unavailable'); header('Retry-After: 3600'); die( 'Site undergoing maintenance.' );" );
			if ( false === $maintenance_result ) {
				$this->_error( '.maintenance file unable to be generated to prevent viewing.' );
				return false;
			} else {
				pb_backupbuddy::status( 'details', '.maintenance file generated to prevent viewing partially migrated site.' );
			}
		} else {
			pb_backupbuddy::status( 'details', '.maintenance file already exists. Skipping creation.' );
		}
		return true;
	} // End maintenanceOn().
	
	
	
	/* maintenanceOff()
	 *
	 * Turn OFF WordPress maintenance mode.
	 *
	 */
	public function maintenanceOff( $onlyDeleteOurFile = false ) {
		$this->_before( __FUNCTION__ );
		
		pb_backupbuddy::status( 'details', 'Turn off maintenance mode off if on.' );
		if ( file_exists( ABSPATH . '.maintenance' ) ) {
			
			if ( false === $onlyDeleteOurFile ) {
				pb_backupbuddy::status( 'details', '.maintenance file exists. Deleting...' );
				if ( false === @unlink( ABSPATH . '.maintenance' ) ) {
					$this->_error( 'Unable to delete .maintenance file.' );
				}
			} else { // See if ImportBuddy created it before deleting.


				pb_backupbuddy::status( 'details', '.maintenance file exists. Checking to see if ImportBuddy generated it.' );
				$maintenance_contents = @file_get_contents( ABSPATH . '.maintenance' );
				if ( false === $maintenance_contents ) { // Cannot read.
					pb_backupbuddy::status( 'error', '.maintenance file unreadable. You may need to manually delete it to view your site.' );
				} else { // Read file succeeded.
					if ( trim( $maintenance_contents ) == "<?php header('HTTP/1.1 503 Service Temporarily Unavailable'); header('Status: 503 Service Temporarily Unavailable'); header('Retry-After: 3600'); die( 'Site undergoing maintenance.' );" ) { // Our file. Delete it!
						$maintenance_unlink = @unlink( ABSPATH . '.maintenance' );
						if ( true === $maintenance_unlink ) {
							pb_backupbuddy::status( 'details', 'Temporary .maintenance file created by ImportBuddy successfully deleted.' );
						} else {
							pb_backupbuddy::status( 'error', 'Unable to delete temporary .maintenance file.  This is likely due to permissions. You may need to manually delete it to view your site.' );
						}
					} else { // Not our file. Leave alone. We will warn about this later though.
						pb_backupbuddy::status( 'details', '.maintenance file not generated by ImportBuddy. Leaving as is. You may need to delete it to view your site.' );
					}
				}
				
				
			}
			
			
		} else {
			pb_backupbuddy::status( 'details', '.maintenance file does not exist.' );
		}
	} // End maintenanceOff().



	/**
	 *	migrateWpConfig()
	 *
	 *	@return		true on success, new wp config file content on failure.
	 */
	function migrateWpConfig() {
		if ( isset( $this->_state['dat']['wp-config_in_parent'] ) ) {
			if ( $this->_state['dat']['wp-config_in_parent'] === true ) { // wp-config.php used to be in parent. Must copy from temp dir to root.
				pb_backupbuddy::status( 'details', 'DAT file indicates wp-config.php was previously in the parent directory. Copying into site root.' );

				$config_source = ABSPATH . 'wp-content/uploads/backupbuddy_temp/' . $this->_state['serial'] . '/wp-config.php';
				$result = copy( $config_source, ABSPATH . 'wp-config.php' );
				if ( $result === true ) {
					pb_backupbuddy::status( 'message', 'wp-config.php file was restored to the root of the site `' . ABSPATH . 'wp-config.php`. It was previously in the parent directory of the source site. You may move it manually to the parent directory.' );
				} else {
					pb_backupbuddy::status( 'error', 'Unable to move wp-config.php file from temporary location `' . $config_source . '` to root.' );
				}

			} else { // wp-config.php was in normal location on source site. Nothing to do.
				pb_backupbuddy::status( 'details', 'DAT file indicates wp-config.php was previously in the normal location.' );
			}
		} else { // Pre 3.0 backup
			pb_backupbuddy::status( 'details', 'Backup pre-v3.0 so wp-config.php must be in normal location.' );
		}

		if ( 'files' == $this->_state['dat']['backup_type'] ) {
			pb_backupbuddy::status( 'details', 'Skipping update of Database Settings and URLs in wp-config.php as this is a Files Only Backup.' );
			$migrateResult = true;
		} else {
			pb_backupbuddy::status( 'details', 'Updating Database Settings and URLs in wp-config.php as this is not a Files Only Backup.' );
			$migrateResult = $this->_migrateWpConfigGruntwork();
		}
		return $migrateResult;
	} // End migrateWpConfig().



	/**
	 *	_migrateWpConfigGruntwork()
	 *
	 *	Migrates and updates the wp-config.php file contents as needed.
	 *
	 *	@return			true|string			True on success. On false returns the new wp-config file content.
	 */
	function _migrateWpConfigGruntwork() {
		pb_backupbuddy::status( 'message', 'Starting migration of wp-config.php file...' );

		pb_backupbuddy::flush();

		$configFile = ABSPATH . 'wp-config.php';
		pb_backupbuddy::status( 'details', 'Config file: `' . $configFile . '`.' );

		if ( file_exists( $configFile ) ) {
			// Useful REGEX site: http://gskinner.com/RegExr/

			$updated_home_url = false;
			$wp_config = array();
			$lines = file( $configFile );

			$patterns = array();
			$replacements = array();

			/*
			Update WP_SITEURL, WP_HOME if they exist.
			Update database DB_NAME, DB_USER, DB_PASSWORD, and DB_HOST.
			RegExp: /define\([\s]*('|")WP_SITEURL('|"),[\s]*('|")(.)*('|")[\s]*\);/gi
			pattern: define\([\s]*('|")WP_SITEURL('|"),[\s]*('|")(.)*('|")[\s]*\);
			*/
			$pattern[0] = '/define\([\s]*(\'|")WP_SITEURL(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[0] = "define( 'WP_SITEURL', '" . trim( $this->_state['siteurl'], '/' ) . "' );";
			pb_backupbuddy::status( 'details', 'wp-config.php: Setting WP_SITEURL (if applicable) to `' . trim( $this->_state['siteurl'], '/' ) . '`.' );
			$pattern[1] = '/define\([\s]*(\'|")WP_HOME(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[1] = "define( 'WP_HOME', '" . trim( $this->_state['homeurl'], '/' ) . "' );";
			pb_backupbuddy::status( 'details', 'wp-config.php: Setting WP_HOME (if applicable) to `' . trim( $this->_state['homeurl'], '/' ) . '`.' );

			$pattern[2] = '/define\([\s]*(\'|")DB_NAME(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[2] = "define( 'DB_NAME', '" . $this->_state['databaseSettings']['database'] . "' );";
			$pattern[3] = '/define\([\s]*(\'|")DB_USER(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[3] = "define( 'DB_USER', '" . $this->_state['databaseSettings']['username'] . "' );";
			$pattern[4] = '/define\([\s]*(\'|")DB_PASSWORD(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[4] = "define( 'DB_PASSWORD', '" . $this->_preg_escape_back( $this->_state['databaseSettings']['password'] ) . "' );";
			$pattern[5] = '/define\([\s]*(\'|")DB_HOST(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
			$replace[5] = "define( 'DB_HOST', '" . $this->_state['databaseSettings']['server'] . "' );";
			
			// If multisite, update domain.
			if ( isset( pb_backupbuddy::$options['domain'] ) && ( pb_backupbuddy::$options['domain'] != '' ) ) {
				$pattern[6] = '/define\([\s]*(\'|")DOMAIN_CURRENT_SITE(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
				$replace[6] = "define( 'DOMAIN_CURRENT_SITE', '" . $this->_state['defaultDomain'] . "' );";
				pb_backupbuddy::status( 'details', 'wp-config.php: Setting DOMAIN_CURRENT_SITE (if applicable) to `' . $this->_state['databaseSettings']['defaultDomain'] . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'wp-config.php did not update DOMAIN_CURRENT_SITE as it was blank.' );
			}
			/*
			Update table prefix.
			RegExp: /\$table_prefix[\s]*=[\s]*('|")(.)*('|");/gi
			pattern: \$table_prefix[\s]*=[\s]*('|")(.)*('|");
			*/
			$pattern[7] = '/\$table_prefix[\s]*=[\s]*(\'|")(.)*(\'|");/i';
			$replace[7] = '$table_prefix = \'' . $this->_state['databaseSettings']['prefix'] . '\';';
			
			if ( isset( $this->_state['dat']['wp_content_url'] ) ) {
				$new_content_url = str_replace( trim( $this->_state['dat']['siteurl'], '\\/' ), trim( $this->_state['siteurl'], '/' ), $this->_state['dat']['wp_content_url'] );
				$pattern[8] = '/define\([\s]*(\'|")WP_CONTENT_URL(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
				$replace[8] = "define( 'WP_CONTENT_URL', '" . $new_content_url . "' );";
				pb_backupbuddy::status( 'details', 'wp-config.php: Setting WP_CONTENT_URL (if applicable) to `' . $new_content_url . '`.' );
			}
			if ( isset( $this->_state['dat']['wp_content_dir'] ) ) {
				$new_content_dir = str_replace( $this->_state['dat']['abspath'], ABSPATH, $this->_state['dat']['wp_content_dir'] );
				$pattern[9] = '/define\([\s]*(\'|")WP_CONTENT_DIR(\'|"),[\s]*(\'|")(.)*(\'|")[\s]*\);/i';
				$replace[9] = "define( 'WP_CONTENT_DIR', '" . $new_content_dir . "' );";
				pb_backupbuddy::status( 'details', 'wp-config.php: Setting WP_CONTENT_DIR (if applicable) to `' . $new_content_dir . '`.' );
			}

			// Perform the actual replacement.
			$lines = preg_replace( $pattern, $replace, $lines );

			// Check that we can write to this file.
			if ( ! is_writable( $configFile ) ) {
				pb_backupbuddy::status( 'warning', 'Warning #28572: wp-config.php shows to be unwritable. Attempting to override permissions temporarily.' );
				$oldPerms = ( fileperms( $configFile ) & 0777 );
				@chmod( $configFile, 0644 ); // Try to make writable.
			}

			// Write changes to config file.
			if ( false === ( file_put_contents( $configFile, $lines ) ) ) {
				pb_backupbuddy::alert( 'ERROR #84928: Unable to save changes to wp-config.php. Verify this file has proper write permissions. You may need to manually edit it.', true, '9020' );
				return implode( "\n", $lines );
			}

			// Restore prior permissions if applicable.
			if ( isset( $oldPerms ) ) {
				@chmod( $configFile, $oldPerms );
			}

			unset( $lines );
		} else {
			pb_backupbuddy::status( 'details', 'Warning: wp-config.php file not found.' );
			//pb_backupbuddy::alert( 'Note: wp-config.php file not found. This is normal for a database only backup.' );
		}

		pb_backupbuddy::status( 'message', 'Migration of wp-config.php complete.' );

		return true;
	} // End _migrateWpConfigGruntwork().
	
	
	
	/*	_preg_escape_back()
	 *	
	 *	Escape backreferences from string for use with regex. Used by migrate_wp_config().
	 *	@see migrate_wp_config()
	 *	
	 *	@param		string		$string		String to escape.
	 *	@return		string					Escaped string.
	 */
	function _preg_escape_back($string) {
		// Replace $ with \$ and \ with \\
		$string = preg_replace('#(?<!\\\\)(\\$|\\\\)#', '\\\\$1', $string);
		return $string;
	} // End _preg_escape_back().
	
	
	
	/* troubleScan()
	 *
	 * Scans for potential problems and provided informative warnings.
	 *
	 * @return array Array of text warnings to display to user.
	 *
	 */
	function troubleScan() {
		$trouble = array();

		// .maintenance
		if ( file_exists( ABSPATH . '.maintenance' ) ) {
			$trouble[] = '.maintenance file found in WordPress root. The site may not be accessible unless this file is deleted.';
		}

		// index.htm
		if ( file_exists( ABSPATH . 'index.htm' ) ) {
			$trouble[] = 'index.htm file found in WordPress root. This may prevent WordPress from loading on some servers. Solution: Delete the file.';
		}

		// index.html
		if ( file_exists( ABSPATH . 'index.html' ) ) {
			$trouble[] = 'index.html file found in WordPress root. This may prevent WordPress from loading on some servers. Solution: Delete the file.';
		}

		// wp-config.php
		if ( ! file_exists( ABSPATH . 'wp-config.php' ) ) {
			$trouble[] = 'Warning only: wp-config.php file not found WordPress root. <i>If this is a database-only restore you should restore a full backup.</i>';
		} else { // wp-config.php exists so check for unchanged URLs not updated due to provenance unknown.

			if ( 'files' == $this->_state['dat']['backup_type'] ) {
				pb_backupbuddy::status( 'details', 'Skipping URL scan for wp-config.php as this is a Files Only restore.' );
			} else {
				pb_backupbuddy::status( 'details', 'Checking wp-config.php file for unchanged URLs.' );
				$config_contents = @file_get_contents( ABSPATH . 'wp-config.php' );
				if ( false === $config_contents ) { // Unable to open.
					pb_backupbuddy::status( 'error', 'Unable to open wp-config.php for checking though it exists. Verify permissions.' );
				} else { // Able to open.
					$new_content_url = '';
					if ( isset( $this->_state['dat']['wp_content_url'] ) ) {
						$new_content_url = str_replace( trim( $this->_state['dat']['siteurl'], '\\/' ), trim( $this->_state['siteurl'], '/' ), $this->_state['dat']['wp_content_url'] );
					}
					
					preg_match_all( '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $config_contents, $matches );
					$matches = $matches[0];
					foreach( $matches as $match ) {
						if ( false !== stristr( $match, 'api.wordpress.org' ) ) {
							continue;
						}
						if ( false !== stristr( $match, 'codex.wordpress.org' ) ) {
							continue;
						}
						if ( $match == $new_content_url ) { // Ignore new WP_CONTENT_URL define that was updated.
							continue;
						}
						$trouble[] = 'A URL found in one or more locations in wp-config.php was not migrated as it was either not recognized or in an unrecognized location in the file: "' . htmlentities( $match ) . '".';
					}

					if ( false !== stristr( $config_contents, 'COOKIE_DOMAIN' ) ) { // Found cookie domain.
						$trouble[] = 'Cookie domain set in wp-config.php file and has not been updated. You may need to manualy update this.';
					}
				}
			}
		}

		// .htaccess
		if ( ! file_exists( ABSPATH . '.htaccess' ) ) {
			$trouble[] = 'Warning only: .htaccess file not found in WordPress root. This is used for permalinks on servers which support it. If needed or URLs result in a 404 you may regenerate this file by logging into the wp-admin & navigating to Settings: Permalinks and clicking "Save".';
		} else { // Exists, check if AddHandler inside.
			$contents = @file_get_contents( ABSPATH . '.htaccess' );
			if ( strstr( $contents, 'AddHandler' ) ) {
				$trouble[] = 'Warning: An AddHandler directive has been found in your .htaccess file. This could result in WordPress and PHP not running properly if configured improperly, especially when migrating to a new server. If you encounter problems such as an Internal Server Error or Error 500, try removing this line from your .htaccess file. Solution: Delete this AddHandler line from the .htaccess file. <a target="_blank" href="http://ithemes.com/codex/page/BackupBuddy:_Frequent_Support_Issues#.htaccess_404_.2F_Addhandler_Warning">Click here for more information & help.</a>';
			}
		}

		// php.ini
		if ( file_exists( ABSPATH . 'php.ini' ) ) {
			$trouble[] = 'A php.ini file was restored in the import process in the site root. This may cause problems with site functionality if imported to a different server as configuration options often differ between servers, possibly resulting in degraded performance or unexpected behavior.';
		}

		if ( count( $trouble ) > 0 ) {
			//pb_backupbuddy::status( 'warning', 'Potential problems that may need your attention: ' . implode( '; ', $trouble ) );
		} else {
			pb_backupbuddy::status( 'details', 'No potential problems detected.' );
		}

		return $trouble;

	} // End troubleScan().
	
	
	
	/* scrubIndexFiles()
	 *
	 * Deletes index.htm file if it appears to have the contents that ImportBuddy created it with.
	 * Non-importbuddy created index.htm files are left in place to be warned about later as the user may want it there.
	 *
	 */
	function scrubIndexFiles() {
		$this->_before( __FUNCTION__ );
		
		$indexFiles = array( 'index.htm', 'index.html' );
		foreach( $indexFiles as $indexFile ) {
			if ( file_exists( ABSPATH . $indexFile ) ) {
				pb_backupbuddy::status( 'details', $indexFile . ' file exists. Checking to see if ImportBuddy generated it or it is empty.' );
				$index_contents = @file_get_contents( ABSPATH . $indexFile );
				if ( false === $index_contents ) { // Cannot read.
					pb_backupbuddy::status( 'error', $indexFile . ' file unreadable. You may need to manually delete it to view your site.' );
				} else { // Read file succeeded.
					$index_contents = trim( $index_contents );
					if ( ( $index_contents == '<html></html>' ) || ( '' == $index_contents ) ) { // Our file. Delete it!
						$index_unlink = @unlink( ABSPATH . $indexFile );
						if ( true === $index_unlink ) {
							pb_backupbuddy::status( 'details', $indexFile . ' file successfully deleted.' );
						} else {
							pb_backupbuddy::status( 'error', 'Unable to delete ' . $indexFile . ' file.  This is likely due to permissions. You may need to manually delete it to view your site.' );
						}
					} else { // Not our file. Leave alone. We will warn about this later though.
						pb_backupbuddy::status( 'details', $indexFile . ' file not generated by ImportBuddy and not empty. Leaving as is. You may need to delete it to view your site.' );
					}
				}
			} else { // No index.htm file.
				pb_backupbuddy::status( 'details', $indexFile . ' file not found. Skipping deletion.' );
			}
		}
		return true;
	} // End scrubIndexFiles().
	
	
	
	/*	renameHtaccessTemp()
	 *	
	 *	Renames .htaccess to .htaccess.bb_temp until last ImportBuddy step to avoid complications.
	 *	
	 *	@return		null
	 */
	function renameHtaccessTemp() {
		$this->_before( __FUNCTION__ );
		
		if ( !file_exists( ABSPATH . '.htaccess' ) ) {
			pb_backupbuddy::status( 'details', 'No .htaccess file found. Skipping temporary file rename.' );
		}
		
		$result = @rename( ABSPATH . '.htaccess', ABSPATH . '.htaccess.bb_temp' );
		if ( $result === true ) { // Rename succeeded.
			pb_backupbuddy::status( 'message', 'Renamed `.htaccess` file to `.htaccess.bb_temp` until final ImportBuddy step.' );
		} else { // Rename failed.
			pb_backupbuddy::status( 'warning', 'Unable to rename `.htaccess` file to `.htaccess.bb_temp`. Your file permissions may be too strict. You may wish to manually rename this file and/or check permissions before proceeding.' );
		}
		return true;
		
	} // End renameHtaccessTemp().



	/*	renameHtaccessTempBack()
	 *	
	 *	Renames .htaccess to .htaccess.bb_temp until last ImportBuddy step to avoid complications.
	 *	
	 *	@return		null
	 */
	function renameHtaccessTempBack() {
		$this->_before( __FUNCTION__ );
		
		$tempFile = ABSPATH . '.htaccess.bb_temp';
		$finalFile = ABSPATH . '.htaccess';
		
		if ( ! file_exists( $tempFile ) ) {
			pb_backupbuddy::status( 'details', 'No `.htaccess.bb_temp` file found. Skipping temporary file rename.' );
			return;
		}

		$result = @rename( $tempFile, $finalFile );
		if ( $result === true ) { // Rename succeeded.
			pb_backupbuddy::status( 'message', 'Renamed `.htaccess.bb_temp` file to `.htaccess` until final ImportBuddy step.' );
		} else { // Rename failed.
			pb_backupbuddy::status( 'error', 'Unable to rename `.htaccess.bb_temp` file to `.htaccess`. Your file permissions may be too strict. You may wish to manually rename .htaccess.bb_temp at `' . $tempFile . '` to .htaccess.' );
		}
		
		return;
	} // End renameHtaccessTempBack().
	
	
	
	/**
	 *	migrateHtaccess()
	 *
	 *	Migrates .htaccess.bb_temp file if it exists.
	 *
	 *	@return		boolean		False only if file is unwritable. True if write success; true if file does not even exist.
	 *
	 */
	function migrateHtaccess() {

		$htaccessFile = ABSPATH . '.htaccess.bb_temp';

		// If no .htaccess.bb_temp file exists then create a basic default one then migrate that as needed. @since 2.2.32.
		if ( ! file_exists( $htaccessFile ) ) {
			pb_backupbuddy::status( 'message', 'No `' . basename( $htaccessFile ) . '` file found. Creating basic default .htaccess.bb_temp file (to be later renamed to .htaccess).' );

			// Default .htaccess file.
			$htaccess_contents =
				"# BEGIN WordPress\n
				<IfModule mod_rewrite.c>\n
				RewriteEngine On\n
				RewriteBase /\n
				RewriteRule ^index\\.php$ - [L]\n
				RewriteCond %{REQUEST_FILENAME} !-f\n
				RewriteCond %{REQUEST_FILENAME} !-d\n
				RewriteRule . /index.php [L]\n
				</IfModule>\n
				# END WordPress\n";
			file_put_contents( $htaccessFile, $htaccess_contents );
			unset( $htaccess_contents );
		}

		pb_backupbuddy::status( 'message', 'Migrating `' . basename( $htaccessFile ) . '` file...' );

		$oldurl = strtolower( $this->_state['dat']['siteurl'] );
		$oldurl = str_replace( '/', '\\', $oldurl );
		$oldurl = str_replace( 'http:\\', '', $oldurl );
		$oldurl = trim( $oldurl, '\\' );
		$oldurl = explode( '\\', $oldurl );
		$oldurl[0] = '';

		$old_path = implode( '/', $oldurl );

		$newurl = strtolower( $this->_state['siteurl'] );
		$newurl = str_replace( '/', '\\', $newurl );
		$newurl = str_replace( 'http:\\', '', $newurl );
		$newurl = trim( $newurl, '\\' );
		$newurl = explode( '\\', $newurl );
		$newurl[0] = '';

		pb_backupbuddy::status( 'message', 'Checking `' . basename( $htaccessFile ) . '` file.' );
		if ( $newurl !== $oldurl ) {
			pb_backupbuddy::status( 'message', 'URL directory has changed. Updating from `' . implode( '/', $oldurl ) . '` to `' . implode( '/', $newurl ) . '`.' );
		}
		
		$rewrite_lines = array();
		$got_rewrite = false;
		$rewrite_path = implode( '/', $newurl );
		$file_array = file( $htaccessFile );
		$htaccessNeedsUpdating = false;
		
		// Loop through .htaccess lines, updating as needed.
		foreach ( (array)$file_array as $line_number => $line) {
			if ( $got_rewrite == true ) { // In a WordPress section.
				if ( strstr( $line, 'END WordPress' ) ) { // End of a WordPress block so stop replacing.
					$got_rewrite = false;
					$rewrite_lines[] =  $line; // Captures end of WordPress block.
				} else {
					
					if ( $newurl !== $oldurl ) {
						if ( strstr( $line, 'RewriteBase' ) ) { // RewriteBase
							$rewrite_lines[] = 'RewriteBase ' . $rewrite_path . '/' . "\n";
							$htaccessNeedsUpdating = true;
						} elseif ( strstr( $line, 'RewriteRule' ) ) { // RewriteRule
							if ( strstr( $line, '^index\.php$' ) ) { // Handle new strange rewriterule. Leave as is.
								$rewrite_lines[] = $line;
								pb_backupbuddy::status( 'details', '.htaccess ^index\.php$ detected. Leaving as is.' );
							} elseif ( ! strstr( $line, 'RewriteRule . ') ) {
								// Handle what is probably a user generated rule - better detection needed.
								$new_line = str_replace( $old_path, $rewrite_path, $line );
								$rewrite_lines[] = $new_line;
								$htaccessNeedsUpdating = true;
								if ( $new_line != $line ) {
									pb_backupbuddy::status( 'message', '.htaccess line changed from `' . trim( $line ) . '` to `' . trim( $new_line ) . '`.' );
								}
							} else { // Normal spot.
								$rewrite_lines[] = 'RewriteRule . ' . $rewrite_path . '/index.php' . "\n";
								$htaccessNeedsUpdating = true;
							}
						} else { // User custom rewriterule we did not update.
							
							$rewrite_lines[] =  $line; // Captures everything inside WordPress block we arent modifying.
							if ( false !== strstr( $line, 'RewriteRule . ') ) { // RewriteRule, warn user potentially if path may need changed.
								if ( $old_path !== $rewrite_path ) {
									pb_backupbuddy::status( 'warning', 'User-defined RewriteRule found and WordPress path has changed so this rule MAY need manually updated by you to function properly.  Line: "' . $line . '".' );
								}
							}
							
						}
					}
					
					
					
				}
			} else { // Outside a WordPress section.
				if ( strstr( $line, 'BEGIN WordPress' ) ) {
					$got_rewrite = true; // Beginning of a WordPress block so start replacing.
				}
				if ( strstr( $line, 'AddHandler' ) ) { // Has AddHandler. Skip keeping this line -- if importbuddy is running now then we do not need this most likely.
					$message = 'An AddHandler directive has been found in your .htaccess file. This could result in WordPress and PHP not running properly if configured improperly, especially when migrating to a new server. If you encounter problems such as an Internal Server Error or Error 500, try removing this line from your .htaccess file.  Line: "' . $line . '".';
					pb_backupbuddy::status( 'warning', $message );
					pb_backupbuddy::alert( $message );
				} else {
					$rewrite_lines[] =  $line; // Captures everything outside of WordPress block (except addhandler stuff).
				}
			}
		} // end foreach.
		
		// If the URL (domain and/or URL subdirectory ) has changed, then need to update .htaccess.bb_temp file.
		
		
		
		if ( true === $htaccessNeedsUpdating ) {
			// Check that we can write to this file (if it already exists).
			if ( file_exists( $htaccessFile ) && ( ! is_writable( $htaccessFile ) ) ) {
				pb_backupbuddy::status( 'warning', 'Warning #28573: Temp `' . basename( $htaccessFile ) . '` file shows to be unwritable. Attempting to override permissions temporarily.' );
				$oldPerms = ( fileperms( $htaccessFile ) & 0777 );
				@chmod( $htaccessFile, 0644 ); // Try to make writable.
				// Check if still not writable...
				if ( ! is_writable( $htaccessFile ) ) {
					pb_backupbuddy::status( 'error', 'Error #9020: Unable to write to `' . basename( $htaccessFile ) . '` file. Verify permissions.' );
					pb_backupbuddy::alert( 'Warning: Unable to write to temporary .htaccess file. Verify this file has proper write permissions. You may receive 404 Not Found errors on your site if this is not corrected. To fix after migration completes: Log in to your WordPress admin and select Settings: Permalinks from the left menu then save. To manually update, copy/paste the following into your .htaccess file: <textarea>' . implode( $rewrite_lines ) . '</textarea>', '9020' );
					return false;
				}
			}

			$handling = fopen( $htaccessFile, 'w');
			fwrite( $handling, implode( $rewrite_lines ) );
			fclose( $handling );
			unset( $handling );

			// Restore prior permissions if applicable.
			if ( isset( $oldPerms ) ) {
				@chmod( $htaccessFile, $oldPerms );
			}

			pb_backupbuddy::status( 'message', 'Migrated `' . basename( $htaccessFile ) . '` file. It will be renamed back to `.htaccess` on the final step.' );
		} else {
			pb_backupbuddy::status( 'message', 'No changes needed for `' . basename( $htaccessFile ) . '` file.' );
		}

		return true;
	} // End migrateHtaccess().
	
	
	
	function getDefaultUrl() {
		$this->_before( __FUNCTION__ );
		
		// Get the current URL of where the importbuddy tool is running.
		$url = str_replace( $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'] );
		$url = str_replace( basename( $url ) , '', $url );
		$url = preg_replace( '|/*$|', '', $url );  // strips trailing slash(es).
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $url;
		
		return $url;
	} // End getDefaultUrl().
	
	
	
	function getDefaultDomain() {
		$this->_before( __FUNCTION__ );
		
		preg_match("/^(http:\/\/)?([^\/]+)/i", $this->getDefaultUrl(), $domain );
		return $domain[2];
	} // End getDefaultDomain().
	
	
	
	/**
	 *	connectDatabase()
	 *
	 *	Initializes a connection to the mysql database.
	 *	REQUIRES: databaseSettings portion of state to be set.
	 *
	 *	@return		boolean		True on success; else false. Success testing is very loose.
	 */
	function connectDatabase() {
		$this->_before( __FUNCTION__ );
		
		global $wpdb;
		$wpdb = new wpdb( $this->_state['databaseSettings']['username'], $this->_state['databaseSettings']['password'], $this->_state['databaseSettings']['database'],$this->_state['databaseSettings']['server'] );
		
		// See if we have a specified character set and collation to use from the source site.
		$charset = null;
		$collate = null;
		if ( isset( $this->_state['dat']['db_charset'] ) ) {
			$charset = $this->_state['dat']['db_charset'];
		}
		if ( isset( $this->_state['dat']['db_collate'] ) ) {
			$collate = $this->_state['dat']['db_collate'];
		}
		if ( ( null !== $charset ) || ( null !== $collate ) ) {
			pb_backupbuddy::status( 'details', 'Setting charset to `' . $charset . '` and collate to `' . $collate . '` based on source site.' );
			$wpdb->set_charset( $wpdb->dbh, $charset, $collate );
		} else {
			pb_backupbuddy::status( 'details', 'Charset nor collate are in DAT file. Using defaults for database connection.' );
			pb_backupbuddy::status( 'details', 'Charset in wpdb: ' . $wpdb->charset );
		}
		
		// Warn if mysql versions are incompatible; eg importing a mysql < 5.1 version into a server running 5.1+.
		global $wpdb;
		if ( isset( $this->_state['dat']['db_version'] ) ) {
			$incomingVersion = $this->_state['dat']['db_version'];
		}
		$thisVersion = $wpdb->db_version();
		pb_backupbuddy::status( 'details', 'Incoming mysql version: `' . $incomingVersion . '`. This server\'s mysql version: `' . $thisVersion . '`.' );
		if ( version_compare( $incomingVersion, '5.1.0', '<' ) && version_compare( $thisVersion, '5.1.0', '>=' ) ) {
			pb_backupbuddy::status( 'warning', 'Error #7001: This server\'s mysql version, `' . $thisVersion . '` may have SQL query incompatibilities with the backup mysql version `' . $incomingVersion . '`. This may result in #9010 errors due to syntax of TYPE= changing to ENGINE=. If none occur you may ignore this error.' );
		}
		
		
		return true;
	} // End connectDatabase().
	
	
	
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
	}
	
	
	
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
		pb_backupbuddy::status( 'details', 'Getting rollback state.' );
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
		$this->_state['stepHistory'][] = array( 'function' => $functionName, 'start' => time() );
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
	
	
	
	function _array_replace_recursive($array, $array1) {
		function recurse($array, $array1)
		{
		  foreach ($array1 as $key => $value)
		  {
		    // create new key in $array, if it is empty or not an array
		    if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key])))
		    {
		      $array[$key] = array();
		    }

		    // overwrite the value in the base array
		    if (is_array($value))
		    {
		      $value = recurse($array[$key], $value);
		    }
		    $array[$key] = $value;
		  }
		  return $array;
		}

		// handle the arguments, merge one by one
		$args = func_get_args();
		$array = $args[0];
		if (!is_array($array))
		{
		  return $array;
		}
		for ($i = 1; $i < count($args); $i++)
		{
		  if (is_array($args[$i]))
		  {
		    $array = recurse($array, $args[$i]);
		  }
		}
		return $array;
	}
	
	
	
} // end class.

