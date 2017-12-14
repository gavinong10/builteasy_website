<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	if ( is_admin() ) {
		global $mega_main_menu;
		$menu_locations = ( is_array( get_nav_menu_locations() ) ) ? get_nav_menu_locations() : array();


		$nav_menu_selected_id = ( isset( $_REQUEST['menu'] ) 
			? (int) $_REQUEST['menu'] 
			: ( ( get_user_option( 'nav_menu_recently_edited' ) != false ) 
				? absint( get_user_option( 'nav_menu_recently_edited' ) )
				: 0
			)
		);
		$current_menu_location = array_search( $nav_menu_selected_id, $menu_locations );
        $self_current_menu_location = str_replace( ' ', '-', $current_menu_location );
		$mega_menu_locations = $mega_main_menu->get_option( 'mega_menu_locations' );

		if ( ( is_array( $mega_menu_locations ) && ( in_array( $self_current_menu_location, $mega_menu_locations ) ) ) || ( is_array( $mega_main_menu->get_option( 'indefinite_location_mode' ) ) && in_array( 'true', $mega_main_menu->get_option( 'indefinite_location_mode' ) ) ) ) {
			include_once( 'menu_options_array.php' );
			include_once( 'backend_walker.php' );
		}
	} else {
		include_once( 'frontend_walker.php' );
		include_once( 'handler.php' );

		/** 
		 * register link to MM options in admin toolbar.
		 * @return void
		 */
		/*
		add_action( 'admin_bar_menu', 'toolbar_link_to_mm_options', 10 );
		function toolbar_link_to_mm_options( $wp_admin_bar ) {
			global $mega_main_menu;
			$args = array(
				'parent' => 'site-name',
				'id' => $mega_main_menu->constant[ 'MM_OPTIONS_NAME' ],
				'title' => $mega_main_menu->constant[ 'MM_WARE_NAME' ],
				'href' => get_admin_url() . 'admin.php?page=' . $mega_main_menu->constant[ 'MM_OPTIONS_NAME' ],
			);
			$wp_admin_bar->add_node( $args );
		}
		 */
	}
?>