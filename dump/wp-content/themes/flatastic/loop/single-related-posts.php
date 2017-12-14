<?php
$this_id = $post->ID;
$tag_ids = array();
$tags = wp_get_post_terms($this_id, 'post_tag');

if (!empty($tags) && is_array($tags)) {

	$query = array(
		'post_type' => 'post',
		'numberposts' => mad_custom_get_option('related_posts_count'),
		'ignore_sticky_posts'=> 1,
		'post__not_in' => array($this_id)
	);

	foreach ($tags as $tag) {
		$tag_ids[] = (int) $tag->term_id;
	}

	if (!empty($tag_ids)) {
		$query['tag__in'] = $tag_ids;

		$entries = get_posts($query);
		switch (mad_custom_get_option('related_posts_columns')) {
			case '2': $columns = 'col-sm-6'; break;
			case '3': $columns = 'col-sm-4'; break;
			default:  $columns = 'col-sm-6'; break;
		}
		?>

		<?php if (!empty($entries)): ?>

			<div class="related-posts">

				<h2 class="m_bottom_20">
					<?php _e('Related Articles', MAD_BASE_TEXTDOMAIN); ?>
				</h2>

				<div class="row">

					<?php foreach($entries as $post): setup_postdata($post); ?>

						<?php
							$this_post = array();
							$this_post['post_id'] = $id = get_the_ID();
							$this_post['post_format'] = $format = get_post_format() ? get_post_format() : 'standard';
							$this_post['image_size'] = mad_blog_alias($format, array('450*285', array(450, 285)));
							$this_post['content'] = apply_filters('the_content', get_the_content());

							$this_post = apply_filters('entry-format-'. $format, $this_post);
							$post_class = "post-entry-{$id} entry-format-{$format} related-item {$columns}";
							extract($this_post);
						?>

						<article <?php post_class($post_class); ?> id="post-<?php the_ID(); ?>">

							<figure class="d_xs_inline_b d_mxs_block">
								<div class="m_bottom_15">
									<?php echo $before_content; ?>
								</div>
								<figcaption>
									<h4>
										<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
											<b><?php the_title(); ?></b>
										</a>
									</h4>
									<?php echo mad_blog_post_meta($this_id); ?>
								</figcaption>
							</figure>

						</article>

					<?php endforeach; ?>

				</div><!--/ .row-->

				<?php wp_reset_postdata(); ?>

			</div><!--/ .related-posts-->

		<?php endif; ?>

	<?php
	}
}