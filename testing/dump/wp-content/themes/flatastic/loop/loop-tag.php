<?php

$blog_style = mad_custom_get_option('blog_style');
if (empty($blog_style)) {
	$blog_style = 'blog-medium';
}

$type_blog = 'entry-' . $blog_style;
$before_content = '';

$this_post = array();
$this_post['post_id'] = $id = get_the_ID();
$this_post['url'] = $link = get_permalink();
$this_post['title'] = $title = get_the_title();
$this_post['post_format'] = $format = get_post_format() ? get_post_format() : 'standard';
$this_post['image_size'] = mad_blog_alias($format, '', $type_blog);
$this_post['content'] = (has_excerpt()) ? get_the_excerpt() : get_the_content();
$this_post['content'] = apply_filters('the_content', $this_post['content']);

$this_post = apply_filters('entry-format-'. $format, $this_post);
$post_class = "post-item clearfix {$type_blog}";
extract($this_post);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class) ?>>

	<?php echo $before_content; ?>

	<div class="post-content">

		<?php if (is_sticky()): ?>
			<?php printf( '<span class="sticky-post">%s</span>', __( 'Featured', MAD_BASE_TEXTDOMAIN ) ); ?>
		<?php endif; ?>

		<div class="entry-meta">

			<h4 class="entry-title">
				<a href="<?php echo esc_url($link) ?>"><?php echo esc_html($title) ?></a>
			</h4><!--/ .entry-title-->

			<?php echo mad_blog_post_meta($id); ?>

		</div><!--/ .entry-meta-->

		<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', MAD_BASE_TEXTDOMAIN) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', MAD_BASE_TEXTDOMAIN) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>

		<a href="<?php echo esc_url($link); ?>" class="read-more">
			<?php _e('Read More', MAD_BASE_TEXTDOMAIN); ?>
		</a>

		<?php edit_post_link( __( '[ Edit ]', MAD_BASE_TEXTDOMAIN ), '<span class="edit-link">', '</span>' ); ?>

	</div><!--/ .post-content-->

</article><!--/ .post-item-->