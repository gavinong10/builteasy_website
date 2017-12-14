<?php

echo View::render('404.twig', array(
   'wp_query' => $wp_query,
   'posts' => $wp_query->posts,
));
