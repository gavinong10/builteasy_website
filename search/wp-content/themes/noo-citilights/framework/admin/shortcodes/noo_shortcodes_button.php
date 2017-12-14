<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #skin').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "custom") {
        $('.skin-custom-child').show();
      } else {
        $('.skin-custom-child').hide();
      }
    });
    $('.noo-form-group #size').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "custom") {
        $('.size-custom-child').show();
      } else {
        $('.size-custom-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Text on the Button', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="href"><?php _e('URL (Link)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="href" id="href" />
          <label for="target">
            <input name="target" id="target" type="checkbox" /><?php _e('Open in new tab', 'noo'); ?>
          </label>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="size"><?php _e('Size', 'noo'); ?></label>
        <div class="noo-control">
          <select name="size" id="size">
            <option value="x_small"><?php _e('Extra Small', 'noo'); ?></option>
            <option value="small"><?php _e('Small', 'noo'); ?></option>
            <option value="medium" selected="true"><?php _e('Regular', 'noo'); ?></option>
            <option value="large"><?php _e('Large', 'noo'); ?></option>
            <option value="custom"><?php _e('Custom', 'noo'); ?></option>
          </select>
          <label for="fullwidth">
            <input name="fullwidth" id="fullwidth" type="checkbox" /><?php _e('Forge Full-Width', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group size-custom-child" style="display:none;">
        <label for="vertical_padding"><?php _e('Vertical Padding (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="vertical_padding" name="vertical_padding" class="noo-slider" value="10" data-min="0" data-max="50"/>
        </div>
      </div>
      <div class="noo-form-group size-custom-child" style="display:none;">
        <label for="horizontal_padding"><?php _e('Horizontal Padding (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="horizontal_padding" name="horizontal_padding" class="noo-slider" value="10" data-min="0" data-max="50"/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="icon"><?php _e('Icon', 'noo'); ?></label>
        <div class="noo-control">
          <div class="icon-selector">
            <input type="text" class="toolbox-icon-input" name="icon" id="icon" value="" />
            <a class="noo-fontawesome-dialog" id="nooFontAwesomeDialog" href="#" target="_blank"><i class="dashicons dashicons-search"></i><?php _e('Font Awesome', 'noo'); ?></a>
          </div>
          <label for="icon_right">
            <input name="icon_right" id="icon_right" type="checkbox" /><?php _e('Right Icon', 'noo'); ?>
          </label>
          <label for="icon_only">
            <input name="icon_only" id="icon_only" type="checkbox" /><?php _e('Show only Icon', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="icon_color"><?php _e('Icon Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="icon_color" type="text" name="icon_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="shape"><?php _e('Shape', 'noo'); ?></label>
        <div class="noo-control">
          <select name="shape" id="shape">
            <option value="square" selected="true"><?php _e('Square', 'noo'); ?></option>
            <option value="rounded"><?php _e('Rounded', 'noo'); ?></option>
            <option value="pill"><?php _e('Pill', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="style"><?php _e('Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="pressable"><?php _e('3D Pressable', 'noo'); ?></option>
            <option value="metro"><?php _e('Metro', 'noo'); ?></option>
            <option value="" selected="true"><?php _e('Blank', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="skin"><?php _e('Skin', 'noo'); ?></label>
        <div class="noo-control">
          <select name="skin" id="skin">
            <option value="default" selected="true"><?php _e('Default', 'noo'); ?></option>
            <option value="custom"><?php _e('Custom Skin', 'noo'); ?></option>
            <option value="primary"><?php _e('Primary', 'noo'); ?></option>
            <option value="success"><?php _e('Success', 'noo'); ?></option>
            <option value="info"><?php _e('Info', 'noo'); ?></option>
            <option value="warning"><?php _e('Warning', 'noo'); ?></option>
            <option value="danger"><?php _e('Danger', 'noo'); ?></option>
            <option value="link"><?php _e('Link', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="text_color"><?php _e('Text Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="text_color" type="text" name="text_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover_text_color"><?php _e('Hover Text Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover_text_color" type="text" name="hover_text_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="bg_color"><?php _e('Background Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="bg_color" type="text" name="bg_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover_bg_color"><?php _e('Hover Background Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover_bg_color" type="text" name="hover_bg_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="border_color"><?php _e('Border Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="border_color" type="text" name="border_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover_border_color"><?php _e('Hover Border Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover_border_color" type="text" name="hover_border_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
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
  <div id="idIconsDialog" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-body">
      <div class="divDialogElements">
        <div class="tabbable">
          <ul id="idTabTitles" class="nav nav-tabs" data-tabs="tabs">
            <li class="active"><a href="#one" data-toggle="tab">A</a></li>
            <li><a href="#two" data-toggle="tab">B</a></li>
            <li><a href="#three" data-toggle="tab">C</a></li>
            <li><a href="#four" data-toggle="tab">D</a></li>
            <li><a href="#five" data-toggle="tab">E</a></li>
            <li><a href="#six" data-toggle="tab">F</a></li>
            <li><a href="#seven" data-toggle="tab">G</a></li>
            <li><a href="#eight" data-toggle="tab">H</a></li>
            <li><a href="#nine" data-toggle="tab">I</a></li>
          </ul>
          <div id="idTabsContent" class="tab-content" style="margin-top: 15px;">
            <div class="active tab-pane" id="one"></div>
            <div class="tab-pane" id="two"></div>
            <div class="tab-pane" id="three"></div>
            <div class="tab-pane" id="four"></div>
            <div class="tab-pane" id="five"></div>
            <div class="tab-pane" id="six"></div>
            <div class="tab-pane" id="seven"></div>
            <div class="tab-pane" id="eight"></div>
            <div class="tab-pane" id="nine"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
