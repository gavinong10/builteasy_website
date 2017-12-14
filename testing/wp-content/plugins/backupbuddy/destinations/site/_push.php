<?php
/* Incoming vars from _manage.php:
 *
 *	$activePluginsA, $activePluginsB, $activeThemeBInfo
 *
 */


_e( "This will <b>push</b> to the destination site to make it match this site's database, media, etc. Contents of the destination site will be overwritten as needed. Verify the details below to make sure this is the correct deployment you wish to commence. You will be given the opportunity to test the changes and undo the database changes before making them permanent.", 'it-l10n-backupbuddy' );

echo ' <span style="position: relative; top: -0.5em; font-size: 0.7em;">&dagger;</span> ';
_e( 'WordPress core files will not be transferred between sites.', 'it-l10n-backupbuddy' );

echo ' <span style="position: relative; top: -0.5em; font-size: 0.7em;">&Dagger;</span> ';
_e( 'BackupBuddy plugin files will not be transferred between sites.', 'it-l10n-backupbuddy' );

echo '<br><br>';


if ( $deployData['remoteInfo']['activeTheme'] == $localInfo['activeTheme'] ) {
	$activeThemeBInfo = ' (' . count( $deployData['pushThemeFiles'] ) . ' files to push)';
} else {
	$activeThemeBInfo = ' (' . __( 'Active theme differs so not updating.', 'it-l10n-backupbuddy' ) . ')';
}


if ( isset( $deployData['remoteInfo']['activeChildTheme'] ) ) {
	if ( $deployData['remoteInfo']['activeChildTheme'] == $localInfo['activeTheme'] ) { // theme & child theme are same (aka not using a child theme)
		$activeChildThemeBSame = '; same as theme so not re-sending';
	} else {
		$activeChildThemeBSame = '';
	}
	
	if ( $deployData['remoteInfo']['activeChildTheme'] == $localInfo['activeChildTheme'] ) {
		$activeChildThemeBInfo = ' (' . count( $deployData['pushChildThemeFiles'] ) . ' files to push' . $activeChildThemeBSame . ')';
	} else {
		$activeChildThemeBInfo = ' (' . __( 'Active child theme differs so not updating.', 'it-l10n-backupbuddy' ) . ')';
	}
	$remoteActiveChildTheme = $deployData['remoteInfo']['activeChildTheme'];
} else {
	$activeChildThemeBInfo = '';
	$remoteActiveChildTheme = __( 'Unknown [NOTE: Remote site does not support detecting child theme. Update remote BackupBuddy]', 'it-l10n-backupbuddy' );
}

$activePluginsBInfo = ' (' . count( $deployData['pushPluginFiles'] ) . ' files to push)';


$headFoot = array( __( 'From this site (source)', 'it-l10n-backupbuddy' ), __( '<b>Pushing</b> to (destination)', 'it-l10n-backupbuddy' ) );
$pushRows = array(
	'Site URL' => array( $localInfo['siteurl'], $deployData['remoteInfo']['siteurl'] ),
	'Home URL' => array( $localInfo['homeurl'], $deployData['remoteInfo']['homeurl'] ),
	'Max Execution Time' => array( $localInfo['php']['max_execution_time'] . ' sec', $deployData['remoteInfo']['php']['max_execution_time'] . ' sec' ),
	'Max Upload File Size' => array( $localInfo['php']['upload_max_filesize'] . ' MB', $deployData['remoteInfo']['php']['upload_max_filesize'] . ' MB' ),
	'Memory Limit' => array( $localInfo['php']['memory_limit'] . ' MB', $deployData['remoteInfo']['php']['memory_limit'] . ' MB' ),
	//'PHP Upload Limit' => array( $localInfo['php']['upload_max_filesize'], $deployData['remoteInfo']['php']['upload_max_filesize'] ),
	'WordPress Version <span style="position: relative; top: -0.5em; font-size: 0.7em;">&dagger;</span>' => array( $localInfo['wordpressVersion'], $deployData['remoteInfo']['wordpressVersion'] ),
	'BackupBuddy Version <span style="position: relative; top: -0.5em; font-size: 0.7em;">&Dagger;</span>' => array( $localInfo['backupbuddyVersion'], $deployData['remoteInfo']['backupbuddyVersion'] ),
	'Active Plugins' => array( $activePluginsA, $activePluginsB . $activePluginsBInfo ),
	'Active Theme' => array( $localInfo['activeTheme'], $deployData['remoteInfo']['activeTheme'] . ' ' . $activeThemeBInfo ),
	'Active Child Theme' => array( $localInfo['activeChildTheme'], $remoteActiveChildTheme . ' ' . $activeChildThemeBInfo ),
	'Media / Attachments' => array( $localInfo['mediaCount'], $deployData['remoteInfo']['mediaCount'] . ' (' . count( $deployData['pushMediaFiles'] ) . ' files to push)' ),
);


$deployDirection = 'push';
require( '_pushpull_foot.php' );