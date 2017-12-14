/**
 * Created by dan bilauca on 7/14/2016.
 * Util functions/plugins for backbone
 */

var TD_NM = TD_NM || {};
TD_NM.util = TD_NM.util || {};

(function ( $ ) {
	TD_NM.util.camel_case = function ( string ) {
		var chunks = string.split( '_' ),
			result = '';
		$.each( chunks, function ( index, value ) {
			result += value.toLowerCase().charAt( 0 ).toUpperCase() + value.slice( 1 );
		} );

		return result;
	};

	/**
	 * Sprintf js version
	 * @param string
	 * @param {...*} args
	 * @returns {*}
	 */
	TD_NM.util.printf = function ( string, args ) {
		if ( ! args ) {
			return string;
		}

		var is_array = args instanceof Array;

		if ( ! is_array ) {
			args = [args];
		}

		_.each(
			args, function ( replacement ) {
				string = string.replace( "%s", replacement );
			}
		);

		return string;
	};

	/**
	 * Returns the correct form of translated string
	 * based on count number
	 *
	 * @param {String} single
	 * @param {String} plural
	 * @param {Int} count
	 *
	 * @returns {String}
	 */
	TD_NM.util.plural = function ( single, plural, count ) {
		return count == 1 ? ThriveUlt.util.printf( single, count ) : ThriveUlt.util.printf( plural, count );
	};

	/**
	 * binds all form elements on a view
	 * Form elements must have a data-bind attribute which should contain the field name from the model
	 * composite fields are not supported
	 *
	 * this will bind listeners on models and on the form elements
	 *
	 * @param {Backbone.View} view
	 * @param {Backbone.Model} [model] optional, it will default to the view's model
	 */
	TD_NM.util.data_binder = function ( view, model ) {

		if ( typeof model === 'undefined' ) {
			model = view.model;
		}

		if ( ! model instanceof Backbone.Model ) {
			return;
		}

		/**
		 * separate value by input type
		 *
		 * @param {object} $input jquery
		 * @returns {*}
		 */
		function value_getter( $input ) {
			if ( $input.is( ':checkbox' ) ) {
				return $input.is( ':checked' ) ? true : false;
			}
			if ( $input.is( ':radio' ) ) {
				return $input.is( ':checked' ) ? $input.val() : '';
			}

			return $input.val();
		}

		/**
		 * separate setter vor values based on input type
		 *
		 * @param {object} $input jquery object
		 * @param {*} value
		 * @returns {*}
		 */
		function value_setter( $input, value ) {
			if ( $input.is( ':radio' ) ) {
				return view.$el.find( 'input[name="' + $input.attr( 'name' ) + '"]:radio' ).filter( '[value="' + value + '"]' ).prop( 'checked', true );
			}
			if ( $input.is( ':checkbox' ) ) {
				return $input.prop( 'checked', value ? true : false );
			}

			return $input.val( value );
		}

		/**
		 * iterate through each of the elements and bind change listeners on DOM and on the model
		 */
		var $elements = view.$el.find( '[data-bind]' ).each(
			function () {

				var $this = $( this ),
					prop = $this.attr( 'data-bind' ),
					_dirty = false;

				$this.on(
					'change', function () {
						var _value = value_getter( $this );
						if ( model.get( prop ) != _value ) {
							_dirty = true;
							model.set( prop, _value )
							_dirty = false;
						}
					}
				);

				view.listenTo(
					model, 'change:' + prop, function () {
						if ( ! _dirty ) {
							value_setter( $this, this.model.get( prop ) );
						}
					}
				);
			}
		);

		/**
		 * if a model defines a validate() function, it should return an array of binds in the form of:
		 *      ['post_title']
		 * this will add error classes to the bound dom elements
		 */
		view.listenTo(
			model, 'invalid', function ( model, error ) {
				if ( _.isArray( error ) ) {
					_.each(
						error, function ( field ) {
							var _field = field;
							if ( field.field ) { // if this is an object, we need to use the field property
								_field = field.field
							}
							var $target = $elements.filter( '[data-bind="' + _field + '"]' ).first().addClass( 'tvd-validate tvd-invalid' ).focus();
							if ( field.message ) {
								$target.siblings( 'label' ).attr( 'data-error', field.message );
							}
							if ( $target.is( ':radio' ) || $target.is( ':checkbox' ) ) {
								TVE_Dash.err( $target.next( 'label' ).attr( 'data-error' ) );
							}
						}
					);
				} else if ( _.isString( error ) ) {
					TVE_Dash.err( error );
				}
			}
		);
	};

	/**
	 * Email validator
	 *
	 * @param string
	 * @returns {boolean}
	 */
	TD_NM.util.is_email = function ( string ) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test( string );
	};

	/**
	 * pre-process the ajaxurl admin js variable and append a querystring to it
	 * some plugins are adding an extra parameter to the admin-ajax.php url. Example: admin-ajax.php?lang=en
	 *
	 * @param {string} [query_string] optional, query string to be appended
	 */
	TD_NM.util.ajaxurl = function ( query_string ) {
		var _q = ajaxurl.indexOf( '?' ) !== - 1 ? '&' : '?';
		if ( ! query_string || ! query_string.length ) {
			return ajaxurl + _q + '_nonce=' + TD_NM.admin_nonce;
		}
		query_string = query_string.replace( /^(\?|&)/, '' );
		query_string += '&_nonce=' + TD_NM.admin_nonce;

		return ajaxurl + _q + query_string;
	};

})( jQuery );;/**
 * Created by dan bilauca on 7/11/2016.
 * Notification Manager Models
 */

var TD_NM = TD_NM || {};
TD_NM.models = TD_NM.models || {};
TD_NM.collections = TD_NM.collections || {};

