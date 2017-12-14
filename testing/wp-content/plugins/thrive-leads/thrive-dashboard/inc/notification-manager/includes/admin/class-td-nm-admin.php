<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Class TD_NM_Admin
 */
class TD_NM_Admin {

	private $_dashboard_page = 'tve_dash_notification_manager';

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditional_hooks' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'td_nm_get_wordpress_notifications' ) );
	}

	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	public function includes() {

	}

	public function enqueue_styles() {
		tve_dash_enqueue_style( 'td-nm-admin', TD_NM()->url( 'assets/css/admin.css' ) );
	}

	public function enqueue_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( $screen_id === 'admin_page_tve_dash_notification_manager' ) {

			tve_dash_enqueue();

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'backbone' );

			tve_dash_enqueue_script( 'td-nm-admin', TD_NM()->url( 'assets/js/admin/backbone.min.js' ), array( 'jquery', 'backbone' ), false, true );

			$params = array(
				't'                  => include TD_NM()->path( 'i18n.php' ),
				'dash_url'           => admin_url( 'admin.php?page=tve_dash_section' ),
				'url'                => TD_NM()->url(),
				'trigger_types'      => TD_NM()->get_trigger_types(),
				'action_types'       => TD_NM()->get_action_types(),
				'tl'                 => array(
					'groups'       => $this->get_tl_groups(),
					'shortcodes'   => $this->get_tl_shortcodes(),
					'thrive_boxes' => $this->get_tl_thrive_boxes(),
				),
				'message_shortcodes' => TD_NM()->get_message_shortcodes(),
				'split_tests'        => $this->get_split_tests(),
				'admin_nonce'        => wp_create_nonce( 'td_nm_admin_ajax_request' ),
				'notifications'      => td_nm_get_notifications(),
				'email_services'     => $this->get_email_services(),
			);

			wp_localize_script( 'td-nm-admin', 'TD_NM', $params );
		}
	}

	/**
	 * Hook into based on current screen
	 */
	public function conditional_hooks() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		/**
		 * Main Dashboard section
		 */
		if ( $screen->id === 'toplevel_page_tve_dash_section' ) {
			add_filter( 'tve_dash_filter_features', array( $this, 'admin_notification_feature' ) );
			add_filter( 'tve_dash_features', array( $this, 'admin_enable_feature' ) );
		}

		/**
		 * NM Dashboard
		 */
		if ( $screen->id === 'admin_page_tve_dash_notification_manager' ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_backbone_templates' ) );
		}
	}

	/**
	 * Push in the Notification Manager Feature to be displayed on Dashboard Thrive Features Section
	 *
	 * @param $features
	 *
	 * @return mixed
	 */
	public function admin_notification_feature( $features ) {
		$features['notification_manager'] = array(
			'icon'        => 'tvd-icon-notification',
			'title'       => 'Notification Manager',
			'description' => __( 'Receive notifications when certain events occur on your site', TVE_DASH_TRANSLATE_DOMAIN ),
			'btn_link'    => add_query_arg( 'page', $this->_dashboard_page, admin_url( 'admin.php' ) ),
			'btn_text'    => __( "Manage Notifications", TVE_DASH_TRANSLATE_DOMAIN ),
		);

		return $features;
	}

	/**
	 * Enable the NM feature to be displayed on Thrive Features Section
	 *
	 * @param $features
	 *
	 * @return mixed
	 */
	public function admin_enable_feature( $features ) {

		$features['notification_manager'] = true;

		return $features;
	}

	/**
	 * Add page to admin menu so the page could be accessed
	 */
	public function admin_menu() {
		add_submenu_page( null, __( 'Notification Manager', TVE_DASH_TRANSLATE_DOMAIN ), __( 'Notification Manager', TVE_DASH_TRANSLATE_DOMAIN ), 'manage_options', $this->_dashboard_page, array(
			$this,
			'admin_dashboard'
		) );
	}

	/**
	 * Main TD_NM page content
	 */
	public function admin_dashboard() {
		ob_start(); ?>
		<div id="tvd-nm-header"></div>
		<div class="tvd-nm-breadcrumbs-wrapper" id="tvd-nm-breadcrumbs-wrapper"></div>
		<div id="tvd-nm-wrapper"></div><?php
		echo ob_get_clean();
	}

	/**
	 * Append the backbone templates to source to be later used by Backbone scripts
	 */
	public function admin_backbone_templates() {
		$templates = tve_dash_get_backbone_templates( TD_NM()->path( 'includes/admin/views/backbone' ) );
		tve_dash_output_backbone_templates( $templates );
	}

	/**
	 * Get TL Groups if the plugin/function is available
	 *
	 * @return array
	 */
	public function get_tl_groups() {
		$db = function_exists( 'tve_leads_get_groups' ) ? tve_leads_get_groups() : array();

		$items = array();

		foreach ( $db as $post ) {
			$items[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
			);
		}

		return $items;
	}

	/**
	 * Get TL Shortcodes if the plugin/function is available
	 *
	 * @return array
	 */
	public function get_tl_shortcodes() {
		$db = function_exists( 'tve_leads_get_shortcodes' ) ? tve_leads_get_shortcodes() : array();

		$items = array();

		foreach ( $db as $post ) {
			$items[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
			);
		}

		return $items;
	}

	/**
	 * Get TL ThriveBoxes if the plugin/function is available
	 *
	 * @return array
	 */
	public function get_tl_thrive_boxes() {
		$db = function_exists( 'tve_leads_get_two_step_lightboxes' ) ? tve_leads_get_two_step_lightboxes() : array();

		$items = array();

		foreach ( $db as $post ) {
			$items[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
			);
		}

		return $items;
	}

	public function get_split_tests() {

		/** @var $tvedb Thrive_Leads_DB */
		global $tvedb;

		/** @var $tvedb Tho_Db */
		global $thodb;

		$tests = array(
			'tl'  => array(),
			'tho' => array(),
		);

		if ( $tvedb ) {
			$lead_tests = $tvedb->tve_leads_get_tests( array(
				'status' => defined( 'TVE_LEADS_STATUS_RUNNING' ) ? TVE_LEADS_STATUS_RUNNING : 'running'
			) );

			foreach ( $lead_tests as $test ) {
				$tests['tl'][] = array(
					'value' => $test->id,
					'label' => $test->title,
				);
			}
		}

		if ( $thodb ) {
			$headline_tests = $thodb->get_tests( array( 'status' => THO_TEST_STATUS_ACTIVE ) );

			foreach ( $headline_tests as $test ) {
				$tests['tho'][] = array(
					'value' => $test->id,
					'label' => get_the_title( $test->post_id ),
				);
			}
		}

		return $tests;
	}

	public function get_email_services() {
		$email_services     = Thrive_Dash_List_Manager::getAvailableAPIsByType( false, array( 'email' ) );
		$connected_services = Thrive_Dash_List_Manager::getAvailableAPIsByType( true, array( 'email' ) );
		$connected_keys     = array_keys( $connected_services );

		$active_connection = get_option( 'tvd-nm-email-service' );

		$items = array();

		/**
		 * @var string $key
		 * @var Thrive_Dash_List_Connection_Abstract $instance
		 */
		foreach ( $email_services as $key => $instance ) {
			$item = array(
				'key'       => $key,
				'title'     => $instance->getTitle(),
				'connected' => in_array( $key, $connected_keys ),
				'active'    => $key === $active_connection,
				'status'    => in_array( $key, $connected_keys ) ? __( 'connected', TVE_DASH_TRANSLATE_DOMAIN ) : __( 'Unset', TVE_DASH_TRANSLATE_DOMAIN ),
			);

			$items[] = $item;
		}

		return $items;
	}

	/**
	 *  Displays WordPress notifications
	 */
	public function td_nm_get_wordpress_notifications() {
		global $wpdb;
		$results = $wpdb->get_results( "select post_id from $wpdb->postmeta where meta_key = 'td_nm_wordpress_notification'", ARRAY_A );
		if ( ! empty( $results ) ) {
			/*Enqueue Script*/
			wp_enqueue_script( 'tve-nm-wordpress-notification-js', TD_NM()->url( 'assets/js/admin/wordpress_notification.js' ) );
			$thrive_nm_special_routes = array(
				'nonce'  => wp_create_nonce( 'td_nm_admin_ajax_request' ),
				'routes' => array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				),
			);
			wp_localize_script( 'tve-nm-wordpress-notification-js', 'ThriveNMWordpressNotification', $thrive_nm_special_routes );


			foreach ( $results as $post ) {
				$post_meta_value = get_post_meta( $post['post_id'], 'td_nm_wordpress_notification', true );
				printf( '<div data-key="%1$s" class="%2$s"><p>%3$s</p></div>', $post['post_id'], 'notice notice-success td_nm_wordpress_notice is-dismissible', $post_meta_value['message'] );
			}
		}
	}
}

return new TD_NM_Admin();
