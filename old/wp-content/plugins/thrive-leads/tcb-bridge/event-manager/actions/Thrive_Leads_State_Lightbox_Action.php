<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 05.08.2014
 * Time: 14:35
 */

if ( ! class_exists( 'Thrive_Leads_State_Lightbox_Action' ) ) {
	if ( ! class_exists( 'TCB_Thrive_Lightbox' ) ) {
		require_once TVE_TCB_ROOT_PATH . 'event-manager/classes/actions/TCB_Thrive_Lightbox.php';
	}

	/**
	 *
	 * handles the server-side logic for the Thrive Lightbox action = opens a lightbox on an Event Trigger
	 *
	 * Class TCB_Thrive_Lightbox
	 */
	class Thrive_Leads_State_Lightbox_Action extends TCB_Thrive_Lightbox {

		public static $LOADED_STATES = array();

		protected static $SAVED_CONTENT = array();

		/**
		 * name differs when we are in a lightbox state (child) and we need to change to another lightbox state
		 * the names turns into "Switch Lightbox State"
		 * @var string
		 */
		public static $ACTION_NAME = '';

		/**
		 * scope differs when we are in a lightbox state (child) and we need to change to another lightbox state
		 * @var string
		 */
		public static $ACTION_SCOPE = 'open_lightbox';

		/**
		 * holds all .state-root <div>s for each variation
		 *
		 * @var array
		 */
		private static $FOOTER_HTML = array();

		/**
		 * only load the "Open State Lightbox" Action if the parent form type is != lightbox
		 * @return boolean
		 */
		public function isAvailable() {
			global $variation;
			if ( empty( $variation ) ) {
				return false;
			}

			$parent_form_type = get_post_meta( $variation['post_parent'], 'tve_form_type', true );
			$parent_form_type = Thrive_Leads_Template_Manager::tpl_type_map( $parent_form_type );

			return $parent_form_type != 'lightbox' && $parent_form_type != 'screen_filler';
		}

		/**
		 * Should return the user-friendly name for this Action
		 *
		 * @return string
		 */
		public function getName() {
			return self::$ACTION_NAME ? self::$ACTION_NAME : __( 'Open lightbox state', 'thrive-leads' );
		}

		/**
		 * Should output the settings needed for this Action when a user selects it from the list
		 *
		 * @param mixed $data
		 *
		 * @return string the full html for the settings view
		 */
		public function renderSettings( $data ) {
			$all = tve_leads_get_form_related_states( $_POST['_key'] );

			$states = array();
			foreach ( $all as $state ) {
				if ( $state['key'] == $_POST['_key'] ) {
					continue;
				}
				if ( $state['form_state'] == 'lightbox' ) {
					$states[ $state['key'] ] = $state;
				}
			}

			$title = self::$ACTION_SCOPE == 'switch_state' ? __( 'Switch lightbox state', 'thrive-leads' ) : __( 'Open lightbox state settings', 'thrive-leads' );

			$data['states']             = $states;
			$data['animations']         = tve_leads_get_available_animations();
			$data['animation_settings'] = self::$ACTION_SCOPE != 'switch_state';
			$this->data                 = $data;
			ob_start();
			include dirname( dirname( __FILE__ ) ) . '/views/state-lightbox-settings.php';
			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}

		/**
		 * this will just trigger a click on the container that holds the 2-step trigger
		 * @return string
		 */
		public function getJsActionCallback() {
			return 'function (t, a, c) {
                var $this = ThriveGlobal.$j(this);
                if ($this.parents(".tve_post_lightbox").length) {
                    var current = ThriveGlobal.$j(this).parents(".tl-style").first();
                    var root = current.parents(".tl-states-root").first()
                    var container = root.find("[data-state=" + c.s + "]");
                    if (!container.length) {
                        return false;
                    }
                    current.hide();
                    container.show();
                    root.trigger("switchstate", [container, current]);
                    return false;
                }
                TL_Front.parent_state = $this.parents(".tl-style").first();
                var evtData = {form_type: "lightbox", $target: ThriveGlobal.$j(".tl-style[data-state=" + c.s + "] .tve_p_lb_content").parent()};ThriveGlobal.$j(TL_Front).trigger("showform.thriveleads", evtData);return false;
            }';
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

			$animation = ! empty( $config['a'] ) && in_array( $config['a'], TVE_Leads_Animation_Abstract::$AVAILABLE ) ? $config['a'] : TVE_Leads_Animation_Abstract::ANIM_INSTANT;

			return '; Animation: ' . TVE_Leads_Animation_Abstract::factory( $animation )->get_display_name();
		}

		/**
		 * output edit links for the lightbox
		 */
		public function getRowActions() {
			if ( empty( $this->config ) ) {
				return '';
			}

			return sprintf(
				'<br><a href="javascript:void(0)" data-ctrl="function:ext.tve_leads.state.state_click" data-id="%s" class="tve_click tve_link_no_warning">%s</a>',
				$this->config['s'],
				__( 'Edit Lightbox State', 'thrive-leads' )
			);
		}

		/**
		 * check if the associated lightbox exists and it's not trashed
		 * @return bool
		 */
		public function validateConfig() {
			if ( empty( $this->config ) || empty( $this->config['s'] ) ) {
				return false;
			}

			global $variation;

			$state = tve_leads_get_form_variation( null, $this->config['s'] );

			if ( empty( $state ) || $state['form_state'] != 'lightbox' || $state['post_parent'] != $variation['post_parent'] ) {
				return false;
			}

			return true;
		}

		/**
		 * called inside the_content filter
		 * make sure that if custom icons are used, the CSS for that is included in the main page
		 * the same with Custom Fonts
		 *
		 * @param array $data configuration data
		 *
		 * @return string
		 */
		public function mainPostCallback( $data ) {
			$data = $data['config'];
			if ( empty( $data['s'] ) ) {
				return '';
			}
			if ( isset( self::$LOADED_STATES[ $data['s'] ] ) ) {
				return '';
			}

			$state = tve_leads_get_form_variation( null, $data['s'] );
			if ( empty( $state ) ) {
				return '';
			}

			if ( empty( $GLOBALS['tl_event_parse_variation'] ) ) {
				return '';
			}

			self::$LOADED_STATES[ $data['s'] ] = $state;
			tve_leads_enqueue_variation_scripts( $state );

			if ( ! empty( $data['a'] ) ) {
				$state['display_animation'] = $data['a'];
			}

			$params            = array(
				'wrap' => false,
			);
			$current_variation = end( $GLOBALS['tl_event_parse_variation'] );
			$current_form_type = tve_leads_get_form_type_from_variation( $current_variation );
			/**
			 * if current variation is a lightbox, then this action will be changed to "switch state"
			 */
			if ( $current_form_type == 'lightbox' ) {
				$params = array(
					'wrap'       => false,
					'hide'       => true,
					'hide_inner' => false,
					'animation'  => false
				);
			}

			if ( empty( self::$FOOTER_HTML[ $state['parent_id'] ] ) ) {
				self::$FOOTER_HTML[ $state['parent_id'] ]['root']   = '<div class="tl-states-root tl-anim-' . $state['display_animation'] . '">';
				self::$FOOTER_HTML[ $state['parent_id'] ]['states'] = array();
			}
			/**
			 * if the lightbox is opened from a non-lightbox state we need to wrap the lightbox in a div
			 */
			if ( $current_form_type != 'lightbox' ) {
				$params['wrap_tl_style']                               = true;
				self::$FOOTER_HTML[ $state['parent_id'] ]['states'] [] = tve_leads_display_form_lightbox( '__return_content', tve_editor_custom_content( $state ), $state, null, null, $params );
			} else {
				array_unshift( self::$FOOTER_HTML[ $state['parent_id'] ]['states'], tve_leads_display_form_lightbox( '__return_content', tve_editor_custom_content( $state ), $state, null, null, $params ) );
			}

			remove_filter( 'tve_leads_append_states_ajax', array( 'Thrive_Leads_State_Lightbox_Action', 'ajax_output_states' ), 10 );
			add_filter( 'tve_leads_append_states_ajax', array( 'Thrive_Leads_State_Lightbox_Action', 'ajax_output_states' ), 10, 2 );

		}

		/**
		 * we just display a hidden element that holds the lightbox
		 *
		 * @param $data
		 *
		 * @return string
		 */
		public function applyContentFilter( $data ) {
			$out = '';
			foreach ( self::$FOOTER_HTML as $parts ) {
				$out .= $parts['root'] . implode( '', $parts['states'] ) . '</div>';
			}
			/**
			 * output it just once
			 */
			self::$FOOTER_HTML = array();

			return $out;
		}

		/**
		 * @param array $output_variations
		 *
		 * @return array
		 *
		 * @see tve_leads_ajax_load_forms
		 */
		public static function ajax_output_states( $output_variations ) {
			foreach ( self::$LOADED_STATES as $id => $v ) {
				if ( is_array( $v ) ) {
					$output_variations [] = $v;
				}
			}

			return $output_variations;
		}

	}
}