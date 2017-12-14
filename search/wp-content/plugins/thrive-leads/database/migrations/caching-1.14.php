<?php


/**
 * adds fields for caching the impression and conversion counts for a form variation
 *
 */

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();


global $wpdb;

$table = tve_leads_table_name( 'form_variations' );

$sql = "ALTER TABLE `{$table}` ADD `cache_impressions` BIGINT( 20 ) NULL DEFAULT NULL, ADD `cache_conversions` BIGINT( 20 ) NULL DEFAULT NULL";

$wpdb->query( $sql );