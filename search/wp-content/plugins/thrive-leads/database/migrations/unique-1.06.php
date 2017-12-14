<?php
defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();
global $wpdb;
$table = tve_leads_table_name( 'event_log' );
$wpdb->query( "ALTER TABLE `{$table}` ADD  `is_unique` TINYINT(1) DEFAULT  '0';" );