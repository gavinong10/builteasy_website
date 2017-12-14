<?php

ob_start();

function noo_customizer_export_theme_settings() {
	$blogname  = strtolower( str_replace( ' ', '-', get_option( 'blogname' ) ) );
	$file_name = $blogname . '-nootheme-' . date( 'Ydm' ) . '.json';
	$options   = get_theme_mods();

	unset( $options['nav_menu_locations'] );

	foreach ( $options as $key => $value ) {
		$value              = maybe_unserialize( $value );
		$need_options[$key] = $value;
	}

	$json_file = json_encode( $need_options );

	ob_clean();

	header( 'Content-Type: text/json; charset=' . get_option( 'blog_charset' ) );
	header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );

	echo $json_file;

	exit();
}