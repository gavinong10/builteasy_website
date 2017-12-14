<?php
backupbuddy_core::verifyAjaxAccess();


// Button to stop backup.

$serial = pb_backupbuddy::_POST( 'serial' );
set_transient( 'pb_backupbuddy_stop_backup-' . $serial, true, ( 60*60*24 ) );

die( '1' );