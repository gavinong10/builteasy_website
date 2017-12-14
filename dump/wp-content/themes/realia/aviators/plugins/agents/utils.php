<?php

function aviators_agents_get( $count = 3 ) {
	$query = new WP_Query( array(
		'post_type'      => 'agent',
		'posts_per_page' => $count,
	) );

	return _aviators_agents_prepare( $query );
}

function aviators_agents_get_assigned( $property, $count = 3 ) {
	$agents = get_post_meta( $property->ID, '_property_agents', TRUE );
	if ( ! is_array( $agents ) ) {
		return array();
	}

	$query = new WP_Query( array(
		'post__in'       => array_values( $agents ),
		'post_type'      => 'agent',
		'posts_per_page' => $count,
	) );
	return _aviators_agents_prepare( $query );
}

function _aviators_agents_prepare( $query ) {
	$results = array();

	foreach ( $query->posts as $agent ) {
		$agent->meta = get_post_meta( $agent->ID, '', true );
		$results[]   = $agent;
	}
	return $results;
}