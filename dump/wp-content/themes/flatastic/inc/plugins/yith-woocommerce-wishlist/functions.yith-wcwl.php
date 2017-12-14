<?php
/**
 * Install file
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( !defined( 'YITH_WCWL' ) ) { exit; } // Exit if accessed directly

if( !function_exists( 'yith_wcwl_locate_template' ) ) {
    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $path
     * @param array $var
     * @return void
     * @since 1.0.0
     */
    function yith_wcwl_locate_template( $path, $var = NULL ){
    	$template_woocommerce_path = '/woocommerce/' . $path;
        $template_path = '/' . $path;
    	
    	$located = locate_template( array(
            $template_woocommerce_path, // Search in <theme>/woocommerce/
            $template_path              // Search in <theme>/
        ) );

        if( !$located )
            { $located = YITH_WCWL_DIR . 'templates/' . $path; }
                               
        return $located;
    }
}

if( !function_exists( 'yith_wcwl_get_template' ) ) {
    /**
     * Retrieve a template file.
     * 
     * @param string $path
     * @param mixed $var
     * @param bool $return
     * @return void
     * @since 1.0.0
     */
    function yith_wcwl_get_template( $path, $var = null, $return = false ) {
        $located = yith_wcwl_locate_template( $path, $var );      
        
        if ( $var && is_array( $var ) ) 
    		extract( $var );
                               
        if( $return )
            { ob_start(); }   
                                                                     
        // include file located
        include( $located );
        
        if( $return )
            { return ob_get_clean(); }
    }
}

if( !function_exists( 'yith_wcwl_count_products' ) ) {
    /**
     * Retrieve the number of products in the wishlist.
     * 
     * @return int
     * @since 1.0.0
     */
    function yith_wcwl_count_products() {
        global $yith_wcwl;
        return $yith_wcwl->count_products();
    }
}

if( !function_exists( 'yith_frontend_css_color_picker' ) ) {
    /**
     * Output a colour picker input box.
     * 
     * This function is not of the plugin YITH WCWL. It is from WooCommerce.
     * We redeclare it only because it is needed in the tab "Styles" where it is not available.
     * The original function name is woocommerce_frontend_css_colorpicker and it is declared in
     * wp-content/plugins/woocommerce/admin/settings/settings-frontend-styles.php
     *
     * @access public
     * @param mixed $name
     * @param mixed $id
     * @param mixed $value
     * @param string $desc (default: '')
     * @return void
     */
    function yith_frontend_css_color_picker( $name, $id, $value, $desc = '' ) {
    	global $woocommerce;
    
    	echo '<div class="color_box"><strong>' . $name . '</strong>
       		<input name="' . esc_attr( $id ). '" id="' . $id . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
        </div>';
    
    }
}

if( !function_exists( 'yith_setcookie' ) ) {
    /**
     * Create a cookie.
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     * @since 1.0.0
     */
    function yith_setcookie( $name, $value = array(), $time = null ) {
        $time = $time != null ? $time : time() + 60 * 60 * 24 * 30;
        
        $value = maybe_serialize( stripslashes_deep( $value ) );
        $expiration = apply_filters( 'yith_wcwl_cookie_expiration_time', $time ); // Default 30 days
        
        return setcookie( $name, $value, $expiration, '/' );
    }
}

if( !function_exists( 'yith_getcookie' ) ) {
    /**
     * Retrieve the value of a cookie.
     * 
     * @param string $name
     * @return mixed
     * @since 1.0.0
     */
    function yith_getcookie( $name ) {
        if( isset( $_COOKIE[$name] ) )
            { return maybe_unserialize( stripslashes( $_COOKIE[$name] ) ); }
        
        return array();
    }
}

if( !function_exists( 'yith_usecookies' ) ) {
    /**
     * Check if the user want to use cookies or not.
     * 
     * @return bool
     * @since 1.0.0
     */
    function yith_usecookies() {
        return get_option( 'yith_wcwl_use_cookie' ) == 'yes' ? true : false;
    }
}

if( !function_exists ( 'yith_destroycookie' ) ) {
    /**
     * Destroy a cookie.
     * 
     * @param string $name
     * @return void
     * @since 1.0.0
     */
    function yith_destroycookie( $name ) {
        yith_setcookie( $name, array(), time() - 3600 );
    }
}