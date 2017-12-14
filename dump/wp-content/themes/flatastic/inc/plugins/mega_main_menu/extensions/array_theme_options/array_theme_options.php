<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	if ( !function_exists( 'mega_main_menu__array_theme_options' ) ) {
		function mega_main_menu__array_theme_options( $constants ){
			foreach ( get_nav_menu_locations() as $key => $value ){
				$key = str_replace( ' ', '-', $key );
				$theme_menu_locations[ $key ] = $key;
			}
			$locations_options = array(
				array(
					'name' => __( 'Below are all the locations, which are supported this theme. Toggle for change their settings.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
					'key' => 'primary_settings',
					'type' => 'caption',
				),
			);
			if ( isset( $theme_menu_locations ) && is_array( $theme_menu_locations ) ) {
				$locations_options[] = array(
					'name' => __( 'Activate Mega Main Menu in such locations:', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
					'descr' => __( 'Mega Main Menu and its settings will be displayed in selected locations only after the activation of this location.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
					'key' => 'mega_menu_locations',
					'type' => 'checkbox',
					'values' => $theme_menu_locations
				);
			} else {
				$locations_options[] = array(
					'name' => __( 'Firstly, You need to create at least one menu', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . ' (<a href="' . home_url() . '/wp-admin/nav-menus.php">' . __( 'Theme Menu Settings', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '</a>) ' . __( 'and set theme-location for him', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . ' (<a href="' . home_url() . '/wp-admin/nav-menus.php?action=locations">' . __( 'Theme Menu Locations', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '</a>).',
					'key' => 'no_locations',
					'type' => 'caption',
				);
			}

			foreach ( get_nav_menu_locations() as $key => $value ){
				$original_menu_slug = $key;
				$key = str_replace( ' ', '-', $key );
				$locations_options = array_merge( 
					$locations_options, array(
						array(
							'name' =>  __( 'Layout Options: ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '&nbsp; <strong>' . $key . '</strong>',
							'key' => $key . '_menu_options',
							'type' => 'collapse_start',
						),
//						array(
//							'name' => __( 'Add to Mega Main Menu:', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'You can add to the primary menu container: logo and search.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_included_components',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Company Logo (on left side)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'company_logo',
//								__( 'Search Box (on right side)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'search_box',
//								__( 'BuddyPress Bar (on right side)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'buddypress',
//								__( 'WooCart (on right side)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'woo_cart',
//								__( 'WPML switcher (on right side)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'wpml_switcher',
//							),
//							'default' => array( '' ),
//						),
//						array(
//							'name' => __( 'Height of the initial container and menu items of the first level.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Set the extent for the initial menu container and items of the first level.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_first_level_item_height',
//							'type' => 'number',
//							'min' => 20,
//							'max' => 300,
//							'units' => 'px',
//							'values' => '50',
//							'default' => '50',
//						),
						array(
							'name' => __( 'Primary Style', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Select the button style that fits the style of your site.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_primary_style',
							'type' => 'radio',
							'col_width' => 4,
							'values' => array(
								__( 'Flat', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'flat',
								__( 'Buttons', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'buttons',
							),
							'default' => array( 'flat', ),
						),
						array(
							'name' => __( 'Buttons Height', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Only for "Buttons" style. Specify here height of the first level buttons.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_first_level_button_height',
							'type' => 'number',
							'min' => 20,
							'max' => 300,
							'units' => 'px',
							'values' => '30',
							'default' => '30',
							'dependency' => array(
								'element' => 'mega_main_menu_options[' . $key . '_primary_style]', 
								'value' => array(
									'buttons',
								),
							),
						),
						array(
							'name' => __( 'Alignment of the first level items.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Choose how to locate menu elements of the first level.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_first_level_item_align',
							'type' => 'radio',
							'col_width' => 4,
							'values' => array(
								__( 'Left', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'left',
								__( 'Center', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'center',
								__( 'Right', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'right',
								__( 'Justify', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'justify',
							),
							'default' => array( 'left', ),
						),
						array(
							'name' => __( 'Location of icon in first level elements', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Choose where to locate icon for first level items.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_first_level_icons_position',
							'type' => 'radio',
							'col_width' => 4,
							'values' => array(
								__( 'Left', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'left',
								__( 'Above', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'top',
								__( 'Right', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'right',
								__( 'Disable Icons In First Level Items', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable_first_lvl',
								__( 'Disable Icons Globally', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable_globally',
							),
							'default' => array( 'left', ),
						),
//						array(
//							'name' => __( 'Separator:', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Select type of separator between the first level items of this menu.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_first_level_separator',
//							'type' => 'radio',
//							'col_width' => 4,
//							'values' => array(
//								__( 'None', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'none',
//								__( 'Smooth', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'smooth',
//								__( 'Sharp', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'sharp',
//							),
//							'default' => array( 'smooth', ),
//						),
//						array(
//							'name' => __( 'Rounded corners', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Select the value of corners radius.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_corners_rounding',
//							'type' => 'number',
//							'min' => 0,
//							'max' => 100,
//							'units' => 'px',
//							'default' => 0,
//						),
						array(
							'name' => __( 'Dropdowns Animation:', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Select the type of animation to displaying dropdowns. <span style="color: #f11;">Warning:</span> Animation correctly works only in the latest versions of progressive browsers.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_dropdowns_animation',
							'type' => 'select',
							'values' => array(
								__( 'None', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'none',
								__( 'Unfold', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'anim_1',
								__( 'Fading', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'anim_2',
								__( 'Scale', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'anim_3',
								__( 'Down to Up', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'anim_4',
								__( 'Dropdown', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'anim_5',
							),
							'default' => array( 'anim_4', ),
						),
//						array(
//							'name' => __( 'Minimized on Handheld Devices', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'If this option is activated you get the folded menu on handheld devices.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_mobile_minimized',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Activate', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//							'default' => array( 'true', ),
//						),
//						array(
//							'name' => __( 'Label for Mobile Menu', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Here you can specify label that will be displayed on the mobile version of the menu.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_mobile_label',
//							'type' => 'text',
//							'values' => '',
//							'default' => 'Menu',
//							'dependency' => array(
//								'element' => 'mega_main_menu_options[' . $key . '_mobile_minimized]',
//								'value' => array(
//									'true',
//								),
//							),
//						),
						array(
							'name' => __( 'Direction', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Here you can determine the direction of the menu. Horizontal for classic top menu bar. Vertical for sidebar menu.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_direction',
							'type' => 'select',
							'values' => array(
								__( 'Horizontal', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'horizontal',
								__( 'Vertical', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'vertical',
							),
							'default' => array( 'horizontal' ),
						),
//						array(
//							'name' => __( 'Full Width Initial Container', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'If this option is enabled then the primary container will try to be the full width.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_fullwidth_container',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Enable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//							'dependency' => array(
//								'element' => 'mega_main_menu_options[' . $key . '_direction]',
//								'value' => array(
//									'horizontal',
//								),
//							),
//						),
//						array(
//							'name' => __( 'Height of the first level items when menu is Sticky (or Mobile)', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Set the extent for the initial menu container and items of the first level.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_first_level_item_height_sticky',
//							'type' => 'number',
//							'min' => 20,
//							'max' => 300,
//							'units' => 'px',
//							'values' => '40',
//							'default' => '40',
//							'dependency' => array(
//								'element' => 'mega_main_menu_options[' . $key . '_direction]',
//								'value' => array(
//									'horizontal',
//								),
//							),
//						),
//						array(
//							'name' => __( 'Sticky', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Check this option to make the menu sticky. Not working on mobile devices. If the menu will be is sticky on mobile devices when you open it - you can not click on the last item, because it will always be outside the screen.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_sticky_status',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Enable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//						),
//						array(
//							'name' => __( 'Sticky scroll offset', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Set the length of the scroll for each user to pass before the menu will stick to the top of the window.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_sticky_offset',
//							'type' => 'number',
//							'min' => 0,
//							'max' => 2000,
//							'units' => 'px',
//							'default' => 340,
//							'dependency' => array(
//								'element' => 'mega_main_menu_options[' . $key . '_sticky_status]',
//								'value' => array(
//									'true',
//								),
//							),
//						),
						array(
							'name' => __( 'Forced PHP Integration', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'If you have knowledge of PHP you can call this function anywhere in order to display this menu. Please use it only if you professional.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'forced_integration',
							'type' => 'just_html',
							'default' => '<pre>&lt;?php echo wp_nav_menu( array( "theme_location" => "' . $original_menu_slug . '" ) ); ?&gt;</pre>',
						),
						array(
							'name' => '',
							'type' => 'collapse_end',
						),
					) // 'options' => array
				);
			};

			$locations_options = array_merge( 
				$locations_options, array(
					array(
						'name' => __( 'Logo Settings', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'key' => 'mega_menu_logo',
						'type' => 'caption',
					),
					array(
						'name' => __( 'The logo file', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'descr' => __( "Select image to be used as logo in Main Mega Menu. It's recommended to use image with transparent background (.PNG) and sizes from 200 to 800 px.", $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'key' => 'logo_src',
						'type' => 'file',
						'default' => $constants[ 'MM_WARE_IMG_URL' ] . '/megamain-logo-120x120.png',
					),
					array(
						'name' => __( 'Maximum logo height', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'descr' => __( 'Maximum logo height in terms of percentage in regard to the height of the initial container.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'key' => 'logo_height',
						'min' => 10,
						'max' => 100,
						'units' => '%',
						'type' => 'number',
						'default' => 90,
					),
					array(
						'name' => __( 'Backup of the configuration', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'descr' => __( 'You can make a backup of the plugin configuration and restore this configuration later. Notice: Options of each menu item from the section "Menu Structure" is not imported.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'key' => 'backup',
						'type' => 'just_html',
						'default' => '<a href="' . get_admin_url() . '?' . $constants[ 'MM_WARE_PREFIX' ] . '_page=backup_file">' . __( 'Download backup file with current settings', MAD_BASE_TEXTDOMAIN) . '</a><br /><br />' . __( 'Upload backup file and restore settings. Chose file and click "Save All Settings".') . '<br /><input class="col-xs-12 form-control input-sm" type="file" name="' . $constants[ 'MM_OPTIONS_NAME' ] . '_backup" />',
					),
				) // 'options' => array
			);

			$skins_options = array(
				array(
					'name' => __( 'You can change any properties for any menu location', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
					'key' => 'mega_menu_skins',
					'type' => 'caption',
				)
			);
			foreach ( get_nav_menu_locations() as $key => $value ){
				$key = str_replace( ' ', '-', $key );
				$skins_options = array_merge( 
					$skins_options, array(
						array(
							'name' =>  __( 'Skin Options: ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '&nbsp; <strong>' . $key . '</strong>',
							'key' => $key . '_menu_skin',
							'type' => 'collapse_start',
						),
						array(
							'name' => __( 'Skin Options of the Initial Container', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'dropdowns_skin_options',
							'type' => 'caption',
						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the primary container ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_bg_gradient',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '', 'color2' => '', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
//						array(
//							'name' => __( 'Background image of the primary container', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'You can choose and tune the background image for the primary container.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_bg_image',
//							'type' => 'background_image',
//							'default' => '',
//						),
						array(
							'name' => __( 'First Level Items', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'dropdowns_skin_options',
							'type' => 'caption',
						),
						array(
							'name' => __( 'Font of the First Level Item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'You can change size and weight of the font for first level items.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_menu_first_level_link_font',
							'type' => 'font',
							'values' => array( 'font_family', 'font_size', 'font_weight' ),
							'default' => array( 'font_family' => 'Inherit', 'font_size' => '14', 'font_weight' => '700' ),
						),
//						array(
//							'name' => __( 'Text color of the first level item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_first_level_link_color',
//							'type' => 'color',
//							'default' => '#f8f8f8',
//						),
						array(
							'name' => __( 'Icons in the first level item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_menu_first_level_icon_font',
							'type' => 'font',
							'values' => array( 'font_size', ),
							'default' => array( 'font_size' => '15', ),
						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the first level item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_first_level_link_bg',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '', 'color2' => '', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
//						array(
//							'name' => __( 'Text color of the active first level item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_first_level_link_color_hover',
//							'type' => 'color',
//							'default' => '#f8f8f8',
//						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the active first level item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_first_level_link_bg_hover',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '#e74c3c', 'color2' => '#e74c3c', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
//						array(
//							'name' => __( 'Background color of the Search Box', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_search_bg',
//							'type' => 'color',
//							'default' => '',
//						),
//						array(
//							'name' => __( 'Text and icon color of the Search Box', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_search_color',
//							'type' => 'color',
//							'default' => '',
//						),
						array(
							'name' => __( 'Dropdowns', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'dropdowns_skin_options',
							'type' => 'caption',
						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the Dropdown elements', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_wrapper_gradient',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '#ffffff', 'color2' => '#ffffff', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
						array(
							'name' => __( 'Font of the dropdown menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_menu_dropdown_link_font',
							'type' => 'font',
							'values' => array( 'font_family', 'font_size', 'font_weight' ),
							'default' => array( 'font_family' => 'Inherit', 'font_size' => '14', 'font_weight' => '300' ),
						),
//						array(
//							'name' => __( 'Text color of the dropdown menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_link_color',
//							'type' => 'color',
//							'default' => '#292f38',
//						),
						array(
							'name' => __( 'Icons of the dropdown menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => $key . '_menu_dropdown_icon_font',
							'type' => 'font',
							'values' => array( 'font_size', ),
							'default' => array( 'font_size' => '14', ),
						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the dropdown menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_link_bg',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '', 'color2' => '', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
//						array(
//							'name' => __( 'Border color between dropdown menu items', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_link_border_color',
//							'type' => 'color',
//							'default' => '',
//						),
//						array(
//							'name' => __( 'Text color of the dropdown active menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_link_color_hover',
//							'type' => 'color',
//							'default' => '#e74c3c',
//						),
//						array(
//							'name' => __( 'Background Gradient (Color) of the dropdown active menu item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_link_bg_hover',
//							'type' => 'gradient',
//							'default' => array( 'color1' => '#ecf0f1', 'color2' => '#ecf0f1', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//						),
//						array(
//							'name' => __( 'Plain Text Color of the Dropdown', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => $key . '_menu_dropdown_plain_text_color',
//							'type' => 'color',
//							'default' => '#292f38',
//						),
						array(
							'name' => '',
							'type' => 'collapse_end',
						),
					) // 'options' => array
				);
			};
			$skins_options = array_merge( 
				$skins_options, array(
					array(
						'name' => __( 'Set of Installed Google Fonts', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'descr' => __( 'Select the fonts to be included on the site. Remember that a lot of fonts affect on the speed of load page. Always remove unnecessary fonts. Font faces can see on this page - ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '<a href="http://www.google.com/fonts" target="_blank">Google fonts</a>',
						'key' => 'set_of_google_fonts',
						'type' => 'multiplier',
						'default' => '0',
						'values' => array(
							array(
								'name' => __( 'Font 1', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
								'key' => 'font_item',
								'type' => 'collapse_start',
							),
							array(
								'name' => __( 'Fonts Faily', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
								'key' => 'family',
								'type' => 'select',
								'values' => mad_mm_datastore::get_googlefonts_list(),
								'default' => 'Open Sans'
							),
							array(
								'name' => '',
								'key' => 'font_item',
								'type' => 'collapse_end',
							),
						),
					),
					array(
						'name' => __( 'Custom Icons', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'descr' => __( 'You can add custom raster icons. After saving these settings, icons will become available in a modal window of icons selection. Recommended size 64x64 pixels.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
						'key' => 'set_of_custom_icons',
						'type' => 'multiplier',
						'default' => '1',
						'values' => array(
							array(
								'name' => __( 'Custom Icon 1', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
								'key' => 'icon_item',
								'type' => 'collapse_start',
							),
							array(
								'name' => __( 'Icon File', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
								'key' => 'custom_icon',
								'type' => 'file',
								'default' => $constants[ 'MM_WARE_IMG_URL' ] . '/megamain-logo-120x120.png',
							),
							array(
								'name' => '',
								'key' => 'icon_item',
								'type' => 'collapse_end',
							),
						),
					),
//					array(
//						'name' =>  __( 'Additional Styles: ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//						'descr' => __( 'Here you can add and edit highlighting styles. After that you can select these styles for menu item in "Menus -> Your Menu Item -> Style of This Item" option.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] )	,
//						'key' => 'additional_styles_presets',
//						'type' => 'multiplier',
//						'default' => '0',
//						'values' => array(
//							array(
//								'name' => __( 'Style 1', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'additional_style_item',
//								'type' => 'collapse_start',
//							),
//							array(
//								'name' => __( 'Style Name', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'style_name',
//								'type' => 'textfield',
//								'default' => 'My Highlight Style'
//							),
//							array(
//								'name' => __( 'Font', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'font',
//								'type' => 'font',
//								'values' => array( 'font_family', 'font_size', 'font_weight' ),
//								'default' => array( 'font_family' => 'Inherit', 'font_size' => '12', 'font_weight' => '400' ),
//							),
//							array(
//								'name' => __( 'Icon Size', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'icon',
//								'type' => 'font',
//								'values' => array( 'font_size', ),
//								'default' => array( 'font_size' => '12', ),
//							),
//							array(
//								'name' => __( 'Text color', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'text_color',
//								'type' => 'color',
//								'default' => '#f8f8f8',
//							),
//							array(
//								'name' => __( 'Background Gradient (Color) ', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'bg_gradient',
//								'type' => 'gradient',
//								'default' => array( 'color1' => '#34495E', 'color2' => '#2C3E50', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//							),
//							array(
//								'name' => __( 'Text color of the active item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'text_color_hover',
//								'type' => 'color',
//								'default' => '#f8f8f8',
//							),
//							array(
//								'name' => __( 'Background Gradient (Color) of the active item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//								'key' => 'bg_gradient_hover',
//								'type' => 'gradient',
//								'default' => array( 'color1' => '#3d566e', 'color2' => '#354b60', 'start' => '0', 'end' => '100', 'orientation' => 'top' ),
//							),
//							array(
//								'name' => '',
//								'key' => 'additional_style_item',
//								'type' => 'collapse_end',
//							),
//						),
//					),
				)
			);

			return array(
				array(
					'title' => 'General',
					'key' => 'mm_general',
					'icon' => 'im-icon-wrench-3',
					'options' => $locations_options,
				),
				array(
					'title' => 'Skins',
					'key' => 'mm_skins',
					'icon' => 'im-icon-brush',
					'options' => $skins_options, // 'options' => array
				),
				array(
					'title' => 'Specific Options',
					'key' => 'mm_specific_options',
					'icon' => 'im-icon-hammer',
					'options' => array(
						array(
							'name' => __( 'Custom CSS', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'You can place here any necessary custom CSS properties.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'custom_css',
							'type' => 'textarea',
							'col_width' => 12,
						),
//						array(
//							'name' => __( 'Responsive for Handheld Devices', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Enable responsive properties. If this option is enabled, then the menu will be transformed, if the user uses the handheld device.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'responsive_styles',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Activate', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//							'default' => array( 'true', ),
//						),
//						array(
//							'name' => __( 'Responsive Resolution', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Select on which screen resolution menu will be transformed for mobile devices.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'responsive_resolution',
//							'type' => 'radio',
//							'col_width' => 3,
//							'values' => array(
//								'480px (iPhone Landscape)' => '480',
//								'768px (iPad Portrait)' => '768',
//								'960px' => '960',
//								'1024px (iPad Landscape)' => '1024',
//							),
//							'default' => array( '768', ),
//						),
						array(
							'name' => __( 'Use Coercive Styles', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'If this option is checked - all CSS properties for this plugin will be have "!important" priority.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'coercive_styles',
							'type' => 'checkbox',
							'values' => array(
								__( 'Activate', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
							),
						),
						array(
							'name' => __( '"Indefinite location" mode', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( '<span style="color: #f11;">Warning:</span> If this option is checked - all menus will be replaced by the mega menu. This will be useful only for templates in which are not defined locations of the menu and template has only one menu.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'indefinite_location_mode',
							'type' => 'checkbox',
							'values' => array(
								__( 'Activate', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
							),
						),
//						array(
//							'name' => __( 'Number of widget areas', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'descr' => __( 'Set here how many independent widget areas you need.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'number_of_widgets',
//							'type' => 'number',
//							'min' => 0,
//							'max' => 100,
//							'units' => 'areas',
//							'values' => '1',
//							'default' => '1',
//						),
						array(
							'name' => __( 'Language text direction', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'You can select direction of the text for this plugin. LTR - sites where text is read from left to right. RTL - sites where text is read from right to left.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'language_direction',
							'type' => 'radio',
							'values' => array(
								__( 'Left To Right', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'ltr',
								__( 'Right To Left', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'rtl',
							),
							'default' =>  array(
								'ltr',
							),
						),
					), // 'options' => array
				),
				array(
					'title' => 'Settings of the structure',
					'key' => 'mm_structure_settings',
					'icon' => 'im-icon-checkbox',
					'options' => array(
						array(
							'name' => __( 'Here you can deactivate the options that you  do not use to customize the menu structure. It helps reduce the number of options and reduce the load on the server.', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'menu_structure_settings',
							'type' => 'caption',
						),
						array(
							'name' => __( 'Description of the item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'item_descr',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Style of the item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'item_style',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Visibility Control', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'item_visibility',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Icon of the item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'item_icon',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
	/*
						array(
							'name' => __( 'Hide Icon of the Item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'disable_icon',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
	*/
						array(
							'name' => __( 'Hide Text of the Item', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'disable_text',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Disable Link', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'disable_link',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Submenu Type', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_type',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Side of dropdown elements', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_drops_side',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Submenu Columns', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_columns',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
						array(
							'name' => __( 'Enable Full Width Dropdown', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_enable_full_width',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
							),
						),
//						array(
//							'name' => __( 'Dropdown Background Image', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'submenu_bg_image',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Disable', $constants[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'disable',
//							),
//						),
					), // 'options' => array
				),
				array(
					'title' => 'Documentation & Support',
					'key' => 'mm_support',
					'icon' => 'im-icon-support',
					'options' => array(
						array(
							'name' => '',
							'key' => 'support',
							'type' => 'just_html',
							'default' => '<br /><br /> <a href="http://manual.menu.megamain.com/" target="_blank">Online documentation</a>. <br /><br /> If you need support, <br /> If you have a question or suggestion - <br /> Leave a message on our support page <br /> <a href="http://support.megamain.com/?ref=' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '" target="_blank">Support.MegaMain.com</a> (in new tab).',
						),
					), // 'options' => array
				),
			); // END FRIMARY ARRAY
		}
	}
?>
