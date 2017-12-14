<script type="text/javascript">
	(function ($) {
	var event_data = <?php echo json_encode( $data ) ?>,
	$window = $(window);
	event_data.source = 'page_bottom';

	$(document).ready(function () {
	var _triggered = false,
	_check = function () {
	if (_triggered) {
	return;
	}
	if (ThriveGlobal.$j(window).scrollTop() + ThriveGlobal.$j(window).height() == ThriveGlobal.$j(document).height()) {
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
	_triggered = true;
	}
	};
	$window.scroll(_check);
	_check();
	});
	})
(ThriveGlobal.$j);
</script>

