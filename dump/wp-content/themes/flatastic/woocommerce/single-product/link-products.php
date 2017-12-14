<?php
/**
 * Link Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php
$next_post = get_next_post();
$prev_post = get_previous_post();
$next_post_url = $prev_post_url = "";
$next_post_title = $prev_post_title = "";

if (is_object($next_post)) {
	$next_post_url = get_permalink($next_post->ID);
	$next_post_title = $next_post->post_title;
}
if (is_object($prev_post)) {
	$prev_post_url = get_permalink($prev_post->ID);
	$prev_post_title = $prev_post->post_title;
}
?>

<?php if (!empty($prev_post_url) || !empty($next_post_url)): ?>

	<div class="product-link-pages">

		<div class="product-nav-left">
			<?php if (!empty($prev_post_url)): ?>
				<a class="product-prev-button" href="<?php echo $prev_post_url ?>" title="">
					<?php _e('Previous Product', MAD_BASE_TEXTDOMAIN) ?>
				</a>
				<span><?php echo $prev_post_title; ?></span>
			<?php endif; ?>
		</div><!--/ .product-nav-left-->

		<div class="product-nav-right">
			<?php if (!empty($next_post_url)): ?>
				<a class="product-next-button" href="<?php echo $next_post_url ?>" title="">
					<?php _e('Next Product', MAD_BASE_TEXTDOMAIN) ?>
				</a>
				<span><?php echo $next_post_title; ?></span>
			<?php endif; ?>
		</div><!--/ .product-nav-right-->

	</div><!--/ .product-link-pages-->

<?php endif; ?>