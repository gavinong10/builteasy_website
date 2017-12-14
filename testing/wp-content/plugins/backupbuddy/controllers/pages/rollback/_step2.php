<?php
// Incoming vars: $backupFile, $step
if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Error #473623. Access Denied.' );
}
pb_backupbuddy::verify_nonce();

$nextStep = 3;

$restoreData = unserialize( base64_decode( pb_backupbuddy::_POST( 'restoreData' ) ) );
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$rollback = new backupbuddy_restore( 'rollback', $restoreData );

$status = $rollback->restoreDatabase();
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
} elseif ( is_numeric( $status ) ) { // Incomplete, has more to import at a certain point.
	$nextStep = 2; // more to do on step 2.
} elseif ( is_array( $status ) ) { // Finished an entire SQL file, pick back up on next SQL file defined in the state.
	$nextStep = 2; // more to do on step 2.
} elseif ( true === $status ) { // Completely finished everything!
	// yay.
} else {
	$error = 'Error #9483493: Unknown mysqlbuddy response `' . $status . '`.';
	pb_backupbuddy::status( 'error', $error );
}

$restoreData = $rollback->getState();
?>


<?php if ( true === $restoreData['autoAdvance'] ) { // Auto-advance if enabled. ?>
	<?php _e( 'Continuing to next step... You should be redirected momentarily.', 'it-l10n-backupbuddy' ); ?>
	<br><br>
	<script>
		jQuery(document).ready(function() {
			jQuery( '#pb_backupbuddy_rollback_form' ).submit();
		});
	</script>
<?php } ?>


<form id="pb_backupbuddy_rollback_form" method="post" action="?action=pb_backupbuddy_backupbuddy&function=rollback&step=<?php echo $nextStep; ?>&archive=<?php echo basename( $restoreData['archive'] ); ?>">
	<?php pb_backupbuddy::nonce(); ?>
	<input type="hidden" name="restoreData" value="<?php echo base64_encode( serialize( $restoreData ) ); ?>">
	<input type="submit" name="submitForm" class="button button-primary" value="<?php echo __('Next Step') . ' &raquo;'; ?>">
</form>
