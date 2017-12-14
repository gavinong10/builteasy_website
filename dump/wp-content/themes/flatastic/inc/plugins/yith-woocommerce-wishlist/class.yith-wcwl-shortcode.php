<?php
/**
 * Shortcodes class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( !defined( 'YITH_WCWL' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCWL_Shortcode' ) ) {
    /**
     * YITH WCWL Shortcodes
     *
     * @since 1.0.0
     */
    class YITH_WCWL_Shortcode {
        /**
         * Print the wishlist HTML.
         * 
         * @since 1.0.0
         */
        public static function wishlist( $atts, $content = null ) {

            $atts = shortcode_atts( array(
                'per_page' => 10,
                'pagination' => 'no' 
            ), $atts );

            ob_start();
            yith_wcwl_get_template( 'wishlist.php', $atts );
            
            return apply_filters( 'yith_wcwl_wishlisth_html', ob_get_clean() );
        }
        
        /**
         * Return "Add to Wishlist" button.
         * 
         * @since 1.0.0
         */
        public static function add_to_wishlist( $atts, $content = null ) {
            global $product, $yith_wcwl;
            
            $html = YITH_WCWL_UI::add_to_wishlist_button( $yith_wcwl->get_wishlist_url(), $product->product_type, $yith_wcwl->is_product_in_wishlist( $product->id ) ); 
            
//            $html .= YITH_WCWL_UI::popup_message();
            
            echo $html;
        }
    }
}

//add_shortcode( 'yith_wcwl_wishlist', array( 'YITH_WCWL_Shortcode', 'wishlist' ) );
//add_shortcode( 'yith_wcwl_add_to_wishlist', array( 'YITH_WCWL_Shortcode', 'add_to_wishlist' ) );