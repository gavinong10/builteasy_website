<?php

$mad_icons_arr = array(
	__( 'None', MAD_BASE_TEXTDOMAIN ) => 'none',
	__( 'Pencil', MAD_BASE_TEXTDOMAIN ) => 'fa-pencil',
	__( 'Shopping Cart', MAD_BASE_TEXTDOMAIN ) => 'fa-shopping-cart',
	__( 'Info', MAD_BASE_TEXTDOMAIN ) => 'fa-info-circle',
	__( 'Check', MAD_BASE_TEXTDOMAIN ) => 'fa-check',
	__( 'Warning', MAD_BASE_TEXTDOMAIN ) => 'fa-warning',
	__( 'Flash', MAD_BASE_TEXTDOMAIN ) => 'fa-flash',
	__( 'Refresh', MAD_BASE_TEXTDOMAIN ) => 'fa-refresh',
	__( 'Times', MAD_BASE_TEXTDOMAIN ) => 'fa-times'
);

$mad_target_arr = array(
	__( 'Same window', MAD_BASE_TEXTDOMAIN ) => '_self',
	__( 'New window', MAD_BASE_TEXTDOMAIN ) => "_blank"
);

$mad_colors_arr = array(
	__( 'Default', MAD_BASE_TEXTDOMAIN ) => 'btn-orange',
	__( 'Grey', MAD_BASE_TEXTDOMAIN ) => 'btn-grey',
	__( 'Blue', MAD_BASE_TEXTDOMAIN ) => 'btn-blue',
	__( 'Navy Blue', MAD_BASE_TEXTDOMAIN ) => 'btn-navy-blue',
	__( 'Green', MAD_BASE_TEXTDOMAIN ) => 'btn-green',
	__( 'Yellow', MAD_BASE_TEXTDOMAIN ) => 'btn-yellow',
	__( 'Transparent', MAD_BASE_TEXTDOMAIN ) => 'btn-transparent'
);

$mad_size_arr = array(
	__( 'Large', MAD_BASE_TEXTDOMAIN ) => 'btn-large',
	__( 'Medium', MAD_BASE_TEXTDOMAIN ) => 'btn-medium',
	__( 'Small', MAD_BASE_TEXTDOMAIN ) => "btn-small",
	__( 'Mini', MAD_BASE_TEXTDOMAIN ) => "btn-mini"
);

