var TVE_Dash = TVE_Dash || {};
TVE_Dash.API = TVE_Dash.API || {};
TVE_Dash.API.models = TVE_Dash.API.models || {};
TVE_Dash.API.collections = TVE_Dash.API.collections || {};
TVE_Dash.API.views = TVE_Dash.API.views || {};
(function ( $ ) {
		'use strict';

		_.templateSettings = {
			evaluate: /<#([\s\S]+?)#>/g,
			interpolate: /<#=([\s\S]+?)#>/g,
			escape: /<#-([\s\S]+?)#>/g
		};

		Backbone.emulateHTTP = true;

		var appRouter = Backbone.Router.extend( {
			routes: {
				"done/:api": 'done',
				"failed/:api": 'failed',
				'edit/:api': 'edit'
			},
			done: function ( api ) {
				TVE_Dash.API.ConnectedAPIs.findWhere( {key: api} ).set( 'state', 'done' );
			},
			failed: function ( api ) {
				TVE_Dash.API.ToBeConnected.set( {key: api, state: 'error', response: {message: tve_dash_api_error.message, success: false}} );
			},
			edit: function ( api ) {
				TVE_Dash.API.ConnectedAPIs.findWhere( {key: api} ).set( 'state', 'edit' );
			}
		} );

		TVE_Dash.API.models.Connection = Backbone.Model.extend( {
			defaults: {
				state: ''
			}
		} );

		TVE_Dash.API.models.ToBeConnected = Backbone.Model.extend( {
			defaults: {
				state: 'new'
			}
		} );
		TVE_Dash.API.models.APIType = Backbone.Model.extend( {} );
		TVE_Dash.API.collections.APITypes = Backbone.Collection.extend( {
			model: TVE_Dash.API.models.APIType
		} );

		TVE_Dash.API.collections.Connections = Backbone.Collection.extend( {
			model: TVE_Dash.API.models.Connection,
			getAPIs: function ( connected ) {
				if ( connected !== true ) {
					connected = false;
				}
				var connected = this.filter( function ( item ) {
					return item.get( 'connected' ) === connected;
				} );

				return new TVE_Dash.API.collections.Connections( connected );
			},
			getByType: function ( type ) {
				if ( type === undefined ) {
					return this;
				}

				var filtered = this.filter( function ( item ) {
					return item.get( 'type' ) === type;
				} );

				return new TVE_Dash.API.collections.Connections( filtered );
			}
		} );

		TVE_Dash.API.views.Connection = Backbone.View.extend( {
			initialize: function () {
				this.listenTo( this.model, 'change:state', _.bind( this.renderState, this ) );
			},
			render: function () {
				this.renderState();
				return this;
			},
			renderState: function () {
				var state,
					view,
					param;

				if ( ! this.model.get( 'state' ).length ) {
					state = 'connected';
				} else {
					state = this.model.get( 'state' )[0].toUpperCase() + this.model.get( 'state' ).slice( 1 );
				}

				if ( TVE_Dash.API.views["Connection" + state] ) {
					view = new TVE_Dash.API.views['Connection' + state]( {
						model: this.model,
						collection: this.collection
					} );
				} else {
					view = new TVE_Dash.API.views.ConnectionConnected( {
						model: this.model,
						collection: this.collection
					} );
				}

				if ( state === 'Edit' ) {
					param = true;
				}

				view.render( param );

				this.$el.replaceWith( view.$el );
				this.setElement( view.$el );
				if ( window.rebindWistiaFancyBoxes ) {
					window.rebindWistiaFancyBoxes();
				}
				this.$el.find( 'select' ).select2();
				return this;
			}
		} );

		TVE_Dash.API.views.ConnectionConnected = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-edit': function () {
					this.collection.each( _.bind( function ( item ) {
						item.set( 'state', 'connected' );
					}, this ) );
					this.model.set( 'state', 'edit' );
				},
				'click .tvd-api-test': function () {
					this.model.set( 'state', 'test' );
				},
				'click .tvd-api-delete': function () {
					this.model.set( 'state', 'delete' );
				}
			},
			render: function () {

				TVE_Dash.API.ToBeConnected.set( 'state', 'new' );

				this.$el.html( TVE_Dash.tpl( 'tvd-api-connected', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.ConnectionTest = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-close-card': function () {
					this.model.set( 'state', 'connected' );
				},
				'click .tvd-close-anywhere': function () {
					this.model.set( 'state', 'connected' );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-connection-hover', {
					text: TVE_Dash_Const.translations.Testing,
					color: '',
					icon: 'tvd-no-icon'
				} ) );

				TVE_Dash.cardLoader( this.$el.find( '.tvd-card' ) );

				this.test();

				return this;
			},
			test: function () {
				var self = this,
					ajax = $.ajax( {
						type: 'post',
						url: ajaxurl,
						dataType: 'json',
						data: {
							api: this.model.get( 'key' ),
							action: TVE_Dash_Const.actions.api_handle_save,
							test: true
						}
					} );

				ajax.fail( function () {
					self.model.set( 'title', 'Connection Error' );
					self.model.set( 'state', 'connected' );
				} );

				ajax.done( function ( response ) {
					var model = {};
					if ( response.success ) {
						model.text = TVE_Dash_Const.translations.ConnectionWorks;
						model.color = 'tvd-green tvd-close-anywhere';
						model.icon = 'tvd-icon-check';
						self.$el.html( TVE_Dash.tpl( 'tvd-api-connection-hover', model ) );
					} else {
						self.model.set( 'response', response );
						self.model.set( 'state', 'error' );
					}
				} );
			}
		} );

		TVE_Dash.API.views.ConnectionError = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-edit': function () {
					this.model.set( 'state', 'edit' );
				},
				'click .tvd-close-card': function () {
					this.model.set( 'state', 'connected' );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-connection-error', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.ConnectionDelete = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-delete-yes': 'yes',
				'click .tvd-api-delete-no': function () {
					this.model.set( 'state', 'connected' );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-confirm-delete', {item: this.model} ) );
				return this;
			},
			yes: function () {
				var self = this,
					ajax = $.ajax( {
						type: 'post',
						url: ajaxurl,
						data: {
							api: this.model.get( 'key' ),
							action: TVE_Dash_Const.actions.api_handle_save,
							disconnect: true
						},
						dataType: 'json'
					} );

				this.$el.find( '.tvd-card' ).replaceWith( TVE_Dash.tpl( 'tvd-api-connection-hover', {
					text: TVE_Dash_Const.translations.Deleting,
					color: 'tvd-red',
					icon: 'tvd-icon-spinner mdi-pulse'
				} ) );

				ajax.fail( function ( response ) {
					self.model.set( 'state', 'connected' );
				} );

				ajax.done( function ( response ) {
					if ( response.success ) {
						var unconnected = new TVE_Dash.API.models.Connection( {
							connected: false,
							key: self.model.get( 'key' ),
							credentials: self.model.get( 'credentials' ),
							title: self.model.get( 'title' ),
							type: self.model.get( 'type' ),
							logoUrl: self.model.get( 'logoUrl' )
						} );
						self.collection.remove( self.model );
						self.remove();
						TVE_Dash.API.AvailableAPIs.push( unconnected );
					} else {
						self.model.set( 'state', 'connected' );
					}
				} );
			}
		} );

		TVE_Dash.API.views.ConnectionDone = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-done': function () {
					this.model.set( 'state', 'connected' );
					TVE_Dash.API.router.navigate( '', {trigger: true} );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-success', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.ConnectionSuccess = TVE_Dash.API.views.ConnectionDone.extend( {} )

		TVE_Dash.API.views.NewState = Backbone.View.extend( {
			className: "tvd-col tvd-s12 tvd-m4 tvd-l3",
			events: {
				'click .tvd-card-content': function () {
					this.model.set( 'state', 'select' );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-new', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.HiddenState = Backbone.View.extend( {
			render: function () {
				return this;
			}
		} );

		TVE_Dash.API.views.SelectState = Backbone.View.extend( {
			className: "tvd-col tvd-s12 tvd-m4 tvd-l3",
			events: {
				'change #selected-api': 'onAPISelect'
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-select', {
					item: this.model
				} ) );
				this.renderAPIsList();

				return this;
			},
			onAPISelect: function ( event ) {
				var $select = $( event.target );
				if ( $select.val() === 'none' ) {
					return;
				}
				this.model.set( 'key', $select.val() );
				this.model.set( 'state', 'form' );
			},
			renderAPIsList: function () {
				var $select = this.$el.find( '#selected-api' );
				TVE_Dash.API.APITypes.each( function ( type ) {
					var $optgroup = $( '<optgroup/>' ).attr( 'label', type.get( 'label' ) ),
						APIs = this.collection.getAPIs( false ).getByType( type.get( 'type' ) );
					APIs.each( function ( api ) {
						var $option = $( '<option/>' ).html( api.get( 'title' ) ).attr( 'value', api.get( 'key' ) );
						$optgroup.append( $option );
					}, this );
					if ( APIs.length ) {
						$select.append( $optgroup );
					}
				}, this );
			}
		} );

		TVE_Dash.API.views.FormState = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			input_selector: 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea',
			events: {
				'click .tvd-api-cancel': function () {
					this.model.set( 'state', 'new' );
				},
				'click .tvd-api-connect': 'connect',
				'click .tvd-api-redirect': 'redirect',
				'keypress .tvd-api-add-chip': 'addChip',
				'click .tvd-icon-close2': 'deleteChip'
			},
			render: function ( editMode ) {
//				$( '#tvd-add-new-api' ).hide();
				var excluded = ['awsses', 'campaignmonitor'];
				TVE_Dash.API.ToBeConnected.set( 'state', 'hidden' );

				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-form', {item: this.model} ) );
				this.$el.find( '.tvd-card-content' ).prepend( TVE_Dash.tpl( 'tvd-api-form-' + this.model.get( 'key' ), {item: this.model} ) );
				if ( editMode !== true && excluded.indexOf(this.model.get( 'key' )) == -1 ) {
					this.$el.find( 'input[type="text"]' ).val( '' );
				}

				//activate labels for inputs with value
				this.$el.find( this.input_selector ).each( function ( index, element ) {
					if ( $( element ).val().length > 0 || $( this ).attr( 'placeholder' ) !== undefined || $( element )[0].validity.badInput === true ) {
						$( this ).siblings( 'label' ).addClass( 'tvd-active' );
					}
					else {
						$( this ).siblings( 'label, i' ).removeClass( 'tvd-active' );
					}
				} );
				if ( this.model.get( 'new_credentials' ) === 1 ) {
					var self = this;
					$.each( this.model.get( 'credentials' ), function ( index, field ) {
						self.$el.find( 'input[name="connection[' + index + ']"]' ).val( field );
					} );
					this.model.unset( 'new_credentials' );
				}
				return this;
			},
			addChip: function ( event ) {
				if ( event.keyCode !== 13 ) {
					return;
				}

				var $manager = $( event.target );
				if ( ! $manager.val().length ) {
					return;
				}
				var $input = $( '<input/>' ).attr( 'type', 'hidden' ).attr( 'name', $manager.data( 'name' ) ).val( $manager.val() );
				var $chip = $( '<div class="tvd-chip">' + $manager.val() + '<i class="tvd-icon-close2"></i></div>' );

				$chip.on( 'click', '.tvd-icon-close2', _.bind( this.deleteChip, this ) );

				this.$el.find( 'form' ).prepend( $input );
				this.$el.find( '.tvd-api-chip-wrapper' ).append( $chip );
				$manager.val( '' );
			},
			deleteChip: function ( event ) {
				var $chip = $( event.target ).parent();
				var text = $chip.contents().filter( function () {
					return this.nodeType === 3;
				} ).text();
				this.$el.find( 'input[value="' + text + '"]' ).remove();
				$chip.remove();
			},
			connect: function () {
				var data = this.$el.find( 'form' ).serialize(),
					data_array = {};

				$.each( this.$el.find( 'form' ).serializeArray(), function ( i, field ) {
					data_array[field.name] = field.value;
				} );
				data += "&action=" + TVE_Dash_Const.actions.api_handle_save;
				var new_credentials = {};
				for ( var index in this.model.get( 'credentials' ) ) {
					new_credentials[index] = data_array['connection[' + index + ']'];
				}

				var self = this,
					request = $.ajax( {
						url: ajaxurl,
						type: 'post',
						dataType: 'json',
						data: data
					} );
				TVE_Dash.cardLoader( this.$el );
				request.fail( function ( response ) {
					TVE_Dash.hideCardLoader( self.$el );
					self.model.set( 'response', {
						success: false,
						message: TVE_Dash_Const.translations.UnknownError
					} );
					self.model.set( 'state', 'error' );
				} );

				request.done( function ( response ) {
					TVE_Dash.hideCardLoader( self.$el );
					if ( ! response.success ) {
						self.model.set( 'response', response );
						self.model.set( "state", "error" );
					} else if ( response === "0" ) {
						self.model.set( 'response', {
							success: false,
							message: TVE_Dash_Const.translations.UnknownError
						} );
						self.model.set( "state", "error" );
					} else {
						self.model.set( 'credentials', new_credentials );
						self.model.set( 'new_credentials', 1 );
						self.model.set( 'response', response );
						self.model.set( "state", "success" );
					}
				} );
			},
			redirect: function () {
				var data = this.$el.find( 'form' ).serialize(),
					data_array = {};

				$.each( this.$el.find( 'form' ).serializeArray(), function ( i, field ) {
					data_array[field.name] = field.value;
				} );
				data += "&action=" + TVE_Dash_Const.actions.api_handle_redirect;
				var new_credentials = {};
				for ( var index in this.model.get( 'credentials' ) ) {
					new_credentials[index] = data_array['connection[' + index + ']'];
				}

				var self = this,
					request = $.ajax( {
						url: ajaxurl,
						type: 'post',
						dataType: 'json',
						data: data
					} );
				TVE_Dash.cardLoader( this.$el );
				request.fail( function ( response ) {
					TVE_Dash.hideCardLoader( self.$el );
					self.model.set( 'response', {
						success: false,
						message: TVE_Dash_Const.translations.UnknownError
					} );
					self.model.set( 'state', 'error' );
				} );

				request.done( function ( response ) {
					if ( ! response.success ) {
						self.model.set( 'response', response );
						self.model.set( "state", "error" );
					} else if ( response === "0" ) {
						self.model.set( 'response', {
							success: false,
							message: TVE_Dash_Const.translations.UnknownError
						} );
						self.model.set( "state", "error" );
					} else {
						window.location.href = response.message;
					}
					TVE_Dash.hideCardLoader( self.$el );
				} );
			}
		} );

		TVE_Dash.API.views.ErrorState = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-cancel': function () {
					this.model.set( 'state', 'new' );
				},
				'click .tvd-api-retry': function () {
					this.model.set( 'state', 'form' );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-error', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.SuccessState = Backbone.View.extend( {
			className: "tvd-col tvd-s6 tvd-ms6 tvd-m4 tvd-l3",
			events: {
				'click .tvd-api-done': function () {
					var connected = TVE_Dash.API.AvailableAPIs.findWhere( {key: this.model.get( 'key' )} );
					connected.set( 'connected', true );
					connected.set( 'state', 'connected' );

					//clear the model and set its state to new
					//to display the add new connection state
					this.model.clear( {
						silent: true
					} );
					this.model.set( 'state', 'new' );

					TVE_Dash.API.ConnectedAPIs.push( connected );
					TVE_Dash.API.AvailableAPIs.remove( connected );
				}
			},
			render: function () {
				this.$el.html( TVE_Dash.tpl( 'tvd-api-state-success', {item: this.model} ) );
				return this;
			}
		} );

		TVE_Dash.API.views.ToBeConnected = Backbone.View.extend( {
			initialize: function () {
				this.listenTo( this.model, 'change:state', _.bind( this.renderState, this ) )
			},
			render: function () {
				if ( this.collection.getAPIs( false ).length <= 0 ) {
					this.$el.html( '' );
					return this;
				}
				this.renderState();
				return this;
			},
			renderState: function () {
				var state = this.model.get( 'state' )[0].toUpperCase() + this.model.get( 'state' ).slice( 1 );

				if ( TVE_Dash.API.views[state + "State"] ) {
					var view = new TVE_Dash.API.views[state + 'State']( {
						model: this.model,
						collection: this.collection
					} );
				} else {
					var view = new TVE_Dash.API.views.NewState( {
						model: this.model
					} );
				}
				view.render();
				this.$el.replaceWith( view.$el );
				this.setElement( view.$el );
				this.$el.find( 'select' ).select2();
				if ( window.rebindWistiaFancyBoxes ) {
					window.rebindWistiaFancyBoxes();
				}
			}
		} );

		TVE_Dash.API.views.ConnectionEdit = TVE_Dash.API.views.FormState.extend( {} );

		TVE_Dash.API.views.App = Backbone.View.extend( {
			el: '.tvd-api-list',
			initialize: function () {
				this.listenTo( this.collection, 'add', _.bind( this.renderConnection, this ) );
			},
			render: function () {
				this.$el.html( '' );
				this.renderAddNewConnection( TVE_Dash.API.ToBeConnected );
				this.collection.each( this.renderConnection, this );

			},
			renderConnection: function ( item ) {
				var v = new TVE_Dash.API.views.Connection( {
					model: item,
					collection: this.collection
				} );
				this.$el.find( '> .tvd-col' ).last().before( v.render().$el );
			},
			renderAddNewConnection: function ( item ) {
				var v = new TVE_Dash.API.views.ToBeConnected( {
					model: item,
					collection: TVE_Dash.API.AvailableAPIs
				} );
				this.$el.append( v.render().$el );
			}
		} );

		$( function () {
			window.app = new TVE_Dash.API.views.App( {
				collection: TVE_Dash.API.ConnectedAPIs
			} );
			window.app.render();
			TVE_Dash.API.router = new appRouter;
			Backbone.history.start();

			TVE_Dash.showLoader();

			setTimeout( function () {
				$( '.tvd-show-onload' ).removeClass( 'tvd-hide' );
				TVE_Dash.hideLoader();
			}, 200 );
		} );

	})( jQuery );
