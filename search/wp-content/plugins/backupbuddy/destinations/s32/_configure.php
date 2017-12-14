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
		$default_name = 'My S3 (v2)';
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
	'css'		=>		'width: 250px;',
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
	'css'		=>		'width: 250px;',
	'rules'		=>		'required|string[1-45]',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'bucket',
	'title'		=>		__( 'Bucket name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: wordpress_backups] - This bucket will be created for you automatically if it does not already exist. Bucket names must be globally unique amongst all Amazon S3 users.', 'it-l10n-backupbuddy' ),
	'after'		=>		'',
	'css'		=>		'width: 250px;',
	'rules'		=>		'required|string[1-500]',
) );

$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'region',
	'title'		=>		__( 'Bucket region', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								's3.amazonaws.com'					=>		'us-east-1 &nbsp;|&nbsp; US East (US Standard)',
								's3-us-west-2.amazonaws.com'		=>		'us-west-2 &nbsp;|&nbsp; US West (Oregon)',
								's3-us-west-1.amazonaws.com'		=>		'us-west-1 &nbsp;|&nbsp; US West (Northern California)',
								's3-eu-central-1.amazonaws.com'		=>		'eu-central-1 &nbsp;|&nbsp; EU (Frankfurt)',
								's3-eu-west-1.amazonaws.com'		=>		'eu-west-1 | EU (Ireland)',
								's3-ap-southeast-1.amazonaws.com'	=>		'ap-southeast-1 &nbsp;|&nbsp; Asia Pacific (Singapore)',
								's3-ap-southeast-2.amazonaws.com'	=>		'ap-southeast-2 &nbsp;|&nbsp; Asia Pacific (Sydney)',
								's3-ap-northeast-1.amazonaws.com'	=>		'ap-northeast-1 &nbsp;|&nbsp; Asia Pacific (Tokyo)',
								's3-sa-east-1.amazonaws.com'		=>		'sa-east-1 &nbsp;|&nbsp; South America (Sao Paulo)',
								/*
								's3-us-gov-west-1.amazonaws.com'			=>		'US GovCloud',
								's3-fips-us-gov-west-1.amazonaws.com'		=>		'US GovCloud (FIPS 140-2)',
								's3-website-us-gov-west-1.amazonaws.com'	=>		'US GovCloud (website)',
								*/
							),
	'tip'		=>		__('[Default: US East aka US Standard] - Determines the region where your S3 bucket exists. This must be correct for BackupBuddy to access your bucket.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'directory',
	'title'		=>		__( 'Directory (optional)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: backupbuddy] - Directory name to place the backup within.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'string[0-500]',
) );



$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'full_archive_limit',
	'title'		=>		__( 'Full backup limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Full (complete) backup archives for this site (based on filename) to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'db_archive_limit',
	'title'		=>		__( 'Database only limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Database Only backup archives for this site (based on filename) to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'files_archive_limit',
	'title'		=>		__( 'Files only limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Files Only backup archives for this site (based on filename) to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
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
	'name'		=>		'storage',
	'title'		=>		__( 'Storage Class', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'STANDARD'				=>		'Standard Storage [default]',
								'REDUCED_REDUNDANCY'	=>		'Reduced Redundancy',
							),
	'tip'		=>		__('[Default: Standard Storage] - Determines the type of storage to use when placing this file on Amazon S3. Reduced redundancy offers less protection against loss but costs less. See Amazon for for details.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
	'row_class'	=>		'advanced-toggle',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_burst',
	'title'		=>		__( 'Send per burst', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default 15] - This is the amount of data that will be sent per burst within a single PHP page load/chunk. Bursts happen within a single page load. Chunks occur when broken up between page loads/PHP instances. Reduce if hitting PHP memory limits. Chunking time limits will only be checked between bursts. Lower burst size if timeouts occur before chunking checks trigger.', 'it-l10n-backupbuddy' ),
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

/*
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'skip_bucket_prepare',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Skip bucket preparation', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled, BackupBuddy will not verify the bucket exists (and create it if missing) before trying to upload files so it must already exist. Additionally, the region will not be verified to be correct for the bucket so make sure you are connecting to the correct region for your bucket.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Skips bucket validation, creation, and region checks.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'',
	'row_class'	=>		'advanced-toggle',
) );
*/


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