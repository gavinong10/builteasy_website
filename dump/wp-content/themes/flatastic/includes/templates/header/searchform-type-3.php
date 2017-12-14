<div class="col-lg-6 col-md-6 col-sm-8">

	<div class="row">

		<?php
			$is_show_search = mad_custom_get_option('show_search');
			$col = 'col-lg-6';
			if (!$is_show_search) {
				$col = 'col-lg-12';
			}
		?>

		<div class="<?php echo esc_attr($col) ?> col-md-6 col-sm-6 t_align_r t_xs_align_c m_xs_bottom_15">

			<dl class="l_height_medium">
				<dd class="f_size_small">
					<?php if (mad_custom_get_option('show_call_us')): ?>
						<p><?php echo mad_custom_get_option('call_us', __('Call us toll free: <b>(123) 456-7890</b>', MAD_BASE_TEXTDOMAIN), true); ?></p>
					<?php endif; ?>
				</dd>
			</dl>

		</div>

		<?php if ($is_show_search): ?>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div class="search-outer">
					<?php
						if (mad_is_shop_installed()) {
							echo do_shortcode('[yith_woocommerce_ajax_search]');
						} else {
							get_search_form();
						}
					?>
				</div><!--/ .search-outer-->
			</div>
		<?php endif; ?>

	</div><!--/ .row-->

</div>
