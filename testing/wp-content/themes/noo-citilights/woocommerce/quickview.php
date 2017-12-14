<?php
global $woocommerce,$product,$post;
?>
<div class="modal fade product-quickview">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-body">
    			<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
					<div class="row">
						<?php do_action( 'woocommerce_before_single_product_summary' );?>
						<div class="col-md-6">
							<div class="summary entry-summary">
							<?php
								/**
								 * woocommerce_single_product_summary hook
								 *
								 * @hooked woocommerce_template_single_title - 5
								 * @hooked woocommerce_template_single_rating - 10
								 * @hooked woocommerce_template_single_price - 10
								 * @hooked woocommerce_template_single_excerpt - 20
								 * @hooked woocommerce_template_single_add_to_cart - 30
								 * @hooked woocommerce_template_single_meta - 40
								 * @hooked woocommerce_template_single_sharing - 50
								 */
								do_action( 'woocommerce_single_product_summary' );
							?>
							</div><!-- .summary -->
						</div>
					</div>
					<meta itemprop="url" content="<?php the_permalink(); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>
