<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.
pb_backupbuddy::load_view( '_iframe_header');
pb_backupbuddy::set_greedy_script_limits();
echo "<script>pageTitle( 'Step 5: Migrating Database' );</script>";
pb_backupbuddy::status( 'details', 'Loading step 5.' );
echo "<script>bb_showStep( 'migratingDatabase' );</script>";
pb_backupbuddy::flush();



// Final functions to run after DB migration is done. In function since this is called both in standard and at end of deployment.
function finalActions( $restore ) {
	
	// Rename .htaccess.bb_temp back to .htaccess.
	$restore->renameHtaccessTempBack();
	
	// Remove any temporary .maintenance file created by ImportBuddy.
	$restore->maintenanceOff( $onlyOurCreatedFile = true );
	
	// Remove any temporary index.htm file created by ImportBuddy.
	$restore->scrubIndexFiles();
	
	// TODO: Make these thnings be able to output stuff into the cleanupSettings.ejs template. Add functions?
	// Update wpconfig if needed.
	$wpconfig_result = $restore->migrateWpConfig();
	if ( $wpconfig_result !== true ) {
		pb_backupbuddy::alert( 'Error: Unable to update wp-config.php file. Verify write permissions for the wp-config.php file then refresh this page. You may manually update your wp-config.php file by changing it to the following:<textarea readonly="readonly" style="width: 80%;">' . $wpconfig_result . '</textarea>' );
	}
	
	// Scan for 'trouble' such as a remaining .maintenance file, index.htm, index.html, missing wp-config.php, missing .htaccess, etc etc.
	$problems = $restore->troubleScan();
	if ( count( $problems ) > 0 ) {
		$restore->_state['potentialProblems'] = $problems;
		$trouble_text = '';
		foreach( $problems as $problem ) {
			$trouble_text .= '<li>' . $problem . '</li>';
		}
		$trouble_text = '<ul>' . $trouble_text . '</ul>';
		pb_backupbuddy::status( 'warning', 'One or more potential issues detected that may require your attention: ' . $trouble_text );
	}
	
	pb_backupbuddy::status( 'details', 'Finished final actions function.' );
	
} // End finalActions().



if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // deployment mode pre-loads state data in a file instead of passing via post.
	// Parse submitted restoreData restore state from previous step.
	$restoreData = pb_backupbuddy::_POST( 'restoreData' );
	if ( NULL === ( $restoreData = json_decode( urldecode( base64_decode( $restoreData ) ), true ) ) ) { // All the encoding/decoding due to UTF8 getting mucked up along the way without all these layers. Blech!
		$message = 'ERROR #83893b: unable to decode JSON restore data `' . htmlentities( $restoreData ) . '`. Restore halted.';
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
	} else {
		die( 'Error #843797944: Missing expected default state override.' );
	}
}






// Instantiate restore class.
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$restore = new backupbuddy_restore( 'restore', $restoreData );
unset( $restoreData ); // Access via $restore->_state to make sure it is always up to date.
if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // We dont accept submitted form options during deploy.
	$restore->_state = parse_options( $restore->_state );
}

/*
echo '<pre>';
print_r( $restore->_state );
echo '</pre>';
*/

// Parse submitted options/settings.
function parse_options( $restoreData ) {
	if ( '1' == pb_backupbuddy::_POST( 'migrateDatabase' ) ) { $restoreData['databaseSettings']['migrateDatabase'] = true; } else { $restoreData['databaseSettings']['migrateDatabase'] = false; }
	if ( '1' == pb_backupbuddy::_POST( 'migrateDatabaseBruteForce' ) ) { $restoreData['databaseSettings']['migrateDatabaseBruteForce'] = true; } else { $restoreData['databaseSettings']['migrateDatabaseBruteForce'] = false; }

	$restoreData['siteurl'] = preg_replace( '|/*$|', '', pb_backupbuddy::_POST( 'siteurl' ) ); // Strip trailing slashes.
	$restoreData['homeurl'] = preg_replace( '|/*$|', '', pb_backupbuddy::_POST( 'homeurl' ) ); // Strip trailing slashes.
	if ( '' == $restoreData['homeurl'] ) {
		$restoreData['homeurl'] = $restoreData['siteurl'];
	}
	$restoreData['maxExecutionTime'] = pb_backupbuddy::_POST( 'max_execution_time' );
	
	return $restoreData;
}


