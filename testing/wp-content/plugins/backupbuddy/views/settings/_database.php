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


require_once( '_filetree.php' );
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		/* Begin Table Selector */
		jQuery( '.pb_backupbuddy_table_addexclude' ).click(function(){
			jQuery('#pb_backupbuddy_profiles__<?php echo $profile_id; ?>__mysqldump_additional_excludes').val( jQuery(this).parent().parent().parent().find( 'a' ).attr( 'alt' ) + "\n" + jQuery('#pb_backupbuddy_profiles__<?php echo $profile_id; ?>__mysqldump_additional_excludes').val() );
			return false;
		});
		jQuery( '.pb_backupbuddy_table_addinclude' ).click(function(){
			jQuery('#pb_backupbuddy_profiles__<?php echo $profile_id; ?>__mysqldump_additional_includes').val( jQuery(this).parent().parent().parent().find( 'a' ).attr( 'alt' ) + "\n" + jQuery('#pb_backupbuddy_profiles__<?php echo $profile_id; ?>__mysqldump_additional_includes').val() );
			return false;
		});
	});
</script>



<?php


global $wpdb;
if ( $profile_array['type'] == 'defaults' ) {
	$title = '<b>Default</b> base database tables to backup';
	$options = array( '0' => 'This WordPress\' tables (prefix ' . $wpdb->prefix . ')', '1' => __( 'All tables (including non-WordPress)', 'it-l10n-backupbuddy' ), '2' => __( 'None (use with caution)', 'it-l10n-backupbuddy' ) );
} else {
	$title = 'Base database tables to backup for profile';
	$options = array( '-1' => __( 'Use global default', 'it-l10n-backupbuddy' ), '0' => 'WordPress tables (prefix ' . $wpdb->prefix . ')', '1' => __( 'All tables (including non-WordPress)', 'it-l10n-backupbuddy' ), '2' => __( 'None (use with caution)', 'it-l10n-backupbuddy' ) );
}
$settings_form->add_setting( array(
	'type'			=>		'radio',
	'name'			=>		'profiles#' . $profile_id . '#backup_nonwp_tables',
	'options'		=>		$options,
	'title'			=>		$title,
	'tip'			=>		__( '[Default: This WordPress\' tables prefix (' . $wpdb->prefix . ')] - Determines the default set of tables to backup.  If this WordPress\' tables is selected then only tables with the same prefix (for example ' . $wpdb->prefix . ' for this installation) will be backed up by default.  If all are selected then all tables will be backed up by default. Additional inclusions & exclusions may be defined below.', 'it-l10n-backupbuddy' ),
	'css'			=>		'',
	'rules'			=>		'required',
	'orientation'	=>	'vertical',
) );


if ( $profile_array['type'] != 'defaults' ) {
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'profiles#' . $profile_id . '#profile_globaltables',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		'Use global defaults for tables to backup?',
		'after'		=>		' Use global defaults...<br><span class="description" style="padding-left: 25px;">Uncheck to customize tables.</span>',
		'css'		=>		'',
	) );
}


function pb_additional_tables( $profile_array, $display_size = false ) {
	
	$return = '';
	$size_string = '';
	
	global $wpdb;
	if ( true === $display_size ) {
		$results = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
	} else {
		$results = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()", ARRAY_A );
	}
	foreach( $results as $result ) {
		
		if ( true === $display_size ) {
			// Fix up row count and average row length for InnoDB engine which returns inaccurate (and changing) values for these.
			if ( 'InnoDB' === $result[ 'Engine' ] ) {
				if ( false !== ( $rowCount = $wpdb->get_var( "SELECT COUNT(1) as rowCount FROM `{$rs[ 'Name' ]}`", ARRAY_A ) ) ) {
					if ( 0 < ( $result[ 'Rows' ] = $rowCount ) ) {
						$result[ 'Avg_row_length' ] = ( $result[ 'Data_length' ] / $result[ 'Rows' ] );
					}
				}
				unset( $rowCount );
			}
			
			// Table size.
			$size_string = ' (' . pb_backupbuddy::$format->file_size( ( $result['Data_length'] + $result['Index_length'] ) ) . ') ';
			
		} // end if display size enabled.
		
		$return .= '<li class="file ext_sql collapsed">';
		$return .= '<a rel="/" alt="' . $result['table_name'] . '">' . $result['table_name'] . $size_string;
		$return .= '<div class="pb_backupbuddy_treeselect_control">';
		$return .= '<img src="' . pb_backupbuddy::plugin_url() . '/images/redminus.png" style="vertical-align: -3px;" title="Add to exclusions..." class="pb_backupbuddy_table_addexclude"> <img src="' . pb_backupbuddy::plugin_url() . '/images/greenplus.png" style="vertical-align: -3px;" title="Add to inclusions..." class="pb_backupbuddy_table_addinclude">';
		$return .= '</div>';
		$return .= '</a>';
		$return .= '</li>';
	}
	
	return '<div class="jQueryOuterTree" style="position: absolute; height: 160px;"><ul class="jqueryFileTree">' . $return . '</ul></div>';
	
} // end pb_additional_tables().


global $wpdb;
$prefix = $wpdb->prefix;
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'profiles#' . $profile_id . '#mysqldump_additional_includes',
	'title'		=>		'Hover & select <img src="' . pb_backupbuddy::plugin_url() .'/images/greenplus.png" style="vertical-align: -3px;"> to include, <img src="' . pb_backupbuddy::plugin_url() .'/images/redminus.png" style="vertical-align: -3px;"> to exclude.' . ' ' . pb_additional_tables( $profile_array ),
	'before'	=>		__('<b>Inclusions</b> beyond base', 'it-l10n-backupbuddy' ) . '*:',
	'tip'		=>		__('Additional databases tables to include OR exclude IN ADDITION to the DEFAULTS determined by the previous option. You may override defaults with exclusions. Excluding tables may result in an incomplete or broken backup so exercise caution.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'',
	'css'		=>		'width: 100%;',
) );
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'profiles#' . $profile_id . '#mysqldump_additional_excludes',
	//'title'		=>		__('Additional tables to <b>exclude</b>', 'it-l10n-backupbuddy' ) . '<br><span class="description">' . __( 'One table per line.', 'it-l10n-backupbuddy' ) . '</span>',
	'title'		=>		'&nbsp;',
	'before'	=>		__('<b>Exclusions</b> beyond base', 'it-l10n-backupbuddy' ) . '*:',
	'tip'		=>		__('Additional databases tables to EXCLUDE from the backup. Exclusions are exempted after calculating defaults and additional table includes first. These may include non-WordPress and WordPress tables. WARNING: Excluding WordPress tables results in an incomplete backup and could result in failure in the ability to restore or data loss. Use with caution.', 'it-l10n-backupbuddy' ),
	'after'		=>		'<br><br><span class="description">* ' . __( 'One table per line. {prefix} may be used for the WordPress database prefix (currently: ', 'it-l10n-backupbuddy' ) . $prefix . ')</span>',
	'rules'		=>		'',
	'css'		=>		'width: 100%;',
) );



?>