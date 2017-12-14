<?php
/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/18/2015
 * Time: 1:39 PM
 */

if ( ! class_exists( 'Thrive_Leads_Form_Close_Action' ) ) {

	class Thrive_Leads_Form_Close_Action extends TCB_Event_Action_Abstract {
		protected $form_types = array(
			'ribbon',
			'widget',
			'post_footer',
			'slide_in',
			'in_content',
			'php_insert',
			'shortcode',
			'greedy_ribbon',
		);

		/**
		 * Should return the user-friendly name for this Action
		 *
		 * @return string
		 */
		public function getName() {
			return __( 'Close', 'thrive-leads' );
		}

		/**
		 * should check if the current action is available to be displayed in the lists inside the event manager
		 * @return boolean
		 */
		public function isAvailable() {
			global $variation;
			if ( empty( $variation ) ) {
				return false;
			}

			$parent_form_type = get_post_meta( $variation['post_parent'], 'tve_form_type', true );
			$parent_form_type = Thrive_Leads_Template_Manager::tpl_type_map( $parent_form_type );

			return in_array( $parent_form_type, $this->form_types );
		}


		/**
		 * Should output the settings needed for this Action when a user selects it from the list
		 *
		 * @param mixed $data existing configuration data, etc
		 *
		 * @return string empty
		 */
		public function renderSettings( $data ) {
			return '';
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
		 * The function will be called in the context of the element
		 *
		 * The output MUST be a valid JS function definition.
		 *
		 * @return string the JS function definition (declaration + body)
		 */
		public function getJsActionCallback() {
			return 'function(t, a, c){TL_Front.close_form(this, t, a, c); return false;}';
		}

	}
}
