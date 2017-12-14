<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

	add_action( 'init', 'mmm_enqueue_styles', 20, 5 );
	add_action( 'wp_enqueue_scripts', 'mmm_enqueue_styles' );

	function mmm_enqueue_styles( ) {
		// remove later
		global $mega_main_menu;

		if ( function_exists( 'is_multisite' ) && is_multisite() ){
			$cache_file_name = 'cache.skin.b' . get_current_blog_id();
		} else {
			$cache_file_name = 'cache.skin';
		}
		/* check cache or dynamic file enqueue */
		$options_last_modified = $mega_main_menu->get_option( 'last_modified' );

//		if( file_exists( $mega_main_menu->constant[ 'MM_WARE_CSS_DIR' ] . '/' . $cache_file_name . '.css' ) ) {
//			$cache_status[] = 'exist';
//			if ( $options_last_modified > filemtime( $mega_main_menu->constant[ 'MM_WARE_CSS_DIR' ] . '/' . $cache_file_name . '.css' ) ) {
//				$cache_status[] = 'old';
//			} else {
//				$cache_status[] = 'actual';
//			}
//		} else {
			$cache_status[] = 'no-exist';
//		};

/*
		$cache_status[] = 'no-exist';
*/


		if ( in_array( 'actual', $cache_status ) ) {
			$skin_css[] = array( 'name' => $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_mega_main_menu', 'path' => $mega_main_menu->constant[ 'MM_WARE_CSS_URL' ] . '/' . $cache_file_name . '.css' );
		} else {

			$static_css = mad_mm_common::get_url_content( $mega_main_menu->constant[ 'MM_WARE_CSS_DIR' ] . '/frontend/mega_main_menu.css' );

			if ( ( $static_css !== false ) && ( $cache_file = @fopen( $mega_main_menu->constant[ 'MM_WARE_CSS_DIR' ] . '/' . $cache_file_name . '.css', 'w' ) ) ) {
				$out = '';
				/* google fonts */
//				if ( $set_of_google_fonts = $mega_main_menu->get_option( 'set_of_google_fonts' ) ) {
//					if ( count( $set_of_google_fonts ) > 0 ) {
//						$out .= '/* google fonts */';
//						foreach ( $set_of_google_fonts as $key => $value ) {
//							$additional_font = '@import url(https://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $value['family'] ) . ':400italic,600italic,300,400,600,700,800&subset=latin,latin-ext,cyrillic,cyrillic-ext);';
//							$out .= $additional_font;
//						}
//					}
//				}

				$out .= ''; /* mmm_get_skin() */
				$out = str_replace( array( "\t", "
", "\n", "  ", ), array( "", "", " ", " ", ), $out );
				if ( @fwrite( $cache_file, $out ) ) {
					$skin_css = array( array( 'name' => $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $cache_file_name, 'path' => $mega_main_menu->constant[ 'MM_WARE_CSS_URL' ] . '/' . $cache_file_name . '.css' ) );
					@touch( $mega_main_menu->constant[ 'MM_WARE_CSS_DIR' ] . '/' . $cache_file_name . '.css', time(), time() );
				}
			} else {
				$skin_css[] = array( 'name' => $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_common_styles', 'path' => $mega_main_menu->constant[ 'MM_WARE_CSS_URL' ] . '/frontend/mega_main_menu.css' );
				$skin_css[] = array( 'name' => $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_dynamic.skin', 'path' => '/?' . $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page=skin' );

			}
		}

		$skin_css[] = array(
			'name' => 'mega_main_menu',
			'path' => is_ssl() ? str_replace('http://', 'https://', $mega_main_menu->constant[ 'MM_WARE_CSS_URL' ]) . '/frontend/mega_main_menu.css' : $mega_main_menu->constant[ 'MM_WARE_CSS_URL' ] . '/frontend/mega_main_menu.css'
		);

		/* check and enqueue google fonts */
		/* register and enqueue styles */
		foreach ( $skin_css as $single_css ) {
			wp_register_style( $single_css[ 'name' ], $single_css[ 'path' ], array(), $options_last_modified );
			wp_enqueue_style( $single_css[ 'name' ] );
		}

		if ( isset( $_GET[ $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page' ] ) && ( $_GET[ $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page' ] == 'skin' ) ) {

			header("Content-type: text/css", true);
			//echo '/* CSS Generator  */';
			$generated = microtime(true);
			if ( file_exists( dirname( __FILE__ ) . '/skin.php' ) ) {
				$out = mmm_get_skin();

				if ( in_array( 'true', $mega_main_menu->get_option( 'coercive_styles' , array() ) ) ) {
					$out = str_replace( array( ";
", ";\n", " !important !important" ), array( " !important;", " !important;", " !important" ), $out );
				}
				echo $out;
			} else {
				echo '/* Not have called CSS */';
			}
			die('/* CSS Generator Execution Time: ' . floatval( ( microtime(true) - $generated ) ) . ' seconds */');
		}
	}

?>