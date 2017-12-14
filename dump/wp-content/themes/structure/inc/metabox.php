<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB directory)
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function cmb2_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}

	return true;
}

add_filter( 'cmb2_meta_boxes', 'thememove_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 *
 * @return array
 */
function thememove_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'thememove_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['page_metabox'] = array(
		'id'           => 'page_metabox',
		'title'        => __( 'Page Settings', 'thememove' ),
		'object_types' => array( 'page' ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		'fields'       => array(
			array(
				'name'    => __( 'Bread Crumb', 'thememove' ),
				'desc'    => __( 'Custom settings for breadcrumb', 'thememove' ),
				'id'      => $prefix . 'bread_crumb_enable',
				'type'    => 'select',
				'options' => array(
					'enable'  => __( 'Enable', 'thememove' ),
					'disable' => __( 'Disable', 'thememove' ),
				),
			),
			array(
				'name'    => __( 'Uncover Footer', 'thememove' ),
				'desc'    => __( 'Custom settings for uncover footer option', 'thememove' ),
				'id'      => $prefix . 'uncover_enable',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default', 'thememove' ),
					'enable'  => __( 'Enable', 'thememove' ),
					'disable' => __( 'Disable', 'thememove' ),
				),
			),
			array(
				'name'    => __( 'Top Area', 'thememove' ),
				'desc'    => __( 'Custom settings for header top area', 'thememove' ),
				'id'      => $prefix . 'header_top',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default', 'thememove' ),
					'enable'  => __( 'Enable', 'thememove' ),
					'disable' => __( 'Disable', 'thememove' ),
				),
			),
			array(
				'name'    => __( 'Sticky Header', 'thememove' ),
				'desc'    => __( 'Custom settings for sticky header', 'thememove' ),
				'id'      => $prefix . 'sticky_header',
				'type'    => 'select',
				'options' => array(
					'default' => __( 'Default', 'thememove' ),
					'enable'  => __( 'Enable', 'thememove' ),
					'disable' => __( 'Disable', 'thememove' ),
				),
			),
			array(
				'name' => __( 'Custom Logo', 'thememove' ),
				'desc' => __( 'Upload an image or enter a URL for logo', 'thememove' ),
				'id'   => $prefix . 'custom_logo',
				'type' => 'file',
			),
			array(
				'name'    => __( 'Header Presets', 'thememove' ),
				'desc'    => __( 'Custom settings for header presets', 'thememove' ),
				'id'      => $prefix . 'header_preset',
				'type'    => 'select',
				'options' => array(
					'default'          => __( 'Default', 'thememove' ),
					'header-preset-01' => __( 'Preset 01', 'thememove' ),
					'header-preset-02' => __( 'Preset 02', 'thememove' ),
					'header-preset-03' => __( 'Preset 03', 'thememove' ),
					'header-preset-04' => __( 'Preset 04', 'thememove' ),
					'header-preset-05' => __( 'Preset 05', 'thememove' ),
					'header-preset-06' => __( 'Preset 06', 'thememove' ),
					'header-preset-07' => __( 'Preset 07', 'thememove' ),
					'header-preset-08' => __( 'Preset 08', 'thememove' ),
				),
			),
			array(
				'name'    => __( 'Color Scheme', 'thememove' ),
				'desc'    => __( 'Custom settings for color scheme', 'thememove' ),
				'id'      => $prefix . 'color_scheme',
				'type'    => 'select',
				'options' => array(
					'default'  => __( 'Default', 'thememove' ),
					'scheme1'  => __( 'Color Scheme for Header Preset 01', 'thememove' ),
					'scheme2'  => __( 'Color Scheme for Header Preset 02', 'thememove' ),
					'scheme3'  => __( 'Color Scheme for Header Preset 03', 'thememove' ),
					'scheme4'  => __( 'Color Scheme for Header Preset 04', 'thememove' ),
					'scheme5'  => __( 'Color Scheme for Header Preset 05', 'thememove' ),
					'scheme6'  => __( 'Color Scheme for Header Preset 06', 'thememove' ),
					'scheme7'  => __( 'Color Scheme for Header Preset 07', 'thememove' ),
					'scheme8'  => __( 'Color Scheme for Home V2 Default', 'thememove' ),
					'scheme9'  => __( 'Color Scheme for Home V2 Black', 'thememove' ),
					'scheme10' => __( 'Color Scheme for Home V2 White', 'thememove' ),
				),
			),
			array(
				'name'    => __( 'Page Layout', 'thememove' ),
				'desc'    => __( 'Choose a layout you want', 'thememove' ),
				'id'      => $prefix . 'page_layout_private',
				'type'    => 'select',
				'options' => array(
					'default'         => __( 'Default', 'thememove' ),
					'full-width'      => __( 'Full width', 'thememove' ),
					'content-sidebar' => __( 'Content-Sidebar', 'thememove' ),
					'sidebar-content' => __( 'Sidebar-Content', 'thememove' ),
				),
			),
			array(
				'name' => 'Disable Title',
				'desc' => 'Check this box to disable the title of the page',
				'id'   => $prefix . 'disable_title',
				'type' => 'checkbox'
			),
			array(
				'name' => __( 'Title Background', 'thememove' ),
				'desc' => __( 'Upload an image or enter a URL for heading title', 'thememove' ),
				'id'   => $prefix . 'heading_image',
				'type' => 'file',
			),
			array(
				'name' => 'Alternative Title',
				'desc' => 'Enter your alternative title here',
				'id'   => $prefix . 'alt_title',
				'type' => 'textarea_small'
			),
			array(
				'name' => 'Disable Parallax',
				'desc' => 'Check this box to disable parallax effect for heading title',
				'id'   => $prefix . 'disable_parallax',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Contact Adress',
				'desc' => 'Enter your address here and it will display on map in contact page',
				'id'   => $prefix . 'contact_address',
				'type' => 'text'
			),
			array(
				'name' => 'Custom Class',
				'desc' => 'Enter custom class for this page',
				'id'   => $prefix . 'custom_class',
				'type' => 'text'
			),
		),
	);

	return $meta_boxes;
}
