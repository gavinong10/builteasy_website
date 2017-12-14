<?php
// Incoming vars: $backupFile, $step
if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Error #473623. Access Denied.' );
}
pb_backupbuddy::verify_nonce();


echo '<h3 style="margin-top: 0;">' . __( 'Rollback Complete', 'it-l10n-backupbuddy' ) . '</h3>';


$restoreData = unserialize( base64_decode( pb_backupbuddy::_POST( 'restoreData' ) ) );
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$rollback = new backupbuddy_restore( 'rollback', $restoreData );


$status = $rollback->finalizeRollback();
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
}
?>


<b>Thank you for choosing BackupBuddy!</b>


<script>
	pb_status_undourl( '' ); // Hide box.
</script>