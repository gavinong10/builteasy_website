<?php
/**
 * Flatastic functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @since Flatastic 1.0
 */

/* 	Basic Settings
/* ---------------------------------------------------------------------- */

define('MAD_THEMENAME', 'Flatastic');
define('MAD_BASE_TEXTDOMAIN', 'flatastic');
define('MAD_THEME_VERSION', '1.2.7');
define('MAD_PREFIX', 'flatastic-');

define('MAD_HOME_URL', get_home_url('/'));
define('MAD_BASE_URI', trailingslashit(get_template_directory_uri()));
define('MAD_BASE_PATH', trailingslashit(get_template_directory()));
define('MAD_ADMIN_PATH', MAD_BASE_PATH . trailingslashit('admin'));
define('MAD_FRAMEWORK_PATH', MAD_ADMIN_PATH . trailingslashit('framework'));

define('MAD_INC_PATH', MAD_BASE_PATH . trailingslashit('inc'));
define('MAD_INC_URI', MAD_BASE_URI . trailingslashit('inc'));

define('MAD_INC_PLUGINS_PATH', MAD_INC_PATH . 'plugins/');
define('MAD_INC_PLUGINS_URI', MAD_INC_URI . 'plugins/');

define('MAD_INCLUDES_URI', MAD_BASE_URI . trailingslashit('includes'));
define('MAD_INCLUDES_PATH', MAD_BASE_PATH . trailingslashit('includes'));

define('MAD_INCLUDE_CLASSES_PATH', trailingslashit(MAD_INCLUDES_PATH) . trailingslashit('classes'));

define('MAD_TEMPLATES_PATH', MAD_INCLUDES_PATH . 'templates/');
define('MAD_TEMPLATES_URL',  MAD_INCLUDES_URI . trailingslashit('templates'));

define('MAD_BASE_HELPERS', MAD_INCLUDES_PATH . trailingslashit('helpers'));

define('MAD_INCLUDES_METABOXES_PATH', MAD_INCLUDES_PATH . trailingslashit('meta-box'));
define('MAD_INCLUDES_METABOXES_URI', MAD_INCLUDES_URI . trailingslashit('meta-box'));

if ( !isset( $content_width ) ) $content_width = 1140;

/* For Screets Chat X
/* ---------------------------------------------------------------------- */

define( 'CX_ACTIVATE_K', 'cx_c26ff42d96d5fd8c09f91' );
define( 'CX_ACTIVATE_T', 'Flatastic' );
if ( function_exists( 'cx_api' ) ) {
	cx_api();
}

/* Load Theme Helpers
/* ---------------------------------------------------------------------- */
include_once MAD_BASE_HELPERS . 'aq_resizer.php';
include_once MAD_BASE_HELPERS . 'nav-walker.php';
include_once MAD_BASE_HELPERS . 'theme-helper.php';
include_once MAD_BASE_HELPERS . 'post-format-helper.php';

/*  Load Functions Files
/* ---------------------------------------------------------------------- */
include_once MAD_INCLUDES_PATH . 'functions-core.php';
include_once MAD_INCLUDES_PATH . 'functions-template.php';

/*  Add Widgets
/* ---------------------------------------------------------------------- */
include_once MAD_INCLUDES_PATH . 'widgets.php';

/*  Load hooks
/* ---------------------------------------------------------------------- */
if (!is_admin()) {
	include_once MAD_INCLUDES_PATH . 'templates-hooks.php';
}

/*  Include Framework
/* ---------------------------------------------------------------------- */
include_once MAD_FRAMEWORK_PATH . 'framework.php';

/*  Include Plugins
/* ---------------------------------------------------------------------- */
require_once( MAD_BASE_PATH . 'admin/plugin-bundle.php');
include_once 'config-plugins/config.php';
include_once MAD_INC_PLUGINS_PATH . 'plugins.php';

/*  Add Meta Boxes
/* ---------------------------------------------------------------------- */
include_once MAD_INCLUDES_PATH . 'meta-box/meta-box.php';
include_once MAD_INCLUDES_PATH . 'config-meta.php';

