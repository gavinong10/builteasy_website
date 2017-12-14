(function ($) {

	$.mad_woocommerce_mod = $.mad_woocommerce_mod || {};

	/* Quick Preview
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.quickPreview = function () {
		this.$userItems.on('click', 'a', function (e) {
			e.preventDefault();
			var $this = $(this),
				src = $this.data('image');
			var preview = $this.closest('.images').children('.qv-preview').children('img');
			preview.addClass('preview-hide');
				setTimeout(function(){
					preview.attr('src', src).removeClass('preview-hide');
				}, 400);
		});
	}

	/* Tabbed
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.tabbed = function () {

		if ($('[class*="tabs-holder"]').length) {

			var $tabsholder = $('[class*="tabs-holder"]');

			$tabsholder.each(function (i, val) {
				var $tabsNav = $('[class*="tabs-nav"]', val),
					eventtype = Modernizr.touch ? 'touchstart' : 'click';
				$tabsNav.on(eventtype, 'a', function (e) {
					e.preventDefault();

					var $this = $(this).parent('li'),
						$index = $this.index();

					if ($this.hasClass('active')) { e.preventDefault(); }

					$this.siblings()
						.removeClass('active')
						.end()
						.addClass('active')
						.parent()
						.next()
						.children('[class*="tab-content"]')
						.stop(true, true)
						.hide()
						.eq($index)
						.stop(true, true).fadeIn(800);
				});
			});
		}
	}

	/*	Toggle Categories
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.toggleCategories = function () {
		var $productCats = $('.product-categories');

		if ($productCats.length) {
			$productCats.find('li').each(function (idx, element) {
				if ($(element).children('ul.children').length) {
					$(element).children('a').append('<span class="toggle-switch"></span>');
				}
			});

			$productCats.on('click', '.toggle-switch', function (e) {
				e.preventDefault();
				var $self = $(e.target),
					$this = $self.parent('a').parent('li');
				if ($this.children('ul.children').length) {
					$this.toggleClass('active').children('ul.children').slideToggle();
				}
			});
		}
	}

	/*	Custom ScrollBar
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.setCustomScrollBar = function () {
		var scroll = $('.custom-scrollbar');
		if (scroll.length) {
			var isVisible = setInterval(function () {
				if (scroll.is(':visible')) {
					scroll.customScrollbar({
						preventDefaultScroll: true,
						updateOnWindowResize: true
					});
					clearInterval(isVisible);
				}
			}, 25 );
		}
	}

	/*	Raty
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.raty = function () {

		if ($('.rating').length) {
			// Read Only
			$('.rating.readonly-rating').raty({
				readOnly: true,
				path: global.paththeme + '/images/img',
				score: function() {
					return $(this).attr('data-score');
				}
			});

			// Rate
			$('.rating.rate').raty({
				path: global.paththeme + '/images/img',
				score: function() {
					return $(this).attr('data-score');
				}
			});
		}

	}

	/*	Elevate Zoom
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.zoom = function () {

		var mad_get_gallery_list = function () {

			var gallerylist = [],
				gallery = 'qv-carousel';

			$('#' + gallery + ' a').each(function () {

				var img_src = '';

				if ($(this).data("zoom-image")) {
					img_src = $(this).data("zoom-image");
				} else if($(this).data("image")){
					img_src = $(this).data("image");
				}

				if (img_src) {
					gallerylist.push({
						href: '' + img_src + '',
						title: $(this).find('img').attr("title")
					});
				}

			});

			return gallerylist;

		}

		if ($('[data-zoom-image]').length) {

			var zoomWindowPosition = 1;
			if ($('body').hasClass('rtl')) {
				zoomWindowPosition = 10;
			}

			var $button = $('.jackboxInit'), data = '';

			if ($('#zoom_image').length) {

				var zs = $("#zoom_image").elevateZoom({
					gallery: 'qv-carousel',
					zoomWindowFadeIn: 500,
					zoomWindowFadeOut: 500,
					zoomWindowPosition: zoomWindowPosition
				}),
					ez = zs.data('elevateZoom'),
					data = ez.getGalleryList();

			}

			if (data.length) {

				if (data.length == 1) {

					var href_value = data[0].href;

					$button.attr({
						'href' : href_value,
						'data-group' : 'images'
					});

					$.jackBox.available(function() {
						$button.jackBox('newItem');
					});

					$button.jackBox("init", {
						dynamic: true,
						baseName: global.template_directory + 'js/jackbox'
					});

				} else {

					$.each(data, function (id, val) {

						if (id == 0) {
							$button.attr({
								'href' : val.href,
								'data-group' : 'images'
							});
							return;
						}

						var $link = $('<a>', {
							'data-group': 'images',
							'class': 'jackboxInit',
							'href' : val.href
						}), $inlink = $link.appendTo($button);

						$.jackBox.available(function () {
							$button.jackBox('newItem');
							$inlink.jackBox('newItem');
						});

						$button.jackBox("init", {
							dynamic: true,
							baseName: global.template_directory + 'js/jackbox'
						});

					});

				}

			} else {

				if ($('#qv-carousel').length) {

					var data = mad_get_gallery_list();

					if (data) {

						$.each(data, function (id, val) {

							if (id == 0) {
								$button.attr({
									'href' : val.href,
									'data-group' : 'images'
								});
								return;
							}

							var $link = $('<a>', {
								'data-group': 'images',
								'class': 'jackboxInit',
								'href' : val.href
							}), $inlink = $link.appendTo($button);

							$.jackBox.available(function () {
								$button.jackBox('newItem');
								$inlink.jackBox('newItem');
							});

							$button.jackBox("init", {
								dynamic: true,
								baseName: global.template_directory + 'js/jackbox'
							});

						});

					}

				} else {

					$button.jackBox("init", {
						baseName: global.template_directory + 'js/jackbox',
						className: ".jackboxInit"
					});

				}

			}

		}

	}

	/*	Product Isotope
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.productIsotope = function () {

		function randomSort(selector, items) {
			var it = items,
				random = [],
				len = it.length;
			it.removeClass('random');

			if (selector === ".random") {
				for (var i = 0; i < len; i++){
					random.push(+(Math.random() * len).toFixed());
				}
				$.each(random,function (i,v) {
					items.eq(Math.floor(Math.random() * v - 1)).addClass('random');
				});
			}
		}

		var $productsIsotope = $('.products-isotope');

		if ($productsIsotope.length) {
			$productsIsotope.each(function () {

				var $this = $(this),
					$products = $('ul.products', $this),
					options = {
						itemSelector : '.product',
						layoutMode : 'fitRows',
						duration: 750,
						easing: 'linear',
						queue: false
					}

				if ($('body').hasClass('rtl')) {
					options['isOriginLeft'] = false;
				}

				$products.imagesLoaded(function () {
					$products.isotope(options);
				});

				$('.product-filter', $productsIsotope).on('click', 'button', function () {
					var $this = $(this),
						selector = $this.attr('data-filter');
					if (selector == 'random') {
						randomSort.call($this, '.' + selector, $products.find('.product'));
					}
					$this.closest('li').addClass('active').siblings().removeClass('active');

					if (selector == '*') {
						$products.isotope({ filter: selector });
					} else {
						$products.isotope({ filter: '.' + selector });
					}
				});

			});
		}
	}

	/*	Specials Carousel
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.specialsCarousel = function () {

		var specialsCarousel = $('.specials-carousel');

		if (specialsCarousel.length) {

			specialsCarousel.owlCarousel({
				singleItem: true,
				theme: 'owl-widget-theme',

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
		}

	}

	/*	Product Carousel
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.productCarousel = function () {

		var $productsCarousel = $('.product-carousel'),
			$carousel = $('ul.products', $productsCarousel),
			$dataColumns = $productsCarousel.data('columns') || 3,
			$dataSidebarLandScape = $productsCarousel.data('sidebar') == 'no_sidebar' ? 4 : 3,
			$dataSidebarPortrait = $productsCarousel.data('sidebar') == 'no_sidebar' ? 3 : 2,
			customItems = [
				[1199, $dataColumns],
				[992, $dataSidebarLandScape],
				[768,3],
				[558,2],
				[480,$dataSidebarPortrait],
				[470, 2],
				[300,1]
			];

		if ($productsCarousel.length) {
			$carousel.owlCarousel({
				theme: 'owl-tm-theme',
				itemsCustom : customItems,

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
				paginationNumbers: false,

				afterInit: function (el) {
					var base = this,
						items = base.$userItems,
						filter = $('.product-filter', $productsCarousel);

					filter.on('click', 'li', function () {
						var	self = $(this),
							activeElem = self.children().data('filter');

						el.addClass('changed').find('.owl-wrapper').animate({
							opacity : 0
						}, function () {

							el.children().remove();
							el.data('owlCarousel').destroy();

							if (activeElem == "*") {
								$.each(items, function (i, v) {
									el.append($(v));
								});
							} else {
								$.each(items, function (i, v) {
									var element = $(v);

									if (element.hasClass(activeElem)) {
										element
											.find('.animate_vertical_finished')
											.removeClass('animate_vertical_finished');
										el.append(element);
									}
								});
							}

							el.owlCarousel({
								items: $productsCarousel.data('columns') || 4,
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
								paginationNumbers: false,
								afterInit: function (el) {
									el.addClass('no_children_animate');
								}
							});

							$(window).trigger('resize');

						});

						self.closest('li').addClass('active').siblings().removeClass('active');

					});

				}
			});

		}

	}

	/*	Related Carousel
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.relatedCarousel = function () {

		var $relatedProducts = $('.related.products'),
			$carousel = $('ul.products', $relatedProducts),
			$dataColumns = $relatedProducts.parent('.products-container').data('columns') || 3,
			$dataSidebarLandScape = $relatedProducts.data('sidebar') == 'no_sidebar' ? 4 : 3,
//			$dataSidebarPortrait = $relatedProducts.data('sidebar') == 'no_sidebar' ? 3 : 2,
			customItems = [
				[1199, $dataColumns],
				[992, $dataSidebarLandScape],
				[768, 2],
				[480, 2],
				[300, 1]
			];

		if ($relatedProducts.length) {

			$carousel.owlCarousel({
				itemsCustom: customItems,
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
		}

	}

	/*	qvCarousel
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.qvCarousel = function () {

		var qvCarousel = $('.qv-carousel');

		if (qvCarousel.length) {
			qvCarousel.each(function () {

				$(this).owlCarousel({
					theme : "owl-qv-carousel-theme",
					itemsCustom : [[1199, 3],[992, 3],[768,3],[480,3],[300,2]],

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
					paginationNumbers: false,

					afterInit: function () {
						$.mad_woocommerce_mod.quickPreview.call(this);
					}
				});

			});

		}

	}

	/*	Change View Product
	/* --------------------------------------------- */

	$.changeView = function (options) {
		this.options = $.extend({}, $.changeView.DEFAULTS, options);
		this.init();
	}

	$.changeView.DEFAULTS = { }

	$.changeView.prototype = {
		init: function () {
			var base = this;
				base.body = $('body');
				base.support = {
					touch : Modernizr.touch
				};
				base.view = $('.list-or-grid');
				base.view.eventtype = base.support.touch ? 'touchstart' : 'click';
				base.event();
		},
		event: function () {
			this.view.on(this.view.eventtype, 'a', $.proxy(function (e) {
				this.load(e);
			}, this));
		},
		load: function (e) {
			e.preventDefault();
			var el = $(e.target),
				view = el.data('view'),
				container = el.closest('.products-container');
				el.siblings().removeClass('active').end().addClass('active');
				container.removeClass('view-grid-center view-list').addClass(view);
				$.cookie('mad_shop_view', view);
		}
	}

	/*	Cart Dropdown
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.cartDropdown = function () {
		({
			init: function () {
				var base = this;

				base.support = {
					touch : Modernizr.touch,
					transitions : Modernizr.csstransitions
				};
				base.eventtype = base.support.touch ? 'touchstart' : 'click';

				var transEndEventNames = {
					'WebkitTransition': 'webkitTransitionEnd',
					'MozTransition': 'transitionend',
					'OTransition': 'oTransitionEnd',
					'msTransition': 'MSTransitionEnd',
					'transition': 'transitionend'
				};
				base.transEndEventName = transEndEventNames[Modernizr.prefixed( 'transition' )];
				base.clicked_product = {};
				base.add_buttons();
				base.listeners();
			},
			add_buttons: function () {
				$( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" id="add1" class="plus" />' ).prepend( '<input type="button" value="-" id="minus1" class="minus" />' );
			},
			track_ajax_add_to_cart: function () {
				var base = this, product = {};

				$('body').on(base.eventtype, '.add_to_cart_button', function () {
					var $this = $(this), product = {},
						productContainer = $this.parents('.product').eq(0);
						product.name  = productContainer.find('h3 a').text();
						product.image = productContainer.find('.thumbnail-container img');
						product.price = productContainer.find('.price .amount').last().text();

					$this.block({
						message: null,
						overlayCSS: {
							background: '#fff url(' + global.ajax_loader_url + ') no-repeat center',
							backgroundSize: '16px 16px',
							opacity: 0.6
						}}
					);

					if (product.image.length) {
						product.image = "<img class='added-product-image' src='" + product.image.get(0).src + "' />";
					}
					base.clicked_product = product;
				});
			},
			update_cart_dropdown: function (event) {

				var base = this,
					cart 	  = $('.cart-dropdown'),
					msg	 	  = cart.data('text'),
					product   = base.clicked_product;

				if (typeof event != 'undefined') {

					var template = $("<li class='cart-notification'><div class='added-product-text'><strong>"+ product.name + "</strong> " + msg + "</div> "+ product.image +"</li>");

					template.on('mouseenter template_hide', function () {
						var $this = $(this);

						setTimeout( function() {
							$this.removeClass('visible-cart');
						}, 100);
						var onEndTransitionFn = function () {
							$this.remove();
						};
						if (base.support.transitions) {
							$this.on( base.transEndEventName, onEndTransitionFn );
						} else {
							onEndTransitionFn();
						}
					}).prependTo(cart);

					setTimeout(function () {
						template.addClass('visible-cart');
					}, 50);

					setTimeout(function () {
						template.trigger('template_hide');
					}, 2500);
				}

			},
			listeners: function () {
				var base = this;
					base.track_ajax_add_to_cart();

				$('body')
					.on('added_to_cart', $.proxy(function (e, fragments) {
						$('.add_to_cart_button').unblock();

						$('.shopping-button .count').html(fragments.count);
						$('.shopping-button .amount').html(fragments.subtotal);

						base.update_cart_dropdown(e);
				}, base))
					.on(base.eventtype, '.cart_list .remove-item', function () {
						$(this).closest('li').animate({ 'opacity' : 0 }, function () {
							$(this).slideUp(400);
						});
					});

				$( document ).on( 'click', '.plus, .minus', function() {

					// Get values
					var $qty		= $( this ).closest( '.quantity' ).find( '.qty' ),
						currentVal	= parseFloat( $qty.val() ),
						max			= parseFloat( $qty.attr( 'max' ) ),
						min			= parseFloat( $qty.attr( 'min' ) ),
						step		= $qty.attr( 'step' );

					// Format values
					if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
					if ( max === '' || max === 'NaN' ) max = '';
					if ( min === '' || min === 'NaN' ) min = 0;
					if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

					// Change the value
					if ( $( this ).is( '.plus' ) ) {
						if ( max && ( max == currentVal || currentVal > max ) ) {
							$qty.val( max );
						} else {
							$qty.val( currentVal + parseFloat( step ) );
						}
					} else {
						if ( min && ( min == currentVal || currentVal < min ) ) {
							$qty.val( min );
						} else if ( currentVal > 0 ) {
							$qty.val( currentVal - parseFloat( step ) );
						}
					}

					// Trigger change event
					$qty.trigger( 'change' );
				});

			}
		}.init());
	}

	$.fn.css3Dropdown = function () {
		return $(this).on('click', function (e) {
			var dropdown = $(this).next();
			$(this).toggleClass('active');
			e.preventDefault();

			if (dropdown.children('ul').children('li').length) {
				if (dropdown.hasClass('opened')) {
					dropdown.removeClass('opened').addClass('closed');
					setTimeout(function(){
						dropdown.removeClass('closed')
					},500);
				} else {
					dropdown.addClass('opened');
				}
			}
		});
	}

	/*	Cart Variation
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.check_cart_variation = function () {

		// wc_add_to_cart_variation_params is required to continue, ensure the object exists
		if ( typeof wc_add_to_cart_variation_params === 'undefined' )
			return false;

		$( '.variations_form' ).wc_variation_form();
		$( '.variations_form .variations select' ).change();

	}

	/*	Quick View
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.quickView = function () {
		({
			init: function () {

				if ($('.quick-view').length) {
					new $.mad_popup_prepare('.quick-view', {
						actionpopup: woocommerce_mod.action_quick_view,
						noncepopup: woocommerce_mod.nonce_quick_view_popup,
						on_load: function () {

							this.container.imagesLoaded(function () {

								$('div.quantity:not(.buttons_added)', this.modal).addClass('buttons_added')
									.append( '<input type="button" value="+" id="add1" class="plus" />' )
									.prepend( '<input type="button" value="-" id="minus1" class="minus" />' );

								if ($('.woo-custom-select').length) {
									$('.woo-custom-select').heapbox();
								}

								$.mad_woocommerce_mod.check_cart_variation();
								$.mad_woocommerce_mod.qvCarousel();
								$.mad_woocommerce_mod.raty();
								$.mad_woocommerce_mod.setCustomScrollBar();

							});

						}
					});
				}
			}
		}).init();
	}

	/*	Form Login
	/* --------------------------------------------- */

	$.mad_woocommerce_mod.formLogin = function() {
		({
			init: function () {
				if ($('.to-login').length) {
					new $.mad_popup_prepare('.to-login', {
						actionpopup: woocommerce_mod.action_login,
						noncepopup: woocommerce_mod.nonce_login_popup,
						on_load: function () {
							var base = this,
								href = $(this.el).data().href;

							$('form.login').ajaxForm({
								url: href,
								success: function() {
									base.closeModal();
									window.location.href = href;
								}
							});

						}
					});
				}
			}
		}).init();
	}


	/*	DOM READY
	/* --------------------------------------------- */

	$(function () {

		$.mad_woocommerce_mod.quickView();
		$.mad_woocommerce_mod.formLogin();
		$.mad_woocommerce_mod.qvCarousel();
		$.mad_woocommerce_mod.zoom();
		$.mad_woocommerce_mod.relatedCarousel();
		$.mad_woocommerce_mod.specialsCarousel();
		$.mad_woocommerce_mod.productCarousel();
		$.mad_woocommerce_mod.productIsotope();
		$.mad_woocommerce_mod.toggleCategories();
		$.mad_woocommerce_mod.cartDropdown();
		$.mad_woocommerce_mod.raty();
		$.mad_woocommerce_mod.tabbed();

		if ($('.list-or-grid').length) { new $.changeView(); }

		if ($('.woo-custom-select').length) {
			$('.woo-custom-select').heapbox();
		}

		if ($('.to-rating').length) {
			$('.to-rating').on('click', function (e) {
				e.preventDefault();

				var $this = $(this),
					hash = $this.attr('href'),
					$tabs = $('.woocommerce-tabs');

				if ( hash == '#reviews' ) {
					$('ul.tabs li.reviews_tab a', $tabs).click();

					$('html, body').stop(true, true).animate({
						scrollTop: $tabs.offset().top + "px"
					}, {
						duration: 1000,
						easing: 'easeOutQuint'
					});
				}
			});
		}

	});

})(jQuery);
