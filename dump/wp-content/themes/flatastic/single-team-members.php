<?php
/**
 * The template for displaying single team members.
 *
 * @package WordPress
 * @subpackage Flatastic
 * @since Flatastic 1.0
 */

get_header(); ?>

<?php if ( have_posts() ): ?>

	<div class="template-area">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
			global $post;
			$this_post = array();
			$this_post['post_id'] = get_the_ID();
			$this_post['content'] = apply_filters('the_content', get_the_content());
			$this_post['image_size'] = '90*90';
			$this_post = apply_filters('entry-format-template', $this_post);
			extract($this_post); ?>

			<div class="section-line">

				<div class="meta-holder">

					<h2 class="section-title"><?php the_title() ?></h2>
					<?php echo mad_blog_post_meta(); ?>

				</div><!--/ .meta-holder-->

				<div class="link-pages-holder">

					<?php
					$mad_next_post = get_next_post();
					$mad_prev_post = get_previous_post();
					$mad_next_post_url = $mad_prev_post_url = "";
					is_object($mad_next_post) ? $mad_next_post_url = get_permalink($mad_next_post->ID) : "";
					is_object($mad_prev_post) ? $mad_prev_post_url = get_permalink($mad_prev_post->ID) : "";
					?>

					<ul class="projects-nav clearfix">
						<?php if (!empty($mad_prev_post_url)): ?>
							<li>
								<a class="prev" href="<?php echo esc_url($mad_prev_post_url) ?>" title="<?php _e('Previous post', MAD_BASE_TEXTDOMAIN) ?>">
									<?php _e('Previous Post', MAD_BASE_TEXTDOMAIN) ?>
								</a>
							</li>
						<?php endif; ?>
						<li>
							<a class="all-projects" href="<?php echo get_post_type_archive_link('team-members') ?>"></a>
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

			<div class="template-box">

				<div class="template-image-format">

					<?php echo (!empty($before_content)) ? $before_content : ""; ?>

				</div><!--/ .template-image-format-->

				<div class="template-description">

					<?php echo $content; ?>

					<div class="m_bottom_30">

						<span class="d_inline_middle"><?php _e('Share this:', MAD_BASE_TEXTDOMAIN) ?></span>
						<div class="d_inline_middle m_left_5 addthis_widget_container">

							<!-- AddThis Button BEGIN -->
							<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
								<a class="addthis_button_preferred_1"></a>
								<a class="addthis_button_preferred_2"></a>
								<a class="addthis_button_preferred_3"></a>
								<a class="addthis_button_preferred_4"></a>
								<a class="addthis_button_compact"></a>
								<a class="addthis_counter addthis_bubble_style"></a>
							</div>
							<!-- AddThis Button END -->

							<?php MAD_BASE_FUNCTIONS::enqueue_script('addthis'); ?>

						</div>

					</div>

				</div><!--/ .template-description-->

			</div><!--/ .template-box-->

			<?php get_template_part('loop/single', 'link-pages'); ?>

		<?php endwhile ?>

	</div><!--/ .template-area-->

<?php endif; ?>

<?php get_footer(); ?>