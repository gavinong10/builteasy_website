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

class Mad_Pinterest_Aside_Panel {

	protected static $instance = null;

	/**
	 * Initialize the plugin 
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Add public scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add shortcode profile widget
		add_shortcode( 'mad_pin_profile', array( $this, 'pw_profile_widget' ));
	}

	/**
	 * Enqueue necessary scripts
	 *
	 * @since     1.0.0
	 *
	*/
	public function enqueue_scripts() {
		wp_register_script( MAD_PREFIX . 'pinterest-pinit-js', '//assets.pinterest.com/js/pinit.js', array(), '', true );
		wp_enqueue_script( MAD_PREFIX . 'pinterest-pinit-js' );
	}

	function pw_profile_widget( $attr ) {

		$username = '';

		extract( shortcode_atts( array(
			'username' => 'pinterest',
			'size'     => 'square'
		), $attr ) );

		$html  = '<div class="pw-wrap pw-shortcode">' . $this->pw_widget_boards( $username, '', 'embedUser' ) . '</div>';
		return $html;
	}

	function pw_widget_boards( $url, $label, $action ) {

		$scale_width  = 65;
		$scale_height = 250;
//		$board_width  = 300;

		if( $action == 'embedUser' ) {
			$url = "http://www.pinterest.com/" . $url;
		}

		$widget  = '<a data-pin-do="' . $action . '"';
		$widget .= ' href="' . esc_url( $url ) . '"';
		$widget .= ( ! empty( $scale_width ) ? ' data-pin-scale-width="' . $scale_width . '"' : '' );
		$widget .= ( ! empty( $scale_height ) ? ' data-pin-scale-height="' . $scale_height . '"' : '' );
		// if the board_width is empty then it has been set to 'auto' so we need to leave the data-pin-board-width attribute completely out
		$widget .= ( ! empty( $board_width ) ? ' data-pin-board-width="' . $board_width . '"' : '' );
		$widget .= '>' . $label . '</a>';

		return $widget;
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
