<?php
/**
 * Shortcodes class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( !defined( 'YITH_WCWL' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCWL_UI' ) ) {
class YITH_WCWL_UI {

    /**
     * Build the popup message HTML/jQuery.
     *
     * @return string
     * @static
     * @since 1.0.0
     */
public static function popup_message() {
    ob_start() ?>
    <script type="text/javascript">
        if( !jQuery( '#yith-wcwl-popup-message' ).length ) {
            jQuery( 'body' ).prepend(
                '<div id="yith-wcwl-popup-message" style="display:none;">' +
                    '<div id="yith-wcwl-message"></div>' +
                    '</div>'
            );
        }
    </script>
    <?php
    return ob_get_clean();
}

    /**
     * Build the "Add to Wishlist" HTML
     *
     * @param string $url
     * @param string $product_type
     * @param bool $exists
     * @return string
     * @static
     * @since 1.0.0
     */
    public static function add_to_wishlist_button( $url, $product_type, $exists ) {
        global $yith_wcwl, $product;

        $label_option = get_option( 'yith_wcwl_add_to_wishlist_text' );
        $localize_label = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_button', $label_option ) : $label_option;

        $label = apply_filters( 'yith_wcwl_button_label', $localize_label );
        $icon = get_option( 'yith_wcwl_add_to_wishlist_icon' ) != 'none' ? '<i class="' . get_option( 'yith_wcwl_add_to_wishlist_icon' ) . '"></i>' : '';

        $classes = get_option( 'yith_wcwl_use_button' ) == 'yes' ? 'class="add_to_wishlist single_add_to_wishlist button alt"' : 'class="add_to_wishlist"';

        $html  = '<div class="yith-wcwl-add-to-wishlist">';
        $html .= '<div class="yith-wcwl-add-button';

        $html .= $exists ? ' hide" ' : ' show"';

        $html .= '><a href="' . esc_url( $yith_wcwl->get_addtowishlist_url() ) . '" data-product-id="' . $product->id . '" data-product-type="' . $product_type . '" ' . $classes . ' ><span class="feedback">' . __( $label_option, MAD_BASE_TEXTDOMAIN ) . '</span>' . $icon . $label . '</a>';
//        $html .= '<img src="' . esc_url( admin_url( 'images/wpspin_light.gif' ) ) . '" class="ajax-loading" id="add-items-ajax-loading" alt="" width="16" height="16" style="visibility:hidden" />';
        $html .= '</div>';

        $html .= '<div class="yith-wcwl-wishlistaddedbrowse hide"><a href="' . esc_url( $url ) . '"><span class="feedback">' . __( 'Product added!',MAD_BASE_TEXTDOMAIN ) . '</span>' . apply_filters( 'yith-wcwl-browse-wishlist-label', __( 'Browse Wishlist', MAD_BASE_TEXTDOMAIN ) ) . '</a></div>';
        $html .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '"><a href="' . esc_url( $url ) . '"><span class="feedback">' . __( 'The product is already in the wishlist!', MAD_BASE_TEXTDOMAIN ) . '</span>' . apply_filters( 'yith-wcwl-browse-wishlist-label', __( 'Browse Wishlist', MAD_BASE_TEXTDOMAIN ) ) . '</a></div>';
        $html .= '<div class="yith-wcwl-wishlistaddresponse"></div>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Build the "Add to cart" HTML.
     *
     * @param string $url
     * @param string $stock_status
     * @param string $type
     * @return string
     * @static
     * @since 1.0.0
     */
    public static function add_to_cart_button( $product_id, $stock_status ) {
        global $yith_wcwl;

        if ( function_exists( 'get_product' ) )
            $product = get_product( $product_id );
        else
            $product = new WC_Product( $product_id );

        $url = $product->product_type == 'external' ? $yith_wcwl->get_affiliate_product_url( $product_id ) : $yith_wcwl->get_addtocart_url( $product_id );

        $label_option = get_option( 'yith_wcwl_add_to_cart_text' );
        $localize_label = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_button', $label_option ) : $label_option;

        $label = $product->product_type == 'variable' ? apply_filters( 'variable_add_to_cart_text', __('Select options', MAD_BASE_TEXTDOMAIN) ) : apply_filters( 'yith_wcwl_add_to_cart_label', $localize_label );
        $icon = get_option( 'yith_wcwl_use_button' ) == 'yes' && get_option( 'yith_wcwl_add_to_cart_icon' ) != 'none' ? '<i class="' . get_option( 'yith_wcwl_add_to_cart_icon' ) . '"></i>' : '';

        $cartlink = '';
        $redirect_to_cart = get_option( 'yith_wcwl_redirect_cart' ) == 'yes' && $product->product_type != 'variable' ? 'true' : 'false';
        $style = ''; //indicates the style (background-color and font color)

        if( get_option( 'yith_wcwl_use_button' ) == 'yes' ) {
            if( $product->product_type == 'external' ) {
                $cartlink .= '<a target="_blank" class="add_to_cart_button form-button button alt" href="' . $url . '"';
            } else {
                $js_action = esc_attr( 'check_for_stock(\'' . $url . '\',\'' . $stock_status . '\',\'' . $redirect_to_cart . '\');' );
                $cartlink .= '<a class="add_to_cart_button form-button button alt" onclick="' . $js_action . '"';
            }

            $cartlink .= $style . '>' . $icon . $label . '</a>';
        } else {
            if( $product->product_type == 'external' ) {
                $cartlink .= '<a target="_blank" class="add_to_cart_button form-button alt" href="' . $url . '">' . $icon . $label . '</a>';
            } else {
                $js_action = esc_attr( 'check_for_stock(\'' . $url . '\',\'' . $stock_status . '\',\'' . $redirect_to_cart . '\');' );
                $cartlink .= '<a class="add_to_cart_button form-button alt" href="javascript:void(0);" onclick="' . $js_action . '">' . $icon . $label . '</a>';
            }
        }

        return $cartlink;
    }

    /**
     * Build share HTML.
     *
     * @param string $url
     * @return $string
     * @static
     * @since 1.0.0
     */
//    public static function get_share_links( $url ) {
//        $normal_url = $url;
//        $url = urlencode( $url );
//        $title = apply_filters( 'plugin_text', urlencode( get_option( 'yith_wcwl_socials_title' ) ) );
//        $twitter_summary = str_replace( '%wishlist_url%', '', get_option( 'yith_wcwl_socials_text' ) );
//        $summary = urlencode( str_replace( '%wishlist_url%', $normal_url, get_option( 'yith_wcwl_socials_text' ) ) );
//        $imageurl = urlencode( get_option( 'yith_wcwl_socials_image_url' ) );
//
//        $html  = '<div class="yith-wcwl-share">';
//        $html .= apply_filters( 'yith_wcwl_socials_share_title', '<h4>' . __( 'Share on:', MAD_BASE_TEXTDOMAIN ) . '</h4>' );
//        $html .= '<ul>';
//
//        if( get_option( 'yith_wcwl_share_fb' ) == 'yes' )
//        { $html .= '<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="facebook" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $url . '&amp;p[summary]=' . $summary . '&amp;p[images][0]=' . $imageurl . '" title="' . __( 'Facebook', MAD_BASE_TEXTDOMAIN ) . '"></a></li>'; }
//
//        if( get_option( 'yith_wcwl_share_twitter' ) == 'yes' )
//        { $html .= '<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="twitter" href="https://twitter.com/share?url=' . $url . '&amp;text=' . $twitter_summary . '" title="' . __( 'Twitter', MAD_BASE_TEXTDOMAIN ) . '"></a></li>'; }
//
//        if( get_option( 'yith_wcwl_share_pinterest' ) == 'yes' )
//        { $html .= '<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="pinterest" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . $summary . '&media=' . $imageurl . '" onclick="window.open(this.href); return false;"></a></li>'; }
//
//        if( get_option( 'yith_wcwl_share_googleplus' ) == 'yes' )
//        { $html .= '<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="googleplus" href="https://plus.google.com/share?url=' . $url . '&amp;title="' . $title . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'></a></li>'; }
//
//        if( get_option( 'yith_wcwl_share_email' ) == 'yes' )
//        { $html .= '<li style="list-style-type: none; display: inline-block;"><a class="email" href="mailto:?subject=I wanted you to see this site&amp;body= ' . $url . '&amp;title="' . __('email', MAD_BASE_TEXTDOMAIN) . '" ></a></li>'; }
//
//        $html .= '</ul>';
//        $html .= '</div>';
//
//        return $html;
//    }
	}
}