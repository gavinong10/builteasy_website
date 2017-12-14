<?php
/**
 * ============================================================================
 * Initial setup
 * ============================================================================
 */
$new_vc_dir = get_template_directory() . '/inc/vc-template';
if ( function_exists( "vc_set_shortcodes_templates_dir" ) ) {
	vc_set_shortcodes_templates_dir( $new_vc_dir );
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Thememove_Recentposts extends WPBakeryShortCode {
	}

	class WPBakeryShortCode_Thememove_Testi extends WPBakeryShortCode {
	}

	class WPBakeryShortCode_Thememove_Gmaps extends WPBakeryShortCode {
		public function __construct( $settings ) {
			parent::__construct( $settings );
			$this->jsScripts();
		}

		public function jsScripts() {
			wp_enqueue_script( 'thememove-js-maps', 'http://maps.google.com/maps/api/js?sensor=false&amp;language=en' );
			wp_enqueue_script( 'thememove-js-gmap3', THEME_ROOT . '/js/gmap3.min.js' );
		}
	}

	class WPBakeryShortCode_Thememove_Project_Details extends WPBakeryShortCode {
	}
}

/**
 * ============================================================================
 * Visual Composer Rewrite Classes
 * ============================================================================
 */
function custom_css_classes_for_vc_row_and_vc_column( $class_string, $tag ) {
	if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
		$class_string = str_replace( 'vc_row-fluid', '', $class_string );
	}
	if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
		$class_string = preg_replace( '/vc_col-sm-(\d{1,2})/', 'col-sm-$1 col-lg-$1', $class_string );
	}

	return $class_string;
}

add_filter( 'vc_shortcodes_css_class', 'custom_css_classes_for_vc_row_and_vc_column', 10, 2 );

add_action( 'vc_before_init', 'disable_updater' );
function disable_updater() {
	vc_manager()->disableUpdater();
}

/**
 * ============================================================================
 * Removing shortcodes
 * ============================================================================
 */
//vc_remove_element("vc_widget_sidebar");
vc_remove_element( "vc_wp_search" );
vc_remove_element( "vc_wp_meta" );
vc_remove_element( "vc_wp_recentcomments" );
vc_remove_element( "vc_wp_calendar" );
vc_remove_element( "vc_wp_pages" );
vc_remove_element( "vc_wp_tagcloud" );
vc_remove_element( "vc_wp_custommenu" );
vc_remove_element( "vc_wp_text" );
vc_remove_element( "vc_wp_posts" );
vc_remove_element( "vc_wp_links" );
vc_remove_element( "vc_wp_categories" );
vc_remove_element( "vc_wp_archives" );
vc_remove_element( "vc_wp_rss" );
vc_remove_element( "vc_teaser_grid" );
vc_remove_element( "vc_button" );
vc_remove_element( "vc_cta_button" );
vc_remove_element( "vc_cta_button2" );
vc_remove_element( "vc_message" );
vc_remove_element( "vc_tour" );
vc_remove_element( "vc_posts_slider" );
vc_remove_element( "vc_toggle" );
vc_remove_element( "vc_posts_grid" );
//vc_remove_element("vc_pie");
//vc_remove_element("vc_progress_bar");
//vc_remove_element("vc_button2");
//vc_remove_element("vc_images_carousel");
//vc_remove_element("vc_carousel");
//vc_remove_element("vc_custom_heading");

/**
 * ============================================================================
 * Remove unused parameters
 * ============================================================================
 */
if ( function_exists( 'vc_remove_param' ) ) {
	vc_remove_param( 'vc_row', 'full_width' );
	vc_remove_param( 'vc_row', 'video_bg' );
	vc_remove_param( 'vc_row', 'video_bg_url' );
	vc_remove_param( 'vc_row', 'video_bg_parallax' );
	vc_remove_param( 'vc_row', 'full_height' );
	vc_remove_param( 'vc_row', 'content_placement' );
	vc_remove_param( 'vc_row', 'parallax' );
	vc_remove_param( 'vc_row', 'parallax_image' );
	vc_remove_param( 'vc_row', 'bg_image' );
	vc_remove_param( 'vc_row', 'bg_color' );
	vc_remove_param( 'vc_row', 'font_color' );
	vc_remove_param( 'vc_row', 'margin_bottom' );
	vc_remove_param( 'vc_row', 'bg_image_repeat' );
	vc_remove_param( 'vc_tabs', 'interval' );
	vc_remove_param( 'vc_separator', 'style' );
	vc_remove_param( 'vc_separator', 'color' );
	vc_remove_param( 'vc_separator', 'accent_color' );
	vc_remove_param( 'vc_separator', 'el_width' );
	vc_remove_param( 'vc_text_separator', 'style' );
	vc_remove_param( 'vc_text_separator', 'color' );
	vc_remove_param( 'vc_text_separator', 'accent_color' );
	vc_remove_param( 'vc_text_separator', 'el_width' );
	vc_remove_param( 'vc_custom_heading', 'google_fonts' );
}

