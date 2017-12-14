
<!-- - - - - - - - - - - - - Breadcrumbs - - - - - - - - - - - - - -->

<?php if (is_page()): ?>

	<?php if (mad_custom_get_option('page_breadcrumbs')): ?>

		<?php
			$mad_post_id = mad_post_id();
			$mad_breadcrumb = rwmb_meta('mad_breadcrumb', '', $mad_post_id);
		?>

		<?php if ($mad_breadcrumb == 'breadcrumb'): ?>

			<div class="breadcrumbs">
				<div class="container">
					<div class="mad-breadcrumbs">
						<?php echo mad_breadcrumbs(array(
							'separator' => '<i class="fa fa-angle-right"></i>'
						)); ?>
					</div>
				</div><!--/ .container-->
			</div><!--/ .breadcrumbs-->

		<?php endif; ?>

	<?php endif; ?>

<?php elseif (is_post_type_archive('product') || mad_is_product_category() || mad_is_product_tag() || is_singular('product')): ?>

	<?php if (mad_custom_get_option('shop_breadcrumbs')): ?>

		<?php if (is_post_type_archive('product') || mad_is_product_category() || mad_is_product_tag()): ?>

			<div class="breadcrumbs">
				<div class="container">
					<div class="mad-breadcrumbs">
						<?php woocommerce_breadcrumb(array(
							'delimiter' => '<i class="fa fa-angle-right"></i>'
						)); ?>
					</div>
				</div><!--/ .container-->
			</div><!--/ .breadcrumbs-->

		<?php elseif (is_singular('product')): ?>

			<?php
				$mad_post_id = mad_post_id();
				$mad_breadcrumb = rwmb_meta('mad_breadcrumb', '', $mad_post_id);
			?>

			<?php if ($mad_breadcrumb == 'breadcrumb'): ?>

				<div class="breadcrumbs">
					<div class="container">
						<div class="mad-breadcrumbs">
							<?php woocommerce_breadcrumb(array(
								'delimiter' => '<i class="fa fa-angle-right"></i>'
							)); ?>
						</div>
					</div><!--/ .container-->
				</div><!--/ .breadcrumbs-->

			<?php endif; ?>

		<?php endif; ?>

	<?php endif; ?>

<?php else: ?>

	<?php if (mad_custom_get_option('single_breadcrumbs')): ?>

		<?php $mad_breadcrumb = rwmb_meta('mad_breadcrumb'); ?>

		<?php if ($mad_breadcrumb == 'breadcrumb'): ?>

			<div class="breadcrumbs">
				<div class="container">
					<div class="mad-breadcrumbs">
						<?php echo mad_breadcrumbs(array(
							'separator' => '<i class="fa fa-angle-right"></i>'
						)); ?>
					</div>
				</div><!--/ .container-->
			</div><!--/ .breadcrumbs-->

		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>

<!-- - - - - - - - - - - - - / Breadcrumbs - - - - - - - - - - - - -->