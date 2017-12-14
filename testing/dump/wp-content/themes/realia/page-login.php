<?php
/**
 * Template Name: Login Template
 */

if (is_user_logged_in()) {
    aviators_flash_add_message(AVIATORS_FLASH_INFO, __('You are already logged in.', 'aviators'));
    return header('Location: ' . site_url());
} else {
    global $wp_query;

    echo View::render('page-login.twig', array(
        'wp_query' => $wp_query,
        'posts' => $wp_query->posts,
        'active' => 'login',
    ));
}