<?php

/* General */
/*---------------------------------*/

$hex_to_rgb_highlight_color = madhex2rgba($highlight_color, .8);
$hex_to_rgb_overlay_bg_color = madhex2rgba($overlay_bg_color, .3);
$hex_to_rgb_open_buttons_bg_color = madhex2rgba($open_buttons_bg_color, .8);

$output = "";
$output .= "
	::selection {
		background-color: $highlight_bg_color;
		color: $highlight_text_color;
	}

	::-moz-selection{
		background-color: $highlight_bg_color;
		color: $highlight_text_color;
	}

	::-webkit-scrollbar {
		background-color: $highlight_color;
	}

	 ::-webkit-scrollbar-thumb {
		background-color: $secondary_color;
	 }

	mark, ins {
		background-color: $highlight_bg_color;
		color: $highlight_text_color;
	}

	body {
		color: $general_font_color;
		font-size: $general_font_size;
	}

	body { background: $body_bg; }

	#header {
		background-color: $header_bg_color;
		border-top-color: $primary_color;
	}

	a { color: $primary_color; }

	.search-button:hover::before,
	.submit-search:hover::before,
	.close-search-form:hover::before,
	.info-block .icon-text-holder .icon-text-link:hover { color: $primary_color; }

	.image-overlay:hover .image-extra {
		background-color: $hex_to_rgb_overlay_bg_color;
	}

	a:hover,
	.entry-title a:hover,
	blockquote:before,
	.product .price,
	.summary .price ins .amount,
	ul.fl-countdown li span,
	table.compare-list td ins .amount,
	table.compare-list td > span.amount,
	table.shop_table td.product-subtotal,
	table.shop_table a.product-name:hover,
	.products .product h3 a:hover,
	.products .product h5 a:hover,
	.popup-modal .popup-close:hover::before,
	.cart-list .toggle-button:hover,
	.bar-login a:hover,
	.users-nav li a:hover,
	.image-overlay:hover .inner-extra a:hover,
	#header.type-4 .h_top_part p a:hover,
	#header.type-4 .bar-login a:hover,
	.view-grid-center .product-actions .compare:hover,
	.view-grid-center .yith-wcwl-add-to-wishlist > div > a:hover,
	#sidebar .widget_tag_cloud a:hover,
	#footer .widget_tag_cloud a:hover,
	#sidebar .yith-woocompare-widget a:hover,
	#footer .yith-woocompare-widget a:hover,
	#sidebar .product-categories li a:hover,
	#footer .product-categories li a:hover,
	.info-block.type-3 .icon-wrap,
	.vertical_list_type_5 li::before,
	.vertical_list_type_6 li::before,
	.vertical_list_type_7 li::before,
	.list-styles.upper-roman li:before,
	.list-styles.decimal li:before,
	.list-styles.upper-latin li:before,
	.error-404-text h2,
	.woof_container > .close_woof_container:hover::before,
	#sidebar .widget_meta li:hover > a,
	#sidebar .widget_links li:hover > a,
	#sidebar .widget_archive li:hover > a,
	#sidebar .widget_categories li:hover > a,
	#sidebar .widget_pages li:hover > a,
	#sidebar .widget_tag_cloud li:hover > a,
	#sidebar .widget_recent_entries li:hover > a,
	#sidebar .widget_recent_comments li:hover > a,
	#sidebar .widget_nav_menu ul.menu > li:hover > a,
	.post-meta a:hover,
	.info-block.type-1:hover .icon-wrap,
	.widget_shopping_cart_content .total li:last-child .amount,
	.cart-dropdown li .remove-item:hover,
	.single-product-summary .rating-box > span a:hover,
	.product-single-meta a:hover,
	#sidebar .widget_recent_reviews .product_list_widget li > a:hover,
	#footer .widget_recent_reviews .product_list_widget li > a:hover,
	#sidebar .widget_shopping_cart_content li .remove-item:hover,
	#footer .widget_shopping_cart_content li .remove-item:hover,
	table.shop_table .remove:hover,
	table.shop_table .remove:hover::before,
	.woocommerce-message a:hover,
	.woocommerce-info a:hover,
	.woocommerce-error a:hover,
	.form-row label abbr.required,
	#sidebar .widget_product_tag_cloud a:hover,
	#footer .widget_product_tag_cloud a:hover,
	.wpcf7-form span.required,
	.comment-title a:hover,
	.features-list li:before,
	.wpb_btn-transparent:hover,
	.form-row-wide span.lost_password:hover a,
	.product-categories li.active > a > .toggle-switch:before,
	.type-2 .banner-in-caption a:hover,
	.pricing-table.bg_color_dark .price-box dt,
	.vc_dropcap_type_1 > .dropcap-letter,
	#footer .widget_meta li:hover::after,
	#footer .widget_links li:hover::after,
	#footer .widget_archive li:hover::after,
	#footer .widget_categories li:hover::after,
	#footer .widget_pages li:hover::after,
	#footer .widget_tag_cloud li:hover::after,
	#footer .widget_recent_comments li:hover::after
	{
		color: $secondary_color;
	}

	.cart-set > li > a > span.count,
	.shopping-button,
	.shopping-button:hover .count,
	.add_to_cart_button,
	.product_type_simple,
	.single-product .single_add_to_cart_button,
	 table.compare-list .add_to_cart_button,
	 table.compare-list .added_to_cart,
	 table.compare-list .button,
	 .form-row .button,
	 .return-to-shop .button,
	 .foot-modal-button:hover,
	 .cwallowcookies.button,
	 .wpb_btn-orange,
	 .load-button,
	 .list-or-grid a:hover,
	 .list-or-grid a.active,
	 .woocommerce.widget .ui-slider .ui-slider-range,
	 .order-param-button a:hover,
	 .owl-theme .owl-controls .owl-buttons div:hover,
	 .pagination li > .prev:hover, .pagination li > .next:hover,
	 .nav-links > .prev:hover, .nav-links > .next:hover,
	 .projects-nav li a:hover,
	 .owl-theme .owl-controls .owl-page.active span,
	 .owl-theme .owl-controls.clickable .owl-page:hover span,
	 .info-block.type-1 .icon-wrap,
	 .wpb_text_column ul li:before,
	 .widget_shopping_cart_content .button.checkout,
	.woocommerce #respond input#submit,
	.woocommerce a.button,
	.woocommerce button.button,
	.woocommerce input.button,
	 .vertical_list_type_2 li::before,
	 .vertical_list_type_4 li::before,
	 .list-styles.fill li:before,
	 .vc_dropcap_type_2 > .dropcap-letter,
	 .dropcap-result,
	 .sticky-post,
	 .go-to-top.animate_finished:hover,
	 .wpb_btn-navy-blue:hover
	 {
		background-color: $secondary_color;
	 }

	 .cart-dropdown > li { border-top-color: $secondary_color;  }

	 .sticky-post:after { border-left-color: $secondary_color; }

	 .cart-dropdown > li:after { border-bottom-color: $secondary_color;  }

	 .list-styles.bordered li:before {
	 	border-color: $secondary_color;
	 	color: $secondary_color;
	 }

	 .vertical_list_type_3 li:before { border-color: $secondary_color; }

	#header_language_list ul,
	#currency-switcher ul,
	#contactform button[type='submit']:hover,
	.single-product .single_add_to_cart_button:hover,
	.single-product .product-actions .woocommerce-review-link:hover,
	.add_to_cart_button:hover,
	.product_type_simple:hover,
	.form-row .button:hover,
	.return-to-shop .button:hover,
	.foot-modal-button,
	.cwallowcookies.button:hover,
	.cwcookiesmoreinfo.button:hover,
	.wpb_btn-grey:hover,
	.wpb_btn-orange:hover,
	.wpb_btn-blue:hover,
	.wpb_btn-green:hover,
	.wpb_btn-yellow:hover,
	.load-button:hover,
	.single-product .yith-wcwl-add-to-wishlist > div > a:hover,
	.view-grid .yith-wcwl-add-to-wishlist > div > a:hover,
	.view-list .yith-wcwl-add-to-wishlist > div > a:hover,
	.view-list .product-actions .compare:hover,
	.single-product .product-actions .compare:hover,
	.view-grid .product-actions .compare:hover,
	.widget_product_search button[type='submit']:hover,
	.wpb_accordion_header.ui-accordion-header-active,
	.wpb_tour .wpb_tabs_nav li:hover > a,
	.wpb_tour .wpb_tabs_nav li.ui-tabs-active a,
	.widget_shopping_cart_content .button.view-cart:hover,
	.widget_shopping_cart_content .button.checkout:hover,
	.woocommerce #respond input#submit:hover,
	.woocommerce a.button:hover,
	.woocommerce button.button:hover,
	.woocommerce input.button:hover,
	div.product .woocommerce-tabs ul.tabs li:hover a,
	div.product .woocommerce-tabs ul.tabs li.active a,
	.product-nav-left .product-prev-button:hover,
	.product-nav-right .product-next-button:hover,
	.comment-form .form-submit #submit:hover,
	table.shop_table .actions .button:hover,
	.shipping-calculator-form button[type='submit']:hover,
	.follow-button:hover,
	.wpcf7-submit:hover,
	.post-nav-left .post-prev-button:hover,
	.post-nav-right .post-next-button:hover,
	.vc_toggle_active .vc_toggle_title,
	.tabs-nav li:hover > a,
	.tabs-nav li.active a,
	.wpb_tabs .wpb_tabs_nav li.ui-tabs-active a,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-tab.vc_active > a,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-tab > a:hover,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-tab > a:focus,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:hover,
	 .vc_tta-color-blue.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:focus,
	.shopping-button:hover,
	.wpb_btn-navy-blue,
	.widget_search .submit-search:hover,
	.wpb_btn-grey:hover {
		background-color: $highlight_color;
	}

	.open-button .curtain {
		background-color: $hex_to_rgb_open_buttons_bg_color;
	}

	.open-button .curtain:hover {
		background-color: $open_buttons_bg_color;
	}

	#header_language_list:before,
	#currency-switcher:before {
		border-bottom-color: $highlight_color;
	}

	.pricing-table.bg_color_dark .pricing-header {
		background-color: $pricing_header;
	}

	.pricing-table.bg_color_dark .price-box,
	.pricing-table.bg_color_dark .pricing-footer {
		background-color: $pricing_box;
	}

	.pricing-table.bg_color_dark .pricing-footer {
		background-color: $pricing_footer;
	}

