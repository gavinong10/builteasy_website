(function ( $ ) {

	/**
	 * Init image zooming
	 */
	$.fn.vcImageZoom = function () {

		this.each( function () {
			var $this = $( this ),
				src = $this.data( 'vcZoom' );

			$this
				.removeAttr( 'data-vc-zoom' )
				.wrap( '<div class="vc-zoom-wrapper"></div>' )
				.parent()
				.zoom( {
					duration: 500,
					url: src,
					onZoomIn: function () {
						// If original image is smaller than one that is visible, don't show it (by destroying).
						// Rebind ensures that we still have working zoom in case image size changes later.
						if ( $this.width() > $( this ).width() ) {
							$this
								.trigger( 'zoom.destroy' )
								.attr( 'data-vc-zoom', '' )
								.unwrap()
								.vcImageZoom();
						}
					}
				} );
		} );

		return this;
	};

	// Allow users to rewrite function inside theme.
	if ( 'function' !== typeof( window.vc_image_zoom  ) ) {
		window.vc_image_zoom = function ( model_id ) {
			var selector = '[data-vc-zoom]';
			if ( 'undefined' !== typeof( model_id ) ) {
				selector = '[data-model-id="' + model_id + '"] ' + selector;
			}
			$( selector ).vcImageZoom();
		};
	}

	$( document ).ready( function () {
		! window.vc_iframe && vc_image_zoom();
	} );

}( jQuery ));