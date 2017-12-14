<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Title (optional)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
          <small class="noo-control-desc"><?php _e( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo' ); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="style"><?php _e('Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="grid" selected="true"><?php _e('Grid', 'noo'); ?></option>
            <option value="list"><?php _e('List', 'noo'); ?></option>
            <option value="slider"><?php _e('Slider', 'noo'); ?></option>
            <option value="featured"><?php _e('Featured Style', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="show_control"><?php _e('Show layout control', 'noo'); ?></label>
        <div class="noo-control">
          <select name="show_control" id="show_control">
            <option value="no" selected="true"><?php _e( 'Hide', 'noo' ); ?></option>
            <option value="yes"><?php _e('Show', 'noo'); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e( 'Show/hide grid/list switching button.', 'noo' ); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="show_pagination"><?php _e('Show Pagination', 'noo'); ?></label>
        <div class="noo-control">
          <select name="show_pagination" id="show_pagination">
            <option value="no" selected="true"><?php _e( 'Hide', 'noo' ); ?></option>
            <option value="yes"><?php _e('Show', 'noo'); ?></option>
          </select>
          <small class="noo-control-desc"><?php _e( 'Show/hide Pagination.', 'noo' ); ?></small>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="show"><?php _e('Show', 'noo'); ?></label>
        <div class="noo-control">
          <select name="show" id="show">
            <option value="" selected="true"><?php _e('All Property','noo'); ?></option>
            <option value="featured"><?php _e('Only Featured Property','noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="number"><?php _e('Number of Properties to show', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="number" name="number" class="noo-slider" value="6" data-min="1" data-max="30"/>
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
