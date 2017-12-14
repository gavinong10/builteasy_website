<?php
/**
 * Admin class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Magnifier
 * @version 1.1.4
 */

if ( !defined( 'YITH_WOOCOMPARE' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Woocompare_Admin' ) ) {
    /**
     * Admin class.
     * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     */
class YITH_Woocompare_Admin {
    /**
     * Plugin version
     *
     * @var string
     * @since 1.0.0
     */
    public $version = YITH_WOOCOMPARE_VERSION;

    /**
     * Plugin options
     *
     * @var array
     * @access public
     * @since 1.0.0
     */
    public $options = array();

    /**
     * The standard fields
     *
     * @var array
     * @since 1.0.0
     */
    public $default_fields = array();

    /**
     * Various links
     *
     * @var string
     * @access public
     * @since 1.0.0
     */
    public $banner_url = 'http://cdn.yithemes.com/plugins/yith_woocommerce_compare.php?url';
    public $banner_img = 'http://cdn.yithemes.com/plugins/yith_woocommerce_compare.php';
    public $doc_url    = 'http://yithemes.com/docs-plugins/yith_woocommerce_compare/';


    /**
     * Constructor
     *
     * @access public
     * @since 1.0.0
     */
    public function __construct() {

        // populate default fields for the comparison table
        $this->default_fields = YITH_Woocompare_Helper::standard_fields();

        // add image size
//        YITH_Woocompare_Helper::set_image_size();

        //Actions
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

        add_action( 'woocommerce_settings_tabs_yith_woocompare', array( $this, 'print_plugin_options' ) );
        add_action( 'woocommerce_update_options_yith_woocompare', array( $this, 'update_options' ) );
        if ( !has_action('woocommerce_admin_field_slider')) add_action( 'woocommerce_admin_field_slider', array( $this, 'admin_fields_slider' ) );
        if ( !has_action('woocommerce_admin_field_picker')) add_action( 'woocommerce_admin_field_picker', array( $this, 'admin_fields_picker' ) );
        if ( !has_action('woocommerce_admin_field_attributes')) add_action( 'woocommerce_admin_field_attributes', array( $this, 'admin_fields_attributes' ) );
        if ( !has_action('woocommerce_admin_field_yit_wc_image_width')) add_action( 'woocommerce_admin_field_yit_wc_image_width', array( $this, 'admin_fields_yit_wc_image_width' ) );
        add_action( 'admin_print_footer_scripts', array( $this, 'admin_fields_image_deps' ) );

        add_action( 'woocommerce_update_option_slider', array( $this, 'admin_update_option' ) );
        add_action( 'woocommerce_update_option_picker', array( $this, 'admin_update_option' ) );
        add_action( 'woocommerce_update_option_attributes', array( $this, 'admin_update_option' ) );

        //Filters
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab_woocommerce' ), 30 );

        // YITH WCWL Loaded
        do_action( 'yith_woocompare_loaded' );
    }


    /**
     * Init method:
     *  - default options
     *
     * @access public
     * @since 1.0.0
     */
    public function init() {
        $this->options = $this->_initOptions();
        $this->_default_options();
    }


    /**
     * Update plugin options.
     *
     * @return void
     * @since 1.0.0
     */
    public function update_options() {
        foreach( $this->options as $section_options ) {
            woocommerce_update_options( $section_options );
        }
    }


    /**
     * Add Magnifier's tab to Woocommerce -> Settings page
     *
     * @access public
     * @param array $tabs
     *
     * @return array
     */
    public function add_tab_woocommerce($tabs) {
        $tabs['yith_woocompare'] = __('Products Compare', MAD_BASE_TEXTDOMAIN);
        return $tabs;
    }


    /**
     * Print all plugin options.
     *
     * @return void
     * @since 1.0.0
     */
    public function print_plugin_options() {
        $links = apply_filters( 'yith_woocompare_tab_links', array(
            '<a href="#yith_woocompare_general">' . __( 'General Settings', MAD_BASE_TEXTDOMAIN ) . '</a>'
        ) );

//        $this->_printBanner();
        ?>
        <div class="subsubsub_section">

            <?php foreach( $this->options as $id => $tab ) : ?>
                <!-- tab #<?php echo $id ?> -->
                <div class="section" id="yith_woocompare_<?php echo $id ?>">
                    <?php woocommerce_admin_fields( $this->options[$id] ) ?>
                </div>
            <?php endforeach ?>
        </div>
    <?php
    }


    /**
     * Initialize the options
     *
     * @access protected
     * @return array
     * @since 1.0.0
     */
    protected function _initOptions() {
        include dirname(__FILE__) . '/yith-woocompare-options.php';
        return apply_filters('yith_woocompare_tab_options', $options);
    }


    /**
     * Default options
     *
     * Sets up the default options used on the settings page
     *
     * @access protected
     * @return void
     * @since 1.0.0
     */
    protected function _default_options() {

        foreach ($this->options as $section) {
            foreach ( $section as $value ) {
                if ( isset( $value['std'] ) && isset( $value['id'] ) ) {
                    if ( $value['type'] == 'image_width' ) {
                        add_option($value['id'], $value['std']);
                    } elseif ( $value['type'] == 'attributes' ) {
                        $value_id = str_replace( '_attrs', '', $value['id'] );
                        if ( $value['default'] == 'all' ) {
                            $fields = array_merge( $this->default_fields, YITH_Woocompare_Helper::attribute_taxonomies() );
                            $all = array();
                            foreach ( array_keys( $fields ) as $field ) $all[$field] = true;
                            add_option( $value_id, $all );
                        } else {
                            add_option( $value_id, $value['std'] );
                        }
                    } else {
                        add_option($value['id'], $value['std']);
                    }
                }
            }
        }
    }


    /**
     * Create new Woocommerce admin field: slider
     *
     * @access public
     * @param array $value
     * @return void
     * @since 1.0.0
     */
    public function admin_fields_slider( $value ) {
        $slider_value = ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) ?
            esc_attr( stripslashes( get_option($value['id'] ) ) ) :
            esc_attr( $value['std'] );

        ?><tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
            </th>
            <td class="forminp">
                <div id="<?php echo esc_attr( $value['id'] ); ?>_slider" class="yith_woocommerce_slider" style="width: 300px; float: left;"></div>
                <div id="<?php echo esc_attr( $value['id'] ); ?>_value" class="yith_woocommerce_slider_value ui-state-default ui-corner-all"><?php echo $slider_value ?></div>
                <input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="hidden" value="<?php echo $slider_value ?>" /> <?php echo $value['desc']; ?></td>
        </tr>



        <script>
            jQuery(document).ready(function($){
                $('#<?php echo esc_attr( $value['id'] ); ?>_slider').slider({
                    min: <?php echo $value['min'] ?>,
                    max: <?php echo $value['max'] ?>,
                    step: <?php echo $value['step'] ?>,
                    value: <?php echo $slider_value ?>,
                    slide: function( event, ui ) {
                        $( "#<?php echo esc_attr( $value['id'] ); ?>" ).val( ui.value );
                        $( "#<?php echo esc_attr( $value['id'] ); ?>_value" ).text( ui.value );
                    }
                });
            });
        </script>

    <?php
    }


    /**
     * Create new Woocommerce admin field: picker
     *
     * @access public
     * @param array $value
     * @return void
     * @since 1.0.0
     */
    public function admin_fields_picker( $value ) {
        $picker_value = ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) ?
            esc_attr( stripslashes( get_option($value['id'] ) ) ) :
            esc_attr( $value['std'] );
        ?><tr valign="top">
        <th scope="row" class="titledesc">
            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
        </th>

        <td class="forminp">
            <div class="color_box"><strong><?php echo $value['name']; ?></strong>
                <input name="<?php echo esc_attr( $value['id'] ) ?>" id="<?php echo esc_attr( $value['id'] ) ?>" type="text" value="<?php echo $picker_value ?>" class="colorpick" /> <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ) ?>" class="colorpickdiv"></div>
            </div> <?php echo $value['desc']; ?></td>
        </tr>
    <?php
    }

    /**
     * Create new Woocommerce admin field: checkboxes
     *
     * @access public
     * @param array $value
     * @return void
     * @since 1.0.0
     */
    public function admin_fields_attributes( $value ) {
        $fields = array_merge( $this->default_fields, YITH_Woocompare_Helper::attribute_taxonomies() );
        $all = array();

        foreach ( array_keys( $fields ) as $field ) {
            $all[$field] = true;
        }

        $checkboxes = get_option( str_replace( '_attrs', '', $value['id'] ), $value['default'] == 'all' ? $all : array() );

        // add fields that are not still saved
        foreach ( $checkboxes as $k => $v ) {
            unset( $all[ $k ] );
        }
        $checkboxes = array_merge( $checkboxes, $all );
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
            </th>

            <td class="forminp attributes">
                <p class="description"><?php echo $value['desc'] ?></p>
                <ul class="fields">
                    <?php foreach ( $checkboxes as $slug => $checked ) { ?>
                    <li><label><input type="checkbox" name="<?php echo $value['id'] ?>[]" id="<?php echo $value['id'] ?>_<?php echo $slug ?>" value="<?php echo $slug ?>"<?php checked( $checked ) ?> /> <?php echo $fields[$slug] ?></label></li><?php
                    } ?>
                </ul>
                <input type="hidden" name="<?php echo $value['id'] ?>_positions" value="<?php echo implode( ',', array_keys( $checkboxes ) ) ?>" />
            </td>
        </tr>
    <?php
    }

    /**
     * Save the admin field: slider
     *
     * @access public
     * @param mixed $value
     * @return void
     * @since 1.0.0
     */
    public function admin_update_option($value) {

        global $woocommerce;

        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
            $wc_clean = 'woocommerce_clean';
        }
        else {
            $wc_clean = 'wc_clean';
        }

        if ( $value['type'] == 'attributes' ) {
            $val = array();
            $checked_fields = isset( $_POST[$value['id']] ) ? $_POST[$value['id']] : array();
            $fields = array_map( 'trim', explode( ',', $_POST[ $value['id'] . '_positions' ] ) );
            foreach ( $fields as $field ) {
                $val[$field] = in_array( $field, $checked_fields );
            }

//			delete_option(str_replace('_attrs', '', $value['id']));
            update_option( str_replace( '_attrs', '', $value['id'] ), $val );
        } else{
            update_option( str_replace( '_attrs', '', $value['id'] ), $wc_clean($_POST[$value['id']]) );
        }
    }

    /**
     * Create new Woocommerce admin field: yit_wc_image_width
     *
     * @access public
     * @param array $value
     * @return void
     * @since 1.0.0
     */
    public function admin_fields_yit_wc_image_width( $value ){

        $width 	= WC_Admin_Settings::get_option( $value['id'] . '[width]', $value['default']['width'] );
        $height = WC_Admin_Settings::get_option( $value['id'] . '[height]', $value['default']['height'] );
        $crop   = WC_Admin_Settings::get_option( $value['id'] . '[crop]', $value['default']['crop'] );
        $crop   = WC_Admin_Settings::get_option( $value['id'] . '[crop]' );
        $crop   = ( $crop == 'on' || $crop == '1' ) ? 1 : 0;
        $crop   = checked( 1, $crop, false );

        ?><tr valign="top">
            <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $value['desc'] ?></th>
            <td class="forminp image_width_settings">

                <input name="<?php echo esc_attr( $value['id'] ); ?>[width]" id="<?php echo esc_attr( $value['id'] ); ?>-width" type="text" size="3" value="<?php echo $width; ?>" /> &times; <input name="<?php echo esc_attr( $value['id'] ); ?>[height]" id="<?php echo esc_attr( $value['id'] ); ?>-height" type="text" size="3" value="<?php echo $height; ?>" />px

                <label><input name="<?php echo esc_attr( $value['id'] ); ?>[crop]" id="<?php echo esc_attr( $value['id'] ); ?>-crop" type="checkbox" <?php echo $crop; ?> /> <?php _e( 'Hard Crop?', 'woocommerce' ); ?></label>

                </td>
        </tr><?php

    }

    /**
     * Create new Woocommerce admin field: image deps
     *
     * @access public
     * @param array $value
     * @return void
     * @since 1.0.0
     */
