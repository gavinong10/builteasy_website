<?php

defined( 'TVE_TCB_DB_UPGRADE' ) or exit();

$icon_pack = get_option( 'thrive_icon_pack' );

if ( ! empty( $icon_pack ) && ! empty( $icon_pack['folder'] ) ) {
	$css_file = trailingslashit( $icon_pack['folder'] ) . basename( $icon_pack['css'] );

	$old_umask = umask( 0 );

	if ( is_file( $css_file ) && is_writable( $css_file ) ) {
		$file_contents = file_get_contents( $css_file );
		$font_family   = $icon_pack['fontFamily'];

		$file_contents = str_replace( "font-family: '{$font_family}' !important;", "font-family: '{$font_family}';", $file_contents );

		$search      = "font-family: '{$font_family}';";
		$replacement = "font-family: '{$font_family}' !important;";

		$position_found = strpos( $file_contents, $search, strpos( $file_contents, 'url' ) );
		if ( $position_found ) {
			$file_contents = substr_replace( $file_contents, $replacement, $position_found, strlen( $search ) );
		}

		if ( file_put_contents( $css_file, $file_contents ) ) {
			$icon_pack['css_version'] = rand( 1, 9 ) . '.' . str_pad( rand( 1, 99 ), 2, '0', STR_PAD_LEFT );
			update_option( 'thrive_icon_pack', $icon_pack );
		}
	}
}