var dsidxLinkBuilder = (function($) {
	var nodeEditing;
	var returnObj;
	
	returnObj = {
		init: function() {
			tinyMCEPopup.editor.execCommand('mceSelectNode', false, nodeEditing);
		},
		insert: function() {
			var url  = $('#dsidxpress-assembled-url').val();
			if ($('#dsidx-linkbuilder-mode').val() == 'update') {
				tinyMCEPopup.editor.execCommand('mceInsertLink', false, url);
				tinyMCEPopup.editor.selection.getNode().innerHTML = $('#dsidxpress-menu-item-label').val();
				
			} else {
				var html = '<a href="' + url + '">' + $('#dsidxpress-menu-item-label').val() + '</a>';
				tinyMCEPopup.editor.execCommand(nodeEditing ? 'mceReplaceContent' : 'mceInsertContent', false, html);
			}
			tinyMCEPopup.close();
		}
	};
	
	return returnObj;
})(jQuery);

tinyMCEPopup.onInit.add(dsidxLinkBuilder.init, dsidxLinkBuilder);