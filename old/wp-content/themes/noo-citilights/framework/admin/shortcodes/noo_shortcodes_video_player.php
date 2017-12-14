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
        <label for="video_m4v"><?php _e('M4V File URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="video_m4v" id="video_m4v" />
          <small class="noo-control-desc"><?php _e('Place the URL to your .m4v video file here.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="video_ogv"><?php _e('OGV File URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="video_ogv" id="video_ogv" />
          <small class="noo-control-desc"><?php _e('Place the URL to your .ogv video file here.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="video_ratio"><?php _e('Video Aspect Ratio', 'noo'); ?></label>
        <div class="noo-control">
          <select name="video_ratio" id="video_ratio">
            <option value="16:9" selected="true"><?php echo '16:9'; ?></option>
            <option value="5:3"><?php echo '5:3'; ?></option>
            <option value="5:4"><?php echo '5:4'; ?></option>
            <option value="4:3"><?php echo '4:3'; ?></option>
            <option value="3:2"><?php echo '3:2'; ?></option>
          </select>
          <small class="noo-control-desc"><?php _e('Choose the aspect ratio for your video.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="video_poster"><?php _e('Poster Image', 'noo'); ?></label>
        <div class="noo-control">
          <input id="video_poster" type="text" name="video_poster" class="noo-wpmedia parent-control" value=""/>
          <a id="video_poster-clear" style="display: none; text-decoration: none;" role="button" href="#" class="video_poster-child">
            <i class="dashicons dashicons-no"></i><?php _e('Remove Image', 'noo'); ?>
          </a>
        </div>
        <script type="text/javascript">
          jQuery(document).ready(function($) {   
            $('#video_poster-clear').click(function() {
              jQuery('#video_poster').val('').change();
            });
          });
        </script>
      </div>
      <div class="noo-form-group">
        <label for="auto_play"><?php _e('Auto Play Video', 'noo'); ?></label>
        <div class="noo-control">
          <input name="auto_play" id="auto_play" type="checkbox" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="hide_controls"><?php _e('Hide Player Controls', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_controls" id="hide_controls" type="checkbox" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="show_play_icon"><?php _e('Show Play Icon', 'noo'); ?></label>
        <div class="noo-control">
          <input name="show_play_icon" id="show_play_icon" type="checkbox" />
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
