
window.vcParallaxSkroll = false;
if ( typeof window[ 'vc_rowBehaviour' ] !== 'function' ) {
	window.vc_rowBehaviour = function () {
		var $ = window.jQuery;
		var local_function = function () {
			var $elements = $( '[data-vc-full-width="true"]' );
			$.each( $elements, function ( key, item ) {
				var $el = $( this );
				var $el_full = $el.next( '.vc_row-full-width' );
				var el_margin_left = parseInt( $el.css( 'margin-left' ), 10 );
				var el_margin_right = parseInt( $el.css( 'margin-right' ), 10 );
				var offset = 0 - $el_full.offset().left - el_margin_left;
				var width = $( window ).width(), offset_rtl = 0;

				if ($('body').is('.rtl')) {
					offset_rtl = Math.abs(offset);

					$el.css( {
						'position': 'relative',
						'left': offset_rtl,
						'box-sizing': 'border-box',
						'width': $( window ).width()
					} );
				} else {
					$el.css( {
						'position': 'relative',
						'left': offset,
						'box-sizing': 'border-box',
						'width': $( window ).width()
					} );
				}

				if ( ! $el.data( 'vcStretchContent' ) ) {
					var padding = (- 1 * offset);
					if ( padding < 0 ) {
						padding = 0;
					}
					var paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right;
					if ( paddingRight < 0 ) {
						paddingRight = 0;
					}
					$el.css( { 'padding-left': padding + 'px', 'padding-right': paddingRight + 'px' } );
				}
				$el.attr( "data-vc-full-width-init", "true" );
			} );
		};
		/**
		 * @todo refactor as plugin.
		 * @returns {*}
		 */
		var parallaxRow = function () {
			var vcSkrollrOptions,
				callSkrollInit = false;
			if ( vcParallaxSkroll ) {
				vcParallaxSkroll.destroy();
			}
			$( '.vc_parallax-inner' ).remove();
			$( '[data-5p-top-bottom]' ).removeAttr( 'data-5p-top-bottom data-30p-top-bottom' );
			$( '[data-vc-parallax]' ).each( function () {
				var skrollrSpeed,
					skrollrSize,
					skrollrStart,
					skrollrEnd,
					$parallaxElement,
					parallaxImage;
				callSkrollInit = true; // Enable skrollinit;
				if ( $( this ).data( 'vcParallaxOFade' ) == 'on' ) {
					$( this ).children().attr( 'data-5p-top-bottom', 'opacity:0;' ).attr( 'data-30p-top-bottom',
						'opacity:1;' );
				}

				skrollrSize = $( this ).data( 'vcParallax' ) * 100;
				$parallaxElement = $( '<div />' ).addClass( 'vc_parallax-inner' ).appendTo( $( this ) );
				$parallaxElement.height( skrollrSize + '%' );

				parallaxImage = $( this ).data( 'vcParallaxImage' );

				if ( parallaxImage !== undefined ) {
					$parallaxElement.css( 'background-image', 'url(' + parallaxImage + ')' );
				}

				skrollrSpeed = skrollrSize - 100;
				skrollrStart = - skrollrSpeed;
				skrollrEnd = 0;

				$parallaxElement.attr( 'data-bottom-top', 'top: ' + skrollrStart + '%;' ).attr( 'data-top-bottom',
					'top: ' + skrollrEnd + '%;' );
			} );

			if ( callSkrollInit && window.skrollr ) {
				vcSkrollrOptions = {
					forceHeight: false,
					smoothScrolling: false,
					mobileCheck: function () {
						return false;
					}
				};
				vcParallaxSkroll = skrollr.init( vcSkrollrOptions );
				return vcParallaxSkroll;
			}
			return false;
		};
		$( window ).unbind( 'resize.vcRowBehaviour' ).bind( 'resize.vcRowBehaviour', local_function );
		local_function();
		parallaxRow();
	}
}

