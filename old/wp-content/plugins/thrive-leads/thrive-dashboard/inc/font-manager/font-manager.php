<?php

add_action( "wp_ajax_tve_dash_display_font_manager", "tve_dash_display_font_options" );
/* Load font manager view in admin */
function tve_dash_display_font_options() {
	include 'views/admin-font-options.php';
	die;
}

add_action( "wp_ajax_tve_dash_font_manager_delete", "tve_dash_font_manager_delete" );
/* Delete font from font manager AJAX REQUEST */
function tve_dash_font_manager_delete() {
	$font_id     = $_REQUEST['font_id'];
	$old_options = json_decode( get_option( 'thrive_font_manager_options' ), true );
	$delete_key  = - 1;
	foreach ( $old_options as $key => $font ) {
		if ( $font['font_id'] == $font_id ) {
			$delete_key = $key;
		}
	}
	if ( $delete_key != - 1 ) {
		unset( $old_options[ $delete_key ] );
	}
	$old_options = array_values( $old_options );
	update_option( 'thrive_font_manager_options', json_encode( $old_options ) );
	die;
}

add_action( "wp_ajax_tve_dash_font_manager_add", "tve_dash_font_manager_add" );
/* Add new font in font manager */
function tve_dash_font_manager_add() {
	$data = $_REQUEST;

	$options = get_option( 'thrive_font_manager_options' );
	$option  = array(
		'font_name'          => $data['font_name'],
		'font_style'         => $data['font_style'],
		'font_bold'          => $data['font_bold'],
		'font_italic'        => $data['font_italic'],
		'font_character_set' => $data['font_character_set'],
		'font_class'         => $data['font_class'],
		'font_size'          => $data['font_size'],
		'font_height'        => $data['font_height'],
		'font_color'         => $data['font_color'],
		'custom_css'         => $data['custom_css']
	);
	if ( $options == false || count( json_decode( $options ), true ) == 0 ) {
		//we don't have any other options saved
		$option['font_id'] = 1;
		update_option( 'thrive_font_manager_options', json_encode( array( $option ) ) );
	} else {
		$old_options       = json_decode( get_option( 'thrive_font_manager_options' ), true );
		$last_option       = end( $old_options );
		$option['font_id'] = $last_option['font_id'] + 1;
		$old_options[]     = $option;
		update_option( 'thrive_font_manager_options', json_encode( $old_options ) );
	}
	die;
}

add_action( "wp_ajax_tve_dash_font_manager_edit", "tve_dash_font_manager_edit" );
/* Edit saved font from font manager */
function tve_dash_font_manager_edit() {
	$data = $_REQUEST;

	$old_options = json_decode( get_option( 'thrive_font_manager_options' ), true );
	foreach ( $old_options as $key => $font ) {
		if ( $font['font_id'] == intval( $data['font_id'] ) ) {
			$old_options[ $key ]['font_name']          = $data['font_name'];
			$old_options[ $key ]['font_style']         = $data['font_style'];
			$old_options[ $key ]['font_bold']          = $data['font_bold'];
			$old_options[ $key ]['font_italic']        = $data['font_italic'];
			$old_options[ $key ]['font_character_set'] = $data['font_character_set'];
			$old_options[ $key ]['font_class']         = $data['font_class'];
			$old_options[ $key ]['font_size']          = $data['font_size'];
			$old_options[ $key ]['font_height']        = $data['font_height'];
			$old_options[ $key ]['font_color']         = $data['font_color'];
			$old_options[ $key ]['custom_css']         = $data['custom_css'];
		}
	}
	update_option( 'thrive_font_manager_options', json_encode( $old_options ) );
	die;
}

add_action( "wp_ajax_tve_dash_font_manager_duplicate", "tve_dash_font_manager_duplicate" );

function tve_dash_font_manager_duplicate() {
	$font_id     = $_REQUEST['font_id'];
	$old_options = json_decode( get_option( 'thrive_font_manager_options' ), true );
	$option      = null;
	foreach ( $old_options as $key => $font ) {
		if ( $font['font_id'] == $font_id ) {
			$option = $font;
		}
	}
	if ( $option ) {
		$last_option          = end( $old_options );
		$option['font_id']    = intval( $last_option['font_id'] ) + 1;
		$option['font_class'] = 'ttfm' . $option['font_id'];
		$old_options[]        = $option;
	}
	update_option( 'thrive_font_manager_options', json_encode( $old_options ) );
	die;
}

