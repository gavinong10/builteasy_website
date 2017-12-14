<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_action_activate_page($page_id, $page_report){

    global $wpdb;
    $gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';

    if(is_array($page_report) && !empty($page_report)) {

        // Build our where clauses for selecting and deleting URLs
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

        // Remove selected URLs from blacklist table
        $wpdb->query("
            DELETE $gpi_page_blacklist FROM $gpi_page_blacklist
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

        // Remove selected URL from blacklist table
        $wpdb->query("
            DELETE $gpi_page_blacklist FROM $gpi_page_blacklist
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

