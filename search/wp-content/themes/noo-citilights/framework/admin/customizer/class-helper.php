<?php
/**
 * NOO Customizer Package
 *
 * NOO Customizer Helper class
 * This file defines the helper class for NOO Customizer, it provides function for add section, sub-section and control.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */


if ( !defined( 'NOO_CUSTOMIZER_DATA_FILE' ) ) {
	define( 'NOO_CUSTOMIZER_DATA_FILE', get_template_directory() . '/framework/admin/customizer/options.json' );
}

/**
 * NOO-Customizer Helper class.
 *
 * This class has functions used when add section, sub-section and control to Customizer.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @since 1.0.0
 */
class NOO_Customizer_Helper {

	// Log section and sub-section as well as their priority.
	private static $section_list = array();

	// Remember current section to use when there's no input for section.
	private static $current_section = '';

	// Remember current sub_section to use when there's no input for sub_section.
	private static $current_sub_section = '';

	// Reserved priority number, every new section must not have the same
	// priority as every number stored in this array.
	private static $reserved_priority = array();

	private static $noo_options = null;

	// Object wp_customize
	public $wp_customize = null;

	public function __construct( $wp_customize = null ) {
		$this->wp_customize = $wp_customize;

		if ( empty ( self::$noo_options ) ) {
			self::$noo_options = $this->get_data();
		}
	}

	public function get_new_section_priority() {
		if ( !empty( self::$current_section ) ) {
			$priority = ( self::$section_list[self::$current_section]['priority'] / 100 + 1 ) * 100;
		} else {
			$priority = 100;
		}

		self::$reserved_priority[] = $priority;

		return $priority;
	}

	/**
	 * Add section function
	 */
	public function add_section( $id, $label, $description = '', $is_panel = false, $priority = 0, $args = array() ) {

		if ( empty( $id ) ) {
			return false;
		}

		do_action( 'noo_customizer_option_before_' . $id, $this );

		if ( isset( self::$noo_options[$id] ) ) {
			if ( self::$noo_options[$id]->disabled )
				return;
		}

		if ( !isset( self::$section_list[$id] ) ) {
			// log section
			self::$section_list[$id] = array();

			// register section priority
			if ( is_numeric( $priority ) && $priority > 0 ) {
				self::$section_list[$id]['priority'] = $priority * 100;
			}
			elseif ( !empty( self::$current_section ) ) {
				self::$section_list[$id]['priority'] = ( self::$section_list[self::$current_section]['priority'] / 100 + 1 ) * 100;
			}
			else {
				self::$section_list[$id]['priority'] = 100;
			}

			// reserve this priority number to prevent possible conflict
			while ( in_array( self::$section_list[$id]['priority'], self::$reserved_priority ) ) {
				self::$section_list[$id]['priority'] = self::$section_list[$id]['priority'] + 100;
			}
			self::$reserved_priority[] = self::$section_list[$id]['priority'];

			$args = array_merge(array(
					'title'       => $label,
					'description' => $description,
					'priority'    => self::$section_list[$id]['priority']
				), $args);

			global $wp_version;
			if ( $is_panel && $wp_version >= 4.0 ) {
				$this->wp_customize->add_panel( $id, $args );
				self::$section_list[$id]['is_panel'] = true;
			} else {
				$this->wp_customize->add_section( $id, $args );
				self::$section_list[$id]['is_panel'] = false;
			}

			self::$current_section = $id;
			self::$current_sub_section = null;
		}

		do_action( 'noo_customizer_option_after_' . $id, $this );
	}

