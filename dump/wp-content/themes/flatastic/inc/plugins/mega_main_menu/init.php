<?php
/**
 * @package Mega Main Menu
 * @version 2.0.3
 * Plugin Name: Mega Main Menu
 * Plugin URI: http://menu.megamain.com
 * Description: Multifunctional and responsive menu. Features: icons, dropdowns, sticky menu, custom styles, images, google fonts. All in one...
 * Version: 2.0.3
 * Author: MegaMain.com
 * Author URI: http://megamain.com
 */

	include_once( 'framework/init.php' );
	$mad_mm_config = array(
		'MM_WARE_NAME' => 'Mega Main Menu',
		'MM_WARE_SLUG' => 'mega_main_menu',
		'MM_WARE_PREFIX' => 'mmm',
		'MM_WARE_VERSION' => '2.0.3',
		'MM_WARE_INIT_FILE' => __FILE__,
	);
	new mad_mega_main_init( $mad_mm_config );
?>
