if ( ! window.vc ) {
	var vc = {};
}
(function ( $ ) {
	var ListenerHelper = vc.events = {};
	_.extend( ListenerHelper, Backbone.Events );

	/**
	 * Used to trigger shortcodes events (just alias and shortcut)
	 *
	 * @param eventType
	 * @param shortcodeModel
	 */
	ListenerHelper.triggerShortcodeEvents = function ( eventType, shortcodeModel ) {
		var shortcodeTag;
		shortcodeTag = shortcodeModel.get( 'shortcode' );
		this.trigger( 'shortcodes', shortcodeModel, eventType );
		this.trigger( 'shortcodes:' + shortcodeTag, shortcodeModel, eventType );
		this.trigger( 'shortcodes:' + eventType, shortcodeModel );
		this.trigger( 'shortcodes:' + shortcodeTag + ':' + eventType, shortcodeModel );
		this.trigger( 'shortcodes:' + shortcodeTag + ':' + eventType + ':parent:' + shortcodeModel.get( 'parent_id' ),
			shortcodeModel );
		// Now trigger shortcode params events
		this.triggerParamsEvents( eventType, shortcodeModel );
	};

	/**
	 * Used to trigger shortcodes params events for exact param
	 * @param eventType
	 * @param shortcodeModel
	 */
	ListenerHelper.triggerParamsEvents = function ( eventType, shortcodeModel ) {
		var shortcodeTag,
			params,
			settings;

		shortcodeTag = shortcodeModel.get( 'shortcode' );
		params = _.extend( {}, shortcodeModel.get( 'params' ) ); // can be received only when main "add" event called!
		settings = vc.map[ shortcodeTag ];
		if ( _.isArray( settings.params ) ) {
			_.each( settings.params, function ( paramSettings ) {

				// Also triggering changes for params
				this.trigger( 'shortcodes:' + eventType + ':param',
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );
				this.trigger( 'shortcodes:' + shortcodeTag + ':' + eventType + ':param',
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );

				this.trigger( 'shortcodes:' + eventType + ':param:type:' + paramSettings.type,
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );
				this.trigger( 'shortcodes:' + shortcodeTag + ':' + eventType + ':param:type:' + paramSettings.type,
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );

				this.trigger( 'shortcodes:' + eventType + ':param:name:' + paramSettings.param_name,
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );
				this.trigger( 'shortcodes:' + shortcodeTag + ':' + eventType + ':param:name:' + paramSettings.param_name,
					shortcodeModel,
					params[ paramSettings.param_name ],
					paramSettings );

			}, this );
		}
	};

})( window.jQuery );