<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.
pb_backupbuddy::load_view( '_iframe_header');
pb_backupbuddy::set_greedy_script_limits();
echo "<script>pageTitle( 'Step 4: Restoring Database' );</script>";
pb_backupbuddy::status( 'details', 'Loading step 4.' );
pb_backupbuddy::flush();

global $wpdb;


if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // deployment mode pre-loads state data in a file instead of passing via post.
	// Parse submitted restoreData restore state from previous step.
	$restoreData = pb_backupbuddy::_POST( 'restoreData' );
	if ( NULL === ( $restoreData = json_decode( urldecode( base64_decode( $restoreData ) ), true ) ) ) { // All the encoding/decoding due to UTF8 getting mucked up along the way without all these layers. Blech!
		$message = 'ERROR #83893a: unable to decode JSON restore data `' . htmlentities( $restoreData ) . '`. Restore halted.';
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
		die( 'Error #4643225: Missing expected default state override.' );
	}
}



// Instantiate restore class.
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$restore = new backupbuddy_restore( 'restore', $restoreData );
unset( $restoreData ); // Access via $restore->_state to make sure it is always up to date.


if ( false === $restore->_state['restoreDatabase'] ) {
	pb_backupbuddy::status( 'details', 'Database restore SKIPPED based on Step 1 settings.' );
	echo "<script>bb_action( 'databaseRestoreSkipped' );</script>";
} else {
	
	// Connect ImportBuddy to the database with these settings.
	$restore->connectDatabase();
	
	
	// CAUTION: Wipe database tables with matching prefix if option was selected.
	if ( TRUE === $restore->_state['databaseSettings']['wipePrefix'] ) {
		if ( ( ! isset( $restore->_state['databaseSettings']['importResumeFiles'] ) ) && ( '' == $restore->_state['databaseSettings']['importResumePoint'] ) ) { // Only do this if not in process of resuming.
			pb_backupbuddy::status( 'message', 'Wiping existing database tables with the prefix `' .  $restore->_state['databaseSettings']['prefix'] . '` based on settings...' );
			if ( TRUE !== pb_backupbuddy::$classes['import']->wipePrefix( $restore->_state['databaseSettings']['prefix'], TRUE ) ) {
				pb_backupbuddy::status( 'error', 'Unable to wipe database tables matching prefix.' );
			}
		}
	}
	
	
	// DANGER: Wipe database of ALL TABLES if option was selected.
	if ( TRUE === $restore->_state['databaseSettings']['wipeDatabase'] ) {
		if ( ( ! isset( $restore->_state['databaseSettings']['importResumeFiles'] ) ) && ( '' == $restore->_state['databaseSettings']['importResumePoint'] ) ) { // Only do this if not in process of resuming.
			pb_backupbuddy::status( 'message', 'Wiping ALL existing database tables based on settings (use with caution)...' );
			if ( TRUE !== pb_backupbuddy::$classes['import']->wipeDatabase( TRUE ) ) {
				pb_backupbuddy::status( 'error', 'Unable to wipe entire database as configured in the settings.' );
			}
		}
	}

	// Restore the database.
	if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
		// Drop any previous incomplete deployment / rollback tables.
		pb_backupbuddy::status( 'details', 'Dropping any existing temporary deployment or rollback tables.' );
		
		$results = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE ( ( table_name LIKE 'bbnew-\_%' ) OR ( table_name LIKE 'bbold-\_%' ) ) AND table_schema = DATABASE()", ARRAY_A );
		if ( count( $results ) > 0 ) {
			foreach( $results as $result ) {
				if ( false === $wpdb->query( "DROP TABLE `" . backupbuddy_core::dbEscape( $result['table_name'] ) . "`" ) ) {
					return $this->_error( 'Error #372837683: Unable to copy over BackupBuddy settings from live site to incoming database in temp table. Details: `' . $wpdb->last_error . '`.' );
					pb_backupbuddy::status( 'details', 'Error #8493984: Unable to drop temp rollback/deploy table `' . $result['table_name'] . '`. Details: `' . $wpdb->last_error . '`.' );
				}
			}
		}
		
		$restore->_state['databaseSettings']['tempPrefix'] = 'bbnew-' . substr( $restore->_state['serial'], 0, 4 ) . '_' . $restore->_state['databaseSettings']['prefix'];
	}
	
	
	pb_backupbuddy::status( 'details', 'About to restore database.' );
	
	
	$restoreResult = $restore->restoreDatabase( $restore->_state['databaseSettings']['tempPrefix'] );
	
	
	
	if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
		
		if ( is_array( $restoreResult ) ) { // Chunking. Resume same step.
			$nextStepNum = 4;
		} else { // Next step.
			$nextStepNum = 5;
		}
		echo '<!-- AUTOPROCEED TO STEP ' . $nextStepNum . ' -->';
		
		
		//echo '<script>console.log( "' . print_r( $restore->_state, true ) . '" );</script>';
		
		
		// Write default state overrides.
		global $importbuddy_file;
		$importFileSerial = backupbuddy_core::get_serial_from_file( $importbuddy_file );
		$state_file = ABSPATH . 'importbuddy-' . $importFileSerial . '-state.php';
		pb_backupbuddy::status( 'details', 'Writing to state file `' . $state_file . '`.' );
		if ( false === ( $file_handle = @fopen( $state_file, 'w' ) ) ) {
			pb_backupbuddy::status( 'error', 'Error #283464: Temp state file is not creatable/writable. Check your permissions. (' . $state_file . ')' );
			return false;
		}
		if ( false === fwrite( $file_handle, "<?php die('Access Denied.'); // <!-- ?>\n" . base64_encode( serialize( $restore->_state ) ) ) ) {
			pb_backupbuddy::status( 'error', 'Error #2389373: Unable to write to state file.' );
		} else {
			pb_backupbuddy::status( 'details', 'Wrote to state file.' );
		}
		fclose( $file_handle );
		?>
		<form method="post" action="?ajax=<?php echo $nextStepNum; ?>&v=<?php echo pb_backupbuddy::_GET( 'v' ); ?>&deploy=true&direction=<?php echo pb_backupbuddy::_GET( 'direction' ); ?>&display_mode=embed" id="deploy-autoProceed">
			<!-- input type="hidden" name="restoreData" value="<?php //echo base64_encode( urlencode( json_encode( $restore->_state ) ) ); ?>" -->
			<input type="submit" name="my-submit" value="Next Step" style="visibility: hidden;">
		</form>
		<script>jQuery( '#deploy-autoProceed' ).submit();</script>
		<?php
		
	} else { // Normal import.
		
		if ( TRUE !== $restoreResult ) {
			if ( is_array( $restoreResult ) ) {
				pb_backupbuddy::status( 'details', 'Database restore did not fully complete this pass. Chunking in progress. Resuming where left off. If the process does not proceed check your browser error console or PHP error log.' );
				?>

				<form id="restoreChunkForm" method="post" action="?ajax=4">
					<input type="hidden" name="restoreData" value="<?php echo base64_encode( urlencode( json_encode( $restore->_state ) ) ); ?>">
					<input type="submit" name="submitForm" class="button button-primary" value="Next Step" style="display: none;">
				</form>
				<script>
					jQuery(document).ready(function() {
						jQuery( '#restoreChunkForm' ).submit();
					});
				</script>
			<?php
			} else {
				pb_backupbuddy::status( 'error', 'Error restoring database. See status log for details.' );
				pb_backupbuddy::status( 'details', 'Database restore failed.' );
				echo "<script>bb_action( 'databaseRestoreFailed' );</script>";
				return false;
			}
			
			
			return;
		} else { // Success.
			pb_backupbuddy::status( 'details', 'Database restore completed.' );
			echo "<script>bb_action( 'databaseRestoreSuccess' );</script>";
		}
		
	}
}


pb_backupbuddy::status( 'details', 'Finishing step 4.' );
echo "<script>
	setTimeout( function(){
		pageTitle( 'Step 5: Site URL Settings' );
		bb_showStep( 'urlReplaceSettings', " . json_encode( $restore->_state ) . " );
	}, 2000 );
	</script>";


pb_backupbuddy::load_view( '_iframe_footer');