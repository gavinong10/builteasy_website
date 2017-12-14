// Update status log details, messages, or errors.
jQuery('#backupbuddy_messages').bind('backupbuddy_details backupbuddy_message backupbuddy_error backupbuddy_warning', function(e, json) {
	classType = '';
	
	if ( 'backupbuddy_error' == e.type ) {
		json.data = backupbuddyError( json.data );
		//json.data = '<span class="backupbuddy_log_error">' + json.data + '</span>';
		classType = 'error';
	}
	if ( 'backupbuddy_warning' == e.type ) {
		json.data = backupbuddyWarning( json.data );
		//json.data = '<span class="backupbuddy_log_warning">' + json.data + '</span>';
		classType = 'warning';
	}
	
	backupbuddy_log( json, classType );
});



function backupbuddy_finishbackup() {
	keep_polling = 0; // Stop polling server for status updates.
	statusBoxQueueEnabled = false;
	
	//jQuery( '#pb_backupbuddy_stop' ).css( 'visibility', 'hidden' );
	jQuery( '#pb_backupbuddy_stop' ).hide();
	
	// Mark any running steps as finished.
	jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
	
	//jQuery( '.bb_progress-step-active').removeClass('bb_progress-step-active');
	jQuery( '.bb_progress-step-unfinished').addClass( 'bb_progress-step-completed' );
	jQuery( '.bb_progress-step-unfinished').addClass( 'bb_progress-step-finished' );
	jQuery( '.bb_progress-step-unfinished').removeClass( 'bb_progress-step-unfinished' );
	
	jQuery( '.backup-step-active' ).addClass( 'backup-step-finished' ).removeClass( 'backup-step-active' );
	
	jQuery( '#backup-function-backup_success' ).addClass( 'backup-step-finished' );
	jQuery( '#backup-function-deploy_success' ).addClass( 'backup-step-finished' );
	
	// Need this to force dumping of any remaning status queue text.
	setTimeout(function(){
		jQuery( '.backup-step-active' ).removeClass( 'backup-step-active' );
		backupbuddy_log( 'Process Finished.', 'notice' );
		
		// Tell BackupBuddy to stop this backup in case it is still trying to run.
		backupbuddy_ajax_call_stop();
	},500);
}

// Remote backup finished during pull. NOTE: This has been moved to be handled lcoally on this server before returning log data to ajax client. See _backup-perform.php for finish_deploymentPullBackup.
function backupbuddy_deploymentPullBackupFinish() {
}


jQuery('#backupbuddy_messages').bind( 'backupbuddy_milestone', function(e, json) {
	if ( 'finish_settings' == json.data ) {

	} else if ( 'start_database' == json.data ) {

	} else if ( 'finish_database' == json.data ) {

	} else if ( 'start_files' == json.data ) {

	} else if ( 'finish_backup' == json.data ) {
		backupbuddy_finishbackup();
	/*
	} else if ( 'deploymentPullBackupFinish' == json.data ) {
		backupbuddy_deploymentPullBackupFinish();
	*/
	
	} else if ( 'finish_importbuddy' == json.data ) {
		//alert( 'importbuddy finish' );
		finishOverviewFunction( 'deploy_success' );
		//jQuery( '#backup-function-backup_success').text( 'Deployment completed successfully' );
		jQuery( '.backup-step-active' ).removeClass( 'backup-step-active' );
		
	} else {
		console.log( 'BackupBuddy unspecified milestone: `' + json.data + '`.' );
	}
});


function startOverviewFunction( functionName ) {
	if ( 0 === keep_polling ) { // dont start function after polling has stopped.
		return;
	}
	jQuery('#backup-function-' + functionName ).addClass('backup-step-active');
}
function finishOverviewFunction( functionName ) {
	if ( 'deploy_runningImportBuddy' == functionName ) { // Mark all functions finish when they finish except deploy function runningImportBuddy.
		return;
	}
	jQuery('#backup-function-' + functionName ).removeClass('backup-step-active');
	jQuery('#backup-function-' + functionName ).addClass('backup-step-finished');
}


