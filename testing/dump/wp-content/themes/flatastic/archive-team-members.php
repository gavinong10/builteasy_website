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

			<div class="team-members">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$id = get_the_ID();
						$name = get_the_title();
						$link  = get_permalink();
						$position = rwmb_meta('mad_tm_position', '', $id);

						$facebook = rwmb_meta('mad_tm_facebook', '', $id);
						$twitter = rwmb_meta('mad_tm_twitter', '', $id);
						$gplus = rwmb_meta('mad_tm_gplus', '', $id);
						$pinterest = rwmb_meta('mad_tm_pinterest', '', $id);
						$instagram = rwmb_meta('mad_tm_instagram', '', $id);

						$not_empty_social = $facebook . $twitter . $gplus . $pinterest . $instagram;
						$content = apply_filters('the_content', get_the_content());
						$thumbnail_atts = array(
							'title'	=> trim(strip_tags($name)),
							'alt'	=> trim(get_the_excerpt()),
							'title'	=> trim(get_the_title())
						);
					?>

					<div class="team-item">

						<?php if (has_post_thumbnail()): ?>

							<div class="team-photo">
								<?php echo MAD_HELPER::get_the_post_thumbnail($id, '200*200', $thumbnail_atts); ?>
							</div><!--/ .team-photo-->

							<div class="members-text-holder">

								<h4 class="entry-title">
									<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($name); ?></a>
								</h4>
								<span class="team-member-position"><?php echo esc_html($position); ?></span>

								<p><?php echo do_shortcode($content); ?></p>

							</div><!--/ .members-text-holder-->

							<?php if ($not_empty_social != ''): ?>

								<ul class="social-links">

									<?php if (!empty($facebook)): ?>
										<li class="facebook">
											<a target="_blank" href="<?php echo esc_url($facebook) ?>"></a>
										</li>
									<?php endif; ?>

									<?php if (!empty($twitter)): ?>
										<li class="twitter">
											<a target="_blank" href="<?php echo esc_url($twitter) ?>"></a>
										</li>
									<?php endif; ?>

									<?php if (!empty($gplus)): ?>
										<li class="gplus">
											<a target="_blank" href="<?php echo esc_url($gplus) ?>"></a>
										</li>
									<?php endif; ?>

									<?php if (!empty($pinterest)): ?>
										<li class="pinterest">
											<a target="_blank" href="<?php echo esc_url($pinterest) ?>"></a>
										</li>
									<?php endif; ?>

									<?php if (!empty($instagram)): ?>
										<li class="instagram">
											<a target="_blank" href="<?php echo esc_url($instagram) ?>"></a>
										</li>
									<?php endif; ?>

								</ul><!--/ .social-links-->

							<?php endif; ?>

						<?php endif; ?>

					</div><!--/ .team-item-->

				<?php endwhile; // end of the loop. ?>

				<div class="clear"></div>

			</div><!--/ .team-members-->

			<?php echo mad_corenavi(); ?>

		</div><!--/ .template-area-->

	<?php endif; ?>

<?php get_footer(); ?>