<?php

function aviators_partners_get($count = -1) {
	$query = new WP_Query( array(
		'post_type'      => 'partner',
		'posts_per_page' => $count,
	) );

	return $query->posts;
}