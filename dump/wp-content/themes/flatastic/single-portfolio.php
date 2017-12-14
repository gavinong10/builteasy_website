<?php get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="container single-folio">
		<div class="row">
			<div class="col-sm-12">

				<div class="section-line">

					<h2 class="section-title"><?php the_title() ?></h2>

					<?php
					$mad_next_post = get_next_post();
					$mad_prev_post = get_previous_post();
					$mad_next_post_url = $mad_prev_post_url = "";
					is_object($mad_next_post) ? $mad_next_post_url = get_permalink($mad_next_post->ID) : "";
					is_object($mad_prev_post) ? $mad_prev_post_url = get_permalink($mad_prev_post->ID) : "";
					?>

					<div class="link-pages-holder">

						<ul class="projects-nav clearfix">
							<?php if (!empty($mad_prev_post_url)): ?>
								<li>
									<a class="prev" href="<?php echo esc_url($mad_prev_post_url) ?>" title="<?php _e('Previous post', MAD_BASE_TEXTDOMAIN) ?>">
										<?php _e('Previous Post', MAD_BASE_TEXTDOMAIN) ?>
									</a>
								</li>
							<?php endif; ?>
							<li>
								<a class="all-projects" href="<?php echo get_post_type_archive_link('portfolio') ?>"></a>
							</li>
							<?php if (!empty($mad_next_post_url)): ?>
								<li>
									<a class="next" href="<?php echo esc_url($mad_next_post_url) ?>" title="<?php _e('Next post', MAD_BASE_TEXTDOMAIN) ?>">
										<?php _e('Next Post', MAD_BASE_TEXTDOMAIN) ?>
									</a>
								</li>
							<?php endif; ?>
						</ul><!--/ .project-nav-->

					</div><!--/ .link-pages-holder-->

				</div><!--/ .section-line-->

			</div>
		</div><!--/ .row-->
	</div><!--/ .container-->

	<?php the_content(); ?>

	<?php wp_link_pages(array('before' => '' . __('Pages:', MAD_BASE_TEXTDOMAIN), 'after' => '')); ?>

	<?php endwhile; ?>

<?php endif;

get_footer(); ?>