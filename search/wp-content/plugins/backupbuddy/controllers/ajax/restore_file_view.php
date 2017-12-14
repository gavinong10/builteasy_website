<?php
backupbuddy_core::verifyAjaxAccess();


// File viewer (view content only) in the file restore page.
/* restore_file_view()
*
* View contents of a file (text) that is inside a zip archive.
*
*/

pb_backupbuddy::$ui->ajax_header( true, false ); // js, no padding

$archive_file = pb_backupbuddy::_GET( 'archive' ); // archive to extract from.
$file = pb_backupbuddy::_GET( 'file' ); // file to extract.
$serial = backupbuddy_core::get_serial_from_file( $archive_file ); // serial of archive.
$temp_file = uniqid(); // temp filename to extract into.

require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
$zipbuddy = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );

// Calculate temp directory & lock it down.
$temp_dir = get_temp_dir();
$destination = $temp_dir . 'backupbuddy-' . $serial;
if ( ( ( ! file_exists( $destination ) ) && ( false === mkdir( $destination ) ) ) ) {
	$error = 'Error #458485945b: Unable to create temporary location.';
	pb_backupbuddy::status( 'error', $error );
	die( $error );
}

// If temp directory is within webroot then lock it down.
$temp_dir = str_replace( '\\', '/', $temp_dir ); // Normalize for Windows.
$temp_dir = rtrim( $temp_dir, '/\\' ) . '/'; // Enforce single trailing slash.
if ( FALSE !== stristr( $temp_dir, ABSPATH ) ) { // Temp dir is within webroot.
	pb_backupbuddy::anti_directory_browsing( $destination );
}
unset( $temp_dir );

$message = 'Extracting "' . $file . '" from archive "' . $archive_file . '" into temporary file "' . $destination . '". ';
echo '<!-- ';
pb_backupbuddy::status( 'details', $message );
echo $message;


$extractions = array( $file => $temp_file );
$extract_result = $zipbuddy->extract( backupbuddy_core::getBackupDirectory() . $archive_file, $destination, $extractions );
if ( false === $extract_result ) { // failed.
	echo ' -->';
	$error = 'Error #584984458. Unable to extract.';
	pb_backupbuddy::status( 'error', $error );
	die( $error );
} else { // success.
	_e( 'Success.', 'it-l10n-backupbuddy' );
	echo ' -->';
	?>
	<textarea readonly="readonly" wrap="off" style="width: 100%; min-height: 175px; height: 100%; margin: 0;"><?php echo file_get_contents( $destination . '/' . $temp_file ); ?></textarea>
	<?php
	//unlink( $destination . '/' . $temp_file );
}

// Try to cleanup.
if ( file_exists( $destination ) ) {
	if ( false === pb_backupbuddy::$filesystem->unlink_recursive( $destination ) ) {
		pb_backupbuddy::status( 'details', 'Unable to delete temporary holding directory `' . $destination . '`.' );
	} else {
		pb_backupbuddy::status( 'details', 'Cleaned up temporary files.' );
	}
}

pb_backupbuddy::$ui->ajax_footer();
die();
