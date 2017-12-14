(function ($) {
	$(function () {

		$( '#widgets-right').on('click', '.popw-collapse', function () {
			var $this = $(this);
			if ($this.next().is(':hidden')) {
				$this.next().slideDown(400);
			} else {
				$this.next().slideUp(400);
			}
		});

		$("#widgets-right").on('click', '.sort-type input[type=radio]', function () {
			var $this = $(this),
				$targetElement = $this.parents('.widget-content').children('[data-tab="calculate"]');
				$value = $this.val();
			if ($value == 'popular') {
				$targetElement.removeClass('disabled');
			} else if ($value == 'latest') {
				$targetElement.addClass('disabled');
			}
		});

	});
})(jQuery);