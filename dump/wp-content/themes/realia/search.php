<?php
global $wp_query;

echo View::render('search.twig', array(
     'wp_query' => $wp_query,
     'posts' => $wp_query->posts,
));