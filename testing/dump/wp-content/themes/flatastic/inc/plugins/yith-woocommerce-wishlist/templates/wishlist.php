<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

global $wpdb, $yith_wcwl, $woocommerce;

if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) ) {
    $user_id = $_GET['user_id'];
} elseif( is_user_logged_in() ) {
    $user_id = get_current_user_id();
}

$current_page = 1;
$limit_sql = '';

//if ( $pagination == 'yes' ) {
//    $count = array();
//
//    if( is_user_logged_in() || ( isset( $user_id ) && !empty( $user_id ) ) ) {
//        $count = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) as `cnt` FROM `' . YITH_WCWL_TABLE . '` WHERE `user_id` = %d', $user_id  ), ARRAY_A );
//        $count = $count[0]['cnt'];
//    } elseif( yith_usecookies() ) {
//        $count[0]['cnt'] = count( yith_getcookie( 'yith_wcwl_products' ) );
//    } else {
//        $count[0]['cnt'] = count( $_SESSION['yith_wcwl_products'] );
//    }
//
//    $total_pages = $count/$per_page;
//
//    if( $total_pages > 1 ) {
//        $current_page = max( 1, get_query_var( 'page' ) );
//
//        $page_links = paginate_links( array(
//            'base' => get_pagenum_link( 1 ) . '%_%',
//            'format' => '&page=%#%',
//            'current' => $current_page,
//            'total' => $total_pages,
//            'show_all' => true
//        ) );
//    }
//
//    $limit_sql = "LIMIT " . ( $current_page - 1 ) * 1 . ',' . $per_page;
//}

if( is_user_logged_in() || ( isset( $user_id ) && !empty( $user_id ) ) )
{ $wishlist = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . YITH_WCWL_TABLE . "` WHERE `user_id` = %s" . $limit_sql, $user_id ), ARRAY_A ); }
elseif( yith_usecookies() )
{ $wishlist = yith_getcookie( 'yith_wcwl_products' ); }
else
{ $wishlist = isset( $_SESSION['yith_wcwl_products'] ) ? $_SESSION['yith_wcwl_products'] : array(); }

// Start wishlist page printing
if (function_exists('wc_print_notices')) {
	wc_print_notices();
} else {
	$woocommerce->show_messages();
}
?>

<div id="yith-wcwl-messages"></div>


