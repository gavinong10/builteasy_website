<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.
pb_backupbuddy::load_view( '_iframe_header');
pb_backupbuddy::set_greedy_script_limits();
echo "<script>pageTitle( 'Step 2: Restoring Files' );</script>";
pb_backupbuddy::status( 'details', 'Loading step 2.' );
echo "<script>bb_showStep( 'unzippingFiles' );</script>";
pb_backupbuddy::flush();

if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // deployment mode pre-loads state data in a file instead of passing via post.
	// Determine selected archive file.
	$archiveFile = ABSPATH . str_replace( array( '\\', '/' ), '', pb_backupbuddy::_POST( 'file' ) );
	$existing_state = array();
} else {
	if ( isset( pb_backupbuddy::$options['default_state_overrides'] ) && ( count( pb_backupbuddy::$options['default_state_overrides'] ) > 0 ) ) { // Default state overrides exist. Apply them.
		$archiveFile = pb_backupbuddy::$options['default_state_overrides']['archive'];
		$existing_state = pb_backupbuddy::$options['default_state_overrides'];
		$existing_state['tempPath'] = ABSPATH . 'importbuddy/temp_' . pb_backupbuddy::random_string( 12 ) . '/';
	} else {
		die( 'Error #843797944: Missing expected default state override.' );
	}
}

if ( ! file_exists( $archiveFile ) ) {
	die( 'Error #834984: Specified backup archive `' . htmlentities( $archiveFile ) . '` not found. Did you delete it? If the file exists, try again or verify proper read file permissions for PHP to access the file.' );
}

if ( '1' == pb_backupbuddy::_POST( 'skipUnzip' ) ) {
	$skipUnzip = true;
} else {
	$skipUnzip = false;
}

// Instantiate restore class.
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$restore = new backupbuddy_restore( 'restore', $existing_state );
$status = $restore->start( $archiveFile, $skipUnzip );
if ( false === $status ) {
	$errors = $restore->getErrors();
	if ( count( $errors ) > 0 ) {
		$errorMsg = 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.';
		pb_backupbuddy::status( 'error', $errorMsg );
	} else {
		$errorMsg = 'Error #894383: Unknown error starting restore. See advanced status log for details.';
	}
	pb_backupbuddy::alert( $errorMsg );
	return;
}

$restore->_state['defaultURL'] = $restore->getDefaultUrl();
$restore->_state['defaultDomain'] = $restore->getDefaultDomain();
if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) { // deployment mode pre-loads state data in a file instead of passing via post.
	$restore->_state = parse_options( $restore->_state );
}
$restore->_state['skipUnzip'] = $skipUnzip;

// Set up state variables.
if ( ( 'db' == $restore->_state['dat']['backup_type'] ) || ( false == $restore->_state['restoreFiles'] ) ) {
	pb_backupbuddy::status( 'details', 'Database backup OR not restoring files.' );
	$restore->_state['tempPath'] = ABSPATH . 'importbuddy/temp_' . pb_backupbuddy::random_string( 12 ) . '/';
	$restore->_state['restoreFileRoot'] = $restore->_state['tempPath'];
	pb_backupbuddy::anti_directory_browsing( $restore->_state['restoreFileRoot'], $die = false );
} else {
	pb_backupbuddy::status( 'details', 'Restoring files.' );
	$restore->_state['restoreFileRoot'] = ABSPATH; // Restore files into current root.
}

