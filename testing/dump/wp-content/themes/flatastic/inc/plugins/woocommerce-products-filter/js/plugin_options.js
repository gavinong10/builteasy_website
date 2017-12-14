(function ($) {

	$(function () {

		$('#woof_options').sortable({
			placeholder: 'woof-options-highlight'
		});

		$(document).on('mad_colorpicker', function () {
			$('.mad-colorpicker').each(function () {
				$(this).wpColorPicker();
			});
		}).trigger('mad_colorpicker');

		$(document).on('change', '.woof_select_type', function () {

			var $this = $(this),
				container = $this.parents('li').find('.woof_placeholder').html('');

			if ($this.val() == 'color') {

				var spinner = container.next('.spinner').show(),
					data = {
						action   : 'woof_select_type',
						attribute: $this.data('attribute'),
						value    : $this.val()
					};

				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: data,
					dataType: 'json',
					success: function (response) {
						container.html(response.content);
						$(document).trigger('mad_colorpicker');
					},
					complete: function () {
						container.show(0);
						spinner.hide();
					}
				});

			} else {
				container.hide(0);
			}

		});

	});

})(jQuery);