// A backup function (step) began.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_startFunction', function(e, json) {
	
	try {
		functionInfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing startFunction JSON:' + json.data );
	}
	
	//console.log( 'Start function: `' + functionInfo.function + '`.' );
	startOverviewFunction( functionInfo.function );
	
	// End any in-progress steps.
	/*
	if ( jQuery( '.bb_progress-step-active' ).hasClass( 'bb_progress-step-files' ) ) { // function started while Files active.
	} else { // Anything else.
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
	}
	*/
	
	// If already set as completed then remove that since we are starting the function again.
	//jQuery('.bb_progress-step-' + functionInfo.function ).removeClass( 'bb_progress-step-completed' );
	
	if ( ( 'pre_backup' == functionInfo.function ) && ( jQuery('.bb_progress-step-settings').length > 0 ) ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-settings' ).addClass( 'bb_progress-step-active' );
	} else if ( ( 'backup_create_database_dump' == functionInfo.function ) && ( jQuery('.bb_progress-step-database').length > 0 ) ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-database' ).addClass( 'bb_progress-step-active' );
	} else if ( ( 'backup_zip_files' == functionInfo.function ) && ( jQuery('.bb_progress-step-files').length > 0 ) ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-files' ).addClass( 'bb_progress-step-active' );
	
	
	// Deploy both directions.
	} else if ( ( 'deploy_start' == functionInfo.function ) && ( jQuery('.bb_progress-step-deployTransfer').length > 0 ) ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-deployTransfer' ).addClass( 'bb_progress-step-active' );
		//startOverviewFunction( 'pre_backup' );
		startOverviewFunction( 'backup_create_database_dump' );
		
		
	// Deploy PUSH.
	} else if ( ( 'deploy_push_start' ) == functionInfo.function ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-deployTransfer' ).addClass( 'bb_progress-step-active' );
		startOverviewFunction( 'deploy_sendContent' );
	} else if ( ( 'deploy_push_renderImportBuddy' == functionInfo.function ) && ( jQuery('.bb_progress-step-deployRestore').length > 0 ) ) {
		finishOverviewFunction( 'deploy_sendContent' );
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-deployRestore' ).addClass( 'bb_progress-step-active' );
	} else if ( 'deploy_runningImportBuddy' == functionInfo.function ) {
		jQuery( '#backup-function-deploy_runningImportBuddy-secondary' ).slideDown();
	
	
	// Deploy PULL.
	} else if ( 'deploy_pull_files' == functionInfo.function ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-deployTransfer' ).addClass( 'bb_progress-step-active' );
		startOverviewFunction( 'deploy_sendContent' );
	} else if ( 'deploy_pull_renderImportBuddy' == functionInfo.function ) {
		jQuery( '.bb_progress-step-active' ).addClass( 'bb_progress-step-completed' ).removeClass( 'bb_progress-step-active' );
		jQuery( '.bb_progress-step-deployRestore' ).addClass( 'bb_progress-step-active' );
		finishOverviewFunction( 'deploy_sendContent' );
		startOverviewFunction( 'deploy_runningImportBuddy' );
	}
	
	
	
	
	html = '<div class="backup-step backup-step-primary backup-function-' + functionInfo.function + '"><span class="backup-step-status backup-step-status-working"></span><span class="backup-step-title">' + functionInfo.title + '</span></div>';
	jQuery( '.backup-steps' ).append( html );
	if ( '' !== backupbuddy_currentFunction ) {
		backupbuddy_log( 'Warning #237832a: A function `' + functionInfo.function + '` started before a prior function `' + backupbuddy_currentFunction + '` was completed.' );
	}
	backupbuddy_currentFunction = functionInfo.function;
});


