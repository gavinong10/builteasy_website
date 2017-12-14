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
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
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
        <label for="alt"><?php _e('Alt Text', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="alt" id="alt" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="style"><?php _e('Image Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="" selected="true"><?php _e('None', 'noo'); ?></option>
            <option value="rounded"><?php _e('Rounded', 'noo'); ?></option>
            <option value="circle"><?php _e('Circle', 'noo'); ?></option>
            <option value="thumbnail"><?php _e('Thumbnail', 'noo'); ?></option>
          </select>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="href"><?php _e('Image Link', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="href" id="href" class="parent-control" />
          <small class="noo-control-desc"><?php _e( 'Input the URL if you want the image to wrap inside an anchor.', 'noo' ); ?></small>    
        </div>
        <div class="noo-control href-child" style="display:none; margin-left:30%;">
          <label for="target">
            <input name="target" id="target" type="checkbox" /><?php _e('Open in new tab', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group href-child" style="display:none;">
        <label for="link_title"><?php _e('Image Title', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="link_title" id="link_title" />
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
