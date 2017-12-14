(function ($) {

	"use strict";

	/* --------------------------------------------- */
	/* Price Slider Modification
	/* --------------------------------------------- */

	$.mad_price_slider_mod = function () {
		this.init();
	}

	$.mad_price_slider_mod.prototype = {
		init: function () {

			// wc_price_slider_params is required to continue, ensure the object exists
			if ( typeof woocommerce_price_slider_params === 'undefined' ) {
				return false;
			}

			// Get markup ready for slider
			$( 'input#min_price, input#max_price' ).hide();
			$( '.price_slider, .price_label' ).show();

			// Price slider uses jquery ui
			var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
				max_price = $( '.price_slider_amount #max_price' ).data( 'max' );

			var current_min_price = parseInt( min_price, 10),
				current_max_price = parseInt( max_price, 10 );

			if ( woocommerce_price_slider_params.min_price ) current_min_price = parseInt( woocommerce_price_slider_params.min_price, 10 );
			if ( woocommerce_price_slider_params.max_price ) current_max_price = parseInt( woocommerce_price_slider_params.max_price, 10 );

			$( 'body' ).bind( 'price_slider_create price_slider_slide', function( event, min, max ) {

				$( '.price_slider_amount span.from' ).html( woocommerce_price_slider_params.currency_symbol + min );
				$( '.price_slider_amount span.to' ).html( woocommerce_price_slider_params.currency_symbol + max );

				if ( woocommerce_price_slider_params.currency_pos === 'left' ) {

					$( '.price_slider_amount span.from' ).html( woocommerce_price_slider_params.currency_symbol + min );
					$( '.price_slider_amount span.to' ).html( woocommerce_price_slider_params.currency_symbol + max );

				} else if ( woocommerce_price_slider_params.currency_pos === 'left_space' ) {

					$( '.price_slider_amount span.from' ).html( woocommerce_price_slider_params.currency_symbol + " " + min );
					$( '.price_slider_amount span.to' ).html( woocommerce_price_slider_params.currency_symbol + " " + max );

				} else if ( woocommerce_price_slider_params.currency_pos === 'right' ) {

					$( '.price_slider_amount span.from' ).html( min + woocommerce_price_slider_params.currency_symbol );
					$( '.price_slider_amount span.to' ).html( max + woocommerce_price_slider_params.currency_symbol );

				} else if ( woocommerce_price_slider_params.currency_pos === 'right_space' ) {

					$( '.price_slider_amount span.from' ).html( min + " " + woocommerce_price_slider_params.currency_symbol );
					$( '.price_slider_amount span.to' ).html( max + " " + woocommerce_price_slider_params.currency_symbol );

				}

				$( 'body' ).trigger( 'price_slider_updated', min, max );
			});

			$( '.price_slider' ).slider({
				range: true,
				animate: true,
				min: min_price,
				max: max_price,
				values: [ current_min_price, current_max_price ],
				create : function( event, ui ) {

					$( '.price_slider_amount #min_price' ).val( current_min_price );
					$( '.price_slider_amount #max_price' ).val( current_max_price );

					$( 'body' ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
				},
				slide: function( event, ui ) {
					$( 'input#min_price' ).val( ui.values[0] );
					$( 'input#max_price' ).val( ui.values[1] );

					$( 'body' ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
				},
				change: function( event, ui ) {

					$( 'body' ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );

					var wrapContainer = $('.wrap-product-container'),
						href = $(this).parents('form').attr('action');

					//loading
					wrapContainer.html('').addClass('woof-loading');
					$(woof_mod.pagination).hide();
					$(woof_mod.result_count).hide();

					$.ajax({
						url: href,
						success: function ( response ) {

							wrapContainer.removeClass('woof-loading');
							$(this).parents('form').submit();

							// container
							if ( $(response).find(woof_mod.container).length > 0 ) {
								wrapContainer.html( $(response).find(woof_mod.container) );
							} else {
								wrapContainer.html( $(response).find('.woocommerce-info') );
							}

							// pagination
							if ( $(response).find(woof_mod.pagination).length > 0 ) {

//								if( $(woof_mod.pagination).length == 0 ) {
//									$.jseldom( woof_mod.pagination ).insertAfter( $(woof_mod.container) );
//								}

								$(woof_mod.pagination)
									.html( $(response).find(woof_mod.pagination).html())
									.show();
							}

							// result count
							if ( $(response).find(woof_mod.result_count).length > 0 ) {
								$(woof_mod.result_count).html( $(response).find(woof_mod.result_count).html()).show();
							}


							// load new widgets
							$('.widget-woof-filter').each(function () {
								var $this = $(this), id = $this.attr('id');
								$this.html( $(response).find('#' + id ).html() );
								$this.text() == '' ? $this.hide() : $this.show();
							});

							// update browser history (IE doesn't support it)
							if ( !navigator.userAgent.match(/msie/i) ) {
								window.history.pushState({"pageTitle":response.pageTitle},"", href);
							}

							// trigger ready event
							$(document).trigger("ready");
						}

					});

					$(this).parents('form').submit();

				}
			});

		}

	}

	$(function () {
		new $.mad_price_slider_mod();
	});

})(jQuery);