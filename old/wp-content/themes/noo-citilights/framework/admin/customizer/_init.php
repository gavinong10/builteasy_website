<?php
/**
 * NOO Customizer Package
 *
 * Initialize NOO Customizer
 * This file set up NOO Customizer menu as well as including martial needed by Customizer.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// 0. Define constance
if ( !defined( 'NOO_CUSTOMIZER_PATH' ) ) {
	define( 'NOO_CUSTOMIZER_PATH', NOO_FRAMEWORK_ADMIN . '/customizer' );
}

if ( !defined( 'NOO_CUSTOMIZER_DATA_FILE' ) ) {
	define( 'NOO_CUSTOMIZER_DATA_FILE', NOO_CUSTOMIZER_PATH . '/options.json' );
}

// 1. Init NOO-Customizer
// 1.1 Remove WP Theme Customize Submenu
if ( floatval( get_bloginfo( 'version' ) ) >= 3.6 ) {
	function noo_remove_wp_customize_submenu() {
		// remove_submenu_page( 'themes.php', 'customize.php' );
		global $submenu;
        unset($submenu['themes.php'][6]); // Work on WP 4.0
	}

	add_action( 'admin_menu', 'noo_remove_wp_customize_submenu', 999 );
}

// 1.2. Add NOO-Customizer Menu
function noo_add_customizer_menu() {
	$customizer_icon = NOO_FRAMEWORK_ADMIN_URI . '/assets/images/noo20x20.png';
	// if ( floatval( get_bloginfo( 'version' ) ) >= 3.8 ) {
	//	$customizer_icon = 'dashicons-welcome-view-site';
	// }

	add_menu_page( __( 'Customizer', 'noo' ), __( 'Customizer', 'noo' ), 'edit_theme_options', 'customize.php', null, $customizer_icon, 61 );
	add_submenu_page( 'options.php', '', '', 'edit_theme_options', 'export_settings', 'noo_customizer_export_theme_settings' );
}

add_action( 'admin_menu', 'noo_add_customizer_menu' );



// 2. Include materials
// 2.1 Include framework materials
require_once NOO_CUSTOMIZER_PATH . '/class-helper.php';
require_once NOO_CUSTOMIZER_PATH . '/custom_controls.php';
require_once NOO_CUSTOMIZER_PATH . '/options.php';
require_once NOO_CUSTOMIZER_PATH . '/live-css.php';
require_once NOO_CUSTOMIZER_PATH . '/live-ajax.php';


// 3. Generating Live CSS & JS
// 3.1. Generating Custom CSS
function noo_theme_option_custom_css() {
	if ( noo_get_option( 'noo_custom_css', '' ) ) :
?>
    <style id="noo-custom-css" type="text/css"><?php echo noo_get_option( 'noo_custom_css', '' ); ?></style>
  <?php
	endif;
}

add_action( 'wp_head', 'noo_theme_option_custom_css', 9999, 0 );

// 3.2. Generating Custom JS
function noo_theme_option_custom_js() {
	if ( noo_get_option( 'noo_custom_javascript', '' ) ) :
?>
	<script>
		<?php echo noo_get_option( 'noo_custom_javascript', '' ); ?>
	</script>
	<?php
	endif;
}

add_action( 'wp_footer', 'noo_theme_option_custom_js', 999, 0 );


// 4. Enqueue script for NOO Customizer
// 4.1 Customizer Controls
// 4.1.1 Localize String
if ( ! function_exists( 'noo_customizer_controls_l10n' ) ) :
	function noo_customizer_controls_l10n() {
		return array(
			'navbar_height' => __( 'NavBar Height (px)', 'noo'),
			'mobile_navbar_height' => __( 'Mobile NavBar Height (px)', 'noo'),
			'ajax_update_msg'    => __( 'Updating ...', 'noo' ),
			'import_error_msg' => __( 'Error when parsing your file.', 'noo' ),
			'export_preparing_msg' => __( 'We are preparing your export file, please wait...', 'noo' ),
			'export_fail_msg' => __( 'There was a problem generating your export file, please try again.', 'noo' ),
			'export_url'  => admin_url( 'options.php?page=export_settings' ),
			'ajax_url'    => admin_url( 'admin-ajax.php', 'relative' )
		);
	}
endif;

// 4.1.2 Enqueue script for Customizer Controls
if ( ! function_exists( 'noo_enqueue_customizer_controls_js' ) ) :
	function noo_enqueue_customizer_controls_js() {
		wp_enqueue_media();

		wp_register_script( 'noo-customizer-controls-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-customizer-controls.js', array( 'jquery', 'vendor-chosen-js', 'vendor-alertify-js', 'vendor-fileDownload-js' ), null, true );
		wp_localize_script( 'noo-customizer-controls-js', 'nooCustomizerL10n', noo_customizer_controls_l10n() );
		wp_enqueue_script( 'noo-customizer-controls-js' );

		wp_print_media_templates();

		?>
		<?php

	}
endif;
add_action( 'customize_controls_print_footer_scripts', 'noo_enqueue_customizer_controls_js' );

// 4.2 Enqueue script for Customizer Live Preview
// 4.1.1 Customizer Live Data
if ( ! function_exists( 'noo_customizer_live_data' ) ) :
	function noo_customizer_live_data() {
		$blog_page             = ( get_option( 'show_on_front' ) == 'page' ) ? get_permalink( get_option('page_for_posts' ) ) : home_url();
		$shop_page             = ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ? get_permalink( woocommerce_get_page_id( 'shop' ) ) : '';
		$query_args =  array(
			'orderby' => 'name',
			'order' => 'ASC',
		);
		$category_terms  = get_terms('category', $query_args);
		$archive_page    = !empty( $category_terms ) ? reset($category_terms) : '';
		$archive_page    = !empty( $category_terms ) ? get_term_link( $archive_page->term_id ) : $blog_page;
		$post            = get_posts( array('posts_per_page' => 1 ) );
		$post_page       = !empty( $post ) ? get_permalink( $post[0]->ID ) : $blog_page;
		$agents_page     = get_post_type_archive_link( 'noo_agent' );
		$properties_page = get_post_type_archive_link( 'noo_property' );
		$property        = get_posts( array('posts_per_page' => 1, 'post_type' => 'noo_property' ) );
		$property_page   = !empty( $property ) ? get_permalink( $property[0]->ID ) : $properties_page;
		$product         = get_posts( array('posts_per_page' => 1, 'post_type' => 'product' ) );
		$product_page   = !empty( $product ) ? get_permalink( $product[0]->ID ) : $shop_page;

		wp_reset_query();

		return array(
			'is_preview'			=> 'true',
			'customize_live_css'	=> wp_create_nonce('noo_customize_live_css'),
			'customize_attachment'	=> wp_create_nonce('noo_customize_attachment'),
			'customize_menu'		=> wp_create_nonce('noo_customize_menu'),
			'customize_social_icons'=> wp_create_nonce('noo_customize_social_icons'),
			'blog_page'				=> $blog_page,
			'shop_page'				=> $shop_page,
			'archive_page'			=> $archive_page,
			'post_page'				=> $post_page,
			'product_page'			=> $product_page,
			'agents_page'			=> $agents_page,
			'properties_page'		=> $properties_page,
			'property_page'			=> $property_page,
			'ok'					=> __( 'Yes', 'noo' ),
			'cancel'				=> __( 'No', 'noo' ),
			'ajax_update_msg'		=> __( 'Updating ...', 'noo' ),
			'cannot_preview_msg'	=> __( 'This option doesn\'t support live preview. Save it and see the change on your site.', 'noo' ),
			'redirect_msg'			=> __( 'Wanna go to %s to see the change?', 'noo' ),
			'blog_page_txt'			=> __( 'Blog Page', 'noo' ),
			'shop_text'				=> __( 'Shop Page', 'noo' ),
			'archive_page_txt'		=> __( 'An Archive Page', 'noo' ),
			'post_page_txt'			=> __( 'A Post', 'noo' ),
			'agents_page_txt'		=> __( 'Agents Archive Page', 'noo' ),
			'properties_page_txt'	=> __( 'Properties Archive Page', 'noo' ),
			'property_page_txt'		=> __( 'Property Detail Page', 'noo' ),
		);
	}
endif;

// 4.2.2 Enqueue script for Customizer Live
if ( ! function_exists( 'noo_enqueue_customizer_live_js' ) ) :
	function noo_enqueue_customizer_live_js() {
		// Script
		wp_register_script( 'vendor-alertify-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/alertify.mod.min.js', null, null, true );
		wp_register_script( 'noo-customizer-live-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-customizer-live.js', array( 'jquery', 'vendor-alertify-js' ), null, true );
		wp_localize_script( 'noo-customizer-live-js', 'nooCustomizerL10n', noo_customizer_live_data() );
		wp_enqueue_script( 'noo-customizer-live-js' );

		// Style
		wp_register_style( 'noo-customizer-live-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-customizer-live.css', array( 'jquery' ), null, true );
		wp_enqueue_style( 'noo-customizer-live-css' );

		wp_register_style( 'vendor-alertify-core-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/alertify.core.css', null, null, 'all' );
		wp_register_style( 'vendor-alertify-default-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/alertify.default.css', array('vendor-alertify-core-css'), null, 'all' );
		wp_enqueue_style( 'vendor-alertify-default-css' );
	}
endif;
add_action( 'customize_preview_init', 'noo_enqueue_customizer_live_js' );


// 5. Enqueue style for NOO Customizer
// 5.1 Enqueue style for Customizer Controls
if ( ! function_exists( 'noo_enqueue_customizer_controls_css' ) ) :
	function noo_enqueue_customizer_controls_css() {

		wp_register_style( 'noo-customizer-controls-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-customizer-control.css', array( 'noo-jquery-ui-slider', 'vendor-chosen-css' ), null, 'all' );
		wp_enqueue_style( 'noo-customizer-controls-css' );

		wp_enqueue_style( 'vendor-alertify-default-css' );

	}
endif;
add_action( 'customize_controls_print_styles', 'noo_enqueue_customizer_controls_css' );

// 6. Import/Export functions
// 6.1 Import

// 6.2 Export
require_once NOO_CUSTOMIZER_PATH . '/export-settings.php';

// 7. Generate CSS file
if ( ! function_exists( 'noo_output_css_file' ) ) :
	function noo_output_css_file($creds) {
		ob_start();

		require_once( dirname( __FILE__ ) . '/css-php/layout.php' );
		require_once( dirname( __FILE__ ) . '/css-php/design.php' );
		require_once( dirname( __FILE__ ) . '/css-php/typography.php' );
		require_once( dirname( __FILE__ ) . '/css-php/header.php' );

		$css = ob_get_clean();

		if( !defined('WP_DEBUG') || !WP_DEBUG ) {
			// Remove comment, space
			$css = preg_replace( '#/\*.*?\*/#s', '', $css );
			$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );
			$css = preg_replace( '/\s\s+(.*)/', '$1', $css );
		}

		$css = "/* This custom.css file is automatically generated each time admin update Customize settings.\nTherefore, please DO NOT CHANGE ANYTHING as your changes will be lost.\n@NooTheme */" . $css;

		// file_put_contents($css_dir . 'custom.css', $css, LOCK_EX); // Save it

		$creds = get_theme_mod('noo_customizer_credits', '');
		WP_Filesystem( $creds );
		global $wp_filesystem; 
		
		$css_dir = noo_create_upload_dir( $wp_filesystem );
		if ( ! $css_dir || ! $wp_filesystem->put_contents(  $css_dir . '/custom.css', $css, FS_CHMOD_FILE) ) {

			// store option for using inline css
			set_theme_mod('noo_use_inline_css', true);

			wp_die("error saving file!", '', array( 'response' => 403 ));
		} else {
			set_theme_mod('noo_use_inline_css', false);
		}
	}
