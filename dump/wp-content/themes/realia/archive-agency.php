<?php

global $wp_query;

echo View::render( 'archive-agency.twig', array(
	'wp_query'       => $wp_query,
	'posts'          => $wp_query->posts,
	'posts_per_page' => - 1
) );
