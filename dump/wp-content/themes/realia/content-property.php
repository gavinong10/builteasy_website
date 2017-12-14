<?php

global $post;

echo View::render('content-property.twig', array(
   'wp_query' => $wp_query,
   'property' => _aviators_properties_prepare_single(get_post(get_the_ID())),
));