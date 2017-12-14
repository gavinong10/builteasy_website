<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Compare
 * @version 1.1.4
 */

if ( !defined( 'YITH_WOOCOMPARE' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Woocompare_Helper' ) ) {
    /**
     * YITH Woocommerce Compare helper
     *
     * @since 1.0.0
     */
    class YITH_Woocompare_Helper {

        /**
         * Set the image size used in the comparison table
         *
         * @since 1.0.0
         */
        public static function set_image_size() {
            $size = get_option( 'yith_woocompare_image_size' );
            $size['crop'] = isset( $size['crop'] ) ? true : false;
            add_image_size( 'yith-woocompare-image', $size['width'], $size['height'], $size['crop'] );
        }

        /*
         * The list of standard fields
         *
         * @since 1.0.0
         * @access public
         */
        public static function standard_fields() {
            return array(
                'image' => __('Image', MAD_BASE_TEXTDOMAIN),
                'title' => __('Title', MAD_BASE_TEXTDOMAIN),
                'price' => __('Price', MAD_BASE_TEXTDOMAIN),
                'description' => __('Description', MAD_BASE_TEXTDOMAIN),
                'stock' => __( 'Availability', MAD_BASE_TEXTDOMAIN ),
                'sku' => __( 'SKU', MAD_BASE_TEXTDOMAIN ),
                'attributes' => __( 'Attributes', MAD_BASE_TEXTDOMAIN ),
				'actions' => __('Action', MAD_BASE_TEXTDOMAIN)
            );
        }

        /*
         * Get Woocommerce Attribute Taxonomies
         *
         * @since 1.0.0
         * @access public
         */
        public static function attribute_taxonomies() {
            global $woocommerce;

            if ( ! isset( $woocommerce ) ) return array();

            $attributes = array();

            if( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if( empty( $attribute_taxonomies ) ) return array();
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    if ( taxonomy_exists( $tax ) ) {
                        $attributes[$tax] = ucfirst( $attribute->attribute_name );
                    }
                }
            }else{
                $attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
                if( empty( $attribute_taxonomies ) ) return array();
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = $woocommerce->attribute_taxonomy_name( $attribute->attribute_name );
                    if ( taxonomy_exists( $tax ) ) {
                        $attributes[$tax] = ucfirst( $attribute->attribute_name );
                    }
                }
            }


            return $attributes;
        }


    }
}