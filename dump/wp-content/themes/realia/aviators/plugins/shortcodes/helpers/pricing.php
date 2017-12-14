<?php

function aviators_shortcodes_pricing( $params, $content = NULL ) {
	if ( ! empty( $params['post'] ) ) {
		$post = get_post( $params['post'] );
	}
	$result = View::render( 'shortcodes/pricing.twig', array(
		'post'        => $post,
		'promoted'    => ! empty( $params['promoted'] ) ? $params['promoted'] : FALSE,
		'title'       => ! empty( $params['title'] ) ? $params['title'] : NULL,
		'subtitle'    => ! empty( $params['subtitle'] ) ? $params['subtitle'] : NULL,
		'price'       => ! empty( $params['price'] ) ? $params['price'] : NULL,
		'link'        => ! empty( $params['link'] ) ? $params['link'] : FALSE,
		'button_text' => ! empty( $params['button_text'] ) ? $params['button_text'] : FALSE,
	) );

	return force_balance_tags( $result );
}

add_shortcode( 'pricing', 'aviators_shortcodes_pricing' );