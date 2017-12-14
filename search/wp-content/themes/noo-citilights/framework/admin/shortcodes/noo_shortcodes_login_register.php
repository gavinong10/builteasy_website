<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #mode').change(function() {
      var value = $(this).find(':selected').val();
      
      $('.mode-child').hide();
      if( value == "login") {
        $('.mode-login-child').show();
      } else if( value == "register" ) {
        $('.mode-register-child').show();
      } else if( value == "both" ) {
        $('.mode-both-child').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="mode"><?php _e('Mode', 'noo'); ?></label>
        <div class="noo-control">
          <select name="mode" id="mode">
            <option value="login" selected="true"><?php _e('Only Login form', 'noo'); ?></option>
            <option value="register"><?php _e('Only Register form', 'noo'); ?></option>
            <option value="both"><?php _e('Login and Register', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group mode-child mode-login-child mode-both-child">
        <label for="login_text"><?php _e('Login Text', 'noo'); ?></label>
        <div class="noo-control">
          <textarea id="login_text" name="login_text" ><?php _e( 'Already a member of CitiLights. Please use the form below to log in site.', 'noo' ); ?></textarea>
        </div>
      </div>
      <div class="noo-form-group mode-child mode-login-child mode-both-child">
        <label for="show_register_link"><?php _e('Show Register Link', 'noo'); ?></label>
        <div class="noo-control">
          <input type="checkbox" id="show_register_link" name="show_register_link" />
        </div>
      </div>
      <div class="noo-form-group mode-child mode-register-child mode-both-child" style="display: none;">
        <label for="register_text"><?php _e('Register Text', 'noo'); ?></label>
        <div class="noo-control">
          <textarea id="register_text" name="register_text" ><?php _e( 'Don\'t have an account? Please fill in the form below to create one.', 'noo' ); ?></textarea>
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
