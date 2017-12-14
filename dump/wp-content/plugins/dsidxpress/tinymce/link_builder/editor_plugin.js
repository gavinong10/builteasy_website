tinymce.create('tinymce.plugins.dsidxLinkBuilder', {
	init : function(ed, url) {
		ed.addCommand('dsidx-link-builder', function() {
			var s = ed.selection.getNode(), idxtxt, idxurl, idxmode, params = '';
			if (s.nodeName.toLowerCase() == 'a') {
				idxtxt = s.innerHTML
				idxurl = s.href;
				idxmode = 'update';
			} else {
				idxtxt = ed.selection.getContent();
				idxmode = 'insert';
			}
			params += (idxtxt != undefined) ? 'selected_text=' + encodeURIComponent(idxtxt) + '&' : '';
			params += (idxurl != undefined) ? 'selected_url=' + encodeURIComponent(idxurl) + '&' : '';
			params += 'idxlinkmode=' + idxmode;
			ed.windowManager.open({
				file : url + '/dialog.php?' + params,
				width : 350,
				height : 450,
				inline : 1
			}, {
				plugin_url : url
			});
		});
		ed.addButton('idxlinkbuilder', {
			title : 'Build an IDXPress Link',
			cmd : 'dsidx-link-builder',
			//image : url + '/img/link_builder.png'
		});
		ed.onNodeChange.add(function(ed, cm, n) {
			cm.setActive('idxlinkbuilder', !tinymce.isIE && /^\[idx-link-builder /.test(n.innerHTML));
		});
	},
	createControl : function(n, cm) {
		return null;
	},
	getInfo : function() {
		return {
			longname : 'Insert IDXPress Link',
			author : 'Diverse Solutions',
			authorurl : 'http://www.diversesolutions.com',
			infourl : 'javascript:void(0)',
			version : '1.0'
		};
	}
});
tinymce.PluginManager.add('idxlinkbuilder', tinymce.plugins.dsidxLinkBuilder);