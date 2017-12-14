jQuery( function( $ ) {

	$( 'body' ).on( 'change', '.menu-item-noo-mega-menu-enable', function() {
		var $this   = $( this );
		var $container = $this.closest( '.noo-menu-form' );
		if( $this.prop( 'checked' ) ) {
			$container.find('.megamenu-child-options').show();
			var column = $container.find('.noo-mega-menu-columns select option:selected').val();
			if( column.substr(column.length - 1) > 4 ) {
				$container.find('.noo-submenu-alignment select option:selected').removeAttr('selected');
				$container.find('.noo-submenu-alignment select option[value=full-width]').attr("selected","selected");
			}
		} else {
			$container.find('.megamenu-child-options').hide();
			var currentAlignment = $container.find('.noo-submenu-alignment select option:selected');
			if( currentAlignment.val() == 'full-width' ) {
				currentAlignment.removeAttr('selected');
				$container.find('.noo-submenu-alignment select option:first-child').attr("selected","selected");
			}
		}
	} );
} );