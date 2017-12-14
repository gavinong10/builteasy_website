<li class="tve_ed_btn tve_btn_text<?php if ( isset( $btn_class ) ) {
	echo ' ' . $btn_class;
}
unset( $btn_class ) ?>">
	<div class="tve_option_separator">
        <span class="tve_ind tve_left"><?php if ( ! empty( $margin_prefix ) ) : echo $margin_prefix;
		        unset( $margin_prefix ); endif; ?>
	        <?php echo __( "Margins", "thrive-cb" ) ?><?php if ( empty( $margin_config['hide_padding'] ) ) : ?> & <?php echo __( "Paddings", "thrive-cb" ) ?><?php endif ?></span><span
			class="tve_caret tve_icm tve_left" id="sub_02"></span>

		<?php if ( ! empty( $css_selector ) ) : ?>
			<input type="hidden" class="css-selector" value="<?php echo $css_selector ?>"/>
			<?php unset( $css_selector ); endif ?>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix"
			     style="min-width: <?php echo empty( $margin_config['hide_padding'] ) ? '320px' : '150px' ?>">
				<ul style="width: 150px;">
					<li><strong><?php echo __( "Margin", "thrive-cb" ) ?></strong></li>
					<li class="tve_no_hover tve_no_click"><label class="tve_text"><span
								class="tve_label_spacer tve_small"><?php echo __( "Top:", "thrive-cb" ) ?> </span><input
								id="tve_margin_top" type="text" class="margin-control tve_change"
								data-size="1" data-target="margin-top" size="5"/> px</label></li>

					<li class="tve_no_hover tve_no_click"><label class="tve_text"><span
								class="tve_label_spacer tve_small"><?php echo __( "Bottom:", "thrive-cb" ) ?> </span><input
								id="tve_margin_top" type="text" class="margin-control tve_change"
								data-size="1" data-target="margin-bottom" size="5"/> px</label></li>

					<?php if ( empty( $margin_left_hide ) ) : ?>
						<li class="tve_no_hover tve_no_click"><label class="tve_text"><span
									class="tve_label_spacer tve_small"><?php echo __( "Left:", "thrive-cb" ) ?> </span><input
									id="tve_margin_top" type="text" class="margin-control tve_change"
									data-size="1" data-target="margin-left" size="5"/> px</label></li>
					<?php else : ?><?php unset( $margin_left_hide ); ?><?php endif; ?>

					<?php if ( empty( $margin_right_hide ) ) : ?>
						<li class="tve_no_hover tve_no_click"><label class="tve_text"><span
									class="tve_label_spacer tve_small"><?php echo __( "Right:", "thrive-cb" ) ?> </span><input
									id="tve_margin_top" type="text" class="margin-control tve_change"
									data-size="1" data-target="margin-right" size="5"/> px</label></li>
					<?php else : ?><?php unset( $margin_right_hide ); ?><?php endif; ?>

					<li id="tve_margin_clear"
					    class="tve_no_hover tve_no_click tve_ed_btn tve_btn_text tve_click tve_center "
					    style="text-align: center;"><?php echo __( "Clear margins", "thrive-cb" ) ?>
					</li>
				</ul>
				<?php if ( empty( $margin_config['hide_padding'] ) ) : ?>

					<?php if ( ! empty( $css_padding_selector ) ) : ?>
						<input type="hidden" class="css-padding-selector" value="<?php echo $css_padding_selector ?>"/>
						<?php unset( $css_padding_selector ); endif ?>
					<ul style="width: 150px;">
						<li><strong><?php echo __( "Padding", "thrive-cb" ) ?></strong></li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text"> <span class="tve_label_spacer tve_small"><?php echo __( "Top:", "thrive-cb" ) ?></span>
								<input id="tve_padding_top" type="text" class="padding-control tve_change" data-size="1"
								       data-target="padding-top" size="5"/> px
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text"> <span class="tve_label_spacer tve_small"><?php echo __( "Bottom:", "thrive-cb" ) ?></span>
								<input id="tve_padding_bottom" type="text" class="padding-control tve_change"
								       data-size="1" data-target="padding-bottom" size="5"/> px
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text"> <span class="tve_label_spacer tve_small"><?php echo __( "Left:", "thrive-cb" ) ?></span>
								<input id="tve_padding_left" type="text" class="padding-control tve_change"
								       data-size="1" data-target="padding-left" size="5"/> px
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text"> <span class="tve_label_spacer tve_small"><?php echo __( "Right:", "thrive-cb" ) ?></span>
								<input id="tve_padding_right" type="text" class="padding-control tve_change"
								       data-size="1" data-target="padding-right" size="5"/> px
							</label>
						</li>
						<li id="tve_padding_clear"
						    class="tve_no_hover tve_no_click tve_ed_btn tve_btn_text tve_click tve_center "
						    style="text-align: center;"><?php echo __( "Clear paddings", "thrive-cb" ) ?>
						</li>
					</ul>
				<?php endif ?>
			</div>
		</div>
	</div>
</li>
<?php unset( $margin_config ) ?>