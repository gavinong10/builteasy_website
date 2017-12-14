/**
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
