<?php

global $post;

echo View::render('single-agent.twig', array(
	'post' => $post,
	'properties' => aviators_properties_get_by_agent(get_the_ID()),
));