/* Display font manager admin interface */
function tve_dash_font_manager_main_page() {
	$font_options = is_array( json_decode( get_option( 'thrive_font_manager_options' ), true ) ) ? json_decode( get_option( 'thrive_font_manager_options' ), true ) : array();
	$last_option  = end( $font_options );
	$font_id      = intval( $last_option['font_id'] ) + 1;

	include 'views/admin-font-manager.php';
}

add_action( 'admin_enqueue_scripts', 'tve_dash_font_manager_include_scripts' );

function tve_dash_font_manager_include_scripts( $hook ) {

	$allowed = array(
		'admin_page_tve_dash_font_manager',
		'admin_page_tve_dash_font_import_manager'
	);

	if ( ! in_array( $hook, $allowed ) ) {
		return;
	}

	tve_dash_enqueue();

	if ( $hook == 'admin_page_tve_dash_font_import_manager' && ! empty( $_POST['attachment_id'] ) && $_POST['attachment_id'] != "-1" && count( $_POST ) == 1 ) {
		/**
		 * we don't want to load dash style.css when WP FTP form is displayed
		 */
		wp_deregister_style( 'tve-dash-styles-css' );
	}

	if ( $hook == 'admin_page_tve_dash_font_import_manager' ) {
		wp_enqueue_media();
		tve_dash_enqueue_style( 'tve-dash-font-import-manager-css', TVE_DASH_URL . '/inc/font-import-manager/views/css/manager.css' );
		tve_dash_enqueue_script( 'tve-dash-font-import-manager-js', TVE_DASH_URL . '/inc/font-import-manager/views/js/manager.js', array(
			'jquery',
			'media-upload',
			'thickbox'
		) );
	}

	if ( $hook == 'admin_page_tve_dash_font_manager' ) {
		add_thickbox();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		tve_dash_enqueue_style( 'tve-dash-font-manager', TVE_DASH_URL . '/inc/font-manager/css/style.css' );
	}
}

add_action( "wp_ajax_tve_dash_font_manager_update_posts_fonts", "tve_dash_font_manager_update_posts_fonts" );

function tve_dash_font_manager_update_posts_fonts() {
	$posts = get_posts( array( 'posts_per_page' => - 1 ) );

	foreach ( $posts as $post ) {

		$post_id      = $post->ID;
		$post_content = $post->post_content;
		preg_match_all( "/thrive_custom_font id='\d+'/", $post_content, $font_ids );

		$post_fonts = array();
		foreach ( $font_ids[0] as $font_id ) {
			$parts = explode( "'", $font_id );
			$id    = $parts[1];
			$font  = thrive_get_font_options( $id );
			if ( tve_dash_font_manager_is_safe_font( $font->font_name ) ) {
				continue;
			}
			if ( Tve_Dash_Font_Import_Manager::isImportedFont( $font->font_name ) ) {
				$post_fonts[] = Tve_Dash_Font_Import_Manager::getCssFile();
				continue;
			}
			$post_fonts[] = "//fonts.googleapis.com/css?family=" . str_replace( " ", "+", $font->font_name ) . ( $font->font_style != 0 ? ":" . $font->font_style : "" ) . ( $font->font_italic ? "" . $font->font_italic : "" ) . ( $font->font_bold != 0 ? "," . $font->font_bold : "" ) . ( $font->font_character_set != 0 ? "&subset=" . $font->font_character_set : "" );
		}
		$post_fonts = array_unique( $post_fonts );
		update_post_meta( $post_id, 'thrive_post_fonts', sanitize_text_field( json_encode( $post_fonts ) ) );
	}
	die;
}


