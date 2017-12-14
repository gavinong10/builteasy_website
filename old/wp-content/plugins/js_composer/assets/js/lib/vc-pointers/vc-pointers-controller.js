/* =========================================================
 * vc-pointers-control.js v2.0.0
 * =========================================================
 * Copyright 2015 Wpbakery
 *
 * Vc Pointers controller messages.
 *
 * @since 4.5
 * ========================================================= */
/* global vcPointerMessage, vcPointer, ajaxurl */
var vcPointersController;
(function ( $ ) {
	'use strict';
	/**
	 * Initialize pointers control
	 * @param Pointer {{pointer_id: String, messages: Array, closeEvent, showEvent, showCallback, closeCallback}}
	 * @param texts
	 */
	vcPointersController = function ( Pointer, texts ) {
		this.pointers = ( Pointer && Pointer.messages ) || [];
		this._texts = texts; // @TODO: Remove from controller use simple localization
		this.pointerId = Pointer && Pointer.pointer_id ? Pointer.pointer_id : '';
		this.pointerData = {};
		this._index = 0;
		this.messagesDismissed = false;
		this.init();
	};
	/**
	 * Pointers controller to show and set next or prev pointer message.
	 * @since 4.5
	 *
	 * @type {{init: Function, getPointer: Function, build: Function, show: Function, setCustomCloseEventHandler: Function, next: Function, prev: Function, close: Function, openedEvent: Function, buttonsEvent: Function, domButtonsWrapper: Function, domCloseBtn: Function, domNextBtn: Function, domPrevBtn: Function, clickEventClose: Function, clickEventNext: Function, clickEventPrev: Function, dismissMessages: Function}}
	 */
	vcPointersController.prototype = {
		init: function () {
			_.bindAll( this,
				'show',
				'clickEventClose',
				'clickEventNext',
				'clickEventPrev',
				'buttonsEvent'
			);
			this.build();
		},
		/**
		 * Get pointer by index.
		 *
		 * @param index
		 * @returns {*}
		 */
		getPointer: function ( index ) {
			this.pointerData = this.pointers[ index ] && this.pointers[ index ].target ? this.pointers[ index ] : null;
			if ( ! this.pointerData || ! this.pointerData.options ) {
				return null;
			}
			return new vcPointerMessage( this.pointerData.target,
				this.buildOptions( this.pointerData.options ),
				this._texts );
		},
		/**
		 * Build options for vcPointerMessage object.
		 *
		 * @param data
		 * @returns {*}
		 */
		buildOptions: function ( data ) {
			// if button is not a function remove from settings or find a definition of it in global scope.
			if ( data.buttonsEvent && _.isFunction( window[ data.buttonsEvent ] ) ) {
				data.buttons = _.bind( window[ data.buttonsEvent ], this );
			} else {
				data.buttons = this.buttonsEvent;
			}
			data.vcPointerController = this; // Just in case. TODO: Remove it in the future.
			return data;
		},
		/**
		 * Build settings to show next pointer.
		 *
		 * @returns {boolean}
		 */
		build: function () {
			this.pointer = this.getPointer( this._index );
			vc.events.once( 'backendEditor.close', this.close, this );
			if ( ! this.pointer ) {
				return false;
			}
			this.setShowEventHandler();
		},
		/**
		 * Show/render pointer in DOM tree.
		 */
		show: function () {
			this.pointer.show();
			this.setCloseEventHandler();
			vc.events.trigger( 'vcPointer:show' );
		},
		/**
		 * Show Pointer depending on settings.
		 *
		 * Possible to show with showCallback global function from settings
		 * on showEvent from settings in "event object" format or vc.event.
		 *
		 */
		setShowEventHandler: function () {
			var showEvent;
			if ( this.pointerData.showCallback && window[ this.pointerData.showCallback ] ) {
				window[ this.pointerData.showCallback ].call( this );
			} else if ( this.pointerData.showEvent ) {
				if ( this.pointerData.showEvent.match( /\s/ ) ) {
					showEvent = this.pointerData.closeEvent.split( /\s+(.+)?/ );
					1 < showEvent.length && $( showEvent[ 1 ] ).one( showEvent[ 0 ], this.show );
				} else {
					vc.events.once( this.pointerData.showEvent, this.show );
				}
			} else {
				this.show();
			}
		},
		/**
		 * Close Pointer on events.
		 *
		 * Default event is click on pointer target.
		 * Possible to close with on showCallback global function from settings
		 * on closeEvent from settings in "event object" format or vc.event.
		 *
		 */
		setCloseEventHandler: function () {
			var closeEvent;
			if ( this.pointerData.closeCallback && window[ this.pointerData.closeCallback ] ) {
				window[ this.pointerData.closeCallback ].call( this );
			} else if ( this.pointerData.closeEvent ) {
				// If it is a pair of event type and selector then build event.
				if ( this.pointerData.closeEvent.match( /\s/ ) ) {
					closeEvent = this.pointerData.closeEvent.split( /\s+(.+)?/ );
					$( closeEvent[ 1 ] || this.$pointer )
						.one( closeEvent[ 1 ] && closeEvent[ 0 ] ? closeEvent[ 0 ] : 'click',
						this.clickEventNext );
				} else {
					// Add to vc event
					vc.events.once( this.pointerData.closeEvent, this.nextOnEvent, this );
				}
			} else {
				this.pointer.$pointer && 0 < this.pointer.$pointer.length
				&& $( this.pointer.$pointer ).one( 'click', this.clickEventNext );
			}
		},
		nextOnEvent: function () {
			this.close();
			this.next();
		},
		next: function () {
			this._index ++;
			this.build();
		},
		prev: function () {
			this._index --;
			this.build();
		},
		close: function () {
			if ( this.pointer ) {
				this.pointer.close();
				this.pointerData = null;
				this.pointer = null;
				vc.events.trigger( 'vcPointer:close', this );
			}
		},
		/**
		 * Build html controls for pointer DOM element. Called by $.fn.pointer
		 * @returns {*}
		 */
		buttonsEvent: function () {
			var $closeBtn, $nextBtn, $prevBtn, $buttons, controls;
			$closeBtn = this.pointer.domCloseBtn();
			$nextBtn = this.pointer.domNextBtn();
			$prevBtn = this.pointer.domPrevBtn();

			$closeBtn.bind( 'click.vcPointer', this.clickEventClose );

			$buttons = this.pointer.domButtonsWrapper().append( $closeBtn );
			if ( 0 < this._index ) {
				$prevBtn.bind( 'click.vcPointer', this.clickEventPrev );
				$buttons.addClass( 'vc_wp-pointer-controls-prev' ).append( $prevBtn );
			}
			if ( this._index + 1 < this.pointers.length ) {
				$nextBtn.bind( 'click.vcPointer', this.clickEventNext );
				$buttons.addClass( 'vc_wp-pointer-controls-next' ).append( $nextBtn );
			}
			return $buttons;
		},
		// Events
		clickEventClose: function () {
			this.close();
			this.dismissMessages();
		},
		clickEventNext: function () {
			this.close();
			this.next();
		},
		clickEventPrev: function () {
			this.close();
			this.prev();
		},
		/**
		 * Send server notification not to show this pointers messages again.
		 */
		dismissMessages: function () {
			if ( this.messagesDismissed ) {
				return false;
			}
			$.post( window.ajaxurl, {
				pointer: this.pointerId,
				action: 'dismiss-wp-pointer'
			} );
			this.messagesDismissed = true;
		}
	};
}( window.jQuery ));