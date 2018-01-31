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
        <label for="name"><?php _e('Member Name', 'noo'); ?></label>
        <div class="noo-control">
          <input id="name" type="text" name="name" value=""></textarea>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="avatar"><?php _e('Member Image', 'noo'); ?></label>
        <div class="noo-control">
          <input id="avatar" type="text" name="avatar" class="noo-wpmedia parent-control" value=""/>
          <a id="avatar-clear" style="display: none; text-decoration: none;" role="button" href="#" class="avatar-child">
            <i class="dashicons dashicons-no"></i><?php _e('Clear Image', 'noo'); ?>
          </a>
        </div>
        <script type="text/javascript">
          jQuery(document).ready(function($) {   
            $('#avatar-clear').click(function() {
              jQuery('#avatar').val('').change();
            });
          });
        </script>
      </div>
      <div class="noo-form-group">
        <label for="role"><?php _e('Job Position', 'noo'); ?></label>
        <div class="noo-control">
          <input id="role" type="text" name="role" value=""></textarea>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="description"><?php _e('Description', 'noo'); ?></label>
        <div class="noo-control">
          <textarea id="description" name="description" value=""></textarea>
          <small class="noo-control-desc"><?php _e('Input description here to override Author\'s description.', 'noo'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="facebook"><?php _e('Facebook Profile', 'noo'); ?></label>
        <div class="noo-control">
          <input id="facebook" type="text" name="facebook" value=""></textarea>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="twitter"><?php _e('Twitter Profile', 'noo'); ?></label>
        <div class="noo-control">
          <input id="twitter" type="text" name="twitter" value=""></textarea>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="googleplus"><?php _e('Google+ Profile', 'noo'); ?></label>
        <div class="noo-control">
          <input id="googleplus" type="text" name="googleplus" value=""></textarea>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="linkedin"><?php _e('LinkedIn Profile', 'noo'); ?></label>
        <div class="noo-control">
          <input id="linkedin" type="text" name="linkedin" value=""></textarea>
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
