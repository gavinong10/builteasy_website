<?php

/**
 * Structure for all the available animations
 */
class TVE_Leads_Animation_Abstract {
	const ANIM_INSTANT = 'instant';
	const ANIM_ZOOM_IN = 'zoom_in';
	const ANIM_ZOOM_OUT = 'zoom_out';
	const ANIM_ROTATIONAL = 'rotational';
	const ANIM_SLIDE_IN_TOP = 'slide_top';
	const ANIM_SLIDE_IN_BOT = 'slide_bot';
	const ANIM_SLIDE_IN_LEFT = 'slide_left';
	const ANIM_SLIDE_IN_RIGHT = 'slide_right';
	const ANIM_3D_SLIT = '3d_slit';
	const ANIM_3D_FLIP_HORIZONTAL = '3d_flip_horizontal';
	const ANIM_3D_FLIP_VERTICAL = '3d_flip_vertical';
	const ANIM_3D_SIGN = '3d_sign';
	const ANIM_3D_ROTATE_BOTTOM = '3d_rotate_bottom';
	const ANIM_3D_ROTATE_LEFT = '3d_rotate_left';
	const ANIM_BLUR = 'blur';
	const ANIM_MAKE_WAY = 'make_way';
	const ANIM_SLIP_FORM_TOP = 'slip_from_top';
	const ANIM_BOUNCE_IN = 'bounce_in';
	const ANIM_BOUNCE_IN_DOWN = 'bounce_in_down';
	const ANIM_BOUNCE_IN_LEFT = 'bounce_in_left';
	const ANIM_BOUNCE_IN_RIGHT = 'bounce_in_right';
	const ANIM_BOUNCE_IN_UP = 'bounce_in_up';

	public static $AVAILABLE = array(
		self::ANIM_INSTANT,
		self::ANIM_ZOOM_IN,
		self::ANIM_ZOOM_OUT,
		self::ANIM_ROTATIONAL,
		self::ANIM_SLIDE_IN_TOP,
		self::ANIM_SLIDE_IN_BOT,
		self::ANIM_SLIDE_IN_LEFT,
		self::ANIM_SLIDE_IN_RIGHT,
		self::ANIM_3D_SLIT,
		self::ANIM_3D_FLIP_HORIZONTAL,
		self::ANIM_3D_FLIP_VERTICAL,
		self::ANIM_3D_SIGN,
		self::ANIM_3D_ROTATE_BOTTOM,
		self::ANIM_3D_ROTATE_LEFT,
		self::ANIM_BLUR,
		self::ANIM_MAKE_WAY,
		self::ANIM_SLIP_FORM_TOP,
		self::ANIM_BOUNCE_IN,
		self::ANIM_BOUNCE_IN_DOWN,
		self::ANIM_BOUNCE_IN_LEFT,
		self::ANIM_BOUNCE_IN_RIGHT,
		self::ANIM_BOUNCE_IN_UP,
	);

	/**
	 * @var string title to be displayed
	 */
	protected $title = '';

	/**
	 * @var string internal animation key
	 */
	protected $key = '';

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
	 * @return TVE_Leads_Animation_Abstract
	 */
	public static function factory( $type ) {
		$parts = explode( '_', $type );

		$class = 'TVE_Leads_Animation';
		foreach ( $parts as $part ) {
			$class .= '_' . ucfirst( $part );
		}

		if ( ! class_exists( $class ) ) {
			return null;
		}

		return new $class( $type );
	}

