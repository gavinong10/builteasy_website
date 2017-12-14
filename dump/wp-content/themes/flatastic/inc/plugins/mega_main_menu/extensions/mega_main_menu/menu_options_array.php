<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	if ( !function_exists( 'mmm_menu_options_array' ) ) {
		function mmm_menu_options_array(){
			global $mmm_menu_options_array;
			global $mega_main_menu;
			if ( isset( $mmm_menu_options_array ) && $mmm_menu_options_array !== false ) {
				$options = $mmm_menu_options_array;
			} else {
				/* Additional styles */
				$additional_styles_presets = $mega_main_menu->get_option( 'additional_styles_presets' );
				$additional_styles[ __( 'Default', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) ] = 'default_style';
				if ( is_array( $additional_styles_presets ) ) {
					unset( $additional_styles_presets['0'] );
					foreach ( $additional_styles_presets as $key => $value) {
						$additional_styles[ $key . '. ' . $value['style_name'] ] = 'additional_style_' . $key;
					}
				}
				/* Submenu types */
				$submenu_types = array(
					__( 'Standard Submenu', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'default_dropdown',
					__( 'Multicolumn Submenu', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'multicolumn_dropdown',
					__( 'Tabs Submenu', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'tabs_dropdown',
					__( 'Grid Submenu', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'grid_dropdown',
					__( 'Posts Grid Submenu', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'post_type_dropdown',
				);

				$number_of_widgets = $mega_main_menu->get_option( 'number_of_widgets', '1' );
				if ( is_numeric( $number_of_widgets ) && $number_of_widgets > 0 ) {
					for ( $i=1; $i <= $number_of_widgets; $i++ ) { 
						$submenu_widgets[ __( 'Widgets area ', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . $i ] = $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_menu_widgets_area_' . $i;
					}
					$submenu_types = array_merge( $submenu_types, $submenu_widgets );
				}
				/* options */
				$options = array(
//						array(
//							'descr' => __( 'Description', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'item_descr',
//							'type' => 'textarea',
//							'col_width' => 2
//						),
//						array(
//							'descr' => __( 'Style of This Item', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'item_style',
//							'type' => 'select',
//							'values' => $additional_styles,
//							'default' => 'default',
//						),
//						array(
//							'descr' => __( 'Visibility Control', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'item_visibility',
//							'type' => 'select',
//							'values' => array(
//								__( 'Always Visible', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'all',
//								__( 'Visible Only to Logged Users', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'logged',
//								__( 'Visible Only to Unlogged Visitors', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'visitors',
//							),
//						),
//						array(
//							'descr' => __( 'Icon of This Item (set empty to hide)', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'key' => 'item_icon',
//							'type' => 'icons',
//						),
//						array(
//							'key' => 'disable_text',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Hide Text of This Item', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//						),
						array(
							'key' => 'disable_link',
							'type' => 'checkbox',
							'values' => array(
								__( 'Disable Link', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
							),
						),
//						array(
//							'key' => 'pull_to_other_side',
//							'type' => 'checkbox',
//							'values' => array(
//								__( 'Pull to the Other Side', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
//							),
//						),
						array(
							'name' => __( 'Options of Dropdown', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( 'Submenu Type', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_type',
							'type' => 'select',
							'values' => $submenu_types,
/*
							'dependency' => array(
								'element' =>'submenu_post_type',
								'value' => 'post_type_dropdown',
							),
*/
					   ),
						array(
							'key' => 'submenu_post_type',
							'descr' => __( 'Post Type for Display in Dropdown', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'type' => 'select',
							'values' => mad_mm_common::get_all_taxonomies(),
							'dependency' => array(
								'element' => 'menu-item-submenu_type[__ID__]', 
								'value' => array(
									'post_type_dropdown',
								),
							),
						),
//						array(
//							'key' => 'submenu_drops_side',
//							'descr' => __( 'Side of Dropdown Elements', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
//							'type' => 'select',
//							'values' => array(
//								__( 'Drop To Right Side', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'drop_to_right',
//								__( 'Drop To Left Side', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'drop_to_left',
//								__( 'Drop To Center', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'drop_to_center',
//							),
//						),
						array(
							'descr' => __( 'Submenu Columns (Not For Standard Drops)', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_columns',
							'type' => 'select',
							'values' => range(1, 10),
						),
						array(
							'key' => 'submenu_enable_full_width',
							'type' => 'checkbox',
							'values' => array(
								__( 'Enable Full Width Dropdown (only for horizontal menu)', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'true',
							),
						),
						array(
							'name' => __( 'Dropdown Background Image', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'descr' => __( '', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ),
							'key' => 'submenu_bg_image',
							'type' => 'background_image',
							'default' => '',
						),
				);
				$GLOBALS['mmm_menu_options_array'] = $options;
			}
			return $options;
		}
	}
?>