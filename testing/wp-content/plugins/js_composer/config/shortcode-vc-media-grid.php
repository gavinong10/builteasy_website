<?php
$media_grid_params = array_merge( array(
	array(
		'type' => 'attach_images',
		'heading' => __( 'Images', 'js_composer' ),
		'param_name' => 'include',
		'description' => __( 'Select images from media library.', 'js_composer' ),
	),
	$grid_params[5],
	$grid_params[6],
	$grid_params[8],
	$grid_params[9],
	$grid_params[21],
	$grid_params[22],
	$grid_params[23],
	$grid_params[24],
	$grid_params[25],
	$grid_params[26],
	$grid_params[27],
	$grid_params[28],
	$grid_params[29],
	//$grid_params[30],
	$grid_params[31],
	array(
		'type' => 'vc_grid_item',
		'heading' => __( 'Grid element template', 'js_composer' ),
		'param_name' => 'item',
		'description' => sprintf( __( '%sCreate new%s template or %smodify selected%s. Predefined templates will be cloned.', 'js_composer' ), '<a href="'
		. esc_url( admin_url( 'post-new.php?post_type=vc_grid_item' ) ) . '" target="_blank">', '</a>', '<a href="#" target="_blank" data-vc-grid-item="edit_link">', '</a>' ),
		'group' => __( 'Item Design', 'js_composer' ),
		'value' => 'mediaGrid_Default',
	),
	array(
		'type' => 'vc_grid_id',
		'param_name' => 'grid_id',
	),
	array(
		'type' => 'css_editor',
		'heading' => __( 'CSS box', 'js_composer' ),
		'param_name' => 'css',
		'group' => __( 'Design Options', 'js_composer' ),
	),
	), $btn3_params, array(
		// Load more btn bc
		array(
			'type' => 'hidden',
			'heading' => __( 'Button style', 'js_composer' ),
			'param_name' => 'button_style',
			'value' => '',
			'param_holder_class' => 'vc_colored-dropdown',
			'group' => __( 'Load More Button', 'js_composer' ),
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'load-more' ),
			),
			'description' => __( 'Select button style.', 'js_composer' ),
		),
		array(
			'type' => 'hidden',
			'heading' => __( 'Button color', 'js_composer' ),
			'param_name' => 'button_color',
			'value' => '',
			'param_holder_class' => 'vc_colored-dropdown',
			'group' => __( 'Load More Button', 'js_composer' ),
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'load-more' ),
			),
			'description' => __( 'Select button color.', 'js_composer' ),
		),
		array(
			'type' => 'hidden',
			'heading' => __( 'Button size', 'js_composer' ),
			'param_name' => 'button_size',
			'value' => '',
			'description' => __( 'Select button size.', 'js_composer' ),
			'group' => __( 'Load More Button', 'js_composer' ),
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'load-more' ),
			),
		),
	)
);
$media_grid_params[4]['std'] = '5';
vc_map( array(
	'name' => __( 'Media Grid', 'js_composer' ),
	'base' => 'vc_media_grid',
	'icon' => 'vc_icon-vc-media-grid',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Media grid from Media Library', 'js_composer' ),
	'params' => $media_grid_params,
) );
