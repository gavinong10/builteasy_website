<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

if ( !defined( 'YITH_WOOCOMPARE' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Woocompare_Frontend' ) ) {
    /**
     * YITH Custom Login Frontend
     *
     * @since 1.0.0
     */
    class YITH_Woocompare_Frontend {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version = YITH_WOOCOMPARE_VERSION;

        /**
         * The list of products inside the comparison table
         *
         * @var array
         * @since 1.0.0
         */
        public $products_list = array();

        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $template_file = 'compare.php';

        /**
         * The name of cookie name
         *
         * @var string
         * @since 1.0.0
         */
        public $cookie_name = 'yith_woocompare_list';

        /**
         * The action used to view the table comparison
         *
         * @var string
         * @since 1.0.0
         */
        public $action_view = 'yith-woocompare-view-table';

        /**
         * The action used to add the product to compare list
         *
         * @var string
         * @since 1.0.0
         */
        public $action_add = 'yith-woocompare-add-product';

        /**
         * The action used to add the product to compare list
         *
         * @var string
         * @since 1.0.0
         */
        public $action_remove = 'yith-woocompare-remove-product';

        /**
         * The standard fields
         *
         * @var array
         * @since 1.0.0
         */
        public $default_fields = array();

        /**
         * Constructor
         *
         * @return YITH_Woocompare_Frontend
         * @since 1.0.0
         */
        public function __construct() {

            // set coookiename
            if ( is_multisite() ) $this->cookie_name .= '_' . get_current_blog_id();

            // populate the list of products
            $this->products_list = isset( $_COOKIE[ $this->cookie_name ] ) ? unserialize( $_COOKIE[ $this->cookie_name ] ) : array();

            // populate default fields for the comparison table
            $this->default_fields = YITH_Woocompare_Helper::standard_fields();

            // add image size
//            YITH_Woocompare_Helper::set_image_size();

            // Add link or button in the products list or
//            if ( get_option('yith_woocompare_compare_button_in_product_page') == 'yes' )  add_action( 'woocommerce_single_product_summary', array( $this, 'add_compare_link' ), 35 );
//            if ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_compare_link' ), 20 );

			add_action('product-actions-after', array( $this, 'add_compare_link' ), 5);

			add_action( 'init', array( $this, 'add_product_to_compare_action' ) );
            add_action( 'init', array( $this, 'remove_product_from_compare_action' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'template_redirect', array( $this, 'compare_table_html' ) );

            // add the shortcode
            add_shortcode( 'yith_compare_button', array( $this, 'compare_button_sc' ) );

            // AJAX
            add_action( 'wp_ajax_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );

            add_action( 'wp_ajax_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );

            add_action( 'wp_ajax_' . $this->action_view, array( $this, 'refresh_widget_list_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_view, array( $this, 'refresh_widget_list_ajax' ) );

            return $this;
        }

        /**
         * Enqueue the scripts and styles in the page
         */
        public function enqueue_scripts() {

            // scripts
            wp_enqueue_script( 'yith-woocompare-main', YITH_WOOCOMPARE_URL . 'assets/js/unminified/woocompare.js', array('jquery'), $this->version, true );
            wp_localize_script( 'yith-woocompare-main', 'yith_woocompare', array(
                'nonceadd' => wp_create_nonce( $this->action_add ),
                'nonceremove' => wp_create_nonce( $this->action_remove ),
                'nonceview' => wp_create_nonce( $this->action_view ),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'actionadd' => $this->action_add,
                'actionremove' => $this->action_remove,
                'actionview' => $this->action_view,
                'added_label' => __( 'Added', MAD_BASE_TEXTDOMAIN ),
                'table_title' => __( 'Product Comparison', MAD_BASE_TEXTDOMAIN ),
                'auto_open' => get_option( 'yith_woocompare_auto_open', 'yes' )
            ));

            // colorbox
            wp_enqueue_style( 'jquery-colorbox', YITH_WOOCOMPARE_URL . 'assets/css/colorbox.css' );
            wp_enqueue_script( 'jquery-colorbox', YITH_WOOCOMPARE_URL . 'assets/js/jquery.colorbox-min.js', array('jquery'), '1.4.21', true );

            // widget
            if ( is_active_widget( false, false, 'yith-woocompare-widget', true ) && ! is_admin() ) {
                wp_enqueue_style( 'yith-woocompare-widget', YITH_WOOCOMPARE_URL . 'assets/css/widget.css' );
            }
        }

        /**
         * The fields to show in the table
         *
         * @return mixed|void
         * @since 1.0.0
         */
        public function fields() {
            $fields = get_option( 'yith_woocompare_fields', array() );

            foreach ( $fields as $field => $show ) {
                if ( $show ) {
                    if ( isset( $this->default_fields[$field] ) ) {
                        $fields[$field] = $this->default_fields[$field];

                    } else {
                        if ( taxonomy_exists( $field ) ) {
                            $fields[$field] = get_taxonomy( $field )->label;
                        }
                    }
                } else {
                    unset( $fields[$field] );
                }
            }
            return $fields;
        }

        /**
         * Render the maintenance page
         *
         */
        public function compare_table_html() {
            if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_view ) ) return;

            global $woocommerce;

            extract( $this->_vars() );

            // remove all styles from compare template
            add_action('wp_print_styles', array( $this, 'remove_all_styles' ), 100);

            // remove admin bar
            remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
            remove_action( 'wp_head', '_admin_bar_bump_cb' );

            $plugin_path   = plugin_dir_path(__FILE__) . 'templates/' . $this->template_file;

            if ( defined('WC_TEMPLATE_PATH') ) {
                $template_path = get_template_directory() . '/' . WC_TEMPLATE_PATH . $this->template_file;
                $child_path    = get_stylesheet_directory() . '/'  .WC_TEMPLATE_PATH . $this->template_file;

            }else{
                $template_path = get_template_directory() . '/' . $woocommerce->template_url . $this->template_file;
                $child_path    = get_stylesheet_directory() . '/' . $woocommerce->template_url . $this->template_file;
            }

            foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                if ( file_exists( ${$var} ) ) {
                    include ${$var};
                    exit();
                }
            }
        }

        /**
         * Return the array with all products and all attributes values
         *
         * @return array The complete list of products with all attributes value
         */
        public function get_products_list() {
            $list = array();
            $products = $this->products_list;
            $fields = $this->fields();

            foreach ( $products as $product_id ) {
                $product = $this->wc_get_product( $product_id );
                if ( ! $product ) continue;

                $product->fields = array();

                // custom attributes
                foreach ( $fields as $field => $name )  {

                    switch( $field ) {
                        case 'title':
							$product->fields[$field] = '<a class="title" target="_blank" href="'. esc_url(get_permalink( $product_id)) .'">'. $product->get_title() .'</a>';
							$product->fields[$field] .= '<span class="product-terms">' . $product->get_categories() . '</span>';
							break;
                        case 'price':
                            $product->fields[$field] = $product->get_price_html();
                            break;
                        case 'image':
                            $product->fields[$field] = intval( get_post_thumbnail_id( $product_id ) );
                            break;
                        case 'description':
                            $product->fields[$field] = apply_filters( 'woocommerce_short_description', $product->post->post_excerpt );
                            break;
                        case 'stock':
                            $availability = $product->get_availability();
                            if ( empty( $availability['availability'] ) ) {
                                $availability['availability'] = __( 'In stock', MAD_BASE_TEXTDOMAIN );
                            }
                            $product->fields[$field] = sprintf( '<span class="%s">%s</span>', esc_attr( $availability['class'] ), esc_html( $availability['availability'] ) );
                            break;
						case 'sku':
							$product->fields[$field] = ($sku = $product->get_sku()) ? $sku : __( 'N/A', MAD_BASE_TEXTDOMAIN );
						break;
						case 'attributes':
							$product->fields[$field] = $product->get_attributes();
						break;
                        default:

                            //$taxonomy = 'pa_' . $field;

                            if ( taxonomy_exists( $field ) ) {

                                $product->fields[$field] = array();

                                $terms = get_the_terms( $product_id, $field );

                                if ( ! empty( $terms ) ) {
                                    foreach ( $terms as $term ) {
                                        $term = sanitize_term( $term, $field );
                                        $product->fields[$field][] = $term->name;
                                    }
                                }

                                $product->fields[$field] = implode( ', ', $product->fields[$field] );

                            } else {

                                do_action_ref_array( 'yith_woocompare_field_' . $field, array( $product, &$product->fields ) );
                            }

                            break;
                    }
                }

                $list[] = $product;
            }

            return $list;
        }

        /**
         * The URL of product comparison table
         *
         * @param $product_id The ID of the product to add
         * @return string The url to add the product in the comparison table
         */
        public function view_table_url() {
            return add_query_arg( 'action', $this->action_view );
        }

        /**
         * The URL to add the product into the comparison table
         *
         * @param $product_id The ID of the product to add
         * @return string The url to add the product in the comparison table
         */
        public function add_product_url( $product_id ) {
            $url_args = array(
                'action' => $this->action_add,
                'id' => $product_id
            );
            return wp_nonce_url( add_query_arg( $url_args ), $this->action_add );
        }

        /**
         * The URL to remove the product into the comparison table
         *
         * @param $product_id The ID of the product to remove
         * @return string The url to remove the product in the comparison table
         */
        public function remove_product_url( $product_id ) {
            $url_args = array(
                'action' => $this->action_remove,
                'id' => $product_id
            );
            return wp_nonce_url( add_query_arg( $url_args ), $this->action_remove );
        }

        /**
         *  Add the link to compare
         */
        public function add_compare_link( $product_id = false, $args = array() ) {
            extract( $args );

            if ( ! $product_id ) {
                global $product;
                $product_id = isset( $product->id ) && $product->exists() ? $product->id : 0;
            }

            // return if product doesn't exist
            if ( empty( $product_id ) ) return;

            $button_text = get_option( 'yith_woocompare_button_text', __( 'Compare', MAD_BASE_TEXTDOMAIN ) );
            $localized_button_text = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_compare_button_text', $button_text ) : $button_text;

            printf( '<a href="%s" class="%s" data-product_id="%d"><span class="feedback">%s</span>%s</a>', $this->add_product_url( $product_id ), 'compare', $product_id, $button_text, ( isset( $button_text ) && $button_text != 'default' ? $button_text : $localized_button_text ) );
        }

        /**
         * Return the url of stylesheet position
         */
        public function stylesheet_url() {
            global $woocommerce;

            $filename = 'compare.css';
            
            $plugin_path   = array( 'path' => plugin_dir_path(__FILE__) . 'assets/css/style.css', 'url' => YITH_WOOCOMPARE_URL . 'assets/css/style.css' );

            if ( defined('WC_TEMPLATE_PATH') ) {
                $template_path = array( 'path' => get_template_directory() . '/' . WC_TEMPLATE_PATH . $filename,         'url' => get_template_directory_uri() . '/' . WC_TEMPLATE_PATH . $filename );
                $child_path    = array( 'path' => get_stylesheet_directory() . '/' . WC_TEMPLATE_PATH . $filename,       'url' => get_stylesheet_directory_uri() . '/' . WC_TEMPLATE_PATH . $filename );
            }else{
                $template_path = array( 'path' => get_template_directory() . '/' . $woocommerce->template_url . $filename,         'url' => get_template_directory_uri() . '/' . $woocommerce->template_url . $filename );
                $child_path    = array( 'path' => get_stylesheet_directory() . '/' . $woocommerce->template_url . $filename,       'url' => get_stylesheet_directory_uri() . '/' . $woocommerce->template_url . $filename );
            }

            foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                if ( file_exists( ${$var}['path'] ) ) {
                    return ${$var}['url'];
                }
            }
        }


        /**
         * Generate template vars
         *
         * @return array
         * @since 1.0.0
         * @access protected
         */
        protected function _vars() {
            $vars = array(
                'products' => $this->get_products_list(),
                'fields' => $this->fields(),
                'repeat_price' => get_option( 'yith_woocompare_price_end' ),
                'repeat_add_to_cart' => get_option( 'yith_woocompare_add_to_cart_end' ),
            );
            return $vars;
        }

        /**
         * The action called by the query string
         */
        public function add_product_to_compare_action() {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ||
                ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], $this->action_add ) ) &&
                ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add ) )
                return;

            $this->add_product_to_compare( intval( $_REQUEST['id'] ) );

            wp_redirect( remove_query_arg( array( 'id', 'action', '_wpnonce' ) ) );
            exit();
        }

        /**
         * The action called by AJAX
         */
        public function add_product_to_compare_ajax() {
            check_ajax_referer( $this->action_add, '_yitnonce_ajax' );

            $this->add_product_to_compare( intval( $_REQUEST['id'] ) );

            $json = array(
                'table_url' => add_query_arg( array(
                    'action' => $this->action_view,
                    'iframe' => 'true',
                    'ver' => time()
                ), site_url() ),
                'widget_table' => $this->list_products_html(),
				'count' => count($this->products_list)
            );

            echo json_encode( $json );
            die();
        }

        /**
         * Add a product in the products comparison table
         *
         * @param $product_id The product ID to add in the comparison table
         */
        public function add_product_to_compare( $product_id ) {
            $product = $this->wc_get_product( $product_id );

            // don't add the product if doesn't exist
            if ( !$product->exists() || in_array( $product_id, $this->products_list ) ) return;

            $this->products_list[] = $product_id;
            setcookie( $this->cookie_name, serialize($this->products_list), 0, COOKIEPATH, COOKIE_DOMAIN, false, true );
        }

        /**
         * The action called by the query string
         */
        public function remove_product_from_compare_action() {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ||
                ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], $this->action_remove ) ) &&
                ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove ) )
                return;

            $this->remove_product_from_compare( intval( $_REQUEST['id'] ) );

            // redirect
            $redirect = remove_query_arg( array( 'id', 'action', '_wpnonce' ) );
            if ( isset( $_REQUEST['redirect'] ) && $_REQUEST['redirect'] == 'view' ) $redirect = remove_query_arg( 'redirect', add_query_arg( 'action', $this->action_view, $redirect ) );
            wp_redirect( $redirect );
            exit();
        }

        /**
         * The action called by AJAX
         */
        public function remove_product_from_compare_ajax() {
            check_ajax_referer( $this->action_remove, '_yitnonce_ajax' );

            $lang = isset( $_REQUEST['lang'] ) ? $_REQUEST['lang'] : false;

            if ( ! isset( $_REQUEST['id'] ) ) die();

            if ( $_REQUEST['id'] == 'all' ) {
                $products = $this->products_list;
                foreach ( $products as $product_id ) {
                    $this->remove_product_from_compare( intval( $product_id ) );
                }
            } else {
                $this->remove_product_from_compare( intval( $_REQUEST['id'] ) );
            }

            header('Content-Type: text/html; charset=utf-8');

            if ( isset( $_REQUEST['responseType'] ) && $_REQUEST['responseType'] == 'product_list' ) {
				$json = array(
					'widget_table' => $this->list_products_html($lang),
					'count' => count($this->products_list)
				);
				echo json_encode( $json );
            } else {
                $this->compare_table_html();
            }

            die();
        }

        /**
         * Return the list of widget table, used in AJAX
         */
        public function refresh_widget_list_ajax() {
			$json = array(
				'widget_table' => $this->list_products_html(),
				'count' => count($this->products_list)
			);
			echo json_encode( $json );
            die();
        }

        /**
         * The list of products as HTML list
         */
        public function list_products_html( $lang = false ) {
            ob_start();

            /**
             * WPML Suppot:  Localize Ajax Call
             */
            global $sitepress;

            if( defined( 'ICL_LANGUAGE_CODE' ) &&  $lang != false && isset( $sitepress )) {
                $sitepress->switch_lang( $lang, true );
            }

            if ( empty( $this->products_list ) ) {
                echo '<li>' . __( 'No products to compare', MAD_BASE_TEXTDOMAIN ) . '</li>';
                return ob_get_clean();
            }

            foreach ( $this->products_list as $product_id ) {
                $product = $this->wc_get_product( $product_id );
                if ( ! $product ) continue;
                ?>
                <li>
					<div class="entry-thumb-image">
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo $product->get_image(array(80, 80)); ?>
						</a>
					</div>
					<div class="entry-post-holder">
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<h6 class="entry-post-title"><?php echo $product->get_title(); ?></h6>
						</a>
						<a href="<?php echo $this->remove_product_url( $product_id ) ?>" data-product_id="<?php echo $product_id; ?>" class="remove" title="<?php _e( 'Remove', MAD_BASE_TEXTDOMAIN ) ?>">x</a>
					</div>
					<div class="clear"></div>
                </li>
            <?php
            }

            return ob_get_clean();
        }

        /**
         * Remove a product from the comparison table
         *
         * @param $product_id The product ID to remove from the comparison table
         */
        public function remove_product_from_compare( $product_id ) {
            $product = $this->wc_get_product( $product_id );
            if ( ! $product ) return;

            // don't add the product if doesn't exist
            if ( !$product->exists() || !in_array( $product_id, $this->products_list ) ) return;

            foreach ( $this->products_list as $k => $id ) {
                if ( $product_id == $id ) unset( $this->products_list[$k] );
            }
            setcookie( $this->cookie_name, serialize($this->products_list), 0, COOKIEPATH, COOKIE_DOMAIN, false, true );
        }

        /**
         * Remove all styles from the compare template
         */
        public function remove_all_styles() {
            global $wp_styles;
            $wp_styles->queue = array('flatastic-font_awesome');
        }

        /**
         * Show the html for the shortcode
         */
        public function compare_button_sc( $atts, $content = null ) {
            $atts = shortcode_atts(array(
                'product' => false,
                'type' => 'default',
                'container' => 'yes'
            ), $atts);

            $product_id = 0;

            /**
             * Retrieve the product ID in these steps:
             * - If "product" attribute is not set, get the product ID of current product loop
             * - If "product" contains ID, post slug or post title
             */
            if ( ! $atts['product'] ) {
                global $product;
                $product_id = isset( $product->id ) && $product->exists() ? $product->id : 0;
            } else {
                global $wpdb;
                $product = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE ID = %d OR post_name = %s OR post_title = %s LIMIT 1", $atts['product'], $atts['product'], $atts['product'] ) );
                if ( ! empty( $product ) ) {
                    $product_id = $product->ID;
                }
            }

            // if product ID is 0, maybe the product doesn't exists or is wrong.. in this case, doesn't show the button
            if ( empty( $product_id ) ) return;

            ob_start();
            if ( $atts['container'] == 'yes' ) echo '<div class="woocommerce product compare-button">';
            $this->add_compare_link( $product_id, array(
                'button_or_link' => ( $atts['type'] == 'default' ? false : $atts['type'] ),
                'button_text' => empty( $content ) ? 'default' : $content
            ) );
            if ( $atts['container'] == 'yes' ) echo '</div>';
            return ob_get_clean();
        }

        public function wc_get_product( $product_id ){
            $wc_get_product = function_exists( 'wc_get_product' ) ? 'wc_get_product' : 'get_product';
            return $wc_get_product( $product_id );
        }

    }
}