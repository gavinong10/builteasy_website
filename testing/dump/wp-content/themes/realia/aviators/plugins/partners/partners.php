<?php

require_once get_template_directory() . '/aviators/plugins/partners/utils.php';

require_once get_template_directory() . '/aviators/plugins/partners/widgets/partners.php';

/**
 * Custom post type
 */
function aviators_partners_create_post_type() {
	$labels = array(
		'name'               => __( 'Partners', 'aviators' ),
		'singular_name'      => __( 'Partner', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New Partner', 'aviators' ),
		'edit_item'          => __( 'Edit Partner', 'aviators' ),
		'new_item'           => __( 'New Partner', 'aviators' ),
		'all_items'          => __( 'All Partners', 'aviators' ),
		'view_item'          => __( 'View Partner', 'aviators' ),
		'search_items'       => __( 'Search Partner', 'aviators' ),
		'not_found'          => __( 'No Partners found', 'aviators' ),
		'not_found_in_trash' => __( 'No Partners found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Partners', 'aviators' ),
	);

	register_post_type( 'partner',
		array(
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', ),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'menu_position'       => 32,
			'menu_icon'           => get_template_directory_uri() . '/aviators/plugins/partners/assets/img/partners.png',
		)
	);
}

add_action( 'init', 'aviators_partners_create_post_type' );

/**
 * Meta options for custom post type
 */
$partner_metabox = new WPAlchemy_MetaBox( array(
	'id'       => '_partner_meta',
	'title'    => __( 'Partner Options', 'aviators' ),
	'template' => get_template_directory() . '/aviators/plugins/partners/meta.php',
	'types'    => array( 'partner', ),
	'prefix'   => '_partner_',
	'mode'     => WPALCHEMY_MODE_EXTRACT,
) );