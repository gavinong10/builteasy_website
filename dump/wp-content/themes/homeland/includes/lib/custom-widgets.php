<?php
	$homeland_all_agents = esc_attr( get_option('homeland_all_agents') );
	
	if(empty($homeland_all_agents)) : 
		include get_template_directory() . '/includes/widgets/widget-agent.php'; 
	endif;

	include get_template_directory() . '/includes/widgets/widget-follow-us.php';
	include get_template_directory() . '/includes/widgets/widget-contact.php';
	include get_template_directory() . '/includes/widgets/widget-video.php';
	include get_template_directory() . '/includes/widgets/widget-flickr.php';
	include get_template_directory() . '/includes/widgets/widget-popular.php';
	include get_template_directory() . '/includes/widgets/widget-dribbble.php';
	include get_template_directory() . '/includes/widgets/widget-featured-properties.php';
	include get_template_directory() . '/includes/widgets/widget-search-type.php';
	include get_template_directory() . '/includes/widgets/widget-search-location.php';
	include get_template_directory() . '/includes/widgets/widget-search-status.php';
	include get_template_directory() . '/includes/widgets/widget-search-amenities.php';
	include get_template_directory() . '/includes/widgets/widget-twitter.php';
	include get_template_directory() . '/includes/widgets/widget-advance-search.php';
	include get_template_directory() . '/includes/widgets/widget-facebook-like.php';
	include get_template_directory() . '/includes/widgets/widget-gmap.php';
	include get_template_directory() . '/includes/widgets/widget-portfolio.php';


	/**********************************************
	SIDEBAR WIDGETS
	***********************************************/

	if ( ! function_exists( 'homeland_sidebar_widgets_init' ) ) :
		function homeland_sidebar_widgets_init() {
		
			register_sidebar( array(
				'name' => __( 'Page Sidebar', 'codeex_theme_name' ),
				'id' => 'homeland_sidebar',
				'description' => __( 'Main widgets of the theme', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Sliding Bar', 'codeex_theme_name' ),
				'id' => 'homeland_sliding_bar',
				'description' => __( 'Sliding Bar widgets of the theme located at the top of the website', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Top Slider', 'codeex_theme_name' ),
				'id' => 'homeland_top_slider',
				'description' => __( 'Top slider widgets of the theme for revolution slider', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Search Type', 'codeex_theme_name' ),
				'id' => 'homeland_search_type',
				'description' => __( 'Search Type widgets of the theme for IDX listing search. Leave it empty if you want to use the default Advance Search', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Dual Sidebar', 'codeex_theme_name' ),
				'id' => 'homeland_dual_sidebar',
				'description' => __( 'This widgets of the theme is for dual sidebar, this is displayed in left sidebar', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Footer One', 'codeex_theme_name' ),
				'id' => 'homeland_footer_one',
				'description' => __( 'Footer column widget of the theme', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Footer Two', 'codeex_theme_name' ),
				'id' => 'homeland_footer_two',
				'description' => __( 'Footer column widget of the theme', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Footer Three', 'codeex_theme_name' ),
				'id' => 'homeland_footer_three',
				'description' => __( 'Footer column widget of the theme', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );

			register_sidebar( array(
				'name' => __( 'Footer Four', 'codeex_theme_name' ),
				'id' => 'homeland_footer_four',
				'description' => __( 'Footer column widget of the theme', 'codeex_theme_name' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => "</div>",
				'before_title' => '<h5>',
				'after_title' => '</h5>',
			) );
			
		}
	endif;
	add_action( 'widgets_init', 'homeland_sidebar_widgets_init' );
?>