<?php
/**
* The main template file
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* e.g., it puts together the home page when no home.php file exists.
*
* @package WordPress
* @subpackage Flatatic
* @since Flatastic 1.0
*/

get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php $mad_blog_style = mad_custom_get_option('blog_style'); ?>

	<div class="post-area <?php echo esc_attr($mad_blog_style); ?>">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			get_template_part( 'loop/loop', 'index' );
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