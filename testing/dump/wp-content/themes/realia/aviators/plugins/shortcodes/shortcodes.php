<?php


require_once get_template_directory() . '/aviators/plugins/shortcodes/helpers/columns.php';
require_once get_template_directory() . '/aviators/plugins/shortcodes/helpers/boxes.php';
require_once get_template_directory() . '/aviators/plugins/shortcodes/helpers/faq.php';
require_once get_template_directory() . '/aviators/plugins/shortcodes/helpers/pricing.php';


function aviators_buttons() {
	add_filter( 'mce_external_plugins', 'aviators_add_buttons' );
	add_filter( 'mce_buttons_3', 'aviators_register_buttons' );
}

add_action( 'init', 'aviators_buttons' );


function aviators_add_buttons( $plugin_array ) {
	$plugin_array['aviators'] = get_template_directory_uri() . '/aviators/plugins/shortcodes/shortcodes.js';
	return $plugin_array;
}


function aviators_register_buttons( $buttons ) {
	array_push( $buttons, 'row' );
	array_push( $buttons, 'span3' );
	array_push( $buttons, 'span4' );
	array_push( $buttons, 'span6' );
	array_push( $buttons, 'span8' );
	array_push( $buttons, 'span9' );
	array_push( $buttons, 'content_box' );
	array_push( $buttons, 'faq' );
	array_push( $buttons, 'pricing' );

	return $buttons;
}