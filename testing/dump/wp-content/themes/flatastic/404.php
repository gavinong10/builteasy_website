<?php get_header(); ?>

	<div class="template-404">

		<div class="row">
			<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

				<!-- - - - - - - - - - - - error 404 - - - - - - - - - - - - - - -->

				<div class="error-404-text type-3">

					<?php echo html_entity_decode(mad_custom_get_option('440_content'), ENT_COMPAT, get_bloginfo('charset')); ?>

					<p>
						<a href="<?php echo MAD_HOME_URL; ?>" class="wpb_button wpb_btn-medium wpb_btn-grey">
							<?php _e('Go to Homepage', MAD_BASE_TEXTDOMAIN); ?>
						</a>
					</p>

					<div class="search-outer">
						<?php
						if (isset($_GET['post_type']) && $_GET['post_type'] == 'product' && function_exists('get_product_search_form')) {
							get_product_search_form();
						} else {
							get_search_form();
						}
						?>
					</div><!--/ .search-outer-->

				</div><!--/ .error-404-text-->

				<!-- - - - - - - - - - - / error 404  - - - - - - - - - - - - - -->

			</div>
		</div><!--/ .row-->

	</div><!--/ .template-404-->

<?php get_footer(); ?>