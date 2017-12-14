<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package VPBakeryVisualComposer
 *
 */

$vc_is_wp_version_3_6_more = version_compare( preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), '3.6' ) >= 0;

// Used in "Button", "Call __( 'Blue', 'js_composer' )to Action", "Pie chart" blocks
$colors_arr = array(
	__( 'Grey', 'js_composer' ) => 'wpb_button',
	__( 'Blue', 'js_composer' ) => 'btn-primary',
	__( 'Turquoise', 'js_composer' ) => 'btn-info',
	__( 'Green', 'js_composer' ) => 'btn-success',
	__( 'Orange', 'js_composer' ) => 'btn-warning',
	__( 'Red', 'js_composer' ) => 'btn-danger',
	__( 'Black', 'js_composer' ) => "btn-inverse"
);

// Used in "Button" and "Call to Action" blocks
$size_arr = array(
	__( 'Regular', 'js_composer' ) => 'wpb_regularsize',
	__( 'Large', 'js_composer' ) => 'btn-large',
	__( 'Small', 'js_composer' ) => 'btn-small',
	__( 'Mini', 'js_composer' ) => "btn-mini"
);

$target_arr = array(
	__( 'Same window', 'js_composer' ) => '_self',
	__( 'New window', 'js_composer' ) => '_blank'
);
global $vc_add_css_animation;
$vc_add_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'CSS Animation', 'js_composer' ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		__( 'No', 'js_composer' ) => '',
		__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
		__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
		__( 'Left to right', 'js_composer' ) => 'left-to-right',
		__( 'Right to left', 'js_composer' ) => 'right-to-left',
		__( 'Appear from center', 'js_composer' ) => 'appear'
	),
	'description' => __( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'js_composer' )
);

