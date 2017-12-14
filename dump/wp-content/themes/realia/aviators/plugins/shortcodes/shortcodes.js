(function () {
	tinymce.create('tinymce.plugins.aviators', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init: function (ed, url) {
			ed.addButton('row', {title: 'Row', cmd: 'row', image: url + '/assets/img/row.png' });
			ed.addCommand('row', function () {
				ed.execCommand('mceInsertContent', 0, '[row]Insert columns here[/row]');
			});

			ed.addButton('span3', {title: 'Span 3', cmd: 'span3', image: url + '/assets/img/span3.png' });
			ed.addCommand('span3', function () {
				ed.execCommand('mceInsertContent', 0, '[span3]Column content[/span3]');
			});

			ed.addButton('span4', {title: 'Span 4', cmd: 'span4', image: url + '/assets/img/span4.png' });
			ed.addCommand('span4', function () {
				ed.execCommand('mceInsertContent', 0, '[span4]Column content[/span4]');
			});

			ed.addButton('span6', {title: 'Span 6', cmd: 'span6', image: url + '/assets/img/span6.png' });
			ed.addCommand('span6', function () {
				ed.execCommand('mceInsertContent', 0, '[span6]Column content[/span6]');
			});

			ed.addButton('span8', {title: 'Span 8', cmd: 'span8', image: url + '/assets/img/span8.png' });
			ed.addCommand('span8', function () {
				ed.execCommand('mceInsertContent', 0, '[span8]Column content[/span8]');
			});

			ed.addButton('span9', {title: 'Span 9', cmd: 'span9', image: url + '/assets/img/span9.png' });
			ed.addCommand('span9', function () {
				ed.execCommand('mceInsertContent', 0, '[span9]Column content[/span9]');
			});

			ed.addButton('content_box', {title: 'Content Box', cmd: 'content_box', image: url + '/assets/img/content-box.png' });
			ed.addCommand('content_box', function () {
				ed.execCommand('mceInsertContent', 0, '[content_box icon="path" title="value"]content[/content_box]');
			});

			ed.addButton('faq', {title: 'FAQ', cmd: 'faq', image: url + '/assets/img/faq.png' });
			ed.addCommand('faq', function () {
				ed.execCommand('mceInsertContent', 0, '[faq category="ID"][/faq]');
			});

			ed.addButton('pricing', {title: 'Pricing', cmd: 'pricing', image: url + '/assets/img/pricing.png' });
			ed.addCommand('pricing', function () {
				ed.execCommand('mceInsertContent', 0, '[pricing link="#" post="ID" button_text="Text" promoted="yes or no" title="Title" price="price" subtitle="Subtitle"][/pricing]');
			});
		},


		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl: function (n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo: function () {
			return {
				longname: 'Aviators Buttons',
				author  : 'Aviators',
				version : '1.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('aviators', tinymce.plugins.aviators);
})();