(function ( $ ) {

	/**
	 * Sets Backbone to emulate HTTP requests for models
	 * HTTP_X_HTTP_METHOD_OVERRIDE set to PUT|POST|PATH|DELETE|GET
	 *
	 * @type {boolean}
	 */
	Backbone.emulateHTTP = true;

	/**
	 * Base Model
	 */
	TD_NM.models.Base = Backbone.Model.extend( {
		idAttribute: 'ID',
		defaults: {
			ID: ''
		},
		validation_error: function ( field, message ) {
			return {
				field: field,
				message: message
			};
		},
		url: function () {
			var url = TD_NM.util.ajaxurl( this.get_action() + '&' + this.get_route() );

			if ( $.isNumeric( this.get( 'ID' ) ) ) {
				url += '&ID=' + this.get( 'ID' );
			}

			return url;
		},
		get_action: function () {
			return 'action=td_nm_admin_controller';
		},
		get_route: function () {
			return 'route=no_route';
		}
	} );

	/**
	 * Base Collection
	 */
	TD_NM.collections.Base = Backbone.Collection.extend( {
		/**
		 * helper function to get the last item of a collection
		 *
		 * @return Backbone.Model
		 */
		last: function () {
			return this.at( this.size() - 1 );
		}
	} );

	/**
	 * Option Model
	 */
	TD_NM.models.Option = TD_NM.models.Base.extend( {
		defaults: {
			value: null,
			label: ''
		}
	} );

	/**
	 * Options Collections
	 */
	TD_NM.collections.Options = TD_NM.collections.Base.extend( {
		model: TD_NM.models.Option
	} );

	/**
	 * Message Model
	 */
	TD_NM.models.Message = TD_NM.models.Base.extend( {
		defaults: {
			subject: '',
			content: ''
		},
		validate: function ( args ) {
			var errors = [];

			if ( args.subject.length === 0 ) {
				errors.push( this.validation_error( 'subject', TD_NM.t.subject_empty ) );
			}

			if ( args.content.length === 0 ) {
				errors.push( this.validation_error( 'content', TD_NM.t.message_empty ) );
			}

			if ( errors.length ) {
				return errors;
			}
		}
	} );

	/**
	 * Action Model
	 */
	TD_NM.models.Action = TD_NM.models.Base.extend( {
		defaults: _.extend( {}, TD_NM.models.Base.prototype.defaults, {
			type: null
		} ),
		get_route: function () {
			return 'route=action';
		},
		url: function () {
			var url = TD_NM.util.ajaxurl( this.get_action() + '&' + this.get_route() );

			if ( $.isNumeric( this.get( 'ID' ) ) ) {
				url += '&ID=' + this.get( 'ID' );
			}

			if ( $.isNumeric( this.get( 'notification_id' ) ) ) {
				url += '&notification_id=' + this.get( 'notification_id' );
			}

			return url;
		}
	} );

	/**
	 * Action Complex Model
	 */
	TD_NM.models.ActionComplex = TD_NM.models.Action.extend( {
		defaults: _.extend( {}, TD_NM.models.Action.prototype.defaults, {
			url: null
		} ),
		initialize: function ( attrs ) {
			this.set( 'message', new TD_NM.models.Message( attrs && attrs.message ? attrs.message : {} ) );
			this.set( 'recipients', new TD_NM.collections.Options( attrs && attrs.recipients ? attrs.recipients : [] ) );
		},
		validate: function ( attrs ) {
			var errors = [];

			this['validate_' + this.get( 'type' )]( errors, attrs );

			if ( errors.length ) {
				return errors;
			}
		},
		validate_custom_script: function ( errors, attrs ) {

			if ( ! attrs.url ) {
				errors.push( this.validation_error( 'url', TD_NM.t.invalid_url ) );
			}

			if ( errors.length ) {
				return errors;
			}
		},
		validate_send_email_notification: function ( errors, attrs ) {
			var recipients = this.get( 'recipients' );

			if ( recipients && recipients.length === 0 ) {
				errors.push( TD_NM.t.no_recipient );
				return errors;
			}

			recipients.each( function ( model ) {
				if ( ! TD_NM.util.is_email( model.get( 'value' ) ) ) {
					errors.push( TD_NM.t.invalid_recipient );
				}
			} );

			if ( errors.length ) {
				return errors;
			}

			var message_errors = this.get( 'message' ).validate( this.get( 'message' ).attributes );
			if ( message_errors instanceof Array ) {
				_.each( message_errors, function ( error, index ) {
					errors.push( error );
				} );
			}

			if ( errors.length ) {
				return errors;
			}
		},
		validate_wordpress_notification: function ( errors, attrs ) {
			var message_errors = this.get( 'message' ).validate( this.get( 'message' ).attributes );
			if ( message_errors instanceof Array ) {
				_.each( message_errors, function ( error, index ) {
					if ( error.field === 'subject' ) {
						return;
					}
					errors.push( error );
				} );
			}
		}
	} );

	/**
	 * Collection of Complex Action
	 */
	TD_NM.collections.ActionsComplex = TD_NM.collections.Base.extend( {
		model: TD_NM.models.ActionComplex
	} );

	/**
	 * Actions Collection
	 */
	TD_NM.collections.Actions = TD_NM.collections.Base.extend( {
		model: function ( model, options ) {
			switch ( model.type ) {
				case 'send_email_notification':
					return new TD_NM.models.ActionSendEmailNotification( model, options );
				case 'custom_script':
					return new TD_NM.models.ActionCustomScript( model, options );
				default:
					return new TD_NM.models.Action( model );
			}
		}
	} );

	/**
	 * Action Types Collection
	 */
	TD_NM.collections.ActionTypes = TD_NM.collections.Base.extend( {
		model: TD_NM.models.Base.extend( {
			defaults: {
				key: '',
				label: ''
			}
		} )
	} );

	/**
	 * Action Custom Script Model
	 */
	TD_NM.models.ActionCustomScript = TD_NM.models.Action.extend( {
		defaults: _.extend( {}, TD_NM.models.Action.prototype.defaults, {
			type: 'custom_script',
			url: null
		} ),
		validate: function ( attrs ) {
			var errors = [];

			if ( ! attrs.url ) {
				errors.push( this.validation_error( 'url', TD_NM.t.invalid_url ) );
			}

			if ( errors.length ) {
				return errors;
			}
		}
	} );

	/**
	 * Action Send Email Model
	 */
	TD_NM.models.ActionSendEmailNotification = TD_NM.models.Action.extend( {
		defaults: _.extend( {}, TD_NM.models.Action.prototype.defaults, {
			type: 'send_email_notification'
		} ),
		initialize: function ( attrs, options ) {
			this.set( 'message', new TD_NM.models.Message( attrs && attrs.message ? attrs.message : {} ) );
			this.set( 'recipients', new TD_NM.collections.Options( attrs && attrs.recipients ? attrs.recipients : {} ) );
		},
		validate: function () {
			var errors = [],
				recipients = this.get( 'recipients' );

			if ( recipients && recipients.length === 0 ) {
				errors.push( TD_NM.t.no_recipient );
				return errors;
			}

			recipients.each( function ( model ) {
				if ( ! TD_NM.util.is_email( model.get( 'value' ) ) ) {
					errors.push( TD_NM.t.invalid_recipient );
				}
			} );

			if ( errors.length ) {
				return errors;
			}

			var message_errors = this.get( 'message' ).validate( this.get( 'message' ).attributes );
			if ( message_errors instanceof Array ) {
				_.each( message_errors, function ( error, index ) {
					errors.push( error );
				} );
			}

			if ( errors.length ) {
				return errors;
			}
		}
	} );

	/**
	 * BreadcrumbLink Model
	 */
	TD_NM.models.BreadcrumbLink = TD_NM.models.Base.extend( {
		defaults: _.extend( {}, TD_NM.models.Base.prototype.defaults, {
			hash: '',
			label: '',
			full_link: false
		} ),
		/**
		 * we pass only hash and label, and build the ID based on the label
		 *
		 * @param {object} attrs
		 */
		initialize: function ( attrs ) {
			if ( attrs && ! this.get( 'ID' ) ) {
				this.set( 'ID', attrs.label.split( ' ' ).join( '' ).toLowerCase() );
			}
			if ( attrs ) {
				this.set( 'full_link', attrs.hash.match( /^http/ ) );
			}
		},
		/**
		 * @returns {String}
		 */
		get_url: function () {
			return this.get( 'full_link' ) ? this.get( 'hash' ) : ( '#' + this.get( 'hash' ));
		}
	} );

	/**
	 * Breadcrumbs Collection
	 */
	TD_NM.collections.Breadcrumbs = TD_NM.collections.Base.extend( {
		model: TD_NM.models.Base.extend( {
			defaults: {
				hash: '',
				label: ''
			}
		} ),
		/**
		 * helper function allows adding items to the collection easier
		 *
		 * @param {string} route
		 * @param {string} label
		 */
		add_page: function ( route, label ) {
			var _model = new TD_NM.models.BreadcrumbLink( {
				hash: route,
				label: label
			} );
			return this.add( _model );
		}
	} );

	/**
	 * Checkbox Option Model
	 */
	TD_NM.models.OptionCheckbox = TD_NM.models.Option.extend( {
		defaults: _.extend( {}, TD_NM.models.Option.prototype.defaults, {
			selected: false
		} )
	} );

	/**
	 * Checkbox Options Collections
	 */
	TD_NM.collections.OptionsCheckbox = TD_NM.collections.Options.extend( {
		model: TD_NM.models.OptionCheckbox,
		match_selected: function ( collection ) {
			var _self = this;
			collection.each( function ( model ) {
				var temp = _self.findWhere( {value: model.get( 'value' )} );
				if ( temp ) {
					temp.set( 'selected', true, {silent: true} );
				}
			} );
		}
	} );

	/**
	 * Input Option Model
	 */
	TD_NM.models.OptionInput = TD_NM.models.Option.extend( {
		defaults: _.extend( {}, TD_NM.models.Option.prototype.defaults, {
			selected: false
		} )
	} );

	/**
	 * Input Options Collections
	 */
	TD_NM.collections.OptionsInput = TD_NM.collections.Options.extend( {
		model: TD_NM.models.OptionInput
	} );

	/**
	 * Trigger Model
	 */
	TD_NM.models.Trigger = TD_NM.models.Base.extend( {
		defaults: {
			type: null,
			settings: {}
		},
		get_route: function () {
			return 'route=trigger';
		}
	} );

	/**
	 * Notification Model
	 */
	TD_NM.models.Notification = TD_NM.models.Base.extend( {
		defaults: _.extend( {}, TD_NM.models.Base.prototype.defaults, {} ),
		initialize: function ( args ) {
			this.set( 'trigger', new TD_NM.models.Trigger( args && args.trigger ? args.trigger : {} ) );
			this.set( 'actions', new TD_NM.collections.ActionsComplex( args && args.actions ? args.actions : [] ) );
		},
		get_route: function () {
			return 'route=notification';
		}
	} );

	/**
	 * Notifications Collection
	 */
	TD_NM.collections.Notifications = TD_NM.collections.Base.extend( {
		model: TD_NM.models.Notification
	} );

	/**
	 * Trigger Types Collection
	 */
	TD_NM.collections.TriggerTypes = TD_NM.collections.Base.extend( {
		model: TD_NM.models.Base.extend( {
			defaults: {
				key: '',
				label: ''
			}
		} )
	} );

	/**
	 * Email Service Model
	 */
	TD_NM.models.EmailService = TD_NM.models.Base.extend( {
		defaults: {
			key: null,
			title: '',
			connected: false,
			active: false,
			status: ''
		}
	} );

	/**
	 * Email Services Collection
	 */
	TD_NM.collections.EmailServices = TD_NM.collections.Base.extend( {
		model: TD_NM.models.EmailService
	} );

})( jQuery );
;/**
 * Created by dan bilauca on 7/12/2016.
 * Notification Manager Modal views
 */

var TD_NM = TD_NM || {};
TD_NM.views = TD_NM.views || {};

