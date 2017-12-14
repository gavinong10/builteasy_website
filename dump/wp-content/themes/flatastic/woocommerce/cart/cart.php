<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">

	<thead>
		<tr>
			<th><?php _e( 'Product Image & Name', MAD_BASE_TEXTDOMAIN ); ?></th>
			<th><?php _e( 'SKU', 'woocommerce' ); ?></th>
			<th><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-thumbnail" data-title="<?php _e( 'Product Image & Name', MAD_BASE_TEXTDOMAIN ); ?>">
						<?php

						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array('110', '110')), $cart_item, $cart_item_key );

						if ( ! $_product->is_visible() ) {
							echo '<div class="thumbnail">';
								echo $thumbnail;
							echo '</div>';
						} else {
							echo '<div class="thumbnail">';
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
							echo '</div>';
						}
						?>

						<?php
						if ( ! $_product->is_visible() ) {
							echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
						} else {
							echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="product-name" href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );
						}

						// Meta data
						echo WC()->cart->get_item_data( $cart_item );

						// Backorder notification
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
							echo '<p class="backorder_notification">' . __( 'Available on backorder', MAD_BASE_TEXTDOMAIN ) . '</p>';
						?>

					</td>

					<td class="product-sku" data-title="<?php _e( 'SKU', 'woocommerce' ); ?>">
						<?php echo ($sku = $_product->get_sku()) ? $sku : __('N/A', MAD_BASE_TEXTDOMAIN); ?>
					</td>

					<td class="product-price" data-title="<?php _e( 'Price', 'woocommerce' ); ?>">
						<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
					</td>

					<td class="product-quantity" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">

						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'  => "cart[{$cart_item_key}][qty]",
								'input_value' => $cart_item['quantity'],
								'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
								'min_value'   => '0'
							), $_product, false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>

						<div class="clear"></div>

						<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">%s</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove', MAD_BASE_TEXTDOMAIN ), __( 'Remove', MAD_BASE_TEXTDOMAIN ) ), $cart_item_key ); ?>

					</td>

					<td class="product-subtotal" data-title="<?php _e( 'Subtotal', 'woocommerce' ); ?>">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
					</td>

				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );

		?>
		<tr>
			<td colspan="5" class="actions">

				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
				<input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	<?php if ( is_cart() ) : ?>
		<?php woocommerce_shipping_calculator(); ?>
	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
