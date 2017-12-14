<?php

require_once get_template_directory() . '/aviators/plugins/agencies/utils.php';

require_once 'widgets/agencies.php';
require_once 'widgets/assigned_agencies.php';


/**
 * Creates agent custom post type
 */
function aviators_agencies_create_post_type() {
	$labels = array(
		'name'               => __( 'Agencies', 'aviators' ),
		'singular_name'      => __( 'Agency', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New Agency', 'aviators' ),
		'edit_item'          => __( 'Edit Agency', 'aviators' ),
		'new_item'           => __( 'New Agency', 'aviators' ),
		'all_items'          => __( 'All Agencies', 'aviators' ),
		'view_item'          => __( 'View Agency', 'aviators' ),
		'search_items'       => __( 'Search Agency', 'aviators' ),
		'not_found'          => __( 'No agencies found', 'aviators' ),
		'not_found_in_trash' => __( 'No agencies found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Agencies', 'aviators' ),
	);

	register_post_type( 'agency',
		array(
			'labels'        => $labels,
			'rewrite'       => array(
				'slug' 		=> __( 'agencies', 'aviators' ),
			),
			'hierarchical'  => true,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 32,
			'menu_icon'     => get_template_directory_uri() . '/aviators/plugins/agencies/assets/img/agencies.png',
		)
	);
}

add_action( 'init', 'aviators_agencies_create_post_type' );

/**
 * Meta options for custom post type
 */
$agent_metabox = new WPAlchemy_MetaBox( array(
	'id'       => '_agency_meta',
	'title'    => __( 'Agency Options', 'aviators' ),
	'template' => get_template_directory() . '/aviators/plugins/agencies/meta.php',
	'types'    => array( 'agency', ),
	'prefix'   => '_agency_',
	'mode'     => WPALCHEMY_MODE_EXTRACT,
) );