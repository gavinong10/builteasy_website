<br><?php
$tests = array();

$uploads_dirs = wp_upload_dir();
$directories = array(
	ABSPATH . '',
	ABSPATH . 'wp-includes/',
	ABSPATH . 'wp-admin/',
	WP_CONTENT_DIR . '/themes/',
	WP_PLUGIN_DIR . '/',
	WP_CONTENT_DIR . '/',
	//ABSPATH . 'wp-content/',
	rtrim( $uploads_dirs['basedir'], '\\/' ) . '/',
	ABSPATH . 'wp-includes/',
	backupbuddy_core::getBackupDirectory(),
	backupbuddy_core::getLogDirectory(),
);
if ( @file_exists( backupbuddy_core::getTempDirectory() ) ) { // This dir is usually transient so may not exist.
	$directories[] = backupbuddy_core::getTempDirectory();
}


foreach( $directories as $directory ) {
	
	$mode_octal_four = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
	$owner = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
	
	if ( ! file_exists( $directory ) ) {
		$mode_octal_four = 'Directory does\'t exist';
		$owner = 'n/a';
	}
	$stats = pluginbuddy_stat::stat( $directory );
	if ( false !== $stats ) {
		$mode_octal_four = $stats['mode_octal_four'];
		$owner = $stats['uid'] . ':' . $stats['gid'];
	}
	$this_test = array(
					'title'			=>		'/' . str_replace( ABSPATH, '', $directory ),
					'suggestion'	=>		'<= 755',
					'value'			=>		$mode_octal_four,
					'owner'			=>		$owner,
				);
	if ( false === $stats || $mode_octal_four > 755 ) {
		$this_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$this_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $this_test );
	
} // end foreach.


?>

<table class="widefat">
	<thead>
		<tr class="thead">
			<?php 
				echo '<th>', __('Relative Path','it-l10n-backupbuddy' ),'</th>',
					'<th>', __('Suggestion', 'it-l10n-backupbuddy' ), '</th>',
					'<th>', __('Value', 'it-l10n-backupbuddy' ), '</th>',
					'<th>', __('Owner (UID:GID)', 'it-l10n-backupbuddy' ), '</th>',
					// '<th>', __('Result', 'it-l10n-backupbuddy' ), '</th>',
					 '<th style="width: 60px;">', __('Status', 'it-l10n-backupbuddy' ), '</th>';
			?>
		</tr>
	</thead>
	<tfoot>
		<tr class="thead">
			<?php 
				echo '<th>', __('Relative Path','it-l10n-backupbuddy' ),'</th>',
					'<th>', __('Suggestion', 'it-l10n-backupbuddy' ), '</th>',
					'<th>', __('Value', 'it-l10n-backupbuddy' ), '</th>',
					'<th>', __('Owner (UID:GID)', 'it-l10n-backupbuddy' ), '</th>',
					// '<th>', __('Result', 'it-l10n-backupbuddy' ), '</th>',
					'<th style="width: 60px;">', __('Status', 'it-l10n-backupbuddy' ), '</th>';
			?>
		</tr>
	</tfoot>
	<tbody>
		<?php
		foreach( $tests as $this_test ) {
			echo '<tr class="entry-row alternate">';
			echo '	<td>' . $this_test['title'] . '</td>';
			echo '	<td>' . $this_test['suggestion'] . '</td>';
			echo '	<td>' . $this_test['value'] . '</td>';
			echo '	<td>' . $this_test['owner'] . '</td>';
			//echo '	<td>' . $this_test['status'] . '</td>';
			echo '	<td>';
			if ( $this_test['status'] == __('OK', 'it-l10n-backupbuddy' ) ) {
				//echo '<div style="background-color: #22EE5B; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
				echo '<span class="pb_label pb_label-success">Pass</span>';
			} elseif ( $this_test['status'] == __('FAIL', 'it-l10n-backupbuddy' ) ) {
				//echo '<div style="background-color: #CF3333; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
				echo '<span class="pb_label pb_label-important">Fail</span>';
			} elseif ( $this_test['status'] == __('WARNING', 'it-l10n-backupbuddy' ) ) {
				//echo '<div style="background-color: #FEFF7F; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
				echo '<span class="pb_label pb_label-warning">Warning</span>';
			} else {
				echo 'unknown';
			}
			echo '	</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>

<br><br>