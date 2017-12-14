<?php // Settings to display in a form for a user to configure.
/*
	Pre-populated variables coming into this script:
		$destination_settings
		$mode
*/
if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

global $pb_hide_test, $pb_hide_save;
$pb_hide_test = false;

$itxapi_username = '';
$itxapi_password = '';
$itxapi_token = '';


if ( $mode == 'add' ) { // ADD MODE.
	
	
	$credentials_form = new pb_backupbuddy_settings( 'pre_settings', false, 'action=pb_backupbuddy_backupbuddy&function=destination_picker&quickstart=' . htmlentities( pb_backupbuddy::_GET( 'quickstart' ) ) . '&add=' . htmlentities( pb_backupbuddy::_GET( 'add' ) ) . '&callback_data=' . htmlentities( pb_backupbuddy::_GET( 'callback_data' ) ) . '&sending=' . pb_backupbuddy::_GET( 'sending' ) . '&selecting=' . pb_backupbuddy::_GET( 'selecting' ) ); // name, savepoint|false, additional querystring
	
	$credentials_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'itxapi_username',
		'title'		=>		__( 'iThemes username', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: kerfuffle] - Your iThemes.com membership username.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|string[1-45]',
	) );
	$credentials_form->add_setting( array(
		'type'		=>		'password',
		'name'		=>		'itxapi_password_raw',
		'title'		=>		__( 'iThemes password', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 48dsds!s08K%x2s] - Your iThemes.com membership password.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|string[1-250]',
	) );
	
	$settings_result = $credentials_form->process();
	
	$login_welcome = __( 'Log in with your iThemes.com member account to begin.', 'it-l10n-backupbuddy' );
	
	if ( count( $settings_result ) == 0 ) { // No form submitted.
		
		echo $login_welcome;
		$credentials_form->display_settings( 'Submit' );
		
		$pb_hide_test = true;
		$pb_hide_save = true;
		return;
	} else { // Form submitted.
		if ( count( $settings_result['errors'] ) > 0 ) { // Form errors.
			echo $login_welcome;
			pb_backupbuddy::alert( implode( '<br>', $settings_result['errors'] ) );
			$credentials_form->display_settings( 'Submit' );
			
			$pb_hide_test = true;
			$pb_hide_save = true;
			return;
		} else { // No form errors; process!
			$pb_hide_test = true;
			$pb_hide_save = true;
			
			require_once( dirname( __FILE__ ) . '/class.itx_helper2.php' );
			global $wp_version;
			
			$itxapi_username = strtolower( $settings_result['data']['itxapi_username'] );
			$password_hash = iThemes_Credentials::get_password_hash( $itxapi_username, $settings_result['data']['itxapi_password_raw'] );
			$access_token = ITXAPI_Helper2::get_access_token( $itxapi_username, $password_hash, site_url(), $wp_version );
			
			$settings = array(
				'itxapi_username' => $itxapi_username,
				'itxapi_password' => $access_token,
			);
			$response = pb_backupbuddy_destination_stash2::stashAPI( $settings, 'connect' );
			
			if ( ! is_array( $response ) ) { // Error message.
				pb_backupbuddy::alert( 'Error #23333: Unexpected server response. Check your login and try again. Detailed response: `' . print_r( $response, true ) .'`.' );
				$credentials_form->display_settings( 'Submit' );
			} else {
				if ( isset( $response['error'] ) ) {
					pb_backupbuddy::alert( 'Error: ' . $response['error']['message'] );
					$credentials_form->display_settings( 'Submit' );
				} else {
					if ( isset( $response['token'] ) ) {
						$itxapi_token = $response['token'];
					} else {
						pb_backupbuddy::alert( 'Error #382383232: Unexpected server response. Token missing. Check your login and try again. Detailed response: `' . print_r( $response, true ) .'`.' );
						$credentials_form->display_settings( 'Submit' );
					}
				}
			}
			
		}
		
	} // end form submitted.
	
	
} elseif ( $mode == 'edit' ) { // EDIT MODE.
	
	$settings = array(
		'itxapi_username' => $itxapi_username,
		'itxapi_token' => $itxapi_token,
	);
	$account_info = pb_backupbuddy_destination_stash2::get_quota( $settings );
	$itxapi_username = $destination_settings['itxapi_username'];
	
}


