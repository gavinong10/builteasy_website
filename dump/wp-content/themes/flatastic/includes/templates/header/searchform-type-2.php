<?php if (mad_custom_get_option('show_search')): ?>

	<a href="javascript: void(0);" class="search-button"></a>

	<div class="searchform-wrap">
		<div class="search-outer">
			<?php
				if (mad_is_shop_installed()) {
					echo do_shortcode('[yith_woocommerce_ajax_search]');
				} else {
					get_search_form();
				}
			?>
			<button class="close-search-form"></button>
		</div><!--/ .search-outer-->
	</div><!--/ .searchform-wrap -->

<?php endif; ?>
