<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="audio_mp3"><?php _e('MP# File URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="audio_mp3" id="audio_mp3" />
          <small class="noo-control-desc"><?php _e('Place the URL to your .m4v audio file here.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="audio_oga"><?php _e('OGA File URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="audio_oga" id="audio_oga" />
          <small class="noo-control-desc"><?php _e('Place the URL to your .oga audio file here.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="auto_play"><?php _e('Auto Play Audio', 'noo'); ?></label>
        <div class="noo-control">
          <input name="auto_play" id="auto_play" type="checkbox" />
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