	/**
	 * merge the received config with the defaults
	 *
	 */
	public function __construct( $key ) {
		$this->key      = $key;
		$this->base_dir = plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * get the title
	 */
	public function get_title() {
		return $this->title;
	}

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
	 * output javascript required for the animation, if the case applies
	 *
	 * renders directly JS code, without returning it
	 *
	 * @param $data - this should usually be the variation
	 */
	public function output_js( $data ) {
		if ( is_file( $this->base_dir . 'js/animations/' . $this->key . '.js.php' ) ) {
			include $this->base_dir . 'js/animations/' . $this->key . '.js.php';
		}
	}

	/**
	 * parse a CSS selector, making sure it's compliant
	 *
	 * @param $raw
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
	 * get the human-friendly animation name (and also include the configuration settings)
	 *
	 * @return string
	 */
	public function get_display_name() {
		return $this->get_title();
	}
}

/**
 * Instant Animation - No Animation
 *
 * Class TVE_Leads_Animation_Instant
 */
class TVE_Leads_Animation_Instant extends TVE_Leads_Animation_Abstract {
	protected $title = 'Instant';
}

/**
 * Make the form zoom in at display
 *
 * Class TVE_Leads_Animation_Zoom_In
 */
class TVE_Leads_Animation_Zoom_In extends TVE_Leads_Animation_Abstract {
	protected $title = 'Zoom In';
}

/**
 * Make the form zoom out at display
 *
 * Class TVE_Leads_Animation_Zoom_Out
 */
class TVE_Leads_Animation_Zoom_Out extends TVE_Leads_Animation_Abstract {
	protected $title = 'Zoom Out';
}

/**
 * Rotate the form at display
 *
 * Class TVE_Leads_Animation_Rotational
 */
class TVE_Leads_Animation_Rotational extends TVE_Leads_Animation_Abstract {
	protected $title = 'Rotational';

}

/**
 * The form slides in from the top
 *
 * Class TVE_Leads_Animation_Slide_Top
 */
class TVE_Leads_Animation_Slide_Top extends TVE_Leads_Animation_Abstract {
	protected $title = 'Slide in from Top';

}

/**
 * The form slides in from the Bottom
 *
 * Class TVE_Leads_Animation_Slide_Bot
 */
class TVE_Leads_Animation_Slide_Bot extends TVE_Leads_Animation_Abstract {
	protected $title = 'Slide in from Bottom';
}

/**
 * Form slides in from lateral
 *
 * Class TVE_Leads_Animation_Slide_Left
 */
class TVE_Leads_Animation_Slide_Left extends TVE_Leads_Animation_Abstract {
	protected $title = 'Slide in from Left';
}

/**
 * Form slides in from right
 *
 * Class TVE_Leads_Animation_Slide_Right
 */
class TVE_Leads_Animation_Slide_Right extends TVE_Leads_Animation_Abstract {
	protected $title = 'Slide in from Right';
}

/**
 * Form 3D Slit
 *
 * Class TVE_Leads_Animation_3d_Slit
 */
class TVE_Leads_Animation_3d_Slit extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Slit';
}

/**
 * Form 3D Flip Horizontal
 *
 * Class TVE_Leads_Animation_3d_Flip_Horizontal
 */
class TVE_Leads_Animation_3d_Flip_Horizontal extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Flip (Horizontal)';
}

/**
 * Form 3D Flip Vertical
 *
 * Class TVE_Leads_Animation_3d_Flip_Vertical
 */
class TVE_Leads_Animation_3d_Flip_Vertical extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Flip (Vertical)';
}

/**
 * Form 3D Flip Vertical
 *
 * Class TVE_Leads_Animation_3d_Sign
 */
class TVE_Leads_Animation_3d_Sign extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Sign';
}

/**
 * Form 3D Rotate Bottom
 *
 * Class TVE_Leads_Animation_3d_Rotate_Bottom
 */
class TVE_Leads_Animation_3d_Rotate_Bottom extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Rotate Bottom';
}

/**
 * Form 3D Rotate Bottom
 *
 * Class TVE_Leads_Animation_3d_Rotate_Left
 */
class TVE_Leads_Animation_3d_Rotate_Left extends TVE_Leads_Animation_Abstract {
	protected $title = '3D Rotate Left';
}

/**
 * Form Blur
 *
 * Class TVE_Leads_Animation_Blur
 */
class TVE_Leads_Animation_Blur extends TVE_Leads_Animation_Abstract {
	protected $title = 'Blur';
}

/**
 * Form Make Way
 *
 * Class TVE_Leads_Animation_Make_Way
 */
class TVE_Leads_Animation_Make_Way extends TVE_Leads_Animation_Abstract {
	protected $title = 'Make Way';
}

/**
 * Form Slip from Top
 *
 * Class TVE_Leads_Animation_Slip_From_Top
 */
class TVE_Leads_Animation_Slip_From_Top extends TVE_Leads_Animation_Abstract {
	protected $title = 'Slip from Top';
}

/**
 * Form Bounce In
 *
 * Class TVE_Leads_Animation_Bounce_In
 */
class TVE_Leads_Animation_Bounce_In extends TVE_Leads_Animation_Abstract {
	protected $title = 'Bounce In';
}

/**
 * Form Bounce In Down
 *
 * Class TVE_Leads_Animation_Bounce_In_Down
 */
class TVE_Leads_Animation_Bounce_In_Down extends TVE_Leads_Animation_Abstract {
	protected $title = 'Bounce In Down';
}

/**
 * Form Bounce In Left
 *
 * Class TVE_Leads_Animation_Bounce_In_Left
 */
class TVE_Leads_Animation_Bounce_In_Left extends TVE_Leads_Animation_Abstract {
	protected $title = 'Bounce In Left';
}

/**
 * Form Bounce In Right
 *
 * Class TVE_Leads_Animation_Bounce_In_Right
 */
class TVE_Leads_Animation_Bounce_In_Right extends TVE_Leads_Animation_Abstract {
	protected $title = 'Bounce In Right';
}

/**
 * Form Bounce In Up
 *
 * Class TVE_Leads_Animation_Bounce_In_Up
 */
class TVE_Leads_Animation_Bounce_In_Up extends TVE_Leads_Animation_Abstract {
	protected $title = 'Bounce In Up';
}
