<?php

if( !function_exists( 'noo_cusomizer_upload_settings' ) ) :
function noo_cusomizer_upload_settings(){
	$file_name = $_FILES['noo-customizer-settings-upload']['name'];
	$file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);
	// $file_size = $_FILES['noo-customizer-settings-upload']['size'];
	if ( $file_ext == 'json' ) {
		$encode_options = file_get_contents( $_FILES['noo-customizer-settings-upload']['tmp_name'] );
		if( !empty($encode_options) ) {
			echo $encode_options;
			exit();
		}
	}

	exit('-1');
}

endif;

add_action( 'wp_ajax_noo_cusomizer_upload_settings', 'noo_cusomizer_upload_settings' );

if( !function_exists( 'noo_get_customizer_css_layout' ) ) :
function noo_get_customizer_css_layout(){
	check_ajax_referer('noo_customize_live_css', 'nonce');

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/layout.php' );

	$css = ob_get_contents(); ob_end_clean();

	// Remove comment
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );

	// Remove space
	$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );

	// Remove start space
	$css = preg_replace( '/\s\s+(.*)/', '$1', $css );

	echo $css;
	exit();
}

endif;

add_action( 'wp_ajax_noo_get_customizer_css_layout', 'noo_get_customizer_css_layout' );
add_action( 'wp_ajax_nopriv_noo_get_customizer_css_layout', 'noo_get_customizer_css_layout' );

if( !function_exists( 'noo_get_customizer_css_design' ) ) :
function noo_get_customizer_css_design(){
	check_ajax_referer('noo_customize_live_css', 'nonce');

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/design.php' );

	$css = ob_get_contents(); ob_end_clean();

	// Remove comment
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );

	// Remove space
	$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );

	// Remove start space
	$css = preg_replace( '/\s\s+(.*)/', '$1', $css );

	echo $css;
	exit();
}

endif;

add_action( 'wp_ajax_noo_get_customizer_css_design', 'noo_get_customizer_css_design' );
add_action( 'wp_ajax_nopriv_noo_get_customizer_css_design', 'noo_get_customizer_css_design' );

if( !function_exists( 'noo_get_customizer_css_typography' ) ) :
function noo_get_customizer_css_typography(){
	check_ajax_referer('noo_customize_live_css', 'nonce');

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/typography.php' );

	$css = ob_get_contents(); ob_end_clean();

	// Remove comment
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );

	// Remove space
	$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );

	// Remove start space
	$css = preg_replace( '/\s\s+(.*)/', '$1', $css );

	echo $css;
	exit();
}

endif;

add_action( 'wp_ajax_noo_get_customizer_css_typography', 'noo_get_customizer_css_typography' );
add_action( 'wp_ajax_nopriv_noo_get_customizer_css_typography', 'noo_get_customizer_css_typography' );

if( !function_exists( 'noo_get_customizer_css_header' ) ) :
function noo_get_customizer_css_header(){
	check_ajax_referer('noo_customize_live_css', 'nonce');

	ob_start();

	require_once( dirname( __FILE__ ) . '/css-php/header.php' );

	$css = ob_get_contents(); ob_end_clean();

	// Remove comment
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );

	// Remove space
	$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );

	// Remove start space
	$css = preg_replace( '/\s\s+(.*)/', '$1', $css );

	echo $css;
	exit();
}

endif;

add_action( 'wp_ajax_noo_get_customizer_css_header', 'noo_get_customizer_css_header' );
add_action( 'wp_ajax_nopriv_noo_get_customizer_css_header', 'noo_get_customizer_css_header' );

if( !function_exists( 'noo_ajax_get_attachment_url' ) ) :
function noo_ajax_get_attachment_url(){
	check_ajax_referer('noo_customize_attachment', 'nonce');

	if ( ! isset( $_POST['attachment_id'] ) ) {
		exit();
	}
				
	$attachment_id = $_POST['attachment_id'];

	echo wp_get_attachment_url( $attachment_id );
	exit();
}

endif;

add_action( 'wp_ajax_noo_ajax_get_attachment_url', 'noo_ajax_get_attachment_url' );
add_action( 'wp_ajax_nopriv_noo_ajax_get_attachment_url', 'noo_ajax_get_attachment_url' );

if( !function_exists( 'noo_ajax_get_menu' ) ) :
function noo_ajax_get_menu(){
	check_ajax_referer('noo_customize_menu', 'nonce');

	if ( ! isset( $_POST['menu_location'] ) ) {
		exit();
	}

	$menu_location = $_POST['menu_location'];
	?>
	<div class="topbar-content">
				
	<?php if ( has_nav_menu( $menu_location ) ) :
		wp_nav_menu( array(
			'theme_location'    => $menu_location,
			'container'         => false,
			'depth'				=> 1,
			'menu_class'        => 'noo-menu'
			) );
	else :
		echo '<ul class="noo-menu"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __( 'Assign a menu', 'noo' ) . '</a></li></ul>';
	endif; ?>

	</div>
	<?php
	exit();
}

endif;

add_action( 'wp_ajax_noo_ajax_get_menu', 'noo_ajax_get_menu' );
add_action( 'wp_ajax_nopriv_noo_ajax_get_menu', 'noo_ajax_get_menu' );

if( !function_exists( 'noo_ajax_get_social_icons' ) ) :
function noo_ajax_get_social_icons(){
	check_ajax_referer('noo_customize_social_icons', 'nonce');
	noo_social_icons();
	exit();
}

endif;

add_action( 'wp_ajax_noo_ajax_get_social_icons', 'noo_ajax_get_social_icons' );
add_action( 'wp_ajax_nopriv_noo_ajax_get_social_icons', 'noo_ajax_get_social_icons' );