/*  Load Classes
/* ---------------------------------------------------------------------- */
include_once MAD_INCLUDE_CLASSES_PATH . 'register-page.class.php';

/*  Base Function Class
/* ---------------------------------------------------------------------- */

if (!class_exists('MAD_BASE_FUNCTIONS')) {

	class MAD_BASE_FUNCTIONS {

		function __construct() {
			add_action('after_setup_theme', array(&$this, 'after_setup_theme'), 1);

			$this->init();

			/*  Init Classes
			/* ---------------------------------------------------------------------- */
			new MAD_PAGE();
		}

		/* 	After Setup Theme
		/* ---------------------------------------------------------------------- */

		public function after_setup_theme() {

			// Post Formats Support
			add_theme_support('post-formats', array( 'gallery', 'quote', 'video', 'audio' ));

			// Post Thumbnails Support
			add_theme_support('post-thumbnails');

			// Add default posts and comments RSS feed links to head
			add_theme_support('automatic-feed-links');

			add_theme_support('title-tag');

			add_filter('widget_text', 'do_shortcode');

			// This theme uses wp_nav_menu() in one location.
			register_nav_menu( 'primary', 'Primary Menu' );

			// Load theme textdomain
			self::load_textdomain();

			// Theme Activation
			self::theme_activation();
		}

		/* 	Initialization
		/* ---------------------------------------------------------------------- */

		public function init() {

			if (!is_admin()) {
				add_action('wp_enqueue_scripts', array(&$this, 'register_styles'));
				add_action('wp_enqueue_scripts', array(&$this, 'register_scripts'));

				add_action('wp_print_styles', array(&$this, 'wp_print_styles'), 2);
				add_action('wp_head', array(&$this, 'wp_head'), 3);
				add_action('wp_footer', array(&$this, 'wp_footer'));

				add_filter('body_class', array(&$this, 'body_class'));

				if (mad_custom_get_option('cookie_alert') == 'show') {
					if (!self::getcookie('cwallowcookies')) {
//						add_action('wp_head', array(&$this, 'top_cookie_alert_localize'), 3);
						add_action('body_prepend', array(&$this, 'top_cookie_alert_body_prepend'));
					}
				}
			}

		}

		function body_class($classes) {
			if (mad_custom_get_option('animation')) {
				$classes[] = 'animated';
			}
			return $classes;
		}

		/* 	Theme Activation
		/* ---------------------------------------------------------------------- */

		public static function theme_activation() {
			global $pagenow;
			if (is_admin() && 'themes.php' == $pagenow && isset($_GET['activated'])) {
				update_option('show_on_front', 'posts');
				do_action('backend_theme_activation');
				wp_redirect(admin_url('admin.php?page=mad'));
			}
		}

		/* 	Register Theme Styles
		/* ---------------------------------------------------------------------- */

		public function register_styles() {
			wp_register_style(MAD_PREFIX . 'bootstrap', MAD_BASE_URI . 'css/bootstrap.min.css', MAD_PREFIX . 'style');
			wp_register_style(MAD_PREFIX . 'style', MAD_BASE_URI . 'style.css');
			wp_register_style(MAD_PREFIX . 'layout', MAD_BASE_URI . 'css/layout.css');
			wp_register_style(MAD_PREFIX . 'camera', MAD_BASE_URI . 'css/camera.css');
			wp_register_style(MAD_PREFIX . 'owlcarousel', MAD_BASE_URI . 'js/owl-carousel/owl.carousel.css');
			wp_register_style(MAD_PREFIX . 'owltheme', MAD_BASE_URI . 'js/owl-carousel/owl.theme.css');
			wp_register_style(MAD_PREFIX . 'owltransitions', MAD_BASE_URI . 'js/owl-carousel/owl.transitions.css');
			wp_register_style(MAD_PREFIX . 'scrollbar', MAD_BASE_URI . 'css/jquery.custom-scrollbar.css');
			wp_register_style(MAD_PREFIX . 'font_awesome', MAD_BASE_URI . 'css/font-awesome.min.css');
			wp_register_style(MAD_PREFIX . 'jackbox', MAD_BASE_URI . 'js/jackbox/css/jackbox.css');
			wp_register_style(MAD_PREFIX . 'heapbox', MAD_BASE_URI . 'js/heapbox/heapbox.css');
		}

		/* 	Register Theme Scripts
		/* ---------------------------------------------------------------------- */

		public function register_scripts() {
			wp_register_script(MAD_PREFIX . 'modernizr', MAD_BASE_URI . 'js/modernizr.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'plugins', MAD_BASE_URI . 'js/plugins' . (WP_DEBUG ? '':'.min') . '.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'camera', MAD_BASE_URI . 'js/camera.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'isotope', MAD_BASE_URI . 'js/jquery.isotope.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'owlcarousel', MAD_BASE_URI . 'js/owl-carousel/owl.carousel.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'scrollbar', MAD_BASE_URI . 'js/jquery.custom-scrollbar.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'jacked', MAD_BASE_URI . 'js/jackbox/js/libs/Jacked.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'jackbox-swipe', MAD_BASE_URI . 'js/jackbox/js/jackbox-swipe.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'jackbox', MAD_BASE_URI . 'js/jackbox/js/jackbox.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'raty', MAD_BASE_URI . 'js/jquery.raty.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'heapbox', MAD_BASE_URI . 'js/heapbox/jquery.heapbox-0.9.4.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'jflickrfeed', MAD_BASE_URI . 'js/jflickrfeed.min.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'cookie', MAD_BASE_URI . 'js/jq-cookie.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'cookiealert', MAD_BASE_URI . 'js/cookiealert.js', array('jquery'));
			wp_register_script(MAD_PREFIX . 'scripts', MAD_BASE_URI . 'js/scripts' . (WP_DEBUG ? '' : '.min') . '.js', array('jquery'));
		}

		/* 	WP Header Action
		/* ---------------------------------------------------------------------- */

		public function wp_head() {
			wp_enqueue_script('jquery');

			self::enqueue_script('modernizr');
			self::enqueue_script('cookie');

			if (!self::getcookie('cwallowcookies')) {
				self::enqueue_script('cookiealert');
			}

			self::localize_script();
		}

		/*  WP Footer Action
		/* ---------------------------------------------------------------------- */

		public function wp_footer() {
			self::enqueue_script('plugins');
			self::enqueue_script('isotope');

			self::enqueue_script('jacked');
			self::enqueue_script('jackbox-swipe');
			self::enqueue_script('jackbox');

			self::enqueue_script('raty');
			self::enqueue_script('heapbox');
			self::enqueue_script('owlcarousel');
			self::enqueue_script('scrollbar');
			self::enqueue_script('scripts');
		}

		/* 	WP Print Styles
		/* ---------------------------------------------------------------------- */

		public static function wp_print_styles() {
			self::enqueue_style('bootstrap');
			self::enqueue_style('style');
			self::enqueue_style('layout');
			self::enqueue_style('camera');
			self::enqueue_style('owlcarousel');
			self::enqueue_style('owltheme');
			self::enqueue_style('owltransitions');
			self::enqueue_style('scrollbar');

			self::enqueue_style('jackbox');
			self::enqueue_style('heapbox');
			self::enqueue_style('font_awesome');

			$scheme = mad_custom_get_option('color_scheme');
			if ($scheme != '') {
				wp_register_style(MAD_PREFIX . 'scheme-style', MAD_BASE_URI . "css/schemes/{$scheme}.css", array( MAD_PREFIX . 'style' ));
				wp_enqueue_style(MAD_PREFIX . 'scheme-style');
			}

			if (is_rtl()) {
				wp_enqueue_style( 'mad-rtl',  MAD_BASE_URI . "css/rtl.css", array( MAD_PREFIX . 'style' ), '1', 'all' );
			}

			global $mad_theme_data;
			$prefix_name = sanitize_file_name($mad_theme_data['prefix']);

			if (get_option('exists_stylesheet'. $prefix_name ) == true) {
				$upload_dir = wp_upload_dir();
				if (is_ssl()) {
					$upload_dir['baseurl'] = str_replace("http://", "https://", $upload_dir['baseurl']);
				}
				$version = get_option('stylesheet_version' . $prefix_name);
				if (empty($version)) $version = '1';
				wp_enqueue_style(MAD_PREFIX . 'dynamic-styles', $upload_dir['baseurl'] . '/dynamic_mad_dir/' . $prefix_name . '.css', array( MAD_PREFIX . 'style' ), $version, 'all');
			}

			self::enqueue_style('custom_styles');
		}

		/* 	Localize Scripts
		/* ---------------------------------------------------------------------- */

		public function localize_script() {
			wp_localize_script('jquery', 'global', array(
				'template_directory' => MAD_BASE_URI,
				'site_url' => MAD_HOME_URL,
				'ajax_nonce' => wp_create_nonce('ajax-nonce'),
				'ajaxurl' => admin_url('admin-ajax.php'),
				'paththeme' => get_template_directory_uri(),
				'ajax_loader_url' => MAD_BASE_URI . 'images/ajax-loader@2x.gif'
			));
		}

		public function top_cookie_alert_localize() {
			wp_localize_script('jquery', 'cwmessageobj', array(
				'cwmessage' => __("Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.", MAD_BASE_TEXTDOMAIN),
				'cwagree' => __("Accept Cookies", MAD_BASE_TEXTDOMAIN),
				'cwmoreinfo' =>  sanitize_text_field(__("Read more...", MAD_BASE_TEXTDOMAIN)),
				'cwmoreinfohref' => is_ssl() ? "https://" : "http://" . "www.cookielaw.org/the-cookie-law"
			));
		}

		public function top_cookie_alert_body_prepend() {
			$cookie_alert_message = mad_custom_get_option('cookie_alert_message', __('Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.', MAD_BASE_TEXTDOMAIN));
			$cookie_alert_read_more_link = mad_custom_get_option('cookie_alert_read_more_link');
			?>
			<script type="text/javascript">
				(function ($) {

					var cwmessageobj = {
						'cwmessage' : "<?php _e("Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.", MAD_BASE_TEXTDOMAIN) ?>",
						'cwagree' : "<?php _e("Accept Cookies", MAD_BASE_TEXTDOMAIN) ?>",
						'cwmoreinfo' :  "<?php _e("Read more...", MAD_BASE_TEXTDOMAIN) ?>",
						'cwmoreinfohref' : "<?php echo (is_ssl()) ? "https://" : "http://" . "www.cookielaw.org/the-cookie-law" ?>"
					}

					$(function () {

						<?php if (!empty($cookie_alert_message)): ?>
							cwmessageobj['cwmessage'] = "<?php echo $cookie_alert_message; ?>";
						<?php endif; ?>

						<?php if (!empty($cookie_alert_read_more_link)): ?>
							cwmessageobj['cwmoreinfohref'] = "<?php echo $cookie_alert_read_more_link; ?>";
						<?php endif; ?>

						$('body').cwAllowCookies(cwmessageobj);

					});

				})(jQuery);
			</script>
		<?php
		}

		/* 	Get Cookie
		/* ---------------------------------------------------------------------- */

		public static function getcookie( $name ) {
			if ( isset( $_COOKIE[$name] ) )
				return maybe_unserialize( stripslashes( $_COOKIE[$name] ) );

			return array();
		}

		/* 	Load Textdomain
		/* ---------------------------------------------------------------------- */

		public static function load_textdomain () {
			load_theme_textdomain(MAD_BASE_TEXTDOMAIN, MAD_BASE_PATH  . 'lang');
		}

		/* 	Helpers enqueue style & script methods
		/* ---------------------------------------------------------------------- */

		public static function enqueue_style($style)   { wp_enqueue_style(MAD_PREFIX . $style);   }
		public static function enqueue_script($script) { wp_enqueue_script(MAD_PREFIX . $script); }

	}

	new MAD_BASE_FUNCTIONS();
}

/*  Include Config Widget Meta Box
/* ---------------------------------------------------------------------- */

require_once( 'config-widget-meta-box/config.php' );

/*  Include Config Composer
/* ---------------------------------------------------------------------- */

if (class_exists('Vc_Manager')) {
	require_once( 'config-composer/config.php' );
}

/*  Include Config DHVC Forms
/* ---------------------------------------------------------------------- */

if (defined('WPCF7_VERSION')) {
	require_once('config-contact-form-7/config.php');
}

/*  Include Config WooCommerce
/* ---------------------------------------------------------------------- */

if (class_exists('WooCommerce')) {

	if ( ! function_exists( 'mad_woo_config' ) ) {

		function mad_woo_config() {
			// Load required classes and functions
			require_once( 'config-woocommerce/config.php' );
			return MAD_WOOCOMMERCE_CONFIG::instance();
		}

	}

	/**
	 * Instance main plugin class
	 */
	global $mad_woo_config;
	$mad_woo_config = mad_woo_config();
}

/*  Include Config WPML
/* ---------------------------------------------------------------------- */

if (defined('ICL_SITEPRESS_VERSION') && defined('ICL_LANGUAGE_CODE')) {
	require_once( 'config-wpml/config.php' );
}

/*  Is shop installed
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_is_shop_installed' )) {
	function mad_is_shop_installed() {
		global $woocommerce;
		if ( isset( $woocommerce ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/*  Is product category
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'mad_is_product_category' ) ) {
	function mad_is_product_category( $term = '' ) {
		return is_tax( 'product_cat', $term );
	}
}

/*  Is product tag
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'mad_is_product_tag' ) ) {
	function mad_is_product_tag( $term = '' ) {
		return is_tax( 'product_tag', $term );
	}
}

/*  Get user name
/* ---------------------------------------------------------------------- */

if (!function_exists("mad_get_user_name")) {
	function mad_get_user_name($current_user) {

		if (!$current_user->user_firstname && !$current_user->user_lastname) {

			if (mad_is_shop_installed()) {

				$firstname_billing = get_user_meta($current_user->ID, "billing_first_name", true);
				$lastname_billing = get_user_meta($current_user->ID, "billing_last_name", true);

				if (!$firstname_billing && !$lastname_billing) {
					$user_name = $current_user->user_nicename;
				} else {
					$user_name = $firstname_billing . ' ' . $lastname_billing;
				}

			} else {
				$user_name = $current_user->user_nicename;
			}

		} else {
			$user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		}

		return $user_name;
	}
}

/*  @hooked Product class
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_product_class')) {
	function mad_product_class() {
		$classes = array();
		$classes = apply_filters( 'product_class', $classes );
		echo join( ' ', array_unique( $classes ) );
	}
}

/*  Generate Dynamic Styles
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_dynamic_styles')) {
	function mad_dynamic_styles() {
		require_once(MAD_FRAMEWORK::$path['frameworkPHP'] . 'register-dynamic-styles.php');
		mad_pre_dynamic_stylesheet();
	}
	add_action('init', 'mad_dynamic_styles', 15);
	add_action('admin_init', 'mad_dynamic_styles', 15);
}

if (!function_exists('mad_generate_styles')) {
	function mad_generate_styles() {
		$globalObject = $GLOBALS['mad_global_data'];
		$globalObject->reset_options();
		$prefix_name = sanitize_file_name($globalObject->theme_data['prefix']);

		mad_pre_dynamic_stylesheet();
		$generate_styles = new MAD_DYNAMIC_STYLES(false);
		$styles = $generate_styles->create_styles();

		$wp_upload_dir  = wp_upload_dir();
		$stylesheet_dynamic_dir = $wp_upload_dir['basedir'] . '/dynamic_mad_dir';
		$stylesheet_dynamic_dir = str_replace('\\', '/', $stylesheet_dynamic_dir);
		mad_backend_create_folder($stylesheet_dynamic_dir);

		$stylesheet = trailingslashit($stylesheet_dynamic_dir) . $prefix_name.'.css';
		$create = mad_write_to_file($stylesheet, $styles, true);

		if ($create === true) {
			update_option('exists_stylesheet' . $prefix_name, true);
			update_option('stylesheet_version' . $prefix_name, uniqid());
		}
	}
	add_action('mad_ajax_after_save_options_page', 'mad_generate_styles', 25);
	add_action('after_import_hook', 'mad_generate_styles', 28);
}