";

/* Logo */
/*---------------------------------*/

$output .= "
	#header .logo {
		font-size: $logo_font_size;
	}

		#header .logo a > span {
			color: $logo_font_color;
		}
";

/* Headings */
/*---------------------------------*/

$output .= "
	h1 {
		color: $h1_font_color;
		font-size: $h1_font_size;
	}
	h2 {
		color: $h2_font_color;
		font-size: $h2_font_size;
	}
	h3 {
		color: $h3_font_color;
		font-size: $h3_font_size;
	}
	h4 {
		color: $h4_font_color;
		font-size: $h4_font_size;
	}
	h5 {
		color: $h4_font_color;
		font-size: $h4_font_size;
	}
	h6 {
		color: $h6_font_color;
		font-size: $h6_font_size;
	}
";

/* Layer Slider */
/*---------------------------------*/

$output .= "
	$color_scheme .ls-flatastic .ls-nav-prev:hover,
	$color_scheme .ls-flatastic .ls-nav-next:hover { background-color: $primary_color; }

	$color_scheme .ls-flatastic .ls-nav-start:hover:before,
	$color_scheme .ls-flatastic .ls-nav-stop:hover:before,
	$color_scheme .ls-flatastic .ls-nav-start.ls-nav-start-active:before,
	$color_scheme .ls-flatastic .ls-nav-stop.ls-nav-stop-active:before { color: $primary_color; }

	$color_scheme .ls-flatastic .ls-bottom-slidebuttons a.ls-nav-active,
	$color_scheme .ls-flatastic .ls-bottom-slidebuttons a:hover { background-color: $primary_color; }
