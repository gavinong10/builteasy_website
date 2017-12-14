<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #style').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "transparent") {
        $('.style-transparent-no-child').hide();
      } else {
        $('.style-transparent-no-child').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="letter"><?php _e('Letter', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="letter" id="letter" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="color"><?php _e('Letter Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="color" type="text" name="color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="style"><?php _e('Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="transparent" selected="true"><?php _e('Transparent', 'noo'); ?></option>
            <option value="square"><?php _e('Filled Square', 'noo'); ?></option>
            <option value="circle"><?php _e('Filled Circle', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group style-transparent-no-child" style="display:none;">
        <label for="bg_color"><?php _e('Background Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="bg_color" type="text" name="bg_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
    </div>
    <div class="noo-form-footer">
      <input type="button" name="insert" id="noo-save-shortcodes" class="button button-primary" value="<?php _e('Save', 'noo'); ?>"/>
      <input type="button" name="cancel" id="noo-cancel-shortcodes" class="button" value="<?php _e('Cancel', 'noo'); ?>"/>
    </div>
  </form>
</div>