<?php

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();

global $wpdb;

$table = tve_leads_table_name( 'form_variations' );

$sql = "ALTER TABLE `{$table}` ADD `form_state` VARCHAR( 64 ) NULL DEFAULT NULL, ADD `parent_id` INT( 11 ) NULL DEFAULT '0', ADD `state_order` INT( 11 ) NULL DEFAULT '0' ";

$wpdb->query( $sql );