<div class="cp_draggable tve_option_separator tve_clearfix" title="<?php echo __( "Thrive Leads Shortcodes", "thrive-leads" ) ?>">
	<div class="tve_icm tve-ic-my-library-books tve_left"></div>
	<span class="tve_expanded tve_left"><?php echo __( "Thrive Leads Forms", "thrive-leads" ) ?></span>
	<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>
	<div class="tve_clear"></div>
	<div class="tve_sub_btn" title="<?php echo __( "Table", "thrive-leads" ) ?>">
		<div class="tve_sub">
			<ul>
				<?php foreach ( $thrive_leads_shortcodes as $thrive_leads_shortcode ): ?>
					<li class="cp_draggable" title="<?php echo $thrive_leads_shortcode->post_title ?>" data-elem="sc_thrive_leads_shortcode" data-overlay="1"
					    data-wpapi="1">
						<input type="hidden" name="thrive_leads_shortcode_id" value="<?php echo $thrive_leads_shortcode->ID ?>"/>
						<div class="tve_icm tve-ic-plus"></div><?php echo $thrive_leads_shortcode->post_title ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>