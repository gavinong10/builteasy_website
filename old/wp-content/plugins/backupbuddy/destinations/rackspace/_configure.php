<?php // Settings to display in a form for a user to configure.

$default_name = NULL;
if ( 'add' == $mode ) {
	$default_name = 'My Rackspace Cloud Files';
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
	'name'		=>		'username',
	'title'		=>		__( 'Username', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: badger] - Your Rackspace Cloudfiles username.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-250]',
) );

if ( $mode == 'add' ) { // text mode to show secret key during adding.
	$key_type_mode = 'text';
} else { // pass field to hide secret key for editing.
	$key_type_mode = 'password';
}
$settings_form->add_setting( array(
	'type'		=>		$key_type_mode,
	'name'		=>		'api_key',
	'title'		=>		__( 'API key', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 9032jk09jkdspo9sd32jds9swd039dwe] - Log in to your Rackspace Cloudfiles Account and navigate to Your Account: API Access', 'it-l10n-backupbuddy' ),
	'after'		=>		'',
	'css'		=>		'width: 255px;',
	'rules'		=>		'required|string[1-100]',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'container',
	'title'		=>		__( 'Container', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: wordpress_backups] - This container will NOT be created for you automatically if it does not already exist. Please create it first.', 'it-l10n-backupbuddy' ),
	'after'		=>		'',
	'css'		=>		'width: 255px;',
	'rules'		=>		'string[0-500]',
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
	'type'		=>		'select',
	'name'		=>		'server',
	'title'		=>		__( 'Cloud network', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'https://auth.api.rackspacecloud.com'		=>		'USA',
								'https://lon.auth.api.rackspacecloud.com'		=>		'UK',
							),
	'rules'		=>		'required',
	'row_class'	=>		'advanced-toggle',
) );

$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'service_net',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Use internal service net', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: unchecked] - When checked, BackupBuddy will connect to Rackspace using the internal Service Net (prefixes the host with `snet-`).  If you are hosting within Rackspace this may prevent external bandwidth charges.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'rules'		=>		'',
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