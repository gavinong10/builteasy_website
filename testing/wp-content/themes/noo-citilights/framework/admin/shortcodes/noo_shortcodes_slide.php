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

    $('.noo-form-group #type').change(function() {
      var $this = $(this);
      if($this.find(':selected').val() == "image") {
        $('.type-image-child').show();
        $('.type-content-child').hide();
      } else {
        $('.type-image-child').hide();
        $('.type-content-child').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="type"><?php _e('Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="type" id="type">
            <option value="image" selected="true"><?php _e('Image', 'noo'); ?></option>
            <!-- <option value="video"><?php _e('Video', 'noo'); ?></option> -->
            <option value="content"><?php _e('HTML Content', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group type-image-child">
        <label for="image"><?php _e('Image', 'noo'); ?></label>
        <div class="noo-control">
          <input id="image" type="text" name="image" class="noo-wpmedia parent-control" value=""/>
          <a id="image-clear" style="display: none; text-decoration: none;" role="button" href="#" class="image-child">
            <i class="dashicons dashicons-no"></i><?php _e('Remove Image', 'noo'); ?>
          </a>
        </div>
        <script type="text/javascript">
          jQuery(document).ready(function($) {   
            $('#image-clear').click(function() {
              jQuery('#image').val('').change();
            });
          });
        </script>
      </div>
      <div class="noo-form-group type-image-child">
        <label for="caption"><?php _e('Caption', 'noo'); ?></label>
        <div class="noo-control">
          <textarea id="caption" name="caption"></textarea>
        </div>
      </div> 
      <!-- <div class="noo-form-group type-video-child" style="display:none;">
        <label for="video_url"><?php _e('Video URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="video_url" name="video_url"/>
        </div>
      </div>
      <div class="noo-form-group type-video-child" style="display:none;">
        <label for="video_poster"><?php _e('Video Poster Image', 'noo'); ?></label>
        <div class="noo-control">
          <input id="video_poster" type="hidden" name="video_poster" class="noo-wpmedia parent-control" value=""/>
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
      </div> -->
      <div class="noo-form-group type-content-child" style="display:none;">
        <label for="content"><?php _e('HTML Content', 'noo'); ?></label>
        <?php
        $editor_id = 'content' . uniqid();
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
        <input type="hidden" id="content_editor_id" name="content_editor_id" value="<?php echo $editor_id; ?>">
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
</div>
