<?php
global $wp_query;

$title = sprintf(
    __('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'aviators'),
    number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>'
);

echo View::render('comments.twig', array(
    'title' => $title,
    'wp_query' => $wp_query,
    'posts' => $wp_query->posts,
));