(function ($) {

	function mad_grid_portfolio(el, options) {
		this.holder = $(el);
		this.options = $.extend({}, mad_grid_portfolio.DEFAULTS, options);
		this.init();
	}

	mad_grid_portfolio.DEFAULTS = {
		isotope: ''
	}

	mad_grid_portfolio.prototype = {
		init: function () {
			var base = this;

			base.loading = false;
			base.support = { animations: Modernizr.cssanimations };
			base.touch = Modernizr.touch;

			base.el = base.options.isotope;
			base.loadButton = $('.load-button', base.holder);
			base.loadButton.eventtype = base.touch ? 'touchstart' : 'click';

			if (base.loadButton.length) {
				base.ajaxLoad.call(base, base.loadButton);
			}

			var $mode = base.el.attr('data-layout-type'),
				$masonryBase = {};

			if ($mode == 'masonry') {
				$mode = 'masonry';
				$masonryBase = { 'columnWidth': 10, 'qutter': 0 }
			}

			var options = {
				itemSelector : '.portfolio-item',
				duration: 750,
				easing: 'linear',
				layoutMode : $mode,
				masonry: $masonryBase
			}

			if ($('body').hasClass('rtl')) {
				options['isOriginLeft'] = false;
			}

			base.el.imagesLoaded(function () {
				base.folio = base.el.addClass('loaded').isotope(options);
			});

		},
		loadMore: function (e) {
			e.preventDefault();

			var base = this,
				el	= $(e.target),
				data = el.data(),
				hide_button = function () {
					el.animate({
						'opacity' : 0
					}, 200, function () {
						$(this).animate({ 'height': 0 }, 400)
					});
				}

			if (base.loading) return false;
				base.loading = true;

			if (!data.items_per_page) { data.items_per_page = 3; }
			if (!data.offset)	 	  { data.offset = data.items_per_page; }

				data.offset += data.items_per_page;
				data.items = data.items_per_page;

			$.ajax({
				url: global.ajaxurl,
				type: "POST",
				data: data,
				beforeSend: function() {
					setTimeout(function () { el.addClass('blockloader'); }, 100);
				},
				success: function (response, textStatus) {

					if (response.indexOf("{mad-isotope-loaded}") !== -1) {
						var response  = response.split('{mad-isotope-loaded}'),
							$newItems = $(response.pop()).filter('.portfolio-item');

						if ($newItems.length > 0) {
							base.holder.imagesLoaded(function () {
								$newItems.appendTo(base.folio).hide();
								setTimeout(function () {
									base.folio.isotope('appended', $newItems).isotope('layout');
								}, 150);

								$.jackBox.available(function() {
									$(".jackbox[data-group]").each(function() {
										$(this).jackBox("newItem");
									});
									$(".jackbox[data-group]").jackBox("init");
								});

								base.loading = false;
							});
						} else {
							hide_button();
						}
					} else { hide_button(); }

					setTimeout(function () { el.removeClass('blockloader'); }, 100);
				},
				complete: function () {},
				error: function (xhr, ajaxOptions, thrownError) { hide_button(); }
			});

		},
		ajaxLoad: function (el) {
			el.on(el.eventtype, $.proxy(function (e) {
				this.loadMore(e);
			}, this));
		}
	}

	$.fn.mad_grid_portfolio = function (option) {
		return this.each(function () {
			var $this = $(this), data = $this.data('mad_grid_portfolio'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('mad_grid_portfolio', new mad_grid_portfolio(this, options));
			}
		});
	}

})(jQuery);


