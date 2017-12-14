<?php
/**
 * Template Name: Register Template
 */

if (is_user_logged_in()) {
	aviators_flash_add_message(AVIATORS_FLASH_INFO, __('You are already logged in.', 'aviators'));
	return header('Location: ' . site_url());
} else {
	echo View::render('page-register.twig', array(
        'wp_query' => $wp_query,
        'posts' => $wp_query->posts,
        'active' => 'register',
    ));
}