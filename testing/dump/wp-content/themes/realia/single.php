<?php

echo View::render('single.twig', array(
   'wp_query' => $wp_query,
   'posts' => $wp_query->posts,
));
