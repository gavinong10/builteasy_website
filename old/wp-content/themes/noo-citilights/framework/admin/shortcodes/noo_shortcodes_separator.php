<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #type').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "line-with-text") {
        $('.line-with-text-child').show();
      } else {
        $('.line-with-text-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="type" class="noo-label"><?php _e('Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="type" id="type">
            <option value="line"><?php _e('Line Separator', 'noo'); ?></option>
            <option value="line-with-text"><?php _e('Text Separator', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group line-with-text-child" style="display: none;">
        <label for="title" class="noo-label"><?php _e('Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" value=""/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="size" class="noo-label"><?php _e('Size', 'noo'); ?></label>
        <div class="noo-control">
          <select name="size" id="size">
            <option value="fullwidth"><?php _e('Full-Width', 'noo'); ?></option>
            <option value="half"><?php _e('Half', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="position" class="noo-label"><?php _e('Position', 'noo'); ?></label>
        <div class="noo-control">
          <select name="position" id="position">
            <option value="center"><?php _e('Center', 'noo'); ?></option>
            <option value="left"><?php _e('Left', 'noo'); ?></option>
            <option value="right"><?php _e('Right', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="color"><?php _e('Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="color" type="text" name="color" class="noo-color-picker" style="display: inline-block;" value=""/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="thickness" class="noo-label"><?php _e('Line Thickness (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="thickness" name="thickness" class="noo-slider" value="2" data-min="0" data-max="10"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="" class="noo-label"><?php _e('Space Before (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="space_before" name="space_before" class="noo-slider" value="20" data-min="0" data-max="100" data-step="5"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="" class="noo-label"><?php _e('Space After (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="space_after" name="space_after" class="noo-slider" value="20" data-min="0" data-max="100" data-step="5"/>
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