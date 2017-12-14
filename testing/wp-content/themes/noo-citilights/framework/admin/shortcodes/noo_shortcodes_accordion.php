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
      <div class="noo-form-group">
        <label for="tab_count"><?php _e('Number of Tabs', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="tab_count" name="tab_count" class="noo-slider" value="3" data-min="1" data-max="20"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="active_tab"><?php _e('Active Tab', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="active_tab" id="active_tab" />
          <small class="noo-control-desc"><?php _e('The tab number to be active on load, default is 1. Enter -1 to collapse all tabs.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="icon_style"><?php _e('Icon Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="icon_style" id="icon_style">
            <option value="dark_circe" selected="true"><?php _e('Dark Circle', 'noo'); ?></option>
            <option value="light_circe"><?php _e('Light Circle', 'noo'); ?></option>
            <option value="dark_square"><?php _e('Dark Square', 'noo'); ?></option>
            <option value="light_square"><?php _e('Light Square', 'noo'); ?></option>
            <option value="simple"><?php _e('Simple Icon', 'noo'); ?></option>
            <option value="left_arrow"><?php _e('Left Arrow', 'noo'); ?></option>
            <option value="right_arrow"><?php _e('Right Arrow', 'noo'); ?></option>
          </select>
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
