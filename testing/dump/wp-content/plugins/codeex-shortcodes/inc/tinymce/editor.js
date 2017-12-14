(function($) {
"use strict";   

   //Shortcodes
   tinymce.PluginManager.add( 'typo_path', function( editor, url ) {
      
      editor.addButton( 'typo_shortcodes', {
         type: 'splitbutton',
         icon: false,
         title:  'Typo Shortcodes',
         onclick : function(e) {},
         menu: [
            {text: 'Alerts',onclick:function(){
               tb_show("Insert Alert Shortcode", url + "/modal.php?popup=alerts&width=900&height=100");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Blockquote',onclick:function(){
               tb_show("Insert Blockquote Shortcode", url + "/modal.php?popup=blockquotes");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Buttons',onclick:function(){
               tb_show("Insert Buttons Shortcode", url + "/modal.php?popup=buttons");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Callouts',onclick:function(){
               tb_show("Insert Callout Shortcode", url + "/modal.php?popup=callouts");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Columns',onclick:function(){
               tb_show("Insert Columns Shortcode", url + "/modal.php?popup=columns");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Dropcaps',onclick:function(){
               tb_show("Insert Dropcaps Shortcode", url + "/modal.php?popup=dropcaps");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Google Map',onclick:function(){
               tb_show("Insert Google Map Shortcode", url + "/modal.php?popup=gmap");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Images',onclick:function(){
               tb_show("Insert Image Shortcode", url + "/modal.php?popup=images");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'List Icons',onclick:function(){
               tb_show("Insert List Icons Shortcode", url + "/modal.php?popup=list-icons");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Skills Bar',onclick:function(){
               tb_show("Insert Skills Bar Shortcode", url + "/modal.php?popup=skills-bar");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Social Icons',onclick:function(){
               tb_show("Insert Social Icons Shortcode", url + "/modal.php?popup=social-icons");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Tabs',onclick:function(){
               tb_show("Insert Tab Shortcode", url + "/modal.php?popup=tabs");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Toggle',onclick:function(){
               tb_show("Insert Toggle Shortcode", url + "/modal.php?popup=toggles");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
            {text: 'Video',onclick:function(){
               tb_show("Insert Video Shortcode", url + "/modal.php?popup=videos");
               tinymce.DOM.setStyle(["TB_overlay", "TB_window", "TB_load"], "z-index", "999999");
            }},
         ]
      });

   });

})(jQuery);