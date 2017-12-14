/* =========================================================
 * vc-roles.js v1.0.0
 * =========================================================
 * Copyright 2015 WPBakery
 *
 * VC roles settings tab script.
 * ========================================================= */
(function ( $ ) {
	'use strict';
	$( function () {
		var $form = $( '[data-vc-roles="form"]' );
		$( '[data-vc-roles="part-state"]' ).click( function () {
			var $this = $( this );
			$this.data( 'vcCustomSelector', $this.find( ':selected' ).data( 'vcCustomSelector' ) || '' );
		} ).change( function () {
			var value, part, $customBlock, $this, customSelector;
			$this = $( this );
			part = $this.data( 'vcRolePart' );
			$customBlock = $( '[data-vc-role-related-part="' + part + '"]' );
			if ( '*' === $customBlock.data( 'vcRolePartState' ) ) {
				return false;
			}
			value = $this.val();
			customSelector = $this.data( 'vcCustomSelector' ) || '';

			if ( $customBlock.data( 'vcRolePartState' ).toString() === value ) {
				$customBlock.addClass( 'vc_visible' );
				if ( customSelector.length ) {
					$customBlock.find( $this.data( 'vcCustomSelector' ) ).prop( 'checked', true );
				}
			} else {
				$customBlock.removeClass( 'vc_visible' );
			}
		} );
		$form.submit( function ( e ) {
			var $submitButton, data = {};
			e.preventDefault();
			$submitButton = $( '#submit_btn' );
			$submitButton.attr( 'disabled', true );
			$( '#vc_wp-spinner' ).addClass( 'is-active' );
			data.action = $( '#vc_settings-roles-action' ).val();
			data.vc_nonce_field = $( '#vc_nonce_field' ).val();
			data.vc_roles = {};
			$( '[data-vc-role]' ).each( function () {
				var $this = $( this ),
					role = $this.data( 'vcRole' ),
					roleData = {};
				$this.find( 'select' ).each( function () {
					var $this = $( this ),
						part = $this.data( 'vcPart' );
					if ( undefined === roleData[ part ] ) {
						roleData[ part ] = {};
					}
					roleData[ part ][ $this.data( 'vcName' ) ] = $this.val();
				} );
				$this.find( '[data-vc-role-related-part].vc_visible [data-vc-name][type="checkbox"]' ).each( function () {
					var $this = $( this ),
						part = $this.data( 'vcPart' );
					if ( undefined === roleData[ part ] ) {
						roleData[ part ] = {};
					}
					roleData[ part ][ $this.data( 'vcName' ) ] = $this.is( ':checked' ) ? $this.val() : '0';
				} );
				data.vc_roles[ role ] = JSON.stringify( roleData );
			} );
			$.ajax( {
				url: $form.attr( 'action' ),
				type: 'POST',
				dataType: 'json',
				data: data,
				context: this
			} ).done( function ( data ) {
				var $messageHtml;
				$( '#vc_wp-spinner' ).removeClass( 'is-active' );
				if ( data.message ) {
					$messageHtml = $( '<div id="vc_roles-message" class="updated vc_updater-result-message hidden"><p><strong></strong></p></div>' );
					$messageHtml.find( 'strong' ).text( data.message );
					$messageHtml.insertBefore( $submitButton ).fadeIn( 100 );
					window.setTimeout( function () {
						$messageHtml.slideUp( 100, function () {
							$( this ).remove();
							$submitButton.attr( 'disabled', false );
						} );
					}, 2000 );
				}
			} );
		} );
	} );
	$( '[data-vc-accordion]' ).on( 'show.vc.accordion', function () {
		$( this ).addClass( 'vc_opened' );
	} ).on( 'hide.vc.accordion', function () {
		$( this ).removeClass( 'vc_opened' );
	} );
	$( '[data-vc-ui-element="panel-tab-control"]' ).click( function ( e ) {
		var $control = $( this ),
			$fieldset = $control.parents( 'fieldset' ).first(), // too slow need to change
			filter = '.vc_wp-form-table',
			filterValue;
		e.preventDefault();
		$( '[data-vc-ui-element="panel-tabs-controls"] .vc_active', $fieldset ).removeClass( 'vc_active' );
		$control.parent().addClass( 'vc_active' );
		filterValue = $control.data( 'filter' );
		$fieldset.attr( 'data-vc-roles-filter-value', filterValue );
		filter += ' ' + filterValue;
		$( '.vc_wp-form-table [data-vc-capability]', $fieldset ).addClass( 'vc_hidden' );
		$( filter, $fieldset ).removeClass( 'vc_hidden' );
		// $( 'thead [data-vc-roles-select-all-checkbox]', $fieldset ).trigger('change');
	} );
	$( '[data-vc-roles="table-checkbox"]' ).change( function () {
		var $this = $( this );
		if ( $this.is( ':checked' ) ) {
			$this.parents( 'tr:first' ).find( '[data-vc-name!="' +
				$this.attr( 'data-vc-name' ) + '"]:checked' ).prop( 'checked', false );
		} else {
			$this.parents( '[data-vc-roles="table"]' )
				.first()
				.find( '[data-vc-roles-select-all-checkbox="' + $this.data( 'vcCap' ) + '"]' )
				.prop( 'checked', false );
		}
	} );
	$( '[data-vc-roles-select-all-checkbox]' ).change( function () {
		var $this, checked, $parent,$relatedControl, value;
		$this = $( this );
		checked = $this.is( ':checked' );
		$parent = $this.parents( '[data-vc-roles="table"]' ).first();
		$relatedControl = $parent.find($this.data('vcRelatedControls'));
		value = $this.data( 'vcRolesSelectAllCheckbox' );
		$parent.find( '[data-vc-cap="' + value + '"]:visible' )
			.prop( 'checked', checked );
		$relatedControl.prop('checked', checked);
		if ( checked ) {
			_.defer(function(){
				$parent.find('[data-vc-roles-select-all-checkbox!=' + value + ']:not([data-vc-cap])' )
					.prop('checked', false );
				$parent.find( '[data-vc-cap!="' + value + '"]:not([data-vc-roles-select-all-checkbox]):visible' )
					.prop( 'checked', false );
			});
		}
	} );
	$( '[data-vc-role-related-part].vc_visible [data-vc-roles="table"]' ).each( function () {
		var $table = $( this );
		$table.find( 'thead [data-vc-roles-select-all-checkbox]' ).each( function () {
			var $this = $( this ),
				value = $this.data( 'vcRolesSelectAllCheckbox' );
			if ( ! $table.find( '[data-vc-cap="' + value + '"]:not(:checked)' ).length ) {
				$this.prop( 'checked', true );
				$table.find( 'tfoot [data-vc-roles-select-all-checkbox="' + value + '"]' ).prop( 'checked', true );
			}
		} );
	} );
})( window.jQuery );