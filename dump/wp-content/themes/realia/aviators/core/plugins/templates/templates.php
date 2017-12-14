<?php

require_once dirname( __FILE__ ) . '/helpers.php';
require_once dirname( __FILE__ ) . '/extensions.php';


class Template {
	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance !== null ) {
			return self::$instance;
		}

		Twig_Autoloader::register();
		$templates   = array();
        // child theme support
        if(get_stylesheet_directory() != get_stylesheet_directory()) {
          $templates[] = get_stylesheet_directory() . '/templates';
        }
		$templates[] = get_template_directory() . '/templates';

        // child theme support
        if(get_stylesheet_directory() != get_stylesheet_directory()) {
          $templates   = array_merge( $templates, aviators_templates_prepare_plugins_template_dirs(get_stylesheet_directory()) );
        }
		$templates   = array_merge( $templates, aviators_templates_prepare_plugins_template_dirs(get_template_directory()) );


		$loader = new Twig_Loader_Filesystem( $templates );

		$instance = new Twig_Environment( $loader, array(
			'cache' => get_template_directory() . '/templates/cache',
			'debug' => aviators_settings_get_value( 'templates', 'cache', 'debug' ),
		) );

		$instance->addGlobal( 'wp', new TwigProxy() );
		$instance->addGlobal( 'q', $_GET );
		$instance->addGlobal( 'p', $_POST );

		$instance->addFunction( new Twig_SimpleFunction( 'wp_footer', 'aviators_templates_helpers_wp_footer' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'wp_head', 'aviators_templates_helpers_wp_head' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'comment_form', 'aviators_templates_helpers_comment_form' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'body_class', 'aviators_templates_body_class' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'wp_list_comments', 'aviators_templates_helpers_wp_list_comments' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'post_class', 'aviators_templates_helpers_post_class' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'dynamic_sidebar', 'aviators_templates_helpers_dynamic_sidebar' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'comments_template', 'aviators_templates_helpers_comments_template' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'paginate_comments_links', 'aviators_templates_helpers_paginate_comments_links' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'next_comments_link', 'aviators_templates_helpers_next_comments_link' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'previous_comments_link', 'aviators_templates_helpers_previous_comments_link' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'posts_nav_link', 'aviators_templates_helpers_posts_nav_link' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'paginate_links', 'aviators_templates_helpers_paginate_links' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'next_posts_link', 'aviators_templates_helpers_next_posts_link' ) );
		$instance->addFunction( new Twig_SimpleFunction( 'previous_posts_link', 'aviators_templates_helpers_previous_posts_link' ) );
        $instance->addFunction( new Twig_SimpleFunction( 'kk_star_ratings', 'aviators_templates_helpers_kk_star_ratings' ) );

		$instance->addExtension( new HTMLDecodeTwigExtension() );

		return $instance;
	}
}

function aviators_templates_init() {
	$clear = aviators_settings_get_value( 'templates', 'cache', 'clear' );
	$debug = aviators_settings_get_value( 'templates', 'cache', 'debug' );
	if ( $clear == 'on' ) {
		$instance = Template::getInstance();
		$instance->clearCacheFiles();
		update_option( 'templates_cache_clear', FALSE );
	}

}

add_action( 'init', 'aviators_templates_init' );