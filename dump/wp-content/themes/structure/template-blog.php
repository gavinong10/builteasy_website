<?php
/*
Template Name: Blog Page
*/
$thememove_page_layout_private = get_post_meta( get_the_ID(), "thememove_page_layout_private", true );
$thememove_header_top          = get_post_meta( get_the_ID(), "thememove_header_top", true );
$thememove_header_preset       = get_post_meta( get_the_ID(), "thememove_header_preset", true );
$thememove_color_scheme        = get_post_meta( get_the_ID(), "thememove_color_scheme", true );
$thememove_sticky_header       = get_post_meta( get_the_ID(), "thememove_sticky_header", true );
$thememove_custom_logo         = get_post_meta( get_the_ID(), "thememove_custom_logo", true );
$thememove_custom_class        = get_post_meta( get_the_ID(), "thememove_custom_class", true );
$thememove_bread_crumb_enable  = get_post_meta( get_the_ID(), "thememove_bread_crumb_enable", true );
$thememove_uncover_enable      = get_post_meta( get_the_ID(), "thememove_uncover_enable", true );
$thememove_disable_title       = get_post_meta( get_the_ID(), "thememove_disable_title", true );
$breadcrumb_home_text          = get_theme_mod( 'breadcrumb_home_text', 'Home' );
if ( $thememove_page_layout_private != 'default' ) {
	$layout = get_post_meta( get_the_ID(), "thememove_page_layout_private", true );
} else {
	$layout = get_theme_mod( 'site_layout', site_layout );
}
get_header(); ?>
<?php if ( $layout == 'full-width' ) { ?>
	<div class="content-wrapper">
		<main class="content" role="main">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope"
			     itemtype="http://schema.org/CreativeWork">
				<?php if ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) && get_post_meta( get_the_ID(), "thememove_heading_image", true ) ) { ?>
					<div data-stellar-background-ratio="0.5" class="entry-header has-bg"
					     style="background-image: url('<?php echo get_post_meta( get_the_ID(), "thememove_heading_image", true ) ?>')">
						<div class="container">
							<?php if ( get_post_meta( get_the_ID(), "thememove_alt_title", true ) ) { ?>
								<h1 class="entry-title"
								    itemprop="headline"><?php echo get_post_meta( get_the_ID(), "thememove_alt_title", true ); ?></h1>
							<?php } else { ?>
								<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
							<?php } ?>
							<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
								<div class="breadcrumb">
									<div class="container">
										<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } elseif ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) && ! get_post_meta( get_the_ID(), "thememove_heading_image", true ) ) { ?>
					<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
						<div class="breadcrumb">
							<div class="container">
								<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
							</div>
						</div>
					<?php } ?>
					<div class="entry-header">
						<div class="container">
							<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
						</div>
					</div>
				<?php } elseif ( get_post_meta( get_the_ID(), "thememove_disable_title", true ) ) { ?>
					<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
						<div class="breadcrumb">
							<div class="container">
								<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
						<div class="breadcrumb">
							<div class="container">
								<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
							</div>
						</div>
					<?php } ?>
					<div class="entry-header">
						<div class="container">
							<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
						</div>
					</div>
				<?php } ?>
				<div class="entry-content" itemprop="text">
					<div class="container">
						<?php
						// the query
						global $wp_query;

						$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$args      = array(
							'post_type' => 'post',
							'paged'     => $paged
						);
						$the_query = new WP_Query( $args );

						// Put default query object in a temp variable
						$tmp_query = $wp_query;
						// Now wipe it out completely
						$wp_query = null;
						// Re-populate the global with our custom query
						$wp_query = $the_query;
						?>
						<?php if ( $the_query->have_posts() ) : ?>
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'content', '2' );
								?>
							<?php endwhile; ?>
							<?php thememove_paging_nav(); ?>
							<?php wp_reset_postdata(); ?>

						<?php else : ?>
							<p><?php _e( 'Sorry, no posts matched your criteria.','thememove' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</main>
	</div>

<?php } else { ?>
	<div class="content-wrapper">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) && get_post_meta( get_the_ID(), "thememove_heading_image", true ) ) { ?>
				<div data-stellar-background-ratio="0.5" class="entry-header has-bg"
				     style="background-image: url('<?php echo get_post_meta( get_the_ID(), "thememove_heading_image", true ) ?>')">
					<div class="container">
						<?php if ( get_post_meta( get_the_ID(), "thememove_alt_title", true ) ) { ?>
							<h1 class="entry-title"
							    itemprop="headline"><?php echo get_post_meta( get_the_ID(), "thememove_alt_title", true ); ?></h1>
						<?php } else { ?>
							<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
						<?php } ?>
						<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
							<div class="breadcrumb">
								<div class="container">
									<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } elseif ( ! get_post_meta( get_the_ID(), "thememove_disable_title", true ) && ! get_post_meta( get_the_ID(), "thememove_heading_image", true ) ) { ?>
				<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
					<div class="breadcrumb">
						<div class="container">
							<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
						</div>
					</div>
				<?php } ?>
				<div class="entry-header">
					<div class="container">
						<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
					</div>
				</div>
			<?php } elseif ( get_post_meta( get_the_ID(), "thememove_disable_title", true ) ) { ?>
				<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
					<div class="breadcrumb">
						<div class="container">
							<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
						</div>
					</div>
				<?php } ?>
			<?php } else { ?>
				<?php if ( function_exists( 'tm_bread_crumb' ) && $thememove_bread_crumb_enable != 'disable' ) { ?>
					<div class="breadcrumb">
						<div class="container">
							<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
						</div>
					</div>
				<?php } ?>
				<div class="entry-header">
					<div class="container">
						<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
					</div>
				</div>
			<?php } ?>
		<?php endwhile; // end of the loop. ?>
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
					<div class="entry-content" itemprop="text">
						<?php
						// the query
						global $wp_query;

						$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$args      = array(
							'post_type' => 'post',
							'paged'     => $paged
						);
						$the_query = new WP_Query( $args );

						// Put default query object in a temp variable
						$tmp_query = $wp_query;
						// Now wipe it out completely
						$wp_query = null;
						// Re-populate the global with our custom query
						$wp_query = $the_query;
						?>
						<?php if ( $the_query->have_posts() ) : ?>
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'content', '2' );
								?>
							<?php endwhile; ?>
							<?php thememove_paging_nav(); ?>
						<?php else : ?>
							<p><?php _e( 'Sorry, no posts matched your criteria.','thememove' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $layout == 'content-sidebar' ) { ?>
					<?php get_sidebar(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer(); ?>