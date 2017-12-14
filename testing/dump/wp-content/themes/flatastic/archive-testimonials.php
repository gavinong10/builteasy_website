<?php
/**
 * The template for displaying Testimonials Archive area.
 */

get_header(); ?>

	<?php if (have_posts()): ?>

		<?php $mad_results = mad_which_archive(); ?>

		<div class="template-area">

			<?php if (!empty($mad_results)): ?>
				<?php echo mad_title(array('title' => $mad_results)) ?>
			<?php endif; ?>

			<?php
				$mad_columns = mad_custom_get_option('testimonials_archive_column_count');
				switch ($mad_columns) {
					case 2: $mad_grid = 'two_columns';  break;
					case 3: $mad_grid = 'three_columns';  break;
					case 4: $mad_grid = 'four_columns';   break;
					default: $mad_grid = 'three_columns'; break;
				}
			?>

			<div class="testimonials-area <?php echo esc_attr($mad_grid); ?>">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$id = get_the_ID();
						$link  = get_permalink();
						$thumbnail_atts = array(
							'class'	=> "tr_all_long_hover",
							'alt'	=> trim(get_the_excerpt()),
							'title'	=> trim(get_the_title()),
						);
						$name = get_the_title();
						$place = rwmb_meta('mad_tm_place', '', $id);
						$content = apply_filters('the_content', get_the_content());
					?>

					<div class="tm-item">

						<blockquote class="tm-blockquote">
							<?php echo do_shortcode($content); ?>
						</blockquote>

						<?php if (has_post_thumbnail()): ?>
							<div class="tm-photo">
								<?php echo MAD_HELPER::get_the_post_thumbnail($id, '70*70', $thumbnail_atts); ?>
							</div>
						<?php endif; ?>

						<div class="tm-text-holder">
							<h5 class="entry-title"><a href="<?php echo esc_url($link) ?>"><?php echo esc_html($name); ?></a></h5>
							<span class="tm-place"><?php echo esc_html($place); ?></span>
						</div><!--/ .tm-text-holder-->

					</div><!--/ .tm-item-->

				<?php endwhile; // end of the loop. ?>

			</div><!--/ .testimonials-area-->

			<?php echo mad_corenavi(); ?>

		</div><!--/ .template-area-->

	<?php endif; ?>

<?php get_footer(); ?>