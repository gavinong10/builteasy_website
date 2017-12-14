<?php
/**
 * Output general background controls (background image, background pattern, clear etc)
 */
global $page_section_patterns, $template_uri; ?>
<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click" data-ctrl="function:ext.tve_leads.open_form_bg_media">
	<?php echo __( 'Background image...', 'thrive-leads' ) ?>
</li>
<?php if ( ! empty( $page_section_patterns ) ) : ?>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( 'Background pattern', 'thrive-leads' ) ?></span>
			<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="width: 715px;">
				<div class="tve_sub active_sub_menu" style="width: 100%">
					<ul class="tve_clearfix">
						<?php foreach ( $page_section_patterns as $i => $_image ) : ?>
							<?php $_uri = $template_uri . '/images/patterns/' . $_image . '.png' ?>
							<li class="tve_ed_btn tve_btn_text tve_left clearfix tve_click" data-fn="bgPattern" data-plugin="tve_leads_form">
								<span class="tve_section_colour tve_left" style="background:url('<?php echo $_uri ?>')"></span>
								<span class="tve_left"><?php echo 'pattern' . ( $i + 1 ); ?></span>
								<input type="hidden" data-image="<?php echo $_uri; ?>"/>
							</li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
	</li>
<?php endif ?>
<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click" data-plugin="tve_leads_form" data-fn="clearBgColor">
	<?php echo __( 'Clear background color', 'thrive-leads' ) ?>
</li>
<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click" data-plugin="tve_leads_form" data-fn="clearBgImage">
	<?php echo __( 'Clear background pattern / image', 'thrive-leads' ) ?>
</li>