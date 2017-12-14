<?php

function noo_customizer_css_generator() {

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/layout.php' );

	$css_layout = ob_get_contents(); ob_end_clean();

	// Remove comment, space
	$css_layout = preg_replace( '#/\*.*?\*/#s', '', $css_layout );
	$css_layout = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_layout );
	$css_layout = preg_replace( '/\s\s+(.*)/', '$1', $css_layout );

	echo '<style id="noo-customizer-css-layout" type="text/css">' . $css_layout . '</style>';

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/design.php' );

	$css_design = ob_get_contents(); ob_end_clean();

	// Remove comment, space
	$css_design = preg_replace( '#/\*.*?\*/#s', '', $css_design );
	$css_design = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_design );
	$css_design = preg_replace( '/\s\s+(.*)/', '$1', $css_design );

	echo '<style id="noo-customizer-css-design" type="text/css">' . $css_design . '</style>';

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/typography.php' );

	$css_typography = ob_get_contents(); ob_end_clean();

	// Remove comment, space
	$css_typography = preg_replace( '#/\*.*?\*/#s', '', $css_typography );
	$css_typography = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_typography );
	$css_typography = preg_replace( '/\s\s+(.*)/', '$1', $css_typography );

	echo '<style id="noo-customizer-css-typography" type="text/css">' . $css_typography . '</style>';

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/header.php' );

	$css_header = ob_get_contents(); ob_end_clean();

	// Remove comment, space
	$css_header = preg_replace( '#/\*.*?\*/#s', '', $css_header );
	$css_header = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_header );
	$css_header = preg_replace( '/\s\s+(.*)/', '$1', $css_header );

	echo '<style id="noo-customizer-css-header" type="text/css">' . $css_header . '</style>';

	global $wp_customize;
	if ( isset( $wp_customize ) ) {

		// with customizer, we will need an extra <style> tag for temporary css
		echo '<style id="noo-customizer-live-css" type="text/css"></style>';
	}
}

global $wp_customize;
if ( isset( $wp_customize ) || noo_get_option('noo_use_inline_css', false) ) {
	add_action( 'wp_head', 'noo_customizer_css_generator', 100, 0 );
}
