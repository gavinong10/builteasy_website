<?php
/*
 * Dropbox Destination for PHP 5.3+. Requires PHP 5.3+ and Curl.
 *
 * @author Dustin Bolton, July 2013.
 *
 * Incoming variables:
 *		$mode					string		Mode for configuring this destination. Values:  add, edit, save
 *		$destination_settings	array		Array of this destination's configuration settings. Key => value pairs.
 *
 * Globally editable variables:
 *		$pb_hide_test			bool		Whether or not to hide the "Test" button. Modifiable.
 *		$pb_hide_save			bool		Whether or not to hide the "Save" button. Modifiable.
 *
 */
global $pb_hide_test,$pb_hide_save;
$pb_hide_test = true; // Always hiding test button for Dropbox.
$pb_hide_save = true;



$show_config_form = false;

if ( 'add' == $mode ) { // ADD mode.
	
	$webAuth = new \Dropbox\WebAuthNoRedirect( pb_backupbuddy_destination_dropbox2::$appInfo, 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ), 'en' );
	
	$bad_auth_code = false;
	if ( '' != pb_backupbuddy::_POST( 'dropbox_authorization_code' ) ) { // Authorization code entered. Try it out before showing form or not.
		
		
		$pb_hide_save = false;
		$authCode = trim( pb_backupbuddy::_POST( 'dropbox_authorization_code' ) );
		try {
			list( $accessToken, $dropboxUserId ) = $webAuth->finish( $authCode );
		} catch ( \Exception $e ) {
			pb_backupbuddy::alert( '<b>Verify you authorized BackupBuddy access in Dropbox and copied the Dropbox authorization code exactly in the BackupBuddy field.</b><br><br>Error details: ' . $e->getMessage(), true ); // '<br><br>' . pb_backupbuddy::$ui->button( pb_backupbuddy::page_url(), '&larr; go back & retry' )
			$bad_auth_code = true;
			$pb_hide_save = true;
		}
		
		if ( false === $bad_auth_code ) {
			$dropboxClient = new \Dropbox\Client( $accessToken, 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) );
			$accountInfo = $dropboxClient->getAccountInfo();
			$show_config_form = true;
		}
		
	}
	
	
	if ( '' == pb_backupbuddy::_POST( 'dropbox_authorization_code' ) || ( true === $bad_auth_code ) ) { // No authorization code entered yet so user needs to authorize.
		
		try {
			$authorizeUrl = $webAuth->start();
		} catch( Exception $e ) {
			pb_backupbuddy::alert( 'Error #8778656: Dropbox error. Details: `' . $e->getMessage() . '`.', true );
			return false;
		}
		
		echo '<form method="post" action="' . pb_backupbuddy::ajax_url( 'destination_picker' ) . '&add=dropbox2&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '">';
		echo '<br><b>Adding a Dropbox destination</b><ol>';
		echo '<li> <a href="' . $authorizeUrl . '" class="button-primary pb_dropbox_authorize" target="_blank">' . __('Connect to Dropbox.com & Authorize (opens new window)', 'it-l10n-backupbuddy' ) . '</a></li>';
		echo '<li>Click <b>Allow</b> in the new window (you may need to login to Dropbox.com first).</li>';
		echo '<li>Enter the provided <b>Authorization Code</b>: <input type="text" name="dropbox_authorization_code" size="45"></li>';
		echo '<li><input type="submit" class="button-primary" value="' . __( "Yes, I've Authorized BackupBuddy with Dropbox & Entered the Code above", 'it-l10n-backupbuddy' ) . '"></li>';
		echo '</ol>';
		echo '</form>';
		
	} // end authorication code submitted.
	
} elseif ( 'edit' == $mode ) { // EDIT mode.
	
	$accessToken = $destination_settings['access_token'];
	try {
		$dropboxClient = new \Dropbox\Client( $accessToken, 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) );
	} catch ( \Exception $e ) {
		pb_backupbuddy::alert( 'Dropbox Error #143838: ' . $e->getMessage() . '<br><br>' . pb_backupbuddy::$ui->button( pb_backupbuddy::page_url(), '&larr; go back & retry' ), true );
		return false;
	}
	try {
		$accountInfo = $dropboxClient->getAccountInfo();
	} catch ( \Exception $e ) {
		pb_backupbuddy::alert( 'Dropbox Error #132852: ' . $e->getMessage() . '<br><br>' . pb_backupbuddy::$ui->button( pb_backupbuddy::page_url(), '&larr; go back & retry' ), true );
		return false;
	}
	
	$show_config_form = true; // Enable showing configuration form below.
	
} elseif ( 'save' == $mode ) {
	
	$show_config_form = true;
	
} else { // UNKNOWN mode.
	
	die( 'Error #3283489434: Unknown destination form mode.' );
		
} // End checking mode.



// Display configuration form.
if ( true === $show_config_form ) {
	
	if ( 'save' != $mode ) {
		// Account info.
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_owner',
			'title'		=>		__( 'Dropbox Owner', 'it-l10n-backupbuddy' ),
			'default'	=>		$accountInfo['display_name'] . ' (UID: ' . $accountInfo['uid'] . ') [<a href="' . $accountInfo['referral_link'] . '" target="_blank">' . __('Referral Link', 'it-l10n-backupbuddy' ) .'</a>]',
		) );
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_email',
			'title'		=>		__( 'Email', 'it-l10n-backupbuddy' ),
			'default'	=>		$accountInfo['email'],
		) );
		$settings_form->add_setting( array(
			'type'		=>		'plaintext',
			'name'		=>		'plaintext_quotausage',
			'title'		=>		__('Quota Usage', 'it-l10n-backupbuddy' ),
			'default'	=>		pb_backupbuddy::$format->file_size( $accountInfo['quota_info']['normal'] ) . ' normal + ' . pb_backupbuddy::$format->file_size( $accountInfo['quota_info']['shared'] ) . ' shared out of ' . pb_backupbuddy::$format->file_size( $accountInfo['quota_info']['quota'] ) . ' (' . round( ( ( $accountInfo['quota_info']['normal']+$accountInfo['quota_info']['shared'] ) / $accountInfo['quota_info']['quota'] ) * 100, 2 ) . '%)',
		) );
	}
	
	$default_name = NULL;
	// Settings.
	if ( 'add' == $mode ) {
		$default_name = 'My Dropbox (v2)';
		$settings_form->add_setting( array(
			'type'		=>		'hidden',
			'name'		=>		'access_token',
			'title'		=>		'Access Token for oAuth2',
			'rules'		=>		'required',
			'default'	=>		$accessToken,
		) );
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
	
	
	
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'max_chunk_size',
		'title'		=>		__( 'Max chunk size', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Example: 5] - Enter 0 for no chunking; minimum of 5 if enabling. This is the maximum file size to send in one whole piece. Files larger than this will be transferred in pieces up to this file size one part at a time. This allows to transfer of larger files than you server may allow by breaking up the send process. Chunked files may be delayed if there is little site traffic to trigger them.', 'it-l10n-backupbuddy' ),
		'rules'		=>		'required|int[0-9999999]',
		'css'		=>		'width: 50px;',
		'after'		=>		' MB (recommended; leave at 80mb if unsure)',
		'row_class'	=>		'advanced-toggle',
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
	
} // End showing config form.


