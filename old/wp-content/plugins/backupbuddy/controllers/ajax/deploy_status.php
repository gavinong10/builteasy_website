<?php
backupbuddy_core::verifyAjaxAccess();


$backupSerial = pb_backupbuddy::_POST( 'serial' );
$profileID = pb_backupbuddy::_POST( 'profileID' );
$thisStep = pb_backupbuddy::_POST( 'step' );
$stepCounter = pb_backupbuddy::_POST( 'stepCounter' );


if ( '0' == $thisStep ) {
	$backupFiles = glob( backupbuddy_core::getBackupDirectory() . 'backup*' . $backupSerial . '*.zip' );
	if ( ! is_array( $backupFiles ) ) { $backupFiles = array(); }
	if ( count( $backupFiles ) > 0 ) {
		$backupFile = $backupFiles[0];
		die( json_encode( array(
			'statusStep' => 'backupComplete',
			'stepTitle' => 'Backup finished. File: ' . $backupFile . ' -- Next step start sending the file chunks to remote API server via curl.',
			'nextStep' => 'sendFiles',
		) ) );
	}

	$lastBackupStats = backupbuddy_api::getLatestBackupStats();
	if ( $backupSerial != $lastBackupStats['serial'] ) {
		die( json_encode( array( 'stepTitle' => 'Waiting for backup to begin.', 'statusStep' => 'waitingBackupBegin' ) ) );
	} else { // Last backup stats is our deploy backup.
		die( json_encode( array(
			'stepTitle' => $lastBackupStats['processStepTitle'] . ' with profile "' . pb_backupbuddy::$options['profiles'][ $profileID ]['title'] . '".',
			'statusStep' => 'backupStats',
			'stats' => $lastBackupStats,
		) ) );
		
	}

} elseif ( 'sendFiles' == $thisStep ) {
	
	if ( '0' == $stepCounter ) {
		die( json_encode( array(
			'stepTitle' => 'FIRST SENDFILES RUN',
			'statusStep' => 'sendFiles',
			'nextStep' => 'sendFiles',
		) ) );
	} else {
		die( json_encode( array(
			'stepTitle' => 'Sending files...',
			'statusStep' => 'sendFiles',
			'nextStep' => 'sendFiles',
		) ) );
	}
	
} else {
	die( 'Invalid step `' . htmlentities( $thisStep ) . '`.' );
}


//'nextStep' => '-1', // Finished.