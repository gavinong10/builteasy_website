/**
 * contains general backbone views definitions
 */

/**
 * utility functions to use throughout Thrive products
 *
 * @type {object}
 */
var TVE_Dash = TVE_Dash || {};
TVE_Dash.views = TVE_Dash.views || {};

(function ( $ ) {

	/**
	 * base class for modals
	 *
	 * backbone view wrapped over a modal div from materialize
	 */
	TVE_Dash.views.Modal = Backbone.View.extend( {
		className: 'tvd-modal',
		id: 'tvd-modal-base',
		/**
		 * .tvd-modal-content div reference
		 */
		$_content: '',
		/* this will be populated with data coming from params when instantiating the view / modal */
		data: {},
		/**
		 * used to setup this.data field - it will set all constructor arguments into this.data so that they are available
		 * @param {object} args
		 */
		initialize: function ( args ) {

			this.beforeInitialize( args );

			this.data = args;
			var self = this;

			_.each( args, function ( v, k ) {

				if ( typeof self[k] === 'undefined' ) {
					self[k] = v;
				}
			} );

			this.afterInitialize( args );
		},
		/**
		 * we should not override this, use afterRender() instead
		 * this does not open the modal, it just renders it and appends it to the body tag
		 * @returns {TVE_Dash.views.Modal}
		 */
		render: function () {
			var self = this;
			this.$el.empty();
			this.$el.appendTo( 'body' );
			// make the whole data object available to the template
			this.data = _.extend( {
				model: this.model
			}, this.data );

			this.$el.html( this.template( this.data ) );

			if ( this.data['max-width'] ) {
				/* max-width should always be sent in % */
				this.$el.css( 'max-width', this.data['max-width'] );
			} else {
				this.$el.css( 'max-width', '' );
			}

			if ( this.data['no_close'] ) {
				this.$el.find( '.tvd-modal-close' ).remove();
			} else if ( ! this.$el.children( '.tvd-modal-close' ).length ) {
				this.$el.append( '<a href="javascript:void(0)" class="tvd-modal-action tvd-modal-close tvd-modal-close-x"><i class="tvd-icon-close2"></i></a>' );
			}

			if ( this.data['title'] ) {
				var $title = this.$el.find( 'h3.tvd-modal-title' );
				if ( ! $title.length ) {
					$title = $( '<h3 class="tvd-modal-title"></h3>' );
					this.$el.find( '.tvd-modal-content' ).prepend( $title );
				}
				$title.html( this.data['title'] );
			}

			if ( this.data['width'] ) {
				this.$el.css( 'width', this.data['width'] );
			}
			if ( this.data['height'] ) {
				this.$el.css( 'height', this.data['height'] + 'px' );
			}

			/**
			 * rebind the wistia listeners
			 */
			if ( window.rebindWistiaFancyBoxes ) {
				window.rebindWistiaFancyBoxes();
			}

			this.$_content = this.$el.find( '.tvd-modal-content' );

			this.afterRender();

			/**
			 * when the autocomplete opens, we need to scroll the .tvd-modal-content so that the results are visible
			 */
			this.$el.on( 'autocompleteopen', _.bind( this.autocomplete_scroll, this ) );

			this.$el.on( 'tvdpickeropen', _.bind( this.pickadate_scroll, this ) );

			return this;
		},
		/**
		 * called when a jquery autocomplete widget is opened inside a modal
		 * scrolls the modal frame to contain as much of the autocomplete element as possible
		 *
		 * @param event
		 * @param ui
		 */
		autocomplete_scroll: function ( event, ui ) {
			var $input = $( event.target ),
				$ul = $input.parent().find( 'ul.ui-autocomplete' ),
				ui_height = $input.outerHeight() + $ul.outerHeight(),
				ui_top = $input.offset().top,
				c_top = this.$_content.offset().top,
				delta_scroll = ui_top - c_top + this.$_content.scrollTop() - 25;

			if ( ui_height + ui_top > this.$_content.outerHeight() + c_top ) {
				this.$_content.animate( {
					scrollTop: delta_scroll
				} );
			}
		},
		/**
		 * called when a datepicker (pickadate) is opened inside this modal
		 * it scroll the modal frame so that the date picker is fully visible
		 *
		 * @param {object} event jQuery event instance
		 * @param {object} P Picker instance
		 */
		pickadate_scroll: function ( event, P ) {
			var $input = $( event.target ),
				$picker = P.$root,
				picker_height = $picker.outerHeight( true ) + 40,
				picker_top = $input.offset().top - picker_height / 2,
				c_top = this.$_content.offset().top;

			if ( picker_top + picker_height > this.$_content.outerHeight() + c_top ) {
				this.$_content.animate( {
					scrollTop: picker_height + picker_top - c_top - this.$_content.outerHeight() + this.$_content.scrollTop() - 25
				} );
			} else if ( picker_top < c_top ) {
				this.$_content.animate( {
					scrollTop: this.$_content.scrollTop() - ( c_top - picker_top )
				} );
			}
		},
		/**
		 * auto-focus the first input and bind the ENTER event
		 *
		 * @param {object} [$root] optional, jquery wrapper over a DOM element
		 */
		input_focus: function ( $root ) {
			$root = $root || this.$el;

			var $inputs = $root.find( 'input:not(.tvd-select-dropdown),textarea,.tve-confirm-delete-action' );
			$inputs.filter( ':visible' ).filter( ':not(.tvd-no-focus)' ).first().focus().select();

			$root.add( $inputs ).not( '.tvd-skip-modal-save' ).off( 'keyup.tvd-save' ).on( 'keyup.tvd-save', function ( e ) {
				if ( e.which === 13 ) {
					if ( this.tagName.toLowerCase() === 'textarea' ) {
						event.preventDefault();
					} else {
						$root.find( '.tvd-modal-submit' ).filter( ':visible' ).first().click();
					}
					return false;
				}
			} );
		},
		/**
		 * triggered immediately after the rendering is completed (after the template is added to DOM) but before showing the modal
		 * override this to populate extra stuff in the view
		 */
		afterRender: function () {
			return this;
		},
		/**
		 * open the modal
		 * options for the modal can be sent when instantiating the view
		 *
		 * @returns {TVE_Dash.Modal}
		 */
		open: function () {
			var self = this;

			var options = _.extend( {
				in_duration: 200,
				out_duration: 300,
				dismissible: true,
				ready: function () {
					self.onOpen.call( self );
					/**
					 * allow also 'afterOpen' callback
					 */
					if ( typeof self.afterOpen === 'function' ) {
						self.afterOpen.call( self );
					}
					TVE_Dash.materialize( self.$el );
					self.input_focus();

					if ( typeof self.afterMaterialize === 'function' ) {
						self.afterMaterialize.call( self );
					}
				},
				complete: function () {
					delete TVE_Dash.opened_modal_view;
					self.$el.css( 'width', '' ).css( 'height', '' );
					self.beforeClose.call( self );
					self.remove();
					self.onClose.call( self );
					/**
					 * allow also 'afterClose' callback
					 */
					if ( typeof self.afterClose === 'function' ) {
						self.afterClose.call( self );
					}
				}
			}, this.data );

			this.modal_options = options;

			this.$el.openModal( options );

			TVE_Dash.opened_modal_view = this;

			return this;
		},
		close: function () {
			this.$el.closeModal( this.modal_options );
			return this;
		},
		/**
		 * triggered after the modal has been opened
		 */
		onOpen: function () {
		},
		/**
		 * triggered after the modal has been closed and the html has been removed
		 */
		onClose: function () {
		},
		/**
		 * triggered before the modal has been closed and the html has been removed
		 */
		beforeClose: function () {
		},
		/**
		 * show a preloader over this modal
		 */
		showLoader: function () {
			var _loader = this.$el.find( '.tvd-modal-preloader' );
			if ( ! _loader.length ) {
				_loader = $( TVE_Dash.tpl( 'modal-loader', {} ) );
				this.$el.prepend( _loader );
			}
			_loader.css( 'top', (
			                    this.$el.height() / 2 + this.$el.scrollTop()
			                    ) + 'px' );
			_loader.fadeIn();
			this.$el.addClass( 'tvd-modal-disable' );

			return this;
		},
		/**
		 * hide the preloader
		 */
		hideLoader: function () {
			this.$el.removeClass( 'tvd-modal-disable' );
			this.$el.find( '.tvd-modal-preloader' ).fadeOut();

			return this;
		},
		/**
		 * set the loading state on a button
		 * @param $btn
		 * @returns {*}
		 */
		btnLoading: function ( $btn ) {
			return jQuery( $btn ).addClass( 'tvd-disabled' ).prop( 'disabled', true );
		},
		beforeInitialize: function ( args ) {
			return this;
		},
		afterInitialize: function ( args ) {
			return this;
		}
	} );

	/**
	 * Modal Steps View
	 */
	TVE_Dash.views.ModalSteps = TVE_Dash.views.Modal.extend( {
		stepClass: '.tvd-modal-step',
		currentStep: 0,
		$step: null,
		events: {
			'click .tvd-modal-next-step': "next",
			'click .tvd-modal-prev-step': "prev"
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
	 * page loader view
	 * shows a global page loader
	 */
	TVE_Dash.views.PageLoader = Backbone.View.extend( {
		_state: 'closed',
		render: function () {
			this.$el.html( TVE_Dash.tpl( 'page-loader', {} ) );
			this.$el.appendTo( 'body' );
			this.loader = this.$el.find( '#tvd-hide-onload' );
		},
		open: function () {
			this.loader.show();
		},
		close: function () {
			var self = this;
			setTimeout( function () {
				self.loader.fadeOut( 100 );
			}, 200 );
		}
	} );

})( jQuery );