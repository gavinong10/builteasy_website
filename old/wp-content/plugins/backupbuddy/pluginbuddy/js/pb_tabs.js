jQuery(document).ready(function() {
	
	// Change tab on click.
	jQuery( '.backupbuddy-tabs-wrap .nav-tab[href^="#"]' ).click( function(e){ /* ignores any non hashtag links since they go direct to a URL... */
		e.preventDefault();
		
		// Hide all tab blocks.
		thisTabBlock = jQuery(this).closest( '.backupbuddy-tabs-wrap' );
		thisTabBlock.find( '.backupbuddy-tab' ).hide();
		
		// Update selected tab.
		thisTabBlock.find( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
		jQuery(this).addClass( 'nav-tab-active' );
		
		// Show the correct tab block.
		//targetDivID = jQuery(this).attr( 'href' ).substring(1);
		thisTabBlock.find( jQuery(this).attr( 'href' ) ).show();
	});
	
	// Change tab on click -- AJAX version.
	jQuery( '.backupbuddy-tabs-wrap .nav-tab[href^="javascript"]' ).click( function(e){ /* ignores any non hashtag links since they go direct to a URL... */
		// Hide all tab blocks.
		thisTabBlock = jQuery(this).closest( '.backupbuddy-tabs-wrap' );
		thisTabBlock.find( '.backupbuddy-tab' ).hide();
		
		// Update selected tab.
		thisTabBlock.find( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
		jQuery(this).addClass( 'nav-tab-active' );
		
		// Show the correct tab block.
		//targetDivID = jQuery(this).attr( 'href' ).substring(1);
		thisTabBlock.find( jQuery(this).attr( 'data-ajax' ) ).show();
	});
	
	// Auto-display the correct tab on load if specifying a non-default.
	jQuery( '.nav-tab-active' ).each( function(){
		jQuery(this).click();
	});
});