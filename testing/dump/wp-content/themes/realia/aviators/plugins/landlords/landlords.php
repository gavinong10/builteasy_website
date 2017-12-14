<?php

require_once get_template_directory() . '/aviators/plugins/landlords/utils.php';


/**
 * Meta options for custom post type
 */
$landlord_metabox = new WPAlchemy_MetaBox( array(
	'id'       => '_landlord_meta',
	'title'    => __( 'Landlord', 'aviators' ),
	'template' => AVIATORS_DIR . '/plugins/landlords/meta.php',
	'types'    => array( 'landlord', ),
	'prefix'   => '_landlord_',
	'mode'     => WPALCHEMY_MODE_EXTRACT,
) );


/**
 * Custom post type
 */
function aviators_landlords_create_post_type() {
	$labels = array(
		'name'               => __( 'Landlords', 'aviators' ),
		'singular_name'      => __( 'Landlord', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New Landlord', 'aviators' ),
		'edit_item'          => __( 'Edit Landlord', 'aviators' ),
		'new_item'           => __( 'New Landlord', 'aviators' ),
		'all_items'          => __( 'All Landlords', 'aviators' ),
		'view_item'          => __( 'View Landlord', 'aviators' ),
		'search_items'       => __( 'Search Landlord', 'aviators' ),
		'not_found'          => __( 'No landlords found', 'aviators' ),
		'not_found_in_trash' => __( 'No landlords found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Landlords', 'aviators' ),
	);

	register_post_type( 'landlord',
		array(
			'labels'        => $labels,
			'supports'      => array( 'title', 'editor' ),
			'public'        => false,
			'show_ui'       => true,
			'rewrite'       => false,
			'menu_position' => 32,
			'menu_icon'     => get_template_directory_uri() . '/aviators/plugins/landlords/assets/img/landlords.png',
		)
	);
}

add_action( 'init', 'aviators_landlords_create_post_type' );