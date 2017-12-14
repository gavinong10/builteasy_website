<?php

global $post;
$this_post = array();
$this_post['post_id'] = $this_id = get_the_ID();
$this_post['post_format'] = $format = get_post_format() ? get_post_format() : 'standard';
$this_post['content'] = get_the_content();
$this_post['image_size'] = mad_blog_alias($format);
$this_post['link'] = get_permalink($this_id);
$this_post = apply_filters('entry-format-single', $this_post);
extract($this_post);

?>
<article <?php post_class('post-entry'); ?>>
	<div class="row clearfix m_bottom_15">

		<div class="col-sm-8">

			<?php if (is_sticky($this_id)): ?>
				<?php printf( '<span class="sticky-post">%s</span>', __( 'Featured', MAD_BASE_TEXTDOMAIN ) ); ?>
			<?php endif; ?>

			<div class="entry-meta">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<?php echo mad_blog_post_meta($this_id); ?>
			</div><!--/ .entry-meta-->

		</div>

		<div class="col-sm-4 t_align_r">

			<?php if (mad_custom_get_option('blog-single-meta-ratings')): ?>

				<?php if (PostRatings()->getControl($this_id, true)) { ?>
					<div class="rating-box">
						<?php echo PostRatings()->getControl($this_id, true); ?>
					</div>
				<?php } ?>

			<?php endif;  ?>

		</div>

	</div><!--/ .row-->

	<div class="post-body">

		<p class="m_bottom_15">
			<?php echo do_shortcode(apply_filters('the_content', $content)); ?>
		</p>

		<div class="m_bottom_30">
			<?php mad_share_post_this(false); ?>
		</div>

		<?php
			$mad_related_cats = get_posts(array(
				'category__in' => wp_get_post_categories($this_id),
				'numberposts' => -1,
				'post__not_in' => array($this_id))
			);
		?>

		<?php if ($mad_related_cats): ?>

			<div class="tax-holder">

				<div class="categories-tax-list">
					<span><?php _e('More in this category:', MAD_BASE_TEXTDOMAIN); ?></span>
					<?php foreach ($mad_related_cats as $post): setup_postdata($post); ?>
						<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?> » </a>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</div>

				<?php if (is_single() && has_tag() && !post_password_required()): ?>
					<div class="tags-tax-list">
						<?php the_tags(__('Tags: ', MAD_BASE_TEXTDOMAIN), ', ', ''); ?>
					</div>
				<?php endif; ?>

			</div><!--/ .tax-holder-->

		<?php endif; ?>

		<?php if (mad_custom_get_option('blog-single-link-pages')): ?>
			<?php get_template_part('loop/single', 'link-pages'); ?>
		<?php endif; ?>

		<?php if (mad_custom_get_option('blog-single-related-posts')): ?>
			<?php get_template_part('loop/single', 'related-posts'); ?>
		<?php endif; ?>

	</div><!--/ .post-body-->

</article><!--/ .post-entry-->