	/**
	 * Add sub-section function
	 */
	public function add_sub_section( $id, $label, $description = '', $section_id = '' ) {

		if ( empty( $id ) ) {
			return false;
		}

		do_action( 'noo_customizer_option_before_' . $id, $this );

		if ( isset( self::$noo_options[$id] ) ) {
			if ( self::$noo_options[$id]->disabled )
				return;
		}

		$section_id = empty( $section_id ) ? self::$current_section : $section_id;

		if ( empty( $section_id ) || !isset( self::$section_list[$section_id] ) ) {
			if ( !empty( self::$current_section ) )
				$section_id = self::$current_section;
			else
				return false;
		}

		if ( !isset( self::$section_list[$section_id][$id] ) ) {
			// register sub-section priority
			self::$section_list[$section_id]['priority'] = ( self::$section_list[$section_id]['priority'] / 10 + 1 ) * 10;
			$priority = self::$section_list[$section_id]['priority'];

			$wp_customize = $this->wp_customize;

			global $wp_version;
			if ( self::$section_list[$section_id]['is_panel'] && $wp_version >= 4.0 ) {
				$this->wp_customize->add_section( $id, array(
					'title'       => $label,
					'description' => $description,
					'panel'       => $section_id,
					'priority'    => $priority
				) );
			} else {
				$wp_customize->add_setting( $id, array( 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
				$wp_customize->add_control(
					new NOO_Customizer_Sub_Section( $wp_customize, $id, array(
							'label'  => $label,
							'section' => $section_id,
							'settings' => $id,
							'json'  => array( 'description' => $description ),
							'priority' => $priority
						) )
				);
			}

			self::$current_sub_section = $id;
		}

		do_action( 'noo_customizer_option_after_' . $id, $this );
	}

	/**
	 * Add control function
	 */
	public function add_control(
		$id,
		$type,
		$label,
		$default = null,
		$control = array(),
		$settings = array()
	) {

		if ( empty( $id ) || empty( $type ) ) {
			return false;
		}

		do_action( 'noo_customizer_option_before_' . $type, $this, $id );
		do_action( 'noo_customizer_option_before_' . $id, $this );

		if ( isset( self::$noo_options[$id] ) ) {
			if ( isset(self::$noo_options[$id]->disabled) && self::$noo_options[$id]->disabled )
				return;

			$default = ( isset( self::$noo_options[$id]->default ) && ( self::$noo_options[$id]->default != null ) ) ? self::$noo_options[$id]->default : $default;
		}

		$section_id = isset( $control['section'] ) && !empty( $control['section'] ) ? $control['section'] : self::$current_section;

		if ( empty( $section_id ) || !isset( self::$section_list[$section_id] ) ) {
			return false;
		}

		// register control priority// register sub-section priority
		$priority = ++self::$section_list[$section_id]['priority'];

		// if this section is panel, decrease 1 level
		if( self::$section_list[$section_id]['is_panel'] && !is_null(self::$current_sub_section) ) {
			$section_id = self::$current_sub_section;
		}

		// get the setting options
		$setting_options = array( 'type' => 'theme_mod' );
		if(!empty( $default )) {
			$setting_options['default'] = $default;
		}

		if ( !empty( $settings ) ) {
			$setting_options = array_merge( $setting_options, $settings );
		}

		$sanitize_func = 'sanitize_callback';
		if( $type == 'color_control' || $type == 'alpha_color' ) {
			// Add sanitize function
			$sanitize_func = 'sanitize_color';
		}

		$wp_customize  = $this->wp_customize;

		// Below is some stupid code but I must do it to pass Theme Check
		if( !isset( $setting_options['sanitize_callback'] ) ) {
			$wp_customize->add_setting( $id, array_merge( $setting_options, array( 'sanitize_callback' => array(&$this, $sanitize_func) ) ) );	
		} else {
			$wp_customize->add_setting( $id, array_merge( $setting_options, array( 'sanitize_callback' => array(&$this, $setting_options['sanitize_callback']) ) ) );	
		}

		// get the control options
		$control_options = array(
			'label'  => $label,
			'section' => $section_id,
			'settings' => $id,
			'priority' => $priority
		);
		$control_options = !empty( $control ) ? array_merge( $control_options, $control ) : $control_options;

		switch ( trim( $type ) ) {
		case 'noo_switch':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Switch( $wp_customize, $id, $control_options ) );
			break;
		case 'ui_slider':
			$wp_customize->add_control(
				new NOO_Customizer_Control_UI_Slider( $wp_customize, $id, $control_options ) );
			break;

		case 'noo_radio':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Radio( $wp_customize, $id, $control_options ) );
			break;

		case 'noo_same_as_radio':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Same_As_Radio( $wp_customize, $id, $control_options ) );
			break;

		case 'radio':
		case 'text':
		// case 'checkbox':
		case 'select':
			$control_options['type'] = $type;
			$wp_customize->add_control( $id, $control_options );
			break;

		case 'checkbox':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Checkbox( $wp_customize, $id, $control_options ) );
			break;

