<?php
/**
 * Main admin class
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

if ( !defined( 'YITH_WOOCOMPARE' ) ) { exit; } // Exit if accessed directly

global $woocommerce;

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.2', '<' ) ) {
    $image_width_type = 'image_width';
}
else {
    $image_width_type = 'yit_wc_image_width';
}

$options = array(
    'general' => array(
        array(
            'name' => __( 'General Settings', MAD_BASE_TEXTDOMAIN ),
            'type' => 'title',
            'desc' => '',
            'id' => 'yith_woocompare_general'
        ),

//        array(
//            'name' => __( 'Link or Button', 'yit' ),
//            'desc' => __( 'Choose if you want to use a link or a button for the action button.', 'yit' ),
//            'id'   => 'yith_woocompare_is_button',
//            'std'  => 'button',
//            'default' => 'button',
//            'type' => 'select',
//            'options' => array(
//                'link' => __( 'Link', 'yit' ),
//                'button' => __( 'Button', 'yit' )
//            )
//        ),

        array(
            'name' => __( 'Link/Button text', MAD_BASE_TEXTDOMAIN ),
            'desc' => __( 'Type the text to use for the button or the link of the compare.', MAD_BASE_TEXTDOMAIN ),
            'id'   => 'yith_woocompare_button_text',
            'std'  => __( 'Compare', MAD_BASE_TEXTDOMAIN ),
            'type' => 'text'
        ),

//        array(
//            'name' => __( 'Show button in single product page', 'yit' ),
//            'desc' => __( 'Say if you want to show the button in the single product page.', 'yit' ),
//            'id'   => 'yith_woocompare_compare_button_in_product_page',
//            'std'  => 'yes',
//            'default' => 'yes',
//            'type' => 'checkbox'
//        ),

//        array(
//            'name' => __( 'Show button in products list', 'yit' ),
//            'desc' => __( 'Say if you want to show the button in the products list.', 'yit' ),
//            'id'   => 'yith_woocompare_compare_button_in_products_list',
//            'std'  => 'no',
//            'default' => 'no',
//            'type' => 'checkbox'
//        ),

        array(
            'name' => __( 'Open automatically lightbox', MAD_BASE_TEXTDOMAIN ),
            'desc' => __( 'Open link after click into "Compare" button".', MAD_BASE_TEXTDOMAIN ),
            'id'   => 'yith_woocompare_auto_open',
            'std'  => 'yes',
            'default' => 'yes',
            'type' => 'checkbox'
        ),

        array( 'type' => 'sectionend', 'id' => 'yith_woocompare_general' )
    ),

    'table-settings' => array(
        array(
            'name' => __( 'Table Settings', MAD_BASE_TEXTDOMAIN ),
            'type' => 'title',
            'desc' => '',
            'id' => 'yith_woocompare_general'
        ),

        array(
            'name' => __( 'Table title', MAD_BASE_TEXTDOMAIN ),
            'desc' => __( 'Type the text to use for the table title.', MAD_BASE_TEXTDOMAIN ),
            'id'   => 'yith_woocompare_table_text',
            'std'  => __( 'Compare Products', MAD_BASE_TEXTDOMAIN ),
            'type' => 'text'
        ),

        array(
            'name' => __( 'Fields to show', MAD_BASE_TEXTDOMAIN ),
            'desc' => __( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', MAD_BASE_TEXTDOMAIN ),
            'id'   => 'yith_woocompare_fields_attrs',
            'std'  => 'all',
            'default' => 'all',
            'type' => 'attributes'
        ),

//        array(
//            'name' => __( 'Repeat "Price" field at the end of the table', 'yit' ),
//            'desc' => __( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', 'yit' ),
//            'id'   => 'yith_woocompare_price_end',
//            'std'  => 'yes',
//            'default' => 'yes',
//            'type' => 'checkbox'
//        ),

//        array(
//            'name' => __( 'Repeat "Add to cart" field at the end of the table', 'yit' ),
//            'desc' => __( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', 'yit' ),
//            'id'   => 'yith_woocompare_add_to_cart_end',
//            'std'  => 'no',
//            'default' => 'no',
//            'type' => 'checkbox'
//        ),

//        array(
//            'name' => __( 'Image size', 'yit' ),
//            'desc' => __( 'Set the size for the images', 'yit' ),
//            'id'   => 'yith_woocompare_image_size',
//            'type' 		=> $image_width_type,
//            'default'	=> array(
//                'width' 	=> 220,
//                'height'	=> 154,
//                'crop'		=> 1
//            ),
//            'std'	=> array(
//                'width' 	=> 220,
//                'height'	=> 154,
//                'crop'		=> 1
//            )
//        ),

        array( 'type' => 'sectionend', 'id' => 'yith_woocompare_general' )
    ),
);
