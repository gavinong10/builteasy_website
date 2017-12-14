<?php

if (!class_exists('MAD_META')) {

	class MAD_META {

		function __construct() {
			add_action('init', array(&$this, 'init') );
		}

		public function init() {
			add_filter('rwmb_meta_boxes', array(&$this, 'meta_boxes_array'));
		}

		public function meta_boxes_array($mad_meta_boxes) {

			/*	Meta Box Definitions
			/* ---------------------------------------------------------------------- */

			$mad_prefix = 'mad_';

			/*	Post Format: Quote
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'post-quote-settings',
				'title'    => __('Quote Settings', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('post'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __('The Quote', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'quote',
						'type' => 'textarea',
						'std'  => '',
						'desc' => '',
						'cols' => '40',
						'rows' => '8'
					),
					array(
						'name' => __('The Author', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'quote_author',
						'type' => 'text',
						'std'  => '',
						'desc' => __('(optional)', MAD_BASE_TEXTDOMAIN)
					)
				)
			);

			/*	Layout Settings
			/* ---------------------------------------------------------------------- */

			$mad_pages = get_pages('title_li=&orderby=name');
			$mad_list_pages = array('' => 'None');
			foreach ($mad_pages as $key => $entry) {
				$mad_list_pages[$entry->ID] = $entry->post_title;
			}

			$mad_list_menus = array('' => 'Default');
			$mad_menu_terms = get_terms('nav_menu');
			if ( !empty( $mad_menu_terms ) ) {
				foreach ($mad_menu_terms as $term) {
					$mad_list_menus[$term->term_id] = $term->name;
				}
			}

			$mad_registered_sidebars = MAD_HELPER::get_registered_sidebars(array("" => 'Default Sidebar'), array('General Widget Area'));
			$mad_registered_custom_sidebars = array();

			foreach($mad_registered_sidebars as $key => $value) {
				if (strpos($key, 'Footer Row') === false) {
					$mad_registered_custom_sidebars[$key] = $value;
				}
			}

			$mad_meta_boxes[] = array(
				'id'       => 'layout-settings',
				'title'    => __('Layout', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('post', 'page', 'portfolio', 'product', 'testimonials', 'team-members'),
				'context'  => 'side',
				'priority' => 'default',
				'fields'   => array(
					array(
						'name'    => __('Header Layout', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'header_layout',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Choose header layout', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default', MAD_BASE_TEXTDOMAIN),
							'type-1' => __('Header 1', MAD_BASE_TEXTDOMAIN),
							'type-2' => __('Header 2', MAD_BASE_TEXTDOMAIN),
							'type-3' => __('Header 3', MAD_BASE_TEXTDOMAIN),
							'type-4' => __('Header 4', MAD_BASE_TEXTDOMAIN),
							'type-5' => __('Header 5', MAD_BASE_TEXTDOMAIN),
							'type-6' => __('Header 6', MAD_BASE_TEXTDOMAIN)
						)
					),
					array(
						'name'    => __('Navigation Menu', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'nav_menu',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Choose navigation menu', MAD_BASE_TEXTDOMAIN),
						'options' => $mad_list_menus
					),
					array(
						'name'    => __('After Header Content', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'header_after',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Display content after the header', MAD_BASE_TEXTDOMAIN),
						'options' => $mad_list_pages
					),
					array(
						'name'    => __('Sidebar Position', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'page_sidebar_position',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Choose page sidebar position', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default Sidebar Position', MAD_BASE_TEXTDOMAIN),
							'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN),
							'sbl' => __('Left Sidebar', MAD_BASE_TEXTDOMAIN),
							'sbr' => __('Right Sidebar', MAD_BASE_TEXTDOMAIN)
						)
					),
					array(
						'name'    => __('Sidebar Setting', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'page_sidebar',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Choose a custom sidebar', MAD_BASE_TEXTDOMAIN),
						'options' => $mad_registered_custom_sidebars
					),
					array(
						'name'    => __('Breadcrumb Navigation', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'breadcrumb',
						'type'    => 'select',
						'std'     => 'breadcrumb',
						'desc'    => __('Display the Breadcrumb Navigation?', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'breadcrumb' => __('Display breadcrumbs', MAD_BASE_TEXTDOMAIN),
							'hide' => __('Hide', MAD_BASE_TEXTDOMAIN)
						)
					),
					array(
						'name'    => '',
						'id'      => $mad_prefix . 'page_layout',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Choose page layout style', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default Layout', MAD_BASE_TEXTDOMAIN),
							'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN),
							'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN)
						)
					)
				)
			);

			/*	Body Background
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'body-background',
				'title'    => __('Body Background', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('page'),
				'context'  => 'side',
				'priority' => 'default',
				'fields'   => array(
					array(
						'name'    => __('Background color', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'bg_color',
						'type'    => 'color',
						'std'     => '',
						'desc'    => __('Select the background color of the body', MAD_BASE_TEXTDOMAIN)
					),
					array(
						'name'    => __('Background image', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'bg_image',
						'type'    => 'thickbox_image',
						'std'     => '',
						'desc'    => __('Select the background image', MAD_BASE_TEXTDOMAIN)
					),
					array(
						'name'    => __('Background repeat', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'bg_image_repeat',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Select the repeat mode for the background image', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default', MAD_BASE_TEXTDOMAIN),
							'repeat' => __('Repeat', MAD_BASE_TEXTDOMAIN),
							'no-repeat' => __('No Repeat', MAD_BASE_TEXTDOMAIN),
							'repeat-x' => __('Repeat Horizontally', MAD_BASE_TEXTDOMAIN),
							'repeat-y' => __('Repeat Vertically', MAD_BASE_TEXTDOMAIN)
						)
					),
					array(
						'name'    => __('Background position', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'bg_image_position',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Select the position for the background image', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default', MAD_BASE_TEXTDOMAIN),
							'top left' => __('Top left', MAD_BASE_TEXTDOMAIN),
							'top center' => __('Top center', MAD_BASE_TEXTDOMAIN),
							'top right' => __('Top right', MAD_BASE_TEXTDOMAIN),
							'bottom left' => __('Bottom left', MAD_BASE_TEXTDOMAIN),
							'bottom center' => __('Bottom center', MAD_BASE_TEXTDOMAIN),
							'bottom right' => __('Bottom right', MAD_BASE_TEXTDOMAIN)
						)
					),
					array(
						'name'    => __('Background attachment', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'bg_image_attachment',
						'type'    => 'select',
						'std'     => '',
						'desc'    => __('Select the attachment for the background image ', MAD_BASE_TEXTDOMAIN),
						'options' => array(
							'' => __('Default', MAD_BASE_TEXTDOMAIN),
							'scroll' => __('Scroll', MAD_BASE_TEXTDOMAIN),
							'fixed' => __('Fixed', MAD_BASE_TEXTDOMAIN)
						)
					),
				)
			);

			/*	Team Settings
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'team-settings',
				'title'    => __('Team Settings', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('team-members'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __('Position', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_position',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					)
				)
			);

			$mad_meta_boxes[] = array(
				'id'       => 'team-social-settings',
				'title'    => __('Team Social Links', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('team-members'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __('Facebook', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_facebook',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					),
					array(
						'name' => __('Twitter', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_twitter',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					),
					array(
						'name' => __('Google Plus', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_gplus',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					),
					array(
						'name' => __('Pinterest', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_pinterest',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					),
					array(
						'name' => __('Instagram', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_instagram',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					)
				)
			);

			/*	Testimonials Settings
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'testimonials-settings',
				'title'    => __('Testimonials Settings', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('testimonials'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __('Place', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'tm_place',
						'type' => 'text',
						'std'  => '',
						'desc' => ''
					)
				)
			);

			/*	Portfolio Settings
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'portfolio-settings',
				'title'    => __('Portfolio Slider Settings', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('portfolio'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __('Portfolio Slider Images', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'portfolio_images',
						'type' => 'image_advanced',
						'std'  => '',
						'desc' => __('Upload portfolio single images come here', MAD_BASE_TEXTDOMAIN)
					),
					array(
						'name' => __('Slideshow', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'flex_slideshow',
						'type' => 'checkbox',
						'std'  => '1',
						'desc' => __('Boolean: Animate slider automatically', MAD_BASE_TEXTDOMAIN)
					),
					array(
						'name' => __('Slideshow speed', MAD_BASE_TEXTDOMAIN),
						'id'   => $mad_prefix . 'flex_slideshow_speed',
						'type' => 'number',
						'std'  => 5000,
						'step' => 10,
						'desc' => __('Integer: Set the speed of the slideshow cycling, in milliseconds', MAD_BASE_TEXTDOMAIN)
					)
				)
			);

			/*	Masonry Settings
			/* ---------------------------------------------------------------------- */

			$mad_meta_boxes[] = array(
				'id'       => 'masonry-settings',
				'title'    => __('Masonry Settings', MAD_BASE_TEXTDOMAIN),
				'pages'    => array('portfolio'),
				'context'  => 'side',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name'    => __('Masonry Size', MAD_BASE_TEXTDOMAIN),
						'id'      => $mad_prefix . 'masonry_size',
						'type'    => 'select',
						'options' => array(
							'masonry-big' => __('Masonry Big Size', MAD_BASE_TEXTDOMAIN),
							'masonry-medium' => __('Masonry Medium Size', MAD_BASE_TEXTDOMAIN),
							'masonry-small' => __('Masonry Small Size', MAD_BASE_TEXTDOMAIN),
							'masonry-long' => __('Masonry Long Size', MAD_BASE_TEXTDOMAIN)
						),
						'std'     => 'masonry-medium',
						'desc'    => __('Choose width for masonry portfolio', MAD_BASE_TEXTDOMAIN)
					),
				)
			);

			/*	Product Settings
			/* ---------------------------------------------------------------------- */

//			$mad_meta_boxes[] = array(
//				'id' => $mad_prefix . 'product_custom_meta_box',
//				'title' => __('Custom Tab Options', MAD_BASE_TEXTDOMAIN),
//				'pages' => array('product'),
//				'context' => 'normal',
//				'priority' => 'high',
//				'fields' => array(
//					array(
//						'name' => __('', MAD_BASE_TEXTDOMAIN),
//						'id' => $mad_prefix . 'title_product_tab',
//						'type' => 'text',
//						'desc' => __('Title Custom Tab',  MAD_BASE_TEXTDOMAIN),
//						'std' => '',
//					),
//					array(
//						'name' => __('', MAD_BASE_TEXTDOMAIN),
//						'id' => $mad_prefix . 'content_product_tab',
//						'desc' => __('Content Custom Tab',  MAD_BASE_TEXTDOMAIN),
//						'std' => '',
//						'type' => 'wysiwyg'
//					)
//				)
//			);

			return $mad_meta_boxes;
		}

	}

	new MAD_META;
}