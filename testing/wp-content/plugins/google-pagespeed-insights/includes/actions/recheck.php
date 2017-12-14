<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_action_recheck_page($page_id, $page_report){

    global $wpdb;
    $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';

    if(is_array($page_report) && !empty($page_report)) {

        // Build our where clauses for selecting URLs
        $page_report_count = count($page_report);
        $x = 1;
        $where_clause = '';
        foreach($page_report as $page)
        {
            if($x < $page_report_count) {
                $where_clause .= 'ID = ' . $page . ' OR ';
            } else {
                $where_clause .= 'ID = ' . $page;
            }
            $x++;
        }

        // Set Force Recheck to 1 on selected URLs
        $wpdb->query("
            UPDATE $gpi_page_stats SET force_recheck = 1
            WHERE $where_clause
        ");

        // Schedule the api to check pages immediately
        $GPI_ListTable = new GPI_List_Table();
        $gpi_options = $GPI_ListTable->getOptions();

        require_once GPI_DIRECTORY . '/core/core.php';
        $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);

        $googlePagespeedInsights->google_pagespeed_insights_Update_Options('last_run_finished',false,'gpagespeedi_options');
        wp_schedule_event( time(), 'gpi_lastrun_checker', 'googlepagespeedinsightschecker' );

        $return_message = $page_report_count;

    } elseif(!empty($page_id)) {

        // Set Force Recheck to 1 on selected URL
        $wpdb->query("
            UPDATE $gpi_page_stats SET force_recheck = 1
            WHERE ID = $page_id
        ");

        // Schedule the api to check pages immediately
        $GPI_ListTable = new GPI_List_Table();
        $gpi_options = $GPI_ListTable->getOptions();

        require_once GPI_DIRECTORY . '/core/core.php';
        $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);

        $googlePagespeedInsights->google_pagespeed_insights_Update_Options('last_run_finished',false,'gpagespeedi_options');
        wp_schedule_event( time(), 'gpi_lastrun_checker', 'googlepagespeedinsightschecker' );

        $return_message = '1';

    }
    return $return_message;
}

