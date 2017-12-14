<p>
    <label for="<?php echo $widget->get_field_id('title'); ?>"><?php _e('Title', MAD_BASE_TEXTDOMAIN) ?>:</label>
    <input class="widefat" type="text" id="<?php echo $widget->get_field_id('title'); ?>" name="<?php echo $widget->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('facebook_links'); ?>"><?php _e('Facebook Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('facebook_links'); ?>" name="<?php echo $widget->get_field_name('facebook_links'); ?>" value="<?php echo $instance['facebook_links']; ?>" />
</p>

<p>
    <label for="<?php echo $widget->get_field_id('twitter_links'); ?>"><?php _e('Twitter Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
    <input class="widefat" type="text" id="<?php echo $widget->get_field_id('twitter_links'); ?>" name="<?php echo $widget->get_field_name('twitter_links'); ?>" value="<?php echo $instance['twitter_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('gplus_links'); ?>"><?php _e('Google Plus Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('gplus_links'); ?>" name="<?php echo $widget->get_field_name('gplus_links'); ?>" value="<?php echo $instance['gplus_links']; ?>" />
</p>

<p>
	<?php
	$checked = "";
	if ($instance['rss_links'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('rss_links'); ?>" name="<?php echo $widget->get_field_name('rss_links'); ?>" value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('rss_links'); ?>"><?php _e('Show RSS Link', 'i') ?></label>
</p>

<p>
	<label for="<?php echo $widget->get_field_id('pinterest_links'); ?>"><?php _e('Pinterest Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('pinterest_links'); ?>" name="<?php echo $widget->get_field_name('pinterest_links'); ?>" value="<?php echo $instance['pinterest_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('instagram_links'); ?>"><?php _e('Instagram Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('instagram_links'); ?>" name="<?php echo $widget->get_field_name('instagram_links'); ?>" value="<?php echo $instance['instagram_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('linkedin_links'); ?>"><?php _e('Linkedin Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('linkedin_links'); ?>" name="<?php echo $widget->get_field_name('linkedin_links'); ?>" value="<?php echo $instance['linkedin_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('vimeo_links'); ?>"><?php _e('Vimeo Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('vimeo_links'); ?>" name="<?php echo $widget->get_field_name('vimeo_links'); ?>" value="<?php echo $instance['vimeo_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('youtube_links'); ?>"><?php _e('Youtube Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('youtube_links'); ?>" name="<?php echo $widget->get_field_name('youtube_links'); ?>" value="<?php echo $instance['youtube_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('flickr_links'); ?>"><?php _e('Flickr Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('flickr_links'); ?>" name="<?php echo $widget->get_field_name('flickr_links'); ?>" value="<?php echo $instance['flickr_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('vk_links'); ?>"><?php _e('Vkontakte Link', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('vk_links'); ?>" name="<?php echo $widget->get_field_name('vk_links'); ?>" value="<?php echo $instance['vk_links']; ?>" />
</p>

<p>
	<label for="<?php echo $widget->get_field_id('contact_us'); ?>"><?php _e('Contact us', MAD_BASE_TEXTDOMAIN) ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('contact_us'); ?>" name="<?php echo $widget->get_field_name('contact_us'); ?>" value="<?php echo $instance['contact_us']; ?>" />
</p>





