<style type="text/css">
#backupbuddy-meta-link-wrap a.show-settings {
	float: right;
	margin: 0 0 0 6px;
}
#screen-meta-links #backupbuddy-meta-link-wrap a {
	background: none;
}
#screen-meta-links #backupbuddy-meta-link-wrap a:after {
	content: '';
	margin-right: 5px;
}
</style>
<script type="text/javascript">
	jQuery(document).ready( function() {
		jQuery('#screen-meta-links').append(
			'<div id="backupbuddy-meta-link-wrap" class="hide-if-no-js screen-meta-toggle">' +
				'<a href="" class="show-settings pb_backupbuddy_begintour"><?php _e( "Tour Page", "it-l10n-backupbuddy" ); ?></a>' +
			'</div>'
		);
		
		
		jQuery( '.bb-tab-other' ).click( function(){
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'getMainLog' ); ?>', { }, 
				function(data) {
					//data = jQuery.trim( data );
					jQuery( '#backupbuddy_logFile' ).text( data );
				}
			);
		});
		
		<?php if ( '2' == pb_backupbuddy::_GET( 'tab' ) ) { ?>
		jQuery( '.nav-tab-2' ).trigger( 'click' );
		<?php } ?>
		
	}); // end on ready.
	
</script>
<?php
// Tutorial
pb_backupbuddy::load_script( 'jquery.joyride-2.0.3.js' );
pb_backupbuddy::load_script( 'modernizr.mq.js' );
pb_backupbuddy::load_style( 'joyride.css' );
?>
<ol id="pb_backupbuddy_tour" style="display: none;">
	<li data-id="ui-id-1">These settings are your defaults for all backups. Profiles may be used to override many settings to customize various backups to your needs.</li>
	<li data-id="ui-id-2">Use profiles to customize various settings on a case-by-case basis and override default backup settings.</li>
	<li data-id="ui-id-3">Customize advanced options or troubleshoot issues by overriding functionality or changing default operation.</li>
	<li data-class="jQueryOuterTree">Database tables may be omitted from backups by hovering over a table in the list and clicking the (-) minus symbol.</li>
	<li data-id="exlude_dirs" data-button="Finish">Directories & files may be omitted from backups by hovering over an item in the list and clicking the (-) minus symbol.</li>
</ol>
<script>
jQuery(window).load(function() {
	jQuery(document).on( 'click', '.pb_backupbuddy_begintour', function(e) {
		jQuery("#pb_backupbuddy_tour").joyride({
			tipLocation: 'top',
		});
		return false;
	});
});
</script>

<?php
pb_backupbuddy::$ui->title( __( 'Settings', 'it-l10n-backupbuddy' ) );
backupbuddy_core::versions_confirm();

pb_backupbuddy::disalert( 'profile_suggest', '<span class="pb_label" style="font-size: 12px; margin-left: 10px; position: relative;">Tip</span> &nbsp; You can create & customize multiple different backup types with profiles on the <a href="?page=pb_backupbuddy_backup">Backups</a> page by selecting the gear icon next to each profile.' );

$data = array(); // To pass to view.



// Reset settings to defaults.
if ( pb_backupbuddy::_POST( 'reset_defaults' ) != '' ) {
	pb_backupbuddy::verify_nonce();
	
	$keepDestNote = '';
	$remote_destinations = pb_backupbuddy::$options['remote_destinations'];
	pb_backupbuddy::$options = pb_backupbuddy::settings( 'default_options' );
	if ( '1' == pb_backupbuddy::_POST( 'keep_destinations' ) ) {
		pb_backupbuddy::$options['remote_destinations'] = $remote_destinations;
		$keepDestNote = ' ' . __( 'Remote destination settings were not reset.', 'it-l10n-backupbuddy' );
	}
	
	pb_backupbuddy::save();
	
	backupbuddy_core::verify_directories( $skipTempGeneration = true ); // Re-verify directories such as backup dir, temp, etc.
	$resetNote = __( 'Plugin settings have been reset to defaults.', 'it-l10n-backupbuddy' );
	pb_backupbuddy::alert( $resetNote . $keepDestNote );
	backupbuddy_core::addNotification( 'settings_reset', 'Plugin settings reset', $resetNote . $keepDestNote );
}



