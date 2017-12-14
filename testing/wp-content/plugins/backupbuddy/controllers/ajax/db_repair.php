<?php
backupbuddy_core::verifyAjaxAccess();


// Repair db integrity of a table.

/*	db_repair()
*	
*	Repair specific table. Used on server info page.
*	
*	@return		null
*/


$table = base64_decode( pb_backupbuddy::_GET( 'table' ) );

global $wpdb;

pb_backupbuddy::$ui->ajax_header();
echo '<h2>Database Table Repair</h2>';
echo 'Repairing table `' . $table . '`...<br><br>';
$rows = $wpdb->get_results( "REPAIR TABLE `" . backupbuddy_core::dbEscape( $table ) . "`", ARRAY_A );
echo '<b>Results:</b><br><br>';
echo '<table class="widefat">';
foreach( $rows as $row ) {
	echo '<tr>';
	echo '<td>' . $row['Msg_type'] . '</td>';
	echo '<td>' . $row['Msg_text'] . '</td>';
	echo '</tr>';
}
unset( $rows );
echo '</table>';
pb_backupbuddy::$ui->ajax_footer();

die();

