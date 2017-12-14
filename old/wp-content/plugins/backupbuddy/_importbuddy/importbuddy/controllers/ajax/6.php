<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.
pb_backupbuddy::load_view( '_iframe_header');
pb_backupbuddy::set_greedy_script_limits();
echo "<script>pageTitle( 'Step 6: Cleanup & Completion' );</script>";
echo "<script>bb_showStep( 'cleanupSettings' );</script>";
pb_backupbuddy::flush();


if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // deployment mode pre-loads state data in a file instead of passing via post.
	// Parse submitted restoreData restore state from previous step.
	$restoreData = pb_backupbuddy::_POST( 'restoreData' );
	if ( NULL === ( $restoreData = json_decode( urldecode( base64_decode( $restoreData ) ), true ) ) ) { // All the encoding/decoding due to UTF8 getting mucked up along the way without all these layers. Blech!
		$message = 'ERROR #83893c: unable to decode JSON restore data `' . htmlentities( $restoreData ) . '`. Restore halted.';
		if ( function_exists( 'json_last_error' ) ) {
	 		$message .= ' json_last_error: `' . json_last_error() . '`.';
	 	}
		pb_backupbuddy::alert( $message );
		pb_backupbuddy::status( 'error', $message );
		die();
	}
} else {
	if ( isset( pb_backupbuddy::$options['default_state_overrides'] ) && ( count( pb_backupbuddy::$options['default_state_overrides'] ) > 0 ) ) { // Default state overrides exist. Apply them.
		$restoreData = pb_backupbuddy::$options['default_state_overrides'];
		
		/*
		echo '<pre>';
		print_r( $restoreData );
		echo '</pre>';
		*/
	} else {
		die( 'Error #3278321: Missing expected default state override.' );
	}
}

// Instantiate restore class.
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$restore = new backupbuddy_restore( 'restore', $restoreData );
unset( $restoreData ); // Access via $restore->_state to make sure it is always up to date.
if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // We dont accept submitted form options during deploy.
	$restore->_state = parse_options( $restore->_state );
} else { // Deployment should sleep to help give time for the source site to grab the last status log.
	sleep( 5 );
}


$footer = file_get_contents( pb_backupbuddy::$_plugin_path . '/views/_iframe_footer.php' );

if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
	echo '<h5>Finished deploying pushed data & temporary file cleanup.</h5>';
}
echo "<script>bb_showStep( 'finished', " . json_encode( $restore->_state ) . " );</script>";
sleep( 3 );

cleanup( $restore->_state );

echo $footer; // We must preload the footer file contents since we are about to delete it.


// Parse submitted options/settings.
function parse_options( $restoreData ) {
	
	if ( '1' == pb_backupbuddy::_POST( 'delete_backup' ) ) {
		$restoreData['cleanup']['deleteArchive'] = true;
	} else {
		$restoreData['cleanup']['deleteArchive'] = false;
	}
	
	if ( '1' == pb_backupbuddy::_POST( 'delete_temp' ) ) {
		$restoreData['cleanup']['deleteTempFiles'] = true;
	} else {
		$restoreData['cleanup']['deleteTempFiles'] = false;
	}
	
	if ( '1' == pb_backupbuddy::_POST( 'delete_importbuddy' ) ) {
		$restoreData['cleanup']['deleteImportBuddy'] = true;
	} else {
		$restoreData['cleanup']['deleteImportBuddy'] = false;
	}
	
	if ( '1' == pb_backupbuddy::_POST( 'delete_importbuddylog' ) ) {
		$restoreData['cleanup']['deleteImportLog'] = true;
	} else {
		$restoreData['cleanup']['deleteImportLog'] = false;
	}
	
	return $restoreData;
}



/*	cleanup()
 *	
 *	Cleans up any temporary files left by importbuddy.
 *	
 *	@return		null
 */
