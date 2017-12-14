<?php

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();

global $wpdb;

$variation_table = tve_leads_table_name( 'form_variations' );

$_sql = "ALTER TABLE `{$variation_table}` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
$wpdb->query( $_sql );

$_sql = "ALTER TABLE `{$variation_table}` CHANGE `post_title` `post_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
$wpdb->query( $_sql );