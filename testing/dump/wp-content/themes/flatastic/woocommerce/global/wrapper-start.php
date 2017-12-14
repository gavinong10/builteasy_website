<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	global $woocommerce_loop, $mad_config;

	$woocommerce_loop['columns'] = $mad_config['shop_overview_column_count'];

	$view = $woocommerce_loop['view'] = isset( $_COOKIE[ 'mad_shop_view' ] ) ? $_COOKIE[ 'mad_shop_view' ] : mad_custom_get_option('shop-view');

	if (empty($view)) {
		$view = 'view-grid-center';
	}

	if (is_singular('product')) {
		$view = '';
	}

?>

<div class="products-container shop-columns-<?php echo $woocommerce_loop['columns'] ?> <?php echo $view ?>">