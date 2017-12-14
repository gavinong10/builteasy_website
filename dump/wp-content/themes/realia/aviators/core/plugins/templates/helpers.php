<?php

/**
 * Get an array of all available template directories for plugins
 * @return array
 */
function aviators_templates_prepare_plugins_template_dirs($dir) {
	$templates = array();



	$plugins   = glob( $dir . '/aviators/plugins/*', GLOB_ONLYDIR );

	$core_plugins = aviators_core_get_core_plugins_list();

	foreach ( $core_plugins as $core_plugin ) {
		$result = glob( $core_plugin . '/*', GLOB_ONLYDIR );

		if ( is_bool( $result ) ) {
			continue;
		}

		if ( in_array( $core_plugin . '/templates', $result ) ) {

			$templates[] = $core_plugin . '/templates';
		}
	}

	foreach ( $plugins as $plugin ) {
		$result = glob( $plugin . '/*', GLOB_ONLYDIR );

		if ( is_bool( $result ) ) {
			continue;
		}

		if ( in_array( $plugin . '/templates', $result ) ) {
			$templates[] = $plugin . '/templates';
		}
	}


	return $templates;
}

function aviators_templates_helpers_wp_footer() {
	wp_footer();
}

function aviators_templates_helpers_wp_head() {
	wp_head();
}

function aviators_templates_helpers_comment_form() {
  return comment_form();
}

function aviators_templates_helpers_body_class( $attrs = NULL ) {
	body_class( $attrs );
}

function aviators_templates_helpers_wp_list_comments( $attrs = NULL ) {
	wp_list_comments();
}

function aviators_templates_helpers_post_class( $attrs = NULL ) {
	post_class( $attrs );
}

function aviators_templates_helpers_dynamic_sidebar( $attrs = NULL ) {
	dynamic_sidebar( $attrs );
}

function aviators_templates_helpers_comments_template( $attrs = NULL ) {
	comments_template();
}

function aviators_templates_helpers_paginate_comments_links( $attrs = NULL ) {
	paginate_comments_links();
}

function aviators_templates_helpers_next_comments_link( $attrs = NULL ) {
	next_comments_link();
}

function aviators_templates_helpers_previous_comments_link( $attrs = NULL ) {
	paginate_comments_links();
}

function aviators_templates_helpers_posts_nav_link( $attrs = NULL ) {
	posts_nav_link( $attrs );
}

function aviators_templates_helpers_paginate_links( $attrs = NULL ) {
	paginate_links( $attrs );
}

function aviators_templates_helpers_next_posts_link( $attrs = NULL ) {
	next_posts_link( $attrs );
}

function aviators_templates_helpers_previous_posts_link( $attrs = NULL ) {
	previous_posts_link( $attrs );
}

function aviators_templates_helpers_kk_star_ratings( $attrs = NULL ) {
  if(function_exists('kk_star_ratings')) {
    return kk_star_ratings( $attrs );
  }
}






