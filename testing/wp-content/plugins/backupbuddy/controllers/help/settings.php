<?php
// Incoming variable: $screen

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_general',
	'title'   => __( 'General Settings', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'This section contains the basic settings for BackupBuddy. At a minimum you should configure the ImportBuddy password and notification emails so you will be notified if backups fail.', 'it-l10n-backupbuddy' ) .
				'</p>',
));

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_advanced',
	'title'   => __( 'Advanced Settings', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'BackupBuddy contains many advanced settings for power users.  Additionally as servers differ significantly, there can sometimes be problems with functionality due to server problems or limitations.  Advanced settings allow you to override advanced functionality to work around server problems. Technical support may advise you to enable or disable certain features to work around issues or troubleshoot problems.', 'it-l10n-backupbuddy' ) .
				'</p>',
));

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_misc',
	'title'   => __( 'Logging & Misc', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'BackupBuddy by default only logs errors. The Advanced Settings section allows enabling logging all activity to this page for advanced troubleshooting. You may force clearing out temporary files, reset dismissed popup alerts, and view debugging data as well.', 'it-l10n-backupbuddy' ) .
				'</p>',
));