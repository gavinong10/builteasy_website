<?php
$serial = self::get_serial_from_file( $file );
pb_backupbuddy::status( 'details', 'Started backup_integrity_check() function for `' . $serial . '` for file `' . $file . '`.' );

// User selected to rescan a file.
if ( pb_backupbuddy::_GET( 'reset_integrity' ) == $serial ) {
	pb_backupbuddy::alert( 'Rescanning backup integrity for backup file `' . basename( $file ) . '`' );
	pb_backupbuddy::flush();
}

$createdFileOptions = false;
if ( ! file_exists( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' ) ) { // No fileoptions so get some minimal information from DAT file within zip.
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	if ( ! isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
		pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getLogDirectory() . 'fileoptions/' );
	}
	
	if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $serial . '/backupbuddy_dat.php' ) === true ) { // Post 2.0 full backup
		$backup_type = 'full';
		$pass = true;
	}
	if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/temp_' . $serial . '/backupbuddy_dat.php' ) === true ) { // Pre 2.0 full backup
		$backup_type = 'full';
		$pass = true;
	}
	if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'backupbuddy_dat.php' ) === true ) { // DB backup
		$backup_type = 'db';
		$pass = true;
	}
	
	$backup_dat = self::getDatArrayFromZip( $file );
	/*
	echo 'DAT:<pre>';
	print_r( $backup_dat );
	echo '</pre>';
	*/
	$options = $backup_dat; //['profile'];
	
	
	$createdFileOptions = true;
}

$options = array_merge(
	array(
		'skip_database_dump' => '0',
	),
	(array)$options
);

$scan_notes = array();


