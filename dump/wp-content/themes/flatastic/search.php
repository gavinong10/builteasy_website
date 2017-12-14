<?php
	/**
	 * The template for displaying Search Results pages.
	 */
	get_header();

?>

	<?php $mad_results = mad_which_archive(); ?>

	<div class="template-search">

		<?php if (!empty($mad_results)): ?>
			<?php echo mad_title(array('title' => $mad_results)) ?>
		<?php endif; ?>

		<?php if (!empty($_GET['s']) || have_posts()): ?>
			<?php

			if (have_posts()):

				$loop_count = 1;
				$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
				if ($page > 1) {
					$loop_count = ((int) ($page - 1) * (int) get_query_var('posts_per_page')) + 1;
				}

				while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class("post-item clearfix"); ?>>

						<span class="search-result-counter">
							<span class="dropcap-result"><?php echo esc_html($loop_count) ?></span>
						</span>

						<div class="post-content">

							<div class="entry-meta">

								<h4 class="entry-title">
									<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
								</h4>

								<?php echo mad_blog_post_meta(get_the_ID()); ?>

							</div><!--/ .entry-meta-->

							<?php if (mad_custom_get_option('blog-listing-meta-ratings')): ?>

								<?php if (isset($post->rating)): ?>
									<div class="rating-box">
										<span class="rate-desc"><?php _e('Rate this item', MAD_BASE_TEXTDOMAIN); ?></span>
										<div class="rating readonly-rating" data-score="<?php echo esc_attr($post->rating); ?>"></div>
										<span>(<?php printf(_n('%d vote', '%d votes', $post->votes, MAD_BASE_TEXTDOMAIN), $post->votes); ?>)</span>
									</div>
								<?php endif; ?>

							<?php endif; ?>

							<?php
								$excerpt = trim(get_the_excerpt());
								if (!empty($excerpt)) {
									the_excerpt();
								} else {
									$excerpt = strip_shortcodes(str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content())));
									$excerpt = apply_filters('the_excerpt', $excerpt);
									echo $excerpt;
								}
							?>

						</div><!--/ .post-content-->

					</article><!--/ .post-item-->

					<?php $loop_count++; endwhile; ?>

			<?php else: ?>

				<?php get_template_part('content', 'none'); ?>

			<?php endif; ?>

			<?php echo mad_corenavi(); ?>

		<?php endif; ?>

	</div><!--/ .template-search-->

<?php get_footer(); ?>
