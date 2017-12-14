<script type="text/javascript">
	(function ($) {
	$(function () {
		var event_data = <?php echo json_encode( $data ) ?>;
		event_data.source = 'page_load';
		setTimeout(function () {
			ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
			}, 200);
		});
	})
(ThriveGlobal.$j);
</script>