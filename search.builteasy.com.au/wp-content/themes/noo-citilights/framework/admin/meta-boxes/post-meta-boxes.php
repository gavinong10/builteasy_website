<?php
/**
 * NOO Meta Boxes Package
 *
 * Setup NOO Meta Boxes for Post
 * This file add Meta Boxes to WP Post edit page.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_post_meta_boxes')):
	function noo_post_meta_boxes() {
		// Declare helper object
		$prefix = '_noo_wp_post';
		$helper = new NOO_Meta_Boxes_Helper($prefix, array(
			'page' => 'post'
		));
		// Post type: Image
		$meta_box = array(
			'id' => "{$prefix}_meta_box_image",
			'title' => __('Image Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_main_image",
					'label' => __('Main Image', 'noo'),
					'type' => 'radio',
					'std' => 'featured',
					'options' => array(
						array(
							'label' => __('Featured Image', 'noo'),
							'value' => 'featured',
						),
						array(
							'label' => __('Choose Your Image', 'noo'),
							'value' => 'image',
						),
					),
					'child-fields' => array(
						'image' => "{$prefix}_image,{$prefix}_image_preview",
					),
				),
				array(
					'id' => "{$prefix}_image",
					'label' => __('Your Image', 'noo'),
					'type' => 'image',
				),
				array(
					'type' => 'divider',
				),
				array(
					'id' => "{$prefix}_image_preview",
					'label' => __('Preview Content', 'noo'),
					'type' => 'radio',
					'std' => 'image',
					'options' => array(
						array(
							'label' => __('Featured Image', 'noo'),
							'value' => 'featured',
						),
						array(
							'label' => __('Your Image', 'noo'),
							'value' => 'image',
						),
					)
				)
			)
		);

		// // Add dimension for image preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_image_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);
		// Post type: Gallery
		$meta_box = array(
			'id' => "{$prefix}_meta_box_gallery",
			'title' => __('Gallery Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_gallery",
					// 'label' => __( 'Your Gallery', 'noo' ),
					'type' => 'gallery',
				),
				array(
					'type' => 'divider',
				),
				array(
					'id' => "{$prefix}_gallery_preview",
					'label' => __('Preview Content', 'noo'),
					'type' => 'radio',
					'std' => 'featured',
					'options' => array(
						array(
							'label' => __('Featured Image', 'noo'),
							'value' => 'featured',
						),
						array(
							'label' => __('First Image on Gallery', 'noo'),
							'value' => 'first_image',
						),
						array(
							'label' => __('Image Slideshow', 'noo'),
							'value' => 'slideshow',
						),
					)
				)
			)
		);

		// // Add dimension for gallery preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_gallery_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);
		// Post type: Video
		$meta_box = array(
			'id' => "{$prefix}_meta_box_video",
			'title' => __('Video Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_video_m4v",
					'label' => __('M4V File URL', 'noo'),
					'desc' => __('Place the URL to your .m4v video file here.', 'noo'),
					'type' => 'text',
				),
				array(
					'id' => "{$prefix}_video_ogv",
					'label' => __('OGV File URL', 'noo'),
					'desc' => __('Place the URL to your .ogv video file here.', 'noo'),
					'type' => 'text',
				),
				array(
					'id' => "{$prefix}_video_embed",
					'label' => __('Embedded Video Code', 'noo'),
					'desc' => __('If you are using videos from online sharing sites (YouTube, Vimeo, etc.) paste the embedded code here. This field will override the above settings.', 'noo'),
					'type' => 'textarea',
					'std' => ''
				),
				array(
					'id' => "{$prefix}_video_ratio",
					'label' => __('Video Aspect Ratio', 'noo'),
					'desc' => __('Choose the aspect ratio for your video.', 'noo'),
					'type' => 'select',
					'std' => '16:9',
					'options' => array(
						array('value'=>'16:9','label'=>'16:9'),
						array('value'=>'5:3','label'=>'5:3'),
						array('value'=>'5:4','label'=>'5:4'),
						array('value'=>'4:3','label'=>'4:3'),
						array('value'=>'3:2','label'=>'3:2')
					)
				),
				array(
					'type' => 'divider',
				),
				array(
					'label' => __('Preview Content', 'noo'),
					'id' => "{$prefix}_video_preview",
					'type' => 'radio',
					'std' => 'both',
					'options' => array(
						array(
							'label' => __('Featured Image', 'noo'),
							'value' => 'featured',
						),
						array(
							'label' => __('Video', 'noo'),
							'value' => 'video',
						),
						array(
							'label' => __('Both (use Featured Image as Thumbnail for Video)', 'noo'),
							'value' => 'both',
						),
					)
				)
			)
		);

		// // Add dimension for video preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_video_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);
		// Post type: Link
		$meta_box = array(
			'id' => "{$prefix}_meta_box_link",
			'priority' => 'core',
			'title' => __('Link Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_link",
					'label' => __('The Link', 'noo'),
					'type' => 'text',
					'std' => 'http://nootheme.com',
				)
			)
		);

		// // Add dimension for link preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_link_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);
		// Post type: Quote
		$meta_box = array(
			'id' => "{$prefix}_meta_box_quote",
			'title' => __('Quote Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_quote",
					'label' => __('The Quote', 'noo'),
					'desc' => __('Input your quote.', 'noo'),
					'type' => 'textarea',
				),
				array(
					'id' => "{$prefix}_quote_citation",
					'label' => __('Citation', 'noo'),
					'desc' => __('Who originally said the quote?', 'noo'),
					'type' => 'text',
				)
			)
		);

		// // Add dimension for quote preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_quote_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);
		// Post type: Audio
		$meta_box = array(
			'id' => "{$prefix}_meta_box_audio",
			'title' => __('Audio Settings', 'noo'),
			'fields' => array(
				array(
					'id' => "{$prefix}_audio_mp3",
					'label' => __('MP3 File URL', 'noo'),
					'desc' => __('Place the URL to your .mp3 audio file here.', 'noo'),
					'type' => 'text',
				),
				array(
					'id' => "{$prefix}_audio_oga",
					'label' => __('OGA File URL', 'noo'),
					'desc' => __('Place the URL to your .oga audio file here.', 'noo'),
					'type' => 'text',
				),
				array(
					'id' => "{$prefix}_audio_embed",
					'label' => __('Embedded Audio Code', 'noo'),
					'desc' => __('If you are using videos from online sharing sites (like Soundcloud) paste the embedded code here. This field will override above settings.', 'noo'),
					'type' => 'textarea',
					'std' => ''
				)
			)
		);

		// // Add dimension for audio preview
		// if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
		// 	$meta_box['fields'][] = array(
		// 		'id' => "{$prefix}_masonry_audio_size",
		// 		'label'    => __( 'Masonry Item Size', 'noo' ),
		// 		'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
		// 		'type'    => 'radio',
		// 		'std'     => 'regular',
		// 		'options' => array(
		// 			array(
		// 				'label' => __( 'Normal', 'noo' ),
		// 				'value' => 'regular'
		// 			),
		// 			array(
		// 				'label' => __( 'Big (double)', 'noo' ),
		// 				'value' => 'wide'
		// 			)
		// 		)
		// 	);
		// }
		
		$helper->add_meta_box($meta_box);


		// Add dimension for masonry style
		if(noo_get_option('noo_blog_style', 'standard') == 'masonry' || noo_get_option('noo_blog_archive_style', 'same_as_blog') == 'masonry') {
			$meta_box = array(
				'id' => "{$prefix}_meta_box_mansory",
				'title' => __('Mansory Style', 'noo'),
				'fields' => array(
					array(
						'id' => "{$prefix}_masonry_item_size",
						'label'    => __( 'Masonry Item Size', 'noo' ),
						'desc'    => __( 'Choose the width for your preview content.', 'noo' ),
						'type'    => 'radio',
						'std'     => 'regular',
						'options' => array(
							array(
								'label' => __( 'Normal', 'noo' ),
								'value' => 'regular'
							),
							array(
								'label' => __( 'Big (double)', 'noo' ),
								'value' => 'wide'
							)
						)
					)
				)
			);

			$helper->add_meta_box($meta_box);
		}

		// Page Settings: Single Post
		$meta_box = array(
			'id' => "{$prefix}_meta_box_single_page",
			'title' => __('Page Settings: Single Post', 'noo'),
			'description' => __('Choose various setting for your Single Post page.', 'noo'),
			'fields' => array(
				array(
					'label' => __('Body Custom CSS Class', 'noo'),
					'id' => "_noo_body_css",
					'type' => 'text',
				),
				array(
					'type' => 'divider'
				),
				array(
					'label' => __('Page Layout', 'noo'),
					'id' => "{$prefix}_global_setting",
					'type' => 'page_layout',
				),
				array(
					'label' => __('Override Global Settings?', 'noo'),
					'id' => "{$prefix}_override_layout",
					'type' => 'checkbox',
					'child-fields' => array(
						'on' => "{$prefix}_layout,{$prefix}_sidebar"
					),
				),
				array(
					'label' => __('Page Layout', 'noo'),
					'id' => "{$prefix}_layout",
					'type' => 'radio',
					'std' => 'sidebar',
					'options' => array(
						'fullwidth' => array(
							'label' => __('Full-Width', 'noo'),
							'value' => 'fullwidth',
						),
						'sidebar' => array(
							'label' => __('With Right Sidebar', 'noo'),
							'value' => 'sidebar',
						),
						'left_sidebar' => array(
							'label' => __('With Left Sidebar', 'noo'),
							'value' => 'left_sidebar',
						),
					),
					// 'child-fields' => array(
					// 	'sidebar' => "{$prefix}_sidebar",
					// 	'left_sidebar' => "{$prefix}_sidebar",
					// ),
					
				),
				array(
					'label' => __('Post Sidebar', 'noo'),
					'id' => "{$prefix}_sidebar",
					'type' => 'sidebars',
					'std' => 'sidebar-main'
				),
			)
		);

		$helper->add_meta_box( $meta_box );
	}
	
endif;

add_action('add_meta_boxes', 'noo_post_meta_boxes');
