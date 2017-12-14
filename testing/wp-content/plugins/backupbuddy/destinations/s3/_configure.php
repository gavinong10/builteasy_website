<?php // Settings to display in a form for a user to configure.
/*
	Pre-populated variables coming into this script:
		$destination_settings
		$mode
*/

global $pb_hide_test, $pb_hide_save;
$pb_hide_test = false;


if ( ! is_callable( 'curl_init' ) ) {
	pb_backupbuddy::alert( 'Error #43893489: The Amazon S3 destination requires curl. Please enable it on your server to use this destination.', true );
	echo '<br>';
	//return false;
}


$default_name = NULL;

if ( $mode != 'save' ) {
	
	if ( $mode == 'add' ) {
		$default_name = 'My S3';
	}
	
} else { // save mode
	if ( isset( $_POST['pb_backupbuddy_directory'] ) ) {
		$_POST['pb_backupbuddy_bucket'] = strtolower( $_POST['pb_backupbuddy_bucket'] ); // bucket must be lower-case.
	}
}


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
	'name'		=>		'accesskey',
	'title'		=>		__( 'AWS access key', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: BSEGHGSDEUOXSQOPGSBE] - Log in to your Amazon S3 AWS Account and navigate to Account: Access Credentials: Security Credentials.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
	'after'		=>		' <a target="_new" href="http://ithemes.com/codex/page/BackupBuddy_Remote_Destinations:_Amazon_S3">Help setting up S3</a>',
) );


if ( $mode == 'add' ) { // text mode to show secret key during adding.
	$secretkey_type_mode = 'text';
} else { // pass field to hide secret key for editing.
	$secretkey_type_mode = 'password';
}
$settings_form->add_setting( array(
	'type'		=>		$secretkey_type_mode,
	'name'		=>		'secretkey',
	'title'		=>		__( 'AWS secret key', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: GHOIDDWE56SDSAZXMOPR] - Log in to your Amazon S3 AWS Account and navigate to Account: Access Credentials: Security Credentials.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'bucket',
	'title'		=>		__( 'Bucket name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: wordpress_backups] - This bucket will be created for you automatically if it does not already exist. Bucket names must be globally unique amongst all Amazon S3 users.', 'it-l10n-backupbuddy' ),
	'after'		=>		'',
	'rules'		=>		'required|string[1-500]',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'archive_limit',
	'title'		=>		__( 'Remote archive limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of backup archives to be stored in this specific destination. If this limit is met the oldest backup will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'directory',
	'title'		=>		__( 'Directory (optional)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: backupbuddy] - Directory name to place the backup within.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'string[0-500]',
) );





$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'advanced_begin',
	'title'		=>		'<span class="dashicons dashicons-arrow-right"></span> ' . __( 'Advanced Options', 'it-l10n-backupbuddy' ),
	'row_class'	=>		'advanced-toggle-title',
) );

$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'region',
	'title'		=>		__( 'New bucket region', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								's3.amazonaws.com'					=>		'US Standard [default]',
								's3-us-west-2.amazonaws.com'		=>		'US West (Oregon)',
								's3-us-west-1.amazonaws.com'		=>		'US West (Northern California)',
								's3-eu-central-1.amazonaws.com'		=>		'EU (Frankfurt)',
								's3-eu-west-1.amazonaws.com'		=>		'EU (Ireland)',
								's3-ap-southeast-1.amazonaws.com'	=>		'Asia Pacific (Singapore)',
								's3-ap-southeast-2.amazonaws.com'	=>		'Asia Pacific (Sydney)',
								's3-ap-northeast-1.amazonaws.com'	=>		'Asia Pacific (Tokyo)',
								's3-sa-east-1.amazonaws.com'		=>		'South America (Sao Paulo)',
								/*
								's3-us-gov-west-1.amazonaws.com'			=>		'US GovCloud',
								's3-fips-us-gov-west-1.amazonaws.com'		=>		'US GovCloud (FIPS 140-2)',
								's3-website-us-gov-west-1.amazonaws.com'	=>		'US GovCloud (website)',
								*/
							),
	'tip'		=>		__('[Default: US Standard] - Determines the region where NEW buckets will be created (if any). If your bucket already exists then it will NOT be modified.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
	'after'		=>		' <span class="description">Applies to <b>new</b> buckets only.</span>',
	'row_class'	=>		'advanced-toggle',
) );

$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'storage',
	'title'		=>		__( 'Storage Class', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'standard'					=>		'Standard Storage [default]',
								'reduced'					=>		'Reduced Redundancy',
							),
	'tip'		=>		__('[Default: Standard Storage] - Determines the type of storage to use when placing this file on Amazon S3. Reduced redundancy offers less protection against loss but costs less. See Amazon for for details.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
	'row_class'	=>		'advanced-toggle',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_chunk_size',
	'title'		=>		__( 'Max chunk size', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no chunking; minimum of 5 if enabling. This is the maximum file size to send in one whole piece. Files larger than this will be transferred in pieces up to this file size one part at a time. This allows to transfer of larger files than you server may allow by breaking up the send process. Chunked files may be delayed if there is little site traffic to trigger them. Amazon recommends 100mb chunk sizes or less.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' MB. <span class="description">' . __( 'Default', 'it-l10n-backupbuddy' ) . ': 80 MB</span>',
	'row_class'	=>		'advanced-toggle',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ssl',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Encrypt connection', 'it-l10n-backupbuddy' ) . '*',
	'tip'		=>		__( '[Default: enabled] - When enabled, all transfers will be encrypted with SSL encryption. Disabling this may aid in connection troubles but results in lessened security. Note: Once your files arrive on our server they are encrypted using AES256 encryption. They are automatically decrypted upon download as needed.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Enable connecting over SSL.', 'it-l10n-backupbuddy' ) . '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Files are always encrypted with AES256 upon arrival at S3.</span>',
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