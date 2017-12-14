<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}



/****** BEGIN AUTHENTICATION *****/
//require_once( ABSPATH . 'importbuddy/classes/auth.php' );
Auth::check();
if ( ( true === Auth::is_authenticated() ) && ( 'login' == pb_backupbuddy::_POST( 'action' ) ) ) { // On successful login to step 0, redirect to step 1.
	header( 'Location: ' . pb_backupbuddy::page_url() );
}
/****** END AUTHENTICATION *****/



$mode = 'html';



// Register PHP shutdown function to help catch and log fatal PHP errors during backup.
register_shutdown_function( 'shutdown_function' );
//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE ); // HIGH

/*	shutdown_function()
 *	
 *	Used for catching fatal PHP errors during backup to write to log for debugging.
 *	
 *	@return		null
 */
function shutdown_function() {
	
	
	// Get error message.
	// Error types: http://php.net/manual/en/errorfunc.constants.php
	$e = error_get_last();
	if ( $e === NULL ) { // No error of any kind.
		return;
	} else { // Some type of error.
		if ( !is_array( $e ) || ( $e['type'] != E_ERROR ) && ( $e['type'] != E_USER_ERROR ) ) { // Return if not a fatal error.
			//echo '<!-- ' . print_r( $e, true ) . ' -->' . "\n";
			return;
		}
	}
	
	
	// Calculate log directory.
	$log_directory = backupbuddy_core::getLogDirectory(); // Also handle when in importbuddy.
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
	$e_string = '----- FATAL ERROR ----- A fatal PHP error was encountered: ';
	foreach( (array)$e as $e_line_title => $e_line ) {
		$e_string .= $e_line_title . ' => ' . $e_line . "; ";
	}
	$e_string = rtrim( $e_string, '; ' ) . '.';
	
	// Write to log.
	@file_put_contents( $main_file, $e_string, FILE_APPEND );
	
	// IMPORTBUDDY
	$status = pb_backupbuddy::$format->date( time() ) . "\t" .
				sprintf( "%01.2f", round( microtime( true ) - pb_backupbuddy::$start_time, 2 ) ) . "\t" .
				sprintf( "%01.2f", round( memory_get_peak_usage() / 1048576, 2 ) ) . "\t" .
				'error' . "\t\t" .
				str_replace( chr(9), '   ', $e_string )
			;
	$status = str_replace( '\\', '/', $status );
	echo '<script type="text/javascript">pb_status_append("' . str_replace( '"', '&quot;', $status ) . '");</script>';
	
} // End shutdown_function.




// Handle AJAX.

$ajax = '';
if ( pb_backupbuddy::_POST( 'ajax' ) != '' ) {
	$ajax = pb_backupbuddy::_POST( 'ajax' );
} elseif ( pb_backupbuddy::_GET( 'ajax' ) != '' ) {
	$ajax = pb_backupbuddy::_GET( 'ajax' );
}
if ( $ajax != '' ) { // AJAX
	
	Auth::require_authentication(); // Die if not logged in.
	
	$page = ABSPATH . 'importbuddy/controllers/ajax/' . $ajax . '.php';
	if ( file_exists( $page ) ) {
		require_once( $page );
	} else {
		echo '{Error: Invalid AJAX action `' . htmlentities( $ajax ) . '` File not found: `' . $page . '`.}';
	}
	return;
	
}


// Determine page to load.

if ( pb_backupbuddy::_GET( 'page' ) != '' ) { // Named page.
	
	Auth::require_authentication(); // Die if not logged in.
	
	$pageSlug = str_replace( array( '\\', '/' ), '', pb_backupbuddy::_GET( 'page' ) );
	if ( ! ctype_alnum( str_replace( array( '-', '_' ), '', $pageSlug ) ) ) { // Disallow non-alphanumeric except dash, underscore.
		die( 'Error #85747833. Page contains disallowed characters. Only alphanumeric, dashes, and underscores permitted.' );
	} 
	
	$pageFile = ABSPATH . 'importbuddy/controllers/pages/' . $pageSlug . '.php';
	if ( file_exists( $pageFile ) ) {
		echo '<!-- Starting page ' . $pageSlug . '. -->';
		require_once( $pageFile );
		pb_backupbuddy::status( 'details', 'Finished page ' . $pageSlug . '.' );
	} else {
		echo '{Error: Invalid page `' . htmlentities( pb_backupbuddy::_GET( 'step' ) ) . '.php' . '`.}';
	}
	return;
	
} elseif ( pb_backupbuddy::_GET( 'step' ) != '' ) { // Numerical step.
	
	if ( true !== Auth::is_authenticated() ) { // If not logged in then provide login page.
		$step = 'login';
	} else {
		$step = pb_backupbuddy::_GET( 'step' );
		Auth::require_authentication(); // Die if not logged in.
	}
	
} else { // Unknown. Default to login.
	if ( true !== Auth::is_authenticated() ) { // If not logged in then provide login page.
		$step = 'login';
	} else {
		$step = 'homeBackupSelect';
	}
}

$stepFile = ABSPATH . 'importbuddy/controllers/pages/' . $step . '.php';
$step = pb_backupbuddy::_GET( 'step' );
require_once( ABSPATH . 'importbuddy/views/_header.php' );
echo '<!-- Starting step file `' . basename( $stepFile ) . '`. -->';

/*if ( $step > 0 ) { // Load steps after 0 in iframe.
	echo pb_backupbuddy::$classes['import']->status_box( 'ImportBuddy v' . pb_backupbuddy::$options['bb_version'] . '... Powered by BackupBuddy.' );
	echo '<iframe id="pb_backupbuddy_modal_iframe" name="pb_backupbuddy_modal_iframe" src="' . pb_backupbuddy::page_url() . 'importbuddy.php?ajax=' . $step . '" width="100%" height="1800" frameborder="0" padding="0" margin="0">Error #4584594579. Browser not compatible with iframes.</iframe>';
} else {
	*/
	if ( file_exists( $stepFile ) ) {
		require_once( $stepFile );
	} else {
		echo '{Error: Invalid step file `' . htmlentities( $step ) . '.php' . '`.}';
	}
//}
pb_backupbuddy::status( 'details', 'Finished step.' );
require_once( ABSPATH . 'importbuddy/views/_footer.php' );

return;

