/**
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
