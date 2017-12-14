/*
 * Function for Mega Main Menu.
 */
;jQuery(document).ready(function(){

	/*
	 * Unbinded all previous JS actions with menu.
	 */
//	;jQuery( '#mega_main_menu, #mega_main_menu *' ).unbind();

	/*
	 * INIT
	 */
//	mm_sticky_menu();
//	mmm_fullwidth_menu();

	/*
	 * EVENTS
	 */
	;jQuery(window).resize( function(){
//		mmm_fullwidth_menu();
//		mm_sticky_menu();
	});


	/*
	 * Reversal z-index.
	 */
//	var z_index = 5000;
//	;jQuery( '.mega_main_menu' ).each(function(index,element){
//		z_index = z_index - 10;
//		jQuery( element ).css({
//			'z-index' : z_index
//		});
//	});

	/*
	 * Mobile toggle menu
	 */
//	;jQuery( '.mobile_toggle' ).click(function() {
//		jQuery( this ).parent().toggleClass( 'mobile_menu_active' );
//		jQuery( '#mega_main_menu .keep_open' ).removeClass('keep_open');
//	});

	/*
	 * Mobile Double tap to go
	 */
	;if( /iphone|ipad|ipod|android|webos|blackberry|iemobile|opera mini/i.test( navigator.userAgent.toLowerCase() ) )
	{
		var clicked_item = false;
		jQuery('#mega_main_menu li:has(.mega_dropdown) > .item_link').on( 'click', function( index ) {

			if ( clicked_item != this) {
				index.preventDefault();
				if ( jQuery( this ).parent().parent().parent().hasClass('keep_open') ) {

				} else {
					jQuery( '#mega_main_menu .keep_open' ).removeClass('keep_open');
				}
				jQuery( this ).parent().addClass('keep_open');
				clicked_item = this;
			}
		});
	}

	/*
	 * Sticky menu
	 */
	function mm_sticky_menu () {
		;jQuery( '#mega_main_menu > .menu_holder' ).each(function(index,element){

			var stickyoffset = [];
			var menu_inner_width = [];
			var menu_inner = [];
			var style_attr = [];
			menu_inner[ index ] = jQuery( element ).find( '.menu_inner' );
			stickyoffset[ index ] = jQuery( element ).data( 'stickyoffset' ) * 1;

			if ( jQuery( element ).attr( 'data-sticky' ) == '1' && stickyoffset[ index ] == 0 ) {
				menu_inner_width[ index ] = menu_inner[ index ].parents( '.mega_main_menu' ).width();
				menu_inner[ index ].attr( 'style' , 'width:' + menu_inner_width[ index ] + 'px;' );
				jQuery( element ).addClass( 'sticky_container' );
			} else {
				;jQuery(window).on('scroll', function(){
					if ( jQuery( element ).attr( 'data-sticky' ) == '1' ) {
						scrollpath = jQuery(window).scrollTop();
						if ( scrollpath > stickyoffset[ index ] ) {
							menu_inner_width[ index ] = menu_inner[ index ].parents( '.mega_main_menu' ).width();
							jQuery( element ).find( '.mmm_fullwidth_container' ).css({ 'left' : '0px' });
							jQuery( element ).find( '.menu_inner' ).attr( 'style' , 'width:' + menu_inner_width[ index ] + 'px;' );
							if ( !jQuery( element ).hasClass( 'sticky_container' ) ) {
								jQuery( element ).addClass( 'sticky_container' );
							}
						} else {
							mmm_fullwidth_menu();
							jQuery( element ).removeClass( 'sticky_container' );
							style_attr[ index ] = jQuery( menu_inner[ index ] ).attr( 'style' );
							if ( typeof style_attr[ index ] !== 'undefined' && style_attr[ index ] !== false ) {
								menu_inner[ index ].removeAttr( 'style' );
							}
						}
					} else {
						jQuery( element ).removeClass( 'sticky_container' );
					}
				});
			}
		});
	}

	/*
	 * Fullwidth menu container
	 */
	function mmm_fullwidth_menu () {
		body_width = jQuery( 'body' ).width();
		jQuery( '.mega_main_menu.direction-horizontal.fullwidth-enable' ).each( function( index, element ) {
			offset_left = jQuery( element ).offset().left;
			if ( jQuery( element ).hasClass( 'coercive_styles-enable' ) ) {
				rules_priority = ' !important';
			} else {
				rules_priority = '';
			}
			jQuery( element ).find( '.mmm_fullwidth_container' ).attr( 'style' , 'width:' + body_width + 'px' + rules_priority + ';left: -' + offset_left + 'px' + rules_priority + ';right:auto' + rules_priority + ';' );
		});
	}

	/*
	 * Smooth scroll to anchor link
	 */
//	jQuery(function() {
//		jQuery('#mega_main_menu a[href*=#]:not([href=#])').click(function() {
//			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
//				var target = jQuery(this.hash);
//				target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +'], [id=' + this.hash.slice(1) +']');
//				if (target.length) {
//					jQuery('html,body').animate({
//						scrollTop: target.offset().top - 90
//					}, 600);
//					return false;
//				}
//			}
//		});
//	});

	/*
	 * Keep dropdown open if some inner element has a :focus.
	 */
//	jQuery(function() {
//		jQuery('#mega_main_menu .menu-item *').focus(function(){
//			jQuery( this ).parents('.menu-item, .post_item').addClass('keep_open');
//		})
//		jQuery('#mega_main_menu .menu-item *').on( 'hover', function(){
//			jQuery( this ).parents('.menu-item, .post_item').removeClass('keep_open');
//		})
//		jQuery('#mega_main_menu .menu-item *').blur(function(){
//			jQuery( this ).parents('.menu-item, .post_item').removeClass('keep_open');
//		})
//	});

	/*
	 *
	 */
//	jQuery(function() {
//		jQuery('#mega_main_menu .tabs_dropdown > .mega_dropdown > li').on( 'hover', function(){
//			jQuery( this ).parent().css({
//				"min-height": jQuery( this ).find(' > .mega_dropdown').outerHeight( true )
//			});
//		});
//		jQuery('#mega_main_menu .tabs_dropdown > .mega_dropdown > li').on( 'mouseleave', function(){
//			jQuery( this ).parent().css({
//				"min-height": '0px'
//			});
//		});
//	});


});
