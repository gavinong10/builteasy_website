<?php
/**
 * Admin class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCAS_Admin' ) ) {
    /**
     * Admin class. 
	 * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WCAS_Admin {
		/**
		 * Plugin options
		 * 
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public $options = array();

        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version;

        /**
         * Various links
         *
         * @var string
         * @access public
         * @since 1.0.0
         */
        public $banner_url = 'http://cdn.yithemes.com/plugins/yith_magnifier.php?url';
        public $banner_img = 'http://cdn.yithemes.com/plugins/yith_magnifier.php';
        public $doc_url    = 'http://yithemes.com/docs-plugins/yith_ajax_search/';
    
    	/**
		 * Constructor
		 * 
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct( $version ) {

			$this->options = $this->_initOptions();
            $this->version = $version;

			//Actions
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
            add_filter( 'plugin_action_links_' . plugin_basename( dirname(__FILE__) . '/init.php' ), array( $this, 'action_links' ) );


			add_action( 'woocommerce_settings_tabs_yith_wcas', array( $this, 'print_plugin_options' ) );
			add_action( 'woocommerce_update_options_yith_wcas', array( $this, 'update_options' ) );


            add_action( 'woocommerce_admin_field_banner', array( $this, 'admin_fields_banner' ) );


            if ( !has_action('woocommerce_admin_field_slider')) add_action( 'woocommerce_admin_field_slider', array( $this, 'admin_fields_slider' ) );
            if ( !has_action('woocommerce_admin_field_picker')) add_action( 'woocommerce_admin_field_picker', array( $this, 'admin_fields_picker' ) );
            add_action( 'woocommerce_update_option_slider', array( $this, 'admin_update_option' ) );
            add_action( 'woocommerce_update_option_picker', array( $this, 'admin_update_option' ) );


            //Filters
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab_woocommerce' ), 30 );
            //add_filter( 'woocommerce_catalog_settings', array( $this, 'add_catalog_image_size' ) );

            //Apply filters
            $this->banner_url = apply_filters('yith_wcas_banner_url', $this->banner_url);

            // YITH WCAS Loaded
            do_action( 'yith_wcas_loaded' );
		}
		
		
		/**
		 * Init method:
		 *  - default options
		 * 
		 * @access public
		 * @since 1.0.0
		 */
		public function init() {
			$this->_default_options();
		}
		
		
        /**
         * Update plugin options.
         * 
         * @return void
         * @since 1.0.0
         */
        public function update_options() {
            foreach( $this->options as $option ) {
                woocommerce_update_options( $option );   
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
            $tabs['yith_wcas'] = __('Ajax Search', 'yit');
            
            return $tabs;
		}
		
		
		/**
		 * Add Zoom Image size to Woocommerce -> Catalog
		 * 
		 * @access public
		 * @param array $settings
		 * 
		 * @return array

		public function add_catalog_image_size( $settings ) {
		    $tmp = $settings[ count($settings)-1 ];
		    unset( $settings[ count($settings)-1 ] );
			
			$settings[] = 	array(
				'name' => __( 'Catalog Zoom Images', 'yit' ),
				'desc' 		=> __('The size of images used within the magnifier box', 'yit'),
				'id' 		=> 'woocommerce_magnifier_image',
				'css' 		=> '',
				'type' 		=> 'image_width',
				'default' 	=> array( 
									'width' => 600,
									'height' => 600,
									'crop' => true
								),
				'std' 		=> array( 
									'width' => 600,
									'height' => 600,
									'crop' => true
								),
				'desc_tip'	=>  true
			);                                  
			$settings[] = $tmp;
			return $settings;
		}
         */
		
        /**
         * Print all plugin options.
         * 
         * @return void
         * @since 1.0.0
         */
        public function print_plugin_options() {
            $links = apply_filters( 'yith_wcas_tab_links', array(
                '<a href="#yith_wcas_general">' . __( 'General Settings', 'yit' ) . '</a>'
            ) );

            ?>

            <div class="subsubsub_section">
                <ul class="subsubsub">
                    <li>
                        <?php echo implode( ' | </li><li>', $links ) ?>
                    </li>
                </ul>
                <br class="clear" />
                
                <?php
                $option_theme = apply_filters('yith_wcas_options_theme_plugin', $this->options );
                foreach( $option_theme as $id => $tab ) : ?>
                <!-- tab #<?php echo $id ?> -->
                <div class="section" id="yith_wcas_<?php echo $id ?>">
                    <?php woocommerce_admin_fields( $option_theme[$id] ) ?>
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
			$options = array(
				'general' => array(
	                array(
	                	'name' => __( 'General Settings', 'yit' ), 
	                	'type' => 'title', 
	                	'desc' => '', 
	                	'id' => 'yith_wcas_general'
					),

                    array(
                        'name' => __( 'Search input label', 'yit' ),
                        'desc' => __( 'Label for Search input field.', 'yit' ),
                        'id'   => 'yith_wcas_search_input_label',
                        'std'  => __( 'Search for products', 'yit' ),
                        'default' => __( 'Search for products', 'yit' ),
                        'desc_tip'	=>  true,
                        'type' 		=> 'text',
                    ),

                    array(
                        'name' => __( 'Search submit label', 'yit' ),
                        'desc' => __( 'Label for Search submit field.', 'yit' ),
                        'id'   => 'yith_wcas_search_submit_label',
                        'std'  => __( 'Search', 'yit' ),
                        'default' => __( 'Search', 'yit' ),
                        'desc_tip'	=>  true,
                        'type' 		=> 'text',
                    ),

                    array(
                        'name' => __( 'Minimum number of characters', 'yit' ),
                        'desc' => __( 'Minimum number of characters required to trigger autosuggest.', 'yit' ),
                        'id'   => 'yith_wcas_min_chars',
                        'std'  => '3',
                        'default' => '3',
                        'css' 		=> 'width:50px;',
                        'desc_tip'	=>  true,
                        'type' 		=> 'number',
                        'custom_attributes' => array(
                            'min' 	=> 1,
                            'max'   => 10,
                            'step' 	=> 1
                        )
                    ),

                    array(
                        'name' => __( 'Maximum number of results', 'yit' ),
                        'desc' => __( 'Maximum number of results showed within the autosuggest box.', 'yit' ),
                        'id'   => 'yith_wcas_posts_per_page',
                        'std'  => '3',
                        'default' => '3',
                        'css' 		=> 'width:50px;',
                        'desc_tip'	=>  true,
                        'type' 		=> 'number',
                        'custom_attributes' => array(
                            'min' 	=> 1,
                            'max'   => 15,
                            'step' 	=> 1
                        )
                    ),

					array( 'type' => 'sectionend', 'id' => 'yith_wcas_general_end' )
				),
			);
			
			return apply_filters('yith_wcas_tab_options', $options);
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
			        		add_option($value['id'].'_width', $value['std']);
			        		add_option($value['id'].'_height', $value['std']);
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

            update_option( $value['id'], $wc_clean($_POST[$value['id']]) );
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
			
			wp_enqueue_style( 'yith_wcas_admin', YITH_WCAS_URL . 'assets/css/admin.css' );
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
         * action_links function.
         *
         * @access public
         * @param mixed $links
         * @return void
         */
        public function action_links( $links ) {

            global $woocommerce;

            if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
                $wc_clean = 'woocommerce_settings';
            }
            else {
                $wc_clean = 'wc-settings';
            }

            $plugin_links = array(
                '<a href="' . admin_url( 'admin.php?page=' . $wc_clean . '&tab=yith_wcas' ) . '">' . __( 'Settings', 'yit' ) . '</a>',
                '<a href="' . $this->doc_url . '">' . __( 'Docs', 'yit' ) . '</a>',
            );

            return array_merge( $plugin_links, $links );
        }
    }
}
