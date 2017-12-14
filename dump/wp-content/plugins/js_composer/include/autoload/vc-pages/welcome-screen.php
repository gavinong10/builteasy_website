<?php
// The List of welcome pages tabs.
global $vc_page_welcome_tabs;
/**
 * Get welcome pages main slug.
 *
 * @since 4.5
 * @return mixed|string
 */
function vc_page_welcome_slug() {
	global $vc_page_welcome_tabs;

	return isset( $vc_page_welcome_tabs ) ? key( $vc_page_welcome_tabs ) : '';
}

/**
 * Build vc-welcome page block which will be shown after Vc installation.
 *
 * vc_filter: vc_page_welcome_render_capabilities
 *
 * @since 4.5
 */
function vc_page_welcome_render() {
	global $vc_page_welcome_tabs;
	$slug = vc_page_welcome_slug();
	$tab_slug = vc_get_param( 'tab', $slug );
	// If tab slug in the list please render;
	if ( ! empty( $tab_slug ) && isset( $vc_page_welcome_tabs[ $tab_slug ] ) ) {
		$pages_group = vc_pages_group_build( $slug, $vc_page_welcome_tabs[ $tab_slug ], $tab_slug );
		$pages_group->render();
	}
}

function vc_page_welcome_add_sub_page() {
	// Add submenu page
	$page = add_submenu_page( VC_PAGE_MAIN_SLUG,
		__( 'About', 'js_composer' ),
		__( 'About', 'js_composer' ),
		'manage_options',
		vc_page_welcome_slug(), 'vc_page_welcome_render' );
	// Css for perfect styling.
	add_action( 'admin_print_styles-' . $page, 'vc_page_css_enqueue' );

}

add_action( 'vc_menu_page_build', 'vc_page_welcome_add_sub_page', 11 );
add_action( 'vc_network_menu_page_build', 'vc_page_welcome_add_sub_page', 1 );


/**
 * ====================
 * Redirect to welcome page on plugin activation.
 * ====================
 */

/**
 * Set redirect transition on update or activation
 * @since 4.5
 */
function vc_page_welcome_set_redirect() {
	if ( ! is_network_admin() && ! vc_get_param( 'activate-multi' ) ) {
		set_transient( '_vc_page_welcome_redirect', 1, 30 );
	}
}

/**
 * Do redirect if required on welcome page
 * @since 4.5
 */
function vc_page_welcome_redirect() {
	$redirect = get_transient( '_vc_page_welcome_redirect' );
	delete_transient( '_vc_page_welcome_redirect' );
	$redirect && wp_redirect( admin_url( 'admin.php?page=' . rawurlencode( vc_page_welcome_slug() ) ) );
}

// Enables redirect on activation.
add_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
add_action( 'init', 'vc_page_welcome_redirect' );

/**
 * ===============================
 * Main list of Welcome page tabs.
 * ===============================
 */
$vc_page_welcome_tabs = apply_filters( 'vc_page-welcome-slugs-list',
	array(
		'vc-welcome' => __( 'What\'s New', 'js_composer' ),
		'vc-faq' => __( 'FAQ', 'js_composer' ),
		'vc-resources' => __( 'Resources', 'js_composer' ),
	) );

