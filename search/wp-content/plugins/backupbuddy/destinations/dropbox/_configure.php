<?php
global $pb_hide_test;
$pb_hide_test = true; // Always hiding test button for Dropbox.
$pb_hide_save = true;

$hide_add = true;

if ( ( $mode == 'edit' ) || ( $mode == 'add' ) ) {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.pb_dropbox_authorize').click(function(e) {
				jQuery('.pb_dropbox_authorize').hide();
				jQuery('#pb_dropbox_authorize').slideDown();
			});
		});
	</script>
	<?php
	$memory = pb_backupbuddy_destination_dropbox::memory_guesstimate();
	pb_backupbuddy::alert(
		'Note: The Dropbox API limits uploads to a maximum of 150MB.  Additionally, backup files must be fully loaded into memory to transfer to Dropbox.
		BackupBuddy estimates you will be able to transfer backups up to ' . round( $memory['hypothesis'], 0 ) . ' MB with your current memory limit of ' . $memory['limit'] . ' MB
		and <a target="_blank" href="https://www.dropbox.com/developers/reference/api">Dropbox\'s 150 MB limit</a>.'
	);
	
	
	
	
	// Handle token resetting when user clicked re-authorize button on config.
	if ( isset( $_GET['clear_dropboxtemptoken'] ) && ( $_GET['clear_dropboxtemptoken'] == 'true' ) ) {
		pb_backupbuddy::$options['dropboxtemptoken'] = ''; // Clear temp token.
		pb_backupbuddy::save();
	}
	
	
	require_once( dirname( __FILE__ ) . '/lib/dropbuddy/dropbuddy.php' );
	
	
	
	
	
	
	
	if ( isset( $destination_settings['token'] ) && ( $destination_settings['token'] != '' ) ) {
		$dropbox_token = $destination_settings['token'];
	} else {
		$dropbox_token = &pb_backupbuddy::$options['dropboxtemptoken'];
	}
	
	
	
	//echo '<pre>' . print_r( $dropbox_token, true ) . '</pre>';
	
	// Create dropbox instance.
	//pb_backupbuddy::status( 'details', 'Authenticating to dropbox with token: `' . implode( ';', $dropbox_token ) . '`.' );
	$dropbuddy = new pb_backupbuddy_dropbuddy( $dropbox_token ); // TOKEN MUST BE A REFERENCE to its location in options for saving as it is updated and saved from this.
	
	
	$dropbox_connected = false;
	if ( is_array( $dropbox_token ) ) {
		//echo 'Existing token found. Trying to use it!';
		if ( $dropbuddy->authenticate() === true ) {
			$dropbox_connected = true;
			//echo 'Authorized & connected to Dropbox!<br><br>';
			
			$hide_add = false;
			
			if ( pb_backupbuddy::_GET( 'add' ) != '' ) { // Adding a Dropbox account.
				echo '<br><b>Finish adding Dropbox destination</b><ol>';
				echo '<li>Finish by configuring the destination below and clicking the <b>+ ' . __('Add Destination', 'it-l10n-backupbuddy' ) . '</b> button. To choose another account or authenticate again select ';
				
				echo '<a href="';
				echo pb_backupbuddy::ajax_url( 'destination_picker' ) . '&add=dropbox&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '&clear_dropboxtemptoken=true';
				echo '" class="button-secondary">', __('Re-authenticate Dropbox', 'it-l10n-backupbuddy' ), '</a>';
				
				echo '</li>';
				echo '</ol>';
			}
			
			$account_info = $dropbuddy->get_account_info();
			//echo '<div class="pb_dropbox_authorize"><a href="' . $dropbuddy->get_authorize_url() . '" class="button-primary" target="_blank">Re-Authorize with Dropbox</a></div>';
			echo '<!-- Authorized to Dropbox but not adding. -->';
		} else {
			//echo 'Access Denied. Did you authenticate via the URL?<br><br>';
			if ( isset( $_GET['dropbox_auth'] ) && ( $_GET['dropbox_auth'] == 'true' ) ) {
				// do nothing
				echo 'Error #89485954. You indicated you authenticated but access was denied. Please go back and try again.';
			} else { // First step to user adding a Dropbox account.
				
				if ( $mode != 'edit' ) {
					global $pb_hide_save;
					global $pb_hide_test;
					$pb_hide_save = true;
					$pb_hide_test = true;
					
					echo '<br><b>Adding a Dropbox destination</b><ol>';
					echo '<li>Click the <b>' . __('Connect to Dropbox & Authorize', 'it-l10n-backupbuddy' ) . '</b> button below.</li>';
					echo '<li>In the new window that opens, login to Dropbox.com if prompted and click <b>Allow</b>.</li>';
					echo '<li>Return to this window and click the <b>' . __( "Yes, I've Authorized BackupBuddy with Dropbox", 'it-l10n-backupbuddy' ) . '</b> button below.</li>';
					echo '<li>Configure the destination and click the <b>+' . __('Add Destination', 'it-l10n-backupbuddy' ) . '</b> button.</li>';
					echo '</ol>';
					echo '<a href="' . $dropbuddy->get_authorize_url() . '" class="button-primary pb_dropbox_authorize" target="_blank">' . __('Connect to Dropbox & Authorize (opens new window)', 'it-l10n-backupbuddy' ) . '</a>';
				} else {
					pb_backupbuddy::$options['dropboxtemptoken'] = ''; // Clear temp token.
					pb_backupbuddy::save();
					pb_backupbuddy::alert( 'Error #6557565: Dropbox authentication failed; BackupBuddy access to your account is no longer valid. You should delete this destination and re-add it.', true );
				}
			}
		}
	}
	
	
	
	// Yes, I've Authorized BackupBuddy with Dropbox BUTTON.
	echo '<a href="';
	echo pb_backupbuddy::ajax_url( 'destination_picker' ) . '&add=dropbox&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '&t=' . time() . '&dropbox_auth=true';
	echo '" id="pb_dropbox_authorize" style="display: none;" class="button-primary">' . __( "Yes, I've Authorized BackupBuddy with Dropbox", 'it-l10n-backupbuddy' ) . '</a>';
	//echo '<br>';
	
	
	
} else { // end add & edit mode.
	$hide_add = false;
}
	




