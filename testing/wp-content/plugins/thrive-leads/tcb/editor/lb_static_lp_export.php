<h4><?php echo __( 'Export Template', 'thrive-cb' ) ?></h4>
<hr class='tve_lightbox_line'/>

<input type="hidden" name="tve_lb_type" value="landing_page_export">
<div class="tve_lb_fields">

	<label for="tve_lp_export_name" class="tve_lightbox_label"><?php echo __( 'Template Name', 'thrive-cb' ) ?></label>
	<input class="tve_lightbox_input tve_lightbox_input_inline" type="text" name="lp_name" id="lp_name" value="" style="width: 400px;">

	<div class="tve-sp"></div>

	<div class="tve-field-value">

		<div class="tve-sp"></div>
		<label for="" class="tve_lightbox_label"><?php echo __( "Thumbnail", "thrive-cb" ) ?></label>
        <span class="tve_comment">
            <?php echo __( 'Recommended image size: 166x140px. If you do not choose a picture, the default template thumbnail will be used.', 'thrive-cb' ) ?>
        </span>
		<div class="tve-sp"></div>
		<label for="" class="tve_lightbox_label">&nbsp;</label>
		<a href="javascript:void(0)"
		   class="tve_editor_button tve_editor_button_success tve_mousedown"
		   data-ctrl="controls.lp_export_thumb">
			<?php echo __( "Choose image", "thrive-cb" ) ?>
		</a>
		&nbsp;
		<a href="javascript:void(0)" class="tve_editor_button tve_editor_button_cancel tve-lp-remove-thumb tve_click"
		   data-ctrl="controls.lp_export_remove_thumb" style="display: none">
			<?php echo __( "Remove image", "thrive-cb" ) ?>
		</a>
		<input type="hidden" class="lp-export-attachment" name="lp_thumb" value=""/>
		<div class="tve-sp"></div>
		<label for="" class="tve_lightbox_label">&nbsp;</label><img src="" class="tve-lp-thumb" style="max-width:200px;display: none;border:1px solid #ccc;padding:1px;">

		<div class="tve-sp"></div>
	</div>
	<br/>
</div>