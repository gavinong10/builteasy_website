<?php

class TCB_Thrive_Tooltip extends TCB_Event_Action_Abstract {

	/**
	 * available tooltip positions
	 * @var array
	 */
	protected $_positions = array(
		'top'          => 'Top',
		'top_right'    => 'Top right',
		'right'        => 'Right',
		'bottom_right' => 'Bottom right',
		'bottom'       => 'Bottom',
		'bottom_left'  => 'Bottom left',
		'left'         => 'Left',
		'top_left'     => 'Top left',
	);

	/**
	 * available tooltip styles
	 * @var array
	 */
	protected $_styles = array(
		'light' => 'Light',
		'dark'  => 'Dark',
	);

	/**
	 * available tooltip text decorations
	 * @var array
	 */
	protected $_decorations = array(
		'solid'  => 'Solid',
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',

	);

	/**
	 * Should return the user-friendly name for this Action
	 *
	 * @return string
	 */
	public function getName() {
		return 'Tooltip';
	}

	/**
	 * Should output the settings needed for this Action when a user selects it from the list
	 *
	 * @param mixed $data existing configuration data, etc
	 */
	public function renderSettings( $data ) {
		return $this->renderTCBSettings( 'tooltip', $data );
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

		return 'function(trigger, action, config) {
             var tip, text, position,
				base = document.createElement( \'div\' );
				base.setAttribute( "class", "tve_ui_tooltip tve_tooltip_style_"+config.event_tooltip_style + " tve_tooltip_position_"+config.event_tooltip_position);
				text = config.event_tooltip_text;
				tip = document.createTextNode( text );
				if ( text != null ) {
					base.innerHTML = \'\';
					base.appendChild( tip );
					if ( document.getElementsByClassName( \'tve_ui_tooltip\' )[0] ) {
						document.getElementsByClassName( \'tve_ui_tooltip\' )[0].remove();
					}					
					document.body.appendChild( base );
				}
				var tooltip_width = base.offsetWidth,
					tooltip_height = base.offsetHeight,
					offset = 10,
					top = 0,
					left = 0;
				position = config.event_tooltip_position;
				var rect = this.getBoundingClientRect();
				switch(position) {
					case \'top\':
						left = (rect.right - rect.left - tooltip_width )/2 + rect.left;
						top = rect.top - tooltip_height - offset;
						break;
					case \'top_right\':
						left = rect.right + offset;
						top = rect.top - tooltip_height - offset;
						break;
					case \'right\':
						left = rect.right + offset;
						top = ( rect.bottom - rect.top - tooltip_height )/2 + rect.top;
						break;
					case \'bottom_right\':
						left = rect.right + offset;
						top = rect.bottom + offset;
						break;
					case \'bottom\':
						left = ( rect.right - rect.left - tooltip_width )/2 + rect.left;
						top = rect.bottom + offset;
						break;
					case \'bottom_left\':
						left = rect.left - tooltip_width - offset;
						top = rect.bottom + offset;
						break;
					case \'left\':
						left = rect.left - tooltip_width - offset;
						top = (rect.bottom -rect.top - tooltip_height )/2 + rect.top;
						break;
					case \'top_left\':
						left = rect.left - tooltip_width - offset;
						top = rect.top - tooltip_height - offset;
						break;
					default:
						left = 1;
						top = 1;
				}
				base.style.top = ( top ) + \'px\';
				base.style.left = ( left ) + \'px\';
				this.onmouseout = function () {
					if ( document.getElementsByClassName( \'tve_ui_tooltip\' )[0] ) {
						document.getElementsByClassName( \'tve_ui_tooltip\' )[0].remove();
					}
				};
            return true;
        }';
	}

	public function getSummary() {

	}

	/**
	 * should check if the current action is available to be displayed in the lists inside the event manager
	 * @return bool
	 */
	public function isAvailable() {

		return true;
	}

}