(function ($) {

	/*	Parallax											*/
	/* ---------------------------------------------------- */

	$.fn.mad_parallax_bg_image = function (xpos, speed) {
		var top, pos;
		return this.each(function (idx, value) {
			var $this = $(value);

			if (arguments.length < 1 || xpos === null)  {
				xpos = "55%";
			}
			if (arguments.length < 2 || speed === null) {
				speed = 0.6;
			}

			var methods = {
				update: function() {
					top = $this.offset().top;
					pos = $(window).scrollTop();
					$this.css('backgroundPosition', xpos + " " + Math.round((top - pos) * speed) + "px");
				},
				init: function() {
					var base = this;
					base.update();
					$(window).on('scroll', base.update);
				}
			};
			methods.init();
		});
	};

	/*	DOM READY															*/
	/* -------------------------------------------------------------------- */

	$(function () {

		/*	Init Parallax	BG Image							*/
		/* ---------------------------------------------------- */

		if (!Modernizr.touch) {
			if ($('.bg-section-image').length) {
				$('.bg-section-image').mad_parallax_bg_image('center', 0.4);
			}
		}

		/*	Product Brands Carousel											  */
		/* ------------------------------------------------------------------ */

		(function () {

			var $mad_product_brands = $('.product-brands');

			if ($mad_product_brands.length) {

				$mad_product_brands.each(function () {

					var $this = $(this);

					var ifNoSidebar = $this.closest('.no_sidebar').length,
						ifColHalf = $this.closest('.vc_col-sm-6').length,
						items;

					if (ifNoSidebar) {
						items = [[1199,6],[992,5],[768,4],[480,3],[300,2]];
						if (ifColHalf) {
							items = [[1199,3],[992,2],[768,2],[480,2],[300,2]];
						}
					} else {
						items = [[1199,4],[992,4],[768,3],[480,2],[300,2]];
						if (ifColHalf) {
							items = [[1199,2],[992,2],[768,2],[480,2],[300,1]];
						}
					}

					$this.owlCarousel({
						itemsCustom : items,
						autoPlay: $this.data('autoplay') == true ? $this.data('autoplaytimeout') : false,
						stopOnHover : true,
						slideSpeed : 600,
						autoHeight : true,

						// Navigation
						navigation : true,

						// Pagination
						pagination : false,
						theme : "owl-tm-theme"

					});

				});
			}

		})();

		/*	Blog Carousel													  */
		/* ------------------------------------------------------------------ */

		(function () {

			var $mad_post_slider = $('.post-carousel'),
				items = $mad_post_slider.data('items') || 1;

			var mad_post_slider_set = {
				autoPlay : false,
				stopOnHover : true,
				slideSpeed : 600,
				autoHeight : true,

				// Navigation
				navigation : true,

				// Pagination
				pagination : false,
				theme : "owl-tm-theme"
			},

			customItems = [
				[1199, items],
				[992, items],
				[768, 1],
				[480, 1],
				[300, 1]
			];

			if (items == 1) {
				mad_post_slider_set.singleItem = true;
			} else {
				mad_post_slider_set.itemsCustom = customItems;
			}

			if ($mad_post_slider.length) {
				$mad_post_slider.each(function () {
					var $this = $(this);
						$this.owlCarousel(mad_post_slider_set);
				});
			}

		})();

		/*	Testimonials													  */
		/* ------------------------------------------------------------------ */

		(function () {

			var $mad_tm_slider = $('.tm-slider');

			if ($mad_tm_slider.length) {
				$mad_tm_slider.each(function () {
					var $this = $(this);

					$this.owlCarousel({
						singleItem : true,
						autoPlay: $this.data('autoplay') == true ? $this.data('autoplaytimeout') : false,
						stopOnHover : true,
						slideSpeed : 1000,
						autoHeight : true,

						// Navigation
						navigation : true,

						// Pagination
						pagination : false,
						theme : "owl-tm-theme"
					});
				});
			}

		})();

		/*	 Portfolio Carousel												  */
		/* ------------------------------------------------------------------ */

		(function () {

			var $mad_portfolio_carousel = $('.portfolio-carousel');

			if ($mad_portfolio_carousel.length) {
				$mad_portfolio_carousel.each(function () {

					var $this = $(this),
						dataColumns = $this.data('columns');

					var ifNoSidebar = $this.closest('.no_sidebar').length,
						ifColHalf = $this.closest('.vc_col-sm-6').length;

					if (ifNoSidebar) {
						items = [[1199, dataColumns],[992,3],[768,2],[480,1],[300,1]];
						if (ifColHalf) {
							items = [[1199, 2],[992,2],[768,2],[480,1],[300,1]];
						}
					} else {
						items = [[1199, dataColumns],[992,2],[768,2],[480,1],[300,1]];
						if (ifColHalf) {
							items = [[1199,1],[992,1],[768,1],[480,1],[300,1]];
						}
					}

					$this.owlCarousel({
						itemsCustom: items,
						theme: 'owl-tm-theme',

						//Autoplay
						autoPlay : false,
						slideSpeed : 1000,
						autoHeight : true,
						stopOnHover : true,

						// Navigation
						navigation : true,
						rewindNav : true,
						scrollPerPage : false,

						//Pagination
						pagination : false,
						paginationNumbers: false
					});
				});
			}

		})();

		/*	Images Carousel												  	  */
		/* ------------------------------------------------------------------ */

		(function () {

			var $mad_images_carousel = $('.images-carousel');

			if ($mad_images_carousel.length) {
				$mad_images_carousel.each(function () {
					var $this = $(this);

					var ifNoSidebar = $this.closest('.no_sidebar').length,
						ifColHalf = $this.closest('.vc_col-sm-6').length,
						items;

					if (ifNoSidebar) {
						items = [[1199,3],[992,3],[768,2],[480,1],[300,1]];
						if (ifColHalf) {
							items = [[1199,2],[992,2],[768,2],[480,1],[300,1]];
						}
					} else {
						items = [[1199,2],[992,2],[768,2],[480,1],[300,1]];
						if (ifColHalf) {
							items = [[1199,1],[992,1],[768,1],[480,1],[300,1]];
						}
					}

					$this.owlCarousel({
						itemsCustom: items,
						theme: 'owl-tm-theme',

						//Autoplay
						autoPlay: $this.data('autoplay') == true ? $this.data('autoplaytimeout') : false,
						autoHeight : true,
						stopOnHover : true,

						// Navigation
						navigation : true,
						rewindNav : true,
						scrollPerPage : false,

						//Pagination
						pagination : false,
						paginationNumbers: false
					});

				});
			}

		})();

	});

	/*	jQuery(document).ready												*/
	/* -------------------------------------------------------------------- */

	$(function() {
		vc_rowBehaviour();
	});

	/*	Load															    */
	/* -------------------------------------------------------------------- */

	$(window).load(function () {

		/*	Isotope															  */
		/* ------------------------------------------------------------------ */

		(function () {

			/*	Portfolio														  */
			/* ------------------------------------------------------------------ */

			var $mad_portfolio_holder = $('.portfolio-holder');

			if ($mad_portfolio_holder.length) {
				$mad_portfolio_holder.each(function () {

					var $this = $(this),
						$mad_portfolio = $('.portfolio-isotope', $this);

					if ($mad_portfolio.length) {
						$('.section-line .heapOptions a', $mad_portfolio.parent()).on('click', function () {
							var $this = $(this), selector = $this.attr('rel');
							$mad_portfolio.isotope({ filter: selector });
						});

						$('.section-line .portfolio-filter a', $mad_portfolio.parent()).on('click', function () {
							var $this = $(this), selector = $this.attr('data-filter');
							$this.parent('li').addClass('active').siblings().removeClass('active');
							$mad_portfolio.isotope({ filter: selector });
						});

						$this.mad_grid_portfolio({
							isotope: $mad_portfolio
						});
					}
				});

			}

		})();

	});

})(jQuery);