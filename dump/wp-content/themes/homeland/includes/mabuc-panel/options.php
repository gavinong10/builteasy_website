<?php
	function homeland_options_setup() {
		global $themename, $shortname, $options, $version, $help, $author, $homeland_blog_category;

		//Theme Variables
		$version = "2.8.0";	
		$author = "codeex";	
		$themename = "Homeland";
		$shortname = "homeland";	
		$help = "http://themecss.com/codeex/support/theme/homeland/";

		//Theme Fonts
		$homeland_theme_fonts = array( __( 'Default', 'codeex_theme_name' ), "Abel", "Alegreya Sans", "Archivo Narrow", "Arimo", "Armata", "Asap", "Cabin", "Cambo", "Carrois Gothic", "Crimson Text", "Didact Gothic", "Dosis", "Droid Sans", "Duru Sans", "EB Garamond", "Exo", "Gafata", "Gudea", "Glegoo", "Hind", "Inconsolata", "Istok Web", "Josefin Sans", "Karla", "Lato", "Lora", "Merriweather", "Monda", "Montserrat", "Muli", "News Cycle", "Nobile", "Noticia Text", "Noto Sans", "Open Sans", "Oswald", "Oxygen", "PT Sans", "Pontano Sans", "Puritan", "Questrial", "Quicksand", "Raleway", "Source Sans Pro", "Sintony", "Slabo", "Titillium Web", "Ubuntu", "Voces" );

		//Theme Patterns
		$homeland_theme_patterns = array( __( 'Select Pattern', 'codeex_theme_name' ), "Symphony", "Contemporary China", "Eight Horns", "Swirl", "Mini Waves", "Skulls", "Pentagon", "Halftone", "Giftly", "Food", "Sprinkles", "Geometry", "Dimension", "Pixel Weave", "Hoffman", "Gray Lines", "Noise Lines", "Tiny Grid", "Bullseye", "Gray Paper", "Norwegian Rose", "Subtle Net", "Polyester Lite", "Absurdity", "White Bed Sheet", "Subtle Stripes", "Light Mesh", "Rough Diagonal", "Arabesque", "Stack Circles", "Hexellence", "White Texture", "Concrete Wall", "Brush Aluminum", "Groovepaper", "Diagonal Noise", "Rocky Wall", "Whitey", "Bright Squares", "Freckles", "Wallpaper", "Project Paper", "Cubes", "Washi", "Dot Noise", "xv", "Little Plaid", "Old Wall", "Connect", "Ravenna", "Smooth Wall", "Tapestry", "Psychedelic", "Scribble Light", "GPlay", "Lil Fiber", "First Aid", "Frenchstucco", "Light Wool", "Gradient Squares", "Escheresque", "Climpek", "Lyonnette", "Gray Floral", "Reticular Tissue" );

		//Theme Sort By
		$homeland_theme_sort_by = array( "ID", "author", "title", "name", "date", "modified", "parent", "rand", "comment_count", "menu_order" );

		//Theme Options
		$options = array(	


			/*-----------------------
			Theme Settings
			-----------------------*/

			array("name" => __( 'Theme Settings - W W W . N U L L 2 4 . I R', 'codeex_theme_name' ), "type" => "top_section"),
			array("type" => "close"),	


			/*-----------------------
			General Settings
			-----------------------*/

			array("name" => __( 'General', 'codeex_theme_name' ),
				   "type" => "section",
		         "icon" => "fa fa-cog"),
					
			array("type" => "open"),

			array("name" => __( 'Main<a href="http://goo.gl/EZNSSi" target="_blank"><img class="alignnone" src="http://s6.picofile.com/file/8212501250/logo.png" alt="" width="379" height="71" /></a>', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Responsive Layout', 'codeex_theme_name' ),
					"desc" => __( 'Do you want to disable the responsive style of this theme? then tick the box', 'codeex_theme_name' ),
					"id" => $shortname."_site_layout",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Favicon', 'codeex_theme_name' ),
					"desc" => __( 'Upload your favorite icon for your website', 'codeex_theme_name' ),
					"id" => $shortname."_favicon",
					"type" => "img_preview",
					"std" => ""),
					
			array("name" => __( 'Logo', 'codeex_theme_name' ),
					"desc" => __( 'Upload your company logo', 'codeex_theme_name' ),
					"id" => $shortname."_logo",
					"type" => "img_preview",
					"std" => ""),	

			array("name" => __( 'Logo Retina', 'codeex_theme_name' ),
					"desc" => __( 'Upload your website logo for retina feature (NOTE: you need to upload image with @2x in your filename like this image@2x.jpg)', 'codeex_theme_name' ),
					"id" => $shortname."_logo_retina",
					"type" => "img_preview",
					"std" => ""),	

			array("name" => __( 'Hide Agents', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide agents or agency all over the theme', 'codeex_theme_name' ),
					"id" => $shortname."_all_agents",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Disable Preloader', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to disbale preloader', 'codeex_theme_name' ),
					"id" => $shortname."_disable_preloader",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Preloader Icon', 'codeex_theme_name' ),
					"desc" => __( 'Upload your preloader icon use when your site is still loading, please upload only .gif extension', 'codeex_theme_name' ),
					"id" => $shortname."_preloader_icon",
					"type" => "img_preview",
					"std" => ""),

			array("name" => __( 'Map Pointer', 'codeex_theme_name' ),
					"desc" => __( 'Upload your map pointer icon', 'codeex_theme_name' ),
					"id" => $shortname."_map_pointer_icon",
					"type" => "img_preview",
					"std" => ""),	

			array("name" => __( 'Map Marker Clusterer', 'codeex_theme_name' ),
					"desc" => __( 'Upload your map marker clusterer icon', 'codeex_theme_name' ),
					"id" => $shortname."_map_pointer_clusterer_icon",
					"type" => "img_preview",
					"std" => ""),	

			array("name" => __( 'Styles', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Layout Style', 'codeex_theme_name' ),
					"desc" => __( 'Select your theme layout style', 'codeex_theme_name' ),
					"id" => $shortname."_theme_layout",
					"type" => "select",
					"options" => array("Fullwidth", "Boxed", "Boxed Left"),
					"std" => ""),

			array("name" => __( 'Mobile Menu Style', 'codeex_theme_name' ),
					"desc" => __( 'Select your mobile menu style', 'codeex_theme_name' ),
					"id" => $shortname."_theme_mobile_menu",
					"type" => "select",
					"options" => array("Classic", "Modern"),
					"std" => ""),

			array("name" => __( 'Paging Style', 'codeex_theme_name' ),
					"desc" => __( 'Select your theme paging navigation style', 'codeex_theme_name' ),
					"id" => $shortname."_pnav",
					"type" => "select",
					"options" => array("Pagination", "Next Previous Link"),
					"std" => ""),

			array("name" => __( 'Custom CSS', 'codeex_theme_name' ),
					"desc" => __( 'Enter your custom CSS tags here', 'codeex_theme_name' ),
					"id" => $shortname."_custom_css",
					"type" => "textarea",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Typography Settings
			-----------------------*/

			array("name" => __( 'Typography', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-text-height"),
					
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), 
					"desc" => __( 'This settings is use for adjusting your font size', 'codeex_theme_name' ),
					"type" => "headers"),

			array("name" => __( 'Font Family', 'codeex_theme_name' ),
					"desc" => __( 'Select your theme font family', 'codeex_theme_name' ),
					"id" => $shortname."_theme_font",
					"type" => "select",
					"options" => $homeland_theme_fonts,
					"std" => ""),

			array("name" => __( 'Body', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for body font size (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_body_font_size",
					"type" => "slide_amount_fonts",
					"std" => "12"),		

			array("name" => __( 'Line Height', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for body line height (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_body_line_height",
					"type" => "slide_amount_fonts",
					"std" => "24"),	

			array("name" => __( 'Homepage Header', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for homepage header (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_homepage_header_font_size",
					"type" => "slide_amount_fonts",
					"std" => "24"),		

			array("name" => __( 'Page Top Header', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for page top header (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_page_top_header_font_size",
					"type" => "slide_amount_fonts",
					"std" => "35"),		

			array("name" => __( 'Page Top Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for page top subtitle (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_page_top_subtitle_font_size",
					"type" => "slide_amount_fonts",
					"std" => "12"),

			array("name" => __( 'Page Content Header', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for page content header (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_page_content_header_font_size",
					"type" => "slide_amount_fonts",
					"std" => "22"),

			array("name" => __( 'Sidebar Header', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for sidebar header (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_sidebar_header_font_size",
					"type" => "slide_amount_fonts",
					"std" => "16"),

			array("name" => __( 'Footer Header', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for footer header (pixel)', 'codeex_theme_name' ),
					"id" => $shortname."_footer_font_size",
					"type" => "slide_amount_fonts",
					"std" => "24"),

			array("name" => __( 'Color Scheme', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Global Color', 'codeex_theme_name' ),
					"desc" => __( 'Enter your global color for your theme', 'codeex_theme_name' ),
					"id" => $shortname."_global_color",
					"type" => "color",
					"std" => ""),	

			array("name" => __( 'Top Header Background', 'codeex_theme_name' ),
					"desc" => __( 'Enter your top header background color', 'codeex_theme_name' ),
					"id" => $shortname."_top_header_bg_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Menu Text', 'codeex_theme_name' ),
					"desc" => __( 'Enter menu text color', 'codeex_theme_name' ),
					"id" => $shortname."_menu_text_color",
					"type" => "color",
					"std" => ""),	

			array("name" => __( 'Menu Text Current', 'codeex_theme_name' ),
					"desc" => __( 'Enter menu text color for active and hovered menu', 'codeex_theme_name' ),
					"id" => $shortname."_menu_text_color_active",
					"type" => "color",
					"std" => ""),	

			array("name" => __( 'Menu Background', 'codeex_theme_name' ),
					"desc" => __( 'Enter menu background color text when active and hovered', 'codeex_theme_name' ),
					"id" => $shortname."_menu_bg_color",
					"type" => "color",
					"std" => ""),	

			array("name" => __( 'Headers', 'codeex_theme_name' ),
					"desc" => __( 'Enter your header text color', 'codeex_theme_name' ),
					"id" => $shortname."_header_text_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Sidebar Header', 'codeex_theme_name' ),
					"desc" => __( 'Enter your sidebar header text color', 'codeex_theme_name' ),
					"id" => $shortname."_sidebar_text_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Button Text', 'codeex_theme_name' ),
					"desc" => __( 'Enter your button text color', 'codeex_theme_name' ),
					"id" => $shortname."_button_text_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Button Background', 'codeex_theme_name' ),
					"desc" => __( 'Enter your button background color', 'codeex_theme_name' ),
					"id" => $shortname."_button_bg_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Button Hover', 'codeex_theme_name' ),
					"desc" => __( 'Enter your button hover background color', 'codeex_theme_name' ),
					"id" => $shortname."_button_bg_hover_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Footer Text', 'codeex_theme_name' ),
					"desc" => __( 'Enter your footer text color', 'codeex_theme_name' ),
					"id" => $shortname."_footer_text_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Footer Background', 'codeex_theme_name' ),
					"desc" => __( 'Enter your footer background color', 'codeex_theme_name' ),
					"id" => $shortname."_footer_bg_color",
					"type" => "color",
					"std" => ""),

			array("name" => __( 'Sliding Bar', 'codeex_theme_name' ),
					"desc" => __( 'Enter your top sliding bar background color', 'codeex_theme_name' ),
					"id" => $shortname."_slide_top_bg_color",
					"type" => "color",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Background Settings
			-----------------------*/

			array("name" => __( 'Background', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-square"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Background Type', 'codeex_theme_name' ),
					"desc" => __( 'Select the background type you want, either full image or pattern', 'codeex_theme_name' ),
					"id" => $shortname."_bg_type",
					"type" => "select",
					"options" => array("Image", "Pattern", "Color"),
					"std" => ""),

			array("name" => __( 'Image', 'codeex_theme_name' ),
					"desc" => __( 'Upload your default background image', 'codeex_theme_name' ),
					"id" => $shortname."_default_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Pattern', 'codeex_theme_name' ),
					"desc" => __( 'Select 65 built-in background patterns. You can only use this if you are using Pattern Background Type', 'codeex_theme_name' ),
					"id" => $shortname."_pattern",
					"type" => "select",
					"options" => $homeland_theme_patterns,
					"std" => ""),

			array("name" => __( 'Color', 'codeex_theme_name' ),
					"desc" => __( 'Enter your background color for this theme', 'codeex_theme_name' ),
					"id" => $shortname."_bg_color",
					"type" => "color",
					"std" => ""),	

			array("name" => __( 'Images', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Archive', 'codeex_theme_name' ),
					"desc" => __( 'Upload your archive background image', 'codeex_theme_name' ),
					"id" => $shortname."_archive_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Taxonomy', 'codeex_theme_name' ),
					"desc" => __( 'Upload your taxonomy background image', 'codeex_theme_name' ),
					"id" => $shortname."_taxonomy_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Agent', 'codeex_theme_name' ),
					"desc" => __( 'Upload your agent background image for agent single page', 'codeex_theme_name' ),
					"id" => $shortname."_agent_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Search', 'codeex_theme_name' ),
					"desc" => __( 'Upload your search background image', 'codeex_theme_name' ),
					"id" => $shortname."_search_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( '404 Not Found', 'codeex_theme_name' ),
					"desc" => __( 'Upload your 404 not found background image', 'codeex_theme_name' ),
					"id" => $shortname."_notfound_bgimage",
					"type" => "upload",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Headers Settings
			-----------------------*/

			array("name" => __( 'Headers', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-align-left"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Sticky Header', 'codeex_theme_name' ),
					"desc" => __( 'Enable Sticky header effect', 'codeex_theme_name' ),
					"id" => $shortname."_sticky_header",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Top Sliding Bar', 'codeex_theme_name' ),
					"desc" => __( 'Enable Top Sliding Bar at the top of header', 'codeex_theme_name' ),
					"id" => $shortname."_enable_slide_bar",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Header Style', 'codeex_theme_name' ),
					"desc" => __( 'Select your theme header style', 'codeex_theme_name' ),
					"id" => $shortname."_theme_header",
					"type" => "select",
					"options" => array("Default", "Header 2", "Header 3", "Header 4", "Header 5", "Header 6", "Header 7"),
					"std" => ""),

			array("name" => __( 'Call us', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your call us', 'codeex_theme_name' ),
					"id" => $shortname."_call_us_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Search', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your search button', 'codeex_theme_name' ),
					"id" => $shortname."_search_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Hide Header Image', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide header images at the top', 'codeex_theme_name' ),
					"id" => $shortname."_hide_header_image",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Hide Page Title and Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide page title and subtitle', 'codeex_theme_name' ),
					"id" => $shortname."_hide_ptitle_stitle",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Buttons', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide Login', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide login button', 'codeex_theme_name' ),
					"id" => $shortname."_hide_login",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Login', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your login button', 'codeex_theme_name' ),
					"id" => $shortname."_login_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Custom Link Login', 'codeex_theme_name' ),
					"desc" => __( 'Add your custom link for login button', 'codeex_theme_name' ),
					"id" => $shortname."_login_link",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Logout', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your logout button, you only see this label if you already logged in', 'codeex_theme_name' ),
					"id" => $shortname."_logout_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Hide Register', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide register button', 'codeex_theme_name' ),
					"id" => $shortname."_hide_register",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Register', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your register button', 'codeex_theme_name' ),
					"id" => $shortname."_register_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Custom Link Register', 'codeex_theme_name' ),
					"desc" => __( 'Add your custom link for register button', 'codeex_theme_name' ),
					"id" => $shortname."_register_link",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Social Icons', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Brand Color', 'codeex_theme_name' ),
					"desc" => __( 'Enable social color branding', 'codeex_theme_name' ),
					"id" => $shortname."_brand_color",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'RSS Feed URL', 'codeex_theme_name' ),
					"desc" => __( 'Enter rss feed link', 'codeex_theme_name' ),
					"id" => $shortname."_rss",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Twitter ID', 'codeex_theme_name' ),
					"desc" => __( 'Enter twitter id', 'codeex_theme_name' ),
					"id" => $shortname."_twitter",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Facebook ID', 'codeex_theme_name' ),
					"desc" => __( 'Enter facebook id', 'codeex_theme_name' ),
					"id" => $shortname."_facebook",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Youtube URL', 'codeex_theme_name' ),
					"desc" => __( 'Enter your youtube profile link', 'codeex_theme_name' ),
					"id" => $shortname."_youtube",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Pinterest', 'codeex_theme_name' ),
					"desc" => __( 'Enter your pinterest id', 'codeex_theme_name' ),
					"id" => $shortname."_pinterest",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'LinkedIn URL', 'codeex_theme_name' ),
					"desc" => __( 'Enter your linkedin link', 'codeex_theme_name' ),
					"id" => $shortname."_linkedin",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Dribbble', 'codeex_theme_name' ),
					"desc" => __( 'Enter your dribbble id', 'codeex_theme_name' ),
					"id" => $shortname."_dribbble",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Instagram', 'codeex_theme_name' ),
					"desc" => __( 'Enter your instagram id', 'codeex_theme_name' ),
					"id" => $shortname."_instagram",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Google Plus URL', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google plus profile link', 'codeex_theme_name' ),
					"id" => $shortname."_gplus",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Images', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Default', 'codeex_theme_name' ),
					"desc" => __( 'Upload your default header image', 'codeex_theme_name' ),
					"id" => $shortname."_default_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Archive', 'codeex_theme_name' ),
					"desc" => __( 'Upload your archive header image', 'codeex_theme_name' ),
					"id" => $shortname."_archive_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Taxonomy', 'codeex_theme_name' ),
					"desc" => __( 'Upload your taxonomy header image', 'codeex_theme_name' ),
					"id" => $shortname."_taxonomy_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Agent', 'codeex_theme_name' ),
					"desc" => __( 'Upload your header image for agent single page', 'codeex_theme_name' ),
					"id" => $shortname."_agent_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Search', 'codeex_theme_name' ),
					"desc" => __( 'Upload your search header image', 'codeex_theme_name' ),
					"id" => $shortname."_search_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( '404 Not Found', 'codeex_theme_name' ),
					"desc" => __( 'Upload your 404 not found header image', 'codeex_theme_name' ),
					"id" => $shortname."_notfound_hdimage",
					"type" => "upload",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Advance Search Settings
			-----------------------*/

			array("name" => __( 'Advance Search', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-search"),
			
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Disable advance search in all pages, except homepage', 'codeex_theme_name' ),
					"id" => $shortname."_disable_advance_search",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Hide Map', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide google map in advance search page', 'codeex_theme_name' ),
					"id" => $shortname."_gmap_search",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add your search button label', 'codeex_theme_name' ),
					"id" => $shortname."_search_button_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'ID', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide property id textfield in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_pid",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your property id label', 'codeex_theme_name' ),
					"id" => $shortname."_pid_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Location', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide location selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_location",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your location label', 'codeex_theme_name' ),
					"id" => $shortname."_location_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Status', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide status selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_status",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your status label', 'codeex_theme_name' ),
					"id" => $shortname."_status_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Type', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide property type selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_property_type",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your property type label', 'codeex_theme_name' ),
					"id" => $shortname."_property_type_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Bedrooms', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide bedrooms selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_bed",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your bedrooms label', 'codeex_theme_name' ),
					"id" => $shortname."_bed_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Selections', 'codeex_theme_name' ),
					"desc" => __( 'Add your bedrooms selections', 'codeex_theme_name' ),
					"id" => $shortname."_bed_number",
					"type" => "text",
					"std" => "1, 2, 3"),

			array("name" => __( 'Bathrooms', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide bathrooms selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_bath",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your bathrooms label', 'codeex_theme_name' ),
					"id" => $shortname."_bath_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Selections', 'codeex_theme_name' ),
					"desc" => __( 'Add your bathrooms selections', 'codeex_theme_name' ),
					"id" => $shortname."_bath_number",
					"type" => "text",
					"std" => "1, 2, 3"),

			array("name" => __( 'Minimum Price', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide minimum price selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_min_price",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your minimum price label', 'codeex_theme_name' ),
					"id" => $shortname."_min_price_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Price Range', 'codeex_theme_name' ),
					"desc" => __( 'Add your minimum price values selection', 'codeex_theme_name' ),
					"id" => $shortname."_min_price_value",
					"type" => "textarea",
					"std" => "5000, 10000, 50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000, 1500000, 2000000, 2500000, 5000000"),

			array("name" => __( 'Maximum Price', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide maximum price selectbox in advance search', 'codeex_theme_name' ),
					"id" => $shortname."_hide_max_price",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Label', 'codeex_theme_name' ),
					"desc" => __( 'Add your maximum price label', 'codeex_theme_name' ),
					"id" => $shortname."_max_price_label",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Price Range', 'codeex_theme_name' ),
					"desc" => __( 'Add your maximum price values selection', 'codeex_theme_name' ),
					"id" => $shortname."_max_price_value",
					"type" => "textarea",
					"std" => "10000, 50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000, 1500000, 2000000, 2500000, 5000000, 10000000"),

			array("type" => "close"),


			/*-----------------------
			Agents Settings
			-----------------------*/

			array("name" => __( 'Agents', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-group"),
			
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your agents number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_agent_page_limit",
					"type" => "text",
					"std" => "5"),

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your agent order type', 'codeex_theme_name' ),
					"id" => $shortname."_agent_page_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for agents', 'codeex_theme_name' ),
					"id" => $shortname."_agent_page_orderby",
					"type" => "select",
					"options" => array( "ID", "display_name", "name", "login", "nicename", "email", "url", "registered", "post_count", "meta_value" ),
					"std" => ""),

			array("name" => __( 'Agent Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your agent button', 'codeex_theme_name' ),
					"id" => $shortname."_agent_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Profile', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Enter your agents header in profile page', 'codeex_theme_name' ),
					"id" => $shortname."_agent_profile_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Enter your agents subtitle in profile page', 'codeex_theme_name' ),
					"id" => $shortname."_agent_profile_subtitle",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your agent profile layout', 'codeex_theme_name' ),
					"id" => $shortname."_agent_profile_layout",
					"type" => "select",
					"options" => array( __( 'Default', 'codeex_theme_name' ), "Fullwidth" ),
					"std" => ""),	

			array("name" => __( 'List Header', 'codeex_theme_name' ),
					"desc" => __( 'Add list header label in your agent profile page', 'codeex_theme_name' ),
					"id" => $shortname."_agent_list_header",
					"type" => "text",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Footer Settings
			-----------------------*/

			array("name" => __( 'Footer', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-align-justify"),
			
			array("type" => "open"),

			array("name" => __( 'Hide Widgets', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide footer widgets', 'codeex_theme_name' ),
					"id" => $shortname."_hide_widgets",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your footer layout style', 'codeex_theme_name' ),
					"id" => $shortname."_footer_layout",
					"type" => "select",
					"options" => array( 
						__( 'Default', 'codeex_theme_name' ), 
						"Layout 2", 
						"Layout 3", 
						"Layout 4", 
						"Layout 5",
						"Layout 6", 
					),
					"std" => ""),	

			array("name" => __( 'Copyright Text', 'codeex_theme_name' ),
					"desc" => __( 'Enter your copyright text here', 'codeex_theme_name' ),
					"id" => $shortname."_copyright_text",
					"type" => "text",
					"std" => __( 'All Rights Reserved', 'codeex_theme_name' )),

			array("name" => __( 'Google Analytics Code', 'codeex_theme_name' ),
					"desc" => __( 'You can paste your Google Analytics or other tracking code in this box', 'codeex_theme_name' ),
					"id" => $shortname."_ga_code",
					"type" => "textarea",
					"std" => ""),

			array("type" => "close"),


		/*-----------------------
		Theme Pages Settings
		-----------------------*/

		array("name" => __( 'Theme Pages - W W W . N U L L 2 4 . I R', 'codeex_theme_name' ), "type" => "top_section"),
		array("type" => "close"),


			/*-----------------------
			Homepage Settings
			-----------------------*/

			array("name" => __( 'Homepage', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-home"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide Advance Search', 'codeex_theme_name' ),
					"desc" => __( 'Hide Advance Search in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_advance_search",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Hide Bottom 2 Columns', 'codeex_theme_name' ),
					"desc" => __( 'Hide the two columns at the bottom', 'codeex_theme_name' ),
					"id" => $shortname."_hide_two_cols",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Hide Bottom 3 Columns', 'codeex_theme_name' ),
					"desc" => __( 'Hide the three columns at the bottom', 'codeex_theme_name' ),
					"id" => $shortname."_hide_three_cols",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Video Url', 'codeex_theme_name' ),
					"desc" => __( 'Add your video embedded code here (ex. http://player.vimeo.com/video/21942776 and http://youtube.com/embed/68AqHwgk2s8)', 'codeex_theme_name' ),
					"id" => $shortname."_video_url",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Slider', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide Slider Details', 'codeex_theme_name' ),
					"desc" => __( 'Hide slider details box', 'codeex_theme_name' ),
					"id" => $shortname."_hide_properties_details",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Display List', 'codeex_theme_name' ),
					"desc" => __( 'Select your post slider display list, default is featured properties', 'codeex_theme_name' ),
					"id" => $shortname."_slider_display_list",
					"type" => "select",
					"options" => array("Properties", "Featured Properties", "Blog", "Portfolio"),
					"std" => ""),	

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your slider number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_slider_limit",
					"type" => "text",
					"std" => "5"),

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your slider order type', 'codeex_theme_name' ),
					"id" => $shortname."_slider_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for slider', 'codeex_theme_name' ),
					"id" => $shortname."_slider_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => ""),

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your slider button', 'codeex_theme_name' ),
					"id" => $shortname."_slider_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Welcome', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Welcome box in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_welcome",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Enter your welcome header', 'codeex_theme_name' ),
					"id" => $shortname."_welcome_header",
					"type" => "text",
					"std" => __( 'Start a good life', 'codeex_theme_name' )),

			array("name" => __( 'Welcome Text', 'codeex_theme_name' ),
					"desc" => __( 'Enter your welcome text or paragraph here', 'codeex_theme_name' ),
					"id" => $shortname."_welcome_text",
					"type" => "textarea",
					"std" => __( 'Our Services go beyond just providing safe, serene and beautiful exclusive house. As an extended service, we also provide house packages that give you access to an array of house designs, suited to a variety of lot sizes and unique family needs', 'codeex_theme_name' )),

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your welcome button', 'codeex_theme_name' ),
					"id" => $shortname."_welcome_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Button Link', 'codeex_theme_name' ),
					"desc" => __( 'Add link of your welcome button', 'codeex_theme_name' ),
					"id" => $shortname."_welcome_link",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Background Image', 'codeex_theme_name' ),
					"desc" => __( 'Upload your welcome background image', 'codeex_theme_name' ),
					"id" => $shortname."_welcome_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Properties', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Properties box in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_properties",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your properties number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_property_limit",
					"type" => "text",
					"std" => "6"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your properties', 'codeex_theme_name' ),
					"id" => $shortname."_property_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Limit Featured', 'codeex_theme_name' ),
					"desc" => __( 'Enter your properties number of posts limit for featured properties', 'codeex_theme_name' ),
					"id" => $shortname."_featured_property_limit",
					"type" => "text",
					"std" => "2"),

			array("name" => __( 'Header Featured', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your featured properties', 'codeex_theme_name' ),
					"id" => $shortname."_featured_property_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Blog', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your blog number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_blog_limit",
					"type" => "text",
					"std" => "3"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your blog list', 'codeex_theme_name' ),
					"id" => $shortname."_blog_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Category', 'codeex_theme_name' ),
					"desc" => __( 'Select the category you want to display for blog', 'codeex_theme_name' ),
					"id" => $shortname."_blog_category",
					"type" => "select",
					"options" => $homeland_blog_category,
					"std" => ""),

			array("name" => __( 'Agent', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your agents number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_agent_limit",
					"type" => "text",
					"std" => "3"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your agents list', 'codeex_theme_name' ),
					"id" => $shortname."_agents_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your agent order type', 'codeex_theme_name' ),
					"id" => $shortname."_agent_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for agents', 'codeex_theme_name' ),
					"id" => $shortname."_agent_orderby",
					"type" => "select",
					"options" => array(
						"ID", "display_name", "name", "login", "nicename", "email", "url", "registered", "post_count", "meta_value"
					),
					"std" => ""),

			array("name" => __( 'Services', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Services box in Homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_services",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your services number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_services_limit",
					"type" => "text",
					"std" => "3"),

			array("name" => __( 'Background Image', 'codeex_theme_name' ),
					"desc" => __( 'Upload your services background image', 'codeex_theme_name' ),
					"id" => $shortname."_services_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Testimonials', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Testimonials box in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_testimonials",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your testimonials number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_testi_limit",
					"type" => "text",
					"std" => "3"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your testimonials list', 'codeex_theme_name' ),
					"id" => $shortname."_testi_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Background Image', 'codeex_theme_name' ),
					"desc" => __( 'Upload your testimonials background image', 'codeex_theme_name' ),
					"id" => $shortname."_testimonials_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Partners', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Partners in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_partners",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your partners list', 'codeex_theme_name' ),
					"id" => $shortname."_partners_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your partner number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_partners_limit",
					"type" => "text",
					"std" => "5"),

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your partner order type', 'codeex_theme_name' ),
					"id" => $shortname."_partner_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for partner', 'codeex_theme_name' ),
					"id" => $shortname."_partner_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => ""),

			array("name" => __( 'Portfolio', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Hide Portfolio in homepage', 'codeex_theme_name' ),
					"id" => $shortname."_hide_portfolio",
					"type" => "checkbox",
					"std" => ""),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your portfolio list', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Enter your portfolio number of posts limit', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_limit",
					"type" => "text",
					"std" => "6"),

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your portfolio order type', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_home_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for portfolio', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_home_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => ""),

			array("name" => __( 'Google Map', 'codeex_theme_name' ), 
					"desc" => __( 'Get google map latitude and longitude value <a href="http://latlong.net" target="_blank">here</a>', 'codeex_theme_name' ),
					"type" => "headers"),

			array("name" => __( 'Latitude', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map latitude', 'codeex_theme_name' ),
					"id" => $shortname."_home_map_lat",
					"type" => "text",
					"std" => "37.0625"),

			array("name" => __( 'Longitude', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map longitude', 'codeex_theme_name' ),
					"id" => $shortname."_home_map_lng",
					"type" => "text",
					"std" => "-95.677068"),

			array("name" => __( 'Map Zoom', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for map zoom from 1-20', 'codeex_theme_name' ),
					"id" => $shortname."_home_map_zoom",
					"type" => "slide_amount",
					"std" => "8"),		

			array("type" => "close"),


			/*-----------------------
			Property Settings
			-----------------------*/

			array("name" => __( 'Properties', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-th"),

			array("name" => __( 'Currency', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Currency Sign', 'codeex_theme_name' ),
					"desc" => __( 'Global - Currency for properties', 'codeex_theme_name' ),
					"id" => $shortname."_property_currency",
					"type" => "text",
					"std" => ""),	

			array("name" => __( 'Price Format', 'codeex_theme_name' ),
					"desc" => __( 'Global - Price Format for property', 'codeex_theme_name' ),
					"id" => $shortname."_price_format",
					"type" => "select",
					"options" => array("Comma", "Dot", "Europe", "Brazil", "None"),
					"std" => ""),	

			array("name" => __( 'Position of Currency Sign', 'codeex_theme_name' ),
					"desc" => __( 'Select your currency position sign in property price', 'codeex_theme_name' ),
					"id" => $shortname."_property_currency_sign",
					"type" => "select",
					"options" => array("Before", "After"),
					"std" => ""),	

			array("name" => __( 'Decimal', 'codeex_theme_name' ),
					"desc" => __( 'Global - Decimal number to be display for property price', 'codeex_theme_name' ),
					"id" => $shortname."_property_decimal",
					"type" => "text",
					"std" => ""),	

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Hide Map', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide list property map', 'codeex_theme_name' ),
					"id" => $shortname."_hide_map_list",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Global - Posts per page for properties', 'codeex_theme_name' ),
					"id" => $shortname."_num_properties",
					"type" => "text",
					"std" => "10"),	

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your ordering parameter in properties album', 'codeex_theme_name' ),
					"id" => $shortname."_album_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter in properties album', 'codeex_theme_name' ),
					"id" => $shortname."_album_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => "menu_order"),

			array("name" => __( 'Hide Excerpt', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide property excerpt', 'codeex_theme_name' ),
					"id" => $shortname."_property_excerpt",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your property list read more', 'codeex_theme_name' ),
					"id" => $shortname."_property_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Filter Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your filter default', 'codeex_theme_name' ),
					"id" => $shortname."_filter_default",
					"type" => "select",
					"options" => array( "Date", "Name", "Price" ),
					"std" => ""),	

			array("name" => __( 'Preferred Size', 'codeex_theme_name' ),
					"desc" => __( 'Select your preferred size to be display as default', 'codeex_theme_name' ),
					"id" => $shortname."_preferred_size",
					"type" => "select",
					"options" => array( "Lot Area", "Floor Area" ),
					"std" => ""),	

			array("name" => __( 'Single Post', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your property single page layout', 'codeex_theme_name' ),
					"id" => $shortname."_single_property_layout",
					"type" => "select",
					"options" => array( __( 'Default', 'codeex_theme_name' ) , "Left Sidebar", "Fullwidth"),
					"std" => ""),	

			array("name" => __( 'Featured Image', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to exclude featured image in properties slider', 'codeex_theme_name' ),
					"id" => $shortname."_properties_thumb_slider",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Attachment Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your ordering parameter in properties page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_attachment_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),

			array("name" => __( 'Attachment Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter in properties page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_attachment_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => "menu_order"),

			array("name" => __( 'Amenities Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your property amenities', 'codeex_theme_name' ),
					"id" => $shortname."_property_amenities_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Clickable Amenities List', 'codeex_theme_name' ),
					"desc" => __( 'Check the box for clickable amenities list', 'codeex_theme_name' ),
					"id" => $shortname."_clickable_amenities",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Hide Map', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide property map', 'codeex_theme_name' ),
					"id" => $shortname."_hide_map",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Show Street View', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to show map street view', 'codeex_theme_name' ),
					"id" => $shortname."_show_street_view",
					"type" => "checkbox",
					"std" => ""),		

			array("name" => __( 'Map Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your property map', 'codeex_theme_name' ),
					"id" => $shortname."_property_map_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Hide Agent Details', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide agent details information', 'codeex_theme_name' ),
					"id" => $shortname."_agent_info",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Agent Form', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your agent contact form', 'codeex_theme_name' ),
					"id" => $shortname."_agent_form",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Hide Other Properties', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide other properties', 'codeex_theme_name' ),
					"id" => $shortname."_other_properties",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Limit Other Properties', 'codeex_theme_name' ),
					"desc" => __( 'Enter your other properties number of posts', 'codeex_theme_name' ),
					"id" => $shortname."_other_property_limit",
					"type" => "text",
					"std" => "3"),

			array("name" => __( 'Other Properties', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your other properties', 'codeex_theme_name' ),
					"id" => $shortname."_other_properties_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Hide Comments', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide property comments', 'codeex_theme_name' ),
					"id" => $shortname."_hide_property_comments",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Archive', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Taxonomy and Archive Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your taxonomy and archive layout style', 'codeex_theme_name' ),
					"id" => $shortname."_tax_layout",
					"type" => "select",
					"options" => array( 
						__( 'Default', 'codeex_theme_name' ), 
						"Left Sidebar", 
						"1 Column", 
						"2 Columns", 
						"3 Columns", 
						"4 Columns", 
						"Grid Sidebar", 
						"Grid Left Sidebar"
					),
					"std" => ""),	

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your property archive page', 'codeex_theme_name' ),
					"id" => $shortname."_property_archive_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your property archive page', 'codeex_theme_name' ),
					"id" => $shortname."_property_archive_subtitle",
					"type" => "text",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Blog Settings
			-----------------------*/

			array("name" => __( 'Blog', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-pencil"),
			
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your blog learn more button', 'codeex_theme_name' ),
					"id" => $shortname."_blog_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Excerpt', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to enable the_excerpt of blog post', 'codeex_theme_name' ),
					"id" => $shortname."_blog_excerpt",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Archive Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your archive layout style', 'codeex_theme_name' ),
					"id" => $shortname."_archive_layout",
					"type" => "select",
					"options" => array( 
						__( 'Default', 'codeex_theme_name' ), 
						"Left Sidebar",
						"Grid", 
						"Grid Left Sidebar", 
						"2 Columns",
						"3 Columns", 
						"4 Columns", 
						"Fullwidth", 
						"Timeline"
					),
					"std" => ""),	

			array("name" => __( 'Single Post', 'codeex_theme_name' ), "type" => "headers"),	

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your blog single page layout', 'codeex_theme_name' ),
					"id" => $shortname."_single_blog_layout",
					"type" => "select",
					"options" => array( __( 'Default', 'codeex_theme_name' ) , "Left Sidebar", "Fullwidth"),
					"std" => ""),		

			array("name" => __( 'Thumbnail Slider', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to exclude post thumbnail in blog slider', 'codeex_theme_name' ),
					"id" => $shortname."_blog_thumb_slider",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Attachment Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your order parameter in blog single page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_blog_attachment_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),

			array("name" => __( 'Attachment Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter in blog single page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_blog_attachment_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => "menu_order"),

			array("name" => __( 'Hide Author', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide blog author details', 'codeex_theme_name' ),
					"id" => $shortname."_blog_author_hide",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Hide Comments', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide blog comments', 'codeex_theme_name' ),
					"id" => $shortname."_hide_blog_comments",
					"type" => "checkbox",
					"std" => ""),	

			array("type" => "close"),


			/*-----------------------
			Portfolio Settings
			-----------------------*/

			array("name" => __( 'Portfolio', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-laptop"),
			
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Global - Posts per page for portfolio', 'codeex_theme_name' ),
					"id" => $shortname."_num_portfolio",
					"type" => "text",
					"std" => "10"),	

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your ordering parameter in portfolio album', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter in portfolio album', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => "menu_order"),

			array("name" => __( 'Single Post', 'codeex_theme_name' ), "type" => "headers"),		

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your portfolio single page layout', 'codeex_theme_name' ),
					"id" => $shortname."_single_portfolio_layout",
					"type" => "select",
					"options" => array( 
						__( 'Default', 'codeex_theme_name' ), 
						"Right Sidebar", 
						"Left Sidebar"
					),
					"std" => ""),

			array("name" => __( 'Static Images', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to enable static images without a slider', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_static_image",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Attachment Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your order parameter in portfolio single page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_attachment_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),

			array("name" => __( 'Attachment Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter in portfolio single page for slider attachment images', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_attachment_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => "menu_order"),

			array("name" => __( 'Archive', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Taxonomy and Archive Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your taxonomy and archive layout style', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_tax_layout",
					"type" => "select",
					"options" => array( 
						__( 'Default', 'codeex_theme_name' ), 
						"Right Sidebar", 
						"Left Sidebar"
					),
					"std" => ""),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your portfolio archive page', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_archive_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your portfolio archive page', 'codeex_theme_name' ),
					"id" => $shortname."_portfolio_archive_subtitle",
					"type" => "text",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Services Settings
			-----------------------*/

			array("name" => __( 'Services', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-gears"),

			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Limit', 'codeex_theme_name' ),
					"desc" => __( 'Global - Posts per page for services', 'codeex_theme_name' ),
					"id" => $shortname."_num_services",
					"type" => "text",
					"std" => "10"),	

			array("name" => __( 'Order', 'codeex_theme_name' ),
					"desc" => __( 'Select your services order type', 'codeex_theme_name' ),
					"id" => $shortname."_services_order",
					"type" => "select",
					"options" => array("DESC", "ASC"),
					"std" => ""),	

			array("name" => __( 'Sort By', 'codeex_theme_name' ),
					"desc" => __( 'Select your sort by parameter for services', 'codeex_theme_name' ),
					"id" => $shortname."_services_orderby",
					"type" => "select",
					"options" => $homeland_theme_sort_by,
					"std" => ""),

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add label of your services read more button', 'codeex_theme_name' ),
					"id" => $shortname."_services_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Single Post', 'codeex_theme_name' ), "type" => "headers"),	

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your single services layout', 'codeex_theme_name' ),
					"id" => $shortname."_services_single_layout",
					"type" => "select",
					"options" => array( __( 'Default', 'codeex_theme_name' ), "Left Sidebar", "Fullwidth"),
					"std" => ""),	

			array("name" => __( 'Archive', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Layout', 'codeex_theme_name' ),
					"desc" => __( 'Select your services archive layout', 'codeex_theme_name' ),
					"id" => $shortname."_services_archive_layout",
					"type" => "select",
					"options" => array( __( 'Default', 'codeex_theme_name' ), "Left Sidebar", "Fullwidth", "Grid Fullwidth"),
					"std" => ""),	

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your services archive page', 'codeex_theme_name' ),
					"id" => $shortname."_services_archive_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your services archive page', 'codeex_theme_name' ),
					"id" => $shortname."_services_archive_subtitle",
					"type" => "text",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Contact Settings
			-----------------------*/

			array("name" => __( 'Contact', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-phone-square"),
			
			array("type" => "open"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Email', 'codeex_theme_name' ),
					"desc" => __( 'Enter your email address here', 'codeex_theme_name' ),
					"id" => $shortname."_email_address",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Fax', 'codeex_theme_name' ),
					"desc" => __( 'Enter your fax number here', 'codeex_theme_name' ),
					"id" => $shortname."_fax",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Phone Number', 'codeex_theme_name' ),
					"desc" => __( 'Enter your company phone number', 'codeex_theme_name' ),
					"id" => $shortname."_phone_number",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Address', 'codeex_theme_name' ),
					"desc" => __( 'Enter your company complete address', 'codeex_theme_name' ),
					"id" => $shortname."_contact_address",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Working Hours', 'codeex_theme_name' ),
					"desc" => __( 'Enter your company working hours', 'codeex_theme_name' ),
					"id" => $shortname."_working_hours",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Alternate Background', 'codeex_theme_name' ),
					"desc" => __( 'Upload your contact us alternate background image', 'codeex_theme_name' ),
					"id" => $shortname."_contact_alt_bgimage",
					"type" => "upload",
					"std" => ""),	

			array("name" => __( 'Google Map', 'codeex_theme_name' ), 
					"desc" => __( 'Get google map latitude and longitude value <a href="http://latlong.net" target="_blank">here</a>', 'codeex_theme_name' ),
					"type" => "headers"),

			array("name" => __( 'Hide', 'codeex_theme_name' ),
					"desc" => __( 'Check the box to hide google map', 'codeex_theme_name' ),
					"id" => $shortname."_hide_gmap",
					"type" => "checkbox",
					"std" => ""),	

			array("name" => __( 'Latitude', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map latitude', 'codeex_theme_name' ),
					"id" => $shortname."_map_lat",
					"type" => "text",
					"std" => "37.0625"),

			array("name" => __( 'Longitude', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map longitude', 'codeex_theme_name' ),
					"id" => $shortname."_map_lng",
					"type" => "text",
					"std" => "-95.677068"),

			array("name" => __( 'Map Zoom', 'codeex_theme_name' ),
					"desc" => __( 'Select your value for map zoom from 1-20', 'codeex_theme_name' ),
					"id" => $shortname."_map_zoom",
					"type" => "slide_amount",
					"std" => "8"),		

			array("name" => __( 'Marker Title', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map marker title', 'codeex_theme_name' ),
					"id" => $shortname."_map_marker",
					"type" => "text",
					"std" => __( 'Add your marker title window', 'codeex_theme_name' )),	

			array("name" => __( 'Marker Window', 'codeex_theme_name' ),
					"desc" => __( 'Enter your google map marker info window', 'codeex_theme_name' ),
					"id" => $shortname."_map_window",
					"type" => "textarea",
					"std" => __( 'Please add your text marker here', 'codeex_theme_name' )),			

			array("type" => "close"),


			/*-----------------------
			Other Pages Settings
			-----------------------*/

			array("name" => __( 'Other Pages', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-folder"),
			
			array("type" => "open"),

			array("name" => __( 'About', 'codeex_theme_name' ), "type" => "headers"),

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of our team', 'codeex_theme_name' ),
					"id" => $shortname."_team_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( '404 Page', 'codeex_theme_name' ), "type" => "headers"),		

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your 404 page', 'codeex_theme_name' ),
					"id" => $shortname."_not_found_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your 404 page', 'codeex_theme_name' ),
					"id" => $shortname."_not_found_subtitle",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Content Header', 'codeex_theme_name' ),
					"desc" => "Add your 404 large text",
					"id" => $shortname."_not_found_large_text",
					"type" => "text",
					"std" => __( 'Error <span>404</span>', 'codeex_theme_name' )),

			array("name" => __( 'Content Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add your 404 small text', 'codeex_theme_name' ),
					"id" => $shortname."_not_found_small_text",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Button', 'codeex_theme_name' ),
					"desc" => __( 'Add your 404 button', 'codeex_theme_name' ),
					"id" => $shortname."_not_found_button",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Search', 'codeex_theme_name' ), "type" => "headers"),		

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your search results page', 'codeex_theme_name' ),
					"id" => $shortname."_search_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your search results page', 'codeex_theme_name' ),
					"id" => $shortname."_search_subtitle",
					"type" => "text",
					"std" => ""),

			array("type" => "close"),


			/*-----------------------
			Forum Settings
			-----------------------*/

			array("name" => __( 'Forum', 'codeex_theme_name' ),
					"type" => "section",
					"icon" => "fa fa-comments"),

			array("name" => __( 'Main', 'codeex_theme_name' ), "type" => "headers"),		

			array("name" => __( 'Header', 'codeex_theme_name' ),
					"desc" => __( 'Add header label of your forum page', 'codeex_theme_name' ),
					"id" => $shortname."_forum_header",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Subtitle', 'codeex_theme_name' ),
					"desc" => __( 'Add subtitle label of your forum page', 'codeex_theme_name' ),
					"id" => $shortname."_forum_subtitle",
					"type" => "text",
					"std" => ""),

			array("name" => __( 'Header Images', 'codeex_theme_name' ), "type" => "headers"),	

			array("name" => __( 'Forum', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum header image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_hdimage",
					"type" => "upload",
					"std" => ""),	

			array("name" => __( 'Single Forum', 'codeex_theme_name' ),
					"desc" => __( 'Upload your single forum header image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_single_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Single Topic', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum single topic header image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_single_topic_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Topic Edit', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum topic edit header image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_topic_edit_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Forum Search', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum search header image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_search_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'User Profile', 'codeex_theme_name' ),
					"desc" => __( 'Upload your user profile header image', 'codeex_theme_name' ),
					"id" => $shortname."_user_profile_hdimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Background Images', 'codeex_theme_name' ), "type" => "headers"),	

			array("name" => __( 'Forum', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum background image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_bgimage",
					"type" => "upload",
					"std" => ""),	

			array("name" => __( 'Single Forum', 'codeex_theme_name' ),
					"desc" => __( 'Upload your single forum background image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_single_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Single Topic', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum single topic background image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_single_topic_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Topic Edit', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum topic edit background image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_topic_edit_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'Forum Search', 'codeex_theme_name' ),
					"desc" => __( 'Upload your forum search background image', 'codeex_theme_name' ),
					"id" => $shortname."_forum_search_bgimage",
					"type" => "upload",
					"std" => ""),

			array("name" => __( 'User Profile', 'codeex_theme_name' ),
					"desc" => __( 'Upload your user profile background image', 'codeex_theme_name' ),
					"id" => $shortname."_user_profile_bgimage",
					"type" => "upload",
					"std" => ""),

			array("type" => "close"),
		
		); 
	}

	add_action('init', 'homeland_options_setup');
?>