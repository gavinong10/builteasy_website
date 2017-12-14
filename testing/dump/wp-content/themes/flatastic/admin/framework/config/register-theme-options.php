<?php

// Include Google Webfonts
include('register-google-webfonts.php');

// Include Color Schemes
include('register-color-schemes.php');

/* ---------------------------------------------------------------------- */
/*	Pages Elements
/* ---------------------------------------------------------------------- */

$mad_pages = array(
	array(
		'title' =>  __('Theme Options', MAD_BASE_TEXTDOMAIN),
		'slug' => 'mad',
		'class' => 'admin-icon-general',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Styling Options', MAD_BASE_TEXTDOMAIN),
		'slug' => 'styling',
		'class' => 'admin-icon-styling',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Header', MAD_BASE_TEXTDOMAIN),
		'slug' => 'header',
		'class' => 'admin-icon-header',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Pages', MAD_BASE_TEXTDOMAIN),
		'slug' => 'page',
		'class' => 'admin-icon-header',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Sidebar', MAD_BASE_TEXTDOMAIN),
		'slug' => 'sidebar',
		'class' => 'admin-icon-sidebar',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Blog', MAD_BASE_TEXTDOMAIN),
		'slug' => 'blog',
		'class' => 'admin-icon-blog',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Portfolio', MAD_BASE_TEXTDOMAIN),
		'slug' => 'portfolio',
		'class' => 'admin-icon-portfolio',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Testimonials', MAD_BASE_TEXTDOMAIN),
		'slug' => 'testimonials',
		'class' => 'admin-icon-testimonials',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Team Members', MAD_BASE_TEXTDOMAIN),
		'slug' => 'team-members',
		'class' => 'admin-icon-team-members',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Footer', MAD_BASE_TEXTDOMAIN),
		'slug' => 'footer',
		'class' => 'admin-icon-footer',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Shop', MAD_BASE_TEXTDOMAIN),
		'slug' => 'shop',
		'class' => 'admin-icon-shop',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Side Tabbed Panel', MAD_BASE_TEXTDOMAIN),
		'slug' => 'admin',
		'class' => 'admin-icon-admin',
		'parent'=> 'mad',
	),
	array(
		'title' =>  __('Import / Export', MAD_BASE_TEXTDOMAIN),
		'slug' => 'import',
		'class' => 'admin-icon-import',
		'parent'=> 'mad',
	)
);

/* ---------------------------------------------------------------------- */
/*	General Elements
/* ---------------------------------------------------------------------- */

