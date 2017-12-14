(function($) {
    "use strict";
    
    /****************************
    COLOR PICKER
    ****************************/

    $('#homeland_global_color').wpColorPicker();
    $('#homeland_top_header_bg_color').wpColorPicker();
    $('#homeland_menu_bg_color').wpColorPicker();
    $('#homeland_menu_text_color').wpColorPicker();
    $('#homeland_menu_text_color_active').wpColorPicker();
    $('#homeland_header_text_color').wpColorPicker();
    $('#homeland_sidebar_text_color').wpColorPicker();
    $('#homeland_button_bg_color').wpColorPicker();
    $('#homeland_button_text_color').wpColorPicker();
    $('#homeland_button_bg_hover_color').wpColorPicker();
    $('#homeland_footer_bg_color').wpColorPicker();
    $('#homeland_footer_text_color').wpColorPicker();
    $('#homeland_slide_top_bg_color').wpColorPicker();
    $('#homeland_bg_color').wpColorPicker();


    /****************************
    ACCORDION
    ****************************/

    jQuery('.mabuc_options').slideUp();    
    jQuery('.mabuc_section h3').click(function(){       
        if(jQuery(this).parent().next('.mabuc_options').css('display')==='none') {   
            jQuery(this).removeClass('inactive');
            jQuery(this).addClass('active');       
        }else {   
            jQuery(this).removeClass('active');
            jQuery(this).addClass('inactive');    
        }            
        jQuery(this).parent().next('.mabuc_options').slideToggle('slow');   
    });
       

    /****************************
    TABS
    ****************************/ 

    $(document).ready(function(){
        $('ul.mabuc-tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.mabuc-tabs li').removeClass('current');
            $('.mabuc-tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })
    });


    /****************************
    UPLOADS
    ****************************/

    var formfield = '';
    var formfield_video = '';
    var formfield_audio = '';

    jQuery('#upload_image_button_homeland_logo').click(function() {
        formfield = jQuery('#homeland_logo').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;   
    });

    jQuery('#upload_image_button_homeland_logo_retina').click(function() {
        formfield = jQuery('#homeland_logo_retina').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;   
    });

    jQuery('#upload_image_button_homeland_favicon').click(function() {
        formfield = jQuery('#homeland_favicon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_preloader_icon').click(function() {
        formfield = jQuery('#homeland_preloader_icon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_bgimage').click(function() {
        formfield = jQuery('#homeland_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_video').click(function() {
        formfield_video = jQuery('#homeland_video').attr('name');
        tb_show('', 'media-upload.php?type=video&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_svideo').click(function() {
        formfield_video = jQuery('#homeland_video').attr('name');
        tb_show('', 'media-upload.php?type=video&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    }); 

    jQuery('#upload_image_button_homeland_custom_icon').click(function() {
        formfield_video = jQuery('#homeland_custom_icon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    }); 

    jQuery('#upload_image_button_homeland_map_pointer_icon').click(function() {
        formfield_video = jQuery('#homeland_map_pointer_icon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    }); 

     jQuery('#upload_image_button_homeland_map_pointer_clusterer_icon').click(function() {
        formfield_video = jQuery('#homeland_map_pointer_clusterer_icon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    }); 


    /*BACKGROUND IMAGES*/ 

    jQuery('#upload_image_button_homeland_default_bgimage').click(function() {
        formfield = jQuery('#homeland_default_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_archive_bgimage').click(function() {
        formfield = jQuery('#homeland_archive_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_search_bgimage').click(function() {
        formfield = jQuery('#homeland_search_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_notfound_bgimage').click(function() {
        formfield = jQuery('#homeland_notfound_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_agent_bgimage').click(function() {
        formfield = jQuery('#homeland_agent_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_taxonomy_bgimage').click(function() {
        formfield = jQuery('#homeland_taxonomy_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_welcome_bgimage').click(function() {
        formfield = jQuery('#homeland_welcome_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_services_bgimage').click(function() {
        formfield = jQuery('#homeland_services_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_testimonials_bgimage').click(function() {
        formfield = jQuery('#homeland_testimonials_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_bgimage').click(function() {
        formfield = jQuery('#homeland_forum_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_single_bgimage').click(function() {
        formfield = jQuery('#homeland_forum_single_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_single_topic_bgimage').click(function() {
        formfield = jQuery('#homeland_forum_single_topic_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_topic_edit_bgimage').click(function() {
        formfield = jQuery('#homeland_forum_topic_edit_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_search_bgimage').click(function() {
        formfield = jQuery('#homeland_forum_search_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_user_profile_bgimage').click(function() {
        formfield = jQuery('#homeland_user_profile_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_contact_alt_bgimage').click(function() {
        formfield = jQuery('#homeland_contact_alt_bgimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    
    /*HEADER IMAGES*/ 

    jQuery('#upload_image_button_homeland_hdimage').click(function() {
        formfield = jQuery('#homeland_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_default_hdimage').click(function() {
        formfield = jQuery('#homeland_default_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_archive_hdimage').click(function() {
        formfield = jQuery('#homeland_archive_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_search_hdimage').click(function() {
        formfield = jQuery('#homeland_search_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_notfound_hdimage').click(function() {
        formfield = jQuery('#homeland_notfound_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_agent_hdimage').click(function() {
        formfield = jQuery('#homeland_agent_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_taxonomy_hdimage').click(function() {
        formfield = jQuery('#homeland_taxonomy_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_avatar').click(function() {
        formfield = jQuery('#homeland_custom_avatar').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_hdimage').click(function() {
        formfield = jQuery('#homeland_forum_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_single_hdimage').click(function() {
        formfield = jQuery('#homeland_forum_single_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_single_topic_hdimage').click(function() {
        formfield = jQuery('#homeland_forum_single_topic_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_topic_edit_hdimage').click(function() {
        formfield = jQuery('#homeland_forum_topic_edit_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_forum_search_hdimage').click(function() {
        formfield = jQuery('#homeland_forum_search_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });

    jQuery('#upload_image_button_homeland_user_profile_hdimage').click(function() {
        formfield = jQuery('#homeland_user_profile_hdimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;width=640&amp;height=550&amp;TB_iframe=true');
        return false;           
    });
    

    
    /****************************
    SEND EDITOR
    ****************************/

    var original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html) {

        /*IMAGE :: VIDEO :: AUDIO*/

        if(formfield) {
            var fileurl = jQuery(html).attr('src');
            if (typeof(fileurl)==="undefined") {
                fileurl = jQuery('img',html).attr('src');
            }
            jQuery('#' + formfield).val(fileurl);
            tb_remove();
            formfield = '';
        }else if(formfield_video) {
            var fileurl_video = jQuery(html).attr('href');
            if (typeof(fileurl_video)==="undefined") {
                fileurl_video = jQuery('video',html).attr('src');
            }
            jQuery('#' + formfield_video).val(fileurl_video);
            tb_remove();
            formfield_video = '';
        }else if(formfield_audio) {
            var fileurl_audio = jQuery(html).attr('href');
            if (typeof(fileurl_audio)==="undefined") {
                fileurl_audio = jQuery('audio',html).attr('src');
            }
            jQuery('#' + formfield_audio).val(fileurl_audio);
            tb_remove();
            formfield_audio = '';
        }else {
            original_send_to_editor(html);
        }

    };


    /****************************
    BACKGROUND TYPE
    ****************************/
   
    $('.homeland_default_bgimage').css('display', 'none');
    $('.homeland_pattern').css('display', 'none');
    $('.homeland_bg_color').css('display', 'none');

    if ($('#homeland_bg_type').val() === 'Image') {
        $('.homeland_default_bgimage').css('display', 'block');
        $('.homeland_pattern').css('display', 'none');
        $('.homeland_bg_color').css('display', 'none');
    }else if ($('#homeland_bg_type').val() === 'Pattern') {
        $('.homeland_default_bgimage').css('display', 'none');
        $('.homeland_pattern').css('display', 'block');
        $('.homeland_bg_color').css('display', 'none'); 
    }else if ($('#homeland_bg_type').val() === 'Color') {
        $('.homeland_default_bgimage').css('display', 'none');
        $('.homeland_pattern').css('display', 'none');
        $('.homeland_bg_color').css('display', 'block');
    }

    $('#homeland_bg_type').change(function(){
        if ($(this).val() === 'Image') {
            $('.homeland_default_bgimage').css('display', 'block');
            $('.homeland_pattern').css('display', 'none');
            $('.homeland_bg_color').css('display', 'none');
        }else if ($(this).val() === 'Pattern') {
            $('.homeland_default_bgimage').css('display', 'none');
            $('.homeland_pattern').css('display', 'block');
               $('.homeland_bg_color').css('display', 'none'); 
        }else if ($(this).val() === 'Color') {
            $('.homeland_default_bgimage').css('display', 'none');
            $('.homeland_pattern').css('display', 'none');
            $('.homeland_bg_color').css('display', 'block');
        }
    });
    
})(jQuery);