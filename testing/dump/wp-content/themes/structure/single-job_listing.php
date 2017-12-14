<?php
/**
 * The template for displaying all single posts.
 *
 * @package ThemeMove
 */

get_header();
$breadcrumb_home_text = get_theme_mod( 'breadcrumb_home_text', 'Home' );
?>
<div class="content-wrapper">
	<div data-stellar-background-ratio="0.5" class="has-bg">
		<div class="container">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
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
			<?php get_sidebar( 'jobs' ); ?>
			<div class="col-md-9">
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="row">
						<div class="col-md-8">
							<?php the_content(); ?>
							<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'thememove' ),
								'after'  => '</div>',
							) );
							?>
						</div>
						<div class="col-md-4">
							<div class="single_job_info">
								<ul>
									<li>
										<h3><?php echo __( 'Job Department', 'thememove' ) ?></h3>
										<?php display_job_department(); ?>
									</li>
									<li>
										<h3><?php echo __( 'Job Type', 'thememove' ) ?></h3>
										<?php the_job_type(); ?>
									</li>
									<li>
										<h3><?php echo __( 'Job Location', 'thememove' ) ?></h3>
										<?php the_job_location(); ?>
									</li>
									<li>
										<h3><?php echo __( 'Job Expiry Date', 'thememove' ) ?></h3>
										<?php display_job_date(); ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