// A backup function (step) finished.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_finishFunction', function(e, json) {
	try {
		functionInfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing finishFunction JSON:' + json.data );
	}
	
	finishOverviewFunction( functionInfo.function );
	
	
	if ( 'pre_backup' == functionInfo.function ) {
		//jQuery('.bb_progress-step-settings').removeClass( 'bb_progress-step-active' );
		//jQuery('.bb_progress-step-settings').addClass( 'bb_progress-step-completed' );
	} else if ( 'backup_create_database_dump' == functionInfo.function ) {
		//jQuery('.bb_progress-step-database').removeClass( 'bb_progress-step-active' );
		//jQuery('.bb_progress-step-database').addClass( 'bb_progress-step-completed' );
		last_sql_change = 0; // Clear out checking for sql file size to increase.
	} else if ( 'backup_zip_files' == functionInfo.function ) {
		//jQuery('.bb_progress-step-files').removeClass( 'bb_progress-step-active' );
		//jQuery('.bb_progress-step-files').addClass( 'bb_progress-step-completed' );
		last_archive_change = 0; // Clear out checking for archive size to increase.
		//console.log( 'FinishZip3' );
	}
	/* else if ( 'post_backup' == functionInfo.function ) {
		jQuery('.bb_progress-step-files').removeClass( 'bb_progress-step-active' );
	}
	*/
	
	
	jQuery( '.backup-function-' + functionInfo.function ).find( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-finished' );
	
	if ( functionInfo.function !== backupbuddy_currentFunction ) {
		backupbuddy_log( 'Warning #237832b: A function `' + functionInfo.function + '` completed that does not match the function which was thought to be running `' + backupbuddy_currentFunction + '`.' );
	}
	backupbuddy_currentFunction = '';
});


// Track minor events so we can detect certain things not finishing, such as importbuddy generation.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_startAction', function(e, json) {
	backupbuddy_log( 'Starting action: ' + json.data );
	if ( '' !== backupbuddy_currentAction ) {
		backupbuddy_log( 'Warning #3278374a: An action `' + json.data + '` started before a prior action `' + backupbuddy_currentAction + '` was completed.' );
	}
	backupbuddy_currentAction = json.data;
	backupbuddy_currentActionStart = unix_timestamp();
	backupbuddy_currentActionLastWarn = 0;
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_finishAction', function(e, json) {
	backupbuddy_log( 'Finishing action: ' + json.data );
	if ( ( '' !== backupbuddy_currentAction ) && ( json.data !== backupbuddy_currentAction ) ) {
		backupbuddy_log( 'Warning #3278374b: An action `' + json.data + '` completed that does not match the action `' + backupbuddy_currentAction + '` which was thought to be running.' );
	}
	backupbuddy_currentAction = '';
	backupbuddy_currentActionStart = 0;
	backupbuddy_currentActionLastWarn = 0;
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_deployFinished', function(e, json) {
	backupbuddy_log( 'Deployment finished.' );
	//jQuery( '#backup-function-deploy_runningImportBuddy-secondary' ).slideDown();
	setTimeout(function(){
		backupbuddy_finishbackup();
		
		jQuery('.bb_actions_during').hide();
		jQuery('.bb_actions_after-deploy').show();
	}, 5000);
	
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_undoDeployURL', function(e, json) {
	jQuery( '.pb_backupbuddy_deployUndo' ).attr( 'href', json.data ).show();
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_deployFilesRemaining', function(e, json) {
	jQuery( '.backupbuddy_sendContent_progress' ).text( json.data + ' files remain.' );
});

jQuery('#backupbuddy_messages').bind( 'backupbuddy_deployFileSent', function(e, json) {
	
	sentObj = jQuery( '#backupbuddy_sendContent_sent' );
	count = parseInt( sentObj.attr( 'data-count' ), 10 ) + 1;
	sentObj.attr( 'data-count', count );
	
	jQuery( '.backupbuddy_sendContent_sent' ).text( count + ' transferred.' );
});


jQuery( '#backupbuddy_messages' ).bind( 'backupbuddy_loadImportBuddy', function(e, json) {
	try {
		importinfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing loadImportBuddy JSON:' + json.data );
	}
	
	jQuery( '#backupbuddy_deploy_runningImportBuddy' ).attr( 'src', importinfo.url + '&display_mode=embed' );
	
	console.log( 'ImportStatusLog: ' + importinfo.logurl );
	console.log( importinfo );
});



