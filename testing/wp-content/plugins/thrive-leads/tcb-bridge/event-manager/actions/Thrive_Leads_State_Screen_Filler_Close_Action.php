<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 05.08.2014
 * Time: 14:35
 */

if ( ! class_exists( 'Thrive_Leads_State_Screen_Filler_Close_Action' ) ) {
	/**
	 *
	 * handles the server-side logic for the Thrive Lightbox action = opens a lightbox on an Event Trigger
	 *
	 * Class TCB_Thrive_Lightbox
	 */
	class Thrive_Leads_State_Screen_Filler_Close_Action extends TCB_Event_Action_Abstract {
		/**
		 * only load the "Close lightbox" action if we are dealing with a lightbox
		 * @return boolean
		 */
		public function isAvailable() {
			global $variation;
			if ( empty( $variation ) ) {
				return false;
			}

			$parent_form_type = get_post_meta( $variation['post_parent'], 'tve_form_type', true );
			$parent_form_type = Thrive_Leads_Template_Manager::tpl_type_map( $parent_form_type );

			if ( $parent_form_type == 'screen_filler' || $variation['form_state'] == 'screen_filler' ) {
				return true;
			}

			return false;
		}

		/**
		 * Should return the user-friendly name for this Action
		 *
		 * @return string
		 */
		public function getName() {
			return __( 'Close current screen filler', 'thrive-leads' );
		}

		/**
		 * Should output the settings needed for this Action when a user selects it from the list
		 *
		 * @param mixed $data
		 *
		 * @return string the full html for the settings view
		 */
		public function renderSettings( $data ) {
			return '';
		}

		/**
		 * this will just trigger a click on the container that holds the 2-step trigger
		 * @return string
		 */
		public function getJsActionCallback() {
			return 'function(){ThriveGlobal.$j(".tve-screen-filler-close").trigger("click");return false;}';
		}

		/**
		 *
		 * @param $data
		 *
		 * @return string
		 */
		public function applyContentFilter( $data ) {
			return '';
		}

	}
}