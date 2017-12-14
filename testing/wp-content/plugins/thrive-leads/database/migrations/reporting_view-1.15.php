<?php


/**
 * adds fields for screen type and id fo the new reporting view where we need to know the source of an impression or conversion
 */

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();


global $wpdb;

$table = tve_leads_table_name( 'event_log' );

$sql = "ALTER TABLE `{$table}` ADD `screen_type` TINYINT NULL ,ADD `screen_id` BIGINT NULL ;";

$wpdb->query( $sql );