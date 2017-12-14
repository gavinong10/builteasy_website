<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 11.08.2014
 * Time: 15:24
 */

/**
 * represents logic for an event Trigger
 * Class TCB_Event_Trigger_Abstract
 *
 * Each Event Trigger must override this class
 */
abstract class TCB_Event_Trigger_Abstract {
	/**
	 * holds the configuration for the selected trigger
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * should return the Event name
	 * @return mixed
	 */
	public abstract function getName();

	/**
	 * convenience method to display the name of this Event Trigger. It allows statements like echo $trigger;
	 * @return mixed
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * instantiate a new Event Trigger class based on $class_name
	 *
	 * if a class does not exist, it tries to automatically include it from within the /actions folder
	 *
	 * if it's a third-party API - added trigger, the class MUST previously exist
	 *
	 * @param string $class_name
	 * @param mixed $constructor_param optional argument to call the class constructor with
	 *
	 * @return TCB_Event_Trigger_Abstract
	 */
	public static final function triggerFactory( $class_name, $constructor_param = null ) {
		if ( ! class_exists( $class_name ) && file_exists( dirname( __FILE__ ) . '/triggers/' . $class_name . '.php' ) ) {
			require_once dirname( __FILE__ ) . '/triggers/' . $class_name . '.php';
		}

		if ( ! class_exists( $class_name ) ) {
			throw new Exception( 'TCB Event Trigger factory: Could not find ' . $class_name );
		}

		return new $class_name( $constructor_param );
	}

	/**
	 * this will only be called ONCE if this trigger is encountered in the element's settings, and it should output all the necessary
	 * preparation javascript, such as helpers etc for this action
	 * It should use namespaces and WP naming conventions, we don't want to mess up the global namespace with these
	 *
	 * @return mixed
	 */
	public function outputGlobalJavascript() {

	}

	/**
	 * called for each instance of the trigger that's registered in the Event Manager
	 * it just needs to return a piece of javascript code that will trigger the event, if this is the case
	 *
	 * and example: Timer triggers -> need to trigger the event after a delay
	 */
	public function getInstanceJavascript( $config ) {
		return '';
	}

	/**
	 * set the current configuration, if any
	 *
	 * @param $data
	 *
	 * @return $this
	 */
	public function setConfig( $config ) {
		$this->config = $config;

		return $this;
	}

	/**
	 * render individual settings for a trigger (if it applies)
	 *
	 * @param $data
	 */
	public function renderSettings( $data ) {
		return '';
	}

	/**
	 * used only by TCB, it will render a view from /settings folder
	 *
	 * @param string $view view file to be rendered
	 * @param mixed $data data to make available in the view
	 */
	protected final function renderTCBSettings( $view, $data ) {
		if ( substr( $view, - 4 ) !== '.php' ) {
			$view .= '.php';
		}
		$file = dirname( dirname( __FILE__ ) ) . '/views/settings/triggers/' . $view;

		if ( ! file_exists( $file ) ) {
			exit( 'No settings found' );
		}

		$this->data = $data;
		ob_start();
		include $file;
		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}
} 