/* BEGIN VERIFYING BACKUP DIRECTORY */
if ( isset( $_POST['pb_backupbuddy_backup_directory'] ) ) {
	$backup_directory = pb_backupbuddy::_POST( 'pb_backupbuddy_backup_directory' );
	if ( '' == $backup_directory ) { // blank so set to default.
		$backup_directory = backupbuddy_core::_getBackupDirectoryDefault();
	}
	$backup_directory = str_replace( '\\', '/', $backup_directory );
	$backup_directory = rtrim( $backup_directory, '/\\' ) . '/'; // Enforce single trailing slash.
	
	if ( ! is_dir( $backup_directory ) ) {
		if ( false === @mkdir( $backup_directory, 0755 ) ) {
			pb_backupbuddy::alert( 'Error #4838594589: Selected backup directory does not exist and it could not be created. Verify the path is correct or manually create the directory and set proper permissions. Reset to default path.' );
			$_POST['pb_backupbuddy_backup_directory'] = backupbuddy_core::getBackupDirectory(); // Set back to previous value (aka unchanged).
		}
	}
	
	if ( backupbuddy_core::getBackupDirectory() != $backup_directory ) { // Directory differs. Needs updated in post var. Give messages here as this value is going to end up being saved.
		pb_backupbuddy::anti_directory_browsing( $backup_directory );
		
		$old_backup_dir = backupbuddy_core::getBackupDirectory();
		$new_backup_dir = $backup_directory;
		
		// Move all files from old backup to new.
		$old_backups_moved = 0;
		$old_backups = glob( $old_backup_dir . 'backup*.zip' );
		if ( !is_array( $old_backups ) || empty( $old_backups ) ) { // On failure glob() returns false or an empty array depending on server settings so normalize here.
			$old_backups = array();
		}
		foreach( $old_backups as $old_backup ) {
			if ( false === rename( $old_backup, $new_backup_dir . basename( $old_backup ) ) ) {
				pb_backupbuddy::alert( 'ERROR: Unable to move backup "' . basename( $old_backup ) . '" to new storage directory. Manually move it or delete it for security and to prevent it from being backed up within backups.' );
			} else { // rename success.
				$old_backups_moved++;
				$serial = backupbuddy_core::get_serial_from_file( basename( $old_backup ) );
				
				require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
				$fileoptions_files = glob( backupbuddy_core::getLogDirectory() . 'fileoptions/*.txt' );
				if ( ! is_array( $fileoptions_files ) ) {
					$fileoptions_files = array();
				}
				foreach( $fileoptions_files as $fileoptions_file ) {
					pb_backupbuddy::status( 'details', 'Fileoptions instance #21.' );
					$backup_options = new pb_backupbuddy_fileoptions( $fileoptions_file );
					if ( true !== ( $result = $backup_options->is_ok() ) ) {
						pb_backupbuddy::status( 'error', __('Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
						continue;
					}
					
					if ( isset( $backup_options->options[ $serial ] ) ) {
						if ( isset( $backup_options->options['archive_file'] ) ) {
							$backup_options->options['archive_file'] = str_replace( $old_backup_dir, $new_backup_dir, $backup_options->options['archive_file'] );
						}
					}
					$backup_options->save();
					unset( $backup_options );
				}
				
			}
		}
		
		if ( '' == pb_backupbuddy::_POST( 'pb_backupbuddy_backup_directory' ) ) { // Blank default.
			$_POST['pb_backupbuddy_backup_directory'] = '';
		} else {
			$_POST['pb_backupbuddy_backup_directory'] = $backup_directory;
		}
		pb_backupbuddy::alert( 'Your backup storage directory has been updated from "' . $old_backup_dir . '" to "' . $new_backup_dir . '". ' . $old_backups_moved . ' backup(s) have been moved to the new location. You should perform a manual backup to verify that your backup storage directory changes perform as expected.' );
	}
}
/* END VERIFYING BACKUP DIRECTORY */



/* BEGIN DISALLOWING DEFAULT IMPORT/REPAIR PASSWORD */
if ( strtolower( pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) ) == 'myp@ssw0rd' ) {
	pb_backupbuddy::alert( 'Warning: The example password is not allowed for security reasons for ImportBuddy. Please choose another password.' );
	$_POST['pb_backupbuddy_importbuddy_pass_hash'] = '';
}
/* END DISALLOWING DEFAULT IMPORT/REPAIR PASSWORD */



/* BEGIN VERIFYING PASSWORD CONFIRMATIONS MATCH */
$importbuddy_pass_match_fail = false;
if ( pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) != pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash_confirm' ) ) {
	pb_backupbuddy::alert( 'Error: The provided ImportBuddy password and confirmation do not match. Please make sure you type the password and re-type it correctly.' );
	$_POST['pb_backupbuddy_importbuddy_pass_hash'] = '';
	$_POST['pb_backupbuddy_importbuddy_pass_hash_confirm'] = '';
	$importbuddy_pass_match_fail = true;
}
/* END VERIFYING PASSWORD CONFIRMATIONS MATCH */



