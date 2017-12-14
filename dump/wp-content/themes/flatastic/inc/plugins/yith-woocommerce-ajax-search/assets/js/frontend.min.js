/**
 * frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */
jQuery(document).ready(function($){
    "use strict";

    var el = $('#yith-s'),
        loader_icon = el.data('loader-icon') == '' ? woocommerce_params.ajax_loader_url : el.data('loader-icon'),
        min_chars = el.data('min-chars');

    el.autocomplete({
        minChars: min_chars,
        appendTo: '.yith-ajaxsearchform-container',
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
        }
    });
});
