<?php
// Incoming vars: $backupFile, $step
if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Error #473623. Access Denied.' );
}
pb_backupbuddy::verify_nonce();


$restoreData = unserialize( base64_decode( pb_backupbuddy::_POST( 'restoreData' ) ) );
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$rollback = new backupbuddy_restore( 'rollback', $restoreData );

$status = $rollback->swapDatabases();
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
}

$restoreData = $rollback->getState();
?>


<h3>Test your site then select <i>Accept</i> to lock in changes or <i>Cancel</i> to undo.</h3>
<a href="<?php echo site_url(); ?>" target="_blank"><b>Click here to test your site</b></a> before proceeding.
<br><br>
If your site functions as expected you may proceed to making these changes permanent by selecting the button below to
accept the changes. If anything appears wrong or you have not made a backup of your original site you should select to cancel
the rollback to revert your site to its prior condition.
<br><br><br>



<form method="post" action="?action=pb_backupbuddy_backupbuddy&function=rollback&step=4&archive=<?php echo basename( $restoreData['archive'] ); ?>">
	<?php pb_backupbuddy::nonce(); ?>
	<input type="hidden" name="restoreData" value="<?php echo base64_encode( serialize( $restoreData ) ); ?>">
	<input type="submit" name="submit" id="submitForm" class="button button-primary" value="<?php echo __( "Accept Rollback - Everything looks good", 'it-l10n-backupbuddy' ); ?>">
	<span style="vertical-align: -5px; margin-left: 20px; margin-right: 20px; font-weight: 800;">OR</span>
	<a href="<?php echo $restoreData['undoURL']; ?>?confirm=1" class="button button-secondary"><?php _e('<b>CANCEL</b> the Rollback - Something is wrong', 'it-l10n-backupbuddy' ); ?></a>
</form>