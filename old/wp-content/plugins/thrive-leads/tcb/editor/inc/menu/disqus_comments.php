<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Disqus Comments", "thrive-cb" ) ?></span>
<ul class="tve_menu">

	<li class="tve_btn_text">
		<label class="tve_text">
			<?php echo __( "Disqus forum name", "thrive-cb" ); ?>
			<span class="tve_tooltip" data-title='<?php echo __( 'Your forum name is part of the address that you login to "http://xxxxxxxx.disqus.com" - the xxxxxxx is your shortname.  For example, with this URL: https://hairfreelife.disqus.com/ the shortname is "hairfreelife".', 'thrive-cb' ); ?>'>?</span>
			<input type="text" data-field="disqus_shortname" data-ctrl="controls.comments_element.disqus_field_change" class="tve_change tve_disqus_comment_field"/>
		</label>
	</li>

	<li class="tve_btn_text">
		<label class="tve_text">
			<?php echo __( "Disqus URL", "thrive-cb" ) ?>
			<span class="tve_tooltip" data-title='<?php echo __( 'If you leave this blank then the URL of the current piece of content will be used.  You can, however, specify a different URL to store comments against, if you prefer.', 'thrive-cb' ); ?>'>?</span>
			<input type="text" data-field="disqus_url" data-ctrl="controls.comments_element.disqus_field_change" class="tve_change tve_disqus_comment_field"/>
		</label>
	</li>
</ul>
