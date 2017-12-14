/**
 * frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */
jQuery(document).ready(function($){
    "use strict";

	(function () {

		var el = $('#yith-s'),
			loader_icon = el.data('loader-icon') == '' ? global.ajax_loader_url : el.data('loader-icon'),
			min_chars = el.data('min-chars');

		el.autocomplete({
			minChars: min_chars,
			appendTo: '.search-outer .yith-ajaxsearchform-container',
			serviceUrl: woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
			onSearchStart: function(){
				$(this).css('background', 'url(' + loader_icon + ') no-repeat 99% center');
			},
			onSearchComplete: function(){
				$(this).css('background', 'transparent');
			},
			onSelect: function (suggestion) {
				if( suggestion.id != -1 ) {
					window.location.href = suggestion.url;
				}
			},
			zIndex: 20,
			maxHeight: 'auto'
		});

	})();


	(function () {

		var el = $('#yith-s-widget'),
			loader_icon = el.data('loader-icon') == '' ? global.ajax_loader_url : el.data('loader-icon'),
			min_chars = el.data('min-chars');

		el.autocomplete({
			minChars: min_chars,
			appendTo: '.widget .yith-ajaxsearchform-container',
			serviceUrl: woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
			onSearchStart: function(){
				$(this).css('background', 'url(' + loader_icon + ') no-repeat right center');
			},
			onSearchComplete: function(){
				$(this).css('background', 'transparent');
			},
			onSelect: function (suggestion) {
				if( suggestion.id != -1 ) {
					window.location.href = suggestion.url;
				}
			},
			zIndex: 20,
			maxHeight: 'auto'
		});

	})();



});
