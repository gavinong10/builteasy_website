<div class="searchform-wrap">
	<div class="container">
		<div class="col-sm-12">
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
		</div>
	</div>
</div><!--/ .searchform-wrap -->