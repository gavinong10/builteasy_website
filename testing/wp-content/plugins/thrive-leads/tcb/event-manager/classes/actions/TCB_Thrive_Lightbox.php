<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 05.08.2014
 * Time: 14:35
 */

if ( ! class_exists( 'TCB_Thrive_Lightbox' ) ) {
	/**
	 *
	 * handles the server-side logic for the Thrive Lightbox action = opens a lightbox on an Event Trigger
	 *
	 * Class TCB_Thrive_Lightbox
	 */
	class TCB_Thrive_Lightbox extends TCB_Event_Action_Abstract {
		/**
		 * holds all lightbox ids that have been rendered in the footer - this is to not render a lightbox twice
		 * @var array
		 */
		private static $_LOADED_LIGHTBOXES = array();

		/**
		 * holds all lightbox ids that have been parsed for events configuration - this is to not create an infinite loop in case of
		 * lightboxes used within lightboxes
		 * @var array
		 */
		private static $_LIGHBOXES_EVENTS_PARSED = array();

		/**
		 * available lightbox animations
		 * @var array
		 */
		protected $_animations = array(
			'instant'      => 'Instant (No animation)',
			'zoom_in'      => 'Zoom',
			'zoom_out'     => 'Zoom Out',
			'rotate'       => 'Rotational',
			'slide_top'    => 'Slide in from Top',
			'slide_bottom' => 'Slide in from Bottom',
			'lateral'      => 'Lateral'
		);

		/**
		 * Should return the user-friendly name for this Action
		 *
		 * @return string
		 */
		public function getName() {
			return 'Open Thrive Lightbox';
		}

		/**
		 * Should output the settings needed for this Action when a user selects it from the list
		 *
		 * @param mixed $data
		 */
		public function renderSettings( $data ) {
			$post_id               = $_POST['post_id'];
			$landing_page_template = tve_post_is_landing_page( $post_id );

			$all_lightboxes = get_posts( array(
				'posts_per_page' => - 1,
				'post_type'      => 'tcb_lightbox',
			) );

			$data['lightboxes'] = array();
			foreach ( $all_lightboxes as $lightbox ) {
				if ( $lightbox->ID == $post_id ) { // makes no sense to open the same lightbox from within itself
					continue;
				}
				$lightbox_lp = get_post_meta( $lightbox->ID, 'tve_lp_lightbox', true );
				if ( ! empty( $landing_page_template ) ) {
					if ( $lightbox_lp != $landing_page_template ) {
						continue;
					}
				} elseif ( ! empty( $lightbox_lp ) ) {
					continue;
				}
				$data['lightboxes'] [] = $lightbox;
			}
			/* we use this to display the user the possibility of creating a new lightbox */
			$data['for_landing_page'] = $landing_page_template;

			return $this->renderTCBSettings( 'lightbox', $data );
		}

		/**
		 * Should return an actual string containing the JS function that's handling this action.
		 * The function will be called with 3 parameters:
		 *      -> event_trigger (e.g. click, dblclick etc)
		 *      -> action_code (the action that's being executed)
		 *      -> config (specific configuration for each specific action - the same configuration that has been setup in the settings section)
		 *
		 * Example (php): return 'function (trigger, action, config) { console.log(trigger, action, config); }';
		 *
		 * The output MUST be a valid JS function definition.
		 *
		 * @return string the JS function definition (declaration + body)
		 */
		public function getJsActionCallback() {
			return 'function(t,a,c){var $t=jQuery("#tve_thrive_lightbox_"+c.l_id).css("display", ""),a=c.l_anim?c.l_anim:"instant";TCB_Front.openLightbox($t,a);return false;};';
		}

		/**
		 * makes all necessary changes to the content depending on the $data param
		 *
		 * this gets called each time this action is encountered in the DOM event configuration
		 *
		 * @param $data
		 */
		public function applyContentFilter( $data ) {
			$lightbox_id = isset( $data['config']['l_id'] ) ? intval( $data['config']['l_id'] ) : 0;

			if ( ! $lightbox_id ) {
				return false;
			}

			if ( isset( self::$_LOADED_LIGHTBOXES[ $lightbox_id ] ) ) {
				return self::$_LOADED_LIGHTBOXES[ $lightbox_id ];
			}

			$lightbox = get_post( $lightbox_id );
			if ( empty( $lightbox ) ) {
				return '';
			}

			global $post;
			$old_post                          = $post;
			$GLOBALS['tcb_main_post_lightbox'] = $old_post;
			$post                              = $lightbox;

			/**
			 * this if was added for TU Main Ajax request, the the html must be returned
			 */
			if ( ! has_filter( 'the_content', 'tve_editor_content' ) ) {
				add_filter( 'the_content', 'tve_editor_content' );
			}

			$lightbox_content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $lightbox->post_content ) );
			$config           = tve_get_lightbox_globals( $lightbox->ID );

			$lightbox                                 = sprintf(
				'<div style="display: none" id="tve_thrive_lightbox_%s"><div class="tve_p_lb_overlay" data-style="%s" style="%s"%s></div>' .
				'<div class="tve_p_lb_content bSe cnt%s tcb-lp-lb" style="%s"%s><div class="tve_p_lb_inner" id="tve-p-scroller" style="%s"><article>%s</article></div>' .
				'<a href="javascript:void(0)" class="tve_p_lb_close%s" style="%s"%s title="Close">x</a></div></div>',
				$lightbox_id,
				$config['overlay']['css'],
				$config['overlay']['css'],
				$config['overlay']['custom_color'],
				$config['content']['class'],
				$config['content']['css'],
				$config['content']['custom_color'],
				$config['inner']['css'],
				$lightbox_content,
				$config['close']['class'],
				$config['close']['css'],
				$config['content']['custom_color']
			);
			$post                                     = $old_post;
			self::$_LOADED_LIGHTBOXES[ $lightbox_id ] = $lightbox;

			return $lightbox;
		}

		/**
		 * output the main options for this lightbox (in the editor events list)
		 * @return string
		 */
		public function getSummary() {
			$config = $this->config;
			if ( empty( $config ) ) {
				return '';
			}

			$animation = empty( $config['l_anim'] ) ? $this->_animations['instant'] : $this->_animations[ $config['l_anim'] ];

			return '; Animation: ' . $animation;
		}

		/**
		 * output edit links for the lightbox
		 */
		public function getRowActions() {
			if ( empty( $this->config ) ) {
				return '';
			}

			return sprintf(
				'<br><a href="%s" title="Edit this Lightbox" target="_blank" class="tve_link_no_warning tve_lightbox_link tve_lightbox_link_edit">Edit this Lightbox</a>',
				tcb_get_editor_url( $this->config['l_id'] )
			);
		}

		/**
		 * check if the associated lightbox exists and it's not trashed
		 * @return bool
		 */
		public function validateConfig() {
			$lightbox_id = $this->config['l_id'];
			if ( empty( $lightbox_id ) ) {
				return false;
			}

			$lightbox = get_post( $lightbox_id );
			if ( empty( $lightbox ) || $lightbox->post_status === 'trash' || $lightbox->post_type != 'tcb_lightbox' ) {
				return false;
			}

			return true;
		}

		/**
		 * make sure that if custom icons are used, the CSS for that is included in the main page
		 * the same with Custom Fonts
		 *
		 * @param array $data
		 */
		public function mainPostCallback( $data ) {

			$lightbox_id = empty( $data['config']['l_id'] ) ? 0 : $data['config']['l_id'];
			if ( isset( self::$_LIGHBOXES_EVENTS_PARSED[ $lightbox_id ] ) ) {
				return;
			}
			self::$_LIGHBOXES_EVENTS_PARSED[ $lightbox_id ] = true;
			if ( tve_get_post_meta( $lightbox_id, 'thrive_icon_pack' ) && ! wp_style_is( 'thrive_icon_pack', 'enqueued' ) ) {
				tve_enqueue_icon_pack();
			}

			tve_enqueue_extra_resources( $lightbox_id );

			/* check for the lightbox style and include it */
			tve_enqueue_style_family( $lightbox_id );

			tve_enqueue_custom_fonts( $lightbox_id, true );

			/* output any css needed for the extra (imported) fonts */
			if ( function_exists( 'tve_output_extra_custom_fonts_css' ) ) {
				tve_output_extra_custom_fonts_css( $lightbox_id );
			}

			$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

			if ( tve_get_post_meta( $lightbox_id, 'tve_has_masonry' ) ) {
				wp_script_is( "jquery-masonry" ) || wp_enqueue_script( "jquery-masonry", array( 'jquery' ) );
			}

			if ( tve_get_post_meta( $lightbox_id, 'tve_has_typefocus' ) ) {
				wp_script_is( "tve_typed" ) || wp_enqueue_script( "tve_typed", tve_editor_js() . '/typed' . $js_suffix, array( 'tve_frontend' ) );
			}

			$lightbox_content = get_post_meta( $lightbox_id, 'tve_updated_post', true );
			tve_parse_events( $lightbox_content );

			$globals = tve_get_post_meta( $lightbox_id, 'tve_globals' );
			if ( ! empty( $globals['js_sdk'] ) ) {
				foreach ( $globals['js_sdk'] as $handle ) {
					wp_script_is( 'tve_js_sdk_' . $handle ) || wp_enqueue_script( 'tve_js_sdk_' . $handle, tve_social_get_sdk_link( $handle ), array(), false );
				}
			}
		}

	}
}