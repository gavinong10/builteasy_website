<?php
/*
Plugin Name: LeadPages connector
Plugin URI: http://www.leadpages.net/
Description: LeadPages connector plugin
Author: LeadPages
Version: 1.2.0.4
Author URI: http://www.leadpages.net/
 */

defined( 'ABSPATH' ) or die( "This page intentionally left blank." );
if ( ! class_exists( 'LeadPages' ) ) {
	define( 'LEADPAGES_ABS_URL', plugin_dir_url( __FILE__ ) );


	class LeadPages {
		const wp_version_required = '3.4';
		const php_version_required = '5.2';

		private static $api_key = false;
		private static $api_url = false;


		function leadpages_permalink( $url, $post ) {
			if ( 'leadpages_post' == get_post_type( $post ) ) {
				$path = esc_html( get_post_meta( $post->ID, 'leadpages_slug', true ) );
				if ( $path != '' ) {
					return site_url() . '/' . $path;
				} else {
					return '';
				}
			}

			return $url;
		}

		function LeadPages() {
			add_filter( 'post_type_link', array( &$this, 'leadpages_permalink' ), 99, 2 );

			$compat_status = self::compatibility();

			if ( $compat_status !== true ) {
				self::show_message( false, $compat_status );

				return;
			}

			if ( get_option( 'permalink_structure' ) == '' ) {
				self::show_message( false, 'LeadPages plugin needs <a href="options-permalink.php">permalinks</a> enabled!' );

				return;
			}

			self::register_auto_update();

			if ( ! self::update_settings() ) {
				return;
			}
			if ( is_admin() ) {
				add_action( 'init', array( &$this, 'leadpages_post_register' ) );
				add_action( 'add_meta_boxes', array( &$this, 'add_custom_meta_box' ) );
				add_action( 'save_post', array( &$this, 'save_custom_meta' ), 10, 2 );
				add_action( 'save_post', array( &$this, 'validate_custom_meta' ), 20, 2 );
				add_filter( 'manage_edit-leadpages_post_columns', array( &$this, 'my_columns' ) );
				add_action( 'manage_posts_custom_column', array( &$this, 'populate_columns' ) );
				add_filter( 'post_updated_messages', array( &$this, 'updated_message' ) );
				add_filter( 'display_post_states', array( &$this, 'custom_post_state' ) );
				add_filter( 'post_row_actions', array( &$this, 'remove_quick_edit' ), 10, 2 );
				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts_n_styles' ), 11 );
				add_action( 'trashed_post', array( &$this, 'post_trash' ), 10 );
				add_action( 'save_post', array( &$this, 'custom_post_type_title' ), 10 );
			}

			add_filter( 'the_posts', array( &$this, 'check_custom_leadpage_url' ), 1 );
			add_action( 'parse_request', array( &$this, 'check_root' ), 1 );
			add_action( 'template_redirect', array( &$this, 'check_404_page' ), 1 );
		}

		function post_trash( $pid ) {
			if ( $pid == get_option( 'leadpages_404_page_id' ) ) {
				update_option( 'leadpages_404_page_id', false );
			}
		}

		function check_404_page() {
			$nf = self::get_404_lead_page();
			if ( $nf === false ) {
				return;
			}
			if ( is_404() ) {
				$nfid = self::get_404_lead_page();
				self::display_custom_404_leadpage( $nfid );
			}
		}

		public static function compatibility() {
			global $wp_version;
			// Check wordpress version
			if ( version_compare( self::wp_version_required, $wp_version, '>' ) ) {
				return 'LeadPages plugin requires that you are using a Wordpress minimum version of ' . self::wp_version_required;
			}

			if ( version_compare( self::php_version_required, phpversion(), '>' ) ) {
				return 'LeadPages requires that you are using a PHP minimum version of ' . self::php_version_required;
			}

			return true;
		}

		function enqueue_scripts_n_styles() {
			global $post_type;
			if ( 'leadpages_post' == $post_type ) {
				self::load_custom_wp_admin_style();
			}
		}

		function custom_post_type_title( $post_id ) {
			global $wpdb, $post_type;
			if ( 'leadpages_post' == $post_type ) {
				$slug  = get_post_meta( $post_id, 'leadpages_slug', true );
				$title = '/' . $slug;
				$where = array( 'ID' => $post_id );
				$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
			}
		}

		function remove_meta_boxes() {
			global $wp_meta_boxes;

			foreach ( $wp_meta_boxes as $k => $v ) {
				foreach ( $wp_meta_boxes[ $k ] as $j => $u ) {
					foreach ( $wp_meta_boxes[ $k ][ $j ] as $l => $y ) {
                        // XXX: Is this use of $y on the next line correct?
						foreach ( $wp_meta_boxes[ $k ][ $j ][ $l ] as $m => $y ) {
							if ( $m != 'leadpages_meta_box' ) {
								unset( $wp_meta_boxes[ $k ][ $j ][ $l ][ $m ] );
							}
						}
					}
				}
			}

			return;
		}

		function check_root() {
			if ( is_admin() ) {
				return;
			}
			// current for front page override
			$front = self::get_front_lead_page();
			$wg    = self::get_wg_lead_page();
			// check if already redirected before
			if ( self::is_wg_cookie_active() ) {
				$wg = false;
			}
			if ( false === $front && false === $wg ) {
				return;
			}
			// get current full url
			$current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			// calculate the path
			$part = substr( $current, strlen( site_url() ) );
			// remove any variables from the url
			$pos       = strpos( $part, '?' );
			$rest_part = false;
			if ( false !== $pos ) {
				$rest_part = substr( $part, $pos, strlen( $part ) );
				$part = substr($part, 0, $pos);
			}
			$search = false;
			if (isset($_REQUEST['s']) && '' != $_REQUEST['s']) {
				$search = true;
			}
			$preview = false;
			if (isset($_REQUEST['p']) && '' != $_REQUEST['p']) {
				$preview = true;
			}
			// display the homepage if enabled
			if (!$search && !$preview && ('' === $part || 'index.php' == $part || '/' == $part || '/index.php' == $part)) {
				// take action
				if ( false !== $wg ) {
					$mp = self::get_page_by_id( $wg );
					if ( false !== $mp && 'publish' == $mp->post_status ) {
						$wgurl = site_url() . '/' . $mp->slug;
						if ( $rest_part !== false ) {
							$wgurl .= '/' . $rest_part;
						}
						self::welcome_gate_redirect( $wgurl, ( $front !== false ) );
					}
				}
				if ( false !== $front ) {
					$mp = self::get_page_by_id( $front );
					if ( false !== $mp && 'publish' == $mp->post_status ) {
						// get and display the page at root
						if ( $mp->lp_split_test ) {
							$html = self::get_variation_html( $mp->lp_id );
						} else {
							$html = self::get_page_html( $mp->lp_id );

							if ( is_array( $html ) ) {
								$html = self::get_variation_html( $mp->lp_id );
							}
						}
						if ( ob_get_length() > 0 ) {
							ob_end_clean();
						}
						// flush previous cache
						if ( ! ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) && ob_start( "ob_gzhandler" ) ) ) {
							ob_start();
						}
						status_header( '200' );
						print $html;
						ob_end_flush();
						die();
					}
				}
			}
		}

		function is_wg_cookie_active() {
			return ( isset( $_COOKIE['leadpages-welcome-gate-displayed'] ) && $_COOKIE['leadpages-welcome-gate-displayed'] == '1' );
		}

		private static $js_redirect_target = false;

		function get_javascript_redirect_link() {
			$v         = self::get_url_ver();
			$admin_url = LEADPAGES_ABS_URL . 'admin/';

			return $admin_url . 'js/welcomegate_fwd_leadpages.js' . $v;
		}

		function welcome_gate_redirect( $link, $frontpage_active ) {
			$javascript = ( self::get_redirect_method() == 'js' );
			if ( $javascript ) {
				// JavaScript Redirect
				$url = self::get_javascript_redirect_link();
				if ( $frontpage_active ) {
					self::$js_redirect_target = $link;

					return;
				} else {
					wp_register_script( 'leadpages_welcomegate_fwd', $url );
					wp_enqueue_script( 'leadpages_welcomegate_fwd' );
					wp_localize_script( 'leadpages_welcomegate_fwd', 'leadpages_wg_redirect_path', $link );

					return;
				}
			} else {
				// HTTP Redirect
				if ( ob_get_length() > 0 ) {
					ob_end_clean();
				}
				setcookie( 'leadpages-welcome-gate-displayed', '1', time() + 60 * 60 * 24 * 365 );
				self::no_cache();
				header( 'Location: ' . $link, true, 307 );
				die();
			}
		}

		function no_cache() {
			header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );
			header( 'X-Random-Header: ' . ( rand() + time() ) );
		}

		function update_settings() {
			$ak = WP_PLUGIN_DIR . '/leadpages/api_key.php';

			if ( file_exists( $ak ) ) {
				require_once( $ak );
				if ( defined( 'PRIVATE_LEADPAGES_API_KEY' ) ) {
					$old = self::get_api_key();
					if ( $old != PRIVATE_LEADPAGES_API_KEY ) {
						self::set_api_key( PRIVATE_LEADPAGES_API_KEY );
					}
				}
			}

			self::$api_key = self::get_api_key();
			self::$api_url = 'http://lead-pages.appspot.com/api/';

			if ( defined( 'PRIVATE_LEADPAGES_API_URL' ) ) {
				self::$api_url = PRIVATE_LEADPAGES_API_URL;
			}

			if ( self::$api_key == false ) {
				self::show_message( false, 'Missing LeadPages API key! Please deactivate and delete this plugin, re-download the plugin and install again or follow our <a href="https://support.leadpages.net/entries/21804555-Missing-LeadPages-API-key-Please-contact-support-Error-Message" target="_blank">instructions</a>.' );

				return false;
			}

			return true;
		}

		private static $message = false;

		function show_message( $not_error, $message ) {
			self::$message = $message;
			if ( $not_error ) {
				add_action( 'admin_notices', array( &$this, 'showMessage' ) );
			} else {
				add_action( 'admin_notices', array( &$this, 'showErrorMessage' ) );
			}
		}

		function showMessage() {
			echo '<div id="message" class="updated">';
			echo '<p><strong>' . self::$message . '</strong></p></div>';
		}

		function showErrorMessage() {
			echo '<div id="message" class="error">';
			echo '<p><strong>' . self::$message . '</strong></p></div>';
		}

		public static function set_api_key( $key ) {
			update_option( 'leadpages_private_api_key', $key );
		}

		public static function get_api_key() {
			return get_option( 'leadpages_private_api_key', false );
		}

		function remove_quick_edit( $actions ) {
			global $post;
			if ( $post->post_type == 'leadpages_post' ) {
				unset( $actions['inline hide-if-no-js'] );
			}

			return $actions;
		}

		function custom_post_state( $states ) {
			global $post;
			if ( $post->post_type == 'leadpages_post' ) {
				$states = array();
			}

			return $states;
		}

		function updated_message( $m ) {
			$lpid = get_post_meta( get_the_ID(), 'leadpages_my_selected_page', true );
			if ( $lpid == "" ) {
				return $m;
			}
			$a             = '';
			$is_front_page = self::is_front_page( get_the_ID() );
			if ( $is_front_page ) {
				$url = site_url() . '/';
				$a   = ' <a href="' . $url . '" target="_blank">View</a>';
			} else {
				$path = esc_html( get_post_meta( get_the_ID(), 'leadpages_slug', true ) );
				if ( $path != '' ) {
					$url = site_url() . '/' . $path;
					$a   = ' <a href="' . $url . '" target="_blank">View</a>';
				}
			}

			$m['post'][1]  = 'LeadPage updated.' . $a;
			$m['post'][4]  = 'LeadPage updated.' . $a;
			$m['post'][6]  = 'LeadPage saved.' . $a;
			$m['post'][10] = 'LeadPage updated.' . $a;

			return $m;
		}

		function my_columns( $columns ) {
			$cols                        = array();
			$cols['cb']                  = $columns['cb'];
			$cols['leadpages_post_name'] = 'Name';
			$cols['leadpages_post_type'] = 'Type';
			$cols['leadpages_post_path'] = 'Url';
			$cols['date']                = 'Date';

			return $cols;
		}

		function populate_columns( $column ) {
			$path          = esc_html( get_post_meta( get_the_ID(), 'leadpages_slug', true ) );
			$is_front_page = self::is_front_page( get_the_ID() );
			$is_wg_page    = self::is_wg_page( get_the_ID() );
			$is_nf_page    = self::is_404_page( get_the_ID() );
			if ( 'leadpages_post_type' == $column ) {
				if ( $is_front_page ) {
					echo '<strong style="color:#003399">Home Page</strong>';
				} elseif ( $is_wg_page ) {
					echo '<strong style="color:#0000ff">Welcome Gate&trade;</strong>';
				} elseif ( $is_nf_page ) {
					echo '<strong style="color:#F89406">404 Page</strong>';
				} else {
					echo 'Normal';
				}
			}
			if ( 'leadpages_post_path' == $column ) {
				if ( $is_front_page ) {
					$url = site_url() . '/';
					echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
				} elseif ( $is_nf_page ) {
					$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$randomString = '';
					$length       = 10;
					for ( $i = 0; $i < $length; $i ++ ) {
						$randomString .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
					}
					$url = site_url() . '/random-test-url-' . $randomString;
					echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
				} else {
					if ( $path == '' ) {
						echo '<strong style="color:#ff3300">Missing path!</strong> <i>Page is not active</i>';
					} else {
						$url = site_url() . '/' . $path;
						echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
					}
				}
			}
			if ( 'leadpages_post_name' == $column ) {
				$url    = get_edit_post_link( get_the_ID() );
				$p_name = get_post_meta( get_the_ID(), 'leadpages_name', true );
				echo '<strong><a href="' . $url . '">' . $p_name . '</a></strong>';
			}
		}

		function get_url_ver() {
			return '?lp-ver=' . self::_plugin_get( 'Version' );
		}

		function _plugin_get( $i ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_folder = get_plugins( '/leadpages' );

			return $plugin_folder['leadpages.php'][ $i ];
		}

		function load_custom_wp_admin_style() {
			$v         = self::get_url_ver();
			$admin_url = LEADPAGES_ABS_URL . 'admin/';
			wp_register_script( 'leadpages_jquery', $admin_url . 'js/jquery_leadpages.js' . $v );
			wp_register_script( 'leadpages_bootstrap', $admin_url . 'js/twitter_bootstrap_leadpages.js' . $v, array( 'leadpages_jquery' ) );
			wp_register_script( 'leadpages_admin', $admin_url . 'js/admin_leadpages.js' . $v, array(
				'leadpages_jquery',
				'leadpages_bootstrap'
			) );
			wp_enqueue_script( 'leadpages_jquery' );
			wp_enqueue_script( 'leadpages_bootstrap' );
			wp_enqueue_script( 'leadpages_admin' );
			wp_register_style( 'leadpages_bootstrap', $admin_url . 'css/twitter_bootstrap_leadpages.css' . $v );
			wp_register_style( 'leadpages_admin', $admin_url . 'css/admin_leadpages.css' . $v );
			wp_enqueue_style( 'leadpages_bootstrap' );
			wp_enqueue_style( 'leadpages_admin' );
		}

		private static $_posts = false;

		function get_page_by_id( $post_id ) {
			$res = get_post( $post_id );
			if ( empty( $res ) ) {
				return false;
			}
			$url                = get_post_meta( $post_id, 'leadpages_url', true );
			$slug               = get_post_meta( $post_id, 'leadpages_slug', true );
			$lpid               = get_post_meta( $post_id, 'leadpages_my_selected_page', true );
			$lpst               = get_post_meta( $post_id, 'leadpages_split_test', true );
			$res->lp_id         = $lpid;
			$res->lp_url        = $url;
			$res->slug          = $slug;
			$res->lp_split_test = $lpst;

			return $res;
		}

		function get_my_posts() {
			global $wpdb;

			$sql = "SELECT {$wpdb->posts}.ID, {$wpdb->postmeta}.meta_key, {$wpdb->postmeta}.meta_value FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id) WHERE ({$wpdb->posts}.post_type = %s) AND ({$wpdb->posts}.post_status = 'publish') AND ({$wpdb->postmeta}.meta_key IN ('leadpages_my_selected_page', 'leadpages_name', 'leadpages_my_selected_page', 'leadpages_slug', 'leadpages_split_test'))";

			$rows  = $wpdb->get_results( $wpdb->prepare( $sql, 'leadpages_post' ) );
			$posts = array();
			foreach ( $rows as $k => $row ) {
				if ( ! array_key_exists( $row->ID, $posts ) ) {
					$posts[ $row->ID ] = array();
				}
				$posts[ $row->ID ][ $row->meta_key ] = $row->meta_value;
			}

			return $posts;
		}

		function get_all_posts() {
			if ( self::$_posts != false ) {
				return self::$_posts;
			}
			$front = self::get_front_lead_page();
			$p     = self::get_my_posts();
			$res   = array();
			foreach ( $p as $k => $v ) {
				if ( $front == $k ) {
					continue;
				}

				$res[ $v['leadpages_slug'] ] = array(
					'post_id'    => $k,
					'id'         => $v['leadpages_my_selected_page'],
					'name'       => $v['leadpages_name'],
					'split_test' => isset( $v['leadpages_split_test'] ) && $v['leadpages_split_test']
				);
			}
			self::$_posts = $res;

			return $res;
		}

		function check_custom_leadpage_url( $posts ) {
			if ( is_admin() ) {
				return $posts;
			}

			// Determine if request should be handled by this plugin
			$requested_page = self::parse_request();
			if ( false == $requested_page ) {
				return $posts;
			}

			$id = $requested_page["id"];

			// show the LeadPage
			if ( $requested_page['split_test'] ) {
				$html = self::get_variation_html( $id );
			} else {
				$html = self::get_page_html( $id );

				if ( is_array( $html ) ) {
					update_post_meta( $id, "leadpages_split_test", true );
					$html = self::get_variation_html( $id );
				}
			}
			if ( ob_get_length() > 0 ) {
				ob_end_clean();
			}
			status_header( '200' );

			if ( ! class_exists( 'Scarcity_Samurai' ) ) {
				print $html;
			} else {
				// following lines are experimental to support scarcity samurai!!!
				$html = str_replace( '</body>', '', $html );
				print $html;
				$r = Scarcity_Samurai::add_banners_css();
				print "<link rel='stylesheet' id='ss-banner-styles-css'  href='$r[0]' type='text/css' media='all' />";
				print "<style type='text/css'>$[1]</style>";
				wp_footer();
				print "</body>";
			}
			die();
		}

		function display_custom_404_leadpage( $id_404 ) {
			// show the LeadPage
			$mp = self::get_page_by_id( $id_404 );
			if ( ! $mp ) {
				return;
			}
			if ( $mp->lp_split_test ) {
				$html = self::get_variation_html( $mp->lp_id );
			} else {
				$html = self::get_page_html( $mp->lp_id );
			}
			if ( ob_get_length() > 0 ) {
				ob_end_clean();
			}
			status_header( '404' );
			print $html;
			die();
		}

		function parse_request() {
			$posts = self::get_all_posts();
			if ( ! is_array( $posts ) ) {
				return false;
			}
			// get current url
			$current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			// calculate the path
			$part = substr( $current, strlen( site_url() ) );
			if ( $part[0] == '/' ) {
				$part = substr( $part, 1 );
			}
			// strip parameters
			$real   = explode( '?', $part );
			$tokens = explode( '/', $real[0] );
			if ( array_key_exists( $tokens[0], $posts ) ) {
				if ( $tokens[0] == '' ) {
					return false;
				}

				return $posts[ $tokens[0] ];
			}

			return false;
		}

		function leadpages_post_register() {
			//commenting out, we really don't need to check for updates on every page load
			//self::silent_update_check();
			$labels = array(
				'name'               => _x( 'LeadPages', 'post type general name' ),
				'singular_name'      => _x( 'LeadPage', 'post type singular name' ),
				'add_new'            => _x( 'Add New', 'leadpage' ),
				'add_new_item'       => __( 'Add New LeadPage' ),
				'edit_item'          => __( 'Edit LeadPage' ),
				'new_item'           => __( 'New LeadPage' ),
				'view_item'          => __( 'View LeadPages' ),
				'search_items'       => __( 'Search LeadPages' ),
				'not_found'          => __( 'Nothing found' ),
				'not_found_in_trash' => __( 'Nothing found in Trash' ),
				'parent_item_colon'  => ''
			);
			$args   = array(
				'labels'               => $labels,
				'description'          => 'Allows you to have LeadPages on your WordPress site.',
				'public'               => false,
				'publicly_queryable'   => true,
				'show_ui'              => true,
				'query_var'            => true,
				'menu_icon'            => plugin_dir_url( __FILE__ ) . 'admin/img/menu-icon.png',
				'capability_type'      => 'page',
				'menu_position'        => null,
				'rewrite'              => false,
				'can_export'           => false,
				'hierarchical'         => false,
				'has_archive'          => false,
				'supports'             => array(
					'leadpages_my_selected_page',
					'leadpages_slug',
					'leadpages_name',
					'leadpages_url'
				),
				'register_meta_box_cb' => array( &$this, 'remove_meta_boxes' )
			);
			register_post_type( 'leadpages_post', $args );
		}

		// Add the Meta Box
		function add_custom_meta_box() {
			//self::silent_update_check();
			add_meta_box(
				'leadpages_meta_box', // $id
				'Configure your LeadPage', // $title
				array( &$this, 'show_custom_meta_box' ), // $callback
				'leadpages_post', // $page
				'normal', // $context
				'high' // $priority
			);
		}

		private static $_mypages = false;

		function format_error( $msg ) {
			return <<<EOT
<!DOCTYPE html>
<html>
  <head>
    <title>LeadPages&trade; Alert</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <style type="text/css">
      body { padding-top: 40px; padding-bottom: 40px; background-color: #f5f5f5; }
      .error-box { max-width: 300px; padding: 19px 29px 29px; margin: 0 auto 20px; background-color: #fff; border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05); -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05); box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
    </style>
  </head>
  <body>
    <div class="container error-box">
        <h3><a href="https://www.leadpages.net/">LeadPages&trade;</a> Alert</h3>
        <div class="alert alert-error">$msg</div>
        <div>
            <a href="http://www.leadpages.net/">LeadPages&trade;</a>
            <a href="https://support.leadpages.net/" class="pull-right">Support</a>
        </div>
    </div>
  </body>
</html>
EOT;
		}

		function fix_html_head( $html ) {
			if ( self::$js_redirect_target !== false ) {
				$js   = <<<EOT
function setCookie(name,value,days){if(days){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));var expires="; expires="+date.toGMTString();}
else var expires="";document.cookie=name+"="+value+expires+"; path=/";}
function getCookie(name){var nameEQ=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length);}
return null;}
var c=getCookie('leadpages-welcome-gate-displayed');if(c!='1'){setCookie('leadpages-welcome-gate-displayed','1',365);window.location=leadpages_wg_redirect_path;throw new Error("Redirecting...");}
EOT;
				$head = '<head$1><script type="text/javascript"> var leadpages_wg_redirect_path = "' . self::$js_redirect_target . '"; ' . $js . '; </script>';
				$html = preg_replace( '#<head([^>]*)>#si', $head, $html );
			}

			return $html;
		}

		function get_variation_html( $lpid ) {
			$cookieName = "head-idx-${lpid}";
			$request    = array( 'leadpage-id' => $lpid );
			if ( isset( $_COOKIE[ $cookieName ] ) ) {
				$head_idx = $_COOKIE[ $cookieName ];
				$cache    = get_site_transient( "leadpages_variation_html_cache_${lpid}_${head_idx}" );
				if ( $cache && ! is_user_logged_in() ) {
					return self::fix_html_head( $cache );
				}
				$request['leadpage-head-idx'] = $_COOKIE[ $cookieName ];
			}
			$result = self::leadpages_api_call( 'page-html', $request );
			$result = $result[0];
			$data   = $result['data'];
			$html   = $data['html'];
			if ( $result === false ) {
				return self::format_error( 'Alert! There was a problem while connecting to LeadPages server! Message: ' . $result[1] . ' ' );
			}
			setcookie(
				$cookieName,
				$data['head_idx'],
				time() + 3600 * 24 * 30,
				COOKIEPATH,
				COOKIE_DOMAIN,
				false
			);
			set_site_transient(
				"leadpages_variation_html_cache_${lpid}_${data['head_idx']}",
				$html,
				600
			);

			return self::fix_html_head( $html );
		}

		function get_page_html( $lpid ) {
			$cache = get_site_transient( 'leadpages_page_html_cache_' . $lpid );
			if ( $cache && ! is_user_logged_in() ) {
				return self::fix_html_head( $cache );
			}
			$result = self::leadpages_api_call( 'page-html', array( 'leadpage-id' => $lpid ) );
			$res    = $result[0];
			if ( $res === false ) {
				delete_site_transient( 'leadpages_page_html_cache_' . $lpid );

				return self::format_error( 'Alert! There was a problem while connecting to LeadPages server! Message: ' . $result[1] . ' ' );
			}
			set_site_transient( 'leadpages_page_html_cache_' . $lpid, $res['data'], 600 );

			return self::fix_html_head( $res['data'] );
		}

		function leadpages_api_call( $method, $extra_input = array() ) {
			$url       = self::$api_url;
			$body      = array(
				'api_key' => self::$api_key,
				'method'  => $method
			);
			$full_body = array_merge( $body, $extra_input );
			$response  = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 70,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => $full_body,
					'cookies'     => array()
				)
			);
			if ( is_wp_error( $response ) ) {
				return array( false, $response->get_error_message() );
			}
			if ( isset( $response['response']['code'] ) ) {
				$code_char = substr( $response['response']['code'], 0, 1 );
			} else {
				$code_char = '5';
			}
			if ( $code_char == '5' || $code_char == '4' ) {
				return array( false, $response['response']['message'] );
			}
			$res = json_decode( $response['body'], true );
			if ( ! is_array( $res ) ) {
				return array( false, 'Unexpected response. Failed to decode JSON.' );
			}
			if ( isset( $res['result'] ) && $res['result'] == 'ko' ) {
				return array( false, $res['message'] );
			}

			return array( $res, 'Everything is good!' );
		}

		function load_my_pages() {
			if ( self::$_mypages != false ) {
				return array( self::$_mypages, 'Everything is good!' );
			}
			$result = self::leadpages_api_call( 'my-pages' );
			$res    = $result[0];
			if ( $res === false ) {
				return array( false, $result[1] );
			}
			$pages = array();
			foreach ( $res['data'] as $k => $v ) {
				$pages[ $v['id'] ] = $v;
			}
			self::$_mypages = $pages;

			return array( $pages, 'Everything is good!' );
		}

		public static function is_page_mode_active( $new_edit = null ) {
			global $pagenow;
			// make sure we are on the backend
			if ( ! is_admin() ) {
				return false;
			}

			if ( $new_edit == "edit" ) {
				return in_array( $pagenow, array( 'post.php', ) );
			} elseif ( $new_edit == "new" ) { // check for new post page
				return in_array( $pagenow, array( 'post-new.php' ) );
			} else { // check for either new or edit
				return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
			}
		}

		public static function get_front_lead_page() {
			$v = get_option( 'leadpages_front_page_id', false );

			return ( $v == '' ) ? false : $v;
		}

		public static function set_front_lead_page( $id ) {
			update_option( 'leadpages_front_page_id', $id );
		}

		function is_front_page( $id ) {
			$front = self::get_front_lead_page();

			return ( $id == $front && $front !== false );
		}

		public static function get_wg_lead_page() {
			$v = get_option( 'leadpages_wg_page_id', false );

			return ( $v == '' ) ? false : $v;
		}

		public static function set_wg_lead_page( $id ) {
			update_option( 'leadpages_wg_page_id', $id );
		}

		public static function get_404_lead_page() {
			$v = get_option( 'leadpages_404_page_id', false );

			return ( $v == '' ) ? false : $v;
		}

		public static function set_404_lead_page( $id ) {
			update_option( 'leadpages_404_page_id', $id );
		}

		public static function get_redirect_method() {
			$v = get_option( 'leadpages_redirect_method', 'http' );

			return ( $v == '' ) ? 'http' : $v;
		}

		public static function set_redirect_method( $val ) {
			update_option( 'leadpages_redirect_method', $val );
		}

		function is_404_page( $id ) {
			$nf = self::get_404_lead_page();

			return ( $id == $nf && $nf !== false );
		}

		function is_wg_page( $id ) {
			$wg = self::get_wg_lead_page();

			return ( $id == $wg && $wg !== false );
		}

		// The Callback
		function show_custom_meta_box() {
			global $post;

			// Field Array
			$field  = array(
				'label'   => 'My Page',
				'desc'    => 'Select from your pages.',
				'id'      => 'leadpages_my_selected_page',
				'type'    => 'select',
				'options' => array()
			);
			$result = self::load_my_pages();
			if ( $result[0] !== false ) {
				$pages = $result[0];
			} else {
				echo 'Error while loading your pages! Message: ' . $result[1];

				return;
			}
			foreach ( $pages as $k => $v ) {
				$field['options'][ $v['id'] ] = array(
					'label' => $v['name'],
					'value' => $v['id']
				);
			}
			$is_front_page = self::is_front_page( $post->ID );
			$is_wg_page    = self::is_wg_page( $post->ID );
			$is_nf_page    = self::is_404_page( get_the_ID() );
			$meta          = get_post_meta( $post->ID, 'leadpages_my_selected_page', true );
			$meta_slug     = get_post_meta( $post->ID, 'leadpages_slug', true );
			$missing_slug  = ( self::is_page_mode_active( 'edit' ) && $meta_slug == '' && ! $is_front_page );

			$delete_link = get_delete_post_link( $post->ID );

			$leadpages_post_type = 'lp';
			$redirect_method     = 'http';
			if ( $is_front_page ) {
				$leadpages_post_type = 'fp';
			} elseif ( $is_wg_page ) {
				$leadpages_post_type = 'wg';
				$redirect_method     = self::get_redirect_method();
			} elseif ( $is_nf_page ) {
				$leadpages_post_type = 'nf';
			}
			?>
			<script type="text/javascript">
				var leadpages_post_type_area = true;
			</script>
			<div class="bootstrap-wpadmin">
				<input type="hidden" name="leadpages_meta_box_nonce"
				       value="<?php echo wp_create_nonce( basename( __FILE__ ) ) ?>"/>

				<div class="row-fluid form-horizontal">
					<br/>

					<div class="control-group">
						<label class="control-label">LeadPage type</label>

						<div class="controls">
							<div class="btn-group multichoice subsection" data-subsection="leadpages_url"
							     data-target="leadpages-post-type">
								<button class="btn" data-value="lp">Normal Page</button>
								<button class="btn" data-value="fp">Home Page</button>
								<button class="btn" data-value="wg">Welcome Gate&trade;</button>
								<button class="btn" data-value="nf">404 Page</button>
							</div>
							<a class="leadpages-help-ico leadpages-check-style" rel="popover"
							   data-original-title="LeadPage type" data-content="
                        &lt;strong&gt;Normal&lt;/strong&gt; - this will work similar to a typical WordPress page.&lt;br/&gt;
                        &lt;strong&gt;Homepage&lt;/strong&gt; - this will make any LeadPage appear on your sites's root. &lt;br/&gt;
                        &lt;strong&gt;WelcomeGate&lt;/strong&gt; - your visitors will be automatically redirected to this page from your homepage on their first visit. &lt;br/&gt;
                        &lt;strong&gt;404 Page&lt;/strong&gt; - this will make any LeadPage appear as a &quot;404 - Not Found Page&quot;.
                    ">&nbsp;</a>
							<input type="hidden" name="leadpages-post-type" class="leadpages-post-type"
							       value="<?php echo $leadpages_post_type ?>"/>
						</div>
					</div>

					<div class="control-group">
						<label for="leadpages_my_selected_page" class="control-label">LeadPage to display</label>

						<div class="controls">
							<select name="leadpages_my_selected_page" id="leadpages_my_selected_page"
							        class="input-xlarge">
								<?php foreach ( $field['options'] as $option ) { ?>
									<option <?php echo( ( $meta == $option['value'] ) ? ' selected="selected"' : '' ) ?>
										value="<?php echo $option['value'] ?>">
										<?php echo $option['label'] ?>
									</option>
								<?php } ?>
							</select>
							<a data-content="Select one of the LeadPages that you've created on &lt;strong&gt;http://my.leadpages.net/&lt;/strong&gt;"
							   data-original-title="LeadPage to be displayed" rel="popover" class="leadpages-help-ico">
								&nbsp;</a>
						</div>
					</div>

					<div style="display:none" class="subsection_leadpages_url control-group">
						<label for="leadpages_slug" class="control-label">Custom url</label>

						<div class="controls <?php
						if ( $missing_slug ) {
							echo 'lp-error';
						} ?>" id="leadpages-wp-path">
							<div class="input-prepend">
								<span class="add-on"><?php echo site_url() ?>/</span><input type="text"
								                                                            class="input-xlarge"
								                                                            id="leadpages_slug"
								                                                            name="leadpages_slug"
								                                                            value="<?php echo $meta_slug ?>"/>
							</div>
							<a class="leadpages-help-ico" rel="popover" data-original-title="LeadPage url"
							   data-content="Pick your own url based on your WordPress site. It will work as if the selected LeadPage was a &quot;Page&quot; on your site.">
								&nbsp;</a>
							<?php if ( $missing_slug ) { ?>
								<label for="leadpages_slug" generated="true" class="error" style="color:#ff3300"
								       id="lp-error-path">Valid path is required.</label>
							<?php } ?>
						</div>
					</div>

					<div style="display:none" class="control-group" id="wg-options">
						<label class="control-label">Redirection method</label>

						<div class="controls">
							<div class="btn-group multichoice" data-target="leadpages_redirect_method">
								<button class="btn" data-value="http">HTTP</button>
								<button class="btn" data-value="js">JavaScript</button>
							</div>
							<a class="leadpages-help-ico leadpages-check-style" rel="popover"
							   data-original-title="WelcomeGate redirect method"
							   data-content="&lt;strong&gt;HTTP&lt;/strong&gt; redirect is recommended in most cases. Occasionally  with some caching plugins or systems, &lt;strong&gt;JavaScript&lt;/strong&gt; redirect might be better choice.">
								&nbsp;</a>
							<input type="hidden" name="leadpages_redirect_method" class="leadpages_redirect_method"
							       value="<?php echo $redirect_method ?>"/>
						</div>
					</div>

					<hr/>

					<div class="row-fluid lpgs-submit-controls">
						<input type="submit" name="publish" id="publish" class="btn btn-primary btn-large"
						       value="Publish" accesskey="p"> &nbsp;&nbsp;
						<?php if ( self::is_page_mode_active( 'edit' ) ) { ?>
							<a href="<?php echo $delete_link ?>" type="submit" class="btn btn-warning">Delete</a>
						<?php } ?>
						<a href="<?php echo admin_url( 'edit.php?post_type=leadpages_post' ) ?>" type="submit"
						   class="btn">Back</a>
					</div>
				</div>
			</div>
		<?php
		}

