<?php

function aviators_admin_login_head() {
	if ( aviators_settings_get_value( 'admin', 'login_screen', 'default' ) != 'on' ) {
		echo "
        <style>
        body.login #login h1 a {
            background: url('" . get_template_directory_uri() . "/aviators/core/plugins/admin/assets/img/admin.png') no-repeat scroll center top transparent;
            height: 241px;
	    width: auto;
        }
        </style>";
	}
	if ( aviators_settings_get_value( 'admin', 'login_screen', 'image' ) ) {
		echo "
        <style>
        body.login #login h1 a {
            background: url('" . aviators_settings_get_value( 'admin', 'login_screen', 'image' ) . "') no-repeat scroll center top transparent;
            height: 241px;
        }
        </style>";
	}
}

add_action( 'login_head', 'aviators_admin_login_head' );


function aviators_admin_load_styles() {
	if ( is_admin() ) {
		wp_register_style( 'admin-css', get_template_directory_uri() . '/aviators/core/plugins/admin/assets/css/admin.css' );
		wp_enqueue_style( 'admin-css' );
	}
}

add_action( 'admin_head', 'aviators_admin_load_styles' );


function aviators_admin_add_admin_menu_separator( $position ) {
	global $menu;
	$index = 0;

	foreach ( $menu as $offset => $section ) {
		if ( substr( $section[2], 0, 9 ) == 'separator' )
			$index ++;
		if ( $offset >= $position ) {
			$menu[$position] = array( '', 'read', "separator{$index}", '', 'wp-menu-separator' );
			break;
		}
	}

	ksort( $menu );
}

add_action( 'admin_menu', 'aviators_admin_admin_menu_separator' );

function aviators_admin_admin_menu_separator() {
	aviators_admin_add_admin_menu_separator( 30 );
	aviators_admin_add_admin_menu_separator( 50 );
}
