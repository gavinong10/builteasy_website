<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Content Reveal options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_text tve_slider_config tve_firstOnRow"
	    data-value="5"
	    data-min-value="0"
	    data-max-value="21600"
	    data-input-selector=".content_reveal_after"
	    data-handler="content_reveal">
		<label for="" class="tve_left">&nbsp;<?php echo __( "Reveal content after", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="tve_content_reveal_slider"></div>
		</div>
		<input class="content_reveal_after minutes" type="text" value="0" size="2" maxlength="3"> m &nbsp; <input
			class="content_reveal_after seconds" type="text" value="5" size="2" maxlength="2"> s

		<div class="clear"></div>
	</li>
	<li class="tve_text">
		<input type="checkbox" id="content_reveal_scroll" class="tve_change" data-ctrl="controls.change.content_reveal_scroll">
		<label for="content_reveal_scroll">&nbsp; Auto scroll to content when revealed</label>
	</li>
	<li class="tve_clear"></li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li class="tve_btn_text">
		<label class="tve_text">
			<?php echo __( "Redirect to URL", "thrive-cb" ) ?> <input type="text" class="tve_change" id="content_reveal_redirect_url"/>
		</label>
	</li>
</ul>