<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::$ui->ajax_header();
pb_backupbuddy::load_style( 'thickboxed.css' );
require_once( pb_backupbuddy::plugin_path() . '/views/_quicksetup.php' );
pb_backupbuddy::$ui->ajax_footer();
die();