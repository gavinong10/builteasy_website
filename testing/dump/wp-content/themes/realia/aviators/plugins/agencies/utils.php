<?php

function aviators_agencies_get($count = -1) {
	$query = new WP_Query( array(
		'post_type'      => 'agency',
		'posts_per_page' => $count,
	) );

	return $query->posts;
}

function aviators_agencies_get_assigned( $property, $count = 3 ) {
	$agencies = get_post_meta( $property->ID, '_property_agencies', TRUE );

	if ( ! is_array( $agencies ) ) {
		return array();
	}

	$query = new WP_Query( array(
		'post__in'       => array_values( $agencies ),
		'post_type'      => 'agency',
		'posts_per_page' => $count,
	) );
	return _aviators_agencies_prepare( $query );
}

function aviators_agencies_get_properties_count( $id ) {
	return count( aviators_properties_get_by_agency( $id ) );
}

function _aviators_agencies_prepare( $query ) {
	$results = array();

	foreach ( $query->posts as $agency ) {
		$agency->meta = get_post_meta( $agency->ID, '', true );
		$results[]    = $agency;
	}
	return $results;
}