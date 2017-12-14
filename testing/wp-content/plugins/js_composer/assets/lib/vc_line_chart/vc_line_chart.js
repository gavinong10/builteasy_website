(function ( $ ) {

	/**
	 * Generate line/bar charts
	 *
	 * Legend must be generated manually. If color is array (gradient), then legend won't show it.
	 */
	$.fn.vcLineChart = function () {
		this.each( function () {
			var data,
				gradient,
				chart,
				i,
				j,
				$this = $( this ),
				ctx = $this.find( 'canvas' )[ 0 ].getContext( '2d' ),
				options = {
					showTooltips: $this.data( 'vcTooltips' ),
					animationEasing: $this.data( 'vcAnimation' ),
					datasetFill: true,
					responsive: true
				},
				color_keys = [
					'fillColor',
					'strokeColor',
					'highlightFill',
					'highlightFill',
					'pointHighlightFill',
					'pointHighlightStroke'
				];

			// If plugin has been called on already initialized element, reload it
			if ( $this.data( 'chart' ) ) {
				$this.data( 'chart' ).destroy();
			}

			data = $this.data( 'vcValues' );

			ctx.canvas.width = $this.width();
			ctx.canvas.height = $this.width();

			// If color/highlight is array (of 2 colors), replace it with generated gradient
			for ( i = data.datasets.length - 1;
				  0 <= i;
				  i -- ) {
				for ( j = color_keys.length - 1;
					  0 <= j;
					  j -- ) {
					if ( 'object' === typeof( data[ 'datasets' ][ i ][ color_keys[ j ] ] ) && 2 === data[ 'datasets' ][ i ][ color_keys[ j ] ].length ) {
						gradient = ctx.createLinearGradient( 0, 0, 0, ctx.canvas.height );
						gradient.addColorStop( 0, data[ 'datasets' ][ i ][ color_keys[ j ] ][ 0 ] );
						gradient.addColorStop( 1, data[ 'datasets' ][ i ][ color_keys[ j ] ][ 1 ] );
						data[ 'datasets' ][ i ][ color_keys[ j ] ] = gradient;
					}
				}
			}

			if ( 'bar' === $this.data( 'vcType' ) ) {
				chart = new Chart( ctx ).Bar( data, options );
			} else {
				chart = new Chart( ctx ).Line( data, options );
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
	if ( 'function' !== typeof( window.vc_line_charts ) ) {
		window.vc_line_charts = function ( model_id ) {
			var selector = '.vc_line-chart';
			if ( 'undefined' !== typeof( model_id ) ) {
				selector = '[data-model-id="' + model_id + '"] ' + selector;
			}
			$( selector ).vcLineChart();
		};
	}
	$( document ).ready( function () {
		! window.vc_iframe && vc_line_charts();
	} );
}( jQuery ));