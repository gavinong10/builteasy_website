<?php

global $post;

echo View::render( 'single-agency.twig', array(
	'post'       => $post,
	'properties' => aviators_properties_get_by_agency( get_the_ID() ),
) );