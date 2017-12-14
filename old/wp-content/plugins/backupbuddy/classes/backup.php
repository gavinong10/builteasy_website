<?php
/*	pb_backupbuddy_backup class
 *	
 *	Handles the actual backup procedures.
 *	
 *	USED BY:
 *
 *	1) Full & DB backups
 *	2) Multisite backups & exports
 *
 */
class pb_backupbuddy_backup {
	
	private $_errors = array();								// TODO:  No longer used? Remove?
	private $_status_logging_started = false;				// Marked true once anything has been status logged during this process. Used by status().
	private $_currentStepIndex = '';						// When running a backup, this is the current index in the steps array within the fileoptions.
	
	// Constants for Zip Build Strategy - here for now but will be moved to central file
	const ZIP_BUILD_STRATEGY_SBSS = 2;						// Single-Burst/Single-Step
	const ZIP_BUILD_STRATEGY_MBSS = 3;						// Muti-Burst/Single-Step
	const ZIP_BUILD_STRATEGY_MBMS = 4;						// Multi-Burst/Multi-Step
	const ZIP_BUILD_STRATEGY_MIN = self::ZIP_BUILD_STRATEGY_SBSS;
	const ZIP_BUILD_STRATEGY_MAX = self::ZIP_BUILD_STRATEGY_MBMS;
	
	
	
	/*	__construct()
	 *	
	 *	Default contructor. Initialized core and zipbuddy classes.
	 *	
	 *	@return		null
	 */
	function __construct() {
		
		// Load core if it has not been instantiated yet.
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		// Load zipbuddy if it has not been instantiated yet.
		if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
			pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		}
		