// An error was encountered running a function.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_errorFunction', function(e, json) {
	jQuery( '.backup-function-' + json.data ).find( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-error' );
});

// Start a subfunction. These are typically more minor, though still notable, events.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_startSubFunction', function(e, json) {
	try {
		functionInfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing startSubFunction JSON:' + json.data );
	}
	//console.log( 'Subfunction start function `' + functionInfo.function + '` `' + functionInfo.title + '`.' );
	
	if ( 'undefined' != typeof functionInfo.more ) {
		jQuery('#backup-secondary-function-pre_backup').append( '<div class="backup-step-title" title="' + functionInfo.function + '">' + functionInfo.title + ' <a href="javascript:void(0)" class="backup-step-view-more">View List</a></div><div class="backup-step-title backup-step-secondary-hidden">' + functionInfo.more + '</div>' );
	} else {
		jQuery('#backup-secondary-function-pre_backup').append( '<div class="backup-step-title" title="' + functionInfo.function + '">' + functionInfo.title + '</div>' );
	}
	
	jQuery('#backup-secondary-function-pre_backup').slideDown();
});


// The zip file was deleted -- backup most likely was cancelled.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_archiveDeleted', function(e, json) {
	jQuery( '#pb_backupbuddy_archive_url' ).addClass( 'button-disabled' );
	jQuery( '#pb_backupbuddy_archive_url' ).attr( 'onClick', 'return false;' );
	jQuery( '#pb_backupbuddy_archive_send' ).addClass( 'button-disabled' );
	jQuery( '#pb_backupbuddy_archive_send' ).attr( 'onClick', 'var event = arguments[0] || window.event; event.stopPropagation(); return false;' );
});


// Just pinged the server.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_ping', function(e, json) {
	backupbuddy_log( date + '&#x0009;0sec&#x0009;&#x0009;0mb&#x0009;Ping. Waiting for server . . .' );
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


// Current size of the ZIP archive (formatted; eg "50 MB").
jQuery('#backupbuddy_messages').bind( 'backupbuddy_archiveSize', function(e, json) {
	if ( last_archive_size != json.data ) { // Track time archive size last changed.
		last_archive_size = json.data;
		if ( 'post_backup' == backupbuddy_currentFunction ) { // zipping is finished so clear out change timer.
			last_archive_change = 0;
		} else {
			last_archive_change = unix_timestamp();
		}
	}
	jQuery( '.backupbuddy_archive_size' ).text( json.data );
});




jQuery('#backupbuddy_messages').bind( 'backupbuddy_startTableDump', function(e, json ) {
	jQuery( '#backup-function-current-table' ).text( '(' + json.data + ')' );
});



jQuery('#backupbuddy_messages').bind( 'backupbuddy_finishTableDump', function(e, json ) {
	jQuery( '#backup-function-current-table' ).text( '' );
});



jQuery('#backupbuddy_messages').bind( 'backupbuddy_sqlSize', function(e, json) {
	if ( last_sql_size != json.data ) { // Track time archive size last changed.
		last_sql_size = json.data;
		last_sql_change = unix_timestamp();
	}
	backupbuddy_currentDatabaseSize = parseInt( json.data, 10 ) + parseInt( backupbuddy_currentDatabaseSize, 10 );
	//console.log( 'newdbsize: ' + backupbuddy_currentDatabaseSize );
	
	totalPrettySize = backupbuddy_bytesToSize( backupbuddy_currentDatabaseSize );
	jQuery( '.backupbuddy_sql_size' ).text( totalPrettySize );
	backupbuddy_log( 'Total aggregate SQL dump size so far: ' + totalPrettySize );
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_sqlFile', function(e, json) {
	current_sql_file = json.data;
});



jQuery('#backupbuddy_messages').bind( 'backupbuddy_backupState', function(e, json) {
	try {
		stateInfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing backupState JSON:' + json.data );
	}
	
	console.log( 'BackupBuddy state (full logging mode only):' );
	console.dir( stateInfo ); // window.atob( json )
});


