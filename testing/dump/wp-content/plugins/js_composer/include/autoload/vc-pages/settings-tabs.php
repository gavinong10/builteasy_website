<?php
function vc_page_settings_render() {
	$page = vc_get_param( 'page' );
	do_action( 'vc_page_settings_render-' . $page );
	vc_settings()->renderTab( $page );
}

function vc_page_settings_build() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$tabs = vc_settings()->getTabs();
	foreach ( $tabs as $slug => $title ) {
		$page = add_submenu_page( VC_PAGE_MAIN_SLUG, $title, $title, 'manage_options', $slug, 'vc_page_settings_render' );
		add_action( "load-" . $page, array(
			vc_settings(),
			'adminLoad'
		) );
	}
	do_action( 'vc_page_settings_build' );
}

function vc_page_settings_admin_init() {
	vc_settings()->initAdmin();
}

add_action( 'vc_menu_page_build', 'vc_page_settings_build' );
add_action( 'vc_network_menu_page_build', 'vc_page_settings_build' );
add_action( 'admin_init', 'vc_page_settings_admin_init' );


