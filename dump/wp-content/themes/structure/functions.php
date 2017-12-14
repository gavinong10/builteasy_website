<?php
/**
 * ThemeMove functions and definitions
 *
 * @package ThemeMove
 */

/**
 * ============================================================================
 * Set the content width based on the theme's design and stylesheet.
 * ============================================================================
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**
 * ============================================================================
 * Thememove only works in WordPress 3.9 or later.
 * ============================================================================
 */
if ( version_compare( $GLOBALS['wp_version'], '3.9', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

/**
 * ============================================================================
 * Define Constants
 * ============================================================================
 */
define( 'THEME_ROOT', get_template_directory_uri() );
require_once get_template_directory() . '/inc/variables.php';

if ( ! function_exists( 'thememove_setup' ) ) :
	/**
	 * ============================================================================
	 * Sets up theme defaults and registers support for various WordPress features.
	 * ============================================================================
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thememove_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ThemeMove, use a find and replace
		 * to change 'thememove' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'thememove', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( "post-thumbnails" );
		add_image_size( 'post-thumb', 848, 450, true );
		add_image_size( 'post-thumb-2', 320, 300, true );
		add_image_size( 'small-thumb', 120, 90, true );
		add_image_size( 'project-single', 1170, 600, true );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'thememove' ),
			'social'  => __( 'Social Profile Links', 'thememove' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery', ) );

		// Setup the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'thememove_custom_background_args', array(
			'default-color' => 'f7f7f7',
			'default-image' => 'http://structurecdn.thememove.com/01/data/images/notebook.png',
		) ) );

		//support woocommerce
		add_theme_support( 'woocommerce' );
	}
endif; // thememove_setup
add_action( 'after_setup_theme', 'thememove_setup' );

/**
 * ============================================================================
 * Register widget area.
 * ============================================================================
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function thememove_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'thememove' ),
		'id'            => 'sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar for shop', 'thememove' ),
		'id'            => 'sidebar-shop',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Top Slider', 'thememove' ),
		'id'            => 'top-slider',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Top Widget Area', 'thememove' ),
		'id'            => 'top-area',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header Right Widget Area', 'thememove' ),
		'id'            => 'header-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1 Widget Area', 'thememove' ),
		'id'            => 'footer',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2 Widget Area', 'thememove' ),
		'id'            => 'footer2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3 Widget Area', 'thememove' ),
		'id'            => 'footer3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Language Widget Area', 'thememove' ),
			'id'            => 'lang-area',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget widget-language %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	if ( class_exists( 'WP_Job_Manager' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Job Widget Area', 'thememove' ),
			'id'            => 'job-area',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget widget-job %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}

add_action( 'widgets_init', 'thememove_widgets_init' );

/**
 * ============================================================================
 * Enqueue scripts and styles.
 * ===========================================================================
 */
function thememove_scripts() {
	wp_enqueue_style( 'thememove-style', THEME_ROOT . '/style.css' );
	wp_enqueue_style( 'thememove-main', THEME_ROOT . '/css/main-style.css' );
	wp_enqueue_style( 'thememove-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );

	if ( get_theme_mod( 'page_transition' ) == 'type1' ) {
		wp_enqueue_script( 'thememove-js-nprogress', THEME_ROOT . '/js/nprogress.js', array( 'jquery' ), null, true );
	} elseif ( get_theme_mod( 'page_transition' ) == 'type2' ) {
		wp_enqueue_script( 'thememove-js-animsition', THEME_ROOT . '/js/jquery.animsition.min.js', array( 'jquery' ), null, true );
	}
	wp_enqueue_script( 'thememove-js-stellar', THEME_ROOT . '/js/jquery.stellar.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'thememove-js-owl-carousel', THEME_ROOT . '/js/owl.carousel.min.js' );
	if ( get_theme_mod( 'enable_smooth_scroll', enable_smooth_scroll ) ) {
		wp_enqueue_script( 'thememove-js-smooth-scroll', THEME_ROOT . '/js/smoothscroll.js' );
	}
	global $thememove_sticky_header;
	if ( ( get_theme_mod( 'header_sticky_enable', header_sticky_enable ) || $thememove_sticky_header == 'enable' ) && $thememove_sticky_header != 'disable' ) {
		wp_enqueue_script( 'thememove-js-head-room-jquery', THEME_ROOT . '/js/jQuery.headroom.min.js' );
		wp_enqueue_script( 'thememove-js-head-room', THEME_ROOT . '/js/headroom.min.js' );
	}
	wp_enqueue_script( 'thememove-js-magnific', THEME_ROOT . '/js/jquery.magnific-popup.min.js' );
	if ( is_page_template( 'template-contact.php' ) ) {
		wp_enqueue_script( 'thememove-js-maps', 'http://maps.google.com/maps/api/js?sensor=false&amp;language=en' );
		wp_enqueue_script( 'thememove-js-gmap3', THEME_ROOT . '/js/gmap3.min.js' );
	}
	wp_enqueue_script( 'thememove-js-counterup', THEME_ROOT . '/js/jquery.counterup.min.js' );
	wp_enqueue_script( 'thememove-js-waypoints', THEME_ROOT . '/js/waypoints.min.js' );
	wp_enqueue_script( 'thememove-js-main', THEME_ROOT . '/js/main.js', array( 'jquery' ), null, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'thememove_scripts' );

/**
 * ============================================================================
 * Loading components.
 * ============================================================================
 */
require_once get_template_directory() . '/core/thememove-core.php';
require_once get_template_directory() . '/inc/oneclick.php';
require_once get_template_directory() . '/inc/extras.php';
require_once get_template_directory() . '/inc/custom-header.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/jetpack.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/metabox.php';
require_once get_template_directory() . '/inc/custom-css.php';
require_once get_template_directory() . '/inc/custom-js.php';

/**
 * ============================================================================
 * Requirement Plugins
 * ============================================================================
 */
require_once get_template_directory() . '/inc/tmg-plugin-activation.php';
require_once get_template_directory() . '/inc/tmg-plugin-registration.php';

/**
 * ============================================================================
 * Auto Update Theme
 * ============================================================================
 */
require_once( 'wp-updates-theme.php' );
new WPUpdatesThemeUpdater_1276( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ) );


/**
 * ============================================================================
 * General setups
 * ============================================================================
 */
add_filter( 'widget_text', 'do_shortcode' );
//add_filter( 'the_excerpt', 'do_shortcode');

// Remove admin notification of Projects
if ( class_exists( 'Projects_Admin' ) ) {
	global $projects;
	remove_action( 'admin_notices', array( $projects->admin, 'configuration_admin_notice' ) );
}

add_filter( 'projects_enqueue_styles', '__return_false' );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

add_filter( 'loop_shop_columns', 'loop_columns' );
if ( ! function_exists( 'loop_columns' ) ) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 6;' ), 20 );

// Extend VC
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	function requireVcExtend() {
		require_once get_template_directory() . '/inc/vc-extend.php';
	}

	add_action( 'init', 'requireVcExtend', 2 );
}

// Notification for update VC 4.6.2
$my_theme = wp_get_theme( 'structure' );
if ( $my_theme->get( 'Version' ) == '2.4' && version_compare( WPB_VC_VERSION, '4.6.2', '<' ) ) {
	function my_admin_error_notice() {
		echo "<div class=\"error\"> <p>In version 2.4 of Structure, you must have to update visual composer to 4.6.2 to make it work well, please follow <a href='https://cldup.com/8oPbxTV-jL.gif' target='_blank'>this image</a> to update it automatic</p></div>";
	}

	add_action( 'admin_notices', 'my_admin_error_notice' );
}

// Remove admin notification of Projects
if ( class_exists( 'Projects' ) ) {
	global $projects;
	remove_action( 'admin_notices', array( $projects->admin, 'configuration_admin_notice' ) );
}