(function() {
	tinymce.PluginManager.requireLangPack('ega_shortcode');

	tinymce.create('tinymce.plugins.ega_shortcode', {

		init : function(ed, url) {

			ed.addCommand('mceEGAttachments', function() {
				var post_id = tinymce.DOM.get('post_ID').value;
				ed.windowManager.open({
					file : url + '/eg_attach_popup.php?post_id=' + post_id,
					width : 600 + parseInt(ed.getLang('ega_shortcode.delta_width', 0)),
					height : 500 + parseInt(ed.getLang('ega_shortcode.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
				});
			});

			ed.addButton('ega_shortcode', {
				title :  ed.getLang('ega_shortcode.title'),
				cmd : 'mceEGAttachments',
				image : url + '/img/egattachments.png'
			});
		},
		/* 
		createControl : function(n, cm) {
			return null;
		},*/
		
		getInfo : function() {
			return {
				longname  : 'EG-Attachments',
				author 	  : 'Emmanuel GEORJON',
				authorurl : 'http://www.emmanuelgeorjon.com/',
				infourl   : 'http://wordpress.org/extend/plugins/eg-attachments/',
				version   : '2.0.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ega_shortcode', tinymce.plugins.ega_shortcode);
})();