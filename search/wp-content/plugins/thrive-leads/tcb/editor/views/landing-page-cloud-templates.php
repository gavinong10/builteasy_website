<?php
/**
 * Output the list of landing page templates fetched from the cloud, or just a simple button (in case of a successful download)
 *
 */ ?>
<?php if ( ! empty( $error ) ) : ?>
	<div class="tve_message tve_error tve_warning"><?php echo $error ?></div>
<?php elseif ( ! empty( $template_downloaded ) ) : ?>
	<?php if ( ! empty( $is_update ) ) : ?>
		<?php echo __( 'Template updated', 'thrive-cb' ) ?>
	<?php else : ?>
		<a href="javascript:void(0)"
		   class="tve_editor_button tve_editor_button_success tve_editor_button_small tve_click"
		   data-ctrl="function:tve_landing_page_cloud_select"
		   data-template="<?php echo $template_downloaded ?>"><?php echo __( 'Open', 'thrive-cb' ) ?></a>
		<span class="tve-grid-btn-sep">&nbsp;</span>
		<a href="http://landingpages.thrivethemes.com/thrive-landing-pages/?tcb_preview=<?php echo $template; ?>"
		   class="tve_editor_button tve_editor_button_default tve_editor_button_small"
		   target="_blank"><?php echo __( 'Preview', 'thrive-cb' ) ?></a>
		<div class="tve_clear"></div>
	<?php endif ?>
<?php elseif ( ! empty( $templates ) ) : ?>

	<?php
	foreach ( $templates as $_code => $tpl ) {
		if ( ! empty( $tpl['downloaded'] ) ) {
			$tpl['tags'] [] = 'tag-downloaded-template';
		}
		$_tags = '';
		foreach ( $tpl['tags'] as $tag ) {
			$_tags .= ' ' . str_replace( ' ', '-', $tag );
		}
		$templates[ $_code ]['tag_classes'] = $_tags;
	}
	?>

	<?php foreach ( $templates as $key => $template ) : ?>
		<div class="tve_cloud_tpl_item<?php echo ' ' . $template['tag_classes'] ?>">
            <span
	            class="tve_grid_cell<?php echo ! empty( $template['downloaded'] ) && $selected == $key ? ' tve_cell_template_cloud_selected' : '' ?>">
                <input type="hidden" class="lp_code" value="<?php echo $key ?>"/>
                <img src="<?php echo $template['thumb'] ?>" width="178" height="150"/>
                <span class="tve_cell_caption_holder"><span
		                class="tve_cell_caption"><?php echo $template['name'] ?></span></span>
                <span class="tve_cell_check tve_icm tve-ic-checkmark"></span>
	            <?php if ( ! empty( $template['update_available'] ) ) : ?>
		            <span class="tve_tpl_update">
                        <a href="javascript:void(0)" class="tve_click tve-tpl-update"
                           data-ctrl="function:tve_cloud_template_download"
                           data-template="<?php echo $key ?>"
                           title="<?php echo __( 'Click to update to the latest version', 'thrive-cb' ) ?>">
	                        <?php echo __( 'Update available. Click to update', 'thrive-cb' ) ?>
                        </a>
                    </span>
	            <?php endif ?>
            </span>
			<div class="tve_cell_actions" style="text-align: center">
				<?php if ( ! empty( $template['downloaded'] ) ) : ?>
					<a href="javascript:void(0)"
					   class="tve_click tve_editor_button tve_editor_button_success tve_editor_button_small"
					   data-ctrl="function:tve_landing_page_cloud_select"
					   data-template="<?php echo $key ?>"><?php echo __( 'Open', 'thrive-cb' ) ?></a>
				<?php else : ?>
					<a href="javascript:void(0)"
					   class="tve_click tve_editor_button tve_editor_button_success tve_editor_button_small"
					   data-ctrl="function:tve_cloud_template_download"
					   data-template="<?php echo $key ?>"
					   title="<?php echo __( 'Download', 'thrive-cb' ) ?>"><?php echo __( 'Download', 'thrive-cb' ) ?></a>
				<?php endif ?>
				<span class="tve-grid-btn-sep">&nbsp;</span>
				<a href="<?php echo $template['preview'] ?>"
				   class="tve_editor_button tve_editor_button_default tve_editor_button_small"
				   target="_blank"><?php echo __( 'Preview', 'thrive-cb' ) ?></a>
				<div class="tve_clear"></div>
			</div>
		</div>
	<?php endforeach ?>
<?php else : ?>
	<p><?php echo __( 'No templates found' ) ?></p>
<?php endif ?>
