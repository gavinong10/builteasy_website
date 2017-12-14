<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Post Grid options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$extra_attr              = 'data-multiple-hide';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li id="lb_post_grid" class="tve_ed_btn tve_btn tve_btn_text tve_click" data-load="1" data-wpapi="lb_post_grid" data-ctrl="controls.lb_open" data-multiple-hide><?php echo __( "Edit Grid", "thrive-cb" ) ?></li>
	<?php include dirname( __FILE__ ) . '/_custom_font.php' ?>

	<?php $font_size_label = __( 'Title Font Size', "thrive-cb" ) ?>
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
	<?php $font_size_label = null; ?>

	<?php $line_height_label = __( 'Title Line Height', "thrive-cb" ) ?>
	<?php include dirname( __FILE__ ) . '/_line_height.php' ?>
	<?php $line_height_label = null ?>

	<li class="tve_btn_text">
		<label>
			<?php echo __( "Image Max Height", "thrive-cb" ) ?> <input class="tve_text tve_height tve_change" data-ctrl-change="controls.height" type="text" size="3" maxlength="3"/> px
		</label>
	</li>
	<li class="tve_btn_text">
		<label>
			<?php echo __( "Read more text", "thrive-cb" ) ?> <input id="tve-post-grid-read-more-text" class="tve_text tve_change" type="text"/>
		</label>
	</li>
	<?php /*
    <li class="tve_ed_btn tve_btn_text">
        <div class="tve_option_separator">
            <span class="tve_ind tve_left">Horizontal</span><span id="sub_02" class="tve_caret tve_icm tve_left"></span>

            <div class="tve_clear"></div>
            <div class="tve_sub_btn">
                <div class="tve_sub active_sub_menu" style="display: block;">
                    <ul>
                        <li class="lead_generation_style tve_click tve_no_click" data-ctrl="controls.post_grid.design" data-args="vertical">
                            <div class="lead_generation_image" id="lead_generation_vertical_image"></div>
                            <div>Vertical</div>
                        </li>
                        <li class="lead_generation_style tve_click tve_no_click"data-ctrl="controls.post_grid.design" data-args="horizontal">
                            <div class="lead_generation_image" id="lead_generation_horizontal_image"></div>
                            <div>Horizontal</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </li>
    */ ?>
</ul>