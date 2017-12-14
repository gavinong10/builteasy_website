<?php

require_once AVIATORS_DIR . '/plugins/submission/utils.php';
require_once AVIATORS_DIR . '/plugins/submission/renderers.php';


/**
 * Meta options for custom post type
 */
$transaction_metabox = new WPAlchemy_MetaBox( array(
	'id'       => '_transaction_meta',
	'title'    => __( 'Transaction', 'aviators' ),
	'template' => AVIATORS_DIR . '/plugins/submission/meta.php',
	'types'    => array( 'transaction' ),
	'prefix'   => '_transaction_',
	'mode'     => WPALCHEMY_MODE_EXTRACT,
) );

/**
 * Custom post type
 */
add_action( 'init', 'aviators_submission_create_post_type' );
function aviators_submission_create_post_type() {
	$labels = array(
		'name'               => __( 'Transactions', 'aviators' ),
		'singular_name'      => __( 'Transaction', 'aviators' ),
		'add_new'            => __( 'Add New', 'aviators' ),
		'add_new_item'       => __( 'Add New Transaction', 'aviators' ),
		'edit_item'          => __( 'Edit Transaction', 'aviators' ),
		'new_item'           => __( 'New Transaction', 'aviators' ),
		'all_items'          => __( 'All Transactions', 'aviators' ),
		'view_item'          => __( 'View Transaction', 'aviators' ),
		'search_items'       => __( 'Search Transaction', 'aviators' ),
		'not_found'          => __( 'No transactions found', 'aviators' ),
		'not_found_in_trash' => __( 'No Transaction found in Trash', 'aviators' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Transactions', 'aviators' ),
	);

	register_post_type( 'transaction',
		array(
			'labels'              => $labels,
			'rewrite'             => false,
			'supports'            => array(),
			'public'              => false,
			'query_var'           => false,
			'show_ui'             => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'menu_position'       => 32,
			'menu_icon'           => get_template_directory_uri() . '/aviators/plugins/submission/assets/img/transactions.png',
		)
	);
}
