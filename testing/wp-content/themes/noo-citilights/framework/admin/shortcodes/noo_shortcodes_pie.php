<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Title (optional)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="value"><?php _e('Pie Value', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="value" name="value" class="noo-slider" value="50" data-min="0" data-max="100" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="label_value"><?php _e('Pie Label Value', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="label_value" name="label_value" class="noo-slider" value="50" data-min="0" data-max="100" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="units"><?php _e('Units', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="units" id="units" value="%" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="style"><?php _e('Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="filled" selected="true"><?php _e('Filled', 'noo'); ?></option>
            <option value="bordered"><?php _e('Bordered', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="color"><?php _e('Bar Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="color" type="text" name="color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="width"><?php _e('Bar Width (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="width" name="width" class="noo-slider" value="1" data-min="1" data-max="20" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="value_color"><?php _e('Value Label Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="value_color" type="text" name="value_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
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