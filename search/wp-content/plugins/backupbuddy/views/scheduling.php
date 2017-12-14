<?php



/*
echo '<pre>';
print_r( backupbuddy_api::getSchedules() );
echo '</pre>';
*/

wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );
?>
<script type="text/javascript">
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after, mode ) {
		jQuery( '#pb_backupbuddy_remotedestinations_list' ).append( '<li id="pb_remotedestination_' + destination_id + '">' + destination_title + ' <img class="pb_remotedestionation_delete" src="<?php echo pb_backupbuddy::plugin_url(); ?>/images/redminus.png" style="vertical-align: -3px; cursor: pointer;" title="<?php _e( 'Remove remote destination from this schedule.', 'it-l10n-backupbuddy' ); ?>" /></li>' + "\n" );
		jQuery( '#pb_backupbuddy_deleteafter' ).slideDown();
	}
	
	
	jQuery(document).ready(function() {
		/* Generate the remote destination list upon submission. */
		jQuery('#pb_backupbuddy_scheduling_form').submit(function(e) {
			remote_destinations = '';
			jQuery( '#pb_backupbuddy_remotedestinations_list' ).children('li').each(function () {
				remote_destinations = jQuery(this).attr( 'id' ).substr( 21 ) + '|' + remote_destinations ;
			});
			jQuery( '#pb_backupbuddy_remote_destinations' ).val( remote_destinations );
		});
		
		
		/* Allow deleting of remote destinations from the list. */
		jQuery(document).on( 'click', '.pb_remotedestionation_delete', function(e) {
			jQuery( '#pb_remotedestination_' + jQuery(this).parent( 'li' ).attr( 'id' ).substr( 21 ) ).remove();
		});
		
		
		jQuery('.pluginbuddy_pop').click(function(e) {
			showpopup('#'+jQuery(this).attr('href'),'',e);
			return false;
		});
	});
</script>




<?php
pb_backupbuddy::$ui->title( __( 'BackupBuddy Schedules', 'it-l10n-backupbuddy' ) );
pb_backupbuddy::disalert( 'schedule_limit_reminder', '<span class="pb_label">Tip</span> ' . __( 'Keep old backups from piling up by configuring "Local Archive Storage Limits" on the Settings page.', 'it-l10n-backupbuddy' ) );



if ( ( count( $schedules ) > 0 ) && ( pb_backupbuddy::_GET( 'edit' ) == '' ) ) {
	pb_backupbuddy::$ui->list_table(
		$schedules,
		array(
			'action'		=>		pb_backupbuddy::page_url(),
			'columns'		=>		array(
										__( 'Title', 'it-l10n-backupbuddy' ),
										__( 'Profile', 'it-l10n-backupbuddy' ),
										__( 'Interval', 'it-l10n-backupbuddy' ),
										__( 'Destinations', 'it-l10n-backupbuddy' ),
										__( 'Run Time', 'it-l10n-backupbuddy' ) . pb_backupbuddy::tip( __( 'First run indicates the first time thie schedule ran or will run.  Last run time is the last time that this scheduled backup started. This does not imply that the backup completed, only that it began at this time. The last run time is reset if the schedule is edited. Next run indicates when it is next scheduled to run. If there is no server activity during this time the schedule will be delayed.', 'it-l10n-backupbuddy' ), '', false ),
										__( 'Status', 'it-l10n-backupbuddy' ),
									),
			'hover_actions'	=>		array( 'edit' => 'Edit Schedule', 'run' => 'Run Now' ),
			'bulk_actions'	=>		array( 'delete_schedule' => 'Delete' ),
			'css'			=>		'width: 100%;',
		)
	);
	echo '<br>';
}


if ( pb_backupbuddy::_GET( 'edit' ) == '' ) {
	echo '<h3>' . __( 'Add New Schedule', 'it-l10n-backupbuddy' ) . '</h3>';
} else {
	echo '<h3>' . __( 'Edit Schedule', 'it-l10n-backupbuddy' ) . '</h3>';
}
$schedule_form->display_settings( '+ ' . $mode_title );
if ( pb_backupbuddy::_GET( 'edit' ) != '' ) {
	echo '<br><br><a href="' . pb_backupbuddy::page_url() . '&tab=1#database_replace" class="button secondary-button">&larr; ' .  __( 'back', 'it-l10n-backupbuddy' ) . '</a>';
}
echo '<br><br>';
?>



<br /><br />
<div class="description">
	<b>Note</b>: Due to the way schedules are triggered in WordPress your site must be accessed (frontend or admin area) for scheduled backups to occur.
	WordPress scheduled events ("crons") may be viewed or run manually for testing from the <a href="?page=pb_backupbuddy_server_tools">Server Tools page</a>.
	A <a href="https://www.google.com/search?q=free+website+uptime&oq=free+website+uptime" target="_blank">free website uptime</a> service or <a href="https://ithemes.com/sync-pro/uptime-monitoring/" target="_blank">iThemes Sync Pro's Uptime Monitoring</a> can be used to automatically access your site regularly to help trigger scheduled actions ("crons") in cases of low site activity, with the added perk of keeping track of your site uptime.
</div>
<br /><br />



<?php
// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
?>