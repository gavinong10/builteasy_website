<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::$ui->ajax_header();


if ( isset( pb_backupbuddy::$options['remote_destinations'][ pb_backupbuddy::_GET( 'destination_id' ) ] ) ) {
	$destination = pb_backupbuddy::$options['remote_destinations'][$_GET['destination_id']];
} else {
	echo 'Error #438934894349. Invalid destination ID `' . pb_backupbuddy::_GET( 'destination_id' ) . '`.';
	return;
}


require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
pb_backupbuddy_destinations::manage( $destination, $_GET['destination_id'] );

/*
echo '<br><br><br>';
echo '<a class="button" href="';
if ( is_network_admin() ) {
	echo network_admin_url( 'admin.php' );
} else {
	echo admin_url( 'admin.php' );
}
echo '?page=pb_backupbuddy_destinations">&larr; back to destinations</a><br><br>';
*/


pb_backupbuddy::$ui->ajax_footer();
die();