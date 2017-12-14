
var dsidxSearchForm = (function() {
	var nodeEditing;
	var returnObj;
	
	returnObj = {
		init: function() {
			var startNode = tinyMCEPopup.editor.selection.getStart();
			var nodeTextContent = startNode ? startNode.textContent || startNode.innerText : '';
			var showAllIsSet;
			
			if (/^\[idx-quick-search /.test(nodeTextContent) && startNode.tagName == 'P') {
				nodeEditing = startNode;
				tinyMCEPopup.editor.execCommand('mceSelectNode', false, nodeEditing);
				format = /^[^\]]+ format=['"]?([^ "']+)/.exec(nodeTextContent)[1] || 'horizontal';
				jQuery("input[name=format][value=" + format + "]").prop('checked', true);
			}
		},
		insert: function() {
			format = jQuery('input:radio[name=format]:checked').val();
			if (!format)
				tinyMCEPopup.close();
			
			shortcode = '<p>[idx-quick-search format="' + format+ '"]</p>';
			
			tinyMCEPopup.editor.execCommand(nodeEditing ? 'mceReplaceContent' : 'mceInsertContent', false, shortcode);
			tinyMCEPopup.close();
		}
	};
	
	return returnObj;
})();

tinyMCEPopup.onInit.add(dsidxSearchForm.init, dsidxSearchForm);
