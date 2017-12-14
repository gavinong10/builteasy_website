/* =========================================================
 * vc-tta-autoplay.js v1.0.0
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer tabs, tours, accordion auto play
 * ========================================================= */
+ function ( $ ) {
	'use strict';

	var Plugin, TtaAutoPlay, old;

	Plugin = function ( action, options ) {
		var args;

		args = Array.prototype.slice.call( arguments, 1 );
		return this.each( function () {
			var $this, data;

			$this = $( this );
			data = $this.data( 'vc.tta.autoplay' );
			if ( ! data ) {
				data = new TtaAutoPlay( $this,
					$.extend( true, {}, TtaAutoPlay.DEFAULTS, $this.data( 'vc-tta-autoplay' ), options ) );
				$this.data( 'vc.tta.autoplay', data );
			}
			if ( 'string' === typeof(action) ) {
				data[ action ].apply( data, args );
			} else {
				data.start( args ); // start the auto play by default
			}
		} );
	};

	/**
	 * AutoPlay constuctor
	 * @param $element
	 * @param options
	 * @constructor
	 */
	TtaAutoPlay = function ( $element, options ) {
		this.$element = $element;
		this.options = options;
	};

	TtaAutoPlay.DEFAULTS = {
		delay: 5000,
		pauseOnHover: true,
		stopOnClick: true
	};

	/**
	 * Method called on timeout hook call
	 */
	TtaAutoPlay.prototype.show = function () {
		this.$element.find( '[data-vc-accordion]:eq(0)' ).vcAccordion( 'showNext' );
	};

	/**
	 * Check is container has set window.setInterval
	 *
	 * @returns {boolean}
	 */
	TtaAutoPlay.prototype.hasTimer = function () {
		return undefined !== this.$element.data( 'vc.tta.autoplay.timer' );
	};

	/**
	 * Set for container window.setInterval and save it in data-attribute
	 *
	 * @param windowInterval
	 */
	TtaAutoPlay.prototype.setTimer = function ( windowInterval ) {
		this.$element.data( 'vc.tta.autoplay.timer', windowInterval );
	};

	/**
	 * Get containers timer from data-attributes
	 *
	 * @returns {*|Number}
	 */
	TtaAutoPlay.prototype.getTimer = function () {
		return this.$element.data( 'vc.tta.autoplay.timer' );
	};

	/**
	 * Removes from container data-attributes timer
	 */
	TtaAutoPlay.prototype.deleteTimer = function () {
		this.$element.removeData( 'vc.tta.autoplay.timer' );
	};

	/**
	 * Starts the autoplay timer with multiple call preventions
	 */
	TtaAutoPlay.prototype.start = function () {
		var $this,
			that;

		$this = this.$element;
		that = this;

		/**
		 * Local method called when accordion title being clicked
		 * Used to stop autoplay
		 *
		 * @param e {jQuery.Event}
		 */
		function stopHandler( e ) {
			e.preventDefault && e.preventDefault();

			if ( that.hasTimer() ) {
				Plugin.call( $this, 'stop' );
			}
		}

		/**
		 * Local method called when mouse hovers a [data-vc-tta-autoplay] element( this.$element )
		 * Used to pause/resume autoplay
		 *
		 * @param e {jQuery.Event}
		 */
		function hoverHandler( e ) {
			e.preventDefault && e.preventDefault();

			if ( that.hasTimer() ) {
				Plugin.call( $this, 'mouseleave' === e.type ? 'resume' : 'pause' );
			}
		}

		if ( ! this.hasTimer() ) {
			this.setTimer( window.setInterval( this.show.bind( this ), this.options.delay ) );

			// On switching tab by click it stop/clears the timer
			this.options.stopOnClick && $this.on( 'click.vc.tta.autoplay.data-api',
				'[data-vc-accordion]',
				stopHandler );

			// On hover it pauses/resumes the timer
			this.options.pauseOnHover && $this.hover( hoverHandler );
		}
	};

	/**
	 * Resumes the paused autoplay timer
	 */
	TtaAutoPlay.prototype.resume = function () {
		if ( this.hasTimer() ) {
			this.setTimer( window.setInterval( this.show.bind( this ), this.options.delay ) );
		}
	};

	/**
	 * Stop the autoplay timer
	 */
	TtaAutoPlay.prototype.stop = function () {
		this.pause();
		this.deleteTimer();
		// Remove bind events in TtaAutoPlay.prototype.start method
		this.$element.off( 'click.vc.tta.autoplay.data-api mouseenter mouseleave' );
	};

	/**
	 * Pause the autoplay timer
	 */
	TtaAutoPlay.prototype.pause = function () {
		var timer;

		timer = this.getTimer();
		if ( undefined !== timer ) {
			window.clearInterval( timer );
		}
	};

	old = $.fn.vcTtaAutoPlay;

	$.fn.vcTtaAutoPlay = Plugin;

	$.fn.vcTtaAutoPlay.Constructor = TtaAutoPlay;

	/**
	 * vcTtaAutoPlay no conflict
	 * @returns {$.fn.vcTtaAutoPlay}
	 */
	$.fn.vcTtaAutoPlay.noConflict = function () {
		$.fn.vcTtaAutoPlay = old;
		return this;
	};

	/**
	 * Find all autoplay elements and start the timer
	 */
	function startAutoPlay() {
		$( '[data-vc-tta-autoplay]' ).each( function () {
			$( this ).vcTtaAutoPlay();
		} );
	}

	/**
	 *
	 */
	$( document ).ready( startAutoPlay );
}( window.jQuery );