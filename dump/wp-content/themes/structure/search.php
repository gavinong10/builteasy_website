<?php
/**
 * The template for displaying search results pages.
 *
 * @package ThemeMove
 */

$layout               = get_theme_mod( 'search_layout', search_layout );
$breadcrumb_home_text = get_theme_mod( 'breadcrumb_home_text', 'Home' );
get_header(); ?>
<div class="content-wrapper">
	<div data-stellar-background-ratio="0.5" class="entry-header has-bg">
		<div class="container">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'thememove' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<?php if ( function_exists( 'tm_bread_crumb' ) && ! is_front_page() ) { ?>
				<div class="breadcrumb">
					<div class="container">
						<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
					</div>
				</div><!-- .breadcrumb -->
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
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
					<?php endwhile; ?>
					<?php thememove_paging_nav(); ?>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( $layout == 'content-sidebar' ) { ?>
				<?php get_sidebar(); ?>
			<?php } ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
