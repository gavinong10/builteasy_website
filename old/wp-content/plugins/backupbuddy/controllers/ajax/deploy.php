<?php
backupbuddy_core::verifyAjaxAccess();


// Database roll back feature.
/* deploy()
 *
 * Displayed in page by iframe via deploy.php
 * Expects GET variables:
 * 		step		Step file to run.
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
pb_backupbuddy::set_status_serial( 'deploy' );


$step = strip_tags( pb_backupbuddy::_GET( 'step' ) );
if ( ! ctype_alnum( $step ) ) {
	die( 'Error #8549845: Invalid step `' . htmlentities( $step ) . '`.' );
}
$stepFile = pb_backupbuddy::plugin_path() . '/controllers/pages/deploy/_' . $step . '.php';

if ( ! file_exists( $stepFile ) ) {
	pb_backupbuddy::alert( 'Error #3298238. Invalid deploy step `' . htmlentities( pb_backupbuddy::_GET( 'step' ) ) . '` (' . $step . ').' );
	die();
}
require( $stepFile );

echo '<br><br><br>';
echo '<script type="text/javascript">jQuery("#pb_backupbuddy_working").hide();</script>';
pb_backupbuddy::$ui->ajax_footer();
pb_backupbuddy::flush();

die();