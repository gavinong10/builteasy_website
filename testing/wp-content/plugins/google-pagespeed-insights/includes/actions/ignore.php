<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_action_ignore_page($page_id, $page_report){
    global $wpdb;
    $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
    $gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';
    $gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';

    if(is_array($page_report) && !empty($page_report)) {
        // Build our where clauses for selecting and deleting URLs
        $page_report_count = count($page_report);
        $x = 1;
        $delete_where_clause = '';
        $select_where_clause = '';
        foreach($page_report as $page)
        {
            if($x < $page_report_count) {
                $delete_where_clause .= $gpi_page_stats. '.ID = ' . $gpi_page_reports . '.page_id AND ' . $gpi_page_stats . '.ID = ' . $page . ' OR ';
                $select_where_clause .= 'ID = ' . $page . ' OR ';
            } else {
                $delete_where_clause .= $gpi_page_stats. '.ID = ' . $gpi_page_reports . '.page_id AND ' . $gpi_page_stats . '.ID = ' . $page;
                $select_where_clause .= 'ID = ' . $page;
            }
            $x++;
        }

        // Get the URLs for all pages being blacklisted
        $blacklist_array = $wpdb->get_results("SELECT URL, type, object_id, term_id FROM $gpi_page_stats WHERE $select_where_clause", ARRAY_A);
        $blacklist_array_count = count($blacklist_array);
        if(!empty($blacklist_array)) {
            $y = 1;
            $values = '';
            foreach($blacklist_array as $blacklist) 
            {
                $object_id = (!is_null($blacklist['object_id'])) ? $blacklist['object_id'] : 'NULL';
                $term_id = (!is_null($blacklist['term_id'])) ? $blacklist['term_id'] : 'NULL';
                if($y < $page_report_count) {
                    $values .= '("'.$blacklist['URL'].'", "'.$blacklist['type'].'", '.$object_id.', '.$term_id.'),'; 
                } else {
                    $values .= '("'.$blacklist['URL'].'", "'.$blacklist['type'].'", '.$object_id.', '.$term_id.')'; 
                }
                $y++;
            }
            // Save blacklisted URLs into the database
            $wpdb->query("
                INSERT INTO $gpi_page_blacklist (URL, type, object_id, term_id)
                VALUES $values
            ");

            // Remove blacklisted URL data from page reports and page stats
            $wpdb->query("
                DELETE $gpi_page_stats, $gpi_page_reports FROM $gpi_page_stats, $gpi_page_reports
                WHERE $delete_where_clause
            ");
        }
        $return_message = $blacklist_array_count;

    } elseif(!empty($page_id)) {

        $query = "
            SELECT URL, type, object_id, term_id
            FROM $gpi_page_stats
            WHERE ID = $page_id
        ";
        $blacklist_obj = $wpdb->get_row($query);

        if(!empty($blacklist_obj)) {
            $object_id = (!is_null($blacklist_obj->object_id)) ? $blacklist_obj->object_id : 'NULL';
            $term_id = (!is_null($blacklist_obj->term_id)) ? $blacklist_obj->term_id : 'NULL';

            $values = '("'.$blacklist_obj->URL.'", "'.$blacklist_obj->type.'", '.$object_id.', '.$term_id.')'; 

            // Save blacklisted URLs into the database
            $wpdb->query("
                INSERT INTO $gpi_page_blacklist (URL, type, object_id, term_id)
                VALUES $values
            ");
        
            // Delete existing reports
            $wpdb->query("
                DELETE $gpi_page_stats, $gpi_page_reports FROM $gpi_page_stats, $gpi_page_reports
                WHERE $gpi_page_stats.ID = $gpi_page_reports.page_id
                AND $gpi_page_stats.ID = $page_id
            ");

            $return_message = '1';
        }
    }

    return $return_message;

}

