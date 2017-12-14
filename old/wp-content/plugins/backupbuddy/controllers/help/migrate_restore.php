<?php
// Incoming variable: $screen

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_restore',
	'title'   => __( 'Restore', 'it-l10n-backupbuddy' ),
	'content' => '<p><b>' . __( 'Restore', 'it-l10n-backupbuddy' ) . '</b>: ' .
				__( 'Restoring re-creates your backed up site back to the same server, directory, and URL where it was previously located.  This is typically done if the site was damaged or accidently deleted. This process mostly only differs from a Migration in the preparation.  Once the restore actually begins, it is mostly the same as migration.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'If your old site or WordPress installation already exists, you must delete it first before proceeding. If you would like to re-use the same database settings, you may do so but on Step 3 of running the restore script, importbuddy.php, click the "Advanced Options" button and select to wipe the existing database contents.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Follow the standard Restore / Migrate instructions in the page below.', 'it-l10n-backupbuddy' ) .
				'</p>',
));

$screen->add_help_tab(
	array(
	'id'      => 'pb_backupbuddy_migrate',
	'title'   => __( 'Migrate', 'it-l10n-backupbuddy' ),
	'content' => '<p><b>' . __( 'Migrate', 'it-l10n-backupbuddy' ) . '</b>: ' .
				__( 'Migrating re-creates your backed up site on a new server and/or directory and/or URL.  This is typically done when changing web hosts, URLs, or duplicating as a starting point for a new website. This process mostly only differs from a Migration in the preparation.  Additionally on Step 3 of the import script, importbuddy.php, you will be prompted for the new site URL.  This is usually automatically detected for you based on the location importbuddy.php is run from.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Do not install WordPress on the destination server prior to migrating. If you keep the same database settings on the destination site as they were before then your previous site where the backup was made will likely encounter problems as you will have two sites sharing the same database.', 'it-l10n-backupbuddy' ) .
				'<br><br>' .
				__( 'Follow the standard Restore / Migrate instructions in the page below.', 'it-l10n-backupbuddy' ) .
				'</p>',
));