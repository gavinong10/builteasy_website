<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
 
global $product;

if ( !$product->is_in_stock() ) {
	$label  = apply_filters( 'out_of_stock_add_to_cart_text', __( 'Out of stock', 'woocommerce' ) ); ?>

	<?php printf( '<span class="out-of-stock"><span>%s</span></span>', $label ); ?>

	<?php
}