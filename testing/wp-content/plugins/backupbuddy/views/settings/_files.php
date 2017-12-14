<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }


/*
IMPORTANT INCOMING VARIABLES (expected to be set before this file is loaded):
$profile	Index number of profile.
*/
if ( isset( pb_backupbuddy::$options['profiles'][$profile] ) ) {
	$profile_id = $profile;
	$profile_array = &pb_backupbuddy::$options['profiles'][$profile];
	$profile_array = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), $profile_array );
} else {
	die( 'Error #565676756. Invalid profile ID index.' );
}


?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_filetree_exclude' ).click( function() { alert( 'Error #3484578347843873. Not implemented here. Deprecated.' ); } );
		
		/* Begin Directory / File Selector */
		jQuery(document).on( 'click', '.pb_backupbuddy_filetree_exclude', function(){
			text = jQuery(this).parent().parent().find( 'a' ).attr( 'rel' );
			if ( ( text == 'wp-config.php' ) || ( text == '/wp-content/' ) || ( text == '/wp-content/uploads/' ) || ( text == '<?php echo '/' . str_replace( ABSPATH, '', backupbuddy_core::getBackupDirectory() ); ?>' ) || ( text == '<?php echo '/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ); ?>' ) ) {
				alert( "<?php _e('You cannot exclude /wp-content/, the uploads directory, or BackupBuddy directories which will be automatically excluded.  However, you may exclude subdirectories within these. BackupBuddy directories such as backupbuddy_backups are automatically excluded and cannot be added to exclusion list.', 'it-l10n-backupbuddy' );?>" );
			} else {
				jQuery('#pb_backupbuddy_excludes').val( text + "\n" + jQuery('#pb_backupbuddy_excludes').val() );
			}
			return false;
		});
	});
</script>



<?php
require_once( '_filetree.php' );



if ( $profile_array['type'] == 'defaults' ) {
	$before_text = __('<b>Default</b> excluded files & directories (relative to WordPress root)' , 'it-l10n-backupbuddy' );
} else {
	$before_text = __('Excluded files & directories for this profile', 'it-l10n-backupbuddy' );
		//'<br><span class="description">' . __( '(Global defaults do not apply; relative to WordPress root)' , 'it-l10n-backupbuddy' ) . '</span>';
}


if ( $profile_array['type'] != 'defaults' ) {
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'profiles#' . $profile_id . '#profile_globalexcludes',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		'Use global defaults for files to backup?',
		'after'		=>		' Use global defaults<br><span class="description" style="padding-left: 25px;">Uncheck to customize files.</span>',
		'css'		=>		'',
	) );
}


$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'profiles#' . $profile_id . '#excludes',
	'title'		=>		'Hover & select to navigate, <img src="' . pb_backupbuddy::plugin_url() .'/images/redminus.png" style="vertical-align: -3px;"> to exclude.' . ' ' .
						pb_backupbuddy::tip( __('Click on a directory name to navigate directories. Click the red minus sign to the right of a directory to place it in the exclusion list. /wp-content/, the uploads directory, and BackupBuddy backup & temporary directories cannot be excluded. BackupBuddy directories are automatically excluded.', 'it-l10n-backupbuddy' ), '', false ) .
						'<br><div id="exlude_dirs" class="jQueryOuterTree"></div>',
	//'tip'		=>		,
	'rules'		=>		'string[0-9000]',
	'css'		=>		'width: 100%; height: 135px;',
	'before'	=>		$before_text . pb_backupbuddy::tip( __('List paths relative to the WordPress installation directory to be excluded from backups.  You may use the directory selector to the left to easily exclude directories by ctrl+clicking them.  Paths are relative to root, for example: /wp-content/uploads/junk/', 'it-l10n-backupbuddy' ), '', false ) . '<br>',
	'after'		=>		'<span class="description">' . __( 'One file or directory exclusion per line.', 'it-l10n-backupbuddy' ) . '</span>',
) );



