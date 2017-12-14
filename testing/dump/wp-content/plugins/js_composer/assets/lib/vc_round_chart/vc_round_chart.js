(function ( $ ) {

	/**
	 * Generate pie/doughnut charts
	 *
	 * Legend must be generated manually. If color is array (gradient), then legend won't show it.
	 */
	$.fn.vcRoundChart = function () {
		this.each( function (	) {
			var data,
				gradient,
				chart,
				i,
				j,
				$this = $( this ),
				ctx = $this.find( 'canvas' )[ 0 ].getContext( '2d' ),
				stroke_width = $this.data( 'vcStrokeWidth' ) ? parseInt( $this.data( 'vcStrokeWidth' ), 10 ) : 0,
				options = {
					showTooltips: $this.data( 'vcTooltips' ),
					animationEasing: $this.data( 'vcAnimation' ),
					segmentStrokeColor: $this.data( 'vcStrokeColor' ),
					segmentShowStroke: 0 !== stroke_width,
					segmentStrokeWidth: stroke_width,
					responsive: true
				},
				color_keys = [
					'color',
					'highlight'
				];

			// If plugin has been called on already initialized element, reload it
			if ( $this.data( 'chart' ) ) {
				$this.data( 'chart' ).destroy();
			}

			data = $this.data( 'vcValues' );

			ctx.canvas.width = $this.width();
			ctx.canvas.height = $this.width();

			// If color/highlight is array (of 2 colors), replace it with generated gradient
			for ( i = data.length - 1;
				  0 <= i;
				  i -- ) {
				for ( j = color_keys.length - 1;
					  0 <= j;
					  j -- ) {
					if ( 'object' === typeof( data[ i ][ color_keys[ j ] ] ) && 2 === data[ i ][ color_keys[ j ] ].length ) {
						gradient = ctx.createLinearGradient( 0, 0, 0, ctx.canvas.height );
						gradient.addColorStop( 0, data[ i ][ color_keys[ j ] ][ 0 ] );
						gradient.addColorStop( 1, data[ i ][ color_keys[ j ] ][ 1 ] );
						data[ i ][ color_keys[ j ] ] = gradient;
					}
				}
			}

			if ( 'doughnut' === $this.data( 'vcType' ) ) {
				chart = new Chart( ctx ).Doughnut( data, options );
			} else {
				chart = new Chart( ctx ).Pie( data, options );
			}
			$this.data( 'vcChartId', chart.id );
			// We can later access chart to call methods on it
			$this.data( 'chart', chart );
		} );

		return this;
	};

	/**
	 * Allows users to rewrite function inside theme.
	 */
	if ( 'function' !== typeof( window.vc_round_charts  ) ) {
		window.vc_round_charts = function ( model_id ) {
			var selector = '.vc_round-chart';
			if ( 'undefined' !== typeof( model_id ) ) {
				selector = '[data-model-id="' + model_id + '"] ' + selector;
			}
			$( selector ).vcRoundChart();
		};
	}

	$( document ).ready( function () {
		! window.vc_iframe && vc_round_charts();
	} );

}( jQuery ));