// Parse submitted options for saving to state.
function parse_options( $restoreData ) {
	
	if ( '1' == pb_backupbuddy::_POST( 'restoreFiles' ) ) { $restoreData['restoreFiles'] = true; } else { $restoreData['restoreFiles'] = false; }
	if ( '1' == pb_backupbuddy::_POST( 'restoreDatabase' ) ) { $restoreData['restoreDatabase'] = true; } else { $restoreData['restoreDatabase'] = false; }
	if ( '1' == pb_backupbuddy::_POST( 'migrateHtaccess' ) ) { $restoreData['migrateHtaccess'] = true; } else { $restoreData['migrateHtaccess'] = false; }
	
	if ( ( 'all' == pb_backupbuddy::_POST( 'zipMethodStrategy' ) ) || ( 'ziparchive' == pb_backupbuddy::_POST( 'zipMethodStrategy' ) ) || ( 'pclzip' == pb_backupbuddy::_POST( 'zipMethodStrategy' ) ) ) {
		$restoreData['zipMethodStrategy'] = pb_backupbuddy::_POST( 'zipMethodStrategy' );
	}
	
	return $restoreData;
	
} // End parse_options().


// Turn on maintenance mode for WordPress to prevent browsing the site until it is fully imported.
$restore->maintenanceOn();


if ( true !== $restore->_state['skipUnzip'] ) {

	// Unzip backup archive. For DB-only only restores SQL files to temp directory. For files, unzips all to ABSPATH.
	$results = $restore->restoreFiles();
	if ( true !== $results ) { // Unzip failed.
		
		pb_backupbuddy::alert( 'File extraction process did not complete successfully. Unable to continue to next step. Manually extract the backup ZIP file and choose to "Skip File Extraction" from the advanced options on Step 1. Details: ' . $restore->getErrors(), true, '9005' );
		
	} else { // Unzip success.
		
		echo "<script>bb_action( 'unzipSuccess' );</script>";
		
		if ( false === $restore->_state['restoreFiles'] ) { // Skip restoring files.
			pb_backupbuddy::status( 'details', 'SKIPPING restore of files based on settings from Step 1.' );
			echo "<script>bb_action( 'filesRestoreSkipped' );</script>";
		} else { // Unzip all files and/or database sql files.
			echo "<script>bb_action( 'filesRestoreSuccess' );</script>";
		}

	}
	
} else {
	$results = true;
	pb_backupbuddy::status( 'details', 'Completely skipping ALL file extraction based on skipUnzip advanced setting.' );
	echo "<script>bb_action( 'filesRestoreSkipped' );</script>";
}


// On unzip success OR skipping unzip.
pb_backupbuddy::status( 'details', 'Finishing step 2.' );
if ( ( false === $restore->_state['restoreFiles'] ) || ( true === $results ) ) {
	$restore->renameHtaccessTemp(); // Rename .htaccess to .htaccess.bb_temp until end of migration.
	//sleep(1); // Give time for file rename?
	$restore->determineDatabaseFiles();
	pb_backupbuddy::status( 'details', 'About to load Step 3.' );
	?>
	<script>
		setTimeout( function(){
			pageTitle( 'Step 3: Database Settings' );
			bb_showStep( 'databaseSettings', <?php echo json_encode( $restore->_state ); ?> );
		}, 2000);
	</script>
	<?php
}


// Load footer.
pb_backupbuddy::load_view( '_iframe_footer');

// Deployment proceed.
if ( 'true' == pb_backupbuddy::_GET( 'deploy' ) ) {
	$nextStepNum = 4;
	echo '<!-- AUTOPROCEED TO STEP ' . $nextStepNum . ' -->';
	
	
	//echo '<script>console.log( "' . print_r( $restore->_state, true ) . '" );</script>';
	
	
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
	?>
	<form method="post" action="?ajax=<?php echo $nextStepNum; ?>&v=<?php echo pb_backupbuddy::_GET( 'v' ); ?>&deploy=true&direction=<?php echo pb_backupbuddy::_GET( 'direction' ); ?>&display_mode=embed" id="deploy-autoProceed">
		<!-- input type="hidden" name="restoreData" value="<?php //echo base64_encode( urlencode( json_encode( $restore->_state ) ) ); ?>" -->
		<input type="submit" name="my-submit" value="Next Step" style="visibility: hidden;">
	</form>
	<script>jQuery( '#deploy-autoProceed' ).submit();</script>
	<?php
}
