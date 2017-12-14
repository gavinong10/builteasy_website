// JavaScript Document
(function() {
	tinymce.create('tinymce.plugins.kkStarRatings', {
		init : function(ed, url) {

			// Register button and click event
			ed.addButton('kkstarratings', {
				title : 'Insert Star Ratings', 
				cmd : 'mceKKStarRatings', 
				image: url + '/icon.png', 
				onclick : function(){
					ed.execCommand('mceReplaceContent', false, "[kkstarratings]");
				}});
		},

		getInfo : function() {
			return {
				longname : 'kk Star Ratings',
				author : 'Kamal Khan',
				authorurl : 'http://bhittani.com',
				infourl : 'http://wakeusup.com/2011/05/kk-star-ratings',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('kkstarratings', tinymce.plugins.kkStarRatings);
	
})();