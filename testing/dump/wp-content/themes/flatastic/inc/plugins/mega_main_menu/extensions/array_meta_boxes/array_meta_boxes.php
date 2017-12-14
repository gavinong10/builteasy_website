<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	if ( !function_exists('mega_main_menu__array_meta_boxes') ) {
		function mega_main_menu__array_meta_boxes( $constants ){
			return array(
				array( // START single element params array
					'key' => 'mm_general', // HTML atribute "id" for metabox
					'title' => __( 'Mega Main Options', $constants[ 'MM_TEXTDOMAIN_ADMIN'] ), // Caption for metabox
					'post_type' => 'all_post_types', // Post types where will be displayed this metabox
					'context' => 'normal', // Position where will be displayed this metabox
					'priority' => 'high', // Priority for this metabox
					'options' => array(
						array(
							'name' => __( 'Post Icon', $constants[ 'MM_TEXTDOMAIN_ADMIN'] ),
							'descr' => __( 'Select icon for this post, which will be displayed in the "Post Grid Dropdown Menu"', $constants[ 'MM_TEXTDOMAIN_ADMIN'] ),
							'key' => 'post_icon',
							'default' => 'im-icon-plus-circle-2',
							'type' => 'icons',
						),
					), // END element options
				), // END single element params array
			);
		}
	}
?>