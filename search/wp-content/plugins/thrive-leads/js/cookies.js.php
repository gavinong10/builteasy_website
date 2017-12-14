<?php
/**
 * set the individual cookies from javascript - the ones that we cannot set server-side - as in the shortcodes case
 */
?>
<script type="text/javascript">
	var _now = new Date(), sExpires;
	<?php foreach ($GLOBALS['tve_leads_set_cookies'] as $key => $cookie) : ?>
	_now.setTime(_now.getTime() + (<?php echo intval( $cookie['expires'] ); ?> * 24 * 3600 * 1000
))
;
	sExpires = _now.toUTCString();
	document.cookie = encodeURIComponent(<?php echo json_encode($key) ?>) + '=' + encodeURIComponent(<?php echo json_encode($cookie['value']) ?>) +
	'; expires = ' + sExpires + '; path =
/
';
	<?php endforeach ?>
</script>
