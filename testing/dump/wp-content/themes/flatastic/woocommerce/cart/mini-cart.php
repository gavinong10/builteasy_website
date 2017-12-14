<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array(60, 60)), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					?>
					<li>

						<?php if ( ! $_product->is_visible() ) { ?>
							<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
						<?php } else { ?>
							<a href="<?php echo get_permalink( $product_id ); ?>">
								<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
							</a>
						<?php } ?>

						<div class="product-text">
							<div class="product-name"><?php echo $product_name ?></div>
							<span class="sku"><?php _e("Product SKU ", 'woocommerce') ?><?php echo ($sku = $_product->get_sku()) ? $sku : __('N/A', MAD_BASE_TEXTDOMAIN); ?></span>

							<?php echo WC()->cart->get_item_data( $cart_item ); ?>
							<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
						</div>

						<span class="cart-quantity">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove-item" title="%s">%s</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ), __( 'remove', 'woocommerce'  ), $cart_item_key ));
							?>
						</span>

					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

	<ul class="total">
		<li>
			<strong><?php _e( 'Tax', 'woocommerce') ?>:</strong><?php wc_cart_totals_taxes_total_html() ?>
		</li>
		<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
			<li class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
				<?php wc_cart_totals_coupon_label( $coupon ); ?>
				<?php wc_cart_totals_coupon_html( $coupon ); ?>
			</li>
		<?php endforeach; ?>
		<li>
			<strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong><?php echo WC()->cart->get_cart_subtotal(); ?>
		</li>
	</ul><!--/ .total-->

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<footer class="buttons">
		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button view-cart wc-forward"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
	</footer>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
