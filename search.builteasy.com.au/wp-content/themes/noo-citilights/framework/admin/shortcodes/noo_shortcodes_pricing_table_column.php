<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
require_once( 'class_editor_helper.php' );
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
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="featured"><?php _e('Featured', 'noo'); ?></label>
        <div class="noo-control">
          <input name="featured" id="featured" type="checkbox" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="price"><?php _e('Price', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="price" id="price" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="symbol"><?php _e('Currency Symbol', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="symbol" id="symbol" value="$" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="before_price"><?php _e('Text Before Price', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="before_price" id="before_price" value="<?php _e('From', 'noo'); ?>" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="after_price"><?php _e('Text After Price', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="after_price" id="after_price" value="<?php _e('per Month', 'noo'); ?>" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="pricing_item"><?php _e('Text', 'noo'); ?></label>
        <?php
        $default_text = '[icon_list]' . 
                        '[icon_list_item icon="fa fa-check"]Etiam rhoncus[/icon_list_item]' . 
                        '[icon_list_item icon="fa fa-times"]Donec mi[/icon_list_item]' . 
                        '[icon_list_item icon="fa fa-times"]Nam ipsum[/icon_list_item]' . 
                        '[/icon_list]';
        $editor_id = 'pricing_item' . uniqid();
        add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
        wp_editor( $default_text, $editor_id, array(
                    'media_buttons' => false,
                    'tinymce' => true,
                    'quicktags' => true,
                    'textarea_rows' => 8,
                    'textarea_cols' => 80,
                    'wpautop' => false));
        $mce_init = noo_editor_helper::get_mce_init($editor_id);
        $qt_init = noo_editor_helper::get_qt_init($editor_id);
        ?>
        <input type="hidden" id="pricing_item_editor_id" name="textblock_editor_id" value="<?php echo $editor_id; ?>">
        <script type="text/javascript">
          tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init; ?>);
          tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init; ?>);
        </script>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="button_text"><?php _e('Button Text', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="button_text" id="button_text" />
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
      <div class="noo-form-group">
        <label for="size"><?php _e('Button Size', 'noo'); ?></label>
        <div class="noo-control">
          <select name="size" id="size">
            <option value="x_small"><?php _e('Extra Small', 'noo'); ?></option>
            <option value="small"><?php _e('Small', 'noo'); ?></option>
            <option value="medium" selected="true"><?php _e('Medium', 'noo'); ?></option>
            <option value="large"><?php _e('Large', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="button_shape"><?php _e('Button Shape', 'noo'); ?></label>
        <div class="noo-control">
          <select name="button_shape" id="button_shape">
            <option value="square" selected="true"><?php _e('Square', 'noo'); ?></option>
            <option value="rounded"><?php _e('Rounded', 'noo'); ?></option>
            <option value="pill"><?php _e('Pill', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="button_style"><?php _e('Button Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="button_style" id="button_style">
            <option value="pressable"><?php _e('3D Pressable', 'noo'); ?></option>
            <option value="metro"><?php _e('Metro', 'noo'); ?></option>
            <option value="" selected="true"><?php _e('Blank', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="skin"><?php _e('Button Skin', 'noo'); ?></label>
        <div class="noo-control">
          <select name="skin" id="skin">
            <option value="default" selected="true"><?php _e('Default', 'noo'); ?></option>
            <option value="custom"><?php _e('Custom Style', 'noo'); ?></option>
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
        <label for="text_color"><?php _e('Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="text_color" type="text" name="text_color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover-color"><?php _e('Hover Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover-color" type="text" name="hover-color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="bg-color"><?php _e('Background Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="bg-color" type="text" name="bg-color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover-bg-color"><?php _e('Hover Background Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover-bg-color" type="text" name="hover-bg-color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="border-color"><?php _e('Border Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="border-color" type="text" name="border-color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
        </div>
      </div>
      <div class="noo-form-group skin-custom-child" style="display:none;">
        <label for="hover-border-color"><?php _e('Hover Border Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="hover-border-color" type="text" name="hover-border-color" class="noo-color-picker" style="display: inline-block;" value="#ffffff" />
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