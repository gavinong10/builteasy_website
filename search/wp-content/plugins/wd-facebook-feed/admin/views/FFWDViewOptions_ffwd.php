<?php

class FFWDViewOptions_ffwd
{
    ////////////////////////////////////////////////////////////////////////////////////////
    // Events                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constants                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Variables                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    private $model;


    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($model)
    {
        $this->model = $model;
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function display($reset = FALSE)
    {
        global $WD_BWG_UPLOAD_DIR;

      
        ?>


        <div class="ffwd_upgrade wd-clear" >
            <div class="ffwd-left">

                <div style="font-size: 14px; ">
							    <?php _e("This section allows you to change settings for different views and general options.","ffwd");?>
                    <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/wordpress-facebook-feed/options.html"><?php _e("Read More in User Manual.","ffwd");?></a>
                </div>

            </div>
            <div class="ffwd-right">
                <div class="wd-table">
                    <div class="wd-cell wd-cell-valign-middle">
                        <a href="https://wordpress.org/support/plugin/wd-facebook-feed" target="_blank">
                            <img src="<?php echo WD_FFWD_URL; ?>/images/i_support.png" >
											    <?php _e("Support Forum", "gmwd"); ?>
                        </a>
                    </div>
                    <div class="wd-cell wd-cell-valign-middle">
                        <a href="https://web-dorado.com/products/wordpress-facebook-feed-plugin.html" target="_blank">
											    <?php _e("UPGRADE TO PAID VERSION", "gmwd"); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>



	    <?php
        $row = $this->model->get_row_data($reset);
        ?>
        <script>
            wd_fb_log_in = false;
        </script>
        <form method="post" class="wrap" action="admin.php?page=options_ffwd" style="width:99%;">
            <?php wp_nonce_field('options_ffwd', 'ffwd_nonce'); ?>
            <div class="ffwd_plugin_header">
                <span class="option-icon"></span>
                <h2 class="ffwd_page_name">Edit options</h2>
            </div>

            <div style="display: inline-block; width: 100%;">
                <div style="float: right;padding-top: 10px;">
                    <input class="ffwd-button-primary  ffwd-button-reset" type="submit" onclick="if (confirm('Do you want to reset to default?')) {
                                                                 spider_set_input_value('task', 'reset');
                                                               } else {
                                                                 return false;
                                                               }" value="Reset all options"/>
                    <input class="ffwd-button-primary ffwd-button-save" type="submit"
                           onclick="if (spider_check_required('title', 'Title')) {return false;}; check_app('<?php echo WD_FB_PREFIX; ?>'); spider_set_input_value('task', 'save')"
                           value="Save"/>

                </div>
            </div>
            <div style=" width: 100%;" id="display_panel">

                <!--User options-->
                <div class="spider_div_options" id="div_content_1" style="">
                    <table style="width: 60%;">
                        <tbody>
                        <tr>
                            <td class="spider_label_options">
                                <label for="<?php echo WD_FB_PREFIX; ?>_app_id">Facebook app Id: </label>
                            </td>
                            <td>
                                <input  type="text" name="<?php echo WD_FB_PREFIX; ?>_app_id"
                                       id="<?php echo WD_FB_PREFIX; ?>_app_id" size="30"
                                       value="<?php echo $row->app_id; ?>" class=""
                                       onchange="if(wd_fb_log_in) alert('Before change app id and secret please logout from curent app.')"/>

                            </td>
                        </tr>
                        <tr>
                            <td class="spider_label_options">
                                <label for="<?php echo WD_FB_PREFIX; ?>_app_secret">Facebook app Secret: </label>
                            </td>
                            <td>
                                <input  type="text" name="<?php echo WD_FB_PREFIX; ?>_app_secret"
                                       id="<?php echo WD_FB_PREFIX; ?>_app_secret" size="40"
                                       value="<?php echo $row->app_secret; ?>" class=""
                                       onchange="if(wd_fb_log_in) alert('Before change app id and secret please logout from curent app.'); "/>
                                <div class="spider_description">App ID and App Secret are optional. You only need to fill in this fields if you want your application Access Token to be used instead of the default Access Token.</div>

                            </td>
                        </tr>
                        <tr>
                            <td class="spider_label_options">
                                <label for="facebook_log_in">Facebook login / logout: </label>
                            </td>
                            <td>
                                <?php echo $this->model->log_in_log_out(); ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="spider_label_options">
                                <label>Feed autoupdate interval:</label>
                            </td>
                            <td>
                                <input type="number" id="autoupdate_interval_hour" class="spider_int_input"
                                       name="autoupdate_interval_hour" min="0" max="24"
                                       value="<?php echo floor($row->autoupdate_interval / 60); ?>"/>
                                hour
                                <input type="number" id="autoupdate_interval_min" class="spider_int_input"
                                       name="autoupdate_interval_min" min="0" max="59"
                                       value="<?php echo floor($row->autoupdate_interval % 60); ?>"/>
                                min
                                <div class="spider_description">Minimum 1 min.</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="spider_label_options">
                                <label>Timezone:</label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $row->date_timezone ?>" name="<?php echo WD_FB_PREFIX; ?>_date_timezone" />

                                <div class="spider_description">If left empty, the server timezone will be used</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="spider_label_options">
                                <label>Date format for posts:</label>
                            </td>
                            <td>
                                <select name="<?php echo WD_FB_PREFIX; ?>_post_date_format">
                                    <?php
                                    foreach ($this->model->date_formats as $key => $date_format) {
                                        ?>
                                        <option
                                            value="<?php echo $key; ?>" <?php if ($row->post_date_format == $key) echo 'selected="selected"'; ?>><?php echo $date_format; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="spider_description">Choose a date type.</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="spider_label_options">
                                <label>Date format for events:</label>
                            </td>
                            <td>
                                <select name="<?php echo WD_FB_PREFIX; ?>_event_date_format">
                                    <?php
                                    foreach (array_slice($this->model->date_formats, 1) as $key => $date_format) {
                                        ?>
                                        <option
                                            value="<?php echo $key; ?>" <?php if ($row->event_date_format == $key) echo 'selected="selected"'; ?>><?php echo $date_format; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="spider_description">Choose a date type.</div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="opacity_div"
                 style="display: none; background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
            <div id="loading_div"
                 style="display:none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
                <img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading"
                     style="margin-top: 200px; width:50px;">
            </div>
            <input id="task" name="task" type="hidden" value=""/>
            <input id="current_id" name="current_id" type="hidden" value="<?php /* echo $row->id; */ ?>"/>
            <script>
                // "State" global get var is for checking redirect from facebook
                function ffwd_show_hide_options(id, display) {
                    jQuery("#" + id).css("display", display);
                }
            </script>
        </form>
        <?php
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Listeners                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
}
