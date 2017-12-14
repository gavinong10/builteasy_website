(function ($, window, document) {

	"use strict";

	/*	DOM READY															*/
	/* -------------------------------------------------------------------- */

	$(function () {

		/*	Smoothscroll													  */
		/* ------------------------------------------------------------------ */

		try {
			$.browserSelector();
			var $html = $('html');
			if ($html.hasClass('chrome') || $html.hasClass('ie11') || $html.hasClass('ie10')) {
				$.smoothScroll();
			}
		} catch(err) {}

		/*	Synchronise														  */
		/* ------------------------------------------------------------------ */

		$.fn.waypointSynchronise = function (config) {
			var element = $(this);

			function addClassToElem(el,eq) {
				el.eq(eq).addClass('animate_finished');
			}

			element.closest(config.container).waypoint(function(direction) {
				element.each(function (i) {
					if (direction === 'down') {
						if (config.globalDelay != undefined) {
							setTimeout(function () {
								setTimeout(function () {
									addClassToElem(element,i);
								},i * config.delay);
							}, config.globalDelay);
						} else {
							setTimeout(function () {
								addClassToElem(element,i)
							},i * config.delay);
						}
					} else {
						if (config.inv) {
							setTimeout(function () {
								element.eq(i).removeClass(config.classN);
							}, i * config.delay);
						}
					}
				});
			},{ offset : config.offset });

			return element;
		};

		(function () {

			/*	WP THEME Custom Plugin								*/
			/* ---------------------------------------------------- */

			$('body').Temp({
				sticky: $('#header').data('shrink')
			});

		})();

		/*	Jackbox											  				  */
		/* ------------------------------------------------------------------ */

		(function () {

			if ($(".jackbox[data-group]").length) {
				$(".jackbox[data-group]").jackBox("init", {
					dynamic: true,
					showInfoByDefault: false,
					preloadGraphics: true,
					fullscreenScalesContent: true,
					autoPlayVideo: true,
					flashVideoFirst: false,
					defaultVideoWidth: 960,
					defaultVideoHeight: 540,
					baseName: global.template_directory + 'js/jackbox',
					className: ".jackbox",
					useThumbs: true,
					thumbsStartHidden: false,
					thumbnailWidth: 75,
					thumbnailHeight: 50,
					useThumbTooltips: true,
					showPageScrollbar: false,
					useKeyboardControls: true
				});
			}

		})();

		/*	ie9 placeholder													  */
		/* ------------------------------------------------------------------ */

		(function () {
			if ($('html').hasClass('ie9')) {
				$('input[placeholder]').each(function () {
					$(this).val($(this).attr('placeholder'));
					var v = $(this).val();
					$(this).on('focus', function () {
						if ($(this).val() === v) {
							$(this).val("");
						}
					}).on("blur", function () {
						if($(this).val() == ""){
							$(this).val(v);
						}
					});
				});
			}
		})();

		/*	 Post Slider										 	  		  */
		/* ------------------------------------------------------------------ */

		(function () {

			if ($('.post-slider').length) {
				$('.post-slider').owlCarousel({
					singleItem: true,
					theme : "owl-theme",

					// Autoplay
					autoPlay : true,
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

		})();

		/*	FitVids														 	  */
		/* ------------------------------------------------------------------ */

		$('#content').fitVids();

		/*	Custom Select													  */
		/* ------------------------------------------------------------------ */

		(function () {

			if ($('.custom-select').length) {
				$('.custom-select').customSelect();
			}

			if ($('.portfolio_filter').length) {
				$(".portfolio_filter").heapbox();
			}

		})();

		/*	Raty Init														  */
		/* ------------------------------------------------------------------ */

		(function () {

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

		})();

		/*	First Letter													  */
		/* ------------------------------------------------------------------ */

//		(function(){
//
//			var dp = $('[class*="first_letter"]');
//
//			dp.each(function(){
//				var self = $(this),
//					fl = self.text().charAt(0);
//				self.text(self.text().substr(1)).prepend('<span class="fl r_corners t_align_c f_left d_block">'+fl+'</span>');
//			});
//
//		})();

	});

}(jQuery));

