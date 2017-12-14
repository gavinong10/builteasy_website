function importbuddy_loadRestoreEvents() {
	
	
	// Update status log details, messages, or errors.
	jQuery('#backupbuddy_messages').bind('backupbuddy_details backupbuddy_message backupbuddy_error backupbuddy_warning', function(e, json) {
		classType = '';
		
		if ( 'backupbuddy_error' == e.type ) {
			json.data = backupbuddyError( json.data );
			classType = 'error';
		}
		if ( 'backupbuddy_warning' == e.type ) {
			json.data = backupbuddyWarning( json.data );
			classType = 'warning';
		}
	
		backupbuddy_log( json, classType );
	});
	
	
	
	
	// A backup function (step) began.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_startFunction', function(e, json) {
		functionInfo = jQuery.parseJSON(json.data );
		html = '<div class="backup-step backup-step-primary backup-function-' + functionInfo.function + '"><span class="backup-step-status backup-step-status-working"></span><span class="backup-step-title">' + functionInfo.title + '</span></div>';
		jQuery( '.backup-steps' ).append( html );
		if ( '' !== backupbuddy_currentFunction ) {
			backupbuddy_log( 'Warning #237832a: A function `' + functionInfo.function + '` started before a prior function `' + backupbuddy_currentFunction + '` was completed.' );
		}
		backupbuddy_currentFunction = functionInfo.function;
	});
	
	
	// A backup function (step) finished.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_finishFunction', function(e, json) {
		functionInfo = jQuery.parseJSON( json.data );
		jQuery( '.backup-function-' + functionInfo.function ).find( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-finished' );
		if ( functionInfo.function !== backupbuddy_currentFunction ) {
			backupbuddy_log( 'Warning #237832b: A function `' + functionInfo.function + '` completed that does not match the function which was thought to be running `' + backupbuddy_currentFunction + '`.' );
		}
		backupbuddy_currentFunction = '';
	});
	
	
	// Track minor events so we can detect certain things not finishing, such as importbuddy generation.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_startAction', function(e, json) {
		console.log( 'Starting action: ' + json.data );
		if ( '' !== backupbuddy_currentAction ) {
			backupbuddy_log( 'Warning #3278374a: An action `' + json.data + '` started before a prior action `' + backupbuddy_currentAction + '` was completed.' );
		}
		backupbuddy_currentAction = json.data;
		backupbuddy_currentActionStart = unix_timestamp();
		backupbuddy_currentActionLastWarn = 0;
	});
	
	
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_finishAction', function(e, json) {
		console.log( 'Finishing action: ' + json.data );
		if ( json.data !== backupbuddy_currentAction ) {
			backupbuddy_log( 'Warning #3278374b: An action `' + json.data + '` completed that does not match the action `' + backupbuddy_currentAction + '` which was thought to be running.' );
		}
		backupbuddy_currentAction = '';
		backupbuddy_currentActionStart = 0;
		backupbuddy_currentActionLastWarn = 0;
	});
	
	
	
	
	// An error was encountered running a function.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_errorFunction', function(e, json) {
		jQuery( '.backup-function-' + json.data ).find( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-error' );
	});
	
	
	// Start a subfunction. These are typically more minor, though still notable, events.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_startSubFunction', function(e, json) {
		functionInfo = jQuery.parseJSON(json.data );
		html = '<div class="backup-step backup-step-secondary backup-function-' + functionInfo.function + '"><span class="backup-step-status"></span><span class="backup-step-title">' + functionInfo.title + '</span></div>';
		jQuery( '.backup-steps' ).append( html );
	});
	
	
	
	// An error message was sent from the server.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_error', function(e, json) {
		console.log( 'BACKUPBUDDY ERROR: ' + json.data );
	});
	
	//var backupbuddy_actions = [];
	
	
	
	
	
	// A warning message was sent from the server.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_warning', function(e, json) {
		html = '<span class="backup-step-status backup-step-status-warning"></span><div class="backup-step backup-step-secondary"><span class="backup-step-title">' + json.data + '</span></div>';
		jQuery( '.backup-steps' ).append( html );
	});
	
	
	// The entire backup process has been halted, whether by BackupBuddy or the user.
	jQuery('#backupbuddy_messages').bind( 'backupbuddy_haltScript', function(e, json) {
	
		if ( 0 === keep_polling ) { // Only show once.
			return;
		}
	
		keep_polling = 0; // Stop polling server for status updates.
	
		jQuery( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-error' ); // Anything that was currently running turns into an error.
		jQuery( '#pb_backupbuddy_stop' ).css( 'visibility', 'hidden' );
		jQuery( '.pb_backupbuddy_blinkz' ).css( 'background-position', 'top' ); // turn off led
		jQuery( '#pb_backupbuddy_slot1_led' ).removeClass( 'pb_backupbuddy_blinkz' ); // disable blinking
		jQuery( '#pb_backupbuddy_slot2_led' ).removeClass( 'pb_backupbuddy_blinkz' ); // disable blinking
		jQuery( '#pb_backupbuddy_slot3_led' ).removeClass( 'pb_backupbuddy_blinkz' ); // disable blinking
		jQuery( '#pb_backupbuddy_slot4_led' ).removeClass( 'pb_backupbuddy_empty' ); // Remove empty LED hole.
		jQuery( '#pb_backupbuddy_slot4_led' ).addClass( 'pb_backupbuddy_codered' ); // set checkmark
	
		// Briefly wait
		//setTimeout(function(){
		backupbuddy_log( '***' );
		if ( '' !== backupbuddy_currentFunction ) {
			backupbuddy_log( '* Unfinished function: `' + backupbuddy_currentFunction + '`.' );
		} else {
			backupbuddy_log( '* No in-progress function detected.' );
		}
	
		if ( '' !== backupbuddy_currentAction ) {
			backupbuddy_log( '* Unfinished action: `' + backupbuddy_currentAction + '` (' + ( unix_timestamp() - backupbuddy_currentActionStart ) + ' seconds ago).' );
		} else {
			backupbuddy_log( '* No in-progress action detected.' );
		}
		backupbuddy_log( '***' );
	
		// Calculate suggestions.
		/*
		 if ( 'importbuddyCreation' == backupbuddy_currentAction ) {
		 suggestions.push( {
		 description: 'BackupBuddy by default includes a copy of the restore tool, importbuddy.php, inside the backup ZIP file for retrieval if needed in the future.',
		 quickFix: 'Turn off inclusion of ImportBuddy. Navigate to Settings: Advanced Settings / Troubleshooting tab: Uncheck "Include ImportBuddy in full backup archive".',
		 solution: 'Increase available PHP memory.'
		 } );
		 }
		 */
	
		backupbuddy_showSuggestions( suggestions );
	
		setTimeout(function(){
			backupbuddy_log( '* The backup has halted.' );
		},500);
		alert( 'The backup has halted.' );
		//},1000);
	
	});

} // end load