<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 1.1.5
 */

if ( !defined( 'YITH_WCWL' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCWL' ) ) {    
    /**
     * WooCommerce Wishlist
     *
     * @since 1.0.0
     */
    class YITH_WCWL {
        /**
         * Errors array
         * 
         * @var array
         * @since 1.0.0
         */
        public $errors;
        
        /**
         * Details array
         * 
         * @var array
         * @since 1.0.0
         */
        public $details;
        
        /**
         * Messages array
         * 
         * @var array
         * @since 1.0.0
         */
        public $messages;
        
        /**
         * Constructor.
         * 
         * @param array $details
         * @return void
         * @since 1.0.0
         */
        public function __construct( $details ) {              

    		// Start a PHP session, if not yet started
//    		if ( ! session_id() )
//    			session_start();

            $this->details = $details;
            $yith_wcwl_init = new YITH_WCWL_Init();
            
            add_action( 'wp_ajax_add_to_wishlist', array( $this, 'add_to_wishlist_ajax' ) );
            add_action( 'wp_ajax_nopriv_add_to_wishlist', array( $this, 'add_to_wishlist_ajax' ) );
            
            add_action( 'wp_ajax_remove_from_wishlist', array( $this, 'remove_from_wishlist_ajax' ) );
            add_action( 'wp_ajax_nopriv_remove_from_wishlist', array( $this, 'remove_from_wishlist_ajax' ) );
        }
        
        /**
         * Check if the product exists in the wishlist.
         * 
         * @param int $product_id
         * @return bool
         * @since 1.0.0
         */
        public function is_product_in_wishlist( $product_id ) {
            global $wpdb;
                
            $exists = false;
                
    		if( is_user_logged_in() ) {		
    			$sql = "SELECT COUNT(*) as `cnt` FROM `" . YITH_WCWL_TABLE . "` WHERE `prod_id` = " . $product_id . " AND `user_id` = " . $this->details['user_id'];
    			$results = $wpdb->get_results( $sql );
    			$exists = $results[0]->cnt > 0 ? true : false;
    		} else {
                if( yith_usecookies() ) {
                    $tmp_products = yith_getcookie( 'yith_wcwl_products' );
                } elseif( isset( $_SESSION['yith_wcwl_products'] ) ) {
                    $tmp_products = $_SESSION['yith_wcwl_products'];
                } else {
                    $tmp_products = array();
                }
                
                    if( isset( $tmp_products[ $product_id ] ) )
                        { $exists = 1; }
                    else
                        { $exists = 0; }
    		}
            
            return $exists;
        }
        
        /**
         * Add a product in the wishlist.
         * 
         * @return string "error", "true" or "exists"
         * @since 1.0.0
         */
        public function add() {
            global $wpdb, $woocommerce;
            
            if ( isset( $this->details['add_to_wishlist'] ) && is_numeric( $this->details['add_to_wishlist'] ) ) {
                //single product
                $quantity = ( isset( $this->details['quantity'] ) ) ? ( int ) $this->details['quantity'] : 1;
                
                //check for existence,  product ID, variation ID, variation data, and other cart item data
                if( $this->is_product_in_wishlist( $this->details['add_to_wishlist'] ) ) {
                    return "exists";   
                }
                
                $return = "error";
                
                if( is_user_logged_in() ) {
                    $sql = "INSERT INTO `" . YITH_WCWL_TABLE . "` ( `prod_id`, `quantity`, `user_id`, `dateadded` ) VALUES ( " . $this->details['add_to_wishlist'] . " , $quantity, " . $this->details['user_id'] . ", now() )";
                    $return = $wpdb->query( $sql );   //echo $sql;die;
                } elseif( yith_usecookies() ) {
                    $cookie[$this->details['add_to_wishlist']]['add-to-wishlist'] = $this->details['add_to_wishlist'];
                    $cookie[$this->details['add_to_wishlist']]['quantity'] = $quantity;
                    
                    $cookie = $cookie + yith_getcookie( 'yith_wcwl_products' );
                    
                    yith_setcookie( 'yith_wcwl_products', $cookie );
                    $return = true;  
                } else {
                    $_SESSION['yith_wcwl_products'][$this->details['add_to_wishlist']]['add-to-wishlist'] = $this->details['add_to_wishlist'];
				    $_SESSION['yith_wcwl_products'][$this->details['add_to_wishlist']]['quantity'] = $quantity;
				    $return = true; 
                }
                
                if( $return ) {
                    return "true";
                } else {
                    $this->errors[] = __( 'Error occurred while adding product to wishlist.', MAD_BASE_TEXTDOMAIN );
                    return "error";
                }
            } 
        }
        
        /**
         * Remove an entry from the wishlist.
         * 
         * @param int $id Record ID
         * @return bool
         * @since 1.0.0
         */
        public function remove( $id ) {
            global $wpdb;

            if( is_user_logged_in() ) {
                $sql = "DELETE FROM `" . YITH_WCWL_TABLE . "` WHERE `ID` = " . $id . " AND `user_id` = " . $this->details['user_id'];
                
                if( $wpdb->query( $sql ) ) {
                    return true;
                } else {
                    $this->errors[] = __( 'Error occurred while removing product from wishlist', MAD_BASE_TEXTDOMAIN );
                    return false;
                }
            } elseif( yith_usecookies() ) {
                $cookie = yith_getcookie( 'yith_wcwl_products' );
                unset( $cookie[$id] );
                
                yith_destroycookie( 'yith_wcwl_products' );
                yith_setcookie( 'yith_wcwl_products', $cookie );
                
                return true;
            } else {
                unset( $_SESSION['yith_wcwl_products'][$id] );
			    return true;
            }
        }
        
        /**
         * Get all errors in HTML mode or simple string.
         * 
         * @param bool $html
         * @return string
         * @since 1.0.0
         */
        public function get_errors( $html = true ) {
            return implode( ( $html ? '<br />' : ', ' ), $this->errors );
        }
        
        /**
         * Retrieve the number of products in the wishlist.
         * 
         * @return int
         * @since 1.0.0
         */
        public function count_products() {
            global $wpdb;
            
            if( is_user_logged_in() ) {
                $sql = "SELECT COUNT(*) as `cnt` FROM `" . YITH_WCWL_TABLE . "` WHERE `user_id` = " . get_current_user_id();
                $results = $wpdb->get_results( $sql );
                return $results[0]->cnt;
            } elseif( yith_usecookies() ) {
                $cookie = yith_getcookie( 'yith_wcwl_products' );
                return count( $cookie );
            } else {
                if( isset( $_SESSION['yith_wcwl_products'] ) ) 
        			{ return count( $_SESSION['yith_wcwl_products'] ); }
            }
            
            return 0;
        }
        
        /**
         * Retrieve details of a product in the wishlist.
         * 
         * @param int $id
         * @param string $request_from
         * @return array
         * @since 1.0.0
         */
        public function get_product_details( $id ) {
            global $wpdb;
            
            if( is_user_logged_in() ) {
                return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . YITH_WCWL_TABLE . '` WHERE `prod_id` = %d', $id ), ARRAY_A );
            } elseif( yith_usecookies() ) {
                $cookie = yith_getcookie( 'yith_wcwl_products' );
                $temp_arr[0] = $cookie[$id];
                $temp_arr[0]['prod_id'] = $id;
    			return $temp_arr;
            } else {
                $temp_arr[0] = $_SESSION['yith_wcwl_products'][$id];
    			$temp_arr[0]['prod_id'] = $id;
    			return $temp_arr;
            }
            
            return array();
        }
        
        /**
         * Build wishlist page URL.
         * 
         * @return string
         * @since 1.0.0
         */
        public function get_wishlist_url() {

            return get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) );
        }
        
        /**
         * Build wishlist page URL based on user ID.
         * 
         * @param int $user_id
         * @return string
         * @since 1.0.0
         */
        public function get_public_wishlist_url( $user_id ) {
            return home_url() . '/?id=' . $user_id . '&page_id=' . $page_id;
        }
        
        /**
         * Build the URL used to remove an item from the wishlist.
         * 
         * @param int $item_id
         * @return string
         * @since 1.0.0
         */
        public function get_remove_url( $item_id ) {
            return admin_url( 'admin-ajax.php?wishlist_item_id=' . $item_id );
            //return YITH_WCWL_URL . 'yith-wcwl-ajax.php?action=remove_from_wishlist&wishlist_item_id=' . $item_id; 
        }
        
        /**
         * Build the URL used to add an item in the wishlist.
         * 
         * @return string
         * @since 1.0.0
         */
        public function get_addtowishlist_url() {
            global $product;
            	
            $url = YITH_WCWL_URL . "yith-wcwl-ajax.php?action=add_to_wishlist&add_to_wishlist=" . $product->id;
            
            return $url;
        }
        
        /**
         * Build the URL used to add an item to the cart from the wishlist.
         * 
         * @param int $id
         * @param int $user_id
         * @return string
         * @since 1.0.0
         */
        public function get_addtocart_url( $id, $user_id = '' ) {
            global $yith_wcwl;
            
            //$product = $yith_wcwl->get_product_details( $id );
            if ( function_exists( 'get_product' ) )    
                $product = get_product( $id );
            else
                $product = new WC_Product( $id );
                
            if ( $product->product_type == 'variable' ) {
                return get_permalink( $product->id );
            }
            
    		$url = YITH_WCWL_URL . 'add-to-cart.php?wishlist_item_id=' . rtrim( $id, '_' );
    		
    		if( $user_id != '' ) {
    			$url .= '&id=' . $user_id;
    		}
            
    		return $url;
    	}

        /**
         * Build the URL used for an external/affiliate product.
         *
         * @param $id
         * @return string
         */
        public function get_affiliate_product_url( $id ) {
            $product = get_product( $id );
            return get_post_meta( $product->id, '_product_url', true );
        }
        
        /**
         * Build an URL with the nonce added.
         * 
         * @param string $action
         * @param string $url
         * @return string
         * @since 1.0.0
         */
        public function get_nonce_url( $action, $url = '' ) {
            return add_query_arg( '_n', wp_create_nonce( 'yith-wcwl-' . $action ), $url );
        }             
        
        /**
         * AJAX: add to wishlist action
         * 
         * @return void
         * @since 1.0.0
         */
        public function add_to_wishlist_ajax() {    
            $return = $this->add();
    
            if( $return == 'true'  )
                { echo $return . '##' . __( 'Product added!', MAD_BASE_TEXTDOMAIN ); }
            elseif( $return == 'exists' )
                { echo $return . '##' . __( 'Product already in the wishlist.', MAD_BASE_TEXTDOMAIN ); }
            elseif( count( $this->errors ) > 0 )
                { echo $this->get_errors(); }
            
            die();        
        }
        
        /**
         * AJAX: remove from wishlist action
         * 
         * @return void
         * @since 1.0.0
         */
        public function remove_from_wishlist_ajax() {   
            $count = yith_wcwl_count_products();
                
            if( $this->remove( $_GET['wishlist_item_id'] ) )
                { echo apply_filters( 'yith_wcwl_product_removed_text', __( 'Product successfully removed.', MAD_BASE_TEXTDOMAIN ) ); }
            else {
                echo '#' . $count . '#';
                _e( 'Error. Unable to remove the product from the wishlist.', MAD_BASE_TEXTDOMAIN );
            }
            
            if( !$count )
                { _e( 'No products were added to the wishlist', MAD_BASE_TEXTDOMAIN ); }
            
            die();
        }
    }   
}