";

/* Revolution Slider */
/*---------------------------------*/

$output .= "
	$color_scheme .tp-leftarrow.default.custom:hover,
	$color_scheme .tp-rightarrow.default.custom:hover { background-color: $primary_color; }

	$color_scheme .tp-bullets.simplebullets.custom .bullet.selected,
	$color_scheme .tp-bullets.simplebullets.custom .bullet:hover { background-color: $primary_color; }

";

/* Navigation */
/*---------------------------------*/

$output .= "

	.navigation > ul > li:hover > a,
	.navigation > ul > li.current-menu-item > a,
	.navigation > ul > li.current-menu-parent > a,
	.navigation > ul > li.current-menu-ancestor > a,
	.navigation > ul > li.current_page_item > a,
	.navigation > ul > li.current_page_parent > a,
	.navigation > ul > li.current_page_ancestor > a,
	.mega_main_menu_ul > li:hover > a.item_link,
	.mega_main_menu_ul > li.current-menu-item > a.item_link,
	.mega_main_menu_ul > li.current-menu-parent > a.item_link,
	.mega_main_menu_ul > li.current-menu-ancestor > a.item_link,
	.mega_main_menu_ul > li.current_page_item > a.item_link,
	.mega_main_menu_ul > li.current_page_parent > a.item_link,
	.mega_main_menu_ul > li.current_page_ancestor > a.item_link {
		background-color: $primary_color;
	}

	.navigation ul li ul.children,
	.navigation ul li ul.sub-menu,
	#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.default_dropdown ul.mega_dropdown,
	#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.multicolumn_dropdown > ul.mega_dropdown {
		border-top-color: $primary_color;
	}

	.navigation ul li ul.children:before,
	.navigation ul li ul.sub-menu:before,
	#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.default_dropdown > ul.mega_dropdown:before,
	#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.multicolumn_dropdown > ul.mega_dropdown:before {
		border-bottom-color: $primary_color;
	}

	.navigation ul.children li:hover > a,
	.navigation ul.children li.current-menu-item > a,
	.navigation ul.children li.current-menu-parent > a,
	.navigation ul.children li.current-menu-ancestor > a,
	.navigation ul.children li.current_page_item > a,
	.navigation ul.children li.current_page_parent > a,
	.navigation ul.children li.current_page_ancestor > a,
	.navigation ul.sub-menu li:hover > a,
	.navigation ul.sub-menu li.current-menu-item > a,
	.navigation ul.sub-menu li.current-menu-parent > a,
	.navigation ul.sub-menu li.current-menu-ancestor > a,
	.navigation ul.sub-menu li.current_page_item > a,
	.navigation ul.sub-menu li.current_page_parent > a,
	.navigation ul.sub-menu li.current_page_ancestor > a,
	#mega_main_menu li .mega_dropdown > li > a.item_link:hover,
	#mega_main_menu li .mega_dropdown > li.current-menu-item > a.item_link,
	#mega_main_menu li .mega_dropdown > li.current-menu-parent > a.item_link,
	#mega_main_menu li .mega_dropdown > li.current-menu-ancestor > a.item_link
	{
		color: $secondary_color;
	}

	@media only screen and (min-width: 993px) {
		#header.type-2 #mega_main_menu > .menu_holder > .menu_inner > ul > li > .item_link,
		#header.type-4 #mega_main_menu > .menu_holder > .menu_inner > ul > li > .item_link {
			color: $nav_type_2_4_text_color;
		}
	}

	@media only screen and (max-width: 992px) {

		.mobile-button 		  { background-color: $secondary_color; }
		.mobile-button.active { background-color: $highlight_color; }

		.navigation > ul > li > a,
		.navigation > ul > li:hover > a,
		.navigation > ul > li.current-menu-item > a,
		.navigation > ul > li.current-menu-parent > a,
		.navigation > ul > li.current-menu-ancestor > a,
		.navigation > ul > li.current_page_item > a,
		.navigation > ul > li.current_page_parent > a,
		.navigation > ul > li.current_page_ancestor > a,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li > .item_link,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li:hover > .item_link,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current-menu-item > .item_link *,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current-menu-parent > .item_link *,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link *,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current_page_item > .item_link *,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current_page_parent > .item_link *,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li.current_page_ancestor > .item_link * {
			color: $secondary_color;
		}

		.navigation > ul > li:hover > a,
		.navigation > ul > li.current-menu-item > a,
		.navigation > ul > li.current-menu-parent > a,
		.navigation > ul > li.current-menu-ancestor > a,
		.navigation > ul > li.current_page_item > a,
		.navigation > ul > li.current_page_parent > a,
		.navigation > ul > li.current_page_ancestor > a,
		.mega_main_menu_ul > li:hover > a.item_link,
		.mega_main_menu_ul > li.current-menu-item > a.item_link,
		.mega_main_menu_ul > li.current-menu-parent > a.item_link,
		.mega_main_menu_ul > li.current-menu-ancestor > a.item_link,
		.mega_main_menu_ul > li.current_page_item > a.item_link,
		.mega_main_menu_ul > li.current_page_parent > a.item_link,
		.mega_main_menu_ul > li.current_page_ancestor > a.item_link {
			background-color: $secondary_color;
		}

		.navigation > ul > li > a,
		#mega_main_menu > .menu_holder > .menu_inner > ul > li > .item_link { border-color: $secondary_color; }

	}

