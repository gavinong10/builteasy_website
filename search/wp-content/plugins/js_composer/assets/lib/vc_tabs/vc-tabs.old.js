+ function ( $ ) {
	'use strict';

	var old, clickHandler, Tab;

	Tab = function ( element ) {
		this.element = $( element );
	};

	Tab.prototype.show = function () {
		var $previous, $current, hideEvent, showEvent, $target, $this, $ul, selector, $container;

		$this = this.element;
		$ul = $this.closest( 'ul' );
		selector = $this.data( 'vcTarget' );
		$container = $this.closest( $this.data( 'vcContainer' ) );

		if ( ! selector ) {
			selector = $this.attr( 'href' );
		}
		if ( $container.length ) {
			$ul = $container.find( 'ul' ).has( '[data-vc-toggle="tab"]' );
		}

		if ( $this.parent( 'li' ).hasClass( 'vc_active' ) ) {
			return;
		}
		if ( $container.length && $container.find( selector ).has( '[data-vc-toggle="tab"]' ).hasClass( 'vc_active' ) ) {
			return;
		}

		$previous = $ul.find( '.vc_active:last a' );
		$current = $this;
		// we already have $current, no need to find it again in container.
		// also this "break" the backend tab switching with data-attribute [data-vc-target=[data-model-id='adasdada']] = error
		/*if ($container.length) {
		 $current = $ul.find('[data-vc-target=' + selector + '], [href=' + selector + ']');
		 }*/

		hideEvent = $.Event( 'hide.vc.tab', {
			relatedTarget: $current[ 0 ]
		} );
		showEvent = $.Event( 'show.vc.tab', {
			relatedTarget: $previous[ 0 ]
		} );

		$previous.trigger( hideEvent );
		$current.trigger( showEvent );

		if ( showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented() ) {
			return;
		}

		$target = $container.find( selector );

		this.activate( $current.closest( 'li' ), $ul );
		this.activate( $target, $target.parent(), function () {
			$previous.trigger( {
				type: 'hidden.vc.tab',
				relatedTarget: $current[ 0 ]
			} );
			$current.trigger( {
				type: 'shown.vc.tab',
				relatedTarget: $previous[ 0 ]
			} );
		} );
	};

	Tab.prototype.activate = function ( element, container, callback ) {
		var $active;
		$active = container.find( '> .vc_active' );

		function next() {
			$active
				.removeClass( 'vc_active' )
				.end()
				.find( '[data-vc-toggle="tab"]' )
				.attr( 'aria-expanded', false );

			element
				.addClass( 'vc_active' )
				.find( '[data-vc-toggle="tab"]' )
				.attr( 'aria-expanded', true );

			callback && callback();
		}

		next();
	};

	function Plugin( option ) {
		return this.each( function () {
			var $this, data;

			$this = $( this );
			data = $this.data( 'vc.tab' );

			if ( ! data ) {
				$this.data( 'vc.tab', (data = new Tab( this )) );
			}
			if ( 'string' === typeof(option) ) {
				data[ option ]();
			}
		} );
	}

	old = $.fn.tab;

	$.fn.tab = Plugin;
	$.fn.tab.Constructor = Tab;

	$.fn.tab.noConflict = function () {
		$.fn.tab = old;
		return this;
	};

	clickHandler = function ( e ) {
		e.preventDefault();
		Plugin.call( $( this ), 'show' );
	};

	$( document )
		.on( 'click.vc.tab.data-api', '[data-vc-toggle="tab"]', clickHandler );

}( jQuery );