<?php

echo View::render( 'content-agency.twig', array(
	'wp_query' => $wp_query,
	'posts'    => $wp_query->posts,
) );