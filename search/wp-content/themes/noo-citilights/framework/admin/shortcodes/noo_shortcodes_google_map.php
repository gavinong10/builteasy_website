<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="content"><?php _e('Map Embed Iframe', 'noo'); ?></label>
        <div class="noo-control">
          <textarea name="content" id="content" ></textarea>
          <small class="noo-control-desc"><?php echo sprintf( __( 'Visit <a href="%s" target="_blank">Google maps</a> and create your map with following steps: 1) Find a location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.</span>', 'noo' ), 'http://maps.google.com/'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="size"><?php _e('Map Height', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="size" id="size" />
          <small class="noo-control-desc"><?php _e('Enter map height in pixels. Example: 200 or leave it empty to make map responsive.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="disable_zooming"><?php _e('Disable Zooming', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" name="diable_zooming" id="diable_zooming" />
          <small class="noo-control-desc"><?php _e('Disable zooming to prevent map accidentally zoom when mouse scroll over it.', 'noo'); ?></small>
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
