<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Flatastic
 * @since Flatastic 1.0
 */
get_header(); ?>

<?php if ( have_posts() ): ?>

	<div class="post-area">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'loop/loop', 'single' ); ?>
		<?php endwhile ?>

	</div><!--/ .post-area-->

<?php endif; ?>

<?php if (comments_open() || '0' != get_comments_number()): ?>
	<?php comments_template(); ?>
<?php endif; ?>

<?php get_footer(); ?>