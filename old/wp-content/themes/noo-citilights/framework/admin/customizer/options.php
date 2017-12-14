<?php
/**
 * NOO Customizer Package.
 *
 * Register Options
 * This file register options used in NOO-Customizer
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================


// 0. Remove Unused WP Customizer Sections
if ( ! function_exists( 'noo_customizer_remove_wp_native_sections' ) ) :
	function noo_customizer_remove_wp_native_sections( $wp_customize ) {
		$wp_customize->remove_section( 'title_tagline' );
		// $wp_customize->remove_section( 'colors' );
		// $wp_customize->remove_section( 'background_image' );
		$wp_customize->remove_section( 'nav' );
		$wp_customize->remove_section( 'static_front_page' );
	}

add_action( 'customize_register', 'noo_customizer_remove_wp_native_sections' );
endif;


//
// Register NOO Customizer Sections and Options
//

// 1. Site Enhancement options.
if ( ! function_exists( 'noo_customizer_register_options_general' ) ) :
	function noo_customizer_register_options_general( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Site Enhancement
		$helper->add_section(
			'noo_customizer_section_site_enhancement',
			__( 'Site Enhancement', 'noo' ),
			__( 'Enable/Disable some features for your site.', 'noo' )
		);

		// Control: Favicon
		$helper->add_control(
			'noo_custom_favicon',
			'noo_image',
			__( 'Custom Favicon', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// // Control: Breadcrumbs
		// $helper->add_control(
		// 	'noo_breadcrumbs',
		// 	'noo_switch',
		// 	__( 'Enable Breadcrumbs', 'noo' ),
		// 	1,
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Back to Top
		// $helper->add_control(
		// 	'noo_back_to_top',
		// 	'noo_switch',
		// 	__( 'Back To Top Button', 'noo' ),
		// 	1,
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Control: Smooth Scrolling
		$helper->add_control(
			'noo_smooth_scrolling',
			'noo_switch',
			__( 'Smooth Scrolling', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);
	}
add_action( 'customize_register', 'noo_customizer_register_options_general' );
endif;

// 2. Design and Layout options.
if ( ! function_exists( 'noo_customizer_register_options_layout' ) ) :
	function noo_customizer_register_options_layout( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Layout & Skin
		$helper->add_section(
			'noo_customizer_section_layout',
			__( 'Design and Layout', 'noo' ),
			__( 'Set Style and Layout for your site. Boxed Layout will come with additional setting options for background color and image.', 'noo' )
		);

		// Control: Site Skin
		$helper->add_control(
			'noo_site_skin',
			'noo_radio',
			__( 'Site Design', 'noo' ),
			'light',
			array(
				'choices' => array( 'light' => __( 'Light', 'noo' ),
				'dark'    => __( 'Dark', 'noo' ) )
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Site Layout
		$helper->add_control(
			'noo_site_layout',
			'noo_radio',
			__( 'Site Layout', 'noo' ),
			'fullwidth',
			array(
				'choices' => array( 'fullwidth' => __( 'Fullwidth', 'noo' ), 'boxed' => __( 'Boxed', 'noo' ) ),
				'json'  => array(
					'child_options' => array(
						'boxed' => 'noo_layout_site_width
									,noo_layout_site_max_width
									,noo_layout_bg_color
                                    ,noo_layout_bg_image_sub_section
                                    ,noo_layout_bg_image
                                    ,noo_layout_bg_repeat
                                    ,noo_layout_bg_align
                                    ,noo_layout_bg_attachment
                                    ,noo_layout_bg_cover'
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Site Width (%)
		$helper->add_control(
			'noo_layout_site_width',
			'ui_slider',
			__( 'Site Width (%)', 'noo' ),
			'90',
			array(
				'json' => array(
					'data_min' => '60',
					'data_max' => '100',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Site Max Width (px)
		$helper->add_control(
			'noo_layout_site_max_width',
			'ui_slider',
			__( 'Site Max Width (px)', 'noo' ),
			'1200',
			array(
				'json' => array(
					'data_min'  => '980',
					'data_max'  => '1600',
					'data_step' => '10',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Background Color
		$helper->add_control(
			'noo_layout_bg_color',
			'color_control',
			__( 'Background Color', 'noo' ),
			'#ffffff',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-section: Background Image
		$helper->add_sub_section(
			'noo_layout_bg_image_sub_section',
			__( 'Background Image', 'noo' ),
			__( 'Upload your background image here, you have various settings for your image:<br/><strong>Repeat Image</strong>: enable repeating your image, you will need it when using patterned background.<br/><strong>Alignment</strong>: Set the position to align your background image.<br/><strong>Attachment</strong>: Make your image scroll with your site or fixed.<br/><strong>Auto resize</strong>: Enable it to ensure your background image always fit the windows.', 'noo' )
		);

		// Control: Background Image
		$helper->add_control(
			'noo_layout_bg_image',
			'noo_image',
			__( 'Background Image', 'noo' ),
			null,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Repeat Image
		$helper->add_control(
			'noo_layout_bg_repeat',
			'radio',
			__( 'Background Repeat', 'noo' ),
			'no-repeat',
			array(
				'choices' => array(
					'repeat' => __( 'Repeat', 'noo' ),
					'no-repeat' => __( 'No Repeat', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Align Image
		$helper->add_control(
			'noo_layout_bg_align',
			'select',
			__( 'BG Image Alignment', 'noo' ),
			'left top',
			array(
				'choices' => array(
					'left top'       => __( 'Left Top', 'noo' ),
					'left center'     => __( 'Left Center', 'noo' ),
					'left bottom'     => __( 'Left Bottom', 'noo' ),
					'center top'     => __( 'Center Top', 'noo' ),
					'center center'     => __( 'Center Center', 'noo' ),
					'center bottom'     => __( 'Center Bottom', 'noo' ),
					'right top'     => __( 'Right Top', 'noo' ),
					'right center'     => __( 'Right Center', 'noo' ),
					'right bottom'     => __( 'Right Bottom', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Enable Scrolling Image
		$helper->add_control(
			'noo_layout_bg_attachment',
			'radio',
			__( 'BG Image Attachment', 'noo' ),
			'fixed',
			array(
				'choices' => array(
					'fixed' => __( 'Fixed Image', 'noo' ),
					'scroll' => __( 'Scroll with Site', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Auto Resize
		$helper->add_control(
			'noo_layout_bg_cover',
			'noo_switch',
			__( 'Auto Resize', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Links Color
		$helper->add_sub_section(
			'noo_general_sub_section_links_color',
			__( 'Color', 'noo' ),
			__( 'Here you can set the color for links and various elements on your site.', 'noo' )
		);

		// Control: Site Links Color
		$helper->add_control(
			'noo_site_link_color',
			'color_control',
			__( 'Links Color', 'noo' ),
			noo_default_primary_color(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Site Links Hover Color
		$helper->add_control(
			'noo_site_link_hover_color',
			'color_control',
			__( 'Links Hover Color', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Secondary Color
		$helper->add_control(
			'noo_site_secondary_color',
			'color_control',
			__( 'Secondary Color', 'noo' ),
			'#f0e797',
			array(),
			array( 'transport' => 'postMessage' )
		);
	}
add_action( 'customize_register', 'noo_customizer_register_options_layout' );
endif;

// 3. Typography options.
if ( ! function_exists( 'noo_customizer_register_options_typo' ) ) :
	function noo_customizer_register_options_typo( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Typography
		$helper->add_section(
			'noo_customizer_section_typo',
			__( 'Typography', 'noo' ),
			__( 'Customize your Typography settings. Merito integrated all Google Fonts. See font preview at <a target="_blank" href="http://www.google.com/fonts/">Google Fonts</a>.', 'noo' )
		);

		// Control: Use Custom Fonts
		$helper->add_control(
			'noo_typo_use_custom_fonts',
			'noo_switch',
			__( 'Use Custom Fonts?', 'noo' ),
			0,
			array( 'json' => array( 
				'on_child_options'  => 'noo_typo_headings_font,noo_typo_body_font' 
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Use Custom Font Color
		$helper->add_control(
			'noo_typo_use_custom_fonts_color',
			'noo_switch',
			__( 'Custom Font Color?', 'noo' ),
			0,
			array( 'json' => array(
				'on_child_options'  => 'noo_typo_headings_font_color,noo_typo_body_font_color'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Headings
		$helper->add_sub_section(
			'noo_typo_sub_section_headings',
			__( 'Headings', 'noo' )
		);

		// Control: Headings font
		$helper->add_control(
			'noo_typo_headings_font',
			'google_fonts',
			__( 'Headings Font', 'noo' ),
			noo_default_headings_font_family(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Headings Font Color
		$helper->add_control(
			'noo_typo_headings_font_color',
			'color_control',
			__( 'Font Color', 'noo' ),
			noo_default_headings_color(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Headings Font Uppercase
		$helper->add_control(
			'noo_typo_headings_uppercase',
			'checkbox',
			__( 'Transform to Uppercase', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Body
		$helper->add_sub_section(
			'noo_typo_sub_section_body',
			__( 'Body', 'noo' )
		);

		// Control: Body font
		$helper->add_control(
			'noo_typo_body_font',
			'google_fonts',
			__( 'Body Font', 'noo' ),
			noo_default_font_family(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Body Font Size
		$helper->add_control(
			'noo_typo_body_font_size',
			'font_size',
			__( 'Font Size (px)', 'noo' ),
			noo_default_font_size(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Body Font Color
		$helper->add_control(
			'noo_typo_body_font_color',
			'color_control',
			__( 'Font Color', 'noo' ),
			noo_default_text_color(),
			array(),
			array( 'transport' => 'postMessage' )
		);
	}
add_action( 'customize_register', 'noo_customizer_register_options_typo' );
endif;

// // 4. Color options.
// if ( ! function_exists( 'noo_customizer_register_options_color' ) ) :
// 	function noo_customizer_register_options_color( $wp_customize ) {

// 		// declare helper object.
// 		$helper = new NOO_Customizer_Helper( $wp_customize );

// 		// Section: Color
// 		$helper->add_section(
// 			'noo_customizer_section_color',
// 			__( 'Color', 'noo' )
// 		);
// 	}
// add_action( 'customize_register', 'noo_customizer_register_options_color' );
// endif;

// // 5. Buttons options.
// if ( ! function_exists( 'noo_customizer_register_options_buttons' ) ) :
// 	function noo_customizer_register_options_buttons( $wp_customize ) {

// 		// declare helper object.
// 		$helper = new NOO_Customizer_Helper( $wp_customize );

// 		// Section: Buttons
// 		$helper->add_section(
// 			'noo_customizer_section_buttons',
// 			__( 'Buttons', 'noo' )
// 		);
// 	}
// add_action( 'customize_register', 'noo_customizer_register_options_buttons' );
// endif;

// 6. Header options.
if ( ! function_exists( 'noo_customizer_register_options_header' ) ) :
	function noo_customizer_register_options_header( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Header
		$helper->add_section(
			'noo_customizer_section_header',
			__( 'Header', 'noo' ),
			__( 'Customize settings for your Header, including Navigation Bar (Logo and Navigation) and an optional Top Bar.', 'noo' ),
			true
		);

		// Sub-section: General Options
		$helper->add_sub_section(
			'noo_header_sub_section_general',
			__( 'General Options', 'noo' ),
			''
		);

		// // Control: Header Layout
		// $helper->add_control(
		// 	'noo_header_layout',
		// 	'noo_radio',
		// 	__( 'Header Layout', 'noo' ),
		// 	'inline', 
		// 	array(
		// 		'choices' => array(
		// 			'inline'       => __( 'Inline Logo and NavBar', 'noo' ),
		// 			'stack'        => __( 'Stack Logo and NavBar', 'noo' ),
		// 		)
		// 	)
		// );

		// // Control: Header Background Color
		// $helper->add_control(
		// 	'noo_header_bg_color',
		// 	'alpha_color',
		// 	__( 'Header Background Color', 'noo'),
		// 	'',
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Sub-Section: Navigation Bar
		$helper->add_sub_section(
			'noo_header_sub_section_nav',
			__( 'Navigation Bar', 'noo' ),
			__( 'Adjust settings for Navigation Bar. You also can customize some settings for the Toggle Button on Mobile in this section.', 'noo' )
		);

		// Control: NavBar Position
		$helper->add_control(
			'noo_header_nav_position',
			'noo_radio',
			__( 'NavBar Position', 'noo' ),
			'fixed_top', 
			array(
				'choices' => array(
					'static_top'       => __( 'Static Top', 'noo' ),
					'fixed_top'     => __( 'Fixed Top', 'noo' ),
					// 'fixed_left'     => __( 'Fixed Left', 'noo' ),
					// 'fixed_right'     => __( 'Fixed Right', 'noo' ),
				),
				'json' => array(
					'child_options' => array(
						'fixed_top'   => 'noo_header_nav_shrinkable,noo_header_nav_smart_scroll',
					//	'fixed_left'  => 'noo_header_side_nav_width,noo_header_side_nav_alignment,noo_header_side_logo_margin_top,noo_header_side_nav_link_height',
					//	'fixed_right' => 'noo_header_side_nav_width,noo_header_side_nav_alignment,noo_header_side_logo_margin_top,noo_header_side_nav_link_height',
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Shrink NavBar when Scroll
		$helper->add_control(
			'noo_header_nav_shrinkable',
			'checkbox',
			__( 'Shrink NavBar when Scroll', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Smart Scroll
		$helper->add_control(
			'noo_header_nav_smart_scroll',
			'checkbox',
			__( 'Only Display when Scroll Up', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 1
		$helper->add_control( 'noo_header_nav_divider_1', 'divider', '' );

		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			// Control: Show Cart Icon
			$helper->add_control(
				'noo_header_nav_icon_cart_woo',
				'noo_switch',
				__( 'Show Shopping Cart', 'noo' ),
				1,
				array(),
				array( 'transport' => 'postMessage' )
			);
		}

		// Control: Show Search Icon
		$helper->add_control(
			'noo_header_nav_icon_search',
			'noo_switch',
			__( 'Show Search Icon', 'noo' ),
			0,
			array(
					'json' => array(
						'on_child_options'  => 'noo_header_nav_search_property'
					)
				),
			array( 'transport' => 'postMessage' )
		);

		// Control: Only Search Properties
		$helper->add_control(
			'noo_header_nav_search_property',
			'checkbox',
			__( 'Only Search Properties', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Phone Number
		$helper->add_control(
			'noo_header_nav_phone_number',
			'text',
			__( 'Phone Number', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 2
		$helper->add_control( 'noo_header_nav_divider_2', 'divider', '' );

		// Control: Custom NavBar Font
		$helper->add_control(
			'noo_header_custom_nav_font',
			'noo_switch',
			__( 'Use Custom NavBar Font and Color?', 'noo' ),
			0,
			array( 'json' => array( 
				'on_child_options'  => 'noo_header_nav_font,noo_header_nav_link_color,noo_header_nav_link_hover_color' 
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar font
		$helper->add_control(
			'noo_header_nav_font',
			'google_fonts',
			__( 'NavBar Font', 'noo' ),
			noo_default_font_family(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar Font Size
		$helper->add_control(
			'noo_header_nav_font_size',
			'ui_slider',
			__( 'Font Size (px)', 'noo' ),
			'14',
			array(
				'json' => array(
					'data_min' => '9',
					'data_max' => '30',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar Link Color
		$helper->add_control(
			'noo_header_nav_link_color',
			'color_control',
			__( 'Link Color', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar Link Hover Color
		$helper->add_control(
			'noo_header_nav_link_hover_color',
			'color_control',
			__( 'Link Hover Color', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar Font Uppercase
		$helper->add_control(
			'noo_header_nav_uppercase',
			'checkbox',
			__( 'Transform to Uppercase', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 3
		$helper->add_control( 'noo_header_nav_divider_3', 'divider', '' );

		// Control: Custom Dropdown Style
		$helper->add_control(
			'noo_header_nav_dropdown_custom',
			'noo_switch',
			__( 'Custom Dropdown Style', 'noo' ),
			0,
			array(
				'json' => array(
					'on_child_options'  => 'noo_header_nav_dropdown_bg_color,noo_header_nav_dropdown_link_color,noo_header_nav_dropdown_link_hover_color,noo_header_nav_dropdown_font_size'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Dropdown Background Color
		$helper->add_control(
			'noo_header_nav_dropdown_bg_color',
			'alpha_color',
			__( 'Dropdown Background Color', 'noo'),
			'#ffffff',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Dropdown Font Size
		$helper->add_control(
			'noo_header_nav_dropdown_font_size',
			'ui_slider',
			__( 'Dropdown Font Size (px)', 'noo' ),
			'14',
			array(
				'json' => array(
					'data_min' => '9',
					'data_max' => '30',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Dropdown Link Color
		$helper->add_control(
			'noo_header_nav_dropdown_link_color',
			'color_control',
			__( 'Dropdown Link Color', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Dropdown Link Hover Color
		$helper->add_control(
			'noo_header_nav_dropdown_link_hover_color',
			'color_control',
			__( 'Dropdown Link Hover Color', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Logo
		$helper->add_sub_section(
			'noo_header_sub_section_logo',
			__( 'Logo', 'noo' ),
			__( 'All the settings for Logo go here. If you do not use Image for Logo, plain text will be used.', 'noo' )
		);

		// Control: Use Image for Logo
		$helper->add_control(
			'noo_header_use_image_logo',
			'noo_switch',
			__( 'Use Image for Logo?', 'noo' ),
			0,
			array(
				'json' => array(
					'on_child_options'   => 'noo_header_logo_image
                                        ,noo_header_logo_retina_image
                                        ,noo_header_logo_image_height',
					'off_child_options'  => 'blogname
										,noo_header_logo_font
                                        ,noo_header_logo_font_size
                                        ,noo_header_logo_font_color
                                        ,noo_header_logo_uppercase'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Blog Name
		$helper->add_control(
			'blogname',
			'text',
			__( 'Blog Name', 'noo' ),
			get_bloginfo( 'name' ),
			array(),
			array( 'transport' => 'postMessage', 'type' => 'option' )
		);

		// Control: Logo font
		$helper->add_control(
			'noo_header_logo_font',
			'google_fonts',
			__( 'Logo Font', 'noo' ),
			noo_default_logo_font_family(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Font Size
		$helper->add_control(
			'noo_header_logo_font_size',
			'ui_slider',
			__( 'Font Size (px)', 'noo' ),
			'30',
			array(
				'json' => array(
					'data_min' => '15',
					'data_max' => '80',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Font Color
		$helper->add_control(
			'noo_header_logo_font_color',
			'color_control',
			__( 'Font Color', 'noo' ),
			noo_default_logo_color(),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Font Uppercase
		$helper->add_control(
			'noo_header_logo_uppercase',
			'checkbox',
			__( 'Transform to Uppercase', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Image
		$helper->add_control(
			'noo_header_logo_image',
			'noo_image',
			__( 'Upload Your Logo', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Retina Image
		$helper->add_control(
			'noo_header_logo_retina_image',
			'noo_image',
			__( 'Retina Logo', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Logo Image Height
		$helper->add_control(
			'noo_header_logo_image_height',
			'ui_slider',
			__( 'Image Height (px)', 'noo' ),
			'30',
			array(
				'json' => array(
					'data_min' => '20',
					'data_max' => '120',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: NavBar Alignment
		$helper->add_sub_section(
			'noo_header_sub_section_alignment',
			__( 'NavBar Alignment', 'noo' ),
			''
		);

		// // Control: Side Nav - Width (px)
		// $helper->add_control(
		// 	'noo_header_side_nav_width',
		// 	'ui_slider',
		// 	__( 'Side Nav - Width', 'noo' ),
		// 	'240',
		// 	array(
		// 		'json' => array(
		// 			'data_min'  => '100',
		// 			'data_max'  => '500',
		// 			'data_step' => '5',
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Side Nav - Link Alignment
		// $helper->add_control(
		// 	'noo_header_side_nav_alignment',
		// 	'radio',
		// 	__( 'Side Nav - Link Alignment', 'noo'),
		// 	'center',
		// 	array(
		// 		'choices' => array(
		// 			'center'   => __( 'Center', 'noo' ),
		// 			'left'	=> __( 'Left', 'noo' ),
		// 			'right'   => __( 'Right', 'noo' ),
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Side Nav - Link Spacing (px)
		// $helper->add_control(
		// 	'noo_header_side_nav_link_height',
		// 	'ui_slider',
		// 	__( 'Side Nav - Link Spacing (px)', 'noo' ),
		// 	'40',
		// 	array(
		// 		'json' => array(
		// 			'data_min' => '0',
		// 			'data_max' => '100',
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Side Nav - Logo Top Alignment (px)
		// $helper->add_control(
		// 	'noo_header_side_logo_margin_top',
		// 	'ui_slider',
		// 	__( 'Side Nav - Logo Top Alignment (px)', 'noo' ),
		// 	'30',
		// 	array(
		// 		'json' => array(
		// 			'data_min' => '0',
		// 			'data_max' => '100',
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Control: NavBar Height (px)
		$helper->add_control(
			'noo_header_nav_height',
			'ui_slider',
			__( 'NavBar Height (px)', 'noo' ),
			'70',
			array(
				'json' => array(
					'data_min' => '20',
					'data_max' => '150',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: NavBar Link Spacing (px)
		$helper->add_control(
			'noo_header_nav_link_spacing',
			'ui_slider',
			__( 'NavBar Link Spacing (px)', 'noo' ),
			'12',
			array(
				'json' => array(
					'data_min' => '5',
					'data_max' => '30',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Mobile Icon Size (px)
		$helper->add_control(
			'noo_header_nav_toggle_size',
			'ui_slider',
			__( 'Mobile Icon Size (px)', 'noo' ),
			'25',
			array(
				'json' => array(
					'data_min' => '15',
					'data_max' => '100',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Top Bar
		$helper->add_sub_section(
			'noo_header_sub_section_top_bar',
			__( 'Top Bar', 'noo' ),
			__( 'Top Bar lays on top of your site, above Navigation Bar. It is suitable for placing contact information and social media link. Enable to control its layout and content.', 'noo' )
		);

		// Control: Header TopBar
		$helper->add_control(
			'noo_header_top_bar',
			'noo_switch',
			__( 'Enable Top Bar', 'noo' ),
			0,
			array(
				'json' => array(
					'on_child_options'  => 'noo_top_bar_social,noo_top_bar_content,noo_top_bar_email,noo_top_bar_show_register,noo_top_bar_show_login'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Top Bar Social
		$helper->add_control(
			'noo_top_bar_social',
			'noo_switch',
			__( 'Show Social Icons', 'noo' ),
			'1',
			array(
				'json' => array(
					'on_child_options' => 'noo_top_bar_social_layout',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Top Bar Layout (with Social)
		$helper->add_control(
			'noo_top_bar_social_layout',
			'radio',
			__( 'Social Position', 'noo' ),
			'content_left',
			array(
				'choices' => array(
					'content_right'   => __( 'Social Left', 'noo' ),
					'content_left'   => __( 'Social Right', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Top Bar Email
		$helper->add_control(
			'noo_top_bar_email',
			'text',
			__( 'Email', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Top Bar Register
		$helper->add_control(
			'noo_top_bar_show_register',
			'checkbox',
			__( 'Show Register Link', 'noo' ),
			'1',
			array(
				'json' => array(
					'on_child_options' => 'noo_top_bar_register_page',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Register Page
		$helper->add_control(
			'noo_top_bar_register_page',
			'pages_select',
			__( 'Custom Register Page', 'noo' ),
			'1',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Top Bar Login
		$helper->add_control(
			'noo_top_bar_show_login',
			'checkbox',
			__( 'Show Login Link', 'noo' ),
			'1',
			array(
				'json' => array(
					'on_child_options' => 'noo_top_bar_login_page',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Login Page
		$helper->add_control(
			'noo_top_bar_login_page',
			'pages_select',
			__( 'Custom Login Page', 'noo' ),
			'1',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// // Control: Top Bar Content
		// $helper->add_control(
		// 	'noo_top_bar_content',
		// 	'textarea',
		// 	__( 'Custom Content (HTML)', 'noo' ),
		// 	'',
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );
	}
add_action( 'customize_register', 'noo_customizer_register_options_header' );
endif;

// 7. Footer options.
if ( ! function_exists( 'noo_customizer_register_options_footer' ) ) :
	function noo_customizer_register_options_footer( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Footer
		$helper->add_section(
			'noo_customizer_section_footer',
			__( 'Footer', 'noo' )
		);

		// Control: Footer Columns (Widgetized)
		$helper->add_control(
			'noo_footer_widgets',
			'select',
			__( 'Footer Columns (Widgetized)', 'noo' ),
			'4',
			array(
				'choices' => array(
					0       => __( 'None (No Footer Main Content)', 'noo' ),
					1     => __( 'One', 'noo' ),
					2     => __( 'Two', 'noo' ),
					3     => __( 'Three', 'noo' ),
					4     => __( 'Four', 'noo' )
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Footer Background
		$helper->add_control(
			'noo_footer_background',
			'noo_image',
			__( 'Footer Background Image', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// // Control: Show Footer Menu
		// $helper->add_control(
		// 	'noo_bottom_bar_menu',
		// 	'checkbox',
		// 	__( 'Show Footer Menu', 'noo' ),
		// 	0,
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Show Footer Social Icons
		// $helper->add_control(
		// 	'noo_bottom_bar_social',
		// 	'checkbox',
		// 	__( 'Show Footer Social Icons', 'noo' ),
		// 	1,
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Control: Footer Image
		$helper->add_control(
			'noo_bottom_bar_logo_image',
			'noo_image',
			__( 'Upload Footer Logo', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Bottom Bar Content
		$helper->add_control(
			'noo_bottom_bar_content',
			'textarea',
			__( 'Bottom Bar Content (HTML)', 'noo' ),
			'2014 Nootheme. All Rights Reserved. Powered by Wordpress',
			array(),
			array( 'transport' => 'postMessage' )
		);

	}
add_action( 'customize_register', 'noo_customizer_register_options_footer' );
endif;

// 8. WP Sidebar options.
if ( ! function_exists( 'noo_customizer_register_options_sidebar' ) ) :
	function noo_customizer_register_options_sidebar( $wp_customize ) {

		global $wp_version;
		if ( $wp_version >= 4.0 ) {
			// declare helper object.
			$helper = new NOO_Customizer_Helper( $wp_customize );

			// Change the sidebar panel priority
			$widget_panel = $wp_customize->get_panel('widgets');
			if(!empty($widget_panel)) {
				$widget_panel->priority = $helper->get_new_section_priority();
			}
		}
	}
add_action( 'customize_register', 'noo_customizer_register_options_sidebar' );
endif;

// 9. Blog options.
if ( ! function_exists( 'noo_customizer_register_options_blog' ) ) :
	function noo_customizer_register_options_blog( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Blog
		$helper->add_section(
			'noo_customizer_section_blog',
			__( 'Blog', 'noo' ),
			__( 'In this section you have settings for your Blog page, Archive page and Single Post page.', 'noo' ),
			true
		);

		// Sub-section: Blog Page (Index Page)
		$helper->add_sub_section(
			'noo_blog_sub_section_blog_page',
			__( 'Blog Page', 'noo' ),
			__( 'Choose Layout and Headline settings for your Blog Page', 'noo' )
		);

		// Control: Blog Layout
		$helper->add_control(
			'noo_blog_layout',
			'noo_radio',
			__( 'Blog Layout', 'noo' ),
			'sidebar',
			array(
				'choices' => array(
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' )
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_blog_sidebar',
						'left_sidebar'   => 'noo_blog_sidebar'
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Blog Sidebar
		$helper->add_control(
			'noo_blog_sidebar',
			'widgets_select',
			__( 'Blog Sidebar', 'noo' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// // Control: Blog Style
		// $helper->add_control(
		// 	'noo_blog_style',
		// 	'noo_radio',
		// 	__( 'Blog Style', 'noo' ),
		// 	'standard',
		// 	array(
		// 		'choices' => array(
		// 			'standard'   => __( 'Standard Blog', 'noo' ),
		// 			'masonry'   => __( 'Masonry Blog', 'noo' ),
		// 		),
		// 		'json'	=> array(
		// 			'child_options'	=> array(
		// 				'masonry'	=> 'noo_blog_masonry_columns'
		// 			)
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Masonry Columns
		// $helper->add_control(
		// 	'noo_blog_masonry_columns',
		// 	'select',
		// 	__( 'Masonry Columns', 'noo' ),
		// 	'2',
		// 	array(
		// 		'choices' => array(
		// 			2     => __( 'Two', 'noo' ),
		// 			3     => __( 'Three', 'noo' ),
		// 			4     => __( 'Four', 'noo' )
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Show Blog Headline
		// $helper->add_control(
		// 	'noo_blog_heading',
		// 	'noo_switch',
		// 	__( 'Show Blog Headline', 'noo' ),
		// 	0,
		// 	array(
		// 		'json' => array(
		// 			'on_child_options' => 'noo_blog_heading_image,noo_blog_heading_title,noo_blog_heading_sub_title'
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Headline Background Image
		// $helper->add_control(
		// 	'noo_blog_heading_image',
		// 	'noo_image',
		// 	__( 'Headline Background Image', 'noo' ),
		// 	'',
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Headline Title
		// $helper->add_control(
		// 	'noo_blog_heading_title',
		// 	'text',
		// 	__( 'Headline Title', 'noo' ),
		// 	__( 'My Blog', 'noo' ),
		// 	array(),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Headline Sub-Title
		// $helper->add_control(
		// 	'noo_blog_heading_sub_title',
		// 	'textarea',
		// 	__( 'Headline Sub-Title', 'noo' ),
		// 	'',
		// 	array('json' => array('size' => 'small')),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Control: Divider 1
		$helper->add_control( 'noo_blog_divider_1', 'divider', '' );

		// Control: Show Post Meta
		$helper->add_control(
			'noo_blog_show_post_meta',
			'checkbox',
			__( 'Show Post Meta', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Post Tag
		$helper->add_control(
			'noo_blog_show_post_tag',
			'checkbox',
			__( 'Show Post Tags', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Readmore link
		$helper->add_control(
			'noo_blog_show_readmore',
			'checkbox',
			__( 'Show Readmore link', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Excerpt Length
		$helper->add_control(
			'noo_blog_excerpt_length',
			'text',
			__( 'Excerpt Length', 'noo' ),
			'60',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-section: Archive Page
		$helper->add_sub_section(
			'noo_blog_sub_section_archive',
			__( 'Archive Page', 'noo' )
		);

		// Control: Archive Layout
		$helper->add_control(
			'noo_blog_archive_layout',
			'noo_same_as_radio',
			__( 'Archive Layout', 'noo' ),
			'same_as_blog',
			array(
				'choices' => array(
					'same_as_blog'	=> __( 'Same as Blog Layout', 'noo' ),
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' ),
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_blog_archive_sidebar',
						'left_sidebar'   => 'noo_blog_archive_sidebar',
					)
				),
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Archive Sidebar
		$helper->add_control(
			'noo_blog_archive_sidebar',
			'widgets_select',
			__( 'Archives Sidebar', 'noo' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// // Control: Archive Style
		// $helper->add_control(
		// 	'noo_blog_archive_style',
		// 	'noo_same_as_radio',
		// 	__( 'Archive Style', 'noo' ),
		// 	'same_as_blog',
		// 	array(
		// 		'choices' => array(
		// 			'same_as_blog'   => __( 'Same as Blog Style', 'noo' ),
		// 			'standard'   => __( 'Standard', 'noo' ),
		// 			'masonry'   => __( 'Masonry', 'noo' ),
		// 		),
		// 		'json'	=> array(
		// 			'child_options'	=> array(
		// 				'masonry'	=> 'noo_blog_archive_masonry_columns'
		// 			)
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// // Control: Masonry Columns
		// $helper->add_control(
		// 	'noo_blog_archive_masonry_columns',
		// 	'select',
		// 	__( 'Masonry Columns', 'noo' ),
		// 	'2',
		// 	array(
		// 		'choices' => array(
		// 			2     => __( 'Two', 'noo' ),
		// 			3     => __( 'Three', 'noo' ),
		// 			4     => __( 'Four', 'noo' )
		// 		)
		// 	),
		// 	array( 'transport' => 'postMessage' )
		// );

		// Sub-section: Single Post
		$helper->add_sub_section(
			'noo_blog_sub_section_post',
			__( 'Single Post', 'noo' )
		);

		// Control: Post Layout
		$helper->add_control(
			'noo_blog_post_layout',
			'noo_same_as_radio',
			__( 'Post Layout', 'noo' ),
			'same_as_blog',
			array(
				'choices' => array(
					'same_as_blog'   => __( 'Same as Blog Layout', 'noo' ),
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' ),
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_blog_post_sidebar',
						'left_sidebar'   => 'noo_blog_post_sidebar',
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Post Sidebar
		$helper->add_control(
			'noo_blog_post_sidebar',
			'widgets_select',
			__( 'Post Sidebar', 'noo' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 1
		$helper->add_control( 'noo_blog_post_divider_1', 'divider', '' );

		// Control: Show Post Meta
		$helper->add_control(
			'noo_blog_post_show_post_meta',
			'checkbox',
			__( 'Show Post Meta', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Post Tags
		$helper->add_control(
			'noo_blog_post_show_post_tag',
			'checkbox',
			__( 'Show Post Tags', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Author Bio
		$helper->add_control(
			'noo_blog_post_author_bio',
			'checkbox',
			__( 'Show Author\'s Bio', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 2
		$helper->add_control( 'noo_blog_post_divider_2', 'divider', '' );

		// Control: Enable Social Sharing
		$helper->add_control(
			'noo_blog_social',
			'noo_switch',
			__( 'Enable Social Sharing', 'noo' ),
			1,
			array(
				'json' => array( 'on_child_options' => 'noo_blog_social_title,
														noo_blog_social_facebook,
		                                                noo_blog_social_twitter,
		                                                noo_blog_social_google,
		                                                noo_blog_social_pinterest,
		                                                noo_blog_social_linkedin'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Sharing Title
		$helper->add_control(
			'noo_blog_social_title',
			'text',
			__( 'Sharing Title', 'noo' ),
			__( 'Share This Post', 'noo' ),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Facebook Share
		$helper->add_control(
			'noo_blog_social_facebook',
			'checkbox',
			__( 'Facebook Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Twitter Share
		$helper->add_control(
			'noo_blog_social_twitter',
			'checkbox',
			__( 'Twitter Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Google+ Share
		$helper->add_control(
			'noo_blog_social_google',
			'checkbox',
			__( 'Google+ Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Pinterest Share
		$helper->add_control(
			'noo_blog_social_pinterest',
			'checkbox',
			__( 'Pinterest Share', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: LinkedIn Share
		$helper->add_control(
			'noo_blog_social_linkedin',
			'checkbox',
			__( 'LinkedIn Share', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

	}
add_action( 'customize_register', 'noo_customizer_register_options_blog' );
endif;

// 11. Property options.
if ( ! function_exists( 'noo_customizer_register_options_property' ) ) :
	function noo_customizer_register_options_property( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Property
		$helper->add_section(
			'noo_customizer_section_property',
			__( 'Agent & Property', 'noo' ),
			'',
			true
		);

		// Sub-section: Agent Layout
		$helper->add_sub_section(
			'noo_property_sub_section_agent_layout',
			__( 'Agent Layout', 'noo' ),
			__( 'Setting here will affect all the pages of Agent. Index page, Archive page and Single agent page.', 'noo' )
		);

		// Control: Agent Layout
		$helper->add_control(
			'noo_agent_layout',
			'noo_radio',
			__( 'Agent Layout', 'noo' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' )
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_agent_sidebar',
						'left_sidebar'   => 'noo_agent_sidebar',
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Agent Sidebar
		$helper->add_control(
			'noo_agent_sidebar',
			'widgets_select',
			__( 'Agent Sidebar', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-section: Property Listing
		$helper->add_sub_section(
			'noo_property_sub_section_listing',
			__( 'Property Listing', 'noo' ),
			__( 'This section has setting for Property listing pages (Index or Archive pages)', 'noo' )
		);

		// Control: Listing Layout
		$helper->add_control(
			'noo_property_layout',
			'noo_radio',
			__( 'Listing Page Layout', 'noo' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' )
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_property_sidebar',
						'left_sidebar'   => 'noo_property_sidebar',
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Listing Sidebar
		$helper->add_control(
			'noo_property_sidebar',
			'widgets_select',
			__( 'Listing Page Sidebar', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: List Style
		$helper->add_control(
			'noo_property_listing_layout',
			'noo_radio',
			__( 'Listing Style', 'noo' ),
			'grid',
			array(
				'choices' => array(
					'grid'   => __( 'Grid Style', 'noo' ),
					'list'   => __( 'List Style', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		$helper->add_control(
			'noo_property_listing_orderby_default',
			'select',
			__( 'Sort Properties By', 'noo' ),
			'date',
			array(
				'choices' => array(
					'featured'   => __( 'Featured', 'noo' ),
					'date'   => __( 'Date', 'noo' ),
					'price'   => __( 'Price', 'noo' ),
					'name'   => __( 'Name', 'noo' ),
					'area'   => __( 'Area', 'noo' ),
					'rand'   => __( 'Random', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		$helper->add_control(
			'noo_property_listing_orderby',
			'checkbox',
			__( 'Show Sort Button', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 1
		$helper->add_control( 'noo_property_divider_1', 'divider', '' );

		// Control: Listing Map
		$helper->add_control(
			'noo_property_listing_map',
			'noo_switch',
			__( 'Show Map on Header', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		$helper->add_control(
			'noo_property_listing_search',
			'noo_switch',
			__( 'Show Property Search', 'noo' ),
			1,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_listing_map_layout,noo_property_listing_advanced_search',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Search Layout
		$helper->add_control(
			'noo_property_listing_map_layout',
			'noo_radio',
			__( 'Search Layout', 'noo' ),
			'horizontal',
			array(
				'choices' => array(
					'horizontal'   => __( 'Horizontal Search', 'noo' ),
					'vertical'   => __( 'Vertical Search', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Search Layout
		$helper->add_control(
			'noo_property_listing_advanced_search',
			'checkbox',
			__( 'Enable Features/Amenities Search', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-section: Property Detail
		$helper->add_sub_section(
			'noo_property_sub_section_detail',
			__( 'Property Detail', 'noo' ),
			__( 'This section has setting for Property detail pages (Single pages)', 'noo' )
		);

		// Control: Property Detail Layout
		$helper->add_control(
			'noo_property_detail_layout',
			'noo_same_as_radio',
			__( 'Property Page Layout', 'noo' ),
			'same_as_listing',
			array(
				'choices' => array(
					'same_as_listing'	=> __( 'Same as Listing Layout', 'noo' ),
					'fullwidth'   => __( 'Full-Width', 'noo' ),
					'sidebar'   => __( 'With Right Sidebar', 'noo' ),
					'left_sidebar'   => __( 'With Left Sidebar', 'noo' ),
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_property_detail_sidebar',
						'left_sidebar'   => 'noo_property_detail_sidebar',
					)
				),
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Detail Sidebar
		$helper->add_control(
			'noo_property_detail_sidebar',
			'widgets_select',
			__( 'Property Page Sidebar', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 2
		$helper->add_control( 'noo_property_divider_2', 'divider', '' );

		// Control: Detail Map
		$helper->add_control(
			'noo_property_detail_map',
			'noo_switch',
			__( 'Show Map on Header', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Detail Search
		$helper->add_control(
			'noo_property_detail_search',
			'noo_switch',
			__( 'Show Property Search', 'noo' ),
			0,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_detail_map_layout,noo_property_detail_advanced_search',
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Search Layout
		$helper->add_control(
			'noo_property_detail_map_layout',
			'noo_radio',
			__( 'Search Layout', 'noo' ),
			'horizontal',
			array(
				'choices' => array(
					'horizontal'   => __( 'Horizontal Search', 'noo' ),
					'vertical'   => __( 'Vertical Search', 'noo' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Advanced Search
		$helper->add_control(
			'noo_property_detail_advanced_search',
			'checkbox',
			__( 'Enable Features/Amenities Search', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider 3
		$helper->add_control( 'noo_property_divider_3', 'divider', '' );

		// Control: Number of Similar Properties
		$helper->add_control(
				'noo_property_similar_num',
				'ui_slider',
				__( 'Number of Similar Properties', 'noo' ),
				'2',
				array(
					'json' => array(
						'data_min'  => '0',
						'data_max'  => '8',
						'data_step' => '2'
					)
				),
				array( 'transport' => 'postMessage' )
			);

		// Control: Enable Social Sharing
		$helper->add_control(
			'noo_property_social',
			'noo_switch',
			__( 'Enable Social Sharing', 'noo' ),
			1,
			array(
				'json' => array( 'on_child_options' => 'noo_property_social_facebook,
		                                                noo_property_social_twitter,
		                                                noo_property_social_google,
		                                                noo_property_social_pinterest,
		                                                noo_property_social_linkedin'
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Facebook Share
		$helper->add_control(
			'noo_property_social_facebook',
			'checkbox',
			__( 'Facebook Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Twitter Share
		$helper->add_control(
			'noo_property_social_twitter',
			'checkbox',
			__( 'Twitter Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Google+ Share
		$helper->add_control(
			'noo_property_social_google',
			'checkbox',
			__( 'Google+ Share', 'noo' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Pinterest Share
		$helper->add_control(
			'noo_property_social_pinterest',
			'checkbox',
			__( 'Pinterest Share', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: LinkedIn Share
		$helper->add_control(
			'noo_property_social_linkedin',
			'checkbox',
			__( 'LinkedIn Share', 'noo' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		if( defined('DSIDXPRESS_PLUGIN_VERSION') ) {
			// Sub-section: dsIDXPress Plugin
			$helper->add_sub_section(
				'noo_property_sub_section_idx',
				__( 'dsIDXPress Plugin', 'noo' ),
				__( 'This section has settings dedicated to dsIDXPress plugin. Please note that you will have to use "dsIDX Template" on dsIDX general option for it to work.', 'noo' )
			);

			// Control: IDX Layout
			$helper->add_control(
				'noo_property_idx_layout',
				'noo_radio',
				__( 'IDX Layout', 'noo' ),
				'fullwidth',
				array(
					'choices' => array(
						'fullwidth'   => __( 'Full-Width', 'noo' ),
						'sidebar'   => __( 'With Right Sidebar', 'noo' ),
						'left_sidebar'   => __( 'With Left Sidebar', 'noo' )
					),
					'json' => array(
						'child_options' => array(
							'fullwidth'   => '',
							'sidebar'   => 'noo_property_idx_sidebar',
							'left_sidebar'   => 'noo_property_idx_sidebar',
						)
					)
				),
				array( 'transport' => 'postMessage' )
			);

			// Control: IDX Sidebar
			$helper->add_control(
				'noo_property_idx_sidebar',
				'widgets_select',
				__( 'IDX Sidebar', 'noo' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

			// Control: Enable Google Map
			$helper->add_control(
				'noo_property_idx_gmap',
				'checkbox',
				__( 'Show Google Map on IDX page ( The map will show if you use IDX Listing widget on the same page. )', 'noo' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

			// Control: Enable Form Search IDX
			$helper->add_control(
				'noo_property_map_form_search',
				'checkbox',
				__( 'Show Form Search Map on IDX page.', 'noo' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);
		}

	}
add_action( 'customize_register', 'noo_customizer_register_options_property' );
endif;

// 11. Page options.
if ( ! function_exists( 'noo_customizer_register_options_page' ) ) :
	function noo_customizer_register_options_page( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Page
		$helper->add_section(
			'noo_customizer_section_page',
			__( 'Page', 'noo' )
		);

	}
add_action( 'customize_register', 'noo_customizer_register_options_page' );
endif;

//12. WooCommerce options.
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) :
	if ( ! function_exists( 'noo_customizer_register_options_woocommerce' ) ) :
		function noo_customizer_register_options_woocommerce( $wp_customize ) {

			// declare helper object.
			$helper = new NOO_Customizer_Helper( $wp_customize );

			// Section: Revolution Slider
			$helper->add_section(
				'noo_customizer_section_shop',
				__( 'WooCommerce', 'noo' ),
				'',
				true
			);

			// Sub-section: Shop Page
			$helper->add_sub_section(
				'noo_woocommerce_sub_section_shop_page',
				__( 'Shop Page', 'noo' ),
				__( 'Choose Layout for your Shop Page.', 'noo' )
			);

			// Control: Shop Layout
			$helper->add_control(
				'noo_shop_layout',
				'noo_radio',
				__( 'Shop Layout', 'noo' ),
				'fullwidth',
				array(
					'choices' => array(
						'fullwidth'   => __( 'Full-Width', 'noo' ),
						'sidebar'   => __( 'With Right Sidebar', 'noo' ),
						'left_sidebar'   => __( 'With Left Sidebar', 'noo' )
					),
					'json' => array(
						'child_options' => array(
							'fullwidth'   => '',
							'sidebar'   => 'noo_shop_sidebar',
							'left_sidebar'   => 'noo_shop_sidebar',
						)
					)
				),
				array( 'transport' => 'postMessage' )
			);

			// Control: Shop Sidebar
			$helper->add_control(
				'noo_shop_sidebar',
				'widgets_select',
				__( 'Shop Sidebar', 'noo' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

			// Control: Number of Product per Page
			$helper->add_control(
				'noo_shop_num',
				'ui_slider',
				__( 'Products Per Page', 'noo' ),
				'12',
				array(
					'json' => array(
						'data_min'  => '4',
						'data_max'  => '50',
						'data_step' => '2'
					)
				),
				array( 'transport' => 'postMessage' )
			);

			// Sub-section: Single Product
			$helper->add_sub_section(
				'noo_woocommerce_sub_section_product',
				__( 'Single Product', 'noo' )
			);

			// Control: Product Layout
			$helper->add_control(
				'noo_woocommerce_product_layout',
				'noo_same_as_radio',
				__( 'Product Layout', 'noo' ),
				'same_as_shop',
				array(
					'choices' => array(
						'same_as_shop'   => __( 'Same as Shop Layout', 'noo' ),
						'fullwidth'   => __( 'Full-Width', 'noo' ),
						'sidebar'   => __( 'With Right Sidebar', 'noo' ),
						'left_sidebar'   => __( 'With Left Sidebar', 'noo' ),
					),
					'json' => array(
						'child_options' => array(
							'fullwidth'   => '',
							'sidebar'   => 'noo_woocommerce_product_sidebar',
							'left_sidebar'   => 'noo_woocommerce_product_sidebar',
						)
					)
				),
				array( 'transport' => 'postMessage' )
			);

			// Control: Product Sidebar
			$helper->add_control(
				'noo_woocommerce_product_sidebar',
				'widgets_select',
				__( 'Product Sidebar', 'noo' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

		}
	add_action( 'customize_register', 'noo_customizer_register_options_woocommerce' );
	endif;
endif;

// // 13. Revolution Slider options.
// if ( ! function_exists( 'noo_customizer_register_options_rev_slider' ) ) :
// 	function noo_customizer_register_options_rev_slider( $wp_customize ) {

// 		// declare helper object.
// 		$helper = new NOO_Customizer_Helper( $wp_customize );

// 		// Section: Revolution Slider
// 		$helper->add_section(
// 			'noo_customizer_section_rev_slider',
// 			__( 'Revolution Slider', 'noo' )
// 		);

// 	}
// add_action( 'customize_register', 'noo_customizer_register_options_rev_slider' );
// endif;

// 14. Social Media options
if ( ! function_exists( 'noo_customizer_register_options_social' ) ) :
	function noo_customizer_register_options_social( $wp_customize ) {

		// Declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Social Media
		$helper->add_section(
			'noo_customizer_section_social',
			__( 'Social Media', 'noo' ),
			__( 'Input URLs of your social media profile. Inputting URL here means that corresponding social icon will be displayed when Social Icon is enabled on Top Bar and/or on Bottom Bar.', 'noo' )
		);

		// Control: Facebook Profile URL
		$helper->add_control(
			'noo_social_facebook',
			'text',
			__( 'Facebook Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Twitter Profile URL
		$helper->add_control(
			'noo_social_twitter',
			'text',
			__( 'Twitter Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Google+ Profile URL
		$helper->add_control(
			'noo_social_google',
			'text',
			__( 'Google+ Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Pinterest Profile URL
		$helper->add_control(
			'noo_social_pinterest',
			'text',
			__( 'Pinterest Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: LinkedIn Profile URL
		$helper->add_control(
			'noo_social_linkedin',
			'text',
			__( 'LinkedIn Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: RSS Feed URL
		$helper->add_control(
			'noo_social_rss',
			'text',
			__( 'RSS Feed URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Youtube Profile URL
		$helper->add_control(
			'noo_social_youtube',
			'text',
			__( 'Youtube Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Instagram Profile URL
		$helper->add_control(
			'noo_social_instagram',
			'text',
			__( 'Instagram Profile URL', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

	}
add_action( 'customize_register', 'noo_customizer_register_options_social' );
endif;

// 15. Custom Code
if ( ! function_exists( 'noo_customizer_register_options_custom_code' ) ) :
	function noo_customizer_register_options_custom_code( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Custom Code
		$helper->add_section(
			'noo_customizer_section_custom_code',
			__( 'Custom Code', 'noo' ),
			__( 'In this section you can add custom JavaScript and CSS to your site.<br/>Your Google analytics tracking code should be added to Custom JavaScript field.', 'noo' )
		);

		// Control: Custom JS (Google Analytics)
		$helper->add_control(
			'noo_custom_javascript',
			'textarea',
			__( 'Custom JavaScript', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Custom CSS
		$helper->add_control(
			'noo_custom_css',
			'textarea',
			__( 'Custom CSS', 'noo' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);
	}
add_action( 'customize_register', 'noo_customizer_register_options_custom_code' );
endif;

// 16. Import/Export Settings.
if ( ! function_exists( 'noo_customizer_register_options_tools' ) ) :
	function noo_customizer_register_options_tools( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Custom Code
		$helper->add_section(
			'noo_customizer_section_tools',
			__( 'Import/Export Settings', 'noo' ),
			__( 'All themes from NooTheme share the same theme setting structure so you can export then import settings from one theme to another conveniently without any problem.', 'noo' )
		);

		// Sub-section: Import Settings
		$helper->add_sub_section(
			'noo_tools_sub_section_import',
			__( 'Import Settings', 'noo' ),
			__( 'Click Upload button then choose a JSON file (.json) from your computer to import settings to this theme.<br/>All the settings will be loaded for preview here and will not be saved until you click button "Save and Publish".', 'noo' )
		);

		// Control: Upload Settings
		$helper->add_control(
			'noo_tools_import',
			'import_settings',
			__( 'Upload', 'noo' )
		);

		// Sub-section: Export Settings
		$helper->add_sub_section(
			'noo_tools_sub_section_export',
			__( 'Export Settings', 'noo' ),
			__( 'Simply click Download button to export all your settings to a JSON file (.json).<br/>You then can use that file to restore theme settings to any theme of NooTheme.', 'noo' )
		);

		// Control: Download Settings
		$helper->add_control(
			'noo_tools_export',
			'export_settings',
			__( 'Download', 'noo' )
		);

	}
add_action( 'customize_register', 'noo_customizer_register_options_tools' );
endif;

