<?php

function mad_filter_constructor() {

	global $woocommerce;
	if ( ! isset( $woocommerce ) ) { return; }

	define('WOOF_PATH', trailingslashit(dirname(__FILE__)));
	define('WOOF_LINK', trailingslashit(MAD_BASE_URI . 'inc/plugins/woocommerce-products-filter'));
	define('WOOF_PLUGIN_NAME', MAD_BASE_TEXTDOMAIN);

	require_once( 'class.woof.php' );
	require_once( 'widgets/class.woocommerce-filter.php' );

	// Let's start the game!
	global $WOOF;
	$WOOF = new MAD_WOOF();
}

mad_filter_constructor();

