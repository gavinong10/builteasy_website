<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCAS' ) ) {
    /**
     * WooCommerce Magnifier
     *
     * @since 1.0.0
     */
    class YITH_WCAS {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version = '1.1.3';
        
        /**
         * Plugin object
         *
         * @var string
         * @since 1.0.0
         */
        public $obj = null;

		/**
		 * Constructor
		 * 
		 * @return mixed|YITH_WCAS_Admin|YITH_WCAS_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {
			
			// actions
			add_action( 'init', array( $this, 'init' ) );
            add_action( 'widgets_init', array( $this, 'registerWidgets' ) );
            add_action( 'wp_ajax_yith_ajax_search_products', array( $this, 'ajax_search_products') );
            add_action( 'wp_ajax_nopriv_yith_ajax_search_products', array( $this, 'ajax_search_products') );

            //register shortcode
            add_shortcode( 'yith_woocommerce_ajax_search', array( $this, 'add_woo_ajax_search_shortcode') );
            add_shortcode( 'yith_woocommerce_ajax_search_widget', array( $this, 'add_woo_ajax_search_shortcode_widget') );

			if( is_admin() ) {
				$this->obj = new YITH_WCAS_Admin( $this->version );
			} else {
				$this->obj = new YITH_WCAS_Frontend( $this->version );
			}
			
			return $this->obj;
		}     
		
		
		/**
		 * Init method:
		 *  - default options
		 * 
		 * @access public
		 * @since 1.0.0
		 */
		public function init() {}

        /**
         * Load template for [yith_woocommerce_ajax_search] shortcode
         *
         * @access public
         * @param $args array
         * @return void
         * @since 1.0.0
         */
        public function add_woo_ajax_search_shortcode( $args = array() ) {
            $args = shortcode_atts( array(), $args );

            $wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
            $wc_get_template( 'yith-woocommerce-ajax-search.php', $args, '', YITH_WCAS_DIR . 'templates/' );
        }

		public function add_woo_ajax_search_shortcode_widget( $args = array() ) {
			$args = shortcode_atts( array(), $args );

			$wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
			$wc_get_template( 'yith-woocommerce-ajax-search-widget.php', $args, '', YITH_WCAS_DIR . 'templates/' );
		}

        /**
         * Load and register widgets
         *
         * @access public
         * @since 1.0.0
         */
        public function registerWidgets() {
            register_widget( 'YITH_WCAS_Ajax_Search_Widget' );
        }


        /**
         * Perform jax search products
         */
        public function ajax_search_products() {
            global $woocommerce;

            $search_keyword = esc_attr($_REQUEST['query']);

            $ordering_args = $woocommerce->query->get_catalog_ordering_args( 'title', 'asc' );
            $suggestions = array();

            $args = array(
                's'                     => apply_filters('yith_wcas_ajax_search_products_search_query', $search_keyword),
                'post_type'				=> 'product',
                'post_status' 			=> 'publish',
                'ignore_sticky_posts'	=> 1,
                'orderby' 				=> $ordering_args['orderby'],
                'order' 				=> $ordering_args['order'],
                'posts_per_page' 		=> apply_filters('yith_wcas_ajax_search_products_posts_per_page', get_option('yith_wcas_posts_per_page')),
                'meta_query' 			=> array(
                    array(
                        'key' 			=> '_visibility',
                        'value' 		=> array('catalog', 'visible'),
                        'compare' 		=> 'IN'
                    )
                )
            );

            if( isset( $_REQUEST['product_cat']) ){
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $_REQUEST['product_cat']
                    ));
            }

            $products = get_posts( $args );

            if ( ! empty( $products ) ) {
                foreach ( $products as $post ) {
                    $product = wc_get_product( $post );

                    $suggestions[] = apply_filters( 'yith_wcas_suggestion', array(
                        'id'    => $product->id,
                        'value' => $product->get_title(),
                        'url'   => $product->get_permalink(),
						'thumbnail' => $product->get_image(array(60, 60)),
						'sku' => $product->get_sku()
                    ), $product );
                }
            } else {
                $suggestions[] = array(
                    'id' => -1,
                    'value' => __('No results', 'yit'),
                    'url' => '',
					'thumbnail' => '',
					'sku' => ''
                );
            }
            wp_reset_postdata();

            $suggestions = array(
                'suggestions' => $suggestions
            );

            echo json_encode( $suggestions );
            die();
        }
	}
}