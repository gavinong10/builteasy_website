<script type="text/javascript">
	(function ($) {
		/* Chrome has a stupid bug in which it triggers almost simultaneously "mouseenter" "mouseleave" "mouseenter" if the following applies:
		 - at page load, the cursor is outside the html element
		 - the user moves the cursor over the html element
		 */
		var chrome_fix_id = 0,
			me = function (e) { /* mouse enter */
				clearTimeout(chrome_fix_id);
			},
			ml = function (e) {

				if (e.clientY <= config.s) {
					chrome_fix_id = setTimeout(function () {
						$(document).trigger('tve-page-event-exit');
						c();
					}, 50);
				}
			},
			c = function () { // cancel
				$(document).off('mouseenter.exit_intent mouseleave.exit_intent');
			},
			config = { // we can adjust this and the code below to allow users to tweak settings
				s: 20 // sensitivity
			};

		$(function () {
			$(document).on('mouseleave.exit_intent', ml)
				.on('mouseenter.exit_intent', me);

		});
	})(jQuery);
</script>