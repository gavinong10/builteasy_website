<?php
add_filter( 'thememove_import_theme', 'thememove_import_theme' );

function thememove_import_theme() {
	return 'Structure';
}

add_filter( 'thememove_import_demos', 'thememove_import_demos' );

function thememove_import_demos() {
	return array(
		'thememove01' => 'Structure',
	);
}

add_filter( 'thememove_import_types', 'thememove_import_types' );

function thememove_import_types() {
	return array(
		'all'                => 'All',
		'content'            => 'Content',
		'widgets'            => 'Widgets',
		'page_options'       => 'Page Options',
		'menus'              => 'Menus',
		'customizer_options' => 'Customizer Options',
		'essential_grid'     => 'Essential Grid',
		'rev_slider'         => 'Revolution Slider',
		'vc_templates'       => 'VC Templates'
	);
}