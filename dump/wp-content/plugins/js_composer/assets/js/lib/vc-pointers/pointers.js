/* =========================================================
 * pointers.js v2.0.0
 * =========================================================
 * Copyright 2015 Wpbakery
 *
 * Vc Pointers messages.
 *
 * @since 4.5
 * ========================================================= */
/* global vcPointer, ajaxurl, vcPointersController, vcEventPointersController */
(function ( $ ) {
	'use strict';
	vc.events.on( 'app.render', function () {
		// Init vcPointers if messages exists
		if ( vcPointer && vcPointer.pointers && vcPointer.pointers.length ) {
			_.each( vcPointer.pointers, function ( pointer ) {
				new vcPointersController( pointer, vcPointer.texts );
			}, this );
		}
	} );
	vc.events.on( 'vcPointer:show', function () {
		vc.app.disableFixedNav = true; // disable vc bar jumping enhancement
	} );
	vc.events.on( 'vcPointer:close', function () {
		vc.app.disableFixedNav = false; // disable vc bar jumping enhancement
	} );
	window.vcPointersEditorsTourEvents = function () {
		var $closeBtn;
		$closeBtn = this.pointer.domCloseBtn();
		$closeBtn.bind( 'click.vcPointer', this.clickEventClose );
		this.dismissMessages();
		return $closeBtn;
	};
	// Special for backend
	window.vcPointersShowOnContentElementControls = function () {
		if ( this.pointer && $( this.pointer.target ).length ) {
			$( this.pointer.target ).parent().addClass( 'vc-with-vc-pointer-controls' );
			this.show();
			$( '#wpb_visual_composer' ).one( 'click', function () {
				$( '.vc-with-vc-pointer-controls' ).removeClass( 'vc-with-vc-pointer-controls' );
			} );
		} else {
			vc.events.once( 'shortcodes:add', vcPointersShowOnContentElementControls, this );
		}
	};
	window.vcPointersSetInIFrame = function () {
		if ( this.pointerData && vc.frame_window.jQuery( this.pointerData.target ).length ) {
			this.pointer = new vc.frame_window.vcPointerMessage( this.pointerData.target,
				this.buildOptions( this.pointerData.options ),
				this._texts );
			this.show();
			this.pointer.$pointer.closest( '.vc_controls' ).addClass( 'vc-with-vc-pointer-controls' );
		} else {
			vc.events.once( 'shortcodeView:ready', vcPointersSetInIFrame, this );
		}
	};
	window.vcPointersCloseInIFrame = function () {
		var controller, _$;
		controller = this;
		_$ = vc.frame_window.jQuery;
		_$( 'body' ).one( 'click', function () {
			_$( '.vc-with-vc-pointer-controls' ).removeClass( 'vc-with-vc-pointer-controls' );
			controller.nextOnEvent();
		} );
	};
})( window.jQuery );