/**
 * Created by Ovidiu on 8/5/2016.
 */
var ThriveNMWordpressNotification = ThriveNMWordpressNotification || {};
ThriveNMWordpressNotification.functions = ThriveNMWordpressNotification.functions || {};

(function ( $ ) {

	$( document ).on( 'click', '.td_nm_wordpress_notice .notice-dismiss', function () {
		var key = $( this ).parent().attr( 'data-key' );
		ThriveNMWordpressNotification.functions.dismiss_notice( key, false );
	} );

	/**
	 * Trigger dismiss notice
	 * @param test_id
	 */
	ThriveNMWordpressNotification.functions.trigger_dismiss_notice = function ( key ) {
		this.dismiss_notice( key, true );
	};

	/**
	 * Dismiss notice ajax request
	 * @param test_id
	 * @param redirect
	 */
	ThriveNMWordpressNotification.functions.dismiss_notice = function ( key, redirect ) {

		$.ajax( {
			headers: {
				'X-WP-Nonce': ThriveNMWordpressNotification.nonce
			},
			cache: false,
			url: ThriveNMWordpressNotification.routes.ajaxurl + '?_nonce=' + ThriveNMWordpressNotification.nonce,
			type: 'POST',
			data: {
				key: key,
				redirect: redirect,
				action: 'td_nm_admin_controller',
				route: 'deletenotification'
			}
		} ).done( function ( response ) {
			if ( response.status == true && redirect == true ) {
				top.location.href = response.redirect_url;
			}
			$( 'div' ).find( '[data-key="' + key + '"]' ).remove();
		} )
		 .error( function () {
			 console.log( "error" );
		 } );

	};


})( jQuery );