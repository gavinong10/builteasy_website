<?php

/**
 * Structure for all the available triggers
 */
abstract class TVE_Leads_Trigger_Abstract {
	public static $AVAILABLE = array(
		'page_load',
		'time',
		'scroll_element',
		'scroll_percent',
		'click',
		'exit_intent',
		'viewport',
		'page_bottom'
	);

	protected $default_config = array();

	protected $config = array();

	/**
	 * all form types that this trigger applies to
	 * @var array
	 */
	protected $applies_to = array();

	/**
	 * @var string title to be displayed
	 */
	protected $title = '';

	/**
	 * base dir path for the plugin
	 *
	 * @var string
	 */
	protected $base_dir = '';

	/**
	 * @param $type
	 * @param $config array
	 *
	 * @return TVE_Leads_Trigger_Abstract
	 */
	public static function factory( $type, $config = array() ) {
		$parts = explode( '_', $type );

		$class = 'TVE_Leads_Trigger';
		foreach ( $parts as $part ) {
			$class .= '_' . ucfirst( $part );
		}

		if ( ! class_exists( $class ) ) {
			return null;
		}

		return new $class( $config );
	}

	/**
	 * merge the received config with the defaults
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = array_merge( $this->default_config, $config );

		$this->base_dir = plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * setter for the configuration array
	 *
	 * @param array $config
	 *
	 * @return TVE_Leads_Trigger_Abstract allow chained calls
	 */
	public function set_config( $config ) {
		foreach ( $config as $k => $v ) {
			$this->config[ $k ] = $v;
		}

		return $this;
	}

	/**
	 *
	 * whether or not this trigger applies to a form type
	 *
	 * @param string $form_type
	 *
	 * @return bool
	 */
	public function appliesTo( $form_type ) {
		if ( is_string( $this->applies_to ) && $this->applies_to == '*' ) {
			return true;
		}

		return in_array( $form_type, $this->applies_to );
	}

	/**
	 * get the title
	 */
	public abstract function get_title();

	/**
	 * prepare data to be used in JS
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'title'  => $this->get_title(),
			'key'    => $this->key,
			'config' => $this->config
		);
	}

	/**
	 * output settings that (might) be required for this trigger
	 */
	public function output_settings() {
		$path = dirname( dirname( __FILE__ ) ) . '/admin/views/trigger_settings/' . $this->key . '.phtml';
		if ( is_file( $path ) ) {
			$config = $this->config;
			include $path;
		}
	}

	/**
	 * output javascript required for the trigger, if the case applies
	 *
	 * renders directly JS code, without returning it
	 *
	 * @param $data - this should usually be the variation
	 */
	public function output_js( $data ) {
		if ( is_file( $this->base_dir . 'js/triggers/' . $this->key . '.js.php' ) ) {
			include $this->base_dir . 'js/triggers/' . $this->key . '.js.php';
		}
	}

	/**
	 * parse a CSS selector, making sure it's compliant
	 *
	 * @param $raw
	 *
	 * @return String
	 */
	protected function parse_selector( $raw, $prefix = '.' ) {
		$selector = '';
		$raw      = str_replace( array( '#', '.' ), '', $raw );

		$parts = explode( ',', $raw );
		foreach ( $parts as $part ) {
			$selector .= ( $selector ? ',' : '' ) . $prefix . $part;
		}

		return trim( $selector, ', ' );
	}

	/**
	 * get the human-friendly trigger name (and also include the configuration settings)
	 *
	 * @return string
	 */
	public function get_display_name() {
		return $this->get_title();
	}
}

/**
 * Page Load trigger - the simplest version of a trigger
 *
 * Class TVE_Leads_Trigger_Page_Load
 */
class TVE_Leads_Trigger_Page_Load extends TVE_Leads_Trigger_Abstract {
	protected $key = 'page_load';

	protected $applies_to = '*';

	public function get_display_name() {
		return __( 'Displays immediately on page load', 'thrive-leads' );
	}

	public function get_title() {
		return __( 'Show on page load', 'thrive-leads' );
	}


}

/**
 * Show after a period of time
 *
 * Class TVE_Leads_Trigger_Time
 */
class TVE_Leads_Trigger_Time extends TVE_Leads_Trigger_Abstract {
	protected $key = 'time';

	protected $applies_to = array(
		'ribbon',
		'lightbox',
		'slide_in',
		'screen_filler',
		'shortcode'
	);

	protected $default_config = array(
		's'   => 10,
		'exi' => '', // trigger this form on exit intent if the visitor attempts to leave the site before the set amount of time passes
	);

	public function get_display_name() {
		$name = sprintf( _n( 'Displays after %s second', 'Displays after %s seconds', $this->config['s'], 'thrive-leads' ), $this->config['s'] );

		if ( ! empty( $this->config['exi'] ) ) {
			$name .= ' ' . __( '(exit intent before that)', 'thrive-leads' );
		}

		return $name;
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show after a certain period of time', 'thrive-leads' );
	}

}

