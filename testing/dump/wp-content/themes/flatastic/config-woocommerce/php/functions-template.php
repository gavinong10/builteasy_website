<?php

/* ---------------------------------------------------------------------- */
/*	Template: Woocommerce
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'mad_wc_get_template' ) ) {
	function mad_wc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template( $template_name, $args, $template_path, $default_path );
		} else {
			woocommerce_get_template( $template_name, $args, $template_path, $default_path );
		}
	}
}

if ( ! function_exists( 'mad_woocommerce_product_custom_tab' ) ) {
	function mad_woocommerce_product_custom_tab($key) {
		global $post;

		$mad_title_product_tab = $mad_content_product_tab = '';
		$custom_tabs_array = get_post_meta($post->ID, 'mad_custom_tabs', true);
		$custom_tab = $custom_tabs_array[$key];

		extract($custom_tab);

		if ($mad_title_product_tab != '') {

			preg_match("!\[embed.+?\]|\[video.+?\]!", $mad_content_product_tab, $match_video);
			preg_match("!\[(?:)?gallery.+?\]!", $mad_content_product_tab, $match_gallery);
			$zoom_image = mad_custom_get_option('zoom_image', '');

			if (!empty($match_video)) {

				global $wp_embed;

				$video = $match_video[0];

				$before = "<div class='image-overlay ". esc_attr($zoom_image) ."'>";
					$before .= "<div class='entry-media photoframe'>";
						$before .= $wp_embed->run_shortcode($video);
					$before .= "</div>";
				$before .= "</div>";
				$before = apply_filters('the_content', $before);
				echo $before;

			} elseif (!empty($match_gallery)) {

				$gallery = $match_gallery[0];
				if (strpos($gallery, 'vc_') === false) {
					$gallery = str_replace("gallery", 'mad_gallery image_size="848*370"', $gallery);
				}
				$before = apply_filters('the_content', $gallery);
				echo do_shortcode($before);

			} else {
				echo do_shortcode($mad_content_product_tab);
			}

		}

	}
}

if (!function_exists('mad_woocommerce_show_product_loop_out_of_sale_flash')) {
	function mad_woocommerce_show_product_loop_out_of_sale_flash() {
		mad_wc_get_template( 'loop/out-of-stock-flash.php' );
	}
}

if (!function_exists('mad_woocommerce_shop_link_products')) {
	function mad_woocommerce_shop_link_products() {
		mad_wc_get_template( 'single-product/link-products.php' );
	}
}

if (!function_exists('mad_woocommerce_single_variation_add_to_cart_button')) {
	function mad_woocommerce_single_variation_add_to_cart_button() {
		global $product;
		?>
		<div class="variations_button">

			<table class="description-table">
				<tbody>
				<tr>
					<td><?php _e('Quantity:', MAD_BASE_TEXTDOMAIN); ?></td>
					<td class="product-quantity">
						<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
					</td>
				</tr>
				</tbody>
			</table><!--/ .description-table-->

			<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
			<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
			<input type="hidden" name="variation_id" class="variation_id" value="" />
		</div>
		<?php
	}
}

if (!function_exists('mad_overwrite_catalog_ordering')) {
	function mad_overwrite_catalog_ordering($args) {
		global $mad_config;

		$product_sort = $product_count = '';
		$keys = array('product_count', 'product_sort', 'product_order');
		if (empty($mad_config['woocommerce'])) $mad_config['woocommerce'] = array();

		foreach ($keys as $key) {
			if (isset($_GET[$key]) ) {
				$_SESSION['mad_woocommerce'][$key] = esc_attr($_GET[$key]);
			}
			if (isset($_SESSION['mad_woocommerce'][$key]) ) {
				$mad_config['woocommerce'][$key] = $_SESSION['mad_woocommerce'][$key];
			}
		}

		extract($mad_config['woocommerce']);

		if (!empty($product_count)) {
			$mad_config['shop_overview_product_count'] = (int) $product_count;
		}

		if (!empty($product_sort)) {
			switch ( $product_sort ) {
				case 'desc' : $order = 'desc'; break;
				case 'asc' : $order = 'asc'; break;
				default : $order = 'asc'; break;
			}
		}

		if ( isset($order) ) $args['order'] = $order;

		$mad_config['woocommerce']['product_sort'] = $args['order'];

		return $args;
	}
	add_action( 'woocommerce_get_catalog_ordering_args', 'mad_overwrite_catalog_ordering');
}

if (!function_exists('mad_woocommerce_output_product_data_tabs')) {
	function mad_woocommerce_output_product_data_tabs() {
		echo '<div class="clear"></div>';
		woocommerce_output_product_data_tabs();
	}
}

if (!function_exists('mad_woocommerce_output_related_products')) {
	function mad_woocommerce_output_related_products() {
		global $mad_config;

		$mad_config['shop_single_column'] = ($mad_config['sidebar_position'] != 'no_sidebar') ? 3 : 4; // columns for related products
		$mad_config['shop_single_column_items'] = mad_custom_get_option('shop_single_column_items'); // number of items for related products

		ob_start();

		woocommerce_related_products(
			array(
				'columns' => $mad_config['shop_single_column'],
				'posts_per_page' => $mad_config['shop_single_column_items']
			)
		);

		$content = ob_get_clean(); ?>

		<?php if ($content): ?>

			<div class="products-container view-grid" data-columns="<?php echo esc_attr($mad_config['shop_single_column']) ?>">
				<?php echo $content; ?>
			</div><!--/ .products-container-->

		<?php endif;
	}
}