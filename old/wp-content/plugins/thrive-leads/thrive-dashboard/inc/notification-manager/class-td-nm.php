<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

if ( ! class_exists( 'TD_NM' ) ) :

	final class TD_NM {

		/**
		 * The single instance of the class
		 *
		 * @var TD_NM
		 */
		protected static $_instance;

		/**
		 * @var TD_NM_Data
		 */
		public $data = null;

		public $checker = null;

		/**
		 * Tve_Dash_Notification_Manager constructor.
		 */
		protected function __construct() {
			$this->includes();
			$this->init_hooks();
		}

		/**
		 * Main instance of Notification Manager
		 *
		 * Ensure only one instance of Notification Manager is loaded or can be loaded
		 *
		 * @return TD_NM
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Hook into actions and filters
		 */
		protected function init_hooks() {
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Includes required core files used in admin and on the frontend.
		 */
		protected function includes() {

			include_once( 'includes/class-td-nm-ajax.php' ); //ajax manager for all ajax requests
			include_once( 'includes/td-nm-core-functions.php' ); //functions file
			include_once( 'includes/class-td-nm-data.php' ); //data manager for custom db tables
			include_once( 'includes/class-td-nm-post-types.php' ); //register post types
			include_once( 'includes/actions/class-nm-action-abstract.php' );
			include_once( 'includes/actions/class-nm-action-custom-script.php' );
			include_once( 'includes/actions/class-nm-action-send-email-notification.php' );
			include_once( 'includes/actions/class-nm-action-wordpress-notification.php' );
			include_once( 'includes/triggers/class-nm-trigger-abstract.php' );
			include_once( 'includes/triggers/class-nm-trigger-email-sign-up.php' );
			include_once( 'includes/triggers/class-nm-trigger-split-test-ends.php' );
			include_once( 'includes/class-td-nm-checker.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'includes/admin/class-td-nm-admin.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {

			}
		}

		/**
		 * Hook that init the TD_NM
		 */
		public function init() {
			$this->data    = new TD_NM_Data();
			$this->checker = TD_NM_Checker::instance();
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		protected function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * NM path with appended file if passed as parameter
		 *
		 * @param string $file
		 *
		 * @return string
		 */
		public function path( $file = '' ) {
			return untrailingslashit( plugin_dir_path( __FILE__ ) ) . ( ! empty( $file ) ? '/' : '' ) . ltrim( $file, '\\/' );
		}

		/**
		 * NM url with appended file if passed as parameter
		 *
		 * @param string $file
		 *
		 * @return string
		 */
		public function url( $file = '' ) {
			return untrailingslashit( TVE_DASH_URL ) . '/inc/notification-manager' . ( ! empty( $file ) ? '/' : '' ) . ltrim( $file, '\\/' );
		}

		public function get_trigger_types() {
			$types = apply_filters( 'td_nm_trigger_types', array() );

			$triggers = array();

			foreach ( $types as $key => $label ) {
				$model = array(
					'key'   => $key,
					'label' => $label,
					'icon'  => '',
				);

				if ( $key === 'email_sign_up' ) {
					$model['icon'] = 'tvd-nm-icon-envelope-o';
				} else if ( $key === 'split_test_ends' ) {
					$model['icon'] = 'tvd-nm-icon-flask';
				}

				$triggers[] = $model;
			}

			return $triggers;
		}

		public function get_action_types() {

			$actions = array(
				array(
					'key'   => 'send_email_notification',
					'label' => __( 'Send Email Notification', TVE_DASH_TRANSLATE_DOMAIN ),
					'icon'  => 'tvd-nm-icon-envelope-o'
				),
				array(
					'key'   => 'custom_script',
					'label' => __( 'Call a Custom Script', TVE_DASH_TRANSLATE_DOMAIN ),
					'icon'  => 'tvd-nm-icon-code'
				),
				array(
					'key'   => 'wordpress_notification',
					'label' => __( 'WordPress Notification', TVE_DASH_TRANSLATE_DOMAIN ),
					'icon'  => 'tvd-nm-icon-wordpress'
				),
			);

			return $actions;
		}

		/**
		 * List of shortcodes used for creating messages that will be sent to users
		 *
		 * @return array
		 */
		public function get_message_shortcodes() {
			return array(
				'email_sign_up'   => array(
					'send_email_notification' => array(
						array(
							'shortcode' => '[lead_details]',
							'label'     => __( 'Displays the name of your opt-in offer', TVE_DASH_TRANSLATE_DOMAIN ),
						),
						array(
							'shortcode' => '[lead_email]',
							'label'     => __( 'Displays the email address of the new lead', TVE_DASH_TRANSLATE_DOMAIN ),
						)
					),
					'wordpress_notification'  => array(
						array(
							'shortcode' => '[link]Lead Form[/link]',
							'label'     => __( 'Hyperlink that links to the lead reporting reporting screen.', TVE_DASH_TRANSLATE_DOMAIN ),
						),
					)
				),
				'split_test_ends' => array(
					'send_email_notification' => array(
						array(
							'shortcode' => '[test_link]Test Reports[/test_link]',
							'label'     => __( 'Hyperlink that links to the A/B test screen', TVE_DASH_TRANSLATE_DOMAIN ),
						)
					),
					'wordpress_notification'  => array(
						array(
							'shortcode' => '[link]Test Reports[/link]',
							'label'     => __( 'Hyperlink that links to the A/B test screen', TVE_DASH_TRANSLATE_DOMAIN ),
						),
					),
				),
			);
		}
	}

endif;

function TD_NM() {
	return TD_NM::instance();
}

$GLOBALS['TD_NM'] = TD_NM();
