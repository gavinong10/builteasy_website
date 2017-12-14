<?php
if ( !current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
	die( 'Access Denied. Error 445543454754.' );
}

pb_backupbuddy::load_script( 'jquery.leanModal.min.js' );
pb_backupbuddy::load_style( 'admin.css' );

pb_backupbuddy::load_script( 'filetree.js' );
pb_backupbuddy::load_style( 'filetree.css' );


if ( pb_backupbuddy::_GET( 'value' ) == '' ) {
	$file = pb_backupbuddy::_GET( 'zip_viewer' );
} else {
	$file = pb_backupbuddy::_GET( 'value' );
}
$file = str_replace( '\\', '', $file );
$file = str_replace( '/', '', $file );
$serial = backupbuddy_core::get_serial_from_file( $file );


pb_backupbuddy::disalert( 'restore_caution', __( 'Caution: Restored files may overwrite existing files of the same name.  Use caution when restoring, especially when restoring large numbers of files to avoid breaking the site.', 'it-l10n-backupbuddy' ) );
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		
		
		
		jQuery('#pb_backupbuddy_file_browser').fileTree(
			{
				root: '',
				multiFolder: false,
				script: '<?php echo pb_backupbuddy::ajax_url( 'file_tree' ); ?>&serial=<?php echo $serial; ?>&zip_viewer=<?php echo $file; ?>'
			},
			function(file) {
			},
			function(directory) {
			}
		);
		
		
		
		// Show options on hover.
		jQuery(document).on('mouseover mouseout', '.jqueryFileTree > li a', function(event) {
			if ( event.type == 'mouseover' ) {
				jQuery(this).children( '.pb_backupbuddy_treeselect_control' ).css( 'visibility', 'visible' );
			} else {
				jQuery(this).children( '.pb_backupbuddy_treeselect_control' ).css( 'visibility', 'hidden' );
			}
		});
		
		
		
		jQuery( '#leanModal_a' ).leanModal(
			{ top : 20, overlay : 0.4, closeButton: ".modal_close" }
		);
		
		
		
		// Restore button for restoring selected files.
		jQuery( '.pb_backupbuddy_restore' ).click( function() {
			
			jQuery( '#pb_backupbuddy_modal_iframe' ).attr( 'src', 'about:blank' ); // clear while we load new URL.
			
			var files = [];
			jQuery( '.jqueryFileTree > li > input:checked' ).each( function() {
				files.push( jQuery(this).next( 'a' ).attr( 'rel' ) );
			});
			if ( files == '' ) {
				alert( "<?php _e( "You must select one or more files to restore.", "it-l10n-backupbuddy" ); ?>" );
				return false;
			}
			
			if ( !confirm( 'WARNING - Any existing files will be overwritten. This cannot be undone. Are you sure you want to restore selected files?' ) ) {
				return false;
			}
			
			jQuery( '#pb_backupbuddy_title' ).html( '<?php _e( "File Restore", "it-l10n-backupbuddy" ); ?>' );
			
			var url = '<?php echo pb_backupbuddy::ajax_url("restore_file_restore"); ?>&archive=<?php echo strip_tags( $file ); ?>&files=' + files;
			jQuery( '#pb_backupbuddy_modal_iframe' ).attr( 'src', url );
			jQuery( '#pb_backupbuddy_title' ).html( '<?php _e( "Restore", "it-l10n-backupbuddy" ); ?>' );
			
			jQuery( '#leanModal_a' ).click();
		});
		
		
		jQuery(document).on( 'mouseenter', '.viewable a', function() {
			//console.log( jQuery(this).attr( 'class' ) );
			jQuery(this).find( '.viewlink_place' ).hide();
			jQuery(this).find( '.viewlink' ).show();
		});
		jQuery(document).on( 'mouseleave', '.viewable a', function() {
			jQuery(this).find( '.viewlink' ).hide();
			jQuery(this).find( '.viewlink_place' ).show();
		});
		
		
	});

	function modal_live( ajax_url_name, source_obj_val ) {
		jQuery( '#pb_backupbuddy_modal_iframe' ).attr( 'src', 'about:blank' ); // clear while we load new URL.
		jQuery( '#pb_backupbuddy_title' ).html( '<?php _e( "File Viewer", "it-l10n-backupbuddy" ); ?>' );
		var url = '<?php echo pb_backupbuddy::ajax_url(""); ?>' + ajax_url_name + '&archive=<?php echo strip_tags( $file ); ?>&file=' + source_obj_val.attr( 'rel' );
		jQuery( '#pb_backupbuddy_modal_iframe' ).attr( 'src', url );
		jQuery( '#leanModal_a' ).click();
	}
