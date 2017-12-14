<?php
pb_backupbuddy::$ui->title(
	__( 'Deploy Database', 'it-l10n-backupbuddy' ) .
	' &nbsp;&nbsp; <a style="font-size: 0.6em;" href="#" onClick="jQuery(\'#pb_backupbuddy_status_wrap\').toggle();">Display Status Log</a>'
);

if ( ! defined( 'BACKUPBUDDY_API_ENABLE' ) || ( TRUE != BACKUPBUDDY_API_ENABLE ) ) {
	pb_backupbuddy::alert( "Make sure the following is in your wp-config.php file on this server:<br><textarea style='width: 100%;' disabled='disabled'>define( 'BACKUPBUDDY_API_ENABLE', true ); // Requires API key to access.</textarea>" );
	return false;
}
/*
if ( ! defined( 'BACKUPBUDDY_API_SALT' ) || ( 'CHANGEME' == BACKUPBUDDY_API_SALT ) || ( strlen( BACKUPBUDDY_API_SALT ) < 5 ) ) {
	pb_backupbuddy::alert( "Make sure the following is in your wp-config.php file on this server:<br><textarea style='width: 100%;' disabled='disabled'>define( 'BACKUPBUDDY_API_SALT', 'CHANGEME' ); // !!! IMPORTANT !!! Change CHANGEME to the left to a unique password/phrase for generating API keys. 5+ characters.</textarea>" );
	return false;
}
*/

if ( '1' == pb_backupbuddy::_POST( 'regenerate_api_key' ) ) {
	pb_backupbuddy::verify_nonce(); // Security check.
	pb_backupbuddy::$options['api_key'] = backupbuddy_core::generate_api_key();
	pb_backupbuddy::save();
}
?>

<b>Note:</b> wp-config.php files as well as BackupBuddy settings will NOT be transferred in either direction. Your current BackupBuddy settings, destinations, API keys etc. will remain as they are on both sites.<br><br>

<form method="post">
	<?php pb_backupbuddy::nonce(); ?>
	<input type="hidden" name="regenerate_api_key" value="1">
	<button class="button secondary-button" onClick="jQuery('.backupbuddy_api_key-hide').toggle(); return false;">Show API Key</button><span class="backupbuddy_api_key-hide" style="display: none;">&nbsp;&nbsp;<input type="submit" name="submit" value="Generate New API Key" class="button button-primary"></span>
	<br>
	<br>
	<div class="backupbuddy_api_key-hide" style="display: none;">
		<b>Api Key:</b><br>
		<textarea style="width: 100%; padding: 15px;" readonly="readonly" onClick="this.focus();this.select();"><?php echo pb_backupbuddy::$options['api_key']; ?></textarea>
	</div>
</form>

<br><br>

<?php
/*
pb_backupbuddy::$options['deployments'] = array(
	array( 'siteurl' => 'http://backupbuddy2', 'api_key' => 'xxx' )
);
pb_backupbuddy::save();

if ( pb_backupbuddy::_GET( 'deployment' ) == '' ) {
	foreach( pb_backupbuddy::$options['deployments'] as $deployment_id => $deployment ) {
		echo '<a href="' . pb_backupbuddy::page_url() . '&deployment=' . $deployment_id . '">' . $deployment['siteurl'] . '</a>';
		
		echo '<pre>';
		print_r( $deployment );
		echo '</pre>';
	}
	return;
}
*/


$deployment_id = pb_backupbuddy::_GET( 'deployment' );
?>





<script>
function pb_status_undourl( undo_url ) {
	if ( '' == undo_url ) {
		jQuery( '#pb_backupbuddy_undourl' ).parent('#message').slideUp();
		return;
	}
	jQuery( '#pb_backupbuddy_undourl' ).attr( 'href', undo_url );
	jQuery( '#pb_backupbuddy_undourl' ).text( undo_url );
	jQuery( '#pb_backupbuddy_undourl' ).parent('#message').slideDown();
}
</script>

<style>
	#pb_backupbuddy_status_wrap {
		display: none;
		margin-bottom: 10px;
	}
</style>



<?php
echo '<div id="pb_backupbuddy_status_wrap">';
echo pb_backupbuddy::status_box( 'Starting deployment process . . .' );
echo '</div>';
global $wp_version;
pb_backupbuddy::status( 'details', 'BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . ' using WordPress v' . $wp_version . ' on ' . PHP_OS . '.' );
?>


<script type="text/javascript">
	function pb_status_append( status_string ) {
		//console.log( status_string );
		target_id = 'pb_backupbuddy_status'; // importbuddy_status or pb_backupbuddy_status
		if( jQuery( '#' + target_id ).length == 0 ) { // No status box yet so suppress.
			return;
		}
		jQuery( '#' + target_id ).append( "\n" + status_string );
		textareaelem = document.getElementById( target_id );
		textareaelem.scrollTop = textareaelem.scrollHeight;
	}
</script>


<div id="message" style="display: none; padding: 9px;" rel="" class="pb_backupbuddy_alert updated fade below-h2">
	<?php _e( 'If the deployment should fail for any reason you may attempt to undo its changes at any time by visiting the URL', 'it-l10n-backupbuddy' ); ?>:<br>
	<a href="" id="pb_backupbuddy_undourl" target="pb_backupbuddy_modal_iframe"></a>
</div>


<iframe id="pb_backupbuddy_modal_iframe" name="pb_backupbuddy_modal_iframe" src="<?php echo pb_backupbuddy::ajax_url( 'deploy' ); ?>&step=init&deployment=<?php echo $deployment_id; ?>" width="100%" height="1800" frameBorder="0" padding="0" margin="0">Error #4584594579. Browser not compatible with iframes.</iframe>
