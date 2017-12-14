<?php
/*
Template Name: Contact Page
*/
$thememove_page_layout_private = get_post_meta( get_the_ID(), "thememove_page_layout_private", true );
if ( $thememove_page_layout_private != 'default' ) {
	$layout = get_post_meta( get_the_ID(), "thememove_page_layout_private", true );
} else {
	$layout = get_theme_mod( 'site_layout', site_layout );
}
get_header();
?>
<?php if ( $layout == 'full-width' ) { ?>
	<div class="content-wrapper">
		<main class="content" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope"
				         itemtype="http://schema.org/CreativeWork">
					<div id="map-canvas"
					     data-address="<?php echo get_post_meta( get_the_ID(), "thememove_contact_address", true ) ?>"></div>
					<?php if ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) ) { ?>
						<header class="entry-header align-center">
							<div class="container">
								<?php the_title( '<h1 class="entry-title heading-title-2" itemprop="headline">', '</h1>' ); ?>
							</div>
						</header>
					<?php } ?>
					<div class="entry-content" itemprop="text">
						<?php the_content(); ?>
						<?php
						wp_link_pages( array(
							'before' => '<div class="page-links">' . __( 'Pages:', 'thememove' ),
							'after'  => '</div>',
						) );
						?>
					</div>
					<!-- .entry-content -->
				</article><!-- #post-## -->
				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
			<?php endwhile; // end of the loop. ?>
		</main>
		<!-- .content -->
	</div>

<?php } else { ?>
	<div class="content-wrapper">
		<div id="map-canvas"
		     data-address="<?php echo get_post_meta( get_the_ID(), "thememove_contact_address", true ) ?>"></div>
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
					<main class="content" role="main" itemprop="mainContentOfPage">
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope"
							         itemtype="http://schema.org/CreativeWork">
								<?php if ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) ) { ?>
									<header class="entry-header align-center">
										<div class="container">
											<?php the_title( '<h1 class="entry-title heading-title-2" itemprop="headline">', '</h1>' ); ?>
										</div>
									</header>
								<?php } ?>
								<div class="entry-content" itemprop="text">
									<?php the_content(); ?>
									<?php
									wp_link_pages( array(
										'before' => '<div class="page-links">' . __( 'Pages:', 'thememove' ),
										'after'  => '</div>',
									) );
									?>
								</div>
								<!-- .entry-content -->
							</article><!-- #post-## -->
							<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						<?php endwhile; // end of the loop. ?>
					</main>
					<!-- .content -->
				</div>
				<?php if ( $layout == 'content-sidebar' ) { ?>
					<?php get_sidebar(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer(); ?>