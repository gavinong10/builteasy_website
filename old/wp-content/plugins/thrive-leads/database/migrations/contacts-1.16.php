<?php
/* Creates tables for storing contact data */

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();
global $wpdb;

$contact_table = tve_leads_table_name( 'contacts' );

$sql = "CREATE TABLE IF NOT EXISTS {$contact_table}(
    `id` INT( 11 ) AUTO_INCREMENT,
    `log_id` INT( 11 ),
    `name` VARCHAR( 255 ) NULL,
    `email` VARCHAR( 255 ) NULL,
    `date` DATETIME NULL,
    `custom_fields` TEXT,
     PRIMARY KEY( `id` )
 ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
$wpdb->query( $sql );

$manager_table = tve_leads_table_name( 'contact_download' );

$sql = "CREATE TABLE IF NOT EXISTS {$manager_table}(
    `id` INT( 11 ) AUTO_INCREMENT,
    `type` VARCHAR( 255 ),
    `download_link` VARCHAR( 255 ) NULL,
    `date` DATETIME NULL,
    `status` VARCHAR( 64 ) ,
     PRIMARY KEY( `id` )
 ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
$wpdb->query( $sql );
