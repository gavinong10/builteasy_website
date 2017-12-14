<?php
	$post_id = mad_post_id();

	$footer_row_top_show = get_post_meta($post_id, 'footer_row_top_show', true);
	$footer_row_bottom_show = get_post_meta($post_id, 'footer_row_bottom_show', true);

	$get_sidebars_top_widgets = get_post_meta($post_id, 'get_sidebars_top_widgets', true);
	$get_sidebars_bottom_widgets = get_post_meta($post_id, 'get_sidebars_bottom_widgets', true);

	$footer_row_top_columns_variations = get_post_meta($post_id, 'footer_row_top_columns_variations', true);
	$footer_row_bottom_columns_variations = get_post_meta($post_id, 'footer_row_bottom_columns_variations', true);

	if (empty($footer_row_top_show)) {
		$footer_row_top_show = (mad_custom_get_option('show_row_top_widgets') != '0') ? 'yes' : 'no';
	}
	if (empty($footer_row_bottom_show)) {
		$footer_row_bottom_show = (mad_custom_get_option('show_row_bottom_widgets') != '0') ? 'yes' : 'no';
	}

	if (empty($footer_row_top_columns_variations)) {
		$footer_row_top_columns_variations = mad_custom_get_option('footer_row_top_columns_variations');
	}

	if (empty($footer_row_bottom_columns_variations)) {
		$footer_row_bottom_columns_variations = mad_custom_get_option('footer_row_bottom_columns_variations');
	}

	if (empty($get_sidebars_top_widgets)) {
		$get_sidebars_top_widgets = array(
			'Footer Row - widget 1',
			'Footer Row - widget 2',
			'Footer Row - widget 3',
			'Footer Row - widget 4',
			'Footer Row - widget 5'
		);
	}

	if (empty($get_sidebars_bottom_widgets)) {
		$get_sidebars_bottom_widgets = array(
			'Footer Row - widget 6',
			'Footer Row - widget 7',
			'Footer Row - widget 8',
			'Footer Row - widget 9',
			'Footer Row - widget 10'
		);
	}

?>

<?php if ($footer_row_top_show == 'yes' || $footer_row_bottom_show == 'yes'): ?>

	<div class="footer_top_part">

		<?php if ($footer_row_top_show == 'yes'): ?>

			<div class="footer-row-top">

				<div class="container">

					<div class="row">

						<?php if (!empty($footer_row_top_columns_variations)):
							$number_of_top_columns = key( json_decode( html_entity_decode ( $footer_row_top_columns_variations ), true));
							$columns_top_array = json_decode( html_entity_decode ( $footer_row_top_columns_variations ), true );
							?>

							<?php for ($i = 1; $i <= $number_of_top_columns; $i++): ?>

								<div class="col-sm-<?php echo esc_attr($columns_top_array[$number_of_top_columns][0][$i-1]); ?>">
									<?php if ( !dynamic_sidebar($get_sidebars_top_widgets[$i-1]) ) : endif; ?>
								</div>

							<?php endfor; ?>

						<?php endif; ?>

					</div><!--/ .row-->

				</div><!--/ .container-->

			</div><!--/ .footer-row-top-->

		<?php endif; ?>

		<?php if ($footer_row_bottom_show == 'yes'): ?>

			<hr />

			<div class="footer-row-bottom">

				<div class="container">

					<div class="row">

						<?php if (!empty($footer_row_bottom_columns_variations)):
							$number_of_bottom_columns = key( json_decode( html_entity_decode ( $footer_row_bottom_columns_variations ), true));
							$columns_bottom_array = json_decode( html_entity_decode ( $footer_row_bottom_columns_variations ), true );
							?>

							<?php for ($i = 1; $i <= $number_of_bottom_columns; $i++): ?>

								<div class="col-sm-<?php echo esc_attr($columns_bottom_array[$number_of_bottom_columns][0][$i-1]); ?>">
									<?php if ( !dynamic_sidebar($get_sidebars_bottom_widgets[$i-1]) ) : endif; ?>
								</div>

							<?php endfor; ?>

						<?php endif; ?>

					</div><!--/ .row-->

				</div><!--/ .container-->

			</div><!--/ .footer-row-bottom-->

		<?php endif; ?>

	</div><!--/ .footer_top_part-->

<?php endif; ?>