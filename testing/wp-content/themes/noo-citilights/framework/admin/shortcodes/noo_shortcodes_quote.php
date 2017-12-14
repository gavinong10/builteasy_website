<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
require_once( 'class_editor_helper.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #type').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "block") {
        $('.type-block-child').show();
        $('.type-pull-child').hide();
      } else {
        $('.type-block-child').hide();
        $('.type-pull-child').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="quote"><?php _e('Quote', 'noo'); ?></label>
        <?php
        $editor_id = 'quote' . uniqid();
        add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
        wp_editor( '', $editor_id, array(
                    'media_buttons' => false,
                    'tinymce' => true,
                    'quicktags' => true,
                    'textarea_rows' => 8,
                    'textarea_cols' => 80,
                    'wpautop' => false));
        $mce_init = noo_editor_helper::get_mce_init($editor_id);
        $qt_init = noo_editor_helper::get_qt_init($editor_id);
        ?>
        <input type="hidden" id="quote_editor_id" name="quote_editor_id" value="<?php echo $editor_id; ?>">
        <script type="text/javascript">
          tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init; ?>);
          tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init; ?>);
        </script>
      </div>
      <div class="noo-form-group">
        <label for="cite"><?php _e('Cite', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="cite" id="cite" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="type"><?php _e('Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="type" id="type">
            <option value="block" selected="true"><?php _e('Block Quote', 'noo'); ?></option>
            <option value="pull"><?php _e('Pull Quote', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="alignment"><?php _e('Alignment', 'noo'); ?></label>
        <div class="noo-control">
          <select name="alignment" id="alignment">
            <option value="center"><?php _e('Center', 'noo'); ?></option>
            <option value="left"><?php _e('Left', 'noo'); ?></option>
            <option value="right"><?php _e('Right', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group type-pull-child" style="display:none;">
        <label for="position"><?php _e('Position', 'noo'); ?></label>
        <div class="noo-control">
          <select name="position" id="position">
            <option value="left"><?php _e('Left', 'noo'); ?></option>
            <option value="right"><?php _e('Right', 'noo'); ?></option>
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