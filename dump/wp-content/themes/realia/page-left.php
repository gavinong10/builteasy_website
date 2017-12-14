<?php
/**
 * Template Name: Left Sidebar Template
 */

global $wp_query;

echo View::render('page-left.twig', array(
     'wp_query' => $wp_query,
     'posts' => $wp_query->posts,
));