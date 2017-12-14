<?php
/**
 * The template for displaying all single posts.
 *
 * @package ThemeMove
 */

get_header();
$layout               = get_theme_mod( 'post_layout', post_layout );
$breadcrumb_home_text = get_theme_mod( 'breadcrumb_home_text', 'Home' );
?>
<div class="content-wrapper">
	<div data-stellar-background-ratio="0.5" class="has-bg">
		<div class="container">
			<h1><?php echo html_entity_decode( get_theme_mod( 'post_heading_text', post_heading_text ) ) ?></h1>
			<?php if ( function_exists( 'tm_bread_crumb' ) && ! is_front_page() ) { ?>
				<div class="breadcrumb">
					<div class="container">
						<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php if ( $layout == 'sidebar-content' ) { ?>
				<?php get_sidebar(); ?>
			<?php } ?>
			<?php if ( $layout == 'sidebar-content' || $layout == 'content-sidebar' ) { ?>
				<?php $class = 'col-md-9'; ?>
			<?php } else { ?>
				<?php $class = 'col-md-12'; ?>
			<?php } ?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<main class="content" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'single' ); ?>

						<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					<?php endwhile; // end of the loop. ?>

				</main>
			</div>
			<?php if ( $layout == 'content-sidebar' ) { ?>
				<?php get_sidebar(); ?>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
