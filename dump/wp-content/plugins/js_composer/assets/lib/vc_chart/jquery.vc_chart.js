/* =========================================================
 * jquery.vc_chart.js v1.0
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Jquery chart plugin for the Visual Composer.
 * ========================================================= */
(function ( $ ) {
	/**
	 * Pie chart animated.
	 * @param element - DOM element
	 * @param options - settings object.
	 * @constructor
	 */
	function VcChart( element, options ) {
		this.el = element;
		this.$el = $( this.el );
		this.options = $.extend( {
			color: '#f7f7f7',
			units: '',
			label_selector: '.vc_pie_chart_value',
			back_selector: '.vc_pie_chart_back',
			responsive: true
		}, options );
		this.init();
	}

	VcChart.prototype = {
		constructor: VcChart,
		_progress_v: 0,
		animated: false,
		init: function () {
			this.color = this.options.color;
			this.value = this.$el.data( 'pie-value' ) / 100;
			this.label_value = this.$el.data( 'pie-label-value' ) || this.$el.data( 'pie-value' );
			this.$wrapper = $( '.vc_pie_wrapper', this.$el );
			this.$label = $( this.options.label_selector, this.$el );
			this.$back = $( this.options.back_selector, this.$el );
			this.$canvas = this.$el.find( 'canvas' );
			this.draw();
			this.setWayPoint();
			if ( true === this.options.responsive ) {
				this.setResponsive();
			}
		},
		setResponsive: function () {
			var that = this;
			$( window ).resize( function () {
				if ( true === that.animated ) {
					that.circle.stop();
				}
				that.draw( true );
			} );
		},
		draw: function ( redraw ) {
			var w = this.$el.addClass( 'vc_ready' ).width(),
				border_w = 5,
				radius;
			if ( ! w ) {
				w = this.$el.parents( ':visible' ).first().width() - 2;
			}
			w = w / 100 * 80;
			radius = w / 2 - border_w - 1;
			this.$wrapper.css( { "width": w + "px" } );
			this.$label.css( { "width": w, "height": w, "line-height": w + "px" } );
			this.$back.css( { "width": w, "height": w } );
			this.$canvas.attr( { "width": w + "px", "height": w + "px" } );
			this.$el.addClass( 'vc_ready' );

			this.circle = new ProgressCircle( {
				canvas: this.$canvas.get( 0 ),
				minRadius: radius,
				arcWidth: border_w
			} );
			if ( true === redraw && true === this.animated ) {
				this._progress_v = this.value;
				this.circle.addEntry( {
					fillColor: this.color,
					progressListener: $.proxy( this.setProgress, this )
				} ).start();
			}
		},
		setProgress: function () {
			if ( this._progress_v >= this.value ) {
				this.circle.stop();
				this.$label.text( this.label_value + this.options.units );
				return this._progress_v;
			}
			this._progress_v += 0.005;
			var label_value = this._progress_v / this.value * this.label_value;
			var val = Math.round( label_value ) + this.options.units;
			this.$label.text( val );
			return this._progress_v;
		},
		animate: function () {
			if ( true !== this.animated ) {
				this.animated = true;
				this.circle.addEntry( {
					fillColor: this.color,
					progressListener: $.proxy( this.setProgress, this )
				} ).start( 5 );
			}
		},
		setWayPoint: function () {
			if ( 'undefined' !== typeof($.fn.waypoint) ) {
				this.$el.waypoint( $.proxy( this.animate, this ), { offset: '85%' } );
			} else {
				this.animate();
			}
		}
	};
	/**
	 * jQuery plugin
	 * @param option - object with settings
	 * @return {*}
	 */
	$.fn.vcChat = function ( option, value ) {
		return this.each( function () {
			var $this = $( this ),
				data = $this.data( 'vc_chart' ),
				options = 'object' === typeof(option) ? option : {
					color: $this.data( 'pie-color' ),
					units: $this.data( 'pie-units' )
				};
			if ( 'undefined' === typeof(option) ) {
				$this.data( 'vc_chart', (data = new VcChart( this, options )) );
			}
			if ( 'string' === typeof(option) ) {
				data[ option ]( value );
			}
		} );
	};
	/**
	 * Allows users to rewrite function inside theme.
	 */
	if ( 'function' !== typeof(window[ 'vc_pieChart' ]) ) {
		window.vc_pieChart = function () {
			$( '.vc_pie_chart:visible' ).vcChat();
		}
	}
	$( document ).ready( function () {
		! window.vc_iframe && vc_pieChart();
	} );

})( window.jQuery );