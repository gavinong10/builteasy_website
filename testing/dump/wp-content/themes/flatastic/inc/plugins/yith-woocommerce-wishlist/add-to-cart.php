<?php
/**
 * Add product from the wishlist to the cart.
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

// Handles all ajax requests pertaining to this plugin
require_once( 'safe-wp-load.php' );
require_once( 'functions.yith-wcwl.php' );

global $woocommerce, $yith_wcwl;

//determine error link redirect url
$error_link_url = $yith_wcwl->get_wishlist_url();


//determine to success link redirect url
//handle redirect option chosen by admin
if ( isset( $_GET['redirect_to_cart'] ) && $_GET['redirect_to_cart'] == 'true' ) {

    if ( function_exists( 'icl_object_id' ) ) {
        $redirect_url = get_permalink( icl_object_id( function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'cart' ) : woocommerce_get_page_id( 'cart' ) ), 'page', true );
    }
    else {
        $redirect_url = get_permalink( function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'cart' ) : woocommerce_get_page_id( 'cart' ) );
    }
}
else {
    $redirect_url = apply_filters( 'yit_wcwl_add_to_cart_redirect_url', $yith_wcwl->get_wishlist_url() );
}

//get the details of the product
$details = $yith_wcwl->get_product_details( $_GET['wishlist_item_id'] );

//add to the cart
if( WC()->cart->add_to_cart( $details[0]['prod_id'], 1 ) ) {
	//$_SESSION['messages'] 	= sprintf( '<a href="%s" class="button">%s</a> %s', get_permalink( woocommerce_get_page_id( 'cart' ) ), __( 'View Cart &rarr;', 'yit' ), __( 'Product successfully added to the cart.', 'yit' ) );

    if ( function_exists('wc_add_to_cart_message') ) {
        wc_add_to_cart_message( $details[0]['prod_id'] );
    } else {
        woocommerce_add_to_cart_message( $details[0]['prod_id'] );
    }
    //$woocommerce->set_messages();

    if( get_option( 'yith_wcwl_remove_after_add_to_cart' ) == 'yes' )
        { $yith_wcwl->remove( $details[0]['ID'] ); }

	header( "Location: $redirect_url" );

} else { //if failed, redirect to wishlist page with errors
    if ( function_exists('wc_get_notices') ) {
        $_SESSION['errors'] = wc_get_notices( "error" );
    } else {
        $_SESSION['errors'] = $woocommerce->get_errors();
    }
	header( "Location: $error_link_url" );
}