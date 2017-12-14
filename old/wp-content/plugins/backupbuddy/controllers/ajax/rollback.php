<?php
backupbuddy_core::verifyAjaxAccess();


// Database roll back feature.
/* rollback()
 *
 * Displayed in page by iframe via _rollback.php
 * Expects GET variables:
 * 		step		Numeric step number to run.
 * 		archive		Zip archive filename (basename only).
 *
 */
pb_backupbuddy::$ui->ajax_header();
pb_backupbuddy::load_script( 'jquery' );
echo '<div id="pb_backupbuddy_working" style="width: 100px; margin-bottom: 30px;"><br><center><img src="' . pb_backupbuddy::plugin_url() . '/images/working.gif" title="Working... Please wait as this may take a moment..."></center></div>';
?>


<script>
function pb_status_append( status_string ) {
	var win = window.dialogArguments || opener || parent || top;
	win.pb_status_append( status_string );
}
function pb_status_undourl( undo_url ) {
	var win = window.dialogArguments || opener || parent || top;
	win.pb_status_undourl( undo_url );
}

var win = window.dialogArguments || opener || parent || top;
win.window.scrollTo(0,0);
</script>


<?php
global $pb_backupbuddy_js_status;
$pb_backupbuddy_js_status = true;
pb_backupbuddy::set_status_serial( 'restore' );


$step = strip_tags( pb_backupbuddy::_GET( 'step' ) );
if ( ( '' == $step ) || ( ! is_numeric( $step ) ) ) {
	$step = 0;
}
$backupFile = strip_tags( pb_backupbuddy::_GET( 'archive' ) );
if ( '' == $backupFile ) {
	pb_backupbuddy::alert( 'The backup file to restore from must be specified.' );
	die();
}
$stepFile = pb_backupbuddy::plugin_path() . '/controllers/pages/rollback/_step' . $step . '.php';
if ( ! file_exists( $stepFile ) ) {
	pb_backupbuddy::alert( 'Error #849743. Invalid roll back step `' . htmlentities( pb_backupbuddy::_GET( 'step' ) ) . '` (' . $step . ').' );
	die();
}
require( $stepFile );

echo '<br><br><br>';
echo '<script type="text/javascript">jQuery("#pb_backupbuddy_working").hide();</script>';
pb_backupbuddy::$ui->ajax_footer();
pb_backupbuddy::flush();

die();