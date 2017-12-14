<?php
$user_templates = get_option( 'tve_user_templates' );
$has_templates  = ! empty( $user_templates ); ?>
<div class="tve_option_separator">
	<div class="tve_icm tve-ic-file-text-o tve_left"></div>
	<span class="tve_expanded tve_left"><?php echo __( 'Content Templates', "thrive-cb" ) ?></span>
	<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

	<div class="tve_clear"></div>
	<div class="tve_sub_btn">
		<div class="tve_sub">
			<ul id="user_template_list">
				<?php if ( $has_templates ): ?>
					<?php foreach ( $user_templates as $tpl_key => $tpl ): ?>
						<li id="tve_user_template" class="cp_draggable user_template_item tve_clearfix" draggable="true" data-key="<?php echo $tpl_key ?>" data-usertpl="1">
							<input type="hidden" class="tpl_key" value="<?php echo $tpl_key ?>"/>
							<span class="tve_left"><?php echo urldecode( stripslashes( $tpl['name'] ) ); ?></span>
							<div class="tve_icm tve-ic-close tve_right tve_click" style="float: right; font-size: 10px; color: #bd362f" data-ctrl="controls.click.remove_user_template"></div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
				<li class="hide-on-tpl-save"<?php echo $has_templates ? ' style="display: none"' : '' ?>><?php echo __( "No Content Templates yet", "thrive-cb" ) ?></li>
			</ul>
		</div>
	</div>
</div>