/**
 * Show after the user scroll a specific percentage of the page
 *
 * Class TVE_Leads_Trigger_Scroll_Percent
 */
class TVE_Leads_Trigger_Scroll_Percent extends TVE_Leads_Trigger_Abstract {
	protected $key = 'scroll_percent';

	protected $applies_to = array(
		'lightbox',
		'slide_in',
		'screen_filler',
		'ribbon'
	);

	protected $default_config = array(
		'p'   => 10,
		'exi' => '', // trigger this form if on exit intent before the defined scroll depth is reached
	);

	public function get_display_name() {
		$name = sprintf( __( 'Displays when content scrolled to %s%%', 'thrive-leads' ), $this->config['p'] );

		if ( ! empty( $this->config['exi'] ) ) {
			$name .= ' ' . __( '(exit intent before that)', 'thrive-leads' );
		}

		return $name;
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when the user scrolls to a percentage of the way down the content', 'thrive-leads' );
	}

}

/**
 * Show when a user scrolls to a specific element
 *
 * Class TVE_Leads_Trigger_Scroll_Element
 */
class TVE_Leads_Trigger_Scroll_Element extends TVE_Leads_Trigger_Abstract {
	protected $key = 'scroll_element';

	protected $applies_to = array(
		'lightbox',
		'slide_in',
		'screen_filler'
	);

	public function output_js( $data ) {
		$selector = '';
		if ( ! empty( $this->config['i'] ) ) {
			$selector .= $this->parse_selector( $this->config['i'], '#' );
		}
		if ( ! empty( $this->config['c'] ) ) {
			$selector .= ( $selector ? ',' : '' ) . $this->parse_selector( $this->config['c'], '.' );
		}
		$selector = trim( $selector, ', ' );

		if ( ! $selector ) {
			return;
		}

		include $this->base_dir . 'js/triggers/scroll_element.js.php';
	}

	public function get_display_name() {
		$name = __( 'Displays when scrolled at a certain part of the content', 'thrive-leads' );

		if ( ! empty( $this->config['exi'] ) ) {
			$name .= ' ' . __( '(exit intent before that)', 'thrive-leads' );
		}

		return $name;
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when the user scrolls to a specific part of the content', 'thrive-leads' );
	}

}

/**
 * Show when a user clicks on an element
 *
 * Class TVE_Leads_Trigger_Click
 */
class TVE_Leads_Trigger_Click extends TVE_Leads_Trigger_Abstract {
	protected $key = 'click';

	protected $applies_to = array(
		'lightbox',
		'slide_in',
//        'screen_filler'
	);

	/**
	 * register the global click listener
	 */
	public function output_js( $data ) {
		$selector = '';
		if ( ! empty( $this->config['i'] ) ) {
			$selector .= $this->parse_selector( $this->config['i'], '#' );
		}
		if ( ! empty( $this->config['c'] ) ) {
			$selector .= ( $selector ? ',' : '' ) . $this->parse_selector( $this->config['c'], '.' );
		}
		$selector = trim( $selector, ', ' );

		if ( ! $selector ) {
			return;
		}

		include $this->base_dir . 'js/triggers/click.js.php';
	}

	public function get_display_name() {
		return __( 'Displays on click', 'thrive-leads' );
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when the user clicks an element', 'thrive-leads' );
	}


}

/**
 * Show when a user is about to exit a page
 *
 * Class TVE_Leads_Trigger_Exit_Intent
 */
class TVE_Leads_Trigger_Exit_Intent extends TVE_Leads_Trigger_Abstract {
	protected $key = 'exit_intent';

	protected $applies_to = array(
		'lightbox',
		'screen_filler'
	);

	protected $default_config = array(
		'm'  => '', //no mobile trigger by default
		'ms' => 10
	);

	public function get_display_name() {
		return __( 'Displays on exit intent' );
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when the user is about to exit the page (exit intent)', 'thrive-leads' );
	}

}

/**
 * Show when a user enters the viewport
 *
 * Class TVE_Leads_Trigger_Viewport
 */
class TVE_Leads_Trigger_Viewport extends TVE_Leads_Trigger_Abstract {
	protected $key = 'viewport';

	protected $applies_to = array(
		'post_footer',
		'php_insert',
		'shortcode',
		'in_content'
	);

	public function get_display_name() {
		return __( 'Displays when the form enters viewport' );
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when the form enters viewport', 'thrive-leads' );
	}


}

/**
 * Show when the user gets to the bottom of the page
 *
 * Class TVE_Leads_Trigger_Page_Bottom
 */
class TVE_Leads_Trigger_Page_Bottom extends TVE_Leads_Trigger_Abstract {
	protected $key = 'page_bottom';

	protected $applies_to = array(
		'ribbon',
		'lightbox',
		'slide_in',
		'shortcode',
		'widget'
	);

	public function get_display_name() {
		return __( 'When user reaches the end of the content', 'thrive-leads' );
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return __( 'Show when user reaches the bottom of the page', 'thrive-leads' );
	}
}
