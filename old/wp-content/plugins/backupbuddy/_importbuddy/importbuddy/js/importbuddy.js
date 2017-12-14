jQuery(document).ready(function() {
	jQuery(window).load(function(){
		if ( jQuery('#pb_importbuddy_working').is(':visible') ) {
			jQuery('#pb_importbuddy_working').replaceWith(
				jQuery('#pb_importbuddy_blankalert').html().replace( '#TITLE#', 'PHP Timeout or Fatal Error Occurred' ).replace( '#MESSAGE#', 'The page did not finish loading as expected.  The most common cause for this is the PHP process taking more time than it has been allowed by your host (php.ini setting <i>max_execution_time</i>). If a PHP error is displayed above this can also cause this error.' )
			);
		}
	});
});

function loadTooltips() {
	jQuery('.pluginbuddy_tip').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: " - ",
		fade: 250
	});
}

jQuery(document).ready(function() {
	loadTooltips();
	
	jQuery('.toggle').click(function(e) {
		jQuery( '#toggle-' + jQuery(this).attr('id') ).slideToggle();
	});
	
	jQuery('.option_toggle').change(function(e) {
		if (jQuery(this).attr('checked')) {
			jQuery('.' + jQuery(this).attr('id') + '_toggle' ).slideToggle();
		} else {
			jQuery('.' + jQuery(this).attr('id') + '_toggle' ).slideToggle();
		}
	});
	
	
	jQuery('#pluginbuddy-tabs').tabs();
});

function backupbuddy_saveLogAsFile() {
    var textFileAsBlob = new Blob([ jQuery( '#backupbuddy_messages' ).text() ], {type:'text/plain'});
    var fileNameToSaveAs = 'importbuddy.txt';

    var downloadLink = document.createElement("a");
    downloadLink.download = fileNameToSaveAs;
    downloadLink.innerHTML = "Download File";
    downloadLink.setAttribute('target', '_new'); // Safari loads this link as a page instead of directly downloading.
   // if ( ( 'undefined' != typeof window.webkitURL ) && ( window.webkitURL !== null) ) {
        // Chrome allows the link to be clicked
        // without actually adding it to the DOM.
        //downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
   // } else {
        // Firefox requires the link to be added to the DOM
        // before it can be clicked.
        downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
        downloadLink.onclick = backupbuddy_destroyClickedElement;
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
    //}

    downloadLink.click();
}

function backupbuddy_destroyClickedElement(event) {
    document.body.removeChild(event.target);
}