// Get backup fileoptions.
if ( $fileoptions != '' ) {
	$backup_options = &$fileoptions;
	$backup_options_options = &$backup_options->options;
} else { //if ( file_exists( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
	pb_backupbuddy::status( 'details', 'Fileoptions instance #44.' );
	$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = false, $ignore_lock = false, $create_file = true ); // Will create file to hold integrity data if nothing exists.
	if ( true !== ( $result = $backup_options->is_ok() ) ) {
		pb_backupbuddy::status( 'error', __('Fatal Error #9034 C. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error on file `' . backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' . '`: ' . $result );
		pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
		return false;
	}
	if ( ! is_array( $backup_options->options ) ) {
		$backup_options->options = array();
	}
	$backup_options_options = &$backup_options->options;
}

// We did not have a fileoptions file so we are rebuilding portions of it using information found in the DAT file.
if ( true === $createdFileOptions ) {
	$backup_options_options['breakout_tables'] = $options['breakout_tables'];
	$backup_options_options['table_sizes'] = $options['tables_sizes'];
	$backup_options_options['type'] = $options['backup_type'];
	if ( isset( $options['force_single_db_file'] ) ) {
		$backup_options_options['force_single_db_file'] = $options['force_single_db_file'];
	}
	
	$options = $options['profile'];
}

if ( isset( $backup_options_options['profile'] ) ) {
	$options = $backup_options_options['profile'];
	$options = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), $options );
}

// If breakout tables set in options but not fileoptions then copy over. eg from DAT file.
/*
if ( ! isset( $backup_options_options['breakout_tables'] ) && isset( $options['breakout_tables'] ) ) {
	$backup_options_options['breakout_tables'] = $options['breakout_tables'];
}
*/

// Return if cached.
if ( isset( $backup_options_options['integrity'] ) && ( count( $backup_options_options['integrity'] ) > 0 ) && ( pb_backupbuddy::_GET( 'reset_integrity' ) != $serial ) ) { // Already have integrity data and NOT resetting this one.
	pb_backupbuddy::status( 'details', 'Integrity data for backup `' . $serial . '` is cached; not scanning again.' );
	return $backup_options_options['integrity'];
} elseif ( pb_backupbuddy::_GET( 'reset_integrity' ) == $serial ) { // Resetting this one.
	pb_backupbuddy::status( 'details', 'Resetting backup integrity stats for backup with serial `' . $serial . '`.' );
}  else { // No integrity data; not resetting. Just keep going...
}

// Integrity check disabled. Skip.
if ( ( ( pb_backupbuddy::$options['profiles'][0]['integrity_check'] == '0' ) ) && ( pb_backupbuddy::_GET( 'reset_integrity' ) == '' ) && ( isset( $options['integrity_check'] ) ) && ( $options['integrity_check'] == '0' ) ) { // Integrity checking disabled. Allows run if manually rescanning on backups page.
	pb_backupbuddy::status( 'details', 'Integrity check disabled. Skipping scan.' );
	$file_stats = @stat( $file );
	if ( $file_stats === false ) { // stat failure.
		pb_backupbuddy::status( 'error', 'Error #4539774. Unable to get file details ( via stat() ) for file `' . $file . '`. The file may be corrupt or too large for the server.' );
		$file_size = 0;
		$file_modified = 0;
	} else { // stat success.
		$file_size = $file_stats['size'];
		$file_modified = $file_stats['mtime'];
	}
	unset( $file_stats );

	$integrity = array(
		'status'				=>		'Unknown',
		'tests'					=>		array(),
		'scan_time'				=>		0,
		'detected_type'			=>		'unknown',
		'size'					=>		$file_size,
		'modified'				=>		$file_modified,
		'file'					=>		basename( $file ),
		'comment'				=>		false,
	);
	$backup_options_options['integrity'] = array_merge( pb_backupbuddy::settings( 'backups_integrity_defaults' ), $integrity );
	$backup_options->save();

	return $backup_options_options['integrity'];
}


//***** BEGIN CALCULATING STATUS DETAILS.


$backup_type = '';


if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
}

$previous_status_serial = pb_backupbuddy::get_status_serial(); // Store current status serial setting to reset back later.
if ( true !== $skipLogRedirect ) {
	pb_backupbuddy::status( 'details', 'Redirecting status logging temporarily.' );
	pb_backupbuddy::set_status_serial( 'zipbuddy_test' ); // Redirect logging output to a certain log file.
}

// Look for comment.
pb_backupbuddy::status( 'details', 'Verifying comment in zip archive.' );
$raw_comment = pb_backupbuddy::$classes['zipbuddy']->get_comment( $file );
$comment = backupbuddy_core::normalize_comment_data( $raw_comment );
$comment = $comment['note'];


$tests = array();
pb_backupbuddy::status( 'details', 'NOTE: It is normal to see several "File not found" messages in the next several log lines.' );


// Check for DAT file.
$pass = false;
pb_backupbuddy::status( 'details', 'Verifying DAT file in zip archive.' );
if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $serial . '/backupbuddy_dat.php' ) === true ) { // Post 2.0 full backup
	$backup_type = 'full';
	$pass = true;
}
if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/temp_' . $serial . '/backupbuddy_dat.php' ) === true ) { // Pre 2.0 full backup
	$backup_type = 'full';
	$pass = true;
}
if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'backupbuddy_dat.php' ) === true ) { // DB backup
	$backup_type = 'db';
	$pass = true;
}
$tests[] = array(
	'test'		=>	'BackupBuddy data file',
	'pass'		=>	$pass,
);



if ( isset( $options['type'] ) && ( 'files' == $options['type'] ) ) {
	pb_backupbuddy::status( 'details', 'Files only backup type so skipping scan of database files in backup as it is not applicable.' );
} else { // Non-files only backup so check for DB.
	// Check for DB .sql file.
	$pass = false;
	$db_test_note = '';
	pb_backupbuddy::status( 'details', 'Verifying database SQL file in zip archive.' );
	
	
	
	if ( isset( $backup_options_options['table_sizes'] ) && ( count( $backup_options_options['table_sizes'] ) > 0 ) ) { // BB v5.0+. && ( $backup_options_options['data_version'] >= 1 )
		// Look for missing SQL files.
		$pass = true;
		
		if ( ! isset( $backup_options_options['force_single_db_file'] ) ) {
			$backup_options_options['force_single_db_file'] = false;
		} else {
			if ( true === $backup_options_options['force_single_db_file'] ) {
				pb_backupbuddy::status( 'details', 'Forcing to a single db_1.sql file WAS enabled for this backup. Only db_1.sql files will be checked for.' );
			} else {
				pb_backupbuddy::status( 'details', 'Forcing to a single db_1.sql file was NOT enabled for this backup.' );
			}
		}
		
		pb_backupbuddy::status( 'details', 'BackupBuddy v5.0+ format database detected.' );
		if ( 'db' == $backup_options_options['type'] ) { // DB.
			pb_backupbuddy::status( 'details', 'Database-only type backup.' );
			if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'db_1.sql' ) === true ) { // Commandline based.
				pb_backupbuddy::status( 'details', 'Command line based database dump type.' );
				if ( isset( $backup_options_options['breakout_tables'] ) && ( count( $backup_options_options['breakout_tables'] ) > 0 ) && ( true !== $backup_options_options['force_single_db_file'] ) ) { // Verify broken out table SQL files exist.
					pb_backupbuddy::status( 'details', 'Some tables were broken out. Checking for them (' . implode(',', $backup_options_options['breakout_tables'] ) . '). (DB type)' );
					foreach( $backup_options_options['breakout_tables'] as $tableName ) {
						$databaseFile = $tableName . '.sql';
						if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
							pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Err 3849474b.' );
							$pass = false;
							break;
						}
					}
				}
			} else { // PHP-based.
				pb_backupbuddy::status( 'details', 'PHP based database dump type.' );
				foreach( $backup_options_options['table_sizes'] as $tableName => $tableSize ) {
					$databaseFile = $tableName . '.sql';
					if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
						pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Err 383783.' );
						$pass = false;
						break;
					} else {
						$backup_type = 'db';
					}
				}
			}
		} else { // Full / MS / Export.
			pb_backupbuddy::status( 'details', 'Not database-only type backup.' );
			if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $serial . '/db_1.sql' ) === true ) { // Commandline based.
				pb_backupbuddy::status( 'details', 'Command line based database dump type.' );
				if ( isset( $backup_options_options['breakout_tables'] ) && ( count( $backup_options_options['breakout_tables'] ) > 0 ) && ( true !== $backup_options_options['force_single_db_file'] ) ) { // Verify broken out table SQL files exist.
					pb_backupbuddy::status( 'details', 'Some tables were broken out. Checking for them (' . implode(',', $backup_options_options['breakout_tables'] ) . '). (DB type)' );
					foreach( $backup_options_options['breakout_tables'] as $tableName ) {
						$databaseFile = 'wp-content/uploads/backupbuddy_temp/' . $serial . '/' . $tableName . '.sql';
						if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
							pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Err 3849474c.' );
							$pass = false;
							break;
						}
					}
				}
			} else { // PHP-based.
				pb_backupbuddy::status( 'details', 'PHP based database dump type.' );
				foreach( $backup_options_options['table_sizes'] as $tableName => $tableSize ) {
					$databaseFile = 'wp-content/uploads/backupbuddy_temp/' . $serial . '/' . $tableName . '.sql';
					if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
						pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Backup type: `' . $backup_options_options['type'] . '`. Err 358383.' );
						$pass = false;
						break;
					} else {
						$backup_type = 'full';
					}
				}
			}
		}
		
		$db_test_note = 's (' . count( $backup_options_options['table_sizes'] ) . ' tables)';
		
	} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $serial . '/db_1.sql' ) === true ) { // post 2.0 full backup
		$backup_type = 'full';
		$pass = true;
		if ( isset( $backup_options_options['breakout_tables'] ) && ( count( $backup_options_options['breakout_tables'] ) > 0 ) ) { // Verify broken out table SQL files exist.
			pb_backupbuddy::status( 'details', 'Some tables were broken out. Checking for them (' . implode(',', $backup_options_options['breakout_tables'] ) . '). (full type)' );
			foreach( $backup_options_options['breakout_tables'] as $tableName ) {
				$databaseFile = 'wp-content/uploads/backupbuddy_temp/' . $serial . '/' . $tableName . '.sql';
				if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
					pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Err 3849474.' );
					$pass = false;
					break;
				}
			}
		}
	} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'db_1.sql' ) === true ) { // db only backup 2.0+. 5.0+ can have this if breaking out tables only partially.
		$backup_type = 'db';
		$pass = true;
		if ( isset( $backup_options_options['breakout_tables'] ) && ( count( $backup_options_options['breakout_tables'] ) > 0 ) ) { // Verify broken out table SQL files exist.
			pb_backupbuddy::status( 'details', 'Some tables were broken out. Checking for them (' . implode(',', $backup_options_options['breakout_tables'] ) . '). (db type)' );
			foreach( $backup_options_options['breakout_tables'] as $tableName ) {
				$databaseFile = $tableName . '.sql';
				if ( ! pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, $databaseFile ) ) {
					pb_backupbuddy::status( 'error', 'Missing database file `' . $databaseFile . '` in backup. Err 3847583.' );
					$pass = false;
					break;
				}
			}
		}
	} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/temp_' . $serial . '/db.sql' ) === true ) { // pre 2.0 full backup
		$backup_type = 'full';
		$pass = true;
	} elseif ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'db.sql' ) === true ) { // db only backup pre-2.0
		$backup_type = 'db';
		$pass = true;
	}
	
	 if ( true !== $pass ) {
		//if ( count( $backup_options_options['table_sizes'] ) > 0 ) {
			
		//}
	}
	
	if ( '1' == $options['skip_database_dump'] ) {
		if ( false === $pass ) {
			pb_backupbuddy::status( 'warning', 'WARNING: Database .SQL does NOT exist because database dump was set to be skipped based on settings. Use with caution. The database was NOT backed up.' );
		} else { // DB dump set to be skipped but was found. Just in case...
			pb_backupbuddy::status( 'warning', 'Warning #58458749. Database dump was set to be skip _BUT_ database file WAS found?' );
		}
		$pass = true;
		$db_test_note = ' <span class="pb_label pb_label-warning">' . __( 'Database skipped', 'it-l10n-backupbuddy' ) . '</span>';
		$scan_notes[] = '<span class="pb_label pb_label-warning">' . __( 'Database skipped', 'it-l10n-backupbuddy' ) . '</span>';
	}
	$tests[] = array(
		'test'		=>	'Database SQL file' . $db_test_note,
		'pass'		=>	$pass,
	);
}



// Use filename to determine backup type if detectable as it is more authoritive than the above guesses.
if ( false !== stristr( $file, '-db-' ) ) {
	$backup_type ='db';
} elseif ( false !== stristr( $file, '-full-' ) ) {
	$backup_type ='full';
} elseif ( false !== stristr( $file, '-files-' ) ) {
	$backup_type ='files';
} elseif ( false !== stristr( $file, '-export-' ) ) {
	$backup_type ='export';
} else {
	// Filename determination was not conclusive. Leave as-is based on deduced backup type determined earlier.
}



// Check for wp-config.php file if full backup.
if ( 'full' == $backup_type ) {
	$pass = false;
	pb_backupbuddy::status( 'details', 'Verifying WordPress wp-config.php configuration file in zip archive.' );
	if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-config.php' ) === true ) {
		$pass = true;
	}
	if ( pb_backupbuddy::$classes['zipbuddy']->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $serial . '/wp-config.php' ) === true ) {
		$pass = true;
	}
	if ( false === $pass ) {
		if ( isset( $options['excludes'] ) ) {
			if ( false !== stristr( $options['excludes'], 'wp-config.' ) ) {
				pb_backupbuddy::status( 'warning', 'Warning: An exclusion containing wp-config.php was found. Exclusions: `' . str_replace(array("\r", "\r\n", "\n"), '; ', $options['excludes'] ) . '`.' );
			}
		}
	}
	$tests[] = array(
		'test'		=>	'WordPress wp-config.php file (full backups only)',
		'pass'		=>	$pass,
	);
} // end if full backup.


// Get zip scan log details.
pb_backupbuddy::status( 'details', 'Retrieving zip scan log.' );
$temp_details = pb_backupbuddy::get_status( 'zipbuddy_test' ); // Get zipbuddy scan log.
$scan_log = array();
foreach( $temp_details as $temp_detail ) {
	$scan_log[] = json_decode( $temp_detail )->{ 'data' };
}
if ( true !== $skipLogRedirect ) {
	pb_backupbuddy::set_status_serial( $previous_status_serial ); // Stop redirecting log to a specific file & set back to what it was prior.
	pb_backupbuddy::status( 'details', 'Stopped temporary redirection of status logging.' );
}

pb_backupbuddy::status( 'details', 'Calculating integrity scan status,' );

// Check for any failed tests.
$is_ok = true;
$integrity_description = '';
foreach( $tests as $test ) {
	if ( $test['pass'] !== true ) {
		$is_ok = false;
		$error = 'Error #389434. Integrity test FAILED. Test: `' . $test['test']. '`. ';
		pb_backupbuddy::status( 'error', $error );
		$integrity_description .= $error;
	}
}


if ( true === $is_ok ) {
	$integrity_status = 'Pass';
} else {
	$integrity_status = 'Fail';
}
pb_backupbuddy::status( 'details', 'Status: `' . $integrity_status . '`. Description: `' . $integrity_description . '`.' );


//***** END CALCULATING STATUS DETAILS.


// Get file information from file system.
pb_backupbuddy::status( 'details', 'Getting file details such as size, timestamp, etc.' );
$file_stats = @stat( $file );
if ( $file_stats === false ) { // stat failure.
	pb_backupbuddy::status( 'error', 'Error #4539774b. Unable to get file details ( via stat() ) for file `' . $file . '`. The file may be corrupt or too large for the server.' );
	$file_size = 0;
	$file_modified = 0;
} else { // stat success.
	$file_size = $file_stats['size'];
	$file_modified = $file_stats['mtime']; // Created time.
}
unset( $file_stats );


// Compile array of results for saving into data structure.
$integrity = array(
	'is_ok'					=>		$is_ok,						// bool
	'tests'					=>		$tests,						// Array of tests.
	'scan_time'				=>		time(),
	'scan_log'				=>		$scan_log,
	'scan_notes'			=>		$scan_notes,				// Misc text to display next to status.
	'detected_type'			=>		$backup_type,
	'size'					=>		$file_size,
	'modified'				=>		$file_modified,				// Actually created time now.
	'file'					=>		basename( $file ),
	'comment'				=>		$comment,					// boolean false if no comment. string if comment.
);

$integrity = array_merge( pb_backupbuddy::settings( 'backups_integrity_defaults' ), $integrity );
if ( is_array( $backup_options_options ) ) {
	pb_backupbuddy::status( 'details', 'Saving backup file integrity check details.' );
	$backup_options_options['integrity'] = $integrity;
	$backup_options->save();
}

return $integrity;