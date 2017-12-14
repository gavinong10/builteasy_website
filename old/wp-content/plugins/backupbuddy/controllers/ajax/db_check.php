<?php
backupbuddy_core::verifyAjaxAccess();


// Check db integrity of a table.

/*	db_check()
*	
*	Check database integrity on a specific table. Used on server info page.
*	
*	@return		null
*/

$table = base64_decode( pb_backupbuddy::_GET( 'table' ) );
$check_level = 'MEDIUM';

global $wpdb;

pb_backupbuddy::$ui->ajax_header();
echo '<h2>Database Table Check</h2>';
echo 'Checking table `' . $table . '` using ' . $check_level . ' scan...<br><br>';
$rows = $wpdb->get_results( "CHECK TABLE `" . backupbuddy_core::dbEscape( $table ) . "` " . $check_level, ARRAY_A );
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