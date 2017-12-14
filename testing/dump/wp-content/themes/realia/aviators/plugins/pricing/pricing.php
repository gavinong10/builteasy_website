<?php

function aviators_pricing_create_post_type() {
	$labels = array(
		'name'               => __( 'Pricings', 'aviators' ),
		'singular_name'      => __( 'Pricing', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New Pricing', 'aviators' ),
		'edit_item'          => __( 'Edit Pricing', 'aviators' ),
		'new_item'           => __( 'New Pricing', 'aviators' ),
		'all_items'          => __( 'All Pricings', 'aviators' ),
		'view_item'          => __( 'View Pricing', 'aviators' ),
		'search_items'       => __( 'Search Pricing', 'aviators' ),
		'not_found'          => __( 'No Pricings found', 'aviators' ),
		'not_found_in_trash' => __( 'No Pricings found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Pricings', 'aviators' ),
	);

	register_post_type( 'pricing',
		array(
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'page-attributes' ),
			'public'              => true,
			'hierarchical'        => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'categories'          => array( 'pricing_categories', ),
			'menu_position'       => 32,
			'menu_icon'           => get_template_directory_uri() . '/aviators/plugins/pricing/assets/img/pricing.png',
		)
	);
}

add_action( 'init', 'aviators_pricing_create_post_type' );