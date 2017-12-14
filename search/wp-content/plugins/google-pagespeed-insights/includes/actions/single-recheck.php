<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_action_single_recheck_page($page_id){

	global $wpdb;

    // If we are going to recheck this data, now is the time to do it
    if(!empty($page_id)) {
        $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
        $query = "
            SELECT URL, type, object_id, term_id
            FROM $gpi_page_stats
            WHERE ID = $page_id
        ";
        $page_stats = $wpdb->get_row($query, ARRAY_A);

        $GPI_ListTable = new GPI_List_Table();
        $gpi_options = $GPI_ListTable->getOptions();

        require_once GPI_DIRECTORY . '/core/core.php';
        $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);

        $urls_to_recheck = array();
        if(!is_null($page_stats['object_id'])) {
            $theid = $page_stats['object_id'];
        } elseif(!is_null($page_stats['term_id'])) {
            $theid = $page_stats['term_id'];
        }
        $urls_to_recheck[$page_stats['type']][] = array('url' => $page_stats['URL'], 'objectid' => $theid);

        $checkstatus = $googlePagespeedInsights->googlepagespeedinsightsworker($urls_to_recheck);

        if($checkstatus == false) {
            $message = __('The API is busy checking other pages, please try again later.', 'gpagespeedi');
        } else {
        	$message = __('Recheck Complete.', 'gpagespeedi');
        }

        return $message;

    }
}
