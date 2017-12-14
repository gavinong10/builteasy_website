<?php
/**
 * AJAX auto-loading of forms should be enabled by default
 */

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();

$test_item_table = tve_leads_table_name( 'split_test_items' );

global $wpdb;
$wpdb->query( "ALTER TABLE $test_item_table ADD `active` TINYINT(2) NOT NULL DEFAULT '1' AFTER `conversions`, ADD `stopped_date` DATETIME NULL DEFAULT NULL AFTER `active`" );