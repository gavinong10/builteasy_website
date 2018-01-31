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
      } else {
        $('.' + parent_id + '-child').hide().find('input.parent-control').change();
      }
    });

    $('.noo-form-group #type').change(function() {
      var $this = $(this);
      var $value = $this.find(':selected').val();
      if($value == "image") {
        $('.type-image-child').show();
        $('.type-iframe-child').hide();
        $('.type-inline-child').hide();
      } else if($value == "iframe") {
        $('.type-image-child').hide();
        $('.type-iframe-child').show();
        $('.type-inline-child').hide();
      } else {
        $('.type-image-child').hide();
        $('.type-iframe-child').hide();
        $('.type-inline-child').show();
      }
    });

    $('.noo-form-group #thumbnail_type').change(function() {
      var $this = $(this);
      var $value = $this.find(':selected').val();
      if($value == "image") {
        $('.thumbnail_type-image-child').show();
        $('.thumbnail_type-link-child').hide();
      } else {
        $('.thumbnail_type-image-child').hide();
        $('.thumbnail_type-link-child').show();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="gallery_id"><?php _e('Gallery ID', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="gallery_id" name="gallery_id"/>
          <small class="noo-control-desc"><?php _e('Lightbox elements with the same Gallery ID will be grouped to in the same slider lightbox.', 'noo'); ?></small>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="type"><?php _e('Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="type" id="type">
            <option value="image" selected="true"><?php _e('Image', 'noo'); ?></option>
            <option value="iframe"><?php _e('IFrame', 'noo'); ?></option>
            <option value="inline"><?php _e('HTML Content', 'noo'); ?></option>
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
        <label for="image_title"><?php _e('Image Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="image_title" name="image_title"/>
        </div>
      </div> 
      <div class="noo-form-group type-iframe-child" style="display:none;">
        <label for="iframe_url"><?php _e('Iframe URL', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="iframe_url" name="iframe_url"/>
          <small class="noo-control-desc"><?php _e('You can input any link like http://wikipedia.com. Youtube and Vimeo link will be converted to embed video, other video site will need embeded link.', 'noo'); ?></small>
        </div>
      </div>
      <div class="noo-form-group type-inline-child" style="display:none;">
        <label for="inline"><?php _e('HTML Content', 'noo'); ?></label>
        <?php
        $editor_id = 'inline' . uniqid();
        add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
        wp_editor( '', $editor_id, array(
                    'media_buttons' => false,
                    'quicktags' => true,
                    'textarea_rows' => 15,
                    'textarea_cols' => 80,
                    'wpautop' => false));
        $mce_init = noo_editor_helper::get_mce_init($editor_id);
        $qt_init = noo_editor_helper::get_qt_init($editor_id);
        ?>
        <input type="hidden" id="inline_editor_id" name="inline_editor_id" value="<?php echo $editor_id; ?>">
        <script type="text/javascript">
          tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init; ?>);
          tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init; ?>);
        </script>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="thumbnail_type"><?php _e('Thumbnail Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="thumbnail_type" id="thumbnail_type">
            <option value="image" selected="true"><?php _e('Image', 'noo'); ?></option>
            <option value="link"><?php _e('Link', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group thumbnail_type-image-child">
        <label for="thumbnail_image"><?php _e('Thumbnail Image', 'noo'); ?></label>
        <div class="noo-control">
          <input id="thumbnail_image" type="text" name="thumbnail_image" class="noo-wpmedia parent-control" value=""/>
          <small class="noo-control-desc"><?php _e('For image Lightbox, thumbnail of original image is automatically created if you do not choose any thumbnail.', 'noo'); ?></small>
          <a id="thumbnail_image-clear" style="display: none; text-decoration: none;" role="button" href="#" class="thumbnail_image-child">
            <i class="dashicons dashicons-no"></i><?php _e('Remove Image', 'noo'); ?>
          </a>
        </div>
        <script type="text/javascript">
          jQuery(document).ready(function($) {   
            $('#thumbnail_image-clear').click(function() {
              jQuery('#thumbnail_image').val('').change();
            });
          });
        </script>
      </div>
      <div class="noo-form-group thumbnail_type-image-child">
        <label for="thumbnail_style"><?php _e('Thumbnail style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="thumbnail_style" id="thumbnail_style">
            <option value=""><?php _e('None', 'noo'); ?></option>
            <option value="rounded" selected="true"><?php _e('Rounded', 'noo'); ?></option>
            <option value="circle"><?php _e('Circle', 'noo'); ?></option>
            <option value="thumbnail"><?php _e('Thumbnail', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group thumbnail_type-link-child" style="display:none;">
        <label for="thumbnail_title"><?php _e('Thumbnail Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="thumbnail_title" id="thumbnail_title" />
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
