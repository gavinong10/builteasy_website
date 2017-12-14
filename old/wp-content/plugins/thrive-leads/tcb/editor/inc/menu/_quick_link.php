
<li class="tve_btn_text<?php if (isset($btn_class)) echo ' ' . $btn_class;unset($btn_class) ?> tve_quick_link_input_holder" data-multiple-hide>

    <div class="tve_option_separator">
        <span class="tve-link-message" style="dysplay:none;"></span>
        <span class="tve_button_disabled tve_click tve_mousedown tve_lb_small tve_icm tve-ic-chain" data-ctrl-mousedown="controls.save_selection" data-ctrl="controls.quick_link.updateContentList"  data-key="linkSel" id="lb_text_link"
              title="<?php echo __("Create link", "thrive-cb") ?>"></span>
        <input  style="float:right" class="tve_button_disabled tve_text tve_keyup tve_right_click tve_mousedown tve_quick_link_input" data-ctrl="controls.quick_link.updateContentList" type="text" data-ctrl-mousedown="controls.save_selection" data-key="linkSel" placeholder="Search or type URL"/>
        <div class="tve_sub_btn">
            <div class="tve_sub active_sub_menu tve_large tve_clearfix" style="min-width:400px">
                <div class="tve-link-options-wrapper">
                    <ul>
                        <li class="tve_no_hover tve_click tve_no_click lb_quick_link_target"  data-ctrl-click="controls.quick_link.updateQuickLink">
                            <input type="checkbox" id="lb_quick_link_target" class="lb_quick_link_target"/>
                            <label for="lb_quick_link_target" class="tve_text">
                                <span class="tve_label_spacer tve_small"><?php echo __("Open in New Window", "thrive-cb"); ?></span>
                            </label>
                        </li>
                    </ul>
                    <ul>
                        <li class="tve_no_hover tve_click tve_no_click lb_quick_link_no_follow" data-ctrl-click="controls.quick_link.updateQuickLink">

                            <input type="checkbox" id="lb_quick_link_no_follow" class="tve_nofollow lb_quick_link_no_follow"  />
                            <label for="lb_quick_link_no_follow" class="tve_text">
                                <span class="tve_label_spacer tve_small"><?php echo __("No Follow Link", "thrive-cb"); ?></span>
                            </label>
                        </li>
                    </ul>
                    <ul>
                        <li class="tve_no_hover">
                            <label class="tve_text">
                                <input type="button" class="tve_click tve_button_disabled tve-link-insert" value="Insert" data-ctrl="controls.text_link"/>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="tve_clear"></div>
                <div class="quick_link_content_table" style="">
                    <div class="tve-no-results-sugested" style="display: none;">
                        <p><?php echo __("Sorry, no results were found for", "thrive-cb") ?> <br/>
        <span class="tve-result-text">
        </span>
                        </p>
                        <p class="tve-sugested-wrapper" style="display: none;">
                            <?php echo __("Did you mean:", "thrive-cb") ?> <br/>
                            <a class="tve-sugested-link tve_click tve_no_click" data-action="autocomplete" href="javascript:void(0)" data-ctrl="controls.change_search"></a><br/>
                            <a href="javascript:void(0)" class="tve_click tve_no_click tve-clear-search" data-ctrl="controls.change_search"><?php echo __("Clear search", "thrive-cb") ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

