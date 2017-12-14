<?php
backupbuddy_core::verifyAjaxAccess();


/* migration_picker()
 *
 * Same as destination picker but in migration mode (only limited destinations are available).
 *
 */
pb_backupbuddy::load();

pb_backupbuddy::$ui->ajax_header();

$mode = 'migration';
require_once( '_destination_picker.php' );

pb_backupbuddy::$ui->ajax_footer();
die();
