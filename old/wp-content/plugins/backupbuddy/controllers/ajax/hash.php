<?php
backupbuddy_core::verifyAjaxAccess();


// Obtain MD5 hash of a backup file.
/* hash()
 *
 * Generate a hash/CRC for a file at user request.
 *
 */

pb_backupbuddy::load();

pb_backupbuddy::$ui->ajax_header();
?>


<h1><?php _e( 'MD5 Checksum Hash', 'it-l10n-backupbuddy' ); ?></h1>
<?php
_e( 'This is a string of characters that uniquely represents this file.  If this file is in any way manipulated then this string of characters will change.  This allows you to later verify that the file is intact and uncorrupted.  For instance you may verify the file after uploading it to a new location by making sure the MD5 checksum matches.', 'it-l10n-backupbuddy' );

$hash = md5_file( backupbuddy_core::getBackupDirectory() . pb_backupbuddy::_GET( 'callback_data' ) );

echo '<br><br><br>';
echo '<b>Hash:</b> &nbsp;&nbsp;&nbsp; <input type="text" size="40" value="' . $hash . '" readonly="readonly">';


pb_backupbuddy::$ui->ajax_footer();
die();

