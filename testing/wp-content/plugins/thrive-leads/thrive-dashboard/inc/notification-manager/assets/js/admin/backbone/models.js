/**
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
