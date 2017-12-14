<script type="text/javascript">
	(function ($) {
	var _DELTA = 80,
	event_data = <?php echo json_encode( $data ) ?>,
	$window = $(window),
	_triggered = false,
	trigger_elements = function (elem) {
	if (!_triggered && elem.offset().top + _DELTA < $window.height() + $window.scrollTop() && elem.offset().top + elem.outerHeight() > $window.scrollTop() + _DELTA) {
	elem.addClass('tve-leads-scroll-triggered');
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
	_triggered = true;
	}
	};
	event_data.source = 'scroll_element';
	$(document).ready(function () {
	var _elem = $(<?php echo json_encode( $selector ) ?>);
	if (!_elem.length) {
	return;
	}
	$window.scroll(function() {
	if (!_elem.hasClass('tve-leads-scroll-triggered')) {
	trigger_elements(_elem);
	}
	});
	trigger_elements(_elem);
	<?php if (! empty( $this->config['exi'] )) : /** if the "Display on exit intent if the set amount of time did not pass" option is set, then output the trigger for exit intent */ ?>
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
	if (!_triggered) {
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
	_triggered = true;
	}
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
	$(document).on('mouseleave.exit_intent', ml)
	.on('mouseenter.exit_intent', me);
	<?php endif ?>
	});
	})
(ThriveGlobal.$j);
</script>