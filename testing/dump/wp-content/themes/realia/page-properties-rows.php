<?php
/**
 * Template Name: Properties Rows Template
 */
global $wp_query;

$rows = aviators_settings_get_value('properties', 'homepage-rows', 'rows');
$type = aviators_settings_get_value('properties', 'homepager-rows', 'type');
$shuffle = aviators_settings_get_value('properties', 'homepage-rows', 'shuffle_results');

$do_shuffle = FALSE;
if ($shuffle == 'on') {
    $do_shuffle = TRUE;
}

switch ($type) {
    case 'reduced':
        $posts = aviators_properties_get_reduced($rows, $do_shuffle);
        break;
    case 'featured':
        $posts = aviators_properties_get_featured($rows, $do_shuffle);
        break;
    default:
        $posts = aviators_properties_get_most_recent($rows, $do_shuffle);
        break;
}

$id = get_the_ID();
$post = get_post($id);
$content = do_shortcode($post->post_content);

echo View::render('page-properties-rows.twig', array(
     'wp_query' => $wp_query,
     'posts' => $posts,
     'page' => $post,
     'content' => $content,
));