function tve_dash_font_manager_get_safe_fonts() {
	return $safe_fonts = array(
		array(
			'family'   => 'Georgia, serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Palatino Linotype, Book Antiqua, Palatino, serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Times New Roman, Times, serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Arial, Helvetica, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Arial Black, Gadget, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Comic Sans MS, cursive, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Impact, Charcoal, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Tahoma, Geneva, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Trebuchet MS, Helvetica, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Verdana, Geneva, sans-serif',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Courier New, Courier, monospace',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
		array(
			'family'   => 'Lucida Console, Monaco, monospace',
			'variants' => array( 'regular', 'italic', '600' ),
			'subsets'  => array( 'latin' ),
		),
	);

}

function tve_dash_font_manager_is_safe_font( $font_family ) {
	foreach ( tve_dash_font_manager_get_safe_fonts() as $font ) {
		if ( $font_family === $font['family'] ) {
			return true;
		}
	}

	return false;
}

function tve_dash_font_manager_get_safe_font( $font_family ) {
	foreach ( tve_dash_font_manager_get_safe_fonts() as $font ) {
		if ( $font['family'] === $font_family ) {
			return $font;
		}
	}

	return array();
}

function tve_dash_get_font_family_array( $font_name = null ) {
	if ( $font_name === false ) {
		return false;
	}
	$font_name = str_replace( " ", "", trim( $font_name ) );
	$fonts     = array(
		'AbrilFatface'        => "font-family: 'Abril Fatface', cursive;",
		'Amatic SC'           => "font-family: 'Amatic SC', cursive;",
		'Archivo Black'       => "font-family: 'Archivo Black', sans-serif;",
		'Arbutus Slab'        => "font-family: 'Arbutus Slab', serif;",
		'Archivo Narrow'      => "font-family: 'Archivo Narrow', sans-serif;",
		'Arial'               => "font-family: 'Arial';",
		'Arimo'               => "font-family: 'Arimo', sans-serif;",
		'Arvo'                => "font-family: 'Arvo', serif;",
		'Boogaloo'            => "font-family: 'Boogaloo', cursive;",
		'Calligraffitti'      => "font-family: 'Calligraffitti', cursive;",
		'CantataOne'          => "font-family: 'Cantata One', serif;",
		'Cardo'               => "font-family: 'Cardo', serif;",
		'Cutive'              => "font-family: 'Cutive', serif;",
		'DaysOne'             => "font-family: 'Days One', sans-serif;",
		'Dosis'               => "font-family: 'Dosis', sans-serif;",
		'Droid Sans'          => "font-family: 'Droid Sans', sans-serif;",
		'Droid Serif'         => "font-family: 'Droid Serif', sans-serif;",
		'FjallaOne'           => "font-family: 'Fjalla One', sans-serif;",
		'FrancoisOne'         => "font-family: 'Francois One', sans-serif;",
		'Georgia'             => "font-family: 'Georgia';",
		'GravitasOne'         => "font-family: 'Gravitas One', cursive;",
		'Helvetica'           => "font-family: 'Helvetica';",
		'JustAnotherHand'     => "font-family: 'Just Another Hand', cursive;",
		'Josefin Sans'        => "font-family: 'Josefin Sans', sans-serif;",
		'Josefin Slab'        => "font-family: 'Josefin Slab', serif;",
		'Lobster'             => "font-family: 'Lobster', cursive;",
		'Lato'                => "font-family: 'Lato', sans-serif;",
		'Montserrat'          => "font-family: 'Montserrat', sans-serif;",
		'NotoSans'            => "font-family: 'Noto Sans', sans-serif;",
		'OleoScript'          => "font-family: 'Oleo Script', cursive;",
		'Old Standard TT'     => "font-family: 'Old Standard TT', serif;",
		'Open Sans'           => "font-family: 'Open Sans', sans-serif;",
		'Oswald'              => "font-family: 'Oswald', sans-serif;",
		'OpenSansCondensed'   => "font-family: 'Open Sans Condensed', sans-serif;",
		'Oxygen'              => "font-family: 'Oxygen', sans-serif;",
		'Pacifico'            => "font-family: 'Pacifico', cursive;",
		'Playfair Display'    => "font-family: 'Playfair Display', serif;",
		'Poiret One'          => "font-family: 'Poiret One', cursive;",
		'PT Sans'             => "font-family: 'PT Sans', sans-serif;",
		'PT Serif'            => "font-family: 'PT Serif', sans-serif;",
		'Raleway'             => "font-family: 'Raleway', sans-serif;",
		'Roboto'              => "font-family: 'Roboto', sans-serif;",
		'Roboto Condensed'    => "font-family: 'Roboto Condensed', sans-serif;",
		'Roboto Slab'         => "font-family: 'Roboto Slab', serif;",
		'ShadowsIntoLightTwo' => "font-family: 'Shadows Into Light Two', cursive;",
		'Source Sans Pro'     => "font-family: 'Source Sans Pro', sans-serif;",
		'Sorts Mill Gaudy'    => "font-family: 'Sorts Mill Gaudy', cursive;",
		'SpecialElite'        => "font-family: 'Special Elite', cursive;",
		'Tahoma'              => "font-family: 'Tahoma';",
		'TimesNewRoman'       => "font-family: 'Times New Roman';",
		'Ubuntu'              => "font-family: 'Ubuntu', sans-serif;",
		'Ultra'               => "font-family: 'Ultra', serif;",
		'VarelaRound'         => "font-family: 'Varela Round', sans-serif;",
		'Verdana'             => "font-family: 'Verdana';",
		'Vollkorn'            => "font-family: 'Vollkorn', serif;"
	);

	if ( $font_name ) {
		if ( isset( $fonts[ $font_name ] ) ) {
			return $fonts[ $font_name ];
		} else {
			return false;
		}
	}

	return $fonts;
}
