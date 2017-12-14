<?php

defined( 'TVE_LEADS_DB_UPGRADE' ) or exit();
global $wpdb, $tvedb;

$deleted_lead_groups = get_posts( array(
	'posts_per_page' => - 1,
	'post_type'      => TVE_LEADS_POST_GROUP_TYPE,
	'post_status'    => 'trash',
) );

foreach ( $deleted_lead_groups as $lead_group ) {
	$tvedb->delete_tests( array( 'main_group_id' => $lead_group->ID ), array( 'delete_items' => true ) );
	foreach ( tve_leads_get_form_types( array( 'lead_group_id' => $lead_group->ID, 'tracking_data' => false ) ) as $form_type ) {
		if ( is_a( $form_type, 'WP_Post' ) && ! empty( $form_type->ID ) ) {
			$tvedb->delete_tests( array( 'main_group_id' => $form_type->ID ), array( 'delete_items' => true ) );
		}
	}
}