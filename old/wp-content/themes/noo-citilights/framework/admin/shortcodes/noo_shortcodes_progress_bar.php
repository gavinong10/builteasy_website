<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>
<script>
  jQuery('document').ready(function($) {
    $('#bar_count').change(function() {
      var bar_count = parseInt(jQuery(this).val(), 10);
      $('#bar-item-group').empty();
      for(var i = 1; i <= bar_count; i++) {
        $('#bar-item-group').append(
          '<hr>' +
          '<div class="noo-form-group">' +
          ' <label for="bar_title_' + i + '"><?php _e("Bar Title", 'noo'); ?></label>' +
          ' <div class="noo-control">' +
          '   <input type="text" name="bar_title_' + i + '" id="bar_title_' + i + '" />' +
          ' </div>' +
          '</div>' +
          '<div class="noo-form-group">' +
          ' <label for="bar_progress_' + i + '"><?php _e("Progress", 'noo'); ?></label>' +
          ' <div class="noo-control">' +
          '   <input type="text" id="bar_progress_' + i + '" name="bar_progress_' + i + '" class="noo-slider" value="50" data-min="1" data-max="100"/>' +
          ' </div>' +
          '</div>' +
          '<div class="noo-form-group">' +
          ' <label for="bar_color_' + i + '"><?php _e("Color", 'noo'); ?></label>' +
          ' <div class="noo-control">' +
          '   <select type="text" id="bar_color_' + i + '" name="bar_color_' + i + '">' +
          '     <option value="primary" selected="true"><?php _e("Primary", 'noo'); ?></option>' +
          '      <option value="success"><?php _e("Success", 'noo'); ?></option>' +
          '      <option value="info"><?php _e("Info", 'noo'); ?></option>' +
          '      <option value="warning"><?php _e("Warning", 'noo'); ?></option>' +
          '      <option value="danger"><?php _e("Danger", 'noo'); ?></option>' +
          '    </select>' +
          ' </div>' +
          '</div>' +
          '<div class="noo-form-group">' +
          '  <label for="color_effect_' + i + '"><?php _e("Color Effect", 'noo'); ?></label>' +
          '  <div class="noo-control">' +
          '    <select name="color_effect_' + i + '" id="color_effect_' + i + '">' +
          '      <option value="" selected="true"><?php _e("None", 'noo'); ?></option>' +
          '      <option value="striped"><?php _e("Striped", 'noo'); ?></option>' +
          '      <option value="striped_animation"><?php _e("Striped with Animation", 'noo'); ?></option>' +
          '    </select>' +
          '  </div>' +
          '</div>'
        );
      }

      $('#bar-item-group').find('.noo-slider').each(function() {
        var $this = jQuery(this);

        var $slider = jQuery('<div>', {id: $this.attr("id") + "-slider"}).insertAfter($this);
        $slider.slider(
          {
            range: "min",
            value: $this.val() || $this.data('min') || 0,
            min: $this.data('min') || 0,
            max: $this.data('max') || 100,
            step: $this.data('step') || 1,
            slide: function(event, ui) {
              $this.val(ui.value).attr('value', ui.value);
            }
          }
        );

        $this.change(function() {
          $slider.slider( "option", "value", $this.val() );
        });
      })
    });
  });
</script>
<div id="noo-shortcodes-form-wrapper">
  <form id="noo-shortcodes-form" name="noo-shortcodes-form" method="post" action="">
    <div class="noo-form-body">
      <div class="noo-form-group">
        <label for="title"><?php _e('Title (optional)', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" name="title" id="title" />
        </div>
      </div>
      <div class="noo-form-group">
        <label for="style"><?php _e('Bar Style', 'noo'); ?></label>
        <div class="noo-control">
          <select name="style" id="style">
            <option value="lean" selected="true"><?php _e('Lean', 'noo'); ?></option>
            <option value="thick"><?php _e('Thick', 'noo'); ?></option>
          </select>
          <label for="rounded">
            <input type="checkbox" name="rounded" id="rounded" checked="true" />
            <?php _e('Rounded Bar', 'noo'); ?>
          </label>
        </div>
      </div>
      <div class="noo-form-group">
        <label for="bar_count"><?php _e('Number of Bar', 'noo'); ?></label>
        <div class="noo-control">
          <input type="text" id="bar_count" name="bar_count" value="3"/>
        </div>
      </div>
      <div class="noo-form-group bar-item-group" id="bar-item-group">
        <?php for( $i = 1; $i <= 3; $i++) : ?>
          <hr>
          <div class="noo-form-group">
            <label for="bar_title_<?php echo $i; ?>"><?php _e("Bar Title", 'noo'); ?></label>
            <div class="noo-control">
              <input type="text" name="bar_title_<?php echo $i; ?>" id="bar_title_<?php echo $i; ?>" />
            </div>
          </div>
          <div class="noo-form-group">
            <label for="bar_progress_<?php echo $i; ?>"><?php _e("Progress", 'noo'); ?></label>
            <div class="noo-control">
              <input type="text" id="bar_progress_<?php echo $i; ?>" name="bar_progress_<?php echo $i; ?>" class="noo-slider" value="50" data-min="1" data-max="100"/>
            </div>
          </div>
          <div class="noo-form-group">
            <label for="bar_color_<?php echo $i; ?>"><?php _e('Color', 'noo'); ?></label>
            <div class="noo-control">
              <select name="bar_color_<?php echo $i; ?>" id="bar_color_<?php echo $i; ?>">
                <option value="primary" selected="true"><?php _e('Primary', 'noo'); ?></option>
                <option value="success"><?php _e('Success', 'noo'); ?></option>
                <option value="info"><?php _e('Info', 'noo'); ?></option>
                <option value="warning"><?php _e('Warning', 'noo'); ?></option>
                <option value="danger"><?php _e('Danger', 'noo'); ?></option>
              </select>
            </div>
          </div>
          <div class="noo-form-group">
            <label for="color_effect_<?php echo $i; ?>"><?php _e('Color Effect', 'noo'); ?></label>
            <div class="noo-control">
              <select name="color_effect_<?php echo $i; ?>" id="color_effect_<?php echo $i; ?>">
                <option value="" selected="true"><?php _e('None', 'noo'); ?></option>
                <option value="striped"><?php _e('Striped', 'noo'); ?></option>
                <option value="striped_animation"><?php _e('Striped with Animation', 'noo'); ?></option>
              </select>
            </div>
          </div>
        <?php endfor; ?>
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