		// Register PHP shutdown function to help catch and log fatal PHP errors during backup.
		register_shutdown_function( array( &$this, 'shutdown_function' ) );
		
	} // End __construct().
	
	
	
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
		
		
		// Calculate log directory.
		$log_directory = backupbuddy_core::getLogDirectory();
		$main_file = $log_directory . 'log-' . pb_backupbuddy::$options['log_serial'] . '.txt';
		
		
		// Determine if writing to a serial log.
		if ( pb_backupbuddy::$_status_serial != '' ) {
			$serial = pb_backupbuddy::$_status_serial;
			$serial_file = $log_directory . 'status-' . $serial . '_' . pb_backupbuddy::$options['log_serial'] . '.txt';
			$write_serial = true;
		} else {
			$write_serial = false;
		}
		
		
		// Format error message.
		$e_string = 'PHP_ERROR ' . __( 'Error #32893. Fatal PHP error encountered:', 'it-l10n-backupbuddy' );
		foreach( (array)$e as $e_line_title => $e_line ) {
			$e_string .= $e_line_title . ' => ' . $e_line . "; ";
		}
		$e_string .= ".\n";
		
		
		// Write to log.
		@file_put_contents( $main_file, $e_string, FILE_APPEND );
		if ( $write_serial === true ) {
			@file_put_contents( $serial_file, $e_string, FILE_APPEND );
		}
		
		
	} // End shutdown_function.
	
	
	
	
	
	
	
	/*	start_backup_process()
	 *	
	 *	Initializes the entire backup process.
	 *	
	 *	@param		array		$profile			Backup profile array. Previously (pre-4.0): Valid values: db, full, export.
	 *	@param		string		$trigger			What triggered this backup. Valid values: ::d, manual.
	 *	@param		array		$pre_backup			Array of functions to prepend to the backup steps array.
	 *	@param		array		$post_backup		Array of functions to append to the backup steps array. Ie. sending to remote destination.
	 *	@param		string		$schedule_title		Title name of schedule. Used for tracking what triggered this in logging. For debugging.
	 *	@param		string		$serial_override	If provided then this serial will be used instead of an auto-generated one.
	 *	@param		array		$export_plugins		For use in export backup type. List of plugins to export.
	 *	@param		string		$deployDirection	blank for not deploy, push, or pull.
	 @	@param		array|''	$deploySettings		Destination settings for the deployment. Empty string when not deployment.
	 *	@return		boolean							True on success; false otherwise.
	 */
	function start_backup_process( $profile, $trigger = 'manual', $pre_backup = array(), $post_backup = array(), $schedule_title = '', $serial_override = '', $export_plugins = array(), $deployDirection = '', $deployDestinationSettings = '' ) {
		
		// Load profile defaults.
		$profile = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), $profile );
		foreach( $profile as $profile_item_name => &$profile_item ) { // replace non-overridden defaults with actual default value.
			if ( '-1' == $profile_item ) { // Set to use default so go grab default.
				if ( isset( pb_backupbuddy::$options['profiles'][0][ $profile_item_name ] ) ) {
					$profile_item = pb_backupbuddy::$options['profiles'][0][ $profile_item_name ]; // Grab value from defaults profile and replace with it.
				}
			}
		}
		
		// Handle backup mode.
		$backup_mode = pb_backupbuddy::$options['backup_mode']; // Load global default mode.
		if ( '1' == $profile['backup_mode'] ) { // Profile forces classic.
			$backup_mode = '1';
		} elseif ( '2' == $profile['backup_mode'] ) { // Profiles forces modern.
			$backup_mode = '2';
		}
		$profile['backup_mode'] = $backup_mode;
		unset( $backup_mode );
		
		// If classic mode then we need to redirect output to displaying inline via JS instead of AJAX-based.
		if ( '1' == $profile['backup_mode'] ) {
			//global $pb_backupbuddy_js_status;
			//$pb_backupbuddy_js_status = true;
		}
		
		if ( $serial_override != '' ) {
			$serial = $serial_override;
		} else {
			$serial = pb_backupbuddy::random_string( 10 );
		}
		pb_backupbuddy::set_status_serial( $serial ); // Default logging serial.
		
		global $wp_version;
		pb_backupbuddy::status( 'details', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . ' using WordPress v' . $wp_version . ' on ' . PHP_OS . '.' );
		//pb_backupbuddy::status( 'details', __('Peak memory usage', 'it-l10n-backupbuddy' ) . ': ' . round( memory_get_peak_usage() / 1048576, 3 ) . ' MB' );
		
		$type = $profile['type'];
		
		
		$archiveFile = backupbuddy_core::calculateArchiveFilename( $serial, $type, $profile );
		
		
		//if ( 'pull' != $deployDirection ) {
			if ( $this->pre_backup( $serial, $archiveFile, $profile, $trigger, $pre_backup, $post_backup, $schedule_title, $export_plugins, $deployDirection, $deployDestinationSettings ) === false ) {
				pb_backupbuddy::status( 'details', 'pre_backup() function failed.' );
				return false;
			}
		/*
		} else { // PULL deployment.
			
			pb_backupbuddy::status( 'details', 'About to load fileoptions data in create mode.' );
			require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #40.' );
			$this->_backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = false, $ignore_lock = false, $create_file = true );
			if ( true !== ( $result = $this->_backup_options->is_ok() ) ) {
				pb_backupbuddy::status( 'error', __('Fatal Error #38293. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			}
			pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
			$this->_backup = &$this->_backup_options->options; // Set reference.
			$this->_backup['serial'] = $serial;
		}
		*/
		
		if ( ( $trigger == 'scheduled' ) && ( pb_backupbuddy::$options['email_notify_scheduled_start'] != '' ) ) {
			pb_backupbuddy::status( 'details', __('Sending scheduled backup start email notification if applicable.', 'it-l10n-backupbuddy' ) );
			backupbuddy_core::mail_notify_scheduled( $serial, 'start', __('Scheduled backup', 'it-l10n-backupbuddy' ) . ' (' . $this->_backup['schedule_title'] . ') has begun.' );
		}
		
		if ( $profile['backup_mode'] == '2' ) { // Modern mode with crons.
			
			pb_backupbuddy::status( 'message', 'Running in modern backup mode based on settings. Mode value: `' . $profile['backup_mode'] . '`. Trigger: `' . $trigger . '`.' );
			
			unset( $this->_backup_options ); // File unlocking is handled on deconstruction.  Make sure unlocked before firing off another cron spawn.
			
			// If using alternate cron on a manually triggered backup then skip running the cron on this pageload to avoid header already sent warnings.
			if ( ( $trigger != 'manual' ) || ( defined('ALTERNATE_WP_CRON') && ALTERNATE_WP_CRON ) ) {
				$this->cron_next_step( false );
			} else {
				//$this->cron_next_step( true );
				$this->cron_next_step( false ); // as of Aug 9, 2013 no longer spawn the cron. Caused very odd issue of double code runs.
			}
			
		} else { // Classic mode; everything runs in this single PHP page load.
			
			pb_backupbuddy::status( 'message', 'Running in classic backup mode based on settings.' );
			$this->process_backup( $this->_backup['serial'], $trigger );
			
		}
		
		return true;
		
	} // End start_backup_process().
	
	
	
	/*	pre_backup()
	 *	
	 *	Set up the backup data structure containing steps, set up temp directories, etc.
	 *	
	 *	@param		array		$profile						Backup profile array data. Prev (pre-4.0):	Backup type. Valid values: db, full, export.
	 *	@param		string		$trigger						What triggered this backup. Valid values: scheduled, manual.
	 *	@param		array		$pre_backup						Array of functions to prepend to the backup steps array.
	 *	@param		array		$post_backup					Array of functions to append to the backup steps array. Ie. sending to remote destination.
	 *	@param		string		$schedule_title					Title name of schedule. Used for tracking what triggered this in logging. For debugging.
	 *	@param		array		$export_plugins					For use in export backup type. List of plugins to export.
	 *	@param		string		$deployDirection				blank for not deploy, push, or pull.
	 @	@param		array|''	$deployDestinationSettings		Destination settings for the deployment. Empty string when not deployment.
	 *	@return		boolean										True on success; false otherwise.
	 */
	function pre_backup( $serial, $archiveFile, $profile, $trigger, $pre_backup = array(), $post_backup = array(), $schedule_title = '', $export_plugins = array(), $deployDirection, $deployDestinationSettings ) {
		
		pb_backupbuddy::status( 'startFunction', json_encode( array( 'function' => 'pre_backup', 'title' => 'Getting ready to backup' ) ) );
		
		$type = $profile['type'];
		
		// Log some status information.
		pb_backupbuddy::status( 'details', __( 'Performing pre-backup procedures.', 'it-l10n-backupbuddy' ) );
		if ( $type == 'full' ) {
			pb_backupbuddy::status( 'message', __( 'Full backup mode.', 'it-l10n-backupbuddy' ) );
		} elseif ( $type == 'db' ) {
			pb_backupbuddy::status( 'message', __( 'Database only backup mode.', 'it-l10n-backupbuddy' ) );
		} elseif ( $type == 'files' ) {
			pb_backupbuddy::status( 'message', __( 'Files only backup mode.', 'it-l10n-backupbuddy' ) );
			//$profile['skip_database_dump'] = '1';
		} elseif ( $type == 'export' ) {
			pb_backupbuddy::status( 'message', __( 'Multisite subsite export mode.', 'it-l10n-backupbuddy' ) );
		} else {
			pb_backupbuddy::status( 'error', 'Error #8587383: Unknown backup mode `' . htmlentities( $type ) . '`.' );
		}
		
		if ( '' != $deployDirection ) {
			pb_backupbuddy::status( 'details', 'Deployment direction: `' . $deployDirection . '`.' );
		}
		
		if ( '1' == pb_backupbuddy::$options['prevent_flush'] ) {
			pb_backupbuddy::status( 'details', 'Flushing will be skipped based on advanced settings.' );
		} else {
			pb_backupbuddy::status( 'details', 'Flushing will not be skipped (default).' );
		}
		
		// Schedule daily housekeeping.
		backupbuddy_core::verifyHousekeeping();		
		
		// Verify directories.
		pb_backupbuddy::status( 'details', 'Verifying directories ...' );
		if ( false === backupbuddy_core::verify_directories() ) {
			pb_backupbuddy::status( 'error', 'Error #18573. Error verifying directories. See details above. Backup halted.' );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			die();
		} else {
			pb_backupbuddy::status( 'details', 'Directories verified.' );
		}
		
		// Delete all backup archives if this troubleshooting option is enabled.
		if ( pb_backupbuddy::$options['delete_archives_pre_backup'] == '1' ) {
			pb_backupbuddy::status( 'message', 'Deleting all existing backups prior to backup as configured on the settings page.' );
			$file_list = glob( backupbuddy_core::getBackupDirectory() . 'backup*.zip' );
			if ( is_array( $file_list ) && !empty( $file_list ) ) {
				foreach( $file_list as $file ) {
					if ( @unlink( $file ) === true ) {
						pb_backupbuddy::status( 'details', 'Deleted backup archive `' . basename( $file ) . '` based on settings to delete all backups.' );
					} else {
						pb_backupbuddy::status( 'details', 'Unable to delete backup archive `' . basename( $file ) . '` based on settings to delete all backups. Verify permissions.' );
					}
				}
			}
		}
				
		// Generate unique serial ID.
		pb_backupbuddy::status( 'details', 'Backup serial generated: `' . $serial . '`.' );
		
		pb_backupbuddy::status( 'details', 'About to load fileoptions data in create mode.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #40.' );
		$this->_backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = false, $ignore_lock = false, $create_file = true );
		if ( true !== ( $result = $this->_backup_options->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034 A. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$this->_backup = &$this->_backup_options->options; // Set reference.
		
		
		// Cleanup internal stats. Deployments should not impact stats.
		if ( '' == $deployDirection ) {
			pb_backupbuddy::status( 'details', 'Updating statistics for last backup start.' );
			pb_backupbuddy::$options['last_backup_start'] = microtime(true); // Reset time since last backup.
			pb_backupbuddy::$options['last_backup_serial'] = $serial;
			pb_backupbuddy::save();
		}
		
		
		// Output active plugins list for debugging...
		$activePlugins = get_option( 'active_plugins' );
		pb_backupbuddy::status( 'details', 'Active WordPress plugins (' . count( $activePlugins ) . '): `' . implode( '; ', $activePlugins ) . '`.' );
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'wp_plugins_found', 'title' => 'Found ' . count( $activePlugins ) . ' active WordPress plugins.' ) ) );
		unset( $activePlugins );
		
		
		// Compression to bool.
		/*
		if ( $profile['compression'] == '1' ) {
			$profile['compression'] = true;
		} else {
			$profile['compression'] = false;
		}
		*/
		if ( pb_backupbuddy::$options['compression'] == '1' ) {
			$compression = true;
		} else {
			$compression = false;
		}
		
		
		
		$archiveURL = '';
		$abspath = str_replace( '\\', '/', ABSPATH ); // Change slashes to handle Windows as we store backup_directory with Linux-style slashes even on Windows.
		$backup_dir = str_replace( '\\', '/', backupbuddy_core::getBackupDirectory() );
		if ( FALSE !== stristr( $backup_dir, $abspath ) ) { // Make sure file to download is in a publicly accessible location (beneath WP web root technically).
			$sitepath = str_replace( $abspath, '', $backup_dir );
			$archiveURL = rtrim( site_url(), '/\\' ) . '/' . trim( $sitepath, '/\\' ) . '/' . basename( $archiveFile );
		}
		
		$forceSingleDatabaseFile = false;
		if ( '1' == pb_backupbuddy::$options['force_single_db_file'] ) {
			$forceSingleDatabaseFile = true;
		}
		
		// Set up the backup data.
		$this->_backup = array(
			'data_version'			=>		1,												// Data structure version. Upped to 1 for BBv5.0.
			'backupbuddy_version'	=>		pb_backupbuddy::settings( 'version' ),			// BB version used for this backup.
			'serial'				=>		$serial,										// Unique identifier.
			'init_complete'			=>		false,											// Whether pre_backup() completed or not. Other step status is already tracked and stored in data structure but pre_backup 'step' was not until now. Jan 6, 2013.
			'backup_mode'			=>		$profile['backup_mode'],						// Tells whether modern or classic mode.
			'type'					=>		$type,											// db, full, or export.
			'profile'				=>		$profile,										// Backup profile data.
			'default_profile'		=>		pb_backupbuddy::$options['profiles'][0],		// Default profile.
			'start_time'			=>		time(),											// When backup started. Now.
			'finish_time'			=>		0,
			'updated_time'			=>		time(),											// When backup last updated. Subsequent steps update this.
			'status'				=>		array(),										// TODO: what goes in this?
			'max_execution_time'	=>		backupbuddy_core::adjustedMaxExecutionTime(),	// Max execution time for chunking, taking into account user-specified override in settings (if any).
			'archive_size'			=>		0,
			'schedule_title'		=>		$schedule_title,								// Title of the schedule that made this backup happen (if applicable).
			'backup_directory'		=>		backupbuddy_core::getBackupDirectory(),			// Directory backups stored in.
			'archive_file'			=>		$archiveFile,									// Unique backup ZIP filename.
			'archive_url'			=>		$archiveURL,									// Target download URL.
			'trigger'				=>		$trigger,										// How backup was triggered: manual or scheduled.
			'zip_method_strategy'	=>		pb_backupbuddy::$options['zip_method_strategy'],// Enumerated zip method strategy
			'compression'			=>		$compression, 									//$profile['compression'], // Boolean - future enumerated?
			'ignore_zip_warnings'	=>		pb_backupbuddy::$options['ignore_zip_warnings'],// Boolean - future bitmask?
			'ignore_zip_symlinks'	=>		pb_backupbuddy::$options['ignore_zip_symlinks'],// Boolean - future bitmask?
			'steps'					=>		array(),										// Backup steps to perform. Set next in this code.
			'integrity'				=>		array(),										// Used later for tests and stats post backup.
			'temp_directory'		=>		'',												// Temp directory to store SQL and DAT file. Differs for exports. Defined in a moment...
			'backup_root'			=>		'',												// Where to start zipping from. Usually root of site. Defined in a moment...
			'export_plugins'		=>		array(),										// Plugins to export during MS export of a subsite.
			'additional_table_includes'	=>	array(),
			'additional_table_excludes'	=>	array(),
			'directory_exclusions'		=>	backupbuddy_core::get_directory_exclusions( $profile, false, $serial ), // Do not trim trailing slash
			'table_sizes'			=>		array(),										// Array of tables to backup AND their sizes.
			'breakout_tables'		=>		array(),										// Array of tables that will be broken out to separate steps.
			'force_single_db_file'	=>		$forceSingleDatabaseFile,						// Whether forcing to a single db_1.sql file.
			'deployment_log'		=>		'',												// PUSH: URL to the importtbuddy status log for deployments. PULL: serial for the backup status log to retrieve via remote api.
			'deployment_direction'	=>		$deployDirection,								// Deployment direction, if any. valid: '', push, pull.
			'deployment_destination' =>		$deployDestinationSettings,						// Deployment remote destination settings if deployment.
			'runnerUID'				=>		get_current_user_id(),							// UID of whomever is running this backup. 0 if scheduled or ran by other automation means.
		);
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'file_excludes', 'title' => 'Found ' . count( $this->_backup['directory_exclusions'] ) . ' file or directory exclusions.' ) ) );
		
		// Warn if excluding key paths.
		$alertFileExcludes = backupbuddy_core::alert_core_file_excludes( $this->_backup['directory_exclusions'] );
		foreach( $alertFileExcludes as $alertFileExcludeId => $alertFileExclude ) {
			pb_backupbuddy::status( 'warning', $alertFileExclude );
		}
		
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
		
		// Figure out paths.
		if ( ( $this->_backup['type'] == 'full' ) || ( $this->_backup['type'] == 'files' ) ) {
			$this->_backup['temp_directory'] = backupbuddy_core::getTempDirectory() . $serial . '/';
			$this->_backup['backup_root'] = ABSPATH; // ABSPATH contains trailing slash.
		} elseif ( $this->_backup['type'] == 'db' ) {
			$this->_backup['temp_directory'] = backupbuddy_core::getTempDirectory() . $serial . '/';
			$this->_backup['backup_root'] = $this->_backup['temp_directory'];
		} elseif ( $this->_backup['type'] == 'export' ) {
			// WordPress unzips into wordpress subdirectory by default so must include that in path.
			$this->_backup['temp_directory'] = backupbuddy_core::getTempDirectory() . $serial . '/wordpress/wp-content/uploads/backupbuddy_temp/' . $serial . '/'; // We store temp data for export within the temporary WordPress installation within the temp directory. A bit confusing; sorry about that.
			$this->_backup['backup_root'] = backupbuddy_core::getTempDirectory() . $serial . '/wordpress/';
		} else {
			pb_backupbuddy::status( 'error', __('Backup FAILED. Unknown backup type.', 'it-l10n-backupbuddy' ) );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
		}
		pb_backupbuddy::status( 'details', 'Temp directory: `' . $this->_backup['temp_directory'] . '`.' );
		pb_backupbuddy::status( 'details', 'Backup root: `' . $this->_backup['backup_root'] . '`.' );
		
		
		// Plugins to export (only for MS exports).
		if ( count( $export_plugins ) > 0 ) {
			$this->_backup['export_plugins'] = $export_plugins;
		}
		
		
		// Calculate additional database table inclusion/exclusion.
		$this->_backup['additional_table_includes'] = backupbuddy_core::get_mysqldump_additional( 'includes', $profile );
		$this->_backup['additional_table_excludes'] = backupbuddy_core::get_mysqldump_additional( 'excludes', $profile );
		
		
		/********* Begin setting up steps array. *********/
		
		if ( $type == 'export' ) {
			pb_backupbuddy::status( 'details', 'Setting up export-specific steps.' );
			
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_download_extract_wordpress',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_create_wp_config',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_copy_plugins',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_copy_themes',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_copy_media',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->_backup['steps'][] = array(
				'function'		=>	'ms_copy_users_table', // Create temp user and usermeta tables.
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
		}
		
		if ( ( 'pull' != $deployDirection ) && ( '1' != $profile['skip_database_dump'] ) && ( $profile['type'] != 'files' ) ) { // Backup database if not skipping AND not a files only backup.
			
			global $wpdb;
			// Default tables to backup.
			if ( $type == 'export' ) { // Multisite Subsite export only dumps tables specific to this subsite prefix.
				$base_dump_mode = 'prefix';
			} else { // Non-multisite export so use profile to determine tables to backup.
				if ( $profile['backup_nonwp_tables'] == '1' ) { // Backup all tables.
					$base_dump_mode = 'all';
				} elseif ( $profile['backup_nonwp_tables'] == '2' ) { // Backup no tables by default. Relies on listed additional tables.
					$base_dump_mode = 'none';
				} else { // Only backup matching prefix.
					$base_dump_mode = 'prefix';
				}
			}
			
			$additional_tables = $this->_backup['additional_table_includes'];
			if ( $type == 'export' ) {
				global $wpdb;
				array_push( $additional_tables, $wpdb->prefix . "users" );
				array_push( $additional_tables, $wpdb->prefix . "usermeta" );
			}
			
			// Warn if excluding key WP tables.
			$tableExcludes = backupbuddy_core::alert_core_table_excludes( $this->_backup['additional_table_excludes'] );
			foreach( $tableExcludes as $tableExcludeId => $tableExclude ) {
				pb_backupbuddy::status( 'warning', $tableExclude );
			}
			
			// Calculate tables to dump based on the provided information. $tables will be an array of tables.
			$tables = $this->_calculate_tables( $base_dump_mode, $additional_tables, $this->_backup['additional_table_excludes'] );
			pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'calculate_tables', 'title' => 'Found ' . count( $tables ) . ' tables to backup based on settings.', 'more' => 'Tables: ' . implode( ', ', $tables ) ) ) );
			
			// If calculations show NO database tables should be backed up then change mode to skip database dump.
			if ( 0 == count( $tables ) ) {
				pb_backupbuddy::status( 'warning', 'WARNING #857272: No database tables will be backed up based on current settings. This will not be a complete backup. Adjust settings if this is not intended and use with caution. Skipping database dump step.' );
				$profile['skip_database_dump'] = '1';
				$this->_backup['profile']['skip_database_dump'] = '1';
			} else { // One or more tables set to backup.
				
				// Obtain tables sizes. Surround each table name by a single quote and implode with commas for SQL query to get sizes.
				$tables_formatted = $tables;
				foreach( $tables_formatted as &$table_formatted ) {
					$table_formatted = "'{$table_formatted}'";
				}
				$tables_formatted = implode( ',', $tables_formatted );
				$sql = "SHOW TABLE STATUS WHERE Name IN({$tables_formatted});";
				$rows = $wpdb->get_results( $sql, ARRAY_A );
				if ( false === $rows ) {
					pb_backupbuddy::alert( 'Error #85473474: Unable to retrieve table status. Query: `' . $sql . '`.', true );
					return false;
				}
				$totalDatabaseSize = 0;
				foreach( $rows as $row ) {
					$this->_backup['table_sizes'][ $row['Name'] ] = ( $row['Data_length'] + $row['Index_length'] );
					$totalDatabaseSize += $this->_backup['table_sizes'][ $row['Name'] ];
				}
				unset( $rows );
				unset( $tables_formatted );
				
				$databaseSize = pb_backupbuddy::$format->file_size( $totalDatabaseSize );
				pb_backupbuddy::status( 'details', 'Total calculated database size: `' . $databaseSize . '`.' );
				
				// Step through tables we want to break out and figure out which ones were indeed set to be backed up and break them out.
				if ( pb_backupbuddy::$options['breakout_tables'] == '0' ) { // Breaking out DISABLED.
					pb_backupbuddy::status( 'details', 'Breaking out tables DISABLED based on settings.' );
				} else { // Breaking out ENABLED.
					// Tables we will try to break out into standalone steps if possible.
					$breakout_tables_defaults = array(
						$wpdb->prefix . 'posts',
						$wpdb->prefix . 'postmeta',
					);
					
					pb_backupbuddy::status( 'details', 'Breaking out tables ENABLED based on settings. Tables to be broken out into individual steps: `' . implode( ', ', $breakout_tables_defaults ) . '`.' );
					foreach( (array)$breakout_tables_defaults as $breakout_tables_default ) {
						if ( in_array( $breakout_tables_default, $tables ) ) {
							$this->_backup['breakout_tables'][] = $breakout_tables_default;
							$tables = array_diff( $tables, array( $breakout_tables_default ) ); // Remove from main table backup list.
						}
					}
					unset( $breakout_tables_defaults ); // No longer needed.
				}
				
				
				$this->_backup['steps'][] = array(
					'function'		=>	'backup_create_database_dump',
					'args'			=>	array( $tables ),
					'start_time'	=>	0,
					'finish_time'	=>	0,
					'attempts'		=>	0,
				);
				
				// Set up backup steps for additional broken out tables.
				foreach( (array)$this->_backup['breakout_tables'] as $breakout_table ) {
					$this->_backup['steps'][] = array(
						'function'		=>	'backup_create_database_dump',
						'args'			=>	array( array( $breakout_table ) ),
						'start_time'	=>	0,
						'finish_time'	=>	0,
						'attempts'		=>	0,
					);
				}
				
			} // end there being tables to backup.
			
		} else {
			pb_backupbuddy::status( 'message', __( 'Skipping database dump based on settings / profile type.', 'it-l10n-backupbuddy' ) . ' Backup type: `' . $type . '`.' );
		}
		
		if ( 'pull' != $deployDirection ) {
			$this->_backup['steps'][] = array(
				'function'		=>	'backup_zip_files',
				'args'			=>	array(),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			
			if ( $type == 'export' ) {
				$this->_backup['steps'][] = array( // Multisite export specific cleanup.
					'function'		=>	'ms_cleanup', // Removes temp user and usermeta tables.
					'args'			=>	array(),
					'start_time'	=>	0,
					'finish_time'	=>	0,
					'attempts'		=>	0,
				);
			}
			
			if ( $profile['integrity_check'] == '1' ) {
				pb_backupbuddy::status( 'details', __( 'Integrity check will be performed based on settings for this profile.', 'it-l10n-backupbuddy' ) );
				$this->_backup['steps'][] = array(
					'function'		=>	'integrity_check',
					'args'			=>	array(),
					'start_time'	=>	0,
					'finish_time'	=>	0,
					'attempts'		=>	0,
				);
			} else {
				pb_backupbuddy::status( 'details', __( 'Skipping integrity check step based on settings for this profile.', 'it-l10n-backupbuddy' ) );
			}
		}
		
		$this->_backup['steps'][] = array(
			'function'		=>	'post_backup',
			'args'			=>	array(),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		
		// Prepend and append pre backup and post backup steps.				
		$this->_backup['steps'] = array_merge( $pre_backup, $this->_backup['steps'], $post_backup );
		
		/********* End setting up steps array. *********/
		
		
		// Save what we have so far so that any errors below will end up displayed to user.
		$this->_backup_options->save();
		
		
		/********* Begin directory creation and security. *********/
		
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getBackupDirectory() );
		
		// Prepare temporary directory for holding SQL and data file.
		if ( backupbuddy_core::getTempDirectory() == '' ) {
			pb_backupbuddy::status( 'error', 'Error #54534344. Temp directory blank. Please deactivate then reactivate plugin to reset.' );
			return false;
		}
		
		if ( !file_exists( $this->_backup['temp_directory'] ) ) {
			if ( pb_backupbuddy::$filesystem->mkdir( $this->_backup['temp_directory'] ) === false ) {
				pb_backupbuddy::status( 'error', 'Error #9002b. Unable to create temporary storage directory (' . $this->_backup['temp_directory'] . ')' );
				return false;
			}
		}
		if ( !is_writable( $this->_backup['temp_directory'] ) ) {
			pb_backupbuddy::status( 'error', 'Error #9015. Temp data directory is not writable. Check your permissions. (' . $this->_backup['temp_directory'] . ')' );
			return false;
		}
		pb_backupbuddy::anti_directory_browsing( ABSPATH . 'wp-content/uploads/backupbuddy_temp/' );
		
		// Prepare temporary directory for holding ZIP file while it is being generated.
		$this->_backup['temporary_zip_directory'] = backupbuddy_core::getBackupDirectory() . 'temp_zip_' . $this->_backup['serial'] . '/';
		if ( !file_exists( $this->_backup['temporary_zip_directory'] ) ) {
			if ( pb_backupbuddy::$filesystem->mkdir( $this->_backup['temporary_zip_directory'] ) === false ) {
				pb_backupbuddy::status( 'details', 'Error #9002c. Unable to create temporary ZIP storage directory (' . $this->_backup['temporary_zip_directory'] . ')' );
				return false;
			}
		}
		if ( !is_writable( $this->_backup['temporary_zip_directory'] ) ) {
			pb_backupbuddy::status( 'error', 'Error #9015. Temp data directory is not writable. Check your permissions. (' . $this->_backup['temporary_zip_directory'] . ')' );
			return false;
		}
		
		/********* End directory creation and security *********/
		
		
		// Generate backup DAT (data) file containing details about the backup.
		if ( $this->backup_create_dat_file( $trigger ) !== true ) {
			pb_backupbuddy::status( 'details', __('Problem creating DAT file.', 'it-l10n-backupbuddy' ) );
			return false;
		}
		
		// Generating ImportBuddy file to include in the backup for FULL BACKUPS ONLY currently. Cannot put in DB because it would be in root and be excluded or conflict on extraction.
		if ( $type == 'full' ) {
			if ( pb_backupbuddy::$options['include_importbuddy'] == '1' ) {
				pb_backupbuddy::status( 'details', 'Generating ImportBuddy tool to include in backup archive: `' . $this->_backup['temp_directory'] . 'importbuddy.php`.' );
				pb_backupbuddy::status( 'startAction', 'importbuddyCreation' );
				backupbuddy_core::importbuddy( $this->_backup['temp_directory'] . 'importbuddy.php' );
				pb_backupbuddy::status( 'finishAction', 'importbuddyCreation' );
				pb_backupbuddy::status( 'details', 'ImportBuddy generation complete.' );
			} else { // dont include importbuddy.
				pb_backupbuddy::status( 'details', 'ImportBuddy tool inclusion in ZIP backup archive skipped based on settings or backup type.' );
			}
		}
		
		// Save all of this.
		$this->_backup['init_complete'] = true; // pre_backup() completed.
		$this->_backup_options->save();
		
		
		pb_backupbuddy::status( 'details', __('Finished pre-backup procedures.', 'it-l10n-backupbuddy' ) );
		pb_backupbuddy::status( 'milestone', 'finish_settings' );
		
		pb_backupbuddy::status( 'finishFunction', json_encode( array( 'function' => 'pre_backup' ) ) );
		return true;
		
	} // End pre_backup().
	
	
	
	/*	process_backup()
	 *	
	 *	Process and run the next backup step.
	 *	
	 *	@param		string		$serial		Unique backup identifier.
	 *	@param		string		$trigger	What triggered this processing: manual or scheduled.
	 *	@return		boolean					True on success, false otherwise.
	 */
	function process_backup( $serial, $trigger = 'manual' ) {
		//pb_backupbuddy::status( 'details', 'Running process_backup() for serial `' . $serial . '`.' );
		
		// Assign reference to backup data structure for this backup.
		if ( ! isset( $this->_backup_options ) ) {
			pb_backupbuddy::status( 'details', 'About to load fileoptions data for serial `' . $serial . '`.' );
			$attempt_transient_prefix = 'pb_backupbuddy_lock_attempts-';
			require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #39.' );
			$this->_backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' );
			if ( true !== ( $result = $this->_backup_options->is_ok() ) ) { // Unable to access fileoptions.
				
				$attempt_delay_base = 10; // Base number of seconds to delay. Each subsequent attempt increases this delay by a multiple of the attempt number.
				$max_attempts = 8; // Max number of attempts to try to delay around a file lock. Delay increases each time.
				
				$this->_backup['serial'] = $serial; // Needs to be populated for use by cron schedule step.
				pb_backupbuddy::status( 'warning', __('Warning #9034 B. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Warning: ' . $result, $serial );
				
				// Track lock attempts in transient system. This is not vital & since locks may be having issues track this elsewhere.
				$lock_attempts = get_transient( $attempt_transient_prefix . $serial );
				if ( false === $lock_attempts ) {
					$lock_attempts = 0;
				}
				$lock_attempts++;
				set_transient( $attempt_transient_prefix . $serial, $lock_attempts, (60*60*24) ); // Increment lock attempts. Hold attempt count for 24 hours to help make sure we don't lose attempt count if very low site activity, etc.
				
				if ( $lock_attempts > $max_attempts ) {
					pb_backupbuddy::status( 'error', 'Backup halted. Maximum number of attempts made attempting to access locked fileoptions file. This may be caused by something causing backup steps to run out of order or file permission issues on the temporary directory holding the file `' . $fileoptions_file  . '`. Verify correct permissions.', $serial );
					pb_backupbuddy::status( 'haltScript', '', $serial ); // Halt JS on page.
					delete_transient( $attempt_transient_prefix . $serial );
					return false;
				}
				
				$wait_time = $attempt_delay_base * $lock_attempts;
				pb_backupbuddy::status( 'message', 'A scheduled step attempted to run before the previous step completed. The previous step may have failed or two steps may be attempting to run simultaneously.', $serial );
				pb_backupbuddy::status( 'message', 'Waiting `' . $wait_time . '` seconds before continuing. Attempt #' . $lock_attempts . ' of ' . $max_attempts . ' max allowed before giving up.', $serial );
				$this->cron_next_step( false, $wait_time );
				return false;
				
			} else { // Accessed fileoptions. Clear/reset any attempt count.
				delete_transient( $attempt_transient_prefix . $serial );
			}
			pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
			$this->_backup = &$this->_backup_options->options;
		}
		
		if ( '-1' == $this->_backup_options->options['finish_time'] ) {
			pb_backupbuddy::status( 'details', 'Warning #8328332: This backup is marked as cancelled. Halting.' );
			return false;
		}
		
		if ( $this->_backup_options->options['profile']['backup_mode'] != '1' ) { // Only check for cronPass action if in modern mode.
			pb_backupbuddy::status( 'finishAction', 'cronPass' );
		}
		
		// Handle cancelled backups (stop button).
		if ( true == get_transient( 'pb_backupbuddy_stop_backup-' . $serial ) ) { // Backup flagged for stoppage. Proceed directly to cleanup.
			
			pb_backupbuddy::status( 'message', 'Backup STOPPED by user. Post backup cleanup step has been scheduled to clean up any temporary files.' );
			foreach( $this->_backup['steps'] as $step_id => $step ) {
				if ( $step['function'] != 'post_backup' ) {
					if ( $step['start_time'] == 0 ) {
						$this->_backup['steps'][$step_id]['start_time'] = -1; // Flag for skipping.
					}
				} else { // Post backup step.
					$this->_backup['steps'][$step_id]['args'] = array( true, true ); // Run post_backup in fail mode & delete backup file.
				}
			}
			//pb_backupbuddy::save();
			$this->_backup_options->save();
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			
		}
		
		
		$found_next_step = false;
		
		//pb_backupbuddy::status( 'details', 'Steps: ' . print_r( $this->_backup['steps'], true ) );
		//pb_backupbuddy::status( 'details', 'FullOptions: ' . print_r( $this->_backup, true ) );
		
		
		// Loop through steps finding first step that has not run.
		$foundComplete = 0;
		foreach( (array)$this->_backup['steps'] as $step_index => $step ) {
			$this->_currentStepIndex = $step_index;
			//pb_backupbuddy::status( 'details', 'step: ' . $step['function'] . 'start: ' . $step['start_time'] );
			if ( ( $step['start_time'] != -1 ) && ( $step['start_time'] != 0 ) && ( $step['finish_time'] == 0 ) ) { // A step is not marked for skippage, has begun but has not finished. This should not happen but the WP cron is funky. Wait a while before continuing.
				
				// Re-load, respecting locks to help avoid race conditions.
				$this->_backup_options->load( $ignore_lock = false, $create_file = false, $retryCount = 0 );
				if ( true !== ( $this->_backup_options->is_ok() ) ) { // Unable to access fileoptions.
					pb_backupbuddy::status( 'warning', 'Unable to update out of order step attempt count due to file lock. It may be being written to by the other step at this moment.' );
				} else {
					pb_backupbuddy::status( 'details', 'Saving update to step attempt count.' );
					$this->_backup['steps'][$step_index]['attempts']++; // Increment this as an attempt.
					$this->_backup_options->save();
				}
				
				if ( ( $step['attempts'] < 6 ) ) {
					$wait_time = 60 * $step['attempts']; // Each attempt adds a minute of wait time.
					pb_backupbuddy::status( 'warning', 'A scheduled step attempted to run before the previous step completed. Waiting `' . $wait_time . '` seconds before continuing for it to catch up. Attempt number `' . $step['attempts'] . '`.' );
					$this->cron_next_step( false, $wait_time );
					return false;
				} else { // Too many attempts to run this step.
					pb_backupbuddy::status( 'error', 'A scheduled step attempted to run before the previous step completed. After several attempts (`' . $step['attempts'] . '`) of failure BackupBuddy has given up. Halting backup.' );
					return false;
				}
				
				break;
				
			} elseif ( $step['start_time'] == 0 ) { // Step that is not marked for skippage and has not started yet.
				$found_next_step = true;
				$this->_backup['steps'][$step_index]['start_time'] = microtime(true); // Set this step time to now.
				$this->_backup['steps'][$step_index]['attempts']++; // Increment this as an attempt.
				$this->_backup_options->save();
				
				pb_backupbuddy::status( 'details', 'Found next step to run: `' . $step['function'] . '`.' );
				
				break; // Break out of foreach loop to continue.
			} elseif ( $step['start_time'] == -1 ) { // Step flagged for skipping. Do not run.
				pb_backupbuddy::status( 'details', 'Step `' . $step['function'] . '` flagged for skipping. Skipping.' );
			} else { // Last case: Finished. Skip.
				// Do nothing for completed steps.
				$foundComplete++;
			}
			
		} // End foreach().
		
		
		if ( $found_next_step === false ) { // No more steps to perform; return.
			// NOTE: This should normally NOT be seen.  If it is run then a cron was scheduled despite there being no steps left which would not make sense.  This does appear some though as of Jul 22, 2015 for unknown reasons.  Missing post_backup() function?
			pb_backupbuddy::status( 'details', 'Backup steps:' );
			pb_backupbuddy::status( 'details', print_r( $this->_backup['steps'], true ) );
			pb_backupbuddy::status( 'warning', 'No more unfinished steps found. This is unexpected usually not normal though may not be harmful to the backup. Total found completed: `' . $foundComplete . '`.' );
			return false;
		}
		//pb_backupbuddy::save();
		
		
		pb_backupbuddy::status( 'details', __('Peak memory usage', 'it-l10n-backupbuddy' ) . ': ' . round( memory_get_peak_usage() / 1048576, 3 ) . ' MB' );		
		
		/********* Begin Running Step Function **********/
		if ( method_exists( $this, $step['function'] ) ) {
			/*
			$args = '';
			foreach( $step['args'] as $arg ) {
				if ( is_array( $arg ) ) {
					$args .= '{' . implode( ',', $arg ) . '},';
				} else {
					$args .= str_replace('],[', '|', trim(json_encode($step['args']), '[]'));
				}
			}
			*/
			
			pb_backupbuddy::status( 'details', '-----' );
			pb_backupbuddy::status( 'details', 'Starting step function `' . $step['function'] . '`. Attempt #' . ( $step['attempts'] + 1 ) . '.' ); // attempts 0-indexed.
			
			$functionTitle = $step['function'];
			$subFunctionTitle = '';
			$functionTitle = backupbuddy_core::prettyFunctionTitle( $step['function'], $step['args'] );
			pb_backupbuddy::status( 'startFunction', json_encode( array( 'function' => $step['function'], 'title' => $functionTitle ) ) );
			if ( '' != $subFunctionTitle ) {
				pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => $step['function'] . '_subfunctiontitle', 'title' => $subFunctionTitle ) ) );
			}
				
			$response = call_user_func_array( array( &$this, $step['function'] ), $step['args'] );
		} else {
			pb_backupbuddy::status( 'error', __( 'Error #82783745: Invalid function `' . $step['function'] . '`' ) );
			$response = false;
		}
		/********* End Running Step Function **********/
		//unset( $step );
		
		if ( $response === false ) { // Function finished but reported failure.
			
			// Failure caused by backup cancellation.
			if ( true == get_transient( 'pb_backupbuddy_stop_backup-' . $serial ) ) {
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			}
			
			pb_backupbuddy::status( 'error', 'Failed function `' . $this->_backup['steps'][$step_index]['function'] . '`. Backup terminated.' );
			pb_backupbuddy::status( 'errorFunction', $this->_backup['steps'][$step_index]['function'] );
			pb_backupbuddy::status( 'details', __('Peak memory usage', 'it-l10n-backupbuddy' ) . ': ' . round( memory_get_peak_usage() / 1048576, 3 ) . ' MB' );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			
			$args = print_r( $this->_backup['steps'][$step_index]['args'], true );
			$attachment = NULL;
			$attachment_note = 'Enable full logging for troubleshooting (a log will be sent with future error emails while enabled).';
			
			if ( pb_backupbuddy::$options['log_level'] == '3' ) { // Full logging enabled.
				// Log file will be attached.
				$log_file = backupbuddy_core::getLogDirectory() . 'status-' . $serial . '_' . pb_backupbuddy::$options['log_serial'] . '.txt';
				if ( file_exists( $log_file ) ) {
					$attachment = $log_file;
					$attachment_note = 'A log file is attached which may provide further details.';
				} else {
					$attachment = NULL;
				}
			}
			
			// Send error notification email.
			backupbuddy_core::mail_error(
				'One or more backup steps reported a failure. ' . $attachment_note . ' Backup failure running function `' . $this->_backup['steps'][$step_index]['function'] . '` with the arguments `' . $args . '` with backup serial `' . $serial . '`. Please run a manual backup of the same type to verify backups are working properly or view the backup status log.',
				NULL,
				$attachment
			);
			
			return false;
			
		} else { // Function finished successfully.
			
			$this->_backup['steps'][$step_index]['finish_time'] = microtime(true);
			if (
				( 'backup_create_database_dump' != $this->_backup['steps'][$step_index]['function'] ) // Do not wipe DB backup steps which track table dumps.
				&&
				( 'send_remote_destination' != $this->_backup['steps'][$step_index]['function'] ) // Do not wipe remote sends as these occur after integrity check and need to remain.
				) { // Wipe arguments.  Keeps fileoptions for growing crazily for finished steps containing state data such as deployment or new zip functionality passing chunking state.
				$this->_backup['steps'][$step_index]['args'] = time();
			}
			$this->_backup['updated_time'] = microtime(true);
			$this->_backup_options->save();
			
			pb_backupbuddy::status( 'details', sprintf( __('Finished function `%s`. Peak memory usage', 'it-l10n-backupbuddy' ) . ': ' . round( memory_get_peak_usage() / 1048576, 3 ) . ' MB', $this->_backup['steps'][$step_index]['function'] ) . ' with BackupBuddy v' . pb_backupbuddy::settings( 'version' ) ) . '.';
			pb_backupbuddy::status( 'finishFunction', json_encode( array( 'function' => $this->_backup['steps'][$step_index]['function'] ) ) );
			pb_backupbuddy::status( 'details', '-----' );
			
			$found_another_step = false;
			foreach( $this->_backup['steps'] as $next_step ) { // Loop through each step and see if any have not started yet.
				if ( $next_step['start_time'] == 0 ) { // Another unstarted step exists. Schedule it.
					$found_another_step = true;
					if ( $this->_backup['profile']['backup_mode'] == '2' ) { // Modern mode with crons.
						$this->cron_next_step( null, null, $next_step['function'] );
					} elseif ( $this->_backup['profile']['backup_mode'] == '1' ) { // classic mode
						pb_backupbuddy::status( 'details', 'Classic mode; skipping cron & triggering next step.' );
						$this->process_backup( $this->_backup['serial'], $trigger );
					} else {
						pb_backupbuddy::status( 'error', 'Error #3838932: Fatal error. Unknown backup mode `' . $this->_backup['profile']['backup_mode'] . '`. Expected 1 (classic) or 2 (modern).' );
						return false;
					}
					
					break;
				}
			} // End foreach().
			
			if ( $found_another_step == false ) {
				pb_backupbuddy::status( 'details', __( 'No more backup steps remain. Finishing...', 'it-l10n-backupbuddy' ) );
				$this->_backup['finish_time'] = microtime(true);
				$this->_backup_options->save();
				pb_backupbuddy::status( 'startFunction', json_encode( array( 'function' => 'backup_success', 'title' => __( 'Backup completed successfully.', 'it-l10n-backupbuddy' ) ) ) );
				pb_backupbuddy::status( 'finishFunction', json_encode( array( 'function' => 'backup_success' ) ) );
				
				// Notification for manual and scheduled backups (omits deployment stuff).
				if ( ( $this->_backup['trigger'] == 'manual' ) || ( 'scheduled' == $this->_backup['trigger'] ) ) {
					
					$data = array();
					$data['serial'] = $this->_backup['serial'];
					$data['type'] = $this->_backup['type'];
					$data['profile_title'] = $this->_backup['profile']['title'];
					if ( '' != $this->_backup['schedule_title'] ) {
						$data['schedule_title'] = $this->_backup['schedule_title'];
					}
					
					backupbuddy_core::addNotification( 'backup_success', 'Backup completed successfully', 'A ' . $this->_backup['trigger'] . ' backup has completed successfully on your site.', $data );
				}
			} else {
				pb_backupbuddy::status( 'details', 'Completed step function `' . $step['function'] . '`.' );
				//pb_backupbuddy::status( 'details', 'The next should run in a moment. If it does not please check for plugin conflicts and that the next step is scheduled in the cron on the Server Information page.' );
			}
			
			// If full logging, output fileoptions state data to brwoser for display in console.
			if ( pb_backupbuddy::$options['log_level'] == '3' ) { // Full logging enabled.
				$thisBackup = $this->_backup;
				if ( '' != $this->_backup['deployment_direction'] ) { // Remove steps for deployment because it gets too large.
					$thisBackup['steps'] = '** Removed since deployment type **';
				}
				pb_backupbuddy::status( 'backupState', json_encode( $thisBackup ) ); //base64_encode( json_encode( $this->_backup ) ) );
			}
			
			return true;
		}
		
		
	} // End process_backup().
	
	
	
	/*	cron_next_step()
	 *	
	 *	Schedule the next step into the cron. Defaults to scheduling to happen _NOW_. Automatically opens a loopback to trigger cron in another process by default.
	 *	
	 *	@param		boolean		$spawn_cron			Whether or not to to spawn a loopback to run the cron. If using an offset this most likely should be false. Default: true
	 *	@param		int			$future_offset		Seconds in the future for this process to run. Most likely set $spawn_cron false if using an offset. Default: 0
	 *	@param		string		$next_step_title	Optional text title/function name/whatever of the next step to run. Useful for troubleshooting. Status logged.
	 *	@return		null
	 */
	function cron_next_step( $spawn_cron = true, $future_offset = 0, $next_step_title = '' ) {
		if ( '1' == pb_backupbuddy::$options['skip_spawn_cron_call'] ) {
			pb_backupbuddy::status( 'details', 'Advanced option to skip call to spawn cron enabled. Setting to skip spawn_cron() call.' );
			$spawn_cron = false;
		}
		
		pb_backupbuddy::status( 'details', 'Scheduling Cron for `' . $this->_backup['serial'] . '`.' );
		
		// Need to make sure the database connection is active. Sometimes it goes away during long bouts doing other things -- sigh.
		// This is not essential so use include and not require (suppress any warning)
		@include_once( pb_backupbuddy::plugin_path() . '/lib/wpdbutils/wpdbutils.php' );
		if ( class_exists( 'pluginbuddy_wpdbutils' ) ) {
			global $wpdb;
			$dbhelper = new pluginbuddy_wpdbutils( $wpdb );
			if ( ! $dbhelper->kick() ) {
				pb_backupbuddy::status( 'error', __('Database Server has gone away, unable to schedule next backup step. The backup cannot continue. This is most often caused by mysql running out of memory or timing out far too early. Please contact your host.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			}
		} else {
			pb_backupbuddy::status( 'details', __('Database Server connection status unverified.', 'it-l10n-backupbuddy' ) );
		}
		
		// Schedule event.
		$cron_time = ( time() + $future_offset );
		$cron_args = array( $this->_backup['serial'] );
		pb_backupbuddy::status( 'details', 'Scheduling next step to run at `' . $cron_time . '` (localized time: ' . pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $cron_time ) ) . ') with cron tag `backupbuddy_cron` to run method `process_backup` and serial arguments `' . implode( ',', $cron_args ) . '`.' );
		$schedule_result = backupbuddy_core::schedule_single_event( $cron_time, 'process_backup', $cron_args );
		if ( $schedule_result === false ) {
			pb_backupbuddy::status( 'error', 'Unable to schedule next cron step. Verify that another plugin is not preventing / conflicting.' );
		} else {
			pb_backupbuddy::status( 'details', 'Next step scheduled.' );
			pb_backupbuddy::status( 'startAction', 'cronPass' );
			pb_backupbuddy::status( 'cronParams', base64_encode( json_encode( array( 'time' => $cron_time, 'tag' => 'backupbuddy_cron', 'method' => 'process_backup', 'args' => $cron_args ) ) ) );
		}
		
		update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
		
		// Spawn cron.
		if ( $spawn_cron === true ) {
			pb_backupbuddy::status( 'details', 'Calling spawn_cron().' );
			spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
		} else {
			pb_backupbuddy::status( 'details', 'Not calling spawn_cron().' );
		}
		
		$next_step_note = '';
		if ( '' != $next_step_title ) {
			$next_step_note = ' (' . $next_step_title . ' expected)';
		}
		pb_backupbuddy::status( 'details', 'About to run next step' . $next_step_note . '. If the backup does not proceed within 15 seconds then something is interfering with the WordPress CRON system such as: server loopback issues, caching plugins, or scheduling plugins. Try disabling other plugins to see if it resolves issue.  Check the Server Information page cron section to see if the next BackupBuddy step is scheduled to run. Enable "Classic" backup mode on the "Settings" page to rule out non-cron issues. Additionally you may verify no other backup processes are trying to run at the same time by verifying there is not an existing backup process listed in the cron hogging the cron process.' );
		return;
		
	} // End cron_next_step().
	
	
	
	/*	backup_create_dat_file()
	 *	
	 *	Generates backupbuddy_dat.php within the temporary directory containing the
	 *	random serial in its name. This file contains a serialized array that has been
	 *	XOR encrypted for security.  The XOR key is backupbuddy_SERIAL where SERIAL
	 *	is the randomized set of characters in the ZIP filename. This file contains
	 *	various information about the source site.
	 *	
	 *	@param		string			$trigger			What triggered this backup. Valid values: scheduled, manual.
	 *	@return		boolean			true on success making dat file; else false
	 */
	function backup_create_dat_file( $trigger ) {
		
		pb_backupbuddy::status( 'details', __( 'Creating DAT (data) file snapshotting site & backup information.', 'it-l10n-backupbuddy' ) );
		
		global $wpdb, $current_blog;
		
		$is_multisite = $is_multisite_export = false; //$from_multisite is from a site within a network
		$upload_url_rewrite = $upload_url = '';
		if ( ( is_multisite() && ( $trigger == 'scheduled' ) ) || (is_multisite() && is_network_admin() ) ) { // MS Network Export IF ( in a network and triggered by a schedule ) OR ( in a network and logged in as network admin)
			$is_multisite = true;
		} elseif ( is_multisite() ) { // MS Export (individual site)
			$is_multisite_export = true;
			$uploads = wp_upload_dir();
			$upload_url_rewrite = site_url( str_replace( ABSPATH, '', $uploads[ 'basedir' ] ) ); // URL we rewrite uploads to. REAL direct url.
			$upload_url = $uploads[ 'baseurl' ]; // Pretty virtual path to uploads directory.
		}
		
		// Handle wp-config.php file in a parent directory.
		if ( $this->_backup['type'] == 'full' ) {
			$wp_config_parent = false;
			if ( file_exists( ABSPATH . 'wp-config.php' ) ) { // wp-config in normal place.
				pb_backupbuddy::status( 'details', 'wp-config.php found in normal location.' );
			} else { // wp-config not in normal place.
				pb_backupbuddy::status( 'message', 'wp-config.php not found in normal location; checking parent directory.' );
				if ( file_exists( dirname( ABSPATH ) . '/wp-config.php' ) ) { // Config in parent.
					$wp_config_parent = true;
					pb_backupbuddy::status( 'message', 'wp-config.php found in parent directory. Copying wp-config.php to temporary location for backing up.' );
					$this->_backup['wp-config_in_parent'] = true;
					
					copy( dirname( ABSPATH ) . '/wp-config.php', $this->_backup['temp_directory'] . 'wp-config.php' );
				} else {
					pb_backupbuddy::status( 'error', 'wp-config.php not found in normal location NOR parent directory. This will result in an incomplete backup which will be marked as bad.' );
				}
			}
		} else {
			$wp_config_parent = false;
		}
		
		global $wp_version;
		
		$totalPosts = 0;
		foreach( wp_count_posts( 'post' ) as $counttype => $count ) {
			$totalPosts += $count;
		}
		$totalPages = 0;
		foreach( wp_count_posts( 'page' ) as $counttype => $count ) {
			$totalPages += $count;
		}
		$totalComments = 0;
		foreach( wp_count_comments() as $counttype => $count ) {
			$totalComments += $count;
		}
		$totalUsers = count_users();
		$totalUsers = $totalUsers['total_users'];
		
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'post_count', 'title' => 'Found ' . $totalPosts . ' posts, ' . $totalPages . ' pages, and ' . $totalComments . ' comments.' ) ) );
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'post_count', 'title' => 'Found ' . $totalUsers . ' user accounts.' ) ) );
		
		$dat_content = array(
			
			// Backup Info.
			'backupbuddy_version'		=> pb_backupbuddy::settings( 'version' ),
			'wordpress_version'			=> $wp_version,													// WordPress version.
			'backup_time'				=> $this->_backup['start_time'],								// Time backup began.
			'backup_type'				=> $this->_backup['type'],										// Backup type: full, db, files
			'profile'					=> $this->_backup['profile'],									// Array of profile settings.
			'default_profile'			=> pb_backupbuddy::$options['profiles'][0],						// Default profile.
			'serial'					=> $this->_backup['serial'],									// Unique identifier (random) for this backup.
			'trigger'					=> $trigger,													// What triggered this backup. Valid values: scheduled, manual.
			'wp-config_in_parent'		=> $wp_config_parent,											// Whether or not the wp-config.php file is in one parent directory up. If in parent directory it will be copied into the temp serial directory along with the .sql and DAT file. On restore we will NOT place in a parent directory due to potential permission issues, etc. It will be moved into the normal location. Value set to true later in this function if applicable.
			'deployment_direction'		=> $this->_backup['deployment_direction'],						// Deployment direction, if any.
			
			// WordPress Info.
			'abspath'					=> ABSPATH,
			'siteurl'					=> site_url(),
			'homeurl'					=> home_url(),
			'blogname'					=> get_option( 'blogname' ),
			'blogdescription'			=> get_option( 'blogdescription' ),
			'active_plugins'			=> implode( ', ', get_option( 'active_plugins' ) ),				// List of active plugins at time of backup.
			'posts'						=> $totalPosts,													// Total WP posts, publishes, draft, private, trash, etc.
			'pages'						=> $totalPages,													// Total WP pages, publishes, draft, private, trash, etc.
			'comments'					=> $totalComments,												// Total WP comments, approved, spam, etc
			'users'						=> $totalUsers,													// Total users on site.
			'wp_content_url'			=> WP_CONTENT_URL,
			'wp_content_dir'			=> WP_CONTENT_DIR,
			
			// Database Info. Remaining sensitive info added in after printing out DAT (for security).
			'db_charset'				=> $wpdb->charset,												// Charset of the database. Eg utf8, utfmb4. @since v6.0.0.6.
			'db_collate'				=> $wpdb->collate,												// Collate of the database. Eg utf8, utfmb4. @since v6.0.0.6.
			'db_prefix'					=> $wpdb->prefix,												// DB prefix. Eg: wp_
			'db_server'					=> DB_HOST,														// DB host / server address.
			'db_name'					=> DB_NAME,														// DB name.
			'db_user'					=> '',															// Set several lines down after printing out DAT.
			'db_password'				=> '',															// Set several lines down after printing out DAT.
			'db_exclusions'				=> implode( ',', backupbuddy_core::get_mysqldump_additional( 'excludes', $this->_backup['profile'] ) ),
			'db_inclusions'				=> implode( ',', backupbuddy_core::get_mysqldump_additional( 'includes', $this->_backup['profile'] ) ),
			'db_version'				=> $wpdb->db_version(),											// Database server (mysql) version.
			'breakout_tables'			=> $this->_backup['breakout_tables'],							// Tables broken out into individual backup steps.
			'tables_sizes'				=> $this->_backup['table_sizes'],								// Tables backed up and their sizes.
			'force_single_db_file'		=> $this->_backup['force_single_db_file'],						// Tables forced into a single db_1.sql file.
			
			// Multisite Info.
			'is_multisite' 				=> $is_multisite,												// Full Network backup?
			'is_multisite_export' 		=> $is_multisite_export,										// Subsite backup (export)?
			'domain'					=> is_object( $current_blog ) ? $current_blog->domain : '',		// Ex: bob.com
			'path'						=> is_object( $current_blog ) ? $current_blog->path : '',		// Ex: /wordpress/
			'upload_url' 				=> $upload_url,  												// Pretty URL.
			'upload_url_rewrite' 		=> $upload_url_rewrite,											// Real existing URL that the pretty URL will be rewritten to.
			
			// ImportBuddy Options.
			// 'import_display_previous_values'	=>	pb_backupbuddy::$options['import_display_previous_values'],	// Whether or not to display the previous values from the source on import. Useful if customer does not want to blatantly display previous values to anyone restoring the backup.
			
		); // End setting $dat_content.
		
		
		// If currently using SSL or forcing admin SSL then we will check the hardcoded defined URL to make sure it matches.
		if ( is_ssl() OR ( defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN == true ) ) {
			$dat_content['siteurl'] = get_option('siteurl');
			pb_backupbuddy::status( 'details', __('Compensating for SSL in siteurl.', 'it-l10n-backupbuddy' ) );
		}
		
		// Output for troubleshooting.
		pb_backupbuddy::status( 'details', 'DAT file contents (sans database user/pass): ' . str_replace( "\n", '; ', print_r( $dat_content, true ) ) );
		
		// Remaining DB settings.
		$dat_content['db_user'] = DB_USER;
		$dat_content['db_password'] = DB_PASSWORD;
		
		
		// Serialize .dat file array.
		$dat_content = base64_encode( serialize( $dat_content ) );
		
		// Write data to the dat file.
		$dat_file = $this->_backup['temp_directory'] . 'backupbuddy_dat.php';
		if ( false === ( $file_handle = fopen( $dat_file, 'w' ) ) ) {
			pb_backupbuddy::status( 'details', sprintf( __('Error #9017: Temp data file is not creatable/writable. Check your permissions. (%s)', 'it-l10n-backupbuddy' ), $dat_file  ) );
			pb_backupbuddy::status( 'error', 'Temp data file is not creatable/writable. Check your permissions. (' . $dat_file . ')', '9017' );
			return false;
		}
		fwrite( $file_handle, "<?php die('Access Denied.'); // <!-- ?>\n" . $dat_content );
		fclose( $file_handle );
		
		pb_backupbuddy::status( 'details', __('Finished creating DAT (data) file.', 'it-l10n-backupbuddy' ) );
		
		return true;
		
	} // End backup_create_dat_file().
	
	
	
	/*	backup_create_database_dump()
	 *	
	 *	Prepares configuration and passes to the mysqlbuddy library to handle backing up the database.
	 *	Automatically handles falling back to compatibility modes.
	 *	
	 *	@return		boolean				True on success; false otherwise.
	 */
	function backup_create_database_dump( $tables, $rows_start = 0 ) {
		
		pb_backupbuddy::status( 'milestone', 'start_database' );
		pb_backupbuddy::status( 'message', __('Starting database backup process.', 'it-l10n-backupbuddy' ) );
		
		if ( 'php' == pb_backupbuddy::$options['database_method_strategy'] ) {
			$force_methods = array( 'php' );
		} elseif ( 'commandline' == pb_backupbuddy::$options['database_method_strategy'] ) {
			$force_methods = array( 'commandline' );
		} elseif ( 'all' == pb_backupbuddy::$options['database_method_strategy'] ) {
			$force_methods = array( 'php', 'commandline' );
		} else {
			pb_backupbuddy::status( 'error', 'Error #48934: Invalid forced database dump method setting: `' . pb_backupbuddy::$options['database_method_strategy'] . '`.' );
			return false;
		}
		
		$maxExecution = backupbuddy_core::adjustedMaxExecutionTime();
		if ( $this->_backup['profile']['backup_mode'] == '1' ) { // Disable DB chunking when in classic mode.
			$maxExecution = -1;
		}
		
		// Load mysqlbuddy and perform dump.
		require_once( pb_backupbuddy::plugin_path() . '/lib/mysqlbuddy/mysqlbuddy.php' );
		global $wpdb;
		pb_backupbuddy::$classes['mysqlbuddy'] = new pb_backupbuddy_mysqlbuddy( DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, $wpdb->prefix, $force_methods, $maxExecution ); // $database_host, $database_name, $database_user, $database_pass, $old_prefix, $force_method = array()
		
		// Force to single db_1.sql file if enabled via advanced options.
		if ( '1' == pb_backupbuddy::$options['force_single_db_file'] ) {
			pb_backupbuddy::$classes['mysqlbuddy']->force_single_db_file( true );
		}
		
		// Do the database dump.
		$result = pb_backupbuddy::$classes['mysqlbuddy']->dump( $this->_backup['temp_directory'], $tables, $rows_start ); // if array, returns tables,rowstart
		
		if ( is_array( $result ) ) { // Chunking.
			$newStep = array(
				'function'		=>	'backup_create_database_dump',
				'args'			=>	array( $result[0], $result[1] ),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			//error_log( print_r( $this->_backup_options->options['steps'], true ) );
			array_splice( $this->_backup_options->options['steps'], $this->_currentStepIndex + 1, 0, array( $newStep ) );
			//error_log( print_r( $this->_backup_options->options['steps'], true ) );
			$this->_backup_options->save();
			pb_backupbuddy::status( 'details', 'Inserted additional database dump step at `' . ( $this->_currentStepIndex + 1 ) . '` to resume at row `' . $result[1] . '`. The next chunk will proceed shortly.' );
		}
		
		// Made it this far so not chunking at this point.
		
		// Check and make sure mysql server is still around. If it's missing at this point we may not be able to trust that it succeeded properly.
		/*
		// REMOVED 3-3-2014. PHP dump now checks connection status after each table and command line is not related to this. Settings are all stored in fileoptions so this is no longer relevant.
		
		global $wpdb;
		if ( @mysql_ping( $wpdb->dbh ) === false ) { // No longer connected to database if false.
			pb_backupbuddy::status( 'error', __( 'ERROR #9027b: The mySQL server went away at some point during the database dump step. This is almost always caused by mySQL running out of memory or the mysql server timing out far too early. Contact your host. The database dump integrity can no longer be guaranteed so the backup has been halted.' ) );
			if ( $result === true ) {
				pb_backupbuddy::status( 'details', 'The database dump reported SUCCESS prior to this problem.' );
			} else {
				pb_backupbuddy::status( 'details', 'The database dump reported FAILURE prior to this problem.' );
			}
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			return false;
		}
		*/
		
		
		return $result;

	} // End backup_create_database_dump().
	
	
	
	/*	backup_zip_files()
	 *	
	 *	Create ZIP file containing everything.
	 *	Currently this is just a wrapper around zip system specific functions
	 *	
	 *	@return		boolean			True on success; false otherwise.
	 */
	function backup_zip_files( $state = array() ) {
	
		if ( isset( pb_backupbuddy::$options['alternative_zip_2'] ) && ( '1' == pb_backupbuddy::$options['alternative_zip_2'] ) ) {
		
			$result = $this->backup_zip_files_alternate( $state );
			
		} else {
		
			$result = $this->backup_zip_files_standard();
			
		}

		return $result;
		
	} // End backup_zip_files().
	
	/*	backup_zip_files_standard()
	 *	
	 *	Create ZIP file containing everything.
	 *	
	 *	@return		boolean			True on success; false otherwise.
	 */
	protected function backup_zip_files_standard() {
		
		pb_backupbuddy::status( 'milestone', 'start_files' );
		pb_backupbuddy::status( 'details', 'Backup root: `' . $this->_backup['backup_root'] . '`.' );
		
		// Set compression on / off.
		//pb_backupbuddy::$classes['zipbuddy']->set_compression( $this->_backup['compression'] );
		
		
		// Calculate some statistics to store in meta later. These need to be calculated before zipping in case the DB goes away later to prevent a possible failure.
		$totalPosts = 0;
		foreach( wp_count_posts( 'post' ) as $type => $count ) {
			$totalPosts += $count;
		}
		$totalPages = 0;
		foreach( wp_count_posts( 'page' ) as $type => $count ) {
			$totalPages += $count;
		}
		$totalComments = 0;
		foreach( wp_count_comments() as $type => $count ) {
			$totalComments += $count;
		}
		$totalUsers = count_users();
		$totalUsers = $totalUsers['total_users'];
		
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		
		
		// Create zip file!
		$zip_response = pb_backupbuddy::$classes['zipbuddy']->add_directory_to_zip(
			$this->_backup['archive_file'],									// string	Zip file to create.
			$this->_backup['backup_root'],									// string	Directory to zip up (root).
			$this->_backup['directory_exclusions'],							// array	Files/directories to exclude. (array of strings).
			$this->_backup['temporary_zip_directory']						// string	Temp directory location to store zip file in.
		);
		
		
		// Zip results.
		if ( $zip_response === true ) { // Zip success.
			pb_backupbuddy::status( 'message', __('Backup ZIP file successfully created.', 'it-l10n-backupbuddy' ) );
			if ( chmod( $this->_backup['archive_file'], 0644) ) {
				pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 succeeded.', 'it-l10n-backupbuddy' ) );
			} else {
				pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 failed.', 'it-l10n-backupbuddy' ) );
			}
			
			// Save meta information in comment.
			if ( '0' == pb_backupbuddy::$options['save_comment_meta'] ) {
				pb_backupbuddy::status( 'details', 'Skipping saving meta data to zip comment based on settings.' );
			} else {
				pb_backupbuddy::status( 'details', 'Saving meta data to zip comment.' );
				
				
				
				global $wp_version;
				$meta = array(
						'serial'		=>	$this->_backup['serial'],
						'siteurl'		=>	site_url(),
						'type'			=>	$this->_backup['type'],
						'profile'		=>	$this->_backup['profile']['title'],
						'created'		=>	$this->_backup['start_time'],
						'db_prefix'		=>	$db_prefix,
						'bb_version'	=>	pb_backupbuddy::settings( 'version' ),
						'wp_version'	=>	$wp_version,
						'dat_path'		=>	str_replace( $this->_backup['backup_root'], '', $this->_backup['temp_directory'] . 'backupbuddy_dat.php' ),
						'posts'			=>	$totalPosts,
						'pages'			=>	$totalPages,
						'comments'		=>	$totalComments,
						'users'			=>	$totalUsers,
						'note'			=>	'',
					);
				$comment = backupbuddy_core::normalize_comment_data( $meta );
				pb_backupbuddy::status( 'startAction', 'zipCommentMeta' );
				$comment_result = pb_backupbuddy::$classes['zipbuddy']->set_comment( $this->_backup['archive_file'], $comment );
				pb_backupbuddy::status( 'finishAction', 'zipCommentMeta' );
				if ( $comment_result !== true ) {
					pb_backupbuddy::status( 'warning', 'Unable to save meta data to zip comment. This is not a fatal warning & will not impact the backup itself.' );
				} else {
					pb_backupbuddy::status( 'details', 'Saved meta data to zip comment.' );
				}
			}
			
		} else { // Zip failure.
			
			// Delete temporary data directory.
			if ( file_exists( $this->_backup['temp_directory'] ) ) {
				pb_backupbuddy::status( 'details', __('Removing temp data directory.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temp_directory'] );
			}
			
			// Delete temporary ZIP directory.
			if ( file_exists( $this->_backup['temporary_zip_directory'] ) ) {
				pb_backupbuddy::status( 'details', __('Removing temp zip directory.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temporary_zip_directory'] );
			}
			
			pb_backupbuddy::status( 'error', __('Error #4001: Unable to successfully generate ZIP archive. Backup FAILED. See logs above for more information.', 'it-l10n-backupbuddy' ) );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			return false;
			
		} // end zip failure.
		
		
		// Need to make sure the database connection is active. Sometimes it goes away during long bouts doing other things -- sigh.
		// This is not essential so use include and not require (suppress any warning)
		@include_once( pb_backupbuddy::plugin_path() . '/lib/wpdbutils/wpdbutils.php' );
		if ( class_exists( 'pluginbuddy_wpdbutils' ) ) {
			// This is the database object we want to use
			global $wpdb;
			
			// Get our helper object and let it use us to output status messages
			$dbhelper = new pluginbuddy_wpdbutils( $wpdb );
			
			// If we cannot kick the database into life then signal the error and return false which will stop the backup
			// Otherwise all is ok and we can just fall through and let the function return true
			if ( !$dbhelper->kick() ) {
				pb_backupbuddy::status( 'error', __('Backup FAILED. Backup file produced but Database Server has gone away, unable to schedule next backup step', 'it-l10n-backupbuddy' ) );
				return false;
			} else {
				//pb_backupbuddy::status( 'details', 'Database seems to still be connected.' );
			}
		} else {
			// Utils not available so cannot verify database connection status - just notify
			pb_backupbuddy::status( 'details', __('Database Server connection status unverified.', 'it-l10n-backupbuddy' ) );
		}
		
		return true;
		
	} // End backup_zip_files_standard().
	
	/*	backup_zip_files_alternate()
	 *	
	 *	Create ZIP file containing everything.
	 *	
	 *	@param		array		$state	Zip creation state information for subsequent steps
	 *	@return		bool|array			True on success (completion); state array for continuation; false on failure.
	 */
	protected function backup_zip_files_alternate( $state = array() ) {
	
		// Dependent on the zip build strategy chosen we will need to set various operational
		// parameters on the zipbuddy object to be used by the method building the zip. Eventually
		// we can use strategy objects but for now we'll do it the old-fashioned way.
		// Strategies are:
		// Single-Burst/Single-Step: Step Period = Infinite; Min/Max Burst Content Size = Infinite;
		// Multi-Burst/Single-Step: Step Period = Infinite; Min/Max Burst Content Size = Per-Config;
		// Multi-Burst/Multi-Step: Step Period = Per-Config; Min/Max Burst Content Size = Per-Config;
		
		$zip_build_strategy_name = array( self::ZIP_BUILD_STRATEGY_SBSS => 'Single-Burst/Single-Step',
										  self::ZIP_BUILD_STRATEGY_MBSS => 'Multi-Burst/Single-Step',
										  self::ZIP_BUILD_STRATEGY_MBMS => 'Multi-Burst/Multi-Step',
										  );
		
		// Get the current strategy
		if ( isset( pb_backupbuddy::$options['zip_build_strategy'] ) ) {
		
			$zip_build_strategy = pb_backupbuddy::$options['zip_build_strategy'];
			if ( ( self::ZIP_BUILD_STRATEGY_MIN > $zip_build_strategy ) || ( self::ZIP_BUILD_STRATEGY_MAX < $zip_build_strategy ) ) {
			
				// Hmm, not valid - have to revert to default
				$zip_build_strategy = self::ZIP_BUILD_STRATEGY_MBSS;
				pb_backupbuddy::status( 'details', 'Zip Build Strategy not recognized - reverting to: ' . $zip_build_strategy_name[ $zip_build_strategy ] );
			
			} else {
			
				pb_backupbuddy::status( 'details', 'Zip Build Strategy: ' . $zip_build_strategy_name[ $zip_build_strategy ] );
			
			}
		
		} else {
		
			// Hmm, should be set - have to revert to default
			$zip_build_strategy = self::ZIP_BUILD_STRATEGY_MBSS;
			pb_backupbuddy::status( 'details', 'Zip Build Strategy not set - reverting to: ' . $zip_build_strategy_name[ $zip_build_strategy ]  );
		
		}
		
		// Now we haev to check if running in Classic mode. If yes then we cannot use multi-step without continually
		// resetting the "start" time for the zip monitor. The better approach is to override the zip build strategy
		// if it is a multi-step strategy and at least revert it to multi-burst/single-step. If it is already this
		// or single-burst/single-step we can leave it as it is
		// The backup mode details _should_ be available through this class variable created in pre_backup() function.
		if ( $this->_backup['profile']['backup_mode'] == '1' ) {
		
			// Running in Classic mode...
			if ( self::ZIP_BUILD_STRATEGY_MBSS < $zip_build_strategy ) {
			
				$zip_build_strategy = self::ZIP_BUILD_STRATEGY_MBSS;
				pb_backupbuddy::status( 'details', 'Zip Build Strategy overridden as incompatible with Classic backup mode - reverting to: ' . $zip_build_strategy_name[ $zip_build_strategy ]  );
			
			}
		
		}
		
		// Now based on the stratgy set build parameters that we will set on the zipbuddy object that
		// define the zip build behaviour
		switch ( $zip_build_strategy ) {
		
			case self::ZIP_BUILD_STRATEGY_SBSS:
				$step_period = PHP_INT_MAX; // Effectively infinite
				$burst_min_content = ( 4 == PHP_INT_SIZE ) ? (double)( pow(2, 63) - 1 ) : (double)PHP_INT_MAX ; // Hack to get large value for either 32 or 64 bit PHP
				$burst_max_content = ( 4 == PHP_INT_SIZE ) ? (double)( pow(2, 63) - 1 ) : (double)PHP_INT_MAX ;
				break;
			case self::ZIP_BUILD_STRATEGY_MBSS:
				$step_period = PHP_INT_MAX;
				$burst_min_content = null;
				$burst_max_content = null;
				break;
			case self::ZIP_BUILD_STRATEGY_MBMS:
				$step_period = null; // Force the option value to be used
				$burst_min_content = null;
				$burst_max_content = null;
				break;
		
		}
		
		// We can set the values on the zipbuddy object at this point
		pb_backupbuddy::$classes['zipbuddy']->set_step_period( $step_period );
		pb_backupbuddy::$classes['zipbuddy']->set_min_burst_content( $burst_min_content );
		pb_backupbuddy::$classes['zipbuddy']->set_max_burst_content( $burst_max_content );
	
		if ( empty( $state ) ) {
		
			// This is our first (and perhaps only) call, so do first time stuff
		
			pb_backupbuddy::status( 'milestone', 'start_files' );
			pb_backupbuddy::status( 'details', 'Backup root: `' . $this->_backup['backup_root'] . '`.' );
		
			// Set compression on / off.
			//pb_backupbuddy::$classes['zipbuddy']->set_compression( $this->_backup['compression'] );
			
			// Currently we'll still allow skipping the addition of the meta data in the comment
			// but eventually this will become mandatory (in al likelihood)
			
			// Save meta information in comment.
			if ( '0' == pb_backupbuddy::$options['save_comment_meta'] ) {
			
				pb_backupbuddy::status( 'details', 'Skipping saving meta data to zip comment based on settings.' );
				
				$comment = '';
				
			} else {
			
				pb_backupbuddy::status( 'details', 'Saving meta data to zip comment.' );
				
				// Calculate some statistics to store in meta later. These need to be calculated before zipping in case the DB goes away later to prevent a possible failure.
				$totalPosts = 0;
				foreach( wp_count_posts( 'post' ) as $type => $count ) {
					$totalPosts += $count;
				}
				$totalPages = 0;
				foreach( wp_count_posts( 'page' ) as $type => $count ) {
					$totalPages += $count;
				}
				$totalComments = 0;
				foreach( wp_count_comments() as $type => $count ) {
					$totalComments += $count;
				}
				$totalUsers = count_users();
				$totalUsers = $totalUsers['total_users'];
		
				global $wpdb;
				$db_prefix = $wpdb->prefix;
		
				global $wp_version;
				$meta = array(
						'serial'		=>	$this->_backup['serial'],
						'siteurl'		=>	site_url(),
						'type'			=>	$this->_backup['type'],
						'profile'		=>	$this->_backup['profile']['title'],
						'created'		=>	$this->_backup['start_time'],
						'db_prefix'		=>	$db_prefix,
						'bb_version'	=>	pb_backupbuddy::settings( 'version' ),
						'wp_version'	=>	$wp_version,
						'dat_path'		=>	str_replace( $this->_backup['backup_root'], '', $this->_backup['temp_directory'] . 'backupbuddy_dat.php' ),
						'posts'			=>	$totalPosts,
						'pages'			=>	$totalPages,
						'comments'		=>	$totalComments,
						'users'			=>	$totalUsers,
						'note'			=>	'',
					);
					
				$comment = backupbuddy_core::normalize_comment_data( $meta );
				
			}

			// Always create the empty zip archive with the optional meta data comment added at this point.
			// This is method independent so is done just in zipbuddy.
			$zip_response = pb_backupbuddy::$classes['zipbuddy']->create_empty_zip(
				$this->_backup['archive_file'],									// string	Zip file to create.
				$this->_backup['temporary_zip_directory'],						// string	Temp directory location to store zip file in.
				$comment
			);
			
			if ( false === $zip_response ) {
			
				// Delete temporary data directory.
				if ( file_exists( $this->_backup['temp_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp data directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temp_directory'] );
				}
		
				// Delete temporary ZIP directory.
				if ( file_exists( $this->_backup['temporary_zip_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp zip directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temporary_zip_directory'] );
				}
		
				pb_backupbuddy::status( 'error', __('Error #4001: Unable to successfully generate ZIP archive. Backup FAILED. See logs above for more information.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			
			}
		
			// Create zip file!
			$zip_response = pb_backupbuddy::$classes['zipbuddy']->add_directory_to_zip(
				$this->_backup['archive_file'],									// string	Zip file to create.
				$this->_backup['backup_root'],									// string	Directory to zip up (root).
				$this->_backup['directory_exclusions'],							// array	Files/directories to exclude. (array of strings).
				$this->_backup['temporary_zip_directory']						// string	Temp directory location to store zip file in.
			);
		
		
			// Zip results.
			if ( $zip_response === true ) { // Zip success.
				pb_backupbuddy::status( 'message', __('Backup ZIP file successfully created.', 'it-l10n-backupbuddy' ) );
				if ( chmod( $this->_backup['archive_file'], 0644) ) {
					pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 succeeded.', 'it-l10n-backupbuddy' ) );
				} else {
					pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 failed.', 'it-l10n-backupbuddy' ) );
				}
							
			} elseif ( is_array( $zip_response ) ) {
			
				// First step returned a continuation state so we only do some stuff and then queue
				// a continuation step
								
				// Now recover the returned state and queue the next step.
				$newStep = array(
					'function'		=>	'backup_zip_files',
					'args'			=>	array( $zip_response ),
					'start_time'	=>	0,
					'finish_time'	=>	0,
					'attempts'		=>	0,
				);
				//error_log( print_r( $this->_backup_options->options['steps'], true ) );
				array_splice( $this->_backup_options->options['steps'], $this->_currentStepIndex + 1, 0, array( $newStep ) );
				//error_log( print_r( $this->_backup_options->options['steps'], true ) );
				$this->_backup_options->save();
				pb_backupbuddy::status( 'details', 'Inserted additional zip grow step at `' . ( $this->_currentStepIndex + 1 ) . '` to resume at index `' . $zip_response['zipper']['fp'] . '`. The next chunk will proceed shortly.' );
				
			} else { // Zip failure.
			
				// Delete temporary data directory.
				if ( file_exists( $this->_backup['temp_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp data directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temp_directory'] );
				}
			
				// Delete temporary ZIP directory.
				if ( file_exists( $this->_backup['temporary_zip_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp zip directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temporary_zip_directory'] );
				}
			
				pb_backupbuddy::status( 'error', __('Error #4001: Unable to successfully generate ZIP archive. Backup FAILED. See logs above for more information.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			
			} // end zip failure.
			
		} else {
		
			// Continuation step with a state
			$zip_response = pb_backupbuddy::$classes['zipbuddy']->grow_zip(
				$this->_backup['archive_file'],									// string	Zip file to create.
				$this->_backup['temporary_zip_directory'],						// string	Temp directory location to store zip file in.
				$state
			);
			
			if ( $zip_response === true ) { // Zip success.
				pb_backupbuddy::status( 'message', __('Backup ZIP file successfully created.', 'it-l10n-backupbuddy' ) );
				if ( chmod( $this->_backup['archive_file'], 0644) ) {
					pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 succeeded.', 'it-l10n-backupbuddy' ) );
				} else {
					pb_backupbuddy::status( 'details', __('Chmod of ZIP file to 0644 failed.', 'it-l10n-backupbuddy' ) );
				}
				
			} elseif ( is_array( $zip_response ) ) {
			
				// First step returned a continuation state so we only do some stuff and then queue
				// a continuation step
								
				// Now recover the returned state and queue the next step.
				$newStep = array(
					'function'		=>	'backup_zip_files',
					'args'			=>	array( $zip_response ),
					'start_time'	=>	0,
					'finish_time'	=>	0,
					'attempts'		=>	0,
				);
				//error_log( print_r( $this->_backup_options->options['steps'], true ) );
				array_splice( $this->_backup_options->options['steps'], $this->_currentStepIndex + 1, 0, array( $newStep ) );
				//error_log( print_r( $this->_backup_options->options['steps'], true ) );
				$this->_backup_options->save();
				pb_backupbuddy::status( 'details', 'Inserted additional zip grow step at `' . ( $this->_currentStepIndex + 1 ) . '` to resume at index `' . $zip_response['zipper']['fp'] . '`. The next chunk will proceed shortly.' );
				
			} else { // Zip failure.
			
				// Delete temporary data directory.
				if ( file_exists( $this->_backup['temp_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp data directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temp_directory'] );
				}
			
				// Delete temporary ZIP directory.
				if ( file_exists( $this->_backup['temporary_zip_directory'] ) ) {
					pb_backupbuddy::status( 'details', __('Removing temp zip directory.', 'it-l10n-backupbuddy' ) );
					pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temporary_zip_directory'] );
				}
			
				pb_backupbuddy::status( 'error', __('Error #4001: Unable to successfully generate ZIP archive. Backup FAILED. See logs above for more information.', 'it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return false;
			
			} // end zip failure.			
		
		}
		
		
		// Need to make sure the database connection is active. Sometimes it goes away during long bouts doing other things -- sigh.
		// This is not essential so use include and not require (suppress any warning)
		@include_once( pb_backupbuddy::plugin_path() . '/lib/wpdbutils/wpdbutils.php' );
		if ( class_exists( 'pluginbuddy_wpdbutils' ) ) {
			// This is the database object we want to use
			global $wpdb;
			
			// Get our helper object and let it use us to output status messages
			$dbhelper = new pluginbuddy_wpdbutils( $wpdb );
			
			// If we cannot kick the database into life then signal the error and return false which will stop the backup
			// Otherwise all is ok and we can just fall through and let the function return true
			if ( !$dbhelper->kick() ) {
				pb_backupbuddy::status( 'error', __('Backup FAILED. Backup file produced but Database Server has gone away, unable to schedule next backup step', 'it-l10n-backupbuddy' ) );
				return false;
			} else {
				//pb_backupbuddy::status( 'details', 'Database seems to still be connected.' );
			}
		} else {
			// Utils not available so cannot verify database connection status - just notify
			pb_backupbuddy::status( 'details', __('Database Server connection status unverified.', 'it-l10n-backupbuddy' ) );
		}
		
		return $zip_response;
		
	} // End backup_zip_files_alternate().

	/*	trim_old_archives()
	 *	
	 *	Get rid of excess archives based on user-defined parameters.
	 *	!!!!! IMPORTANT: The order of application of archivel limiting is important. Users have configured their settings based on the defined order on the Settings page. !!!!!
	 *	
	 *	@param		
	 *	@return		
	 */
	function trim_old_archives() {
		
		pb_backupbuddy::status( 'details', __('Trimming old archives (if needed).', 'it-l10n-backupbuddy' ) );
		
		$summed_size = 0;
		
		$file_list = glob( backupbuddy_core::getBackupDirectory() . 'backup*.zip' );
		if ( is_array( $file_list ) && !empty( $file_list ) ) {
			foreach( (array) $file_list as $file ) {
				$file_stats = stat( $file );
				$modified_time = $file_stats['mtime'];
				$filename = str_replace( backupbuddy_core::getBackupDirectory(), '', $file ); // Just the file name.
				$files[$modified_time] = array(
													'filename'				=>		$filename,
													'size'					=>		$file_stats['size'],
													'modified'				=>		$modified_time,
												);
				$summed_size += ( $file_stats['size'] / 1048576 ); // MB
			}
		}
		unset( $file_list );
		if ( empty( $files ) ) { // return if no archives (nothing else to do).
			pb_backupbuddy::status( 'details', __( 'No old archive trimming needed.', 'it-l10n-backupbuddy' ) );
			return true;
		} else {
			krsort( $files );
		}
		
		$trim_count = 0;
		
		
		// Limit by age if set.
		if ( (int) pb_backupbuddy::$options['archive_limit_age'] > 0 ) {
			foreach( $files as $file_modified => $file ) {
				if ( ! is_numeric( $file['modified'] ) ) { // Could not get age so skipping.
					continue;
				}
				$backup_age = (int) ( time() - $file['modified'] ) / 60 / 60 / 24;
				if ( $backup_age > pb_backupbuddy::$options['archive_limit_age'] ) { // Too old; delete!
					pb_backupbuddy::status( 'details', __('Deleting old archive `' . $file['filename'] . '` as it exceeds the maximum age limit `' . pb_backupbuddy::$options['archive_limit_age'] . '` allowed at `' . $backup_age . '` days.' ) );
					unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
					unset( $files[$file_modified] );
					$trim_count++;
				} else {
					//pb_backupbuddy::status( 'details', 'Not deleted: ' . $backup_age );
				}
			}
		} // end age limit.
		
		
		// Limit by number of archives by backup type FULL.
		if ( pb_backupbuddy::$options['archive_limit_full'] > 0 ) {
			// MAY need to trim.
			$i = 0;
			foreach( $files as $file_modified => $file ) {
				if ( false !== strpos( $file['filename'], '-full-' ) ) {
					$i++;
					if ( $i > pb_backupbuddy::$options['archive_limit_full'] ) {
						pb_backupbuddy::status( 'details', sprintf( __('Deleting old archive `%s` as it causes archives to exceed total number of FULL backups allowed.', 'it-l10n-backupbuddy' ), $file['filename'] ) );
						unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
						unset( $files[$file_modified] );
						$trim_count++;
					}
				}
			}
		} // end number of archives of type FULL limit.
		
		
		// Limit by number of archives by backup type DB.
		if ( pb_backupbuddy::$options['archive_limit_db'] > 0 ) {
			// MAY need to trim.
			$i = 0;
			foreach( $files as $file_modified => $file ) {
				if ( false !== strpos( $file['filename'], '-db-' ) ) {
					$i++;
					if ( $i > pb_backupbuddy::$options['archive_limit_db'] ) {
						pb_backupbuddy::status( 'details', sprintf( __('Deleting old archive `%s` as it causes archives to exceed total number of DATABASE backups allowed.', 'it-l10n-backupbuddy' ), $file['filename'] ) );
						unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
						unset( $files[$file_modified] );
						$trim_count++;
					}
				}
			}
		} // end number of archives of type DB limit.
		
		
		// Limit by number of archives by backup type FILES.
		if ( pb_backupbuddy::$options['archive_limit_files'] > 0 ) {
			// MAY need to trim.
			$i = 0;
			foreach( $files as $file_modified => $file ) {
				if ( false !== strpos( $file['filename'], '-files-' ) ) {
					$i++;
					if ( $i > pb_backupbuddy::$options['archive_limit_files'] ) {
						pb_backupbuddy::status( 'details', sprintf( __('Deleting old archive `%s` as it causes archives to exceed total number of FILES backups allowed.', 'it-l10n-backupbuddy' ), $file['filename'] ) );
						unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
						unset( $files[$file_modified] );
						$trim_count++;
					}
				}
			}
		} // end number of archives of type FILES limit.
		
		
		// Limit by number of archives if set. Deletes oldest archives over this limit.
		if ( ( pb_backupbuddy::$options['archive_limit'] > 0 ) && ( count( $files ) ) > pb_backupbuddy::$options['archive_limit'] ) {
			// Need to trim.
			$i = 0;
			foreach( $files as $file_modified => $file ) {
				$i++;
				if ( $i > pb_backupbuddy::$options['archive_limit'] ) {
					pb_backupbuddy::status( 'details', sprintf( __('Deleting old archive `%s` as it causes archives to exceed total number allowed.', 'it-l10n-backupbuddy' ), $file['filename'] ) );
					unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
					unset( $files[$file_modified] );
					$trim_count++;
				}
			}
		} // end number of archives limit.
		
		
		// Limit by size of archives, oldest first if set.
		$files = array_reverse( $files, true ); // Reversed so we delete oldest files first as long as size limit still is surpassed; true = preserve keys.
		
		if ( 0 == pb_backupbuddy::$options['archive_limit_size'] ) { // Limit of 0. Use BIG limit.
			$lesser_limit = pb_backupbuddy::$options['archive_limit_size_big'];
		} elseif ( 0 == pb_backupbuddy::$options['archive_limit_size_big'] ) { // Big is zero. Use normal limit.
			$lesser_limit = pb_backupbuddy::$options['archive_limit_size'];
		} else { // Big is set so decide which is smaller.
			$lesser_limit = min( pb_backupbuddy::$options['archive_limit_size'], pb_backupbuddy::$options['archive_limit_size_big'] );
		}
		
		if ( ( $lesser_limit > 0 ) && ( $summed_size > $lesser_limit ) ) { // A limit was found and we exceed it.
			// Need to trim.
			foreach( $files as $file_modified => $file ) {
				if ( $summed_size > $lesser_limit ) {
					$summed_size = $summed_size - ( $file['size'] / 1048576 );
					pb_backupbuddy::status( 'details', sprintf( __('Deleting old archive `%s` due as it causes archives to exceed total size allowed.', 'it-l10n-backupbuddy' ),  $file['filename'] ) );
					if ( $file['filename'] != basename( $this->_backup['archive_file'] ) ) { // Delete excess archives as long as it is not the just-made backup.
						unlink( backupbuddy_core::getBackupDirectory() . $file['filename'] );
						unset( $files[$file_modified] );
						$trim_count++;
					} else {
						$message = __( 'ERROR #9028: Based on your backup archive limits (size limit) the backup that was just created would be deleted. Skipped deleting this backup. Please update your archive limits.' );
						pb_backupbuddy::status( 'message', $message );
						backupbuddy_core::mail_error( $message );
					}
				}
			}
		} // end combined file size limit.
		
		
		pb_backupbuddy::status( 'details', 'Trimmed ' . $trim_count . ' old archives based on settings archive limits.' );
		return true;
		
	} // End trim_old_archives().
	
	
	
	/* integrity_check()
	 *
	 * Perform integrity check on backup file to confirm backup.
	 *
	 */
	function integrity_check() {
		
		pb_backupbuddy::status( 'milestone', 'start_integrity' );
		pb_backupbuddy::status( 'message', __( 'Scanning and verifying backup file integrity.', 'it-l10n-backupbuddy' ) );
		if ( ( $this->_backup['profile']['type'] != 'files' ) && ( $this->_backup['profile']['skip_database_dump'] == '1' ) ) {
			pb_backupbuddy::status( 'warning', 'WARNING: Database .SQL file does NOT exist because the database dump has been set to be SKIPPED based on settings. Use with caution!' );
		}
		
		$options = array(
			'skip_database_dump' => $this->_backup['profile']['skip_database_dump'],
		);
		
		pb_backupbuddy::status( 'details', 'Starting integrity check on `' . $this->_backup['archive_file'] . '`.' );
		$result = backupbuddy_core::backup_integrity_check( $this->_backup['archive_file'], $this->_backup_options, $options, $skipLogRedirect = true );
		if ( false === $result['is_ok'] ) {
			$message = __( 'Backup failed to pass integrity check. The backup may have failed OR the backup may be valid but the integrity check could not verify it. This could be due to permissions, large file size, running out of memory, or other error. Verify you have not excluded one or more required files, paths, or database tables; check for warnings above in the status log.  You may wish to manually verify the backup file is functional or re-scan.', 'it-l10n-backupbuddy' );
			pb_backupbuddy::status( 'error', $message );
			
			pb_backupbuddy::status( 'details', 'Running cleanup procedure now in current step as backup procedure is halting.' );
			$this->post_backup( true ); // Post backup cleanup in fail mode.
			//pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			
			pb_backupbuddy::status( 'details', __( 'Sending integrity check failure email.', 'it-l10n-backupbuddy' ) );
			backupbuddy_core::mail_error( $message );
			
			return false;
		}
		
		pb_backupbuddy::status( 'milestone', 'finish_integrity' );
		return true;
		
	} // End integrity_check().
	
	
	
	/*	post_backup()
	 *	
	 *	Post-backup procedured. Clean up, send notifications, etc.
	 *	
	 *	@return		null
	 */
	function post_backup( $fail_mode = false, $cancel_backup = false ) {
		pb_backupbuddy::status( 'message', __('Cleaning up after backup.', 'it-l10n-backupbuddy' ) );
		
		// Delete temporary data directory.
		if ( file_exists( $this->_backup['temp_directory'] ) ) {
			pb_backupbuddy::status( 'details', __('Removing temp data directory.', 'it-l10n-backupbuddy' ) );
			pb_backupbuddy::$filesystem->unlink_recursive( $this->_backup['temp_directory'] );
		}
		// Delete temporary ZIP directory.
		if ( file_exists( backupbuddy_core::getBackupDirectory() . 'temp_zip_' . $this->_backup['serial'] . '/' ) ) {
			pb_backupbuddy::status( 'details', __('Removing temp zip directory.', 'it-l10n-backupbuddy' ) );
			pb_backupbuddy::$filesystem->unlink_recursive( backupbuddy_core::getBackupDirectory() . 'temp_zip_' . $this->_backup['serial'] . '/' );
		}
		
		
		if ( true === $fail_mode ) {
			pb_backupbuddy::status( 'warning', 'Backup archive limiting has been skipped since there was an error to avoid deleting potentially good backups to make room for a potentially bad backup.' );
		} else {
			$this->trim_old_archives(); // Clean up any old excess archives pushing us over defined limits in settings.
		}
		
		
		if ( true === $cancel_backup ) {
			pb_backupbuddy::status( 'details', 'Backup stopped so deleting backup ZIP file.' );
			$unlink_result = @unlink( $this->_backup['archive_file'] );
			if ( true === $unlink_result ) {
				pb_backupbuddy::status( 'details', 'Deleted stopped backup file.' );
			} else {
				pb_backupbuddy::status( 'error', 'Unable to delete stopped backup file. You should delete it manually as it may be damaged from stopping mid-backup. File to delete: `' . $this->_backup['archive_file'] . '`.' );
			}
			
			$this->_backup['finish_time'] = -1;
			//pb_backupbuddy::save();
			$this->_backup_options->save();
			
		} else { // Not cancelled.
			$this->_backup['archive_size'] = @filesize( $this->_backup['archive_file'] );
			pb_backupbuddy::status( 'details', __('Final ZIP file size', 'it-l10n-backupbuddy' ) . ': ' . pb_backupbuddy::$format->file_size( $this->_backup['archive_size'] ) );
			pb_backupbuddy::status( 'archiveSize', pb_backupbuddy::$format->file_size( $this->_backup['archive_size'] ) );
			
			if ( $fail_mode === false ) { // Not cancelled and did not fail so mark finish time.
				
				//error_log( print_r( $this->_backup_options->options, true ) );
				
				$archiveFile = basename( $this->_backup_options->options['archive_file'] );
				
				// Calculate backup download URL, if any.
				//$downloadURL = pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup=' . $archiveFile;
				$downloadURL = '';
				$abspath = str_replace( '\\', '/', ABSPATH ); // Change slashes to handle Windows as we store backup_directory with Linux-style slashes even on Windows.
				$backup_dir = str_replace( '\\', '/', backupbuddy_core::getBackupDirectory() );
				if ( FALSE !== stristr( $backup_dir, $abspath ) ) { // Make sure file to download is in a publicly accessible location (beneath WP web root technically).
					//pb_backupbuddy::status( 'details', 'mydir: `' . $backup_dir . '`, abs: `' . $abspath . '`.');
					$sitepath = str_replace( $abspath, '', $backup_dir );
					$downloadURL = rtrim( site_url(), '/\\' ) . '/' . trim( $sitepath, '/\\' ) . '/' . $archiveFile;
				}
				
				
				$integrityIsOK = '-1';
				if ( isset( $this->_backup_options->options['integrity']['is_ok'] ) ) {
					$integrityIsOK = $this->_backup_options->options['integrity']['is_ok'];
				}
				
				$destinations = array();
				foreach( $this->_backup_options->options['steps'] as $step ) {
					if ( 'send_remote_destination' == $step['function'] ) {
						$destinations[] = array(
											'id' => $step['args'][0],
											'title' => pb_backupbuddy::$options['remote_destinations'][ $step['args'][0] ]['title'],
											'type' => pb_backupbuddy::$options['remote_destinations'][ $step['args'][0] ]['type'],
										);
					}
				}
				
				pb_backupbuddy::status( 'details', 'Updating statistics for last backup completed and number of edits since last backup.' );
				$finishTime = microtime(true);
				pb_backupbuddy::$options['last_backup_finish'] = $finishTime;
				pb_backupbuddy::$options['last_backup_stats'] = array(
																	//'serial'			=> $this->_backup['serial'],
																	'archiveFile'		=> $archiveFile,
																	'archiveURL'		=> $downloadURL,
																	'archiveSize'		=> $this->_backup['archive_size'],
																	'start'				=> pb_backupbuddy::$options['last_backup_start'],
																	'finish'			=> $finishTime,
																	'type'				=> $this->_backup_options->options['profile']['type'],
																	'profileTitle'		=> htmlentities( $this->_backup_options->options['profile']['title'] ),
																	'scheduleTitle'		=> $this->_backup_options->options['schedule_title'], // Empty string is no schedule.
																	'integrityStatus'	=> $integrityIsOK, // 1, 0, -1 (unknown)
																	'destinations'		=> $destinations, // Index is destination ID. Empty array if none.
																);
				//error_log( print_r( pb_backupbuddy::$options['last_backup_stats'], true ) );
				pb_backupbuddy::$options['edits_since_last'] = 0; // Reset edit stats for notifying user of how many posts/pages edited since last backup happened.
				pb_backupbuddy::save();
			}
			
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
		backupbuddy_housekeeping::cleanup_temp_dir();
		
		
		if ( $this->_backup['trigger'] == 'manual' ) {
			// Do nothing. No notifications as of pre-3.0 2012.
		} elseif ( $this->_backup['trigger'] == 'deployment' ) {
			// Do nothing. No notifications.
		} elseif ( $this->_backup['trigger'] == 'deployment_pulling' ) {
			// Do nothing.
		} elseif ( $this->_backup['trigger'] == 'scheduled' ) {
			if ( ( false === $fail_mode ) && ( false === $cancel_backup ) ) {
				pb_backupbuddy::status( 'details', __( 'Sending scheduled backup complete email notification.', 'it-l10n-backupbuddy' ) );
				$message = 'completed successfully in ' . pb_backupbuddy::$format->time_duration( time() - $this->_backup['start_time'] ) . ".\n";
				backupbuddy_core::mail_notify_scheduled( $this->_backup['serial'], 'complete', __( 'Scheduled backup', 'it-l10n-backupbuddy' ) . ' "' . $this->_backup['schedule_title'] . '" ' . $message );
			}
		} else {
			pb_backupbuddy::status( 'error', 'Error #4343434. Unknown backup trigger `' . $this->_backup['trigger'] . '`.' );
		}
		
		
		pb_backupbuddy::status( 'message', __( 'Finished cleaning up.', 'it-l10n-backupbuddy' ) );
		
		if ( true === $cancel_backup ) {
			pb_backupbuddy::status( 'details', 'Backup cancellation complete.' );
			return false;
		} else {
			if ( true === $fail_mode ) {
				pb_backupbuddy::status( 'details', __( 'As this backup did not pass the integrity check you should verify it manually or re-scan. Integrity checks can fail on good backups due to permissions, large file size exceeding memory limits, etc. You may manually disable integrity check on the Settings page but you will no longer be notified of potentially bad backups.', 'it-l10n-backupbuddy' ) );
			} else {
				
				if ( ( $this->_backup['trigger'] != 'deployment' ) && ( $this->_backup['trigger'] != 'deployment_pulling' ) ) {
					//$stats = stat( $this->_backup['archive_file'] );
					//$sizeFormatted = pb_backupbuddy::$format->file_size( $stats['size'] );
					pb_backupbuddy::status(
						'archiveInfo',
						json_encode( array(
							'file' => basename( $this->_backup['archive_file'] ),
							'url' => pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup=' . basename( $this->_backup['archive_file'] ),
							//'sizeBytes' => $stats,
							//'sizeFormatted' => $sizeFormatted,
						) )
					);
				}
				
			}
		}
		
		return true;
		
	} // End post_backup().
	
	
	
	/*	send_remote_destination()
	 *	
	 *	Send the current backup to a remote destination such as S3, Dropbox, FTP, etc.
	 *	Scheduled remote sends end up coming through here before passing to core.
	 *	
	 *	@param		int		$destination_id		Destination ID (remote destination array index) to send to.
	 *	@param		boolean	$delete_after		Whether or not to delete backup file after THIS successful remote transfer.
	 *	@return		boolean						Returns result of pb_backupbuddy::send_remote_destination(). True (success) or false (failure).
	 */
	function send_remote_destination( $destination_id, $delete_after = false ) {
		
		pb_backupbuddy::status( 'details', 'Sending file to remote destination ID: `' . $destination_id . '`. Delete after: `' . $delete_after . '`.' );
		pb_backupbuddy::status( 'details', 'IMPORTANT: If the transfer is set to be chunked then only the first chunk status will be displayed during this process. Subsequent chunks will happen after this has finished.' );
		$response = backupbuddy_core::send_remote_destination( $destination_id, $this->_backup['archive_file'], '', false, $delete_after );
		
		if ( false === $response ) { // Send failure.
			$error_message = 'BackupBuddy failed sending a backup to the remote destination "' . pb_backupbuddy::$options['remote_destinations'][ $destination_id ]['title'] . '" (id: ' . $destination_id . '). Please verify and test destination settings and permissions. Check the error log for further details.';
			pb_backupbuddy::status( 'error', 'Failure sending to remote destination. Details: ' . $error_message );
			backupbuddy_core::mail_error( $error_message );
		}
		
	} // End send_remote_destination().
	
	
	
	// DEPRECATED Mar 5, 2013. - Dustin
	/*	post_remote_delete()
	 *	
	 *	Deletes backup archive. Used to delete the backup after sending to a remote destination for scheduled backups.
	 *	
	 *	@return		boolean		True on deletion success; else false.
	 */
	function post_remote_delete() {
		
		
		// DEPRECATED FUNCTION. DO NOT USE.
		
		
		pb_backupbuddy::status( 'error', 'CALL TO DEPRECATED FUNCTION post_remote_delete().' );
		pb_backupbuddy::status( 'details', 'Deleting local copy of file sent remote.' );
		if ( file_exists( $this->_backup['archive_file'] ) ) {
			unlink( $this->_backup['archive_file'] );
		}
		
		if ( file_exists( $this->_backup['archive_file'] ) ) {
			pb_backupbuddy::status( 'details', __('Error. Unable to delete local archive as requested.', 'it-l10n-backupbuddy' ) );
			return false; // Didnt delete.
		} else {
			pb_backupbuddy::status( 'details', __('Deleted local archive as requested.', 'it-l10n-backupbuddy' ) );
			return true; // Deleted.
		}
	} // End post_remote_delete().
	
	
	public function deploy_push_sendFile( $state, $sendFile, $sendPath, $sendType, $nextStep, $delete_after = false ) {
		$destination_settings = &pb_backupbuddy::$options['remote_destinations'][ $state['destination_id'] ];
		$destination_settings['sendType'] = $sendType;
		$destination_settings['sendFilePath'] = $sendPath;
		$destination_settings['max_time'] = $state['minimumExecutionTime'];
		
		$identifier = $this->_backup['serial'] . '_' . md5( $sendFile . $sendType );
		if ( false === backupbuddy_core::send_remote_destination( $state['destination_id'], $sendFile, 'Deployment', $send_importbuddy = false, $delete_after, $identifier, $destination_settings ) ) {
			$sendFile = ''; // Since failed just set file to blank so we can proceed to next without waiting.
		}
		
		$this->deploy_sendWait( $state, $sendFile, $sendPath, $sendType, $nextStep );
		return true;
	} // End deploy_push_sendFile().
	
	
	public function deploy_sendWait( $state, $sendFile, $sendPath, $sendType, $nextStep ) {
		$identifier = $this->_backup['serial'] . '_' . md5( $sendFile . $sendType );
		pb_backupbuddy::status( 'details', 'Waiting for send to finish for file `' . $sendFile . '` with ID `' . $identifier . '`.' );
		
		$maxSendTime = 60*5;
		
		if ( '' == $sendFile ) { // File failed. Proceed to next.
			$this->insert_next_step( $nextStep );
			return true;
		}
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #38.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $identifier . '.txt', $read_only = false, $ignore_lock = true, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034 E. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options; // Set reference.
		if ( '0' == $fileoptions['finish_time'] ) { // Not finished yet. Insert next chunk to wait.
			$timeAgo = ( time() - $fileoptions['start_time'] );
			if ( $timeAgo > $maxSendTime) {
				pb_backupbuddy::status( 'error', 'Error #4948348: Maximum allowed file send time of `' . $maxSendTime . '` seconds passed. Halting.' );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
			}
			pb_backupbuddy::status( 'details', 'File send not yet finished. Started `' . $timeAgo . '` seconds ago. Inserting wait.' );
			
			$newStep = array(
				'function'		=>	'deploy_sendWait',
				'args'			=>	array( $state, $sendFile, $sendPath, $sendType, $nextStep ),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->insert_next_step( $newStep );
		} else { // Finished. Go to next step.
			$this->insert_next_step( $nextStep );
		}
		
		return true;
	} // End deploy_sendWait().
	
	
	
	/* deploy_push_start()
	 *
	 * Start a deployment of this site to a remote site.
	 *
	 */
	public function deploy_push_start( $state ) {
		pb_backupbuddy::status( 'details', 'Starting PUSH deployment process. Incoming state: `' . print_r( $state, true ) . '`.' );
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'deploy_push_start', 'title' => 'Found deployment.' ) ) );
		
		
		
		// Send backup zip. It will schedule its next chunks as needed. When done it calls the nextstep.
		$nextStep = array(
			'function'		=>	'deploy_push_sendContent',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->deploy_push_sendFile( $state, $this->_backup['archive_file'], '', 'backup', $nextStep, $delete_after = true );
		
		return true;
	} // End deploy_push_start().
	
	
	
	/* deploy_pull_start()
	 *
	 * Start a deployment of this site to a remote site.
	 *
	 */
	public function deploy_pull_start( $state ) {
		pb_backupbuddy::status( 'details', 'Starting PULL deployment process. Incoming state: `' . print_r( $state, true ) . '`.' );
		pb_backupbuddy::status( 'startSubFunction', json_encode( array( 'function' => 'deploy_pull_start', 'title' => 'Found deployment.' ) ) );
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/deploy.php' );
		$deploy = new backupbuddy_deploy( $state['destinationSettings'], $state );
		
		// If not pulling any DB contents then skip making remote backup file.
		//error_log( print_r( $this->_backup['profile'], true ) );
		if ( '1' == $this->_backup['profile']['skip_database_dump'] ) {
			pb_backupbuddy::status( 'details', 'No database tables selected for pulling.  Skipping remote database snapshot (backup) zip creation and inserting file pull step.' );
			$newStep = array(
				'function'		=>	'deploy_pull_files',
				'args'			=>	array( $state ),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->insert_next_step( $newStep );
			return true;
		} else {
			pb_backupbuddy::status( 'details', 'Database tables selected for pulling. Proceeding towards remote snapshot.' );
		}
		
		// Get session token to restore so we won't be logged out. Place them in the remote backup profile array.
		global $wpdb;
		$sql = "SELECT meta_value FROM `" . DB_NAME . "`.`" . $wpdb->prefix . "usermeta` WHERE `user_id` = '" . $this->_backup['runnerUID'] . "' AND `meta_key` = 'session_tokens'";
		pb_backupbuddy::status( 'details', 'TokenSQL: ' . $sql );
		$results = $wpdb->get_var( $sql );
		$this->_backup['profile']['sessionTokens'] = unserialize( $results );
		$this->_backup['profile']['sessionID'] = $this->_backup['runnerUID'];
		//error_log( 'sourcetokens: ' . print_r( $this->_backup['profile'], true ) );
		pb_backupbuddy::status( 'details', 'Session tokens calculated.' );
		
		pb_backupbuddy::status( 'details', 'About to remote call.' );
		if ( false === ( $response = backupbuddy_remote_api::remoteCall( $state['destination'], 'runBackup', array( 'profile' => base64_encode( json_encode( $this->_backup['profile'] ) ) ), $state['minimumExecutionTime'] ) ) ) {
			pb_backupbuddy::status( 'error', 'Error #44548985: Unable to start remote backup via remote API.' );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Server response: `' . print_r( $response, true ) . '`.' );
		$remoteBackupSerial = $response['backupSerial'];
		$remoteBackupFile = $response['backupFile'];
		$this->_backup_options->options['remote_backup_file'] = $remoteBackupFile;
		pb_backupbuddy::status( 'details', 'Remote backup file: `' . $this->_backup_options->options['remote_backup_file'] . '`.' );
		$this->_backup_options->options['deployment_log'] = $remoteBackupSerial; // _getBackupStatus.php uses this serial for remote call retrival of the status log during the backup.
		pb_backupbuddy::status( 'details', 'Remote backup log: `' . $this->_backup_options->options['deployment_log'] . '`.' );
		$this->_backup_options->save();
		
		pb_backupbuddy::status( 'details', 'Inserting deploy step to wait for backup to finish on remote server.' );
		$newStep = array(
			'function'		=>	'deploy_pull_runningBackup',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $newStep );
		
		return true;
	} // End deploy_pull_start().
	
	
	
	public function deploy_pull_files( $state, $pullBackupArchive = '' ) {
		pb_backupbuddy::status( 'details', 'Starting retrieval of files from source remote site.' );
		
		
		
		// Next step will be back here after any send file waiting finishes. Keep coming back here until there is nothing more to send.
		$nextStep = array(
			'function'		=>	'deploy_pull_files',
			'args'			=>	array(), // MUST populate state before passing off this next step.
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		
		// Count up number of files remaining.
		$mediaFileCount = 0;
		$pluginFileCount = 0;
		$themeFileCount = 0;
		$childThemeFileCount = 0;
		if ( true === $state['sendMedia'] ) {
			$mediaFileCount = count( $state['pullMediaFiles'] );
		}
		if ( count( $state['sendPlugins'] > 0 ) ) {
			$pluginFileCount = count( $state['pullPluginFiles'] );
		}
		if ( true === $state['sendTheme'] ) {
			$themeFileCount = count( $state['pullThemeFiles'] );
		}
		if ( true === $state['sendChildTheme'] ) {
			$childThemeFileCount = count( $state['pullChildThemeFiles'] );
		}
		
		$filesRemaining = $mediaFileCount + $pluginFileCount + $themeFileCount + $childThemeFileCount;
		if ( '' != $pullBackupArchive ) { // add in backup archive if not yet sent.
			$filesRemaining++;
		}
		pb_backupbuddy::status( 'deployFilesRemaining', $filesRemaining );
		pb_backupbuddy::status( 'details', 'Files remaining to retrieve: ' . $filesRemaining );
		
		if ( '' != $pullBackupArchive ) {
			$nextStep['args'] = array( $state );
			$state['pullLocalArchiveFile'] = ABSPATH . $pullBackupArchive;
			$nextStep['args'] = array( $state );
			return $this->deploy_getFile( $state, dirname( $state['pullLocalArchiveFile'] ) . '/', $pullBackupArchive, 'backup', $nextStep );
		}
		
		if ( true !== $state['sendMedia'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING pull of media files.' );
		} else {
			if ( $mediaFileCount > 0 ) { // Media files remain to send.
				$getFile = array_pop( $state['pullMediaFiles'] ); // Pop off last item in array. Faster than shift.
				$wp_upload_dir = wp_upload_dir();
				$nextStep['args'] = array( $state );
				pb_backupbuddy::status( 'details', 'About to send media file.' );
				return $this->deploy_getFile( $state, $wp_upload_dir['basedir'] . '/', $getFile, 'media', $nextStep );
			}
		}
		
		if ( 0 == count( $state['sendPlugins'] ) ) {
			pb_backupbuddy::status( 'details', 'No plugin files selected for transfer. Skipping send.' );
		} else {
			if ( $pluginFileCount > 0 ) { // Plugin files remain to send.
				pb_backupbuddy::status( 'details', 'Plugins files remaining to send: ' . count( $state['pushPluginFiles'] ) );
				$getFile = array_pop( $state['pullPluginFiles'] ); // Pop off last item in array. Faster than shift.
				$pluginPath = wp_normalize_path( WP_PLUGIN_DIR ) . '/';
				$nextStep['args'] = array( $state );
				return $this->deploy_getFile( $state, $pluginPath, $getFile, 'plugin', $nextStep );
			}
		}
		
		if ( true !== $state['sendTheme'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING pull of theme files.' );
		} else {
			if ( $themeFileCount > 0 ) { // Plugin files remain to send.
				$getFile = array_pop( $state['pullThemeFiles'] ); // Pop off last item in array. Faster than shift.
				$themePath = get_template_directory(); // contains trailing slash.
				$nextStep['args'] = array( $state );
				return $this->deploy_getFile( $state, $themePath, $getFile, 'theme', $nextStep );
			}
		}
		
		if ( true !== $state['sendChildTheme'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING pull of child theme files.' );
		} else {
			if ( $childThemeFileCount > 0 ) { // Plugin files remain to send.
				$getFile = array_pop( $state['pullChildThemeFiles'] ); // Pop off last item in array. Faster than shift.
				$childThemePath = get_stylesheet_directory(); // contains trailing slash.
				$nextStep['args'] = array( $state );
				return $this->deploy_getFile( $state, $childThemePath, $getFile, 'childTheme', $nextStep );
			}
		}
		
		// If we made it here then all file sending is finished. Move on to next step.
		$nextStep = array(
			'function'		=>	'deploy_pull_renderImportBuddy',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $nextStep );
		
		
		
		
		return true;
	} // End deploy_pull_files().
	
	
	
	/* deploy_getFile()
	 *
	 * Supports chunked retrieval.
	 *
	 * @param	string	$destinationPath	Path to store file in locally. Inlude trailing slash.
	 *
	 */
	public function deploy_getFile( $state, $destinationPath, $sendFile, $sendType, $finalNextStep, $seekTo = 0 ) {
		$TIME_WIGGLE_ROOM = 5; // seconds of wiggle room around sending.
		$numSentThisRound = 0;
		
		/*
		pb_backupbuddy::status( 'details', 'Path: `' . $destinationPath . '`.' );
		pb_backupbuddy::status( 'details', 'File: `' . $sendFile . '`.' );
		pb_backupbuddy::status( 'details', 'Type: `' . $sendType . '`.' );
		*/
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
		
		$timeStart = microtime( true );
		$maxPayload = $state['destinationSettings']['max_payload']; // in MB. convert to bytes.
		$saveFile = $destinationPath . $sendFile;
		$keepSending = true;
		
		while( true == $keepSending ) {
			if ( false === ( $response = backupbuddy_remote_api::remoteCall( $state['destination'], 'getFile_' . $sendType, array( 'filename' => $sendFile, 'seekto' => $seekTo, 'maxPayload' => $maxPayload ), $state['minimumExecutionTime'] ) ) ) {
				pb_backupbuddy::status( 'error', 'Error #2373273: Unable to initiate file get via remote API.' );
				return false;
			}
			
			if ( ! is_array( $response ) ) {
				pb_backupbuddy::status( 'error', 'Error #38937324: Expected return array. Response: `' . htmlentities( $response ) . '`.' );
				return false;
			}
			
			/* $response now contains an array from the server.
			 *	filedata		string		base64 encoded file contents.
			 *	filedatalen		int			length of the filedata param.
			 *	filecrc			string		CRC of the filedata param.
			 *	filedone		1|0			1 if this is the last of the file.
			 *	filesize		int			Total file size in bytes.
			 *	resumepoint		int			ftell resume point to pass to next fseek. 0 if filedone = 1.
			 *	encoded			bool		True if filename needs to be utf8_decoded
			 */
			
			$numSentThisRound++;
			
			if ( ( 0 == $seekTo ) && ( is_file( $saveFile ) ) ) { // First or only part so delete if already exists & is a file (not directory &; not a resumed chunk).
				if ( true !== unlink( $saveFile ) ) {
					$message = 'Error #83832983: Unable to deleting existing local file to overwrite. Check permissions on file/directory `' . $saveFile . '`.';
					pb_backupbuddy::status( 'error', $message );
					return false;
				}
			}
			
			// Make sure containing directory exists.
			$saveDir = dirname( $saveFile );
			if ( ! is_dir( $saveDir ) ) {
				if ( true !== pb_backupbuddy::$filesystem->mkdir( $saveDir ) ) {
					$message = 'Error #327832: Unable to create directory `' . $saveDir . '`. Check permissions or manually create. Halting to preserve deployment integrity';
					pb_backupbuddy::status( 'error', $message );
					return false;
				}
			}
			
			// Open/create file for write/append.
			if ( false === ( $fs = fopen( $saveFile, 'a' ) )) {
				$message = 'Error #43834984: Unable to fopen file `' . $sendFile . '` in directory `' . $$destinationPath . '`.';
				pb_backupbuddy::status( 'error', $message );
				return false;
			}
			
			// Seek to specific location. fseeking rather than direct append in case there's some sort of race condition and this gets out of order in some way. Best to be specific.
			if ( 0 != fseek( $fs, $seekTo ) ) {
				@fclose( $fs );
				$message = 'Error #2373792: Unable to fseek file.';
				pb_backupbuddy::status( 'error', $message );
				return false;
			}
			
			// Write to file.
			if ( false === ( $bytesWritten = fwrite( $fs, base64_decode( $response['filedata'] ) ) ) ) { // Failed writing.
				@fclose( $fs );
				@unlink( $saveFile );
				$message = 'Error #4873474: Error writing to file `' . $saveFile . '`.';
				pb_backupbuddy::status( 'error', $message );
				return false;
			} else { // Success writing.
				@fclose( $fs );
				
				$message = 'Wrote `' . $bytesWritten . '` bytes to `' . $saveFile . '`.';
				pb_backupbuddy::status( 'details', $message );
			}
			
			$elapsed = microtime( true ) - $timeStart;
			
			// Handle finishing up or chunking if needed.
			if ( '1' == $response['filedone'] ) { // Transfer finished.
				pb_backupbuddy::status( 'deployFileSent', 'File sent.' );
				pb_backupbuddy::status( 'details', 'Retrieval of complete file + writing took `' . $elapsed .'` secs.');
				$keepSending = false;
				
				$this->insert_next_step( $finalNextStep );
				return true;
			} else { // More chunks remain.
				pb_backupbuddy::status( 'details', 'Retrieval of chunk + writing took `' . $elapsed . '` seconds. Wrote `' . pb_backupbuddy::$format->file_size( $bytesWritten ) . '` of max limit of `' . pb_backupbuddy::$format->file_size( $maxPayload ) . '` MB per chunk after seeking to `' . $seekTo . '`. Encoded size received: `' . strlen( $response['filedata'] ) . '` bytes.');
				$seekTo = $response['resumepoint']; // Next chunk fseek to this point (got by ftell).
				
				if ( ( ( $elapsed + $TIME_WIGGLE_ROOM ) * ( $numSentThisRound + 1 ) ) >= $state['minimumExecutionTime'] ) { // Could we have time to send another piece based on average send time?
					pb_backupbuddy::status( 'details', 'Not enough time to request more data this pass. Chunking.' );
					$keepSending = false;
					
					$nextStep = array(
						'function'		=>	'deploy_getFile',
						'args'			=>	array( $state, $destinationPath, $sendFile, $sendType, $finalNextStep, $seekTo ),
						'start_time'	=>	0,
						'finish_time'	=>	0,
						'attempts'		=>	0,
					);
					
					$this->insert_next_step( $nextStep );
					return true;
				} else {
					pb_backupbuddy::status( 'details', 'There appears to be enough time to get more data this pass.' );
					$keepSending = true;
				}
			}
		} // end while( true == $keepSending ).
		
	} // End deploy_getFile().
	
	
	
	/* deploy_pull_renderImportBuddy()
	 *
	 * description
	 *
	 */
	public function deploy_pull_renderImportBuddy( $state ) {
		
		if ( '' == $state['pullLocalArchiveFile'] ) {
			pb_backupbuddy::status( 'details', 'Skipping rendering of ImportBuddy step because there is no archive file. This is usually due to selecting no database tables to be pulled, therefore no import/migration needed.' );
			pb_backupbuddy::status( 'deployFinished', 'Finished.' );
			return true;
		}
		
		if ( ! file_exists( $state['pullLocalArchiveFile'] ) ) {
			pb_backupbuddy::status( 'error', 'Error #32783732: Backup file `' . $state['pullLocalArchiveFile'] . '` not found.' );
			return false;
		}
		$backupSerial = backupbuddy_core::get_serial_from_file( $state['pullLocalArchiveFile'] );
		$importbuddyPassword = md5( md5( $state['destination']['key_public'] ) );
		$siteurl = site_url();
		
		$additionalStateInfo = array(
			'maxExecutionTime' => $state['minimumExecutionTime']
		);
		
		$importFileSerial = backupbuddy_core::deploymentImportBuddy( $importbuddyPassword, $state['pullLocalArchiveFile'], $additionalStateInfo );
		if ( is_array( $importFileSerial ) ) { // Could not generate importbuddy file.
			return false;
		}
		
		// Store this serial in settings to cleanup any temp db tables in the future with this serial with periodic cleanup.
		pb_backupbuddy::$options['rollback_cleanups'][ $backupSerial ] = time();
		pb_backupbuddy::save();
		
		// Create undo file.
		$undoFile = 'backupbuddy_deploy_undo-' . $backupSerial . '.php';
		if ( false === copy( pb_backupbuddy::plugin_path() . '/classes/_rollback_undo.php', ABSPATH . $undoFile ) ) {
			$error = 'Error #3289447: Unable to write undo file `' . ABSPATH . $undoFile . '`. Check permissions on directory.' ;
			pb_backupbuddy::status( 'error', $error);
			return false;
		}
		
		// Start pulling importbuddy log.
		$importbuddyURLRoot = $siteurl . '/importbuddy-' . $importFileSerial . '.php';
		$importbuddyLogURL = $importbuddyURLRoot . '?ajax=getDeployLog&v=' . $importbuddyPassword . '&deploy=true'; //$state['destination']['siteurl'] . '/importbuddy/'?ajax=2&v=' . $importbuddyPassword . '&deploy=true; //status-' . $response['importFileSerial'] . '.txt';
		$importbuddyURL = $importbuddyURLRoot . '?ajax=2&v=' . $importbuddyPassword . '&deploy=true&direction=pull&file=' . basename( $state['pullLocalArchiveFile'] );
		pb_backupbuddy::status( 'details', 'Load importbuddy at `' . $importbuddyURLRoot . '` with verifier `' . $importbuddyPassword . '`.' );
		pb_backupbuddy::status( 'loadImportBuddy', json_encode( array( 'url' => $importbuddyURL, 'logurl' => $importbuddyLogURL ) ) );
		
		// Calculate undo URL.
		$undoDeployURL = $siteurl . '/backupbuddy_deploy_undo-' . $this->_backup['serial'] . '.php';
		pb_backupbuddy::status( 'details', 'To undo deployment of database contents go to the URL: ' . $undoDeployURL );
		pb_backupbuddy::status( 'undoDeployURL', $undoDeployURL );
		
		// Pull importbuddy log instead of remote backup log. Nothing else is going to be done on remote server.
		$this->_backup_options->options['deployment_log'] = $importbuddyLogURL;
		$this->_backup_options->save();
		
		// Next step.
		pb_backupbuddy::status( 'details', 'Inserting deploy step to run importbuddy steps on remote server.' );
		$newStep = array(
			'function'		=>	'deploy_runningImportBuddy',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $newStep );
		
		return true;
	} // End deploy_pull_renderImportBuddy().
	
	
	
	/* deploy_pull_runningBackup()
	 *
	 * Wait for remote backup to complete. Log will be pulled in via AJAX waiting for completion. AJAX will need to tell us once it looks finished so we may proceed.
	 *
	 */
	public function deploy_pull_runningBackup( $state ) {
		
		if ( $this->_backup_options->options['deployment_log'] == get_transient( 'backupbuddy_deployPullBackup_finished' ) ) {
			pb_backupbuddy::status( 'details', 'Remote destination backup completed.' );
			
			$this->_backup_options->options['deployment_log'] = ''; // Clear out deployment log so we will not keep hitting the remote server for the backup log, eg while retreiving remote files.
			$this->_backup_options->save();
			
			pb_backupbuddy::status( 'details', 'Inserting deploy step to retrieve files including remote backup archive.' );
			$newStep = array(
				'function'		=>	'deploy_pull_files',
				'args'			=>	array( $state, $pullBackupArchive = $this->_backup_options->options['remote_backup_file'] ),
				'start_time'	=>	0,
				'finish_time'	=>	0,
				'attempts'		=>	0,
			);
			$this->insert_next_step( $newStep );
			
			delete_transient( 'backupbuddy_deployPullBackup_finished' );
			return true;
		}
		
		pb_backupbuddy::status( 'details', 'Inserting deploy step to wait for backup to finish on remote server.' );
		$newStep = array(
			'function'		=>	'deploy_pull_runningBackup',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $newStep );
		
		return true;
	} // End deploy_waitingImportBuddy().
	
	
	
	/* deploy_push_sendContent()
	 *
	 * Used PUSHING.
	 *
	 */
	public function deploy_push_sendContent( $state ) {
		// Next step will be back here after any send file waiting finishes. Keep coming back here until there is nothing more to send.
		$nextStep = array(
			'function'		=>	'deploy_push_sendContent',
			'args'			=>	array(), // MUST populate state before passing off this next step.
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		
		// Count up files to send.
		$mediaFileCount = 0;
		$pluginFileCount = 0;
		$themeFileCount = 0;
		$childThemeFileCount = 0;
		if ( true === $state['sendMedia'] ) {
			$mediaFileCount = count( $state['pushMediaFiles'] );
		}
		if ( count( $state['sendPlugins'] ) > 0 ) {
			$pluginFileCount = count( $state['pushPluginFiles'] );
		}
		if ( true === $state['sendTheme'] ) {
			$themeFileCount = count( $state['pushThemeFiles'] );
		}
		if ( true === $state['sendChildTheme'] ) {
			$childThemeFileCount = count( $state['pushChildThemeFiles'] );
		}
		$filesRemaining = $mediaFileCount + $pluginFileCount + $themeFileCount + $childThemeFileCount;
		pb_backupbuddy::status( 'deployFilesRemaining', $filesRemaining );
		pb_backupbuddy::status( 'details', 'Files remaining to send: ' . $filesRemaining );
		
		if ( true !== $state['sendMedia'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING push of media files.' );
		} else {
			if ( $mediaFileCount > 0 ) { // Media files remain to send.
				$sendFile = array_pop( $state['pushMediaFiles'] ); // Pop off last item in array. Faster than shift.
				$wp_upload_dir = wp_upload_dir();
				$nextStep['args'] = array( $state );
				return $this->deploy_push_sendFile( $state, $wp_upload_dir['basedir'] . '/' . $sendFile, $sendFile, 'media', $nextStep );
			}
		}
		
		if ( 0 == count( $state['sendPlugins'] ) ) {
			pb_backupbuddy::status( 'details', 'No plugin files selected for transfer. Skipping send.' );
		} else {
			if ( $pluginFileCount > 0 ) { // Plugin files remain to send.
				pb_backupbuddy::status( 'details', 'Plugins files remaining to send: ' . count( $state['pushPluginFiles'] ) );
				$sendFile = array_pop( $state['pushPluginFiles'] ); // Pop off last item in array. Faster than shift.
				$pluginPath = wp_normalize_path( WP_PLUGIN_DIR ) . '/';
				$nextStep['args'] = array( $state );
				return $this->deploy_push_sendFile( $state, $pluginPath . $sendFile, $sendFile, 'plugin', $nextStep );
			}
		}
		
		if ( true !== $state['sendTheme'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING push of theme files.' );
		} else {
			if ( $themeFileCount > 0 ) { // Plugin files remain to send.
				$sendFile = array_pop( $state['pushThemeFiles'] ); // Pop off last item in array. Faster than shift.
				$themePath = get_template_directory(); // contains trailing slash.
				$nextStep['args'] = array( $state );
				return $this->deploy_push_sendFile( $state, $themePath . $sendFile, $sendFile, 'theme', $nextStep );
			}
		}
		
		if ( true !== $state['sendChildTheme'] ) {
			pb_backupbuddy::status( 'details', 'SKIPPING push of child theme files.' );
		} else {
			if ( $childThemeFileCount > 0 ) { // Plugin files remain to send.
				$sendFile = array_pop( $state['pushChildThemeFiles'] ); // Pop off last item in array. Faster than shift.
				$childThemePath = get_stylesheet_directory(); // contains trailing slash.
				$nextStep['args'] = array( $state );
				return $this->deploy_push_sendFile( $state, $childThemePath . $sendFile, $sendFile, 'childTheme', $nextStep );
			}
		}
		
		// If we made it here then all file sending is finished. Move on to next step.
		$nextStep = array(
			'function'		=>	'deploy_push_renderImportBuddy',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $nextStep );
		
		return true;
	} // End deploy_push_sendcontent().
	
	
	
	public function deploy_push_renderImportBuddy( $state ) {
		
		$timeout = 10;
		pb_backupbuddy::status( 'details', 'Tell remote server to render importbuddy & place our settings file.' );
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/deploy.php' );
		$deploy = new backupbuddy_deploy( $state['destinationSettings'], $state );
		
		if ( false === ( $response = backupbuddy_remote_api::remoteCall(
				$state['destination'],
				'renderImportBuddy',
				array(
					'backupFile' => basename( $this->_backup['archive_file'] ),
					'max_execution_time' => $state['minimumExecutionTime'],
				),
				$state['minimumExecutionTime']
			) ) ) {
			pb_backupbuddy::status( 'error', 'Error #4448985: Unable to render importbuddy via remote API.' );
			return false;
		
		}
		pb_backupbuddy::status( 'details', 'Render importbuddy result: `' . print_r( $response, true ) . '`.' );
		
		// Calculate importbuddy URL.
		$importbuddyPassword = md5( md5( $state['destination']['key_public'] ) ); // Double md5 like a rainbow.
		$importbuddyURLRoot = $state['destination']['siteurl'] . '/importbuddy-' . $response['importFileSerial'] . '.php';
		$importbuddyLogURL = $importbuddyURLRoot . '?ajax=getDeployLog&v=' . $importbuddyPassword . '&deploy=true'; //$state['destination']['siteurl'] . '/importbuddy/'?ajax=2&v=' . $importbuddyPassword . '&deploy=true; //status-' . $response['importFileSerial'] . '.txt';
		$importbuddyURL = $importbuddyURLRoot . '?ajax=2&v=' . $importbuddyPassword . '&deploy=true&direction=push&file=' . basename( $this->_backup['archive_file'] );
		pb_backupbuddy::status( 'details', 'Load importbuddy at `' . $importbuddyURLRoot . '` with verifier `' . $importbuddyPassword . '`.' );
		pb_backupbuddy::status( 'loadImportBuddy', json_encode( array( 'url' => $importbuddyURL, 'logurl' => $importbuddyLogURL ) ) );
		
		// Calculate undo URL.
		$undoDeployURL = $state['destination']['siteurl'] . '/backupbuddy_deploy_undo-' . $this->_backup['serial'] . '.php';
		pb_backupbuddy::status( 'details', 'To undo deployment of database contents go to the URL: ' . $undoDeployURL );
		pb_backupbuddy::status( 'undoDeployURL', $undoDeployURL );
		
		$this->_backup_options->options['deployment_log'] = $importbuddyLogURL;
		$this->_backup_options->save();
		
		pb_backupbuddy::status( 'details', 'Inserting deploy step to run importbuddy steps on remote server.' );
		$newStep = array(
			'function'		=>	'deploy_runningImportBuddy',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $newStep );
		
		return true;
		
	} // End deploy_push_renderImportBuddy().
	
	
	
	public function deploy_runningImportBuddy( $state ) {
		
		$maxImportBuddyWaitTime = 60*60*48; // 48 hrs.
		
		// Safety net just in case a loop forms.
		if ( ( time() - $state['startTime'] ) > $maxImportBuddyWaitTime ) {
			pb_backupbuddy::status( 'error', 'Error #8349484: Fatal error. ImportBuddy is taking too long to complete. Aborting deployment.' );
			return false;
		}
		
		pb_backupbuddy::status( 'details', 'Inserting deploy step to run importbuddy steps on remote server.' );
		$newStep = array(
			'function'		=>	'deploy_runningImportBuddy',
			'args'			=>	array( $state ),
			'start_time'	=>	0,
			'finish_time'	=>	0,
			'attempts'		=>	0,
		);
		$this->insert_next_step( $newStep );
		sleep( 1 ); // Sleep to insure at least a minimum pause between running importbuddy steps.
		return true;
		
	} // End deploy_waitingImportBuddy().
	
	
	/* insert_next_step()
	 *
	 * Inserts a step to run next and saves it to fileoptions.
	 *
	 * $nextStep = step array.
	 */
	private function insert_next_step( $newStep ) {
		array_splice( $this->_backup_options->options['steps'], $this->_currentStepIndex + 1, 0, array( $newStep ) );
		$this->_backup_options->save();
	} // End nextStep().
	
	
	
	/********* BEGIN MULTISITE (Exporting subsite; creates a standalone backup) *********/
	
	
	
	/*	ms_download_extract_wordpress()
	 *	
	 *	Used by Multisite Exporting.
	 *	Downloads and extracts the latest WordPress for making a standalone backup of a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean		True on success, else false.
	 */
	public function ms_download_extract_wordpress() {
		
		// Step 1 - Download a copy of WordPress.
		if ( !function_exists( 'download_url' ) ) {
			pb_backupbuddy::status( 'details', 'download_url() function not available by default. Loading `/wp-admin/includes/file.php`.' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$wp_url = 'http://wordpress.org/latest.zip';
		pb_backupbuddy::status( 'details', 'Downloading latest WordPress ZIP file from `' . $wp_url . '`.' );
		$wp_file = download_url( $wp_url );
		if ( is_wp_error( $wp_file ) ) { // Grabbing WordPress ZIP failed.
			pb_backupbuddy::status( 'error', 'Error getting latest WordPress ZIP file: `' . $wp_file->get_error_message() . '`.' );
			return false;
		} else { // Grabbing WordPress ZIP succeeded.
			//error_log ('nowperror' );
			pb_backupbuddy::status( 'details', 'Latest WordPress ZIP file successfully downloaded to `' . $wp_file . '`.' );
		}
		
		
		// Step 2 - Extract WP into a separate directory.
		if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
			pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		}
		pb_backupbuddy::status( 'details', 'About to unzip file `' . $wp_file . '` into `' . $this->_backup['backup_root'] . '`.' );
		ob_start();
		pb_backupbuddy::$classes['zipbuddy']->unzip( $wp_file, dirname( $this->_backup['backup_root'] ) );
		pb_backupbuddy::status( 'details', 'Unzip complete.' );
		pb_backupbuddy::status( 'details', 'Debugging information: `' . ob_get_clean() . '`' );
		
		@unlink( $wp_file );
		if ( file_exists( $wp_file ) ) { // Check to see if unlink() worked.
			pb_backupbuddy::status( 'warning', 'Unable to delete temporary WordPress file `' . $wp_file . '`. You may want to delete this after the backup / export completed.' );
		}
		
		return true;
		
	} // End ms_download_wordpress().
	
	
	
	/*	ms_create_wp_config()
	 *	
	 *	Used by Multisite Exporting.
	 *	Creates a standalone wp-config.php file for making a standalone backup from a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean			Currently only returns true.
	 */
	public function ms_create_wp_config() {
		
		pb_backupbuddy::status( 'message', 'Creating new wp-config.php file for temporary WordPress installation.' );
		
		global $current_blog;
		$blog_id = absint( $current_blog->blog_id );
		
		//Step 3 - Create new WP-Config File
		$to_file = "<?php\n";
		$to_file .= sprintf( "define( 'DB_NAME', '%s' );\n", '' );
		$to_file .= sprintf( "define( 'DB_USER', '%s' );\n", '' );
		$to_file .= sprintf( "define( 'DB_PASSWORD', '%s' );\n", '' );
		$to_file .= sprintf( "define( 'DB_HOST', '%s' );\n", '' );
		$charset = defined( 'DB_CHARSET' ) ? DB_CHARSET : '';
		$collate = defined( 'DB_COLLATE' ) ? DB_COLLATE : '';
		$to_file .= sprintf( "define( 'DB_CHARSET', '%s' );\n", $charset );
		$to_file .= sprintf( "define( 'DB_COLLATE', '%s' );\n", $collate );
		
		//Attempt to remotely retrieve salts
		$salts = wp_remote_get( 'https://api.wordpress.org/secret-key/1.1/salt/' );
		if ( !is_wp_error( $salts ) ) { // Success.
			$to_file .= wp_remote_retrieve_body( $salts ) . "\n";
		} else { // Failed.
			pb_backupbuddy::status( 'warning', 'Error getting salts from WordPress.org for wp-config.php. You may need to manually edit your wp-config on restore. Error: `' . $salts->get_error_message() . '`.' );
		}
		$to_file .= sprintf( "define( 'WPLANG', '%s' );\n", WPLANG );
		$to_file .= sprintf( '$table_prefix = \'%s\';' . "\n", 'bbms' . $blog_id . '_' );
		
		$to_file .= "if ( !defined('ABSPATH') ) { \n\tdefine('ABSPATH', dirname(__FILE__) . '/'); }";
		$to_file .= "/** Sets up WordPress vars and included files. */\n
		require_once(ABSPATH . 'wp-settings.php');";
		$to_file .= "\n?>";
		
		//Create the file, save, and close
		$configFile = $this->_backup['backup_root'] . 'wp-config.php';
		$file_handle = fopen( $configFile, 'w' );
		fwrite( $file_handle, $to_file );
		fclose( $file_handle );
		
		pb_backupbuddy::status( 'message', 'Temporary WordPress wp-config.php file created at `' . $configFile . '`.' );
		
		return true;
	} // End ms_create_wp_config().
	
	
	
	/*	ms_copy_plugins()
	 *	
	 *	Used by Multisite Exporting.
	 *	Copies over the selected plugins for inclusion into the backup for creating a standalone backup from a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean			True on success, else false.
	 */
	public function ms_copy_plugins() {
	
		pb_backupbuddy::status( 'message', 'Copying selected plugins into temporary WordPress installation.' );
		
		// Step 4 - Copy over plugins.
		// Move over plugins.
		$plugin_items = $this->_backup['export_plugins'];
		
		// Get plugins for site.
		$site_plugins = get_option( 'active_plugins' );
		if ( !empty( $site_plugins ) ) {
			$plugin_items['site'] = $site_plugins;
		}
		
		//Populate $items_to_copy for all plugins to copy over
		if ( is_array( $plugin_items ) ) {
			$items_to_copy = array();
			//Get content directories by using this plugin as a base
			$content_dir = $dropin_plugins_dir = dirname( dirname( dirname( rtrim( plugin_dir_path(__FILE__), '/' ) ) ) );
			$mu_plugins_dir = $content_dir . '/mu-plugins';
			$plugins_dir = $content_dir . '/plugins';
			
			//Get the special plugins (mu, dropins, network activated)
			foreach ( $plugin_items as $type => $plugins ) {
				foreach ( $plugins as $plugin ) {
					if ( $type == 'mu' ) {
						$items_to_copy[ $plugin ] = $mu_plugins_dir . '/' . $plugin;
					} elseif ( $type == 'dropin' ) {
						$items_to_copy[ $plugin ] = $dropin_plugins_dir . '/' . $plugin;
					} elseif ( $type == 'network' || $type == 'site' ) {
						//Determine if we're a folder-based plugin, or a file-based plugin (such as hello.php)
						$plugin_path = dirname( $plugins_dir . '/' . $plugin );
						if ( basename( $plugin_path ) == 'plugins' ) {
							$plugin_path = $plugins_dir . '/' . $plugin;
						}
						$items_to_copy[ basename( $plugin_path ) ] = $plugin_path;		
					}
				} //end foreach $plugins.
			} //end foreach special plugins.
			
			
			//Copy the files over
			$wp_dir = '';
			if ( count( $items_to_copy ) > 0 ) {
				$wp_dir = $this->_backup['backup_root'];
				$wp_plugin_dir = $wp_dir . 'wp-content/plugins/';
				foreach ( $items_to_copy as $file => $original_destination ) {
					if ( file_exists( $original_destination ) && file_exists( $wp_plugin_dir ) ) {
						//$this->copy( $original_destination, $wp_plugin_dir . $file ); 
						$result = pb_backupbuddy::$filesystem->recursive_copy( $original_destination, $wp_plugin_dir . $file );
						
						if ( $result === false ) {
							pb_backupbuddy::status( 'error', 'Unable to copy plugin from `' . $original_destination . '` to `' . $wp_plugin_dir . $file . '`. Verify permissions.' );
							return false;
						} else {
							pb_backupbuddy::status( 'details', 'Copied plugin from `' . $original_destination . '` to `' . $wp_plugin_dir . $file . '`.' );
						}
					}
				}
			}
			
			// Finished
			
			pb_backupbuddy::status( 'message', 'Copied selected plugins into temporary WordPress installation.' );
			return true;

		} else {
			// Nothing has technically failed at this point - There just aren't any plugins to copy over.
			
			pb_backupbuddy::status( 'message', 'No plugins were selected for backup. Skipping plugin copying.' );
			return true;
		}
		
		return true; // Shouldnt get here.
		
	} // End ms_copy_plugins().
	
	
	
	/*	ms_copy_themes()
	 *	
	 *	Used by Multisite Exporting.
	 *	Copies over the selected themes for inclusion into the backup for creating a standalone backup from a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean			True on success, else false.
	 */
	public function ms_copy_themes() {
	
		
		pb_backupbuddy::status( 'message', 'Copying theme(s) into temporary WordPress installation.' );
		
		if ( !function_exists( 'wp_get_theme' ) ) {
			pb_backupbuddy::status( 'details', 'wp_get_theme() function not found. Loading `/wp-admin/includes/theme.php`.' );
			require_once( ABSPATH . 'wp-admin/includes/theme.php' );
			pb_backupbuddy::status( 'details', 'Loaded `/wp-admin/includes/theme.php`.' );
		}
		
		// Use new wp_get_theme() if available.
		if ( function_exists( 'wp_get_theme' ) ) { // WordPress v3.4 or newer.
			pb_backupbuddy::status( 'details', 'wp_get_theme() available. Using it.' );
			$current_theme = wp_get_theme();
		} else { // WordPress pre-v3.4
			pb_backupbuddy::status( 'details', 'wp_get_theme() still unavailable (pre WordPress v3.4?). Attempting to use older current_theme_info() fallback.' );
			$current_theme = current_theme_info();
		}
		
				
		//Step 5 - Copy over themes
		$template_dir = $current_theme->template_dir;
		$stylesheet_dir = $current_theme->stylesheet_dir;
		
		pb_backupbuddy::status( 'details', 'Got current theme information.' );
		
		//If $template_dir and $stylesheet_dir don't match, that means we have a child theme and need to copy over the parent also
		$items_to_copy = array();
		$items_to_copy[ basename( $template_dir ) ] = $template_dir;
		if ( $template_dir != $stylesheet_dir ) {
			$items_to_copy[ basename( $stylesheet_dir ) ] = $stylesheet_dir;
		}
		
		pb_backupbuddy::status( 'details', 'About to begin copying theme files...' );
		
		//Copy the files over
		if ( count( $items_to_copy ) > 0 ) {
			$wp_dir = $this->_backup['backup_root'];
			$wp_theme_dir = $wp_dir . 'wp-content/themes/';
			foreach ( $items_to_copy as $file => $original_destination ) {
				if ( file_exists( $original_destination ) && file_exists( $wp_theme_dir ) ) {
					
					$result = pb_backupbuddy::$filesystem->recursive_copy( $original_destination, $wp_theme_dir . $file ); 
					
					if ( $result === false ) {
						pb_backupbuddy::status( 'error', 'Unable to copy theme from `' . $original_destination . '` to `' . $wp_theme_dir . $file . '`. Verify permissions.' );
						return false;
					} else {
						pb_backupbuddy::status( 'details', 'Copied theme from `' . $original_destination . '` to `' . $wp_theme_dir . $file . '`.' );
					}
				} // end if file exists.
			} // end foreach $items_to_copy.
		} // end if.
		
		pb_backupbuddy::status( 'message', 'Copied theme into temporary WordPress installation.' );
		return true;
		
	} // End ms_copy_themes().
	
	
	
	/*	ms_copy_media()
	 *	
	 *	Used by Multisite Exporting.
	 *	Copies over media (wp-content/uploads) for this site for inclusion into the backup for creating a standalone backup from a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean			True on success, else false.
	 */
	public function ms_copy_media() {
		
		pb_backupbuddy::status( 'message', 'Copying media into temporary WordPress installation.' );
		
		//Step 6 - Copy over media/upload files
		$upload_dir = wp_upload_dir();
		$original_upload_base_dir = $upload_dir[ 'basedir' ];
		$destination_upload_base_dir = $this->_backup['backup_root'] . 'wp-content/uploads';
		//$result = pb_backupbuddy::$filesystem->custom_copy( $original_upload_base_dir, $destination_upload_base_dir, array( 'ignore_files' => array( $this->_backup['serial'] ) ) );
		
		// Grab directory upload contents so we can exclude backupbuddy directories.
		$upload_contents = glob( $original_upload_base_dir . '/*' );
		if ( !is_array( $upload_contents ) ) {
			$upload_contents = array();
		}
				
		foreach( $upload_contents as $upload_content ) {
			if ( strpos( $upload_content, 'backupbuddy_' ) === false ) { // Dont copy over any backupbuddy-prefixed uploads directories.
				$result = pb_backupbuddy::$filesystem->recursive_copy( $upload_content, $destination_upload_base_dir . '/' . basename( $upload_content ) );
			}
		}
		
		if ( $result === false ) {
			pb_backupbuddy::status( 'error', 'Unable to copy media from `' . $original_upload_base_dir . '` to `' . $destination_upload_base_dir . '`. Verify permissions.' );
			return false;
		} else {
			pb_backupbuddy::status( 'details', 'Copied media from `' . $original_upload_base_dir . '` to `' . $destination_upload_base_dir . '`.' );
			return true;
		}
		
	} // End ms_copy_media().
	
	
	
	/*	ms_copy_users_table()
	 *	
	 *  Step 7
	 *	Used by Multisite Exporting.
	 *	Copies over users to a temp table for this site for inclusion into the backup for creating a standalone backup from a subsite.
	 *	Authored by Ron H. Modified by Dustin B.
	 *	
	 *	@return		boolean			Currently only returns true.
	 */
	public function ms_copy_users_table() {
		
		pb_backupbuddy::status( 'message', 'Copying temporary users table for users in this blog.' );

		global $wpdb, $current_blog;
		
		$new_user_tablename = $wpdb->prefix . 'users';
		$new_usermeta_tablename = $wpdb->prefix . 'usermeta';
		
		if ( $new_user_tablename == $wpdb->users ) {
			pb_backupbuddy::status( 'message', 'Temporary users table would match existing users table. Skipping creation of this temporary users & usermeta tables.' );
			return true;
		}
		
		// Copy over users table to temporary table.
		pb_backupbuddy::status( 'message', 'Created new table `' . $new_user_tablename . '` like `' . $wpdb->users . '`.' );
		$wpdb->query( "CREATE TABLE `{$new_user_tablename}` LIKE `{$wpdb->users}`" );
		$wpdb->query( "INSERT `{$new_user_tablename}` SELECT * FROM `{$wpdb->users}`" );
		
		// Copy over usermeta table to temporary table.
		pb_backupbuddy::status( 'message', 'Created new table `' . $new_usermeta_tablename . '` like `' . $wpdb->usermeta . '`.' );
		$wpdb->query( "CREATE TABLE `{$new_usermeta_tablename}` LIKE `{$wpdb->usermeta}`" );
		$wpdb->query( "INSERT `{$new_usermeta_tablename}` SELECT * FROM `{$wpdb->usermeta}`" );
		
		// Get list of users associated with this site.
		$users_to_capture = array();
		$user_args = array(
			'blog_id' => $current_blog->blog_id
		);
		$users = get_users( $user_args );
		if ( $users ) {
			foreach ( $users as $user ) {
				array_push( $users_to_capture, $user->ID );
			}
		}
		$users_to_capture = implode( ',', $users_to_capture );
		pb_backupbuddy::status( 'details', 'User IDs to capture (' . count( $users_to_capture ) . ' total): ' . print_r( $users_to_capture, true ) );
		
		// Remove users from temporary table that arent associated with this site.
		$wpdb->query( "DELETE from `{$new_user_tablename}` WHERE ID NOT IN( {$users_to_capture} )" );
		$wpdb->query( "DELETE from `{$new_usermeta_tablename}` WHERE user_id NOT IN( {$users_to_capture} )" );
		

		
		pb_backupbuddy::status( 'message', 'Copied temporary users table for users in this blog.' );
		return true;
		
	} // End ms_copy_users_table().
	
	public function ms_cleanup() {
		pb_backupbuddy::status( 'details', 'Beginning Multisite-export specific cleanup.' );
		
		global $wpdb;
		$new_user_tablename = $wpdb->prefix . 'users';
		$new_usermeta_tablename = $wpdb->prefix . 'usermeta';
		
		if ( ( $new_user_tablename == $wpdb->users ) || ( $new_usermeta_tablename == $wpdb->usermeta ) ) {
			pb_backupbuddy::status( 'error', 'Unable to clean up temporary user tables as they match main tables. Skipping to prevent data loss.' );
			return;
		}
		
		pb_backupbuddy::status( 'details', 'Dropping temporary table `' . $new_user_tablename . '`.' );
		$wpdb->query( "DROP TABLE `{$new_user_tablename}`" );
		pb_backupbuddy::status( 'details', 'Dropping temporary table `' . $new_usermeta_tablename . '`.' );
		$wpdb->query( "DROP TABLE `{$new_usermeta_tablename}`" );
		
		pb_backupbuddy::status( 'details', 'Done Multisite-export specific cleanup.' );
	}
	
	/********* END MULTISITE *********/
	
	
	
	
	
	/*	_calculate_tables()
	 *	
	 *	Takes a base level to calculate tables from.  Then adds additional tables.  Then removes any exclusions. Returns array of final table listing to backup.
	 *	
	 *	@see dump().
	 *	
	 *	@param		string		$base_dump_mode			Determines which database tables to dump by default. Valid values:  all, none, prefix
	 *	@param		array		$additional_includes	Array of additional table(s) to INCLUDE in dump. Added in addition to those found by the $base_dump_mode
	 *	@param		array		$additional_excludes	Array of additional table(s) to EXCLUDE from dump. Removed from those found by the $base_dump_mode + $additional_includes.
	 *	@return		array								Array of tables to backup.
	 */
	private function _calculate_tables( $base_dump_mode, $additional_includes = array(), $additional_excludes = array() ) {
		
		global $wpdb;
		$wpdb->show_errors(); // Turn on error display.
		
		$tables = array();
		pb_backupbuddy::status( 'details', 'Calculating mysql database tables to backup.' );
		pb_backupbuddy::status( 'details', 'Base database dump mode (before inclusions/exclusions): `' . $base_dump_mode . '`.' );
		
		// Calculate base tables.
		if ( $base_dump_mode == 'all' ) { // All tables in database to start with.
			$sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()';
			pb_backupbuddy::status( 'startAction', 'schemaTables' );
			$results = $wpdb->get_results( $sql, ARRAY_A );
			pb_backupbuddy::status( 'finishAction', 'schemaTables' );
			if ( ( null === $results ) || ( false === $results ) ) {
				pb_backupbuddy::status( 'error', 'Error #8493894a: Unable to calculate database tables with query: `' . $sql . '`. Check database permissions or contact host.' );
			}
			foreach( (array)$results as $result ) {
				array_push( $tables, $result['table_name'] );
			}
			unset( $results );
		
		} elseif ( $base_dump_mode == 'none' ) { // None to start with.
			
			// Do nothing.
			
		} elseif ( $base_dump_mode == 'prefix' ) { // Tables matching prefix.
			
			pb_backupbuddy::status( 'details', 'Determining database tables with prefix `' . $wpdb->prefix . '`.' );
			$prefix_sql = str_replace( '_', '\_', $wpdb->prefix );
			$sql = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE '{$prefix_sql}%' AND table_schema = DATABASE()";
			pb_backupbuddy::status( 'startAction', 'schemaTables' );
			$results = $wpdb->get_results( $sql, ARRAY_A );
			pb_backupbuddy::status( 'finishAction', 'schemaTables' );
			if ( ( null === $results ) || ( false === $results ) ) {
				pb_backupbuddy::status( 'error', 'Error #8493894b: Unable to calculate database tables with query: `' . $sql . '`. Check database permissions or contact host.' );
			}
			foreach( (array)$results as $result ) {
				array_push( $tables, $result['table_name'] );
			}
			unset( $results );
			
		} else { // unknown dump mode.
			
			pb_backupbuddy::status( 'error', 'Error #454545: Unknown database dump mode.' ); // Should never see this.
			
		}
		pb_backupbuddy::status( 'details', 'Base database tables based on settings (' . count( $tables ) . ' tables): `' . implode( ',', $tables ) . '`' );
		
		// Add additional tables.
		$tables = array_merge( $tables, $additional_includes );
		$tables = array_filter( $tables ); // Trim any phantom tables that the above line may have introduced.
		pb_backupbuddy::status( 'details', 'Database tables after addition (' . count( $tables ) . ' tables): `' . implode( ',', $tables ) . '`' );
		
		// Remove excluded tables.
		$tables = array_diff( $tables, $additional_excludes );
		pb_backupbuddy::status( 'details', 'Database tables after exclusion (' . count( $tables ) . ' tables): `' . implode( ',', $tables ) . '`' );
		
		return array_values( $tables ); // Clean up indexing & return.
		
	} // End _calculate_tables().
	
	
	
} // End class.
