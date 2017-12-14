<?php

if (!class_exists('MAD_CATALOG_ORDERING')) {

	class MAD_CATALOG_ORDERING {

		function __construct() { }

		public function woocoomerce_build_query_string ($params = array(), $key, $value) {
			$params[$key] = $value;
			$paged = (array_key_exists('product_count', $params)) ? 'paged=1&' : '';
			return "?" . $paged . http_build_query($params);
		}

		public function output() {

			global $woocommerce_loop, $mad_config;

			ob_start();
			?>

			<div class="shop-page-meta">

				<div class="row">

					<div class="col-sm-9">

						<label class="d_inline_middle f_size_medium"><?php _e('Sort by:', 'flatastic') ?></label>

						<?php
							woocommerce_catalog_ordering();
							parse_str($_SERVER['QUERY_STRING'], $params);

							$product_per_page = mad_custom_get_option('woocommerce_product_count');
							if (!$product_per_page) {
								$product_per_page = get_option('posts_per_page');
							}

							$product_count_key = !empty($mad_config['woocommerce']['product_count']) ? $mad_config['woocommerce']['product_count'] : $product_per_page;
							$product_sort_key = !empty($mad_config['woocommerce']['product_sort']) ? $mad_config['woocommerce']['product_sort'] : 'asc';
							$product_sort_key = strtolower($product_sort_key);
						?>

						<div class="order-param-button">
							<?php if ($product_sort_key == 'desc'): ?>
								<a class="order-param-asc"  href="<?php echo esc_url($this->woocoomerce_build_query_string($params, 'product_sort', 'asc')); ?>"></a>
							<?php endif; ?>

							<?php if ($product_sort_key == 'asc'): ?>
								<a class="order-param-desc" href="<?php echo esc_url($this->woocoomerce_build_query_string($params, 'product_sort', 'desc')); ?>"></a>
							<?php endif; ?>
						</div><!--/ .order-param-button-->

						<div class="param-count">

							<label class="d_inline_middle f_size_medium"><?php _e('Show:', 'flatastic') ?></label>

							<div class="custom-select">
								<div class="select-title"><?php echo esc_html($product_count_key) ?></div>
								<ul class="select-list"></ul>
								<select name="param-count">
									<option data-href="<?php echo esc_url($this->woocoomerce_build_query_string($params, 'product_count', $product_per_page * 3)); ?>" value="<?php echo (int) esc_attr($product_per_page * 3) ?>">
										<?php echo (int) esc_html($product_per_page * 3) ?>
									</option>
									<option data-href="<?php echo esc_url($this->woocoomerce_build_query_string($params, 'product_count', $product_per_page * 2)); ?>" value="<?php echo (int) esc_attr($product_per_page * 2) ?>">
										<?php echo (int) esc_html($product_per_page * 2) ?>
									</option>
									<option data-href="<?php echo esc_url($this->woocoomerce_build_query_string($params, 'product_count', $product_per_page)); ?>" value="<?php echo (int) esc_attr($product_per_page) ?>">
										<?php echo (int) esc_html($product_per_page) ?>
									</option>
								</select>
							</div><!--/ .custom-select-->

							<label class="d_inline_middle f_size_medium"><?php _e('items per page', 'flatastic') ?></label>

						</div><!--/ .param-count-->

					</div>

					<div class="col-sm-3 t_align_r">

						<p class="list-or-grid">
							<?php _e( 'View as:', 'flatastic' ) ?>
							<a data-view="view-grid-center" class="view-grid<?php if ($woocommerce_loop['view'] == 'view-grid-center') echo ' active'; ?>" href="<?php echo add_query_arg( 'view', 'grid' ) ?>" title="<?php _e( 'Switch to grid view', 'flatastic' ) ?>">
								<?php _e( 'Grid', 'flatastic' ) ?>
							</a>
							<a data-view="view-list" class="view-list<?php if ($woocommerce_loop['view'] == 'view-list') echo ' active'; ?>" href="<?php echo add_query_arg( 'view', 'list' ) ?>" title="<?php _e( 'Switch to list view', 'flatastic' ) ?>">
								<?php _e( 'List', 'flatastic' ) ?>
							</a>
						</p><!--/ .list-or-grid-->

					</div>

				</div><!--/ .row-->

			</div><!--/ .shop-page-meta-->

			<?php return ob_get_clean();
		}

	}
}

?>
