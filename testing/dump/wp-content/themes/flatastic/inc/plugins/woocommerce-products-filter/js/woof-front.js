(function ($) {

	"use strict";

	/* Filter Modification
	/* --------------------------------------------- */

	$.mad_filter_mod = function () {
		this.init();
	}

	$.mad_filter_mod.prototype = {
		init: function () {
			var base = this;

			base.wrapContainer = $(woof_mod.container).wrap('<div class="wrap-product-container"></div>').parent();

			$(document).on('click', '.woof_list a', function (e) {
				e.preventDefault();

				var href = this.href;

				//loading
				base.wrapContainer.html('').addClass('woof-loading');
				$(woof_mod.pagination).hide();
				$(woof_mod.result_count).hide();

				$.ajax({
					url: href,
					success: function ( response ) {

						base.wrapContainer.removeClass('woof-loading');

						// container
						if ( $(response).find(woof_mod.container).length > 0 ) {
							base.wrapContainer.html( $(response).find(woof_mod.container) );
						} else {
							base.wrapContainer.html( $(response).find('.woocommerce-info') );
						}

						// pagination
						if ( $(response).find(woof_mod.pagination).length > 0 ) {

//							if( $(woof_mod.pagination).length == 0 ) {
//								$.jseldom( woof_mod.pagination ).insertAfter( $(woof_mod.container) );
//							}

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
						if ($('.price_slider').length) new $.mad_price_slider_mod();
					}

				});

			}).on('click', '.close_woof_container', function () {
				$(this).parent('.woof_container').animate({
					'opacity':'0'
				}, function() {
					$(this).slideUp();
				});
			});

		}
	}

	$(function () {
		new $.mad_filter_mod();
	});

})(jQuery);