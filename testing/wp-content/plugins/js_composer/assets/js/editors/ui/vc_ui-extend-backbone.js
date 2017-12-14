/* =========================================================
 * vc_ui-extend-backbone.js v1.0.0
 * =========================================================
 * Copyright 2015 WPBakery
 *
 * Visual Composer extend Backbone ui events.
 *
 * ========================================================= */
/* global vc, Backbone, _ */
(function ( _, Backbone, vc ) {
	'use strict';
	/**
	 * Extend UI method to map events for ui.
	 * @param object
	 * @returns {*}
	 * @constructor
	 */
	function ExtendUI( object ) {
		var newObject = this.extend( object );
		if ( ! newObject.prototype._vcUIEventsHooks ) {
			newObject.prototype._vcUIEventsHooks = [];
		}
		if ( object.uiEvents ) {
			newObject.prototype._vcUIEventsHooks.push( object.uiEvents );
		}
		return newObject;
	}

	Backbone.View.vcExtendUI = ExtendUI;
	/**
	 * Add new mapping for ui events in delegateEvents method for default Backbone.View.
	 */
	vc.View = Backbone.View.extend( {
		delegateEvents: function () {
			// Call default
			vc.View.__super__.delegateEvents.call( this );
			if ( this._vcUIEventsHooks && this._vcUIEventsHooks.length ) {
				_.each( this._vcUIEventsHooks, function ( events ) {
					if ( _.isObject( events ) ) {
						_.each( events, function ( methods, e ) {
							if ( _.isString( methods ) ) {
								_.each( methods.split( /\s+/ ), function ( method ) {
									this.on( e, this[ method ], this );
								}, this );
							}
						}, this );
					}
				}, this );
			}
		}
	} );
})( _, Backbone, vc );