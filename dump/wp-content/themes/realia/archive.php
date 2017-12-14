<?php

global $wp_query;

echo View::render('archive.twig', array(
     'wp_query' => $wp_query,
     'posts' => $wp_query->posts,
));
