/**
 * Settings for the underscore templates
 * Enables <##> tags instead of <%%>
 *
 * @type {{evaluate: RegExp, interpolate: RegExp, escape: RegExp}}
 */
_.templateSettings = {
	evaluate: /<#([\s\S]+?)#>/g,
	interpolate: /<#=([\s\S]+?)#>/g,
	escape: /<#-([\s\S]+?)#>/g
};

jQuery( document ).ready( function ( $ ) {

	TVE_Dash.ERROR_LOG = TVE_Dash.ERROR_LOG || {};
	TVE_Dash.ERROR_LOG.models = TVE_Dash.ERROR_LOG.models || {};
	TVE_Dash.ERROR_LOG.collections = TVE_Dash.ERROR_LOG.collections || {};
	TVE_Dash.ERROR_LOG.views = TVE_Dash.ERROR_LOG.views || {};
	TVE_Dash.ERROR_LOG.delay = 4000;

	TVE_Dash.ERROR_LOG.models.LogEntry = Backbone.Model.extend( {} );

	TVE_Dash.ERROR_LOG.collections.LogEntries = Backbone.Collection.extend( {
		order: 'DESC',
		orderby: 'date',
		per_page: 10,
		current_page: 1,
		total_pages: null,
		total_items: null,
		model: TVE_Dash.ERROR_LOG.models.LogEntry,
		url: function () {
			var action = '?action=' + TVE_Dash_Const.actions.backend_ajax,
				route = '&route=' + TVE_Dash_Const.routes.error_log,
				order = '&order=' + this.order,
				orderby = '&orderby=' + this.orderby,
				per_page = '&per_page=' + this.per_page,
				current_page = '&current_page=' + this.current_page;

			return ajaxurl + action + route + order + orderby + per_page + current_page;
		},
		parse: function ( data ) {
			this.total_pages = data.settings.pages;
			this.total_items = data.settings.items;
			return data.models;
		}
	} );

	TVE_Dash.ERROR_LOG.views.LogEntryView = Backbone.View.extend( {
		tagName: 'tr',
		template: TVE_Dash.tpl( 'tvd-error-log-entry-template' ),
		className: 'tvd-error-log-entry tvd-collection-item tvd-row',
		events: {
			'click .delete span, .retry span': function ( event ) {
				if ( $( event.currentTarget ).parent().hasClass( 'retry' ) ) {
					this.entryAction( 'retry' );
				}
				if ( $( event.currentTarget ).parent().hasClass( 'delete' ) ) {
					this.entryAction( 'delete' );
				}
			}
		},
		render: function () {
			this.$el.html( this.template( {item: this.model} ) );
			return this;
		},
		entryAction: function ( action ) {
			var $row = this.$el,
				id = this.$el.find( '.tvd-error-entry-actions' ).attr( 'data-id' ),
				log_data = JSON.parse( this.model.get( 'api_data' ) ),
				post_data = {
					action: action === 'delete' ? 'tve_dash_api_delete_log' : 'tve_dash_api_form_retry',
					connection_name: this.model.get( 'connection' ),
					list_id: this.model.get( 'list_id' ),
					log_id: this.model.get( 'id' ),
					nonce: TVE_Dash.ERROR_LOG.nonce
				};
			$.extend( post_data, log_data );
			if ( action == 'retry' ) {
				$row.find( '.retry span' ).addClass( 'mdi-pulse' );
			}
			var jqxhr = $.ajax( {
				url: ajaxurl,
				type: 'post',
				data: post_data,
				dataType: 'json'
			} );

			jqxhr.fail( function () {
				if ( action == 'retry' ) {
					$row.find( '.retry span' ).removeClass( 'mdi-pulse' );
				}
				alert( TVE_Dash_Const.translations.RequestError );
			} );

			jqxhr.done( function ( data ) {
				if ( data.status === 'error' ) {
					if ( action == 'retry' ) {
						$row.find( '.retry span' ).removeClass( 'mdi-pulse' );
					}
					$row.css( 'background-color', 'pink' );
					setTimeout( function () {
						$row.css( {
							'background-color': ''
						} );
					}, TVE_Dash.ERROR_LOG.delay );
				} else if ( data.status === 'success' ) {
					$row.css( 'background', 'lightgreen' );
					setTimeout( function () {
						$row.remove();
					}, TVE_Dash.ERROR_LOG.delay );
				} else {
					$message_container.html( 'Something went wrong here, please contact Thrive developers team!' )
				}
				if ( action == 'retry' ) {
					$row.find( '.retry span' ).removeClass( 'mdi-pulse' );
				}
			} );
			return this;
		}
	} );

	TVE_Dash.ERROR_LOG.views.Table = Backbone.View.extend( {
		el: '.tvd-error-log-table',
		events: {
			'change #tvd-row-nr-per-page': 'changeNrOfRows',
			'click .tvd-jump-to-page-button': 'jumpToPage',
			'click .tvd-previous-page': 'previousPage',
			'click .tvd-next-page': 'nextPage',
			'keypress .tvd-jump-to-page': 'keyread',
			'click .tvd-error-log-connection, .tvd-error-log-date': function ( event ) {
				this.$el.find( '.tvd-order-filter-active' ).removeClass( 'tvd-order-filter-active' );
				$( event.currentTarget ).addClass( 'tvd-order-filter-active' );

				var filter = $( event.currentTarget ).attr( 'data-val' );
				if ( filter == 'connection' ) {
					this.collection.orderby = 'connection';
				}
				if ( filter == 'date' ) {
					this.collection.orderby = 'date';
				}
				if ( this.collection.order == 'DESC' ) {
					this.collection.order = 'ASC';
					$( event.currentTarget ).find( '.tvd-orderby-icons' ).removeClass( 'tvd-icon-expanded' ).addClass( 'tvd-icon-collapsed' );
				} else {
					$( event.currentTarget ).find( '.tvd-orderby-icons' ).removeClass( 'tvd-icon-collapsed' ).addClass( 'tvd-icon-expanded' );
					this.collection.order = 'DESC';
				}
				var filter_order_class = 'tvd_filter_' + this.collection.order.toLowerCase();
				event.currentTarget.className = event.currentTarget.className.replace( /\btvd_filter_.*?\b/g, '' );
				$( event.currentTarget ).addClass( filter_order_class );
				this.render();
			}
		},
		previousPage: function () {
			if ( this.collection.current_page > 1 ) {
				this.collection.current_page = -- this.collection.current_page;
				this.render();
			}
		},
		nextPage: function () {
			if ( this.collection.current_page < this.collection.total_pages ) {
				this.collection.current_page = ++ this.collection.current_page;
				this.render();
			}
		},
		keyread: function ( event ) {
			if ( event.which === 13 ) {
				this.jumpToPage();
			}
		},
		jumpToPage: function () {
			var at = parseInt( this.$el.find( '#current-page-input' ).val() );
			this.$el.find( '#current-page-input' ).removeClass( 'tvd-invalid' );

			if ( at <= this.collection.total_pages && at > 0 ) {
				this.collection.current_page = this.$el.find( '#current-page-input' ).val();
				this.render();
			} else {
				TVE_Dash.err( this.$el.find( '.tvd-jump-to-page' ).attr( 'data-error' ) );
				this.$el.find( '#current-page-input' ).addClass( 'tvd-invalid' );
			}
		},
		changeNrOfRows: function () {
			this.collection.per_page = this.$el.find( '#tvd-row-nr-per-page' ).val();

			this.collection.current_page = 1;

			this.render();
		},
		renderPageSettings: function () {
			this.$el.find( '.tvd-error-log-item-number' ).html( this.collection.total_items );
			this.$el.find( '.tvd-error-log-current-page' ).html( this.collection.current_page );
			this.$el.find( '.tvd-error-log-total-pages' ).html( this.collection.total_pages );
			this.$el.find( '#tvd-row-nr-per-page' ).val( this.collection.per_page );
			this.$el.find( '.tvd-next-page' ).removeClass( 'tvd-nav-disabled' );
			this.$el.find( '.tvd-previous-page' ).removeClass( 'tvd-nav-disabled' );
			if ( this.collection.current_page == this.collection.total_pages ) {
				this.$el.find( '.tvd-next-page' ).addClass( 'tvd-nav-disabled' );
			}
			if ( this.collection.current_page == 1 ) {
				this.$el.find( '.tvd-previous-page' ).addClass( 'tvd-nav-disabled' );
			}
			this.$el.find( '.tvd-jump-to-page' ).removeClass( 'tvd-invalid' );
		},
		render: function () {
			TVE_Dash.showLoader();
			this.$el.find( '#tvd-row-nr-per-page' ).select2();
			this.$el.find( '.tvd-error-log-table-content' ).html( '' );
			var _call = this.collection.fetch(),
				self = this;
			_call.done( function () {
				self.collection.each( self.renderOne, self );
				self.renderPageSettings();
			} ).always( function () {
				setTimeout( function () {
					TVE_Dash.hideLoader();
				}, 300 );
			} );
			return this;
		},
		renderOne: function ( item ) {
			var view = new TVE_Dash.ERROR_LOG.views.LogEntryView( {model: item} );
			this.$el.find( '.tvd-error-log-table-content' ).append( view.render().$el );

			return this;
		}
	} );
	$( function () {
		TVE_Dash.ERROR_LOG.nonce = $( "#_wpnonce" ).val();
		TVE_Dash.ERROR_LOG.errorLogPage = new TVE_Dash.ERROR_LOG.collections.LogEntries();
		window.errorLog = new TVE_Dash.ERROR_LOG.views.Table( {
			collection: TVE_Dash.ERROR_LOG.errorLogPage
		} );

		window.errorLog.render();
	} );
} );