endif;

add_action( 'customize_save_after', 'noo_output_css_file' );

if ( ! function_exists( 'noo_delete_stored_credits' ) ) :
	function noo_delete_stored_credits() {
		remove_theme_mod( 'noo_customizer_credits' );
	}
endif;

add_action('wp_login','noo_delete_stored_credits' );

if ( ! function_exists( 'noo_jbst_tmpadminheader' ) ) :
	function noo_jbst_tmpadminheader() {
		/**
		 * Dashboard Administration Screen
		 *
		 * @package WordPress
		 * @subpackage Administration
		 */

		/** Load WordPress Bootstrap */
		require_once(ABSPATH . 'wp-admin/admin.php' );

		/** Load WordPress dashboard API */
		require_once(ABSPATH . 'wp-admin/includes/dashboard.php');

		wp_dashboard_setup();

		wp_enqueue_script( 'dashboard' );
		if ( current_user_can( 'edit_theme_options' ) )
			wp_enqueue_script( 'customize-loader' );
		if ( current_user_can( 'install_plugins' ) )
			wp_enqueue_script( 'plugin-install' );
		if ( current_user_can( 'upload_files' ) )
			wp_enqueue_script( 'media-upload' );
		add_thickbox();

		if ( wp_is_mobile() )
			wp_enqueue_script( 'jquery-touch-punch' );

		$title = __('Customizer credentials', 'noo');
		$parent_file = 'index.php';
		include( ABSPATH . 'wp-admin/admin-header.php' );
	}
endif;

if ( ! function_exists( 'noo_store_credits' ) ) :
	function noo_store_credits( $wp_customize ) {
		if(! WP_Filesystem(unserialize(get_theme_mod('noo_customizer_credits')))) {
			ob_start();	
			$in = true;
			$url = 'customize.php';
			if (false === ($creds = request_filesystem_credentials($url, '', false, false,null) ) ) {
				$in = false;

				$form = ob_get_contents();
				ob_end_clean();
				noo_jbst_tmpadminheader();
				echo $form;
				require( ABSPATH . 'wp-admin/admin-footer.php' );
				exit;
			}
			ob_end_clean();
			if ($in && ! WP_Filesystem($creds) ) {
			
				// our credentials were no good, ask the user for theme again
				noo_jbst_tmpadminheader();
				request_filesystem_credentials($url, '', true, false,null);
				require( ABSPATH . 'wp-admin/admin-footer.php' );
				$in = false;
				exit;
			}

			set_theme_mod('noo_customizer_credits', serialize($creds));
		}
	}
endif;

add_action('customize_controls_init', 'noo_store_credits', 1);
