<?php

/**
 * Custom post type
 */
function aviators_faq_create_post_type() {
	$labels = array(
		'name'               => __( 'FAQs', 'aviators' ),
		'singular_name'      => __( 'FAQ', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New FAQ', 'aviators' ),
		'edit_item'          => __( 'Edit FAQ', 'aviators' ),
		'new_item'           => __( 'New FAQ', 'aviators' ),
		'all_items'          => __( 'All FAQs', 'aviators' ),
		'view_item'          => __( 'View FAQ', 'aviators' ),
		'search_items'       => __( 'Search FAQ', 'aviators' ),
		'not_found'          => __( 'No FAQs found', 'aviators' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'FAQs', 'aviators' ),
	);

	register_post_type( 'faq',
		array(
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', ),
			'public'              => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'categories'          => array( 'faq_categories', ),
			'menu_position'       => 32,
			'menu_icon'           => get_template_directory_uri() . '/aviators/plugins/faq/assets/img/faq.png',
		)
	);
}

add_action( 'init', 'aviators_faq_create_post_type' );


/**
 * Custom taxonomies
 */
function aviators_faq_create_taxonomies() {
	$categories_labels = array(
		'name'              => __( 'Categories', 'aviators' ),
		'singular_name'     => __( 'Category', 'aviators' ),
		'search_items'      => __( 'Search Category', 'aviators' ),
		'all_items'         => __( 'All Categories', 'aviators' ),
		'parent_item'       => __( 'Parent Category', 'aviators' ),
		'parent_item_colon' => __( 'Parent Category:', 'aviators' ),
		'edit_item'         => __( 'Edit Category', 'aviators' ),
		'update_item'       => __( 'Update Category', 'aviators' ),
		'add_new_item'      => __( 'Add New Category', 'aviators' ),
		'new_item_name'     => __( 'New Category', 'aviators' ),
		'menu_name'         => __( 'Category', 'aviators' ),
	);

	register_taxonomy( 'faq_categories', 'faq', array(
		'labels'            => $categories_labels,
		'hierarchical'      => true,
		'query_var'         => 'faq_categories',
		'rewrite'           => 'faq_categories',
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
	) );
}

add_action( 'init', 'aviators_faq_create_taxonomies', 0 );