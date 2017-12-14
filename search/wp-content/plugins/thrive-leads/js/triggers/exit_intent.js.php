<script type="text/javascript">
	(function ($) {
	var event_data = <?php echo json_encode( $data ) ?>;
	event_data.source = 'exit_intent';
	<?php /* if we are on mobile devices, show it after an interval, if the user specified it explicitly */ ?>
	<?php if (wp_is_mobile()) : ?>
	<?php if (! empty( $this->config['m'] ) && isset( $this->config['ms'] )) : ?>

	$(function () {
	setTimeout(function () {
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
	}, <?php echo (int) $this->config['ms'] * 1000 ?>)
	});
	<?php endif ?>
	<?php else : ?>
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
	ThriveGlobal.$j(TL_Front).trigger('showform.thriveleads', event_data);
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
	<?php endif ?>
	})
(ThriveGlobal.$j);
</script>