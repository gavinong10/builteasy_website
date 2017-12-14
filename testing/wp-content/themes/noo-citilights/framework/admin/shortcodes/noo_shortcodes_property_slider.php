<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group .parent-control').change(function() {
      var $this = $(this);
      var parent_active = false;
      var parent_type = $this.attr('type');
      var parent_id   = $this.attr('id');
      if(parent_type == 'text') {
        parent_active = ($this.val() !== '');
      } else if(parent_type == 'checkbox') {
        parent_active = ($this.is(':checked'));
      }

      if(parent_active) {
        $('.' + parent_id + '-child').show().find('input.parent-control').change();
      } else {
        $('.' + parent_id + '-child').hide().find('input.parent-control').change();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="animation"><?php _e('Animation', 'noo'); ?></label>
        <div class="noo-control">
          <select name="animation" id="animation">
            <option value="slide" selected="true"><?php _e('Slide', 'noo'); ?></option>
            <option value="fade"><?php _e('Fade', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="slider_time"><?php _e('Slide Time (ms)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="slider_time" name="slider_time" class="noo-slider" value="3000" data-min="500" data-max="8000" data-step="100"/>
        </div>
      </div> 
      <div class="noo-form-group">
        <label for="slider_speed"><?php _e('Slide Speed (ms)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="slider_speed" name="slider_speed" class="noo-slider" value="600" data-min="100" data-max="3000" data-step="100"/>
        </div>
      </div> 
      <div class="noo-form-group">
        <label for="auto_play"><?php _e('Auto Play Slider', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="auto_play" name="auto_play"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="indicator"><?php _e('Show Slide Indicator', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="indicator" name="indicator"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="prev_next_control"><?php _e('Show Previous/Next Navigation', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="prev_next_control" name="prev_next_control"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="show_search_form"><?php _e('Show Property Search', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="show_search_form" name="show_search_form" class="parent-control"/>
          <small class="noo-control-desc"><?php _e('Show Property Advanced Search below the slider.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group show_search_form-child" style="display:none;">
        <label for="show_search_info"><?php _e('Show Search Info', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="show_search_info" name="show_search_info"/> 
          <small class="noo-control-desc"><?php _e('Show Info text on top of property search form.', 'noo'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="visibility"><?php _e('Visibility', 'noo'); ?></label>
        <div class="noo-control">
          <select name="visibility" id="visibility">
            <option value="all" selected="true"><?php _e('All Devices', 'noo'); ?></option>
            <option value="hidden-phone"><?php _e('Hidden Phone', 'noo'); ?></option>
            <option value="hidden-tablet"><?php _e('Hidden Tablet', 'noo'); ?></option>
            <option value="hidden-pc"><?php _e('Hidden PC', 'noo'); ?></option>
            <option value="visible-phone"><?php _e('Visible Phone', 'noo'); ?></option>
            <option value="visible-tablet"><?php _e('Visible Tablet', 'noo'); ?></option>
            <option value="visible-pc"><?php _e('Visible PC', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="class"><?php _e('Class', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="class" id="class" />
          <small class="noo-control-desc"><?php _e('(Optional) Enter a unique class name.', 'noo'); ?></small>
        </div>
      </div>
    </div>
    <div class="noo-form-footer">
      <input type="button" name="insert" id="noo-save-shortcodes" class="button button-primary" value="<?php _e('Save', 'noo'); ?>"/>
      <input type="button" name="cancel" id="noo-cancel-shortcodes" class="button" value="<?php _e('Cancel', 'noo'); ?>"/>
    </div>
  </form>
</div>
