<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }


if ( is_numeric( pb_backupbuddy::_GET( 'profile' ) ) ) {
	$profile = pb_backupbuddy::_GET( 'profile' );
	if ( ! isset( pb_backupbuddy::$options['profiles'][$profile] ) ) {
		die( 'Error #565676756b. Invalid profile ID index.' );
	}
} else {
	die( 'Error #57434. Invalid profile ID index. Not numeric.' );
}

// Defaults
pb_backupbuddy::$options['profiles'][$profile] = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), pb_backupbuddy::$options['profiles'][$profile] );
?>


<script type="text/javascript">
	var pb_settings_changed = false;
	
	jQuery(document).ready(function() {
		
		jQuery( '.pb_form' ).change( function() {
			var win = window.dialogArguments || opener || parent || top;
			win.pb_settings_changed = true;
		});
		
		jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globaltables' ).click( function() {
			if ( jQuery(this).is(':checked') ) {
				hide_tables();
			} else {
				jQuery(this).closest('tr').next('tr').show();
				jQuery(this).closest('tr').next('tr').next('tr').show();
				if ( jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__mysqldump_additional_includes' ).val() == '-1' ) {
					jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__mysqldump_additional_includes' ).val( '' );
				}
				if ( jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__mysqldump_additional_excludes' ).val() == '-1' ) {
					jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__mysqldump_additional_excludes' ).val( '' );
				}
			}
		});
		
		jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globalexcludes' ).click( function() {
			if ( jQuery(this).is(':checked') ) {
				hide_excludes();
			} else {
				jQuery(this).closest('tr').next('tr').show();
				jQuery(this).closest('tr').next('tr').next('tr').show();
				if ( jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__excludes' ).val() == '-1' ) {
					jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__excludes' ).val( '' );
				}
			}
		});
		
	});
	
	function hide_tables() {
		jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globaltables' ).closest('tr').next('tr').hide();
		jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globaltables' ).closest('tr').next('tr').next('tr').hide();
	}
	function hide_excludes() {
		jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globalexcludes' ).closest('tr').next('tr').hide();
		//jQuery( '#pb_backupbuddy_profiles__<?php echo $profile; ?>__profile_globalexcludes' ).closest('tr').next('tr').next('tr').hide();
	}
	
</script>


<?php



// Set defaults.
/*
print_r( pb_backupbuddy::$options['profiles'][$profile] );
echo '<h3>Editing Profile "' . pb_backupbuddy::$options['profiles'][$profile]['title'] . '":</h3>';
*/
?>




<style>
	table {
		font-size: 12px;
		line-height: 1.6em;
	}
/*	body > div {
		margin: 0 !important;
		padding: 0 !important;
	}
*/
	tr {
		margin: 0 !important;
		padding: 0 !important;
	}
</style>


<?php


$settings_form = new pb_backupbuddy_settings( 'profile_settings', '', 'action=pb_backupbuddy_backupbuddy&function=profile_settings&profile=' . $profile, 320 );


if ( pb_backupbuddy::$options['profiles'][$profile]['type'] == 'db' ) {
	$prettyType = __( 'Database Only', 'it-l10n-backupbuddy' );
} elseif ( pb_backupbuddy::$options['profiles'][$profile]['type'] == 'full' ) {
	$prettyType = __( 'Full', 'it-l10n-backupbuddy' );
} elseif( pb_backupbuddy::$options['profiles'][$profile]['type'] == 'files' ) {
	$prettyType = __( 'Files Only', 'it-l10n-backupbuddy' );
} else {
	$prettyType = 'unknown(' . htmlentities( pb_backupbuddy::$options['profiles'][$profile]['type'] ). ')';
}
$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_type',
	'title'		=>		$prettyType . ' Profile',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'profiles#' . $profile . '#title',
	'title'		=>		__('Profile Name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Enter a descriptive profile name for this profile for your use.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[0-75]',
) );