// Migrate htaccess.
if ( TRUE !== $restore->_state['migrateHtaccess'] ) {
	pb_backupbuddy::status( 'details', 'Skipping migration of .htaccess file based on settings.' );
} else {
	$restore->migrateHtaccess();
}


// If deployment and no tables imported then skip migration.
pb_backupbuddy::status( 'details', 'SQL files imported: ' . count( $restore->_state['databaseSettings']['sqlFiles'] ) . '; Deploy?: ' . pb_backupbuddy::_GET( 'deploy' ) );
if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
	if ( 0 == count( $restore->_state['databaseSettings']['sqlFiles'] ) ) {
		pb_backupbuddy::status( 'details', 'Deploy mode and no SQL files imported so skipping database migration step.' );
		$restore->_state['databaseSettings']['migrateDatabase'] = false;
		
		finalActions( $restore );
		$nextStepNum = 6;
		echo '<!-- AUTOPROCEED TO STEP ' . $nextStepNum . ' -->';
	} else {
		pb_backupbuddy::status( 'details', 'Deploy mode but SQL files imported (`' . count( $restore->_state['databaseSettings']['sqlFiles'] ) . '` total) so not skipping database migration step.' );
	}
}


if ( TRUE !== $restore->_state['databaseSettings']['migrateDatabase'] ) {
	pb_backupbuddy::status( 'details', 'Skipping migration of database based on advanced settings.' );
	echo "<script>bb_action( 'databaseMigrationSkipped' );</script>";
	$migrateResults = true;
} else {
	pb_backupbuddy::status( 'details', 'Starting database migration procedures.' );
	
	// Connect ImportBuddy to the database.
	$restore->connectDatabase();
	
	$overridePrefix = '';
	if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
		$overridePrefix = $restore->_state['databaseSettings']['tempPrefix'];
	}
	
	require_once( 'importbuddy/classes/_migrate_database.php' );
	$migrate = new backupbuddy_migrateDB( 'standalone', $restore->_state, $networkPrefix = '', $overridePrefix );
	$migrateResults = $migrate->migrate();
	
	
	
	if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
		if ( is_array( $migrateResults ) ) { // Return to same step for continuing chunking.
			$nextStepNum = 5;
		} else {
			error_log( 'STATE: ' . print_r( $restore->_state, true ) );
			// Don't attempt to swap out backupbuddy settings from options table if options table wasn't pulled.
			if ( isset( $restore->_state['dat']['tables_sizes'] ) && ( ! isset( $restore->_state['dat']['tables_sizes'][ $restore->_state['dat']['db_prefix'] . 'options' ] ) ) ) {
				pb_backupbuddy::status( 'details', 'Options table was not backed up. Skipping swap out of BackupBuddy settings.' );
			} else {
				pb_backupbuddy::status( 'details', 'Options table was backed up. Swapping out of BackupBuddy settings.' );
				if ( true !== $restore->swapDatabaseBBSettings() ) {
					pb_backupbuddy::status( 'error', 'Error #3292373: Unable to swap out BackupBuddy settings. This may not be a fatal error.' );
				} else {
					pb_backupbuddy::status( 'details', 'Finished swapping BackupBuddy settings.' );
				}
			}
			
			// Swap out new and old database prefixes.
			if ( true !== $restore->swapDatabases() ) {
				pb_backupbuddy::status( 'error', 'Error #84378: Unable to swap out temporary database prefixes.' );
				pb_backupbuddy::status( 'haltScript', '' ); // Halt JS on page.
				return;
			} else {
				pb_backupbuddy::status( 'details', 'Finished swapping database based on temporary and live prefixes.' );
			}
			
			finalActions( $restore );
			$nextStepNum = 6;
		}
		echo '<!-- AUTOPROCEED TO STEP ' . $nextStepNum . ' -->';
		
	} else { // Standard import (not deploy)
		
		if ( TRUE === $migrateResults ) { // Completed successfully.
			pb_backupbuddy::status( 'details', 'Database migration completed.' );
			echo "<script>bb_action( 'databaseMigrationSuccess' );</script>";
		} elseif ( is_array( $migrateResults ) ) { // Chunking.
			$restore->_state['databaseSettings']['migrateResumeSteps'] = $migrateResults[0];
			$restore->_state['databaseSettings']['migrateResumePoint'] = $migrateResults[1];
			pb_backupbuddy::status( 'details', 'Database migration did not fully complete in first pass. Chunking in progress. Resuming where left off.' );
			?>
			<form id="migrateChunkForm" method="post" action="?ajax=5">
				<input type="hidden" name="restoreData" value="<?php echo base64_encode( urlencode( json_encode( $restore->_state ) ) ); ?>">
				<input type="submit" name="submitForm" class="button button-primary" value="Next Step" style="display: none;">
			</form>
			<script>
				jQuery(document).ready(function() {
					jQuery( '#migrateChunkForm' ).submit();
				});
			</script>
			<?php
		} else { // Failed.
			pb_backupbuddy::status( 'details', 'Database migration failed. Result: `' . $migrateResults . '`.' );
			echo "<script>bb_action( 'databaseMigrationFailed' );</script>";
		}
		
	}
}



