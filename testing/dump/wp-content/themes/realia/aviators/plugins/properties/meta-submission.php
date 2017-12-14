<div class="control-group">
  <?php global $post; ?>
  <label class="control-label">
    <?php echo __('Price', 'aviators'); ?>
  </label>

  <div class="controls">
    <?php $mb->the_field('price'); ?>
    <div class="input-append">
      <input type="number" name="<?php $mb->the_name(); ?>" value="<?php echo get_post_meta($post->ID, '_property_price', true) ?>">
      <span class="add-on"><?php print aviators_settings_get_value('money', 'currency', 'sign'); ?></span>
    </div>
  </div>
</div>

<div class="control-group">
  <label class="control-label">
    <?php echo __('Bathrooms', 'aviators'); ?>
  </label>

  <div class="controls">
    <?php $mb->the_field('bathrooms'); ?>
    <input type="number" name="<?php $mb->the_name(); ?>" value="<?php echo get_post_meta($post->ID, '_property_bathrooms', true) ?>">
  </div>
</div>

<div class="control-group">
  <label class="control-label">
    <?php echo __('Bedrooms', 'aviators'); ?>
  </label>

  <div class="controls">
    <?php $mb->the_field('bedrooms'); ?>
    <input type="number" name="<?php $mb->the_name(); ?>" value="<?php echo get_post_meta($post->ID, '_property_bedrooms', true) ?>">
  </div>
</div>

<div class="control-group">
  <label class="control-label">
    <?php echo __('Area', 'aviators'); ?>
  </label>

  <div class="controls">
    <?php $mb->the_field('area'); ?>
    <div class="input-append">
      <input type="number" name="<?php $mb->the_name(); ?>" value="<?php echo get_post_meta($post->ID, '_property_area', true) ?>">
      <span class="add-on"><?php print aviators_settings_get_value('properties', 'units', 'area'); ?></span>
    </div>
  </div>
</div>

<div class="control-group">
  <label class="control-label">
    <?php echo __('GPS', 'aviators'); ?>
  </label>

  <div class="controls">
    <?php $mb->the_field('location_search'); ?>
    <?php $mb->the_value(); ?>

    <label>
      <?php echo __('Location search', 'aviators'); ?>
    </label>

    <input id="location-selector" class="location" type="text" name="<?php $mb->the_name(); ?>" value="<?php echo get_post_meta($post->ID, '_property_location_search', true) ?>" placeholder="<?php echo __('Search location', 'aviators'); ?>"/>

    <?php $mb->the_field('latitude'); ?>
    <?php $value = get_post_meta($post->ID, '_property_latitude', true) ?>
    <?php $value = !empty($value) ? $value : aviators_settings_get_value('properties', 'map', 'latitude'); ?>
    <label><?php echo __('Latitude', 'aviators'); ?></label>
    <input id="latitude-selector" class="latitude" type="text" name="<?php $mb->the_name(); ?>" value="<?php echo $value; ?>" placeholder="<?php echo __('Latitude', 'aviators'); ?>"/>

    <?php $mb->the_field('longitude'); ?>
    <?php $value = get_post_meta($post->ID, '_property_longitude', true) ?>
    <?php $value = !empty($value) ? $value : aviators_settings_get_value('properties', 'map', 'longitude'); ?>
    <label><?php echo __('Longitude', 'aviators'); ?></label>
    <input id="latitude-selector" class="longitude" type="text" name="<?php $mb->the_name(); ?>" value="<?php echo $value; ?>" placeholder="<?php echo __('Longitude', 'aviators'); ?>"/>

    <div id="map" style="height:300px; width: 100%;">

    </div>
  </div>
</div>

