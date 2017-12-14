
(function() {
  tinymce.PluginManager.add('noo_shortcodes', function(editor, url) {

    // if ( tinyMCE.activeEditor.id != 'content' ) {
    //   return null;
    // }

    url = noo_shortcodes_data.url;    
    jQuery.ajaxSetup ({
      // Disable caching of AJAX responses
      cache: false
    });

    var initShortcodeTB = function(title, data) {
      var form = jQuery(data);
      form.appendTo('body').hide();
      var width = jQuery(window).width(), W = ( 720 < width ) ? 640 : width - 80;
      var H = jQuery(window).height() - 100;
      
      tb_show( title, '#TB_inline?width=' + W + '&height=' + H + '&inlineId=noo-shortcodes-form-wrapper' );
      jQuery('#TB_window .noo-form-body').css('max-height', (H - 80) + 'px');
      jQuery('#TB_window .noo-color-picker').toggleClass('inline_block');
      jQuery('#TB_window .noo-color-picker').wpColorPicker();
      jQuery('#TB_window .noo-slider').each(function() {
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
      });
      
      //font awesome dialog
      jQuery('#TB_window .noo-fontawesome-dialog').click(function(e){
        initIconsDialog(jQuery(this));
        iconsDialogShow();
        e.preventDefault();
        e.stopPropagation();
      });

      jQuery('#TB_window #noo-cancel-shortcodes').click(function () {
        tb_remove();
      });      
    };

    var menu = [
      {
        text: noo_shortcodes_str.base_element,
        menu : [
          {
            text: noo_shortcodes_str.row,
            onclick: function () {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_row.php", function(data){

                initShortcodeTB(noo_shortcodes_str.row, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var type = jQuery('#TB_window #type option:selected').val();
                  var bg_color = jQuery('#TB_window #bg_color').val();
                  var bg_image = jQuery('#TB_window #bg_image').val();
                  var bg_image_position = 'center'; // jQuery('#TB_window #bg_image-position option:selected').val();
                  var bg_image_repeat = jQuery('#TB_window #bg_image_repeat').is(":checked") ? 'true' : 'false';
                  var parallax = jQuery('#TB_window #parallax').is(":checked") ? 'true' : 'false';
                  var parallax_no_mobile = jQuery('#TB_window #parallax_no_mobile').is(":checked") ? 'true' : 'false';
                  var parallax_velocity = jQuery('#TB_window #parallax_velocity').val();
                  var bg_video_url = '';
                  var bg_video_poster = '';

                  if( jQuery('#TB_window #bg_video').is(":checked") ) {
                    bg_video_url = jQuery('#TB_window #bg_video_url').val();
                    bg_video_poster = jQuery('#TB_window #bg_video_poster').val();
                  }

                  var inner_container = jQuery('#TB_window #inner_container').is(":checked");
                  var border = jQuery('#TB_window #border option:selected').val();
                  var padding_top = jQuery('#TB_window #padding_top').val();
                  var padding_bottom = jQuery('#TB_window #padding_bottom').val();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  var id = jQuery('#TB_window #id').val();
                  tb_remove();

                  var shortcode = '[vc_row';
                  if(bg_color !== '') 
                    shortcode += ' bg_color="' + bg_color + '"';
                  if(bg_image !== '') {
                    shortcode += ' bg_image="' + bg_image + '"';

                    if(bg_color_overlay !== '')
                      shortcode += ' bg_color_overlay="' + bg_color_overlay + '"';
                    if(bg_image_repeat !== '')
                      shortcode += ' bg_repeat="' + bg_image_repeat + '"';
                  }

                  shortcode += ' parallax="' + parallax + '"';
                  if( parallax == 'true' ) {
                    if( parallax_no_mobile == 'true' ) {
                      shortcode += ' parallax_no_mobile="' + parallax_no_mobile + '"';
                    }

                    if( parallax_velocity !== '' ) {
                      shortcode += ' parallax_velocity="' + parallax_velocity + '"';
                    }
                  }

                  if(bg_video_url !== '')
                    shortcode += ' bg_video="' + bg_video_url + '"';
                  if(bg_video_poster !== '')
                    shortcode += ' bg_video_poster="' + bg_video_poster + '"';

                  if(inner_container)
                    shortcode += ' inner_container="true"';
                  if(padding_top !== '')
                    shortcode += ' padding_top="' + padding_top + '"';
                  if(padding_bottom !== '')
                    shortcode += ' padding_bottom="' + padding_bottom + '"';

                  shortcode += ' border="' + border + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';
                  if(id !== '')
                    shortcode += ' id="' + id + '"';

                  shortcode += '] Add text or other shortcode here... [/vc_row]';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.column,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_column.php", function(data){

                initShortcodeTB(noo_shortcodes_str.column, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var width = jQuery('#TB_window #width option:selected').val();
                  var alignment = jQuery('#TB_window #alignment option:selected').val();
                  var animation = jQuery('#TB_window #animation option:selected').val();

                  var animation_offset = '';
                  var animation_delay = '';
                  if(animation !== "") {
                    animation_offset = jQuery('#TB_window #animation_offset').val();
                    animation_delay = jQuery('#TB_window #animation_delay').val();
                    animation_duration = jQuery('#TB_window #animation_duration').val();
                  }

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_column width="' + width + '"';

                  if(alignment !== "")
                    shortcode += ' alignment="' + alignment + '"';
                  if(animation !== "") {
                    shortcode += ' animation="' + animation + '"';

                    if(animation_offset !== '') {
                      shortcode += ' animation_offset="' + animation_offset + '"';
                    }
                    if(animation_delay !== '') {
                      shortcode += ' animation_delay="' + animation_delay + '"';
                    }
                    if(animation_duration !== '') {
                      shortcode += ' animation_duration="' + animation_duration + '"';
                    }
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += '] Add your content here... [/vc_column]';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.animation,
            onclick: function() {
              jQuery("#noo-shortcodes-form-animation").remove();
              jQuery.get(url + "/noo_shortcodes_animation.php", function(data){

                initShortcodeTB(noo_shortcodes_str.animation, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var animation = jQuery('#TB_window #animation option:selected').val();

                  var animation_offset = '';
                  var animation_delay = '';
                  if(animation !== "") {
                    animation_offset = jQuery('#TB_window #animation_offset').val();
                    animation_delay = jQuery('#TB_window #animation_delay').val();
                    animation_duration = jQuery('#TB_window #animation_duration').val();
                  }

                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[animation';

                  if(animation !== "") {
                    shortcode += ' animation="' + animation + '"';

                    if(animation_offset !== '') {
                      shortcode += ' animation_offset="' + animation_offset + '"';
                    }
                    if(animation_delay !== '') {
                      shortcode += ' animation_delay="' + animation_delay + '"';
                    }
                    if(animation_duration !== '') {
                      shortcode += ' animation_duration="' + animation_duration + '"';
                    }
                  }

                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += '] Add shortcode/content here... [/animation]';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.separator,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_separator.php", function(data){

                initShortcodeTB(noo_shortcodes_str.separator, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var type = jQuery('#TB_window #type option:selected').val();
                  var title = (type == 'line-with-text') ? jQuery('#TB_window #title').val() : '';
                  var size = jQuery('#TB_window #size option:selected').val();
                  var position = jQuery('#TB_window #position option:selected').val();
                  var color = jQuery('#TB_window #color').val();
                  var thickness = jQuery('#TB_window #thickness').val();
                  thickness = (thickness !== '') ? thickness : 3;
                  var space_before = jQuery('#TB_window #space_before').val();
                  space_before = (space_before !== '') ? space_before : 50;
                  var space_after = jQuery('#TB_window #space_after').val();
                  space_after = (space_after !== '') ? space_after : 50;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_separator type="' + type + '"';
                  if(title !== "")
                    shortcode += ' title="' + title + '"';

                  shortcode += ' size="' + size + '"';
                  shortcode += ' position="' + position + '"';
                  if(color !== "")
                    shortcode += ' color="' + color + '"';

                  shortcode += ' thickness="' + thickness + '"';
                  shortcode += ' space_before="' + space_before + '"';
                  shortcode += ' space_after="' + space_after + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.gap,
            onclick: function() {
              // editor.windowManager.open( {
              //   title: noo_shortcodes_str.gap,
              //   body: [
              //       {
              //           type: 'textbox',
              //           name: 'size',
              //           label: noo_shortcodes_str.size,
              //           value: '100'
              //       }
              //   ],
              //   onsubmit: function( e ) {
              //       editor.insertContent( '[gap size="' + e.data.size + '"]');
              //   }
              // });
              jQuery("#noo-shortcodes-form-wrapper").remove();
              jQuery.get(url + "/noo_shortcodes_gap.php", function(data){

                initShortcodeTB(noo_shortcodes_str.gap, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var size = jQuery('#TB_window #size').val();
                  var  visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[gap size="' + size + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  tb_remove();
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.clear,
            onclick: function() {
              editor.insertContent( '[clear]');
            }
          }
        ]
      },

      {
        text: noo_shortcodes_str.typography,
        menu: [
          {
            text: noo_shortcodes_str.textblock,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_textblock.php", function(data){

                initShortcodeTB(noo_shortcodes_str.textblock, data);
                
                var textblock_editor_id = jQuery('#TB_window #textblock_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[textblock_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[textblock_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var text = tinyMCE.activeEditor.getContent();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_column_text';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + text + '[/vc_column_text]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.button,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_button.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.button, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var title = jQuery('#TB_window #title').val();
                  var href = jQuery('#TB_window #href').val();
                  var target = jQuery('#TB_window #target').is(":checked");
                  var size = jQuery('#TB_window #size option:selected').val();
                  var fullwidth = jQuery('#TB_window #fullwidth').is(":checked");
                  var vertical_padding = jQuery('#TB_window #vertical_padding').val();
                  var horizontal_padding = jQuery('#TB_window #horizontal_padding').val();
                  var icon = jQuery('#TB_window #icon').val();
                  var icon_right = jQuery('#TB_window #icon_right').is(":checked");
                  var icon_only = jQuery('#TB_window #icon_only').is(":checked");
                  var icon_color = jQuery('#TB_window #icon_color').val();
                  var shape = jQuery('#TB_window #shape option:selected').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var skin = jQuery('#TB_window #skin option:selected').val();
                  var text_color = '';
                  var hover_text_color = '';
                  var bg_color = '';
                  var hover_bg_color = '';
                  var border_color = '';
                  var hover_border_color = '';
                  if(skin == 'custom') {
                    text_color = jQuery('#TB_window #text_color').val();
                    hover_text_color = jQuery('#TB_window #hover_text_color').val();
                    bg_color = jQuery('#TB_window #bg_color').val();
                    hover_bg_color = jQuery('#TB_window #hover_bg_color').val();
                    border_color = jQuery('#TB_window #border_color').val();
                    hover_border_color = jQuery('#TB_window #hover_border_color').val();
                  }

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_button';

                  shortcode += ' title="' + title + '"';
                  if(href !== '')
                    shortcode += ' href="' + href + '"';
                  else
                    shortcode += ' href="#"';
                  if(target)
                    shortcode += ' target="true"';

                  shortcode += ' size="' + size + '"';
                  if(fullwidth)
                    shortcode += ' fullwidth="true"';
                  if( size === 'custom' ) {
                    if(vertical_padding !== '')
                      shortcode += ' vertical_padding="' + vertical_padding + '"';
                    if(horizontal_padding !== '')
                      shortcode += ' horizontal_padding="' + horizontal_padding + '"';
                  }

                  if(icon !== '') {
                    shortcode += ' icon="' + icon + '"';

                    if(icon_color !== '')
                      shortcode += ' icon_color="' + icon_color + '"';
                    if(icon_right)
                      shortcode += ' icon_right="true"';
                    if(icon_only)
                      shortcode += ' icon_only="true"';
                  }
                  if(shape !== '') {
                    shortcode += ' shape="' + shape + '"';
                  }
                  if(style !== '') {
                    shortcode += ' style="' + style + '"';
                  }
                  if(skin !== '') {
                    shortcode += ' skin="' + skin + '"';
                  }
                  if(skin == 'custom') {
                    if(text_color !== '')
                      shortcode += ' text_color="' + text_color + '"';
                    if(hover_text_color !== '')
                      shortcode += ' hover_text_color="' + hover_text_color + '"';
                    if(bg_color !== '')
                      shortcode += ' bg_color="' + bg_color + '"';
                    if(hover_bg_color !== '')
                      shortcode += ' hover_bg_color="' + hover_bg_color + '"';
                    if(border_color !== '')
                      shortcode += ' border_color="' + border_color + '"';
                    if(hover_border_color !== '')
                      shortcode += ' hover_border_color="' + hover_border_color + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          // {
          //   text: noo_shortcodes_str.headline,
          //   onclick: function() {
          //     jQuery("#idIconsDialog").remove();
          //     jQuery("#noo-shortcodes-form-wrapper").remove();

          //     jQuery.get(url + "/noo_shortcodes_headline.php", function(data){
                
          //       initShortcodeTB(noo_shortcodes_str.headline, data);

          //       var headline_editor_id = jQuery('#TB_window #headline_editor_id').val();
          //       tinyMCE.init(tinyMCEPreInit.mceInit[headline_editor_id]);
          //       try {
          //         quicktags( tinyMCEPreInit.qtInit[headline_editor_id] );
          //         QTags._buttonsInit();
          //       }
          //       catch(e){
          //       }

          //       jQuery('#TB_window #noo-save-shortcodes').click(function(){
          //         var headline = tinyMCE.activeEditor.getContent();
          //         var level = jQuery('#TB_window #level option:selected').val();
          //         var alignment = jQuery('#TB_window #alignment option:selected').val();
          //         var icon = jQuery('#TB_window #icon').val();
          //         var visibility = jQuery('#TB_window #visibility option:selected').val();
          //         var el_class = jQuery('#TB_window #class').val();
          //         tb_remove();

          //         var shortcode = '[headline level="' + level + '" alignment="' + alignment + '"';

          //         if(icon !== '')
          //           shortcode += ' icon="' + icon + '"';

          //         if(visibility !== '')
          //           shortcode += ' visibility="' + visibility + '"';
          //         if(el_class !== '')
          //           shortcode += ' class="' + el_class + '"';

          //         shortcode += ']' + headline + '[/headline]';

          //         editor.insertContent(shortcode);
          //         return false;
          //       });
          //     });
          //   }
          // },

          {
            text: noo_shortcodes_str.dropcap,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_dropcap.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.dropcap, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var letter = jQuery('#TB_window #letter').val();
                  if( letter === '' ) {
                    return false;
                  }
                  var style = jQuery('#TB_window #style option:selected').val();
                  var color = jQuery('#TB_window #color').val();
                  var bg_color = '';
                  if(style !== 'transparent') {
                    bg_color = jQuery('#TB_window #bg_color').val();
                  }

                  tb_remove();

                  var shortcode = '[dropcap letter="' + letter + '" style="' + style + '"';

                  if(color !== '')
                    shortcode += ' color="' + color + '"';

                  if(bg_color !== '')
                    shortcode += ' bg_color="' + bg_color + '"';

                  shortcode += '] ' + letter + ' [/dropcap]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.quote,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_quote.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.quote, data);

                var quote_editor_id = jQuery('#TB_window #quote_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[quote_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[quote_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var quote = tinyMCE.activeEditor.getContent();
                  var cite = jQuery('#TB_window #cite').val();
                  var type = jQuery('#TB_window #type option:selected').val();
                  var alignment = '';
                  var position = '';
                  if( type === 'block' )
                    alignment = jQuery('#TB_window #alignment option:selected').val();
                  else
                    position = jQuery('#TB_window #position option:selected').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[quote type="' + type + '"';

                  if(cite !== '')
                    shortcode += ' cite="' + cite + '"';

                  shortcode += ' alignment="' + alignment + '"';

                  if( type === 'pull' )
                    shortcode += ' position="' + position + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + quote + '[/quote]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.icon,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_icon.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.icon, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var icon = jQuery('#TB_window #icon').val();
                  if(icon === '') {
                    tb_remove();
                    return;
                  }
                  var size = jQuery('#TB_window #size option:selected').val();
                  var custom_size = '';
                  if(size == 'custom') {
                    custom_size = jQuery('#TB_window #custom_size').val();
                  }

                  var icon_color = jQuery('#TB_window #icon_color').val();
                  var hover_icon_color = jQuery('#TB_window #hover_icon_color').val();

                  var shape = jQuery('#TB_window #shape option:selected').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var bg_color = '';
                  var hover_bg_color = '';
                  var border_color = '';
                  var hover_border_color = '';

                  if(style == 'custom') {
                    bg_color = jQuery('#TB_window #bg_color').val();
                    hover_bg_color = jQuery('#TB_window #hover_bg_color').val();
                    border_color = jQuery('#TB_window #border_color').val();
                    hover_border_color = jQuery('#TB_window #hover_border_color').val();
                  }

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[icon icon="' + icon + '"';

                  if(size !== '')
                    shortcode += ' size="' + size + '"';
                  if(size === 'custom')
                    shortcode += ' custom_size="' + custom_size + '"';

                  if(icon_color !== '')
                    shortcode += ' icon_color="' + icon_color + '"';
                  if(hover_icon_color !== '')
                    shortcode += ' hover_icon_color="' + hover_icon_color + '"';

                  shortcode += ' shape="' + shape + '" style="' + style + '"';
                  if(style == 'custom') {
                    if(bg_color !== '')
                      shortcode += ' bg_color="' + bg_color + '"';
                    if(hover_bg_color !== '')
                      shortcode += ' hover_bg_color="' + hover_bg_color + '"';
                    if(border_color !== '')
                      shortcode += ' border_color="' + border_color + '"';
                    if(hover_border_color !== '')
                      shortcode += ' hover_border_color="' + hover_border_color + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.social_icon,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_social_icon.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.social_icon, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var icon = jQuery('#TB_window #icon').val();
                  if(icon === '') {
                    tb_remove();
                    return;
                  }
                  var href = jQuery('#TB_window #href').val();
                  var target = jQuery('#TB_window #target').is(":checked");
                  var size = jQuery('#TB_window #size option:selected').val();
                  var custom_size = '';
                  if(size == 'custom') {
                    custom_size = jQuery('#TB_window #custom_size').val();
                  }

                  var icon_color = jQuery('#TB_window #icon_color').val();
                  var hover_icon_color = jQuery('#TB_window #hover_icon_color').val();

                  var shape = jQuery('#TB_window #shape option:selected').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var bg_color = '';
                  var hover_bg_color = '';
                  var border_color = '';
                  var hover_border_color = '';

                  if(style == 'custom') {
                    bg_color = jQuery('#TB_window #bg_color').val();
                    hover_bg_color = jQuery('#TB_window #hover_bg_color').val();
                    border_color = jQuery('#TB_window #border_color').val();
                    hover_border_color = jQuery('#TB_window #hover_border_color').val();
                  }

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[social_icon icon="' + icon + '"';

                  if(href !== '')
                    shortcode += ' href="' + href + '"';
                  else
                    shortcode += ' href="#"';
                  if(target)
                    shortcode += ' target="true"';

                  if(size !== '')
                    shortcode += ' size="' + size + '"';
                  if(size === 'custom')
                    shortcode += ' custom_size="' + custom_size + '"';

                  if(icon_color !== '')
                    shortcode += ' icon_color="' + icon_color + '"';
                  if(hover_icon_color !== '')
                    shortcode += ' hover_icon_color="' + hover_icon_color + '"';

                  shortcode += ' shape="' + shape + '" style="' + style + '"';
                  if(style == 'custom') {
                    if(bg_color !== '')
                      shortcode += ' bg_color="' + bg_color + '"';
                    if(hover_bg_color !== '')
                      shortcode += ' hover_bg_color="' + hover_bg_color + '"';
                    if(border_color !== '')
                      shortcode += ' border_color="' + border_color + '"';
                    if(hover_border_color !== '')
                      shortcode += ' hover_border_color="' + hover_border_color + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.icon_list,
            onclick: function() {
              // editor.insertContent( '[icon_list] Insert list item here ... [/icon_list]');
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_icon_list.php", function(data){

                initShortcodeTB(noo_shortcodes_str.icon_list, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var item_count = parseInt(jQuery('#TB_window #item_count').val(), 10);
                  var active_tab = parseInt(jQuery('#TB_window #active_tab').val(), 10);

                  item_count = isNaN(item_count) ? 2 : item_count;
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  tb_remove();

                  var shortcode = '[icon_list';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  shortcode += '[icon_list_item icon="fa" icon_size="" icon_color=""] Add your content here... [/icon_list_item]';
                  for (var i = 2; i <= item_count; i++) {
                    shortcode += '[icon_list_item icon="fa" icon_size="" icon_color=""] ... [/icon_list_item]';
                  }

                  shortcode += '[/icon_list]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }

          },

          {
            text: noo_shortcodes_str.icon_list_item,
            onclick: function() {

              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_icon_list_item.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.icon_list_item, data);

                var icontext_editor_id = jQuery('#TB_window #icontext_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[icontext_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[icontext_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var icon = jQuery('#TB_window #icon').val();
                  if(icon === '') {
                    tb_remove();
                    return;
                  }
                  var icon_size = jQuery('#TB_window #icon_size').val();
                  var icon_color = jQuery('#TB_window #icon_color').val();
                  var text_same_size = jQuery('#TB_window #text_same_size').is(':checked');
                  var text_same_color = jQuery('#TB_window #text_same_color').is(':checked');
                   var text_size = '';
                  var text_color = '';

                  if( text_same_size )
                    text_size = jQuery('#TB_window #text_size').val();

                  if( text_same_color )
                    text_color = jQuery('#TB_window #text_color').val();

                  var icontext = tinyMCE.activeEditor.getContent();

                  tb_remove();

                  var shortcode = '[icon_list_item icon="' + icon + '"';
                  if(icon_size !== '')
                    shortcode += ' icon_size="' + icon_size + '"';
                  if(icon_color !== '')
                    shortcode += ' icon_color="' + icon_color + '"';

                  if(!text_same_size && text_size !== '')
                    shortcode += ' text_size="' + text_size + '"';
                  if(!text_same_color && text_color !== '')
                    shortcode += ' text_color="' + text_color + '"';

                  shortcode += '] ' + icontext + ' [/icon_list_item]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.label,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_label.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.label, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var word = jQuery('#TB_window #word').val();
                  word = (word === '') ? ' ... ' : word;
                  var color = jQuery('#TB_window #color option:selected').val();
                  var custom_color = jQuery('#TB_window #custom_color').val();
                  var rounded = jQuery('#TB_window #rounded').is(':checked');

                  tb_remove();

                  var shortcode = '[label word="' + word + '" color="' + color + '"';

                  if(color !== '')
                    shortcode += ' color="' + color + '"';

                  if(color === 'custom' && custom_color !== '')
                    shortcode += ' custom_color="' + custom_color + '"';

                  if(rounded)
                    shortcode += ' rounded="true"';
                  else
                    shortcode += ' rounded="false"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },


          {
            text: noo_shortcodes_str.code_block,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_code.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.code_block, data);

                var code_editor_id = jQuery('#TB_window #code_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[code_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[code_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var code = tinyMCE.activeEditor.getContent();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[code';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + code + '[/code]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          }
        ]
      },

      {
        text: noo_shortcodes_str.content,
        menu: [
          {
            text: noo_shortcodes_str.accordion,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_accordion.php", function(data){

                initShortcodeTB(noo_shortcodes_str.accordion, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var tab_count = parseInt(jQuery('#TB_window #tab_count').val(), 10);
                  var active_tab = parseInt(jQuery('#TB_window #active_tab').val(), 10);

                  tab_count = isNaN(tab_count) ? 2 : tab_count;
                  active_tab = isNaN(active_tab) ? 1 : active_tab;

                  var icon_style = jQuery('#TB_window #icon_style option:selected').val();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_accordion';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(active_tab !== '')
                    shortcode += ' active_tab="' + active_tab + '"';
                  if(icon_style !== '')
                    shortcode += ' icon_style="' + icon_style + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  shortcode += '[vc_accordion_tab title="Accordion Item 1"] Add your content here... [/vc_accordion_tab]';
                  for (var i = 2; i <= tab_count; i++) {
                    shortcode += '[vc_accordion_tab title="Accordion Item "' + i + '"] ... [/vc_accordion_tab]';
                  }

                  shortcode += '[/vc_accordion]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.tabs,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_tabs.php", function(data){

                initShortcodeTB(noo_shortcodes_str.tabs, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var tab_count = parseInt(jQuery('#TB_window #tab_count').val(), 10);
                  var active_tab = parseInt(jQuery('#TB_window #active_tab').val(), 10);

                  tab_count = isNaN(tab_count) ? 2 : tab_count;
                  active_tab = isNaN(active_tab) ? 1 : active_tab;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_tabs';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(active_tab !== '')
                    shortcode += ' active_tab="' + active_tab + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  var tab_id = (+new Date() + '-' + tab_count + '-' + Math.floor(Math.random() * 11));
                  shortcode += '[vc_tab title="Tab Item 1" tab_id="' + tab_id + '" icon="fa"] Add your content here... [/vc_tab]';
                  for (var i = 2; i <= tab_count; i++) {
                    tab_id = (+new Date() + '-' + tab_count + '-' + Math.floor(Math.random() * 11));
                    shortcode += '[vc_tab title="Tab Item ' + i + '" tab_id="' + tab_id + '" icon="fa"] ... [/vc_tab]';
                  }

                  shortcode += '[/vc_tabs]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.tour_section,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_tabs.php", function(data){

                initShortcodeTB(noo_shortcodes_str.tour_section, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var tab_count = parseInt(jQuery('#TB_window #tab_count').val(), 10);
                  var active_tab = parseInt(jQuery('#TB_window #active_tab').val(), 10);

                  tab_count = isNaN(tab_count) ? 2 : tab_count;
                  active_tab = isNaN(active_tab) ? 1 : active_tab;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_tour';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(active_tab !== '')
                    shortcode += ' active_tab="' + active_tab + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  var tab_id = (+new Date() + '-' + tab_count + '-' + Math.floor(Math.random() * 11));
                  shortcode += '[vc_tab title="Tab Item 1" tab_id="' + tab_id + '" icon="fa"] Add your content here... [/vc_tab]';
                  for (var i = 2; i <= tab_count; i++) {
                    tab_id = (+new Date() + '-' + tab_count + '-' + Math.floor(Math.random() * 11));
                    shortcode += '[vc_tab title="Tab Item ' + i + '" tab_id="' + tab_id + '" icon="fa"] ... [/vc_tab]';
                  }

                  shortcode += '[/vc_tour]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.block_grid,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_block_grid.php", function(data){

                initShortcodeTB(noo_shortcodes_str.block_grid, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var columns = parseInt(jQuery('#TB_window #columns').val(), 10);
                  var item_count = parseInt(jQuery('#TB_window #item_count').val(), 10);

                  columns = isNaN(columns) ? 3 : columns;
                  item_count = isNaN(item_count) ? 2 : item_count;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[block_grid';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(columns !== '')
                    shortcode += ' columns="' + columns + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  shortcode += '[block_grid_item title="Grid Item 1"] Add your content here... [/block_grid_item]';
                  for (var i = 2; i <= item_count; i++) {
                    shortcode += '[block_grid_item title="Grid Item ' + i + '"] ... [/block_grid_item]';
                  }

                  shortcode += '[/block_grid]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.progress_bar,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_progress_bar.php", function(data){

                initShortcodeTB(noo_shortcodes_str.progress_bar, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  // var units = jQuery('#TB_window #units').val();
                  // units = (units === '') ? '%' : units;

                  var style = jQuery('#TB_window #style option:selected').val();
                  var rounded = jQuery('#TB_window #rounded').is(':checked');

                  var bar_count = parseInt(jQuery('#TB_window #bar_count').val(), 10);
                  bar_count = isNaN(bar_count) ? 2 : bar_count;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[progress_bar';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';

                  if(style !== '')
                    shortcode += ' style="' + style + '"';

                  if(rounded)
                    shortcode += ' rounded="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';
                  var bar_item_title = '';
                  var bar_item_progress = 0;
                  var bar_item_color = '';
                  var bar_item_color_effect = '';
                  for (var i = 1; i <= bar_count; i++) {
                    bar_item_title = jQuery('#TB_window #bar_title_' + i ).val();
                    bar_item_progress = jQuery('#TB_window #bar_progress_' + i ).val();
                    bar_item_color = jQuery('#TB_window #bar_color_' + i ).val();
                    bar_item_color_effect = jQuery('#TB_window #color_effect_' + i ).val();
                    shortcode += '[progress_bar_item progress="' + bar_item_progress + '"';
                    if(bar_item_title !== '')
                      shortcode += ' title="' + bar_item_title + '"';
                    if(bar_item_color !== '')
                      shortcode += ' color="' + bar_item_color + '"';
                    if(bar_item_color_effect !== '')
                      shortcode += ' color_effect="' + bar_item_color_effect + '"';
                    shortcode += ']';
                  }

                  shortcode += '[/progress_bar]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.pricing_table,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_pricing_table.php", function(data){

                initShortcodeTB(noo_shortcodes_str.pricing_table, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var columns = parseInt(jQuery('#TB_window #columns').val(), 10);
                  columns = isNaN(columns) ? 3 : columns;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[pricing_table';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(columns !== '')
                    shortcode += ' columns="' + columns + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += '] Add Pricing Table columns here... [/pricing_table]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.pricing_table_column,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_pricing_table_column.php", function(data){

                initShortcodeTB(noo_shortcodes_str.pricing_table, data);

                var pricing_item_editor_id = jQuery('#TB_window #pricing_item_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[pricing_item_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[pricing_item_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var pricing_item = tinyMCE.activeEditor.getContent();
                  var title = jQuery('#TB_window #title').val();
                  var featured = jQuery('#TB_window #featured').is(':checked');
                  var price = jQuery('#TB_window #price').val();
                  var symbol = jQuery('#TB_window #symbol').val();
                  var before_price = jQuery('#TB_window #before_price').val();
                  var after_price = jQuery('#TB_window #after_price').val();
                  var button_text = jQuery('#TB_window #button_text').val();
                  var href = jQuery('#TB_window #href').val();
                  var target = jQuery('#TB_window #target').is(":checked");
                  var size = jQuery('#TB_window #size option:selected').val();
                  var button_shape = jQuery('#TB_window #button_shape option:selected').val();
                  var button_style = jQuery('#TB_window #button_style option:selected').val();
                  var skin = jQuery('#TB_window #skin option:selected').val();
                  var text_color = '';
                  var hover_text_color = '';
                  var bg_color = '';
                  var hover_bg_color = '';
                  var border_color = '';
                  var hover_border_color = '';
                  if(skin == 'custom') {
                    text_color = jQuery('#TB_window #text_color').val();
                    hover_text_color = jQuery('#TB_window #hover_text_color').val();
                    bg_color = jQuery('#TB_window #bg_color').val();
                    hover_bg_color = jQuery('#TB_window #hover_bg_color').val();
                    border_color = jQuery('#TB_window #border_color').val();
                    hover_border_color = jQuery('#TB_window #hover_border_color').val();
                  }                  

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[pricing_table_column';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';

                  if(featured)
                    shortcode += ' featured="true"';

                  if(price !== '')
                    shortcode += ' price="' + price + '"';

                  symbol = (symbol === '') ? '$' : symbol;
                  shortcode += ' symbol="' + symbol + '"';
                  if(before_price !== '')
                    shortcode += ' before_price="' + before_price + '"';
                  if(after_price !== '')
                    shortcode += ' after_price="' + after_price + '"';

                  if(button_text !== '')
                    shortcode += ' button_text="' + button_text + '"';
                  if(href !== '')
                    shortcode += ' href="' + href + '"';
                  if(target)
                    shortcode += ' target="true"';

                  shortcode += ' size="' + size + '"';
                  if(button_shape !== '') {
                    shortcode += ' button_shape="' + button_shape + '"';
                  }
                  if(button_style !== '') {
                    shortcode += ' button_style="' + button_style + '"';
                  }
                  if(skin !== '') {
                    shortcode += ' skin="' + skin + '"';
                  }
                  if(skin == 'custom') {
                    if(text_color !== '')
                      shortcode += ' text_color="' + text_color + '"';
                    if(hover_text_color !== '')
                      shortcode += ' hover_text_color="' + hover_text_color + '"';
                    if(bg_color !== '')
                      shortcode += ' bg_color="' + bg_color + '"';
                    if(hover_bg_color !== '')
                      shortcode += ' hover_bg_color="' + hover_bg_color + '"';
                    if(border_color !== '')
                      shortcode += ' border_color="' + border_color + '"';
                    if(hover_border_color !== '')
                      shortcode += ' hover_border_color="' + hover_border_color + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + pricing_item + '[/pricing_table_column]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.pie,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_pie.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.pie, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var title = jQuery('#TB_window #title').val();
                  var value = jQuery('#TB_window #value').val();
                  var label_value = jQuery('#TB_window #label_value').val();
                  var units = jQuery('#TB_window #units').val();

                  var style = jQuery('#TB_window #style option:selected').val();
                  var color = jQuery('#TB_window #color').val();
                  var width = jQuery('#TB_window #width').val();
                  var value_color = jQuery('#TB_window #value_color').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_pie';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(value !== '')
                    shortcode += ' value="' + value + '"';
                  if(label_value !== '')
                    shortcode += ' label_value="' + label_value + '"';
                  if(units !== '')
                    shortcode += ' units="' + units + '"';
                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(color !== '')
                    shortcode += ' color="' + color + '"';
                  if(width !== '')
                    shortcode += ' width="' + width + '"';
                  if(value_color !== '')
                    shortcode += ' value_color="' + value_color + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.cta_button,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_cta_button.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.cta_button, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var title = jQuery('#TB_window #title').val();
                  var message = jQuery('#TB_window #message').val();
                  var button_text = jQuery('#TB_window #button_text').val();
                  var href = jQuery('#TB_window #href').val();
                  var target = jQuery('#TB_window #target').is(":checked");
                  var size = jQuery('#TB_window #size option:selected').val();
                  var fullwidth = jQuery('#TB_window #fullwidth').is(":checked");
                  var vertical_padding = jQuery('#TB_window #vertical_padding').val();
                  var horizontal_padding = jQuery('#TB_window #horizontal_padding').val();
                  var icon = jQuery('#TB_window #icon').val();
                  var icon_right = jQuery('#TB_window #icon_right').is(":checked");
                  var icon_only = jQuery('#TB_window #icon_only').is(":checked");
                  var icon_color = jQuery('#TB_window #icon_color').val();
                  var shape = jQuery('#TB_window #shape option:selected').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var skin = jQuery('#TB_window #skin option:selected').val();
                  var text_color = '';
                  var hover_text_color = '';
                  var bg_color = '';
                  var hover_bg_color = '';
                  var border_color = '';
                  var hover_border_color = '';
                  if(skin == 'custom') {
                    text_color = jQuery('#TB_window #text_color').val();
                    hover_text_color = jQuery('#TB_window #hover_text_color').val();
                    bg_color = jQuery('#TB_window #bg_color').val();
                    hover_bg_color = jQuery('#TB_window #hover_bg_color').val();
                    border_color = jQuery('#TB_window #border_color').val();
                    hover_border_color = jQuery('#TB_window #hover_border_color').val();
                  }

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_cta_button';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(message !== '')
                    shortcode += ' message="' + message + '"';
                  if(button_text !== '')
                    shortcode += ' button_text="' + button_text + '"';
                  if(href !== '')
                    shortcode += ' href="' + href + '"';
                  if(target)
                    shortcode += ' target="true"';

                  shortcode += ' size="' + size + '"';
                  if(fullwidth)
                    shortcode += ' fullwidth="true"';
                  if( size === 'custom' ) {
                    if(vertical_padding !== '')
                      shortcode += ' vertical_padding="' + vertical_padding + '"';
                    if(horizontal_padding !== '')
                      shortcode += ' horizontal_padding="' + horizontal_padding + '"';
                  }

                  if(icon !== '') {
                    shortcode += ' icon="' + icon + '"';

                    if(icon_color !== '')
                      shortcode += ' icon_color="' + icon_color + '"';
                    if(icon_right)
                      shortcode += ' icon_right="true"';
                    if(icon_only)
                      shortcode += ' icon_only="true"';
                  }

                  if(shape !== '') {
                    shortcode += ' shape="' + shape + '"';
                  }
                  if(style !== '') {
                    shortcode += ' style="' + style + '"';
                  }
                  if(skin !== '') {
                    shortcode += ' skin="' + skin + '"';
                  }
                  if(skin == 'custom') {
                    if(text_color !== '')
                      shortcode += ' text_color="' + text_color + '"';
                    if(hover_text_color !== '')
                      shortcode += ' hover_text_color="' + hover_text_color + '"';
                    if(bg_color !== '')
                      shortcode += ' bg_color="' + bg_color + '"';
                    if(hover_bg_color !== '')
                      shortcode += ' hover_bg_color="' + hover_bg_color + '"';
                    if(border_color !== '')
                      shortcode += ' border_color="' + border_color + '"';
                    if(hover_border_color !== '')
                      shortcode += ' hover_border_color="' + hover_border_color + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.counter,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_counter.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.counter, data);

                var text_editor_id = jQuery('#TB_window #text_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[text_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[text_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  // get variables
                  var number = jQuery('#TB_window #number').val();
                  var size = jQuery('#TB_window #size').val();
                  var color = jQuery('#TB_window #color').val();
                  var alignment = jQuery('#TB_window #alignment option:selected').val();
                  var text = tinyMCE.activeEditor.getContent();
                  var text_size = jQuery('#TB_window #text_size').val();
                  var text_color = jQuery('#TB_window #text_color').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[counter number="' + number + '" size="' + size + '"';
                  if( color !== '' )
                    shortcode += ' color="' + color + '"';
                  shortcode += ' alignment="' + alignment + '"';

                  if( visibility !== '' )
                    shortcode += ' visibility="' + visibility + '"';
                  if( el_class !== '' )
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + text + '[/counter]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.message,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_message.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.message, data);

                var message_editor_id = jQuery('#TB_window #message_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[message_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[message_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var message = tinyMCE.activeEditor.getContent();
                  var type = jQuery('#TB_window #type option:selected').val();
                  var dismissible = jQuery('#TB_window #dismissible').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_message title="' + title + '" type="' + type + '" dismissible="' + dismissible + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']' + message + '[/vc_message]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          }
        ]
      },

      {
        text: noo_shortcodes_str.citilights,
        menu: [
          {
            text: noo_shortcodes_str.recent_properties,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_recent_properties.php", function(data){

                initShortcodeTB(noo_shortcodes_str.recent_properties, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var show_control = jQuery('#TB_window #show_control option:selected').val();
                  var show_pagination = jQuery('#TB_window #show_pagination option:selected').val();
                  var show = jQuery('#TB_window #show option:selected').val();
                  var number = parseInt(jQuery('#TB_window #number').val(), 10);

                  number = isNaN(number) ? 6 : number;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_recent_properties';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(show_control !== '')
                    shortcode += ' show_control="' + show_control + '"';
                  if(show_pagination !== '')
                    shortcode += ' show_pagination="' + show_pagination + '"';
                  if(show !== '')
                    shortcode += ' show="' + show + '"';
                  if(number !== '')
                    shortcode += ' number="' + number + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.advanced_search_property,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_advanced_search_property.php", function(data){

                initShortcodeTB(noo_shortcodes_str.advanced_search_property, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var disable_map = jQuery('#TB_window #disable_map').is(':checked');
                  var disable_search_form = jQuery('#TB_window #disable_search_form').is(':checked');
                  var advanced_search = jQuery('#TB_window #advanced_search').is(':checked');

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_advanced_search_property';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(disable_map)
                    shortcode += ' disable_map="true"';
                  if(disable_search_form)
                    shortcode += ' disable_search_form="true"';
                  if(advanced_search)
                    shortcode += ' advanced_search="true"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.recent_agents,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_recent_agents.php", function(data){

                initShortcodeTB(noo_shortcodes_str.recent_agents, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var number = parseInt(jQuery('#TB_window #number').val(), 10);

                  number = isNaN(number) ? 6 : number;

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_recent_agents';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(number !== '')
                    shortcode += ' number="' + number + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.membership_packages,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_membership_packages.php", function(data){

                initShortcodeTB(noo_shortcodes_str.membership_packages, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var style = jQuery('#TB_window #style  option:selected').val();
                  var featured_item = jQuery('#TB_window #featured_item').val();
                  var show_register_link = jQuery('#TB_window #show_register_link').is(':checked');
                  var btn_text = jQuery('#TB_window #btn_text').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_membership_packages';

                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(featured_item !== '')
                    shortcode += ' featured_item="' + featured_item + '"';
                  if(btn_text !== '')
                    shortcode += ' btn_text="' + btn_text + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.login_register,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_login_register.php", function(data){

                initShortcodeTB(noo_shortcodes_str.login_register, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var mode = jQuery('#TB_window #mode  option:selected').val();
                  var login_text = jQuery('#TB_window #login_text').val();
                  var show_register_link = jQuery('#TB_window #show_register_link').is(':checked');
                  var register_text = jQuery('#TB_window #register_text').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_login_register';

                  if(mode !== '')
                    shortcode += ' mode="' + mode + '"';
                  if(mode == 'login' || mode == 'both') {
                    if(login_text !== '')
                      shortcode += ' login_text="' + login_text + '"';
                    if(show_register_link)
                      shortcode += ' show_register_link="true"';
                  }

                  if(mode == 'register' || mode == 'both') {
                    if(register_text !== '')
                      shortcode += ' register_text="' + register_text + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.property_slider,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_property_slider.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.property_slider, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var animation = jQuery('#TB_window #animation option:selected').val();
                  // var visible_items = jQuery('#TB_window #visible_items').val();
                  var slider_time = jQuery('#TB_window #slider_time').val();
                  var slider_speed = jQuery('#TB_window #slider_speed').val();
                  var auto_play = jQuery('#TB_window #auto_play').is(':checked');
                  var indicator = jQuery('#TB_window #indicator').is(':checked');
                  var prev_next_control = jQuery('#TB_window #prev_next_control').is(':checked');
                  var show_search_form = jQuery('#TB_window #show_search_form').is(':checked');
                  var show_search_info = show_search_form ? jQuery('#TB_window #show_search_info').is(':checked') : false;
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[property_slider';

                  if(animation !== '')
                    shortcode += ' animation="' + animation + '"';

                  // if(visible_items !== '')
                  //   shortcode += ' visible_items="' + visible_items + '"';

                  if(slider_time !== '')
                    shortcode += ' slider_time="' + slider_time + '"';

                  if(slider_speed !== '')
                    shortcode += ' slider_speed="' + slider_speed + '"';

                  if(auto_play)
                    shortcode += ' auto_play="true"';
                  if(indicator) {
                    shortcode += ' indicator="true"';
                  }
                  if(prev_next_control)
                    shortcode += ' prev_next_control="true"';
                  if(show_search_form)
                    shortcode += ' show_search_form="true"';
                  if(show_search_info)
                    shortcode += ' show_search_info="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += '] Input Property Slide here ... [/property_slider]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.property_slide,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_property_slide.php", function(data){

                initShortcodeTB(noo_shortcodes_str.login_register, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var background_type = jQuery('#TB_window #background_type  option:selected').val();
                  var image = jQuery('#TB_window #image').val();
                  var show_register_link = jQuery('#TB_window #show_register_link').is(':checked');
                  var property_id = jQuery('#TB_window #property_id option:selected').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[property_slide';

                  if(background_type !== '')
                    shortcode += ' background_type="' + background_type + '"';
                  if(background_type == 'image') {
                    if(image !== '')
                      shortcode += ' image="' + image + '"';
                  }
                  if(property_id)
                    shortcode += ' property_id="' + property_id + '"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },
          {
            text: noo_shortcodes_str.single_property,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_single_property.php", function(data){

                initShortcodeTB(noo_shortcodes_str.single_property, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var property_id = jQuery('#TB_window #property_id').val();
                  var style = jQuery('#TB_window #style option:selected').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[noo_single_property';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(property_id !== '')
                    shortcode += ' property_id="' + property_id + '"';
                  if(style !== '')
                    shortcode += ' style="' + style + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          }
        ]
      },

      {
        text: noo_shortcodes_str.wordpress_content,
        menu: [
          {
            text: noo_shortcodes_str.widget_area,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_widget_area.php", function(data){

                initShortcodeTB(noo_shortcodes_str.widget_area, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var sidebar_id = jQuery('#TB_window #sidebar_id option:selected').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_widget_sidebar sidebar_id="' + sidebar_id + '"';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.blog,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_blog.php", function(data){

                initShortcodeTB(noo_shortcodes_str.blog, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var layout = jQuery('#TB_window #layout option:selected').val();
                  var columns = jQuery('#TB_window #columns').val();
                  var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
                  var filter = jQuery('#TB_window #filter').is(':checked');
                  var orderby = jQuery('#TB_window #orderby option:selected').val();
                  var post_count = jQuery('#TB_window #post_count').val();
                  var hide_featured = jQuery('#TB_window #hide_featured').is(':checked');
                  var hide_author = jQuery('#TB_window #hide_author').is(':checked');
                  var hide_date = jQuery('#TB_window #hide_date').is(':checked');
                  var hide_category = jQuery('#TB_window #hide_category').is(':checked');
                  var hide_comment = jQuery('#TB_window #hide_comment').is(':checked');
                  var hide_readmore = jQuery('#TB_window #hide_readmore').is(':checked');
                  var excerpt_length = jQuery('#TB_window #excerpt_length').val();
                  var title = jQuery('#TB_window #title').val();
                  var sub_title = jQuery('#TB_window #sub_title').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[blog categories="' + categories + '" orderby="' + orderby + '"';
                  shortcode += ' layout="' + layout + '"';
                  if ( layout === 'masonry' ) {
                    if( columns !== '' )
                      shortcode += ' columns="' + columns + '"';
                    else
                      shortcode += ' columns="3"';

                    if( filter )
                      shortcode += ' filter="true"';
                  }

                  if(post_count !== '' ) {
                    shortcode += ' post_count="' + post_count + '"';
                  } else {
                    shortcode += ' post_count="4"';
                  }

                  if( hide_featured )
                    shortcode += ' hide_featured="true"';
                  if( hide_author )
                    shortcode += ' hide_author="true"';
                  if( hide_date )
                    shortcode += ' hide_date="true"';
                  if( hide_category )
                    shortcode += ' hide_category="true"';
                  if( hide_comment )
                    shortcode += ' hide_comment="true"';
                  if( hide_readmore )
                    shortcode += ' hide_readmore="true"';

                  if(excerpt_length !== '')
                    shortcode += ' excerpt_length="' + excerpt_length + '"';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';
                  if(sub_title !== '')
                    shortcode += ' sub_title="' + sub_title + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          // {
          //   text: noo_shortcodes_str.recent_posts,
          //   onclick: function() {
          //     jQuery("#noo-shortcodes-form-wrapper").remove();

          //     jQuery.get(url + "/noo_shortcodes_recent_post.php", function(data){

          //       initShortcodeTB(noo_shortcodes_str.recent_post, data);
                
          //       jQuery('#TB_window #noo-save-shortcodes').click(function(){
          //         var categories = jQuery('#TB_window #categories option:selected').map(function(){ return this.value; }).get().join(",");
          //         var post_count = jQuery('#TB_window #post_count').val();
          //         var disable_featured_image = jQuery('#TB_window #disable_featured_image').is(':checked');
          //         var orientation = jQuery('#TB_window #orientation option:selected').val();

          //         var visibility = jQuery('#TB_window #visibility option:selected').val();
          //         var el_class = jQuery('#TB_window #class').val();
          //         tb_remove();

          //         var shortcode = '[recent_post categories="' + categories + '"';
          //         shortcode += ' post_count="' + post_count + '"';
          //         if( disable_featured_image )
          //           shortcode += ' disable_featured_image="true"';
                  
          //         shortcode += ' orientation="' + orientation + '"';
          //         if(visibility !== '')
          //           shortcode += ' visibility="' + visibility + '"';
          //         if(el_class !== '')
          //           shortcode += ' class="' + el_class + '"';

          //         shortcode += ']';

          //         editor.insertContent(shortcode);
          //         return false;
          //       });
          //     });

          //   }
          // },

          // {
          //   text: noo_shortcodes_str.author,
          //   onclick: function() {
          //     jQuery("#noo-shortcodes-form-wrapper").remove();

          //     jQuery.get(url + "/noo_shortcodes_author.php", function(data){

          //       initShortcodeTB(noo_shortcodes_str.author, data);
                
          //       jQuery('#TB_window #noo-save-shortcodes').click(function(){
          //         var author = jQuery('#TB_window #author option:selected').val();
          //         var custom_avatar = jQuery('#TB_window #custom_avatar').val();
          //         var role = jQuery('#TB_window #role').val();
          //         var description = jQuery('#TB_window #description').val();
          //         var facebook = jQuery('#TB_window #facebook').val();
          //         var twitter = jQuery('#TB_window #twitter').val();
          //         var googleplus = jQuery('#TB_window #googleplus').val();
          //         var linkedin = jQuery('#TB_window #linkedin').val();

          //         var visibility = jQuery('#TB_window #visibility option:selected').val();
          //         var el_class = jQuery('#TB_window #class').val();
          //         tb_remove();

          //         var shortcode = '[author author="' + author + '"';
          //         if (custom_avatar !== '') {
          //            shortcode += ' custom_avatar="' + custom_avatar + '"';
          //         }

          //         if (role !== '') {
          //            shortcode += ' role="' + role + '"';
          //         }

          //         if (description !== '') {
          //            shortcode += ' description="' + description + '"';
          //         }

          //         if (facebook !== '') {
          //            shortcode += ' facebook="' + facebook + '"';
          //         }

          //         if (twitter !== '') {
          //            shortcode += ' twitter="' + twitter + '"';
          //         }

          //         if (googleplus !== '') {
          //            shortcode += ' googleplus="' + googleplus + '"';
          //         }

          //         if (linkedin !== '') {
          //            shortcode += ' linkedin="' + linkedin + '"';
          //         }

          //         if(visibility !== '')
          //           shortcode += ' visibility="' + visibility + '"';
          //         if(el_class !== '')
          //           shortcode += ' class="' + el_class + '"';

          //         shortcode += ']';

          //         editor.insertContent(shortcode);
          //         return false;
          //       });
          //     });
          //   }
          // },

          {
            text: noo_shortcodes_str.team_member,
            onclick: function() {
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_team_member.php", function(data){

                initShortcodeTB(noo_shortcodes_str.team_member, data);
                
                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var name = jQuery('#TB_window #name option:selected').val();
                  var avatar = jQuery('#TB_window #avatar').val();
                  var role = jQuery('#TB_window #role').val();
                  var description = jQuery('#TB_window #description').val();
                  var facebook = jQuery('#TB_window #facebook').val();
                  var twitter = jQuery('#TB_window #twitter').val();
                  var googleplus = jQuery('#TB_window #googleplus').val();
                  var linkedin = jQuery('#TB_window #linkedin').val();

                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[team_member name="' + name + '"';
                  if (avatar !== '') {
                     shortcode += ' avatar="' + avatar + '"';
                  }

                  if (role !== '') {
                     shortcode += ' role="' + role + '"';
                  }

                  if (description !== '') {
                     shortcode += ' description="' + description + '"';
                  }

                  if (facebook !== '') {
                     shortcode += ' facebook="' + facebook + '"';
                  }

                  if (twitter !== '') {
                     shortcode += ' twitter="' + twitter + '"';
                  }

                  if (googleplus !== '') {
                     shortcode += ' googleplus="' + googleplus + '"';
                  }

                  if (linkedin !== '') {
                     shortcode += ' linkedin="' + linkedin + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

        ]
      },

      {
        text: noo_shortcodes_str.media,
        menu: [
          {
            text: noo_shortcodes_str.image,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_image.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.image, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var image = jQuery('#TB_window #image').val();
                  var alt = jQuery('#TB_window #alt').val();
                  var style = jQuery('#TB_window #style option:selected').val();
                  var href = jQuery('#TB_window #href').val();
                  var target = jQuery('#TB_window #target').is(':checked');
                  var link_title = jQuery('#TB_window #link_title').val();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_single_image';

                  if(image !== '')
                    shortcode += ' image="' + image + '"';

                  if(alt !== '')
                    shortcode += ' alt="' + alt + '"';

                  if(style !== '')
                    shortcode += ' style="' + style + '"';

                  if( href !== '' ) {
                    shortcode += ' href="' + href + '"';
                    if(target)
                      shortcode += ' target="true"';
                    if(link_title !== '')
                      shortcode += ' link_title="' + link_title + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.slider,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_slider.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.slider, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var animation = jQuery('#TB_window #animation option:selected').val();
                  // var visible_items = jQuery('#TB_window #visible_items').val();
                  var slider_time = jQuery('#TB_window #slider_time').val();
                  var slider_speed = jQuery('#TB_window #slider_speed').val();
                  var auto_play = jQuery('#TB_window #auto_play').is(':checked');
                  var pause_on_hover = jQuery('#TB_window #pause_on_hover').is(':checked');
                  var random = jQuery('#TB_window #random').is(':checked');
                  var indicator = jQuery('#TB_window #indicator').is(':checked');
                  var indicator_position = jQuery('#TB_window #indicator_position option:selected').val();
                  var prev_next_control = jQuery('#TB_window #prev_next_control').is(':checked');
                  var timer = jQuery('#TB_window #timer').is(':checked');
                  var swipe = jQuery('#TB_window #swipe').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[slider';

                  if(animation !== '')
                    shortcode += ' animation="' + animation + '"';

                  // if(visible_items !== '')
                  //   shortcode += ' visible_items="' + visible_items + '"';

                  if(slider_time !== '')
                    shortcode += ' slider_time="' + slider_time + '"';

                  if(slider_speed !== '')
                    shortcode += ' slider_speed="' + slider_speed + '"';

                  if(auto_play)
                    shortcode += ' auto_play="true"';
                  if(pause_on_hover)
                    shortcode += ' pause_on_hover="true"';
                  if(random)
                    shortcode += ' random="true"';
                  if(indicator) {
                    shortcode += ' indicator="true"';
                    shortcode += ' indicator_position="' + indicator_position + '"';
                  }
                  if(prev_next_control)
                    shortcode += ' prev_next_control="true"';
                  if(timer)
                    shortcode += ' timer="true"';
                  if(swipe)
                    shortcode += ' swipe="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += '] Input Slide here ... [/slider]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.slide,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_slide.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.slide, data);

                var content_editor_id = jQuery('#TB_window #content_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[content_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[content_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var type = jQuery('#TB_window #type option:selected').val();
                  var image = jQuery('#TB_window #image').val();
                  var caption = jQuery('#TB_window #caption').val();
                  // var video_url = jQuery('#TB_window #video_url').val();
                  // var video_poster = jQuery('#TB_window #video_poster').val();
                  var content = tinyMCE.activeEditor.getContent();
                  tb_remove();

                  var shortcode = '[slide';

                  // if( type == 'image' ) {
                    shortcode += ' type="image"';
                    if(image !== '')
                      shortcode += ' image="' + image + '"';

                    if(caption !== '')
                      shortcode += ' caption="' + caption + '"';
                  // } else if( type == 'video' ) {
                  //   shortcode += ' type="video"';
                  //   if(video_url !== '')
                  //     shortcode += ' video_url="' + video_url + '"';

                  //   if(video_poster !== '')
                  //     shortcode += ' video_poster="' + video_poster + '"';
                  // }

                  shortcode += ']';

                  if( type == 'content' ) {
                    shortcode += content;
                  }

                  shortcode += '[/slide]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.lightbox,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_lightbox.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.lightbox, data);

                var inline_editor_id = jQuery('#TB_window #inline_editor_id').val();
                tinyMCE.init(tinyMCEPreInit.mceInit[inline_editor_id]);
                try {
                  quicktags( tinyMCEPreInit.qtInit[inline_editor_id] );
                  QTags._buttonsInit();
                }
                catch(e){
                }

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var gallery_id = jQuery('#TB_window #gallery_id').val();
                  var type = jQuery('#TB_window #type option:selected').val();
                  var image = jQuery('#TB_window #image').val();
                  var image_title = jQuery('#TB_window #image_title').val();
                  var iframe_url = jQuery('#TB_window #iframe_url').val();
                  var inline = tinyMCE.activeEditor.getContent();
                  var thumbnail_type = jQuery('#TB_window #thumbnail_type option:selected').val();
                  var thumbnail_image = jQuery('#TB_window #thumbnail_image').val();
                  var thumbnail_style = jQuery('#TB_window #thumbnail_style option:selected').val();
                  var thumbnail_title = jQuery('#TB_window #thumbnail_title').val();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[lightbox';

                  shortcode += ' type="' + type + '"';
                  if( type == 'image' ) {
                    if(image !== '')
                      shortcode += ' image="' + image + '"';
                    if(image_title !== '')
                      shortcode += ' image_title="' + image_title + '"';
                  } else if (type == 'iframe') {
                    if(iframe_url !== '')
                      shortcode += ' iframe_url="' + iframe_url + '"';
                  }

                  shortcode += ' thumbnail_type="' + thumbnail_type + '"';
                  if( thumbnail_type == 'image' ) {
                    if(thumbnail_image !== '')
                      shortcode += ' thumbnail_image="' + thumbnail_image + '"';
                    shortcode += ' thumbnail_style="' + thumbnail_style + '"';
                  } else if (thumbnail_type == 'link') {
                    if(thumbnail_title !== '')
                      shortcode += ' thumbnail_title="' + thumbnail_title + '"';
                  }

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  if (type == 'inline') {
                    shortcode += inline;
                  }

                  shortcode += '[/lightbox]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.video_player,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_video_player.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.video_player, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var video_m4v = jQuery('#TB_window #video_m4v').val();
                  var video_ogv = jQuery('#TB_window #video_ogv').val();
                  var video_ratio = jQuery('#TB_window #video_ratio option:selected').val();
                  var video_poster = jQuery('#TB_window #video_poster').val();
                  var auto_play = jQuery('#TB_window #auto_play').is(':checked');
                  var hide_controls = jQuery('#TB_window #hide_controls').is(':checked');
                  var show_play_icon = jQuery('#TB_window #show_play_icon').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[video_player';

                  if(video_m4v !== '')
                    shortcode += ' video_m4v="' + video_m4v + '"';
                  if(video_ogv !== '')
                    shortcode += ' video_ogv="' + video_ogv + '"';

                  shortcode += ' video_ratio="' + video_ratio + '"';
                  if(video_poster !== '')
                    shortcode += ' video_poster="' + video_poster + '"';
                  if(auto_play)
                    shortcode += ' auto_play="true"';
                  if(hide_controls)
                    shortcode += ' hide_controls="true"';
                  if(show_play_icon)
                    shortcode += ' show_play_icon="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.video_embed,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_video_embed.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.video_embed, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var content = jQuery('#TB_window #content').val();
                  content = content.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(content)) : content;
                  var video_ratio = jQuery('#TB_window #video_ratio option:selected').val();
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[video_embed';

                  shortcode += ' video_ratio="' + video_ratio + '"';
                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  if(content !== '')
                    shortcode += content;

                  shortcode += '[/video_embed]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.audio_player,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_audio_player.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.audio_player, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var audio_mp3 = jQuery('#TB_window #audio_mp3').val();
                  var audio_oga = jQuery('#TB_window #audio_oga').val();
                  var auto_play = jQuery('#TB_window #auto_play').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[audio_player';

                  if(audio_mp3 !== '')
                    shortcode += ' audio_mp3="' + audio_mp3 + '"';
                  if(audio_oga !== '')
                    shortcode += ' audio_oga="' + audio_oga + '"';

                  if(auto_play)
                    shortcode += ' auto_play="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.audio_embed,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_audio_embed.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.audio_embed, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var content = jQuery('#TB_window #content').val();
                  content = content.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(content)) : content;
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[audio_embed';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  if(content !== '')
                    shortcode += content;

                  shortcode += '[/audio_embed]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.google_map,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_google_map.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.google_map, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var content = jQuery('#TB_window #content').val();
                  content = content.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(content)) : content;
                  var size = jQuery('#TB_window #size').val();
                  var disable_zooming = jQuery('#TB_window #disable_zooming').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[vc_gmaps';

                  if(size !== '')
                    shortcode += ' size="' + size + '"';

                  if(disable_zooming)
                    shortcode += ' disable_zooming="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  if(content !== '')
                    shortcode += content;

                  shortcode += '[/vc_gmaps]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.social_share,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_social_share.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.social_share, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var title = jQuery('#TB_window #title').val();
                  var facebook = jQuery('#TB_window #facebook').is(':checked');
                  var twitter = jQuery('#TB_window #twitter').is(':checked');
                  var googleplus = jQuery('#TB_window #googleplus').is(':checked');
                  var pinterest = jQuery('#TB_window #pinterest').is(':checked');
                  var linkedin = jQuery('#TB_window #linkedin').is(':checked');
                  var visibility = jQuery('#TB_window #visibility option:selected').val();
                  var el_class = jQuery('#TB_window #class').val();
                  tb_remove();

                  var shortcode = '[social_share';

                  if(title !== '')
                    shortcode += ' title="' + title + '"';

                  if(facebook)
                    shortcode += ' facebook="true"';
                  if(twitter)
                    shortcode += ' twitter="true"';
                  if(googleplus)
                    shortcode += ' googleplus="true"';
                  if(pinterest)
                    shortcode += ' pinterest="true"';
                  if(linkedin)
                    shortcode += ' linkedin="true"';

                  if(visibility !== '')
                    shortcode += ' visibility="' + visibility + '"';
                  if(el_class !== '')
                    shortcode += ' class="' + el_class + '"';

                  shortcode += ']';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          }

        ]
      },

      {
        text: noo_shortcodes_str.custom,
        menu: [
          {
            text: noo_shortcodes_str.raw_html,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_raw_html.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.raw_html, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var content = jQuery('#TB_window #content').val();
                  content = content.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(content)) : content;
                  tb_remove();

                  var shortcode = '[vc_raw_html';
                  shortcode += ']';

                  if(content !== '')
                    shortcode += content;

                  shortcode += '[/vc_raw_html]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });
            }
          },

          {
            text: noo_shortcodes_str.raw_js,
            onclick: function() {
              jQuery("#idIconsDialog").remove();
              jQuery("#noo-shortcodes-form-wrapper").remove();

              jQuery.get(url + "/noo_shortcodes_raw_js.php", function(data){
                
                initShortcodeTB(noo_shortcodes_str.raw_js, data);

                jQuery('#TB_window #noo-save-shortcodes').click(function(){
                  var content = jQuery('#TB_window #content').val();
                  content = content.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(content)) : content;
                  tb_remove();

                  var shortcode = '[vc_raw_js';
                  shortcode += ']';

                  if(content !== '')
                    shortcode += content;

                  shortcode += '[/vc_raw_js]';

                  editor.insertContent(shortcode);
                  return false;
                });
              });              
            }
          }
        ]
      }
    ];

    if ( noo_shortcodes_data.contact_form_7 ) {
      menu[4].menu.push(
      {
        text: noo_shortcodes_str.contact_form_7,
        onclick: function() {
          jQuery("#noo-shortcodes-form-wrapper").remove();

          jQuery.get(url + "/noo_shortcodes_contact_form_7.php", function(data){

            initShortcodeTB(noo_shortcodes_str.contact_form_7, data);
            
            jQuery('#TB_window #noo-save-shortcodes').click(function(){
              var id = jQuery('#TB_window #id option:selected').val();
              var title = jQuery('#TB_window #title').val();

              tb_remove();

              var shortcode = '[contact-form-7 id="' + id + '"';

              shortcode += ']';

              editor.insertContent(shortcode);
              return false;
            });
          });          
        }
      });
    }

    if( noo_shortcodes_data.rev_slider ) {
      menu[5].menu.splice( 1, 0, 
      {
        text: noo_shortcodes_str.rev_slider,
        onclick: function() {
          jQuery("#noo-shortcodes-form-wrapper").remove();

          jQuery.get(url + "/noo_shortcodes_rev_slider.php", function(data){

            initShortcodeTB(noo_shortcodes_str.rev_slider, data);
            
            jQuery('#TB_window #noo-save-shortcodes').click(function(){
              var slider = jQuery('#TB_window #slider option:selected').val();
              var visibility = jQuery('#TB_window #visibility option:selected').val();
              var el_class = jQuery('#TB_window #class').val();

              tb_remove();

              var shortcode = '[noo_rev_slider slider="' + slider + '"';
              if(visibility !== '')
                shortcode += ' visibility="' + visibility + '"';
              if(el_class !== '')
                shortcode += ' class="' + el_class + '"';

              shortcode += ']';

              editor.insertContent(shortcode);
              return false;
            });
          }); 
        }
      } );
    }
    editor.addButton('noo_shortcodes_mce_button', {

      type  : 'menubutton',
      title : 'NOO Shortcodes',
      text  : '',
      image : url + '/noo20x20.png',
      style : 'background-image: url("' + url + '/noo20x20.png' + '"); background-repeat: no-repeat; background-position: 2px 2px;"',
      icon  : true,
      menu  : menu
      });

  });

})();
