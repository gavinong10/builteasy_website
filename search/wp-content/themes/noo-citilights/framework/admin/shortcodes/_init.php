<?php
/**
 * NOO Shortcodes packages
 *
 * Initialize Admin funciton for NOO Shortcodes
 * This file initialize a button on the WP editor that enable NOO Shortcodes input.
 *
 * @package    NOO Framework
 * @subpackage NOO Shortcodes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Add Admin Shortcode Button
add_action( 'init', 'noo_shortcodes_button_init' );

function noo_shortcodes_button_init() {
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'noo_shortcodes_button' );
		add_filter( 'mce_buttons', 'noo_shortcodes_button_register' );
	}  
}

function noo_shortcodes_button( $plugin_array ) {
	if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
		$tinymce_js = NOO_FRAMEWORK_ADMIN_URI .'/assets/js/noo_shortcodes.tinymce.js';
	} else {
		$tinymce_js = NOO_FRAMEWORK_ADMIN_URI .'/assets/js/noo_shortcodes.tinymce3.js';
	}
	$plugin_array['noo_shortcodes'] = $tinymce_js;
	return $plugin_array;
}

function noo_shortcodes_button_register ( $buttons ) {
	array_push( $buttons, 'noo_shortcodes_mce_button' );
	return $buttons;
}

/** 
 * Localize Data
 */
function noo_shorcodes_data() {
	$data = array(
			'url' => NOO_FRAMEWORK_ADMIN_URI . '/shortcodes',
			'contact_form_7' => ( class_exists( 'WPCF7_ContactForm' ) ? 'true' : 'false' ),
			'rev_slider' => ( class_exists( 'RevSlider' ) ? 'true' : 'false' ),
		);

	return $data;
}

/** 
 * Localize String
 */
function noo_shorcodes_language_string() {
	$string = array(
			'base_element' => __( 'Base Element', 'noo'),
			'row' => __( 'Row', 'noo'),
			'column' => __( 'Column', 'noo'),
			'animation' => __( 'Animation Block', 'noo'),
			'separator' => __( 'Separator', 'noo'),
			'gap' => __( 'Gap', 'noo'),
			'clear' => __( 'Clear', 'noo'),

			'typography' => __( 'Typography', 'noo'),
			'textblock' => __( 'Text Block', 'noo'),
			'button' => __( 'Button', 'noo'),
			'headline' => __( 'Headline', 'noo'),
			'dropcap' => __( 'Dropcap', 'noo'),
			'quote' => __( 'Quote', 'noo'),
			'icon' => __( 'Icon', 'noo'),
			'social_icon' => __( 'Social Icon', 'noo'),
			'icon_list' => __( 'Icon List', 'noo'),
			'icon_list_item' => __( 'Icon List Item', 'noo'),
			'label' => __( 'Label', 'noo'),
			'code_block' => __( 'Code Block', 'noo'),

			'content' => __( 'Content', 'noo'),
			'accordion' => __( 'Accordion', 'noo'),
			'tabs' => __( 'Tabs', 'noo'),
			'tour_section' => __( 'Tour Section', 'noo'),
			'block_grid' => __( 'Block Grid', 'noo'),
			'progress_bar' => __( 'Progress Bar', 'noo'),
			'pricing_table' => __( 'Pricing Table', 'noo'),
			'pricing_table_column' => __( 'Pricing Table Column', 'noo'),
			'pie' => __( 'Pie Chart', 'noo'),
			'cta_button' => __( 'Call to Action', 'noo'),
			'counter' => __( 'Counter', 'noo'),
			'message' => __( 'Message Box (Alert)', 'noo'),

			'citilights' => __( 'Citilights', 'noo'),
			'recent_properties' => __( 'Recent Properties', 'noo'),
			'single_property' => __( 'Single Property', 'noo'),
			'advanced_search_property' => __( 'Property Map & Search', 'noo'),
			'recent_agents' => __( 'Recent Agents', 'noo'),
			'membership_packages' => __( 'Membership Packages (pricing table)', 'noo'),
			'login_register' => __( 'Login/Register', 'noo'),
			'property_slider' => __( 'Property Slider', 'noo'),
			'property_slide' => __( 'Property Slide Item', 'noo'),

			'wordpress_content' => __( 'WordPress Content', 'noo'),
			'widget_area' => __( 'Widget Area', 'noo'),
			'blog' => __( 'Post List', 'noo'),
			'author' => __( 'Author', 'noo'),
			'team_member' => __( 'Team Member', 'noo'),
			'contact_form_7' => __( 'Contact Form 7', 'noo'),
			// 'gravity_form' => __( 'Gravity Form', 'noo'),
			'protected_content' => __( 'Protected Content', 'noo'),
			'search_field' => __( 'Search Field', 'noo'),

			'media' => __( 'Media', 'noo'),
			'image' => __( 'Image', 'noo'),
			'rev_slider' => __( 'Slider Revolution', 'noo'),
			'slider' => __( 'Responsive Slider', 'noo'),
			'slide' => __( 'Slide (Responsive Slider item)', 'noo'),
			'lightbox' => __( 'Lightbox', 'noo'),
			'video_player' => __( 'Video (self hosted)', 'noo'),
			'video_embed' => __( 'Video Embed', 'noo'),
			'audio_player' => __( 'Audio (Self Hosted)', 'noo'),
			'audio_embed' => __( 'Audio Embed', 'noo'),
			'google_map' => __( 'Google Map', 'noo'),
			'social_share' => __( 'Social Sharing', 'noo'),

			'custom' => __( 'Custom', 'noo'),
			'raw_html' => __( 'Raw HTML', 'noo'),
			'raw_js' => __( 'Raw Javascript', 'noo'),

			'title' => __( 'Title', 'noo'),
			'size' => __( 'Size (px)', 'noo'),
		);

	return $string;
}

// Enqueue style for shortcodes admin
if ( ! function_exists( 'noo_enqueue_shortcodes_admin_assets' ) ) :
	function noo_enqueue_shortcodes_admin_assets( $hook ) {
		
// 		if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
// 			return;
// 		}

		// Main style
		wp_register_style( 'noo-shortcodes-admin-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-shortcodes-admin.css', array( 'wp-color-picker', 'vendor-font-awesome-css', 'noo-icon-bootstrap-modal-css', 'noo-jquery-ui-slider' ));
		wp_enqueue_style( 'noo-shortcodes-admin-css' );

		// Main script
		wp_register_script( 'noo-shortcodes-admin-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-shortcodes-admin.js', array( 'jquery-ui-slider', 'wp-color-picker', 'noo-font-awesome-js' ), null, true );
		wp_localize_script( 'noo-shortcodes-admin-js', 'noo_shortcodes_data', noo_shorcodes_data() );
		wp_localize_script( 'noo-shortcodes-admin-js', 'noo_shortcodes_str', noo_shorcodes_language_string() );
		wp_enqueue_script( 'noo-shortcodes-admin-js' );
	}
	add_action( 'admin_enqueue_scripts', 'noo_enqueue_shortcodes_admin_assets' );
endif;