// Database Settings
if ( 'files' != pb_backupbuddy::$options['profiles'][$profile]['type'] ) {
	$settings_form->add_setting( array(
			'type'		=>		'title',
			'name'		=>		'title_database',
			'title'		=>		__( 'Database', 'it-l10n-backupbuddy' ),
		) );
	require_once( pb_backupbuddy::plugin_path() . '/views/settings/_database.php' );
}


// Full / Files Settings
if ( ( 'full' == pb_backupbuddy::$options['profiles'][$profile]['type'] ) || ( 'files' == pb_backupbuddy::$options['profiles'][$profile]['type'] ) ){
	$settings_form->add_setting( array(
			'type'		=>		'title',
			'name'		=>		'title_files',
			'title'		=>		__( 'Files & Directories', 'it-l10n-backupbuddy' ),
		) );
	require_once( pb_backupbuddy::plugin_path() . '/views/settings/_files.php' );
}


require_once( pb_backupbuddy::plugin_path() . '/views/settings/_profiles-advanced.php' );



// If global tables then set table includes & excludes to -1.
$field = 'pb_backupbuddy_profiles#' . $profile . '#profile_globaltables';
if ( isset( $_POST[ $field ] ) && ( $_POST[ $field ] == '1' ) ) {
	$_POST[ 'pb_backupbuddy_profiles#' . $profile . '#mysqldump_additional_includes' ] = '-1';
	$_POST[ 'pb_backupbuddy_profiles#' . $profile . '#mysqldump_additional_excludes' ] = '-1';
}

// If global excludes then set excludes to -1.
$field = 'pb_backupbuddy_profiles#' . $profile . '#profile_globalexcludes';
if ( isset( $_POST[ $field ] ) && ( $_POST[ $field ] == '1' ) ) {
	$_POST[ 'pb_backupbuddy_profiles#' . $profile . '#excludes' ] = '-1';
}





$process_result = $settings_form->process(); // Handles processing the submitted form (if applicable).
if ( ( count( (array)$process_result['errors'] ) == 0 ) && ( count( (array)$process_result['data'] ) > 0 ) ) {
	
	$excludes = pb_backupbuddy::_POST( 'pb_backupbuddy_profiles#' . $profile . '#mysqldump_additional_excludes' );
	$fileExcludes = backupbuddy_core::alert_core_file_excludes( explode( "\n", trim( $excludes ) ) );
	foreach( $fileExcludes as $fileExcludeId => $fileExclude ) {
		pb_backupbuddy::disalert( $fileExcludeId, '<span class="pb_label pb_label-important">Warning</span> ' . $fileExclude );
	}
	
	if ( count( $fileExcludes ) == 0 ) {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				var win = window.dialogArguments || opener || parent || top;
				win.pb_backupbuddy_profile_updated( '<?php echo $profile; ?>', '<?php echo htmlentities( pb_backupbuddy::$options['profiles'][$profile]['title'] ); ?>' );
				win.tb_remove();
			});
		</script>
		<?php
	}
	
}

$settings_form->display_settings( 'Save Profile Settings' );






if ( $profile > 2 ) {
?>
<a style="float: right; margin-top: -35px; margin-right: 10px;" class="button secondary-button" title="Delete this Profile" href="admin.php?page=pb_backupbuddy_backup&delete_profile=<?php echo $profile; ?>" target="_top" onclick="
	if ( !confirm( 'Are you sure you want to delete this profile?' ) ) {
		return false;
	}
">Delete Profile</a>
<?php } ?>


<script type="text/javascript">
<?php
if( pb_backupbuddy::$options['profiles'][$profile]['profile_globaltables'] == '1' ) {
	echo "hide_tables();\n";
}
if( pb_backupbuddy::$options['profiles'][$profile]['profile_globalexcludes'] == '1' ) {
	echo "hide_excludes();\n";
}
?>
</script>



