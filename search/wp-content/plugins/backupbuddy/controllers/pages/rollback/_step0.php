<?php
// Incoming vars: $backupFile, $step
if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Error #473623. Access Denied.' );
}


require_once( pb_backupbuddy::plugin_path() . '/classes/restore.php' );
$rollback = new backupbuddy_restore( 'rollback' );
$status = $rollback->start( backupbuddy_core::getBackupDirectory() . $backupFile );
if ( false === $status ) {
	$errors = $rollback->getErrors();
	if ( count( $errors ) > 0 ) {
		pb_backupbuddy::alert( 'Errors were encountered: ' . implode( ', ', $errors ) . ' If seeking support please click to Show Advanced Details above and provide a copy of the log.' );
	}
	return;
}
$restoreData = $rollback->getState();
?>


<?php _e( "This will roll back this site's database to the state contained within the selected backup file. Verify details below to make sure this is the correct database to roll back to. Current database tables will be a given a temporary prefix.  You will be given the opportunity to confirm changes before making them permanent. <b>Tip!</b> Create a Database or Full Backup before proceeding.", 'it-l10n-backupbuddy' ); ?>
<br><br>


<?php
if ( isset( $restoreData['dat']['wordpress_version'] ) ) {
	$wp_version = $restoreData['dat']['wordpress_version'];
} else {
	$wp_version = 'Unknown';
}


// Backup type.
$pretty_type = array(
	'full'	=>	'Full',
	'db'	=>	'Database',
	'files' =>	'Files',
);


$backupInfo = array(
	array( 'Backup Type', pb_backupbuddy::$format->prettify( $restoreData['dat']['backup_type'], $pretty_type ) ),
	array( 'Backup Date', pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $restoreData['dat']['backup_time'] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $restoreData['dat']['backup_time'] ) . ' ago)</span>' ),
	array( 'Site URL', $restoreData['dat']['siteurl'] ),
	array( 'Blog Name', $restoreData['dat']['blogname'] ),
	array( 'Blog Description', $restoreData['dat']['blogdescription'] ),
	array( 'BackupBuddy Version', $restoreData['dat']['backupbuddy_version'] ),
	array( 'WordPress Version', $wp_version ),
	array( 'Database Prefix', $restoreData['dat']['db_prefix'] ),
	array( 'Active Plugins', $restoreData['dat']['active_plugins'] ),
);
if ( isset( $restoreData['dat']['posts'] ) ) {
	$backupInfo[] = array(
		'Total Posts / Pages / Comments / Users', 
		$restoreData['dat']['posts'] . ' / ' .
		$restoreData['dat']['pages'] . ' / ' .
		$restoreData['dat']['comments'] . ' / ' .
		$restoreData['dat']['users']
	);
}
pb_backupbuddy::$ui->list_table(
	$backupInfo,
	array(
		'columns'		=>	array( __( 'Backup Information', 'it-l10n-backupbuddy' ), 'Value' ),
		'css'			=>	'width: 100%; min-width: 200px;',
		)
);
?>


<br><br>
<form id="pb_backupbuddy_rollback_form" method="post" action="?action=pb_backupbuddy_backupbuddy&function=rollback&step=1&archive=<?php echo basename( $restoreData['archive'] ); ?>">
	<?php pb_backupbuddy::nonce(); ?>
	<input type="hidden" name="restoreData" value="<?php echo base64_encode( serialize( $restoreData ) ); ?>">
	<input type="submit" name="submitForm" class="button button-primary" value="<?php echo __('Begin Rollback') . ' &raquo;'; ?>">
	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<a class="button button-secondary" onclick="jQuery('#pb_backupbuddy_advanced').toggle();">Advanced Options</a>
	<span id="pb_backupbuddy_advanced" style="display: none; margin-left: 15px;">
		<label><input type="checkbox" name="autoAdvance" value="1" checked="checked"> Auto Advance</label>&nbsp;&nbsp;&nbsp;
		<label><input type="checkbox" name="forceMysqlCompatibility" value="1" checked="checked"> Force Mysql Compatibility,</label>
		<label>with chunk time limit: <input size="5" maxlength="5" type="text" name="maxExecutionTime" value="<?php echo backupbuddy_core::detectMaxExecutionTime(); ?>"> sec</label>
	</span>
	
</form>


