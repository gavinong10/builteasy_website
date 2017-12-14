<?php

echo View::render('content-agent.twig', array(
   'wp_query' => $wp_query,
   'posts' => $wp_query->posts,
));