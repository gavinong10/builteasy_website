<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 28.08.2014
 * Time: 09:51
 */

require_once dirname( __FILE__ ) . '/Tve_Dash_Thrive_Icon_Manager_Data.php';
require_once dirname( __FILE__ ) . '/Tve_Dash_Thrive_Icon_Manager_View.php';

if ( ! class_exists( 'Tve_Dash_Thrive_Icon_Manager' ) ) {


	class Tve_Dash_Thrive_Icon_Manager {
		/**
		 * singleton instance
		 * @var Tve_Dash_Thrive_Icon_Manager
		 */
		protected static $instance = null;

		/**
		 * @var Tve_Dash_Thrive_Icon_Manager_View
		 */
		protected $view = null;

		/**
		 * success / error messages
		 * @var array
		 *
		 */
		protected $messages = array();

		/**
		 * singleton implementation
		 *
		 * @return Tve_Dash_Thrive_Icon_Manager
		 */
		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new Tve_Dash_Thrive_Icon_Manager();
			}

			return self::$instance;
		}

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->view = new Tve_Dash_Thrive_Icon_Manager_View( dirname( dirname( __FILE__ ) ) . '/views' );
		}

		/**
		 * main page displaying all options
		 */
		public function mainPage() {
			if ( ! empty( $_POST['tve_save_icon_pack'] ) ) {
				$this->handlePost();
			}

			$this->enqueue();

			$icon_pack = get_option( 'thrive_icon_pack' );

			if ( ! empty( $icon_pack['css'] ) ) {
				wp_enqueue_style( 'thrive_icon_pack', tve_dash_url_no_protocol( $icon_pack['css'] ), array(), $icon_pack['css_version'] );
			}

			$data = array(
				'icons'          => empty( $icon_pack ) ? array() : $icon_pack['icons'],
				'icon_pack_name' => empty( $icon_pack ) ? '' : $icon_pack['attachment_name'],
				'icon_pack_id'   => empty( $icon_pack ) ? '' : $icon_pack['attachment_id'],
				'variations'     => empty( $icon_pack ) || empty( $icon_pack['variations'] ) ? array() : $icon_pack['variations'],
				'messages'       => $this->messages
			);

			$this->view->render( 'main', $data );
		}

		/**
		 * handles the user-submitted data and redirects to the same Icon Manager page, with success / error messages
		 */
		public function handlePost() {
			$icon_pack     = get_option( 'thrive_icon_pack' );
			$new_icon_pack = array();
			$handler       = new Tve_Dash_Thrive_Icon_Manager_Data();

			if ( ! empty( $_POST['attachment_id'] ) ) {
				$maybe_zip_file = get_attached_file( $_POST['attachment_id'] );
				$maybe_zip_url  = wp_get_attachment_url( $_POST['attachment_id'] );

				try {
					$new_icon_pack = $handler->processZip( $maybe_zip_file, $maybe_zip_url );
					if ( ! empty( $icon_pack['folder'] ) && $icon_pack['folder'] != $new_icon_pack['folder'] ) {
						/* remove old folder */
						$old_handler = new Tve_Dash_Thrive_Icon_Manager_Data();
						$font_family = isset( $icon_pack['fontFamily'] ) ? $icon_pack['fontFamily'] : '';
						$old_handler->removeIcoMoonFolder( $icon_pack['folder'], $font_family );
					}
					$new_icon_pack['attachment_id']   = $_POST['attachment_id'];
					$new_icon_pack['attachment_name'] = basename( $maybe_zip_file );

					$success = __( 'New IcoMoon Font Pack installed. ', TVE_DASH_TRANSLATE_DOMAIN );

				} catch ( Exception $e ) {
					$this->messages['error'] = $e->getMessage();
				}
			} else { // remove existing IcoMoon font

				if ( ! empty( $icon_pack['folder'] ) ) {
					/* remove old folder */
					$old_handler = new Tve_Dash_Thrive_Icon_Manager_Data();
					$font_family = isset( $icon_pack['fontFamily'] ) ? $icon_pack['fontFamily'] : '';
					$old_handler->removeIcoMoonFolder( $icon_pack['folder'], $font_family );
				}

				$success = __( 'IcoMoon Font Pack has been removed. ', TVE_DASH_TRANSLATE_DOMAIN );
			}

			if ( isset( $success ) ) {

				update_option( 'thrive_icon_pack', $new_icon_pack );

				$this->messages['success']  = $success . 'You will be redirected to the previous page in <span id="tve-redirect-count">2</span> seconds.';
				$this->messages['redirect'] = admin_url( 'admin.php?page=tve_dash_icon_manager' );
			}
		}

		/**
		 * enqueue scripts and css files for the icon manger admin page
		 */
		protected function enqueue() {
			tve_dash_enqueue();
			wp_enqueue_media();
			tve_dash_enqueue_script( 'tve_dash_icon_manager_options', TVE_DASH_URL . '/inc/icon-manager/views/js/manager.js', array( 'jquery', 'media-upload', 'thickbox' ) );
			tve_dash_enqueue_style( 'tve_dash_icon_manager_style', TVE_DASH_URL . '/inc/icon-manager/views/css/manager.css' );
		}

	}
}
