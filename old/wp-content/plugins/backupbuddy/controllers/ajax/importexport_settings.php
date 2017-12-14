<?php
backupbuddy_core::verifyAjaxAccess();


// Popup thickbox for importing and exporting settings.

pb_backupbuddy::load();
pb_backupbuddy::$ui->ajax_header();

if ( pb_backupbuddy::_POST( 'import_settings' ) != '' ) {
	$import = trim( stripslashes( pb_backupbuddy::_POST( 'import_data' ) ) );
	$import = base64_decode( $import );
	if ( $import === false ) { // decode failed.
		pb_backupbuddy::alert( 'Unable to decode settings data. Import aborted. Insure that you fully copied the settings and did not change any of the text.' );
	} else { // decode success.
		if ( ( $import = maybe_unserialize( $import ) ) === false ) { // unserialize fail.
			pb_backupbuddy::alert( 'Unable to unserialize settings data. Import aborted. Insure that you fully copied the settings and did not change any of the text.' );
		} else { // unserialize success.
			if ( !isset( $import['data_version'] ) ) { // missing expected content.
				pb_backupbuddy::alert( 'Unserialized settings data but it did not contain expected data. Import aborted. Insure that you fully copied the settings and did not change any of the text.' );
			} else { // contains expected content.
				pb_backupbuddy::$options = $import;
				require_once( pb_backupbuddy::plugin_path() . '/controllers/activation.php' ); // Run data migration to upgrade if needed.
				pb_backupbuddy::save();
				pb_backupbuddy::alert( 'Provided settings successfully imported. Prior settings overwritten.' );
			}
		}
	}
}

echo '<h2>Export BackupBuddy Settings</h2>';
echo 'Copy the encoded plugin settings below and paste it into the destination BackupBuddy Settings Import page.<br><br>';
echo '<textarea style="width: 100%; height: 100px;" wrap="on">';
echo base64_encode( serialize( pb_backupbuddy::$options ) );
echo '</textarea>';

echo '<br><br><br>';

echo '<h2>Import BackupBuddy Settings</h2>';
echo 'Paste encoded plugin settings below to import & replace current settings.  If importing settings from an older version and errors are encountered please deactivate and reactivate the plugin.<br><br>';
echo '<form method="post" action="' . pb_backupbuddy::ajax_url( 'importexport_settings' ) . '">';
echo '<textarea style="width: 100%; height: 100px;" wrap="on" name="import_data"></textarea>';
echo '<br><br><input type="submit" name="import_settings" value="Import Settings" class="button button-primary">';
echo '</form>';

pb_backupbuddy::$ui->ajax_footer();
die();
