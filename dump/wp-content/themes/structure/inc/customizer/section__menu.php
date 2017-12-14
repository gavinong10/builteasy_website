<?php
/**
 * ============================================================================
 * Create sections for panel: menu
 * ============================================================================
 */
function register_sections_for_menu_panel( $wp_customize ) {

	$locations     = get_registered_nav_menus();
	$num_locations = count( array_keys( $locations ) );
	//Menu Settings
	$wp_customize->add_section( 'nav', array(
		'title'       => __( 'Menu', 'thememove' ),
		'priority'    => 5,
		'description' => sprintf( _n( 'Your theme supports %s menu. Select which menu you would like to use.', 'Your theme supports %s menus. Select which menu appears in each location.', $num_locations ), number_format_i18n( $num_locations ) ) . "\n\n" . __( 'You can edit your menu content on the Menus screen in the Appearance section.', 'thememove' ),
	) );


}

add_action( 'customize_register', 'register_sections_for_menu_panel' );
