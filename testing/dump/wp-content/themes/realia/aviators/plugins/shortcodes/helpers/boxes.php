<?php
function aviators_shortcodes_content_box( $params, $content = NULL ) {
	$result = View::render( 'shortcodes/content_box.twig', array(
		'content'             => $content,
		'icon'                => ! empty( $params['icon'] ) ? $params['icon'] : FALSE,
		'icon_pictopro_class' => ! empty( $params['icon_pictopro_class'] ) ? $params['icon_pictopro_class'] : FALSE,
		'title'               => ! empty( $params['title'] ) ? $params['title'] : FALSE,
		'columns_for_content' => ! empty( $params['columns_for_content'] ) ? $params['columns_for_content'] : 3,
	) );
	return force_balance_tags( $result );
}

add_shortcode( 'content_box', 'aviators_shortcodes_content_box' );