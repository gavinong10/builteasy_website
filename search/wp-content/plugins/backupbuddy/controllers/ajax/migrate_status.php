<?php
backupbuddy_core::verifyAjaxAccess();


// Magic migration status polling.
/*	migrate_status()
*	
*	Gives the current migration status. Echos.
*	
*	@return		null
*/


$step = pb_backupbuddy::_POST( 'step' );
$backup_file = pb_backupbuddy::_POST( 'backup_file' );
$url = trim( pb_backupbuddy::_POST( 'url' ) );

switch( $step ) {
	case 'step1': // Make sure backup file has been transferred properly.
		// Find last migration.
		$last_migration_key = get_transient( 'pb_backupbuddy_migrationkey' );
		
		if ( false === $last_migration_key ) {
			die( json_encode( array(
				'status_code' 		=>		'failure',
				'status_message'	=>		'Status: Error #54849545. Unable to determine which backup is migrating. Please try again.',
				'next_step'			=>		'0',
			) ) );
		}
		
		pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		pb_backupbuddy::status( 'details', 'Fileoptions instance #26.' );
		$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $last_migration_key . '.txt', $read_only = true, $ignore_lock = true, $create_file = false );
		if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Fatal Error #9034.2342348. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			return false;
		}
		pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
		$fileoptions = &$fileoptions_obj->options;
		
		$migrate_send_status = $fileoptions['status'];
		
		if ( $migrate_send_status == 'timeout' ) {
			$status_message = 'Status: Waiting for backup to finish uploading to server...';
			$next_step = '1';
		} elseif ( $migrate_send_status == 'failure' ) {
			$status_message = 'Status: Sending backup to server failed.';
			$next_step = '0';
		} elseif ( $migrate_send_status == 'success' ) {
			$status_message = 'Status: Success sending backup file.';
			$next_step = '2';
		}
		die( json_encode( array(
			'status_code' 		=>		$migrate_send_status,
			'status_message'	=>		$status_message,
			'next_step'			=>		$next_step,
		) ) );
		
		break;
		
	case 'step2': // Hit importbuddy file to make sure URL is correct, it exists, and extracts itself fine.
		
		$url = rtrim( $url, '/' ); // Remove trailing slash if its there.
		if ( strpos( $url, 'importbuddy.php' ) === false ) { // If no importbuddy.php at end of URL add it.
			$url .= '/importbuddy.php';
		}
		
		if ( ( false === strstr( $url, 'http://' ) ) && ( false === strstr( $url, 'https://' ) ) ) { // http or https is missing; prepend it.
			$url = 'http://' . $url;
		}
		
		$response = wp_remote_get( $url . '?api=ping', array(
				'method' => 'GET',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => null,
				'cookies' => array()
			)
		);
		
		
		if( is_wp_error( $response ) ) {
			die( json_encode( array(
				'status_code' 		=>		'failure',
				'status_message'	=>		'Status: HTTP error checking for importbuddy.php at `' . $url . '`. Error: `' . $response->get_error_message() . '`.',
				'next_step'			=>		'0',
			) ) );
		}
		
		
		if ( trim( $response['body'] ) == 'pong' ) { // Importbuddy found.
			die( json_encode( array(
				'import_url'		=>		$url . '?display_mode=embed&file=' . pb_backupbuddy::_POST( 'backup_file' ) . '&v=' . pb_backupbuddy::$options['importbuddy_pass_hash'],
				'status_code' 		=>		'success',
				'status_message'	=>		'Sucess verifying URL is valid importbuddy.php location. Continue migration below.',
				'next_step'			=>		'0',
			) ) );
		} else { // No importbuddy here.
			die( json_encode( array(
				'status_code' 		=>		'failure',
				'status_message'	=>		'<b>Error</b>: The importbuddy.php file uploaded was not found at <a href="' . $url . '">' . $url . '</a>. Please verify the URL properly matches & corresponds to the upload directory entered for this destination\'s settings.<br><br><b>Tip:</b> This error is only caused by URL not properly matching, permissions on the destination server blocking the script, or other destination server error. You may manually verify that the importbuddy.php scripts exists in the expected location on the destination server and that the script URL <a href="' . $url . '">' . $url . '</a> properly loads the ImportBuddy tool. You may manually upload importbuddy.php and the backup ZIP file to the destination server & navigating to its URL in your browser for an almost-as-quick alternative.',
				'next_step'			=>		'0',
			) ) );
		}
		
		break;
		
	default:
		echo 'Invalid migrate_status() step: `' . pb_backupbuddy::_POST( 'step' ) . '`.';
		break;
} // End switch on action.

die();

