<div class="col-lg-4 col-md-3 col-sm-4">

	<ul class="cart-list clearfix">

		<?php if (mad_custom_get_option('show_language')): ?>
			<?php if (defined('ICL_LANGUAGE_CODE')): ?>
				<li class="container3d">
					<?php echo MAD_WC_WPML_CONFIG::wpml_header_languages_list(); ?>
				</li>
			<?php endif; ?>
		<?php endif; ?>

		<?php
		$currency = '';
		if (function_exists( 'get_woocommerce_currency' )) {
			$currency = get_woocommerce_currency();
		}
		?>
		<?php if (mad_custom_get_option('show_currency')): ?>
			<?php if ($currency != ''): ?>
				<li class="container3d">
					<?php echo MAD_WC_CURRENCY_SWITCHER::output_switcher_html();  ?>
				</li>
			<?php endif; ?>
		<?php endif; ?>

	</ul><!--/ .cart-list-->

	<?php if (mad_custom_get_option('show_woo_links')): ?>

		<?php if (mad_is_shop_installed()): ?>

			<ul class="users-nav">
				<li>
					<a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>">
						<?php _e('Checkout Page', MAD_BASE_TEXTDOMAIN); ?>
					</a>
				</li>

				<?php if (mad_custom_get_option('show_wishlist')): ?>

					<li>
						<a href="<?php echo get_permalink(get_option('yith_wcwl_wishlist_page_id')); ?>">
							<?php _e('Wishlist', MAD_BASE_TEXTDOMAIN); ?>
						</a>
					</li>

				<?php endif; ?>

				<li>
					<a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>">
						<?php _e('Cart', MAD_BASE_TEXTDOMAIN); ?>
					</a>
				</li>

			</ul><!--/ .users-nav-->

		<?php endif; ?>

	<?php endif; ?>

</div>