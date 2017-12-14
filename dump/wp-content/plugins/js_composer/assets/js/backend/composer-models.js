/* =========================================================
 * composer-models.js v0.2
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer backbone/underscore models for shortcodes.
 * ========================================================= */

(function ( $ ) {
	/**
	 * Collection of shortcodes.
	 * Extended Backbone.Collection object.
	 * This collection can be used for root(raw) shortcodes list and inside another shortcodes list as inner shortcodes.
	 * @type {*}
	 */
	var Shortcodes;
	var store = vc.storage;
	/**
	 * Shortcode model.
	 * Represents shortcode as an object.
	 * @type {*}
	 */
	vc.shortcode = Backbone.Model.extend( {
		settings: false,
		defaults: function () {
			var id = window.vc_guid();
			return {
				id: id,
				shortcode: 'vc_text_block',
				order: vc.shortcodes.getNextOrder(),
				params: {},
				parent_id: false,
				root_id: id,
				cloned: false,
				html: false,
				view: false
			};
		},
		initialize: function () {
			this.bind( 'remove', this.removeChildren, this );
			this.bind( 'remove', this.removeEvents, this );
		},
		removeEvents: function ( model ) {
			//triggering shortcodes and shortcodes:destroy events
			vc.events.triggerShortcodeEvents( 'destroy', model );
		},
		/**
		 * Synchronize data with our storage.
		 * @param method
		 * @param model
		 * @param options
		 */
		sync: function ( method, model, options ) {
			var response;
			// Select action to do with data in you storage
			switch ( method ) {
				case "read":
					response = model.id ? store.find( model ) : store.findAll();
					break;
				case "create":
					response = store.create( model );
					break;
				case "update":
					response = store.update( model );
					break;
				case "delete":
					response = store.destroy( model );
					break;
			}
			// Response
			if ( response ) {
				options.success( response );
			} else {
				options.error( "Record not found" );
			}
		},
		getParam: function ( key ) {
			return _.isObject( this.get( 'params' ) ) && ! _.isUndefined( this.get( 'params' )[ key ] ) ? this.get( 'params' )[ key ] : '';
		},
		/**
		 * Remove all children of model from storage.
		 * Will remove children of children models too.
		 * @param parent - model which is parent
		 */
		removeChildren: function ( parent ) {
			var models = vc.shortcodes.where( { parent_id: parent.id } );
			_.each( models, function ( model ) {
				vc.storage.lock();
				model.destroy();
				this.removeChildren( model );
			}, this );
			if ( models.length ) {
				vc.storage.save();
			}
		},
		setting: function ( name ) {
			if ( false === this.settings ) {
				this.settings = vc.getMapped( this.get( 'shortcode' ) ) || {};
			}
			return this.settings[ name ];
		}
	} );

	Shortcodes = vc.shortcodes_collection = Backbone.Collection.extend( {
		model: vc.shortcode,
		last_index: 0,
		getNextOrder: function () {
			return this.last_index ++;
		},
		comparator: function ( model ) {
			return model.get( 'order' );
		},
		initialize: function () {
		},
		/**
		 * Create new models from shortcode string.
		 * @param shortcodes_string - string of shortcodes.
		 * @param parent_model - parent shortcode model for parsed objects from string.
		 */
		createFromString: function ( shortcodes_string, parent_model ) {
			var data;
			data = vc.storage.parseContent(
				{},
				shortcodes_string,
				_.isObject( parent_model ) ? parent_model.toJSON() : false
			);
			_.each( _.values( data ), function ( model ) {
				vc.shortcodes.create( model );
			}, this );
		},
		/**
		 * Synchronize data with our storage.
		 * @param method
		 * @param model
		 * @param options
		 */
		sync: function ( method, model, options ) {
			var response;
			// Select action to do with data in you storage
			switch ( method ) {
				case "read":
					response = model.id ? store.find( model ) : store.findAll();
					break;
				case "create":
					response = store.create( model );
					break;
				case "update":
					response = store.update( model );
					break;
				case "delete":
					response = store.destroy( model );
					break;
			}
			// Response
			if ( response ) {
				options.success( response );
			} else {
				options.error( "Record not found" );
			}
		},
		stringify: function ( state ) {
			return this.modelsToString( _.sortBy( vc.shortcodes.where( { parent_id: false } ), function ( model ) {
				return model.get( 'order' );
			} ), state );
		},
		modelsToString: function ( models, state ) {
			var string = '';
			_.each( models, function ( model ) {
				var data, tag, params, content, mergedParams, paramsForString;

				tag = model.get( 'shortcode' );
				params = _.extend( {}, model.get( 'params' ) );
				paramsForString = {};
				mergedParams = vc.getMergedParams( tag, params );
				_.each( mergedParams, function ( value, key ) {
					if ( 'content' !== key ) {
						paramsForString[ key ] = vc.storage.escapeParam( value );
					}
				}, this );
				content = _.isString( params.content ) ? params.content : '';
				content += this.modelsToString( _.sortBy( vc.shortcodes.where( { parent_id: model.get( 'id' ) } ),
					function ( model ) {
						return model.get( 'order' );
					} ), state );
				var mapped = vc.getMapped( tag );
				data = {
					tag: tag,
					attrs: paramsForString,
					content: content,
					type: _.isUndefined( vc.getParamSettings( tag,
						'content' ) ) && ! mapped.is_container && _.isEmpty( mapped.as_parent ) ? 'single' : ''
				};
				if ( _.isUndefined( state ) ) {
					model.trigger( 'stringify', model, data );
				} else {
					model.trigger( 'stringify:' + state, model, data );
				}
				string += wp.shortcode.string( data );
			}, this );
			return string;
		}
	} );

	vc.shortcodes = new vc.shortcodes_collection();

	vc.getDefaults = vc.memoizeWrapper( function ( tag ) {
		var defaults, params;

		defaults = {};
		params = _.isObject( vc.map[ tag ] ) && _.isArray( vc.map[ tag ].params ) ? vc.map[ tag ].params : [];
		_.each( params, function ( param ) {
			if ( _.isObject( param ) ) {
				if ( ! _.isUndefined( param.std ) ) {
					defaults[ param.param_name ] = param.std;
				} else if ( ! _.isUndefined( param.value ) ) {
					if ( vc.atts[ param.type ] && vc.atts[ param.type ].defaults ) {
						defaults[ param.param_name ] = vc.atts[ param.type ].defaults( param );
					} else if ( _.isObject( param.value ) ) {
						defaults[ param.param_name ] = _.values( param.value )[ 0 ];
					} else if ( _.isArray( param.value ) ) {
						defaults[ param.param_name ] = param.value[ 0 ];
					} else {
						defaults[ param.param_name ] = param.value;
					}
				}
			}
		} );

		return defaults;
	} );

	vc.getDefaultsAndDependencyMap = vc.memoizeWrapper( function ( tag ) {
		var defaults, dependencyMap, params;
		dependencyMap = {};
		defaults = {};
		params = _.isObject( vc.map[ tag ] ) && _.isArray( vc.map[ tag ].params ) ? vc.map[ tag ].params : [];

		_.each( params, function ( param ) {
			if ( _.isObject( param ) && 'content' !== param.param_name ) {
				// Building defaults
				if ( ! _.isUndefined( param.std ) ) {
					defaults[ param.param_name ] = param.std;
				} else if ( ! _.isUndefined( param.value ) ) {
					if ( vc.atts[ param.type ] && vc.atts[ param.type ].defaults ) {
						defaults[ param.param_name ] = vc.atts[ param.type ].defaults( param );
					} else if ( _.isObject( param.value ) ) {
						defaults[ param.param_name ] = _.values( param.value )[ 0 ];
					} else if ( _.isArray( param.value ) ) {
						defaults[ param.param_name ] = param.value[ 0 ];
					} else {
						defaults[ param.param_name ] = param.value;
					}
				}
				// Building dependency map
				if ( ! _.isUndefined( param.dependency ) && ! _.isUndefined( param.dependency.element ) ) {
					// We can only hook dependency to exact element value
					dependencyMap[ param.param_name ] = param.dependency;
				}
			}
		} );

		return { defaults: defaults, dependencyMap: dependencyMap };
	} );

	vc.getMergedParams = function ( tag, values ) {
		var paramsMap, outputParams, paramsDependencies;
		paramsMap = vc.getDefaultsAndDependencyMap( tag );
		outputParams = {};

		// Make all values extended from default
		values = _.extend( {}, paramsMap.defaults, values );
		paramsDependencies = _.extend( {}, paramsMap.dependencyMap );
		_.each( values, function ( value, key ) {
			if ( 'content' !== key ) {
				var paramSettings;

				// checking dependency
				if ( ! _.isUndefined( paramsDependencies[ key ] ) ) {
					// now we know that param has dependency, so we must check is it satisfy a statement
					if ( ! _.isUndefined( paramsDependencies[ paramsDependencies[ key ].element ] ) && _.isBoolean( paramsDependencies[ paramsDependencies[ key ].element ].failed ) && true === paramsDependencies[ paramsDependencies[ key ].element ].failed ) {
						paramsDependencies[ key ].failed = true;
						return; // in case if we already failed a dependency (a-b-c)
					}
					var rules, isDependedEmpty, dependedElement, dependedValue;
					dependedElement = paramsDependencies[ key ].element;
					dependedValue = values[ dependedElement ];
					isDependedEmpty = _.isEmpty( dependedValue );

					rules = _.omit( paramsDependencies[ key ], 'element' );
					if (
						(
							// check rule 'not_empty'
						_.isBoolean( rules.not_empty ) && true === rules.not_empty && isDependedEmpty
						) ||
						(
							// check rule 'is_empty'
						_.isBoolean( rules.is_empty ) && true === rules.is_empty && ! isDependedEmpty
						) ||
						(
							// check rule 'value'
						rules.value && ! _.intersection( (
								_.isArray( rules.value ) ? rules.value : [ rules.value ]),
							(_.isArray( dependedValue ) ? dependedValue : [ dependedValue ] )
						).length
						) ||
						(
							// check rule 'value_not_equal_to'
						rules.value_not_equal_to && _.intersection( (
								_.isArray( rules.value_not_equal_to ) ? rules.value_not_equal_to : [ rules.value_not_equal_to ] ),
							(_.isArray( dependedValue ) ? dependedValue : [ dependedValue ])
						).length
						)
					) {
						paramsDependencies[ key ].failed = true;
						return; // some of these rules doesn't satisfy so just exit
					}
				}
				// now check for defaults if not deleted already
				paramSettings = vc.getParamSettings( tag, key );

				if ( _.isUndefined( paramSettings ) ) {
					outputParams[ key ] = value;
					// this means that param is not mapped
					// so maybe it is can be used somewhere in other place.
					// We need to save it anyway. #93627986
				} else if (
					( // add value if it is not same as default
					! _.isUndefined( paramsMap.defaults[ key ] ) && paramsMap.defaults[ key ] !== value
					) || (
						// or if no defaults exists -> add value if it is not empty
					_.isUndefined( paramsMap.defaults[ key ] ) && '' !== value
					) || (
						// Or it is required to save always
					! _.isUndefined( paramSettings.save_always ) && true === paramSettings.save_always )
				) {
					outputParams[ key ] = value;
				}
			}
		} );

		return outputParams;
	};

	vc.getParamSettings = vc.memoizeWrapper( function ( tag, paramName ) {
		var params, paramSettings;

		params = _.isObject( vc.map[ tag ] ) && _.isArray( vc.map[ tag ].params ) ? vc.map[ tag ].params : [];
		paramSettings = _.find( params, function ( settings ) {
			return _.isObject( settings ) && settings.param_name === paramName;
		}, this );
		return paramSettings;
	}, function () {
		return arguments[ 0 ] + ',' + arguments[ 1 ];
	} );

	vc.getParamSettingsByType = vc.memoizeWrapper( function ( tag, paramType ) {
		var params, paramSettings;

		params = _.isObject( vc.map[ tag ] ) && _.isArray( vc.map[ tag ].params ) ? vc.map[ tag ].params : [];
		paramSettings = _.find( params, function ( settings ) {
			return _.isObject( settings ) && settings.type === paramType;
		}, this );
		return paramSettings;
	}, function () {
		return arguments[ 0 ] + ',' + arguments[ 1 ];
	} );

	/**
	 * Checks if given shortcode has el_id param
	 */
	vc.shortcodeHasIdParam = vc.memoizeWrapper( function ( tag ) {
		return vc.getParamSettingsByType( tag, 'el_id' );
	} );

})( window.jQuery );
