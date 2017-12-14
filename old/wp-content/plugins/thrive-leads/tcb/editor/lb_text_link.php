<h4><?php echo __( "Insert Link", 'thrive-cb' ) ?></h4>
<hr class="tve_lightbox_line"/>
<input type="hidden" name="tve_lb_type" value="text_link">
<input class="tve_lightbox_input" name="lb_link" id="lb_link_location" placeholder="<?php echo __( "URL", 'thrive-cb' ) ?>"/>
<br/><br/>
<input class="tve_lightbox_input" name="lb_link_name" id="lb_link_name" placeholder="<?php echo __( "Anchor name (optional)", 'thrive-cb' ) ?>"/>
<br/><br/>
<div class="tve_lightbox_input_holder">
	<input type="checkbox" name="lb_link_target" id="lb_link_target"/><label for="lb_link_target"><?php echo __( "Open in new window?", "thrive-cb" ) ?></label>
</div>
<div class="tve_lightbox_input_holder">
	<input type="checkbox" name="lb_no_follow" id="lb_no_follow"/><label for="lb_no_follow"><?php echo __( "No follow link?", 'thrive-cb' ) ?></label>
</div>