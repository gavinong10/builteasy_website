<?php
global $wp_query;

$wp_query = new WP_Query(array(
    'post_type' => 'agent',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'posts_per_page' => aviators_settings_get_value('agents', 'agents', 'per_page'),
    'paged' => get_query_var( 'paged' ),
));

echo View::render('archive-agent.twig', array(
   'wp_query' => $wp_query,
   'posts' => $wp_query->posts,
));
