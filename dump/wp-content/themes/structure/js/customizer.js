/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
(function ($) {
  wp.customize('primary_color', function (value) {
    value.bind(function (to) {
      $('.pagination span,.woocommerce ul.products li.product .price,.woocommerce div.product p.price, .woocommerce div.product span.price,.scheme .top-area i,.scheme .testimonial__author,.scheme .structure .esg-filter-wrapper .esg-filterbutton.selected,.scheme .structure .esg-filter-wrapper .esg-filterbutton:hover,.scheme .our-team h4,.scheme .has-bg span,.scheme .footer .menu li:hover:before,.scheme .testimonials-list .author span:first-child').css('color', to ? to : '');
    });
  });
  wp.customize('primary_color', function (value) {
    value.bind(function (to) {
      $('.scheme .scrollup,.scheme.single-project .gallery a:after,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce span.onsale, .woocommerce button.button.alt,.scheme .intro,.scheme .wpb_accordion_wrapper .ui-state-active .ui-icon:before,.scheme .clients .owl-nav div:hover:before,.scheme .owl-controls .owl-dot.active,.scheme .eg-howardtaft-container,.scheme .structure .esg-navigationbutton,.scheme .heading-title-2:after,.scheme .heading-title:after,.scheme .comments-title:after,.scheme .comment-reply-title:after,.scheme .widget-title:after,.scheme input[type="submit"]:hover, .navigation .sub-menu li a:hover,.navigation .children li a:hover,.scheme .sidebar .widget .menu li:hover,.scheme .sidebar .widget .menu li.current-menu-item,.scheme .features .wpb_wrapper p:first-child:after,.scheme .recent-posts__thumb:after, .woocommerce a.button.alt,.scheme .sidebar .widget .menu li a:hover,.scheme .sidebar .widget .menu li.current-menu-item a, .woocommerce a.button:hover,.scheme .widget_product_search input[type="submit"],.scheme .related.products h2:after').css('background-color', to ? to : '');
    });
  });
  wp.customize('primary_color', function (value) {
    value.bind(function (to) {
      $('.scheme .clients .owl-item div:hover,.scheme .header-right i,.scheme .owl-controls .owl-dot.active,.scheme .download:hover,.woocommerce a.button:hover').css('border-color', to ? to : '');
    });
  });
  wp.customize('primary_color', function (value) {
    value.bind(function (to) {
      $('.navigation .sub-menu li:first-child, .navigation .children li:first-child,.navigation > div > ul > li:hover .sub-menu, .navigation > div > ul > li:hover .children').css('border-top-color', to ? to : '');
    });
  });
  wp.customize('secondary_color', function (value) {
    value.bind(function (to) {
      $('.secondary,.clients .owl-nav div:hover:before,input[type="submit"]:hover').css('color', to ? to : '');
    });
  });
  wp.customize('secondary_color', function (value) {
    value.bind(function (to) {
      $('.scheme .who .consulting .info:before,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.scheme .home-projects,.scheme .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header,.scheme .testimonial:before,.scheme .home-projects:before, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active').css('background-color', to ? to : '');
    });
  });
  wp.customize('secondary_color', function (value) {
    value.bind(function (to) {
      $('.scheme .clients .owl-nav div:hover:before,.scheme input[type="submit"]:hover, .woocommerce a.button').css('color', to ? to : '');
    });
  });
  wp.customize('heading_color', function (value) {
    value.bind(function (to) {
      $('h1,h2,h3,h4,h5,h6').css('color', to ? to : '');
    });
  });
  wp.customize('body_bg_color', function (value) {
    value.bind(function (to) {
      $('body').css('background-color', to ? to : '');
    });
  });
  wp.customize('body_text_color',function( value ) {
    value.bind(function(to) {
      $('body').css('color', to ? to : '' );
    });
  });
  wp.customize('site_link_color',function( value ) {
    value.bind(function(to) {
      $('a,a:visited').css('color', to ? to : '' );
    });
  });
  wp.customize('site_hover_link_color',function( value ) {
    value.bind(function(to) {
      $('a:hover').css('color', to ? to : '' );
    });
  });
  wp.customize('header_top_area_bg_color', function (value) {
    value.bind(function (to) {
      $('.top-area').css('background-color', to ? to : '');
    });
  });
  wp.customize('header_top_area_link_color', function (value) {
    value.bind(function (to) {
      $('.top-area a').css('color', to ? to : '');
    });
  });
  wp.customize('header_top_area_hover_link_color', function (value) {
    value.bind(function (to) {
      $('.top-area a:hover').css('color', to ? to : '');
    });
  });
  wp.customize('header_top_area_text_color', function (value) {
    value.bind(function (to) {
      $('.top-area').css('color', to ? to : '');
    });
  });
  wp.customize('header_bg_color', function (value) {
    value.bind(function (to) {
      $('.header').css('background-color', to ? to : '');
    });
  });
  wp.customize('header_text_color', function (value) {
    value.bind(function (to) {
      $('.header').css('color', to ? to : '');
    });
  });
  wp.customize('search_color', function (value) {
    value.bind(function (to) {
      $('.search-box i').css('color', to ? to : '');
    });
  });
  wp.customize('cart_button_color', function (value) {
    value.bind(function (to) {
      $('.mini-cart .mini-cart__button .mini-cart-icon').css('color', to ? to : '');
    });
  });
  wp.customize('cart_number_bg_color', function (value) {
    value.bind(function (to) {
      $('.mini-cart .mini-cart__button .mini-cart-icon:after').css('background-color', to ? to : '');
    });
  });
  wp.customize('menu_bg_color', function (value) {
    value.bind(function (to) {
      $('.navigation').css('background-color', to ? to : '');
    });
  });
  wp.customize('menu_text_color', function (value) {
    value.bind(function (to) {
      $('.scheme .navigation a:before,.scheme .navigation a:after, .navigation .current-menu-item >a, .navigation .menu >li >a:hover').css('color', to ? to : '');
    });
  });
  wp.customize('menu_text_color_hover', function (value) {
    value.bind(function (to) {
      $('.navigation .current-menu-item > a, .navigation .menu > li > a:hover, .navigation .menu > li.current-menu-item > a').css('color', to ? to : '');
    });
  });
  wp.customize('menu_border_right_color', function (value) {
    value.bind(function (to) {
      $('.navigation > div > ul > li > a').css('border-right-color', to ? to : '');
    });
  });
  wp.customize('menu_border_top_color', function (value) {
    value.bind(function (to) {
      $('.header-preset-02 .navigation > div > ul > li.current-menu-item > a, .header-preset-05 .navigation > div > ul > li.current-menu-item > a, .header-preset-02 .navigation > div > ul > li:hover > a, .header-preset-05 .navigation > div > ul > li:hover > a').css('border-top-color', to ? to : '');
    });
  });
  wp.customize('menu_border_bottom_color', function (value) {
    value.bind(function (to) {
      $('.header-preset-02 .navigation > div > ul > li.current-menu-item > a, .header-preset-05 .navigation > div > ul > li.current-menu-item > a, .header-preset-02 .navigation > div > ul > li:hover > a, .header-preset-05 .navigation > div > ul > li:hover > a').css('border-bottom-color', to ? to : '');
    });
  });
  wp.customize('sub_menu_bg_color', function (value) {
    value.bind(function (to) {
      $('.navigation .children').css('background-color', to ? to : '');
    });
  });
  wp.customize('footer_bg_color', function (value) {
    value.bind(function (to) {
      $('.footer').css('background-color', to ? to : '');
    });
  });
  wp.customize('footer_heading_color', function (value) {
    value.bind(function (to) {
      $('.footer .widget-title').css('color', to ? to : '');
    });
  });
  wp.customize('footer_text_color', function (value) {
    value.bind(function (to) {
      $('.footer').css('color', to ? to : '');
    });
  });
  wp.customize('footer_link_color', function (value) {
    value.bind(function (to) {
      $('.footer a').css('color', to ? to : '');
    });
  });
  wp.customize('footer_hover_link_color', function (value) {
    value.bind(function (to) {
      $('.footer a:hover').css('color', to ? to : '');
    });
  });
  wp.customize('copyright_bg_color', function (value) {
    value.bind(function (to) {
      $('.copyright').css('background-color', to ? to : '');
    });
  });
  wp.customize('copyright_text_color', function (value) {
    value.bind(function (to) {
      $('.copyright').css('color', to ? to : '');
    });
  });
  wp.customize('copyright_link_color', function (value) {
    value.bind(function (to) {
      $('.copyright a').css('color', to ? to : '');
    });
  });
  wp.customize('copyright_link_color:hover', function (value) {
    value.bind(function (to) {
      $('.copyright a:hover').css('color', to ? to : '');
    });
  });
  wp.customize('body_font_size', function (value) {
    value.bind(function (to) {
      $('body,[class*="col-"],.footer .menu li').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h1_font_size', function (value) {
    value.bind(function (to) {
      $('h1').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h2_font_size', function (value) {
    value.bind(function (to) {
      $('h2').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h3_font_size', function (value) {
    value.bind(function (to) {
      $('h3').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h4_font_size', function (value) {
    value.bind(function (to) {
      $('h4').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h5_font_size', function (value) {
    value.bind(function (to) {
      $('h5').css('font-size', to ? to + 'px' : '');
    });
  });
  wp.customize('site_h6_font_size', function (value) {
    value.bind(function (to) {
      $('h6').css('font-size', to ? to + 'px' : '');
    });
  });
})(jQuery);
