<?php
/**
 * Single Product Sale Flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale"><span>' . __( 'Sale', 'woocommerce' ) . '</span></span>', $post, $product ); ?>

<?php endif; ?>

<?php if ( $product->is_featured() ) : ?>

	<?php echo apply_filters( 'woocommerce_featured_flash', '<span class="onfeatured"><span>' . __( 'Featured', 'woocommerce' ) . '</span></span>', $post, $product ); ?>

<?php endif; ?>