function cleanup( $restoreData ) {
	if ( true !== $restoreData['cleanup']['deleteArchive'] ) {
		pb_backupbuddy::status( 'details', 'Skipped deleting backup archive.' );
	} else {
		remove_file( $restoreData['archive'], 'backup .ZIP file (' . $restoreData['archive'] . ')', true );
	}
	
	if ( true !== $restoreData['cleanup']['deleteTempFiles'] ) {
		pb_backupbuddy::status( 'details', 'Skipped deleting temporary files.' );
	} else {
		// Full backup .sql file
		remove_file( ABSPATH . 'wp-content/uploads/temp_'. $restoreData['serial'] .'/db.sql', 'db.sql (backup database dump)', false );
		remove_file( ABSPATH . 'wp-content/uploads/temp_'. $restoreData['serial'] .'/db_1.sql', 'db_1.sql (backup database dump)', false );
		remove_file( ABSPATH . 'wp-content/uploads/backupbuddy_temp/'. $restoreData['serial'] .'/db_1.sql', 'db_1.sql (backup database dump)', false );
		// DB only sql file
		remove_file( ABSPATH . 'db.sql', 'db.sql (backup database dump)', false );
		remove_file( ABSPATH . 'db_1.sql', 'db_1.sql (backup database dump)', false );
		
		// Full backup dat file
		remove_file( ABSPATH . 'wp-content/uploads/temp_' . $restoreData['serial'] . '/backupbuddy_dat.php', 'backupbuddy_dat.php (backup data file)', false );
		remove_file( ABSPATH . 'wp-content/uploads/backupbuddy_temp/' . $restoreData['serial'] . '/backupbuddy_dat.php', 'backupbuddy_dat.php (backup data file)', false );
		// DB only dat file
		remove_file( ABSPATH . 'backupbuddy_dat.php', 'backupbuddy_dat.php (backup data file)', false );
		
		remove_file( ABSPATH . 'wp-content/uploads/backupbuddy_temp/' . $restoreData['serial'] . '/', 'Temporary backup directory', false );
		
		// Temp restore dir.
		remove_file( ABSPATH . 'importbuddy/temp_'. $restoreData['serial'] .'/', 'Temporary restore directory', false );
		
		remove_file( ABSPATH . 'importbuddy/', 'ImportBuddy Directory', true );
		remove_file( ABSPATH . 'importbuddy/_settings_dat.php', '_settings_dat.php (temporary settings file)', false );
		
		// Remove state file (deployment/default settings).
		global $importbuddy_file;
		$importFileSerial = backupbuddy_core::get_serial_from_file( $importbuddy_file );
		$state_file = ABSPATH . 'importbuddy-' . $importFileSerial . '-state.php';
		remove_file( $state_file, 'Default state data file', false );
	}
	if ( true !== $restoreData['cleanup']['deleteImportBuddy'] ) {
		pb_backupbuddy::status( 'details', 'Skipped deleting ' . $importbuddy_file . ' (this script).' );
	} else {
		global $importbuddy_file;
		remove_file( ABSPATH . $importbuddy_file, $importbuddy_file . ' (this script)', true );
	}
	// Delete log file last.
	if ( true !== $restoreData['cleanup']['deleteImportLog'] ) {
		pb_backupbuddy::status( 'details', 'Skipped deleting import log.' );
	} else {
		remove_file( 'importbuddy-' . pb_backupbuddy::$options['log_serial'] . '.txt', 'importbuddy-' . pb_backupbuddy::$options['log_serial'] . '.txt log file', true );
	}
}


pb_backupbuddy::status( 'backupbuddy_milestone', 'finish_importbuddy' );


// Used by cleanup() above.
function remove_file( $file, $description, $error_on_missing = false ) {
	pb_backupbuddy::status( 'message', 'Deleting `' . $description . '`...' );

	@chmod( $file, 0755 ); // High permissions to delete.
	
	if ( is_dir( $file ) ) { // directory.
		pb_backupbuddy::$filesystem->unlink_recursive( $file );
		if ( file_exists( $file ) ) {
			pb_backupbuddy::status( 'error', 'Unable to delete directory: `' . $description . '`. You should manually delete it.' );
		} else {
			pb_backupbuddy::status( 'message', 'Deleted.', false ); // No logging of this action to prevent recreating log.
		}
	} else { // file
		if ( file_exists( $file ) ) {
			if ( true !== @unlink( $file ) ) {
				pb_backupbuddy::status( 'error', 'Unable to delete file: `' . $description . '`. You should manually delete it.' );
			} else {
				pb_backupbuddy::status( 'message', 'Deleted.', false ); // No logging of this action to prevent recreating log.
			}
		}
	}
} // End remove_file().



die(); // Don't want to accidently go back to any files which may be gone.
