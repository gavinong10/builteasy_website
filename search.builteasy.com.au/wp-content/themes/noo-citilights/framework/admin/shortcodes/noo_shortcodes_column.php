<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #animation').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() !== "") {
        $('.animation-child').show();
      } else {
        $('.animation-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">  
        <label  class="noo-label" for="width"><strong><?php _e('Column Width', 'noo'); ?></strong></label>
        <div class="noo-control">
          <select name="width" id="width">
            <option value="1/1" selected="true"><?php _e('One Whole (1/1)', 'noo'); ?></option>
            <option value="1/2"><?php _e('One Half (1/2)', 'noo'); ?></option>
            <option value="1/3"><?php _e('One Third (1/3)', 'noo'); ?></option>
            <option value="2/3"><?php _e('Two Thirds (2/3)', 'noo'); ?></option>
            <option value="1/4"><?php _e('One Fourth (1/4)', 'noo'); ?></option>
            <option value="3/4"><?php _e('Three Fourths (3/4)', 'noo'); ?></option>
            <option value="1/5"><?php _e('One Fifths (1/5)', 'noo'); ?></option>
            <option value="2/5"><?php _e('Two Fifths (2/5)', 'noo'); ?></option>
            <option value="3/5"><?php _e('Three Fifths (3/5)', 'noo'); ?></option>
            <option value="4/5"><?php _e('Four Fifths (4/5)', 'noo'); ?></option>
            <option value="1/6"><?php _e('One Six (1/6)', 'noo'); ?></option>
            <option value="5/6"><?php _e('Five Sixs (5/6)', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="alignment"><?php _e('Text Alignment', 'noo'); ?></label>
        <div class="noo-control">
          <select name="alignment" id="alignment">
            <option value="left" selected="true"><?php _e('Left', 'noo'); ?></option>
            <option value="center"><?php _e('Center', 'noo'); ?></option>
            <option value="right"><?php _e('Right', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label  class="noo-label" for="animation"><strong><?php _e('Select Animation', 'noo'); ?></strong></label>
        <div class="noo-control">
          <select name="animation" id="animation">
            <option value="" selected="true"><?php _e('None', 'noo'); ?></option>
            <option value="bounceIn"><?php _e("Bounce In", 'noo'); ?></option>
            <option value="bounceInRight"><?php _e("Bounce In Right", 'noo'); ?></option>
            <option value="bounceInLeft"><?php _e("Bounce In Left", 'noo'); ?></option>
            <option value="bounceInUp"><?php _e("Bounce In Up", 'noo'); ?></option>
            <option value="bounceInDown"><?php _e("Bounce In Down", 'noo'); ?></option>
            <option value="fadeIn"><?php _e("Fade In", 'noo'); ?></option>
            <option value="growIn"><?php _e("Grow In", 'noo'); ?></option>
            <option value="shake"><?php _e("Shake", 'noo'); ?></option>
            <option value="shakeUp"><?php _e("Shake Up", 'noo'); ?></option>
            <option value="fadeInLeft"><?php _e("Fade In Left", 'noo'); ?></option>
            <option value="fadeInRight"><?php _e("Fade In Right", 'noo'); ?></option>
            <option value="fadeInUp"><?php _e("Fade In Up", 'noo'); ?></option>
            <option value="fadeInDown"><?php _e("Fade InDown", 'noo'); ?></option>
            <option value="rotateIn"><?php _e("Rotate In", 'noo'); ?></option>
            <option value="rotateInUpLeft"><?php _e("Rotate In Up Left", 'noo'); ?></option>
            <option value="rotateInDownLeft"><?php _e("Rotate In Down Left", 'noo'); ?></option>
            <option value="rotateInUpRight"><?php _e("Rotate In Up Right", 'noo'); ?></option>
            <option value="rotateInDownRight"><?php _e("Rotate In Down Right", 'noo'); ?></option>
            <option value="rollIn"><?php _e("Roll In", 'noo'); ?></option>
            <option value="wiggle"><?php _e("Wiggle", 'noo'); ?></option>
            <option value="swing"><?php _e("Swing", 'noo'); ?></option>
            <option value="tada"><?php _e("Tada", 'noo'); ?></option>
            <option value="wobble"><?php _e("Wobble", 'noo'); ?></option>
            <option value="pulse"><?php _e("Pulse", 'noo'); ?></option>
            <option value="lightSpeedInRight"><?php _e("Light Speed In Right", 'noo'); ?></option>
            <option value="lightSpeedInLeft"><?php _e("Light Speed In Left", 'noo'); ?></option>
            <option value="flip"><?php _e("Flip", 'noo'); ?></option>
            <option value="flipInX"><?php _e("Flip In X", 'noo'); ?></option>
            <option value="flipInY"><?php _e("Flip In Y", 'noo'); ?></option>
            <option value="bounceOut"><?php _e("Bounce Out", 'noo'); ?></option>
            <option value="bounceOutUp"><?php _e("Bounce Out Up", 'noo'); ?></option>
            <option value="bounceOutDown"><?php _e("Bounce Out Down", 'noo'); ?></option>
            <option value="bounceOutLeft"><?php _e("Bounce Out Left", 'noo'); ?></option>
            <option value="bounceOutRight"><?php _e("Bounce Out Right", 'noo'); ?></option>
            <option value="fadeOut"><?php _e("Fade Out", 'noo'); ?></option>
            <option value="fadeOutUp"><?php _e("Fade Out Up", 'noo'); ?></option>
            <option value="fadeOutDown"><?php _e("Fade Out Down", 'noo'); ?></option>
            <option value="fadeOutLeft"><?php _e("Fade Out Left", 'noo'); ?></option>
            <option value="fadeOutRight"><?php _e("Fade Out Right", 'noo'); ?></option>
            <option value="flipOutX"><?php _e("Flip Out X", 'noo'); ?></option>
            <option value="flipOutY"><?php _e("Flip Out Y", 'noo'); ?></option>
            <option value="lightSpeedOutLeft"><?php _e("Light Speed Out Right", 'noo'); ?></option>
            <option value="rotateOut"><?php _e("Rotate Out", 'noo'); ?></option>
            <option value="rotateOutUpLeft"><?php _e("Rotate Out Up Left", 'noo'); ?></option>
            <option value="rotateOutDownLeft"><?php _e("Rotate Out Down Left", 'noo'); ?></option>
            <option value="rotateOutUpRight"><?php _e("Rotate Out Up Right", 'noo'); ?></option>
            <option value="rollOut"><?php _e("Roll Out", 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group animation-child" style="display:none;">
        <label  class="noo-label" for="animation_offset"><strong><?php _e('Animation Offset (px)', 'noo'); ?></strong></label>
        <div class="noo-control">
          <input type="text" id="animation_offset" name="animation_offset" class="noo-slider" value="40" data-min="0" data-max="200" data-step="10"/>
        </div>
      </div>
      <div class="noo-form-group animation-child" style="display:none;">
        <label  class="noo-label" for="animation_delay"><strong><?php _e('Animation Delay (ms)', 'noo'); ?></strong></label>
        <div class="noo-control">
          <input type="text" id="animation_delay" name="animation_delay" class="noo-slider" value="0" data-min="0" data-max="3000" data-step="50"/>
        </div>
      </div>
      <div class="noo-form-group animation-child" style="display:none;">
        <label  class="noo-label" for="animation_duration"><strong><?php _e('Animation Duration (ms)', 'noo'); ?></strong></label>
        <div class="noo-control">
          <input type="text" id="animation_duration" name="animation_duration" class="noo-slider" value="1000" data-min="0" data-max="3000" data-step="50"/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label  class="noo-label" for="visibility"><strong><?php _e('Visibility', 'noo'); ?></strong></label>
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