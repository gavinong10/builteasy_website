<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #color').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "custom") {
        $('.color-custom-child').show();
      } else {
        $('.color-custom-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="word"><?php _e('Word', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="word" id="word" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="color"><?php _e('Color', 'noo'); ?></label>
        <div class="noo-control">
          <select name="color" id="color">
            <option value="default" selected="true"><?php _e('Default', 'noo'); ?></option>
            <option value="custom"><?php _e('Custom Style', 'noo'); ?></option>
            <option value="primary"><?php _e('Primary', 'noo'); ?></option>
            <option value="success"><?php _e('Success', 'noo'); ?></option>
            <option value="info"><?php _e('Info', 'noo'); ?></option>
            <option value="warning"><?php _e('Warning', 'noo'); ?></option>
            <option value="danger"><?php _e('Danger', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group color-custom-child" style="display: none;">
        <label for="custom_color"><?php _e('Custom Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="custom_color" type="text" name="custom_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="rounded"><?php _e('Rounded Corner', 'noo'); ?></label>
        <div class="noo-control">
          <input id="rounded" type="checkbox" checked="true" />
        </div>
      </div>
    <div class="noo-form-footer">
      <input type="button" name="insert" id="noo-save-shortcodes" class="button button-primary" value="<?php _e('Save', 'noo'); ?>"/>
      <input type="button" name="cancel" id="noo-cancel-shortcodes" class="button" value="<?php _e('Cancel', 'noo'); ?>"/>
    </div>
  </form>
</div>