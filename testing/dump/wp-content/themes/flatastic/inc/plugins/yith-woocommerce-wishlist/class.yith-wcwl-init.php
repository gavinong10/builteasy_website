<?php
/**
 * Init class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( ! defined( 'YITH_WCWL' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWL_Init' ) ) {
    /**
     * Initiator class. Install the plugin database and load all needed stuffs.
     *
     * @since 1.0.0
     */
    class YITH_WCWL_Init {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version = '1.1.7';

        /**
         * Plugin database version
         *
         * @var string
         * @since 1.0.0
         */
        public $db_version = '1.0.0';

        /**
         * Tab name
         *
         * @var string
         * @since 1.0.0
         */
        public $tab;

        /**
         * Plugin options
         *
         * @var array
         * @since 1.0.0
         */
        public $options;

        /**
         * CSS selectors used to style buttons.
         *
         * @var array
         * @since 1.0.0
         */
        public $rules;

        /**
         * Various links
         *
         * @var string
         * @access public
         * @since 1.0.0
         */
        public $banner_url = 'http://cdn.yithemes.com/plugins/yith_wishlist.php?url';
        public $banner_img = 'http://cdn.yithemes.com/plugins/yith_wishlist.php';
        public $doc_url = 'http://yithemes.com/docs-plugins/yith_wishlist/';

        /**
         * Positions of the button "Add to Wishlist"
         *
         * @var array
         * @access private
         * @since 1.0.0
         */
        private $_positions;

        /**
         * Store class yith_WCWL_Install.
         *
         * @var object
         * @access private
         * @since 1.0.0
         */
        private $_yith_wcwl_install;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct() {
            define( 'YITH_WCWL_VERSION', $this->version );
            define( 'YITH_WCWL_DB_VERSION', $this->db_version );

            /**
             * Support to WC 2.0.x
             */
            global $woocommerce;

            $is_woocommerce_2_0 = version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' );

            $this->tab     = __( 'Wishlist', MAD_BASE_TEXTDOMAIN );	
            $this->options = $this->_plugin_options();

            $this->_positions         = apply_filters( 'yith_wcwl_positions', array(
                'add-to-cart' => array( 'hook' => 'woocommerce_single_product_summary', 'priority' => 31 ),
                'thumbnails'  => array( 'hook' => 'woocommerce_product_thumbnails', 'priority' => 21 ),
                'summary'     => array( 'hook' => 'woocommerce_after_single_product_summary', 'priority' => 11 )
            ) );
            $this->_yith_wcwl_install = new YITH_WCWL_Install();

            if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
                $this->install();
            }


            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'admin_init', array( $this, 'load_admin_style' ) );

            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab_woocommerce' ), 30 );
            add_action( 'woocommerce_update_options_yith_wcwl', array( $this, 'update_options' ) );
            add_action( 'woocommerce_settings_tabs_yith_wcwl', array( $this, 'print_plugin_options' ) );
            add_filter( 'plugin_action_links_' . plugin_basename( plugin_basename( dirname( __FILE__ ) . '/init.php' ) ), array( $this, 'action_links' ) );

            if( $is_woocommerce_2_0 ) {
                add_filter( 'woocommerce_page_settings', array( $this, 'add_page_setting_woocommerce' ) );
            }

            if ( get_option( 'yith_wcwl_enabled' ) == 'yes' ) {
                add_action( 'wp_head', array( $this, 'add_button' ) );
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_and_stuffs' ) );
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
                add_filter( 'body_class', array( $this, 'add_body_class' ) );

                // YITH WCWL Loaded
                do_action( 'yith_wcwl_loaded' );
            }

            //Apply filters
            $this->banner_url = apply_filters( 'yith_wcmg_banner_url', $this->banner_url );
        }

        /**
         * Initiator method. Initiate properties.
         *
         * @return void
         * @access private
         * @since 1.0.0
         */
        public function init() {
            global $yith_wcwl;

            if ( is_user_logged_in() ) {
                $yith_wcwl->details['user_id'] = get_current_user_id();

                //check whether any products are added to wishlist, then after login add to the wishlist if not added
                if ( yith_usecookies() ) {
                    $cookie = yith_getcookie( 'yith_wcwl_products' );
                    foreach ( $cookie as $details ) {
                        $yith_wcwl->details            = $details;
                        $yith_wcwl->details['user_id'] = get_current_user_id();

                        $ret_val = $yith_wcwl->add();
                    }

                    yith_destroycookie( 'yith_wcwl_products' );
                }
                else {
                    if ( isset( $_SESSION['yith_wcwl_products'] ) ) {
                        foreach ( $_SESSION['yith_wcwl_products'] as $details ) {
                            $yith_wcwl->details            = $details;
                            $yith_wcwl->details['user_id'] = get_current_user_id();

                            $ret_val = $yith_wcwl->add();
                        }

                        unset( $_SESSION['yith_wcwl_products'] );
                    }
                }
            }

            wp_register_style( 'yith-wcwl-admin', YITH_WCWL_URL . 'assets/css/admin.css' );
        }

        /**
         * Load admin style.
         *
         * @return void
         * @since 1.0.0
         */
        public function load_admin_style() {
            wp_enqueue_style( 'yith-wcwl-admin' );
        }

        /**
         * Run the installation
         *
         * @return void
         * @since 1.0.0
         */
        public function install() {
            if ( $this->db_version != get_option( 'yith_wcwl_db_version' ) || ! $this->_yith_wcwl_install->is_installed() ) {
                add_action( 'init', array( $this->_yith_wcwl_install, 'init' ) );
                //$this->_yith_wcwl_install->init();
                $this->_yith_wcwl_install->default_options( $this->options );

                // Plugin installed
                do_action( 'yith_wcwl_installed' );
            }
        }

        /**
         * Add the "Add to Wishlist" button. Needed to use in wp_head hook.
         *
         * @return void
         * @since 1.0.0
         */
        public function add_button() {
            global $post;

            if ( ! isset( $post ) || ! is_object( $post ) ) {
                return;
            }

            // Add the link "Add to wishlist"
            $position = 'shortcode';
            $position = empty( $position ) ? 'add-to-cart' : $position;

            if ( $position != 'shortcode' ) {
                add_action( $this->_positions[$position]['hook'], create_function( '', 'echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );' ), $this->_positions[$position]['priority'] );
            }

            // Free the memory. Like it needs a lot of memory... but this is rock!
        }


        /**
         * Add specific body class when the Wishlist page is opened
         *
         * @param array $classes
         *
         * @return array
         * @since 1.0.0
         */
        public function add_body_class( $classes ) {
			$classes[] = 'woocommerce-wishlist';
            return $classes;
        }

        /**
         * Enqueue styles, scripts and other stuffs needed in the <head>.
         *
         * @return void
         * @since 1.0.0
         */
        public function enqueue_styles_and_stuffs() {
            $located = locate_template( array(
                'woocommerce/wishlist.css',
                'wishlist.css'
            ) );

            if ( ! $located ) {
                wp_enqueue_style( 'yith-wcwl-main', YITH_WCWL_URL . 'assets/css/style.css' );
            }
            else {
                wp_enqueue_style( 'yith-wcwl-user-main', str_replace( get_template_directory(), get_template_directory_uri(), $located ) );
            }

            ?>
            <script type="text/javascript">
                var yith_wcwl_plugin_ajax_web_url = '<?php echo admin_url('admin-ajax.php') ?>';
                var login_redirect_url = '<?php echo wp_login_url() . '?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] ) ?>';
            </script>
        <?php
        }

        /**
         * Enqueue plugin scripts.
         *
         * @return void
         * @since 1.0.0
         */
        public function enqueue_scripts() {
            wp_register_script( 'jquery-yith-wcwl', YITH_WCWL_URL . 'assets/js/jquery.yith-wcwl.js', array( 'jquery' ), '1.0', true );
            wp_enqueue_script( 'jquery-yith-wcwl' );

            $yith_wcwl_l10n = array(
                'out_of_stock' => __( 'Cannot add to the cart as product is Out of Stock!', MAD_BASE_TEXTDOMAIN ),
            );
            wp_localize_script( 'jquery-yith-wcwl', 'yith_wcwl_l10n', $yith_wcwl_l10n );
        }

        /**
         * Add the tab of the plugin to the WooCommerce theme options
         *
         * @param array $tabs
         *
         * @return array
         * @since 1.0.0
         */
        public function add_tab_woocommerce( $tabs ) {
            $tabs['yith_wcwl'] = $this->tab;

            return $tabs;
        }

        /**
         * Add the select for the Wishlist page in WooCommerce > Settings > Pages
         *
         * @param array $settings
         *
         * @return array
         * @since 1.0.0
         */
        public function add_page_setting_woocommerce( $settings ) {
            unset( $settings[count( $settings ) - 1] );

            $setting[] = $this->get_wcwl_page_option();

            $settings[] = array( 'type' => 'sectionend', 'id' => 'page_options' );

            return $settings;
        }

        /**
         * Update plugin options.
         *
         * @return void
         * @since 1.0.0
         */
        public function update_options() {
            foreach ( $this->options as $option ) {
                woocommerce_update_options( $option );
            }
        }

        /**
         * Print all plugin options.
         *
         * @return void
         * @since 1.0.0
         */
        public function print_plugin_options() {
            $links = apply_filters( 'yith_wcwl_tab_links', array(
                '<a href="#yith_wcwl_general_settings">' . __( 'General Settings', MAD_BASE_TEXTDOMAIN ) . '</a>',
                '<a href="#yith_wcwl_styles">' . __( 'Styles', MAD_BASE_TEXTDOMAIN ) . '</a>',
                '<a href="#yith_wcwl_socials_share">' . __( 'Socials &amp; Share', MAD_BASE_TEXTDOMAIN ) . '</a>',
            ) );

            ?>
            <div class="subsubsub_section">

                <br class="clear" />

                <?php $this->options = apply_filters( 'yith_wcwl_tab_options', $this->options ); ?>

                <?php foreach ( $this->options as $id => $tab ) : ?>

                    <div class="section" id="yith_wcwl_<?php echo $id ?>">
                        <?php woocommerce_admin_fields( $this->options[$id] ) ?>
                    </div>

                <?php endforeach ?>
            </div>
        <?php
        }

        /**
         * action_links function.
         *
         * @access public
         *
         * @param mixed $links
         *
         * @return void
         */
        public function action_links( $links ) {
            global $woocommerce;

            if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
                $wc_settings_page = "woocommerce_settings";
            }
            else {
                $wc_settings_page = "wc-settings";
            }

            $plugin_links = array(
                    '<a href="' . admin_url( 'admin.php?page=' . $wc_settings_page . '&tab=yith_wcwl' ) . '">' . __( 'Settings', MAD_BASE_TEXTDOMAIN ) . '</a>',
                    '<a href="' . $this->doc_url . '">' . __( 'Docs', MAD_BASE_TEXTDOMAIN ) . '</a>',
                );

            return array_merge( $plugin_links, $links );
        }

        /**
         * Return the option to add the wishlist page
         *
         * @access public
         * @return mxied array
         * @since 1.1.3
         */
		public function get_wcwl_page_option(){

			return array(
				'name'     => __( 'Wishlist Page', MAD_BASE_TEXTDOMAIN ),
				'desc'     => __( 'Page contents: [vc_mad_yith_wcwl_wishlist]', MAD_BASE_TEXTDOMAIN ),
				'id'       => 'yith_wcwl_wishlist_page_id',
				'type'     => 'single_select_page',
				'std'      => '', // for woocommerce < 2.0
				'default'  => '', // for woocommerce >= 2.0
				'class'    => 'chosen_select_nostd',
				'css'      => 'min-width:300px;',
				'desc_tip' => false,
			);
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

        /**
         * Plugin options and tabs.
         *
         * @return array
         * @since 1.0.0
         */
        private function _plugin_options() {

            global $woocommerce;

            $is_woocommerce_2_0 = version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' );

            $options['general_settings'] = array();

            if ( $is_woocommerce_2_0 ) {
                $settings_page = 'WooCommerce &gt; Settings &gt; Pages' ;
            } else {
                $settings_page = 'in this settings page';
            }

            $general_settings_start = array(

                array( 'name' => __( 'General Settings', MAD_BASE_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'yith_wcwl_general_settings' ),

                array(
                    'name'    => __( 'Enable YITH Wishlist', MAD_BASE_TEXTDOMAIN ),
                    'desc'    => sprintf( __( 'Enable all plugin features. <strong>Be sure to select a voice in the wishlist page menu in %s.</strong> Also, please read the plugin <a href="%s" target="_blank">documentation</a>.', MAD_BASE_TEXTDOMAIN ), $settings_page, esc_url( $this->doc_url ) ),
                    'id'      => 'yith_wcwl_enabled',
                    'std'     => 'yes', // for woocommerce < 2.0
                    'default' => 'yes', // for woocommerce >= 2.0
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => __( 'Use cookies', MAD_BASE_TEXTDOMAIN ),
                    'desc'    => __( 'Use cookies instead of sessions. With this feature, the wishlist will be available for each not logged user for 30 days. Use the filter yith_wcwl_cookie_expiration_time to change the expiration time ( needs timestamp ).', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_use_cookie',
                    'std'     => 'yes', // for woocommerce < 2.0
                    'default' => 'yes', // for woocommerce >= 2.0
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => __( 'Wishlist title', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_wishlist_title',
                    'std'     => sprintf( __( 'My wishlist on %s', MAD_BASE_TEXTDOMAIN ), get_bloginfo( 'name' ) ), // for woocommerce < 2.0
                    'default' => sprintf( __( 'My wishlist on %s', MAD_BASE_TEXTDOMAIN ), get_bloginfo( 'name' ) ), // for woocommerce >= 2.0
                    'type'    => 'text',
                    'css'     => 'min-width:300px;',
                )
            );


            $general_settings_end = array(
//                array(
//                    'name'     => __( 'Position', MAD_BASE_TEXTDOMAIN ),
//                    'desc'     => __( 'On variable products you can add it only After "Add to Cart" or use the shortcode [yith_wcwl_add_to_wishlist].', MAD_BASE_TEXTDOMAIN ),
//                    'id'       => 'yith_wcwl_button_position',
//                    'type'     => 'select',
//                    'class'    => 'chosen_select',
//                    'css'      => 'min-width:300px;',
//                    'options'  => array(
//                        'add-to-cart' => __( 'After "Add to cart"', MAD_BASE_TEXTDOMAIN ),
//                        'thumbnails'  => __( 'After thumbnails', MAD_BASE_TEXTDOMAIN ),
//                        'summary'     => __( 'After summary', MAD_BASE_TEXTDOMAIN ),
//                        'shortcode'   => __( 'Use shortcode', MAD_BASE_TEXTDOMAIN )
//                    ),
//                    'desc_tip' => true
//                ),
                array(
                    'name'    => __( 'Redirect to cart', MAD_BASE_TEXTDOMAIN ),
                    'desc'    => __( 'Redirect to cart page if "Add to cart" button is clicked in the wishlist page.', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_redirect_cart',
                    'std'     => 'no', // for woocommerce < 2.0
                    'default' => 'no', // for woocommerce >= 2.0
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => __( 'Remove if added to the cart', MAD_BASE_TEXTDOMAIN ),
                    'desc'    => __( 'Remove the product from the wishlist if is been added to the cart.', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_remove_after_add_to_cart',
                    'std'     => 'yes', // for woocommerce < 2.0
                    'default' => 'yes', // for woocommerce >= 2.0
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => __( '"Add to Wishlist" text', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_add_to_wishlist_text',
                    'std'     => __( 'Add to Wishlist', MAD_BASE_TEXTDOMAIN ), // for woocommerce < 2.0
                    'default' => __( 'Add to Wishlist', MAD_BASE_TEXTDOMAIN ), // for woocommerce >= 2.0
                    'type'    => 'text',
                    'css'     => 'min-width:300px;',
                ),
                array(
                    'name'    => __( '"Add to Cart" text', MAD_BASE_TEXTDOMAIN ),
                    'id'      => 'yith_wcwl_add_to_cart_text',
                    'std'     => __( 'Add to Cart', MAD_BASE_TEXTDOMAIN ), // for woocommerce < 2.0
                    'default' => __( 'Add to Cart', MAD_BASE_TEXTDOMAIN ), // for woocommerce >= 2.0
                    'type'    => 'text',
                    'css'     => 'min-width:300px;',
                ),
//                array(
//                    'name'    => __( 'Show Unit price', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_price_show',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox',
//                    'css'     => 'min-width:300px;',
//                ),
//                array(
//                    'name'    => __( 'Show "Add to Cart" button', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_add_to_cart_show',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox',
//                    'css'     => 'min-width:300px;',
//                ),
//                array(
//                    'name'    => __( 'Show Stock status', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_stock_show',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox',
//                    'css'     => 'min-width:300px;',
//                ),

                array( 'type' => 'sectionend', 'id' => 'yith_wcwl_general_settings' )
            );

//            $options['styles'] = array(
//                array( 'name' => __( 'Styles', MAD_BASE_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'yith_wcwl_styles' ),

//                array(
//                    'name'    => __( 'Use buttons', MAD_BASE_TEXTDOMAIN ),
//                    'desc'    => __( 'Use buttons instead of a simple anchors.', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_use_button',
//                    'std'     => 'no', // for woocommerce < 2.0
//                    'default' => 'no', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Custom CSS', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_custom_css',
//                    'css'     => 'width:100%; height: 75px;',
//                    'std'     => '', // for woocommerce < 2.0
//                    'default' => '', // for woocommerce >= 2.0
//                    'type'    => 'textarea'
//                ),
//                array(
//                    'name'    => __( 'Use theme style', MAD_BASE_TEXTDOMAIN ),
//                    'desc'    => __( 'Use the theme style.', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_frontend_css',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Buttons rounded corners', MAD_BASE_TEXTDOMAIN ),
//                    'desc'    => __( 'Make buttons corner rounded', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_rounded_corners',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'     => __( '"Add to Wishlist" icon', MAD_BASE_TEXTDOMAIN ),
//                    'desc'     => __( 'Add an icon to the "Add to Wishlist" button', MAD_BASE_TEXTDOMAIN ),
//                    'id'       => 'yith_wcwl_add_to_wishlist_icon',
//                    'css'      => 'min-width:300px;width:300px;',
//                    'std'      => apply_filters( 'yith_wcwl_add_to_wishlist_std_icon', 'none' ), // for woocommerce < 2.0
//                    'default'  => apply_filters( 'yith_wcwl_add_to_wishlist_std_icon', 'none' ), // for woocommerce >= 2.0
//                    'type'     => 'select',
//                    'class'    => 'chosen_select',
//                    'desc_tip' => true,
//                    'options'  => array( 'none' => 'None' ) + $icons
//                ),
//                array(
//                    'name'     => __( '"Add to Cart" icon', MAD_BASE_TEXTDOMAIN ),
//                    'desc'     => __( 'Add an icon to the "Add to Cart" button', MAD_BASE_TEXTDOMAIN ),
//                    'id'       => 'yith_wcwl_add_to_cart_icon',
//                    'css'      => 'min-width:300px;width:300px;',
//                    'std'      => apply_filters( 'yith_wcwl_add_to_cart_std_icon', 'icon-shopping-cart' ), // for woocommerce < 2.0
//                    'default'  => apply_filters( 'yith_wcwl_add_to_cart_std_icon', 'icon-shopping-cart' ), // for woocommerce >= 2.0
//                    'type'     => 'select',
//                    'class'    => 'chosen_select',
//                    'desc_tip' => true,
//                    'options'  => array( 'none' => 'None' ) + $icons
//                ),

//                array( 'type' => 'sectionend', 'id' => 'yith_wcwl_styles' )
//            );

//            $options['socials_share'] = array(
//                array( 'name' => __( 'Socials &amp; Share', MAD_BASE_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'yith_wcwl_socials_share' ),
//
//                array(
//                    'name'    => __( 'Share on Facebook', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_share_fb',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Tweet on Twitter', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_share_twitter',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Pin on Pinterest', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_share_pinterest',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Share on Google+', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_share_googleplus',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                 array(
//                    'name'    => __( 'Share by Email', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_share_email',
//                    'std'     => 'yes', // for woocommerce < 2.0
//                    'default' => 'yes', // for woocommerce >= 2.0
//                    'type'    => 'checkbox'
//                ),
//                array(
//                    'name'    => __( 'Socials title', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_socials_title',
//                    'std'     => sprintf( __( 'My wishlist on %s', MAD_BASE_TEXTDOMAIN ), get_bloginfo( 'name' ) ), // for woocommerce < 2.0
//                    'default' => sprintf( __( 'My wishlist on %s', MAD_BASE_TEXTDOMAIN ), get_bloginfo( 'name' ) ), // for woocommerce >= 2.0
//                    'type'    => 'text',
//                    'css'     => 'min-width:300px;',
//                ),
//                array(
//                    'name'    => __( 'Socials text', MAD_BASE_TEXTDOMAIN ),
//                    'desc'    => __( 'Will be used by Facebook, Twitter and Pinterest. Use <strong>%wishlist_url%</strong> where you want the URL of your wishlist to appear.', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_socials_text',
//                    'css'     => 'width:100%; height: 75px;',
//                    'std'     => '', // for woocommerce < 2.0
//                    'default' => '', // for woocommerce >= 2.0
//                    'type'    => 'textarea'
//                ),
//                array(
//                    'name'    => __( 'Socials image URL', MAD_BASE_TEXTDOMAIN ),
//                    'id'      => 'yith_wcwl_socials_image_url',
//                    'std'     => '', // for woocommerce < 2.0
//                    'default' => '', // for woocommerce >= 2.0
//                    'type'    => 'text',
//                    'css'     => 'min-width:300px;',
//                ),
//
//                array( 'type' => 'sectionend', 'id' => 'yith_wcwl_styles' )
//            );

			if( $is_woocommerce_2_0 ) {

				$options['general_settings'] = array_merge( $general_settings_start, $general_settings_end );

			} else {

				$options['general_settings'] = array_merge( $general_settings_start,  array( $this->get_wcwl_page_option() ), $general_settings_end );
			}

            return $options;
        }
    }
}