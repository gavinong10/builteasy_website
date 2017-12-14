<?php
/*
Template Name: Under Construction
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_theme_mod( 'favicon_image', favicon_image ); ?>">
	<link rel="apple-touch-icon" href="<?php echo get_theme_mod( 'apple_touch_icon', apple_touch_icon ); ?>"/>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<div class="header-wrapper">
		<?php
		global $thememove_header_top;
		if ( ( get_theme_mod( 'header_top_enable', header_top_enable ) || $thememove_header_top == 'enable' ) && $thememove_header_top != 'disable' ) { ?>
			<div class="top-area">
				<div class="container">
					<div class="row">
						<div class="col-md-8 hidden-xs hidden-sm">
							<?php dynamic_sidebar( 'top-area' ); ?>
						</div>
						<div class="col-md-4 social">
							<?php wp_nav_menu( array( 'theme_location' => 'social', 'fallback_cb' => false ) ); ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="content-wrapper">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; // end of the loop. ?>
	</div>
	<div class="bottom-wrapper">
		<?php if ( get_theme_mod( 'footer_copyright_enable', footer_copyright_enable ) ) { ?>
			<div class="copyright">
				<div class="container">
					<?php echo html_entity_decode( get_theme_mod( 'copyright_text', copyright_text ) ); ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
