<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
require_once( 'class_editor_helper.php' );
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
        $('.' + parent_id + '-no-child').hide().find('input.parent-control').change();
      } else {
        $('.' + parent_id + '-child').hide().find('input.parent-control').change();
        $('.' + parent_id + '-no-child').show().find('input.parent-control').change();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="icon"><?php _e('Icon', 'noo'); ?></label>
        <div class="noo-control">
          <div class="icon-selector">
            <input type="text" name="icon" id="icon" />
            <a class="noo-fontawesome-dialog" id="nooFontAwesomeDialog" href="#" target="_blank"><i class="dashicons dashicons-search"></i><?php _e('Font Awesome', 'noo'); ?></a>
          </div>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="icon_size"><?php _e('Icon Size (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="icon_size" name="icon_size" class="noo-slider" value="0" data-min="0" data-max="60"/>
          <small class="noo-control-desc"><?php _e('Leave it empty or 0 to use the base size of your theme.', 'noo'); ?></small>
        </div>
        <div class="noo-control">
          <label for="text_same_size">
            <input name="text_same_size" id="text_same_size" type="checkbox" class="parent-control" checked="checked" /><?php _e('Use This Size for Text', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group text_same_size-no-child" style="display:none;" >
        <label for="text_size"><?php _e('Text Size (px)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="text_size" name="text_size" class="noo-slider" value="" data-min="0" data-max="60"/>
          <small class="noo-control-desc"><?php _e('Leave it empty or 0 to use the base size of your theme.', 'noo'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group" >
        <label for="icon_color"><?php _e('Icon Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="icon_color" type="text" name="icon_color" class="noo-color-picker" style="display: inline-block;" />
        </div>
        <div class="noo-control">
          <label for="text_same_color">
            <input name="text_same_color" id="text_same_color" type="checkbox" class="parent-control" checked="checked" /><?php _e('Use This Color for Text', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group text_same_color-no-child" style="display:none;" >
        <label for="text_color"><?php _e('Text Color', 'noo'); ?></label>
        <div class="noo-control">
          <input id="text_color" type="text" name="text_color" class="noo-color-picker" style="display: inline-block;" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="icontext"><?php _e('Text', 'noo'); ?></label>
        <?php
        $editor_id = 'icontext' . uniqid();
        add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
        wp_editor( '', $editor_id, array(
                    'media_buttons' => false,
                    'tinymce' => true,
                    'quicktags' => true,
                    'textarea_rows' => 5,
                    'textarea_cols' => 80,
                    'wpautop' => false));
        $mce_init = noo_editor_helper::get_mce_init($editor_id);
        $qt_init = noo_editor_helper::get_qt_init($editor_id);
        ?>
        <input type="hidden" id="icontext_editor_id" name="icontext_editor_id" value="<?php echo $editor_id; ?>">
        <script type="text/javascript">
          tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init; ?>);
          tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init; ?>);
        </script>
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