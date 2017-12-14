jQuery(document).ready(function() {
	
	jQuery('.pb_backupbuddy_remotetest').click(function(e) {
		jQuery( '.pb_backupbuddy_loading' ).show();
		//jQuery(this).html('Testing ...');
		jQuery.post( jQuery(this).attr( 'alt' ), jQuery(this).closest( 'form' ).serialize(), 
			function(data) {
				jQuery( '.pb_backupbuddy_loading' ).hide();
				alert( data );
			}
		); //,"json");
		jQuery(this).html('Test these settings');
		return false;
	});
	
	
	
	
	
	
	
	// NOT CURRENTLY BEING USED.
	jQuery( '.pb_backupbuddy_sendlog' ).click( function(e) {
		if ( confirm( 'This will send the logging information in the box above to PluginBuddy.com support. You will be given a unique identifier code that you may provide and post in support so that support staff may view your sensitive logs without having to post them publicly in the forum. You will NOT receive a response to your submitted logs unless you open a support request via the forum. Are you sure you want to submit this data?' ) ) {
			alert("You pressed OK!");
		} else {
		  alert("You pressed Cancel!");
		}
		
		/*
		result_obj = jQuery( '#pb_stats_' + jQuery(this).attr( 'rel' ) );
		
		jQuery.post( jQuery(this).attr( 'alt' ), jQuery(this).closest( 'form' ).serialize(), 
			function(data) {
				loading.hide();
				result_obj.html( data );
			}
		); //,"json");
		*/
		return false;
	});
	
	
	
	/*
	jQuery('.pb_backupbuddy_selectdestination').click(function(e) {
		var win = window.dialogArguments || opener || parent || top;
		win.pb_backupbuddy_selectdestination( jQuery(this).attr( 'href' ), jQuery(this).attr( 'alt' ), '<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>' );
		win.tb_remove();
		return false;
	});
	*/
	
	
});