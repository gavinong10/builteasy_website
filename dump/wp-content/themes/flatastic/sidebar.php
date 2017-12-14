<aside id="sidebar" class="col-sm-4 col-md-3">
	<?php
		// reset all previous queries
		wp_reset_query();

		$mad_post_id = mad_post_id();
		$mad_custom_sidebar = '';

		if (is_post_type_archive('product') || mad_is_product_category() || mad_is_product_tag()) {
			$mad_woo_shop_page_id = get_option('woocommerce_shop_page_id');
			if ($mad_woo_shop_page_id) {
				$mad_custom_sidebar = rwmb_meta('mad_page_sidebar', '', $mad_woo_shop_page_id);
			}
		}

		if (is_singular() && !empty($mad_post_id)) {
			$mad_custom_sidebar = rwmb_meta('mad_page_sidebar', '', $mad_post_id);
		}

		if (mad_is_shop_installed()) {
			if (is_product()) {
				if (empty($mad_custom_sidebar)) {
					$mad_custom_sidebar = mad_custom_get_option('sidebar_setting_product');
				}
			}
		}

		if (!empty($mad_custom_sidebar)) {
			dynamic_sidebar($mad_custom_sidebar);
		} else {
			if (is_active_sidebar('general-widget-area')) {
				dynamic_sidebar('General Widget Area');
			} else {
			 ?>
				<div class="widget widget_archive">
					<div class="widget-head">
						<h3 class="widget-title"><?php _e('Archives', MAD_BASE_TEXTDOMAIN); ?></h3>
					</div>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</div><!--/ .widget -->

				<div class="widget widget_meta">
					<div class="widget-head">
						<h3 class="widget-title"><?php _e('Meta', MAD_BASE_TEXTDOMAIN); ?></h3>
					</div>
					<ul>
						<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div><!--/ .widget -->
			<?php
			}
		}
	?>

</aside>


