tinymce.create('tinymce.plugins.dsidxListing', {
	init : function(ed, url) {
		ed.addCommand('dsidx-listing', function() {
			ed.windowManager.open({
				file : url + '/dialog.php',
				width : 250,
				height : 330,
				inline : 1
			}, {
				plugin_url : url
			});
		});
		ed.addButton('idxlisting', {
			title : 'Insert single listing from MLS data',
			cmd : 'dsidx-listing',
			//image : url + '/img/single_listing.png'
		});
		ed.onNodeChange.add(function(ed, cm, n) {
			cm.setActive('idxlisting', !tinymce.isIE && /^\[idx-listing /.test(n.innerHTML));
		});
	},
	createControl : function(n, cm) {
		return null;
	},
	getInfo : function() {
		return {
			longname : 'Insert a "live" single listing from MLS data',
			author : 'Diverse Solutions',
			authorurl : 'http://www.diversesolutions.com',
			infourl : 'javascript:void(0)',
			version : "1.0"
		};
	}
});
tinymce.PluginManager.add('idxlisting', tinymce.plugins.dsidxListing);