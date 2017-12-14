tinymce.create('tinymce.plugins.dsidxQuickSearch', {
	init : function(ed, url) {
		ed.addCommand('dsidx-idx-quick-search', function() {
			ed.windowManager.open({
				file : url + '/dialog.php',
				width : 350,
				height : 330,
				inline : 1
			}, {
				plugin_url : url
			});
		});
		ed.addButton('idxquicksearch', {
			title : 'Insert an IDX search form',
			cmd : 'dsidx-idx-quick-search',
			classes: 'widget btn dsidx-search-form-btn',
			//image : url + '/img/single_listing.png'
		});
		ed.onNodeChange.add(function(ed, cm, n) {
			cm.setActive('idxquicksearch', !tinymce.isIE && /^\[idx-quick-search /.test(n.innerHTML));
		});
	},
	createControl : function(n, cm) {
		return null;
	},
	getInfo : function() {
		return {
			longname : 'Insert an IDX search form',
			author : 'Diverse Solutions',
			authorurl : 'http://www.diversesolutions.com',
			infourl : 'javascript:void(0)',
			version : "1.0"
		};
	}
});
tinymce.PluginManager.add('idxquicksearch', tinymce.plugins.dsidxQuickSearch);