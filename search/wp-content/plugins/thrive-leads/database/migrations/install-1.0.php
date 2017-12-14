<?php
/**
 * Main installer script
 */


defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();
global $wpdb;

$log_table       = tve_leads_table_name( 'event_log' );
$test_table      = tve_leads_table_name( 'split_test' );
$test_item_table = tve_leads_table_name( 'split_test_items' );

$sql = "CREATE TABLE IF NOT EXISTS {$log_table}(
    `id` INT( 11 ) AUTO_INCREMENT,
    `date` DATETIME NULL,
    `event_type` TINYINT( 2 ),
    `main_group_id` INT( 11 ),
    `form_type_id` INT( 11 ) NULL,
    `variation_key` INT( 11 ) NULL,
    `user` VARCHAR( 255 ) NULL,
    `ip` VARCHAR( 40 ) NULL,
    `referrer` VARCHAR( 255 ) NULL,
    `utm_source` VARCHAR( 255 ) NULL,
    `utm_medium` VARCHAR( 255 ) NULL,
    `utm_campaign` VARCHAR( 255 ) NULL,
    `archived` TINYINT( 1 ) NULL DEFAULT '0',
     PRIMARY KEY( `id` )
 )";
$wpdb->query( $sql );

$sql = "CREATE TABLE IF NOT EXISTS {$test_table} (
    `id` INT( 11 ) AUTO_INCREMENT,
    `test_type` INT( 11 ),
    `main_group_id` INT( 11 ),
    `date_added` DATETIME NULL,
    `date_started` DATETIME NULL DEFAULT NULL,
    `date_completed` DATETIME NULL DEFAULT NULL,
    `title` VARCHAR( 128 ) NULL,
    `notes` TINYTEXT NULL,
    `auto_win_enabled` INT( 1 ) NULL DEFAULT '0',
    `auto_win_min_conversions` INT( 11 ) NULL,
    `auto_win_min_duration` INT( 11 ) NULL,
    `auto_win_chance_original` DOUBLE( 11, 2 ) NULL,
    `status` ENUM( 'created', 'running', 'completed', 'archived' ),
     PRIMARY KEY( `id` )
)";
$wpdb->query( $sql );

$sql = "CREATE TABLE IF NOT EXISTS {$test_item_table} (
    `id` INT( 11 ) AUTO_INCREMENT,
    `test_id` INT( 11 ),
    `main_group_id` INT( 11 ),
    `form_type_id` INT( 11 ) NULL,
    `variation_key` INT( 11 ) NULL,
    `is_control` INT( 1 ) NULL DEFAULT '0',
    `is_winner` INT( 1 ) NULL DEFAULT '0',
    `impressions` INT( 11 ) NULL DEFAULT '0',
    `unique_impressions` INT( 11 ) NULL DEFAULT '0',
    `conversions` INT( 11 ) NULL DEFAULT '0',
     PRIMARY KEY( `id` )
 )";
$wpdb->query( $sql );

$saved_group_options_table = tve_leads_table_name( 'saved_group_options' );
$sql                       = "CREATE TABLE IF NOT EXISTS {$saved_group_options_table} (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `show_group_options` LONGTEXT NULL,
    `hide_group_options` LONGTEXT NULL,
    PRIMARY KEY (`id`)
 )";
$wpdb->query( $sql );

$group_options_table = tve_leads_table_name( 'group_options' );
$sql                 = "CREATE TABLE IF NOT EXISTS {$group_options_table} (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `group` INT(11) NOT NULL,
    `description` VARCHAR(255),
    `show_group_options` LONGTEXT NULL,
    `hide_group_options` LONGTEXT NULL,
    PRIMARY KEY (`id`)
)";
$wpdb->query( $sql );

$variations_table = tve_leads_table_name( 'form_variations' );
$sql              = "CREATE TABLE IF NOT EXISTS `{$variations_table}`(
    `key` BIGINT( 20 ) AUTO_INCREMENT,
    `date_added` DATETIME NULL,
    `date_modified` DATETIME NULL,
    `post_parent` BIGINT( 20 ),
    `post_status` VARCHAR( 20 ) NULL DEFAULT 'publish',
    `post_title` TEXT NULL DEFAULT NULL,
    `content` LONGTEXT NULL DEFAULT NULL,
    `trigger` VARCHAR ( 64 ) NULL DEFAULT NULL,
    `trigger_config` TEXT NULL DEFAULT NULL,
    `tcb_fields` LONGTEXT NULL DEFAULT NULL,
    `display_frequency` INT( 11 ) NULL DEFAULT '0',
    `display_animation` VARCHAR( 64 ) NULL DEFAULT 'instant',
    `position` VARCHAR( 32 ) NULL DEFAULT 'bot_right',
     PRIMARY KEY( `key` )
 );";

$wpdb->query( $sql );
