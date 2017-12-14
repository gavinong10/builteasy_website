<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Table options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$extra_attr              = 'data-multiple-hide';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Border Type"><?php echo __( "Border Type", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_brdr_none" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "none", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dotted" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dotted", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dashed" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dashed", "thrive-cb" ) ?></li>
						<li id="tve_brdr_solid" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "solid", "thrive-cb" ) ?></li>
						<li id="tve_brdr_double" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "double", "thrive-cb" ) ?></li>
						<li id="tve_brdr_groove" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "groove", "thrive-cb" ) ?></li>
						<li id="tve_brdr_ridge" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "ridge", "thrive-cb" ) ?></li>
						<li id="tve_brdr_inset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "inset", "thrive-cb" ) ?></li>
						<li id="tve_brdr_outset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "outset", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text clearfix" data-multiple-hide>
		<label class="tve_left" style="color: #878787">
			<input id="table_border_width" class="tve_change" value="0" type="text" size="3"
			       data-css-property="border-width" data-suffix="px"
			       data-size="1"> px
		</label>
	</li>
	<li class="tve_text clearfix" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="table_outer_border" value="1">
		<label for="table_outer_border" class="tve_left"><?php echo __( "Outer Border", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="table_inner_border" value="1">
		<label for="table_inner_border" class="tve_left"><?php echo __( "Inner Border", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix">
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="table_make_sortable" value="1">
		<label for="table_make_sortable" class="tve_left"><?php echo __( "Make table sortable", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_clear"></li>
	<li class="tve_text tve_firstOnRow"><?php echo __( "Alignment", "thrive-cb" ) ?></li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_align_left" class="tve_icm tve-ic-paragraph-left btn_alignment" title="<?php echo __( "Text align left", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_align_center" class="tve_icm tve-ic-paragraph-center btn_alignment" title="<?php echo __( "Text align center", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_align_right" class="tve_icm tve-ic-paragraph-right btn_alignment" title="<?php echo __( "Text align right", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_align_justify" class="tve_icm tve-ic-paragraph-justify btn_alignment" title="<?php echo __( "Text align justify", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_valign_top" class="tve_icm tve-ic-uniE634 btn_alignment" title="<?php echo __( "Vertical align top", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_valign_middle" class="tve_icm tve-ic-uniE635 btn_alignment" title="<?php echo __( "Vertical align middle", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve_table_valign_bottom" class="tve_icm tve-ic-uniE636 btn_alignment" title="<?php echo __( "Verical align bottom", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_text"><?php echo __( "Cell Padding", "thrive-cb" ) ?>&nbsp;<input id="table_cell_padding" class="tve_change" type="text" size="3" value="5" data-size="1" data-suffix="px"> px</li>
	<li class="tve_clear"></li>
	<li class="tve_firstOnRow">
		<div class="tve_ed_btn tve_btn_text tve_left tve_click" data-ctrl="controls.click.css" data-elem="> tbody > tr > td,> thead > tr > th"
		     data-prop="width" data-val=""
		     title="<?php echo __( "Reset all column widths to their initial values", "thrive-cb" ) ?>"><?php echo __( "Reset widths", "thrive-cb" ) ?>
		</div>
		<div class="tve_ed_btn tve_btn_text tve_left tve_click" data-ctrl="controls.click.css" data-elem="> tbody > tr > td,> thead > tr > th"
		     data-prop="height" data-val=""
		     title="<?php echo __( "Reset all row heights to their initial values", "thrive-cb" ) ?>"><?php echo __( "Reset heights", "thrive-cb" ) ?>
		</div>
		<div class="tve_ed_btn tve_btn_text tve_center tve_left tve_click" id="tve_table_manage_cells" data-multiple-hide>
			<?php echo __( "Manage cells...", "thrive-cb" ) ?>
		</div>
		<div class="tve_ed_btn tve_btn_text tve_center tve_left tve_click" id="tve_table_clear_alternating" title="<?php echo __( "Clear the alternating colors set for rows", "thrive-cb" ) ?>" data-multiple-hide>
			<?php echo __( "Clear alternating colors", "thrive-cb" ) ?>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>