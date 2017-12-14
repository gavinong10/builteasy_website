<?php
/**
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Flatastic
 * @since Flatastic 1.0
 */
get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php $mad_results = mad_which_archive(); ?>

	<?php
		$id = get_query_var('author');
		$name  =  get_the_author_meta('display_name', $id);
		$email =  get_the_author_meta('email', $id);
		$heading      = __("About", MAD_BASE_TEXTDOMAIN) ." ". $name;
		$description  = get_the_author_meta('description', $id);

		if (current_user_can('edit_users') || get_current_user_id() == $id) {
			$description .= " <a href='" . admin_url( 'profile.php?user_id=' . $id ) . "'>". __( '[ Edit the profile ]', MAD_BASE_TEXTDOMAIN ) ."</a>";
		}
	?>

	<?php if (!empty($mad_results)): ?>
		<?php echo mad_title(array('title' => $mad_results)) ?>
	<?php endif; ?>

	<div class="template-box">

		<div class="template-image-format">
			<?php echo get_avatar($email, '90', '', esc_html($name)); ?>
		</div><!--/ .template-image-format-->

		<div class="template-description">
			<h3 class="template-title"><?php echo esc_html($heading); ?></h3>
			<div class="template-text">
				<?php echo $description; ?>
			</div><!--/ .template-text-->
		</div><!--/ .template-description-->

		<div class="clear"></div>

	</div><!--/ .template-box-->

	<div class="post-area">

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