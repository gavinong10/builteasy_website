<?php
pb_backupbuddy::load_script( 'rollbackEvents.js' );

pb_backupbuddy::$ui->title(
	__( 'Database Rollback', 'it-l10n-backupbuddy' ) .
	' &nbsp;&nbsp; <a style="font-size: 0.6em;" href="#" onClick="jQuery(\'#pb_backupbuddy_status_wrap\').toggle();">Display Status Log</a>'
); ?>

<script>
function pb_status_undourl( undo_url ) {
	if ( '' == undo_url ) {
		jQuery( '#pb_backupbuddy_undourl' ).parent('#message').slideUp();
		return;
	}
	jQuery( '#pb_backupbuddy_undourl' ).attr( 'href', undo_url );
	jQuery( '#pb_backupbuddy_undourl' ).text( undo_url );
	jQuery( '#pb_backupbuddy_undourl' ).parent('#message').slideDown();
}
</script>

<style>
	#pb_backupbuddy_status_wrap {
		display: none;
		margin-bottom: 10px;
	}
</style>



<?php
global $wp_version;
echo '<div id="pb_backupbuddy_status_wrap">';
echo pb_backupbuddy::status_box( 'Starting rollback process with BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . ' using WordPress v' . $wp_version . ' on ' . PHP_OS . '...' );
echo '</div>';
pb_backupbuddy::status( 'details', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . ' using WordPress v' . $wp_version . ' on ' . PHP_OS . '.' );
?>


<script type="text/javascript">
	var statusBox; // Make global.
	var backupbuddy_errors_encountered = 0; // number of errors sent via log.
	
	rollback_loadRestoreEvents();
	
	function backupbuddy_hourpad(n) { return ("0" + n).slice(-2); }
	
	function pb_status_append( json ) {
		if( 'undefined' === typeof statusBox ) { // No status box yet so may need to create it.
			statusBox = jQuery( '#pb_backupbuddy_status' );
			if( statusBox.length == 0 ) { // No status box yet so suppress.
				return;
			}
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
		
		jQuery('#pb_backupbuddy_status').trigger( triggerEvent, [json] );
		
		
	} // End function pb_status_append().
	
	// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php and _rollback.php
	function backupbuddy_log( json ) {
		
		message = '';
		
		if ( 'string' == ( typeof json ) ) {
			message = "-----------\t\t-------\t-------\t" + json;
		} else {
			message = json.date + '.' + json.u + " \t" + json.run + "sec \t" + json.mem + "MB\t" + json.data;
		}

		statusBox.append( "\r\n" + message );
		statusBox.scrollTop( statusBox[0].scrollHeight - statusBox.height() );
		
	}
	
	// Trigger an error to be logged, displayed, etc.
	// Returns updated message with trouble URL, etc.
	// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
	function backupbuddyError( message ) {

		// Get start of any error numbers.
		troubleURL = '';
		error_number_begin = message.toLowerCase().indexOf( 'error #' );

		if ( error_number_begin >= 0 ) {
			error_number_begin += 7; // Shift over index to after 'error #'.
			error_number_end = message.toLowerCase().indexOf( ':', error_number_begin );
			if ( error_number_end < 0 ) { // End still not found.
				error_number_end = message.toLowerCase().indexOf( '.', error_number_begin );
			}
			if ( error_number_end < 0 ) { // End still not found.
				error_number_end = message.toLowerCase().indexOf( ' ', error_number_begin );
			}
			error_number = message.slice( error_number_begin, error_number_end );
			troubleURL = 'http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#' + error_number;
		}

		if ( '' !== troubleURL ) {
			// Display error in error div with class error_alert_box.
			message = message + ' <a href="' + troubleURL + '" target="_blank">Click to <b>view error details</b> in the Knowledge Base</a>';
		}
		jQuery( '.backupbuddy_error_list' ).append( '<li>' +  message + '</li>' );
		jQuery( '.error_alert_box' ).show();

		// Display error box to make it clear errors were encountered.
		backupbuddy_errors_encountered++;
		jQuery( '#backupbuddy_errors_notice_count' ).text( backupbuddy_errors_encountered );
		jQuery( '#backupbuddy_errors_notice' ).slideDown();

		// If the word error is nowhere in the error message then add in error prefix.
		if ( message.toLowerCase().indexOf( 'error' ) < 0 ) {
			message = 'ERROR: ' + message;
		}


		return message; // Return updated error message with trouble URL.
	} // end backupbuddyError().


	// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
	function backupbuddyWarning( message ) {
		jQuery( '.backupbuddy_warning_list' ).append( '<li>' +  message + '</li>' );
		//jQuery( '.warning_alert_box' ).show();
		return 'Warning: ' + message;
	} // end backupbuddyWarning().
</script>
<style>
	.error_alert_box {
		border-left: 3px solid red;
		background: rgb(255, 200, 200);
		max-height: 500px;
		overflow: scroll;
	}
	.error_alert_title {
		display: block;
	}
	.backupbudy_error_list {
		font-size: 14px;
	}
</style>

<div class="error_alert_box" style="display: none;">
	<span class="error_alert_title">Error(s) - See Status Log for details</span>
	<ul class="backupbuddy_error_list">
		<!-- <li>Error #123onlyAtest: An error has NOT happened. This is a only test.</li> -->
	</ul>
</div>


<div id="message" style="display: none; padding: 9px;" rel="" class="pb_backupbuddy_alert updated fade below-h2">
	<?php _e( 'If the rollback should fail for any reason you may undo its changes at any time by visiting the URL', 'it-l10n-backupbuddy' ); ?>:<br>
	<a href="" id="pb_backupbuddy_undourl" target="pb_backupbuddy_modal_iframe"></a>
</div>


<iframe id="pb_backupbuddy_modal_iframe" name="pb_backupbuddy_modal_iframe" src="<?php echo pb_backupbuddy::ajax_url( 'rollback' ); ?>&step=<?php echo pb_backupbuddy::_GET( 'step' ); ?>&archive=<?php echo pb_backupbuddy::_GET( 'rollback' ); ?>" width="100%" style="max-width: 1000px;" height="1800" frameBorder="0" padding="0" margin="0">Error #4584594579. Browser not compatible with iframes.</iframe>
