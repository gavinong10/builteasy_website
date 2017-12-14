<?php
/**
 * @package Mega Main Menu
 * @version 2.0.0
 * Primary functions file. Creates and initializes the primary class that calls all the other php and functions.
 * Author: MegaMain.com
 * Author URI: http://megamain.com
 */
if ( !class_exists('mad_mega_main_init') ) {
	class mad_mega_main_init {
		public $mm_config;
		public $constant;
		public $saved_options;
		public function __construct ( $mm_config = '' ) {
			$this->mm_config = $mm_config;
			add_action( 'init', array( $this, 'init' ), 1 );
		}

		public function init( ) {
			// Order of calling functions is very important first-constant, second-mm_theme_options, last-extensions_loader!
			@ini_set('max_input_vars', 20000);
			$this->constant( $this->mm_config );
			$this->saved_options();
			$GLOBALS[ $this->constant[ 'MM_WARE_SLUG' ] ] = $this;
			$this->load_framework();
			$this->load_extensions();
			// theme options
			add_action( 'admin_menu', array( $this, 'options_menu_item' ), 1 );
			// meta_boxes
			add_action( 'add_meta_boxes', array( $this, 'meta_box_generator' ), 1 );
			add_action( 'save_post', array( $this, 'save_meta_options' ), 1, 2 );
			// src (js, css)
			add_action( 'wp_enqueue_scripts', array( $this, 'load_all_src' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_all_src' ), 20, 1 );
			// theme support
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'widgets' );
			// body class
			add_action( 'body_class', array( $this, 'body_class' ), 1 );
			add_action( 'admin_body_class', array( $this, 'admin_body_class' ), 1 );
//			print_r( $this );
		}

		/*
		 * Function sets theme constants.
		 */
		public function constant( $mm_config = array() ) {
			// Set theme primary information.
			foreach ( $mm_config as $key => $value ) {
				$this->constant[ $key ] = $value;
			}
			// Set theme identificators and prefixes.
			$this->constant[ 'MM_OPTIONS_NAME' ] = $this->constant[ 'MM_WARE_SLUG' ] . '_options';
			$this->constant[ 'MM_OPTIONS_DB_NAME' ] = $this->constant[ 'MM_OPTIONS_NAME' ];
			$this->constant[ 'MM_TEXTDOMAIN' ] = $this->constant[ 'MM_WARE_SLUG' ];
			$this->constant[ 'MM_TEXTDOMAIN_ADMIN' ] = $this->constant[ 'MM_WARE_SLUG' ] . '_admin';
			$this->constant[ 'MM_THEME_PAGE_SLUG' ] = $this->constant[ 'MM_OPTIONS_NAME' ];
			// Set theme static locations.
			// DIRECTORIES
			$this->constant[ 'MM_WARE_DIR' ] = dirname( $this->constant[ 'MM_WARE_INIT_FILE' ] );
			$this->constant[ 'MM_WARE_FRAMEWORK_DIR' ] = $this->constant[ 'MM_WARE_DIR' ] . '/framework';
			$this->constant[ 'MM_WARE_EXTENSIONS_DIR' ] = $this->constant[ 'MM_WARE_DIR' ] . '/extensions';
			$this->constant[ 'MM_WARE_SRC_DIR' ] = $this->constant[ 'MM_WARE_DIR' ] . '/src';
			$this->constant[ 'MM_WARE_CSS_DIR' ] = $this->constant[ 'MM_WARE_SRC_DIR' ] . '/css';
			// URL's
/*
			if ( is_multisite() ) {
				$home_url = get_home_url();
			} else {
				$wpurl = get_bloginfo( 'wpurl' );
				$home_url = $wpurl;
			}
			$ware_dir_explode = array_reverse( explode( '/', str_replace( '\\', '/', $this->constant[ 'MM_WARE_DIR' ] ) ) );
			$this->constant[ 'MM_WARE_URL' ] = $home_url . '/' . $ware_dir_explode[ 2 ] . '/' . $ware_dir_explode[ 1 ] . '/' . $ware_dir_explode[ 0 ];
			$this->constant[ 'MM_WARE_SRC_URL' ] = $this->constant[ 'MM_WARE_URL' ] . '/src';
*/

			$this->constant[ 'MM_WARE_URL' ] = trailingslashit(MAD_BASE_URI . 'inc/plugins/mega_main_menu');
			$this->constant[ 'MM_WARE_SRC_URL' ] = $this->constant[ 'MM_WARE_URL' ] . 'src';
			$this->constant[ 'MM_WARE_CSS_URL' ] = $this->constant[ 'MM_WARE_SRC_URL' ] . '/css';
			$this->constant[ 'MM_WARE_JS_URL' ] = $this->constant[ 'MM_WARE_SRC_URL' ] . '/js';
			$this->constant[ 'MM_WARE_FONTS_URL' ] = $this->constant[ 'MM_WARE_SRC_URL' ] . '/fonts';
			$this->constant[ 'MM_WARE_IMG_URL' ] = $this->constant[ 'MM_WARE_SRC_URL' ] . '/img';
		}

		/** 
		 * The function add CSS class with current ware version in the body tag.
		 */
		public function body_class ( $classes ) {
			$classes[] = $this->constant[ 'MM_WARE_PREFIX' ] . ' ' . $this->constant[ 'MM_WARE_SLUG' ] . '-' . str_replace( '.', '-', $this->constant[ 'MM_WARE_VERSION' ] );
			return $classes;
		}

		/** 
		 * The function add CSS class with current ware version in the admin body tag.
		 */
		public function admin_body_class ( $classes ) {
			$classes .= ' ' . $this->constant[ 'MM_WARE_PREFIX' ] . ' ' . $this->constant[ 'MM_WARE_SLUG' ] . '-' . str_replace( '.', '-', $this->constant[ 'MM_WARE_VERSION' ] );
			return $classes;
		}

		/** 
		 * The function get all saved options of the current ware.
		 */
		public function saved_options () {
			$this->saved_options = get_option( $this->constant[ 'MM_OPTIONS_DB_NAME' ], array() );
			if ( $this->saved_options == 'Not saved options' ) {
				$this->saved_options = get_option( 'mmpm_options_mega_main_menu', array() );
			}
		}

		/** 
		 * The function include common framework files.
		 */
		public function load_framework () {
			if ( is_admin() ) {
				include_once( $this->constant[ 'MM_WARE_FRAMEWORK_DIR' ] . '/datastore.php' ); 
			}
			include_once( $this->constant[ 'MM_WARE_FRAMEWORK_DIR' ] . '/common.php' ); 
			include_once( $this->constant[ 'MM_WARE_FRAMEWORK_DIR' ] . '/image_pro.php' ); 
			include_once( $this->constant[ 'MM_WARE_FRAMEWORK_DIR' ] . '/options_generator.php' ); 
		}

		/** 
		 * The function include all ware extensions files.
		 */
		public function load_extensions () {
			if ( $dir_contents = opendir( $this->constant[ 'MM_WARE_EXTENSIONS_DIR' ] ) ) {
				while( ( $inner_dir = readdir( $dir_contents ) ) !== false ) {
					if ( $inner_dir != "." && $inner_dir != ".." && file_exists( $this->constant[ 'MM_WARE_EXTENSIONS_DIR' ] . '/' . $inner_dir . '/init.php' ) ) { 
						include_once( $this->constant[ 'MM_WARE_EXTENSIONS_DIR' ] . '/' . $inner_dir . '/init.php' ); 
					} 
				}
				closedir($dir_contents);
			}
		}

		/** 
		 * The function return variable which contain only one key from global theme options.
		 * @return $mm_single_option - one vareable from global theme options.
		 */
		public function get_option( $key = false, $default_value = false ) {
			if ( $key != false ) {
				if ( is_array( $this->saved_options ) && array_key_exists( $key, $this->saved_options ) ) {
					$mm_single_option = $this->saved_options[ $key ];
				} else {
					$mm_single_option = $default_value;
				}
			} else {
				$mm_single_option = false;
			}
			return $mm_single_option;
		}

		/** 
		 * 
		 * @return $out
		 */
		public function options_generator( $option = array(), $saved_value = false ) {
			$out = mad_mm_options_generator( $option, $saved_value, $this );
			return $out;
		}

		/** 
		 * register page for theme options and added submenu item in appearance menu.
		 * @return void
		 */
		public function options_menu_item() {
			remove_menu_page( $this->constant[ 'MM_OPTIONS_NAME' ] );
			add_menu_page(
				$this->constant[ 'MM_WARE_NAME' ], 
				$this->constant[ 'MM_WARE_NAME' ], 
				'edit_theme_options',
				$this->constant[ 'MM_OPTIONS_NAME' ],
				array( $this, 'options_page' ),
				$this->constant[ 'MM_WARE_SRC_URL' ] . '/img/megamain-logo-120x120.png'
			);
			register_setting(
				$this->constant[ 'MM_WARE_SLUG' ] . '_options_group', 
				$this->constant[ 'MM_OPTIONS_NAME' ]
			);
		}

		/** 
		 * Build theme options page with menu items and sections.
		 * @return $out
		 */
		public function options_page() {
			$out = '';
			$submit_button = mad_mm_common::ntab(7) . '<input type="submit" class="button-primary pull-right" value="' . __( 'Save All Changes', $this->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '" />';
			$theme_meta = mad_mm_common::ntab(7) . '<div>' . mad_mm_common::ntab(8) . '<span class="theme_name">' . __(  $this->constant[ 'MM_WARE_NAME' ] , $this->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '</span>' . ' <small>v' . $this->constant[ 'MM_WARE_VERSION' ] . mad_mm_common::ntab(7) . '</small></div>';
			$out .= mad_mm_common::ntab(1) . '<div class="wrap bootstrap">';
			$out .= mad_mm_common::ntab(2) . '<div class="mm_theme_page">';
			$out .= mad_mm_common::ntab(3) . '<form id="'. $this->constant[ 'MM_WARE_PREFIX' ] . '_theme_options_form" class="mm_theme_options_form" method="post" action="options.php" enctype="multipart/form-data">';
			$out .= mad_mm_common::ntab(4) . '<div class="save_shanges row no_x_margin">';
			$out .= mad_mm_common::ntab(5) . '<div class="col-xs-12">';
			$out .= mad_mm_common::ntab(6) . '<div class="float_holder">';
			$out .= $submit_button;
			$out .= $theme_meta;
			$out .= mad_mm_common::ntab(6) . '</div>';
			$out .= mad_mm_common::ntab(5) . '</div>';
			$out .= mad_mm_common::ntab(4) . '</div>';
			$out .= mad_mm_common::ntab(4) . '<input type="hidden" name="' . $this->constant[ 'MM_OPTIONS_NAME' ] . '[last_modified]" value="' . ( time() + 20 ) . '" />';
			ob_start();
			settings_fields( $this->constant[ 'MM_WARE_SLUG' ] . '_options_group' );
			$out .= mad_mm_common::ntab(4) . ob_get_contents();
			ob_end_clean();
			$out .= mad_mm_common::ntab(4) . '<div class="mm_theme_options row bootstrap no_x_margin">';
			$out .= mad_mm_common::ntab(5) . '<ul id="mm_navigation" class="mm_navigation nav nav-tabs col-lg-2 col-sm-3 col-xs-12">';
			$ware_options_array = $this->constant[ 'MM_WARE_SLUG' ] . '__array_theme_options' ;
			foreach ( $ware_options_array( $this->constant ) as $key => $section ) {
				$out .= mad_mm_common::ntab(6) . '<li class="menu_item' . ( ( $key == 0) ? ' active' : '' ) . '">';
				$out .= mad_mm_common::ntab(7) . '<a href="#' . $section['key'] . '" data-toggle="tab"><i class="' . ( ( isset( $section['icon'] ) ) ? $section['icon'] : 'empty-icon' ) . '"></i> ' . $section['title'] . '</a></li>';
				$out .= mad_mm_common::ntab(6) . '</li>';
			}
			$out .= mad_mm_common::ntab(5) . '</ul><!-- class="mm_navigation" -->';
			$out .= mad_mm_common::ntab(5) . '<div id="mm_content" class="tab-content mm_content col-lg-10 col-sm-9 col-xs-12">';
			foreach ( $ware_options_array( $this->constant ) as $key => $section ) {
				$out .= mad_mm_common::ntab(6) . '<div class="tab-pane' . ( ( $key == 0) ? ' active in' : '' ) . '" id="' . $section['key'] . '">';
				foreach ( $section['options'] as $option ) {
					$option['key'] = isset( $option['key'] ) ? $option['key'] : 'key_no_set';
					$mmm_saved_value = isset( $this->saved_options[ $option[ 'key' ] ] )
						? $this->saved_options[ $option[ 'key' ] ] 
						: false;
					$option['key'] = $this->constant[ 'MM_OPTIONS_NAME' ] . '[' . $option['key'] . ']';
					$out .= $this->options_generator( $option, $mmm_saved_value );
				}
				$out .= mad_mm_common::ntab(6) . '</div><!-- class="tab-pane" id="' . $section['key'] . '" -->';
			}
			$out .= mad_mm_common::ntab(5) . '</div><!-- id="mm_content" class="tab-content" -->';
			$out .= mad_mm_common::ntab(4) . '</div><!-- class="mm_theme_options" -->';
			$out .= mad_mm_common::ntab(4) . '<div class="save_shanges row no_x_margin">';
			$out .= mad_mm_common::ntab(5) . '<div class="col-xs-12">';
			$out .= mad_mm_common::ntab(6) . '<div class="float_holder">';
			$out .= $submit_button;
			$out .= mad_mm_common::ntab(6) . '</div>';
			$out .= mad_mm_common::ntab(5) . '</div>';
			$out .= mad_mm_common::ntab(4) . '</div>';
			$out .= mad_mm_common::ntab(3) . '</form>';
			$out .= mad_mm_common::ntab(2) . '</div><!--  class="mm_theme_page" -->';
			$out .= mad_mm_common::ntab(1) . '</div><!-- class="wrap" -->';
			echo $out; // general out
		}


		/** 
		 * Register (add) meta boxes.
		 * @return void
		 */
		public function meta_box_generator () {
			$ware_meta_boxes_array = $this->constant[ 'MM_WARE_SLUG' ] . '__array_meta_boxes' ;
			foreach ( $ware_meta_boxes_array( $this->constant ) as $key => $meta_box ) {
				/* if "post_type" variable is array do foreach */
				if ( is_array( $meta_box['post_type'] ) ) {
					foreach ( $meta_box['post_type'] as $post_type ) {
						add_meta_box( 
							$meta_box['key'],
							$meta_box['title'],
							array( $this, 'meta_options_generator'),
							$post_type,
							$meta_box['context'],
							$meta_box['priority'], 
							$meta_box['options']
						);
					}
				/* if "post_type" variable set "all_post_types" add this meta box for all types of posts */
				} elseif ( $meta_box['post_type'] == 'all_post_types' && get_post_type() != 'attachment' ) {
					add_meta_box( 
						$meta_box['key'],
						$meta_box['title'],
						array( $this, 'meta_options_generator'),
						get_post_type(),
						$meta_box['context'],
						$meta_box['priority'], 
						$meta_box['options']
					);
				/* else "post_type" variable contain sting value and we add this meta box for one type of posts */
				} else {
					add_meta_box( 
						$meta_box['key'],
						$meta_box['title'],
						array( $this, 'meta_options_generator'),
						$meta_box['post_type'],
						$meta_box['context'],
						$meta_box['priority'], 
						$meta_box['options']
					);
				}
			}
		}

		/** 
		 * Generator for meta fields.
		 * @return $out
		 */
		public function meta_options_generator ( $object, $options ) {
			$out = '';
			foreach ( $options['args'] as $key => $option ) {
				$option['key'] = ( isset( $option['key'] ) ) ? $option['key'] : '';
				$option['name'] = ( isset( $option['name'] ) ) ? $option['name'] : '';
				$option['descr'] = ( isset( $option['descr'] ) ) ? $option['descr'] : '';
				$option['type'] = ( isset( $option['type'] ) ) ? $option['type'] : '';
				$option['values'] = ( isset( $option['values'] ) ) ? $option['values'] : '';
				$option['default'] = ( isset( $option['default'] ) ) ? $option['default'] : '';
				$mmm_saved_value = get_post_meta( $object->ID, 'mm_' . $option['key'], true ) 
					? get_post_meta( $object->ID, 'mm_' . $option['key'], true ) 
					: get_post_meta( $object->ID, 'mmpm' . '_' . $option['key'], true ); // migrator
				$option['key'] = ( isset( $option['key'] ) ) ? 'mm_' .$option['key'] : '';
				$out .= $this->options_generator( $option, $mmm_saved_value );
			}
			echo $out;
			wp_nonce_field( basename( __FILE__ ), 'mm_meta_nonce' );
		}

		/** 
		 * Save the meta box's post metadata.
		 * @return void
		 */
		public function save_meta_options( $post_id, $post ) {
			/* Verify the nonce before proceeding. */
			if ( !isset( $_POST[ 'mm_meta_nonce'] ) || !wp_verify_nonce( $_POST[ 'mm_meta_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );
			/* Check if the current user has permission to edit the post. */
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}
			$ware_meta_boxes_array = $this->constant[ 'MM_WARE_SLUG' ] . '__array_meta_boxes' ;
			foreach ( $ware_meta_boxes_array( $this->constant ) as $meta_box ) {
				/* Check if current post_type isset in meta options array. */
				if ( $meta_box['post_type'] == $post->post_type || $meta_box['post_type'] == 'all_post_types' || ( is_array( $meta_box['post_type'] ) && in_array( $post->post_type, $meta_box['post_type'] ) ) ) {
					foreach ( $meta_box['options'] as $key => $option ) {
						/* Get the posted data and sanitize it for use as an HTML class. */
						$new_meta_value = ( isset( $_POST[ 'mm_' . $option['key'] ] ) ? $_POST[ 'mm_' . $option['key'] ] : '' );
						/* Get the meta key. */
						$meta_key = 'mm_' . $option['key'];
						/* Get the meta value of the custom field key. */
						$meta_value = get_post_meta( $post_id, $meta_key, true );
						/* If a new meta value was added and there was no previous value, add it. */
						if ( $new_meta_value && '' == $meta_value ) {
							add_post_meta( $post_id, $meta_key, $new_meta_value, true );
						}
						/* If the new meta value does not match the old value, update it. */
						elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
							update_post_meta( $post_id, $meta_key, $new_meta_value );
						}
						/* If there is no new meta value but an old value exists, delete it. */
						elseif ( '' == $new_meta_value && $meta_value ) {
							delete_post_meta( $post_id, $meta_key, $meta_value );
						}
					}
				}
			}
		}

		/** 
		 * register and call styles and sctipts for frontend or backend.
		 * @return void
		 */
		public function load_all_src ( $args ) {
			$ware_src_array = $this->constant[ 'MM_WARE_SLUG' ] . '__array_src';
			$ware_src_array = $ware_src_array( $this->constant );
			if ( function_exists( 'wp_script_is' ) && !wp_script_is( 'jquery', 'queue' ) ) {
				wp_register_script( 'jquery', '/wp-includes/js/jquery/jquery.js' , false, false );
				wp_enqueue_script( 'jquery' );
			}
			if ( is_admin() ) {
				$ware_src_array[ 'backend' ][ 'supported_pages' ];
				foreach ( $ware_src_array[ 'backend' ][ 'css' ] as $key => $value ) {
					wp_register_style( $key, $this->constant[ 'MM_WARE_CSS_URL' ] . $value, array(), $this->constant[ 'MM_WARE_VERSION' ] );
					wp_enqueue_style( $key );
				}
				if ( isset( $ware_src_array[ 'backend' ][ 'supported_pages' ] ) && !in_array( $args, $ware_src_array[ 'backend' ][ 'supported_pages' ] ) ) {
					return false;
				}
				foreach ( $ware_src_array[ 'backend' ][ 'js' ] as $key => $value ) {
					if ( $value != '' ) {
						wp_register_script( $key, $this->constant[ 'MM_WARE_JS_URL' ] . $value, 'jquery', false, true );
					}
					wp_enqueue_script( $key );
				}
			} else {
				foreach ( $ware_src_array[ 'frontend' ][ 'css' ] as $key => $value ) {
					wp_register_style( $key, $this->constant[ 'MM_WARE_CSS_URL' ] . $value, array(), $this->constant[ 'MM_WARE_VERSION' ] );
					wp_enqueue_style( $key );
				}
				foreach ( $ware_src_array[ 'frontend' ][ 'js' ] as $key => $value ) {
					if ( $value != '' ) {
						wp_register_script( $key, $this->constant[ 'MM_WARE_JS_URL' ] . $value, 'jquery', false, true );
					}
					wp_enqueue_script( $key );
				}
			}
		}

	} // end of class
} // end of if class_exist

?>