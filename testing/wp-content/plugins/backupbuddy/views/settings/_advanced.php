<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }
?>



<script>
	function bb_checkZipSystem() {
		if ( jQuery( 'input#pb_backupbuddy_alternative_zip_2' ).is( ':checked' ) ) {
			jQuery( '.bb-alternate-zip-options' ).show();
		} else {
			jQuery( '.bb-alternate-zip-options' ).hide();
		}
	}
	jQuery(document).ready(function() {
		
		jQuery( 'input#pb_backupbuddy_alternative_zip_2' ).change( function(){
			bb_checkZipSystem();
		});
		
		bb_checkZipSystem(); // Run first time.
		
	});
</script>


<style>
	.bb-alternate-zip-options {
		display: none;
	}
</style>



<?php
$settings_form = new pb_backupbuddy_settings( 'advanced_settings', '', 'tab=1', 320 );



$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_basic',
	'title'		=>		__( 'Basic Operation', 'it-l10n-backupbuddy' ),
) );





$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'backup_reminders',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Enable backup reminders', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: enabled] - When enabled links will be displayed upon post or page edits and during WordPress upgrades to remind and allow rapid backing up after modifications or before upgrading.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'archive_name_format',
	'options'	=>		array(
							'date' => 'Date only [default]',
							'datetime' => 'Date + time (12hr format)',
							'datetime24' => 'Date + time (24hr format)',
							'timestamp' => 'Unix Timestamp',
						),
	'title'		=>		__( 'Backup file name date/time', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled (date only)] - When enabled your backup filename will display the time the backup was created in addition to the default date. This is useful when making multiple backups in a one day period.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'archive_name_profile',
	'options'	=>		array( 'unchecked' => 0, 'checked' => 1 ),
	'title'		=>		__( 'Add the backup profile to backup file name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled your backup filename will display the backup profile used to initiate the backup. This is useful when making multiple backups from different profiles.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'lock_archives_directory',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Lock archive directory (high security)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled all downloads of archives via the web will be prevented under all circumstances via .htaccess file. If your server permits it, they will only be unlocked temporarily on click to download. If your server does not support this unlocking then you will have to access the archives via the server (such as by FTP).', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('May prevent downloading backups within WordPress on incompatible servers', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'include_importbuddy',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Include ImportBuddy in full backup archive', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: enabled] - When enabled, the importbuddy.php (restoration tool) file will be included within the backup archive ZIP file in the location `/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ) . ' xxxxxxxxxx/ importbuddy.php` where the x\'s match the unique random string in the backup ZIP filename.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		' <span style="white-space: nowrap;"><span class="description">' . __( 'Located in backup', 'it-l10n-backupbuddy' ) . ':</span>&nbsp; <span class="code" style="white-space: normal; background: #EAEAEA;"">/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ) . 'xxxxxxxxxx/importbuddy.php</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'default_backup_tab',
	'title'		=>		__('Default backup tab', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'0'		=>		__( 'Overview', 'it-l10n-backupbuddy' ),
								'1'		=>		__( 'Status Log', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		sprintf( __('[Default: Overview] - The default tab open during a backup is the overview tab. A more technical view is available in the Status tab.', 'it-l10n-backupbuddy' ) ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disable_localization',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable language localization', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Unchecked] When checked language localization support will be disabled. BackupBuddy will revert to full English language mode. Use this to display logs in English for support.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check to run BackupBuddy in English. This is useful for support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'remote_send_timeout_retries',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Retry timed out remote sends', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Checked] When checked BackupBuddy will attempt ONCE at resending a timed out remote destination send.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check to re-attempt timed out sends once.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'set_greedy_execution_time',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Attempt to override PHP max execution time', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Unchecked] When checked BackupBuddy will attempt to override the default PHP maximum execution time to 7200 seconds.  Note that almost all shared hosting providers block this attempt.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check to force execution time override attempt (most hosts block this).', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'archive_limit_size_big',
	'title'		=>		__('Maximum local storage usage', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Example: 50000] - Maximum size (in MB) to allow BackupBuddy to use. This is a safeguard limit which should be set HIGHER than any other local archive size limits.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int|int',
	'css'		=>		'width: 75px;',
	'after'		=>		' MB. <span class="description">0 for no limit.</span>',
) );




$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_logging',
	'title'		=>		__( 'Logging', 'it-l10n-backupbuddy' ),
) );



$log_file = backupbuddy_core::getLogDirectory() . 'log-' . pb_backupbuddy::$options['log_serial'] . '.txt';
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'log_level',
	'title'		=>		'<b>' . __('Logging Level', 'it-l10n-backupbuddy' ) . '</b>',
	'options'	=>		array(
								'0'		=>		__( 'None', 'it-l10n-backupbuddy' ),
								'1'		=>		__( 'Errors Only (default)', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'Errors & Warnings', 'it-l10n-backupbuddy' ),
								'3'		=>		__( 'Everything (troubleshooting mode)', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		sprintf( __('[Default: Errors Only] - This option controls how much activity is logged for records or troubleshooting. Logs may be viewed from the Logs / Other tab on the Settings page. Additionally when in Everything / Troubleshooting mode error emails will contain encrypted troubleshooting data for support. Log file: %s', 'it-l10n-backupbuddy' ), $log_file ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'save_backup_sum_log',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Temporarily save full backup status logs', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Checked] When checked BackupBuddy will temporarily (~10 days) save the complete full backup status log, regardless of the Logging Level setting.  This is useful for troubleshooting passed backups. View logs by hovering a backup on the Backups page and clicking "View Log".', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Temporarily save full backup status logs.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_site_log_size',
	'title'		=>		__('Maximum main log file size', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: 10 MB] - If the log file exceeds this size then it will be cleared to prevent it from using too much space.' ),
	'rules'		=>		'required|int',
	'css'		=>		'width: 50px;',
	'after'		=>		' MB',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_send_stats_days',
	'title'		=>		__('Recent remote send stats max age', 'it-l10n-backupbuddy' ),
	'tip'		=>		sprintf( __('[Default: Errors Only] - This option controls how much activity is logged for records or troubleshooting. Logs may be viewed from the Logs / Other tab on the Settings page. Additionally when in Everything / Troubleshooting mode error emails will contain encrypted troubleshooting data for support. Log file: %s', 'it-l10n-backupbuddy' ), $log_file ),
	'tip'		=>		__('[Default: 7 days] - Number of days to store recently sent file statistics & logs. Valid options are 1 to 90 days.' ),
	'css'		=>		'width: 50px;',
	'rules'		=>		'required|int[1-90]',
	'after'		=>		' days',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_send_stats_count',
	'title'		=>		__('Recent remote send stats max number', 'it-l10n-backupbuddy' ),
	'tip'		=>		sprintf( __('[Default: Errors Only] - This option controls how much activity is logged for records or troubleshooting. Logs may be viewed from the Logs / Other tab on the Settings page. Additionally when in Everything / Troubleshooting mode error emails will contain encrypted troubleshooting data for support. Log file: %s', 'it-l10n-backupbuddy' ), $log_file ),
	'tip'		=>		__('[Default: 7 days] - Maximum number of recently sent file statistics & logs to store. Valid options are 1 to 25 sends.' ),
	'css'		=>		'width: 50px;',
	'rules'		=>		'required|int[1-25]',
	'after'		=>		' sends',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_notifications_age_days',
	'title'		=>		__('Maximum days to keep recent activity', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: 21 days] - Number of days to store recent activity notifications / audits.' ),
	'rules'		=>		'required|int',
	'css'		=>		'width: 50px;',
	'after'		=>		' days',
) );






$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_advanced',
	'title'		=>		__( 'Technical & Server Compatibility', 'it-l10n-backupbuddy' ),
) );






$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'backup_mode',
	'title'		=>		'<b>' . __('Default global backup mode', 'it-l10n-backupbuddy' ) . '</b>',
	'options'	=>		array(
								'1'		=>		__( 'Classic (v1.x) - Entire backup in single PHP page load', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'Modern (v2.x+) - Split across page loads via WP cron', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		__('[Default: Modern] - If you are encountering difficulty backing up due to WordPress cron, HTTP Loopbacks, or other features specific to version 2.x you can try classic mode which runs like BackupBuddy v1.x did.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'delete_archives_pre_backup',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Delete all backup archives prior to backups', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled all local backup archives will be deleted prior to each backup. This is useful if in compatibilty mode to prevent backing up existing files.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Use if exclusions are malfunctioning or for special purposes.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disable_https_local_ssl_verify',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable local SSL certificate verification', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] When checked, WordPress will skip local https SSL verification.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Workaround if local SSL verification fails (ie. for loopback & local CA cert issues).', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'prevent_flush',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Prevent Flushing', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: not prevented (unchecked)] - Rarely some servers die unexpectedly when flush() or ob_flush() are called multiple times during the same PHP process. Checking this prevents these from ever being called during backups.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'save_comment_meta',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Save meta data in comment', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Enabled] When enabled, BackupBuddy will store general backup information in the ZIP comment header such as Site URL, backup type & time, serial, etc. during backup creation.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'If backups hang when saving meta data disabling skips this process.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'profiles#0#integrity_check',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Perform integrity check on backup files', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: enabled] - By default each backup file is checked for integrity and completion the first time it is viewed on the Backup page.  On some server configurations this may cause memory problems as the integrity checking process is intensive.  If you are experiencing out of memory errors on the Backup file listing, you can uncheck this to disable this feature.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Disable if the backup page will not load or backups hang on integrity check.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'backup_cron_rescheduling',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Reschedule missing crons in manual backups', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - To proceed to subsequent steps during backups BackupBuddy schedules the next step with the WordPress cron system.  If this cron goes missing the backup cannot proceed. This feature instructs BackupBuddy to attempt to re-schedule this cron as it occurs.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'skip_spawn_cron_call',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Skip chained spawn of cron', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - When skipping is enabled BackupBuddy will not call spawn_cron() in an attempt to force chaining of cron processes. spawn_cron() cause trouble on some servers.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'backup_cron_passed_force_time',
	'title'		=>		__('Force cron if behind by X seconds', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: blank] - When in the default modern mode BackupBuddy schedules each backup step with the WordPress simulated cron. If cron steps are not running when they should and the Status Log reports steps should have run many seconds ago, this may help to force BackupBuddy to demand WordPress run the cron step now. Manual backups only; not scheduled.', 'it-l10n-backupbuddy' ),
	'css'		=>		'width: 50px;',
	'after'		=>		' secs. <span class="description"> ' . __( 'Leave blank for default of no forcing.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'',
) );







$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_database',
	'title'		=>		__( 'Database', 'it-l10n-backupbuddy' ),
) );






$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'database_method_strategy',
	'title'		=>		'<b>' . __('Database method strategy', 'it-l10n-backupbuddy' ) . '</b>',
	'options'	=>		array(
		'php'			=>		__( 'PHP-based: Supports automated chunked resuming - default', 'it-l10n-backupbuddy' ),
		'commandline'	=>		__( 'Commandline: Fast but does not support resuming', 'it-l10n-backupbuddy' ),
		'all'			=>		__( 'All Available: ( PHP [chunking] > Commandline via exec()  )', 'it-l10n-backupbuddy' ),
	),
	'tip'		=>		__('[Default: PHP-based] - Normally use PHP-based which supports chunking (as of BackupBuddy v5) to support larger databases. Commandline-based database dumps use mysqldump which is very fast and efficient but cannot be broken up into smaller steps if it is too large which could result in timeouts on larger servers.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'profiles#0#skip_database_dump',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Skip database dump on backup', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - (WARNING: This prevents BackupBuddy from backing up the database during any kind of backup. This is for troubleshooting / advanced usage only to work around being unable to backup the database.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Completely bypass backing up database for all database types. Use caution.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
	'orientation' =>	'vertical',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'breakout_tables',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Break out big table dumps into steps', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: enabled] When enabled, BackupBuddy will dump some of the commonly larger tables in separate steps. Note this only applies to command-line based dumps as PHP-based dumps automatically support chunking with resume on table and/or row as needed.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Commandline method: Break up dumping of big tables (chunking)', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'force_single_db_file',
	'options'	=>		array( 'unchecked' => '1', 'checked' => '0' ),
	'title'		=>		__( 'Use separate files per table (when possible)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: enabled] When enabled, BackupBuddy will dump individual tables to their own database file (eg wp_options.sql, wp_posts.sql, etc) when possible based on other criteria such as the dump method and whether breaking out big tables is enabled.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Uncheck to force dumping all tables into a single db_1.sql file.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'phpmysqldump_maxrows',
	'title'		=>		__('Compatibility mode max rows per select', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: *blank*] - When BackupBuddy is using compatibility mode mysql dumping (via PHP), BackupBuddy selects data from the database. Reducing this number has BackupBuddy grab smaller portions from the database at a time. Leave blank to use built in default (around 2000 rows per select).', 'it-l10n-backupbuddy' ),
	'css'		=>		'width: 50px;',
	'after'		=>		' rows. <span class="description"> ' . __( 'Blank for default.', 'it-l10n-backupbuddy' ) . ' (~1000 rows/select)</span>',
	'rules'		=>		'int',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_execution_time',
	'title'		=>		'<b>' . __('Maximum time per chunk', 'it-l10n-backupbuddy' ) . '</b>',
	'tip'		=>		__('[Default: *blank*] - The maximum amount of time BackupBuddy should allow a database import chunk to run. BackupBuddy by default limits each chunk to your Maximum PHP runtime when using the default PHP-based method. If your database dump step is timing out then lowering this value will instruct the script to limit each `chunk` to allow it to finish within this time period. Raising this value above your servers limits will not increase or override server settings.', 'it-l10n-backupbuddy' ),
	'css'		=>		'width: 50px;',
	'after'		=>		' sec. <span class="description"> ' . __( 'Blank for detected default:', 'it-l10n-backupbuddy' ) . ' ' . backupbuddy_core::detectMaxExecutionTime() . ' sec</span>',
	'rules'		=>		'int',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_command_length_check',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Skip max command line length check ', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - WARNING: BackupBuddy attempts to determine your system\'s maximum command line length to insure that database operation commands do not get inadvertantly cut off. On some systems it is not possible to reliably detect this information which could result in falling back into compatibility mode even though the system is capable of running in normal operational modes. This option instructs BackupBuddy to skip the command line length check.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );



$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_zip',
	'title'		=>		__( 'Zip', 'it-l10n-backupbuddy' ),
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'compression', //'profiles#0#compression',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		'<b>' . __( 'Enable zip compression', 'it-l10n-backupbuddy' ) . '</b>',
	'tip'		=>		__( '[Default: enabled] - ZIP compression decreases file sizes of stored backups. If you are encountering timeouts due to the script running too long, disabling compression may allow the process to complete faster.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Unchecking typically DOUBLES the amount of data which may be zipped up before timeouts.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'zip_method_strategy',
	'title'		=>		'<b>' . __('Zip method strategy', 'it-l10n-backupbuddy' ) . '</b>',
	'options'	=>		array(
								'1'		=>		__( 'Best Available', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'All Available', 'it-l10n-backupbuddy' ),
								'3'		=>		__( 'Force Compatibility', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		__('[Default: Best Only] - Normally use Best Available but if the server is unreliable in this mode can try All Available or Force Compatibility', 'it-l10n-backupbuddy' ),
	'after'		=>		'<span class="description"> ' . __('Select Force Compatibility if absolutely necessary.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'alternative_zip_2',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		'<b>' . __( 'Alternative zip system (BETA)', 'it-l10n-backupbuddy' ) . '</b>',
	'tip'		=>		__( '[Default: Disabled] Use if directed by support.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check if directed by support.</span>',
	'rules'		=>		'required',
) );


//if ( isset( pb_backupbuddy::$options[ 'alternative_zip_2' ] ) && ( ( '1' == pb_backupbuddy::$options[ 'alternative_zip_2' ] ) || ( true == pb_backupbuddy::$options[ 'alternative_zip_2' ] ) ) ) {
	$settings_form->add_setting( array(
		'type'		=>		'select',
		'name'		=>		'zip_build_strategy',
		'title'		=>		'<b>' . __('Zip build strategy', 'it-l10n-backupbuddy' ) . '</b>',
		'options'	=>		array(
									'2'		=>		__( 'Single-Burst/Single-Step', 'it-l10n-backupbuddy' ),
									'3'		=>		__( 'Multi-Burst/Single-Step', 'it-l10n-backupbuddy' ),
									'4'		=>		__( 'Multi-Burst/Multi-Step', 'it-l10n-backupbuddy' ),
								),
		'tip'		=>		__('[Default: Multi-Burst/Single-Step] - Most backups can complete the zip build with the multi-burst/single-step strategy. Single-Burst/Single-Step can give a faster build on good servers. Multi-Burst/Multi-Step is required for slow servers that timeout during the zip build.', 'it-l10n-backupbuddy' ),
		'after'		=>		'<span class="description"> ' . __('Select Multi-Burst/Multi-Step if server timing out during zip build', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'required',
		'row_class'	=>		'bb-alternate-zip-options',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'zip_step_period',
		'title'		=>		'<b>' . __('Maximum time per chunk', 'it-l10n-backupbuddy' ) . '</b>',
		'tip'		=>		__('[Default: *blank* - 30s] - The maximum amount of time BackupBuddy should allow a zip archive build to run before pausing and scheduling a continuation step. BackupBuddy by default will allow the zip archive build to run for an indefinite period until completion but some servers will prematurely timeout without notice and this can cause the zip archive build to stall. This option allows BackupBuddy to pause after the specified period and schedule a continuation step. If your zip archive build is timing out then setting a value here that is comfortably within your server timeout constraints will help your backup progress.', 'it-l10n-backupbuddy' ),
		'css'		=>		'width: 50px;',
		'after'		=>		' sec. <span class="description"> ' . __( 'Blank for default (30s), 0 for infinite', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'int',
		'row_class'	=>		'bb-alternate-zip-options',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'zip_burst_gap',
		'title'		=>		'<b>' . __('Gap between zip build bursts', 'it-l10n-backupbuddy' ) . '</b>',
		'tip'		=>		__('[Default: *blank* - 2s] - The time gap BackupBuddy will apply between each zip archive build burst. Some servers/hosting may benefit from having a small period of time between bursts to allow the server to catch up with file based operations and/or allowing the average load over time to be reduced by spreading out cpu and disk usage. Warning - if the value is set too high some servers may prematurely timeout without notice.', 'it-l10n-backupbuddy' ),
		'css'		=>		'width: 50px;',
		'after'		=>		' sec. <span class="description"> ' . __( 'Blank for default (2s)', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'int',
		'row_class'	=>		'bb-alternate-zip-options',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'zip_min_burst_content',
		'title'		=>		'<b>' . __('Minimum content size for a single burst (MB)', 'it-l10n-backupbuddy' ) . '</b>',
		'tip'		=>		__('[Default: 10] - The minimum content size that BackupBuddy will try for in a zip build burst. If a zip build requires multiple bursts then the actual content size for continuation burst is adaptively varied up to the limit imposd by the maximum burst content size setting.', 'it-l10n-backupbuddy' ),
		'css'		=>		'width: 50px;',
		'after'		=>		' MB <span class="description"> ' . __( 'Blank for default (10MB), 0 for no minimum', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'int',
		'row_class'	=>		'bb-alternate-zip-options',
	) );
	$settings_form->add_setting( array(
		'type'		=>		'text',
		'name'		=>		'zip_max_burst_content',
		'title'		=>		'<b>' . __('Maximum content size for a single burst (MB)', 'it-l10n-backupbuddy' ) . '</b>',
		'tip'		=>		__('[Default: 100] - The maximum content size that BackupBuddy will try for in a zip build burst. If a zip build requires multiple bursts then the actual content size for continuation burst is adaptively varied up to the limit imposd by the maximum burst content size setting.', 'it-l10n-backupbuddy' ),
		'css'		=>		'width: 50px;',
		'after'		=>		' MB <span class="description"> ' . __( 'Blank for default (100MB), 0 for no maximum', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'int',
		'row_class'	=>		'bb-alternate-zip-options',
	) );
//}


$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disable_zipmethod_caching',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable zip method caching', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] Use if directed by support. Bypasses caching available zip methods so they are always displayed in logs. When unchecked BackupBuddy will cache command line zip testing for a few minutes so it does not run too often. This means that your backup status log may not always show the test results unless you disable caching.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check if directed by support to always log zip detection.</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_zip_warnings',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Ignore zip archive warnings', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] When enabled BackupBuddy will ignore non-fatal warnings encountered during the backup process such as inability to read or access a file, symlink problems, etc. These non-fatal warnings will still be logged.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check to ignore non-fatal errors when zipping files.</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_zip_symlinks',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Ignore/do-not-follow symbolic links', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Enabled] When enabled BackupBuddy will ignore/not-follow symbolic links encountered during the backup process', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Symbolic links are followed by default. Unfollowable links may cause failures.</span>',
	'rules'		=>		'required',
) );







$settings_form->process(); // Handles processing the submitted form (if applicable).
$settings_form->display_settings( 'Save Advanced Settings' );

