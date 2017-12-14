<?php
/**
 * Main installer script
 */


defined( 'TVE_TCB_DB_UPGRADE' ) or exit();
global $wpdb;

$table_prefix  = $wpdb->prefix . 'tcb_';
$api_log_table = $table_prefix . 'api_error_log';

$sql = "CREATE TABLE IF NOT EXISTS {$api_log_table}(
    `id` INT( 11 ) AUTO_INCREMENT,
    `date` DATETIME NULL,
    `error_message` VARCHAR( 400 ) NULL,
    `api_data` TEXT NULL,
    `connection` VARCHAR( 64 ) NULL,
    `list_id` VARCHAR( 255 ) NULL,
     PRIMARY KEY( `id` )
 )";

$wpdb->query( $sql );