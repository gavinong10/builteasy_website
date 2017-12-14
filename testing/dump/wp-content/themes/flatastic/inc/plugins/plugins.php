<?php

$mad_include_plugins = array(
	'yith-woocommerce-ajax-search',
	'woocommerce-products-filter',
	'mega_main_menu',
	'post-ratings'
);

if (mad_custom_get_option('show_wishlist')) {
	$mad_include_plugins['yith-woocommerce-wishlist'] = 'yith-woocommerce-wishlist';
}

if (mad_custom_get_option('show_compare')) {
	$mad_include_plugins['yith-woocommerce-compare'] = 'yith-woocommerce-compare';
}

foreach ($mad_include_plugins as $inc) {
	include_once MAD_INC_PLUGINS_PATH . trailingslashit($inc) . 'init' . '.php';
}