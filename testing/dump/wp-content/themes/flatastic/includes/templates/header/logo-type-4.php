<div class="col-lg-8 col-md-9 col-sm-8">

	<div class="search-wrap">

		<?php
			$logo_type = mad_custom_get_option('logo_type');
			$logo_hover = mad_custom_get_option('logo_hover');
		?>

		<?php
		switch ($logo_type) {
			case 'text':
				$logo_text = mad_custom_get_option('logo_text');

				if (empty($logo_text)) { $logo_text = get_bloginfo('name'); }

				if (!empty($logo_text)): ?>

					<h1 id="logo" class="logo <?php if ($logo_hover) { echo 'ministorm'; } ?>">
						<a title="<?php bloginfo('description'); ?>" href="<?php echo esc_url(home_url()); ?>">
							<?php echo html_entity_decode($logo_text, ENT_COMPAT, get_bloginfo('charset')); ?>
						</a>
					</h1>

				<?php endif;

				break;
			case 'upload':

				$logo_image = mad_custom_get_option('logo_image');

				if (!empty($logo_image)) { ?>

					<a id="logo" class="logo <?php if ($logo_hover) { echo 'ministorm'; } ?>" title="<?php bloginfo('description'); ?>" href="<?php echo esc_url(home_url()); ?>">
						<img src="<?php echo esc_attr($logo_image); ?>" alt="<?php bloginfo('description'); ?>" />
					</a>

				<?php }

				break;
		}
		?>

		<!-- - - - - - - - - - - - Searchform - - - - - - - - - - - - - - -->

		<?php mad_searchform_type_2_4(); ?>

		<!-- - - - - - - - - - - / End Searchform - - - - - - - - - - - - -->

	</div><!--/ .search-wrap-->

	<?php echo MAD_TEMPLATES_HOOKS::header_cart_dropdown_type_3() ?>

</div>