vc_map( array(
	'name' => __( 'Row', 'js_composer' ),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Place content elements inside the row', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Row stretch', 'js_composer' ),
			'param_name' => 'full_width',
			'value' => array(
				__( 'Default', 'js_composer' ) => '',
				__( 'Stretch row', 'js_composer' ) => 'stretch_row',
				__( 'Stretch row and content', 'js_composer' ) => 'stretch_row_content',
				__( 'Stretch row and content (no paddings)', 'js_composer' ) => 'stretch_row_content_no_spaces',
			),
			'description' => __( 'Select stretching options for row and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'js_composer' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Full height row?', 'js_composer' ),
			'param_name' => 'full_height',
			'description' => __( 'If checked row will be set to full height.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Content position', 'js_composer' ),
			'param_name' => 'content_placement',
			'value' => array(
				__( 'Middle', 'js_composer' ) => 'middle',
				__( 'Top', 'js_composer' ) => 'top',
			),
			'description' => __( 'Select content position within row.', 'js_composer' ),
			'dependency' => array(
				'element' => 'full_height',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Use video background?', 'js_composer' ),
			'param_name' => 'video_bg',
			'description' => __( 'If checked, video will be used as row background.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'YouTube link', 'js_composer' ),
			'param_name' => 'video_bg_url',
			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k', // default video url
			'description' => __( 'Add YouTube link.', 'js_composer' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'js_composer' ),
			'param_name' => 'video_bg_parallax',
			'value' => array(
				__( 'None', 'js_composer' ) => '',
				__( 'Simple', 'js_composer' ) => 'content-moving',
				__( 'With fade', 'js_composer' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row.', 'js_composer' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'js_composer' ),
			'param_name' => 'parallax',
			'value' => array(
				__( 'None', 'js_composer' ) => '',
				__( 'Simple', 'js_composer' ) => 'content-moving',
				__( 'With fade', 'js_composer' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'js_composer' ),
			'dependency' => array(
				'element' => 'video_bg',
				'is_empty' => true,
			),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'js_composer' ),
			'param_name' => 'parallax_image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'js_composer' ),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'el_id',
			'heading' => __( 'Row ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
	'js_view' => 'VcRowView'
) );

vc_map( array(
	'name' => __( 'Row', 'js_composer' ), //Inner Row
	'base' => 'vc_row_inner',
	'content_element' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'weight' => 1000,
	'show_settings_on_create' => false,
	'description' => __( 'Place content elements inside the row', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'el_id',
			'heading' => __( 'Row ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'js_composer' ), '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . __( 'link', 'js_composer' ) . '</a>' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
	'js_view' => 'VcRowView'
) );
global $vc_column_width_list;
$vc_column_width_list = array(
	__( '1 column - 1/12', 'js_composer' ) => '1/12',
	__( '2 columns - 1/6', 'js_composer' ) => '1/6',
	__( '3 columns - 1/4', 'js_composer' ) => '1/4',
	__( '4 columns - 1/3', 'js_composer' ) => '1/3',
	__( '5 columns - 5/12', 'js_composer' ) => '5/12',
	__( '6 columns - 1/2', 'js_composer' ) => '1/2',
	__( '7 columns - 7/12', 'js_composer' ) => '7/12',
	__( '8 columns - 2/3', 'js_composer' ) => '2/3',
	__( '9 columns - 3/4', 'js_composer' ) => '3/4',
	__( '10 columns - 5/6', 'js_composer' ) => '5/6',
	__( '11 columns - 11/12', 'js_composer' ) => '11/12',
	__( '12 columns - 1/1', 'js_composer' ) => '1/1'
);

/**
 * @shortcode vc_column WPBakeryShortCode_VC_Column
 *     wp-content/plugins/js_composer/include/classes/shortcodes/vc-column.php/WPBakeryShortCode_VC_Column
 *
 * @param font_color wp-content/plugins/js_composer/include/params/colorpicker/colorpicker.php/vc_colorpicker_form_field -
 *  - colorpicker - defines font color for text
 * @param el_class - extra shortcode wrapper class
 * @param css_editor WPBakeryVisualComposerCssEditor wp-content/plugins/js_composer/include/params/css_editor/css_editor.php/
 *     -
 *  - css editor design options margin/padding/border and etc for shortcode wrapper
 * @param width wp-content/plugins/js_composer/include/params/default_params.php/vc_dropdown_form_field - array of
 *     columns width's
 * @param offset Vc_Column_Offset wp-content/plugins/js_composer/include/params/column_offset/column_offset.php/Vc_Column_Offset
 *     -
 *  - responsiveness offset properties for columns.
 *
 * @backend_view VcColumnView
 *     wp-content/plugins/js_composer/assets/js/backend/composer-custom-views.js/window.VcColumnView - custom backend
 *     shortcode view.
 */
vc_map( array(
	'name' => __( 'Column', 'js_composer' ),
	'base' => 'vc_column',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', 'js_composer' ),
			'param_name' => 'width',
			'value' => $vc_column_width_list,
			'group' => __( 'Responsive Options', 'js_composer' ),
			'description' => __( 'Select column width.', 'js_composer' ),
			'std' => '1/1'
		),
		array(
			'type' => 'column_offset',
			'heading' => __( 'Responsiveness', 'js_composer' ),
			'param_name' => 'offset',
			'group' => __( 'Responsive Options', 'js_composer' ),
			'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' )
		)
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __( "Column", "js_composer" ),
	"base" => "vc_column_inner",
	"class" => "",
	"icon" => "",
	"wrapper_class" => "",
	"controls" => "full",
	"allowed_container_element" => false,
	"content_element" => false,
	"is_container" => true,
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => __( "Extra class name", "js_composer" ),
			"param_name" => "el_class",
			"value" => "",
			"description" => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", "js_composer" )
		),
		array(
			"type" => "css_editor",
			"heading" => __( 'CSS box', "js_composer" ),
			"param_name" => "css",
			"group" => __( 'Design Options', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', 'js_composer' ),
			'param_name' => 'width',
			'value' => $vc_column_width_list,
			'group' => __( 'Responsive Options', 'js_composer' ),
			'description' => __( 'Select column width.', 'js_composer' ),
			'std' => '1/1'
		),
		array(
			'type' => 'column_offset',
			'heading' => __( 'Responsiveness', 'js_composer' ),
			'param_name' => 'offset',
			'group' => __( 'Responsive Options', 'js_composer' ),
			'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' )
		)
	),
	"js_view" => 'VcColumnView'
) );
/* Text Block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Text Block', 'js_composer' ),
	'base' => 'vc_column_text',
	'icon' => 'icon-wpb-layer-shape-text',
	'wrapper_class' => 'clearfix',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'A block of text with WYSIWYG editor', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
		),
		$vc_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

include_once "shortcode-vc-icon-element.php";

/* Separator (Divider)
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Separator', 'js_composer' ),
	'base' => 'vc_separator',
	'icon' => 'icon-wpb-ui-separator',
	'show_settings_on_create' => true,
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Horizontal separator line', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
			'std' => 'grey',
			'description' => __( 'Select color of separator.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'js_composer' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Center', 'js_composer' ) => 'align_center',
				__( 'Left', 'js_composer' ) => 'align_left',
				__( 'Right', 'js_composer' ) => "align_right"
			),
			'description' => __( 'Select separator alignment.', 'js_composer' )
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Border Color', 'js_composer' ),
			'param_name' => 'accent_color',
			'description' => __( 'Select border color for your element.', 'js_composer' ),
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => __( 'Separator display style.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border width', 'js_composer' ),
			'param_name' => 'border_width',
			'value' => getVcShared( 'separator border widths' ),
			'description' => __( 'Select border width (pixels).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => __( 'Select separator width (percentage).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Textual block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Separator with Text', 'js_composer' ),
	'base' => 'vc_text_separator',
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Horizontal separator line with heading', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'holder' => 'div',
			'value' => __( 'Title', 'js_composer' ),
			'description' => __( 'Add text to separator.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title position', 'js_composer' ),
			'param_name' => 'title_align',
			'value' => array(
				__( 'Center', 'js_composer' ) => 'separator_align_center',
				__( 'Left', 'js_composer' ) => 'separator_align_left',
				__( 'Right', 'js_composer' ) => "separator_align_right"
			),
			'description' => __( 'Select title location.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Separator alignment', 'js_composer' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Center', 'js_composer' ) => 'align_center',
				__( 'Left', 'js_composer' ) => 'align_left',
				__( 'Right', 'js_composer' ) => "align_right"
			),
			'description' => __( 'Select separator alignment.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
			'std' => 'grey',
			'description' => __( 'Select color of separator.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Color', 'js_composer' ),
			'param_name' => 'accent_color',
			'description' => __( 'Custom separator color for your element.', 'js_composer' ),
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => __( 'Separator display style.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border width', 'js_composer' ),
			'param_name' => 'border_width',
			'value' => getVcShared( 'separator border widths' ),
			'description' => __( 'Select border width (pixels).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => __( 'Separator element width in percents.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'hidden',
			'param_name' => 'layout',
			'value' => 'separator_with_text',
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
	'js_view' => 'VcTextSeparatorView'
) );

/* Message box 2
---------------------------------------------------------- */
global $pixel_icons;
$pixel_icons = array(
	array( 'vc_pixel_icon vc_pixel_icon-alert' => __( 'Alert', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-info' => __( 'Info', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-tick' => __( 'Tick', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-explanation' => __( 'Explanation', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-address_book' => __( 'Address book', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-alarm_clock' => __( 'Alarm clock', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-anchor' => __( 'Anchor', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-application_image' => __( 'Application Image', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-arrow' => __( 'Arrow', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-asterisk' => __( 'Asterisk', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-hammer' => __( 'Hammer', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-balloon' => __( 'Balloon', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-balloon_buzz' => __( 'Balloon Buzz', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-balloon_facebook' => __( 'Balloon Facebook', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-balloon_twitter' => __( 'Balloon Twitter', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-battery' => __( 'Battery', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-binocular' => __( 'Binocular', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_excel' => __( 'Document Excel', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_image' => __( 'Document Image', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_music' => __( 'Document Music', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_office' => __( 'Document Office', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_pdf' => __( 'Document PDF', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_powerpoint' => __( 'Document Powerpoint', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-document_word' => __( 'Document Word', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-bookmark' => __( 'Bookmark', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-camcorder' => __( 'Camcorder', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-camera' => __( 'Camera', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-chart' => __( 'Chart', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-chart_pie' => __( 'Chart pie', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-clock' => __( 'Clock', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-fire' => __( 'Fire', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-heart' => __( 'Heart', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-mail' => __( 'Mail', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-play' => __( 'Play', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-shield' => __( 'Shield', 'js_composer' ) ),
	array( 'vc_pixel_icon vc_pixel_icon-video' => __( 'Video', 'js_composer' ) ),
);
$custom_colors = array(
	__( 'Informational', 'js_composer' ) => 'info',
	__( 'Warning', 'js_composer' ) => 'warning',
	__( 'Success', 'js_composer' ) => 'success',
	__( 'Error', 'js_composer' ) => "danger",
	__( 'Informational Classic', 'js_composer' ) => 'alert-info',
	__( 'Warning Classic', 'js_composer' ) => 'alert-warning',
	__( 'Success Classic', 'js_composer' ) => 'alert-success',
	__( 'Error Classic', 'js_composer' ) => "alert-danger",
);
global $vc_add_css_animation_no_label;
$vc_add_css_animation_no_label = $vc_add_css_animation;
unset( $vc_add_css_animation_no_label['admin_label'] );
/**
 * @since 4.4
 * New message box shortcode (replaces old)
 */
vc_map( array(
	'name' => __( 'Message Box', 'js_composer' ),
	'base' => 'vc_message',
	'icon' => 'icon-wpb-information-white',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Notification box', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'params_preset',
			'heading' => __( 'Message Box Presets', 'js_composer' ),
			'param_name' => 'color', // due to backward compatibility, really it is message_box_type
			'value' => '',
			'options' => array(
				array(
					'label' => __( 'Custom', 'js_composer' ),
					'value' => '',
					'params' => array(),
				),
				array(
					'label' => __( 'Informational', 'js_composer' ),
					'value' => 'info',
					'params' => array(
						'message_box_color' => 'info',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fa fa-info-circle',
					),
				),
				array(
					'label' => __( 'Warning', 'js_composer' ),
					'value' => 'warning',
					'params' => array(
						'message_box_color' => 'warning',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fa fa-exclamation-triangle',
					),
				),
				array(
					'label' => __( 'Success', 'js_composer' ),
					'value' => 'success',
					'params' => array(
						'message_box_color' => 'success',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fa fa-check',
					),
				),
				array(
					'label' => __( 'Error', 'js_composer' ),
					'value' => 'danger',
					'params' => array(
						'message_box_color' => 'danger',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fa fa-times',
					),
				),
				array(
					'label' => __( 'Informational Classic', 'js_composer' ),
					'value' => 'alert-info', // due to backward compatibility
					'params' => array(
						'message_box_color' => 'alert-info',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-info',
					),
				),
				array(
					'label' => __( 'Warning Classic', 'js_composer' ),
					'value' => 'alert-warning', // due to backward compatibility
					'params' => array(
						'message_box_color' => 'alert-warning',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-alert',
					),
				),
				array(
					'label' => __( 'Success Classic', 'js_composer' ),
					'value' => 'alert-success',  // due to backward compatibility
					'params' => array(
						'message_box_color' => 'alert-success',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-tick',
					),
				),
				array(
					'label' => __( 'Error Classic', 'js_composer' ),
					'value' => 'alert-danger',  // due to backward compatibility
					'params' => array(
						'message_box_color' => 'alert-danger',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-explanation',
					),
				),
			),
			'description' => __( 'Select predefined message box design or choose "Custom" for custom styling.', 'js_composer' ),
			'param_holder_class' => 'vc_message-type vc_colored-dropdown',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'message_box_style',
			'value' => getVcShared( 'message_box_styles' ),
			'description' => __( 'Select message box design style.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'js_composer' ),
			'param_name' => 'style', // due to backward compatibility message_box_shape
			'std' => 'rounded',
			'value' => array(
				__( 'Square', 'js_composer' ) => 'square',
				__( 'Rounded', 'js_composer' ) => 'rounded',
				__( 'Round', 'js_composer' ) => 'round',
			),
			'description' => __( 'Select message box shape.', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'message_box_color',
			'value' => $custom_colors + getVcShared( 'colors' ),
			'description' => __( 'Select message box color.', 'js_composer' ),
			'param_holder_class' => 'vc_message-type vc_colored-dropdown',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon library', 'js_composer' ),
			'value' => array(
				__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
				__( 'Open Iconic', 'js_composer' ) => 'openiconic',
				__( 'Typicons', 'js_composer' ) => 'typicons',
				__( 'Entypo', 'js_composer' ) => 'entypo',
				__( 'Linecons', 'js_composer' ) => 'linecons',
				__( 'Pixel', 'js_composer' ) => 'pixelicons',
			),
			'param_name' => 'icon_type',
			'description' => __( 'Select icon library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_fontawesome',
			'value' => 'fa fa-info-circle',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'fontawesome',
			),
			'description' => __( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_openiconic',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'openiconic',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'openiconic',
			),
			'description' => __( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_typicons',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'typicons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
			'description' => __( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_entypo',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'entypo',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'entypo',
			),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_linecons',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'linecons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'linecons',
			),
			'description' => __( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon_pixelicons',
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'pixelicons',
				'source' => $pixel_icons,
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'pixelicons',
			),
			'description' => __( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'messagebox_text',
			'heading' => __( 'Message text', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( '<p>I am message box. Click edit button to change this text.</p>', 'js_composer' )
		),
		$vc_add_css_animation_no_label,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
	'js_view' => 'VcMessageView_Backend'
) );

/* Facebook like button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Facebook Like', 'js_composer' ),
	'base' => 'vc_facebook',
	'icon' => 'icon-wpb-balloon-facebook-left',
	'category' => __( 'Social', 'js_composer' ),
	'description' => __( 'Facebook "Like" button', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button type', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'standard',
				__( 'Horizontal with count', 'js_composer' ) => 'button_count',
				__( 'Vertical with count', 'js_composer' ) => 'box_count'
			),
			'description' => __( 'Select button type.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Tweetmeme button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Tweetmeme Button', 'js_composer' ),
	'base' => 'vc_tweetmeme',
	'icon' => 'icon-wpb-tweetme',
	'show_settings_on_create' => false,
	'category' => __( 'Social', 'js_composer' ),
	'description' => __( '"Tweet" button', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button type', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Horizontal with count', 'js_composer' ) => 'horizontal',
				__( 'Vertical with count', 'js_composer' ) => 'vertical',
				__( 'Horizontal', 'js_composer' ) => 'none'
			),
			'description' => __( 'Select button type.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Google+ button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Google+ Button', 'js_composer' ),
	'base' => 'vc_googleplus',
	'icon' => 'icon-wpb-application-plus',
	'category' => __( 'Social', 'js_composer' ),
	'description' => __( 'Recommend on Google', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button size', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Standard', 'js_composer' ) => 'standard',
				__( 'Small', 'js_composer' ) => 'small',
				__( 'Medium', 'js_composer' ) => 'medium',
				__( 'Tall', 'js_composer' ) => 'tall'
			),
			'description' => __( 'Select button size.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Annotation', 'js_composer' ),
			'param_name' => 'annotation',
			'admin_label' => true,
			'value' => array(
				__( 'Bubble', 'js_composer' ) => 'bubble',
				__( 'Inline', 'js_composer' ) => 'inline',
				__( 'None', 'js_composer' ) => 'none',
			),
			'std' => 'bubble',
			'description' => __( 'Select type of annotation.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Width', 'js_composer' ),
			'param_name' => 'widget_width',
			'dependency' => array(
				'element' => 'annotation',
				'value' => array( 'inline' )
			),
			'description' => __( 'Minimum width of 120px to display. If annotation is set to "inline", this parameter sets the width in pixels to use for button and its inline annotation. Default width is 450px.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Pinterest button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Pinterest', 'js_composer' ),
	'base' => 'vc_pinterest',
	'icon' => 'icon-wpb-pinterest',
	'category' => __( 'Social', 'js_composer' ),
	'description' => __( 'Pinterest button', 'js_composer' ),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button type', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'horizontal',
				__( 'Vertical', 'js_composer' ) => 'vertical',
				__( 'No count', 'js_composer' ) => 'none'
			),
			'description' => __( 'Select button layout.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Toggle 2
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'FAQ', 'js_composer' ),
	'base' => 'vc_toggle',
	'icon' => 'icon-wpb-toggle-small-expand',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Toggle element for Q&A block', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'holder' => 'h4',
			'class' => 'vc_toggle_title',
			'heading' => __( 'Toggle title', 'js_composer' ),
			'param_name' => 'title',
			'value' => __( 'Toggle title', 'js_composer' ),
			'description' => __( 'Enter title of toggle block.', 'js_composer' )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'vc_toggle_content',
			'heading' => __( 'Toggle content', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( '<p>Toggle content goes here, click edit button to change this text.</p>', 'js_composer' ),
			'description' => __( 'Toggle block content.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'toggle styles' ),
			'description' => __( 'Select toggle design style.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon color', 'js_composer' ),
			'param_name' => 'color',
			'value' => array( __( 'Default', 'js_composer' ) => 'default' ) + getVcShared( 'colors' ),
			'description' => __( 'Select icon color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => array_diff_key( getVcShared( 'sizes' ), array( 'Mini' => '' ) ),
			'std' => 'md',
			'description' => __( 'Select toggle size', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Default state', 'js_composer' ),
			'param_name' => 'open',
			'value' => array(
				__( 'Closed', 'js_composer' ) => 'false',
				__( 'Open', 'js_composer' ) => 'true'
			),
			'description' => __( 'Select "Open" if you want toggle to be open by default.', 'js_composer' )
		),
		$vc_add_css_animation,
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter optional ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'js_composer' ), '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . __( 'link', 'js_composer' ) . '</a>' ),
			'settings' => array(
				'auto_generate' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
	'js_view' => 'VcToggleView'
) );

/* Single image */
vc_map( array(
	'name' => __( 'Single Image', 'js_composer' ),
	'base' => 'vc_single_image',
	'icon' => 'icon-wpb-single-image',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Simple image with CSS animation', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image source', 'js_composer' ),
			'param_name' => 'source',
			'value' => array(
				__( 'Media library', 'js_composer' ) => 'media_library',
				__( 'External link', 'js_composer' ) => 'external_link',
				__( 'Featured Image', 'js_composer' ) => 'featured_image'
			),
			'std' => 'media_library',
			'description' => __( 'Select image source.', 'js_composer' )
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'js_composer' ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'media_library'
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'External link', 'js_composer' ),
			'param_name' => 'custom_src',
			'description' => __( 'Select external link.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', 'js_composer' ),
			'param_name' => 'img_size',
			'value' => 'thumbnail',
			'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => array( 'media_library', 'featured_image' )
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', 'js_composer' ),
			'param_name' => 'external_img_size',
			'value' => '',
			'description' => __( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Caption', 'js_composer' ),
			'param_name' => 'caption',
			'description' => __( 'Enter text for image caption.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Add caption?', 'js_composer' ),
			'param_name' => 'add_caption',
			'description' => __( 'Add image caption.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'dependency' => array(
				'element' => 'source',
				'value' => array( 'media_library', 'featured_image' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image alignment', 'js_composer' ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'Left', 'js_composer' ) => 'left',
				__( 'Right', 'js_composer' ) => 'right',
				__( 'Center', 'js_composer' ) => 'center'
			),
			'description' => __( 'Select image alignment.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image style', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'single image styles' ),
			'description' => __( 'Select image display style.', 'js_comopser' ),
			'dependency' => array(
				'element' => 'source',
				'value' => array( 'media_library', 'featured_image' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image style', 'js_composer' ),
			'param_name' => 'external_style',
			'value' => getVcShared( 'single image external styles' ),
			'description' => __( 'Select image display style.', 'js_comopser' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border color', 'js_composer' ),
			'param_name' => 'border_color',
			'value' => getVcShared( 'colors' ),
			'std' => 'grey',
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle' )
			),
			'description' => __( 'Border color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border color', 'js_composer' ),
			'param_name' => 'external_border_color',
			'value' => getVcShared( 'colors' ),
			'std' => 'grey',
			'dependency' => array(
				'element' => 'external_style',
				'value' => array( 'vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle' )
			),
			'description' => __( 'Border color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click action', 'js_composer' ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'None', 'js_composer' ) => '',
				__( 'Link to large image', 'js_composer' ) => 'img_link_large',
				__( 'Open prettyPhoto', 'js_composer' ) => 'link_image',
				__( 'Open custom link', 'js_composer' ) => 'custom_link',
				__( 'Zoom', 'js_composer' ) => 'zoom',
			),
			'description' => __( 'Select action for click action.', 'js_composer' ),
			'std' => ''
		),
		array(
			'type' => 'href',
			'heading' => __( 'Image link', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'js_composer' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => 'custom_link',
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link Target', 'js_composer' ),
			'param_name' => 'img_link_target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link', 'img_link_large' ),
			),
		),
		$vc_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
		// backward compatibility. since 4.6
		array(
			'type' => 'hidden',
			'param_name' => 'img_link_large'
		)
	)
) );

/* Gallery/Slideshow
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Image Gallery', 'js_composer' ),
	'base' => 'vc_gallery',
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Responsive image gallery', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Gallery type', 'js_composer' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Flex slider fade', 'js_composer' ) => 'flexslider_fade',
				__( 'Flex slider slide', 'js_composer' ) => 'flexslider_slide',
				__( 'Nivo slider', 'js_composer' ) => 'nivo',
				__( 'Image grid', 'js_composer' ) => 'image_grid'
			),
			'description' => __( 'Select gallery type.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate', 'js_composer' ),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, __( 'Disable', 'js_composer' ) => 0 ),
			'description' => __( 'Auto rotate slides each X seconds.', 'js_composer' ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide', 'nivo' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image source', 'js_composer' ),
			'param_name' => 'source',
			'value' => array(
				__( 'Media library', 'js_composer' ) => 'media_library',
				__( 'External links', 'js_composer' ) => 'external_link'
			),
			'std' => 'media_library',
			'description' => __( 'Select image source.', 'js_composer' )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', 'js_composer' ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'media_library'
			),
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'External links', 'js_composer' ),
			'param_name' => 'custom_srcs',
			'description' => __( 'Enter external link for each gallery image (Note: divide links with linebreaks (Enter)).', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', 'js_composer' ),
			'param_name' => 'img_size',
			'value' => 'thumbnail',
			'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'media_library'
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', 'js_composer' ),
			'param_name' => 'external_img_size',
			'value' => '',
			'description' => __( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'value' => 'external_link'
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click action', 'js_composer' ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'None', 'js_composer' ) => '',
				__( 'Link to large image', 'js_composer' ) => 'img_link_large',
				__( 'Open prettyPhoto', 'js_composer' ) => 'link_image',
				__( 'Open custom link', 'js_composer' ) => 'custom_link',
			),
			'description' => __( 'Select action for click action.', 'js_composer' ),
			'std' => 'link_image'
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', 'js_composer' ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'js_composer' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', 'js_composer' ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select where to open  custom links.', 'js_composer' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link', 'img_link_large' ),
			),
			'value' => $target_arr
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Image Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Image Carousel', 'js_composer' ),
	'base' => 'vc_images_carousel',
	'icon' => 'icon-wpb-images-carousel',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Animated carousel with images', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', 'js_composer' ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Carousel size', 'js_composer' ),
			'param_name' => 'img_size',
			'value' => 'thumbnail',
			'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size. If used slides per view, this will be used to define carousel wrapper size.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click action', 'js_composer' ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open prettyPhoto', 'js_composer' ) => 'link_image',
				__( 'None', 'js_composer' ) => 'link_no',
				__( 'Open custom links', 'js_composer' ) => 'custom_link'
			),
			'description' => __( 'Select action for click event.', 'js_composer' )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', 'js_composer' ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'js_composer' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', 'js_composer' ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select how to open custom links.', 'js_composer' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider orientation', 'js_composer' ),
			'param_name' => 'mode',
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'horizontal',
				__( 'Vertical', 'js_composer' ) => 'vertical'
			),
			'description' => __( 'Select slider position (Note: this affects swiping orientation).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slider speed', 'js_composer' ),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => __( 'Duration of animation between slides (in ms).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slides per view', 'js_composer' ),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => __( 'Enter number of slides to display at the same time.', 'js_composer' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider autoplay', 'js_composer' ),
			'param_name' => 'autoplay',
			'description' => __( 'Enable autoplay mode.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide pagination control', 'js_composer' ),
			'param_name' => 'hide_pagination_control',
			'description' => __( 'If checked, pagination controls will be hidden.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide prev/next buttons', 'js_composer' ),
			'param_name' => 'hide_prev_next_buttons',
			'description' => __( 'If checked, prev/next buttons will be hidden.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Partial view', 'js_composer' ),
			'param_name' => 'partial_view',
			'description' => __( 'If checked, part of the next slide will be visible.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider loop', 'js_composer' ),
			'param_name' => 'wrap',
			'description' => __( 'Enable slider loop mode.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/**
 * @since 4.6 new TTA, tabs, tours, accordions and pageable
 */
include_once "shortcode-vc-tta-tabs.php";
include_once "shortcode-vc-tta-tour.php";
include_once "shortcode-vc-tta-accordion.php";
include_once "shortcode-vc-tta-pageable.php";
include_once "shortcode-vc-tta-section.php";

/* Tabs
---------------------------------------------------------- */
$tab_id_1 = ''; // 'def' . time() . '-1-' . rand( 0, 100 );
$tab_id_2 = ''; // 'def' . time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'Tabs', 'js_composer' ),
	'base' => 'vc_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
	'category' => __( 'Content', 'js_composer' ),
	'deprecated' => '4.6',
	'description' => __( 'Tabbed content', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate', 'js_composer' ),
			'param_name' => 'interval',
			'value' => array( __( 'Disable', 'js_composer' ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate tabs each X seconds.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . __( 'Tab 1', 'js_composer' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . __( 'Tab 2', 'js_composer' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

/* Tour section
---------------------------------------------------------- */
$tab_id_1 = ''; // time() . '-1-' . rand( 0, 100 );
$tab_id_2 = ''; // time() . '-2-' . rand( 0, 100 );
vc_map( array(
	'name' => __( 'Tour', 'js_composer' ),
	'base' => 'vc_tour',
	'show_settings_on_create' => false,
	'is_container' => true,
	'container_not_allowed' => true,
	'deprecated' => '4.6',
	'icon' => 'icon-wpb-ui-tab-content-vertical',
	'category' => __( 'Content', 'js_composer' ),
	'wrapper_class' => 'vc_clearfix',
	'description' => __( 'Vertical tabbed content', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate slides', 'js_composer' ),
			'param_name' => 'interval',
			'value' => array( __( 'Disable', 'js_composer' ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate slides each X seconds.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_clearfix vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . __( 'Tab 1', 'js_composer' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . __( 'Tab 2', 'js_composer' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

vc_map( array(
	'name' => __( 'Tab', 'js_composer' ),
	'base' => 'vc_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'deprecated' => '4.6',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter title of tab.', 'js_composer' )
		),
		array(
			'type' => 'tab_id',
			'heading' => __( 'Tab ID', 'js_composer' ),
			'param_name' => "tab_id"
		)
	),
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35'
) );

/* Accordion block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Accordion', 'js_composer' ),
	'base' => 'vc_accordion',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-accordion',
	'deprecated' => '4.6',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Collapsible content panels', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Active section', 'js_composer' ),
			'param_name' => 'active_tab',
			'value' => 1,
			'description' => __( 'Enter section number to be active on load or enter "false" to collapse all sections.', 'js_composer' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Allow collapse all sections?', 'js_composer' ),
			'param_name' => 'collapsible',
			'description' => __( 'If checked, it is allowed to collapse all sections.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Disable keyboard interactions?', 'js_composer' ),
			'param_name' => 'disable_keyboard',
			'description' => __( 'If checked, disables keyboard arrow interactions (Keys: Left, Up, Right, Down, Space).', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	),
	'custom_markup' => '
<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
%content%
</div>
<div class="tab_controls">
    <a class="add_tab" title="' . __( 'Add section', 'js_composer' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'js_composer' ) . '</span></a>
</div>
',
	'default_content' => '
    [vc_accordion_tab title="' . __( 'Section 1', 'js_composer' ) . '"][/vc_accordion_tab]
    [vc_accordion_tab title="' . __( 'Section 2', 'js_composer' ) . '"][/vc_accordion_tab]
',
	'js_view' => 'VcAccordionView'
) );
vc_map( array(
	'name' => __( 'Section', 'js_composer' ),
	'base' => 'vc_accordion_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'deprecated' => '4.6',
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'value' => __( 'Section', 'js_composer' ),
			'description' => __( 'Enter accordion section title.', 'js_composer' )
		),
		array(
			'type' => 'el_id',
			'heading' => __( 'Section ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'js_composer' ), '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . __( 'link', 'js_composer' ) . '</a>' ),
		),
	),
	'js_view' => 'VcAccordionTabView'
) );

/* Posts Grid
---------------------------------------------------------- */
$vc_layout_sub_controls = array(
	array( 'link_post', __( 'Link to post', 'js_composer' ) ),
	array( 'no_link', __( 'No link', 'js_composer' ) ),
	array( 'link_image', __( 'Link to bigger image', 'js_composer' ) )
);
vc_map( array(
	'name' => __( 'Posts Grid', 'js_composer' ),
	'base' => 'vc_posts_grid',
	'content_element' => false,
	'deprecated' => '4.4',
	'icon' => 'icon-wpb-application-icon-large',
	'description' => __( 'Posts in grid view', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'loop',
			'heading' => __( 'Grids content', 'js_composer' ),
			'param_name' => 'loop',
			'value' => 'size:10|order_by:date',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'date' ),
			),
			'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns count', 'js_composer' ),
			'param_name' => 'grid_columns_count',
			'value' => array( 6, 4, 3, 2, 1 ),
			'std' => 3,
			'admin_label' => true,
			'description' => __( 'Select columns count.', 'js_composer' )
		),
		array(
			'type' => 'sorted_list',
			'heading' => __( 'Teaser layout', 'js_composer' ),
			'param_name' => 'grid_layout',
			'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', 'js_composer' ),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', __( 'Thumbnail', 'js_composer' ), $vc_layout_sub_controls ),
				array( 'title', __( 'Title', 'js_composer' ), $vc_layout_sub_controls ),
				array(
					'text',
					__( 'Text', 'js_composer' ),
					array(
						array( 'excerpt', __( 'Teaser/Excerpt', 'js_composer' ) ),
						array( 'text', __( 'Full content', 'js_composer' ) )
					)
				),
				array( 'link', __( 'Read more link', 'js_composer' ) )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link target', 'js_composer' ),
			'param_name' => 'grid_link_target',
			'value' => $target_arr,
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show filter', 'js_composer' ),
			'param_name' => 'filter',
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'description' => __( 'Select to add animated category filter to your posts grid.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout mode', 'js_composer' ),
			'param_name' => 'grid_layout_mode',
			'value' => array(
				__( 'Fit rows', 'js_composer' ) => 'fitRows',
				__( 'Masonry', 'js_composer' ) => 'masonry'
			),
			'description' => __( 'Teaser layout template.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', 'js_composer' ),
			'param_name' => 'grid_thumb_size',
			'value' => 'thumbnail',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

/* Post Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Post Carousel', 'js_composer' ),
	'base' => 'vc_carousel',
	'content_element' => false,
	'deprecated' => '4.4',
	'class' => '',
	'icon' => 'icon-wpb-vc_carousel',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Animated carousel with posts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'loop',
			'heading' => __( 'Carousel content', 'js_composer' ),
			'param_name' => 'posts_query',
			'value' => 'size:10|order_by:date',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'date' )
			),
			'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
		),
		array(
			'type' => 'sorted_list',
			'heading' => __( 'Teaser layout', 'js_composer' ),
			'param_name' => 'layout',
			'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', 'js_composer' ),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', __( 'Thumbnail', 'js_composer' ), $vc_layout_sub_controls ),
				array( 'title', __( 'Title', 'js_composer' ), $vc_layout_sub_controls ),
				array(
					'text',
					__( 'Text', 'js_composer' ),
					array(
						array( 'excerpt', __( 'Teaser/Excerpt', 'js_composer' ) ),
						array( 'text', __( 'Full content', 'js_composer' ) )
					)
				),
				array( 'link', __( 'Read more link', 'js_composer' ) )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link target', 'js_composer' ),
			'param_name' => 'link_target',
			'value' => $target_arr,
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', 'js_composer' ),
			'param_name' => 'thumb_size',
			'value' => 'thumbnail',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slider speed', 'js_composer' ),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => __( 'Duration of animation between slides (in ms).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider orientation', 'js_composer' ),
			'param_name' => 'mode',
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'horizontal',
				__( 'Vertical', 'js_composer' ) => 'vertical'
			),
			'description' => __( 'Select slider position (Note: this affects swiping orientation).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slides per view', 'js_composer' ),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => __( 'Enter number of slides to display at the same time.', 'js_composer' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider autoplay', 'js_composer' ),
			'param_name' => 'autoplay',
			'description' => __( 'Enable autoplay mode.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide pagination control', 'js_composer' ),
			'param_name' => 'hide_pagination_control',
			'description' => __( 'If "YES" pagination control will be removed', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide prev/next buttons', 'js_composer' ),
			'param_name' => 'hide_prev_next_buttons',
			'description' => __( 'If "YES" prev/next control will be removed', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Partial view', 'js_composer' ),
			'param_name' => 'partial_view',
			'description' => __( 'If "YES" part of the next slide will be visible on the right side', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider loop', 'js_composer' ),
			'param_name' => 'wrap',
			'description' => __( 'Enable slider loop mode.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
	)
) );

/* Posts slider
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Posts Slider', 'js_composer' ),
	'base' => 'vc_posts_slider',
	'icon' => 'icon-wpb-slideshow',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Slider with WP Posts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider type', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Flex slider fade', 'js_composer' ) => 'flexslider_fade',
				__( 'Flex slider slide', 'js_composer' ) => 'flexslider_slide',
				__( 'Nivo slider', 'js_composer' ) => 'nivo'
			),
			'description' => __( 'Select slider type.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slider count', 'js_composer' ),
			'param_name' => 'count',
			'value' => 3,
			'description' => __( 'Enter number of slides to display (Note: Enter "All" to display all slides).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate', 'js_composer' ),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, __( 'Disable', 'js_composer' ) => 0 ),
			'description' => __( 'Auto rotate slides each X seconds.', 'js_composer' )
		),
		array(
			'type' => 'posttypes',
			'heading' => __( 'Post types', 'js_composer' ),
			'param_name' => 'posttypes',
			'description' => __( 'Select source for slider.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Description', 'js_composer' ),
			'param_name' => 'slides_content',
			'value' => array(
				__( 'No description', 'js_composer' ) => '',
				__( 'Teaser (Excerpt)', 'js_composer' ) => 'teaser'
			),
			'description' => __( 'Select source to use for description (Note: some sliders do not support it).', 'js_composer' ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide' )
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Output post title?', 'js_composer' ),
			'param_name' => 'slides_title',
			'description' => __( 'If selected, title will be printed before the teaser text.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => true ),
			'dependency' => array(
				'element' => 'slides_content',
				'value' => array( 'teaser' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link', 'js_composer' ),
			'param_name' => 'link',
			'value' => array(
				__( 'Link to post', 'js_composer' ) => 'link_post',
				__( 'Link to bigger image', 'js_composer' ) => 'link_image',
				__( 'Open custom links', 'js_composer' ) => 'custom_link',
				__( 'No link', 'js_composer' ) => 'link_no'
			),
			'description' => __( 'Link type.', 'js_composer' )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', 'js_composer' ),
			'param_name' => 'custom_links',
			'value' => site_url() . '/',
			'dependency' => array( 'element' => 'link', 'value' => 'custom_link' ),
			'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', 'js_composer' ),
			'param_name' => 'thumb_size',
			'value' => 'medium',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Post/Page IDs', 'js_composer' ),
			'param_name' => 'posts_in',
			'description' => __( 'Enter page/posts IDs to display only those records (Note: separate values by commas (,)). Use this field in conjunction with "Post types" field.', 'js_composer' )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Categories', 'js_composer' ),
			'param_name' => 'categories',
			'description' => __( 'Enter categories by names to narrow output (Note: only listed categories will be displayed, divide categories with linebreak (Enter)).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', 'js_composer' ),
			'param_name' => 'orderby',
			'value' => array(
				'',
				__( 'Date', 'js_composer' ) => 'date',
				__( 'ID', 'js_composer' ) => 'ID',
				__( 'Author', 'js_composer' ) => 'author',
				__( 'Title', 'js_composer' ) => 'title',
				__( 'Modified', 'js_composer' ) => 'modified',
				__( 'Random', 'js_composer' ) => 'rand',
				__( 'Comment count', 'js_composer' ) => 'comment_count',
				__( 'Menu order', 'js_composer' ) => 'menu_order'
			),
			'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Sort order', 'js_composer' ),
			'param_name' => 'order',
			'value' => array(
				__( 'Descending', 'js_composer' ) => 'DESC',
				__( 'Ascending', 'js_composer' ) => 'ASC'
			),
			'description' => sprintf( __( 'Select ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Widgetised sidebar
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Widgetised Sidebar', 'js_composer' ),
	'base' => 'vc_widget_sidebar',
	'class' => 'wpb_widget_sidebar_widget',
	'icon' => 'icon-wpb-layout_sidebar',
	'category' => __( 'Structure', 'js_composer' ),
	'description' => __( 'WordPress widgetised sidebar', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'widgetised_sidebars',
			'heading' => __( 'Sidebar', 'js_composer' ),
			'param_name' => 'sidebar_id',
			'description' => __( 'Select widget area to display.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

/* Button
---------------------------------------------------------- */
$icons_arr = array(
	__( 'None', 'js_composer' ) => 'none',
	__( 'Address book icon', 'js_composer' ) => 'wpb_address_book',
	__( 'Alarm clock icon', 'js_composer' ) => 'wpb_alarm_clock',
	__( 'Anchor icon', 'js_composer' ) => 'wpb_anchor',
	__( 'Application Image icon', 'js_composer' ) => 'wpb_application_image',
	__( 'Arrow icon', 'js_composer' ) => 'wpb_arrow',
	__( 'Asterisk icon', 'js_composer' ) => 'wpb_asterisk',
	__( 'Hammer icon', 'js_composer' ) => 'wpb_hammer',
	__( 'Balloon icon', 'js_composer' ) => 'wpb_balloon',
	__( 'Balloon Buzz icon', 'js_composer' ) => 'wpb_balloon_buzz',
	__( 'Balloon Facebook icon', 'js_composer' ) => 'wpb_balloon_facebook',
	__( 'Balloon Twitter icon', 'js_composer' ) => 'wpb_balloon_twitter',
	__( 'Battery icon', 'js_composer' ) => 'wpb_battery',
	__( 'Binocular icon', 'js_composer' ) => 'wpb_binocular',
	__( 'Document Excel icon', 'js_composer' ) => 'wpb_document_excel',
	__( 'Document Image icon', 'js_composer' ) => 'wpb_document_image',
	__( 'Document Music icon', 'js_composer' ) => 'wpb_document_music',
	__( 'Document Office icon', 'js_composer' ) => 'wpb_document_office',
	__( 'Document PDF icon', 'js_composer' ) => 'wpb_document_pdf',
	__( 'Document Powerpoint icon', 'js_composer' ) => 'wpb_document_powerpoint',
	__( 'Document Word icon', 'js_composer' ) => 'wpb_document_word',
	__( 'Bookmark icon', 'js_composer' ) => 'wpb_bookmark',
	__( 'Camcorder icon', 'js_composer' ) => 'wpb_camcorder',
	__( 'Camera icon', 'js_composer' ) => 'wpb_camera',
	__( 'Chart icon', 'js_composer' ) => 'wpb_chart',
	__( 'Chart pie icon', 'js_composer' ) => 'wpb_chart_pie',
	__( 'Clock icon', 'js_composer' ) => 'wpb_clock',
	__( 'Fire icon', 'js_composer' ) => 'wpb_fire',
	__( 'Heart icon', 'js_composer' ) => 'wpb_heart',
	__( 'Mail icon', 'js_composer' ) => 'wpb_mail',
	__( 'Play icon', 'js_composer' ) => 'wpb_play',
	__( 'Shield icon', 'js_composer' ) => 'wpb_shield',
	__( 'Video icon', 'js_composer' ) => "wpb_video"
);

vc_map( array(
	'name' => __( 'Button', 'js_composer' ) . " 1",
	'base' => 'vc_button',
	'icon' => 'icon-wpb-ui-button',
	'category' => __( 'Content', 'js_composer' ),
	'deprecated' => '4.5',
	'description' => __( 'Eye catching button', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Text', 'js_composer' ),
			'holder' => 'button',
			'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Enter text on the button.', 'js_composer' )
		),
		array(
			'type' => 'href',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'href',
			'description' => __( 'Enter button link.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', 'js_composer' ),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'href',
				'not_empty' => true,
				'callback' => 'vc_button_param_target_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => __( 'Select button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon', 'js_composer' ),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => __( 'Select icon to display on button.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => __( 'Select button size.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
	),
	'js_view' => 'VcButtonView'
) );

vc_map( array(
	'name' => __( 'Button', 'js_composer' ) . " 2",
	'base' => 'vc_button2',
	'icon' => 'icon-wpb-ui-button',
	'deprecated' => '4.5',
	'category' => array(
		__( 'Content', 'js_composer' )
	),
	'description' => __( 'Eye catching button', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Add link to button.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text', 'js_composer' ),
			'holder' => 'button',
			'class' => 'vc_btn',
			'param_name' => 'title',
			'value' => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Enter text on the button.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'js_composer' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Inline', 'js_composer' ) => "inline",
				__( 'Left', 'js_composer' ) => 'left',
				__( 'Center', 'js_composer' ) => 'center',
				__( 'Right', 'js_composer' ) => "right"
			),
			'description' => __( 'Select button alignment.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'button styles' ),
			'description' => __( 'Select button display style and shape.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => __( 'Select button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => __( 'Select button size.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	),
	'js_view' => 'VcButton2View'
) );

/* Call to Action Button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Call to Action', 'js_composer' ),
	'base' => 'vc_cta_button',
	'icon' => 'icon-wpb-call-to-action',
	'deprecated' => '4.5',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Catch visitors attention with CTA block', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textarea',
			'admin_label' => true,
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'call_text',
			'value' => __( 'Click edit button to change this text.', 'js_composer' ),
			'description' => __( 'Enter text content.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', 'js_composer' ),
			'param_name' => 'title',
			'value' => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Enter text on the button.', 'js_composer' )
		),
		array(
			'type' => 'href',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'href',
			'description' => __( 'Enter button link.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', 'js_composer' ),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'href',
				'not_empty' => true,
				'callback' => 'vc_cta_button_param_target_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => __( 'Select button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button icon', 'js_composer' ),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => __( 'Select icon to display on button.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => __( 'Select button size.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button position', 'js_composer' ),
			'param_name' => 'position',
			'value' => array(
				__( 'Right', 'js_composer' ) => 'cta_align_right',
				__( 'Left', 'js_composer' ) => 'cta_align_left',
				__( 'Bottom', 'js_composer' ) => 'cta_align_bottom'
			),
			'description' => __( 'Select button alignment.', 'js_composer' )
		),
		$vc_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	),
	'js_view' => 'VcCallToActionView'
) );

vc_map( array(
	'name' => __( 'Call to Action Button', 'js_composer' ) . ' 2',
	'base' => 'vc_cta_button2',
	'icon' => 'icon-wpb-call-to-action',
	'deprecated' => '4.5',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'description' => __( 'Catch visitors attention with CTA block', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Heading', 'js_composer' ),
			'admin_label' => true,
			'param_name' => 'h2',
			'value' => __( 'Hey! I am first heading line feel free to change me', 'js_composer' ),
			'description' => __( 'Enter text for heading line.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Subheading', 'js_composer' ),
			'param_name' => 'h4',
			'value' => '',
			'description' => __( 'Enter text for subheading line.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'js_composer' ),
			'param_name' => 'style',
			'value' => getVcShared( 'cta styles' ),
			'description' => __( 'Select display shape and style.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'cta widths' ),
			'description' => __( 'Select element width (percentage).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text alignment', 'js_composer' ),
			'param_name' => 'txt_align',
			'value' => getVcShared( 'text align' ),
			'description' => __( 'Select text alignment in "Call to Action" block.', 'js_composer' )
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background color', 'js_composer' ),
			'param_name' => 'accent_color',
			'description' => __( 'Select background color.', 'js_composer' )
		),
		array(
			'type' => 'textarea_html',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( 'I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'js_composer' )
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Add link to button (Important: adding link automatically adds button).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', 'js_composer' ),
			'param_name' => 'title',
			'value' => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Add text on the button.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'js_composer' ),
			'param_name' => 'btn_style',
			'value' => getVcShared( 'button styles' ),
			'description' => __( 'Select button display style and shape.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => __( 'Select button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => __( 'Select button size.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button position', 'js_composer' ),
			'param_name' => 'position',
			'value' => array(
				__( 'Right', 'js_composer' ) => 'right',
				__( 'Left', 'js_composer' ) => 'left',
				__( 'Bottom', 'js_composer' ) => 'bottom'
			),
			'description' => __( 'Select button alignment.', 'js_composer' )
		),
		$vc_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

/* Video element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Video Player', 'js_composer' ),
	'base' => 'vc_video',
	'icon' => 'icon-wpb-film-youtube',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Embed YouTube/Vimeo player', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Video link', 'js_composer' ),
			'param_name' => 'link',
			'value' => 'http://vimeo.com/92033601',
			'admin_label' => true,
			'description' => sprintf( __( 'Enter link to video (Note: read more about available formats at WordPress <a href="%s" target="_blank">codex page</a>).', 'js_composer' ), 'http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* Google maps element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Google Maps', 'js_composer' ),
	'base' => 'vc_gmaps',
	'icon' => 'icon-wpb-map-pin',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Map block', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'textarea_safe',
			'heading' => __( 'Map embed iframe', 'js_composer' ),
			'param_name' => 'link',
			'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6304.829986131271!2d-122.4746968033092!3d37.80374752160443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808586e6302615a1%3A0x86bd130251757c00!2sStorey+Ave%2C+San+Francisco%2C+CA+94129!5e0!3m2!1sen!2sus!4v1435826432051" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
			'description' => sprintf( __( 'Visit %s to create your map (Step by step: 1) Find location 2) Click the cog symbol in the lower right corner and select "Share or embed map" 3) On modal window select "Embed map" 4) Copy iframe code and paste it).' ),
				'<a href="https://www.google.com/maps" target="_blank">' . __( 'Google maps', 'js_composer' ) . '</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map height', 'js_composer' ),
			'param_name' => 'size',
			'value' => 'standard',
			'admin_label' => true,
			'description' => __( 'Enter map height (in pixels or leave empty for responsive map).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Raw HTML
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Raw HTML', 'js_composer' ),
	'base' => 'vc_raw_html',
	'icon' => 'icon-wpb-raw-html',
	'category' => __( 'Structure', 'js_composer' ),
	'wrapper_class' => 'clearfix',
	'description' => __( 'Output raw HTML code on your page', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => __( 'Raw HTML', 'js_composer' ),
			'param_name' => 'content',
			'value' => base64_encode( '<p>I am raw html block.<br/>Click edit button to change this html</p>' ),
			'description' => __( 'Enter your HTML content.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Raw JS
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Raw JS', 'js_composer' ),
	'base' => 'vc_raw_js',
	'icon' => 'icon-wpb-raw-javascript',
	'category' => __( 'Structure', 'js_composer' ),
	'wrapper_class' => 'clearfix',
	'description' => __( 'Output raw JavaScript code on your page', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => __( 'JavaScript Code', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( base64_encode( '<script type="text/javascript"> alert("Enter your js here!" ); </script>' ), 'js_composer' ),
			'description' => __( 'Enter your JavaScript code.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

/* Flickr
---------------------------------------------------------- */
vc_map( array(
	'base' => 'vc_flickr',
	'name' => __( 'Flickr Widget', 'js_composer' ),
	'icon' => 'icon-wpb-flickr',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Image feed from Flickr account', 'js_composer' ),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Flickr ID', 'js_composer' ),
			'param_name' => 'flickr_id',
			'value' => '95572727@N00',
			'admin_label' => true,
			'description' => sprintf( __( 'To find your flickID visit %s.', 'js_composer' ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Number of photos', 'js_composer' ),
			'param_name' => 'count',
			'value' => array( 9, 8, 7, 6, 5, 4, 3, 2, 1 ),
			'description' => __( 'Select number of photos to display.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', 'js_composer' ),
			'param_name' => 'type',
			'value' => array(
				__( 'User', 'js_composer' ) => 'user',
				__( 'Group', 'js_composer' ) => 'group'
			),
			'description' => __( 'Select photo stream type.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Display order', 'js_composer' ),
			'param_name' => 'display',
			'value' => array(
				__( 'Latest first', 'js_composer' ) => 'latest',
				__( 'Random', 'js_composer' ) => 'random'
			),
			'description' => __( 'Select photo display order.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* Graph
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Progress Bar', 'js_composer' ),
	'base' => 'vc_progress_bar',
	'icon' => 'icon-wpb-graph',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Animated progress bar', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'description' => __( 'Enter values for graph - value, title and color.', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'label' => __( 'Development', 'js_composer' ),
					'value' => '90',
				),
				array(
					'label' => __( 'Design', 'js_composer' ),
					'value' => '80',
				),
				array(
					'label' => __( 'Marketing', 'js_composer' ),
					'value' => '70',
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Label', 'js_composer' ),
					'param_name' => 'label',
					'description' => __( 'Enter text used as title of bar.', 'js_composer' ),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Value', 'js_composer' ),
					'param_name' => 'value',
					'description' => __( 'Enter value of bar.', 'js_composer' ),
					'admin_label' => true,
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Color', 'js_composer' ),
					'param_name' => 'color',
					'value' => array(
						           __( 'Default', 'js_composer' ) => ''
					           ) + array(
						           __( 'Classic Grey', 'js_composer' ) => 'bar_grey',
						           __( 'Classic Blue', 'js_composer' ) => 'bar_blue',
						           __( 'Classic Turquoise', 'js_composer' ) => 'bar_turquoise',
						           __( 'Classic Green', 'js_composer' ) => 'bar_green',
						           __( 'Classic Orange', 'js_composer' ) => 'bar_orange',
						           __( 'Classic Red', 'js_composer' ) => 'bar_red',
						           __( 'Classic Black', 'js_composer' ) => 'bar_black',
					           ) + getVcShared( 'colors-dashed' ) + array(
						           __( 'Custom Color', 'js_composer' ) => 'custom'
					           ),
					'description' => __( 'Select single bar background color.', 'js_composer' ),
					'admin_label' => true,
					'param_holder_class' => 'vc_colored-dropdown'
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Custom color', 'js_composer' ),
					'param_name' => 'customcolor',
					'description' => __( 'Select custom single bar background color.', 'js_composer' ),
					'dependency' => array(
						'element' => 'color',
						'value' => array( 'custom' )
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Custom text color', 'js_composer' ),
					'param_name' => 'customtxtcolor',
					'description' => __( 'Select custom single bar text color.', 'js_composer' ),
					'dependency' => array(
						'element' => 'color',
						'value' => array( 'custom' )
					),
				),
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Units', 'js_composer' ),
			'param_name' => 'units',
			'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'bgcolor',
			'value' => array(
				           __( 'Classic Grey', 'js_composer' ) => 'bar_grey',
				           __( 'Classic Blue', 'js_composer' ) => 'bar_blue',
				           __( 'Classic Turquoise', 'js_composer' ) => 'bar_turquoise',
				           __( 'Classic Green', 'js_composer' ) => 'bar_green',
				           __( 'Classic Orange', 'js_composer' ) => 'bar_orange',
				           __( 'Classic Red', 'js_composer' ) => 'bar_red',
				           __( 'Classic Black', 'js_composer' ) => 'bar_black',
			           ) + getVcShared( 'colors-dashed' ) + array(
				           __( 'Custom Color', 'js_composer' ) => 'custom'
			           ),
			'description' => __( 'Select bar background color.', 'js_composer' ),
			'admin_label' => true,
			'param_holder_class' => 'vc_colored-dropdown',
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Bar custom background color', 'js_composer' ),
			'param_name' => 'custombgcolor',
			'description' => __( 'Select custom background color for bars.', 'js_composer' ),
			'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) )
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Bar custom text color', 'js_composer' ),
			'param_name' => 'customtxtcolor',
			'description' => __( 'Select custom text color for bars.', 'js_composer' ),
			'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', 'js_composer' ),
			'param_name' => 'options',
			'value' => array(
				__( 'Add stripes', 'js_composer' ) => 'striped',
				__( 'Add animation (Note: visible only with striped bar).', 'js_composer' ) => 'animated'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/**
 * Pie chart
 */
vc_map( array(
	'name' => __( 'Pie Chart', 'js_composer' ),
	'base' => 'vc_pie',
	'class' => '',
	'icon' => 'icon-wpb-vc_pie',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Animated pie chart', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Value', 'js_composer' ),
			'param_name' => 'value',
			'description' => __( 'Enter value for graph (Note: choose range from 0 to 100).', 'js_composer' ),
			'value' => '50',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Label value', 'js_composer' ),
			'param_name' => 'label_value',
			'description' => __( 'Enter label for pie chart (Note: leaving empty will set value from "Value" field).', 'js_composer' ),
			'value' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Units', 'js_composer' ),
			'param_name' => 'units',
			'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => getVcShared( 'colors-dashed' ) + array( __( 'Custom', 'js_composer' ) => 'custom' ),
			'description' => __( 'Select pie chart color.', 'js_composer' ),
			'admin_label' => true,
			'param_holder_class' => 'vc_colored-dropdown',
			'std' => 'grey'
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom color', 'js_composer' ),
			'param_name' => 'custom_color',
			'description' => __( 'Select custom color.', 'js_composer' ),
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'custom' )
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/**
 * Round chart
 */
vc_map( array(
	'name' => __( 'Round Chart', 'js_composer' ),
	'base' => 'vc_round_chart',
	'class' => '',
	'icon' => 'icon-wpb-vc-round-chart',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Pie and Doughnat charts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Design', 'js_composer' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Pie', 'js_composer' ) => 'pie',
				__( 'Doughnut', 'js_composer' ) => 'doughnut',
			),
			'description' => __( 'Select type of chart.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'description' => __( 'Select chart color style.', 'js_composer' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Flat', 'js_composer' ) => 'flat',
				__( 'Modern', 'js_composer' ) => 'modern',
				__( 'Custom', 'js_composer' ) => 'custom',
			),
			'dependency' => array(
				'callback' => 'vcChartCustomColorDependency',
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Gap', 'js_composer' ),
			'param_name' => 'stroke_width',
			'value' => array(
				0 => 0,
				1 => 1,
				2 => 2,
				5 => 5,
			),
			'description' => __( 'Select gap size.', 'js_composer' ),
			'std' => 2
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Outline color', 'js_composer' ),
			'param_name' => 'stroke_color',
			'value' => getVcShared( 'colors-dashed' ) + array( __( 'Custom', 'js_composer' ) => 'custom' ),
			'description' => __( 'Select outline color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
			'std' => 'white',
			'dependency' => array(
				'element' => 'stroke_width',
				'value_not_equal_to' => '0'
			),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom outline color', 'js_composer' ),
			'param_name' => 'custom_stroke_color',
			'description' => __( 'Select custom outline color.', 'js_composer' ),
			'dependency' => array(
				'element' => 'stroke_color',
				'value' => array( 'custom' )
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show legend?', 'js_composer' ),
			'param_name' => 'legend',
			'description' => __( 'If checked, chart will have legend.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'std' => 'yes'
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show hover values?', 'js_composer' ),
			'param_name' => 'tooltips',
			'description' => __( 'If checked, chart will show values on hover.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'std' => 'yes'
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'value' => urlencode( json_encode( array(
				array(
					'title' => __( 'One', 'js_composer' ),
					'value' => '60',
					'color' => 'blue'
				),
				array(
					'title' => __( 'Two', 'js_composer' ),
					'value' => '40',
					'color' => 'pink'
				)
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'js_composer' ),
					'param_name' => 'title',
					'description' => __( 'Enter title for chart area.', 'js_composer' ),
					'admin_label' => true
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Value', 'js_composer' ),
					'param_name' => 'value',
					'description' => __( 'Enter value for area.', 'js_composer' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Color', 'js_composer' ),
					'param_name' => 'color',
					'value' => getVcShared( 'colors-dashed' ),
					'description' => __( 'Select area color.', 'js_composer' ),
					'param_holder_class' => 'vc_colored-dropdown',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Custom color', 'js_composer' ),
					'param_name' => 'custom_color',
					'description' => __( 'Select custom area color.', 'js_composer' ),
				),
			),
			'callbacks' => array(
				'after_add' => 'vcChartParamAfterAddCallback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animation', 'js_composer' ),
			'description' => __( 'Select animation style.', 'js_composer' ),
			'param_name' => 'animation',
			'value' => getVcShared( 'animation styles' ),
			'std' => 'easeinOutCubic'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/**
 * Line chart
 */
vc_map( array(
	'name' => __( 'Line Chart', 'js_composer' ),
	'base' => 'vc_line_chart',
	'class' => '',
	'icon' => 'icon-wpb-vc-line-chart',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Line and Bar charts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Design', 'js_composer' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Line', 'js_composer' ) => 'line',
				__( 'Bar', 'js_composer' ) => 'bar',
			),
			'std' => 'bar',
			'description' => __( 'Select type of chart.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'description' => __( 'Select chart color style.', 'js_composer' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Flat', 'js_composer' ) => 'flat',
				__( 'Modern', 'js_composer' ) => 'modern',
				__( 'Custom', 'js_composer' ) => 'custom',
			),
			'dependency' => array(
				'callback' => 'vcChartCustomColorDependency',
			)
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show legend?', 'js_composer' ),
			'param_name' => 'legend',
			'description' => __( 'If checked, chart will have legend.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'std' => 'yes'
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show hover values?', 'js_composer' ),
			'param_name' => 'tooltips',
			'description' => __( 'If checked, chart will show values on hover.', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'std' => 'yes'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'X-axis values', 'js_composer' ),
			'param_name' => 'x_values',
			'description' => __( 'Enter values for axis (Note: separate values with ";").', 'js_composer' ),
			'value' => 'JAN; FEB; MAR; APR; MAY; JUN; JUL; AUG'
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'value' => urlencode( json_encode( array(
				array(
					'title' => __( 'One', 'js_composer' ),
					'y_values' => '10; 15; 20; 25; 27; 25; 23; 25',
					'color' => 'blue'
				),
				array(
					'title' => __( 'Two', 'js_composer' ),
					'y_values' => '25; 18; 16; 17; 20; 25; 30; 35',
					'color' => 'pink'
				)
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'js_composer' ),
					'param_name' => 'title',
					'description' => __( 'Enter title for chart dataset.', 'js_composer' ),
					'admin_label' => true
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Y-axis values', 'js_composer' ),
					'param_name' => 'y_values',
					'description' => __( 'Enter values for axis (Note: separate values with ";").', 'js_composer' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Color', 'js_composer' ),
					'param_name' => 'color',
					'value' => getVcShared( 'colors-dashed' ),
					'description' => __( 'Select chart color.', 'js_composer' ),
					'param_holder_class' => 'vc_colored-dropdown',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Custom color', 'js_composer' ),
					'param_name' => 'custom_color',
					'description' => __( 'Select custom chart color.', 'js_composer' ),
				),
			),
			'callbacks' => array(
				'after_add' => 'vcChartParamAfterAddCallback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animation', 'js_composer' ),
			'description' => __( 'Select animation style.', 'js_composer' ),
			'param_name' => 'animation',
			'value' => getVcShared( 'animation styles' ),
			'std' => 'easeinOutCubic'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	)
) );

/* WordPress default Widgets (Appearance->Widgets)
---------------------------------------------------------- */
vc_map( array(
	'name' => 'WP ' . __( 'Search' ),
	'base' => 'vc_wp_search',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A search form for your site', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Meta' ),
	'base' => 'vc_wp_meta',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Log in/out, admin, feed and WordPress links', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Meta' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Recent Comments' ),
	'base' => 'vc_wp_recentcomments',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'The most recent comments', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Recent Comments' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of comments', 'js_composer' ),
			'description' => __( 'Enter number of comments to display.', 'js_composer' ),
			'param_name' => 'number',
			'value' => 5,
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Calendar' ),
	'base' => 'vc_wp_calendar',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A calendar of your sites posts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Pages' ),
	'base' => 'vc_wp_pages',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Your sites WordPress Pages', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Pages' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', 'js_composer' ),
			'param_name' => 'sortby',
			'value' => array(
				__( 'Page title', 'js_composer' ) => 'post_title',
				__( 'Page order', 'js_composer' ) => 'menu_order',
				__( 'Page ID', 'js_composer' ) => 'ID'
			),
			'description' => __( 'Select how to sort pages.', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Exclude', 'js_composer' ),
			'param_name' => 'exclude',
			'description' => __( 'Enter page IDs to be excluded (Note: separate values by commas (,)).', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

$tag_taxonomies = array();
if ( 'vc_edit_form' === vc_post_param( 'action' ) && vc_verify_admin_nonce() ) {
	$taxonomies = get_taxonomies();
	if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$tax = get_taxonomy( $taxonomy );
			if ( ( is_object( $tax ) && ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) ) || ! is_object( $tax ) ) {
				continue;
			}
			$tag_taxonomies[ $tax->labels->name ] = esc_attr( $taxonomy );
		}
	}
}
vc_map( array(
	'name' => 'WP ' . __( 'Tag Cloud' ),
	'base' => 'vc_wp_tagcloud',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Your most used tags in cloud format', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'value' => __( 'Tags', 'js_composer' ),
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Taxonomy', 'js_composer' ),
			'param_name' => 'taxonomy',
			'value' => $tag_taxonomies,
			'description' => __( 'Select source for tag cloud.', 'js_composer' ),
			'admin_label' => true,
			'save_always' => true,
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

$custom_menus = array();
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
if ( is_array( $menus ) && ! empty( $menus ) ) {
	foreach ( $menus as $single_menu ) {
		if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
			$custom_menus[ $single_menu->name ] = $single_menu->term_id;
		}
	}
}
vc_map( array(
	'name' => 'WP ' . __( "Custom Menu" ),
	'base' => 'vc_wp_custommenu',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Use this widget to add one of your custom menus as a widget', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Menu', 'js_composer' ),
			'param_name' => 'nav_menu',
			'value' => $custom_menus,
			'description' => empty( $custom_menus ) ? __( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'js_composer' ) : __( 'Select menu to display.', 'js_composer' ),
			'admin_label' => true,
			'save_always' => true,
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Text' ),
	'base' => 'vc_wp_text',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Arbitrary text or HTML', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Recent Posts' ),
	'base' => 'vc_wp_posts',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'The most recent posts on your site', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Recent Posts' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts', 'js_composer' ),
			'description' => __( 'Enter number of posts to display.', 'js_composer' ),
			'param_name' => 'number',
			'value' => 5,
			'admin_label' => true
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display post date?', 'js_composer' ),
			'param_name' => 'show_date',
			'value' => array( __( 'Yes', 'js_composer' ) => true ),
			'description' => __( 'If checked, date will be displayed.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

$link_category = array( __( 'All Links', 'js_composer' ) => '' );
$link_cats = get_terms( 'link_category' );
if ( is_array( $link_cats ) && ! empty( $link_cats ) ) {
	foreach ( $link_cats as $link_cat ) {
		if ( is_object( $link_cat ) && isset( $link_cat->name, $link_cat->term_id ) ) {
			$link_category[ $link_cat->name ] = $link_cat->term_id;
		}
	}
}
vc_map( array(
	'name' => 'WP ' . __( 'Links' ),
	'base' => 'vc_wp_links',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'content_element' => (bool) get_option( 'link_manager_enabled' ),
	'weight' => - 50,
	'description' => __( 'Your blogroll', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link Category', 'js_composer' ),
			'param_name' => 'category',
			'value' => $link_category,
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', 'js_composer' ),
			'param_name' => 'orderby',
			'value' => array(
				__( 'Link title', 'js_composer' ) => 'name',
				__( 'Link rating', 'js_composer' ) => 'rating',
				__( 'Link ID', 'js_composer' ) => 'id',
				__( 'Random', 'js_composer' ) => 'rand'
			)
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', 'js_composer' ),
			'param_name' => 'options',
			'value' => array(
				__( 'Show Link Image', 'js_composer' ) => 'images',
				__( 'Show Link Name', 'js_composer' ) => 'name',
				__( 'Show Link Description', 'js_composer' ) => 'description',
				__( 'Show Link Rating', 'js_composer' ) => 'rating'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of links to show', 'js_composer' ),
			'param_name' => 'limit',
			'value' => - 1,
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Categories' ),
	'base' => 'vc_wp_categories',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A list or dropdown of categories', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Categories' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display options', 'js_composer' ),
			'param_name' => 'options',
			'value' => array(
				__( 'Dropdown', 'js_composer' ) => 'dropdown',
				__( 'Show post counts', 'js_composer' ) => 'count',
				__( 'Show hierarchy', 'js_composer' ) => 'hierarchical'
			),
			'description' => __( 'Select display options for categories.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Archives' ),
	'base' => 'vc_wp_archives',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A monthly archive of your sites posts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => __( 'Archives' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display options', 'js_composer' ),
			'param_name' => 'options',
			'value' => array(
				__( 'Dropdown', 'js_composer' ) => 'dropdown',
				__( 'Show post counts', 'js_composer' ) => 'count'
			),
			'description' => __( 'Select display options for archives.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'RSS' ),
	'base' => 'vc_wp_rss',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Entries from any RSS or Atom feed', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'RSS feed URL', 'js_composer' ),
			'param_name' => 'url',
			'description' => __( 'Enter the RSS feed URL.', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items', 'js_composer' ),
			'param_name' => 'items',
			'value' => array(
				__( '10 - Default', 'js_composer' ) => 10,
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8,
				9,
				10,
				11,
				12,
				13,
				14,
				15,
				16,
				17,
				18,
				19,
				20
			),
			'description' => __( 'Select how many items to display.', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', 'js_composer' ),
			'param_name' => 'options',
			'value' => array(
				__( 'Item content', 'js_composer' ) => 'show_summary',
				__( 'Display item author if available?', 'js_composer' ) => 'show_author',
				__( 'Display item date?', 'js_composer' ) => 'show_date'
			),
			'description' => __( 'Select display options for RSS feeds.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		)
	)
) );

/* Empty Space Element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Empty Space', 'js_composer' ),
	'base' => 'vc_empty_space',
	'icon' => 'icon-wpb-ui-empty_space',
	'show_settings_on_create' => true,
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Blank space with custom height', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Height', 'js_composer' ),
			'param_name' => 'height',
			'value' => '32px',
			'admin_label' => true,
			'description' => __( 'Enter empty space height (Note: CSS measurement units allowed).', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		),
	),
) );

/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Custom Heading', 'js_composer' ),
	'base' => 'vc_custom_heading',
	'icon' => 'icon-wpb-ui-custom_heading',
	'show_settings_on_create' => true,
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Text with Google fonts', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text source', 'js_composer' ),
			'param_name' => 'source',
			'value' => array(
				__( 'Custom text', 'js_composer' ) => '',
				__( 'Post or Page Title', 'js_composer' ) => 'post_title'
			),
			'std' => '',
			'description' => __( 'Select text source.', 'js_composer' )
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'text',
			'admin_label' => true,
			'value' => __( 'This is custom heading element', 'js_composer' ),
			'description' => __( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'js_composer' ),
			'dependency' => array(
				'element' => 'source',
				'is_empty' => true,
			),
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Add link to custom heading.', 'js_composer' ),
			// compatible with btn2 and converted from href{btn1}
		),
		array(
			'type' => 'font_container',
			'param_name' => 'font_container',
			'value' => 'tag:h2|text_align:left',
			'settings' => array(
				'fields' => array(
					'tag' => 'h2', // default value h2
					'text_align',
					'font_size',
					'line_height',
					'color',
					'tag_description' => __( 'Select element tag.', 'js_composer' ),
					'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
					'font_size_description' => __( 'Enter font size.', 'js_composer' ),
					'line_height_description' => __( 'Enter line height.', 'js_composer' ),
					'color_description' => __( 'Select heading color.', 'js_composer' ),
				),
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Use theme default font family?', 'js_composer' ),
			'param_name' => 'use_theme_fonts',
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
			'description' => __( 'Use font family from the theme.', 'js_composer' ),
		),
		array(
			'type' => 'google_fonts',
			'param_name' => 'google_fonts',
			'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			'settings' => array(
				'fields' => array(
					'font_family_description' => __( 'Select font family.', 'js_composer' ),
					'font_style_description' => __( 'Select font styling.', 'js_composer' )
				)
			),
			'dependency' => array(
				'element' => 'use_theme_fonts',
				'value_not_equal_to' => 'yes',
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	),
) );

// Note this shortcodes integrates custom heading!
include_once "shortcode-vc-btn.php";

include_once "shortcode-vc-cta3.php";

$post_types = get_post_types( array() );
$post_types_list = array();
if ( is_array( $post_types ) && ! empty( $post_types ) ) {
	foreach ( $post_types as $post_type ) {
		if ( $post_type !== 'revision' && $post_type !== 'nav_menu_item' ) {
			$label = ucfirst( $post_type );
			$post_types_list[] = array( $post_type, $label );
		}
	}
}
$post_types_list[] = array( 'custom', __( 'Custom query', 'js_composer' ) );
$post_types_list[] = array( 'ids', __( 'List of IDs', 'js_composer' ) );

$taxonomies_for_filter = array();

if ( 'vc_edit_form' === vc_post_param( 'action' ) && vc_verify_admin_nonce() ) {
	$vc_taxonomies_types = vc_taxonomies_types();
	if ( is_array( $vc_taxonomies_types ) && ! empty( $vc_taxonomies_types ) ) {
		foreach ( $vc_taxonomies_types as $t => $data ) {
			if ( $t !== 'post_format' && is_object( $data ) ) {
				$taxonomies_for_filter[ $data->labels->name ] = $t;
			}
		}
	}
}

$grid_cols_list = array(
	array( 'label' => "6", 'value' => 2 ),
	array( 'label' => "4", 'value' => 3 ),
	array( 'label' => "3", 'value' => 4 ),
	array( 'label' => "2", 'value' => 6 ),
	array( 'label' => "1", 'value' => 12 ),
);

$grid_params = array(
	array(
		'type' => 'dropdown',
		'heading' => __( 'Data source', 'js_composer' ),
		'param_name' => 'post_type',
		'value' => $post_types_list,
		'save_always' => true,
		'description' => __( 'Select content type for your grid.', 'js_composer' )
	),
	array(
		'type' => 'autocomplete',
		'heading' => __( 'Include only', 'js_composer' ),
		'param_name' => 'include',
		'description' => __( 'Add posts, pages, etc. by title.', 'js_composer' ),
		'settings' => array(
			'multiple' => true,
			'sortable' => true,
			'groups' => true,
		),
		'dependency' => array(
			'element' => 'post_type',
			'value' => array( 'ids' ),
		),
	),
	// Custom query tab
	array(
		'type' => 'textarea_safe',
		'heading' => __( 'Custom query', 'js_composer' ),
		'param_name' => 'custom_query',
		'description' => __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'js_composer' ),
		'dependency' => array(
			'element' => 'post_type',
			'value' => array( 'custom' ),
		),
	),
	array(
		'type' => 'autocomplete',
		'heading' => __( 'Narrow data source', 'js_composer' ),
		'param_name' => 'taxonomies',
		'settings' => array(
			'multiple' => true,
			'min_length' => 1,
			'groups' => true,
			// In UI show results grouped by groups, default false
			'unique_values' => true,
			// In UI show results except selected. NB! You should manually check values in backend, default false
			'display_inline' => true,
			// In UI show results inline view, default false (each value in own line)
			'delay' => 500,
			// delay for search. default 500
			'auto_focus' => true,
			// auto focus input, default true
		),
		'param_holder_class' => 'vc_not-for-custom',
		'description' => __( 'Enter categories, tags or custom taxonomies.', 'js_composer' ),
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
		),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Total items', 'js_composer' ),
		'param_name' => 'max_items',
		'value' => 10, // default value
		'param_holder_class' => 'vc_not-for-custom',
		'description' => __( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'js_composer' ),
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
		),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Display Style', 'js_composer' ),
		'param_name' => 'style',
		'value' => array(
			__( 'Show all', 'js_composer' ) => 'all',
			__( 'Load more button', 'js_composer' ) => 'load-more',
			__( 'Lazy loading', 'js_composer' ) => 'lazy',
			__( 'Pagination', 'js_composer' ) => 'pagination',
		),
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'custom' ),
		),
		'edit_field_class' => 'vc_col-sm-6 vc_column',
		'description' => __( 'Select display style for grid.', 'js_composer' ),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Items per page', 'js_composer' ),
		'param_name' => 'items_per_page',
		'description' => __( 'Number of items to show per page.', 'js_composer' ),
		'value' => '10',
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'lazy', 'load-more', 'pagination' ),
		),
		'edit_field_class' => 'vc_col-sm-6 vc_column',
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Show filter', 'js_composer' ),
		'param_name' => 'show_filter',
		'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
		'description' => __( 'Append filter to grid.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Grid elements per row', 'js_composer' ),
		'param_name' => 'element_width',
		'value' => $grid_cols_list,
		'std' => '4',
		'edit_field_class' => 'vc_col-sm-6 vc_column',
		'description' => __( 'Select number of single grid elements per row.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Gap', 'js_composer' ),
		'param_name' => 'gap',
		'value' => array(
			'0px' => '0',
			'1px' => '1',
			'2px' => '2',
			'3px' => '3',
			'4px' => '4',
			'5px' => '5',
			'10px' => '10',
			'15px' => '15',
			'20px' => '20',
			'25px' => '25',
			'30px' => '30',
			'35px' => '35',
		),
		'std' => '30',
		'description' => __( 'Select gap between grid elements.', 'js_composer' ),
		'edit_field_class' => 'vc_col-sm-6 vc_column',
	),
	// Data settings
	array(
		'type' => 'dropdown',
		'heading' => __( 'Order by', 'js_composer' ),
		'param_name' => 'orderby',
		'value' => array(
			__( 'Date', 'js_composer' ) => 'date',
			__( 'Order by post ID', 'js_composer' ) => 'ID',
			__( 'Author', 'js_composer' ) => 'author',
			__( 'Title', 'js_composer' ) => 'title',
			__( 'Last modified date', 'js_composer' ) => 'modified',
			__( 'Post/page parent ID', 'js_composer' ) => 'parent',
			__( 'Number of comments', 'js_composer' ) => 'comment_count',
			__( 'Menu order/Page Order', 'js_composer' ) => 'menu_order',
			__( 'Meta value', 'js_composer' ) => 'meta_value',
			__( 'Meta value number', 'js_composer' ) => 'meta_value_num',
			__( 'Random order', 'js_composer' ) => 'rand',
		),
		'description' => __( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
		'group' => __( 'Data Settings', 'js_composer' ),
		'param_holder_class' => 'vc_grid-data-type-not-ids',
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
		),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Sort order', 'js_composer' ),
		'param_name' => 'order',
		'group' => __( 'Data Settings', 'js_composer' ),
		'value' => array(
			__( 'Descending', 'js_composer' ) => 'DESC',
			__( 'Ascending', 'js_composer' ) => 'ASC',
		),
		'param_holder_class' => 'vc_grid-data-type-not-ids',
		'description' => __( 'Select sorting order.', 'js_composer' ),
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
		),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Meta key', 'js_composer' ),
		'param_name' => 'meta_key',
		'description' => __( 'Input meta key for grid ordering.', 'js_composer' ),
		'group' => __( 'Data Settings', 'js_composer' ),
		'param_holder_class' => 'vc_grid-data-type-not-ids',
		'dependency' => array(
			'element' => 'orderby',
			'value' => array( 'meta_value', 'meta_value_num' ),
		),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Offset', 'js_composer' ),
		'param_name' => 'offset',
		'description' => __( 'Number of grid elements to displace or pass over.', 'js_composer' ),
		'group' => __( 'Data Settings', 'js_composer' ),
		'param_holder_class' => 'vc_grid-data-type-not-ids',
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
		),
	),
	array(
		'type' => 'autocomplete',
		'heading' => __( 'Exclude', 'js_composer' ),
		'param_name' => 'exclude',
		'description' => __( 'Exclude posts, pages, etc. by title.', 'js_composer' ),
		'group' => __( 'Data Settings', 'js_composer' ),
		'settings' => array(
			'multiple' => true,
		),
		'param_holder_class' => 'vc_grid-data-type-not-ids',
		'dependency' => array(
			'element' => 'post_type',
			'value_not_equal_to' => array( 'ids', 'custom' ),
			'callback' => 'vc_grid_exclude_dependency_callback',
		),
	),
	//Filter tab
	array(
		'type' => 'dropdown',
		'heading' => __( 'Filter by', 'js_composer' ),
		'param_name' => 'filter_source',
		'value' => $taxonomies_for_filter,
		'group' => __( 'Filter', 'js_composer' ),
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
		),
		'save_always' => true,
		'description' => __( 'Select filter source.', 'js_composer' ),
	),
	array(
		'type' => 'autocomplete',
		'heading' => __( 'Exclude from filter list', 'js_composer' ),
		'param_name' => 'exclude_filter',
		'settings' => array(
			'multiple' => true,
			// is multiple values allowed? default false
			'min_length' => 1,
			// min length to start search -> default 2
			'groups' => true,
			// In UI show results grouped by groups, default false
			'unique_values' => true,
			// In UI show results except selected. NB! You should manually check values in backend, default false
			'display_inline' => true,
			// In UI show results inline view, default false (each value in own line)
			'delay' => 500,
			// delay for search. default 500
			'auto_focus' => true,
			// auto focus input, default true
		),
		'description' => __( 'Enter categories, tags won\'t be shown in the filters list', 'js_composer' ),
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
			'callback' => 'vcGridFilterExcludeCallBack'
		),
		'group' => __( 'Filter', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Style', 'js_composer' ),
		'param_name' => 'filter_style',
		'value' => array(
			__( 'Rounded', 'js_composer' ) => 'default',
			__( 'Less Rounded', 'js_composer' ) => 'default-less-rounded',
			__( 'Border', 'js_composer' ) => 'bordered',
			__( 'Rounded Border', 'js_composer' ) => 'bordered-rounded',
			__( 'Less Rounded Border', 'js_composer' ) => 'bordered-rounded-less',
			__( 'Filled', 'js_composer' ) => 'filled',
			__( 'Rounded Filled', 'js_composer' ) => 'filled-rounded',
			__( 'Dropdown', 'js_composer' ) => 'dropdown',
		),
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
		),
		'group' => __( 'Filter', 'js_composer' ),
		'description' => __( 'Select filter display style.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Alignment', 'js_composer' ),
		'param_name' => 'filter_align',
		'value' => array(
			__( 'Center', 'js_composer' ) => 'center',
			__( 'Left', 'js_composer' ) => 'left',
			__( 'Right', 'js_composer' ) => 'right',
		),
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
		),
		'group' => __( 'Filter', 'js_composer' ),
		'description' => __( 'Select filter alignment.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Color', 'js_composer' ),
		'param_name' => 'filter_color',
		'value' => getVcShared( 'colors' ),
		'std' => 'grey',
		'param_holder_class' => 'vc_colored-dropdown',
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
		),
		'group' => __( 'Filter', 'js_composer' ),
		'description' => __( 'Select filter color.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Filter size', 'js_composer' ),
		'param_name' => 'filter_size',
		'value' => getVcShared( 'sizes' ),
		'std' => 'md',
		'description' => __( 'Select filter size.', 'js_composer' ),
		'dependency' => array(
			'element' => 'show_filter',
			'value' => array( 'yes' ),
		),
		'group' => __( 'Filter', 'js_composer' ),
	),
	// Load more btn
	array(
		'type' => 'dropdown',
		'heading' => __( 'Button style', 'js_composer' ),
		'param_name' => 'button_style',
		'value' => getVcShared( 'button styles' ),
		'param_holder_class' => 'vc_colored-dropdown',
		'group' => __( 'Load More Button', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'load-more' ),
		),
		'description' => __( 'Select button style.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Button color', 'js_composer' ),
		'param_name' => 'button_color',
		'value' => getVcShared( 'colors' ),
		'param_holder_class' => 'vc_colored-dropdown',
		'group' => __( 'Load More Button', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'load-more' ),
		),
		'description' => __( 'Select button color.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Button size', 'js_composer' ),
		'param_name' => 'button_size',
		'value' => getVcShared( 'sizes' ),
		'std' => 'md',
		'description' => __( 'Select button size.', 'js_composer' ),
		'group' => __( 'Load More Button', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'load-more' ),
		),
	),
	// Paging controls
	array(
		'type' => 'dropdown',
		'heading' => __( 'Arrows design', 'js_composer' ),
		'param_name' => 'arrows_design',
		'value' => array(
			__( 'None', 'js_composer' ) => 'none',
			__( 'Simple', 'js_composer' ) => 'vc_arrow-icon-arrow_01_left',
			__( 'Simple Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_02_left',
			__( 'Simple Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_03_left',
			__( 'Simple Square', 'js_composer' ) => 'vc_arrow-icon-arrow_09_left',
			__( 'Simple Square Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_12_left',
			__( 'Simple Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_11_left',
			__( 'Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_04_left',
			__( 'Rounded Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_05_left',
			__( 'Rounded Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_06_left',
			__( 'Rounded Square', 'js_composer' ) => 'vc_arrow-icon-arrow_10_left',
			__( 'Simple Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_08_left',
			__( 'Simple Rounded Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_07_left',

		),
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
		'description' => __( 'Select design for arrows.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Arrows position', 'js_composer' ),
		'param_name' => 'arrows_position',
		'value' => array(
			__( 'Inside Wrapper', 'js_composer' ) => 'inside',
			__( 'Outside Wrapper', 'js_composer' ) => 'outside',
		),
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'arrows_design',
			'value_not_equal_to' => array( 'none' ), // New dependency
		),
		'description' => __( 'Arrows will be displayed inside or outside grid.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Arrows color', 'js_composer' ),
		'param_name' => 'arrows_color',
		'value' => getVcShared( 'colors' ),
		'param_holder_class' => 'vc_colored-dropdown',
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'arrows_design',
			'value_not_equal_to' => array( 'none' ), // New dependency
		),
		'description' => __( 'Select color for arrows.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Pagination style', 'js_composer' ),
		'param_name' => 'paging_design',
		'value' => array(
			__( 'None', 'js_composer' ) => 'none',
			__( 'Square Dots', 'js_composer' ) => 'square_dots',
			__( 'Radio Dots', 'js_composer' ) => 'radio_dots',
			__( 'Point Dots', 'js_composer' ) => 'point_dots',
			__( 'Fill Square Dots', 'js_composer' ) => 'fill_square_dots',
			__( 'Rounded Fill Square Dots', 'js_composer' ) => 'round_fill_square_dots',
			__( 'Pagination Default', 'js_composer' ) => 'pagination_default',
			__( 'Outline Default Dark', 'js_composer' ) => 'pagination_default_dark',
			__( 'Outline Default Light', 'js_composer' ) => 'pagination_default_light',
			__( 'Pagination Rounded', 'js_composer' ) => 'pagination_rounded',
			__( 'Outline Rounded Dark', 'js_composer' ) => 'pagination_rounded_dark',
			__( 'Outline Rounded Light', 'js_composer' ) => 'pagination_rounded_light',
			__( 'Pagination Square', 'js_composer' ) => 'pagination_square',
			__( 'Outline Square Dark', 'js_composer' ) => 'pagination_square_dark',
			__( 'Outline Square Light', 'js_composer' ) => 'pagination_square_light',
			__( 'Pagination Rounded Square', 'js_composer' ) => 'pagination_rounded_square',
			__( 'Outline Rounded Square Dark', 'js_composer' ) => 'pagination_rounded_square_dark',
			__( 'Outline Rounded Square Light', 'js_composer' ) => 'pagination_rounded_square_light',
			__( 'Stripes Dark', 'js_composer' ) => 'pagination_stripes_dark',
			__( 'Stripes Light', 'js_composer' ) => 'pagination_stripes_light',
		),
		'std' => 'radio_dots',
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
		'description' => __( 'Select pagination style.', 'js_composer' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Pagination color', 'js_composer' ),
		'param_name' => 'paging_color',
		'value' => getVcShared( 'colors' ),
		'std' => 'grey',
		'param_holder_class' => 'vc_colored-dropdown',
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'paging_design',
			'value_not_equal_to' => array( 'none' ), // New dependency
		),
		'description' => __( 'Select pagination color.', 'js_composer' ),
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Loop pages?', 'js_composer' ),
		'param_name' => 'loop',
		'description' => __( 'Allow items to be repeated in infinite loop (carousel).', 'js_composer' ),
		'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Autoplay delay', 'js_composer' ),
		'param_name' => 'autoplay',
		'value' => '-1',
		'description' => __( 'Enter value in seconds. Set -1 to disable autoplay.', 'js_composer' ),
		'group' => __( 'Pagination', 'js_composer' ),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
	),
	array(
		'type' => 'animation_style',
		'heading' => __( 'Animation In', 'js_composer' ),
		'param_name' => 'paging_animation_in',
		'group' => __( 'Pagination', 'js_composer' ),
		'settings' => array(
			'type' => array( 'in', 'other' ),
		),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
		'description' => __( 'Select "animation in" for page transition.', 'js_composer' ),
	),
	array(
		'type' => 'animation_style',
		'heading' => __( 'Animation Out', 'js_composer' ),
		'param_name' => 'paging_animation_out',
		'group' => __( 'Pagination', 'js_composer' ),
		'settings' => array(
			'type' => array( 'out' ),
		),
		'dependency' => array(
			'element' => 'style',
			'value' => array( 'pagination' ),
		),
		'description' => __( 'Select "animation out" for page transition.', 'js_composer' ),
	),
	array(
		'type' => 'vc_grid_item',
		'heading' => __( 'Grid element template', 'js_composer' ),
		'param_name' => 'item',
		'description' => sprintf( __( '%sCreate new%s template or %smodify selected%s. Predefined templates will be cloned.', 'js_composer' ), '<a href="'
		                                                                                                                                       . esc_url( admin_url( 'post-new.php?post_type=vc_grid_item' ) ) . '" target="_blank">', '</a>', '<a href="#" target="_blank" data-vc-grid-item="edit_link">', '</a>' ),
		'group' => __( 'Item Design', 'js_composer' ),
		'value' => 'none',
	),
	array(
		'type' => 'vc_grid_id',
		'param_name' => 'grid_id',
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Extra class name', 'js_composer' ),
		'param_name' => 'el_class',
		'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
	),
	array(
		'type' => 'css_editor',
		'heading' => __( 'CSS box', 'js_composer' ),
		'param_name' => 'css',
		'group' => __( 'Design Options', 'js_composer' )
	),
);
vc_map( array(
	'name' => __( 'Post Grid', 'js_composer' ),
	'base' => 'vc_basic_grid',
	'icon' => 'icon-wpb-application-icon-large',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Posts, pages or custom posts in grid', 'js_composer' ),
	'params' => $grid_params
) );
$media_grid_params = array(
	array(
		'type' => 'attach_images',
		'heading' => __( 'Images', 'js_composer' ),
		'param_name' => 'include',
		'description' => __( 'Select images from media library.', 'js_composer' )
	),
	$grid_params[5],
	$grid_params[6],
	$grid_params[8],
	$grid_params[9],
	$grid_params[21],
	$grid_params[22],
	$grid_params[23],
	$grid_params[24],
	$grid_params[25],
	$grid_params[26],
	$grid_params[27],
	$grid_params[28],
	$grid_params[29],
	$grid_params[30],
	$grid_params[31],
	array(
		'type' => 'vc_grid_item',
		'heading' => __( 'Grid element template', 'js_composer' ),
		'param_name' => 'item',
		'description' => sprintf( __( '%sCreate new%s template or %smodify selected%s. Predefined templates will be cloned.', 'js_composer' ), '<a href="'
		                                                                                                                                       . esc_url( admin_url( 'post-new.php?post_type=vc_grid_item' ) ) . '" target="_blank">', '</a>', '<a href="#" target="_blank" data-vc-grid-item="edit_link">', '</a>' ),
		'group' => __( 'Item Design', 'js_composer' ),
		'value' => 'mediaGrid_Default',
	),
	array(
		'type' => 'vc_grid_id',
		'param_name' => 'grid_id',
	),
	array(
		'type' => 'css_editor',
		'heading' => __( 'CSS box', 'js_composer' ),
		'param_name' => 'css',
		'group' => __( 'Design Options', 'js_composer' )
	),
);
$media_grid_params[4]['std'] = '5';
vc_map( array(
	'name' => __( 'Media Grid', 'js_composer' ),
	'base' => 'vc_media_grid',
	'icon' => 'vc_icon-vc-media-grid',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Media grid from Media Library', 'js_composer' ),
	'params' => $media_grid_params,
) );
$masonry_grid_params = $grid_params;
unset( $masonry_grid_params[5]['value'][ __( 'Pagination', 'js_composer' ) ] );
$masonry_grid_params[33]['value'] = 'masonryGrid_Default';
vc_map( array(
	'name' => __( 'Post Masonry Grid', 'js_composer' ),
	'base' => 'vc_masonry_grid',
	'icon' => 'vc_icon-vc-masonry-grid',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Posts, pages or custom posts in masonry grid', 'js_composer' ),
	'params' => $masonry_grid_params
) );
$masonry_media_grid_params = $media_grid_params;
$masonry_media_grid_params[16]['value'] = 'masonryMedia_Default';
unset( $masonry_media_grid_params[1]['value'][ __( 'Pagination', 'js_composer' ) ] );
vc_map( array(
	'name' => __( 'Masonry Media Grid', 'js_composer' ),
	'base' => 'vc_masonry_media_grid',
	'icon' => 'vc_icon-vc-masonry-media-grid',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Masonry media grid from Media Library', 'js_composer' ),
	'params' => $masonry_media_grid_params
) );

add_filter( 'vc_autocomplete_vc_basic_grid_include_callback',
	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_vc_basic_grid_include_render',
	'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
add_filter( 'vc_autocomplete_vc_masonry_grid_include_callback',
	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_vc_masonry_grid_include_render',
	'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)


// Narrow data taxonomies
add_filter( 'vc_autocomplete_vc_basic_grid_taxonomies_callback',
	'vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_vc_basic_grid_taxonomies_render',
	'vc_autocomplete_taxonomies_field_render', 10, 1 );

add_filter( 'vc_autocomplete_vc_masonry_grid_taxonomies_callback',
	'vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_vc_masonry_grid_taxonomies_render',
	'vc_autocomplete_taxonomies_field_render', 10, 1 );

// Narrow data taxonomies for exclude_filter
add_filter( 'vc_autocomplete_vc_basic_grid_exclude_filter_callback',
	'vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_vc_basic_grid_exclude_filter_render',
	'vc_autocomplete_taxonomies_field_render', 10, 1 );

add_filter( 'vc_autocomplete_vc_masonry_grid_exclude_filter_callback',
	'vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_vc_masonry_grid_exclude_filter_render',
	'vc_autocomplete_taxonomies_field_render', 10, 1 );
/**
 * @since 4.5.2
 *
 * @param $term
 *
 * @return array|bool
 */
function vc_autocomplete_taxonomies_field_render( $term ) {
	$vc_taxonomies_types = vc_taxonomies_types();
	$terms = get_terms( array_keys( $vc_taxonomies_types ), array(
		'include' => array( $term['value'] ),
		'hide_empty' => false,
	) );
	$data = false;
	if ( is_array( $terms ) && 1 === count( $terms ) ) {
		$term = $terms[0];
		$data = vc_get_term_object( $term );
	}

	return $data;
}

/**
 * @since 4.5.2
 *
 * @param $search_string
 *
 * @return array|bool
 */
function vc_autocomplete_taxonomies_field_search( $search_string ) {
	$data = array();
	$vc_filter_by = vc_post_param( 'vc_filter_by', '' );
	$vc_taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( vc_taxonomies_types() );
	$vc_taxonomies = get_terms( $vc_taxonomies_types, array(
		'hide_empty' => false,
		'search' => $search_string
	) );
	if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
		foreach ( $vc_taxonomies as $t ) {
			if ( is_object( $t ) ) {
				$data[] = vc_get_term_object( $t );
			}
		}
	}

	return $data;
}

/**
 * @param $search
 * @param $wp_query
 *
 * @return string
 */
function vc_search_by_title_only( $search, &$wp_query ) {
	global $wpdb;
	if ( empty( $search ) ) {
		return $search;
	} // skip processing - no search term in query
	$q = $wp_query->query_vars;
	if ( isset( $q['vc_search_by_title_only'] ) && $q['vc_search_by_title_only'] == true ) {
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search =
		$searchand = '';
		foreach ( (array) $q['search_terms'] as $term ) {
			$term = $wpdb->esc_like( $term );
			$like = $n . $term . $n;
			$search .= $wpdb->prepare( "{$searchand}($wpdb->posts.post_title LIKE %s)", $like );
			$searchand = ' AND ';
		}
		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
			if ( ! is_user_logged_in() ) {
				$search .= " AND ($wpdb->posts.post_password = '') ";
			}
		}
	}

	return $search;
}

/**
 * @param $search_string
 *
 * @return array
 */
function vc_include_field_search( $search_string ) {
	$query = $search_string;
	$data = array();
	$args = array( 's' => $query, 'post_type' => 'any' );
	$args['vc_search_by_title_only'] = true;
	$args['numberposts'] = - 1;
	if ( strlen( $args['s'] ) === 0 ) {
		unset( $args['s'] );
	}
	add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
	$posts = get_posts( $args );
	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$data[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
				'group' => $post->post_type,
			);
		}
	}

	return $data;
}

/**
 * @param $value
 *
 * @return array|bool
 */
function vc_include_field_render( $value ) {
	$post = get_post( $value['value'] );

	return is_null( $post ) ? false : array(
		'label' => $post->post_title,
		'value' => $post->ID,
		'group' => $post->post_type
	);
}

add_filter( 'vc_autocomplete_vc_basic_grid_exclude_callback',
	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_vc_basic_grid_exclude_render',
	'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
add_filter( 'vc_autocomplete_vc_masonry_grid_exclude_callback',
	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_vc_masonry_grid_exclude_render',
	'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
/**
 * @param $data_arr
 *
 * @return array
 */
function vc_exclude_field_search( $data_arr ) {
	$query = isset( $data_arr['query'] ) ? $data_arr['query'] : null;
	$term = isset( $data_arr['term'] ) ? $data_arr['term'] : "";
	$data = array();
	$args = ! empty( $query ) ? array( 's' => $term, 'post_type' => $query ) : array(
		's' => $term,
		'post_type' => 'any'
	);
	$args['vc_search_by_title_only'] = true;
	$args['numberposts'] = - 1;
	if ( strlen( $args['s'] ) === 0 ) {
		unset( $args['s'] );
	}
	add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
	$posts = get_posts( $args );
	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$data[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
				'group' => $post->post_type,
			);
		}
	}

	return $data;
}

/**
 * @param $value
 *
 * @return array|bool
 */
function vc_exclude_field_render( $value ) {
	$post = get_post( $value['value'] );

	return is_null( $post ) ? false : array(
		'label' => $post->post_title,
		'value' => $post->ID,
		'group' => $post->post_type
	);
}

/*** Visual Composer Content elements refresh ***/
class VcSharedLibrary {
	// Here we will store plugin wise (shared) settings. Colors, Locations, Sizes, etc...
	/**
	 * @var array
	 */
	private static $colors = array(
		'Blue' => 'blue',
		'Turquoise' => 'turquoise',
		'Pink' => 'pink',
		'Violet' => 'violet',
		'Peacoc' => 'peacoc',
		'Chino' => 'chino',
		'Mulled Wine' => 'mulled_wine',
		'Vista Blue' => 'vista_blue',
		'Black' => 'black',
		'Grey' => 'grey',
		'Orange' => 'orange',
		'Sky' => 'sky',
		'Green' => 'green',
		'Juicy pink' => 'juicy_pink',
		'Sandy brown' => 'sandy_brown',
		'Purple' => 'purple',
		'White' => 'white'
	);

	/**
	 * @var array
	 */
	public static $icons = array(
		'Glass' => 'glass',
		'Music' => 'music',
		'Search' => 'search'
	);

	/**
	 * @var array
	 */
	public static $sizes = array(
		'Mini' => 'xs',
		'Small' => 'sm',
		'Normal' => 'md',
		'Large' => 'lg'
	);

	/**
	 * @var array
	 */
	public static $button_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'3D' => '3d',
		'Square Outlined' => 'square_outlined'
	);

	/**
	 * @var array
	 */
	public static $message_box_styles = array(
		'Standard' => 'standard',
		'Solid' => 'solid',
		'Solid icon' => 'solid-icon',
		'Outline' => 'outline',
		'3D' => '3d',
	);

	/**
	 * Toggle styles
	 * @var array
	 */
	public static $toggle_styles = array(
		'Default' => 'default',
		'Simple' => 'simple',
		'Round' => 'round',
		'Round Outline' => 'round_outline',
		'Rounded' => 'rounded',
		'Rounded Outline' => 'rounded_outline',
		'Square' => 'square',
		'Square Outline' => 'square_outline',
		'Arrow' => 'arrow',
		'Text Only' => 'text_only',
	);

	/**
	 * Animation styles
	 * @var array
	 */
	public static $animation_styles = array(
		'Bounce' => 'easeOutBounce',
		'Elastic' => 'easeOutElastic',
		'Back' => 'easeOutBack',
		'Cubic' => 'easeinOutCubic',
		'Quint' => 'easeinOutQuint',
		'Quart' => 'easeOutQuart',
		'Quad' => 'easeinQuad',
		'Sine' => 'easeOutSine'
	);

	/**
	 * @var array
	 */
	public static $cta_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'Square Outlined' => 'square_outlined'
	);

	/**
	 * @var array
	 */
	public static $txt_align = array(
		'Left' => 'left',
		'Right' => 'right',
		'Center' => 'center',
		'Justify' => 'justify'
	);

	/**
	 * @var array
	 */
	public static $el_widths = array(
		'100%' => '',
		'90%' => '90',
		'80%' => '80',
		'70%' => '70',
		'60%' => '60',
		'50%' => '50',
		'40%' => '40',
		'30%' => '30',
		'20%' => '20',
		'10%' => '10'
	);

	/**
	 * @var array
	 */
	public static $sep_widths = array(
		'1px' => '',
		'2px' => '2',
		'3px' => '3',
		'4px' => '4',
		'5px' => '5',
		'6px' => '6',
		'7px' => '7',
		'8px' => '8',
		'9px' => '9',
		'10px' => '10'
	);

	/**
	 * @var array
	 */
	public static $sep_styles = array(
		'Border' => '',
		'Dashed' => 'dashed',
		'Dotted' => 'dotted',
		'Double' => 'double'
	);

	/**
	 * @var array
	 */
	public static $box_styles = array(
		'Default' => '',
		'Rounded' => 'vc_box_rounded',
		'Border' => 'vc_box_border',
		'Outline' => 'vc_box_outline',
		'Shadow' => 'vc_box_shadow',
		'Bordered shadow' => 'vc_box_shadow_border',
		'3D Shadow' => 'vc_box_shadow_3d'
	);

	/**
	 * Round box styles
	 *
	 * @var array
	 */
	public static $round_box_styles = array(
		'Round' => 'vc_box_circle',
		'Round Border' => 'vc_box_border_circle',
		'Round Outline' => 'vc_box_outline_circle',
		'Round Shadow' => 'vc_box_shadow_circle',
		'Round Border Shadow' => 'vc_box_shadow_border_circle'
	);

	/**
	 * Circle box styles
	 *
	 * @var array
	 */
	public static $circle_box_styles = array(
		'Circle' => 'vc_box_circle_2',
		'Circle Border' => 'vc_box_border_circle_2',
		'Circle Outline' => 'vc_box_outline_circle_2',
		'Circle Shadow' => 'vc_box_shadow_circle_2',
		'Circle Border Shadow' => 'vc_box_shadow_border_circle_2'
	);

	/**
	 * @return array
	 */
	public static function getColors() {
		return self::$colors;
	}

	/**
	 * @return array
	 */
	public static function getIcons() {
		return self::$icons;
	}

	/**
	 * @return array
	 */
	public static function getSizes() {
		return self::$sizes;
	}

	/**
	 * @return array
	 */
	public static function getButtonStyles() {
		return self::$button_styles;
	}

	/**
	 * @return array
	 */
	public static function getMessageBoxStyles() {
		return self::$message_box_styles;
	}

	/**
	 * @return array
	 */
	public static function getToggleStyles() {
		return self::$toggle_styles;
	}

	/**
	 * @return array
	 */
	public static function getAnimationStyles() {
		return self::$animation_styles;
	}

	/**
	 * @return array
	 */
	public static function getCtaStyles() {
		return self::$cta_styles;
	}

	/**
	 * @return array
	 */
	public static function getTextAlign() {
		return self::$txt_align;
	}

	/**
	 * @return array
	 */
	public static function getBorderWidths() {
		return self::$sep_widths;
	}

	/**
	 * @return array
	 */
	public static function getElementWidths() {
		return self::$el_widths;
	}

	/**
	 * @return array
	 */
	public static function getSeparatorStyles() {
		return self::$sep_styles;
	}

	/**
	 * Get list of box styles
	 *
	 * Possible $groups values:
	 * - default
	 * - round
	 * - circle
	 *
	 * @param array $groups Array of groups to include. If not specified, return all
	 *
	 * @return array
	 */
	public static function getBoxStyles( $groups = array() ) {
		$list = array();
		$groups = (array) $groups;

		if ( ! $groups || in_array( 'default', $groups ) ) {
			$list += self::$box_styles;
		}

		if ( ! $groups || in_array( 'round', $groups ) ) {
			$list += self::$round_box_styles;
		}

		if ( ! $groups || in_array( 'cirlce', $groups ) ) {
			$list += self::$circle_box_styles;
		}

		return $list;
	}

	public static function getColorsDashed() {
		$colors = array(
			__( 'Blue', 'js_composer' ) => 'blue',
			__( 'Turquoise', 'js_composer' ) => 'turquoise',
			__( 'Pink', 'js_composer' ) => 'pink',
			__( 'Violet', 'js_composer' ) => 'violet',
			__( 'Peacoc', 'js_composer' ) => 'peacoc',
			__( 'Chino', 'js_composer' ) => 'chino',
			__( 'Mulled Wine', 'js_composer' ) => 'mulled-wine',
			__( 'Vista Blue', 'js_composer' ) => 'vista-blue',
			__( 'Black', 'js_composer' ) => 'black',
			__( 'Grey', 'js_composer' ) => 'grey',
			__( 'Orange', 'js_composer' ) => 'orange',
			__( 'Sky', 'js_composer' ) => 'sky',
			__( 'Green', 'js_composer' ) => 'green',
			__( 'Juicy pink', 'js_composer' ) => 'juicy-pink',
			__( 'Sandy brown', 'js_composer' ) => 'sandy-brown',
			__( 'Purple', 'js_composer' ) => 'purple',
			__( 'White', 'js_composer' ) => 'white'
		);

		return $colors;
	}
}

/**
 * @param string $asset
 *
 * @return array
 */
function getVcShared( $asset = '' ) {
	switch ( $asset ) {
		case 'colors':
			return VcSharedLibrary::getColors();
			break;

		case 'colors-dashed':
			return VcSharedLibrary::getColorsDashed();
			break;

		case 'icons':
			return VcSharedLibrary::getIcons();
			break;

		case 'sizes':
			return VcSharedLibrary::getSizes();
			break;

		case 'button styles':
		case 'alert styles':
			return VcSharedLibrary::getButtonStyles();
			break;
		case 'message_box_styles':
			return VcSharedLibrary::getMessageBoxStyles();
			break;
		case 'cta styles':
			return VcSharedLibrary::getCtaStyles();
			break;

		case 'text align':
			return VcSharedLibrary::getTextAlign();
			break;

		case 'cta widths':
		case 'separator widths':
			return VcSharedLibrary::getElementWidths();
			break;

		case 'separator styles':
			return VcSharedLibrary::getSeparatorStyles();
			break;

		case 'separator border widths':
			return VcSharedLibrary::getBorderWidths();
			break;

		case 'single image styles':
			return VcSharedLibrary::getBoxStyles();
			break;

		case 'single image external styles':
			return VcSharedLibrary::getBoxStyles( array( 'default', 'round' ) );
			break;

		case 'toggle styles':
			return VcSharedLibrary::getToggleStyles();
			break;

		case 'animation styles':
			return VcSharedLibrary::getAnimationStyles();
			break;

		default:
			# code...
			break;
	}

	return '';
}