public function admin_fields_image_deps( $value ) {
    global $woocommerce;

    $force = get_option('yith_woocompare_force_sizes') == 'yes';

    if( $force ) {
        $value['desc'] = 'These values ??are automatically calculated based on the values ??of the Single product. If you\'d like to customize yourself the values, please disable the "Forcing Zoom Image sizes" in "Magnifier" tab.';
    }

if( $force && isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] == 'woocommerce_settings' && $_GET['tab'] == 'catalog' ): ?>
    <script>
        jQuery(document).ready(function($){
            $('#woocommerce_magnifier_image-width, #woocommerce_magnifier_image-height, #woocommerce_magnifier_image-crop').attr('disabled', 'disabled');

            $('#shop_single_image_size-width, #shop_single_image_size-height').on('keyup', function(){
                var value = parseInt( $(this).val() );
                var input = (this.id).indexOf('width') >= 0 ? 'width' : 'height';

                if( !isNaN(value) ) {
                    $('#woocommerce_magnifier_image-' + input).val( value * 2 );
                }
            });

            $('#shop_single_image_size-crop').on('change', function(){
                if( $(this).is(':checked') ) {
                    $('#woocommerce_magnifier_image-crop').attr('checked', 'checked');
                } else {
                    $('#woocommerce_magnifier_image-crop').removeAttr('checked');
                }
            });

            $('#mainform').on('submit', function(){
                $(':disabled').removeAttr('disabled');
            });
        });
    </script>
<?php endif;
}


    /**
     * Enqueue admin styles and scripts
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function enqueue_styles_scripts() {
        wp_enqueue_script( 'jquery-ui' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-mouse' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-sortable' );

        if( isset( $_GET['page'] ) && ( $_GET['page'] == 'woocommerce_settings' || $_GET['page'] == 'wc-settings' ) && isset( $_GET['tab'] ) && $_GET['tab'] == 'yith_woocompare' ) {
            wp_enqueue_style( 'yith_woocompare_admin', YITH_WOOCOMPARE_URL . 'assets/css/admin.css' );
            wp_enqueue_script( 'woocompare', YITH_WOOCOMPARE_URL . 'assets/js/woocompare-admin.js', array( 'jquery', 'jquery-ui-sortable' ) );
        }
    }


    /**
     * Print the banner
     *
     * @access protected
     * @return void
     * @since 1.0.0
     */
    protected function _printBanner() {
        ?>
        <div class="yith_banner">
            <a href="<?php echo $this->banner_url ?>" target="_blank">
                <img src="<?php echo $this->banner_img ?>" alt="" />
            </a>
        </div>
    <?php
    }

}
}