// ACCOUNT INFO ONCE ACCEPTED.
if ( $hide_add !== true ) {

	
	if ( ( $mode == 'edit' ) || ( $mode == 'add' ) ) {
		
		if ( !isset( $account_info ) ) {
			$dropbuddy = new pb_backupbuddy_dropbuddy( pb_backupbuddy::$options['remote_destinations'][$_GET['edit']]['token'] );
			if ( $dropbuddy->authenticate() === true ) {
				$dropbox_connected = true;
				$account_info = $dropbuddy->get_account_info();
			} else {
				echo __('Dropbox Access Denied', 'it-l10n-backupbuddy' );
			}
		}
		
		/*
		echo '<br>';
		echo '<a href="';
		echo pb_backupbuddy::ajax_url( 'destination_picker' ) . '&add=dropbox&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '&clear_dropboxtemptoken=true';
		echo '" class="button-secondary">', __('Re-authenticate Dropbox', 'it-l10n-backupbuddy' ), '</a>';
		*/
		
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_owner',
			'title'		=>		__( 'Dropbox Owner', 'it-l10n-backupbuddy' ),
			'default'	=>		$account_info['display_name'] . ' (UID: ' . $account_info['uid'] . ') [<a href="' . $account_info['referral_link'] . '" target="_blank">' . __('Referral Link', 'it-l10n-backupbuddy' ) .'</a>]',
		) );
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_email',
			'title'		=>		__( 'Email', 'it-l10n-backupbuddy' ),
			'default'	=>		$account_info['email'],
		) );
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_quotausage',
			'title'		=>		__('Quota Usage', 'it-l10n-backupbuddy' ),
			'default'	=>		pb_backupbuddy::$format->file_size( $account_info['quota_info']['normal'] ) . ' / ' . pb_backupbuddy::$format->file_size( $account_info['quota_info']['quota'] ) . ' (' . round( ( $account_info['quota_info']['normal'] / $account_info['quota_info']['quota'] ) * 100, 2 ) . '%)',
		) );
	}
	
	
	$default_name = NULL;
	if ( 'add' == $mode ) {
		$default_name = 'My Dropbox';
	}
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'title',
		'title'		=>		__( 'Destination name', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( 'Name of the new destination to create. This is for your convenience only.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|string[1-45]',
		'default'	=>		$default_name,
	) );
	
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'directory',
		'title'		=>		__( 'Directory (optional)', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: backupbuddy or backupbuddy/mysite/ or myfiles/backups/mysite] - Directory (or subdirectory) name to place the backups within.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'string[0-250]',
	) );
	
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'archive_limit',
		'title'		=>		__( 'Archive limit', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of archives to be stored in this specific destination. If this limit is met the oldest backups will be deleted.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' backups',
	) );
	
	
	
	$settings_form->add_setting( array(
		'type'		=>		'title',
		'name'		=>		'advanced_begin',
		'title'		=>		'<span class="dashicons dashicons-arrow-right"></span> ' . __( 'Advanced Options', 'it-l10n-backupbuddy' ),
		'row_class'	=>		'advanced-toggle-title',
	) );
	
	
	
	if ( $mode !== 'edit' ) {
		$settings_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'disable_file_management',
			'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
			'title'		=>		__( 'Disable file management', 'it-l10n-backupbuddy' ),
			'tip'		=>		__( '[Default: unchecked] - When checked, selecting this destination disables browsing or accessing files stored at this destination from within BackupBuddy.', 'it-l10n-backupbuddy' ),
			'css'		=>		'',
			'rules'		=>		'',
			'row_class'	=>		'advanced-toggle',
		) );
	}
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'disabled',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		__( 'Disable destination', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Default: unchecked] - When checked, this destination will be disabled and unusable until re-enabled. Use this if you need to temporary turn a destination off but don\t want to delete it.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		'after'		=>		'<span class="description"> ' . __('Check to disable this destination until re-enabled.', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'',
		'row_class'	=>		'advanced-toggle',
	) );
	
	
} // end if if ( $hide_add !== true ) {


