<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #background_type').change(function() {
      var value = $(this).find(':selected').val();
      
      $('.background_type-child').hide();
      if( value == "thumbnail") {
        $('.background_type-thumbnail-child').show();
      } else if( value == "image" ) {
        $('.background_type-image-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="background_type"><?php _e('Background Type', 'noo'); ?></label>
        <div class="noo-control">
          <select name="background_type" id="background_type">
            <option value="thumbnail" selected="true"><?php _e('Featured Image', 'noo'); ?></option>
            <option value="image"><?php _e('Custom Image', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group background_type-child background_type-image-child">
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
      <div class="noo-form-group">
        <label for="property_id"><?php _e('Property', 'noo'); ?></label>
        <div class="noo-control">
          <select name="property_id" id="property_id" multiple="true">
            <option value="all" selected="true"><?php _e('All', 'noo'); ?></option>
            <?php 
            $query_args = array(
              'posts_per_page' => -1,
              'post_type'    => 'noo_property'
              );
            $properties = get_posts($query_args);

            foreach ($properties as $property) {
              echo '<option value="' . $property->ID . '">';
              echo $property->post_title . '</option>';
            }
          ?>
          </select>
        </div>
      </div>
    </div>
    <div class="noo-form-footer">
      <input type="button" name="insert" id="noo-save-shortcodes" class="button button-primary" value="<?php _e('Save', 'noo'); ?>"/>
      <input type="button" name="cancel" id="noo-cancel-shortcodes" class="button" value="<?php _e('Cancel', 'noo'); ?>"/>
    </div>
  </form>
</div>
