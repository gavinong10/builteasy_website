<?php
	/*---------------
	Custom Styles
	---------------*/

	if ( ! function_exists( 'homeland_theme_custom_styles' ) ) :
		function homeland_theme_custom_styles() {
			global $post;

			$homeland_bg_type = esc_attr( get_option('homeland_bg_type') );
			$homeland_pattern = esc_attr( get_option('homeland_pattern') );
			$homeland_bg_color = esc_attr( get_option('homeland_bg_color') );
			$homeland_theme_font = esc_attr( get_option('homeland_theme_font') );
			$homeland_theme_color_global = esc_attr( get_option('homeland_global_color') );
			$homeland_rgb_theme_color = homeland_hex2rgba($homeland_theme_color_global);
			$homeland_rgba_theme_color = homeland_hex2rgba($homeland_theme_color_global, 0.8);
			$homeland_top_header_bg_color = esc_attr( get_option('homeland_top_header_bg_color') );
			$homeland_menu_bg_color = esc_attr( get_option('homeland_menu_bg_color') );
			$homeland_menu_text_color = esc_attr( get_option('homeland_menu_text_color') );
			$homeland_menu_text_color_active = esc_attr( get_option('homeland_menu_text_color_active') );
			$homeland_header_text_color = esc_attr( get_option('homeland_header_text_color') );
			$homeland_sidebar_text_color = esc_attr( get_option('homeland_sidebar_text_color') );
			$homeland_button_bg_color = esc_attr( get_option('homeland_button_bg_color') );
			$homeland_button_bg_hover_color = esc_attr( get_option('homeland_button_bg_hover_color') );
			$homeland_button_text_color = esc_attr( get_option('homeland_button_text_color') );
			$homeland_footer_bg_color = esc_attr( get_option('homeland_footer_bg_color') );
			$homeland_footer_text_color = esc_attr( get_option('homeland_footer_text_color') );
			$homeland_slide_top_bg_color = esc_attr( get_option('homeland_slide_top_bg_color') );
			$homeland_custom_css = get_option('homeland_custom_css');
			$homeland_welcome_bgimage = esc_attr( get_option('homeland_welcome_bgimage') );
			$homeland_services_bgimage = esc_attr( get_option('homeland_services_bgimage') );
			$homeland_testimonials_bgimage = esc_attr( get_option('homeland_testimonials_bgimage') );
			$homeland_preloader_icon = esc_attr( get_option('homeland_preloader_icon') );
			$homeland_contact_alt_bgimage = esc_attr( get_option('homeland_contact_alt_bgimage') );

			//Header images
			
			$homeland_page_hd_image = esc_attr( get_post_meta( @$post->ID, 'homeland_hdimage', true ) );
			$homeland_archive_hdimage = esc_attr( get_option('homeland_archive_hdimage') );
			$homeland_search_hdimage = esc_attr( get_option('homeland_search_hdimage') );
			$homeland_notfound_hdimage = esc_attr( get_option('homeland_notfound_hdimage') );
			$homeland_agent_hdimage = esc_attr( get_option('homeland_agent_hdimage') );
			$homeland_taxonomy_hdimage = esc_attr( get_option('homeland_taxonomy_hdimage') );
			$homeland_default_hdimage = esc_attr( get_option('homeland_default_hdimage') );
			$homeland_forum_hdimage = esc_attr( get_option('homeland_forum_hdimage') );
			$homeland_forum_single_hdimage = esc_attr( get_option('homeland_forum_single_hdimage') );
			$homeland_forum_single_topic_hdimage = esc_attr( get_option('homeland_forum_single_topic_hdimage') );
			$homeland_forum_topic_edit_hdimage = esc_attr( get_option('homeland_forum_topic_edit_hdimage') );
			$homeland_forum_search_hdimage = esc_attr( get_option('homeland_forum_search_hdimage') );
			$homeland_user_profile_hdimage = esc_attr( get_option('homeland_user_profile_hdimage') );

			//Fonts sizes

			$homeland_body_font_size = esc_attr( get_option('homeland_body_font_size') );
			$homeland_body_line_height = esc_attr( get_option('homeland_body_line_height') );
			$homeland_homepage_header_font_size = esc_attr( get_option('homeland_homepage_header_font_size') );
			$homeland_page_top_header_font_size = esc_attr( get_option('homeland_page_top_header_font_size') );
			$homeland_page_top_subtitle_font_size = esc_attr( get_option('homeland_page_top_subtitle_font_size') );
			$homeland_page_content_header_font_size = esc_attr( get_option('homeland_page_content_header_font_size') );
			$homeland_sidebar_header_font_size = esc_attr( get_option('homeland_sidebar_header_font_size') );
			$homeland_footer_font_size = esc_attr( get_option('homeland_footer_font_size') );

			?>

			<style type="text/css"><?php	

				//Font Sizes
				if(!empty($homeland_body_font_size)) : ?>
					body { font-size: <?php echo $homeland_body_font_size ?>px !important; }
					<?php
				endif;

				if(!empty($homeland_body_line_height)) : ?>
					body { line-height: <?php echo $homeland_body_line_height ?>px !important; }
					<?php
				endif;

				if(!empty($homeland_homepage_header_font_size)) : ?>
					.property-list-box h2, .agent-block h3, .featured-block h3, 
					.featured-block-two-cols h3, .blog-block h3, .partners-block h3 {
						font-size: <?php echo $homeland_homepage_header_font_size ?>px !important; 
					}<?php
				endif;

				if(!empty($homeland_page_top_header_font_size)) : ?>
					.ptitle { font-size: <?php echo $homeland_page_top_header_font_size ?>px !important; }<?php
				endif;

				if(!empty($homeland_page_top_subtitle_font_size)) : ?>
					.subtitle label { font-size: <?php echo $homeland_page_top_subtitle_font_size ?>px !important; }<?php
				endif;

				if(!empty($homeland_page_content_header_font_size)) : ?>
					.left-container h3 { font-size: <?php echo $homeland_page_content_header_font_size ?>px !important; }<?php
				endif;

				if(!empty($homeland_sidebar_header_font_size)) : ?>
					.sidebar h5 { font-size: <?php echo $homeland_sidebar_header_font_size ?>px !important; }<?php
				endif;

				if(!empty($homeland_footer_font_size)) : ?>
					footer .widget h5 { font-size: <?php echo $homeland_footer_font_size ?>px !important; }<?php
				endif;


				//Patterns

				if($homeland_bg_type == "Pattern") :
					if($homeland_pattern == "Gray Lines") : 
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/gray_lines.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Noise Lines") : 
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/noise_lines.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Tiny Grid") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/tiny_grid.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Bullseye") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/strange_bullseyes.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Gray Paper") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/gray_paper.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Norwegian Rose") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/norwegian_rose.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Subtle Net") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/subtlenet.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Polyester Lite") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/polyester_lite.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Absurdity") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/absurdidad.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "White Bed Sheet") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/white_bed_sheet.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Subtle Stripes") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/subtle_stripes.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Light Mesh") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/lghtmesh.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Rough Diagonal") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/rough_diagonal.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Arabesque") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/arab_tile.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Stack Circles") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/stacked_circles.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Hexellence") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/hexellence.png') repeat top fixed fixed !important; }<?php
					elseif($homeland_pattern == "White Texture") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/white_texture.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Concrete Wall") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/concrete_wall.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Brush Aluminum") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/brushed_alu.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Groovepaper") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/groovepaper.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Diagonal Noise") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/diagonal_noise.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Rocky Wall") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/rockywall.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Whitey") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/whitey.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Bright flexs") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/bright_flexs.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Freckles") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/freckles.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Wallpaper") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/wallpaper.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Project Paper") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/project_papper.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Cubes") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/cubes.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Washi") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/washi.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Dot Noise") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/dotnoise.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "xv") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/xv.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Little Plaid") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/plaid.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Old Wall") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/old_wall.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Connect") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/connect.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Ravenna") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/ravenna.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Smooth Wall") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/smooth_wall.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Tapestry") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/tapestry.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Psychedelic") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/psychedelic.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Scribble Light") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/scribble_light.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "GPlay") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/gplay.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Lil Fiber") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/lil_fiber.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "First Aid") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/first_aid.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Frenchstucco") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/frenchstucco.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Light Wool") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/light_wool.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Gradient flexs") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/gradient_flexs.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Escheresque") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/escheresque.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Climpek") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/climpek.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Lyonnette") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/lyonnette.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Gray Floral") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/greyfloral.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Reticular Tissue") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/reticular_tissue.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Halftone") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/halftone.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Pentagon") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/congruent_pentagon.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Giftly") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/giftly.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Skulls") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/skulls.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Food") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/food.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Sprinkles") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/sprinkles.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Geometry") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/geometry.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Dimension") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/dimension.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Pixel Weave") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/pixel_weave.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Hoffman") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/hoffman.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Mini Waves") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/mini_waves.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Swirl") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/swirl.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Eight Horns") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/eight_horns.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Contemporary China") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/contemporary_china.png') repeat top fixed !important; }<?php
					elseif($homeland_pattern == "Symphony") :
						?>body { background:url('<?php echo get_template_directory_uri(); ?>/img/patterns/symphony.png') repeat top fixed !important; }<?php
					endif;
				elseif($homeland_bg_type == "Color") :
					?>body { background-color: <?php echo $homeland_bg_color; ?> } <?php
				endif;

				//Welcome Background
				if(!empty($homeland_welcome_bgimage)) :
					?>.welcome-block { background: url('<?php echo $homeland_welcome_bgimage; ?>') !important;  }<?php
				endif;

				if(!empty($homeland_background)) :
					?>.welcome-block { background: url('<?php echo $homeland_background; ?>') !important;  }<?php
				endif;

				//Services Background
				if(!empty($homeland_services_bgimage)) :
					?>.services-block-bg { background: url('<?php echo $homeland_services_bgimage; ?>') !important;  }<?php
				endif;

				//Testimonials Background
				if(!empty($homeland_testimonials_bgimage)) :
					?>.testimonial-block { background: url('<?php echo $homeland_testimonials_bgimage; ?>') !important; }<?php
				endif;

				//Preloader Icon
				if(!empty($homeland_preloader_icon)) :
					?>#status { background-image:url('<?php echo $homeland_preloader_icon; ?>') !important; }<?php
				endif;

				//Contact Alternate Background
				if(!empty($homeland_contact_alt_bgimage)) :
					?>.contact-alt-background { background-image:url('<?php echo $homeland_contact_alt_bgimage; ?>') !important; }<?php
				endif;

				//Colors

				if(!empty($homeland_theme_color_global)) : ?>
					.search-title span, .selectBox-dropdown .selectBox-arrow, 
					.home-flexslider .flex-direction-nav li .flex-next:hover, 
					.home-flexslider .flex-direction-nav li .flex-prev:hover, 
					.properties-flexslider .flex-direction-nav li .flex-next:hover, 
					.properties-flexslider .flex-direction-nav li .flex-prev:hover,
					.blog-flexslider .flex-direction-nav li .flex-next:hover, 
					.blog-flexslider .flex-direction-nav li .flex-prev:hover, 
					.services-desc a.more, .cat-price,
					.grid li:hover .property-info, .pimage figcaption i, 
					.feat-thumb figcaption i, .feat-medium figcaption i,
					.nsu-submit, a#toTop, .pactions a:link, .pactions a:visited, 
					.theme-menu ul li.current-menu-item a, .theme-menu ul li.current-menu-ancestor a, 
					.theme-menu ul li.current-menu-parent a, .theme-menu ul li a:hover, 
					.sf-menu li.sfHover a, .sf-menu li.sfHover a:after, .cat-toogles ul li.current-cat a, 
					.cat-toogles ul li a:hover, .page-numbers li a:hover, .alignleft a:hover, .alignright a:hover, 
					.post-link-blog .prev a:hover, .post-link-blog .next a:hover, span.current, 
					a.continue, .wpcf7-submit, #submit,
					.page-template-template-homepage2-php .hi-icon-effect-1 .hi-icon,
					.advance-search-widget ul li input[type="submit"], 
					.dsidx-widget.dsidx-search-widget 
					.dsidx-search-button input[type="submit"], #dsidx-price,
					#dsidx.dsidx-details .dsidx-contact-form table input[type="button"],
					.property-four-cols .view-details a, .agent-form ul li input[type="submit"], 
					a.view-gmap:link, a.view-gmap:visited,
					#bbp_search_submit, .bbp-submit-wrapper button, #bbp_user_edit_submit, 
					#homeland-loginform .login-submit input[type="submit"]  { 
						background:<?php echo $homeland_theme_color_global; ?> 
					} 
					.sfHover ul li.sfHover a.sf-with-ul, a.back-home:link, a.back-home:visited { 
						background:<?php echo $homeland_theme_color_global ?> !important; 
					}
					.hi-icon, .no-touch .hi-icon-effect-1a .hi-icon:hover, 
					.property-desc h4 a:hover, a.view-property:hover,
					.agent-block label span, .homeland_widget-agents label span, 
					.agent-block h4 a:hover, .feat-desc span.price,
					.sf-menu li.sfHover ul li a:hover, .widget ul li a:hover, 
					.widget ul li:hover:before, .copyright a, 
					.agent-block h4 a:hover, .agent-desc h4 a:hover, 
					.agent-social ul li a:hover, .sidebar .pp-desc a:hover, 
					.services-page-desc h5 a:hover, .agent-about-list .agent-image h4 a:hover, 
					.blog-list-desc h4 a:hover, .blog-action ul li a:hover,
					.comment-details h5 a:hover, .property-desc-slide span, .agent-info label,
					.property-three-cols .property-desc span.price, .property-four-cols .view-details a, 
					.agent-desc label.listed span, .contact-info label, .feat-desc h5 a:hover, .bdesc h5 a:hover,
					#dsidx-listings .dsidx-price, .featured-listing .price, 
					.dsidx-prop-title, a.dsidx-actions-button:hover, 
					#dsidx a:hover, .featured-listing h4 a:hover, 
					div.dsidx-results-widget .dsidx-controls a:hover, 
					.marker-window h5, .sitemap a:hover, .property-page-id span,
					.property-page-type a:hover, .property-page-status a:hover, 
					.countdown-section, .countdown-amount, a.property-print:hover,
					.bbp-breadcrumb a:link, .bbp-breadcrumb a:visited,
					a.bbp-forum-title:hover, a.bbp-topic-permalink:link, a.bbp-topic-permalink:visited, 
					.bbp-topic-title h3 a, .bbp-topic-title-meta a, .bbp-forum-title h3 a,
					.bbp-forum-freshness a:link, .bbp-forum-freshness a:visited, 
					.bbp-topic-freshness a:link, .bbp-topic-freshness a:visited, 
					.bbp-author-name:link, .bbp-author-name:visited,
					#bbp-user-navigation ul li a:hover, a.bbp-forum-title, 
					.contact-info label a, 
					.contact-info-alt a, .contact-alternate-main label,
					.contact-alternate-main label a { 
						color:<?php echo $homeland_theme_color_global ?> !important; 
					} 
					.page-template-template-homepage2-php .hi-icon, .property-four-cols .view-details a,
					.featured-flexslider ul li .pimage a i { color:#FFF !important; }
					.hi-icon { border-color:<?php echo $homeland_theme_color_global; ?> } 
					.advance-search-block.advance-search-block-page, 
					.property-page-price { background:<?php echo $homeland_rgba_theme_color; ?> } <?php
				endif;

				if(!empty($homeland_top_header_bg_color)) : ?>
					header { background:<?php echo $homeland_top_header_bg_color ?> !important; } <?php
				endif;

				if(!empty($homeland_menu_text_color)) : ?>
					.theme-menu ul li a { color:<?php echo $homeland_menu_text_color ?> !important; } <?php
				endif;

				if(!empty($homeland_menu_text_color_active)) : ?>
					.theme-menu ul li.current-menu-item a, .theme-menu ul li.current-menu-ancestor a, 
					.theme-menu ul li.current-menu-parent a, .theme-menu ul li a:hover { 
						color:<?php echo $homeland_menu_text_color_active ?> !important; 
					} 
					.theme-menu ul li.current-menu-parent ul.sub-menu li a,
					.theme-menu ul li.current-menu-ancestor ul.sub-menu li a { color: #777 !important; }<?php
				endif;

				if(!empty($homeland_menu_bg_color)) : ?>
					.theme-menu ul li.current-menu-item a, .theme-menu ul li.current-menu-ancestor a, 
					.theme-menu ul li.current-menu-parent a, .theme-menu ul li a:hover,
					.sf-menu li.sfHover a, .sf-menu li.sfHover a:after,
					.sfHover ul li.sfHover a.sf-with-ul { 
						background: <?php echo $homeland_menu_bg_color ?> !important; 
					}
					.theme-menu ul li.current-menu-parent ul.sub-menu li a,
					.theme-menu ul li.current-menu-ancestor ul.sub-menu li a,
					.theme-menu ul li ul.sub-menu li a { color: #777 !important; background: #f2f2f2 !important; }
					.theme-menu ul li.current-menu-parent ul.sub-menu li a:hover,
					.theme-menu ul li.current-menu-ancestor ul.sub-menu li a:hover,
					.theme-menu ul li ul.sub-menu li a:hover { 
						color: <?php echo $homeland_menu_bg_color ?> !important;  background: #FFF !important; }
					<?php
				endif;

				if(!empty($homeland_header_text_color)) : ?>
					.ptitle, .widget h5, .property-list-box h2, .agent-block h3, .featured-block h3, .blog-block h3,
					.services-desc h5, .partners-block h3, .featured-block-two-cols h3 { 
						color:<?php echo $homeland_header_text_color ?> !important; 
					} <?php
				endif;

				if(!empty($homeland_sidebar_text_color)) : ?>
					.sidebar .widget h5 { color:<?php echo $homeland_sidebar_text_color ?> !important; }<?php 
				endif;

				if(!empty($homeland_button_bg_color)) : ?>
					.contact-form input[type="submit"], #respond input[type="submit"],
					.services-desc a.more, .pactions a i, .feat-thumb figcaption a i, 
					.feat-medium figcaption a i, .pimage figcaption a i, 
					a#toTop, .nsu-submit, .advance-search-block input[type="submit"], 
					a.back-home:link, a.back-home:visited, a.continue, 
					.advance-search-widget ul li input[type="submit"], 
					.dsidx-widget.dsidx-search-widget .dsidx-search-button input[type="submit"],
					#dsidx.dsidx-details .dsidx-contact-form table input[type="button"],
					.property-four-cols .view-details a, .agent-form ul li input[type="submit"] { 
						background-color:<?php echo $homeland_button_bg_color ?> !important; } <?php
				endif;

				if(!empty($homeland_button_bg_hover_color)) : ?>
					.contact-form input[type="submit"]:hover, #respond input[type="submit"]:hover,
					.services-desc a.more:hover, .pactions a i:hover, 
					.feat-thumb figcaption a i:hover, .feat-medium figcaption a i:hover, 
					.pimage figcaption a i:hover,
					a#toTop:hover, .nsu-submit:hover, 
					.advance-search-block input[type="submit"]:hover, a.back-home:hover, 
					a.continue:hover, 
					.advance-search-widget ul li input[type="submit"]:hover, 
					.dsidx-widget.dsidx-search-widget .dsidx-search-button input[type="submit"]:hover,
					#dsidx.dsidx-details .dsidx-contact-form table input[type="button"]:hover,
					.property-four-cols .view-details a:hover, .agent-form ul li input[type="submit"]:hover,
					.marker-window a.view-gmap:hover, #bbp_search_submit:hover, #bbp_reply_submit:hover, 
					.wpcf7-submit:hover, #homeland-loginform .login-submit input[type="submit"]:hover { 
						background-color:<?php echo $homeland_button_bg_hover_color ?> !important; } <?php
				endif;

				if(!empty($homeland_button_text_color)) :	?>
					.contact-form input[type="submit"], #respond input[type="submit"],
					.services-desc a.more, .pactions a i, .feat-thumb figcaption a i, 
					.feat-medium figcaption a i, .pimage figcaption a i, 
					a#toTop, a.continue { color:<?php echo $homeland_button_text_color ?> !important; } <?php
				endif;

				if(!empty($homeland_footer_bg_color)) :	?>
					footer { background-color:<?php echo $homeland_footer_bg_color ?> !important; } <?php
				endif;

				if(!empty($homeland_footer_text_color)) :	?>
					footer, .widget-column { color:<?php echo $homeland_footer_text_color ?> !important; } <?php
				endif;

				if(!empty($homeland_slide_top_bg_color)) :	?>
					.sliding-bar, 
					a.slide-toggle { color:#FFF; background-color:<?php echo $homeland_slide_top_bg_color ?> !important; } <?php
				endif;

				if(!empty($homeland_custom_css)) : echo stripslashes($homeland_custom_css); endif;


				//Theme Fonts

				if($homeland_theme_font == "Open Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Open Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Droid Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Droid Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Lato") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Lato', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Raleway") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Raleway', sans-serif !important; } <?php
				elseif($homeland_theme_font == "PT Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'PT Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Noto Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Noto Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Oxygen") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Oxygen', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Source Sans Pro") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Source Sans Pro', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Muli") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Muli', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Istok Web") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Istok Web', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Puritan") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Puritan', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Gafata") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Gafata', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Cambo") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Cambo', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Voces") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Voces', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Duru Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Duru Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Sintony") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Sintony', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Carrois Gothic") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Carrois Gothic', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Alegreya Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Alegreya Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "News Cycle") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'News Cycle', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Dosis") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Dosis', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Abel") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Abel', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Didact Gothic") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Didact Gothic', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Arimo") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Arimo', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Titillium Web") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Titillium Web', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Archivo Narrow") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Archivo Narrow', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Josefin Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Josefin Sans', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Asap") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Asap', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Questrial") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Questrial', sans-serif !important; } <?php
				elseif($homeland_theme_font == "Pontano Sans") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Pontano Sans', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Slabo") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Slabo 27px', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Oswald") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Oswald', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Montserrat") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Montserrat', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Ubuntu") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Ubuntu', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Merriweather") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Merriweather', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Cabin") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Cabin', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Hind") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Hind', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Gudea") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Gudea', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Noticia Text") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Noticia Text', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Nobile") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Nobile', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Lora") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Lora', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Inconsolata") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Inconsolata', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Quicksand") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Quicksand', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Karla") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Karla', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Monda") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Monda', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Crimson Text") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Crimson Text', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Exo") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Exo', sans-serif !important; }<?php
				elseif($homeland_theme_font == "EB Garamond") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'EB Garamond', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Armata") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Armata', sans-serif !important; }<?php
				elseif($homeland_theme_font == "Glegoo") : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Glegoo', sans-serif !important; }<?php
				else : ?>
					body, h1, h2, h3, h4, h5, h6, input, textarea, select, 
					.widget_revslider .tp-caption { font-family:'Roboto', sans-serif !important; }<?php
				endif;


				//Header Images

				if(!empty($homeland_default_hdimage)) : $homeland_default_hdbanner = $homeland_default_hdimage; 
				else : $homeland_default_hdbanner = "http://themecss.com/wp/Homeland/wp-content/uploads/2013/12/View-over-the-lake_www.LuxuryWallpapers.net_-1920x300.jpg"; 
				endif;


				if(function_exists('is_bbpress')) :
					if(bbp_is_single_forum()) :
						if(!empty($homeland_forum_single_hdimage)) :
							?>.page-title-block-forum-single { background:url('<?php echo $homeland_forum_single_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;
					elseif(bbp_is_single_topic()) :
						if(!empty($homeland_forum_single_topic_hdimage)) :
							?>.page-title-block-topic-single { background:url('<?php echo $homeland_forum_single_topic_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;	
					elseif(bbp_is_topic_edit()) :
						if(!empty($homeland_forum_topic_edit_hdimage)) :
							?>.page-title-block-topic-edit { background:url('<?php echo $homeland_forum_topic_edit_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;	
					elseif(bbp_is_search()) :
						if(!empty($homeland_forum_search_hdimage)) :
							?>.page-title-block-forum-search { background:url('<?php echo $homeland_forum_search_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;	
					elseif(bbp_is_single_user()) :
						if(!empty($homeland_user_profile_hdimage)) :
							?>.page-title-block-user-profile { background:url('<?php echo $homeland_user_profile_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;
					elseif(is_bbpress()) :
						if(!empty($homeland_forum_hdimage)) :
							?>.page-title-block-forum { background:url('<?php echo $homeland_forum_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;
					endif;
				endif;

				if(is_archive()) : 
					if(is_author()) : 
						if(!empty($homeland_agent_hdimage)) :
							?>.page-title-block-agent { background:url('<?php echo $homeland_agent_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;
					elseif(is_tax()) : 
						if(!empty($homeland_taxonomy_hdimage)) :
							?>.page-title-block-taxonomy { background:url('<?php echo $homeland_taxonomy_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;
					elseif(function_exists('is_bbpress')) :
						if(bbp_is_forum_archive()) :
							if(!empty($homeland_forum_hdimage)) :
								?>.page-title-block-forum { background:url('<?php echo $homeland_forum_hdimage; ?>') repeat-x center; } <?php 
							else :
								?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
							endif;
						else :
							if(!empty($homeland_archive_hdimage)) :
								?>.page-title-block-archive { background:url('<?php echo $homeland_archive_hdimage; ?>') repeat-x center; } <?php 
							else :
								?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
							endif;
						endif;
					else : 
						if(!empty($homeland_archive_hdimage)) :
							?>.page-title-block-archive { background:url('<?php echo $homeland_archive_hdimage; ?>') repeat-x center; } <?php 
						else :
							?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
						endif;	
					endif; 
				elseif(is_search()) : 
					if(!empty($homeland_search_hdimage)) :
						?>.page-title-block-search { background:url('<?php echo $homeland_search_hdimage; ?>') repeat-x center; } <?php 
					else :
						?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
					endif;	
				elseif(is_404()) : 
					if(!empty($homeland_notfound_hdimage)) :
						?>.page-title-block-error { background:url('<?php echo $homeland_notfound_hdimage; ?>') repeat-x center; } <?php 
					else :
						?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
					endif;
				else : 
					if(!empty($homeland_page_hd_image)) :
						?> .page-title-block { background:url('<?php echo $homeland_page_hd_image; ?>') repeat-x center; } <?php 
					else :
						?> .page-title-block-default { background:url('<?php echo $homeland_default_hdbanner; ?>') repeat-x center; } <?php
					endif;
				endif;

			?></style><?php
		}
	endif;
	add_action( 'wp_head', 'homeland_theme_custom_styles' );


	/*---------------
	Custom Font
	---------------*/

	if ( ! function_exists( 'homeland_custom_font_family' ) ) :
		function homeland_custom_font_family() {
			$homeland_theme_font = esc_attr( get_option('homeland_theme_font') );

			if($homeland_theme_font == "Open Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Droid Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Droid+Sans:400,700' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Lato") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Raleway") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,900,800' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "PT Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic&subset=latin,cyrillic-ext,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Noto Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Oxygen") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Source Sans Pro") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic&subset=latin,vietnamese,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Muli") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Muli:300,400,300italic,400italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Istok Web") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Istok+Web:400,700,400italic,700italic&subset=latin,cyrillic-ext,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Puritan") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Puritan:400,700,400italic,700italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Gafata") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Gafata&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Cambo") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Cambo' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Voces") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Voces&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Duru Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Duru+Sans&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );		
			elseif($homeland_theme_font == "Sintony") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Sintony:400,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Carrois Gothic") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Carrois+Gothic' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Alegreya Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Alegreya+Sans:100,300,400,500,700,800,900,100italic,300italic,400italic,500italic,700italic,800italic,900italic&subset=latin,vietnamese,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "News Cycle") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=News+Cycle:400,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Dosis") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Abel") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Abel' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Didact Gothic") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Didact+Gothic&subset=latin,greek-ext,greek,latin-ext,cyrillic,cyrillic-ext' );
				wp_enqueue_style( 'homeland_custom_font' );	
			elseif($homeland_theme_font == "Arimo") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Arimo:400,700,400italic,700italic&subset=latin,cyrillic-ext,cyrillic,greek-ext,greek,vietnamese,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Titillium Web") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Archivo Narrow") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Archivo+Narrow:400,400italic,700,700italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Josefin Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Asap") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Questrial") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Questrial' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Pontano Sans") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Pontano+Sans&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Slabo") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Slabo+27px&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Oswald") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Oswald:400,300,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Montserrat") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Montserrat:400,700' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Ubuntu") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic&subset=latin,cyrillic-ext,greek-ext,greek,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Merriweather") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Cabin") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Cabin:400,500,600,700,400italic,500italic,600italic,700italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Hind") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Hind:400,300,500,600,700&subset=latin,devanagari,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Gudea") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Gudea:400,700,400italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Noticia Text") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Noticia+Text:400,400italic,700,700italic&subset=latin,vietnamese,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Nobile") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Nobile:400,400italic,700,700italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Lora") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic&subset=latin,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Inconsolata") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Inconsolata:400,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Quicksand") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Quicksand:300,400,700' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Karla") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Karla:400,400italic,700,700italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Monda") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Monda:400,700&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Crimson Text") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Crimson+Text:700italic,600,600italic,400,700,400italic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Exo") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Exo:100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "EB Garamond") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,vietnamese,cyrillic-ext,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Armata") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Armata&subset=latin,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			elseif($homeland_theme_font == "Glegoo") : 
				wp_register_style( 'homeland_custom_font', 'http://fonts.googleapis.com/css?family=Glegoo:400,700&subset=latin,devanagari,latin-ext' );
				wp_enqueue_style( 'homeland_custom_font' );
			else :
				wp_register_style( 'homeland_roboto', 'http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic' );
				wp_enqueue_style( 'homeland_roboto' );			
			endif;
		}
	endif;
	add_action( 'wp_enqueue_scripts', 'homeland_custom_font_family' );
?>