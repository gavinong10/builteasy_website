<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Flatastic
 * @since Flatastic 1.0
 */


get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php
		$mad_results = mad_which_archive();
		$mad_blog_style = mad_custom_get_option('blog_style');
		$mad_description = is_tag() ? tag_description() : category_description();
	?>

	<?php if (!empty($mad_results)): ?>
		<?php echo mad_title(array('title' => $mad_results, 'subtitle' => $mad_description)) ?>
	<?php endif; ?>

	<div class="post-area <?php echo esc_attr($mad_blog_style); ?>">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			get_template_part( 'loop/loop', 'tag' );
		endwhile;
		?>

	</div><!--/ .post-area-->

	<?php
	// Previous/next page navigation.
	the_posts_pagination( array(
		'prev_text'          => __( 'Previous page', MAD_BASE_TEXTDOMAIN ),
		'next_text'          => __( 'Next page', MAD_BASE_TEXTDOMAIN ),
		'show_all' => true,
		'mid_size' => 2
	) );
	?>

<?php else:

	// If no content, include the "No posts found" template.
	get_template_part( 'content', 'none' );

endif; ?>

<?php get_footer(); ?>