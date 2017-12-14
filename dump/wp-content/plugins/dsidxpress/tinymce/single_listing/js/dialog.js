
var dsidxSingleListing = (function() {
	var nodeEditing;
	var returnObj;
	
	returnObj = {
		init: function() {
			var startNode = tinyMCEPopup.editor.selection.getStart();
			var nodeTextContent = startNode ? startNode.textContent || startNode.innerText : '';
			var showAllIsSet;
			
			if (/^\[idx-listing /.test(nodeTextContent) && startNode.tagName == 'P') {
				nodeEditing = startNode;
				tinyMCEPopup.editor.execCommand('mceSelectNode', false, nodeEditing);
				
				showAllIsSet = /^[^\]]+ showall=['"]?true/.test(nodeTextContent);
				jQuery('#show-all').get(0).checked = showAllIsSet;
				jQuery('#show-price-history').get(0).checked = showAllIsSet || /^[^\]]+ showpricehistory=['"]?true/.test(nodeTextContent);
				jQuery('#show-schools').get(0).checked = showAllIsSet || /^[^\]]+ showschools=['"]?true/.test(nodeTextContent);
				jQuery('#show-extra-details').get(0).checked = showAllIsSet || /^[^\]]+ showextradetails=['"]?true/.test(nodeTextContent);
				jQuery('#show-features').get(0).checked = showAllIsSet || /^[^\]]+ showfeatures=['"]?true/.test(nodeTextContent);
				jQuery('#show-location').get(0).checked = showAllIsSet || /^[^\]]+ showlocation=['"]?true/.test(nodeTextContent);
				jQuery('#mls-number').val(/^[^\]]+ mlsnumber=['"]?([^ "']+)/.exec(nodeTextContent)[1]);
			}
			jQuery('#show-all').change(dsidxSingleListing.toggleShowAll).change();
		},
		insert: function() {
			var mlsNumber = jQuery('#mls-number').val();
			
			if (!mlsNumber)
				tinyMCEPopup.close();
			
			var shortcode = '<p>[idx-listing mlsnumber="' + mlsNumber.replace(/\s/g, '') + '"';
			
			if (jQuery('#show-all:checked').length) {
				shortcode += ' showall="true"';
			} else {
				jQuery('#data-show-options input:checked').each(function() {
					shortcode += ' ' + this.name + '="true"';
				});
			}
			shortcode += ']</p>';
			
			tinyMCEPopup.editor.execCommand(nodeEditing ? 'mceReplaceContent' : 'mceInsertContent', false, shortcode);
			tinyMCEPopup.close();
		},
		toggleShowAll: function() {
			var checkbox = jQuery(this);
			var isChecked = checkbox.is(":checked");
			var othersDisabled = isChecked;
			
			jQuery('#data-show-options input:checkbox').not(checkbox).each(function() {
				this.checked = isChecked || this.checked;
				this.disabled = othersDisabled;
			});
		}
	};
	
	return returnObj;
})();

tinyMCEPopup.onInit.add(dsidxSingleListing.init, dsidxSingleListing);
