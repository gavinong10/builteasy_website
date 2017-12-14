<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 11.08.2014
 * Time: 16:03
 */
class TCB_Thrive_CSS_Animation extends TCB_Event_Action_Abstract {

	/**
	 * available CSS animations
	 * @var array
	 */
	protected $_animations = array(
		'slide_top'    => 'Top to bottom',
		'slide_bottom' => 'Bottom to top',
		'slide_left'   => 'Left to right',
		'slide_right'  => 'Right to left',
		'appear'       => 'Appear from Centre (Zoom In)',
		'zoom_out'     => 'Zoom Out',
		'fade'         => 'Fade in',
		'rotate'       => 'Rotational',
		'roll_in'      => 'Roll In',
		'roll_out'     => 'Roll Out'
	);

	/**
	 * Should return the user-friendly name for this Action
	 *
	 * @return string
	 */
	public function getName() {
		return 'Animation';
	}

	/**
	 * Should output the settings needed for this Action when a user selects it from the list
	 *
	 * @param mixed $data existing configuration data, etc
	 */
	public function renderSettings( $data ) {
		return $this->renderTCBSettings( 'animation', $data );
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
		$classes = array();
		foreach ( array_keys( $this->_animations ) as $anim ) {
			$classes [] = 'tve_anim_' . $anim;
		}
		$classes = implode( ' ', $classes );

		return 'function(trigger, action, config) {
            var $element = jQuery(this),
                $at = $element.closest(".thrv_wrapper");
            if ($at.length === 0) {
                $at = $element;
            }
            $at.removeClass("' . $classes . '").addClass("tve_anim_" + config.anim).removeClass("tve_anim_start");
            setTimeout(function () {
                $at.addClass("tve_anim_start");
            }, 50);
            return false;
        }';
	}

	public function getSummary() {
		if ( ! empty( $this->config ) ) {
			return ': ' . $this->_animations[ $this->config['anim'] ];
		}
	}


} 