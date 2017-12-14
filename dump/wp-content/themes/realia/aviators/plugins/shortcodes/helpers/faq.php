<?php

function aviators_shortcodes_faq( $params, $content = NULL ) {
	$attributes = array(
		'post_type'      => 'faq',
		'posts_per_page' => '-1',
	);

	if ( ! empty( $params['category'] ) ) {
		$attributes['tax_query'] = array(
			array(
				'taxonomy' => 'faq_categories',
				'field'    => 'id',
				'terms'    => $params['category'],
				'operator' => 'IN',
			),
		);
	}
	$questions = new WP_Query( $attributes );

	$result = View::render( 'shortcodes/faq.twig', array(
		'questions' => $questions->posts,
		'category'  => ! empty( $params['category'] ) ? $params['category'] : NULL,
	) );

	return force_balance_tags( $result );
}

add_shortcode( 'faq', 'aviators_shortcodes_faq' );