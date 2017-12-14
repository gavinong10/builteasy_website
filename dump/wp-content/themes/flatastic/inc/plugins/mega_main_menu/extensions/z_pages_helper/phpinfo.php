<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 2.0
 */
	/* 
	 * Functions provide information about PHP configuration.
	 */
	global $mega_main_menu;
	if ( isset( $_GET[ $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page' ] ) && !empty( $_GET[ $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page' ] ) ) {
		if ( $_GET[ $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_page' ] == 'phpinfo' ) {
			echo phpinfo();
			die();
		}
	}
?>