<?php
/**
 * Install file
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( !defined( 'YITH_WCWL' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCWL_Install' ) ) {
    /**
     * Install plugin table and create the wishlist page
     * 
     * @since 1.0.0
     */
    class YITH_WCWL_Install {        
        /**
         * Table name
         * 
         * @var string
         * @access private
         * @since 1.0.0
         */
        private $_table;
        
        /**
         * Constructor.
         * 
         * @since 1.0.0
         */
        public function __construct() {
            global $wpdb;
            
            $this->_table = $wpdb->prefix . 'yith_wcwl';
            
            define( 'YITH_WCWL_TABLE', $this->_table );
        }
        
        /**
         * Initiator. Replace the constructor.
         * 
         * @since 1.0.0
         */
        public function init() {            
            $this->_add_table();
            $this->_add_pages();
            
            update_option( 'yith_wcwl_version', YITH_WCWL_VERSION );
            update_option( 'yith_wcwl_db_version', YITH_WCWL_DB_VERSION );
        }
        
        /**
         * Set options to their default value.
         * 
         * @param array $options
         * @return bool
         * @since 1.0.0
         */
        public function default_options( $options ) {
            foreach( $options as $section ) {
        		foreach( $section as $value ) {
        	        if ( isset( $value['std'] ) && isset( $value['id'] ) ) {
      	        		add_option($value['id'], $value['std']);
        	        }
                }
            } 
        }
        
        /**
         * Check if the table of the plugin already exists.
         * 
         * @return bool
         * @since 1.0.0
         */
        public function is_installed() {
            global $wpdb;
            
            return $wpdb->query("SHOW TABLES LIKE '{$this->_table}'" );
        }
        
        /**
         * Add the plugin table to the database.
         * 
         * @return void
         * @access private
         * @since 1.0.0
         */
        private function _add_table() {
            global $wpdb;
            
            if( !$this->is_installed() ) {
                $sql = "CREATE TABLE IF NOT EXISTS {$this->_table} (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `prod_id` int(11) NOT NULL,
                          `quantity` int(11) NOT NULL,
                          `user_id` int(11) NOT NULL,
                          `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`ID`),
                          KEY `product` (`prod_id`)
                        ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
                
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		        dbDelta( $sql );
            }
            
            return;
        }
        
        /**
         * Add a page "Wishlist".
         * 
         * @return void
         * @since 1.0.0
         */
        private function _add_pages() {
        	global $wpdb;

        	$option_value = get_option( 'yith-wcwl-page-id' );

        	if ( $option_value > 0 && get_post( $option_value ) )
        		return;
        
        	$page_found = $wpdb->get_var( "SELECT `ID` FROM `{$wpdb->posts}` WHERE `post_name` = 'wishlist' LIMIT 1;" );

        	if ( $page_found ) :
        		if ( ! $option_value )
        			update_option( 'yith-wcwl-page-id', $page_found );
        		return;
        	endif;
        
        	$page_data = array(
                'post_status' 		=> 'publish',
                'post_type' 		=> 'page',
                'post_author' 		=> 1,
                'post_name' 		=> esc_sql( _x( 'wishlist', 'page_slug', MAD_BASE_TEXTDOMAIN ) ),
                'post_title' 		=> __( 'Wishlist', MAD_BASE_TEXTDOMAIN ),
                'post_content' 		=> '[vc_mad_yith_wcwl_wishlist pagination="yes" per_page="5"]',
                'post_parent' 		=> 0,
                'comment_status' 	=> 'closed'
            );
            $page_id = wp_insert_post( $page_data );
        
            update_option( 'yith-wcwl-page-id', $page_id );
//            update_option( 'yith_wcwl_wishlist_page_id', $page_id );
        }
    } 
}