if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
	
	// Write default state overrides.
	global $importbuddy_file;
	$importFileSerial = backupbuddy_core::get_serial_from_file( $importbuddy_file );
	$state_file = ABSPATH . 'importbuddy-' . $importFileSerial . '-state.php';
	pb_backupbuddy::status( 'details', 'Writing to state file `' . $state_file . '`.' );
	if ( false === ( $file_handle = @fopen( $state_file, 'w' ) ) ) {
		pb_backupbuddy::status( 'error', 'Error #328937: Temp state file is not creatable/writable. Check your permissions. (' . $state_file . ')' );
		return false;
	}
	if ( false === fwrite( $file_handle, "<?php die('Access Denied.'); // <!-- ?>\n" . base64_encode( serialize( $restore->_state ) ) ) ) {
		pb_backupbuddy::status( 'error', 'Error #2389373: Unable to write to state file.' );
	} else {
		pb_backupbuddy::status( 'details', 'Wrote to state file.' );
	}
	fclose( $file_handle );
	
	pb_backupbuddy::status( 'message', 'Deployment finished.' );
	pb_backupbuddy::status( 'deployFinished', 'Finished.' );
	?>
	<form method="post" action="?ajax=<?php echo $nextStepNum; ?>&v=<?php echo pb_backupbuddy::_GET( 'v' ); ?>&deploy=true&direction=<?php echo pb_backupbuddy::_GET( 'direction' ); ?>&display_mode=embed" id="deploy-autoProceed">
		<input type="hidden" name="restoreData" value="<?php echo base64_encode( urlencode( json_encode( $restore->_state ) ) ); ?>">
		<input type="submit" name="my-submit" value="Next Step" style="visibility: hidden;">
	</form>
	<script>setTimeout( function(){ jQuery( '#deploy-autoProceed' ).submit(); }, 3000 );</script>
	<?php
	return;
	
} else { // Standard import
	
	// Success (or migrate was skipped).
	if ( true === $migrateResults ) {
		
		finalActions( $restore );
		
		pb_backupbuddy::status( 'details', 'Finishing step 5.' );
		echo "<script>
		setTimeout( function(){
			pageTitle( 'Step 6: Verify Site & Finish' );
			bb_showStep( 'cleanupSettings', " . json_encode( $restore->_state ) . " );
		}, 2000 );
		</script>";
		
	}

}




pb_backupbuddy::load_view( '_iframe_footer');