</script>



<?php
// Set up zipbuddy.
if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
}

pb_backupbuddy::$ui->title( 'View Backup Contents' );
?>



<a class="button secondary-button pb_backupbuddy_restore">Restore Selected</a>
<div style="width: 100%; height: 100%;">
	<div class="jQueryOuterTree" id="pb_backupbuddy_file_browser" style="position: relative; height: 90%; margin-top: 7px; margin-bottom: 7px;">
		<ul class="jqueryFileTree"></ul>
	</div>
	<span style="float: right;" class="description">Select directories to expand or text-based files to view contents.</span>
	<a class="button secondary-button pb_backupbuddy_restore">Restore Selected</a>
	
	<br><br><br><br style="clear: both; height: 0; overflow: hidden;">
	<?php echo pb_backupbuddy::$ui->button( pb_backupbuddy::page_url(), '&larr; back to backups' ); ?>
	<br style="clear: both; height: 0; overflow: hidden;">
	<br><br><br><br>
</div>
<br style="clear: both; height: 0; overflow: hidden;">



<style type="text/css">
	#leanModal_div {
		height: 90%;
	}
	.modal_content {
		position: absolute;
		width: 660px;
		top: 82px;
		bottom: 0px;
	}
	
	/* Core Styles - USED BY DIRECTORY EXCLUDER */
	.jqueryFileTree LI.directory { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/directory.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.expanded { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/folder_open.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.file { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/file.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.wait { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/spinner.gif') 35px 12px no-repeat; }
	/* File Extensions*/
	.jqueryFileTree LI.ext_3gp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_afp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_afpa { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_asp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_aspx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_avi { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_bat { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_bmp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_c { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_cfm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_cgi { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_com { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_cpp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_css { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/css.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_doc { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/doc.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_exe { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_gif { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_fla { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/flash.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_h { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_htm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/html.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_html { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/html.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_jar { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/java.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_jpg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_jpeg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_js { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_lasso { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_log { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/txt.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_m4p { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_mov { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_mp3 { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_mp4 { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_mpg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_mpeg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_ogg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_pcx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_pdf { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/pdf.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_php { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/php.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_png { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_ppt { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ppt.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_psd { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/psd.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_pl { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_py { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_rb { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_rbx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_rhtml { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_rpm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/linux.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_ruby { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_sql { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/db.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_swf { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/flash.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_tif { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_tiff { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_txt { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/txt.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_vb { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_wav { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_wmv { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_xls { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/xls.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_xml { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 35px 12px no-repeat; }
	.jqueryFileTree LI.ext_zip { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/zip.png') 35px 12px no-repeat; }
	
	
	
	.pb_label {
		padding: 2px;
		cursor: pointer;
	}
	.pb_label:hover {
		background: #ff7373;
	}
	
	
	.jQueryOuterTree {
		padding: 8px;
		width: auto;
		overflow: hidden;
		overflow-y: scroll;
	}
	.jqueryFileTree li a {
		font-size: 13px;
	}
	
	.pb_backupbuddy_fileinfo {
		font-size: 10px;
	}
	
	.pb_backupbuddy_fileinfo .description {
		font-size: 10px;
	}
	
	ul.jqueryFileTree a {
		box-sizing:border-box;
		-moz-box-sizing:border-box;
		-webkit-box-sizing:border-box
		position: relative;
		display: inline-block;
		width: 100%;
		padding: 10px;
		padding-left: 28px;
		padding-right: 20px;
		/* margin-left: 15px; */
	}
	
	.jqueryFileTree li input {
		margin-right: 15px;
	}
	
	.pb_backupbuddy_col1 {
		display: inline-block;
		position: absolute;
		right: 300px;
	}
	.pb_backupbuddy_col2 {
		display: inline-block;
		position: absolute;
		right: 50px;
	}
	
	.pb_backupbuddy_treeselect_control > img {
		margin-top: 8px;
	}
</style>



<div>
	<a id="leanModal_a" href="#leanModal_div" style="display: none;"></a>
	<div id="leanModal_div" style="display: none;">
		<div class="modal">
			<div class="modal_header">
				<a class="modal_close">&times;</a>
				<h2><span id="pb_backupbuddy_title"></span></h2>
				Backup Archive: <?php echo strip_tags( $file ); ?>
			</div>
			<div class="modal_content">
				
				<iframe id="pb_backupbuddy_modal_iframe" src="" width="100%" style="max-width: 1000px;" height="100%" frameBorder="0" padding="0" margin="0">Error #4584594579. Browser not compatible with iframes.</iframe>
				
			</div>
		</div>
	</div>
</div>
