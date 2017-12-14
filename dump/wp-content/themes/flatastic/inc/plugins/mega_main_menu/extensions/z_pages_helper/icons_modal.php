<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
global $mega_main_menu;
if ( isset( $_GET[ 'mm_page' ] ) && !empty( $_GET[ 'mm_page' ] ) ) {
//	header("Content-type: text/css", true);
	if ( $_GET[ 'mm_page' ] == 'icons_list' ) {
		$modal_id = ( isset( $_GET['modal_id'] ) ? $_GET['modal_id'] : '');
		$input_name = ( isset( $_GET['input_name'] ) ? $_GET['input_name'] : '');
		echo mad_mm_common::ntab(1) . '
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(\'.all_icons_search_input.'. $modal_id .'\').keyup(function(){
				setTimeout(function () {
					search_query = jQuery(\'.all_icons_search_input.'. $modal_id .'\').val();
					if ( search_query != \'\' ) {
						jQuery(\'.all_icons_container label\').css({\'display\' : \'none\'});
						jQuery(\'.all_icons_container label[for*="\' + search_query + \'"]\').css({\'display\' : \'block\'});
					} else {
						jQuery(\'.all_icons_container label\').removeAttr(\'style\');
					}
				}, 1200 );
			});
		});
	</script>';
		echo mad_mm_common::ntab(3) . '<div class="modal-body">';
		echo mad_mm_common::ntab(4) . '<div class="holder">';
		echo mad_mm_common::ntab(5) . '<div class="all_icons_control_panel">';
		echo mad_mm_common::ntab(6) . '<input type="text" class="all_icons_search_input '. $modal_id .'" placeholder="'.__( 'Search icon', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN'] ).'">';
		echo mad_mm_common::ntab(6) . '<span class="ok_button btn-primary" onclick="mm_icon_selector(\'' . $input_name . '\', \'' . ( isset( $_GET['modal_id'] ) ? $_GET['modal_id'] : '') . '\' );">'.__( 'OK', $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN'] ).'</span>';
		echo mad_mm_common::ntab(5) . '</div><!-- class="all_icons_control_panel" -->';
		echo mad_mm_common::ntab(5) . '<div class="all_icons_container">';
		$set_of_custom_icons = $mega_main_menu->get_option( 'set_of_custom_icons', array() );
		if ( is_array( $set_of_custom_icons ) && count( $set_of_custom_icons ) >= 1 ) {
			foreach ( $set_of_custom_icons as $value ) {
				$icon_name = str_replace( array( '/', strrchr( $value[ 'custom_icon' ], '.' ) ), '', strrchr( $value[ 'custom_icon' ], '/' ) );
				echo '<label for="ci-icon-' . $icon_name . '-' . $input_name . '"><input name="icon" id="ci-icon-' . $icon_name . '-' . $input_name . '" type="radio" value="ci-icon-' . $icon_name . '"><i class="ci-icon-' . $icon_name . '"></i></label>';
			}
		}
		foreach ( mad_mm_datastore::get_all_icons() as $key => $value ) {
			echo '<label for="' . $value . '-' . $input_name . '"><input name="icon" id="' . $value . '-' . $input_name . '" type="radio" value="' . $value . '"><i class="' . $value . '"></i></label>';
//			echo '<label for="' . $value . '"><input name="icon" id="' . $value . '" type="radio" value="' . $value . '"><i class="' . $value . '"></i><div class="drop">' . $key . '<br />' . htmlentities('<i class="' . $value . '"></i>') . '</div></label>';
		}
		echo mad_mm_common::ntab(5) . '</div><!-- class="all_icons_container" -->';
		echo mad_mm_common::ntab(4) . '</div><!-- class="holder" -->';
		echo mad_mm_common::ntab(3) . '</div><!-- class="modal-body" -->';
		die();
	}
}
?>