<form id="yith-wcwl-form" action="<?php echo esc_url( $yith_wcwl->get_wishlist_url() ) ?>" method="post">
    <?php
    do_action( 'yith_wcwl_before_wishlist_title' );

    $wishlist_title = get_option( 'yith_wcwl_wishlist_title' );
    $wishlist_title = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_title_text', $wishlist_title ) : $wishlist_title;

    if( !empty( $wishlist_title ) )
    { echo apply_filters( 'yith_wcwl_wishlist_title', '<h2 class="product-title">' . $wishlist_title . '</h2>' ); }

    do_action( 'yith_wcwl_before_wishlist' );
    ?>
    <table class="shop_table cart wishlist_table" cellspacing="0">

		<thead>
			<tr>
				<th><?php _e( 'Product Image', MAD_BASE_TEXTDOMAIN ); ?></th>
				<th><?php _e( 'Product Name & Category', MAD_BASE_TEXTDOMAIN ); ?></th>
				<th><?php _e( 'Price', MAD_BASE_TEXTDOMAIN ); ?></th>
				<th><?php _e( 'Quantity', MAD_BASE_TEXTDOMAIN ); ?></th>
				<th><?php _e( 'Action', MAD_BASE_TEXTDOMAIN ); ?></th>
			</tr>
        </thead>

        <tbody>

        <?php

        if ( count( $wishlist ) > 0 ) :
            foreach( $wishlist as $values ) :

                if( !is_user_logged_in() && !isset( $_GET['user_id'] ) ) {
                    if( isset( $values['add-to-wishlist'] ) && is_numeric( $values['add-to-wishlist'] ) ) {
                        $values['prod_id'] = $values['add-to-wishlist'];
                        $values['ID'] = $values['add-to-wishlist'];
                    } else {
                        $values['prod_id'] = $values['product_id'];
                        $values['ID'] = $values['product_id'];
                    }
                }

                $product_obj = wc_get_product( $values['prod_id'] );

				if ( $product_obj !== false && $product_obj->exists() ) : ?>

                    <tr id="yith-wcwl-row-<?php echo $values['ID'] ?>">

						<td class="product-thumbnail" data-title="<?php _e( 'Product Image', MAD_BASE_TEXTDOMAIN ); ?>">
							<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $values['prod_id'] ) ) ) ?>">
								<?php echo $product_obj->get_image(array(110, 110)) ?>
							</a>
						</td>

						<td class="product-name" data-title="<?php _e( 'Product Name & Category', MAD_BASE_TEXTDOMAIN ); ?>">
							<h4>
								<a class="product-name" href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $values['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product_obj->get_title(), $product_obj ) ?></a>
							</h4>
							<span class="product-terms"><?php echo $product_obj->get_categories(); ?></span>
						</td>

						<td class="product-subtotal" data-title="<?php _e( 'Price', MAD_BASE_TEXTDOMAIN ); ?>">
							<?php
								if( $product_obj->price != '0' ) {
									$wc_price = function_exists('wc_price') ? 'wc_price' : 'woocommerce_price';

									if( get_option( 'woocommerce_tax_display_cart' ) == 'excl' )
										{ echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product_obj->get_price_excluding_tax() ), $values, '' ); }
									else
										{ echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product_obj->get_price() ), $values, '' ); }
								} else {
									echo apply_filters( 'yith_free_text', __( 'Free!', MAD_BASE_TEXTDOMAIN ) );
								}
							?>
						</td>

						<td class="product-quantity" data-title="<?php _e( 'Quantity', MAD_BASE_TEXTDOMAIN ); ?>">
							<?php
								if (!$product_obj->is_sold_individually()) {
									woocommerce_quantity_input(array(
										'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product_obj),
										'max_value' => apply_filters('woocommerce_quantity_input_max', $product_obj->backorders_allowed() ? '' : $product_obj->get_stock_quantity(), $product_obj)
									));
								}
							?>
						</td>

						<td class="product-action" data-title="<?php _e( 'Action', MAD_BASE_TEXTDOMAIN ); ?>">
							<?php
								$availability = $product_obj->get_availability();
								$stock_status = $availability['class'];
							?>

							<?php if(isset($stock_status) && $stock_status != 'Out'): ?>
								<?php echo YITH_WCWL_UI::add_to_cart_button( $values['prod_id'], isset($availability['class']) ); ?>
							<?php endif ?>

							<div class="clear"></div>

							<?php $remove_wishlist = esc_attr( "remove_item_from_wishlist( '" . esc_url( $yith_wcwl->get_remove_url( $values['ID'] ) ) . "', 'yith-wcwl-row-" . $values['ID'] ."');" ); ?>

							<a href="javascript:void(0)" onclick="<?php echo $remove_wishlist ?>" class="remove " title="<?php _e( 'Remove', MAD_BASE_TEXTDOMAIN ) ?>"><?php _e( 'Remove', MAD_BASE_TEXTDOMAIN ) ?></a>
						</td>

                    </tr>

                <?php

                endif;
            endforeach;
        else: ?>

            <tr>
                <td colspan="6" class="wishlist-empty"><?php _e( 'No products were added to the wishlist', MAD_BASE_TEXTDOMAIN ) ?></td>
            </tr>

        <?php
        endif;

        if ( isset( $page_links ) ) : ?>
            <tr>
                <td colspan="6"><?php echo $page_links ?></td>
            </tr>
        <?php endif ?>

        </tbody>

    </table>

    <?php do_action( 'yith_wcwl_after_wishlist' ); ?>

</form>