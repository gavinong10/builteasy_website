(function ($) {

	$.mad_custom_tab_mod = {};

	$.mad_custom_tab_mod.init = function () {

		var template = $('#tmpl-add-custom-tab').html();

		$('.custom-box-holder').sortable({
			placeholder: 'mad-custom-tab-highlight',
			handle: ".handle-area"
		});

		({
			init: function () {
				this.listeners();
			},
			listeners: function () {
				var base = this;

				$('#meta_custom_tabs').on('click', '.add-custom-tab', function (e) {
					e.preventDefault();

					var rString = base.randomString(5, 'abcdefghijklmnopqrstuvwxyz'),
						html = template.replace(/__REPLACE_SSS__/gi, rString);

					newTemplate = $(html).appendTo('.custom-box-holder').css({ display: "none" });

					quicktags(rString);
					QTags._buttonsInit();

					newTemplate.slideDown(200);

				}).on('click', '.remove-custom-tab', function (e) {
					e.preventDefault();
					var $this = $(this),
						$item = $this.parents('li');
					$item.slideUp(200, function () { $item.remove(); });
				});
			},
			randomString: function (length, chars) {
				var result = '';
				for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
				return result;
			}
		}).init();
	}

	$(function() {
		$.mad_custom_tab_mod.init();
	});

})(jQuery);