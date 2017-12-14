<?php
/**
 * Loop Rating
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if (get_option('woocommerce_enable_review_rating') == 'no')
	return;

	$num_rating = (int) $product->get_average_rating();

	?>
<div class="rating-box">
	<div class="rating readonly-rating" data-score="<?php echo $num_rating; ?>"></div>
	<?php
		$count = $product->get_rating_count();
		if (is_array($count)) {
			$count = array_shift($count);
		}
	?>
	<span><a class="to-rating" href="#reviews"><?php echo $count; ?> <?php _e( 'Review(s)', MAD_BASE_TEXTDOMAIN ) ?></a></span>
</div>
<div class="clear"></div>
