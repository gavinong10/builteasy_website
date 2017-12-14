<?php

/**
 * holds configuration for all landing page templates
 * these values are loaded in the edit mode of the post, and injected in javascript
 */
/*

documentation on each key:
    [REQUIRED] name => the user-friendly name for the template
    [REQUIRED] tags => an array of keywords that will allow for easier searches (can be empty)
    extended_dropzone_elements => selector for elements that should contain a dropzone if they have no children
    fonts => array of links to custom fonts to include in the <head> section
    custom_color_mappings => extra color pickers to display for the main content area
    icons => an array of icon classes to be merged with (possible) existing icons - use this if the template has some custom icons created with fonts
    has_lightbox => boolean indicating if a lightbox should automatically be created for this landing page
    lightbox => array of lightbox settings. for now: (
        max_width => {val}px
        max_height => {val}px
    hidden_menu_items => array of keys that allows hiding some controls from the Main Content Area menu. possible keys:
        bg_color
        bg_pattern
        bg_image
        max_width
        bg_static
        bg_full_height
        border_radius
    style_family => the default style family for the template. accepted values: Flat | Minimal | Classy
*/

return array(
	'blank'                                     => array(
		'name'                       => 'Blank Page', //required
		'tags'                       => array(),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'opacity'        => 1,
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'opacity'        => 1,
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'opacity'        => 1,
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Flat',
	),
	'author-focused-homepage'                   => array(
		'name'                       => 'Author Focused Homepage', //required
		'tags'                       => array( 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '480px'
		),
		'style_family'               => 'Flat',
	),
	'offer-focused-homepage'                    => array(
		'name'                       => 'Offer Focused Homepage', //required
		'tags'                       => array( 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'offerfocused-icon-arrow'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '480px'
		),
		'style_family'               => 'Flat',
	),
	'content-focused-homepage'                  => array(
		'name'                       => 'Content Focused Homepage', //required
		'tags'                       => array( 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300',
			'//fonts.googleapis.com/css?family=Montserrat:400,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'contentfocused-icon-arrow',
			'offerfocused-icon-speaker'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '480px'
		),
		'style_family'               => 'Classy',
	),
	'hybrid-homepage1'                          => array(
		'name'                       => 'Hybrid Homepage 1', //required
		'tags'                       => array( 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300',
			'//fonts.googleapis.com/css?family=Montserrat:400,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'hybrid-icon-chart',
			'hybrid-icon-speaker',
			'hybrid-icon-ribbon'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '480px'
		),
		'style_family'               => 'Flat',
	),
	'hybrid-homepage2'                          => array(
		'name'                       => 'Hybrid Homepage 2', //required
		'tags'                       => array( 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,300,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'hybrid-icon-chart',
			'hybrid-icon-speaker',
			'hybrid-icon-ribbon'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '480px'
		),
		'style_family'               => 'Flat',
	),
	'copy-2-coming-soon'                        => array(
		'name'                       => 'Copy 2.0 Coming Soon Page', //required
		'tags'                       => array( 'lead generation', '1-step', 'coming soon' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-download-page'                      => array(
		'name'                       => 'Copy 2.0 Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'copy2-icon-book'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-email-confirmation'                 => array(
		'name'                       => 'Copy 2.0 Email Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-product-launch'                     => array(
		'name'                       => 'Copy 2.0 Product Launch Page', //required
		'tags'                       => array( 'product launch', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '1080px',
			'max_height' => '550px'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-lead-generation'                    => array(
		'name'                       => 'Copy 2.0 Lead Generation Page', //required
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-lead-generation-2step'              => array(
		'name'                       => 'Copy 2.0 2-Step Lead Generation Page', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '1080px',
			'max_height' => '550px'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-sold-out'                           => array(
		'name'                       => 'Copy 2.0 Sold Out Page', //required
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-video-sales-page'                   => array(
		'name'                       => 'Copy 2.0 Video Sales Page', //required
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-hybrid-sales-image'                 => array(
		'name'                       => 'Copy 2.0 Hybrid Sales Page (Image Version)', //required
		'tags'                       => array( 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'copy2-icon-paperplane',
			'copy2-icon-speaker',
			'copy2-icon-idea'
		),
		'style_family'               => 'Classy',
	),
	'copy-2-hybrid-sales-video'                 => array(
		'name'                       => 'Copy 2.0 Hybrid Sales Page (Video Version)', //required
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Gentium+Basic:400,700,400italic',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'copy2-icon-paperplane',
			'copy2-icon-speaker',
			'copy2-icon-idea'
		),
		'style_family'               => 'Classy',
	),
	'phonic-bonus-episode-optin'                => array(
		'name'                       => 'Phonic Podcast Bonus Episode Opt-In Page', //required
		'tags'                       => array( 'lead generation', '2-step', 'video', 'podcast' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '470px',
			'max_height' => '600px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-bonus-episode-download'             => array(
		'name'                       => 'Phonic Podcast Bonus Episode Download Page', //required
		'tags'                       => array( 'podcast', 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-download1'                  => array(
		'name'                       => 'Phonic Podcast Download Page 1', //required
		'tags'                       => array( 'podcast', 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-download2'                  => array(
		'name'                       => 'Phonic Podcast Download Page 2', //required
		'tags'                       => array( 'podcast', 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-email-first-landing-page'           => array(
		'name'                       => 'Phonic Podcast “Email First Landing Page', //required
		'tags'                       => array( 'lead generation', 'podcast', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-email-first-download-page'          => array(
		'name'                       => 'Phonic Podcast “Email First Download Page', //required
		'tags'                       => array( 'lead generation', 'podcast', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-email-confirmation-page'            => array(
		'name'                       => 'Phonic Email Confirmation Page', //required
		'tags'                       => array( 'lead generation', 'podcast', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'phonic-icon-email-download',
			'phonic-icon-email-open',
			'phonic-icon-email-click'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-itunes'                     => array(
		'name'                       => 'Phonic Podcast iTunes Landing Page', //required
		'tags'                       => array( 'podcast', 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'phonic-icon-arrowright'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '760px',
			'max_height' => '500px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-soundcloud'                 => array(
		'name'                       => 'Phonic Podcast SoundCloud Landing Page', //required
		'tags'                       => array( 'podcast', 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'phonic-icon-arrowright'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '760px',
			'max_height' => '500px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-stitcher'                   => array(
		'name'                       => 'Phonic Podcast Stitcher Landing Page', //required
		'tags'                       => array( 'podcast', 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '760px',
			'max_height' => '500px'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'phonic-icon-arrowright'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-podcast-subscription'               => array(
		'name'                       => 'Phonic Podcast Subscription Page', //required
		'tags'                       => array( 'podcast', 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'phonic-icon-email-download',
			'phonic-icon-email-open',
			'phonic-icon-email-click'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '760px',
			'max_height' => '500px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'phonic-universal-podcast'                  => array(
		'name'                       => 'Phonic Universal Podcast Landing Page', //required
		'tags'                       => array( 'podcast', 'lead generation', '2-step', 'homepage' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,400,700,500,500italic',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,800,700,400,300'
		),
		'icons'                      => array(
			'phonic-icon-arrow',
			'thrv-icon-forward'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '760px',
			'max_height' => '500px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'confluence-webinar-registration'           => array(
		'name'                       => 'Confluence Webinar Registration Page', //required
		'tags'                       => array( 'webinar', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '750px',
			'max_height' => '400px'
		),
		'style_family'               => 'Minimal',
	),
	'confluence-double-whammy-webinar'          => array(
		'name'                       => 'Confluence Double Whammy Webinar Page', //required
		'tags'                       => array( 'webinar', '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '750px',
			'max_height' => '400px'
		),
		'style_family'               => 'Minimal',
	),
	/*'confluence-live-streaming-page' => array(
        'name' => 'Confluence Live Streaming Page', //required
        'tags' => array('webinar', 'video'),
        'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
        'fonts' => array(
            '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
            '//fonts.googleapis.com/css?family=PT+Sans:700'
        ),
        'hidden_menu_items' => array(
            'max_width', 'bg_full_height', 'border_radius'
        ),
        'style_family' => 'Minimal',
    ),*/
	'confluence-webinar-ended-template'         => array(
		'name'                       => 'Confluence Webinar Ended Template', //required
		'tags'                       => array( 'webinar', '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '750px',
			'max_height' => '400px'
		),
		'style_family'               => 'Minimal',
	),
	/*'confluence-webinar-replay' => array(
        'name' => 'Confluence Webinar Replay Page', //required
        'tags' => array('webinar', 'video'),
        'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
        'fonts' => array(
            '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
            '//fonts.googleapis.com/css?family=PT+Sans:700'
        ),
        'hidden_menu_items' => array(
            'max_width', 'bg_full_height', 'border_radius'
        ),
        'has_lightbox' => true,
        'lightbox' => array(
            'max_width' => '750px',
            'max_height' => '400px'
        ),
        'style_family' => 'Minimal',
    ),*/
	'confluence-email-confirmation'             => array(
		'name'                       => 'Confluence Email Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'confluence-thank-you'                      => array(
		'name'                       => 'Confluence Webinar Thank You Page', //required
		'tags'                       => array( 'webinar' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'confluence-icon-edit',
			'confluence-icon-calendar',
			'confluence-icon-bell'
		),
		'style_family'               => 'Minimal',
	),
	'confluence-thank-you-download'             => array(
		'name'                       => 'Confluence Webinar Thank You Page + Download', //required
		'tags'                       => array( 'webinar', 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300',
			'//fonts.googleapis.com/css?family=PT+Sans:700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'confluence-icon-edit',
			'confluence-icon-calendar',
			'confluence-icon-bell',
			'confluence-icon-pdf'
		),
		'style_family'               => 'Minimal',
	),
	'review-page'                               => array(
		'name'                       => 'Review Page', //required
		'tags'                       => array( 'review' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,300,700,500,500italic',
			'//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700,700italic',
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
		'icons'                      => array(
			'review-page-icon-arrow'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '800px',
			'max_height' => '450px'
		)
	),
	'review-comparison-page'                    => array(
		'name'                       => 'Review Comparison Page', //required
		'tags'                       => array( 'review' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,300,700,500,500italic',
			'//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700,700italic',
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
		'icons'                      => array(
			'review-page-icon-arrow'
		),
	),
	'review-resources-page'                     => array(
		'name'                       => 'Review Resources Page', //required
		'tags'                       => array( 'review' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,300,700,500,500italic',
			'//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700,700italic',
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
	),
	'review-video-recommendation-page'          => array(
		'name'                       => 'Review Video Recommendation Page', //required
		'tags'                       => array( 'review' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,300,700,500,500italic',
			'//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700,700italic',
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
		'icons'                      => array(
			'review-page-icon-gear',
			'review-page-icon-download',
			'review-page-icon-files',
			'review-page-icon-tools',
			'review-page-icon-atom',
			'review-page-icon-arrow'
		),
	),
	'elementary-lead-generation'                => array(
		'name'                       => 'Elementary 1-Step Lead Generation', //required
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300,600,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'elementary-email-confirmation'             => array(
		'name'                       => 'Elementary Email Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300,600,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'elementary-download-page'                  => array(
		'name'                       => 'Elementary Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300,600,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'elementary-video-sales-page'               => array(
		'name'                       => 'Elementary Video Sales Page', //required
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300,600,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'elementary-2step'                          => array(
		'name'                       => 'Elementary Video Sales Page', //required
		'tags'                       => array( '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:300,600,700'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '630px',
			'max_height' => '360px'
		),
		'style_family'               => 'Minimal',
	),
	'video-course-email-confirmation'           => array(
		'name'                       => 'Video Course Email Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Open+Sans:600'

		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow'
		),
		'style_family'               => 'Classy',
	),
	'video-course-email-confirmation2'          => array(
		'name'                       => 'Video Course Email Confirmation 2', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Open+Sans:600'

		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow',
			'video-course-icon-goto',
			'video-course-icon-open',
			'video-course-icon-click'
		),
		'style_family'               => 'Classy',
	),
	'video-course-lead-generation'              => array(
		'name'                       => 'Video Course Lead Generation', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow',
			'video-course-icon-play'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '515px',
			'max_height' => '690px'
		),
		'style_family'               => 'Classy',
	),
	'video-course-lead-generation2'             => array(
		'name'                       => 'Video Course Lead Generation 2', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '515px',
			'max_height' => '690px'
		),
		'style_family'               => 'Classy',
	),
	'video-course-lead-generation3'             => array(
		'name'                       => 'Video Course Lead Generation 3', //required
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'video-course-lead-generation4'             => array(
		'name'                       => 'Video Course Lead Generation 4', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '515px',
			'max_height' => '690px'
		),
		'style_family'               => 'Classy',
	),
	'video-course-video-lesson'                 => array(
		'name'                       => 'Video Course Video Lesson', //required
		'tags'                       => array( 'video', 'course content' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-play'
		),
		'style_family'               => 'Classy',
	),
	'video-course-video-lesson2'                => array(
		'name'                       => 'Video Course Video Lesson 2', //required
		'tags'                       => array( 'video', 'course content' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-play'
		),
		'style_family'               => 'Classy',
	),
	'video-course-video-lesson-page'            => array(
		'name'                       => 'Video Course Video Lessons Page', //required
		'tags'                       => array( 'video', 'course content' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Shadows+Into+Light'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow'
		),
		'style_family'               => 'Classy',
	),
	'video-course-video-lesson-page2'           => array(
		'name'                       => 'Video Course Video Lessons Page 2', //required
		'tags'                       => array( 'video', 'course content' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'video-course-icon-arrow'
		),
		'style_family'               => 'Classy',
	),
	'edition-author-lead-generation'            => array(
		'name'                       => 'Edition Author Lead Generation', //required
		'tags'                       => array( 'lead generation', '2-step', 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'edition-icon-gear',
			'edition-icon-globe',
			'edition-icon-monitor',
			'edition-icon-lock'
		),
		'style_family'               => 'Classy',
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '490px'
		),
	),
	'edition-book-landing-page'                 => array(
		'name'                       => 'Edition Book Landing Page', //required
		'tags'                       => array( 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'edition-icon-download',
			'edition-icon-arrow',
			'edition-icon-atom',
			'edition-icon-comments'
		),
		'style_family'               => 'Classy',
	),
	'edition-lead-generation-page'              => array(
		'name'                       => 'Edition Lead Generation Page', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '970px',
			'max_height' => '490px'
		),
	),
	'edition-email-confirmation'                => array(
		'name'                       => 'Edition Email Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'edition-email-confirmation2'               => array(
		'name'                       => 'Edition Email Confirmation 2', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'edition-download-page'                     => array(
		'name'                       => 'Edition Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,600',
			'//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,600,700,400,300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'corp-app-landing-page'                     => array(
		'name'                       => 'Corp App Landing Page', //required
		'tags'                       => array( '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:300,400,700,600,500,800,200'
		),
		'icons'                      => array(
			'corp-icon-clock',
			'corp-icon-leaf',
			'corp-icon-hat',
			'corp-icon-ribbon'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '720px',
			'max_height' => '520px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'corp-lead-generation'                      => array(
		'name'                       => 'Corp 1 Step Lead Generation Page', //required
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:300,400,700,600,500,800,200'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'corp-download'                             => array(
		'name'                       => 'Corp Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:300,400,700,600,500,800,200'
		),
		'icons'                      => array(
			'corp-icon-download'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'corp-email-confirmation'                   => array(
		'name'                       => 'Corp Email Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:300,400,700,600,500,800,200'
		),
		'icons'                      => array(
			'corp-icon-check'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'corp-webinar-signup'                       => array(
		'name'                       => 'Corp Webinar Registration Page', //required
		'tags'                       => array( 'webinar' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:300,400,700,600,500,800,200'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '720px',
			'max_height' => '520px'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'lime-lead-generation-page'                 => array(
		'name'                       => 'Lime Lead Generation Page', //required
		'tags'                       => array( 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '520px',
			'max_height' => '650px'
		),
		'style_family'               => 'Minimal',
	),
	'lime-lead-generation-page2'                => array(
		'name'                       => 'Lime Lead Generation Page2', //required
		'tags'                       => array( 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'lime-coming-soon'                          => array(
		'name'                       => 'Lime Coming Soon Page', //required
		'tags'                       => array( 'coming soon' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'lime-confirmation-page'                    => array(
		'name'                       => 'Lime Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'lime-download-page'                        => array(
		'name'                       => 'Lime Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'lime-video-lesson'                         => array(
		'name'                       => 'Lime Video Lesson Page', //required
		'tags'                       => array( 'video', 'course content' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
		'icons'                      => array(
			'lime-icon-lock'
		),
	),
	'lime-sales-page'                           => array(
		'name'                       => 'Lime Sales Page', //required
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'lime-video-sales-page'                     => array(
		'name'                       => 'Lime Video Sales Page', //required
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'fame-2step'                                => array(
		'name'                       => 'Fame 2-Step Lead Gen', //required
		'tags'                       => array( '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '530px',
			'max_height' => '420px'
		),
	),
	'fame-coming-soon'                          => array(
		'name'                       => 'Fame Coming Soon', //required
		'tags'                       => array( 'coming soon', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'fame-confirmation'                         => array(
		'name'                       => 'Fame Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'fame-download'                             => array(
		'name'                       => 'Fame Download', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'fame-minimal-lead-gen'                     => array(
		'name'                       => 'Fame Minimal Lead Generation', //required
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'fame-icon-facebook',
			'fame-icon-twitter',
			'fame-icon-google'
		),
		'style_family'               => 'Minimal',
	),
//    'fame-multiple-choice' => array(
//        'name' => 'Fame Multiple Choice', //required
//        'tags' => array('2-step', 'lead generation', 'multiple choice'),
//        'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
//        'fonts' => array(
//            '//fonts.googleapis.com/css?family=Roboto:400,100,500,700,100italic',
//            '//fonts.googleapis.com/css?family=Playball'
//        ),
//        'hidden_menu_items' => array(
//            'max_width', 'bg_full_height', 'border_radius'
//        ),
//        'style_family' => 'Flat',
//        'has_lightbox' => true,
//        'lightbox' => array(
//            'max_width' => '836px',
//            'max_height' => '458px'
//        ),
//    ),
	'fame-video-sales'                          => array(
		'name'                       => 'Fame Video Sales Page', //required
		'tags'                       => array( 'video', 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Minimal',
	),
	'vibrant_double_whammy'                     => array(
		'name'                       => 'Vibrant Double Whammy Webinar',
		'tags'                       => array( 'webinar', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'icons'                      => array(
			'vibrant_whammy_icon_clock'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '850px',
			'max_height' => '440px'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_download_page'                     => array(
		'name'                       => 'Vibrant Download Page',
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'icons'                      => array(
			'vibrant_download_icon'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_email_confirmation'                => array(
		'name'                       => 'Vibrant Email Confirmation',
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'icons'                      => array(
			'vibrant_email_arrow',
			'vibrant_email_download'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_lead_generation'                   => array(
		'name'                       => 'Vibrant Lead Generation',
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '850px',
			'max_height' => '440px'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_live_streaming_page'               => array(
		'name'                       => 'Vibrant Live Streaming Page',
		'tags'                       => array( 'webinar', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_sales_page'                        => array(
		'name'                       => 'Vibrant Sales Page',
		'tags'                       => array( 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'icons'                      => array(
			'vibrant-sales-download',
			'vibrant-sales-gear',
			'vibrant-sales-chart',
			'vibrant-sales-lock',
			'vibrant-sales-plug',
			'vibrant-sales-modem',
			'vibrant-sales-heart',
			'vibrant-sales-briefcase',
			'vibrant-sales-people',
			'vibrant-sales-arrow'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_video_sales_page'                  => array(
		'name'                       => 'Vibrant Video Sales Page',
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_webinar_registration'              => array(
		'name'                       => 'Vibrant Webinar Registration',
		'tags'                       => array( 'webinar', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'icons'                      => array(
			'vibrant_whammy_icon_clock'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '580px',
			'max_height' => '440px'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'vibrant_webinar_replay'                    => array(
		'name'                       => 'Vibrant Webinar Replay',
		'tags'                       => array( 'webinar', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Open+Sans:700',
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Raleway:300,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_lead_generation'                => array(
		'name'                       => 'Foundation Lead Generation',
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,400,700'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_personal_branding_confirmation' => array(
		'name'                       => 'Personal Branding Confirmation',
		'tags'                       => array( 'confirmation page', 'video', 'personal branding' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,300,500,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_personal_branding_download'     => array(
		'name'                       => 'Personal Branding Download',
		'tags'                       => array( 'download', 'personal branding' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,300,500,600'
		),
		'icons'                      => array(
			'foundation-download-icon-download'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_personal_branding_lead'         => array(
		'name'                       => 'Personal Branding Lead Generation',
		'tags'                       => array( 'lead generation', '1-step', 'personal branding' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,300,500,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_personal_branding_welcome'      => array(
		'name'                       => 'Personal Branding Welcome',
		'tags'                       => array( 'personal branding' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,300,500,600'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'foundation_personal_branding_2step'        => array(
		'name'                       => 'Personal Branding 2-Step',
		'tags'                       => array( 'lead generation', '2-step', 'personal branding' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Ek+Mukta:200,300,500,600'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '720px',
			'max_height' => '610px'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '2px 2px 10px 0px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'copy_sales_page'                           => array(
		'name'                       => 'Copy Sales Page',
		'tags'                       => array( 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100',
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'icons'                      => array(
			'copy-salespage-file',
			'copy-salespage-map',
			'copy-salespage-chart'
		),
		'custom_color_mappings'      => array(
			'landing_page_content' => array(
				'undefined' => array(
					'Flat'    => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Classy'  => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					),
					'Minimal' => array(
						array(
							'label'          => 'Shadow color',
							'selector'       => '.tve_lp_content',
							'search_outside' => 1, // global selector, do not search inside edit_mode
							'property'       => 'box-shadow',
							'value'          => '0 0 15px 3px [color]',
						),
					)
				)
			)
		),
		'style_family'               => 'Classy',
	),
	'copy_download'                             => array(
		'name'                       => 'Copy Download Page',
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100',
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'style_family'               => 'Classy',
	),
	'copy_video_lead'                           => array(
		'name'                       => 'Copy Video Lead',
		'tags'                       => array( 'video', 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100',
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '800px',
			'max_height' => '610px'
		),
		'style_family'               => 'Classy',
	),
	'minimal_video_offer_page'                  => array(
		'name'                       => 'Serene',
		'tags'                       => array( 'sales page', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'icons'                      => array(
			'minimal-video-offer-download'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '495px',
			'max_height' => '540px'
		),
		'style_family'               => 'Flat',
	),
	'serene_download_page'                      => array(
		'name'                       => 'Serene Download Page',
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700'
		),
		'icons'                      => array(
			'serene-downloadpage-download',
			'serene-downloadpage-heart'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
	),
	'serene_lead_generation_page'               => array(
		'name'                       => 'Serene Lead Generation Page',
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700'
		),
		'icons'                      => array(
			'serene-leadgeneration-download'
		),
		'style_family'               => 'Flat',
	),
	'mini_squeeze'                              => array(
		'name'                       => 'Mini Squeeze',
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'style_family'               => 'Flat',
	),
	'mini_squeeze_download'                     => array(
		'name'                       => 'Mini Squeeze Download',
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'icons'                      => array(
			'mini-squeeze-download-icon'
		),
		'style_family'               => 'Flat',
	),
	'mini_squeeze_confirmation'                 => array(
		'name'                       => 'Mini Squeeze Confirmation',
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_header, .tve_lp_content, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'style_family'               => 'Flat',
	),
	'lead_generation_image'                     => array(
		'name'                       => 'Rockstar', //required
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,900,300italic,900italic'
		),
		'style_family'               => 'Flat',
	),
	'rockstar_confirmation'                     => array(
		'name'                       => 'Rockstar Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,500,700,300italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'icons'                      => array(
			'rockstar-icon-email',
			'rockstar-icon-file',
			'rockstar-icon-mouse'
		),
		'style_family'               => 'Flat',
	),
	'rockstar_download'                         => array(
		'name'                       => 'Rockstar Download', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300,900,300italic,900italic'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Flat',
	),
	'lead_generation_flat'                      => array(
		'name'                       => 'Flat', //required
		'tags'                       => array( 'lead generation', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '800px',
			'max_height' => '600px'
		),
		'style_family'               => 'Flat',
	),
	'flat_confirmation'                         => array(
		'name'         => 'Flat Confirmation',
		'tags'         => array( 'confirmation page' ),
		'fonts'        => array(
			'//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic',
		),
		'icons'        => array(
			'flat-confirmation-icon
            -envelop',
			'flat-confirmation-icon-envelop-opened',
			'flat-confirmation-icon-pointer',
			'flat-confirmation-icon-checkmark-circle'
		),
		'style_family' => 'Flat',
	),
	'flat_download'                             => array(
		'name'         => 'Flat Download',
		'tags'         => array( 'download' ),
		'fonts'        => array(
			'//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic',
		),
		'icons'        => array(
			'flat-download-icon-download'
		),
		'style_family' => 'Flat',
	),
	'lead_generation'                           => array(
		'name'                       => 'Simple', //required
		'tags'                       => array( '2-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '600px',
			'max_height' => '600px'
		),
		'style_family'               => 'Classy',
	),
	'simple_confirmation_page'                  => array(
		'name'                       => 'Simple Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,500',
			'//fonts.googleapis.com/css?family=Open+Sans:300'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'simple_download_page'                      => array(
		'name'                       => 'Simple Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'hidden_menu_items'          => array(
			'max_width',
			'bg_full_height',
			'border_radius'
		),
		'style_family'               => 'Classy',
	),
	'simple_video_lead'                         => array(
		'name'                       => 'Simple Video Lead', //required
		'thumbnail'                  => 'simple-video-lead.png', //required
		'tags'                       => array( 'lead generation', '1-step', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Raleway:300,400,500'
		),
		'style_family'               => 'Classy',
	),
	'video_lead'                                => array(
		'name'                       => 'Vision', //required
		'tags'                       => array( 'lead generation', 'video', '2-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'has_lightbox'               => true,
		'lightbox'                   => array(
			'max_width'  => '800px',
			'max_height' => '650px'
		),
		'style_family'               => 'Classy',
	),
	'vision-1step'                              => array(
		'name'                       => 'Vision 1-Step Page', //required
		'tags'                       => array( 'lead generation', '1-step' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700'
		),
		'style_family'               => 'Classy',
	),
	'vision_download'                           => array(
		'name'                       => 'Vision Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700,100'
		),
		'icons'                      => array(
			'tve_icon-download'
		),
		'style_family'               => 'Classy',
	),
	'vision_confirmation'                       => array(
		'name'                       => 'Vision Confirmation', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Roboto:300italic,300,700italic,700,100'
		),
		'icons'                      => array(
			'vision-confirmation-mail',
			'vision-confirmation-mailopen',
			'vision-confirmation-link',
			'vision-confirmation-download'
		),
		'style_family'               => 'Flat',
	),
	'big_picture'                               => array(
		'name'                       => 'Big Picture', //required
		'tags'                       => array( '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'style_family'               => 'Classy',
	),
	'big_picture_confirmation'                  => array(
		'name'                       => 'Big Confirmation Page', //required
		'tags'                       => array( 'confirmation page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'icons'                      => array(
			'big-picture-mail-go',
			'big-picture-mail-open',
			'big-picture-mail-click'
		),
		'style_family'               => 'Classy',
	),
	'big_picture_download'                      => array(
		'name'                       => 'Big Picture Download Page', //required
		'tags'                       => array( 'download' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'style_family'               => 'Classy',
	),
	'big_picture_video'                         => array(
		'name'                       => 'Big Picture Video Page', //required
		'tags'                       => array( '1-step', 'lead generation', 'video' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'icons'                      => array(
			'big-picture-icon-download',
			'big-picture-icon-video',
			'big-picture-icon-customization'
		),
		'style_family'               => 'Classy',
	),
	'big_picture_coming_soon'                   => array(
		'name'                       => 'Big Picture Coming Soon', //required
		'tags'                       => array( 'coming soon', '1-step', 'lead generation' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
			'//fonts.googleapis.com/css?family=Open+Sans:700'
		),
		'style_family'               => 'Classy',
	),
	'big_picture_sales_page'                    => array(
		'name'                       => 'Big Picture Sales Page', //required
		'tags'                       => array( 'sales page' ),
		'extended_dropzone_elements' => '.tve_lp_content, .tve_lp_header, .tve_lp_footer',
		'fonts'                      => array(
			'//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic',
		),
		'style_family'               => 'Flat',
	)
);

