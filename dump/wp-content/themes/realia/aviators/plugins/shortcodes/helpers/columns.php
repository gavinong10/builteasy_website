<?php
// Row
function aviators_shortcodes_row( $params, $content = null ) {
	$result = '<div class="row">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'row', 'aviators_shortcodes_row' );

// Col 3/12
function aviators_shortcodes_row_span3( $params, $content = null ) {
	$result = '<div class="span3">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'span3', 'aviators_shortcodes_row_span3' );

// Col 4/12
function aviators_shortcodes_row_span4( $params, $content = null ) {
	$result = '<div class="span4">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'span4', 'aviators_shortcodes_row_span4' );

// Col 6/12
function aviators_shortcodes_row_span6( $params, $content = null ) {
	$result = '<div class="span6">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'span6', 'aviators_shortcodes_row_span6' );

// Col 8/12
function aviators_shortcodes_row_span8( $params, $content = null ) {
	$result = '<div class="span8">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'span8', 'aviators_shortcodes_row_span8' );

// Col 9/12
function aviators_shortcodes_row_span9( $params, $content = null ) {
	$result = '<div class="span9">' . do_shortcode( $content ) . '</div>';
	return force_balance_tags( $result );
}

add_shortcode( 'span9', 'aviators_shortcodes_row_span9' );