<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }

// Display additional information for users on Windows systems.
if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Show in WINdows but not darWIN.
	pb_backupbuddy::disalert( 'windows_boost', __('Windows servers may be able to significantly boost performance, if the server allows executing .exe files (or can be configured to allow this file as an exception), by adding native Zip compatibility executable files <a href="http://ithemes.com/backupbuddy_files/backupbuddy_windows_unzip.zip">available for download here</a>. Instructions are provided within the readme.txt in the package.  This package prevents Windows from falling back to Zip compatiblity mode and works for both BackupBuddy and importbuddy.php. This is particularly useful for <a href="http://ithemes.com/codex/page/BackupBuddy:_Local_Development">local development on a Windows machine using a system like XAMPP</a>.', 'it-l10n-backupbuddy' ) );
}
?>



<style type="text/css">
	.pb_backupbuddy_customize_email_error_row, .pb_backupbuddy_customize_email_scheduled_start_row, .pb_backupbuddy_customize_email_scheduled_complete_row, .pb_backupbuddy_customize_email_send_finish_row {
		display: none;
	}
	
	.form-table th {
		white-space: nowrap;
	}
	.form-table td {
		word-break: break-all;
	}
</style>
<script type="text/javascript">

	function checkEmailNotifyErrorStatus() {
		if ( '' === jQuery('#pb_backupbuddy_email_notify_error').val() ) {
			jQuery('#pb_backupbuddy_email_notify_error').css( 'background-color', '#FFA1A1' );
			jQuery('#emailErrorNotifyHiddenAlert').show();
		} else {
			jQuery('#pb_backupbuddy_email_notify_error').css( 'background-color', '#FFF' );
			jQuery('#emailErrorNotifyHiddenAlert').hide();
		}
	}
	
	
	var pb_settings_changed = false;
	
	jQuery(document).ready(function() {
		
		
		checkEmailNotifyErrorStatus();
		jQuery('#pb_backupbuddy_email_notify_error').change( function(e) {
			checkEmailNotifyErrorStatus();
		});
		
		
		jQuery( 'a' ) .click( function(e) {
			if ( jQuery(this).attr( 'class' ) == 'ui-tabs-anchor' ) {
				if ( true == pb_settings_changed ) {
					
					if ( confirm( 'You have made changes that you have not saved by selecting the "Save Settings" button at the bottom of the page. Abandon changes without saving?' ) ) {
						// Abandon!
						pb_settings_changed = false;
						return true;
					} else {
						e.stopPropagation();
						e.stopImmediatePropagation();
						return false;
					}
				}
			}
		});
		jQuery( '.pb_form' ).change( function() {
			pb_settings_changed = true;
		});
		
		
		
		
	});
	
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after, mode ) {
		window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=' + destination_id;
	}
</script>



<?php
if ( is_numeric( pb_backupbuddy::_GET( 'tab' ) ) ) {
	$active_tab = pb_backupbuddy::_GET( 'tab' );
} else {
	$active_tab = 0;
}

if ( is_network_admin() ) {
	$license_url = network_admin_url( 'settings.php' );
} else {
	$license_url = admin_url( 'options-general.php' );
}
$license_url .= '?page=ithemes-licensing';

pb_backupbuddy::$ui->start_tabs(
	'settings',
	array(
		array(
			'title'		=>		__( 'General Settings', 'it-l10n-backupbuddy' ),
			'slug'		=>		'general',
			'css'		=>		'margin-top: -11px;',
		),
		array(
			'title'		=>		__( 'Advanced Settings / Troubleshooting', 'it-l10n-backupbuddy' ),
			'slug'		=>		'advanced',
			'css'		=>		'margin-top: -11px;',
		),
		array(
			'title'		=>		__( 'Recent Activity', 'it-l10n-backupbuddy' ),
			'slug'		=>		'activity',
			'css'		=>		'margin-top: -11px;',
		),
		array(
			'title'		=>		__( 'Other', 'it-l10n-backupbuddy' ),
			'slug'		=>		'other',
			'css'		=>		'margin-top: -11px;',
		),
		array(
			'title'		=>		__( 'Licensing', 'it-l10n-backupbuddy' ),
			'slug'		=>		'licensing',
			'url'		=>		$license_url,
			'css'		=>		'float: right; margin-top: -2px; font-style: italic;',
		),
	),
	'width: 100%;',
	true,
	$active_tab
);



pb_backupbuddy::$ui->start_tab( 'general' );
require_once( 'settings/_general.php' );
pb_backupbuddy::$ui->end_tab();


pb_backupbuddy::$ui->start_tab( 'advanced' );
require_once( 'settings/_advanced.php' );
pb_backupbuddy::$ui->end_tab();

pb_backupbuddy::$ui->start_tab( 'activity' );
require_once( 'settings/_activity.php' );
pb_backupbuddy::$ui->end_tab();

pb_backupbuddy::$ui->start_tab( 'other' );
pb_backupbuddy::flush(); // Flush before we start loading in the log.
require_once( 'settings/_other.php' );
pb_backupbuddy::$ui->end_tab();

?>





<script type="text/javascript">
	
	
	
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data ) {
		window.location.href = '<?php
			if ( is_network_admin() ) {
				echo network_admin_url( 'admin.php' );
			} else {
				echo admin_url( 'admin.php' );
			}
		?>?page=pb_backupbuddy_backup&custom=remoteclient&destination_id=' + destination_id;
	}
</script>


<?php
// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
?>







</div>



