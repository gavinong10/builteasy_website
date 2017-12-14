<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

	/**
	 * actions what we need for call all functions in this file.
	 * @return void
	 */
	global $mega_main_menu;
	if ( is_array( $mega_main_menu->get_option( 'coercive_styles' ) ) && in_array( 'true', $mega_main_menu->get_option( 'coercive_styles', array() ) ) ) {
		remove_all_filters( 'wp_nav_menu_items', 60 );
		remove_all_filters( 'wp_nav_menu_args', 60 );
	}
	add_filter( 'wp_nav_menu_items', 'mmm_nav_search', 5, 8 );
	add_filter( 'wp_nav_menu_items', 'mmm_nav_woo_cart', 5, 8 );
	add_filter( 'wp_nav_menu_items', 'mmm_nav_buddypress', 5, 8 );
	add_filter( 'wp_nav_menu_items', 'mmm_nav_wpml_switcher', 5, 8 );
	add_filter( 'wp_nav_menu_args', 'mmm_set_walkers', 10, 8 );


	if ( function_exists( 'theme_get_menu' ) && function_exists( 'theme_get_list_menu' ) ) {
		$mega_menu_locations = $mega_main_menu->get_option( 'mega_menu_locations' );

		static $mega_menu_location_id = 0;
		$mega_menu_location_id ++;
		$indefinite_location_mode = ( is_array( $mega_main_menu->get_option( 'indefinite_location_mode' ) ) && in_array( 'true', $mega_main_menu->get_option( 'indefinite_location_mode' ) ) ) ? true : false;
		if ( isset( $mega_menu_locations[ $mega_menu_location_id ] ) && ( $indefinite_location_mode === true ) ) {
			$args['theme_location'] = $mega_menu_locations[ $mega_menu_location_id ];
			$args['echo'] = false;
			$GLOBALS['wp_nav_menu_html'] = wp_nav_menu( $args );
			function artisteer_nav_menu () {
				global $wp_nav_menu_html;
				return $wp_nav_menu_html;
			}
			add_filter( 'wp_nav_menu', 'artisteer_nav_menu', 50 );
		}
	}
	/**
	 * Check current location and set args.
	 * @return $items
	 */
	function mmm_set_walkers ( $args ){
		global $mega_main_menu;
		$args = (array)$args;
		$mega_menu_locations = $mega_main_menu->get_option( 'mega_menu_locations' );
		$indefinite_location_mode = ( is_array( $mega_main_menu->get_option( 'indefinite_location_mode' ) ) && in_array( 'true', $mega_main_menu->get_option( 'indefinite_location_mode' ) ) ) ? true : false;
		$theme_location_safe_name = str_replace( ' ', '-', $args['theme_location'] );

		static $mega_menu_location_id = 0;
		$mega_menu_location_id ++;
		if ( $indefinite_location_mode === true && isset( $args['theme_location'] ) && $args['theme_location'] == '' && isset( $mega_menu_locations[1] ) ) {
			$args['theme_location'] = $mega_menu_locations[ $mega_menu_location_id ];
		}

		if ( ( is_array( $mega_menu_locations ) && in_array( $args['theme_location'], $mega_menu_locations ) ) || ( $indefinite_location_mode === true ) ) {

			// Default args
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
			$args['walker'] = new Mega_Main_Menu_Frontend_Walker;
			$args['container'] = false;
			$args['container_id'] = '';
			$args['container_class'] = '';
			$args['menu_id'] = 'mega_main_menu_ul';
			$args['menu_class'] = 'mega_main_menu_ul';
			$args['before'] = '';
			$args['after'] = '';
			$args['link_before'] = '';
			$args['link_after'] = '';
			$args['depth'] = 9;

			// check container_class variables
			$container_class[] = $theme_location_safe_name;
			$container_class[] = 'primary_style-' . $mega_main_menu->get_option( $theme_location_safe_name . '_primary_style', 'flat' );
			$container_class[] = 'icons-' . $mega_main_menu->get_option( $theme_location_safe_name . '_first_level_icons_position', 'left' );
			$container_class[] = 'first-lvl-align-' . $mega_main_menu->get_option( $theme_location_safe_name . '_first_level_item_align', 'left' );
//			$container_class[] = 'first-lvl-separator-' . $mega_main_menu->get_option( $theme_location_safe_name . '_first_level_separator', 'none' );
			$container_class[] = 'direction-' . $mega_main_menu->get_option( $theme_location_safe_name . '_direction', 'horizontal' );
//			$container_class[] = 'fullwidth-' . ( ( is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_fullwidth_container' ) ) && in_array( 'true', $mega_main_menu->get_option( $theme_location_safe_name . '_fullwidth_container' ) ) ) ? 'enable' : 'disable' );
			$container_class[] = 'mobile_minimized-' . ( ( ( is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_mobile_minimized' ) ) && in_array( 'true', $mega_main_menu->get_option( $theme_location_safe_name . '_mobile_minimized' ) ) ) || ( $indefinite_location_mode === true ) ) ? 'enable' : 'disable' );
			$container_class[] = 'dropdowns_animation-' . $mega_main_menu->get_option( $theme_location_safe_name . '_dropdowns_animation', 'none' ) ;
			$container_class[] = ( ( is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) && in_array( 'company_logo', $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) ) && $mega_main_menu->get_option( 'logo_src' ) ) ? 'include-logo' : 'no-logo';
			$container_class[] = ( ( in_array( $theme_location_safe_name, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) && in_array( 'search_box', $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) ) ? 'include-search' : 'no-search';
			$container_class[] = ( ( in_array( $theme_location_safe_name, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) && in_array( 'woo_cart', $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) ) ? 'include-woo_cart' : 'no-woo_cart';
			$container_class[] = ( ( in_array( $theme_location_safe_name, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) && in_array( 'buddypress', $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) ) ? 'include-buddypress' : 'no-buddypress';
			$container_class[] = 'responsive-' . ( ( is_array( $mega_main_menu->get_option( 'responsive_styles' ) ) && in_array( 'true', $mega_main_menu->get_option( 'responsive_styles' ) ) ) ? 'enable' : 'disable' );
			$container_class[] = 'coercive_styles-' . ( ( is_array( $mega_main_menu->get_option( 'coercive_styles' ) ) && in_array( 'true', $mega_main_menu->get_option( 'coercive_styles' ) ) ) ? 'enable' : 'disable' );
			$container_class[] = 'coercive_styles-' . ( ( is_array( $mega_main_menu->get_option( 'coercive_styles' ) ) && in_array( 'true', $mega_main_menu->get_option( 'coercive_styles' ) ) ) ? 'enable' : 'disable' );
			$container_class[] = 'indefinite_location_mode-' . ( ( $indefinite_location_mode === true ) ? 'enable' : 'disable' );
			$container_class[] = 'language_direction-' . $mega_main_menu->get_option( 'language_direction', 'ltr' );
			$container_class[] = 'version-' . str_replace('.', '-', $mega_main_menu->constant['MM_WARE_VERSION'] );

			if ( in_array( 'disable', $mega_main_menu->get_option( 'item_icon', array() ) ) ) {
				$container_class[] = 'structure_settings-no_icons icons-disable_globally';

			}

			// implode container_class variables
			$container_class_imploded = implode( ' ', $container_class );

			// apply_filters container_class
			if ( has_filter( 'mmm_container_class' ) ) {
				$container_class_imploded .= ' ' . esc_attr( apply_filters( 'mmm_container_class', '', $container_class ) );
			}

			// check sticky variables
//			$data_sticky = ( (is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_status' ) ) && in_array( 'true', $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_status', array() ) ) ) /*|| ( $indefinite_location_mode === true )*/ )
//				? ' data-sticky="1"'
//				: '';
//			$data_sticky .= ( ( $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_offset' ) !== false && is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_status' ) ) && in_array( 'true', $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_status', array() ) ) ) || ( $indefinite_location_mode === true ) )
//				? ' data-stickyoffset="' . $mega_main_menu->get_option( $theme_location_safe_name . '_sticky_offset' ) . '"'
//				: '';

			// items_wrap (container) markup
			$items_wrap = '';
			$items_wrap .= mad_mm_common::ntab(0) . '<div id="mega_main_menu" class="' . $container_class_imploded . ' mega_main mega_main_menu">';
			$items_wrap .= mad_mm_common::ntab(1) . '<div class="menu_holder">';
			$items_wrap .= mad_mm_common::ntab(1) . '<div class="mmm_fullwidth_container"></div><!-- class="fullwidth_container" -->';
			$items_wrap .= mad_mm_common::ntab(2) . '<div class="menu_inner">';
			$items_wrap .= mad_mm_common::ntab(3) . '<span class="nav_logo">';
			if( ( is_array( $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) && in_array( 'company_logo', $mega_main_menu->get_option( $theme_location_safe_name . '_included_components' ) ) ) && $mega_main_menu->get_option( 'logo_src' ) ) {
					$items_wrap .= mad_mm_common::ntab(4) . '<a class="logo_link" href="' . home_url() . '" title="' . get_bloginfo( 'name' ) . '">';
					$items_wrap .= mad_mm_common::ntab(5) . '<img src="' . str_replace( array('%','$'), array('',''), $mega_main_menu->get_option( 'logo_src' ) ) . '" alt="' . get_bloginfo( 'name' ) . '" />';
					$items_wrap .= mad_mm_common::ntab(4) . '</a>';
			}
			$items_wrap .= mad_mm_common::ntab(4) . '<a class="mobile_toggle">';
			$items_wrap .= mad_mm_common::ntab(5) . '<span class="mobile_button">';
			$items_wrap .= mad_mm_common::ntab(6) . $mega_main_menu->get_option( $theme_location_safe_name . '_mobile_label', 'Menu' ) . ' &nbsp;';
			$items_wrap .= mad_mm_common::ntab(6) . '<span class="symbol_menu">&equiv;</span>'; // "Open menu" symbol
			$items_wrap .= mad_mm_common::ntab(6) . '<span class="symbol_cross">&#x2573;</span>'; // "Close menu" symbol
			$items_wrap .= mad_mm_common::ntab(5) . '</span><!-- class="mobile_button" -->';
			$items_wrap .= mad_mm_common::ntab(4) . '</a>';
			$items_wrap .= mad_mm_common::ntab(3) . '</span><!-- /class="nav_logo" -->';
			$items_wrap .= mad_mm_common::ntab(4) . $args['items_wrap'];
			$items_wrap .= mad_mm_common::ntab(2) . '</div><!-- /class="menu_inner" -->';
			$items_wrap .= mad_mm_common::ntab(1) . '</div><!-- /class="menu_holder" -->';
			$items_wrap .= mad_mm_common::ntab(0) . '</div><!-- /id="mega_main_menu" -->';

			// items_wrap (container) markup
			$args['items_wrap'] = $items_wrap;
			$args['container_class'] = $container_class_imploded;
		}
		return $args;
	}

	/**
	 * Include search box in menu.
	 * @return $items
	 */
	function mmm_nav_search( $items, $args ) {
		global $mega_main_menu;
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
			$args->theme_location = str_replace( ' ', '-', $args->theme_location );
			$mega_menu_locations = is_array( $mega_main_menu->get_option( 'mega_menu_locations' ) ) ? $mega_main_menu->get_option( 'mega_menu_locations' ) : array();
			if( ( in_array( $args->theme_location, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) && in_array( 'search_box', $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) ) {
				$searchform = '';
				$searchform .= mad_mm_common::ntab(1) . '<li class="nav_search_box">';
				ob_start();
				include( $mega_main_menu->constant['MM_WARE_EXTENSIONS_DIR'] . '/html_templates/searchform.php' );
				$searchform .= ob_get_contents();
				ob_end_clean();
				$searchform .= mad_mm_common::ntab(1) . '</li><!-- class="nav_search_box" -->' . mad_mm_common::ntab(0);
				$items = $items . $searchform;
			}
		}
		return $items;
	}

	/**
	 * Include woo_cart in menu.
	 * @return $items
	 */
	function mmm_nav_woo_cart( $items, $args ) {
		global $mega_main_menu;
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
			$args->theme_location = str_replace( ' ', '-', $args->theme_location );
			$mega_menu_locations = is_array( $mega_main_menu->get_option( 'mega_menu_locations' ) ) ? $mega_main_menu->get_option( 'mega_menu_locations' ) : array();
			if( (in_array( $args->theme_location, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) && in_array( 'woo_cart', $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) ) {
				if ( class_exists( 'Woocommerce' ) ){
					$drop_side = ( $mega_main_menu->get_option( 'language_direction', 'ltr' ) == 'ltr' ) ? 'drop_to_left' : 'drop_to_right';
					$woo_cart_item = mad_mm_common::ntab(1) . '<li class="menu-item nav_woo_cart multicolumn_dropdown ' . $drop_side . ' submenu_default_width">';
					$woo_cart_item .= mad_mm_common::ntab(2) . '<span tabindex="0" class="item_link menu_item_without_text">';
					$woo_cart_item .= mad_mm_common::ntab(3) . '<i class="im-icon-cart"></i>';
					$woo_cart_item .= mad_mm_common::ntab(2) . '</span><!-- class="item_link" -->';
					$woo_cart_item .= mad_mm_common::ntab(2) . '<ul class="mega_dropdown">';
					$woo_cart_item .= mad_mm_common::ntab(3) . '<div class="woocommerce">';
					$woo_cart_item .= mad_mm_common::ntab(3) . '<div class="widget_shopping_cart_content"></div>';
					$woo_cart_item .= mad_mm_common::ntab(3) . '</div><!-- class="woocommerce" -->';
					$woo_cart_item .= mad_mm_common::ntab(2) . '</ul><!-- class="mega_dropdown" -->';
					$woo_cart_item .= mad_mm_common::ntab(1) . '</li><!-- class="nav_woo_cart" -->';
					$items = $items . $woo_cart_item;
				}
			}
		}
		return $items;
	}

	/**
	 * Include WPML switcher in menu.
	 * @return $items
	 */
	function mmm_nav_wpml_switcher ( $items, $args ) {
		global $mega_main_menu;
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
			$args->theme_location = str_replace( ' ', '-', $args->theme_location );
			$mega_menu_locations = is_array( $mega_main_menu->get_option( 'mega_menu_locations' ) ) ? $mega_main_menu->get_option( 'mega_menu_locations' ) : array();
			if( (in_array( $args->theme_location, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) && in_array( 'wpml_switcher', $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) ) {
				if ( function_exists( 'icl_get_languages' ) ){
					$languages = icl_get_languages('skip_missing=0');
					if ( 1 < count( $languages ) ) {
						$wpml_switcher_dropdown = '';
						foreach( $languages as $l ){
							$wpml_switcher_dropdown .= mad_mm_common::ntab(3) . '<li class="menu-item">';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(4) . '<a href="' . $l[ 'url' ] . '" title="' . $l[ 'translated_name' ] . '" tabindex="0" class="item_link with_icon">';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(3) . '<i class="ci-icon-' . $l[ 'language_code' ] . '"><style>.ci-icon-' . $l[ 'language_code' ] . ':before{ background-image: url("' . $l[ 'country_flag_url' ] . '"); }</style></i>';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(5) . '<span class="link_content">';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(6) . '<span class="link_text">' . $l[ 'native_name' ] . '</span>';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(5) . '</span>';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(4) . '</a><!-- class="item_link" -->';
							$wpml_switcher_dropdown .= mad_mm_common::ntab(3) . '</li>';
							if ( $l[ 'active' ] == 1 ) {
								$primary_item[ 'language_code' ] = $l[ 'language_code' ];
								$primary_item[ 'translated_name' ] = $l[ 'translated_name' ];
								$primary_item[ 'country_flag_url' ] = $l[ 'country_flag_url' ];
							}
						}

						$drop_side = ( $mega_main_menu->get_option( 'language_direction', 'ltr' ) == 'ltr' ) ? 'drop_to_left' : 'drop_to_right';
						$wpml_switcher_item = mad_mm_common::ntab(1) . '<li class="menu-item nav_wpml_switcher default_dropdown ' . $drop_side . ' submenu_default_width">';
						$wpml_switcher_item .= mad_mm_common::ntab(2) . '<span class="item_link menu_item_without_text">';
						$wpml_switcher_item .= mad_mm_common::ntab(3) . '<i class="ci-icon-' . $primary_item[ 'language_code' ] . '"><style>.ci-icon-' . $primary_item[ 'language_code' ] . ':before{ background-image: url("' . $primary_item[ 'country_flag_url' ] . '"); }</style></i>';
						$wpml_switcher_item .= mad_mm_common::ntab(2) . '</span><!-- class="item_link" -->';
						$wpml_switcher_item .= mad_mm_common::ntab(2) . '<ul class="mega_dropdown">';
						$wpml_switcher_item .= $wpml_switcher_dropdown;
						$wpml_switcher_item .= mad_mm_common::ntab(2) . '</ul><!-- class="mega_dropdown" -->';
						$wpml_switcher_item .= mad_mm_common::ntab(1) . '</li><!-- class="nav_wpml_switcher" -->';
						$items = $items . $wpml_switcher_item;
					}
				}
			}
		}
		return $items;
	}

	/**
	 * Include buddypress in menu.
	 * @return $items
	 */
	function mmm_nav_buddypress( $items, $args ) {
		global $mega_main_menu;
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
			$args->theme_location = str_replace( ' ', '-', $args->theme_location );
			$mega_menu_locations = is_array( $mega_main_menu->get_option( 'mega_menu_locations' ) ) ? $mega_main_menu->get_option( 'mega_menu_locations' ) : array();
			if( (in_array( $args->theme_location, $mega_menu_locations) ) && is_array( $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) && in_array( 'buddypress', $mega_main_menu->get_option( $args->theme_location . '_included_components' ) ) ) {
				if ( class_exists( 'BuddyPress' ) ){
					global $bp;

					$bp_avatar = bp_core_fetch_avatar( array( 'item_id'=>$bp->loggedin_user->id, 'html'=>false ) );
					if ( strpos( $bp_avatar, 'gravatar' ) !== false ) {
						$bp_avatar = $bp->avatar->thumb->default;
					}
					$buddypress_item = '';
					$drop_side = ( $mega_main_menu->get_option( 'language_direction', 'ltr' ) == 'ltr' ) ? 'drop_to_left' : 'drop_to_right';
					if ( is_user_logged_in() ) {
						$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
						$count = ! empty( $notifications ) ? count( $notifications ) : 0;
						$menu_link = trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() );
						$notification_class = (int) $count > 0 ? 'notification-yes' : 'notification-none';

						$buddypress_item .= mad_mm_common::ntab(1) . '<li class="menu-item nav_buddypress default_dropdown ' . $drop_side . ' submenu_default_width">';
						$buddypress_item .= mad_mm_common::ntab(2) . '<a href="' . $menu_link . '" tabindex="0" class="item_link ">';
						$buddypress_item .= mad_mm_common::ntab(3) . '<i class="ci-icon-buddypress-user"><style>.ci-icon-buddypress-user:before{ background-image: url("' . $bp_avatar . '"); }</style><span class="mega_notifications ' . $notification_class . '">' . $count . '</span></i>';
						$buddypress_item .= mad_mm_common::ntab(3) . '';
						$buddypress_item .= mad_mm_common::ntab(2) . '</a><!--  class="item_link" -->';
						$buddypress_item .= mad_mm_common::ntab(2) . '<ul class="mega_dropdown">';
						foreach ( $bp->bp_nav as $key => $component ) {
							switch ( $component[ 'slug' ] ) {
								case 'activity':
									$icon = 'health';
									break;
								case 'profile':
									$icon = 'user';
									break;
								case 'notifications':
									$icon = 'notification-2';
									break;
								case 'messages':
									$icon = 'envelop-opened';
									break;
								case 'friends':
									$icon = 'users';
									break;
								case 'groups':
									$icon = 'tree-5';
									break;
								default:
									$icon = 'cog';
									break;
							}
							$buddypress_item .= mad_mm_common::ntab(3) . '<li class="menu-item">';
							$buddypress_item .= mad_mm_common::ntab(4) . '<a href="' . $component[ 'link' ] . '" tabindex="0" class="item_link with_icon">';
							$buddypress_item .= mad_mm_common::ntab(5) . '<i class="im-icon-' . $icon . '"></i>';
							$buddypress_item .= mad_mm_common::ntab(5) . '<span class="link_content">';
							$buddypress_item .= mad_mm_common::ntab(6) . '<span class="link_text">' . $component[ 'name' ] . '</span>';
							$buddypress_item .= mad_mm_common::ntab(5) . '</span>';
							$buddypress_item .= mad_mm_common::ntab(4) . '</a><!-- class="item_link" -->';
							if ( is_array( $bp->bp_options_nav[ $component[ 'slug' ] ] ) ) {
								$buddypress_item .= mad_mm_common::ntab(4) . '<ul class="mega_dropdown">';
								foreach ( $bp->bp_options_nav[ $component[ 'slug' ] ] as $key => $sub_component ) {
									$buddypress_item .= mad_mm_common::ntab(5) . '<li class="menu-item">';
									$buddypress_item .= mad_mm_common::ntab(6) . '<a href="' . $sub_component[ 'link' ] . '" tabindex="0" class="item_link">';
									$buddypress_item .= mad_mm_common::ntab(7) . '<span class="link_content">';
									$buddypress_item .= mad_mm_common::ntab(8) . '<span class="link_text">' . $sub_component[ 'name' ] . '</span>';
									$buddypress_item .= mad_mm_common::ntab(7) . '</span>';
									$buddypress_item .= mad_mm_common::ntab(6) . '</a><!-- class="item_link" -->';
									$buddypress_item .= mad_mm_common::ntab(5) . '</li>';
								}
								$buddypress_item .= mad_mm_common::ntab(4) . '</ul><!-- class="mega_dropdown" -->';
							}
							$buddypress_item .= mad_mm_common::ntab(3) . '</li>';
						}

						$buddypress_item .= mad_mm_common::ntab(3) . '<li class="menu-item">';
						$buddypress_item .= mad_mm_common::ntab(4) . '<a href="' . wp_logout_url() . '" title="' . __( 'Log Out', MAD_BASE_TEXTDOMAIN ) . '" tabindex="0" class="item_link with_icon">';
						$buddypress_item .= mad_mm_common::ntab(5) . '<i class="im-icon-switch"></i>';
						$buddypress_item .= mad_mm_common::ntab(5) . '<span class="link_content">';
						$buddypress_item .= mad_mm_common::ntab(6) . '<span class="link_text">';
						$buddypress_item .= mad_mm_common::ntab(7) . __( 'Log Out', MAD_BASE_TEXTDOMAIN );
						$buddypress_item .= mad_mm_common::ntab(6) . '</span>';
						$buddypress_item .= mad_mm_common::ntab(5) . '</span>';
						$buddypress_item .= mad_mm_common::ntab(4) . '</a>';
						$buddypress_item .= mad_mm_common::ntab(3) . '</li>';
						$buddypress_item .= mad_mm_common::ntab(2) . '</ul><!-- class="mega_dropdown" -->';
						$buddypress_item .= mad_mm_common::ntab(1) . '</li><!-- class="nav_buddypress" -->' . mad_mm_common::ntab(0);
					} else {
						$buddypress_item .= mad_mm_common::ntab(1) . '<li class="nav_buddypress not_logged default_dropdown ' . $drop_side . ' submenu_default_width">';
						$buddypress_item .= mad_mm_common::ntab(2) . '<span class="item_link">';
						$buddypress_item .= mad_mm_common::ntab(3) . '<i class="im-icon-user"></i>';
						$buddypress_item .= mad_mm_common::ntab(2) . '</span><!--  class="item_link" -->';
						$buddypress_item .= mad_mm_common::ntab(2) . '<ul class="mega_dropdown">';
						$buddypress_item .= mad_mm_common::ntab(3) . wp_login_form( array( 'echo' => false ) );
						$buddypress_item .= mad_mm_common::ntab(2) . '</ul><!-- class="mega_dropdown" -->';
						$buddypress_item .= mad_mm_common::ntab(1) . '</li><!-- class="nav_buddypress" -->' . mad_mm_common::ntab(0);
					}
					$items = $items . $buddypress_item;
				}
			}
		}
		return $items;
	}
?>