<div class="tve-hyperlinks-lightbox-wrapper">
    <h3>Thrive Hyperlinks Settings</h3>

    <p><strong>Content to include in Search Results</strong></p>
    <div class="tve-lightbox-settings-wrapper">
        <input type="hidden" value="text_link_settings" name="tve_lb_type">
        <ul>
            <?php
            $all_post_types = get_post_types();

            $exceptionList = apply_filters('tve_post_types_blacklist', array('attachment', 'focus_area', 'thrive_optin', 'wysijap', 'revision', 'nav_menu_item', 'tve_lead_shortcode', 'tve_lead_1c_signup', 'tve_form_type', 'tve_lead_group', 'tcb_lightbox', 'tve_lead_2s_lightbox', 'tve_ult_campaign', 'tve_ult_schedule'));
            $post_types = array_diff($all_post_types, $exceptionList);
            $opt_settings = get_option('tve_hyperlink_settings', array());

            if($opt_settings) {
                $settings = unserialize($opt_settings);
                $opt_settings = unserialize($opt_settings);
            }

            if($opt_settings == NULL) {
                $settings = array();
            }

            $i = 0;
            
            foreach($post_types as $post_type) {
                $post_type_obj = get_post_type_object($post_type);
                $i++;
                ?>
                <li>
                    <input id="link-<?php echo $i; ?>" type="checkbox" class="tve_no_click tve_click filled-in" data-ctrl="controls.notice_on_count" data-count="3" data-show="tve-link-settings-notice" <?php if(in_array($post_type, $settings)) { echo 'checked="checked"'; } elseif (is_array($opt_settings) && empty($opt_settings) && ($post_type == "post" || $post_type == "page")) { echo 'checked="checked"'; } ?> name="<?php echo $post_type ?>" value="<?php echo $post_type ?>"/>
                    <label class="tve_no_click" for="link-<?php echo $i; ?>" >
                        <?php echo $post_type_obj->labels->menu_name;?>
                    </label>
                </li>
            <?php } ?>
        <ul>
        <div class="tve-link-settings-notice" style="display: <?php echo count($settings) > 3 ? 'block' : 'none'; ?>;">
            <p><strong>Note:</strong> the more content types you add, the longer it will take to return search results.</p>
        </div>
    </div>
</div>



