<?php

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();

/**
 * AJAX auto-loading of forms should be enabled by default
 */
add_option( 'tve_leads_ajax_load', 0 );

//remove default value for position field
global $wpdb;
$variations = tve_leads_table_name( 'form_variations' );
$posts      = $wpdb->posts;
$wpdb->query( "ALTER TABLE `{$variations}` CHANGE `position` `position` VARCHAR( 32 ) " );
//update ribbons to have display top as position
$wpdb->query( "UPDATE `{$variations}` JOIN `{$posts}` ON `{$variations}`.`post_parent`=`{$posts}`.ID SET `{$variations}`.`position`='top' WHERE `{$posts}`.post_title='Ribbon'" );
