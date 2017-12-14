<?php
if ( ! class_exists( 'backupbuddy_core' ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
}
require_once( ABSPATH . 'importbuddy/classes/auth.php' );

// If verification code passed then authenticate early.
if ( pb_backupbuddy::_GET( 'display_mode' ) == 'embed' ) {
	Auth::require_authentication();
}

global $importbuddy_file;
$import_serial = backupbuddy_core::get_serial_from_file( $importbuddy_file );
if ( '' != $import_serial ) { // importbuddy has a serial. Look for a default state file that matches.
	pb_backupbuddy::$options['log_serial'] = $import_serial;
	pb_backupbuddy::save();
	
	if ( file_exists( ABSPATH . 'importbuddy-' . $import_serial . '-state.php' ) ) { // Default state exists.
		
		// If an overriding state file exists then load it over the current state.
		$override_state_file = ABSPATH . 'importbuddy-' . $import_serial . '-state.php';
		if ( file_exists( $override_state_file ) ) {
			$statedata = file_get_contents( $override_state_file );
			// Unserialize data; If it fails it then decodes the obscufated data then unserializes it..
			if ( !is_serialized( $statedata ) || ( false === ( $return = unserialize( $statedata ) ) ) ) {
				// Skip first line.
				$second_line_pos = strpos( $statedata, "\n" ) + 1;
				$statedata = substr( $statedata, $second_line_pos );
				// Decode back into an array.
				$statedata = unserialize( base64_decode( $statedata ) );
			}
			if ( is_array( $statedata ) ) { // Valid content.
				pb_backupbuddy::status( 'details', 'Loaded default state overwrite file data and gave it priority over current state. File: `' . $override_state_file . '`.' );
				pb_backupbuddy::$options['default_state_overrides'] = $statedata;
				pb_backupbuddy::save();
			} else {
				pb_backupbuddy::status( 'warning', 'Default state overwrite file found but unable to access it.' );
			}
		}
		
	}
}

// Handle API calls if backupbuddy_api_key is posted. If anything fails security checks pretend nothing at all happened.
if ( '' != pb_backupbuddy::_POST( 'backupbuddy_api_key' ) ) { // Remote API access.
	if ( isset( pb_backupbuddy::$options['remote_api']['keys'] ) && ( count( pb_backupbuddy::$options['remote_api']['keys'] ) > 0 ) ) { // Remote API enabled and 1 or more keys defined.
		include( 'classes/remote_api.php' );
		backupbuddy_remote_api::localCall( $secure = true, $importbuddy = true );
		die();
	}
}

if ( pb_backupbuddy::_GET( 'display_mode' ) == 'embed' ) {
	pb_backupbuddy::$options['display_mode'] = 'embed';
	pb_backupbuddy::save();
}

// ********** ACTIONS (global) **********



// ********** AJAX (global) **********



// ********** CRON (global) **********



// ********** FILTERS (global) **********



// ********** WIDGETS (global) **********



?>