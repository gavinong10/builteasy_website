/*--------------------------------------------------------------
 Custom js
 --------------------------------------------------------------*/
jQuery( document ).ready( function ( $ ) {
	'use strict';

	//setup parallax
	$.stellar();

	$( '.gallery,.single-featured' ).magnificPopup( {
		delegate: 'a', // child items selector, by clicking on it popup will open
		type: 'image',
		removalDelay: 300,
		mainClass: 'mfp-fade',
		gallery: {
			enabled: true
		}
	} );

	$( '.popup-youtube, .popup-vimeo, .popup-gmaps' ).magnificPopup( {
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false
	} );

	//owl carousel
	$( ".wpb_row .client" ).owlCarousel(
		{
			nav: true,
			dots: false,
			loop: true,
			autoplay: true,
			autoplayHoverPause: true,
			autoplayTimeout: 3000,
			autoHeight: true,
			margin: 20,
			responsive: {
				0: {
					items: 2
				},
				768: {
					items: 3
				},
				1024: {
					items: 6
				}
			}
		}
	);
	$( ".single-project .gallery" ).owlCarousel(
		{
			nav: false,
			dots: false,
			loop: true,
			autoplay: true,
			autoplayHoverPause: true,
			autoplayTimeout: 3000,
			margin: 20,
			responsive: {
				0: {
					items: 2
				},
				768: {
					items: 3
				},
				1024: {
					items: 6
				}
			}
		}
	);

	$( '.counter h2' ).counterUp( {
		delay: 10,
		time: 3000
	} );
	$( ".single-project .wpb_gallery .wpb_image_grid_ul" ).owlCarousel(
		{
			nav: false,
			dots: false,
			loop: true,
			autoplay: true,
			autoplayHoverPause: true,
			autoplayTimeout: 3000,
			margin: 10,
			responsive: {
				0: {
					items: 2
				},
				768: {
					items: 3
				},
				1024: {
					items: 6
				}
			}
		}
	);

	$( ".wpb_row .testimonials-list" ).owlCarousel(
		{
			items: 1,
			navigation: false,
			dots: true,
			loop: true,
			autoplay: true,
			autoplayHoverPause: true
		}
	);

	//menu setup
	var $menu = $( '.navigation' ),
		$menulink = $( '.menu-link' );

	$menulink.click( function () {
		$menulink.toggleClass( 'active' );
		$menu.toggleClass( 'active' );
		return false;
	} )

	$( '.navigation' ).find( '.sub-menu-toggle' ).on( 'click', function ( e ) {
		var subMenu = $( this ).parent().find( 'ul' ).first();
		var thisLi = $( this ).parent();
		if ( subMenu.css( 'display' ) != 'block' ) {
			subMenu.css( 'display', 'block' );
			thisLi.addClass( 'is-open' );
		} else {
			subMenu.css( 'display', 'none' );
			thisLi.removeClass( 'is-open' );
		}
		e.stopPropagation();
	} );

	// mini-cart
	var $mini_cart = $( '.mini-cart' );
	$mini_cart.on( 'click', function ( e ) {
		$( this ).addClass( 'open' );
	} );

	$( document ).on( 'click', function ( e ) {
		if ( $( e.target ).closest( $mini_cart ).length == 0 ) {
			$mini_cart.removeClass( 'open' );
		}
	} );

	// search in menu
	var $search_btn = $( '.search-box > i' ),
		$search_form = $( 'form.search-form' );

	$search_btn.on( 'click', function () {
		$search_form.toggleClass( 'open' );
	} );

	$( document ).on( 'click', function ( e ) {
		if ( $( e.target ).closest( $search_btn ).length == 0
		     && $( e.target ).closest( 'input.search-field' ).length == 0
		     && $search_form.hasClass( 'open' ) ) {
			$search_form.removeClass( 'open' );
		}
	} );

} );