if ( ( $mode == 'save' ) || ( $mode == 'edit' ) || ( $itxapi_token != '' ) ) {
	$default_name = NULL;
	
	if ( ( $mode != 'save' ) && ( 'edit' != $mode ) ) {
		$settings = array(
			'itxapi_username' => $itxapi_username,
			'itxapi_token' => $itxapi_token,
		);
		$account_info = pb_backupbuddy_destination_stash2::get_quota( $settings );
		
		if ( ! is_array( $account_info ) ) {
			$pb_hide_test = true;
			$pb_hide_save = true;
			return false;
		} else {
			$pb_hide_test = false;
			$pb_hide_save = false;
		}
		
		$account_details = 'Welcome to your BackupBuddy Stash, <b>' . $itxapi_username . '</b>. Your account is ';
		if ( $account_info['subscriber_locked'] == '1' ) {
			$account_details .= 'LOCKED';
		} elseif ( $account_info['subscriber_expired'] == '1' ) {
			$account_details .= 'EXPIRED';
		} elseif ( $account_info['subscriber_active'] == '1' ) {
			$account_details .= 'active';
		} else {
			$account_details .= 'Unknown';
		}
		$account_details .= '.';
		
		if ( $mode == 'add' ) {
			$default_name = 'My Stash (v2)';
			
			echo $account_details;
			//echo '<br>';
			echo ' ' . __( 'To jump right in using the defaults just hit "Add Destination" below.', 'it-l10n-backupbuddy' );
		} else {
			echo '<div style="text-align: center;">' . $account_details . '</div>';
		}
		
		if ( $mode == 'add' ) {
			// Check to see if user already has a Stash with this username set up for this site. No need for multiple same account Stashes.
			foreach( (array)pb_backupbuddy::$options['remote_destinations'] as $destination ) {
				if ( ( isset( $destination['itxapi_username'] ) ) && ( strtolower( $destination['itxapi_username'] ) == strtolower( $itxapi_username ) ) ) {
					echo '<br><br>';
					pb_backupbuddy::alert( 'Note: You already have a Stash destination set up under the provided iThemes account username.  It is unnecessary to create multiple Stash destinations that go to the same user account as they are effectively the same destination and a duplicate.' );
					break;
				}
			}
		}
		
		echo '<br><br>';
		echo pb_backupbuddy_destination_stash2::get_quota_bar( $account_info );
		
		echo '<!-- STASH DETAILS: ' . print_r( $account_info, true ) . ' -->';
		
	} // end if NOT in save mode.
	
	
	// Form settings.
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
		'name'		=>		'full_archive_limit',
		'title'		=>		__( 'Full backup limit', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Full (complete) backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' backups. &nbsp;<span class="description">0 or blank for no limit.</span>',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'db_archive_limit',
		'title'		=>		__( 'Database only limit', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Database Only backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' backups. &nbsp;<span class="description">0 or blank for no limit.</span>',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'files_archive_limit',
		'title'		=>		__( 'Files only limit', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Files Only backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' backups. &nbsp;<span class="description">0 or blank for no limit.</span>',
	) );
	
	
	
	$settings_form->add_setting( array(
		'type'		=>		'title',
		'name'		=>		'advanced_begin',
		'title'		=>		'<span class="dashicons dashicons-arrow-right"></span> ' . __( 'Advanced Options', 'it-l10n-backupbuddy' ),
		'row_class'	=>		'advanced-toggle-title',
	) );
	
	
	
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'max_burst',
		'title'		=>		__( 'Send per burst', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 10] - This is the amount of data that will be sent per burst within a single PHP page load/chunk. Bursts happen within a single page load. Chunks occur when broken up between page loads/PHP instances. Reduce if hitting PHP memory limits. Chunking time limits will only be checked between bursts. Lower burst size if timeouts occur before chunking checks trigger.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' MB',
		'row_class'	=>		'advanced-toggle',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'max_time',
		'title'		=>		__( 'Max time per chunk', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 30] - Enter 0 for no limit (aka no chunking; bursts may still occur based on burst size setting). This is the maximum number of seconds per page load that bursts will occur. If this time is exceeded when a burst finishes then the next burst will be chunked and ran on a new page load. Multiple bursts may be sent within each chunk.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'',
		'css'		=>		'width: 50px;',
		'after'		=>		' secs. <span class="description">' . __( 'Blank for detected default:', 'it-l10n-backupbuddy' )  . ' ' . backupbuddy_core::detectMaxExecutionTime() . ' sec</span>',
		'row_class'	=>		'advanced-toggle',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'ssl',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		__( 'Encrypt connection', 'it-l10n-backupbuddy' ) . '*',
		'tip'		=>		__( '[Default: enabled] - When enabled, all transfers will be encrypted with SSL encryption. Disabling this may aid in connection troubles but results in lessened security. Note: Once your files arrive on our server they are encrypted using AES256 encryption. They are automatically decrypted upon download as needed.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		'after'		=>		'<span class="description"> ' . __('Enable connecting over SSL.', 'it-l10n-backupbuddy' ) . '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Files are always encrypted with AES256 upon arrival.</span>',
		'rules'		=>		'',
		'row_class'	=>		'advanced-toggle',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'use_packaged_cert',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		__( 'Use included CA bundle', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Default: disabled] - When enabled, BackupBuddy will use its own bundled SSL certificate bundle for connecting to the server. Use this if SSL fails due to SSL certificate issues with your server.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		'after'		=>		'<span class="description"> ' . __('Use included certificate bundle.', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'',
		'row_class'	=>		'advanced-toggle',
	) );
	if ( $mode !== 'edit' ) {
		$settings_form->add_setting( array(
			'type'		=>		'checkbox',
			'name'		=>		'manage_all_files',
			'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
			'title'		=>		__( 'Manage all files', 'it-l10n-backupbuddy' ),
			'tip'		=>		__( '[Default: enabled] - When enabled, you have access to manage and view all files stored in your Stash account. You will be prompted for your password to access backups for sites other than this one.  If disabled the option is entirely removed for added security. For example, you may wish to disable this feature if a client has access and you want to keep them away from your files. This option can NOT be changed without deleting and re-creating the Stash destination for added security.', 'it-l10n-backupbuddy' ),
			'css'		=>		'',
			'rules'		=>		'',
			'row_class'	=>		'advanced-toggle',
		) );
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
	$settings_form->add_setting( array(
		'type'		=>		'hidden',
		'name'		=>		'itxapi_username',
		'default'	=>		$itxapi_username,
	) );
	$settings_form->add_setting( array(
		'type'		=>		'hidden',
		'name'		=>		'itxapi_token',
		'default'	=>		$itxapi_token,
	) );
}