// The entire backup process has been halted by BackupBuddy itself.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_haltScript', function(e, json) {
	
	// Tell BackupBuddy to stop this backup in case it is still trying to run.
	backupbuddy_ajax_call_stop();
	
	statusBoxQueueEnabled = false;
	if ( 0 === keep_polling ) { // Only show once.
		return;
	}
	
	keep_polling = 0; // Stop polling server for status updates.

	
	jQuery( '.bb_progress-step-active').removeClass('bb_progress-step-active');
	
	// Mark the currently running step as failed.
	jQuery( '.backup-step-active').addClass('backup-step-error').removeClass('backup-step-active');
	
	jQuery( '.bb_progress-step-unfinished').addClass( 'bb_progress-step-completed' );
	jQuery( '.bb_progress-step-unfinished').addClass( 'bb_progress-step-error' );
	jQuery( '.bb_progress-step-unfinished').find( '.bb_progress-step-title').text( 'Error!' );
	jQuery( '.bb_progress-step-unfinished').removeClass( 'bb_progress-step-unfinished' );
	jQuery( '#backup-function-backup_success').text( 'Process Failed' );
	jQuery( '.bb_actions_during' ).hide();
	jQuery( '.pb_actions_cancelled' ).show();
	
	// OLD:
	jQuery( '.backup-step-status-working' ).removeClass( 'backup-step-status-working' ).addClass( 'backup-step-status-error' ); // Anything that was currently running turns into an error.
	//jQuery( '#pb_backupbuddy_stop' ).css( 'visibility', 'hidden' );
	jQuery( '#pb_backupbuddy_stop' ).hide();
	
	// Briefly wait
	//setTimeout(function(){
		backupbuddy_log( '***', 'notice' );
		if ( '' !== backupbuddy_currentFunction ) {
			backupbuddy_log( '* Unfinished function: `' + backupbuddy_currentFunction + '`.', 'notice' );
		} else {
			backupbuddy_log( '* No in-progress function detected.', 'notice' );
		}
		
		if ( '' !== backupbuddy_currentAction ) {
			backupbuddy_log( '* Unfinished action: `' + backupbuddy_currentAction + '` (' + ( unix_timestamp() - backupbuddy_currentActionStart ) + ' seconds ago).', 'notice' );
		} else {
			backupbuddy_log( '* No in-progress action detected.', 'notice' );
		}
		backupbuddy_log( '***', 'notice' );
		
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
		
		errorHelp( 'Something went wrong creating the backup. See the Status Log tab above for details. <span class="description">Provide a copy of the Status Log if seeking support.</span>', suggestions );
		
		setTimeout(function(){
			backupbuddy_log( '--- The backup has halted.' );
		},500);
		//alert( 'The backup has halted.' );
	//},1000);
	
});


// We need to wait longer for initialization to complete.
jQuery('#backupbuddy_messages').bind( 'backupbuddy_wait_init', function(e, json) {
	--backup_init_complete_poll_retry_count;
});


jQuery('#backupbuddy_messages').bind( 'backupbuddy_archiveInfo', function(e, json) {
	try {
		archiveInfo = jQuery.parseJSON( json.data );
	} catch(e) {
		console.log( 'Error parsing archiveInfo JSON:' + json.data );
	}
	
	//jQuery( '#pb_backupbuddy_archive_download' ).slideDown();
	jQuery('.bb_actions_during').hide();
	jQuery('.bb_actions_after').show();
	jQuery( '#pb_backupbuddy_archive_url' ).attr( 'href', archiveInfo.url );
	jQuery( '#pb_backupbuddy_archive_send' ).attr( 'rel', archiveInfo.file );
});


jQuery('.bb_overview').on('click', '.backup-step-view-more', function(e) {
	e.preventDefault();
	console.dir( jQuery(this).closest('div') );
	jQuery(this).closest('div').next('.backup-step-title').toggleClass( 'backup-step-secondary-hidden' );
});




