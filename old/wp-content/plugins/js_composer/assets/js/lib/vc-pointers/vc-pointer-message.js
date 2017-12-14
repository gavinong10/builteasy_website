/* =========================================================
 * vc-pointer-message.js v2.0.0
 * =========================================================
 * Copyright 2015 Wpbakery
 *
 * Vc Pointers messages.
 *
 * @since 4.5
 * ========================================================= */
var vcPointerMessage;
(function ( $ ) {
	'use strict';
	/**
	 * Initialize pointers control
	 * @param target
	 * @param pointerOptions
	 * @param texts
	 */
	vcPointerMessage = function ( target, pointerOptions, texts ) {
		this.target = target;
		this.$pointer = null;
		this.texts = texts;
		this.pointerOptions = pointerOptions;
		this.init();
	};
	vcPointerMessage.prototype = {
		init: function () {
			_.bindAll( this, 'openedEvent', 'reposition' );
		},
		show: function () {
			this.$pointer = $( this.target );
			this.$pointer.data( 'vcPointerMessage', this );
			this.pointerOptions.opened = this.openedEvent;
			this.$pointer.addClass( 'vc-with-vc-pointer' ).pointer( this.pointerOptions ).pointer( 'open' );
			$( window ).on( 'resize.vcPointer', this.reposition );
		},
		// Render DOM elements
		domButtonsWrapper: function () {
			return $( '<div class="vc_wp-pointer-controls" />' );
		},
		domCloseBtn: function () {
			return $( '<a class="vc_pointer-close close">' + this.texts.finish + '</a>' );
		},
		domNextBtn: function () {
			return $( '<button class="button button-primary button-large vc_wp-pointers-next">'
			+ this.texts.next + '<i class="vc_pointer-icon"></i></button>' );
		},
		domPrevBtn: function () {
			return $( '<button class="button button-primary button-large vc_wp-pointers-prev">'
			+ '<i class="vc_pointer-icon"></i>'
			+ this.texts.prev + '</button> ' );
		},
		/**
		 * Scroll to pointer. Called by $.fn.pointer
		 * @returns {*}
		 */
		openedEvent: function ( a, b ) {
			var offset = b.pointer.offset();
			offset && offset.top && $( 'body' ).scrollTop( 80 < offset.top ? offset.top - 80 : 0 );
		},
		reposition: function () {
			this.$pointer.pointer( 'reposition' );
		},
		close: function () {
			this.$pointer && this.$pointer.removeClass( 'vc-with-vc-pointer' ).pointer( 'close' );
			$( window ).off( 'resize.vcPointer' );
		}
	};
}( window.jQuery ));