(function ( $ ) {

	$( function () {

		/**
		 * Modal Steps View
		 */
		TD_NM.views.ModalSteps = TVE_Dash.views.Modal.extend( {
			stepClass: '.tvd-nm-modal-step',
			currentStep: 0,
			$step: null,
			events: {
				'click .tvd-nm-modal-next-step': "next",
				'click .tvd-nm-modal-prev-step': "prev"
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 0 );
				return this;
			},
			gotoStep: function ( index ) {
				var step = this.steps.hide().eq( index ).show(),
					self = this;
				this.$step = step;
				setTimeout( function () {
					self.input_focus( step );
				}, 50 );

				this.currentStep = index;

				return this;
			},
			next: function () {
				if ( this.beforeNext() === false ) {
					return;
				}
				this.gotoStep( this.currentStep + 1 );
				this.afterNext();
			},
			prev: function () {
				this.beforePrev();
				this.gotoStep( this.currentStep - 1 );
				this.afterPrev();
			},
			beforeNext: function () {
				return this;
			},
			afterNext: function () {
				return this;
			},
			beforePrev: function () {
				return this;
			},
			afterPrev: function () {
				return this;
			}
		} );

		/**
		 * Modal Create Notification
		 */
		TD_NM.views.ModalCreateNotification = TD_NM.views.ModalSteps.extend( {
			className: 'tvd-modal-fixed-footer tvd-modal',
			template: TVE_Dash.tpl( 'modals/create-notification' ),
			summary_view: null,
			events: function () {
				return _.extend( TD_NM.views.ModalSteps.prototype.events, {
					// selects the card from the event and deselect the other cards
					'click #tvd-nm-trigger-types .tvd-nm-trigger-type': '_select_trigger_type',
					'click #tvd-nm-action-types .tvd-nm-action-type': '_select_action_type',
					'click .tvd-modal-submit': 'save'
				} );
			},
			afterInitialize: function () {
				this.data.trigger_types.on( 'change:selected', this._render_trigger_settings, this );
				this.data.action_types.on( 'change:selected', this._render_action_settings, this );
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 0 );

				this._render_trigger_types();
				this._render_action_types();

				return this;
			},
			_render_summary: function () {
				this.summary_view = new TD_NM.views.Summary( {
					collection: new TD_NM.collections.Base( this.model.get( 'trigger' ) )
				} );

				this.$( '#tvd-nm-notification-summary' ).html( this.summary_view.render().$el );
			},
			_render_trigger_types: function () {
				this.data.trigger_types.each( function ( model ) {
					var _template = TVE_Dash.tpl( 'trigger-type-box' );
					this.$( '#tvd-nm-trigger-types' ).append( _template( {item: model} ) );
				}, this );
			},
			_render_action_types: function () {
				this.data.action_types.each( function ( model ) {
					var _template = TVE_Dash.tpl( 'action-type-box' );
					this.$( '#tvd-nm-action-types' ).append( _template( {item: model} ) );
				}, this );
			},
			_select_trigger_type: function ( event ) {
				var $element = $( event.currentTarget );

				if ( $element.find( '.tvd-card' ).is( '.tvd-nm-selected-card' ) ) {
					return;
				}

				this.$( '#tvd-nm-trigger-types .tvd-nm-selected-card' ).removeClass( 'tvd-nm-selected-card' );
				$element.find( '.tvd-card' ).addClass( 'tvd-nm-selected-card' );
				this.data.trigger_types.forEach( function ( model ) {
					model.set( 'selected', false, {silent: true} );
				} );
				this.data.trigger_types.findWhere( {key: $element.data( 'trigger-type' )} ).set( 'selected', true );
			},
			_select_action_type: function ( event ) {
				var $element = $( event.currentTarget );

				if ( $element.find( '.tvd-card' ).is( '.tvd-nm-selected-card' ) ) {
					return;
				}

				this.$( '#tvd-nm-action-types .tvd-nm-selected-card' ).removeClass( 'tvd-nm-selected-card' );
				$element.find( '.tvd-card' ).addClass( 'tvd-nm-selected-card' );
				this.data.action_types.forEach( function ( model ) {
					model.set( 'selected', false, {silent: true} );
				} );
				this.data.action_types.findWhere( {key: $element.data( 'action-type' )} ).set( 'selected', true );
			},
			_render_trigger_settings: function ( trigger_type ) {
				var view_name = TD_NM.util.camel_case( trigger_type.get( 'key' ) );

				this.model.get( 'trigger' ).set( 'type', trigger_type.get( 'key' ), {silent: true} );

				if ( this.trigger_settings_view ) {
					this.trigger_settings_view.remove();
				}

				var $settings = this.$( '#tvd-nm-trigger-settings' ).html( '' );

				this.trigger_settings_view = new TD_NM.views['Settings' + view_name]( {
					model: this.model
				} );

				$settings.html( this.trigger_settings_view.render().$el );
			},
			_render_action_settings: function ( action_type ) {
				var view_name = TD_NM.util.camel_case( action_type.get( 'key' ) ),
					action_model = new TD_NM.models.ActionComplex( {
						type: action_type.get( 'key' )
					} );

				if ( this.action_settings_view ) {
					this.action_settings_view.remove();
				}

				this.action_settings_view = new TD_NM.views['Settings' + view_name]( {
					model: action_model,
					trigger: this.model.get( 'trigger' )
				} );

				this.model.get( 'actions' ).reset( action_model );

				this.$( '#tvd-nm-action-settings' ).html( this.action_settings_view.render().$el );
			},
			/**
			 * Read the settings before moving to next step
			 *
			 * @returns {boolean}
			 */
			beforeNext: function () {
				/**
				 * if is trigger settings step
				 * validate the step and save the setting into model
				 */
				if ( this.currentStep === 0 ) {
					if ( ! this.trigger_settings_view.are_valid() ) {
						TVE_Dash.err( TD_NM.t.next_step_no_option_selected );

						return false;
					}
					this.model.get( 'trigger' ).set( 'settings', this.trigger_settings_view.get_settings() );

					this._render_summary();

					return true;
				}

				return true;
			},
			afterNext: function () {
				if ( this.currentStep !== 1 ) {
					return this;
				}
			},
			beforePrev: function () {
				this.summary_view.remove();
			},
			save: function () {
				this.tvd_nm_clear_errors();

				if ( this.model.get( 'actions' ).length === 0 ) {
					return TVE_Dash.err( TD_NM.t.select_action );
				}

				if ( ! this.model.get( 'actions' ).last().isValid() ) {
					return this.tvd_nm_show_errors( this.model.get( 'actions' ).last() );
				}
				TVE_Dash.showLoader();

				var notification_model = this.model,
					self = this;

				notification_model.save().done( function ( model_id ) {
					notification_model.set( 'ID', model_id );
					notification_model.get( 'actions' ).last().set( 'notification_id', model_id ).set( 'ID', 0 );
					self.collection.add( notification_model );
					self.close();
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					TVE_Dash.hideLoader();
				} );
			}
		} );

		/**
		 * Add action to notification view
		 */
		TD_NM.views.ModalAddAction = TD_NM.views.ModalCreateNotification.extend( {
			template: TVE_Dash.tpl( 'modals/add-action' ),
			afterInitialize: function () {
				this.data.action_types.on( 'change:selected', this._render_action_settings, this );
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 1 );

				this._render_summary();
				this._render_action_types();
				return this;
			},
			_render_action_types: function () {

				this.data.action_types.each( function ( model ) {

					var selected_model = this.collection.findWhere( {type: model.get( 'key' )} );
					if ( selected_model ) {
						model.set( 'disable', true, {silent: true} );
					}

					var _template = TVE_Dash.tpl( 'action-type-box' );
					this.$( '#tvd-nm-action-types' ).append( _template( {item: model} ) );
				}, this );
			},
			_select_action_type: function ( event ) {
				var $element = $( event.currentTarget );

				if ( $element.find( '.tvd-card' ).is( '.tvd-nm-selected-card' ) ) {
					return;
				}

				this.$( '#tvd-nm-action-types .tvd-nm-selected-card' ).removeClass( 'tvd-nm-selected-card' );
				$element.find( '.tvd-card' ).addClass( 'tvd-nm-selected-card' );

				this.data.action_types.forEach( function ( model ) {
					model.set( 'selected', false, {silent: true} );
				} );

				this.data.action_types.findWhere( {key: $element.data( 'action-type' )} ).set( 'selected', true );
			},
			_render_action_settings: function ( action_type ) {
				var view_name = TD_NM.util.camel_case( action_type.get( 'key' ) );

				this.model.set( 'type', action_type.get( 'key' ), {silent: true} );
				this.model.set( 'notification_id', this.data.notification_model.get( 'ID' ), {silent: true} );

				if ( this.action_settings_view ) {
					this.action_settings_view.remove();
				}

				/**
				 * when user switches between action types we need to reset the message model because it has different default content
				 * WP action has defaults message
				 * Send email action does not have any default message content
				 */
				this.model.get( 'message' ).set( 'content', '', {silent: true} );

				this.action_settings_view = new TD_NM.views['Settings' + view_name]( {
					model: this.model,
					trigger: this.data.notification_model.get( 'trigger' )
				} );

				this.$( '#tvd-nm-action-settings' ).html( this.action_settings_view.render().$el );

				TVE_Dash.materialize( this.$el );
			},
			_render_summary: function () {
				var arr = [];
				arr.push( this.data.notification_model.get( 'trigger' ) );
				arr = arr.concat( this.data.notification_model.get( 'actions' ).models );
				this.summary_view = new TD_NM.views.Summary( {
					collection: new TD_NM.collections.Base( arr )
				} );

				this.$( '#tvd-nm-notification-summary' ).html( this.summary_view.render().$el );

			},
			save: function () {
				this.tvd_nm_clear_errors();
				var self = this;
				if ( ! this.model.isValid() ) {
					return this.tvd_nm_show_errors( this.model );
				}

				TVE_Dash.showLoader();

				this.model.save().done( function ( action_ID ) {
					TVE_Dash.success( TD_NM.t.action_saved );
					self.model.set( 'ID', action_ID, {silent: true} );
					self.collection.push( self.model );
					self.close();
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					TVE_Dash.hideLoader();
				} );
			}
		} );

		/**
		 * Modal Edit Action
		 */
		TD_NM.views.ModalEditAction = TD_NM.views.ModalCreateNotification.extend( {
			template: TVE_Dash.tpl( 'modals/edit-action' ),
			afterInitialize: function () {
				this.data.action_types.on( 'change:selected', this._render_action_settings, this );
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 1 );

				this._select_action_type( this.model.get( 'type' ) );

				return this;
			},
			_render_action_settings: function ( action_type ) {
				var view_name = TD_NM.util.camel_case( action_type.get( 'key' ) );

				this.model.set( 'type', action_type.get( 'key' ), {silent: true} );
				this.model.set( 'action_name', action_type.get( 'label' ), {silent: true} );
				this.model.set( 'ID', this.collection.indexOf( this.model ), {silent: true} );

				if ( this.action_settings_view ) {
					this.action_settings_view.remove();
				}

				this.action_settings_view = new TD_NM.views['Settings' + view_name]( {
					model: this.model,
					trigger: this.data.trigger
				} );

				this.$el.find( '.tvd-modal-title' ).text( TD_NM.util.printf( 'Edit %s', [this.model.get( 'action_name' )] ) );
				this.$( '#tvd-nm-action-settings' ).html( this.action_settings_view.render().$el );

				TVE_Dash.materialize( this.$el );
			},
			_select_action_type: function ( type ) {
				this.data.action_types.findWhere( {key: type} ).set( 'selected', true );

				this.action_model = this.data.action_types.findWhere( {key: type} );
			},
			save: function () {
				this.tvd_nm_clear_errors();

				if ( ! this.model.isValid() ) {
					return this.tvd_nm_show_errors( this.model );
				}

				TVE_Dash.showLoader();

				var model = this.action_settings_view.model,
					self = this;

				model.save().done( function () {
					TVE_Dash.success( TD_NM.t.action_saved );
					model.trigger( 'change', model );
					self.close();
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					TVE_Dash.hideLoader();
				} );
			}
		} );

		/**
		 * Modal Edit Trigger
		 */
		TD_NM.views.ModalEditTrigger = TD_NM.views.ModalCreateNotification.extend( {
			template: TVE_Dash.tpl( 'modals/edit-trigger' ),
			afterInitialize: function () {
				this.data.trigger_types.on( 'change:selected', this._render_trigger_settings, this );
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 0 );

				this._render_trigger_types();

				this.$( '#tvd-nm-trigger-types .tvd-nm-trigger-type[data-trigger-type="' + this.model.get( 'type' ) + '"]' ).trigger( 'click' );

				return this;
			},
			_render_trigger_types: function () {
				this.data.trigger_types.each( function ( model ) {
					/* Stop the user to change the trigger in edit mode */
					if ( this.model.get( 'type' ) == model.get( 'key' ) ) {
						var _template = TVE_Dash.tpl( 'trigger-type-box' );
						this.$( '#tvd-nm-trigger-types' ).append( _template( {item: model} ) );
					}
				}, this );
			},
			_render_trigger_settings: function ( trigger_type ) {
				var view_name = TD_NM.util.camel_case( trigger_type.get( 'key' ) );

				this.model.set( 'type', trigger_type.get( 'key' ), {silent: true} );

				if ( this.trigger_settings_view ) {
					this.trigger_settings_view.remove();
				}

				var $settings = this.$( '#tvd-nm-trigger-settings' ).html( '' ),
					view = new TD_NM.views['Settings' + view_name]( {
						model: this.model
					} );

				this.trigger_settings_view = view;

				$settings.html( view.render().$el );
			},
			save: function () {
				if ( ! this.trigger_settings_view.are_valid() ) {
					TVE_Dash.err( TD_NM.t.next_step_no_option_selected );

					return false;
				}
				var self = this;
				this.model.set( 'settings', this.trigger_settings_view.get_settings(), {silent: true} );

				TVE_Dash.showLoader();
				this.model.save().done( function () {
					TVE_Dash.success( TD_NM.t.trigger_saved );
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					self.close();
					TVE_Dash.hideLoader();
				} );
			}
		} );

		/**
		 * Modal Delete View
		 */
		TD_NM.views.ModalDelete = TVE_Dash.views.Modal.extend( {
			template: TVE_Dash.tpl( 'modals/delete-confirmation' ),
			events: {
				'click .tvd-nm-delete-yes': 'yes'
			},
			yes: function ( event ) {
				var $btn = $( event.currentTarget ), self = this;
				this.btnLoading( $btn );
				TVE_Dash.showLoader();
				this.model.destroy( {
					success: function () {
						TVE_Dash.success( TD_NM.t.success_delete );
					},
					error: function () {
						TVE_Dash.err( TD_NM.t.error_delete );
					},
					complete: function () {
						self.close();
						TVE_Dash.hideLoader();
					}
				} );
			}
		} );

		/**
		 * Modals Email Services
		 */
		TD_NM.views.ModalEmailServices = TD_NM.views.ModalSteps.extend( {
			template: TVE_Dash.tpl( 'modals/email-services' ),
			events: function () {
				return _.extend( TD_NM.views.ModalSteps.prototype.events, {
					'click .tvd-nm-activate-service': 'activate_connection',
					'click .tvd-nm-connection': 'connection_form',
					'click .tvd-nm-save-connection': 'connection_save',
					'click .tvd-nm-connection-test': 'connection_test'
				} );
			},
			afterRender: function () {
				this.steps = this.$el.find( this.stepClass ).hide();
				this.gotoStep( 0 );

				this.render_connected_services();

				return this;
			},
			render_connected_services: function () {
				var $wrapper = this.$( '#tvd-nm-connected-list' ).empty(),
					item_tpl = TVE_Dash.tpl( 'services/item' ),
					$text = this.$( '#tvd-nm-connections-text' );

				if ( this.collection.where( {connected: true} ).length === 0 ) {
					$text.html( TD_NM.t.no_connection_established );
				} else {
					$text.html( TD_NM.t.your_existing_connections );
				}

				this.collection.each( function ( model ) {
					if ( model.get( 'connected' ) !== true ) {
						return;
					}
					$wrapper.append( item_tpl( {item: model} ) );
				}, this );
			},
			activate_connection: function ( event ) {
				var self = this,
					$target = $( event.currentTarget ),
					key = $target.data( 'key' );

				TVE_Dash.showLoader();

				$.ajax( {
					type: 'post',
					url: TD_NM.util.ajaxurl(),
					data: {
						action: 'td_nm_admin_controller',
						route: 'activate_service',
						service: key
					}
				} ).success( function () {
					self.collection.each( function ( model ) {
						model.set( {active: model.get( 'key' ) === key}, {silent: true} );
					} );
				} ).error( function () {
					TVE_Dash.err( TD_NM.t.action_failed );
				} ).always( function () {
					TVE_Dash.hideLoader();
					self.render_connected_services();
				} );
			},
			beforePrev: function () {
				if ( this.currentStep === 2 ) {
					this._render_thumbs();
				}
			},
			beforeNext: function () {
				if ( this.currentStep === 0 ) {//render connection thumbnails
					this._render_thumbs();
				}
				return this;
			},
			_render_thumbs: function () {
				var $list = this.$( '#tvd-nm-connections-list' ).empty();
				var tpl = TVE_Dash.tpl( 'services/thumb-item' );
				this.collection.each( function ( model ) {
					$list.append( tpl( {item: model} ) );
				} );
			},
			connection_form: function ( event ) {
				this.gotoStep( 2 );

				var $target = $( event.currentTarget ),
					key = $target.data( 'key' ),
					$form = this.$( '#tvd-nm-connection-form' ).html( this.$( '.tvd-nm-connection-form[data-key="' + key + '"]' ).html() );

				this.key = key;
				$form.find( '.tvd-card-title, .tvd-card-action' ).hide();
			},
			connection_save: function () {
				TVE_Dash.showLoader();

				var self = this,
					$form_wrapper = this.$( '#tvd-nm-connection-form' ),
					$form = $form_wrapper.find( 'form' ),
					data = {
						action: 'td_nm_admin_controller',
						route: 'setup_connection',
						service: this.key
					};

				$.ajax( {
					type: 'post',
					url: TD_NM.util.ajaxurl( $form.serialize() ),
					data: data,
					dataType: 'json'
				} ).success( function () {
					var connection = self.collection.findWhere( {key: self.key} );
					connection.set( 'connected', true, {silent: true} );
					connection.set( 'status', TD_NM.t.connected, {silent: true} );

					self.collection.each( function ( model ) {
						model.set( {active: model.get( 'key' ) === self.key}, {silent: true} );
					} );

					self.gotoStep( 0 );
					/*Remove the no connection warning on button*/
					$( '#td-nm-email-services i' ).removeClass( 'tvd-nm-warning-sign' );
					TVE_Dash.success( TD_NM.t.connection_established );
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					self.render_connected_services();
					TVE_Dash.hideLoader();
				} );
			},
			connection_test: function ( event ) {
				var self = this,
					$target = $( event.currentTarget ),
					key = $target.data( 'key' );

				TVE_Dash.showLoader();

				$.ajax( {
					type: 'post',
					url: TD_NM.util.ajaxurl(),
					data: {
						action: 'td_nm_admin_controller',
						route: 'connection_test',
						service: key
					},
					dataType: 'json'
				} ).success( function ( response ) {
					TVE_Dash.success( response.success );
				} ).error( function ( response ) {
					TVE_Dash.err( response.responseJSON.error );
				} ).always( function () {
					TVE_Dash.hideLoader();
				} );
			}
		} );

	} );

})( jQuery );
;/**
 * Created by dan bilauca on 7/11/2016.
 * Notification Manager Views
 */

