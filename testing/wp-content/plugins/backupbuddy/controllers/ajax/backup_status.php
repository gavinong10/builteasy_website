<?php
backupbuddy_core::verifyAjaxAccess();


// IMPORTANT: MUST provide 3rd param, backup serial ID, when using pb_backupbuddy::status() within this function for it to show for this backup.
$serial = trim( pb_backupbuddy::_POST( 'serial' ) );
$serial = str_replace( '/\\', '', $serial );

$initRetryCount = (int)trim( pb_backupbuddy::_POST( 'initwaitretrycount' ) );
$specialAction = pb_backupbuddy::_POST( 'specialAction' );
$sqlFile = pb_backupbuddy::_POST( 'sqlFile' );


backupbuddy_api::getBackupStatus( $serial, $specialAction, $initRetryCount, $sqlFile, $echo = true );


die();