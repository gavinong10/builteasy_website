
(function ($) {

	$.widgetMetaBox = function () {
		return ({
			init: function () {
				var base = this;
					base.widgetTemplate = $('#tmpl-options-hidden');
					base.listeners();
			},
			listeners: function () {
				var base = this,
					obj = $.parseJSON(base.widgetTemplate.html());

				$(".meta-list-set ul.options-columns").on('click', 'li', function () {
					var $this = $(this),
						val = $this.data('val'),
						container = $this.closest('.meta-set'),
						hidden = container.find('.data-widgets-hidden'),
						display = container.find('.meta-columns-set');

					var newValue = {};
						newValue[val] = [obj[val][0]];
						hidden.data('columns', val).attr('value', JSON.stringify(newValue));

					$this.siblings('li').removeClass('active').end().addClass('active');

					display.children().removeClass('hidden');
					display.children().slice(val).addClass('hidden');

					for (i = 0; i < obj[val][0].length; i++) {
						display.find('.mod-columns:nth-child(' + (i + 1) + ')').attr("class", "mod-columns mod-grid-" + obj[val][0][i] + "");
					}

//					container.find('.options-type').html('');
//
//					for (i = 0; i < obj[val].length; i++) {
//						class_active = '';
//						if (i == 0) { class_active = 'class="active"'; }
//						container.find('.options-type').append('<li ' + class_active + '>' + (i + 1) + '</li>');
//					}

					return false;
				});

				$('[name="footer_row_top_show"], [name="footer_row_bottom_show"]').on('click', function () {
					var $this = $(this),
						value = $this.val(),
						toggleContainer = $('.' + $this.attr('name'));
					if (value == 'yes') {
						toggleContainer.slideDown(300);
					} else {
						toggleContainer.slideUp(300);
					}
				});

			}
		}.init());

	}

	$(function() {
		new $.widgetMetaBox();
	});

})(jQuery);