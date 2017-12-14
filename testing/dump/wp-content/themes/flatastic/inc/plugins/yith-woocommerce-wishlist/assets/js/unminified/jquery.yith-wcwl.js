jQuery(document).ready(function($){

    $(document).on('click', '.add_to_wishlist', function(){
        var url = yith_wcwl_plugin_ajax_web_url;
        var data = {
            add_to_wishlist : $(this).data('product-id'),
            product_type : $(this).data('product-type'),
            action : 'add_to_wishlist'
        };

        call_ajax_add_to_wishlist($(this), url,data);

        return false;
    });

});

/**
 * Add a product in the wishlist.
 *
 * @param string url
 * @param string prod_type
 * @return void
 * @since 1.0.0
 */
function call_ajax_add_to_wishlist( el, url, data ) {
    var el_wrap = el.parents('.yith-wcwl-add-to-wishlist');

    el_wrap.find( '.ajax-loading' ).css( 'visibility', 'visible' );

    jQuery.ajax({
        type: 'POST',
        url: url,
        data: 'product_id=' + jQuery( '.cart #product_id' ).val() + '&' + jQuery.param(data),
        success: function( response ) {
            var msg = jQuery( '#yith-wcwl-popup-message' );

            el_wrap.find( '.ajax-loading' ).css( 'visibility', 'hidden' );
            response_arr = response.split( "##" );

            jQuery( '#yith-wcwl-message' ).html( response_arr[1] );
            msg.css( 'margin-left', '-' + jQuery( msg ).width() + 'px' ).fadeIn();
            window.setTimeout( function() {
                msg.fadeOut();
            }, 2000 );

            if( jQuery.trim( response_arr[0] ) == "true" ) {
                el_wrap.find( '.yith-wcwl-add-button' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
                el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
                el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).css( 'display', 'block' ).removeClass('hide show').addClass('show');
            } else if( jQuery.trim( response_arr[0] ) == "exists" ) {
                el_wrap.find( '.yith-wcwl-add-button' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
                el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).css( 'display', 'block' ).removeClass('hide show').addClass('show');
                el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
            } else {
                el_wrap.find( '.yith-wcwl-add-button' ).css( 'display', 'block' ).removeClass('hide show').addClass('show');
                el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
                el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).css( 'display', 'none' ).removeClass('hide show').addClass('hide');
            }

            jQuery('body').trigger('added_to_wishlist');
        }

    });
}

/**
 * Remove a product from the wishlist.
 *
 * @param string url
 * @param int rowid
 * @return void
 * @since 1.0.0
 */
function remove_item_from_wishlist( url, rowid ) {
    jQuery( '#yith-wcwl-message' ).html( '&nbsp;' );
    jQuery( '.wishlist_table' ).css( 'opacity', '0.4' );

    jQuery.ajax({
        type: 'POST',
        url: url,
        data: {
            action: 'remove_from_wishlist'
        },
        success: function( response ) {
            jQuery( '.wishlist_table' ).css( 'opacity', '1' );

            jQuery( "#" + rowid ).remove();
            arr = response.split( '#' );

            jQuery( '#yith-wcwl-message' ).html( arr[0] );

            //display no products message, if exists
            jQuery( '.cart' ).append( '<tr><td colspan="6"><center>' + arr[0] + '</center></td></tr>' );
        }

    });
}

/**
 * Add a product to the cart from the wishlist.
 *
 * @param string url
 * @return void
 * @since 1.0.0
 */
function add_tocart_from_wishlist( url ) {
    jQuery( '#yith-wcwl-message' ).html( '&nbsp;' );

    jQuery.ajax({
        type: 'GET',
        url: url,
        success: function( response ) {
            jQuery( '#yith-wcwl-message' ).html( response );
        }
    });
}

/**
 * Check if a product is in stock.
 *
 * @param string url
 * @param string stock_status
 * @param bool redirect_to_cart
 * @return void
 * @since 1.0.0
 */
function check_for_stock( url, stock_status, redirect_to_cart ) {
    if( stock_status == 'out-of-stock' ) {
        alert( yith_wcwl_l10n.out_of_stock );
        return false;
    }

    if( redirect_to_cart == 'true' )
    { location.href = url + '&redirect_to_cart=true'; }
    else
    { location.href = url; }
}