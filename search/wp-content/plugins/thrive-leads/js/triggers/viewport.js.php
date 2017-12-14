<script type="text/javascript">
	(function ($) {
	var _DELTA = 80,
	event_data = <?php echo json_encode( $data ) ?>,
	$window = $(window),
	trigger_elements = function (elem) {
	if (elem.offset().top + _DELTA < $window.height() + $window.scrollTop() && elem.offset().top + elem.outerHeight() > $window.scrollTop() + _DELTA) {
	elem.addClass('tve-leads-viewport-triggered');
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
	}
	};
	event_data.source = 'viewport';
	$(document).ready(function () {
	var _elem = $('.' + event_data.form_id);
	if (!_elem.length) {
	return;
	}

	$window.scroll(function() {
	if (!_elem.hasClass('tve-leads-viewport-triggered')) {
	trigger_elements(_elem);
	}
	});
	trigger_elements(_elem);
	});
	})
(ThriveGlobal.$j);
</script>