<?php

global $wp_query;

$default_sort = aviators_settings_get_value('properties', 'properties', 'default_sort');
if(isset($_GET['filter_sort_by'])) {
    $default_sort = $_GET['filter_sort_by'];
}

echo View::render('archive-property.twig', array(
    'wp_query' => aviators_properties_filter(TRUE),
    'properties' => aviators_properties_filter(FALSE),
    'default_sort' => $default_sort,
));

