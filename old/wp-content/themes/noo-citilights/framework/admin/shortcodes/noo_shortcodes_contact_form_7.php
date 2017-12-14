<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Form Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="title" name="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="id"><?php _e('Contact Form', 'noo'); ?></label>
        <div class="noo-control">
          <select name="id" id="id">
            <?php
            $args = array(
              'post_type' => 'wpcf7_contact_form',
              'fields' => 'ID'
            );
            $contact_forms = new WP_Query( $args );
            foreach ($contact_forms->posts as $form) {
              echo '<option value="' . $form->ID . '">';
              echo esc_html($form->post_title) . '</option>';
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
