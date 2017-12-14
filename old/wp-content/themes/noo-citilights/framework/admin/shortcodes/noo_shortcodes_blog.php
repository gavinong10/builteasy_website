<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery(document).ready(function($) {
    $('.noo-form-group #layout').change(function() {
      var value = $(this).find(':selected').val();
      if( value == "masonry") {
        $('.layout-masonry-child').show();
      } else {
        $('.layout-masonry-child').hide();
      }
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="layout"><?php _e('Layout', 'noo'); ?></label>
        <div class="noo-control">
          <select name='layout' id='layout'>
            <option value="list" selected="true"><?php _e('Default List', 'noo'); ?></option>
            <option value="masonry"><?php _e('Masonry', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group layout-masonry-child">
        <label for="columns"><?php _e('Columns', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="columns" name="columns" class="noo-slider" value="3" data-min="1" data-max="6"/>
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="categories"><?php _e('Blog Categories', 'noo'); ?></label>
        <div class="noo-control">
          <select name="categories" id="categories" multiple="true">
            <option value="all" selected="true"><?php _e('All', 'noo'); ?></option>
            <?php $categories = get_categories( array(
              'orderby' => 'NAME',
              'order' => 'ASC'
            ));
            foreach ($categories as $category) {
              echo '<option value="' . $category->term_id . '">';
              echo $category->name . '</option>';
            }
          ?>
          </select>
          <label for="filter" class="layout-masonry-child">
            <input name="filter" id="filter" type="checkbox" checked="true" /><?php _e('Show Filter', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="orderby"><?php _e('Order By', 'noo'); ?></label>
        <div class="noo-control">
          <select name="orderby" id="orderby">
            <option value="latest" selected="true"><?php _e('Recent First', 'noo'); ?></option>
            <option value="oldest"><?php _e('Older First', 'noo'); ?></option>
            <option value="alphabet"><?php _e('Title Alphabet', 'noo'); ?></option>
            <option value="ralphabet"><?php _e('Title Reversed Alphabet', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="post_count"><?php _e('Max Number of Posts', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="post_count" name="post_count" class="noo-slider" value="4" data-min="1" data-max="20"/>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_featured"><?php _e('Hide Featured Image(s)', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_featured" id="hide_featured" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_author"><?php _e('Hide Author Meta', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_author" id="hide_author" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_date"><?php _e('Hide Date Meta', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_date" id="hide_date" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_category"><?php _e('Hide Category Meta', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_category" id="hide_category" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_comment"><?php _e('Hide Comment Meta', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_comment" id="hide_comment" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="hide_readmore"><?php _e('Hide Readmore link', 'noo'); ?></label>
        <div class="noo-control">
          <input name="hide_readmore" id="hide_readmore" type="checkbox" checked="true" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="excerpt_length"><?php _e('Excerpt Length', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="excerpt_length" name="excerpt_length" value="55" />
        </div>
      </div>
      <hr>
      <div class="noo-form-group">
        <label for="title"><?php _e('Heading (optional)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="title" name="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="sub_title"><?php _e('Sub-Heading (optional)', 'noo'); ?></label>
        <div class="noo-control">
          <textarea id="sub_title" name="sub_title" ></textarea>
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
