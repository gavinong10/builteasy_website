<?php
backupbuddy_core::verifyAjaxAccess();


// Settings page backup profile editing.
/* profile_settings()
 *
 * View a specified profile's settings.
 *
 */

pb_backupbuddy::$ui->ajax_header();
require_once( pb_backupbuddy::plugin_path() . '/views/settings/_includeexclude.php' );
pb_backupbuddy::$ui->ajax_footer();
die();

