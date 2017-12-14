<?php
function thememove_css_custom_code() {
	$color_scheme = thememove_get_color_scheme();
	?>
	<style type="text/css">
		.people .social .menu li a:hover,
		.listing li i,
		.error404 h2,
		.woocommerce .star-rating span:before,
		.woocommerce ul.products li.product h3:hover,
		.scheme .header-right i,
		.pagination span,
		.woocommerce ul.products li.product .price,
		.woocommerce div.product p.price,
		.woocommerce div.product span.price,
		.scheme .testimonial__author,
		.scheme .navigation a:before,
		.scheme .navigation a:after,
		.scheme .structure .esg-filter-wrapper .esg-filterbutton.selected,
		.scheme .structure .esg-filter-wrapper .esg-filterbutton:hover,
		.scheme .has-bg span, .scheme .footer .menu li:hover:before,
		.scheme .testimonials-list .author span:first-child,
		.scheme .introducing li:before,
		.scheme .contact-info i,
		.scheme .consulting-2 .info h3 + h3,
		.scheme .listing li i {
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		.contact-page .call-us .wpb_text_column:last-child,
		.wpcf7 input[type="submit"],
		.faq-classic .vc_custom_heading:before,
		.faq-classic .wpb_text_column:before,
		.scheme .download2:hover,
		.single-project.tm_project_details_layout3 .gallery--layout3 .owl-dots,
		.images-carousel-2 .vc_images_carousel .vc_carousel-indicators,
		.scheme .scrollup,
		.scheme.single-project .gallery a:after,
		.woocommerce #payment #place_order,
		.woocommerce-page #payment #place_order,
		.woocommerce #respond input#submit:hover,
		.woocommerce a.button:hover,
		.woocommerce button.button:hover,
		.woocommerce input.button:hover,
		.woocommerce span.onsale,
		.woocommerce button.button.alt,
		.scheme .intro,
		.scheme .wpb_accordion_wrapper .ui-state-active .ui-icon:before,
		.scheme .clients .owl-nav div:hover:before,
		.scheme .owl-controls .owl-dot.active,
		.scheme .eg-howardtaft-container,
		.scheme .structure .esg-navigationbutton,
		.scheme .heading-title-2:before,
		.scheme .heading-title:before,
		.scheme .comments-title:after,
		.scheme .comment-reply-title:after,
		.scheme .widget-title:after,
		.scheme input[type="submit"]:hover,
		.navigation .sub-menu li a:hover,
		.navigation .children li a:hover,
		.scheme .sidebar .widget .menu li:hover,
		.scheme .wpb_widgetised_column .widget .menu li:hover a,
		.scheme .sidebar .widget .menu li.current-menu-item,
		.scheme .wpb_widgetised_column .widget .menu li.current-menu-item a,
		.scheme .features .wpb_wrapper p:first-child:after,
		.scheme .recent-posts__thumb:after,
		.woocommerce a.button.alt,
		.scheme .sidebar .widget .menu li a:hover,
		.scheme .sidebar .widget .menu li.current-menu-item a,
		.woocommerce a.button:hover,
		.scheme .widget_product_search input[type="submit"],
		.scheme .related.products h2:after,
		.scheme a.read-more:hover,
		.scheme .tagcloud a:hover,
		.scheme .widget_shopping_cart_content .buttons a.button,
		.scheme .heading-title-3:before,
		.scheme .counting .heading:before,
		.scheme .price-active,
		.dates,
		.tp-caption.home-slider-button, .home-slider-button a:hover,
		.single_job_listing .application .application_button:hover,
		.scheme .counting .heading:before {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		.scheme .clients .owl-item div:hover,
		.scheme .header-right i,
		.scheme .owl-controls .owl-dot.active,
		.scheme .download:hover,
		.woocommerce a.button:hover,
		.scheme a.read-more:hover,
		.scheme .search-box input[type=search],
		.scheme .sidebar .widget-title,
		.scheme .wpb_widgetised_column .widget-title,
		.structure .esg-filter-wrapper .esg-filterbutton.selected,
		.single_job_listing .application .application_button:hover,
		.scheme .our-partners img:hover {
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		.who .consulting .info div a {
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.price-table .vc_btn3.vc_general {
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.scheme .price-table .vc_btn3.vc_btn3-color-grey.vc_btn3-style-outline:hover {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			color: #fff !important;
		}

		.price-table-2 .vc_btn3.vc_general {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			color: #fff !important;
		}

		.scheme .price-table-2 .vc_btn3.vc_btn3-color-grey.vc_btn3-style-outline:hover {
			background-color: #fff !important;
			border-color: #fff !important;
			color: #aaa !important;
		}

		.navigation > div > ul > li > a {
			color: <?php echo get_theme_mod( 'menu_text_color',$color_scheme[13]) ?>;
		}

		.navigation .current-menu-item > a,
		.navigation .menu > li > a:hover,
		.navigation .menu > li.current-menu-item > a {
			color: <?php echo get_theme_mod( 'menu_text_color_hover',$color_scheme[14]) ?>;
		}

		.navigation .sub-menu li:first-child, .navigation .children li:first-child, .navigation > div > ul > li:hover .sub-menu, .navigation > div > ul > li:hover .children {
			border-top-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		.contact-page .call-us h4,
		.page-template-template-underconstruction .under:before,
		.scheme .work-with-us:before,
		.scheme .who .consulting .info:before,
		.woocommerce #respond input#submit.alt:hover,
		.woocommerce a.button.alt:hover,
		.woocommerce button.button.alt:hover,
		.woocommerce input.button.alt:hover,
		.scheme .home-projects,
		.scheme .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header,
		.scheme .testimonial:before, .scheme .home-projects:before,
		.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
		.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
		.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
		.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,
		.widget_shopping_cart_content .buttons a.button:hover,
		.projects-7:before,
		.counting:before {
			background-color: <?php echo get_theme_mod( 'secondary_color',$color_scheme[1]) ?>;
		}

		.scheme .clients .owl-nav div:hover:before, .scheme input[type="submit"]:hover, .woocommerce a.button {
			color: <?php echo get_theme_mod( 'secondary_color',$color_scheme[1]) ?>;
		}

		h1, h2, h3, h4, h5, h6 {
			color: <?php echo get_theme_mod( 'heading_color',$color_scheme[2]) ?>;
		}

		a, a:visited {
			color: <?php echo get_theme_mod( 'site_link_color',$color_scheme[3]) ?>;
		}

		.scheme a:hover {
			color: <?php echo get_theme_mod( 'site_hover_link_color',$color_scheme[4]) ?>;
		}

		body.scheme {
			background-color: <?php echo get_theme_mod( 'body_bg_color',$color_scheme[5]) ?>;
		}

		<?php if(get_theme_mod( 'custom_css_enable',custom_css_enable)) { ?>
		<?php echo html_entity_decode(get_theme_mod( 'custom_css')); ?>
		<?php } ?>
		<?php if(get_theme_mod( 'page_transition') == 'type2') { ?>
		.animsition,
		.animsition-overlay {
			position: relative;
			opacity: 0;

			-webkit-animation-fill-mode: both;
			-o-animation-fill-mode: both;
			animation-fill-mode: both;
		}

		<?php } ?>
		<?php if(get_theme_mod( 'custom_scrollbar_enable',custom_scrollbar_enable)) { ?>
		::-webkit-scrollbar {
			width: 10px;
			background-color: <?php echo get_theme_mod( 'secondary_color',$color_scheme[1]) ?>;
		}

		::-webkit-scrollbar-thumb {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		::-webkit-scrollbar-thumb:window-inactive {
			background: rgba(33, 33, 33, .3);
		}

		<?php } ?>
		.breadcrumb ul:before {
			content: '<?php echo get_theme_mod( 'breadcrumb_yah_text','You are here: ') ?>';
		}

		.tp-caption.home01-slider01-02,
		.home01-slider01-02 {
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
		}

		.home-slider-button:hover {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.home-slider-button:hover a {
			color: #fff !important;
		}

		.home01-slider02-03 {
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.tp-caption span.yellow {
			color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.eg-adams-container, .eg-jefferson-container {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
		}

		.vc_row:before {
			display: block !important;
		}

		.vc_images_carousel .vc_carousel-indicators li {
			background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			border-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?> !important;
			-webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
			-moz-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
			box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
		}

		.vc_images_carousel .vc_carousel-indicators .vc_active {
			background-color: #fff !important;
			border-color: #fff !important;
		}

		.images-carousel-2 .vc_images_carousel .vc_carousel-indicators li {
			border-color: #fff !important;
			box-shadow: none;
		}

		.images-carousel-2 .vc_images_carousel .vc_carousel-indicators .vc_active {
			background-color: #fff !important;
			border-color: #fff !important;
		}

		.has-bg {
			background-image: url('<?php echo get_theme_mod( 'default_heading_image',default_heading_image) ?>');
		}

		a.eg-thememove-company-news-element-18 {
			color: #999;
		}

		h1, h2, h3, h4, h5,
		.eg-thememove-features-1-element-0,
		.eg-thememove-features-2-element-0,
		.eg-thememove-company-news-element-0,
		.eg-thememove-features-3-element-0,
		.eg-thememove-features-2-element-15,
		.eg-thememove-features-4-element-15,
		.eg-thememove-features-3-element-18,
		.eg-thememove-company-news-element-18,
		.eg-thememove-blog-element-0,
		.eg-thememove-blog-element-3,
		.scheme a.read-more,
		.navigation,
		.intro,
		.vc_progress_bar,
		.wpb_accordion,
		.testimonial__content,
		.testimonial__author,
		.header-right, .structure .esg-filterbutton,
		.add_to_cart_button,
		.vc_btn,
		.vc_btn3,
		.tp-caption,
		.recent-posts__item a,
		.columns-4.woocommerce ul.products li.product,
		.sidebar .widget .menu li a,
		.wpb_widgetised_column .widget .menu li a,
		.dates,
		.share,
		.eg-thememove-feature-4-element-0,
		.eg-thememove-feature-4-element-15,
		.testimonials-list .author span:first-child,
		.faq-classic .vc_custom_heading:before,
		.faq-classic .wpb_text_column:before,
		.wpcf7 input[type="submit"],
		.single_job_listing .application .application_button,
		.contact-page .call-us .wpb_text_column:last-child p,
		.woocommerce ul.products li.product .add_to_cart_button {
			font-family: <?php echo get_theme_mod( 'site_heading_font_family',site_heading_font_family) ?>, sans-serif;
		}

		.navigation .sub-menu a,
		.download-btn .vc_btn3 {
			font-family: <?php echo get_theme_mod( 'body_font_family',body_font_family) ?>, sans-serif;
		}

		@media (max-width: 1199px) {
			.menu-link {
				color: <?php echo get_theme_mod( 'mobile_menu_toggle_color',$color_scheme[28]) ?>;
			}
		}

		@media only screen and (max-width: 768px) {
			h1 {
				font-size: <?php echo get_theme_mod( 'site_h1_font_size',site_h1_font_size)*0.9 ?>px;
			}

			h2 {
				font-size: <?php echo get_theme_mod( 'site_h2_font_size',site_h2_font_size)*0.9 ?>px;
			}

			h3 {
				font-size: <?php echo get_theme_mod( 'site_h3_font_size',site_h3_font_size)*0.9 ?>px;
			}

			h4 {
				font-size: <?php echo get_theme_mod( 'site_h4_font_size',site_h4_font_size)*0.9 ?>px;
			}

			h5 {
				font-size: <?php echo get_theme_mod( 'site_h5_font_size',site_h5_font_size)*0.9 ?>px;
			}
		}

		@media only screen and (max-width: 480px) {
			h1 {
				font-size: <?php echo get_theme_mod( 'site_h1_font_size',site_h1_font_size)*0.8 ?>px;
			}

			h2 {
				font-size: <?php echo get_theme_mod( 'site_h2_font_size',site_h2_font_size)*0.8 ?>px;
			}

			h3 {
				font-size: <?php echo get_theme_mod( 'site_h3_font_size',site_h3_font_size)*0.8 ?>px;
			}

			h4 {
				font-size: <?php echo get_theme_mod( 'site_h4_font_size',site_h4_font_size)*0.8 ?>px;
			}

			h5 {
				font-size: <?php echo get_theme_mod( 'site_h5_font_size',site_h5_font_size)*0.8 ?>px;
			}
		}

		@media only screen and (min-width: 992px) {
			.header-preset-02 .navigation > div > ul > li > a,
			.header-preset-03 .navigation > div > ul > li > a,
			.header-preset-05 .navigation > div > ul > li > a {
				border-right-color: <?php echo get_theme_mod( 'menu_border_right_color',$color_scheme[25]) ?>;
			}

			.navigation > div > ul > li.current-menu-item > a:after,
			.navigation > div > ul > li:hover > a:after {
				background-color: <?php echo get_theme_mod( 'primary_color',$color_scheme[0]) ?>;
			}

			.header-preset-05 .navigation > div > ul > li > a:first-child {
				border-left-color: <?php echo get_theme_mod( 'menu_border_right_color',$color_scheme[25]) ?>;
			}

			.header-preset-02 .navigation > div > ul > li.current-menu-item > a,
			.header-preset-02 .navigation > div > ul > li:hover > a {
				border-bottom-color: <?php echo get_theme_mod( 'menu_border_bottom_color',$color_scheme[27]) ?>;
			}

			.header-preset-03 .navigation > div > ul > li.current-menu-item > a,
			.header-preset-03 .navigation > div > ul > li:hover > a,
			.header-preset-05 .navigation > div > ul > li:hover > a,
			.header-preset-05 .navigation > div > ul > li.current-menu-item > a {
				border-top-color: <?php echo get_theme_mod( 'menu_border_top_color',$color_scheme[26]) ?>;
			}

			.header-preset-04.home .headroom--not-top.header {
				background-color: <?php echo get_theme_mod( 'secondary_color',$color_scheme[1]) ?>;
			}
		}
	</style>
<?php }

add_action( 'wp_head', 'thememove_css_custom_code' );