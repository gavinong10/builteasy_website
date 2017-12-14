<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Main class for handling the editor page related stuff
 *
 * Class TCB_Editor_Page
 */
class TCB_Editor {
	/**
	 * @var TCB_Editor
	 */
	private static $instance;

	/**
	 * post being edited
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * if the current post can be edited
	 *
	 * @var bool
	 */
	protected $can_edit_post = null;

	final private function __construct() {
	}

	/**
	 * singleton instance method
	 *
	 * @return TCB_Editor
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Template redirect hook for the main window ( containing the control panel and the post content iframe )
	 */
	public function hook_template_redirect() {

		if ( ! $this->is_main_frame() ) {
			return;
		}

		remove_all_actions( 'wp_head' );

		remove_all_actions( 'wp_enqueue_scripts' );
		remove_all_actions( 'wp_print_scripts' );
		remove_all_actions( 'wp_print_footer_scripts' );
		remove_all_actions( 'wp_footer' );

		add_action( 'wp_head', 'wp_enqueue_scripts' );
		add_action( 'wp_head', 'wp_print_styles' );
		add_action( 'wp_print_footer_scripts', '_wp_footer_scripts' );

		add_action( 'wp_footer', '_wp_footer_scripts' );
		add_action( 'wp_footer', 'wp_print_footer_scripts' );
		add_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		add_action( 'wp_enqueue_scripts', array( $this, 'main_frame_enqueue' ) );

		/**
		 * Action hook.
		 * Allows executing 3rd party code in this point. Example: dequeue any necessary resources from the editor main page
		 */
		do_action( 'tcb_hook_template_redirect' );

		include TVE_TCB_ROOT_PATH . 'templates/editor.php';
		exit();
	}

	/**
	 * Check if the current screen is the main frame for the editor ( containing the control panel and the content frame )
	 */
	public function is_main_frame() {
		if ( empty( $_REQUEST[ TVE_EDITOR_FLAG ] ) || ! is_singular() ) {
			return false;
		}
		/**
		 * if we are in the iframe request, we are not in the main editor page request
		 */
		if ( isset( $_REQUEST[ TVE_FRAME_FLAG ] ) ) {
			return false;
		}

		if ( ! $this->can_edit_post() ) { // if this isn't a TCB editable post
			return false;
		}

		return true;
	}

	/**
	 * Check capabilities and regular conditions for the editing screen
	 *
	 * @return bool
	 */
	public function can_edit_post() {
		if ( isset( $this->can_edit_post ) ) {
			return $this->can_edit_post;
		}
		$this->post = get_post();
		if ( ! $this->post ) {
			return $this->can_edit_post = false;
		}

		if ( ! tve_is_post_type_editable( $this->post->post_type ) || ! current_user_can( 'edit_posts' ) ) {
			return $this->can_edit_post = false;
		}

		$page_for_posts = get_option( 'page_for_posts' );
		if ( $page_for_posts && (int) $this->post->ID === (int) $page_for_posts ) {
			return $this->can_edit_post = false;
		}

		if ( ! tve_tcb__license_activated() && ! apply_filters( 'tcb_skip_license_check', false ) ) {
			return $this->can_edit_post = false;
		}

		return $this->can_edit_post = apply_filters( 'tcb_user_can_edit', true, $this->post->ID );
	}

	/**
	 * Check if the current screen (request) if the inner contents iframe ( the one displaying the actual post content )
	 */
	public function is_inner_frame() {
		if ( empty( $_REQUEST[ TVE_EDITOR_FLAG ] ) || ! is_singular() || empty( $_REQUEST[ TVE_FRAME_FLAG ] ) ) {
			return false;
		}
		if ( ! $this->can_edit_post() ) {
			return false;
		}

		/**
		 * the iframe receives a query string variable
		 */
		if ( ! wp_verify_nonce( $_REQUEST[ TVE_FRAME_FLAG ], TVE_FRAME_FLAG . $this->post->ID ) ) {
			return false;
		}

		return true;
	}

	/**
	 * enqueue scripts and styles for the main frame
	 */
	public function main_frame_enqueue() {
		/**
		 * the constant should be defined somewhere in wp-config.php file
		 */
		$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

		wp_enqueue_script( 'jquery' );
		tve_enqueue_script( 'tve-controls', tve_editor_js() . '/util/controls' . $js_suffix, array( 'jquery' ) );
		tve_enqueue_script( 'tve-main-frame', tve_editor_js() . '/main_frame' . $js_suffix, array(
			'jquery',
			'tve-controls',
			'backbone',
		) );

		tve_enqueue_style( 'tve_editor_style', tve_editor_css() . '/editor.css' );

		wp_localize_script( 'tve-main-frame', 'tcb_main_const', $this->main_frame_localize() );
	}

	/**
	 * Javascript localization for the main TCB frame
	 *
	 * @return array
	 */
	public function main_frame_localize() {
		$admin_base_url = admin_url( '/', is_ssl() ? 'https' : 'admin' );
		// for some reason, the above line does not work in some instances
		if ( is_ssl() ) {
			$admin_base_url = str_replace( 'http://', 'https://', $admin_base_url );
		}

		return array(
			'frame_uri' => $this->inner_frame_url(),
			'nonce'     => wp_create_nonce( 'tve-le-verify-sender-track129' ),
			'ajax_url'  => $admin_base_url . 'admin-ajax.php',
			//			'post'      => $this->post, // not sure if this will be needed
		);
	}

	/**
	 * Output the inner control panel menus for elements ( menus for each element )
	 */
	public function inner_frame_menus() {
		if ( ! $this->is_inner_frame() ) {
			return;
		}

		$inner_frame = true;
		include TVE_TCB_ROOT_PATH . 'editor/control_panel.php';
	}

	/**
	 * clean up inner frame ( e.g. remove admin menu )
	 */
	public function clean_inner_frame() {
		if ( ! $this->is_inner_frame() ) {
			return;
		}
		add_filter( 'show_admin_bar', '__return_false' );
	}

	/**
	 * Get the inner frame URL
	 *
	 * @return string
	 */
	public function inner_frame_url() {
		return add_query_arg( TVE_FRAME_FLAG, wp_create_nonce( TVE_FRAME_FLAG . $this->post->ID ), $_SERVER['REQUEST_URI'] );
	}
}
