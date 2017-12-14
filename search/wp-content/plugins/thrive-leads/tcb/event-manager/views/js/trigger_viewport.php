<script type="text/javascript">
	(function ($) {
		var _DELTA = 80,
			$window = $(window),
			trigger_elements = function (elements) {
				elements.each(function () {
					var elem = $(this);
					if (elem.parents('.tve_p_lb_content').length) {
						elem.parents('.tve_p_lb_content').on('tve.lightbox-open', function () {
							if (!elem.hasClass('tve-viewport-triggered')) {
								elem.trigger('tve-viewport').addClass('tve-viewport-triggered');
							}
						});
						return;
					}
					if (elem.offset().top + _DELTA < $window.height() + $window.scrollTop() && elem.offset().top + elem.outerHeight() > $window.scrollTop() + _DELTA) {
						elem.trigger('tve-viewport').addClass('tve-viewport-triggered');
					}
				})
			}
		$(document).ready(function () {
			var $to_test = $('.tve_et_tve-viewport');
			$window.scroll(function () {
				trigger_elements($to_test.filter(':not(.tve-viewport-triggered)'));
			});
			trigger_elements($to_test);
		});
	})
	(jQuery);
</script>