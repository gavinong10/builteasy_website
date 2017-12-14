function backupbuddy_saveLogAsFile() {
    var textFileAsBlob = new Blob([ jQuery( '#backupbuddy_messages' ).text() ], {type:'text/plain'});
    var fileNameToSaveAs = 'backupbuddy-' + backupbuddy_serial + '.txt';

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