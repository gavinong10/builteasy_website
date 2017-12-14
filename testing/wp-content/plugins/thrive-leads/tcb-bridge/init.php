<?php
/**
 * this file is used only when there is no TCB plugin available
 *
 * it should handle the following:
 * - make sure the frontend scripts are included when in editing mode and frontend
 * - add 'post', 'page' to the blacklist for editable post type (so that users cannot edit those types of posts)
 * - however, the user must still be able to edit a tcb_lightbox
 * - surpass the license check for the TCB plugin (there is a filter in WP called pre_option_{option_name}), where option_name would be tve_license_status
 * - tve_load_custom_css - load custom CSS for lightboxes and regular forms - in frontend
 */

/* include autoresponder files for one click signup (new name: Signup Segue) */
add_filter( 'tve_leads_include_auto_responder', 'tve_leads_include_auto_responder_file' );

if ( ! defined( 'TVE_TCB_CORE_INCLUDED' ) ) {
	require_once dirname( dirname( __FILE__ ) ) . '/tcb/plugin-core.php';
}

/* short-circuit the tve_license_check notice by always returning true */
add_filter( 'pre_option_tve_license_status', '__return_true' );

/* force only tve_form_type and tcb_lightbox to be editable with TCB */
add_filter( 'tcb_post_types', 'tve_leads_disable_edit', 5 );

/* enqueue scripts for the frontend - used only in editing and preview modes */
add_action( 'wp_enqueue_scripts', 'tve_leads_frontend_enqueue_scripts' );

add_filter( 'tve_filter_plugin_languages_path', 'tve_leads_filter_tcb_language_path' );

function tve_leads_filter_tcb_language_path( $path ) {
	$path = 'thrive-leads/tcb/languages/';

	return $path;
}

if ( ! function_exists( 'tve_editor_url' ) ) {
	/**
	 * we need override the base path here
	 */
	function tve_editor_url() {
		return plugin_dir_url( dirname( __FILE__ ) ) . 'tcb';
	}
}

/**
 * posts and pages must not be editable
 *
 * @param array $post_types
 *
 * @return array
 */
function tve_leads_disable_edit( $post_types ) {
	$post_types['force_whitelist'] = isset( $post_types['force_whitelist'] ) ? $post_types['force_whitelist'] : array();
	$post_types['force_whitelist'] = array_merge( $post_types['force_whitelist'], array(
		'tcb_lightbox',
		'tve_lead_2s_lightbox',
		TVE_LEADS_POST_FORM_TYPE,
		TVE_LEADS_POST_SHORTCODE_TYPE
	) ); // only allow these types of posts to be editable with TCB

	return $post_types;
}

/**
 * enqueue scripts for the frontend - used only in editing and preview modes
 *
 * for the rest of the pages (where the forms are actually displayed), we need to include these from the point where we detect that a form will be displayed
 */
function tve_leads_frontend_enqueue_scripts() {
	$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';
	/* check if we are in one of: editor page / preview page of tcb_lightbox or tve_form_type */
	if ( ! is_singular( array( TVE_LEADS_POST_FORM_TYPE, 'tcb_lightbox', TVE_LEADS_POST_SHORTCODE_TYPE, TVE_LEADS_POST_TWO_STEP_LIGHTBOX ) ) ) {
		return false;
	}

	if ( tve_get_post_meta( get_the_ID(), 'tve_has_masonry' ) ) {
		wp_enqueue_script( "jquery-masonry", array( 'jquery' ) );
	}
	if ( tve_get_post_meta( get_the_ID(), 'tve_has_typefocus' ) ) {
		tve_enqueue_script( 'tve_typed', tve_editor_js() . '/typed.min.js', array(), false, true );
	}

	tve_enqueue_style_family();

	tve_enqueue_script( "tve_frontend", tve_editor_js() . '/thrive_content_builder_frontend' . $js_suffix, array( 'jquery' ), false, true );

	if ( ! is_editor_page() && is_singular() ) {
		$events = tve_get_post_meta( get_the_ID(), 'tve_page_events' );
		if ( ! empty( $events ) ) {
			tve_page_events( $events );
		}
	}
	
	if ( is_editor_page() ) {
		tve_enqueue_script( 'jquery-zclip', plugins_url( 'thrive-leads/js/jquery.zclip.1.1.1/jquery.zclip.js' ), array(
			'jquery'
		) );
	}

	/* params for the frontend script */
	$frontend_options = array(
		'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		'is_editor_page' => true,
		'page_events'    => isset( $events ) ? $events : array(),
		'is_single'      => (string) ( (int) is_singular() ),
		'dash_url'         => TVE_DASH_URL,
		'translations'     => array(
			'Copy' => __( 'Copy', 'thrive-leads' ),
		),
	);
	// hide tve more tag from front end display
	if ( ! is_editor_page() ) {
		tve_load_custom_css();
		tve_hide_more_tag();
		/* this will enqueue custom fonts for lightboxes */
		tve_enqueue_custom_fonts();
		$frontend_options['is_editor_page'] = false;
	}
	wp_localize_script( 'tve_frontend', 'tve_frontend_options', $frontend_options );
}
