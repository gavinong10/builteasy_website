<?php
die();

if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}

Auth::require_authentication(); // Die if not logged in.

?>
<div id="pb_createadmin_modal" style="display: none; height: 90%;">
		<div class="modal">
			<div class="modal_header">
				<a class="modal_close">&times;</a>
				<h2>Server Information</h2>
			</div>
			<div class="modal_content">
				
				
				
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery( '#createadmin_form' ).submit(function(){
							
							jQuery( '.createadmin_loading' ).show();
							jQuery.post('importbuddy.php?ajax=create_admin',
								jQuery( '#createadmin_form' ).serialize(), function(data) {
									
									data = jQuery.trim( data );
									jQuery( '.createadmin_loading' ).hide();
									
									if ( data == '1' ) {
										alert( 'Success' );
									} else {
										alert( 'Error: ' + data );
									}
									
								}
							);
							
							return false;
							
						});
					});
				</script>
				
				<form id="createadmin_form">
					Username: <input type="text" name="username">
					Email: <input type="email" name="email">
					Password: <input type="password" name="password">
					Confirm Password: <input type="password" name="password_confirm">
					<input type="submit" name="submit" value="Create Admin User" class="button">
					<span class="createadmin_loading" style="display: none; margin-left: 10px;"><img src="<?php echo pb_backupbuddy::plugin_url(); ?>/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;"></span>
				</form>
				
				
		</div>
	</div>
</div>