$mad_elements[] = array(
	"name" 	=> __("Frontpage", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "mad",
	"type" 	=> "select",
	"id" 	=> "frontpage",
	"options" => "page",
	"desc" 	=> __("Choose frontpage description page", MAD_BASE_TEXTDOMAIN)
);

$mad_elements[] = array(
	"name" 	=> __("Favicon", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "mad",
	"type" 	=> "upload",
	"id" 	=> "favicon",
	"desc" 	=> __("A favicon is a 57x57 pixel icon that represents your site", MAD_BASE_TEXTDOMAIN),
	"std" => MAD_BASE_URI . 'images/favicon.png'
);

	/* ---------------------------------------------------------------------- */
	/*	Logo
	/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Logo Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "heading",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Type Logo", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "select",
		"id" 	=> "logo_type",
		"options" => array(
			'text' => __('Text Logo', MAD_BASE_TEXTDOMAIN),
			'upload' => __('Upload Logo', MAD_BASE_TEXTDOMAIN)
		),
		"desc" 	=> __('Choose type logo text or image', MAD_BASE_TEXTDOMAIN),
		"std"	=> 'text'
	);

	$mad_elements[] = array(
		"name" 	=> __("Text Logo", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "editor",
		"id" 	=> "logo_text",
		"desc" 	=> __("If you don't have logo image, write Your Text logo. </br> All Logo text settings you can find in Styling Options Section", MAD_BASE_TEXTDOMAIN),
		"required" => array("logo_type", 'text'),
		"std"	=> '<span>flat</span>astic'
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Logo", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "upload",
		"id" 	=> "logo_image",
		"desc" 	=> __("Upload your logo image. Your logo image width must be no more that 166px", MAD_BASE_TEXTDOMAIN),
		"required" => array("logo_type", 'upload'),
		"std"   => MAD_BASE_URI . 'images/logo.png'
	);

	$mad_elements[] = array(
		"name" => __("Effect Hover Logo", MAD_BASE_TEXTDOMAIN),
		"slug" => "mad",
		"type" => "switch_set",
		"id" => "logo_hover",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("if select 'yes' logo hover effect on", MAD_BASE_TEXTDOMAIN),
	);

//	$elements[] = array(
//		"name" => __("Responsive", MAD_BASE_TEXTDOMAIN),
//		"slug" => "general",
//		"type" => "select",
//		"id" => "responsive",
//		"options" => array(
//			'yes' => __('Yes', MAD_BASE_TEXTDOMAIN),
//			'no' => __('No', MAD_BASE_TEXTDOMAIN)
//		),
//		"std" => 'yes',
//		"desc" 	=> __("Responsive site", MAD_BASE_TEXTDOMAIN),
//	);

	/* --------------------------------------------------------- */
	/* Mailchimp Api Settings
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Mailchimp Api Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "heading",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] =	array(
		"name" 	=> __("Enter your Mailchimp Api", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "text",
		"id" 	=> "mad_mailchimp_api",
		"std"   => "47a1e8e482153a3a553b5f20a2660db7-us4",
		"desc" 	=> __("Please enter your MailChimp API Key. The API Key allows your WordPress site to communicate with your MailChimp account. For help, visit the MailChimp Support article : <a href='http://kb.mailchimp.com/article/where-can-i-find-my-api-key' target='_blank'>Where can I find my API Key?</a>", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] =	array(
		"name" 	=> __("Enter your Mailchimp Id", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "text",
		"id" 	=> "mad_mailchimp_id",
		"std"   => "5b95a7c729",
		"desc" 	=> __("<a target='_blank' href='http://kb.mailchimp.com/article/how-can-i-find-my-list-id'>". __('Where can I find List ID?', MAD_BASE_TEXTDOMAIN) . "</a>", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] =	array(
		"name" 	=> __("Enter your Mailchimp data center(e.g. us4)", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "text",
		"id" 	=> "mad_mailchimp_center",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"std" => 'us4'
	);

	/* --------------------------------------------------------- */
	/* Analytics Tracking Code
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Google Analytics Tracking Code", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "textarea",
		"id" 	=> "analytics",
		"desc" 	=> __("Enter your Google analytics tracking code here. </br> Tracking ID UA - .......", MAD_BASE_TEXTDOMAIN),
	);

	/* --------------------------------------------------------- */
	/* Cookie Alert Settings
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Cookie Alert Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "heading",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Cookie Alert?", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "buttons_set",
		"id" 	=> "cookie_alert",
		"options" => array(
			'show'  => __('Show', MAD_BASE_TEXTDOMAIN),
			'hide' => __('Hide', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'show',
		"desc" 	=> __("Show or hide cookie alert", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Cookie Alert Message", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "textarea",
		"id" 	=> "cookie_alert_message",
		"desc" 	=> __("Message for cookie alert", MAD_BASE_TEXTDOMAIN),
		"std"   => __("Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] =	array(
		"name" 	=> __("Button Read More Link", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "text",
		"id" 	=> "cookie_alert_read_more_link",
		"desc" 	=> __("Input link for button read more", MAD_BASE_TEXTDOMAIN),
		"std" => 'http://www.cookielaw.org/the-cookie-law'
	);

	/* --------------------------------------------------------- */
	/* 404 Page Options
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("404 Page Options", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "heading",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("404 Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "editor",
		"rows"  => 10,
		"id" 	=> "440_content",
		"std"   => "<h2>404</h2><h3> ". __('Page not found!', MAD_BASE_TEXTDOMAIN) . "</h3><p>" . __('We\'re sorry, but we can\'t find the page you were looking for. It\'s probably some thing we\'ve done wrong but now we know about it and we\'ll try to fix it. In the meantime, try one of these options:', MAD_BASE_TEXTDOMAIN) . "</p>",
		"desc" 	=> __("Enter your text for 404 page", MAD_BASE_TEXTDOMAIN),
	);

	/* --------------------------------------------------------- */
	/* Other Options
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Other Options", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "mad",
		"type" 	=> "heading",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" => __("Effect Zoom Image", MAD_BASE_TEXTDOMAIN),
		"slug" => "mad",
		"type" => "buttons_set",
		"id" => "zoom_image",
		"options" => array(
			'zoom-image' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'no-zoom-image' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'zoom-image',
		"desc" 	=> __("if select 'yes' image zoom hover effect on", MAD_BASE_TEXTDOMAIN),
	);


/* ---------------------------------------------------------------------- */
/*	Styling Elements
/* ---------------------------------------------------------------------- */

	/* --------------------------------------------------------- */
	/*	Styling Tabs
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("General Styling", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "styling",
		"type" 	=> "heading",
		"desc" 	=> __("Change the theme style settings", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] =	array(
		"slug"	=> "styling",
		"name" 	=> __("Select a color scheme", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __("Choose a color scheme here.", MAD_BASE_TEXTDOMAIN),
		"id" 	=> "color_scheme",
		"type" 	=> "color_schemes",
		"std" 	=> "scheme_default",
		"options" => $mad_color_schemes
	);

	// start tab container
	$mad_elements[] = array(
		"slug"	=> "styling",
		"type" => "tab_group_start",
		"id" => "styling_tab_container",
		"class" => 'mad-tab-container',
		"desc" => false
	);

		// start 1 tab
		$mad_elements[] = array(
			'name'=>__('General', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "styling_tab_1",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] =	array(
				"name" 	=> __("General Background Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-general_body_bg_color",
				"std" 	=> "#232830",
				"default" 	=> "#232830",
				"desc" 	=> __("General background color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("General Background Image", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "select",
				"id" 	=> "styles-bg_img",
				"options" => array(
					'' => __('No Background Image', MAD_BASE_TEXTDOMAIN),
					'custom' => __('Upload Image', MAD_BASE_TEXTDOMAIN)
				),
				"desc" 	=> __('The background image of your Body', MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] = array(
				"name" 	=> __("Upload Background Image", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "upload",
				"id" 	=> "styles-body_bg_image",
				"desc" 	=> __("Upload background image of your body", MAD_BASE_TEXTDOMAIN),
				"required" => array("styles-bg_img", 'custom'),
				"std"   => ''
			);

			$mad_elements[] = array(
				"name" => __("Repeat", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-body_repeat",
				"options" => array(
					'no-repeat' => __('No Repeat', MAD_BASE_TEXTDOMAIN),
					'repeat' => __('Repeat', MAD_BASE_TEXTDOMAIN),
					'repeat-x' => __('Repeat Horizontally', MAD_BASE_TEXTDOMAIN),
					'repeat-y' => __('Repeat Vertically', MAD_BASE_TEXTDOMAIN)
				),
				"std" => 'no-repeat',
				"required" => array("styles-bg_img", 'custom'),
				"desc" 	=> __("Select the repeat mode for the background image", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Position", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-body_position",
				"options" => array(
					'top center' => __('Top center', MAD_BASE_TEXTDOMAIN),
					'top left' => __('Top left', MAD_BASE_TEXTDOMAIN),
					'top right' => __('Top right', MAD_BASE_TEXTDOMAIN),
					'bottom left' => __('Bottom left', MAD_BASE_TEXTDOMAIN),
					'bottom center' => __('Bottom center', MAD_BASE_TEXTDOMAIN),
					'bottom right' => __('Bottom right', MAD_BASE_TEXTDOMAIN)
				),
				"std" => 'top center',
				"required" => array("styles-bg_img", 'custom'),
				"desc" 	=> __("Select the position for the background image", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Attachment", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-body_attachment",
				"options" => array(
					'fixed' => __('Fixed', MAD_BASE_TEXTDOMAIN),
					'scroll' => __('Scroll', MAD_BASE_TEXTDOMAIN)
				),
				"std" => 'yes',
				"required" => array("styles-bg_img", 'custom'),
				"desc" 	=> __("Select the attachment for the background image ", MAD_BASE_TEXTDOMAIN),
			);


			$mad_elements[] = array(
				"name" 	=> __("General Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-general_font_color",
				"std" 	=> "#696e6e",
				"default" 	=> "#696e6e",
				"desc" 	=> __("General font color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("General Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-general_font_size",
				"options" => "range",
				"range" => "12-30",
				"std" => "14px",
				"desc" => __("General font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("General Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "general_google_webfont",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("General font family", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("Primary Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-primary_color",
				"std" 	=> "#e74c3c",
				"default" 	=> "#e74c3c",
				"desc" 	=> __("Key color for links and other elements", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("Secondary Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-secondary_color",
				"std" 	=> "#e74c3c",
				"default" 	=> "#e74c3c",
				"desc" 	=> __("Color for link hover and other", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("Highlight Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-highlight_color",
				"std" 	=> "#34495e",
				"default" 	=> "#34495e",
				"desc" 	=> __("Color of links and elements when you hover", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("Selection Background Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-highlight_bg_color",
				"std" 	=> "#e74c3c",
				"default" 	=> "#e74c3c",
				"desc" 	=> __("Highlight and selection background color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" 	=> __("Selection Text Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-highlight_text_color",
				"std" 	=> "#fff",
				"default" 	=> "#fff",
				"desc" 	=> __("Highlight and selection text color", MAD_BASE_TEXTDOMAIN),
			);

		// end 1 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 2 tab
		$mad_elements[] = array(
			'name'=>__('Header', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "styling_tab_2",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] =	array(
				"name" 	=> __("Header Background Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-header_bg_color",
				"std" 	=> "#fafbfb",
				"default" 	=> "#fafbfb",
				"desc" 	=> __("Header background color", MAD_BASE_TEXTDOMAIN),
			);

		// end 2 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 3 tab
		$mad_elements[] = array(
			'name'=>__('Logo', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "styling_tab_3",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] =	array(
				"name" 	=> __("Logo Text Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-logo_font_color",
				"std" 	=> "#e74c3c",
				"default" 	=> "#e74c3c",
				"desc" 	=> __("Logo text color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Logo Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-logo_font_size",
				"options" => "range",
				"range" => "35-60",
				"std" => "45px",
				"desc" => __("Logo Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Logo Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-logo_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Logo Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 3 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 4 tab
		$mad_elements[] = array(
			'name'=>__('Footer', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "styling_tab_4",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] =	array(
				"name" 	=> __("Footer Background Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-footer_bg_color",
				"std" 	=> "#323a45",
				"default" 	=> "#323a45",
				"desc" 	=> __("Footer background color", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Footer Bottom Part Background Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-footer_bottom_part_bg_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Footer bottom part background color", MAD_BASE_TEXTDOMAIN)
			);

		// end 4 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

	// end tab container
	$mad_elements[] = array(
		"slug"	=> "styling",
		"type" => "tab_group_end",
		"desc" => false
	);

	/* --------------------------------------------------------- */
	/*	All Headings Styling
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("All Headings (H1-H6)", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "styling",
		"type" 	=> "heading",
		"desc" 	=> __("Change All Headings style settings", MAD_BASE_TEXTDOMAIN),
	);

	// start tab container
	$mad_elements[] = array(
		"slug"	=> "styling",
		"type" => "tab_group_start",
		"id" => "headings_tab_container",
		"class" => 'mad-tab-container',
		"desc" => false
	);

		// start 1 tab
		$mad_elements[] = array(
			'name'=>__('H1', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h1_tab_1",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h1_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h1_font_size",
				"options" => "range",
				"range" => "30-40",
				"unit" => 'px',
				"std" => "36px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h1_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 1 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 2 tab
		$mad_elements[] = array(
			'name'=>__('H2', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h2_tab_2",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h2_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h2_font_size",
				"options" => "range",
				"range" => "22-30",
				"unit" => 'px',
				"std" => "24px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h2_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 2 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 3 tab
		$mad_elements[] = array(
			'name'=>__('H3', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h3_tab_3",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h3_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h3_font_size",
				"options" => "range",
				"range" => "18-24",
				"unit" => 'px',
				"std" => "20px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h3_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 3 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 4 tab
		$mad_elements[] = array(
			'name'=>__('H4', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h4_tab_4",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h4_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h4_font_size",
				"options" => "range",
				"range" => "16-22",
				"unit" => 'px',
				"std" => "18px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h4_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 4 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 5 tab
		$mad_elements[] = array(
			'name'=>__('H5', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h5_tab_5",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h5_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h5_font_size",
				"options" => "range",
				"unit" => 'px',
				"range" => "14-20",
				"std" => "16px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h5_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 5 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 6 tab
		$mad_elements[] = array(
			'name'=>__('H6', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "styling",
			"type" => "tab_group_start",
			"id" => "h6_tab_6",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Font Color", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "styling",
				"type" 	=> "color",
				"id" 	=> "styles-h6_font_color",
				"std" 	=> "#292f38",
				"default" 	=> "#292f38",
				"desc" 	=> __("Heading Color", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Size", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h6_font_size",
				"options" => "range",
				"range" => "12-18",
				"unit" => 'px',
				"std" => "14px",
				"desc" => __("Font size", MAD_BASE_TEXTDOMAIN),
			);

			$mad_elements[] = array(
				"name" => __("Font Family", MAD_BASE_TEXTDOMAIN),
				"slug" => "styling",
				"type" => "select",
				"id" => "styles-h6_font_family",
				"options" => $mad_google_webfonts,
				"std" => "Roboto:300,700,900,500,300italic",
				"desc" => __("Choose Font Family", MAD_BASE_TEXTDOMAIN),
			);

		// end 6 tab
		$mad_elements[] = array(
			"slug"	=> "styling",
			"type" => "tab_group_end",
			"desc" => false
		);

	// end tab container
	$mad_elements[] = array(
		"slug"	=> "styling",
		"type" => "tab_group_end",
		"desc" => false
	);

	/* --------------------------------------------------------- */
	/*	Custom Quick CSS
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Custom Quick CSS", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "styling",
		"type" 	=> "textarea",
		"id" 	=> "custom_quick_css",
		"desc" 	=> __("Here you can make some quick changes in CSS", MAD_BASE_TEXTDOMAIN),
	);

/* ---------------------------------------------------------------------- */
/*	Header Elements
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Header Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for header", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Header Full Width", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "switch_set",
		"id" 	=> "header_full_width",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => '0',
		"desc" 	=> __("If you choose Yes, you will see header full width", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Header Layout", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "select",
		"id" 	=> "header_layout",
		"options" => array(
			'type-1' => __('Header 1', MAD_BASE_TEXTDOMAIN),
			'type-2' => __('Header 2', MAD_BASE_TEXTDOMAIN),
			'type-3' => __('Header 3', MAD_BASE_TEXTDOMAIN),
			'type-4' => __('Header 4', MAD_BASE_TEXTDOMAIN),
			'type-5' => __('Header 5', MAD_BASE_TEXTDOMAIN),
			'type-6' => __('Header 6', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'type-1',
		"desc" 	=> __("Choose your default header style", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sticky Navigation", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "switch_set",
		"id" 	=> "sticky_navigation",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("The sticky navigation menu is a vital part of a website, helping users move between pages and find desired information.", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Header Top Part", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "buttons_set",
		"id" 	=> "header_top_part",
		"options" => array(
			'show'  => __('Show', MAD_BASE_TEXTDOMAIN),
			'hide' => __('Hide', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'show',
		"desc" 	=> __("Show or hide header top part", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show search, wishlist, compare, currency, language, cart in header", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Header parameters", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Product Search", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_search",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Wishlist", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_wishlist",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Compare", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_compare",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Feedback", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_call_us",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col',
		"clear" => 'both'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Language", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_language",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Currency", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_currency",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Cart", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_cart",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Show Woo Links", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "show_woo_links",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col',
		"clear" => 'both'
	);

	$mad_elements[] = array(
		"name" 	=> __("Feedback phone number", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "editor",
		"id" 	=> "call_us",
		"std"	=> __("Call us toll free: <b>(123) 456-7890</b>", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __("Enter your phone number for feedback", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Promo message", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "header",
		"type" 	=> "editor",
		"rows"  => 5,
		"id" 	=> "promo_message",
		"std"   => __("Promo message will be shown here!", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __("Enter your promo message", MAD_BASE_TEXTDOMAIN),
	);

/* ---------------------------------------------------------------------- */
/*	Page Elements
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Page Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "page",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for pages", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Page Layout", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "page",
		"type" 	=> "buttons_set",
		"id" 	=> "page_layout",
		"options" => array(
			'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN),
			'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'wide_layout',
		"desc" 	=> __("Choose a default page layout style", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Breadcrumbs on page", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "page",
		"type" 	=> "switch_set",
		"id" 	=> "page_breadcrumbs",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("Show or hide breadcrumbs by default on page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Breadcrumbs on single page", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "page",
		"type" 	=> "switch_set",
		"id" 	=> "single_breadcrumbs",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("Show or hide breadcrumbs by default on single page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Animation on Pages", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "page",
		"type" 	=> "switch_set",
		"id" 	=> "animation",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN),
		),
		"std" => true,
		"desc" 	=> __("Choose yes for shortcodes animation", MAD_BASE_TEXTDOMAIN),
	);

/* ---------------------------------------------------------------------- */
/*	Sidebar Elements
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Sidebar Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "sidebar",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for sidebar", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sidebar on Pages", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "sidebar",
		"type" 	=> "buttons_set",
		"id" 	=> "sidebar_page_position",
		"options" => array(
			'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
			'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
			'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'no_sidebar',
		"desc" 	=> __("Choose the default page sidebar position", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sidebar on Single Post Pages", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "sidebar",
		"type" 	=> "buttons_set",
		"id" 	=> "sidebar_post_position",
		"options" => array(
			'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
			'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
			'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'sbr',
		"desc" 	=> __("Choose the blog post sidebar position", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sidebar on Archive Pages", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "sidebar",
		"type" 	=> "buttons_set",
		"id" 	=> "sidebar_archive_position",
		"options" => array(
			'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
			'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
			'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'sbr',
		"desc" 	=> __("Choose the archive sidebar position", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Position Sidebar for mobile devices", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "sidebar",
		"type" 	=> "buttons_set",
		"id" 	=> "position_sidebar_mobile",
		"options" => array(
			'top' => __('Top', MAD_BASE_TEXTDOMAIN),
			'bottom' => __('Bottom', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'top',
		"desc" 	=> __("Position Sidebar for mobile devices", MAD_BASE_TEXTDOMAIN),
	);

	/* ---------------------------------------------------------------------- */
	/*	Blog Elements
	/* ---------------------------------------------------------------------- */

		$mad_elements[] = array(
			"name" 	=> __("Post List Settings", MAD_BASE_TEXTDOMAIN),
			"slug"	=> "blog",
			"type" 	=> "heading",
			"heading" => "h4",
			"desc" 	=> __("Parameters for posts list on blog page", MAD_BASE_TEXTDOMAIN),
		);

	$mad_elements[] = array(
		"name" 	=> __("Truncate count post for Big post", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "number",
		"id" 	=> "excerpt_count_big_post",
		"min" => 100,
		"max" => 1000,
		"std"   => 500,
		"desc" 	=> __("Excerpt count ( min-100, max-1000 symbols)", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Date", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-listing-meta-date",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Comment", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-listing-meta-comment",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Category", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-listing-meta-category",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Ratings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-listing-meta-ratings",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_2col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Author", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-listing-meta-author",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_2col',
		"clear" => 'both'
	);

$mad_elements[] = array(
	"name" 	=> __("Single Post Settings", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "blog",
	"type" 	=> "heading",
	"heading" => "h4",
	"desc" 	=> __("Parameters for standart elements on Post page", MAD_BASE_TEXTDOMAIN),
);

	$mad_elements[] = array(
		"name" 	=> __("Truncate count post for Medium post", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "number",
		"id" 	=> "excerpt_count_medium_post",
		"min" => 100,
		"max" => 1000,
		"std"   => 270,
		"desc" 	=> __("Excerpt count ( min-100, max-1000 symbols)", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Date", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-meta-date",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Comment", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-meta-comment",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Category", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-meta-category",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"clear" => 'both',
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Ratings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-meta-ratings",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Post Author", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-meta-author",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Link Pages", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-link-pages",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_4col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Related Posts", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "checkbox",
		"std"   => 1,
		"id" 	=> "blog-single-related-posts",
		"label" => __("If checked show", MAD_BASE_TEXTDOMAIN),
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"clear" => 'both',
		"class" => 'mad_4col'
	);


$mad_elements[] = array(
	"name" 	=> __("Related Posts Settings", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "blog",
	"type" 	=> "heading",
	"heading" => "h4",
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
);

	$mad_elements[] = array(
		"name" 	=> __("Post's Columns", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "buttons_set",
		"id" 	=> "related_posts_columns",
		"options" => array(
			2 => __('2 Columns', MAD_BASE_TEXTDOMAIN),
			3 => __('3 Columns', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 3,
		"desc" 	=> __("Show to display count columns", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Post's Count", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "buttons_set",
		"id" 	=> "related_posts_count",
		"options" => array(
			3 => __('3', MAD_BASE_TEXTDOMAIN),
			6 => __('6', MAD_BASE_TEXTDOMAIN),
			9 => __('9', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 3,
		"desc" 	=> __("Show to display count items", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Archive Posts Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for posts on archive page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Truncate count post for Archive", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "number",
		"id" 	=> "archive_excerpt_count",
		"min" => 100,
		"max" => 1000,
		"std"   => 200,
		"desc" 	=> __("Excerpt count ( min-100, max-1000 symbols)", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Blog Style", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "buttons_set",
		"id" 	=> "blog_style",
		"options" => array(
			'blog-medium' => __('Blog Medium', MAD_BASE_TEXTDOMAIN),
			'blog-grid' => __('Blog Grid', MAD_BASE_TEXTDOMAIN),
		),
		"std" => 'blog-medium',
		"desc" 	=> __("Choose the default blog layout here for archive", MAD_BASE_TEXTDOMAIN),
	);

	/* --------------------------------------------------------- */
	/*	Share Posts Settings
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Share Posts Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for social links on posts", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show social links", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-enable",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("Show social links in product pages", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Facebook Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-facebook",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Twitter Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-twitter",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable LinedIn Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-linkedin",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Google + Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-googleplus",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Pinterest Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-pinterest",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable VK Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-vk",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => '0',
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);


	$mad_elements[] = array(
		"name" 	=> __("Enable Tumblr Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-tumblr",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Xing Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "blog",
		"type" 	=> "switch_set",
		"id" 	=> "share-posts-xing",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-posts-enable', true),
		"std" => '0',
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

/* ---------------------------------------------------------------------- */
/*	Portfolio Elements
/* ---------------------------------------------------------------------- */

$mad_elements[] = array(
	"name" 	=> __("Archive Page Layout", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "buttons_set",
	"id" 	=> "portfolio_archive_page_layout",
	"options" => array(
		'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN),
		'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'wide_layout',
	"desc" 	=> __("Choose a page layout style for the portfolio archive page", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Sidebar on Archive page", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "buttons_set",
	"id" 	=> "sidebar_portfolio_archive_position",
	"options" => array(
		'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
		'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
		'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'no_sidebar',
	"desc" 	=> __("Choose the portfolio archive sidebar position", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Columns on Archive page", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "buttons_set",
	"id" 	=> "portfolio_archive_column_count",
	"options" => array(
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5
	),
	"std" => 3,
	"desc" 	=> __("This controls how many columns should be appeared on the portfolio archive page", MAD_BASE_TEXTDOMAIN),
);


$mad_elements[] = array(
	"name" 	=> __("Show social links", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-enable",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"std" => true,
	"desc" 	=> __("Show social links in product pages", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Enable Facebook Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-facebook",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable Twitter Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-twitter",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable LinedIn Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-linkedin",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable Google + Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-googleplus",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable Pinterest Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-pinterest",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable VK Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-vk",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => '0',
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);


$mad_elements[] = array(
	"name" 	=> __("Enable Tumblr Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-tumblr",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => true,
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

$mad_elements[] = array(
	"name" 	=> __("Enable Xing Share", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "portfolio",
	"type" 	=> "switch_set",
	"id" 	=> "share-portfolio-xing",
	"options" => array(
		'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
		'off' => __('No', MAD_BASE_TEXTDOMAIN)
	),
	"required" => array('share-portfolio-enable', true),
	"std" => '0',
	"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	"class" => 'mad_3col'
);

/* ---------------------------------------------------------------------- */
/*	Testimonials Elements
/* ---------------------------------------------------------------------- */

$mad_elements[] = array(
	"name" 	=> __("Archive Page Layout", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "testimonials",
	"type" 	=> "buttons_set",
	"id" 	=> "testimonials_archive_page_layout",
	"options" => array(
		'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN),
		'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'wide_layout',
	"desc" 	=> __("Choose a page layout style for the testimonials archive", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Sidebar on Archive page", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "testimonials",
	"type" 	=> "buttons_set",
	"id" 	=> "sidebar_testimonials_archive_position",
	"options" => array(
		'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
		'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
		'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'sbr',
	"desc" 	=> __("Choose the portfolio archive sidebar position", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Columns on Archive page", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "testimonials",
	"type" 	=> "buttons_set",
	"id" 	=> "testimonials_archive_column_count",
	"options" => array(
		'2' => '2',
		'3' => '3',
		'4' => '4'
	),
	"std" => '3',
	"desc" 	=> __("This controls how many columns should be appeared on the testimonials archive", MAD_BASE_TEXTDOMAIN),
);

/* ---------------------------------------------------------------------- */
/*	Team Members Elements
/* ---------------------------------------------------------------------- */

$mad_elements[] = array(
	"name" 	=> __("Archive Page Layout", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "team-members",
	"type" 	=> "buttons_set",
	"id" 	=> "team_members_archive_page_layout",
	"options" => array(
		'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN),
		'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'wide_layout',
	"desc" 	=> __("Choose a page layout style for the team members archive", MAD_BASE_TEXTDOMAIN),
);

$mad_elements[] = array(
	"name" 	=> __("Sidebar on Archive page", MAD_BASE_TEXTDOMAIN),
	"slug"	=> "team-members",
	"type" 	=> "buttons_set",
	"id" 	=> "sidebar_team_members_archive_position",
	"options" => array(
		'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
		'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
		'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
	),
	"std" => 'no_sidebar',
	"desc" 	=> __("Choose the team members archive sidebar position", MAD_BASE_TEXTDOMAIN),
);

/* ---------------------------------------------------------------------- */
/*	Footer Elements
/* ---------------------------------------------------------------------- */

	/* --------------------------------------------------------- */
	/* Copyright
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Footer Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for footer", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Footer Full Width", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "switch_set",
		"id" 	=> "footer_full_width",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => '0',
		"desc" 	=> __("If you choose Yes, you will see footer full width", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" => __("Show Footer Row Top widgets ?", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" => "checkbox",
		"std" => 1,
		"id" => "show_row_top_widgets",
		"desc" => " ",
		"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] = array(
		"name" => __("Footer Row Top Widget positions", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" => "widget_positions",
		"std" => '{"4":[["3","3","3","3"]]}',
		"id" => "footer_row_top_columns_variations",
		"desc" => __("Here you can select how your footer row top widgets will be displayed.", MAD_BASE_TEXTDOMAIN),
		"columns" => 6,
		"selectname" => 'get_sidebars_top_widgets'
	);

	$mad_elements[] = array(
		"name" => __("Show Footer Row Bottom widgets ?", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" => "checkbox",
		"std" => 0,
		"id" => "show_row_bottom_widgets",
		"desc" => " ",
		"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] = array(
		"name" => __("Footer Row Bottom Widget positions", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" => "widget_positions",
		"std" => '{"4":[["3","3","3","3"]]}',
		"id" => "footer_row_bottom_columns_variations",
		"desc" => __("Here you can select how your footer row bottom widgets will be displayed.", MAD_BASE_TEXTDOMAIN),
		"columns" => 6,
		"selectname" => 'get_sidebars_bottom_widgets'
	);

	$mad_elements[] = array(
		"name" 	=> __("Copyright", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "editor",
		"rows"  => 4,
		"id" 	=> "copyright",
		"std"   => '&copy; 2015 <span>Flatastic</span>. All Rights Reserved.',
		"desc" 	=> __("Write your copyright text for the footer", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Align the center of the copyright", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "checkbox",
		"std"   => 0,
		"id" 	=> "copyright_center",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
	);

	/* --------------------------------------------------------- */
	/* Type Payment
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Payment in Footer", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Payment 1", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "upload",
		"id" 	=> "payment_1",
		"desc" 	=> __("Upload a payment image.<br/>Payment Dimension: 38px * 24px", MAD_BASE_TEXTDOMAIN),
		"std"   => MAD_BASE_URI . 'images/payment_img_1.png'
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Payment 2", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "upload",
		"id" 	=> "payment_2",
		"desc" 	=> __("Upload a payment image.<br/>Payment Dimension: 38px * 24px", MAD_BASE_TEXTDOMAIN),
		"std"   => MAD_BASE_URI . 'images/payment_img_2.png'
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Payment 3", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "upload",
		"id" 	=> "payment_3",
		"desc" 	=> __("Upload a payment image.<br/>Payment Dimension: 38px * 24px", MAD_BASE_TEXTDOMAIN),
		"std"   => MAD_BASE_URI . 'images/payment_img_3.png'
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Payment 4", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "upload",
		"id" 	=> "payment_4",
		"desc" 	=> __("Upload a payment image.<br/>Payment Dimension: 38px * 24px", MAD_BASE_TEXTDOMAIN),
		"std"   => MAD_BASE_URI . 'images/payment_img_4.png'
	);

	$mad_elements[] = array(
		"name" 	=> __("Upload Payment 5", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "footer",
		"type" 	=> "upload",
		"id" 	=> "payment_5",
		"desc" 	=> __("Upload a payment image.<br/>Payment Dimension: 38px * 24px", MAD_BASE_TEXTDOMAIN),
		"std"   => MAD_BASE_URI . 'images/payment_img_5.png'
	);

/* ---------------------------------------------------------------------- */
/*	Shop Elements
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Page Layout", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "buttons_set",
		"id" 	=> "product_archive_page_layout",
		"options" => array(
			'wide_layout' => __('Wide Layout', MAD_BASE_TEXTDOMAIN),
			'boxed_layout' => __('Boxed Layout', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'wide_layout',
		"desc" 	=> __("Choose the page style layout for the product archive", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Breadcrumbs on shop page", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "shop_breadcrumbs",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("Show or hide breadcrumbs by default on shop page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sidebar on archive page", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "buttons_set",
		"id" 	=> "sidebar_product_archive_position",
		"options" => array(
			'sbl' => __('Left', MAD_BASE_TEXTDOMAIN),
			'sbr' => __('Right', MAD_BASE_TEXTDOMAIN),
			'no_sidebar' => __('No Sidebar', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'sbr',
		"desc" 	=> __("Choose the sidebar position for product archive", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Sidebar setting on single product", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "select",
		"id" 	=> "sidebar_setting_product",
		"options" => 'custom_sidebars',
		'std' => '',
		"desc" 	=> __("Choose the product sidebar setting", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show review tab on single product", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "show_review_tab",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("If you choose Yes, you will see reviews tab on single product", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Shop View", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "buttons_set",
		"id" 	=> "shop-view",
		"options" => array(
			'view-grid-center' => __('Grid View', MAD_BASE_TEXTDOMAIN),
			'view-list' => __('List View', MAD_BASE_TEXTDOMAIN)
		),
		"std" => 'view-grid-center',
		"desc" 	=> __("Choose default style view for the Shop page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Quick View", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "quick_view",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("If you choose Yes, you will see quick view on the product box", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show lightbox on product image", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "lightbox_on_product_image",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("If you choose Yes, you will see lightbox on the product image", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show zoom on product image", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "zoom_on_product_image",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("If you choose Yes, you will see zoom on the product image", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Product Hover", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "product_hover",
		"options" => array(
			'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("If you choose Yes, you will see the first image from gallery on product hover", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Truncate title for Product", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "number",
		"id" 	=> "product_title_count",
		"min" => 40,
		"max" => 200,
		"std"   => 40,
		"desc" 	=> __("Excerpt count ( min-100, max-1000 symbols)", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Column and Product Count", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "heading",
		"heading" => 'h4',
		"desc" 	=> __("The following settings allow you to choose how many columns and items should be appeared on your default shop overview page and on your product archive pages.", MAD_BASE_TEXTDOMAIN)
	);

	$mad_elements[] = array(
		"name" 	=> __("Column Count", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "select",
		"id" 	=> "woocommerce_column_count",
		"options" => array(
			2 => '2',
			3 => '3',
			4 => '4',
			5 => '5'
		),
		"std" => 3,
		"desc" 	=> __("This controls how many columns should be appeared on overview pages. </br> ( 4 and 5 columns are for the without sidebar page layout
2 columns are for the sidebar page layout )", MAD_BASE_TEXTDOMAIN),
	);

	$itemcount = array('-1' => 'All');

	for ($i = 3; $i < 51; $i++) {
		$itemcount[$i] = $i;
	}

	$mad_elements[] = array(
		"name" 	=> __("Product Count", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "select",
		"id" 	=> "woocommerce_product_count",
		"options" => $itemcount,
		"std" => '9',
		"desc" 	=> __("This controls how many products should be appeared on overview pages.", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Product Count of items for related products", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "select",
		"id" 	=> "shop_single_column_items",
		"options" => $itemcount,
		"std" => '6',
		"desc" 	=> __("Number of items for related products", MAD_BASE_TEXTDOMAIN),
	);

	/* --------------------------------------------------------- */
	/*	Share Product Settings
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Share Product Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "heading",
		"heading" => "h4",
		"desc" 	=> __("Parameters for social links on product page", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Show social links", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-enable",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"std" => true,
		"desc" 	=> __("Show social links in product pages", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Facebook Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-facebook",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Twitter Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-twitter",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable LinedIn Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-linkedin",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Google + Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-googleplus",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Pinterest Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-pinterest",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable VK Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-vk",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => '0',
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);


	$mad_elements[] = array(
		"name" 	=> __("Enable Tumblr Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-tumblr",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => true,
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

	$mad_elements[] = array(
		"name" 	=> __("Enable Xing Share", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "shop",
		"type" 	=> "switch_set",
		"id" 	=> "share-product-xing",
		"options" => array(
			'on'  => __('Yes', MAD_BASE_TEXTDOMAIN),
			'off' => __('No', MAD_BASE_TEXTDOMAIN)
		),
		"required" => array('share-product-enable', true),
		"std" => '0',
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"class" => 'mad_3col'
	);

/* ---------------------------------------------------------------------- */
/*	Admin Elemenents
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Show Side Tabbed Panel?", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "admin",
		"type" 	=> "checkbox",
		"std"   => '0',
		"id" 	=> "show_admin_panel",
		"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
		"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
	);

	/* --------------------------------------------------------- */
	/*	Admin Panel Items
	/* --------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Side Tabbed Panel", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "admin",
		"type" 	=> "heading",
		"desc" 	=> __("Change the side tabbed panel", MAD_BASE_TEXTDOMAIN),
	);

	// start tab container
	$mad_elements[] = array(
		"slug"	=> "admin",
		"type" => "tab_group_start",
		"id" => "admin_panel_tab_container",
		"class" => 'mad-tab-container',
		"desc" => false
	);

		// start 1 tab
		$mad_elements[] = array(
			'name'=>__('Join Us on VK', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_1",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show VK Widget Community", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_vk_box",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "vk_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Join Us on VK", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Widget Community", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "textarea",
				"id" 	=> "vk_widget_community",
				"desc" 	=> __("How to create widget community see instruction: </br> <a target='_blank' href='https://vk.com/dev/Community'>https://vk.com/dev/Community</a>", MAD_BASE_TEXTDOMAIN),
				"std" => '<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script><div id="vk_groups"></div><script type="text/javascript">VK.Widgets.Group("vk_groups", {mode: 0, width: "220", height: "400", color1: "FFFFFF", color2: "2B587A", color3: "5B7FA6"}, 20003922);</script>'
			);

		// end 1 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 2 tab
		$mad_elements[] = array(
			'name'=>__('Join Us on Facebook', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_2",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show Facebook Box", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_facebook_box",
				"desc" 	=> __("See: <a target='_blank' href='https://developers.facebook.com/docs/plugins/page-plugin'>https://developers.facebook.com/docs/plugins/page-plugin</a>", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "facebook_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Join Us on Facebook", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Facebook Page Name", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "facebook_page_name",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std"   => 'https://www.facebook.com/WordPress'
			);

			$mad_elements[] =	array(
				"name" 	=> __("Hide Cover?", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "switch_set",
				"id" 	=> "facebook_hide_cover",
				"options" => array(
					'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
					'off' => __('No', MAD_BASE_TEXTDOMAIN)
				),
				"std"   => '0',
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Show Facespile?", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "switch_set",
				"id" 	=> "facebook_show_facespile",
				"options" => array(
					'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
					'off' => __('No', MAD_BASE_TEXTDOMAIN)
				),
				"std"   => true,
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Show Posts?", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "switch_set",
				"id" 	=> "facebook_show_posts",
				"options" => array(
					'on' => __('Yes', MAD_BASE_TEXTDOMAIN),
					'off' => __('No', MAD_BASE_TEXTDOMAIN)
				),
				"std"   => true,
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN)
			);

		// end 2 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);


		// start 3 tab
		$mad_elements[] = array(
			'name'=>__('Latest Tweets', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_3",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show Latest Tweets", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_latest_tweets",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] = array(
				"name" 	=> __("Show Follow Button", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_follow_button",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "latest_tweets_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Latest Tweets", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Username", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "latest_tweets_username",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => "fanfbmltemplate"
			);

			$mad_elements[] = array(
				"name" 	=> __("Count", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "number",
				"id" 	=> "latest_tweets_count",
				"min" => 1,
				"max" => 5,
				"std"   => 2,
				"desc" 	=> __("Count tweets", MAD_BASE_TEXTDOMAIN),
			);

		// end 3 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);


		// start 4 tab
		$mad_elements[] = array(
			'name'=>__('Contact Us', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_4",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show Contact Us", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_contact_us",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "contact_us_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Contact Us", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Short text", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "editor",
				"rows"  => 5,
				"id" 	=> "contact_us_short_text",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => "Lorem ipsum dolor sit amet, consectetuer adipis mauris"
			);

		// end 4 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 5 tab
		$mad_elements[] = array(
			'name'=>__('Store Location', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_5",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show Store Location", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_store_location",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "store_location_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Store Location", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Adress", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "editor",
				"rows"  => 5,
				"id" 	=> "store_location_address",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" =>  "8901 Marmora Road, Glasgow, D04 89GR."
			);

			$mad_elements[] =	array(
				"name" 	=> __("Map embed iframe", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "textarea",
				"id" 	=> "store_location_embed_iframe",
				"desc" 	=> __("How to create map see instruction for Embed map: </br> <a target='_blank' href='https://support.google.com/maps/answer/3544418?hl=en'>https://support.google.com/maps/answer/3544418?hl=en</a>", MAD_BASE_TEXTDOMAIN),
				"std" => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193578.74109040972!2d-73.97968099999999!3d40.703312749999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2z0J3RjNGOLdCZ0L7RgNC6LCDQodCo0JA!5e0!3m2!1sru!2sua!4v1424385645246" width="400" height="300" style="border:0"></iframe>'
			);

			$mad_elements[] =	array(
				"name" 	=> __("Phone", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "store_location_phone",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => "800-559-65-80"
			);

			$mad_elements[] =	array(
				"name" 	=> __("Email", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "store_location_email",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("info@companyname.com", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] =	array(
				"name" 	=> __("Opening Hours", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "editor",
				"rows"  => 5,
				"id" 	=> "store_location_opening_hours",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => "Monday - Friday: 08.00-20.00 </br> Saturday: 09.00-15.00 </br> Sunday: closed"
			);

		// end 5 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 6 tab
		$mad_elements[] = array(
			'name'=>__('Instagram', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_6",
			"class" => "mad_tab",
			"desc" => false
		);

			$mad_elements[] = array(
				"name" 	=> __("Show Instagram", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "checkbox",
				"std"   => 1,
				"id" 	=> "show_instagram",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] = array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "instagram_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Instagram", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] = array(
				"name" 	=> __("Iframe", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "textarea",
				"id" 	=> "instagram_iframe",
				"desc" 	=> __("How to create instagram widget see instruction: </br> <a target='_blank' href='http://snapwidget.com/'>http://snapwidget.com</a>", MAD_BASE_TEXTDOMAIN),
				"std" => '<iframe src="http://snapwidget.com/in/?h=YW1hemluZ3xpbnw1NXw0fDR8fG5vfDJ8bm9uZXxvblN0YXJ0fHllc3xubw==&ve=300415" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:228px; height:228px"></iframe>'
			);

		// end 6 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);

		// start 7 tab
		$mad_elements[] = array(
			'name'=>__('Pinterest', MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" => "tab_group_start",
			"id" => "aside_admin_panel_7",
			"class" => "mad_tab",
			"desc" => false
		);

		$mad_elements[] = array(
			"name" 	=> __("Show Pinterest", MAD_BASE_TEXTDOMAIN),
			"slug"	=> "admin",
			"type" 	=> "checkbox",
			"std"   => 1,
			"id" 	=> "show_pinterest",
			"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
			"label" => __("Show it if the checkbox is checked", MAD_BASE_TEXTDOMAIN)
		);

			$mad_elements[] = array(
				"name" 	=> __("Title", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "pinterest_title",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("Pinterest", MAD_BASE_TEXTDOMAIN)
			);

			$mad_elements[] = array(
				"name" 	=> __("Pinterest Username", MAD_BASE_TEXTDOMAIN),
				"slug"	=> "admin",
				"type" 	=> "text",
				"id" 	=> "pinterest_username",
				"desc" 	=> __(" ", MAD_BASE_TEXTDOMAIN),
				"std" => __("pinterest", MAD_BASE_TEXTDOMAIN)
			);

		// end 7 tab
		$mad_elements[] = array(
			"slug"	=> "admin",
			"type" => "tab_group_end",
			"desc" => false
		);

	// end tab container
	$mad_elements[] = array(
		"slug"	=> "admin",
		"type" => "tab_group_end",
		"desc" => false
	);

/* ---------------------------------------------------------------------- */
/*	Import Elements
/* ---------------------------------------------------------------------- */

	$mad_elements[] = array(
		"name" 	=> __("Import demo files", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"type" 	=> "heading",
		"desc" 	=> __("If you are Wordpress newbie or want to get the theme look like one of our demos, then you can make import dummy posts and pages here. It will help you to understand how everything is organized.", MAD_BASE_TEXTDOMAIN),
	);

	$mad_elements[] = array(
		"name" 	=> __("Import Default Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> "<p>
			<strong>" . __('View demo: ', MAD_BASE_TEXTDOMAIN) ."</strong>
			<a target='_blank' href='http://velikorodnov.com/wordpress/flatastic/classic/'>View Demo Online</a>
			</p> You can import default content dummy posts and pages here </br> </br>
			<strong>Before Import Data install you must install and activate the following plugins: </strong>
			<ul>
				<li>Flatastic Content Types</li>
				<li>WPBakery Visual Composer</li>
				<li>LayerSlider WP</li>
				<li>WPML Multilingual CMS</li>
				<li>Indeed Smart PopUp</li>
				<li>Woo Sale Revolution:Flash Sale + Dynamic Discounts</li>
				<li><a target='_blank' href='https://wordpress.org/plugins/woocommerce/'>Woocommerce</a></li>
				<li><a target='_blank' href='https://wordpress.org/plugins/contact-form-7/'>Contact Form 7</a></li>
			</ul>",
		"id" 	=> "import_default",
		"type" 	=> "import",
		"path" => "admin/demo/default/default",
		"source" => "admin/demo/default",
		"image" => "admin/demo/default/default.jpg"
	);

	$mad_elements[] = array(
		"name" 	=> __("Import Corporate Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> "<p>
				<strong>" . __('View demo: ', MAD_BASE_TEXTDOMAIN) ."</strong>
				<a target='_blank' href='http://velikorodnov.com/wordpress/flatastic/corporate'>View Demo Online</a>
				</p> You can import default content dummy posts and pages here </br> </br>
				<strong>Before Import Data install you must install and activate the following plugins: </strong>
				<ul>
					<li>Flatastic Content Types</li>
					<li>WPBakery Visual Composer</li>
					<li>LayerSlider WP</li>
					<li>WPML Multilingual CMS</li>
					<li>Indeed Smart PopUp</li>
					<li>Woo Sale Revolution:Flash Sale + Dynamic Discounts</li>
					<li><a target='_blank' href='https://wordpress.org/plugins/woocommerce/'>Woocommerce</a></li>
					<li><a target='_blank' href='https://wordpress.org/plugins/contact-form-7/'>Contact Form 7</a></li>
				</ul>",
		"id" 	=> "import_corporate",
		"type" 	=> "import",
		"path" => "admin/demo/corporate/corporate",
		"source" => "admin/demo/corporate",
		"image" => "admin/demo/corporate/corporate.jpg"
	);

	$mad_elements[] = array(
		"name" 	=> __("Import Construction Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> "<p>
				<strong>" . __('View demo: ', MAD_BASE_TEXTDOMAIN) ."</strong>
				<a target='_blank' href='http://velikorodnov.com/wordpress/flatastic/construction/'>View Demo Online</a>
				</p> You can import default content dummy posts and pages here </br> </br>
				<strong>Before Import Data install you must install and activate the following plugins: </strong>
				<ul>
					<li>Flatastic Content Types</li>
					<li>WPBakery Visual Composer</li>
					<li>LayerSlider WP</li>
					<li>Slider Revolution</li>
					<li>WPML Multilingual CMS</li>
					<li>Indeed Smart PopUp</li>
					<li>Woo Sale Revolution:Flash Sale + Dynamic Discounts</li>
					<li><a target='_blank' href='https://wordpress.org/plugins/woocommerce/'>Woocommerce</a></li>
					<li><a target='_blank' href='https://wordpress.org/plugins/contact-form-7/'>Contact Form 7</a></li>
				</ul>",
		"id" 	=> "import_construction",
		"type" 	=> "import",
		"path" => "admin/demo/construction/construction",
		"source" => "admin/demo/construction",
		"image" => "admin/demo/construction/construction.jpg"
	);

	$mad_elements[] = array(
		"name" 	=> __("Import Interior Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> "<p>
					<strong>" . __('View demo: ', MAD_BASE_TEXTDOMAIN) ."</strong>
					<a target='_blank' href='http://velikorodnov.com/wordpress/flatastic/interior/'>View Demo Online</a>
					</p> You can import default content dummy posts and pages here </br> </br>
					<strong>Before Import Data install you must install and activate the following plugins: </strong>
					<ul>
						<li>Flatastic Content Types</li>
						<li>WPBakery Visual Composer</li>
						<li>LayerSlider WP</li>
						<li>Slider Revolution</li>
						<li>WPML Multilingual CMS</li>
						<li>Woo Sale Revolution:Flash Sale + Dynamic Discounts</li>
						<li><a target='_blank' href='https://wordpress.org/plugins/woocommerce/'>Woocommerce</a></li>
						<li><a target='_blank' href='https://wordpress.org/plugins/contact-form-7/'>Contact Form 7</a></li>
					</ul>",
		"id" 	=> "import_interior",
		"type" 	=> "import",
		"path" => "admin/demo/interior/interior",
		"source" => "admin/demo/interior",
		"image" => "admin/demo/interior/interior.jpg"
	);

	$mad_elements[] = array(
		"name" 	=> __("Import One Page Content", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> "<p>
			<strong>" . __('View demo: ', MAD_BASE_TEXTDOMAIN) ."</strong>
			<a target='_blank' href='http://velikorodnov.com/wordpress/flatastic/one-page/'>View Demo Online</a>
			</p> You can import default content dummy posts and pages here </br> </br>
			<strong>Before Import Data install you must install and activate the following plugins: </strong>
			<ul>
				<li>Flatastic Content Types</li>
				<li>WPBakery Visual Composer</li>
				<li>Slider Revolution</li>
				<li><a target='_blank' href='https://wordpress.org/plugins/contact-form-7/'>Contact Form 7</a></li>
			</ul>",
		"id" 	=> "import_onepage",
		"type" 	=> "import",
		"path" => "admin/demo/onepage/onepage",
		"source" => "admin/demo/onepage",
		"image" => "admin/demo/onepage/onepage.jpg"
	);

	$mad_elements[] = array(
		"name" 	=> __("Export Theme Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> __("Export a theme configuration file here. ", MAD_BASE_TEXTDOMAIN),
		"id" 	=> "export_config_file",
		"type" 	=> "export_config_file"
	);

	$mad_elements[] = array(
		"name" 	=> __("Import Theme Settings", MAD_BASE_TEXTDOMAIN),
		"slug"	=> "import",
		"desc" 	=> __("Upload a theme configuration file here. ", MAD_BASE_TEXTDOMAIN),
		"id" 	=> "import_config_file",
		"type" 	=> "import_config_file"
	);
