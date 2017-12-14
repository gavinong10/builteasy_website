<?php

/**
 * Format price
 */
function aviators_price_format( $number ) {
	$decimals      = (int) aviators_settings_get_value( 'money', 'format', 'decimals' );
	$dec_point     = aviators_settings_get_value( 'money', 'format', 'dec_point' );
	$thousands_sep = aviators_settings_get_value( 'money', 'format', 'thousands_sep' );

	$symbol = aviators_settings_get_value( 'money', 'currency', 'sign' );
	$before = (bool) aviators_settings_get_value( 'money', 'currency', 'before' );
	$number = number_format( (float) $number, $decimals, $dec_point, $thousands_sep );

	if ( $before ) {
		return sprintf( '%s %s', $symbol, $number );
	}
	else {
		return sprintf( '%s %s', $number, $symbol );
	}
}