<li class="slide-item noo-property-slide">
	<?php if($background_type == 'thumbnail') :
		echo get_the_post_thumbnail($property->ID,'property-slider');
	elseif ($background_type == 'image' && !empty($image)) :
		$thumbnail = wp_get_attachment_url($image); ?>
		<img class="slide-image" src="<?php echo $thumbnail; ?>">
	<?php endif; ?>
	<div class="slide-caption">
		<div class="slide-caption-info">
			<h3><a href="<?php echo esc_url(get_permalink($property->ID)); ?>">
	<?php echo $property_location = get_the_term_list($property->ID, 'property_location');//get_the_title($property->ID); ?></a>
				<?php if($address = noo_get_post_meta($property->ID,'_address')) : ?>
					<small><?php echo esc_html($address); ?></small>
				<?php endif; ?>
			</h3>
			<div class="info-summary">
			    <div class="bedrooms"><span><?php echo noo_get_post_meta($property->ID,'_bedrooms'); ?></span></div>
				<div class="bathrooms"><span><?php echo noo_get_post_meta($property->ID,'_bathrooms'); ?></span></div>
				<div class="parking"><span><?php echo noo_get_post_meta($property->ID,'_parking'); ?></span></div>
				<!--<div class="size"><span><?php //echo NooProperty::get_area_html($property->ID); ?></span></div>-->
				<div class="property-price"><span><?php echo NooProperty::get_price_html($property->ID); ?></span></div>
			</div>
		</div>
		<div class="slide-caption-action">
			<a href="<?php echo esc_url(get_permalink($property->ID)); ?>"><?php echo __('More Details','noo'); ?></a>
		</div>
	</div>
</li>