";

/* Sidebar */
/*---------------------------------*/

$output .= "
	#sidebar .widget .widget-head { background-color: $widget_head_bg; }
";

/* Footer */
/*---------------------------------*/

$output .= "
	#footer { background-color: $footer_bg_color; }

		.footer_bottom_part { background-color: $footer_bottom_part_bg_color; }

			#sidebar .widget .product_list_widget a:hover h6,
			#footer .widget .product_list_widget a:hover h6,
			#sidebar .entry-post-holder a:hover h6,
			#footer .entry-post-holder a:hover h6,
			#footer .latest-tweets .tweet-text a:hover,

			#footer .widget_meta li:hover > a,
			#footer .widget_links li:hover > a,
			#footer .widget_archive li:hover > a,
			#footer .widget_categories li:hover > a,
			#footer .widget_pages li:hover > a,
			#footer .widget_tag_cloud li:hover > a,
			#footer .widget_recent_entries li:hover > a,
			#footer .widget_recent_comments li:hover > a,
			#footer .widget_nav_menu ul.menu > li:hover > a {
				color: $secondary_color;
			}

";

/* Widgets */
/*---------------------------------*/

$output .= "
	.widget_zn_mailchimp button[type='submit'] {
		background-color: $secondary_color;
	}
";

/* Owl Carousel */
/*---------------------------------*/

