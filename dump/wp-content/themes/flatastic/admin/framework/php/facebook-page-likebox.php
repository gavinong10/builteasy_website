<?php
/**
 * Define public facing class.
 *
 * @subpackage public
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MadFacebookPageLikebox {

	protected static $instance = null;

	private function __construct() {

		// Add public scripts in head
		add_action('wp_head', array(&$this, 'facebook_page_plugin_head'));

		// Add shortcode profile widget
		add_shortcode( 'mad_facebook_page_likebox', array( $this, 'facebook_page_plugin_likebox' ));
	}

	public function facebook_page_plugin_head() {
		echo '<div id="fb-root"></div>' . "\n";
		echo '<script>(function(d, s, id) {' . "\n";
		echo 'var js, fjs = d.getElementsByTagName(s)[0];' . "\n";
		echo 'if (d.getElementById(id)) return;' . "\n";
		echo 'js = d.createElement(s); js.id = id;' . "\n";
		echo 'js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";' . "\n";
		echo 'fjs.parentNode.insertBefore(js, fjs);' . "\n";
		echo '}(document, \'script\', \'facebook-jssdk\'));</script>' . "\n";
	}

	function facebook_page_plugin_likebox($attr) {

		$page_name = $hide_cover = $show_facespile = $show_posts = '';

		extract( shortcode_atts( array(
			'page_name' => 'https://www.facebook.com/WordPress',
			'hide_cover'     => false,
			'show_facespile' => true,
			'show_posts' => true
		), $attr ) );

		$output = '<div class="fb-page" data-height="350" data-href="'. $page_name . '" data-hide-cover="' . $hide_cover . '" data-show-facepile="' . $show_facespile . '" data-show-posts="' . $show_posts . '"><div class="fb-xfbml-parse-ignore"></div></div>';

		return $output;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}