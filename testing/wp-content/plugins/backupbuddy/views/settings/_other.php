<?php
function plugin_information( $plugin_slug, $data ) {
	$plugin_path = $data['path'];
	?>
	
	<textarea readonly="readonly" rows="7" cols="65" wrap="off" style="width: 100%;"><?php
		//echo "Version History:\n\n";
		readfile( $plugin_path . '/history.txt' );
	?></textarea>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#pluginbuddy_<?php echo $plugin_slug; ?>_debugtoggle").click(function() {
				jQuery("#pluginbuddy_<?php echo $plugin_slug; ?>_debugtoggle_div").slideToggle();
			});
		});
	</script>
	<?php
} // end plugin_information().



// User forced cleanup.
if ( '' != pb_backupbuddy::_GET( 'cleanup_now' ) ) {
	pb_backupbuddy::alert( 'Performing cleanup procedures now trimming old files and data.' );
	
	require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
	backupbuddy_housekeeping::run_periodic( 0 ); // 0 cleans up everything even if not very old.
}

// Delete temporary files directory.
if ( '' != pb_backupbuddy::_GET( 'delete_tempfiles_now' ) ) {
	$tempDir = backupbuddy_core::getTempDirectory();
	$logDir = backupbuddy_core::getLogDirectory();
	pb_backupbuddy::alert( 'Deleting all files contained within `' . $tempDir . '` and `' . $logDir . '`.' );
	pb_backupbuddy::$filesystem->unlink_recursive( $tempDir );
	pb_backupbuddy::$filesystem->unlink_recursive( $logDir );
	pb_backupbuddy::anti_directory_browsing( $logDir, $die = false ); // Put log dir back in place.
}



// Reset log.
if ( pb_backupbuddy::_GET( 'reset_log' ) != '' ) {
	if ( file_exists( $log_file ) ) {
		@unlink( $log_file );
	}
	if ( file_exists( $log_file ) ) { // Didnt unlink.
		pb_backupbuddy::alert( 'Unable to clear log file. Please verify permissions on file `' . $log_file . '`.' );
	} else { // Unlinked.
		pb_backupbuddy::alert( 'Cleared log file.' );
	}
}



// Reset disalerts.
if ( pb_backupbuddy::_GET( 'reset_disalerts' ) != '' ) {
	pb_backupbuddy::$options['disalerts'] = array();
	pb_backupbuddy::save();
	
	pb_backupbuddy::alert( 'Dismissed alerts have been reset. They may now be visible again.' );
}


// Cancel all running backups.
if ( '1' == pb_backupbuddy::_GET( 'cancel_running_backups' ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
	
	$fileoptions_directory = backupbuddy_core::getLogDirectory() . 'fileoptions/';
	$files = glob( $fileoptions_directory . '*.txt' );
	if ( ! is_array( $files ) ) {
		$files = array();
	}
	$cancelCount = 0;
	for ($x = 0; $x <= 3; $x++) { // Try this a few times since there may be race conditions on an open file.
		foreach( $files as $file ) {
			pb_backupbuddy::status( 'details', 'Fileoptions instance #383.' );
			
			$backup_options = new pb_backupbuddy_fileoptions( $file, $read_only = false );
			if ( true !== ( $result = $backup_options->is_ok() ) ) {
				pb_backupbuddy::status( 'error', 'Error retrieving fileoptions file `' . $file . '`. Err 335353266.' );
			} else {
				if ( empty( $backup_options->options['finish_time'] ) || ( ( FALSE !== $backup_options->options['finish_time'] ) && ( '-1' != $backup_options->options['finish_time'] ) ) ) {
					$backup_options->options['finish_time'] = -1; // Force marked as cancelled by user.
					$backup_options->save();
					$cancelCount++;
				}
			}
		}
		sleep( 1 );
	}
	
	pb_backupbuddy::alert( 'Marked all timed out or running backups & transfers as officially cancelled (`' . $cancelCount . '` total found).' );
}
?>


<h3><?php _e( 'Version History', 'it-l10n-backupbuddy' ); ?></h3>
<?php
plugin_information( pb_backupbuddy::settings( 'slug' ), array( 'name' => pb_backupbuddy::settings( 'name' ), 'path' => pb_backupbuddy::plugin_path() ) );
?>



<br style="clear: both;"><br>
<h3><?php _e( 'Housekeeping', 'it-l10n-backupbuddy' ); ?></h3>
<div>
	<a href="<?php echo pb_backupbuddy::page_url(); ?>&cleanup_now=true&tab=3" class="button secondary-button"><?php _e('Cleanup OLD temporary data & perform housekeeping', 'it-l10n-backupbuddy' );?>*</a>
	&nbsp;
	<a href="<?php echo pb_backupbuddy::page_url(); ?>&delete_tempfiles_now=true&tab=3" class="button secondary-button"><?php _e('Delete ALL temporary data', 'it-l10n-backupbuddy' );?>*</a>
	&nbsp;
	<a href="<?php echo pb_backupbuddy::page_url(); ?>&reset_disalerts=true&tab=3" class="button secondary-button"><?php _e('Reset Dismissed Alerts (' . count( pb_backupbuddy::$options['disalerts'] ) . ')', 'it-l10n-backupbuddy' );?></a>
	&nbsp;
	<a href="<?php echo pb_backupbuddy::page_url(); ?>&cancel_running_backups=1&tab=3" class="button secondary-button"><?php _e('Force Cancel of all Backups & Transfers', 'it-l10n-backupbuddy' );?></a>
	&nbsp;
	<a href="javascript:void(0);" class="button secondary-button" onClick="jQuery( '#backupbuddy-extra-log' ).toggle();"><?php _e('Show Extraneous Log (do NOT send to support)', 'it-l10n-backupbuddy' );?></a>
</div>
<br style="clear: both;">
<span class="description"><?php _e( '* Temporary files & data are normally automatically cleaned up on a regularly scheduled basis.', 'it-l10n-backupbuddy' ); ?></span>


<br><br><br>


<div id="backupbuddy-extra-log" style="display: none;">
	<h3><?php _e( 'Extraneous Log - Do not send to support unless asked', 'it-l10n-backupbuddy' ); ?></h3>

	<b>Anything logged here is typically not important. Only provide to tech support if specifically requested.</b> By default only errors are logged. Enable Full Logging on the <a href="?page=pb_backupbuddy_settings&tab=1">Advanced Settings</a> tab.
	<br><br>

	<?php
	echo '<textarea readonly="readonly" style="width: 100%;" wrap="off" cols="65" rows="7" id="backupbuddy_logFile">';
	echo '*** Loading log file. Please wait ...';
	echo '</textarea>';
	echo '<a href="' . pb_backupbuddy::page_url() . '&reset_log=true&tab=3" class="button secondary-button">' . __('Clear Log', 'it-l10n-backupbuddy' ) . '</a>';
	echo '<br><br><br>';
echo '</div>';

