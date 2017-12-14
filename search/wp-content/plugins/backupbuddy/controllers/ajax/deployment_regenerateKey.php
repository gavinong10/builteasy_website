<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::load();

if ( ! defined( 'BACKUPBUDDY_API_ENABLE' ) || ( TRUE != BACKUPBUDDY_API_ENABLE ) ) { // && ( defined( 'BACKUPBUDDY_API_SALT' ) && ( 'CHANGEME' != BACKUPBUDDY_API_SALT ) && ( strlen( BACKUPBUDDY_API_SALT ) >= 5 ) )
	die( json_encode( array( 'success' => false, 'message' => 'Error #32332993: BackupBuddy API is not enabled in the wp-config.php.' ) ) );
}

require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
$newKey = backupbuddy_remote_api::generate_key();

pb_backupbuddy::$options['remote_api']['keys'][0] = $newKey;
pb_backupbuddy::save();

die( json_encode( array( 'success' => true, 'key' => $newKey ) ) );