/* BEGIN REPLACING IMPORTBUDDY WITH VALUE OF ACTUAL HASH */
if ( isset( $_POST['pb_backupbuddy_importbuddy_pass_hash'] ) && ( '' == $_POST['pb_backupbuddy_importbuddy_pass_hash'] ) ) { // Clear out length if setting to blank.
	pb_backupbuddy::$options['importbuddy_pass_length'] = 0;
	pb_backupbuddy::$options['importbuddy_pass_hash'] = ''; // Clear out hash when emptying.
}
if ( ( str_replace( ')', '', pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) ) != '' ) && ( md5( pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) ) != pb_backupbuddy::$options['importbuddy_pass_hash'] ) ) {
	pb_backupbuddy::$options['importbuddy_pass_length'] = strlen( pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) );
	$_POST['pb_backupbuddy_importbuddy_pass_hash'] = md5( pb_backupbuddy::_POST( 'pb_backupbuddy_importbuddy_pass_hash' ) );
	$_POST['pb_backupbuddy_importbuddy_pass_hash_confirm'] = '';
} else { // Keep the same.
	if ( $importbuddy_pass_match_fail !== true ) { // keep the same
		$_POST['pb_backupbuddy_importbuddy_pass_hash'] = pb_backupbuddy::$options['importbuddy_pass_hash'];
		$_POST['pb_backupbuddy_importbuddy_pass_hash_confirm'] = '';
	}
}
// Set importbuddy dummy text to display in form box. Equal length to the provided password.
$data['importbuddy_pass_dummy_text'] = str_pad( '', pb_backupbuddy::$options['importbuddy_pass_length'], ')' );
$_POST['pb_backupbuddy_importbuddy_pass_hash_confirm'] = ''; // Always clear confirmation after processing it.



// Run periodic cleanup to make sure high security mode changes are applied.
$lockMode = 0;
if ( isset( $_POST['pb_backupbuddy_lock_archives_directory'] ) ) {
	$lockMode = $_POST['pb_backupbuddy_lock_archives_directory'];
}
if ( $lockMode != pb_backupbuddy::$options['lock_archives_directory'] ) { // Setting changed.
	require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
	backupbuddy_housekeeping::run_periodic( 0 ); // 0 cleans up everything even if not very old.
}



/* BEGIN SAVE MULTISITE SPECIFIC SETTINGS IN SET OPTIONS SO THEY ARE AVAILABLE GLOBALLY */
if ( is_multisite() ) {
	// Save multisite export option to the global site/network options for global retrieval.
	$options = get_site_option( 'pb_' . pb_backupbuddy::settings( 'slug' ) );
	$options[ 'multisite_export' ] = pb_backupbuddy::_POST( 'pb_backupbuddy_multisite_export' );
	update_site_option( 'pb_' . pb_backupbuddy::settings( 'slug' ), $options );
	unset( $options );
}
/* END SAVE MULTISITE SPECIFIC SETTINGS IN SET OPTIONS SO THEY ARE AVAILABLE GLOBALLY */



// Load settings view.
pb_backupbuddy::load_view( 'settings', $data );

/*
echo '<pre>';
print_r( pb_backupbuddy::$options );
echo '</pre>';
*/

?>
<br style="clear: both;">
<br><br>
<br><br>
</div>