$mad_list_unordered_styles = array(
	__( 'Circle', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_2',
	__( 'Bordered Circle', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_3',
	__( 'Square', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_4',
	__( 'Check', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_5',
	__( 'Triangle', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_6',
	__( 'Star', MAD_BASE_TEXTDOMAIN ) => 'vertical_list_type_7'
);

$mad_list_ordered_styles = array(
	__( 'Upper roman', MAD_BASE_TEXTDOMAIN ) => 'upper-roman',
	__( 'Decimal', MAD_BASE_TEXTDOMAIN ) => 'decimal',
	__( 'Upper latin', MAD_BASE_TEXTDOMAIN ) => 'upper-latin',
	__( 'Bordered Square', MAD_BASE_TEXTDOMAIN ) => 'bordered',
	__( 'Fill Square', MAD_BASE_TEXTDOMAIN ) => 'fill'
);

$mad_add_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'CSS Animation', MAD_BASE_TEXTDOMAIN ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		__( 'No', MAD_BASE_TEXTDOMAIN ) => '',
		__( 'Top to bottom', MAD_BASE_TEXTDOMAIN ) => 'top-to-bottom',
		__( 'Bottom to top', MAD_BASE_TEXTDOMAIN ) => 'bottom-to-top',
		__( 'Left to right', MAD_BASE_TEXTDOMAIN ) => 'left-to-right',
		__( 'Right to left', MAD_BASE_TEXTDOMAIN ) => 'right-to-left',
		__( 'Appear from center', MAD_BASE_TEXTDOMAIN ) => "appear",
		__( 'Fade', MAD_BASE_TEXTDOMAIN ) => "fade"
	),
	'group' => __( 'Animations', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', MAD_BASE_TEXTDOMAIN )
);

$mad_short_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'CSS Animation', MAD_BASE_TEXTDOMAIN ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		__( 'No', MAD_BASE_TEXTDOMAIN ) => '',
		__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
	),
	'group' => __( 'Animations', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', MAD_BASE_TEXTDOMAIN )
);

/* Default Shortcodes
/* --------------------------------------------------------------------- */

/* Row
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Row', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Place content elements inside the row', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Row stretch', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'full_width',
			'value' => array(
				__('Default', MAD_BASE_TEXTDOMAIN) => '',
				__('Stretch row', MAD_BASE_TEXTDOMAIN) => 'stretch_row',
				__('Stretch row and content', MAD_BASE_TEXTDOMAIN) => 'stretch_row_content',
				__('Stretch row and content without spaces', MAD_BASE_TEXTDOMAIN) => 'stretch_row_content_no_spaces',
			),
			'description' => __( 'Select stretching options for row and content. Stretched row overlay sidebar and may not work if parent container has overflow: hidden css property.', MAD_BASE_TEXTDOMAIN )
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
			'type' => 'el_id',
			'heading' => __( 'Row ID', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', MAD_BASE_TEXTDOMAIN ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN ),
			'group' => __( 'Design options', MAD_BASE_TEXTDOMAIN )
		)
	),
	'js_view' => 'VcRowView'
) );

/* Toggle 2
---------------------------------------------------------- */

//vc_map( array(
//	'name' => __( 'FAQ', MAD_BASE_TEXTDOMAIN ),
//	'base' => 'vc_toggle',
//	'icon' => 'icon-wpb-toggle-small-expand',
//	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
//	'description' => __( 'Toggle element for Q&A block', MAD_BASE_TEXTDOMAIN ),
//	'params' => array(
//		array(
//			'type' => 'textfield',
//			'holder' => 'h4',
//			'class' => 'vc_toggle_title',
//			'heading' => __( 'Toggle title', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'title',
//			'value' => __( 'Toggle title', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Toggle block title.', MAD_BASE_TEXTDOMAIN )
//		),
//		array(
//			'type' => 'textarea_html',
//			'holder' => 'div',
//			'class' => 'vc_toggle_content',
//			'heading' => __( 'Toggle content', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'content',
//			'value' => __( '<p>Toggle content goes here, click edit button to change this text.</p>', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Toggle block content.', MAD_BASE_TEXTDOMAIN )
//		),
//	),
//	'js_view' => 'VcToggleView'
//) );


/* Video element
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Video Player', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_video',
	'icon' => 'icon-wpb-film-youtube',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Embed YouTube/Vimeo player', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Video link', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'link',
			'admin_label' => true,
			'description' => sprintf( __( 'Link to the video. More about supported formats at %s.', MAD_BASE_TEXTDOMAIN ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN )
		),
		$mad_add_css_animation,
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN ),
			'group' => __( 'Design options', MAD_BASE_TEXTDOMAIN )
		),
	)
) );


/* Custom Heading element
----------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Custom Heading', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_custom_heading',
	'icon' => 'icon-wpb-ui-custom_heading',
	'show_settings_on_create' => true,
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Add custom heading text with google fonts', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textarea',
			'heading' => __( 'Text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'text',
			'admin_label' => true,
			'value'=> __( 'This is custom heading element with Google Fonts', MAD_BASE_TEXTDOMAIN ),
			'description' => __( 'Enter your content. If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', MAD_BASE_TEXTDOMAIN ),
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
					//'font_style_italic'
					//'font_style_bold'
					//'font_family'

					'tag_description' => __( 'Select element tag.', 'js_composer' ),
					'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
					'font_size_description' => __( 'Enter font size.', 'js_composer' ),
					'line_height_description' => __( 'Enter line height.', 'js_composer' ),
					'color_description' => __( 'Select heading color.', 'js_composer' ),
					//'font_style_description' => __('Put your description here','js_composer'),
					//'font_family_description' => __('Put your description here','js_composer'),
				),
			),
			// 'description' => __( '', 'js_composer' ),
		),
		array(
			'type' => 'google_fonts',
			'param_name' => 'google_fonts',
			'value' => 'font_family:Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic|font_style:300%20regular%3A300%3Anormal',
			// default
			//'font_family:'.rawurlencode('Abril Fatface:400').'|font_style:'.rawurlencode('400 regular:400:normal')
			// this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
			'settings' => array(
				//'no_font_style' // Method 1: To disable font style
				//'no_font_style'=>true // Method 2: To disable font style
				'fields' => array(
					//'font_family' => 'Abril Fatface:regular',
					//'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',// Default font family and all available styles to fetch
					//'font_style' => '400 regular:400:normal',
					// Default font style. Name:weight:style, example: "800 bold regular:800:normal"
					'font_family_description' => __( 'Select font family.', 'js_composer' ),
					'font_style_description' => __( 'Select font styling.', 'js_composer' )
				)
			),
			'dependency' => array(
				'element' => 'use_theme_fonts',
				'value_not_equal_to' => 'yes',
			),
			// 'description' => __( '', 'js_composer' ),
		),
//		array(
//			'type' => 'textfield',
//			'heading' => __( 'Font size', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'font_size',
//			'group' => __( 'Font styles', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Enter font size.', MAD_BASE_TEXTDOMAIN )
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => __( 'Font weight', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'font_weight',
//			'group' => __( 'Font styles', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Enter font weight.', MAD_BASE_TEXTDOMAIN )
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => __( 'Font style', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'font_style',
//			'value' => array(
//				'normal' => 'normal',
//				'italic' => 'italic'
//			),
//			'std' => 'normal',
//			'group' => __( 'Font styles', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Choose font style.', MAD_BASE_TEXTDOMAIN )
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => __( 'Text align', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'text_align',
//			'value' => array(
//				'left' => 'align-left',
//				'center' => 'align-center',
//				'right' => 'align-right'
//			),
//			'group' => __( 'Font styles', MAD_BASE_TEXTDOMAIN ),
//			'description' => __( 'Select text alignment.', MAD_BASE_TEXTDOMAIN )
//		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Heading Color', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'heading_color',
			'group' => __( 'Font styles', MAD_BASE_TEXTDOMAIN ),
			'description' => __( 'Select heading color for your heading.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => 'checkbox',
			"heading" => __( 'With bottom border', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "with_bottom_border",
			"description" => "Adds a bottom border to your heading.",
			"value" => array(
				__( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'on'
			)
		),
		$mad_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'css',
			'group' => __( 'Design options', MAD_BASE_TEXTDOMAIN )
		)
	),
) );


/* Button
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Button', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_button',
	'icon' => 'icon-wpb-ui-button',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Eye catching button', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', MAD_BASE_TEXTDOMAIN ),
			'holder' => 'button',
			'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => __( 'Text on the button', MAD_BASE_TEXTDOMAIN ),
			'description' => __( 'Text on the button.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'href',
			'heading' => __( 'URL (Link)', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'href',
			'description' => __( 'Button link.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'target',
			'value' => $mad_target_arr,
			'dependency' => array(
				'element' => 'href',
				'not_empty' => true,
				'callback' => 'vc_button_param_target_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'color',
			'value' => $mad_colors_arr,
			'description' => __( 'Button color.', MAD_BASE_TEXTDOMAIN ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'icon',
			'value' => $mad_icons_arr,
			'description' => __( 'Button icon.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'size',
			'value' => $mad_size_arr,
			'description' => __( 'Button size.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button alignment', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'align',
			'value' => array(
				__( 'Left', MAD_BASE_TEXTDOMAIN ) => 'align-left',
				__( 'Center', MAD_BASE_TEXTDOMAIN ) => 'align-center',
				__( 'Right', MAD_BASE_TEXTDOMAIN ) => "align-right"
			),
			'description' => __( 'Select button alignment.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		$mad_add_css_animation
	),
	'js_view' => 'VcButtonView'
) );

/* Message box
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Message Box', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_message',
	'icon' => 'icon-wpb-information-white',
	'wrapper_class' => 'alert',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Notification box', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Message box type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'color',
			'value' => array(
				__( 'Informational', MAD_BASE_TEXTDOMAIN ) => 'alert-info',
				__( 'Warning', MAD_BASE_TEXTDOMAIN ) => 'alert-warning',
				__( 'Success', MAD_BASE_TEXTDOMAIN ) => 'alert-success',
				__( 'Error', MAD_BASE_TEXTDOMAIN ) => "alert-danger"
			),
			'description' => __( 'Select message type.', MAD_BASE_TEXTDOMAIN ),
			'param_holder_class' => 'vc_message-type'
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'messagebox_text',
			'heading' => __( 'Message text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'content',
			'value' => __( '<p>I am message box. Click edit button to change this text.</p>', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN )
		)
	),
	'js_view' => 'VcMessageView'
) );


/* Separator (Divider)
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Separator', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_separator',
	'icon' => 'icon-wpb-ui-separator',
	'show_settings_on_create' => true,
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Horizontal separator line', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'color',
			'value' => array_merge( madGetVcShared( 'colors' ), array( __( 'Custom color', MAD_BASE_TEXTDOMAIN ) => 'custom' ) ),
			'std' => 'grey',
			'description' => __( 'Separator color.', MAD_BASE_TEXTDOMAIN ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Separator alignment', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'align',
			'value' => array(
				__( 'Center', MAD_BASE_TEXTDOMAIN ) => 'align_center',
				__( 'Left', MAD_BASE_TEXTDOMAIN ) => 'align_left',
				__( 'Right', MAD_BASE_TEXTDOMAIN ) => "align_right"
			),
			'description' => __( 'Select separator alignment.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Border Color', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'accent_color',
			'description' => __( 'Select border color for your element.', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'style',
			'value' => madGetVcShared( 'separator styles' ),
			'description' => __( 'Separator style.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border width', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'border_width',
			'value' => madGetVcShared( 'separator border widths' ),
			'description' => __( 'Border width in pixels.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_width',
			'value' => madGetVcShared( 'separator widths' ),
			'description' => __( 'Separator element width in percents.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* Theme Shortcodes
/* ---------------------------------------------------------------- */

/* List Styles
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'List Styles', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_list_styles',
	'icon' => 'icon-wpb-mad-list-styles',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'List styles', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'List Type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'list_type',
			'value' => array(
				__( 'Unordered', MAD_BASE_TEXTDOMAIN ) => 'unordered',
				__( 'Ordered', MAD_BASE_TEXTDOMAIN ) => 'ordered'
			),
			'description' => __( 'Choose list type', MAD_BASE_TEXTDOMAIN ),
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'List Unordered Styles', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'list_unordered_styles',
			'value' => $mad_list_unordered_styles,
			'description' => __( 'Choose styles for unordered list', MAD_BASE_TEXTDOMAIN ),
			"dependency" => array(
				"element" => "list_type",
				"value" => array("unordered")
			)
//			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'List Ordered Styles', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'list_ordered_styles',
			'value' => $mad_list_ordered_styles,
			'description' => __( 'Choose styles for ordered list', MAD_BASE_TEXTDOMAIN ),
			"dependency" => array(
				"element" => "list_type",
				"value" => array("ordered")
			)
//			'admin_label' => true
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'List Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'values',
			'description' => __( 'Input list items values. Divide values with linebreaks (Enter). Example: Development|Design', MAD_BASE_TEXTDOMAIN ),
			'value' => "Development|Design|Marketing"
		),
		$mad_add_css_animation
	)
) );

/* Tables
---------------------------------------------------------- */

vc_map( array(
	"name"		=> __('Tables', MAD_BASE_TEXTDOMAIN),
	"base"		=> "vc_mad_tables",
	"icon"		=> "icon-wpb-mad-tables",
	"is_container" => true,
	"category"  => __('Content', MAD_BASE_TEXTDOMAIN),
	"description" => __('Tables', MAD_BASE_TEXTDOMAIN),
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "title",
			"holder" => "h4",
			"description" => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "table_number",
			"heading" => __("Columns", MAD_BASE_TEXTDOMAIN),
			"param_name" => "columns",
			"value" => ''
		),
		array(
			"type" => "table_number",
			"heading" => __( 'Rows', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "rows",
			"description" => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "table_hidden",
			"param_name" => "data",
			"class" => "tables-hidden-data",
			"description" => __( '', MAD_BASE_TEXTDOMAIN )
		)
	)
));


/* Info Block
---------------------------------------------------------- */

vc_map( array(
	"name"		=> __('Info Block', MAD_BASE_TEXTDOMAIN),
	"base"		=> "vc_mad_info_block",
	"icon"		=> "icon-wpb-mad-info-block",
	"is_container" => false,
	"category"  => __('Content', MAD_BASE_TEXTDOMAIN),
	"description" => __('Styled info blocks', MAD_BASE_TEXTDOMAIN),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => __( 'Select type', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "type",
			"value" => array(
				'Type 1' => 'type-1',
				'Type 2' => 'type-2',
				'Type 3' => 'type-3',
				'Color Blocks' => 'type-4',
				'Contact Info' => 'type-5'
			),
			"description" => __( 'Choose type for this info block.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "textfield",
			"heading" => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "title",
			"holder" => "h4",
			"dependency" => array(
				'element' => "type",
				'value' => array('type-1', 'type-2', 'type-3')
			),
			"description" => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Background color', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "bg_color",
			"value" => "#2ecc71",
			"description" => __( 'Set background color for info block.', MAD_BASE_TEXTDOMAIN ),
			"dependency" => array(
				'element' => "type",
				'value' => array('type-4')
			)
		),
		array(
			"type" => "choose_icons",
			"heading" => __("Icon", MAD_BASE_TEXTDOMAIN),
			"param_name" => "icon",
			"value" => 'none'
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'content',
			'value' => __( '<p>Click edit button to change this text.</p>', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "vc_link",
			"heading" => __( 'Add URL to the whole box (optional)', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "link",
			"dependency" => array(
				'element' => "type",
				'value' => array('type-1', 'type-2', 'type-3', 'type-4')
			)
		),
		$mad_add_css_animation
	)
));

/* Price Table
---------------------------------------------------------- */

vc_map( array(
	"name"		=> __('Pricing Table', MAD_BASE_TEXTDOMAIN),
	"base"		=> "vc_mad_pricing_box",
	"icon"		=> "icon-wpb-mad-pricing-box",
//	"allowed_container_element" => false,
	"is_container" => false,
	"category"  => __('Content', MAD_BASE_TEXTDOMAIN),
	"description" => __('Styled pricing tables', MAD_BASE_TEXTDOMAIN),
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "title",
			"holder" => "h4",
			"description" => __( 'Give your plan a title.', MAD_BASE_TEXTDOMAIN ),
			"value" => __( 'Free', MAD_BASE_TEXTDOMAIN ),
		),
		array(
			"type" => "textfield",
			"heading" => __( 'Currency', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "currency",
			"holder" => "span",
			"description" => __( 'Enter currency symbol or text, e.g., $ or USD.', MAD_BASE_TEXTDOMAIN ),
			"value" => __( '$', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "textfield",
			"heading" => __( 'Price', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "price",
			"holder" => "span",
			"description" => __( 'Set the price for this plan.', MAD_BASE_TEXTDOMAIN ),
			"value" => __( '15', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "textfield",
			"heading" => __( 'Time', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "time",
			"holder" => "span",
			"description" => __( 'Choose time span for you plan, e.g., per month', MAD_BASE_TEXTDOMAIN ),
			"value" => __( 'per month', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "textarea",
			"heading" => __( 'Features', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "features",
			"holder" => "span",
			"description" => __( 'A short description or list for the plan.', MAD_BASE_TEXTDOMAIN ),
			"value" => __( 'Up to 50 users | Limited team members | Limited disk space | Custom Domain | PayPal Integration | Basecamp Integration', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "vc_link",
			"heading" => __( 'Add URL to the whole box (optional)', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "link",
		),
		array(
			"type" => "dropdown",
			"heading" => __( 'Select style', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "box_style",
			"value" => array(
				'Dark' => 'bg_color_dark',
				'Green' => 'bg_color_green',
				'Orange' => 'bg_color_orange',
				'Red' => 'bg_color_red',
				'Blue' => 'bg_color_blue',
				'Custom' => 'custom'
			),
			"description" => __( 'Choose style for this pricing box.', MAD_BASE_TEXTDOMAIN ),
			"group" => __('Design', MAD_BASE_TEXTDOMAIN)
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Header Background color', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "header_bg_color",
			"value" => "#292f38",
			"description" => __( 'Set background color for pricing box header.', MAD_BASE_TEXTDOMAIN ),
			"group" => __('Design', MAD_BASE_TEXTDOMAIN),
			"dependency" => array(
				'element' => "box_style",
				'value' => array('custom')
			)
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Main Background color', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "main_bg_color",
			"value" => "#323a45",
			"description" => __( 'Set background color for pricing box main.', MAD_BASE_TEXTDOMAIN ),
			"group" => __('Design', MAD_BASE_TEXTDOMAIN),
			"dependency" => array(
				'element' => "box_style",
				'value' => array('custom')
			)
		),
		array(
			"type" => 'checkbox',
			"heading" => __( 'Add hot?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "add_hot",
			"group" => __('Hot', MAD_BASE_TEXTDOMAIN),
			"description" => "Adds a nice hot to your pricing box.",
			"value" => array(
				__( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'on'
			)
		),
		$mad_add_css_animation
	)
//	"js_view" => 'VcPricingView'
));

/* Single Image
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Single Image', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_single_image',
	'icon' => 'icon-wpb-single-image',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Simple image', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size in pixels: 200*100 (Width * Height). Leave empty to use full size.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image alignment', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'None', MAD_BASE_TEXTDOMAIN ) => 'none',
				__( 'Left', MAD_BASE_TEXTDOMAIN ) => 'left',
				__( 'Right', MAD_BASE_TEXTDOMAIN ) => 'right',
				__( 'Center', MAD_BASE_TEXTDOMAIN ) => 'center'
			),
			'description' => __( 'Select image alignment.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Link to lightbox?', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'img_link_large',
			'description' => __( 'If selected, image will be linked to the larger image in lightbox.', MAD_BASE_TEXTDOMAIN ),
			'value' => array(
				__( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes'
			)
		),
		array(
			'type' => 'href',
			'heading' => __( 'Image link', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'link',
			'description' => __( 'Enter URL if you want this image to have a link.', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'img_link_large',
				'is_empty' => true,
				'callback' => 'wpb_single_image_img_link_dependency_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link Target', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'img_link_target',
			'value' => $mad_target_arr,
			'dependency' => array(
				'element' => 'img_link',
				'not_empty' => true
			)
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN ),
			'group' => __( 'Design options', MAD_BASE_TEXTDOMAIN )
		)
	)
) );


/* Testimonials
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Testimonials', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_testimonials',
	'icon' => 'icon-wpb-mad-testimonials',
	'description' => __( 'Testimonials post type', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'edit_field_class' => 'vc_col-sm-6',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Tag for title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'tag_title',
			'value' => array(
				'h1' => 'h1',
				'h2' => 'h2'
			),
			'std' => 'h2',
			'edit_field_class' => 'vc_col-sm-6',
			'description' => __( 'Choose tag for title.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text align', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'text_align',
			'value' => array(
				'left' => 'align-left',
				'center' => 'align-center',
				'right' => 'align-right'
			),
			'std' => 'align-left',
			'group' => __( 'Text styles', MAD_BASE_TEXTDOMAIN ),
			'description' => __( 'Select testimonials alignment.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Testimonial Style', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'style',
			'value' => array(
				__( 'Testimonial List', MAD_BASE_TEXTDOMAIN ) => 'tm-list',
				__( 'Testimonial Slider', MAD_BASE_TEXTDOMAIN ) => 'tm-slider'
			),
			'description' => __( 'Here you can select how to display the testimonials. You can either create a testimonial slider or a testimonial grid with multiple columns', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Count Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'items',
			'value' => MAD_VC_CONFIG::array_number(1, 10, 1, array('All' => '-1')),
			'std' => -1,
			'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display show image', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'display_show_image',
			'description' => __( 'output date', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			"type" => "term_categories",
			"term" => "testimonials_category",
			'heading' => __( 'Which categories should be used for the testimonials?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "categories",
			"holder" => "div",
			'description' => __('The Page will then show testimonials from only those categories.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'orderby',
			'value' => MAD_VC_CONFIG::get_order_sort_array(),
			'description' => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'order',
			'value' => array(
				__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'DESC',
				__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'ASC',
			),
			'description' => __( 'Direction Order', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Pagination', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'pagination',
			'value' => array(
				__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
				__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
			),
			'dependency' => array(
				'element' => 'style',
				'value' => array('tm-list')
			),
			'description' => __( 'Should a pagination be displayed?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Autoplay', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplay',
			'description' => __( 'Enables autoplay mode.', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'number',
			'heading' => __( 'Autoplay timeout', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplaytimeout',
			'description' => __( 'Autoplay interval timeout', MAD_BASE_TEXTDOMAIN ),
			'value' => 5000,
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array( 'yes' )
			)
		),
		$mad_short_css_animation
	)
) );


/* Brands Logo
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Brands Logo', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_brands_logo',
	'icon' => 'icon-wpb-brands-logo',
	'description' => __( 'Brands logo', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Twin Items?', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'twin',
			'description' => __( '', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "textarea",
			"heading" => __( 'Links', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "links",
			"holder" => "span",
			"description" => __( 'Input links values. Divide values with linebreaks (|). Example: http://brand.com | http://brand2.com', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Autoplay', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplay',
			'description' => __( 'Enables autoplay mode.', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'number',
			'heading' => __( 'Autoplay timeout', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplaytimeout',
			'description' => __( 'Autoplay interval timeout', MAD_BASE_TEXTDOMAIN ),
			'value' => 5000,
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array( 'yes' )
			)
		),
		$mad_add_css_animation
	)
) );

/* Team Members
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Team Members', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_team_members',
	'icon' => 'icon-wpb-mad-team-members',
	'description' => __( 'Team Members post type', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Count Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'items',
			'value' => MAD_VC_CONFIG::array_number(1, 12, 1, array('All' => '-1')),
			'std' => -1,
			'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "term_categories",
			"term" => "team_category",
			'heading' => __( 'Which categories should be used for the team?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "categories",
			"holder" => "div",
			'description' => __('The Page will then show team from only those categories.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'orderby',
			'value' => MAD_VC_CONFIG::get_order_sort_array(),
			'description' => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'order',
			'value' => array(
				__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'DESC',
				__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'ASC',
			),
			'description' => __( 'Direction Order', MAD_BASE_TEXTDOMAIN )
		)
	)
) );

/* Blog Posts
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Blog Posts', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_blog_posts',
	'icon' => 'icon-wpb-application-icon-large',
	'description' => __( 'Blog posts', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
//		array(
//			'type' => 'loop',
//			'heading' => __( 'Entries content', MAD_BASE_TEXTDOMAIN ),
//			'param_name' => 'loop',
//			'settings' => array(
//				'size' => array( 'hidden' => false, 'value' => 10 ),
//				'order_by' => array( 'value' => 'date' ),
//			),
//			'description' => __( 'Create WordPress loop, to populate content from your site.', MAD_BASE_TEXTDOMAIN )
//		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Blog Style', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'blog_style',
			'value' => array(
				__( 'Blog Medium', MAD_BASE_TEXTDOMAIN ) => 'blog-medium',
				__( 'Blog Big', MAD_BASE_TEXTDOMAIN ) => 'blog-big',
				__( 'Blog Grid', MAD_BASE_TEXTDOMAIN ) => 'blog-grid'
			),
			'description' => __( 'Choose the default blog layout here.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "term_categories",
			"term" => "category",
			'heading' => __( 'Which categories should be used for the blog?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "category",
			"holder" => "div",
			'description' => __('The Page will then show entries from only those categories.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'orderby',
			'value' => MAD_VC_CONFIG::get_order_sort_array(),
			'std' => 'date',
			'description' => __( 'Sort retrieved posts by parameter', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'order',
			'value' => array(
				__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'DESC',
				__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'ASC'
			),
			'description' => __( 'In what direction order?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Posts Count', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'posts_per_page',
			'value' => MAD_VC_CONFIG::array_number(1, 50, 1, array('-1' => 'All')),
			'std' => 5,
			'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'First post big?', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'first_big_post',
			'description' => __( '', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'blog_style',
				'value' => array('blog-medium')
			),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Pagination', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'pagination',
			'value' => array(
				__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
				__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
			),
			'description' => __( 'Should a pagination be displayed?', MAD_BASE_TEXTDOMAIN )
		)
	)
) );


/* Banners
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Banners', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_banners',
	'icon' => 'icon-wpb-mad-banners',
	'description' => __( '2 type banners', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'type',
			'value' => array(
				__( 'Type 1', MAD_BASE_TEXTDOMAIN ) => 'type-1',
				__( 'Type 2', MAD_BASE_TEXTDOMAIN ) => 'type-2'
			),
			'description' => __( 'Type styles banner', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Border color', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "border_color",
			"value" => "#e74c3c",
			"description" => __( 'Set border color for banner Type 2.', MAD_BASE_TEXTDOMAIN ),
			"dependency" => array(
				'element' => "type",
				'value' => array('type-2')
			),
			'group' => __( 'Styling', MAD_BASE_TEXTDOMAIN ),
			"std" => '#e74c3c'
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Background color', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "bg_color",
			"value" => "#ffffff",
			"description" => __( 'Set background color for banner Type 2.', MAD_BASE_TEXTDOMAIN ),
			"dependency" => array(
				'element' => "type",
				'value' => array('type-2')
			),
			'group' => __( 'Styling', MAD_BASE_TEXTDOMAIN ),
			"std" => '#ffffff'
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'content',
			"dependency" => array(
				'element' => "type",
				'value' => array('type-2')
			),
			'value' => __( '<p>Click edit button to change this text.</p>', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "vc_link",
			"heading" => __( 'Add URL to the button', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "link"
		),
		$mad_add_css_animation
	)
) );


/* Posts Slider
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Posts Slider', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_posts_slider',
	'icon' => 'icon-wpb-mad-posts-slider',
	'description' => __( 'Blog posts', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "term_categories",
			"term" => "category",
			'heading' => __( 'Which categories should be used for the blog?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "category",
			"holder" => "div",
			'description' => __('The Page will then show entries from only those categories.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'orderby',
			'value' => MAD_VC_CONFIG::get_order_sort_array(),
			'std' => 'date',
			'description' => __( 'Sort retrieved posts by parameter', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'order',
			'value' => array(
				__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'DESC',
				__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'ASC'
			),
			'description' => __( 'In what direction order?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Posts Count', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'posts_per_page',
			'value' => MAD_VC_CONFIG::array_number(1, 50, 1, array('-1' => 'All')),
			'std' => 5,
			'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'items',
			'value' => MAD_VC_CONFIG::array_number(1, 2, 1),
			'std' => 1,
			'description' => __( 'This variable allows you to set the maximum amount of items displayed at a time with the widest browser width', MAD_BASE_TEXTDOMAIN )
		),
		$mad_short_css_animation
	)
) );

if (class_exists('WooCommerce')) {

	/* Product Grid
	---------------------------------------------------------- */

	vc_map( array(
		'name' => __( 'Products', MAD_BASE_TEXTDOMAIN ),
		'base' => 'vc_mad_products',
		'icon' => 'icon-wpb-mad-woocommerce',
		'category' => __( 'WooCommerce', MAD_BASE_TEXTDOMAIN ),
		'description' => __( 'Displayed for product grid', MAD_BASE_TEXTDOMAIN ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'title',
				'edit_field_class' => 'vc_col-sm-6',
				'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Tag for title', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'tag_title',
				'value' => array(
					'h1' => 'h1',
					'h2' => 'h2'
				),
				'std' => 'h2',
				'edit_field_class' => 'vc_col-sm-6',
				'description' => __( 'Choose tag for title.', MAD_BASE_TEXTDOMAIN )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Type', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'type',
				'value' => array(
					__('Grid', MAD_BASE_TEXTDOMAIN) => 'product-grid',
					__('Carousel', MAD_BASE_TEXTDOMAIN) => 'product-carousel'
				),
				'description' => __('Choose the type style.', MAD_BASE_TEXTDOMAIN)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Layout', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'layout',
				'value' => array(
					__('Grid View', MAD_BASE_TEXTDOMAIN) => 'view-grid',
					__('Grid View (meta data align center)', MAD_BASE_TEXTDOMAIN) => 'view-grid-center',
					__('Grid List', MAD_BASE_TEXTDOMAIN) => 'view-list'
				),
				'dependency' => array(
					'element' => 'type',
					'value' => array('product-grid')
				),
				'description' => __('Choose layout style.', MAD_BASE_TEXTDOMAIN)
			),
			array(
				'type' => 'autocomplete',
				'settings' => array(
					'multiple' => true,
					// is multiple values allowed? default false
					// 'sortable' => true, // is values are sortable? default false
					'min_length' => 2,
					// min length to start search -> default 2
					// 'no_hide' => true, // In UI after select doesn't hide an select list, default false
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
					// 'values' => $taxonomies_list,
				),
				'heading' => __( 'Select identificators', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'by_id',
				'admin_label' => true,
				'description' => __('Input product ID or product SKU or product title to see suggestions', MAD_BASE_TEXTDOMAIN)
			),
			array(
				"type" => "term_categories",
				"term" => "product_cat",
				'heading' => __( 'Which categories should be used for the products?', MAD_BASE_TEXTDOMAIN ),
				"param_name" => "categories",
				"holder" => "div",
				'description' => __('The Page will then show products from only those categories.', MAD_BASE_TEXTDOMAIN)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Columns', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'columns',
				'value' => array(
					__( '3 Columns', MAD_BASE_TEXTDOMAIN ) => 3,
					__( '4 Columns', MAD_BASE_TEXTDOMAIN ) => 4
				),
				'description' => __( 'How many columns should be displayed?', MAD_BASE_TEXTDOMAIN ),
				'param_holder_class' => ''
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Count Items', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'items',
				'value' => MAD_VC_CONFIG::array_number(1, 30, 1, array('All' => -1)),
				'std' => 9,
				'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN ),
				'param_holder_class' => ''
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Show', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'show',
				'value' => array(
					__( 'All Products', MAD_BASE_TEXTDOMAIN ) => '',
					__( 'Featured Products', MAD_BASE_TEXTDOMAIN ) => 'featured',
					__( 'On-sale Products', MAD_BASE_TEXTDOMAIN ) => 'onsale',
					__( 'Best Selling Products', MAD_BASE_TEXTDOMAIN ) => 'bestselling',
					__( 'Top Rated Products', MAD_BASE_TEXTDOMAIN ) => 'toprated'
				),
				'description' => __( '', MAD_BASE_TEXTDOMAIN ),
				'std' => 'desc'
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order by', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'orderby',
				'value' => array(
					__('Default', MAD_BASE_TEXTDOMAIN ) => '',
					__('Date', MAD_BASE_TEXTDOMAIN ) => 'date',
					__('Price', MAD_BASE_TEXTDOMAIN ) => 'price',
					__('Random', MAD_BASE_TEXTDOMAIN ) => 'rand',
					__('Sales', MAD_BASE_TEXTDOMAIN ) => 'sales',
					__('Sort by Ids', MAD_BASE_TEXTDOMAIN ) => 'post__in',
					__('Sort alphabetically', MAD_BASE_TEXTDOMAIN ) => 'title',
					__('Sort by popularity', MAD_BASE_TEXTDOMAIN ) => 'popularity'
				),
				'description' => __( 'Here you can choose how to display the products', MAD_BASE_TEXTDOMAIN )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Sorting Order', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'order',
				'value' => array(
					__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'asc',
					__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'desc'
				),
				'description' => __( 'Here you can choose how to display the products', MAD_BASE_TEXTDOMAIN ),
				'std' => 'desc'
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Filter', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'filter',
				'value' => array(
					__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
					__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
				),
				'description' => __( 'Should the filter options based on categories be displayed?', MAD_BASE_TEXTDOMAIN )
			),
			array(
				'type' => 'checkbox',
				'heading' => __( 'Add random filter item?', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'random',
				'description' => __( 'Sort by random', MAD_BASE_TEXTDOMAIN ),
				'dependency' => array(
					'element' => 'filter',
					'value' => array('yes')
				),
				'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
			),
			array(
				'type' => 'checkbox',
				'heading' => __( 'Add sale filter item?', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'sale',
				'description' => __( 'Sort by price sale', MAD_BASE_TEXTDOMAIN ),
				'dependency' => array(
					'element' => 'filter',
					'value' => array('yes')
				),
				'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Pagination', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'pagination',
				'value' => array(
					__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
					__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
				),
				'dependency' => array(
					'element' => 'filter',
					'value' => array('no')
				),
				'description' => __( 'Should a pagination be displayed?', MAD_BASE_TEXTDOMAIN )
			),
			$mad_add_css_animation
		)
	) );

	$Vc_Vendor_Woocommerce = new Vc_Vendor_Woocommerce();

	//Filters For autocomplete param:
	//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
	add_filter( 'vc_autocomplete_vc_mad_products_by_id_callback', array($Vc_Vendor_Woocommerce, 'productIdAutocompleteSuggester' ), 10, 1 );
	// Get suggestion(find). Must return an array
	add_filter( 'vc_autocomplete_vc_mad_products_by_id_render', array($Vc_Vendor_Woocommerce, 'productIdAutocompleteRender' ), 10, 1 );
	// Render exact product. Must return an array (label,value)
	//For param: ID default value filter
	add_filter( 'vc_form_fields_render_field_vc_mad_products_by_id_param_value', array($Vc_Vendor_Woocommerce, 'productIdDefaultValue' ), 10, 4 );
	// Defines default value for param if not provided. Takes from other param value.

	/* MAD VC woocommerce product attribute */
	$attributes_tax = wc_get_attribute_taxonomies();
	$attributes = array();
	foreach ( $attributes_tax as $attribute ) {
		$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
	}

	$order_by_values = array(
		'',
		__( 'Date', 'js_composer' ) => 'date',
		__( 'ID', 'js_composer' ) => 'ID',
		__( 'Author', 'js_composer' ) => 'author',
		__( 'Title', 'js_composer' ) => 'title',
		__( 'Modified', 'js_composer' ) => 'modified',
		__( 'Random', 'js_composer' ) => 'rand',
		__( 'Comment count', 'js_composer' ) => 'comment_count',
		__( 'Menu order', 'js_composer' ) => 'menu_order',
	);

	$order_way_values = array(
		'',
		__( 'Descending', 'js_composer' ) => 'DESC',
		__( 'Ascending', 'js_composer' ) => 'ASC',
	);

	vc_map( array(
		'name' => __( 'Product Attribute', MAD_BASE_TEXTDOMAIN),
		'base' => 'vc_mad_product_attribute',
		'icon' => 'icon-wpb-woocommerce',
		'category' => __( 'WooCommerce', MAD_BASE_TEXTDOMAIN ),
		'description' => __( 'List products with an attribute shortcode', MAD_BASE_TEXTDOMAIN ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Per page', MAD_BASE_TEXTDOMAIN ),
				'value' => 12,
				'param_name' => 'per_page',
				'description' => __( 'How much items per page to show', MAD_BASE_TEXTDOMAIN ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Columns', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'columns',
				'value' => array(
					__( '3 Columns', MAD_BASE_TEXTDOMAIN ) => 3,
					__( '4 Columns', MAD_BASE_TEXTDOMAIN ) => 4
				),
				'description' => __( 'How many columns should be displayed?', MAD_BASE_TEXTDOMAIN ),
				'param_holder_class' => ''
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order by', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'orderby',
				'value' => $order_by_values,
				'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order way', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'order',
				'value' => $order_way_values,
				'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Attribute', MAD_BASE_TEXTDOMAIN),
				'param_name' => 'attribute',
				'value' => $attributes,
				'description' => __( 'List of product taxonomy attribute', MAD_BASE_TEXTDOMAIN ),
			),
			array(
				'type' => 'checkbox',
				'heading' => __( 'Filter', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'filter',
				'value' => array( 'empty' => 'empty' ),
				'description' => __( 'Taxonomy values', MAD_BASE_TEXTDOMAIN ),
				'dependency' => array(
					'element' => 'attribute',
					'is_empty' => true,
					'callback' => 'madvcWoocommerceProductAttributeFilterDependencyCallback',
				),
			),
		)
	) );

	/* VC woocommerce order tracking */
	vc_map( array(
			"name" => __("Order Tracking", MAD_BASE_TEXTDOMAIN),
			"base" => "woocommerce_order_tracking",
			"icon" => 'icon-wpb-mad-woocommerce',
			"class" => "wp_woo",
			"category" => __('WooCommerce', MAD_BASE_TEXTDOMAIN),
			"show_settings_on_create" => false
		)
	);

	/* VC woocommerce cart */
	vc_map( array(
			"name" => __("Cart", MAD_BASE_TEXTDOMAIN),
			"base" => "woocommerce_cart",
			"icon" => 'icon-wpb-mad-woocommerce',
			"class" => "wp_woo",
			"category" => __('WooCommerce', MAD_BASE_TEXTDOMAIN),
			"show_settings_on_create" => false
		)
	);

	/* VC woocommerce checkout */
	vc_map( array(
			"name" => __("Checkout", MAD_BASE_TEXTDOMAIN),
			"base" => "woocommerce_checkout",
			"icon" => 'icon-wpb-mad-woocommerce',
			"category" => __('WooCommerce', MAD_BASE_TEXTDOMAIN),
			"show_settings_on_create" => false
		)
	);

	/* VC woocommerce my account */
	vc_map( array(
			"name" => __("My Account", MAD_BASE_TEXTDOMAIN),
			"base" => "woocommerce_my_account",
			"icon" => 'icon-wpb-mad-woocommerce',
			"category" => __('WooCommerce', MAD_BASE_TEXTDOMAIN),
			"show_settings_on_create" => false
		)
	);

	if (defined('YITH_WCWL')) {

		/* VC woocommerce my wishlist */
		vc_map( array(
				"name" => __("Wishlist", MAD_BASE_TEXTDOMAIN),
				"base" => "vc_mad_yith_wcwl_wishlist",
				"icon" => 'icon-wpb-mad-woocommerce',
				"category" => __('WooCommerce', MAD_BASE_TEXTDOMAIN),
				"params" => array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Pagination', MAD_BASE_TEXTDOMAIN ),
						'param_name' => 'pagination',
						'value' => array(
							__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
							__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
						),
						'description' => __( 'Should a pagination be displayed?', MAD_BASE_TEXTDOMAIN )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Count Items', MAD_BASE_TEXTDOMAIN ),
						'param_name' => 'per_page',
						'value' => MAD_VC_CONFIG::array_number(1, 51, 1, array('All' => '-1')),
						'std' => -1,
						'dependency' => array(
							'element' => 'pagination',
							'value' => array('yes')
						),
						'description' => __( 'A number of products on one page', MAD_BASE_TEXTDOMAIN ),
					)
				)
			)
		);

	}

}


/* Portfolio
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Portfolio', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_portfolio',
	'class' => '',
	'icon' => 'icon-wpb-vc_carousel',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Displayed for portfolio items', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'title', MAD_BASE_TEXTDOMAIN ),
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Tag for title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'tag_title',
			'value' => array(
				'h1' => 'h1',
				'h2' => 'h2'
			),
			'std' => 'h2',
			'edit_field_class' => 'vc_col-sm-6',
			'description' => __( 'Choose tag for title.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'layout',
			'value' => array(
				__( 'Grid', MAD_BASE_TEXTDOMAIN ) => 'grid',
				__( 'Carousel', MAD_BASE_TEXTDOMAIN ) => 'carousel',
				__( 'Masonry', MAD_BASE_TEXTDOMAIN ) => 'masonry'
			),
			'description' => __( 'Layout be displayed?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			"type" => "term_categories",
			"term" => "portfolio_categories",
			'heading' => __( 'Which categories should be used for the portfolio?', MAD_BASE_TEXTDOMAIN ),
			"param_name" => "categories",
			"holder" => "div",
			'description' => __('The Page will then show portfolio from only those categories.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'orderby',
			'value' => MAD_VC_CONFIG::get_order_sort_array(),
			'description' => __( 'Sort retrieved items by parameter', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'order',
			'value' => array(
				__( 'DESC', MAD_BASE_TEXTDOMAIN ) => 'DESC',
				__( 'ASC', MAD_BASE_TEXTDOMAIN ) => 'ASC',
			),
			'description' => __( 'Direction Order', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'columns',
			'value' => array(
				__( '2 Columns', MAD_BASE_TEXTDOMAIN ) => 2,
				__( '3 Columns', MAD_BASE_TEXTDOMAIN ) => 3,
				__( '4 Columns', MAD_BASE_TEXTDOMAIN ) => 4,
				__( '5 Columns', MAD_BASE_TEXTDOMAIN ) => 5
			),
			'dependency' => array(
				'element' => 'layout',
				'value' => array('grid', 'carousel')
			),
			'std' => 3,
			'description' => __( 'How many columns should be displayed?', MAD_BASE_TEXTDOMAIN ),
			'param_holder_class' => ''
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Count Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'items',
			'value' => MAD_VC_CONFIG::array_number(1, 30, 1, array('All' => '-1')),
			'std' => -1,
			'description' => __( 'How many items should be displayed per page?', MAD_BASE_TEXTDOMAIN ),
			'param_holder_class' => ''
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Filter', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'sort',
			'value' => array(
				__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
				__( 'Yes', MAD_BASE_TEXTDOMAIN ) => 'yes'
			),
			'dependency' => array(
				'element' => 'layout',
				'value' => array('grid', 'masonry')
			),
			'description' => __( 'Should the sorting options based on categories be displayed?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'filter_style',
			'value' => array(
				__( 'Dropdown', MAD_BASE_TEXTDOMAIN ) => 'dropdown',
				__( 'Buttons', MAD_BASE_TEXTDOMAIN ) => 'buttons',
			),
			'dependency' => array(
				'element' => 'sort',
				'value' => array('yes'),
			),
			'std' => 'dropdown',
			'group' => __( 'Filter', MAD_BASE_TEXTDOMAIN ),
			'description' => __( 'Select filter display style.', MAD_BASE_TEXTDOMAIN ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Pagination', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'pagination',
			'value' => array(
				__( 'No', MAD_BASE_TEXTDOMAIN ) => 'no',
				__( 'Pagination', MAD_BASE_TEXTDOMAIN ) => 'yes',
				__( 'Load more button', MAD_BASE_TEXTDOMAIN ) => 'load-more'

			),
//			'dependency' => array(
//				'element' => 'sort',
//				'value' => array('no')
//			),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => __( 'Select pagination style.', MAD_BASE_TEXTDOMAIN ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Items per page', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'items_per_page',
			'description' => __( 'Number of items to show per page.', MAD_BASE_TEXTDOMAIN ),
			'value' => 4,
			'dependency' => array(
				'element' => 'pagination',
				'value' => array( 'load-more', 'pagination' ),
			),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Position of the text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'position_text',
			'value' => array(
				__( 'Bottom', MAD_BASE_TEXTDOMAIN ) => 'bottom',
				__( 'Inside', MAD_BASE_TEXTDOMAIN ) => 'inside'
			),
//			'dependency' => array(
//				'element' => 'layout',
//				'value' => array('grid')
//			),
			'std' => 'bottom',
			'description' => __( 'Select position of the text in item portfolio.', MAD_BASE_TEXTDOMAIN ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Related Items', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'related',
			'description' => __( 'display only related posts (To display the detailed portfolio page)', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'layout',
				'value' => array('grid')
			),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		)
	)
) );

/* About Portfolio
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'About Portfolio', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_about_portfolio',
	'icon' => 'icon-wpb-single-image',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'To display the detailed portfolio page', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'checkbox',
			'heading' => __( 'Date', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_date',
			'description' => __( 'output date', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Client', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_client',
			'description' => __( 'output client', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Services', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_services',
			'description' => __( 'output services', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Skills', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_skills',
			'description' => __( 'output skills', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Category', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_category',
			'description' => __( 'output category', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Tags', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'output_tags',
			'description' => __( 'output tags', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display share?', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'display_share',
			'description' => __( 'share social services', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		)
	)
) );


/* Portfolio Image List
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Portfolio Image List', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_portfolio_image_list',
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Image List for portfolio single', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size in pixels: 200*100 (Width * Height). Leave empty to use full size. Divide image size with (^). Example: 730*460^730*800^730*360', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN )
		)
	)
));


/* Image Carousel
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Image Carousel', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_images_carousel',
	'icon' => 'icon-wpb-images-carousel',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Animated carousel with images', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library. Your image should be at least 440x345', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open lightBox', MAD_BASE_TEXTDOMAIN ) => 'link_image',
				__( 'Do nothing', MAD_BASE_TEXTDOMAIN ) => 'link_no',
				__( 'Open custom link', MAD_BASE_TEXTDOMAIN ) => 'custom_link'
			),
			'description' => __( 'What to do when slide is clicked?', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter) . ', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select where to open  custom links.', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $mad_target_arr
		),
		array(
			'type' => 'number',
			'heading' => __( 'Slide speed', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'speed',
			'value' => 1000,
			'description' => __( 'Duration of animation between slides (in ms)', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Autoplay', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplay',
			'description' => __( 'Enables autoplay mode.', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'number',
			'heading' => __( 'Autoplay timeout', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'autoplaytimeout',
			'description' => __( 'Autoplay interval timeout', MAD_BASE_TEXTDOMAIN ),
			'value' => 5000,
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array( 'yes' )
			),
		),
		$mad_add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', MAD_BASE_TEXTDOMAIN )
		)
	)
) );

/* Gallery/Slideshow
---------------------------------------------------------- */

vc_map( array(
	'name' => __( 'Image Gallery', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_gallery',
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Responsive image gallery', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Gallery type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'type',
			'value' => array(
				__( 'Image grid', MAD_BASE_TEXTDOMAIN ) => 'image_grid',
				__( 'Masonry', MAD_BASE_TEXTDOMAIN ) => 'masonry_grid'
			),
			'description' => __( 'Select gallery type.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size in pixels: 200*100 (Width * Height). Leave empty to use full size. Divide image size with (^).', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Gallery Columns', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'columns',
			'value' => array(
				2 => 2,
				3 => 3,
				4 => 4,
			),
			'dependency' => array(
				'element' => 'type',
				'value' => array('image_grid')
			),
			'description' => __( 'Choose the column count of your Gallery', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display show image title?', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'image_title',
			'description' => __( '', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => 'yes' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open Lightbox', MAD_BASE_TEXTDOMAIN ) => 'link_image',
				__( 'Do nothing', MAD_BASE_TEXTDOMAIN ) => 'link_no',
				__( 'Open custom link', MAD_BASE_TEXTDOMAIN ) => 'custom_link'
			),
			'description' => __( 'Define action for onclick event if needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'custom_links',
			'description' => __('Enter links for each slide here. Divide links with linebreaks (|) .', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select where to open  custom links.', MAD_BASE_TEXTDOMAIN ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $mad_target_arr
		),
	)
));

/* Google maps element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Google Maps', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_gmaps',
	'icon' => 'icon-wpb-map-pin',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Map block', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textarea_safe',
			'heading' => __( 'Map embed iframe', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'link',
			'description' => sprintf( __( 'Visit %s to create your map. 1) Find location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.', MAD_BASE_TEXTDOMAIN ), '<a href="https://mapsengine.google.com/" target="_blank">Google maps</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Map align', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'align',
			'value' => array(
				__( 'None', MAD_BASE_TEXTDOMAIN ) => '',
				__( 'Left', MAD_BASE_TEXTDOMAIN ) => 'alignleft',
				__( 'Right', MAD_BASE_TEXTDOMAIN ) => 'alignright'
			),
			'description' => __( 'Choose the alignment of your map here', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map width', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'width',
			'admin_label' => true,
			'description' => __( 'Enter map width in pixels. Example: 200 or leave it empty to make map responsive.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map height', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'height',
			'admin_label' => true,
			'description' => __( 'Enter map height in pixels. Example: 200 or leave it empty to make map responsive.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Map type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'type',
			'value' => array( __( 'Map', MAD_BASE_TEXTDOMAIN ) => 'm', __( 'Satellite', MAD_BASE_TEXTDOMAIN ) => 'k', __( 'Map + Terrain', MAD_BASE_TEXTDOMAIN ) => "p" ),
			'description' => __( 'Select map type.', MAD_BASE_TEXTDOMAIN )
  		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Map Zoom', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'zoom',
			'value' => array( __( '14 - Default', MAD_BASE_TEXTDOMAIN ) => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20)
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Remove info bubble', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'bubble',
			'description' => __( 'If selected, information bubble will be hidden.', MAD_BASE_TEXTDOMAIN ),
			'value' => array( __( 'Yes, please', MAD_BASE_TEXTDOMAIN ) => true),
		)
	)
) );



/* Dropcap
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Dropcap', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_dropcap',
	'icon' => 'icon-wpb-mad-dropcap',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Dropcap', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'type',
			'value' => array(
				__('Type 1', MAD_BASE_TEXTDOMAIN) => 'type_1',
				__('Type 2', MAD_BASE_TEXTDOMAIN) => 'type_2'
			),
			'description' => __('Choose the first letter style.', MAD_BASE_TEXTDOMAIN)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Letter', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'letter',
			'admin_label' => true,
			'description' => __( '', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'content',
			'value' => __( '<p>Click edit button to change this text.</p>', MAD_BASE_TEXTDOMAIN )
		),
	)
));

/* Graph
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Progress Bar', MAD_BASE_TEXTDOMAIN ),
	'base' => 'vc_mad_progress_bar',
	'icon' => 'icon-wpb-mad-progress-bar',
	'category' => __( 'Content', MAD_BASE_TEXTDOMAIN ),
	'description' => __( 'Animated progress bar', MAD_BASE_TEXTDOMAIN ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as title. Leave blank if no title is needed.', MAD_BASE_TEXTDOMAIN )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Graphic values', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'values',
			'description' => __( 'Input graph values, titles and color here. Divide values with linebreaks (Enter). Example: 90|Development|#e75956', MAD_BASE_TEXTDOMAIN ),
			'value' => "90|Development,80|Design,70|Marketing"
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Units', MAD_BASE_TEXTDOMAIN ),
			'param_name' => 'units',
			'description' => __( 'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', MAD_BASE_TEXTDOMAIN )
		)
	)
) );





/*** Visual Composer Content elements refresh ***/
class MadVcSharedLibrary {
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
		'Text Only' => 'text_only'
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
		'30%' => '30'
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
		'3D Shadow' => 'vc_box_shadow_3d',
		'Circle' => 'vc_box_circle', //new
		'Circle Border' => 'vc_box_border_circle', //new
		'Circle Outline' => 'vc_box_outline_circle', //new
		'Circle Shadow' => 'vc_box_shadow_circle', //new
		'Circle Border Shadow' => 'vc_box_shadow_border_circle' //new
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
	 * @return array
	 */
	public static function getBoxStyles() {
		return self::$box_styles;
	}

	public static function getColorsDashed() {
		$colors = array(
			__( 'Blue', MAD_BASE_TEXTDOMAIN ) => 'blue',
			__( 'Turquoise', MAD_BASE_TEXTDOMAIN ) => 'turquoise',
			__( 'Pink', MAD_BASE_TEXTDOMAIN ) => 'pink',
			__( 'Violet', MAD_BASE_TEXTDOMAIN ) => 'violet',
			__( 'Peacoc', MAD_BASE_TEXTDOMAIN ) => 'peacoc',
			__( 'Chino', MAD_BASE_TEXTDOMAIN ) => 'chino',
			__( 'Mulled Wine', MAD_BASE_TEXTDOMAIN ) => 'mulled-wine',
			__( 'Vista Blue', MAD_BASE_TEXTDOMAIN ) => 'vista-blue',
			__( 'Black', MAD_BASE_TEXTDOMAIN ) => 'black',
			__( 'Grey', MAD_BASE_TEXTDOMAIN ) => 'grey',
			__( 'Orange', MAD_BASE_TEXTDOMAIN ) => 'orange',
			__( 'Sky', MAD_BASE_TEXTDOMAIN ) => 'sky',
			__( 'Green', MAD_BASE_TEXTDOMAIN ) => 'green',
			__( 'Juicy pink', MAD_BASE_TEXTDOMAIN ) => 'juicy-pink',
			__( 'Sandy brown', MAD_BASE_TEXTDOMAIN ) => 'sandy-brown',
			__( 'Purple', MAD_BASE_TEXTDOMAIN ) => 'purple',
			__( 'White', MAD_BASE_TEXTDOMAIN ) => 'white'
		);

		return $colors;
	}
}

//VcSharedLibrary::getColors();
/**
 * @param string $asset
 *
 * @return array
 */
function madGetVcShared( $asset = '' ) {
	switch ( $asset ) {
		case 'colors':
			return MadVcSharedLibrary::getColors();
			break;

		case 'colors-dashed':
			return MadVcSharedLibrary::getColorsDashed();
			break;

		case 'icons':
			return MadVcSharedLibrary::getIcons();
			break;

		case 'sizes':
			return MadVcSharedLibrary::getSizes();
			break;

		case 'button styles':
		case 'alert styles':
			return MadVcSharedLibrary::getButtonStyles();
			break;
		case 'message_box_styles':
			return MadVcSharedLibrary::getMessageBoxStyles();
			break;
		case 'cta styles':
			return MadVcSharedLibrary::getCtaStyles();
			break;

		case 'text align':
			return MadVcSharedLibrary::getTextAlign();
			break;

		case 'cta widths':
		case 'separator widths':
			return MadVcSharedLibrary::getElementWidths();
			break;

		case 'separator styles':
			return MadVcSharedLibrary::getSeparatorStyles();
			break;

		case 'separator border widths':
			return MadVcSharedLibrary::getBorderWidths();
			break;

		case 'single image styles':
			return MadVcSharedLibrary::getBoxStyles();
			break;

		case 'toggle styles':
			return MadVcSharedLibrary::getToggleStyles();
			break;

		default:
			# code...
			break;
	}

	return '';
}