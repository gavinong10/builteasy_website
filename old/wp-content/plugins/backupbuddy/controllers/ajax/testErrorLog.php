<?php
backupbuddy_core::verifyAjaxAccess();


$testMessage = 'BackupBuddy Test - This is only a test. A user triggered BackupBuddy to determine if writing to the PHP error log is working as expected.';
error_log( $testMessage );
die( 'Your PHP error log should contain the following if logging is enabled and functioning properly:' . "\n\n" . '"' . $testMessage . '".' );