<div class="tve-link-posts-content">
    <div class="tve-link-options">
        <ul>
            <li><a href="#tve-tabs-1" class="tve_no_click tve_click tve-active-tab" data-ctrl-click="controls.tabs_switch"><?php echo __("Content", "thrive-cb") ?></a></li>
            <li><a href="#tve-tabs-2" class="tve_no_click tve_click" data-ctrl-click="controls.tabs_switch"><?php echo __("Thrivebox", "thrive-cb") ?></a></li>
            <li><span id="lb_text_link_settings" class="tve_no_click tve_icm tve-ic-cog tve_click tve_lb_small tve_mousedown" data-ctrl-mousedown="controls.save_selection" data-ctrl-click="controls.lb_open"></span></li>
        </ul>
    </div>
    <div class="tve-link-results" style="" >
        <?php if ($postList) { ?>
            <?php foreach ( $postList as $item ) : ?>
                <?php if ( $item['type'] == 'tve_lead_2s_lightbox' || $item['type'] == 'tcb_lightbox' ) {
                    $lighbox[] = $item;
                } else {
                    $contentPostList[] = $item;
                } ?>
            <?php endforeach ?>
        <?php } ?>

        <?php if (isset($contentPostList)) { ?>
            <div id="tve-tabs-1" class="tve-content-results-list tve-tab tve-current-tab" style="width: 100%; <?php echo (count($postList) > 5 ? 'overflow-y: scroll; max-height: 222px;' : '') ?>">
                <ul>
                    <?php foreach ($contentPostList as $item) : ?>
                        <?php $post_type_obj = get_post_type_object($item['type']); ?>
                        <li class="tve_text tve_select_quick_link_post tve_click tve_no_click" data-ctrl-click="controls.quick_link.updateQuickLink" rel="<?php echo $item['url'] ?>"><span class="tve-link-result-title"><?php echo $item['label'] ?></span><span class="tve-link-result-type"><?php echo $post_type_obj->labels->name; ?></span></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php } else { ?>
            <div id="tve-tabs-1" class="tve-posts-not-found tve-tab tve-current-tab">
                <?php include dirname( __FILE__ ) . '/quick-links-no-results.php' ?>
            </div>
        <?php } ?>

        <?php if (isset($lighbox)) { ?>
            <div id="tve-tabs-2" class="tve-lightbox-results-list tve-tab" style="width: 100%; display:none; <?php echo (count($lighbox) > 5 ? 'overflow-y: scroll; max-height: 222px;' : '') ?>">
                <ul>
                    <?php foreach ($lighbox as $item) : ?>
                        <?php $post_type_obj = get_post_type_object($item['type']); ?>
                        <li class="tve_text tve-link-lightbox tve_select_quick_link_post tve_click tve_no_click" data-ctrl-click="controls.quick_link.updateQuickLink" rel="<?php echo $item['url'] ?>"><span class="tve-link-result-title"><?php echo $item['label'] ?></span><span class="tve-link-result-type"><?php echo $post_type_obj->labels->menu_name; ?></span></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php } else { ?>
            <div id="tve-tabs-2" class="tve-posts-not-found tve-tab" style="width: 100%; display:none;">
                <?php include dirname( __FILE__ ) . '/quick-links-no-results.php' ?>
            </div>
        <?php } ?>
    </div>
</div>
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