		case 'image_control':
		case 'noo_image':
			if ( floatval( get_bloginfo( 'version' ) ) >= 4.1 ) {
				$wp_customize->add_control(
					new WP_Customize_Image_Control( $wp_customize, $id, $control_options ) );
			} else {
				$wp_customize->add_control(
					new NOO_Customize_Image_Control( $wp_customize, $id, $control_options ) );
			}

			break;

		case 'color_control':
			$wp_customize->add_control(
				new WP_Customize_Color_Control( $wp_customize, $id, $control_options ) );
			break;

		case 'alpha_color':
			$wp_customize->add_control(
				new NOO_Customize_Alpha_Color( $wp_customize, $id, $control_options ) );
			break;

		case 'textarea':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Textarea( $wp_customize, $id, $control_options ) );
			break;

		case 'divider':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Divider( $wp_customize, $id, $control_options ) );
			break;

		case 'widgets_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Widgets_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'pages_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Pages_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'posts_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Posts_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'terms_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Terms_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'google_fonts':
			// add more settings for font weight and font subset
			$wp_customize->add_setting( $id.'_weight', array( 'default' => '400', 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
			$wp_customize->add_setting( $id.'_style', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
			$wp_customize->add_setting( $id.'_subset', array( 'default' => 'latin', 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );

			$wp_customize->add_control(
				new NOO_Customizer_Control_Google_Fonts( $wp_customize, $id, $control_options ) );
			break;

		case 'font_size':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Font_Size( $wp_customize, $id, $control_options ) );
			break;

		case 'upload_control':
			$wp_customize->add_control(
				new WP_Customize_Upload_Control( $wp_customize, $id, $control_options ) );
			break;

		case 'import_settings':
			$wp_customize->add_control(
				new NOO_Customize_Settings_Upload( $wp_customize, $id, $control_options ) );
			break;

		case 'export_settings':
			$wp_customize->add_control(
				new NOO_Customize_Settings_Download( $wp_customize, $id, $control_options ) );
			break;

		}

		do_action( 'noo_customizer_option_after_' . $id, $this, $id );
		do_action( 'noo_customizer_option_after_' . $type, $this );
	}

	public function sanitize_callback( $value ) {
		return $value;
	}

	public function sanitize_color( $value ) {
		if( $value == false || $value == 'false' ) {
			$value = '';
		}

		return $value;
	}

	/**
	 * Remove a control, sub-section or section
	 */
	public function remove_control( $id ) {

		if ( empty( $id ) ) {
			return false;
		}

		if( !isset( self::$noo_options[$id] ) ) {
			self::$noo_options[$id] = new stdClass();
		}

		self::$noo_options[$id]->disabled = true;
	}
 
	private function get_data() {
		$content = defined( 'NOO_CUSTOMIZER_DATA_FILE' ) ? json_decode( file_get_contents( NOO_CUSTOMIZER_DATA_FILE ) ) : '';

		$obj = !empty( $content) ? get_object_vars( $content ) : array();

		return apply_filters( 'noo_customizer_data', $obj );
	}
}