var TD_NM = TD_NM || {};
TD_NM.views = TD_NM.views || {};

(function ( $ ) {

	$( function () {

		$( document ).keypress( function ( ev ) {
			if ( ev.which == 13 || ev.keyCode == 13 ) {
				/*On enter key pressed in the delete modal window*/
				if ( $( '.tvd-nm-delete-yes' ).is( ':visible' ) ) {
					$( '.tvd-nm-delete-yes' ).focus().click();
				}
			}
		} );

		/**
		 * remove tvd-invalid class for all inputs in the view's root element
		 *
		 * @returns {Backbone.View}
		 */
		Backbone.View.prototype.tvd_nm_clear_errors = function () {
			this.$( '.tvd-invalid' ).removeClass( 'tvd-invalid' );
			this.$( 'select' ).trigger( 'tvdclear' );

			return this;
		};

		/**
		 *
		 * @param {Backbone.Model|object} [model] backbone model or error object with 'field' and 'message' properties
		 *
		 * @returns {Backbone.View|undefined}
		 */
		Backbone.View.prototype.tvd_nm_show_errors = function ( model ) {
			model = model || this.model;

			if ( ! model ) {
				return;
			}

			var err = model instanceof Backbone.Model ? model.validationError : model,
				self = this,
				$all = $();

			function show_error( error_item ) {
				if ( typeof error_item === 'string' ) {
					return TVE_Dash.err( error_item );
				}
				$all = $all.add( self.$( '[data-field=' + error_item.field + ']' ).addClass( 'tvd-invalid' ).each( function () {
					var $this = $( this );
					if ( $this.is( 'select' ) ) {
						$this.trigger( 'tvderror', error_item.message );
					} else {
						$this.next( 'label' ).attr( 'data-error', error_item.message )
					}
				} ) );
			}

			if ( $.isArray( err ) ) {
				_.each( err, function ( item ) {
					show_error( item );
				} );
			} else {
				show_error( err );
			}
			$all.not( '.tvd-no-focus' ).first().focus();
			/* if the first error message is not visible, scroll the contents to include it in the viewport. At the moment, this is only implemented for modals */
			this.tvd_nm_scroll_first_error( $all.first() );

			return this;
		};

		Backbone.View.prototype.tvd_nm_scroll_first_error = function ( $input ) {
			if ( ! ( this instanceof TVE_Dash.views.Modal ) || ! $input.length ) {
				return this;
			}
			var input_top = $input.offset().top,
				content_top = this.$_content.offset().top,
				scroll_top = this.$_content.scrollTop(),
				content_height = this.$_content.outerHeight();
			if ( input_top >= content_top && input_top < content_height + content_top - 50 ) {
				return this;
			}

			this.$_content.animate( {
				'scrollTop': scroll_top + input_top - content_top - 40 // 40px difference
			}, 200, 'swing' );
		};

		/**
		 * Base View
		 */
		TD_NM.views.Base = Backbone.View.extend( {
			render: function () {

				if ( this.template ) {
					this.$el.html( this.template( {item: this.model !== undefined ? this.model : {}} ) );
				}

				TVE_Dash.materialize( this.$el );

				return this;
			},
			/**
			 *
			 * Instantiate and open a new modal which has the view constructor assigned and send params further along
			 *
			 * @param ViewConstructor View constructor
			 * @param params
			 */
			modal: function ( ViewConstructor, params ) {
				return TVE_Dash.modal( ViewConstructor, params );
			},
			bind_zclip: function () {
				TVE_Dash.bindZClip( this.$el.find( 'a.tvd-copy-to-clipboard' ) );
			}
		} );

		/**
		 * Dashboard View
		 */
		TD_NM.views.Dashboard = TD_NM.views.Base.extend( {
			className: 'tvd-container',
			template: TVE_Dash.tpl( 'dashboard' ),
			events: {
				'click .tvd-nm-create-notification': 'create_notification',
			},
			new_button_view: null,
			create_notification: function () {
				this.modal( TD_NM.views.ModalCreateNotification, {
					trigger_types: new TD_NM.collections.TriggerTypes( TD_NM.trigger_types ),
					action_types: new TD_NM.collections.ActionTypes( TD_NM.action_types ),
					model: new TD_NM.models.Notification(),
					collection: this.collection,
					'max-width': '60%',
					width: '750px',
					collection: this.collection,
					in_duration: 200,
					out_duration: 0
				} );
			},
			initialize: function () {
				this.new_button_view = new TD_NM.views.NewButtonDash();

				this.listenTo( this.collection, 'add', this.toggle_new_button );
				this.listenTo( this.collection, 'add', this._render_notification );
				this.listenTo( this.collection, 'remove', this.toggle_new_button );
			},
			render: function () {
				this.$el.html( this.template( {} ) );
				if ( this.collection.length === 0 ) {
					this._display_new_button_dash();
				}

				this._render_notifications();

				return this;
			},
			toggle_new_button: function () {
				if ( this.collection.length ) {
					this.new_button_view.remove();
				} else {
					this._display_new_button_dash();
				}
			},
			_display_new_button_dash: function () {
				this.$( '#tvd-nm-notifications-list' ).prepend( this.new_button_view.render().$el );
				this.new_button_view.add_custom_text( TD_NM.t.no_notification_created_yet );
			},
			_render_notifications: function () {
				this.collection.each( this._render_notification, this );
			},
			_render_notification: function ( model ) {
				var view = new TD_NM.views.Notification( {
					model: model
				} );

				this.$( '#tvd-nm-notifications-list' ).prepend( view.render().$el );
			}
		} );

		/**
		 * Header View
		 */
		TD_NM.views.Header = TD_NM.views.Base.extend( {
			template: TVE_Dash.tpl( 'header' ),
			events: {
				'click #td-nm-email-services': 'email_services'
			},
			email_services: function () {
				this.modal( TD_NM.views.ModalEmailServices, {
					collection: TD_NM.globals.email_services,
					'max-width': '70%',
					width: '900px',
					in_duration: 200,
					out_duration: 0
				} );
			}
		} );

		/**
		 * Breadcrumbs View
		 */
		TD_NM.views.Breadcrumbs = TD_NM.views.Base.extend( {
			el: $( '#tvd-nm-breadcrumbs-wrapper' )[0],
			template: TVE_Dash.tpl( 'breadcrumbs' ),
			/**
			 * setup collection listeners
			 */
			initialize: function () {
				this.$title = $( 'head > title' );
				this.original_title = this.$title.html();
				this.listenTo( this.collection, 'change', this.render );
				this.listenTo( this.collection, 'add', this.render );
			},
			render: function () {
				this.$el.empty().html( this.template( {links: this.collection} ) );
			}
		} );

		/**
		 * General/Base Settings View
		 */
		TD_NM.views.Settings = TD_NM.views.Base.extend( {
			render: function () {
				this.$el.html( this.template( {} ) );

				this.options_section_view = this.render_section();

				return this;
			},
			render_section: function ( element, model, collection ) {
				var view = new TD_NM.views.OptionsSection( {
					model: model instanceof TD_NM.models.Base ? model : this.get_model(),
					el: element instanceof jQuery ? element : this.get_element(),
					collection: collection instanceof TD_NM.collections.Base ? collection : this.get_collection()
				} );

				return view.render();
			},
			get_model: function () {
				return this.model = new TD_NM.models.Base( {
					title: 'Settings title'
				} );
			},
			get_element: function () {
				return this.$el.append( '<div/>' );
			},
			get_collection: function () {
				return this.collection = new TD_NM.collections.Options( [] );
			},
			get_settings: function () {
				return this.collection;
			},
			are_valid: function () {
				return false;
			}
		} );

		/**
		 * Trigger EmailSignUpSettings View
		 */
		TD_NM.views.SettingsEmailSignUp = TD_NM.views.Settings.extend( {
			template: TVE_Dash.tpl( 'triggers/settings/email-sign-up' ),
			render: function () {
				this.$el.html( this.template( {} ) );

				this.render_groups();
				this.render_shortcodes();
				this.render_thrive_boxes();

				return this;
			},
			render_groups: function () {
				var element = this.$( '#tvd-nm-lead-groups-wrapper' ),
					model = new TD_NM.models.Base( {
						title: TD_NM.t.LeadGroups,
						empty_message: TD_NM.util.printf( TD_NM.t.no_s_to_display, [TD_NM.t.group] )
					} ),
					collection = new TD_NM.collections.OptionsCheckbox( TD_NM.tl.groups );

				if ( this.model instanceof TD_NM.models.Trigger ) {
					var selected_items = new TD_NM.collections.Options( this.model.get( 'settings' ).groups );
					collection.match_selected( selected_items );
				}

				this.group_section_view = this.render_section( element, model, collection );

				return this;
			},
			render_shortcodes: function () {
				var element = this.$( '#tvd-nm-lead-shortcodes-wrapper' ),
					model = new TD_NM.models.Base( {
						title: TD_NM.t.Shortcodes,
						empty_message: TD_NM.util.printf( TD_NM.t.no_s_to_display, [TD_NM.t.shortcode] )
					} ),
					collection = new TD_NM.collections.OptionsCheckbox( TD_NM.tl.shortcodes );

				if ( this.model instanceof TD_NM.models.Trigger ) {
					var selected_items = new TD_NM.collections.Options( this.model.get( 'settings' ).shortcodes );
					collection.match_selected( selected_items );
				}

				this.shortcodes_section_view = this.render_section( element, model, collection );

				return this;
			},
			render_thrive_boxes: function () {
				var element = this.$( '#tvd-nm-lead-thrive-boxes-wrapper' ),
					model = new TD_NM.models.Base( {
						title: TD_NM.t.ThriveBoxes,
						empty_message: TD_NM.util.printf( TD_NM.t.no_s_to_display, [TD_NM.t.thrive_box] )
					} ),
					collection = new TD_NM.collections.OptionsCheckbox( TD_NM.tl.thrive_boxes );

				if ( this.model instanceof TD_NM.models.Trigger ) {
					var selected_items = new TD_NM.collections.Options( this.model.get( 'settings' ).thrive_boxes );
					collection.match_selected( selected_items );
				}

				this.thrive_boxes_section_view = this.render_section( element, model, collection );

				return this;
			},
			get_settings: function () {
				return {
					groups: this.group_section_view.collection.where( {selected: true} ),
					shortcodes: this.shortcodes_section_view.collection.where( {selected: true} ),
					thrive_boxes: this.thrive_boxes_section_view.collection.where( {selected: true} )
				}
			},
			are_valid: function () {

				if ( this.group_section_view.collection.where( {selected: true} ).length > 0 ) {
					return true;
				}

				if ( this.shortcodes_section_view.collection.where( {selected: true} ).length > 0 ) {
					return true;
				}

				if ( this.thrive_boxes_section_view.collection.where( {selected: true} ).length > 0 ) {
					return true;
				}

				return false;
			}
		} );

		/**
		 * Trigger SplitTestEndsSettings View
		 */
		TD_NM.views.SettingsSplitTestEnds = TD_NM.views.Settings.extend( {
			template: TVE_Dash.tpl( 'triggers/settings/split-test-ends' ),
			render: function () {
				this.$el.html( this.template( {} ) );

				this.render_tl_tests();
				this.render_tho_tests();

				return this;
			},
			render_tl_tests: function () {
				var element = this.$( '#tvd-nm-tl-tests-wrapper' ),
					model = new TD_NM.models.Base( {
						title: 'Thrive Leads Tests',
						empty_message: TD_NM.util.printf( TD_NM.t.no_s_found, [TD_NM.t.split_tests] )
					} ),
					collection = new TD_NM.collections.OptionsCheckbox( TD_NM.split_tests.tl );

				if ( this.model instanceof TD_NM.models.Trigger ) {
					var selected_items = new TD_NM.collections.Options( this.model.get( 'settings' ).tl );
					collection.match_selected( selected_items );
				}

				this.tl_section_view = this.render_section( element, model, collection );

				return this;
			},
			render_tho_tests: function () {
				var element = this.$( '#tvd-nm-tho-tests-wrapper' ),
					model = new TD_NM.models.Base( {
						title: 'Thrive Headline Optimizer',
						empty_message: TD_NM.util.printf( TD_NM.t.no_s_found, [TD_NM.t.split_tests] )
					} ),
					collection = new TD_NM.collections.OptionsCheckbox( TD_NM.split_tests.tho );

				if ( this.model instanceof TD_NM.models.Trigger ) {
					var selected_items = new TD_NM.collections.Options( this.model.get( 'settings' ).tho );
					collection.match_selected( selected_items );
				}

				this.tho_section_view = this.render_section( element, model, collection );

				return this;
			},
			get_settings: function () {
				return {
					tl: this.tl_section_view.collection.where( {selected: true} ),
					tho: this.tho_section_view.collection.where( {selected: true} )
				}
			},
			are_valid: function () {

				if ( this.tl_section_view.collection.where( {selected: true} ).length > 0 ) {
					return true;
				}

				if ( this.tho_section_view.collection.where( {selected: true} ).length > 0 ) {
					return true;
				}

				return false;
			}
		} );

		/**
		 * Send Email Notification Settings
		 */
		TD_NM.views.SettingsSendEmailNotification = TD_NM.views.Base.extend( {
			className: 'tvd-nm-settings-email-notification',
			template: TVE_Dash.tpl( 'actions/settings/send-email-notification' ),
			events: {
				'click #tvd-nm-add-recipient': 'add_recipient'
			},
			trigger_model: null,
			initialize: function ( args ) {
				if ( args && args.trigger ) {
					this.trigger_model = args.trigger;
				}
				this.listenTo( this.model.get( 'recipients' ), 'add', this.render_option );
			},
			render: function () {
				this.$el.html( this.template( {} ) );

				if ( this.model.get( 'recipients' ).length ) {
					this.model.get( 'recipients' ).each( function ( model ) {
						this.render_option( model );
					}, this );
				} else {
					this.add_recipient();
				}

				this._render_message();

				return this;
			},
			render_option: function ( option_model ) {
				var view = new TD_NM.views.OptionInputMultipleEmail( {
					collection: this.model.get( 'recipients' ),
					model: option_model
				} );

				this.$( '#tvd-nm-recipients-wrapper' ).append( view.render().$el );
			},
			add_recipient: function () {

				if ( this.model.get( 'recipients' ).where( {value: null} ).length ) {
					return;
				}

				/**
				 * check if last model in collection has a valid email address in value prop
				 */
				var last_model = this.model.get( 'recipients' ).last();
				if ( last_model !== undefined && ! TD_NM.util.is_email( last_model.get( 'value' ) ) ) {
					TVE_Dash.err( TD_NM.t.invalid_email );
					return;
				}

				if ( last_model !== undefined && this.model.get( 'recipients' ).where( {value: last_model.get( 'value' )} ).length > 1 ) {
					TVE_Dash.err( TD_NM.t.email_duplicated );
					return;
				}

				this.model.get( 'recipients' ).add( {
					value: null,
					label: TD_NM.t.insert_recipients_email
				} );
			},
			_render_message: function () {
				var view = new TD_NM.views.MessageForm( {
					model: this.model.get( 'message' ),
					collection: new TD_NM.collections.Base( TD_NM.message_shortcodes[this.trigger_model.get( 'type' )][this.model.get( 'type' )] )
				} );

				this.$( '#tvd-nm-message-wrapper' ).html( view.render().$el );
			}
		} );

		/**
		 * Send Email Notification Settings
		 */
		TD_NM.views.SettingsCustomScript = TD_NM.views.Base.extend( {
			template: TVE_Dash.tpl( 'actions/settings/custom-script' ),
			render: function () {
				this.$el.html( this.template( this.model ? {item: this.model} : {} ) );

				TD_NM.util.data_binder( this );

				return this;
			}
		} );

		/**
		 * Settings Wordpress Notification
		 */
		TD_NM.views.SettingsWordpressNotification = TD_NM.views.Base.extend( {
			template: TVE_Dash.tpl( 'actions/settings/wordpress-notification' ),
			trigger_model: null,
			initialize: function ( args ) {
				if ( args && args.trigger ) {
					this.trigger_model = args.trigger;
				}
			},
			render: function () {

				this.$el.html( this.template( this.model ? {item: this.model} : {} ) );

				this._render_message();

				return this;
			},
			_render_message: function () {

				var message = this.model.get( 'message' );

				/**
				 * set default content for message based on trigger type
				 */
				if ( message.get( 'content' ).length === 0 && this.trigger_model ) {
					switch ( this.trigger_model.get( 'type' ) ) {
						case 'email_sign_up':
							message.set( 'content', TD_NM.t.default_message_send_email_wp );
							break;
						case 'split_test_ends':
							message.set( 'content', TD_NM.t.default_message_split_test_ends_wp );
							break;
					}
				}

				var view = new TD_NM.views.MessageForm( {
					model: message,
					collection: new TD_NM.collections.Base( TD_NM.message_shortcodes[this.trigger_model.get( 'type' )][this.model.get( 'type' )] )
				} );

				view.action_type = this.model.get( 'type' );
				view.trigger_type = this.trigger_model.get( 'type' );

				this.$( '#tvd-nm-message-wrapper' ).html( view.render().$el );
				setTimeout( function () {
					TVE_Dash.materialize( $( '#tvd-nm-message-wrapper' ) );
				}, 10 );

			}
		} );

		/**
		 * Options Section View
		 */
		TD_NM.views.OptionsSection = TD_NM.views.Base.extend( {
			render: function () {
				if ( this.model && this.model.get( 'title' ) ) {
					this.$el.append( '<h4>' + this.model.get( 'title' ) + '</h4>' );
				}

				this.collection.each( this.render_option, this );

				if ( this.collection.length === 0 && this.model && this.model.get( 'empty_message' ) ) {
					this.$el.append( '<p class="tvd-center-align tvd-nm-no-notification">' + this.model.get( 'empty_message' ) + '</p>' );
				}

				return this;
			},
			render_option: function ( model ) {
				var view_name = 'Option';

				if ( model instanceof TD_NM.models.OptionCheckbox ) {
					view_name = 'OptionCheckbox';
				}

				var option_view = new TD_NM.views[view_name]( {
					model: model
				} );

				this.$el.append( option_view.render().$el );

				return this;
			}
		} );

		/**
		 * Option View
		 */
		TD_NM.views.Option = TD_NM.views.Base.extend( {
			template: TVE_Dash.tpl( 'options/option' ),
			render: function () {
				this.$el.append( this.template( {item: this.model} ) );

				return this;
			}
		} );

		/**
		 * Option Checkbox View
		 */
		TD_NM.views.OptionCheckbox = TD_NM.views.Option.extend( {
			className: 'tvd-col tvd-s4',
			template: TVE_Dash.tpl( 'options/checkbox' ),
			events: {
				'click input[type="checkbox"]': function ( event ) {
					var $input = $( event.currentTarget );
					this.model.set( 'selected', $input.is( ':checked' ) );
				}
			},
			render: function () {
				this.$el.append( this.template( {item: this.model} ) );

				this.$( 'input' ).prop( 'checked', this.model.get( 'selected' ) );

				return this;
			}
		} );

		/**
		 * Option Input View
		 */
		TD_NM.views.OptionInput = TD_NM.views.Option.extend( {
			className: 'tvd-nm-option-input tvd-collapse tvd-row',
			template: TVE_Dash.tpl( 'options/input' ),
			render: function () {

				this.$el.append( this.template( {item: this.model} ) );

				TD_NM.util.data_binder( this );

				return this;
			}
		} );

		/**
		 *  Option Input Multiple View
		 */
		TD_NM.views.OptionInputMultiple = TD_NM.views.OptionInput.extend( {
			template: TVE_Dash.tpl( 'options/input-multiple' ),
			events: {
				'click .tvd-nm-remove-recipient': 'remove_option',
				'blur input': 'validate',
				'change input': function ( event ) {
					$( event.currentTarget ).removeClass( 'tvd-invalid' );
				}
			},
			render: function () {
				this.$el.append( this.template( {item: this.model} ) );
				TD_NM.util.data_binder( this );

				return this;
			},
			remove_option: function () {
				this.collection.remove( this.model, {silent: true} );
				this.remove();
			},
			validate: function () {
				return this;
			}
		} );

		/**
		 * Option Input Multiple Email
		 */
		TD_NM.views.OptionInputMultipleEmail = TD_NM.views.OptionInputMultiple.extend( {
			validate: function ( event ) {
				var $target = $( event.currentTarget );
				if ( event.currentTarget.value.length && ! TD_NM.util.is_email( event.currentTarget.value ) ) {
					TVE_Dash.err( TD_NM.t.invalid_email );
					$target.addClass( 'tvd-invalid' );
					event.currentTarget.focus();
					return;
				}

				if ( event.currentTarget.value.length && this.collection.where( {value: this.model.get( 'value' )} ).length > 1 ) {
					TVE_Dash.err( TD_NM.t.email_duplicated );
					event.currentTarget.focus();
					$target.addClass( 'tvd-invalid' );
				}
			}
		} );

		/**
		 * Message Form View
		 */
		TD_NM.views.MessageForm = TD_NM.views.Base.extend( {
			events: {
				'click .tvd-nm-toggle-display': 'toggle_tab_display'
			},
			template: TVE_Dash.tpl( 'messages/form' ),
			render: function () {
				this.$el.html( this.template( {item: this.model} ) );

				if ( this.action_type && this.action_type === 'wordpress_notification' ) {
					this.$( '#tvd-nm-message-subject' ).parents( '.tvd-row' ).first().hide();
					if ( this.trigger_type && this.trigger_type === 'email_sign_up' ) {
						this.$( '#tvd-nm-message-shortcodes-wrapper' ).hide();
					}
				}

				this.render_shortcodes();

				this.bind_zclip();

				TD_NM.util.data_binder( this );

				return this;
			},
			render_shortcodes: function () {
				var $wrapper = this.$( '#tvd-nm-message-shortcodes-container' );
				this.collection.each( function ( model, index ) {
					var tpl = TVE_Dash.tpl( 'messages/shortcodes/item' );
					$wrapper.append( TVE_Dash.tpl( 'messages/shortcodes/item' )( {item: model} ) );
				}, this );
			},
			toggle_tab_display: function () {
				var $elem = this.$el.find( '#tvd-nm-message-shortcodes-container' ),
					collapsed = $elem.hasClass( 'tvd-not-visible' );

				if ( collapsed ) {
					$elem.hide( 0 ).removeClass( 'tvd-not-visible' ).slideDown( 200 );
					this.$el.find( '.tvd-nm-toggle-display-icon' ).removeClass( 'tvd-nm-icon-angle-up' ).addClass( 'tvd-nm-icon-angle-down' );
					this.$el.find( '.tvd-nm-toggle-display-text' ).html( TD_NM.t.hide_available_shortcodes );
				} else {
					$elem.slideUp( 200, function () {
						$elem.addClass( 'tvd-not-visible' );
					} );
					this.$el.find( '.tvd-nm-toggle-display-text' ).html( TD_NM.t.show_available_shortcodes );
					this.$el.find( '.tvd-nm-toggle-display-icon' ).removeClass( 'tvd-nm-icon-angle-down' ).addClass( 'tvd-nm-icon-angle-up' );
				}
			}
		} );

		/**
		 * New Button View
		 */
		TD_NM.views.NewButton = TD_NM.views.Base.extend( {
			className: 'tvd-col tvd-nm-add-action tvd-pointer',
			template: TVE_Dash.tpl( 'new-button' ),
			events: {
				'click .tvd-card': 'add_new_action'
			},
			add_custom_text: function ( text ) {
				this.$el.find( '.tvd-nm-new-button-header-text' ).html( text );
			},
			add_new_action: function () {

				this.modal( TD_NM.views.ModalAddAction, {
					action_types: new TD_NM.collections.ActionTypes( TD_NM.action_types ),
					model: new TD_NM.models.ActionComplex(),
					notification_model: this.model,
					collection: this.model.get( 'actions' ),
					'max-width': '60%',
					width: '750px',
					in_duration: 200,
					out_duration: 0
				} );
			}
		} );

		/**
		 * New Button Dash
		 */
		TD_NM.views.NewButtonDash = TD_NM.views.NewButton.extend( {
			className: function () {
				return 'tvd-col tvd-s12 tvd-nm-add-action tvd-pointer tvd-nm-create-notification tvd-nm-create-notification-area'
			},
			add_custom_text: function ( text ) {
				this.$el.prepend( '<div class="tvd-col tvd-s12 tvd-center-align tvd-nm-no-notification tvd-nm-create-notification-area">' + text + '</div>' );
			},
			add_new_action: function () {
				return;
			}
		} );

		/**
		 * Notification View
		 */
		TD_NM.views.Notification = TD_NM.views.Base.extend( {
			className: 'tvd-row tvd-nm-notification-item',
			template: TVE_Dash.tpl( 'notifications/item' ),
			initialize: function () {
				this.listenTo( this.model, 'remove', this.remove );
				this.listenTo( this.model.get( 'actions' ), 'add', this.reset_notification );
				this.listenTo( this.model.get( 'actions' ), 'remove', this.reset_notification );
			},
			render: function () {
				this.$el.html( this.template( {} ) );
				this.render_trigger( this.model.get( 'trigger' ) );
				this.render_actions( this.model.get( 'actions' ) );

				if ( this.model.get( 'actions' ).length < TD_NM.action_types.length ) {
					this.render_add_button();
				}

				this.render_notification_action_buttons();

				return this;
			},
			render_trigger: function ( item ) {
				var view = new TD_NM.views.SummaryTriggers( {
					collection: new TD_NM.collections.Base( [item] ),
					el: this.$( '#tvd-nm-trigger-wrapper' )
				} );

				view.render();

				return this;
			},
			render_actions: function ( items ) {
				var view = new TD_NM.views.SummaryActions( {
					collection: items,
					el: this.$( '#tvd-nm-actions-wrapper' )
				} );

				view.trigger = this.model.get( 'trigger' );
				view.render();

				return this;
			},
			render_add_button: function () {

				var new_button = new TD_NM.views.NewButton( {
					model: this.model
				} );

				this.$( '#tvd-nm-actions-wrapper' ).append( new_button.render().$el );
				new_button.add_custom_text( TD_NM.t.add_new_action );
			},
			render_notification_action_buttons: function () {
				var action_buttons = new TD_NM.views.NotificationActionButtons( {
					model: this.model
				} );
				this.$( '#tvd-nm-actions-wrapper' ).append( action_buttons.render().$el );
			},
			reset_notification: function () {
				this.$el.empty();
				this.render();
			}
		} );

		/**
		 * Notification action buttons
		 */
		TD_NM.views.NotificationActionButtons = TD_NM.views.Base.extend( {
			className: 'tvd-right tvd-nm-notification-action-buttons',
			template: TVE_Dash.tpl( 'options/action-buttons' ),
			events: {
				'click .tvd-nm-delete-notification': 'delete_notification_modal'
			},
			delete_notification_modal: function () {

				this.modal( TD_NM.views.ModalDelete, {
					model: this.model,
					'max-width': '60%',
					width: '750px',
					in_duration: 200,
					out_duration: 0
				} );
			}
		} );

		/**
		 * Notification Summary View
		 * @author Ovidiu
		 */
		TD_NM.views.NotificationSummary = TD_NM.views.ModalSteps.extend( {
			template: TVE_Dash.tpl( 'modals/notification-summary' ),
			render: function () {
				this.$el.html( this.template( {trigger: this.trigger_model, action: this.action_model} ) );
				return this;
			}
		} );

		/**
		 * Summary View
		 */
		TD_NM.views.Summary = TD_NM.views.Base.extend( {
			className: 'tvd-row tvd-nm-summary',
			render: function () {
				this.collection.each( this.render_item, this );

				return this;
			},
			render_item: function ( item ) {
				var view_name = 'SummaryItem';

				if ( item instanceof TD_NM.models.Trigger || item instanceof TD_NM.models.Action ) {
					view_name = 'SummaryItemNotification';
				}

				var view = new TD_NM.views[view_name]( {
					model: item,
					collection: this.collection
				} );

				this.$el.append( view.render().$el );

				return this;
			}
		} );

		/**
		 * Summary Actions View
		 */
		TD_NM.views.SummaryActions = TD_NM.views.Summary.extend( {
			render_item: function ( item ) {
				var view = new TD_NM.views.SummaryItemAction( {
					model: item,
					collection: this.collection
				} );

				view.trigger = this.trigger;

				this.$el.append( view.render().$el );

				return this;
			}
		} );

		/**
		 * Summary Triggers
		 */
		TD_NM.views.SummaryTriggers = TD_NM.views.Summary.extend( {
			render_item: function ( item ) {
				var view = new TD_NM.views.SummaryItemTrigger( {
					model: item,
					collection: this.collection
				} );

				this.$el.append( view.render().$el );

				return this;
			}
		} );

		/**
		 * Summary Item View
		 */
		TD_NM.views.SummaryItem = TD_NM.views.Base.extend( {
			className: 'tvd-col tvd-s2 tvd-nm-summary-item',
			template: TVE_Dash.tpl( 'summaries/item' ),
			events: {
				'click .tvd-nm-action-edit': 'edit_action',
				'click .tvd-nm-action-delete': 'delete_action'
			},
			edit_action: function () {

				this.modal( TD_NM.views.ModalEditAction, {
					action_types: new TD_NM.collections.ActionTypes( TD_NM.action_types ),
					trigger: this.trigger,
					model: this.model,
					collection: this.collection,
					'max-width': '60%',
					width: '750px',
					in_duration: 200,
					out_duration: 0
				} );
			},
			delete_action: function () {
				this.modal( TD_NM.views.ModalDelete, {
					model: this.model,
					'max-width': '60%',
					width: '750px',
					in_duration: 200,
					out_duration: 0
				} );
			}
		} );

		/**
		 * Summary Item View
		 */
		TD_NM.views.SummaryItemAction = TD_NM.views.Base.extend( {
			className: 'tvd-col',
			template: TVE_Dash.tpl( 'summaries/items/action' ),
			initialize: function () {
				this.listenTo( this.model, 'change', this.render );
				this.listenTo( this.model, 'remove', this.remove_item );
			},
			render: function () {
				var view_name = 'SummaryItem';

				if ( this.model.get( 'type' ) === 'send_email_notification' ) {
					view_name += 'SendEmailNotification';
				} else if ( this.model.get( 'type' ) === 'custom_script' ) {
					view_name += 'CustomScript';
				} else if ( this.model.get( 'type' ) === 'wordpress_notification' ) {
					view_name += 'WordpressNotification';
				}

				var view = new TD_NM.views[view_name]( {
					model: this.model,
					collection: this.collection
				} );

				view.trigger = this.trigger;

				this.$el.html( '' );
				this.$el.append( view.render().$el );

				return this;
			},
			remove_item: function () {
				this.collection.each( function ( model, index ) {
					model.set( 'ID', index );
				} );
			}
		} );

		/**
		 * Summary Item Custom Script View
		 */
		TD_NM.views.SummaryItemCustomScript = TD_NM.views.SummaryItem.extend( {
			specific_class: '',
			className: function () {
				return 'tvd-card tvd-white tvd-small tvd-valign-wrapper tvd-nm-summary-item-action tvd-nm-action td-nm-equal-width-action ' + this.specific_class;
			},
			template: TVE_Dash.tpl( 'summaries/items/actions/custom-script' ),
			render: function () {
				if ( this.collection.indexOf( this.model ) === TD_NM.action_types.length - 1 ) {
					this.$el.addClass( 'tvd-nm-no-icon-action' );
				}

				this.$el.html( this.template( {item: this.model} ) );

				this.bind_zclip();

				return this;
			}
		} );

		/**
		 * Summary Item Send Email Notification VIew
		 */
		TD_NM.views.SummaryItemSendEmailNotification = TD_NM.views.SummaryItem.extend( {
			specific_class: '',
			className: function () {
				return 'tvd-card tvd-white tvd-small tvd-valign-wrapper tvd-nm-summary-item-action tvd-nm-action td-nm-equal-width-action ' + this.specific_class;
			},
			template: TVE_Dash.tpl( 'summaries/items/actions/send-email-notification' ),
			render: function () {
				if ( this.collection.indexOf( this.model ) === TD_NM.action_types.length - 1 ) {
					this.$el.addClass( 'tvd-nm-no-icon-action' );
				}

				this.$el.html( this.template( {item: this.model} ) );

				this.render_recipients();

				return this;
			},
			render_recipients: function () {
				var $wrapper = this.$( '.tvd-nm-emails-list' ),
					show_recipients = 1,
					length_recipients = this.model.get( 'recipients' ).length;

				this.model.get( 'recipients' ).every( function ( model, index ) {
					var tpl = TVE_Dash.tpl( 'options/recipient' );

					if ( index <= show_recipients - 1 ) {
						$wrapper.append( tpl( {item: model} ) );
						if ( index != show_recipients - 1 && length_recipients != 1 ) {
							$wrapper.append( '<span>,&nbsp;</span>' );
						}
						return true;
					} else {
						var remaining_recipients = length_recipients - show_recipients,
							TestModel = new TD_NM.models.Base( {value: '+' + remaining_recipients + ' ' + TD_NM.t.others} );
						$wrapper.append( '<span>,&nbsp;</span>' );
						$wrapper.append( tpl( {item: TestModel} ) );
						return false;
					}

				} );
			}
		} );

		/**
		 * Summary Item Wordpress Notification VIew
		 */
		TD_NM.views.SummaryItemWordpressNotification = TD_NM.views.SummaryItem.extend( {
			specific_class: 'tvd-nm-action-wp',
			className: function () {
				return 'tvd-card tvd-white tvd-small tvd-valign-wrapper tvd-nm-summary-item-action tvd-nm-action td-nm-equal-width-action ' + this.specific_class;
			},
			template: TVE_Dash.tpl( 'summaries/items/actions/wordpress-notification' ),
			render: function () {

				if ( this.collection.indexOf( this.model ) === TD_NM.action_types.length - 1 ) {
					this.$el.addClass( 'tvd-nm-no-icon-action' );
				}

				this.$el.html( this.template( {item: this.model} ) );

				this.bind_zclip();

				return this;
			}
		} );

		/**
		 * Summary Item Trigger View
		 */
		TD_NM.views.SummaryItemTrigger = TD_NM.views.SummaryItem.extend( {
			className: 'tvd-card tvd-white tvd-small tvd-valign-wrapper tvd-nm-summary-item-trigger',
			template: TVE_Dash.tpl( 'summaries/items/trigger' ),
			events: {
				'click .tvd-nm-edit-trigger': 'edit'
			},
			initialize: function () {
				this.listenTo( this.model, 'change', this.render )
			},
			render: function () {
				var trigger_type = TD_NM.globals.trigger_types.findWhere( {key: this.model.get( 'type' )} );
				this.model.set( 'label', trigger_type.get( 'label' ), {silent: true} ).set( 'icon', trigger_type.get( 'icon' ), {silent: true} );

				this.$el.html( this.template( {item: this.model} ) );

				return this;
			},
			edit: function () {
				this.modal( TD_NM.views.ModalEditTrigger, {
					trigger_types: TD_NM.globals.trigger_types,
					model: this.model,
					'max-width': '60%',
					width: '750px',
					in_duration: 200,
					out_duration: 0
				} );
			}
		} );

		/**
		 * Summary Item Trigger Notification
		 */
		TD_NM.views.SummaryItemNotification = TD_NM.views.SummaryItem.extend( {
			className: 'tvd-col tvd-s3',
			template: TVE_Dash.tpl( 'summaries/items/notification' ),
			render: function () {
				var type = null;
				if ( this.model instanceof TD_NM.models.Trigger ) {
					type = TD_NM.globals.trigger_types.findWhere( {key: this.model.get( 'type' )} );
					this.model.set( 'color', 'tvd-green-text' ).set( 'class', 'tvd-nm-summary-item-trigger' );
				} else {
					type = TD_NM.globals.action_types.findWhere( {key: this.model.get( 'type' )} );
					this.model.set( 'color', 'tvd-blue-text' ).set( 'class', 'tvd-nm-summary-item-action' );
				}

				this.model.set( 'label', type.get( 'label' ) ).set( 'icon', type.get( 'icon' ) );

				this.$el.html( this.template( {item: this.model} ) );

				return this;
			}
		} );

		/**
		 * Unavailable View
		 */
		TD_NM.views.Unavailable = TD_NM.views.Base.extend( {
			template: TVE_Dash.tpl( 'unavailable' )
		} );

	} );

})( jQuery );
;/**
 * Created by dan bilauca on 7/11/2016.
 */

