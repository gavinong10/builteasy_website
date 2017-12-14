<?php
backupbuddy_core::verifyAjaxAccess();


// File restorer (actual unzip/restore) in the file restore page.
/* restore_file_restore()
*
* AJAX page for thickbox for restoring a file from inside an archive..
*
*/


$files = pb_backupbuddy::_GET( 'files' ); // file to extract.
$files_array = explode( ',', $files );
$files = array();
foreach( $files_array as $file ) {
	if ( substr( $file, -1 ) == '/' ) { // If directory then add wildcard.
		$file = $file . '*';
	}
	$files[$file] = $file;
}
unset( $files_array );

pb_backupbuddy::$ui->ajax_header( true, false ); // js, no padding
?>

<style>html { background: inherit !important; }</style>
<script type="text/javascript">
	function pb_status_append( json ) {
		if( 'undefined' === typeof statusBox ) { // No status box yet so may need to create it.
			statusBox = jQuery( '#backupbuddy_messages' );
			if( statusBox.length == 0 ) { // No status box yet so suppress.
				return;
			}
		}
		
		if ( 'string' == ( typeof json ) ) {
			backupbuddy_log( json );
			console.log( 'Status log received string: ' + json );
			return;
		}
		
		// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
		json.date = new Date();
		json.date = new Date(  ( json.time * 1000 ) + json.date.getTimezoneOffset() * 60000 );
		var seconds = json.date.getSeconds();
		if ( seconds < 10 ) {
			seconds = '0' + seconds;
		}
		json.date = backupbuddy_hourpad( json.date.getHours() ) + ':' + json.date.getMinutes() + ':' + seconds;
		
		triggerEvent = 'backupbuddy_' + json.event;
		
		
		// Log non-text events.
		if ( ( 'details' !== json.event ) && ( 'message' !== json.event ) && ( 'error' !== json.event ) ) {
			//console.log( 'Non-text event `' + triggerEvent + '`.' );
		} else {
			//console.log( json.data );
		}
		//console.log( 'trigger: ' + triggerEvent );
		
		backupbuddy_log( json );
		
		
	} // End function pb_status_append().
	
	
	// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php and _rollback.php
	function backupbuddy_log( json ) {
		message = '';
		
		if ( 'string' == ( typeof json ) ) {
			message = "-----------\t\t-------\t-------\t" + json;
		} else {
			message = json.date + '.' + json.u + " \t" + json.run + "sec \t" + json.mem + "MB\t" + json.data;
		}
		
		target_id = 'pb_backupbuddy_status'; // importbuddy_status or pb_backupbuddy_status
		if( jQuery( '#' + target_id ).length == 0 ) { // No status box yet so suppress.
			return;
		}
		jQuery( '#' + target_id ).append( "\n" + message );
		textareaelem = document.getElementById( target_id );
		textareaelem.scrollTop = textareaelem.scrollHeight;
	}
	
	function backupbuddy_hourpad(n) { return ("0" + n).slice(-2); }
</script>
<?php
$success = false;

global $pb_backupbuddy_js_status;
$pb_backupbuddy_js_status = true;
echo pb_backupbuddy::status_box( 'Restoring . . .' );
echo '<div id="pb_backupbuddy_working" style="width: 100px;"><br><center><img src="' . pb_backupbuddy::plugin_url() . '/images/working.gif" title="Working... Please wait as this may take a moment..."></center></div>';

pb_backupbuddy::set_status_serial( 'restore' );
global $wp_version;
pb_backupbuddy::status( 'details', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . ' using WordPress v' . $wp_version . ' on ' . PHP_OS . '.' );


$archive_file = pb_backupbuddy::_GET( 'archive' ); // archive to extract from.
require( pb_backupbuddy::plugin_path() . '/classes/_restoreFiles.php' );
$result = backupbuddy_restore_files::restore( backupbuddy_core::getBackupDirectory() . $archive_file, $files, $finalPath = ABSPATH );

echo '<script type="text/javascript">jQuery("#pb_backupbuddy_working").hide();</script>';
pb_backupbuddy::flush();
if ( false === $result ) {
	
} else {
}
pb_backupbuddy::$ui->ajax_footer();

pb_backupbuddy::$ui->ajax_footer();
die();