$output .= "
	.owl-tm-theme .owl-controls .owl-buttons div:hover,
	.owl-qv-carousel-theme .owl-controls .owl-buttons div:hover,
	.owl-widget-theme .owl-controls .owl-buttons div:hover
	{
		background-color: $primary_color;
	}
";

/* Shortcodes */
/*---------------------------------*/

$output .= "
	.info-block.type-2 .icon-wrap {
		color: $primary_color;
	}

	.info-block.type-2:hover .icon-wrap {
		background-color: $primary_color;
	}
";


/*---------------------------------*/
/* CUSTOM */
/*---------------------------------*/

$hex_to_rgb_custom_primary_color_opacity_0_5 = madhex2rgba($custom_primary_color, .5);
$hex_to_rgb_custom_primary_color_opacity_0_8 = madhex2rgba($custom_primary_color, .8);

$quick_view_bg_color_opacity_0_5 = madhex2rgba($quick_view_bg_color, .5);
$quick_view_bg_color_opacity_0_8 = madhex2rgba($quick_view_bg_color, .8);

$output .= "

	input[type='text'],
	input[type='email'],
	input[type='password'],
	input[type='search'],
	select,
	textarea,
	.select2-container,
	.custom-select .select-title,
	.product-quantity .quantity > input[type='button'],
	.product-quantity .quantity > input[type='number'],
	.features-list > li,
	.woof_list_checkbox a:before,
	.woof_list_radio a:before,
	input[type=checkbox] + label:before,
	input[type=radio] + label:before,
	.heapBox .holder { background-color: $input_bg_color; }

	@media only screen and (min-width: 993px) {
		#mega_main_menu li .mega_dropdown > li > a.item_link:hover,
		#mega_main_menu li .mega_dropdown > li.current-menu-item > a.item_link,
		#mega_main_menu li .mega_dropdown > li.current-menu-parent > a.item_link,
		#mega_main_menu li .mega_dropdown > li.current-menu-ancestor > a.item_link {
			background-color: $bg_sub_menu;
		}
	}

	#mega_main_menu li.default_dropdown > .mega_dropdown > li > .item_link { }

	#mega_main_menu li.default_dropdown .item_link { color: $custom_primary_color; }

	.portfolio-filter li a { color: $custom_primary_color; }

	ul.post-carousel .post-title a 		 { color: $custom_primary_color; }
	ul.post-carousel .post-title a:hover { color: $secondary_color;    }

	.single-product .yith-wcwl-add-to-wishlist > div > a,
	.view-grid .yith-wcwl-add-to-wishlist > div > a,
	.product-filter li button,
	.single-product .product-actions .compare,
	.single-product .product-actions .woocommerce-review-link,
	.view-grid .product-actions .compare,
	.heapBox .heap .heapOptions .heapOption a:hover,
	.heapBox .heap .heapOptions .heapOption a.selected,
	.custom-select .select-list > li:hover,
	.wpb_accordion_header,
	.wpb_tour .wpb_tabs_nav a,
	.vc_toggle_title,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading,
	.vc_tta-color-blue.vc_tta-style-classic .vc_tta-tab > a,
	.wpb_tabs .wpb_tabs_nav li a,
	.widget_product_search button[type='submit'],
	.order-param-button a,
	.woocommerce.widget .ui-slider .ui-slider-handle,
	.woocommerce.widget .price_slider_wrapper .ui-widget-content,
	.list-or-grid a,
	.view-list .yith-wcwl-add-to-wishlist > div > a,
	.view-list .product-actions .compare { background-color: $custom_secondary_color; }

	.users-nav li a,
	.product-filter li:not(.active),
	.products .product h3 a,
	.products .product h5 a,
	.post-meta a,
	.entry-title a,
	.twitterfeed .tweet a,
	.info-block.type-5 .icon-wrap,
	.view-list .yith-wcwl-add-to-wishlist > div > a:before,
	.view-list .product-actions .compare:before,
	.comment-title a,
	.info-list li i.fa,
	.wpb_btn-grey,
	.wpcf7-submit,
	.list-or-grid a::before,
	.product-single-meta a,
	.product .price ins,
	.widget .product_list_widget li .entry-post-holder ins,
	.post-item .read-more:hover { color: $custom_primary_color; }

	.info-block.type-1:hover .icon-wrap { background-color: $bg_info_block; }

	.product .quick-view {
		background-color: $quick_view_bg_color;
		background-color: $quick_view_bg_color_opacity_0_5;
	}

	.product .quick-view:hover {
		background-color: $quick_view_bg_color;
		background-color: $quick_view_bg_color_opacity_0_8;
	}

	.single-product .yith-wcwl-add-to-wishlist > div > a:before,
	.view-grid .yith-wcwl-add-to-wishlist > div > a:before,
	.single-product .product-actions .compare:before,
	.single-product .product-actions .woocommerce-review-link:before,
	.view-grid .product-actions .compare:before {
		color: $quick_view_bg_color;
	}

	#contactform button[type='submit'] {
		background-color: $custom_secondary_color;
		color: $custom_primary_color;
	}

	.widget_shopping_cart_content .button.view-cart {
		background-color: $button_bg_color;
		color: $custom_primary_color;
	}

	.wpb_btn-grey,
	table.shop_table .actions .button,
	.shipping-calculator-form button[type='submit'],
	.post-prev-button,
	.post-next-button,
	.product-prev-button,
	.product-next-button,
	.comment-form .form-submit #submit,
	.wpcf7-submit { background-color: $button_bg_color; }

	#header .widget_shopping_cart_content .total,
	.foot-modal-login,
	#sidebar .product-categories .toggle-switch { background-color: $hover_bg_grey ; }

	.tab-content form.login,
	.single-product-summary .rating-box > span a,
	.widget_tag_cloud a,
	#sidebar .product-categories a,
	.yith-woocompare-widget a,
	.wpb_accordion_header a,
	.wpb_tour .wpb_tabs_nav a,
	.wpb_tabs .wpb_tabs_nav li a,
	.order-param-button a:before,
	table.shop_table .sub-td,
	.widget_product_tag_cloud a,
	.widget_product_search button[type='submit']:before,
	table.shop_table .actions .button,
	.shipping-calculator-form button[type='submit'],
	table.shop_table a.product-name,
	table.shop_table .remove,
	.yith-wcwl-add-to-wishlist > div > a,
	.comment-form .form-submit #submit,
	.product-prev-button:before,
	.product-next-button:before,
	.product-nav-right span,
	.product-nav-left span,
	.post-prev-button,
	.post-next-button,
	.product-prev-button,
	.product-next-button { color: $custom_primary_color; }

	.qv-review-expand:hover { background-color: $hex_to_rgb_custom_primary_color_opacity_0_8; }

	.go-to-top,
	.owl-theme .owl-controls .owl-buttons div,
	.qv-review-expand { background-color: $hex_to_rgb_custom_primary_color_opacity_0_5; }

	.vc_progress_bar .vc_single_bar .vc_progress,
	.features-list > li:nth-child(2n+1) {
		background-color: $list_bg_color;
	}

	.breadcrumbs { background-color: $breadcrumb_bg_color; }

	.tabs-nav a,
	 div.product .woocommerce-tabs ul.tabs li a {
		background-color: $tabs_bg_color;
	}

	.product-filter li:hover > button,
	.product-filter li.active > button,
	.portfolio-filter li.active > a,
	.portfolio-filter li:hover > a {
		background-color: $filter_active_color;
	}

	.product-filter li button:hover:after,
	.product-filter li.active button:after,
	.portfolio-filter li a:hover:after,
	.portfolio-filter li.active a:after {
		border-top-color: $filter_active_color;
	}

	.shopping-button .count { background-color: $button_count; }

";

global $mad_config;
$mad_config['styles'] = array(

	array(
		'commenting' => 'Dynamic Styles',
		'values' => array(
			'returnValue' => $output
		)
	),

	array(
		'elements' => 'body',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('general_google_webfont')
		)
	),
	array(
		'elements' => '#header .logo',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-logo_font_family')
		)
	),

	// Heading H1
	array(
		'elements' => 'h1',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h1_font_family')
		)
	),
	// Heading H2
	array(
		'elements' => 'h2',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h2_font_family')
		)
	),
	// Heading H3
	array(
		'elements' => 'h3',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h3_font_family')
		)
	),
	// Heading H4
	array(
		'elements' => 'h4',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h4_font_family')
		)
	),
	// Heading H5
	array(
		'elements' => 'h5',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h5_font_family')
		)
	),
	// Heading H6
	array(
		'elements' => 'h6',
		'values' => array(
			'google_webfonts' => mad_custom_get_option('styles-h6_font_family')
		)
	),

	// The Quick Custom CSS
	array(
		'commenting' => 'Custom Styles',
		'values' => array(
			'returnValue' => html_entity_decode(mad_custom_get_option('custom_quick_css'))
		)
	)
);