var TD_NM = TD_NM || {};

/**
 * Settings for the underscore templates
 * Enables <##> tags instead of <%%>
 */
_.templateSettings = {
	evaluate: /<#([\s\S]+?)#>/g,
	interpolate: /<#=([\s\S]+?)#>/g,
	escape: /<#-([\s\S]+?)#>/g
};


/**
 * Override Backbone ajax call and append wp security token
 *
 * @returns {*}
 */
Backbone.ajax = function () {
	if ( arguments[0].url.indexOf( '_nonce' ) === - 1 ) {
		arguments[0]['url'] += "&_nonce=" + TD_NM.admin_nonce;
	}

	return Backbone.$.ajax.apply( Backbone.$, arguments );
};

(function ( $ ) {

	var Router = Backbone.Router.extend( {
		view: null,
		$el: $( '#tvd-nm-wrapper' ),
		routes: {
			'dashboard': 'dashboard'
		},
		breadcrumbs: {
			collection: null,
			view: null
		},
		init_breadcrumbs: function () {
			this.breadcrumbs.collection = new TD_NM.collections.Breadcrumbs();
			this.breadcrumbs.view = new TD_NM.views.Breadcrumbs( {
				collection: this.breadcrumbs.collection
			} )
		},
		dashboard: function () {

			this._set_page( 'dashboard', TD_NM.t.NM_Dashboard );

			if ( this.view ) {
				this.view.remove();
			}

			this._render_header();

			if ( TD_NM.trigger_types.length !== 0 ) {
				this.view = new TD_NM.views.Dashboard( {
					collection: new TD_NM.collections.Notifications( TD_NM.notifications )
				} );
			} else {
				this.view = new TD_NM.views.Unavailable( {} );
			}

			this.$el.html( this.view.render().$el );
		},
		_render_header: function () {
			if ( ! this.header ) {
				this.header = new TD_NM.views.Header( {
					el: '#tvd-nm-header'
				} );
			} else {
				this.header.setElement( $( '.tvu-header' ) );
			}

			this.header.render();
		},
		_set_page: function ( section, label, structure ) {
			this.breadcrumbs.collection.reset();
			structure = structure || {};
			/* Thrive Dashboard is always the first element */
			this.breadcrumbs.collection.add_page( TD_NM.dash_url, TD_NM.t.Thrive_Dashboard, true );
			_.each( structure, _.bind( function ( item ) {
				this.breadcrumbs.collection.add_page( item.route, item.label );
			}, this ) );
			/**
			 * last link - no need for route
			 */
			this.breadcrumbs.collection.add_page( '', label );
			/* update the page title */
			var $title = $( 'head > title' );
			if ( ! this.original_title ) {
				this.original_title = $title.html();
			}
			$title.html( label + ' &lsaquo; ' + this.original_title )
		}
	} );


	$( function () {

		TD_NM.globals = TD_NM.globals || {};
		TD_NM.globals.trigger_types = new TD_NM.collections.TriggerTypes( TD_NM.trigger_types );
		TD_NM.globals.action_types = new TD_NM.collections.ActionTypes( TD_NM.action_types );
		TD_NM.globals.email_services = new TD_NM.collections.EmailServices( TD_NM.email_services );

		TD_NM.router = new Router;
		TD_NM.router.init_breadcrumbs();

		Backbone.history.start( {hashchange: true} );

		if ( ! Backbone.history.fragment ) {
			TD_NM.router.navigate( '#dashboard', {trigger: true} );
		}
	} );

})( jQuery );
