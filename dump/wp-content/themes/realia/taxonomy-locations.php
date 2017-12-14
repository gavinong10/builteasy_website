<?php

global $wp_query;
$term = $wp_query->queried_object;

$content = get_term_field('description', $term->term_id, $term->taxonomy);

echo View::render('archive-property.twig', array(
    'title' => $term->name,
    'content' => $content,
    'wp_query' => $wp_query,
    'properties' => _aviators_properties_prepare($wp_query),
));
