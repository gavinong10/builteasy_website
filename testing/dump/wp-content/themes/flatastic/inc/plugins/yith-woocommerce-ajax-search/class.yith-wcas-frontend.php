<?php
/**
 * Frontend class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCAS_Frontend' ) ) {
    /**
     * Admin class. 
	 * The class manage all the Frontend behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WCAS_Frontend {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version;

        /**
		 * Constructor
		 * 
		 * @access public
		 * @since 1.0.0
		 */
    	public function __construct( $version ) {
            $this->version = $version;

			//custom styles and javascripts
		    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
        }

		/**
		 * Enqueue styles and scripts
		 * 
		 * @access public
		 * @return void 
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
    		wp_register_script('yith_wcas_jquery-autocomplete', YITH_WCAS_URL . 'assets/js/devbridge-jquery-autocomplete' . $suffix .'.js', array('jquery'), '1.2.7', true);
    		wp_register_script('yith_wcas_frontend', YITH_WCAS_URL . 'assets/js/frontend' . $suffix .'.js', array('jquery'), '1.0', true);

            $css = file_exists( get_stylesheet_directory() . '/woocommerce/yith_ajax_search.css' ) ? get_stylesheet_directory_uri() . '/woocommerce/yith_ajax_search.css' : YITH_WCAS_URL . 'assets/css/yith_wcas_ajax_search.css';
            wp_enqueue_style( 'yith_wcas_frontend', $css );
		}
    }
}
