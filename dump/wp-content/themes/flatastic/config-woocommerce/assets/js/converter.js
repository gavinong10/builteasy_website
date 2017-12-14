(function ($) {

	$.mad_woocommerce_mod = $.mad_woocommerce_mod || {};

	$.mad_woocommerce_mod.currencySwitcher = function () {
		var money             = fx.noConflict();
		var current_currency  = wc_currency_converter_params.current_currency;
		var currency_codes    = $.parseJSON( wc_currency_converter_params.currencies );
		var currency_position = wc_currency_converter_params.currency_pos;
		var currency_decimals = wc_currency_converter_params.num_decimals;
		var remove_zeros      = wc_currency_converter_params.trim_zeros;

		money.rates           = wc_currency_converter_params.rates;
		money.base            = wc_currency_converter_params.base;
		money.settings.from   = wc_currency_converter_params.currency;

		if ( money.settings.from == 'RMB' ) {
			money.settings.from = 'CNY';
		}

		({
			init: function() {
				var base = this;

				if ( current_currency ) {
					base.currencySwitch( current_currency );
					$('ul.currency-switcher li a[data-currency-code="' + current_currency + '"]').addClass('active');
				} else {
					$('ul.currency-switcher li a.default').addClass('active');
				}
				base.listeners();
			},
			listeners: function () {
				var base = this;

				$('ul.currency-switcher').on('click', 'a', function () {
					var $this = $(this),
						toCurrency = $this.data('currency-code');
						base.currencySwitch(toCurrency);
						$.cookie('woocommerce_current_currency', toCurrency, { expires: 10, path: '/' });
						current_currency = toCurrency;
						location.reload();
						return false;
				});

				base.price_filter_update( current_currency );

				$('body').on( "price_slider_create price_slider_slide price_slider_change", function () {
					base.price_filter_update( current_currency );
				}).on('wc_fragments_refreshed wc_fragments_loaded show_variation updated_checkout updated_shipping_method added_to_cart cart_page_refreshed cart_widget_refreshed updated_addons', function() {
					if ( current_currency ) {
						base.currencySwitch( current_currency );
					}
				});

			},
			currencySwitch: function (to_currency) {

				var base = this;

				// Span.amount
				$('span.amount').each(function(){

					// Original markup
					var original_code = $(this).attr("data-original");

					if (typeof original_code == 'undefined' || original_code == false) {
						$(this).attr("data-original", $(this).html());
					}

					// Original price
					var original_price = $(this).attr("data-price");

					if (typeof original_price == 'undefined' || original_price == false) {

						// Get original price
						var original_price = $(this).html();

						// Small hack to prevent errors with $ symbols
						$( '<del></del>' + original_price ).find('del').remove();

						// Remove formatting
						original_price = original_price.replace( wc_currency_converter_params.thousand_sep, '' );
						original_price = original_price.replace( wc_currency_converter_params.decimal_sep, '.' );
						original_price = original_price.replace(/[^0-9\.]/g, '');
						original_price = parseFloat( original_price );

						// Store original price
						$(this).attr("data-price", original_price);
					}

					price = money( original_price ).to( to_currency );
					price = price.toFixed( currency_decimals );
					price = accounting.formatNumber( price, currency_decimals, wc_currency_converter_params.thousand_sep, wc_currency_converter_params.decimal_sep );

					if ( remove_zeros ) {
						price = price.replace( wc_currency_converter_params.zero_replace, '' );
					}

					if ( currency_codes[ to_currency ] ) {
						if ( currency_position == 'left' ) {
							$(this).html( currency_codes[ to_currency ] + price );
						} else if ( currency_position == 'right' ) {
							$(this).html( price + " " + currency_codes[ to_currency ] );
						} else if ( currency_position == 'left_space' ) {
							$(this).html( currency_codes[ to_currency ] + " " + price );
						} else if ( currency_position == 'right_space' ) {
							$(this).html( price + " " + currency_codes[ to_currency ] );
						}
					} else {
						$(this).html( price + " " + to_currency );
					}

					$(this).attr( 'title', wc_currency_converter_params.i18n_oprice + original_price );
				});

				// #shipping_method prices
				$('#shipping_method option').each(function() {

					// Original markup
					var original_code = $(this).attr("data-original");

					if (typeof original_code == 'undefined' || original_code == false) {
						original_code = $(this).text();
						$(this).attr("data-original", original_code);
					}

					var current_option = original_code;
					current_option = current_option.split(":");
					if (!current_option[1] || current_option[1] == '') return;
					price = current_option[1];

					if (!price) return;

					// Remove formatting
					price = price.replace( wc_currency_converter_params.thousand_sep, '' );
					price = price.replace( wc_currency_converter_params.decimal_sep, '.' );
					price = price.replace(/[^0-9\.]/g, '');
					price = parseFloat( price );

					price = money(price).to(to_currency);
					price = price.toFixed( currency_decimals );
					price = accounting.formatNumber( price, currency_decimals, wc_currency_converter_params.thousand_sep, wc_currency_converter_params.decimal_sep );

					if ( remove_zeros ) {
						price = price.replace( wc_currency_converter_params.zero_replace, '' );
					}

					$(this).html( current_option[0] + ": " + price  + " " + to_currency );

				});

				base.price_filter_update( to_currency );

				$('body').trigger( 'currency_converter_switch', [to_currency] );

			},
			price_filter_update: function ( to_currency ) {
				if ( to_currency ) {
					$('.ui-slider').each(function() {
						theslider = $( this );
						values    = theslider.slider("values");

						original_price = "" + values[1];
						original_price = original_price.replace( wc_currency_converter_params.thousand_sep, '' );
						original_price = original_price.replace( wc_currency_converter_params.decimal_sep, '.' );
						original_price = original_price.replace(/[^0-9\.]/g, '');
						original_price = parseFloat( original_price );

						price_max = money(original_price).to(to_currency);

						original_price = "" + values[0];
						original_price = original_price.replace( wc_currency_converter_params.thousand_sep, '' );
						original_price = original_price.replace( wc_currency_converter_params.decimal_sep, '.' );
						original_price = original_price.replace(/[^0-9\.]/g, '');
						original_price = parseFloat( original_price );

						price_min = money(original_price).to(to_currency);

						$('.price_slider_amount').find('span.from').html( price_min.toFixed(2) + " " + to_currency );
						$('.price_slider_amount').find('span.to').html( price_max.toFixed(2) + " " + to_currency );
					});
				}
			}

		}).init();

	}

	$(function () {

		$.mad_woocommerce_mod.currencySwitcher();

		if ($('.toggle-button').length) {
			$('.toggle-button').css3Dropdown();
		}

	});

})(jQuery);