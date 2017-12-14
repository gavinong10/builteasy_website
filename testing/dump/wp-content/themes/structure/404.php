<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package ThemeMove
 */
$breadcrumb_home_text = get_theme_mod( 'breadcrumb_home_text', 'Home' );
get_header(); ?>
<div class="content-wrapper">
	<main id="main" class="site-main" role="main">

		<section class="error-404 not-found">
			<header data-stellar-background-ratio="0.5" class="entry-header has-bg">
				<div class="container">
					<h1 class="page-title"><?php _e( '<span>Oops!</span> That page <span>can&rsquo;t</span> be found.', 'thememove' ); ?></h1>
					<?php if ( function_exists( 'tm_bread_crumb' ) ) { ?>
						<div class="breadcrumb">
							<div class="container">
								<?php echo tm_bread_crumb( array( 'home_label' => $breadcrumb_home_text ) ); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</header>
			<div class="page-content">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<img src="<?php echo THEME_ROOT ?>/images/find.jpg" alt=""/>
						</div>
						<div class="col-md-6">
							<h2><?php _e( 'Look like you are lost', 'thememove' ); ?></h2>

							<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'thememove' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- .page-content -->
		</section>
		<!-- .error-404 -->

	</main>
	<!-- #main -->
</div>

<?php get_footer(); ?>
