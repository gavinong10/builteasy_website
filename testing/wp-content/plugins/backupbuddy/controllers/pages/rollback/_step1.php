<?php
// Incoming vars: $backupFile, $step
if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Error #473623. Access Denied.' );
}
pb_backupbuddy::verify_nonce();


$restoreData = unserialize( base64_decode( pb_backupbuddy::_POST( 'restoreData' ) ) );

// Set any advanced options into the current state.
if ( '1' != pb_backupbuddy::_POST( 'autoAdvance' ) ) {
	$restoreData['autoAdvance'] = false;
}
if ( '1' == pb_backupbuddy::_POST( 'forceMysqlCompatibility' ) ) {
	$restoreData['forceMysqlMethods'] = array( 'php' );
}

$restoreData['restoreDatabase'] = true;
$restoreData['restoreFiles'] = false;
$restoreData['restoreFileRoot'] = $restoreData['tempPath'];

if ( '2' == pb_backupbuddy::$options['zip_method_strategy'] ) {
	$restoreData['zipMethodStrategy'] = 'all';
} else {
	$restoreData['zipMethodStrategy'] = pb_backupbuddy::$options['zip_method_strategy'];
}

// Instantiate rollback.
require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$rollback = new backupbuddy_restore( 'rollback', $restoreData );


$status = $rollback->restoreFiles();
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
}


$status = $rollback->determineDatabaseFiles();
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
}


$restoreData = $rollback->getState();
?>


<script>
	pb_status_undourl( "<?php echo $restoreData['undoURL']; ?>" ); // Show undo URL.
</script>


<?php if ( true === $restoreData['autoAdvance'] ) { // Auto-advance if enabled. ?>
	
	<?php _e( 'Continuing to next step... You should be redirected momentarily.', 'it-l10n-backupbuddy' ); ?>
	<br><br>
	
	<script>
		jQuery(document).ready(function() {
			jQuery( '#pb_backupbuddy_rollback_form' ).submit();
		});
	</script>
	
<?php } ?>


<form id="pb_backupbuddy_rollback_form" method="post" action="?action=pb_backupbuddy_backupbuddy&function=rollback&step=2&archive=<?php echo basename( $restoreData['archive'] ); ?>">
	<?php pb_backupbuddy::nonce(); ?>
	<input type="hidden" name="restoreData" value="<?php echo base64_encode( serialize( $restoreData ) ); ?>">
	<input type="submit" name="submitForm" class="button button-primary" value="<?php echo __('Next Step') . ' &raquo;'; ?>">
</form>
