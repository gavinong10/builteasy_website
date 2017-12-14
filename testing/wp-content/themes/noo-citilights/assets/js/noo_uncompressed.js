/**
 * NOO Site Script.
 *
 * Javascript used in NOO-Framework
 * This file contains base script used on the frontend of NOO theme.
 *
 * @package    NOO Framework
 * @subpackage NOO Site
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

;(function($){
	var nooGetViewport = function() {
	    var e = window, a = 'inner';
	    if (!('innerWidth' in window )) {
	        a = 'client';
	        e = document.documentElement || document.body;
	    }
	    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	};
	var nooGetURLParameters = function(url) {
	    var result = {};
	    var searchIndex = url.indexOf("?");
	    if (searchIndex == -1 ) return result;
	    var sPageURL = url.substring(searchIndex +1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++)
	    {       
	        var sParameterName = sURLVariables[i].split('=');      
	        result[sParameterName[0]] = sParameterName[1];
	    }
	    return result;
	};
	var nooInit = function() {
		if($( '.navbar' ).length) {
			var $window = $( window );
			var $body   = $( 'body' ) ;
			var navTop = $( '.navbar' ).offset().top;
			var lastScrollTop = 0,
				navHeight = 0,
				defaultnavHeight = $( '.navbar-nav' ).outerHeight();
			
			var navbarInit = function () {
				if(nooGetViewport().width > 992){
					var $this = $( window );
					var $navbar = $( '.navbar' );
					if ( $navbar.hasClass( 'fixed-top' ) ) {
						var navFixedClass = 'navbar-fixed-top';
						if( $navbar.hasClass( 'shrinkable' )  && !$body.hasClass('one-page-layout')) {
							navFixedClass += ' navbar-shrink';
						}
						var adminbarHeight = 0;
						if ( $body.hasClass( 'admin-bar' ) ) {
							adminbarHeight = $( '#wpadminbar' ).outerHeight();
						}
						var checkingPoint = navTop + defaultnavHeight;
						if($body.hasClass('one-page-layout')){
							checkingPoint = navTop;
						}

						if ( ($this.scrollTop() + adminbarHeight) > checkingPoint ) {
							if( $navbar.hasClass( 'navbar-fixed-top' ) ) {
								lastScrollTop = $this.scrollTop();
								return;
							}

							if( ! $navbar.hasClass('navbar-fixed-top') && ( ! $navbar.hasClass( 'smart_scroll' ) ) || ( $this.scrollTop() < lastScrollTop ) ) {								
								navHeight = $navbar.hasClass( 'shrinkable' ) ? Math.max(Math.round($( '.navbar-nav' ).outerHeight() - ($this.scrollTop() + adminbarHeight) + navTop),60) : $( '.navbar-nav' ).outerHeight();
								if($body.hasClass('one-page-layout')){
									navHeight = defaultnavHeight;
								}
								$('.navbar-wrapper').css({'min-height': navHeight+'px'});
								$navbar.closest('.noo-header').css({'position': 'relative'});
								$navbar.css({'min-height': navHeight+'px'});
								$navbar.find('.navbar-nav > li > a').css({'line-height': navHeight+'px'});
								$navbar.find('.navbar-brand').css({'height': navHeight+'px'});
								$navbar.find('.navbar-brand img').css({'max-height': navHeight+'px'});
								$navbar.find('.navbar-brand').css({'line-height': navHeight+'px'});
								$navbar.find('.calling-info').css({'max-height': navHeight+'px'});
								$navbar.addClass( navFixedClass );
								if( !$body.hasClass('one-page-layout') ) {
									$navbar.css('top', 0 - navHeight).animate( { 'top': adminbarHeight }, 300);
								} else {
									$navbar.css('top', adminbarHeight);
								}

								lastScrollTop = $this.scrollTop();
								return;
							}
						}

						lastScrollTop = $this.scrollTop();

						$navbar.removeClass( navFixedClass );
						$navbar.css({'top': ''});
						
						$('.navbar-wrapper').css({'min-height': ''});
						$navbar.closest('.noo-header').css({'position': ''});
						$navbar.css({'min-height': ''});
						$navbar.find('.navbar-nav > li > a').css({'line-height': ''});
						$navbar.find('.navbar-brand').css({'height': ''});
						$navbar.find('.navbar-brand img').css({'max-height': ''});
						$navbar.find('.navbar-brand').css({'line-height': ''});
								$navbar.find('.calling-info').css({'max-height': ''});
					}
				}
			};
			$window.bind('scroll',navbarInit).resize(navbarInit);
			if( $body.hasClass('one-page-layout') ) {
				var adminbarHeight = 0;
				if ( $body.hasClass( 'admin-bar' ) ) {
					adminbarHeight = $( '#wpadminbar' ).outerHeight();
				}
	
				// Scroll link
				$('.navbar-scrollspy > .nav > li > a[href^="#"]').click(function(e) {
					e.preventDefault();
					var target = $(this).attr('href').replace(/.*(?=#[^\s]+$)/, '');
					if (target && ($(target).length)) {
						var position = Math.max(0, $(target).offset().top );
							position = Math.max(0,position - (adminbarHeight + $('.navbar').outerHeight()) + 5);
						
						$('html, body').animate({
							scrollTop: position
						},{
							duration: 800, 
				            easing: 'easeInOutCubic',
				            complete: window.reflow
						});
					}
				});
				
				// Initialize scrollspy.
				$body.scrollspy({
					target : '.navbar-scrollspy',
					offset : (adminbarHeight + $('.navbar').outerHeight())
				});
				
				// Trigger scrollspy when resize.
				$(window).resize(function() {
					$body.scrollspy('refresh');
				});
	
			}
			
		}

		// Slider scroll bottom button
		$('.noo-slider-revolution-container .noo-slider-scroll-bottom').click(function(e) {
			e.preventDefault();
			var sliderHeight = $('.noo-slider-revolution-container').outerHeight();
			$('html, body').animate({
				scrollTop: sliderHeight
			}, 900, 'easeInOutExpo');
		});
		
		//Portfolio hover overlay
		$('body').on('mouseenter', '.masonry-style-elevated .masonry-portfolio.no-gap .masonry-item', function(){
			$(this).closest('.masonry-container').find('.masonry-overlay').show();
			$(this).addClass('masonry-item-hover');
		});
	
		$('body').on('mouseleave ', '.masonry-style-elevated .masonry-portfolio.no-gap .masonry-item', function(){
			$(this).closest('.masonry-container').find('.masonry-overlay').hide();
			$(this).removeClass('masonry-item-hover');
		});
		
		//Init masonry isotope
		$('.masonry').each(function(){
			var self = $(this);
			var $container = $(this).find('.masonry-container');
			var $filter = $(this).find('.masonry-filters a');
			$container.isotope({
				itemSelector : '.masonry-item',
				transitionDuration : '0.8s',
				masonry : {
					'gutter' : 0
				}
			});
			
			imagesLoaded(self,function(){
				$container.isotope('layout');
			});
			
			$filter.click(function(e){
				e.stopPropagation();
				e.preventDefault();
				
				var $this = jQuery(this);
				// don't proceed if already selected
				if ($this.hasClass('selected')) {
					return false;
				}
				self.find('.masonry-result h3').text($this.text());
				var filters = $this.closest('ul');
				filters.find('.selected').removeClass('selected');
				$this.addClass('selected');
	
				var options = {
					layoutMode : 'masonry',
					transitionDuration : '0.8s',
					'masonry' : {
						'gutter' : 0
					}
				}, 
				key = filters.attr('data-option-key'), 
				value = $this.attr('data-option-value');
	
				value = value === 'false' ? false : value;
				options[key] = value;
	
				$container.isotope(options);
				
			});
		});
		
		//Go to top
		$(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				$('.go-to-top').addClass('on');
			}
			else {
				$('.go-to-top').removeClass('on');
			}
		});
		$('body').on( 'click', '.go-to-top', function () {
			$("html, body").animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
		//Search
		$('body').on( 'click', '.search-button', function() {
			if ($('.searchbar').hasClass('hide'))
			{
				$('.searchbar').removeClass('hide').addClass('show');
				$('.searchbar #s').focus();
			}
			return false;
		});
		$('body').on('mousedown', $.proxy( function(e){
			var element = $(e.target);
			if(!element.is('.searchbar') && element.parents('.searchbar').length === 0)
			{
				$('.searchbar').removeClass('show').addClass('hide');
			}
		}, this) );

		//Shop mini cart
		$(document).on("mouseenter", ".noo-menu-item-cart", function() {
			clearTimeout($(this).data('timeout'));
			$('.searchbar').removeClass('show').addClass('hide');
			$('.noo-minicart').fadeIn(50);
		});
		$(document).on("mouseleave", ".noo-menu-item-cart", function() {
			var t = setTimeout(function() {
				$('.noo-minicart').fadeOut(50);
			}, 400);
			$(this).data('timeout', t);
		});	
		
		//Shop QuickView
		$(document).on('click','.shop-loop-quickview',function(e){
			var $this = $(this);
			$this.addClass('loading');
			$.post(nooL10n.ajax_url,{
				action: 'woocommerce_quickview',
				product_id: $(this).data('product_id')
			},function(responsive){
				$this.removeClass('loading');
				$modal = $(responsive);
				$('body').append($modal);
				$modal.modal('show');
				$modal.on('hidden.bs.modal',function(){
					$modal.remove();
				});
			});
			e.preventDefault();
			e.stopPropagation();
		});
	};
	$( document ).ready( function () {
		nooInit();
	});
	
	$(document).bind('noo-layout-changed',function(){
		nooInit();	
	});
})(jQuery);