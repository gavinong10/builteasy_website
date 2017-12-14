/**
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