/**
 * ============================================================================
 * Add parameter
 * ============================================================================
 */

//Add parameter for row
vc_add_param( "vc_row", array(
	"type"                    => "dropdown",
	"class"                   => "",
	"show_settings_on_create" => true,
	"heading"                 => "Row Type",
	"param_name"              => "row_type",
	"value"                   => array(
		"Row"      => "row",
		"Parallax" => "parallax"
	)
) );

vc_add_param( "vc_row", array(
	"type"       => "dropdown",
	"class"      => "",
	"heading"    => "Row Layout",
	"param_name" => "type",
	"value"      => array(
		"In Grid"    => "grid",
		"Full Width" => "full_width"
	),
	"dependency" => Array( 'element' => "row_type", 'value' => array( 'row' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "dropdown",
	"class"       => "",
	"heading"     => "Video background",
	"value"       => array(
		"No"  => "",
		"Yes" => "show_video"
	),
	"param_name"  => "video",
	"description" => "",
	"dependency"  => Array( 'element' => "row_type", 'value' => array( 'row' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "textfield",
	"class"       => "",
	"heading"     => "Video background (webm) file url",
	"value"       => "",
	"param_name"  => "video_webm",
	"description" => "",
	"dependency"  => Array( 'element' => "video", 'value' => array( 'show_video' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "textfield",
	"class"       => "",
	"heading"     => "Video background (mp4) file url",
	"value"       => "",
	"param_name"  => "video_mp4",
	"description" => "",
	"dependency"  => Array( 'element' => "video", 'value' => array( 'show_video' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "textfield",
	"class"       => "",
	"heading"     => "Video background (ogv) file url",
	"value"       => "",
	"param_name"  => "video_ogv",
	"description" => "",
	"dependency"  => Array( 'element' => "video", 'value' => array( 'show_video' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "textfield",
	"class"       => "",
	"heading"     => "Background image",
	"value"       => "",
	"param_name"  => "background_image",
	"description" => "",
	"dependency"  => Array( 'element' => "row_type", 'value' => array( 'parallax', 'row' ) )
) );
vc_add_param( "vc_row", array(
	"type"       => "textfield",
	"class"      => "",
	"heading"    => "Parallax speed",
	"param_name" => "parallax_speed",
	"value"      => "",
	"dependency" => Array( 'element' => "row_type", 'value' => array( 'parallax' ) )
) );
vc_add_param( "vc_row", array(
	"type"        => "colorpicker",
	"class"       => "",
	"heading"     => "Background color",
	"param_name"  => "background_color",
	"value"       => "",
	"description" => "",
	"dependency"  => Array( 'element' => "row_type", 'value' => array( 'row' ) )
) );

//add parameter for button
vc_add_params( "vc_button2", array(
	array(
		"type"        => "colorpicker",
		"heading"     => "Background color",
		"param_name"  => "background_color",
		"value"       => "",
		"description" => "Choose a custom background color for button",
	),
	array(
		"type"        => "colorpicker",
		"heading"     => "Background color on hover",
		"param_name"  => "background_color_hover",
		"value"       => "",
		"description" => "Choose a custom background color for button when hover",
	),
	array(
		"type"        => "colorpicker",
		"heading"     => "Text color",
		"param_name"  => "text_color",
		"value"       => "",
		"description" => "Choose a custom text color for button",
	),
	array(
		"type"        => "colorpicker",
		"heading"     => "Text color on hover",
		"param_name"  => "text_color_hover",
		"value"       => "",
		"description" => "Choose a custom text color for button when hover",
	),
	array(
		"type"        => "colorpicker",
		"heading"     => "Border color",
		"param_name"  => "border_color",
		"value"       => "",
		"description" => "Choose a custom border color for button",
	),
	array(
		"type"        => "colorpicker",
		"heading"     => "Border color on hover",
		"param_name"  => "border_color_hover",
		"value"       => "",
		"description" => "Choose a custom border color for button when hover",
	)
) );

/**
 * ============================================================================
 * Map shortcodes
 * ============================================================================
 */

//Latest Posts
vc_map( array(
	'name'     => __( 'Recent Posts 2', 'thememove' ),
	'base'     => 'thememove_recentposts',
	'category' => __( 'by THEMEMOVE', 'thememove' ),
	'params'   => array(
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_title',
			'value'      => array(
				'Show title' => true
			)
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_excerpt',
			'value'      => array(
				'Show excerpt' => true
			)
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_meta',
			'value'      => array(
				'Show Meta' => true
			)
		),
		array(
			'type'       => 'textfield',
			'heading'    => "Enter numbers of articles",
			'param_name' => 'number',
		),
	)
) );

vc_map( array(
	"name"                      => "Testimonials",
	"base"                      => "woothemes_testimonials",
	"category"                  => 'by THEMEMOVE',
	"allowed_container_element" => 'vc_row',
	"params"                    => array(
		array(
			"type"        => "textfield",
			"heading"     => "Number",
			"param_name"  => "limit",
			"value"       => "",
			"description" => "Number of Testimonials"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show Author Info",
			"param_name"  => "display_author",
			"value"       => array(
				"No"  => "false",
				"Yes" => "true",
			),
			"description" => "Choose yes to show author name and byline"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show Author Image",
			"param_name"  => "display_avatar",
			"value"       => array(
				"No"  => "false",
				"Yes" => "true",
			),
			"description" => "Choose yes to show author avatar"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show URL",
			"param_name"  => "display_url",
			"value"       => array(
				"No"  => "false",
				"Yes" => "true",
			),
			"description" => "Choose yes to show author url"
		)
	)
) );

//Map testimonials 2
vc_map( array(
	"name"                      => "Testimonials 2",
	"base"                      => "thememove_testi",
	"category"                  => 'by THEMEMOVE',
	"allowed_container_element" => 'vc_row',
	"params"                    => array(
		array(
			"type"       => "dropdown",
			"heading"    => "Enable Carousel",
			"param_name" => "enable_carousel",
			"value"      => array(
				"No"  => 'false',
				"Yes" => 'true',
			),
		),
		array(
			"type"       => "dropdown",
			"heading"    => "Show Bullets",
			"param_name" => "display_bullets",
			"value"      => array(
				"No"  => 'false',
				"Yes" => 'true',
			),
			"dependency" => Array( 'element' => "enable_carousel", 'value' => array( 'true' ) )
		),
		array(
			"type"       => "dropdown",
			"heading"    => "Enable Autoplay",
			"param_name" => "enable_autoplay",
			"value"      => array(
				"No"  => 'false',
				"Yes" => 'true',
			),
			"dependency" => Array( 'element' => "enable_carousel", 'value' => array( 'true' ) )
		),
		array(
			"type"        => "textfield",
			"heading"     => "Number",
			"param_name"  => "limit",
			"value"       => "",
			"description" => "Number of Testimonials"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Order by",
			"param_name"  => "orderby",
			"value"       => array(
				"None"       => "none",
				"ID"         => "ID",
				"Title"      => "title",
				"Date"       => "date",
				"Menu Order" => "menu_order",
			),
			"description" => "How to order the items"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Order",
			"param_name"  => "order",
			"value"       => array(
				"DESC" => "DESC",
				"ASC"  => "ASC",
			),
			"description" => "How to order the items"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show Author Info",
			"param_name"  => "display_author",
			"value"       => array(
				"No"  => 'false',
				"Yes" => 'true',
			),
			"description" => "Choose yes to show author name and byline"
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show URL",
			"param_name"  => "display_url",
			"value"       => array(
				"No"  => 'false',
				"Yes" => 'true',
			),
			"description" => "Choose yes to show author url",
			"dependency"  => Array( 'element' => "display_author", 'value' => array( 'true' ) )
		),
		array(
			"type"        => "dropdown",
			"heading"     => "Show Author Image",
			"param_name"  => "display_avatar",
			"value"       => array(
				"No"  => false,
				"Yes" => true,
			),
			"description" => "Choose yes to show author avatar",
		),
		array(
			"type"        => "textfield",
			"heading"     => "Avatar Size",
			"param_name"  => "size",
			"value"       => "",
			"description" => "Size of Avatar",
		),
		array(
			'type'       => 'textfield',
			'heading'    => "Extra class name",
			'param_name' => 'el_class',
		)
	)
) );

vc_map( array(
	"name"                      => "Recents Posts",
	"base"                      => "recent_posts",
	"allowed_container_element" => 'vc_row',
	"params"                    => array(
		array(
			"type"        => "textfield",
			"holder"      => "div",
			"class"       => "",
			"heading"     => "Number",
			"param_name"  => "number",
			"value"       => "",
			"description" => "Number of Posts"
		),
		array(
			"type"        => "dropdown",
			"holder"      => "div",
			"class"       => "",
			"heading"     => "Feature Image",
			"param_name"  => "feature_image",
			"value"       => array(
				"No"  => "no",
				"Yes" => "yes",
			),
			"description" => ""
		),
		array(
			"type"        => "dropdown",
			"holder"      => "div",
			"class"       => "",
			"heading"     => "Show description",
			"param_name"  => "show_description",
			"value"       => array(
				"Yes" => "yes",
				"No"  => "no"
			),
			"description" => ""
		)
	)
) );

// Thememove Google Maps
vc_map( array(
	'name'     => __( 'Google Maps', 'thememove' ),
	'base'     => 'thememove_gmaps',
	'category' => __( 'by THEMEMOVE', 'thememove' ),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => "Address or Coordinate",
			'admin_label' => true,
			'param_name'  => 'address',
			'value'       => '40.7590615,-73.969231',
			'description' => __( 'Enter address or coordinate. To learn how to get coordinates, visit <a href="https://support.google.com/maps/answer/18539?hl=en" target="_blank">here</a>', 'thememove' ),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => "Marker icon",
			'param_name'  => 'marker_icon',
			'description' => __( 'Choose a image for marker address', 'thememove' ),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => "Marker Information",
			'param_name'  => 'content',
			'description' => __( 'Content for info window', 'thememove' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => "Height",
			'param_name'  => 'map_height',
			'value'       => '480',
			'description' => __( 'Enter map height (in pixels or percentage)', 'thememove' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => "Width",
			'param_name'  => 'map_width',
			'value'       => '100%',
			'description' => __( 'Enter map width (in pixels or percentage)', 'thememove' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => "Zoom level",
			'param_name'  => 'zoom',
			'value'       => '16',
			'description' => __( 'Map zoom level', 'thememove' ),
		),
		array(
			'type'       => 'checkbox',
			'heading'    => "Enable Map zoom",
			'param_name' => 'zoom_enable',
			'value'      => array(
				'Enable' => 'enable'
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => "Map type",
			'admin_label' => true,
			'param_name'  => 'map_type',
			'description' => __( 'Choose a map type', 'thememove' ),
			'value'       => array(
				'Roadmap'   => 'roadmap',
				'Satellite' => 'satellite',
				'Hybrid'    => 'hybrid',
				'Terrain'   => 'terrain',
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => "Map style",
			'admin_label' => true,
			'param_name'  => 'map_style',
			'description' => __( 'Choose a map style. This approach changes the style of the Roadmap types (base imagery in terrain and satellite views is not affected, but roads, labels, etc. respect styling rules', 'thememove' ),
			'value'       => array(
				'Default'          => 'default',
				'Grayscale'        => 'style1',
				'Subtle Grayscale' => 'style2',
				'Apple Maps-esque' => 'style3',
				'Pale Dawn'        => 'style4',
				'Muted Blue'       => 'style5',
				'Paper'            => 'style6',
				'Light Dream'      => 'style7',
				'Retro'            => 'style8',
				'Avocado World'    => 'style9',
				'Facebook'         => 'style10',
				'Custom'           => 'custom'
			)
		),
		array(
			'type'        => 'textarea_raw_html',
			'heading'     => "Map style snippet",
			'param_name'  => 'map_style_snippet',
			'description' => __( 'To get the style snippet, visit <a href="https://snazzymaps.com" target="_blank">Sanzzymaps</a> or <a href="http://www.mapstylr.com/" target="_blank">Mapstylr</a>.', 'thememove' ),
			'dependency'  => array(
				'element' => 'map_style',
				'value'   => 'custom',
			)
		),
		array(
			'type'        => 'textfield',
			'heading'     => "Extra class name",
			'param_name'  => 'el_class',
			'description' => __( 'If you want to use multiple Google Maps in one page, please add a class name for them.', 'thememove' ),
		),
	)
) );

// Thememove Project Details
vc_map( array(
	'name'        => __( 'Project Details', 'thememove' ),
	'description' => __( 'Only available for Projects.', 'thememove' ),
	'base'        => 'thememove_project_details',
	'category'    => __( 'by THEMEMOVE', 'thememove' ),
	'params'      => array(
		array(
			'type'        => 'dropdown',
			'heading'     => "Layout",
			'admin_label' => true,
			'param_name'  => 'layout',
			'description' => __( 'Choose a layout to display project details', 'thememove' ),
			'value'       => array(
				'Layout 1' => 'layout1',
				'Layout 2' => 'layout2'
			),
		),
	)
) );