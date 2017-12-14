<?php
// Incoming variable: $screen

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_full',
	'title'   => __( 'Full Backups', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'Full Backups by default contain all of your WordPress files, WordPress database, settings, media details and files, posts, content, and more. All files in your WordPress directory and beneath it will be backed up by default.  All database tables beginning with your WordPress database table will be backed up.  You may optionally include or exclude additional database content or files.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Full Backups are typically scheduled to occur less often (ie weekly) than Database Only backups as they can be quite large and the files within typically change less often.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Rule of thumb when determing backup schedules: How far back are you willing to lose if something goes wrong and your backup is needed"', 'it-l10n-backupbuddy' ) .
				'</p>',
));

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_db',
	'title'   => __( 'Database Only Backups', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'Database Only Backups by default contain all of your WordPress database, many settings, media details (but not the actual media files), posts, content, and more. All database tables beginning with your WordPress database table will be backed up by default.  You may optionally include or exclude additional database content.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Database Only Backups are typically scheduled to occur very often (ie daily) as they are usually relatively small and contain your most-changed content.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Rule of thumb when determing backup schedules: How far back are you willing to lose if something goes wrong and your backup is needed?', 'it-l10n-backupbuddy' ) .
				'</p>',
));

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_files',
	'title'   => __( 'Files Only Backups', 'it-l10n-backupbuddy' ),
	'content' => '<p>' .
				__( 'Files Only Backups by default contain all of your WordPress files, media files, and more. All files in your WordPress directory and beneath it will be backed up by default.   You may optionally include or exclude additional files.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Full Backups are typically scheduled to occur less often (ie weekly) than Database Only backups as they can be quite large and the files within typically change less often. This will vary depending on which specific files you are choosing to backup for your specified profile.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Rule of thumb when determing backup schedules: How far back are you willing to lose if something goes wrong and your backup is needed?', 'it-l10n-backupbuddy' ) .
				'</p>',
));