<?php

define( 'WP_USE_THEMES', false );

require_once '../../../../../../wp-load.php';

$properties = aviators_properties_filter( FALSE, FALSE );

wp_send_json( array(
	'count'     => count( $properties ),
	'contents'  => View::render( 'properties/helpers/contents.twig', array( 'properties' => $properties ) ),
	'locations' => View::render( 'properties/helpers/locations.twig', array( 'properties' => $properties ) ),
	'types'     => View::render( 'properties/helpers/types.twig', array( 'properties' => $properties ) ),
) );