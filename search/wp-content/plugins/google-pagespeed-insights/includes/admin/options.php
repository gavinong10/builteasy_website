<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_options_page() {

    ?>
    <?php
        if($_POST) {

            check_admin_referer('gpagespeedi_options');

            //Check for purge all data option and truncate tables if checked
            if($_POST['purge_all_data']) {
                global $wpdb;
                $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
                $gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';
                $gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';
                
                if($_POST['purge_all_data'] == 'purge_reports') {
                    $wpdb->query("TRUNCATE TABLE $gpi_page_stats");
                    $wpdb->query("TRUNCATE TABLE $gpi_page_reports");
                } elseif($_POST['purge_all_data'] == 'purge_everything') {
                    $wpdb->query("TRUNCATE TABLE $gpi_page_stats");
                    $wpdb->query("TRUNCATE TABLE $gpi_page_reports");
                    $wpdb->query("TRUNCATE TABLE $gpi_page_blacklist");
                }
            }

            $options = get_option('gpagespeedi_options');
            $ui_options = get_option('gpagespeedi_ui_options');

            $google_developer_key       = ($_POST['google_developer_key'] != "")    ? $_POST['google_developer_key']        : $options['google_developer_key'];
            $response_language          = ($_POST['response_language'] != "")       ? $_POST['response_language']           : $options['response_language'];
            $strategy                   = ($_POST['strategy'] != "")                ? $_POST['strategy']                    : $options['strategy'];
            $max_execution_time         = ($_POST['max_execution_time'] != "")      ? $_POST['max_execution_time']          : $options['max_execution_time'];
            $sleep_time                 = ($_POST['sleep_time'] != "")              ? $_POST['sleep_time']                  : $options['sleep_time'];
            $log_api_errors             = (isset($_POST['log_api_errors']))         ? true                                  : false;
            $scan_method                = ($_POST['scan_method'] != "")             ? $_POST['scan_method']                 : $options['scan_method'];
            $recheck_interval           = ($_POST['recheck_interval'] != "")        ? $_POST['recheck_interval']            : $options['recheck_interval'];
            $check_pages                = (isset($_POST['check_pages']))            ? true                                  : false;
            $check_posts                = (isset($_POST['check_posts']))            ? true                                  : false;
            $cpt_whitelist              = (isset($_POST['cpt_whitelist']))          ? serialize($_POST['cpt_whitelist'])    : false;
            $check_categories           = (isset($_POST['check_categories']))       ? true                                  : false;
            $default_strategy           = ($_POST['strategy'] != "both")            ? $_POST['strategy']                    : $ui_options['view_preference'];

            $new_values = array(
                'google_developer_key'      => $google_developer_key,
                'response_language'         => $response_language,
                'strategy'                  => $strategy,
                'max_execution_time'        => $max_execution_time,
                'sleep_time'                => $sleep_time,
                'log_api_errors'            => $log_api_errors,
                'scan_method'               => $scan_method,
                'recheck_interval'          => $recheck_interval,
                'check_pages'               => $check_pages,
                'check_posts'               => $check_posts,
                'cpt_whitelist'             => $cpt_whitelist,
                'check_categories'          => $check_categories,
                'first_run_complete'        => $options['first_run_complete'],
                'last_run_finished'         => $options['last_run_finished'],
                'bad_api_key'               => false,
                'pagespeed_disabled'        => false,
                'new_ignored_items'         => false,
                'backend_error'             => false,
                'new_activation_message'    => false
            );
            update_option( 'gpagespeedi_options', $new_values );

            $new_ui_values = array(
                'action_message'            => false,
                'view_preference'           => $default_strategy
            );
            update_option( 'gpagespeedi_ui_options', $new_ui_values );

            if($new_values['scan_method'] == "wp_cron") {
                if(!$options['first_run_complete'] && $google_developer_key != '' || isset($_POST['check_new_pages']) || isset($_POST['recheck_all_pages'])) {
                    
                    if( isset( $_POST['recheck_all_pages'] ) ) {
                        $recheck = true;
                    } else {
                        $recheck = false;
                    }

                    $worker_args = array(
                        array(), false, $recheck
                    );
                    wp_schedule_single_event( time(), 'googlepagespeedinsightschecknow', $worker_args );
                }
            }
        }
        $options = get_option('gpagespeedi_options');
        $cpt_whitelist_arr = unserialize($options['cpt_whitelist']);
    ?>
    <?php if($_POST) { 
        if(!$options['first_run_complete'] || isset($_POST['check_new_pages']) || isset($_POST['recheck_all_pages']) ) {
            ?>
            <div id="message" class="updated">
                <?php if($options['scan_method'] == "session_flush") { ?>                
                    <p><?php _e('Settings Saved. Google Pagespeed Insights will now begin generating page reports. This page may appear to still be loading, however report generation will continue when you navigate away.', 'gpagespeedi'); ?></p>
                <?php } else { ?>
                    <p><?php _e('Settings Saved. Google Pagespeed Insights will now begin generating page reports. Click the "Report List" tab to watch the progress', 'gpagespeedi'); ?></p>
                <?php } ?>
            </div>
            <?php
        } else {
            ?>
            <div id="message" class="updated">
                <p><?php _e('Settings Saved.', 'gpagespeedi'); ?></p>
            </div>
            <?php
        }
    } ?>
    <?php

    require_once GPI_DIRECTORY . '/core/core.php';
    $googlePagespeedInsights = new googlePagespeedInsights($options);

    //Show currently working status on admin pages if GPI is working in the background
    $worker_status = $googlePagespeedInsights->google_pagespeed_insights_Check_Status();
    if($worker_status) { ?>
        <div id="message" class="error">
           <p><?php _e('Google Pagespeed Options cannot be changed while Pagespeed is running. Please wait until it has finished to make any changes.', 'gpagespeedi'); ?></p>
        </div>
    <?php } ?>

    <form method="post" action="?page=<?php echo $_REQUEST['page'];?>&render=options">
        <?php
        if ( function_exists('wp_nonce_field') )  {
            wp_nonce_field('gpagespeedi_options');
        }
        ?>

        <div class="row framed boxsizing">
            <div class="boxheader large toggle">
                <span class="left google"><?php _e('Google Pagespeed Options', 'gpagespeedi'); ?></span>
                <span class="right open"></span>
            </div>
            <div class="padded">
                <p><?php _e('Google API Key:', 'gpagespeedi'); ?></p>
                <input type="text" name="google_developer_key" id="google_developer_key" value="<?php echo $options['google_developer_key'];?>" class="googleapi code" />
                <p class="description"><span style="color:red;"><?php _e('This is required', 'gpagespeedi'); ?></span>: <?php _e('if you do not have an API key you can create a new one for free from', 'gpagespeedi'); ?>: <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a></p>
                <p class="description"><?php _e('If you need help creating an API key, please see the documentation included with this plugin.', 'gpagespeedi'); ?>: <a href="http://mattkeys.me/documentation/google-pagespeed-insights/" target="_blank">Documentation</a></p>

                <p><?php _e('Google Response Language:', 'gpagespeedi'); ?></p>
                <select name="response_language" id="response_language">
                    <option value="ar" <?php if($options['response_language'] == 'ar') {echo 'selected="selected"';} ?>>Arabic</option>
                    <option value="bg" <?php if($options['response_language'] == 'bg') {echo 'selected="selected"';} ?>>Bulgarian</option>
                    <option value="ca" <?php if($options['response_language'] == 'ca') {echo 'selected="selected"';} ?>>Catalan</option>
                    <option value="zh_TW" <?php if($options['response_language'] == 'zh_TW') {echo 'selected="selected"';} ?>>Traditional Chinese (Taiwan)</option>
                    <option value="zh_CN" <?php if($options['response_language'] == 'zh_CN') {echo 'selected="selected"';} ?>>Simplified Chinese</option>
                    <option value="hr" <?php if($options['response_language'] == 'hr') {echo 'selected="selected"';} ?>>Croatian</option>
                    <option value="cs" <?php if($options['response_language'] == 'cs') {echo 'selected="selected"';} ?>>Czech</option>
                    <option value="da" <?php if($options['response_language'] == 'da') {echo 'selected="selected"';} ?>>Danish</option>
                    <option value="nl" <?php if($options['response_language'] == 'nl') {echo 'selected="selected"';} ?>>Dutch</option>
                    <option value="en_US" <?php if($options['response_language'] == 'en_US') {echo 'selected="selected"';} ?>>English</option>
                    <option value="en_GB" <?php if($options['response_language'] == 'en_GB') {echo 'selected="selected"';} ?>>English UK</option>
                    <option value="fil" <?php if($options['response_language'] == 'fil') {echo 'selected="selected"';} ?>>Filipino</option>
                    <option value="fi" <?php if($options['response_language'] == 'fi') {echo 'selected="selected"';} ?>>Finnish</option>
                    <option value="fr" <?php if($options['response_language'] == 'fr') {echo 'selected="selected"';} ?>>French</option>
                    <option value="de" <?php if($options['response_language'] == 'de') {echo 'selected="selected"';} ?>>German</option>
                    <option value="el" <?php if($options['response_language'] == 'el') {echo 'selected="selected"';} ?>>Greek</option>
                    <option value="iw" <?php if($options['response_language'] == 'iw') {echo 'selected="selected"';} ?>>Hebrew</option>
                    <option value="hi" <?php if($options['response_language'] == 'hi') {echo 'selected="selected"';} ?>>Hindi</option>
                    <option value="hu" <?php if($options['response_language'] == 'hu') {echo 'selected="selected"';} ?>>Hungarian</option>
                    <option value="id" <?php if($options['response_language'] == 'id') {echo 'selected="selected"';} ?>>Indonesian</option>
                    <option value="it" <?php if($options['response_language'] == 'it') {echo 'selected="selected"';} ?>>Italian</option>
                    <option value="ja" <?php if($options['response_language'] == 'ja') {echo 'selected="selected"';} ?>>Japanese</option>
                    <option value="ko" <?php if($options['response_language'] == 'ko') {echo 'selected="selected"';} ?>>Korean</option>
                    <option value="lv" <?php if($options['response_language'] == 'lv') {echo 'selected="selected"';} ?>>Latvian</option>
                    <option value="lt" <?php if($options['response_language'] == 'lt') {echo 'selected="selected"';} ?>>Lithuanian</option>
                    <option value="no" <?php if($options['response_language'] == 'no') {echo 'selected="selected"';} ?>>Norwegian</option>
                    <option value="pl" <?php if($options['response_language'] == 'pl') {echo 'selected="selected"';} ?>>Polish</option>
                    <option value="pt_BR" <?php if($options['response_language'] == 'pt_BR') {echo 'selected="selected"';} ?>>Portuguese (Brazilian)</option>
                    <option value="pt_PT" <?php if($options['response_language'] == 'pt_PT') {echo 'selected="selected"';} ?>>Portuguese (Portugal)</option>
                    <option value="ro" <?php if($options['response_language'] == 'ro') {echo 'selected="selected"';} ?>>Romanian</option>
                    <option value="ru" <?php if($options['response_language'] == 'ru') {echo 'selected="selected"';} ?>>Russian</option>
                    <option value="sr" <?php if($options['response_language'] == 'sr') {echo 'selected="selected"';} ?>>Serbian</option>
                    <option value="sk" <?php if($options['response_language'] == 'sk') {echo 'selected="selected"';} ?>>Slovakian</option>
                    <option value="sl" <?php if($options['response_language'] == 'sl') {echo 'selected="selected"';} ?>>Slovenian</option>
                    <option value="es" <?php if($options['response_language'] == 'es') {echo 'selected="selected"';} ?>>Spanish</option>
                    <option value="sv" <?php if($options['response_language'] == 'sv') {echo 'selected="selected"';} ?>>Swedish</option>
                    <option value="th" <?php if($options['response_language'] == 'th') {echo 'selected="selected"';} ?>>Thai</option>
                    <option value="tr" <?php if($options['response_language'] == 'tr') {echo 'selected="selected"';} ?>>Turkish</option>
                    <option value="uk" <?php if($options['response_language'] == 'uk') {echo 'selected="selected"';} ?>>Ukrainian</option>
                    <option value="vi" <?php if($options['response_language'] == 'vi') {echo 'selected="selected"';} ?>>Vietnamese</option>
                </select>
                <?php if($options['first_run_complete']) { ?>
                    <p class="description"><span style="color:red;"><?php _e('Note', 'gpagespeedi'); ?></span>: <?php _e('URLs must be rechecked before language changes take effect. Use the "Delete Data" option under "Advanced Configuration" if you would like to remove old reports.', 'gpagespeedi'); ?></p>
                <?php } ?>

                <p><?php _e('Report Type(s):', 'gpagespeedi'); ?></p>
                <select name="strategy" id="strategy">
                    <option value="desktop" <?php if($options['strategy'] == 'desktop') {echo 'selected="selected"';} ?>><?php _e('Desktop', 'gpagespeedi'); ?></option>
                    <option value="mobile" <?php if($options['strategy'] == 'mobile') {echo 'selected="selected"';} ?>><?php _e('Mobile', 'gpagespeedi'); ?></option>
                    <option value="both" <?php if($options['strategy'] == 'both') {echo 'selected="selected"';} ?>><?php _e('Both', 'gpagespeedi'); ?></option>
                </select>
            </div>
        </div>

        <div class="row framed boxsizing">
            <div class="boxheader large toggle">
                <span class="left cal"><?php _e('URL Configuration', 'gpagespeedi'); ?></span>
                <span class="right"></span>
            </div>
            <div class="padded hidden">

                <label for="recheck_interval"><?php _e('Report Expiration', 'gpagespeedi'); ?>:</label>                    
                <select name="recheck_interval" id="recheck_interval">
                    <option value="<?php echo 86400;?>" <?php if($options['recheck_interval'] == 86400) {echo 'selected="selected"';} ?>><?php _e('1 Day', 'gpagespeedi'); ?></option>
                    <option value="<?php echo 7*86400;?>" <?php if($options['recheck_interval'] == 7*86400) {echo 'selected="selected"';} ?>><?php _e('7 Days', 'gpagespeedi'); ?></option>
                    <option value="<?php echo 15*86400;?>" <?php if($options['recheck_interval'] == 15*86400) {echo 'selected="selected"';} ?>><?php _e('15 Days', 'gpagespeedi'); ?></option>
                    <option value="<?php echo 30*86400;?>" <?php if($options['recheck_interval'] == 30*86400) {echo 'selected="selected"';} ?>><?php _e('30 Days', 'gpagespeedi'); ?></option>
                </select>
                <p class="description"><?php _e('When using "Save Options & Check Pages", pages which are newer than the specified Report Expiration will be skipped.', 'gpagespeedi'); ?></p>

                <p><?php _e('Configure which types of URLs should be checked when running reports.', 'gpagespeedi'); ?></p>
                <p><span style="color:red;"><?php _e('Note', 'gpagespeedi'); ?></span>: <?php _e('Google Pagespeed will load each page to generate a report. The more pages you select, the longer it will take for the scan to complete.', 'gpagespeedi'); ?></p>
                <p class="checkbx">
                    <input type="checkbox" name="check_pages" id="check_pages" <?php if($options['check_pages'] === true) {echo 'checked="checked"';} ?>/>
                    <label for="check_pages"><?php _e('Check Wordpress Pages', 'gpagespeedi'); ?> (<?php echo wp_count_posts( 'page' )->publish; ?>)</label>
                </p>
                <p class="checkbx">
                    <input type="checkbox" name="check_posts" id="check_posts" <?php if($options['check_posts'] === true) {echo 'checked="checked"';} ?>/>
                    <label for="check_posts"><?php _e('Check Wordpress Posts', 'gpagespeedi'); ?> (<?php echo wp_count_posts( 'post' )->publish; ?>)</label>
                </p>
                <?php
                $category_count = count(get_categories());
                ?>
                <p class="checkbx">
                    <input type="checkbox" name="check_categories" id="check_categories" <?php if($options['check_categories'] === true) {echo 'checked="checked"';} ?>/>
                    <label for="check_categories"><?php _e('Check Category Indexes', 'gpagespeedi'); ?> (<?php echo $category_count; ?>)</label>
                </p>
                <?php
                    $args=array(
                      'public'   => true,
                      '_builtin' => false
                    ); 
                    $custom_post_types = get_post_types($args,'names','and');
                    if(!empty($custom_post_types)) {
                        ?>
                        <p class="checkbx">
                            <?php _e('Custom Post Types', 'gpagespeedi'); ?>:
                        </p>

                        <div class="padded" style="padding-top: 0px;">
                        <?php

                        foreach ($custom_post_types  as $custom_post_type ) {
                            
                            $post_count = wp_count_posts( $custom_post_type )->publish;

                            ?>
                            <p class="checkbx posttypes">
                                <input type="checkbox" name="cpt_whitelist[]" id="cpt_<?php echo $custom_post_type; ?>" value="<?php echo $custom_post_type; ?>" <?php if($cpt_whitelist_arr && in_array($custom_post_type, $cpt_whitelist_arr)) {echo 'checked="checked"';} ?> />
                                <label for="cpt_<?php echo $custom_post_type; ?>"><?php echo $custom_post_type; ?> (<?php echo $post_count; ?>)</label>
                            </p>
                            <?php
                        }

                        echo '</div>';

                    }
                ?>
            </div>
        </div>

        <div class="row framed boxsizing">
            <div class="boxheader large toggle">
                <span class="left gear"><?php _e('Advanced Configuration', 'gpagespeedi'); ?></span>
                <span class="right"></span>
            </div>
            <div class="padded hidden">
                <p><?php _e('For most users, the following settings can be left at their defaults unless otherwise instructed by support.', 'gpagespeedi'); ?></p>

                <p><label for="max_execution_time"><?php _e('Maximum Execution Time', 'gpagespeedi'); ?>:</label></p>
                <select name="max_execution_time" id="max_execution_time">
                    <option value="60" <?php if($options['max_execution_time'] == 60) {echo 'selected="selected"';} ?>><?php _e('1 Minute', 'gpagespeedi'); ?></option>
                    <option value="300" <?php if($options['max_execution_time'] == 300) {echo 'selected="selected"';} ?>><?php _e('5 Minutes', 'gpagespeedi'); ?></option>
                    <option value="600" <?php if($options['max_execution_time'] == 600) {echo 'selected="selected"';} ?>><?php _e('10 Minutes', 'gpagespeedi'); ?></option>
                    <option value="900" <?php if($options['max_execution_time'] == 900) {echo 'selected="selected"';} ?>><?php _e('15 Minutes', 'gpagespeedi'); ?></option>
                    <option value="1800" <?php if($options['max_execution_time'] == 1800) {echo 'selected="selected"';} ?>><?php _e('30 Minutes', 'gpagespeedi'); ?></option>
                </select>
                <p class="description"><?php _e('The default value of 5 minutes is fine for most sites.', 'gpagespeedi'); ?> <br /><?php _e('Increasing this value may help if your page reports are missing pages.', 'gpagespeedi'); ?></p>

                <p><label for="sleep_time"><?php _e('Report Throttling Delay Time', 'gpagespeedi'); ?>:</label></p>
                <select name="sleep_time" id="sleep_time">
                    <option value="0" <?php if($options['sleep_time'] == 0) {echo 'selected="selected"';} ?>><?php _e('0 Seconds', 'gpagespeedi'); ?></option>
                    <option value="1" <?php if($options['sleep_time'] == 1) {echo 'selected="selected"';} ?>><?php _e('1 Seconds', 'gpagespeedi'); ?></option>
                    <option value="2" <?php if($options['sleep_time'] == 2) {echo 'selected="selected"';} ?>><?php _e('2 Seconds', 'gpagespeedi'); ?></option>
                    <option value="3" <?php if($options['sleep_time'] == 3) {echo 'selected="selected"';} ?>><?php _e('3 Seconds', 'gpagespeedi'); ?></option>
                    <option value="4" <?php if($options['sleep_time'] == 4) {echo 'selected="selected"';} ?>><?php _e('4 Seconds', 'gpagespeedi'); ?></option>
                    <option value="5" <?php if($options['sleep_time'] == 5) {echo 'selected="selected"';} ?>><?php _e('5 Seconds', 'gpagespeedi'); ?></option>
                    <option value="10" <?php if($options['sleep_time'] == 10) {echo 'selected="selected"';} ?>><?php _e('10 Seconds', 'gpagespeedi'); ?></option>
                </select>
                <p class="description"><?php _e('The default value of 0 seconds is fine for most sites.', 'gpagespeedi'); ?> <br /><?php _e('Raising this value will slow down page reporting, but may help provide more accurate reports on poorly performing web servers', 'gpagespeedi'); ?></p>

                <p class="checkbx">
                    <input type="checkbox" name="log_api_errors" id="log_api_errors" <?php if($options['log_api_errors'] === true) {echo 'checked="checked"';} ?>/>
                    <label for="log_api_errors"><?php _e('Log API Exceptions', 'gpagespeedi'); ?></label>
                </p>
                <p class="description"><?php _e('Exception logs will appear in the "logs" folder in the Google Pagespeed Insights plugin directory. This directory must be writable.', 'gpagespeedi'); ?></p>

                <p><label for="scan_method"><?php _e('Scan Technique', 'gpagespeedi'); ?></label></p>
                <select name="scan_method" id="scan_method">
                    <option value="wp_cron" <?php if($options['scan_method'] == "wp_cron") {echo 'selected="selected"';} ?>><?php _e('WP Cron', 'gpagespeedi'); ?></option>
                    <option value="ajax" <?php if($options['scan_method'] == "ajax") {echo 'selected="selected"';} ?>><?php _e('Ajax', 'gpagespeedi'); ?></option>
                    <option value="session_flush" <?php if($options['scan_method'] == "session_flush") {echo 'selected="selected"';} ?>><?php _e('Session Flush', 'gpagespeedi'); ?></option>
                </select>
                <p class="description"><?php _e('Some servers have difficulty with the default (WP Cron) scan technique. If you are having problems with WP Cron, try Ajax. If you are having trouble with Ajax as well, try Session Flush.', 'gpagespeedi'); ?></p>

                <p><label for="sleep_time"><?php _e('Delete Data', 'gpagespeedi'); ?>:</label></p>
                <select name="purge_all_data" id="purge_all_data">
                    <option><?php _e('Do Nothing', 'gpagespeedi'); ?></option>
                    <option value="purge_reports"><?php _e('Delete Reports Only', 'gpagespeedi'); ?></option>
                    <option value="purge_everything"><?php _e('Delete EVERYTHING', 'gpagespeedi'); ?></option>
                </select>
                <p class="description"><span style="color:red;"><?php _e('Warning', 'gpagespeedi'); ?>:</span> <?php _e('This option can not be reversed.', 'gpagespeedi'); ?></p>
            </div>
        </div>
    <?php
    if(!$worker_status) {
        if(!$options['first_run_complete']) {
            submit_button( __('Save Options & Start Reporting', 'gpagespeedi') );
        } else {
            echo '<p class="submit">';
            submit_button( __('Save Options', 'gpagespeedi'), 'primary', 'submit', false );
            submit_button( __('Save Options & Check Pages', 'gpagespeedi'), 'secondary', 'check_new_pages', false );
            submit_button( __('Save Options & Force Recheck All Pages', 'gpagespeedi'), 'secondary', 'recheck_all_pages', false );
            echo '</p>';
        }
    } ?>
    </form>
    <?php

    //If Alternative Pagescan is enabled in options, do it.
    if($options['scan_method'] == "session_flush") {
        if(!$options['first_run_complete'] && $google_developer_key != '' || isset($_POST['check_new_pages']) || isset($_POST['recheck_all_pages'])) {
            if( isset( $_POST['recheck_all_pages'] ) ) {
                $recheck = true;
            } else {
                $recheck = false;
            }
            $googlePagespeedInsights->googlepagespeedinsightsworker( array(), true, $recheck );
        }
    }

}