// Save the Data
		function save_custom_meta( $post_id, $post ) {
			// verify nonce
			if ( ! isset( $_POST['leadpages_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['leadpages_meta_box_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// check if this is our type
			if ( $post->post_type != 'leadpages_post' ) {
				return $post_id;
			}

			$old = get_post_meta( $post_id, 'leadpages_my_selected_page', true );
			$new = $_POST['leadpages_my_selected_page'];

			$front_page = false;
			$wg_page    = false;
			$nf_page    = false;
			switch ( $_POST['leadpages-post-type'] ) {
				case 'lp':
					break;
				case 'fp':
					$front_page = true;
					break;
				case 'nf':
					$nf_page = true;
					break;
				case 'wg':
					$wg_page = true;
					break;
			}

			// HOME PAGE
			$old_front = self::get_front_lead_page();
			if ( $front_page ) {
				self::set_front_lead_page( $post_id );
			} elseif ( $old_front == $post_id ) {
				self::set_front_lead_page( false );
			}

			// 404 PAGE
			$old_nf = self::get_404_lead_page();
			if ( $nf_page ) {
				self::set_404_lead_page( $post_id );
			} elseif ( $old_nf == $post_id ) {
				self::set_404_lead_page( false );
			}

			// WELCOME GATE
			$old_wg = self::get_wg_lead_page();
			if ( $wg_page ) {
				self::set_wg_lead_page( $post_id );
				self::set_redirect_method( $_POST['leadpages_redirect_method'] );
				// TODO: clead cookie & calculate root?
			} elseif ( $old_wg == $post_id ) {
				self::set_wg_lead_page( false );
			}

			$result = self::load_my_pages();
			if ( $result[0] !== false ) {
				$pages = $result[0];
			} else {
				print 'Critical error, loading of your pages failed! Message: ' . $result[1];
				die();
			}

			// LP ID
			if ( $new && $new != $old ) {
				update_post_meta( $post_id, 'leadpages_my_selected_page', $new );
				$data = $pages[ $new ];
				update_post_meta( $post_id, 'leadpages_name', $data['name'] );
			} elseif ( '' == $new && $old ) {
				delete_post_meta( $post_id, 'leadpages_my_selected_page', $old );
			}

			// Custom URL
			$old = get_post_meta( $post_id, 'leadpages_slug', true );
			$new = sanitize_title( $_POST['leadpages_slug'] );
			if ( $new && $new != $old ) {
				update_post_meta( $post_id, 'leadpages_slug', $new );
			} elseif ( '' == $new && $old ) {
				delete_post_meta( $post_id, 'leadpages_slug', $old );
			}

			update_post_meta(
				$post_id,
				'leadpages_split_test',
				(bool) $pages[ $_POST['leadpages_my_selected_page'] ]['split_test']
			);

			delete_site_transient( 'leadpages_page_html_cache_' . $new );

			return null;
		}

// Validate the Data
		function validate_custom_meta( $post_id, $post ) {
			// verify nonce
			if ( ! isset( $_POST['leadpages_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['leadpages_meta_box_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// check if this is our type
			if ( $post->post_type != 'leadpages_post' ) {
				return $post_id;
			}

			$slug          = get_post_meta( $post_id, 'leadpages_slug' );
			$is_front_page = self::is_front_page( $post_id );
			$invalid_url   = empty( $slug ) && ! $is_front_page;

			// on attempting to publish - check for completion and intervene if necessary
			if ( ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) && $_POST['post_status'] == 'publish' ) {
				// don't allow publishing while any of these are incomplete
				if ( $invalid_url ) {
					global $wpdb;
					$wpdb->update( $wpdb->posts, array( 'post_status' => 'pending' ), array( 'ID' => $post_id ) );
				}
			}

			return null;
		}

		function register_auto_update() {
			// plugin update information
			add_filter( 'plugins_api', array( &$this, '_update_information' ), 9, 3 );
			// exclude from official updates
			add_filter( 'http_request_args', array( &$this, '_updates_exclude' ), 5, 2 );
			// check for update twice a day (same schedule as normal WP plugins)
			add_action( 'lp_check_event', array( &$this, '_check_for_update' ) );
			add_filter( 'transient_update_plugins', array( &$this, 'pro_check_update' ) );
			add_filter( 'site_transient_update_plugins', array( &$this, 'pro_check_update' ) );
			// check and schedule next update
			if ( ! wp_next_scheduled( 'lp_check_event' ) ) {
				wp_schedule_event( current_time( 'timestamp' ), 'daily', 'lp_check_event' );
			}
			// remove cron task upon deactivation
			register_deactivation_hook( __FILE__, array( &$this, '_check_deactivation' ) );
		}

		function _check_deactivation() {
			wp_clear_scheduled_hook( 'lp_check_event' );
		}

		/**
		 * Exclude from WP updates
		 **/
		public static function _updates_exclude( $r, $url ) {
			if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
				return $r;
			} // Not a plugin update request. Bail immediately.
			$plugins = unserialize( $r['body']['plugins'] );
			unset( $plugins->plugins['leadpages'] );
			unset( $plugins->active[ array_search( 'leadpages', $plugins->active ) ] );
			$r['body']['plugins'] = serialize( $plugins );

			return $r;
		}

		function silent_update_check() {
			$result   = self::_check_for_update( true ); // full response if possible is returned
			$response = $result[0];
			if ( false === $response ) {
				self::show_message( false, 'Error while checking for update. Can\'t reach update server. Message: ' . $result[1] );

				return;
			}
			if ( isset( $response->result ) && $response->result == 'ko' ) {
				self::show_message( false, $response->message );

				return;
			}
			$nv              = $response->version;
			$url             = $response->url;
			$current_version = self::_plugin_get( 'Version' );
			if ( $current_version == $nv || version_compare( $current_version, $nv, '>' ) ) {
				return;
			}
			$plugin_file = 'leadpages/leadpages.php';
			$upgrade_url = wp_nonce_url( 'update.php?action=upgrade-plugin&amp;plugin=' . urlencode( $plugin_file ), 'upgrade-plugin_' . $plugin_file );
			$message     = 'There is a new version of LeadPages plugin available! ( ' . $nv . ' )<br>You can <a href="' . $upgrade_url . '">update</a> to the latest version automatically or <a href="' . $url . '">download</a> the update and install it manually.';
			self::show_message( true, $message );
		}

		function pro_check_update( $option, $cache = true ) {
			$response = get_site_transient( 'leadpages_latest_version' );
			if ( ! $response ) {
				$result   = self::lb_api_call( 'update-check' );
				$response = $result[0];
				if ( $response === false ) {
					return $option;
				}
			}
			$current_version = self::_plugin_get( 'Version' );
			if ( $current_version == $response->version ) {
				return $option;
			}
			if ( version_compare( $current_version, $response->version, '>' ) ) {
				return $option; // you have the latest version
			}
			$plugin_path = 'leadpages/leadpages.php';
			if ( empty( $option->response[ $plugin_path ] ) ) {
				$option->response[ $plugin_path ] = new stdClass();
			}
			$option->response[ $plugin_path ]->url         = self::_plugin_get( 'AuthorURI' );
			$option->response[ $plugin_path ]->slug        = 'leadpages';
			$option->response[ $plugin_path ]->package     = $response->url;
			$option->response[ $plugin_path ]->new_version = $response->version;
			$option->response[ $plugin_path ]->id          = "0";

			return $option;
		}

		function _check_for_update( $full = false ) {
			if ( defined( 'WP_INSTALLING' ) ) {
				return false;
			}
			$result   = self::lb_api_call( 'update-check' );
			$response = $result[0];
			if ( $full === true ) {
				return $result; // giving the full response ...
			}
			if ( $response === false ) { // we have a problem
				return array( false, $result[1] );
			}
			$current_version = self::_plugin_get( 'Version' );
			if ( $current_version == $response->version ) {
				return false;
			}
			if ( version_compare( $current_version, $response->version, '>' ) ) {
				return array( true, 'You have the latest version!' );
			}

			return array( $response->version, 'There is a newer version!' );
		}

		function _update_information( $false, $action, $args ) {
			// Check if this plugins API is about this plugin
			if ( ! isset($args->slug) || 'leadpages' !== $args->slug ) {
				return $false;
			}
			$result   = self::lb_api_call( 'info' );
			$response = $result[0];
			if ( $response === false ) {
				return $false;
			}
			$response->slug        = 'leadpages';
			$response->plugin_name = 'leadpages';

			return $response;
		}

		function lb_api_call( $service ) {
			$licence_key = 'upUbSkfvYbd74rYnAl5hWczFlGbnYLCp';
			$url         = 'http://leadbrite.appspot.com/service/leadpages/' . $service . '/';
			$current_ver = self::_plugin_get( 'Version' );
			$response    = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 70,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'php_version' => PHP_VERSION,
						'version'     => $current_ver,
						'licence_key' => $licence_key
					),
					'cookies'     => array()
				)
			);
			if ( is_wp_error( $response ) ) {
				return array( false, $response->get_error_message() );
			}
			if ( isset( $response['response']['code'] ) ) {
				$code_char = substr( $response['response']['code'], 0, 1 );
			} else {
				$code_char = '5';
			}
			if ( $code_char == '5' || $code_char == '4' ) {
				return array( false, $response['response']['message'] );
			}
			$res = json_decode( $response['body'], true );
			if ( ! is_array( $res ) ) {
				return array( false, 'Unexpected response. Failed to decode JSON.' );
			}
			if ( isset( $res['result'] ) && $res['result'] == 'ko' ) {
				return array( false, $res['message'] );
			}
			$r = new stdClass;
			foreach ( $res as $key => $val ) {
				$r->$key = $val;
			}
			if ( $service == 'update-check' ) {
				set_site_transient( 'leadpages_latest_version', $r, 60 * 60 * 12 );
			}

			return array( $r, 'Everything is good!' );
		}
	}
}
// Initiate LeadPages plugin awesomeness!
$leadpages_instance = new LeadPages();
include( plugin_dir_path( __FILE__ ) . 'lbset.php' );
?>
