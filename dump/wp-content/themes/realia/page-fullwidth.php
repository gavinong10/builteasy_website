<?php
/**
 * Template Name: Full Width Template
 */
global $wp_query;

echo View::render('page-fullwidth.twig', array(
     'wp_query' => $wp_query,
     'posts' => $wp_query->posts,	
));