<?php
if ( class_exists( 'woocommerce' ) ) {
	
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		// WooCommerce 2.1 or above is active
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	} else {
		// WooCommerce is less than 2.1
		define( 'WOOCOMMERCE_USE_CSS', false );
	}

	function noo_woocommerce_show_page_title() {
		return false;
	}
	add_filter( 'woocommerce_show_page_title', 'noo_woocommerce_show_page_title' );
	
	// Number of products per page
	function noo_woocommerce_loop_shop_per_page() {
		return noo_get_option( 'noo_shop_num', 12 );
	}
	add_filter( 'loop_shop_per_page', 'noo_woocommerce_loop_shop_per_page' );

	function noo_add_to_cart_fragments( $fragments ) {
		$output = noo_minicart();
		$fragments['.minicart'] = $output;
		$fragments['.mobile-minicart-icon'] = noo_minicart_mobile();
		return $fragments;
	}
	add_filter( 'add_to_cart_fragments', 'noo_add_to_cart_fragments' );

	function noo_woocommerce_remove_cart_item() {
		global $woocommerce;
		$response = array();
		
		if ( ! isset( $_GET['item'] ) && ! isset( $_GET['_wpnonce'] ) ) {
			exit();
		}
		$woocommerce->cart->set_quantity( $_GET['item'], 0 );
		
		$cart_count = $woocommerce->cart->cart_contents_count;
		$response['count'] = $cart_count != 0 ? $cart_count : "";
		$response['minicart'] = noo_minicart( true );
		
		// widget cart update
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();
		$response['widget'] = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
		
		echo json_encode( $response );
		exit();
	}
	add_action( 'wp_ajax_noo_woocommerce_remove_cart_item', 'noo_woocommerce_remove_cart_item' );
	add_action( 'wp_ajax_nopriv_noo_woocommerce_remove_cart_item', 'noo_woocommerce_remove_cart_item' );

	function noo_product_items_text( $count ) {
		$product_item_text = "";
		
		if ( $count > 1 ) {
			$product_item_text = str_replace( '%', number_format_i18n( $count ), __( '% items', 'noo' ) );
		} elseif ( $count == 0 ) {
			$product_item_text = __( '0 items', 'noo' );
		} else {
			$product_item_text = __( '1 item', 'noo' );
		}
		
		return $product_item_text;
	}
	
	// Mobile icon
	function noo_minicart_mobile() {
		if( ! noo_get_option('noo_header_nav_icon_cart_woo', true ) ) {
			return '';
		}

		global $woocommerce;
		
		$cart_output = "";
		$cart_total = $woocommerce->cart->get_cart_total();
		$cart_count = $woocommerce->cart->cart_contents_count;
		$cart_output = '<a href="' . $woocommerce->cart->get_cart_url() . '" title="' . __( 'View Cart', 'noo' ) .
			 '"  class="mobile-minicart-icon"><i class="fa fa-shopping-cart"></i><span>' . $cart_count . '</span></a>';
		return $cart_output;
	}
	
	// Menu cart
	function noo_minicart( $content = false ) {
		if( ! noo_get_option('noo_header_nav_icon_cart_woo', true ) ) {
			return '';
		}
		global $woocommerce;
		
		$cart_output = "";
		$cart_total = $woocommerce->cart->get_cart_total();
		$cart_count = $woocommerce->cart->cart_contents_count;
		$cart_count_text = noo_product_items_text( $cart_count );
		
		$cart_has_items = '';
		if ( $cart_count != "0" ) {
			$cart_has_items = ' has-items';
		}
		
		$output = '';
		if ( ! $content ) {
			$output .= '<li id="nav-menu-item-cart" class="menu-item noo-menu-item-cart minicart"><a title="' .
				 __( 'View cart', 'noo' ) . '" class="cart-button" href="' . $woocommerce->cart->get_cart_url() .
				 '">' . '<span class="cart-item' . $cart_has_items . '"><i class="fa fa-shopping-cart"></i>';
			if ( $cart_count != "0" ) {
				$output .= "<span>" . $cart_count . "</span>";
			}
			$output .= '</span>';
			$output .= '</a>';
			$output .= '<div class="noo-minicart">';
		}
		if ( $cart_count != "0" ) {
			$output .= '<div class="minicart-header">' . $cart_count_text . ' ' .
				 __( 'in the shopping cart', 'noo' ) . '</div>';
			$output .= '<div class="minicart-body">';
			foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
				
				$cart_product = $cart_item['data'];
				$product_title = $cart_product->get_title();
				$product_short_title = ( strlen( $product_title ) > 25 ) ? substr( $product_title, 0, 22 ) . '...' : $product_title;
				
				if ( $cart_product->exists() && $cart_item['quantity'] > 0 ) {
					$output .= '<div class="cart-product clearfix">';
					$output .= '<div class="cart-product-image"><a class="cart-product-img" href="' .
						 get_permalink( $cart_item['product_id'] ) . '">' . $cart_product->get_image() . '</a></div>';
					$output .= '<div class="cart-product-details">';
					$output .= '<div class="cart-product-title"><a href="' . get_permalink( $cart_item['product_id'] ) .
						 '">' .
						 apply_filters( 'woocommerce_cart_widget_product_title', $product_short_title, $cart_product ) .
						 '</a></div>';
					if ( isset ($cart_item['cause_amount']) ) :
						$output .= '<div class="cart-product-price">' . __( "Price", "woocommerce" ) . ' $' .
						 	$cart_item['cause_amount'] . '</div>';
					else :
						$output .= '<div class="cart-product-price">' . __( "Price", "woocommerce" ) . ' ' .
							woocommerce_price( $cart_product->get_price() ) . '</div>';
					endif;
					$output .= '<div class="cart-product-quantity">' . __( 'Quantity', 'noo' ) . ' ' .
						 $cart_item['quantity'] . '</div>';
					$output .= '</div>';
					$output .= apply_filters( 
						'woocommerce_cart_item_remove_link', 
						sprintf( 
							'<a href="%s" class="remove" title="%s">&times;</a>', 
							esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), 
							__( 'Remove this item', 'noo' ) ), 
						$cart_item_key );
					$output .= '</div>';
				}
			}
			$output .= '</div>';
			$output .= '<div class="minicart-footer">';
			$output .= '<div class="minicart-total">' . __( 'Cart Subtotal', 'noo' ) . ' ' . $cart_total .
				 '</div>';
			$output .= '<div class="minicart-actions clearfix">';
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				$cart_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_cart_url() );
				$checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() );
				
				$output .= '<a class="button" href="' . esc_url( $cart_url ) . '"><span class="text">' .
					 __( 'View Cart', 'noo' ) . '</span></a>';
				$output .= '<a class="checkout-button button" href="' . esc_url( $checkout_url ) .
					 '"><span class="text">' . __( 'Proceed to Checkout', 'noo' ) . '</span></a>';
			} else {
				
				$output .= '<a class="button" href="' . esc_url( $woocommerce->cart->get_cart_url() ) .
					 '"><span class="text">' . __( 'View Cart', 'noo' ) . '</span></a>';
				$output .= '<a class="checkout-button button" href="' . esc_url( 
					$woocommerce->cart->get_checkout_url() ) . '"><span class="text">' .
					 __( 'Proceed to Checkout', 'noo' ) . '</span></a>';
			}
			$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div class="minicart-header">' . __( 'Your shopping bag is empty.', 'noo' ) . '</div>';
			$shop_page_url = "";
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
			} else {
				$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
			}
			
			$output .= '<div class="minicart-footer">';
			$output .= '<div class="minicart-actions clearfix">';
			$output .= '<a class="button pull-left" href="' . esc_url( $shop_page_url ) . '"><span class="text">' .
				 __( 'Go to the shop', 'noo' ) . '</span></a>';
			$output .= '</div>';
			$output .= '</div>';
		}
		
		if ( ! $content ) {
			$output .= '</div>';
			$output .= '</li>';
		}
		
		return $output;
	}

	function noo_navbar_minicart( $items, $args ) {
		if ( $args->theme_location == 'primary' ) {
			$minicart = noo_minicart();
			$items .= $minicart;
		}
		return $items;
	}
	add_filter( 'wp_nav_menu_items', 'noo_navbar_minicart', 10, 2 );

	function noo_woocommerce_update_product_image_size() {
		$catalog = array( 'width' => '500', 'height' => '700', 'crop' => 1 );
		$single = array( 'width' => '500', 'height' => '700', 'crop' => 1 );
		$thumbnail = array( 'width' => '100', 'height' => '100', 'crop' => 1 );
		update_option( 'shop_catalog_image_size', $catalog );
		update_option( 'shop_single_image_size', $single );
		update_option( 'shop_thumbnail_image_size', $thumbnail );
	}
	
	if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
		add_action( 'init', 'noo_woocommerce_update_product_image_size', 1 );
	}
	
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	function noo_woocommerce_shop_columns() {
		if ( noo_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
			return 4;
		}
		return 3;
	}
	add_filter( 'loop_shop_columns', 'noo_woocommerce_shop_columns' );

	// Loop thumbnail
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'noo_template_loop_product_thumbnail', 10 );
	// add_action( 'woocommerce_before_shop_loop_item_title', 'noo_template_loop_product_frist_thumbnail', 11 );

	function noo_template_loop_product_thumbnail() {
		$first_image = noo_template_loop_product_get_frist_thumbnail();
		echo '<div class="noo-product-thumbnail'.(!empty($first_image) ? ' noo-product-front-thumbnail':'').'">' . woocommerce_get_product_thumbnail() .'</div>';
		if ( $first_image != '' ) {
			echo '<div class="noo-product-thumbnail noo-product-back-thumbnail">' . $first_image . '</div>';
		}
	}


	function noo_template_loop_product_get_frist_thumbnail() {
		global $product, $post;
		$image = '';
		if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
			$attachment_ids = $product->get_gallery_attachment_ids();
			$image_count = 0;
			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {
					if ( noo_get_post_meta( $attachment_id, '_woocommerce_exclude_image' ) )
						continue;
					
					$image = wp_get_attachment_image( $attachment_id, 'shop_catalog' );
					
					$image_count++;
					if ( $image_count == 1 )
						break;
				}
			}
		} else {
			$attachments = get_posts( 
				array( 
					'post_type' => 'attachment', 
					'numberposts' => - 1, 
					'post_status' => null, 
					'post_parent' => $post->ID, 
					'post__not_in' => array( get_post_thumbnail_id() ), 
					'post_mime_type' => 'image', 
					'orderby' => 'menu_order', 
					'order' => 'ASC' ) );
			$image_count = 0;
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
					
					if ( noo_get_post_meta( $attachment->ID, '_woocommerce_exclude_image' ) == 1 )
						continue;
					
					$image = wp_get_attachment_image( $attachment->ID, 'shop_catalog' );
					
					$image_count++;
					
					if ( $image_count == 1 )
						break;
				}
			}
		}
		return $image;
	}
	
	// Loop actions
	add_action( 'woocommerce_after_shop_loop_item', 'noo_template_loop_quickview', 11 );

	function noo_template_loop_quickview() {
		global $product;
		echo '<a class="shop-loop-quickview" data-product_id ="' . $product->id . '" href="' . $product->get_permalink() .
			 '">' . esc_html( 'Quick shop', 'noo' ) . '</a>';
	}
	
	// Quick view
	add_action( 'wp_ajax_woocommerce_quickview', 'noo_woocommerce_quickview' );
	add_action( 'wp_ajax_nopriv_woocommerce_quickview', 'noo_woocommerce_quickview' );

	function noo_woocommerce_quickview() {
		global $woocommerce, $post, $product;
		$product_id = $_POST['product_id'];
		$product = get_product( $product_id );
		$post = get_post( $product_id );
		$output = '';
		
		ob_start();
		woocommerce_get_template( 'quickview.php' );
		$output = ob_get_contents();
		ob_end_clean();
		
		echo $output;
		die();
	}
	
	// Wishlist
	add_action( 'woocommerce_after_shop_loop_item', 'noo_template_loop_wishlist', 12 );

	function noo_template_loop_wishlist() {
		if ( noo_woocommerce_wishlist_is_active() ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
	}
	if ( ! function_exists( 'noo_woocommerce_wishlist_is_active' ) ) {

		/**
		 * Check yith-woocommerce-wishlist plugin is active
		 *
		 * @return boolean .TRUE is active
		 */
		function noo_woocommerce_wishlist_is_active() {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			
			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			
			return in_array( 'yith-woocommerce-wishlist/init.php', $active_plugins ) ||
				 array_key_exists( 'yith-woocommerce-wishlist/init.php', $active_plugins );
		}
	}
	if ( ! function_exists( 'noo_woocommerce_compare_is_active' ) ) {

		/**
		 * Check yith-woocommerce-compare plugin is active
		 *
		 * @return boolean .TRUE is active
		 */
		function noo_woocommerce_compare_is_active() {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			
			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			
			return in_array( 'yith-woocommerce-compare/init.php', $active_plugins ) ||
				 array_key_exists( 'yith-woocommerce-compare/init.php', $active_plugins );
		}
	}
	
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash' );
	
	// Single share
	// add_action( 'woocommerce_single_product_summary', 'noo_social_share', 50 );
	
	// Related products
	add_filter( 'woocommerce_output_related_products_args', 'noo_woocommerce_output_related_products_args' );

	function noo_woocommerce_output_related_products_args() {
		if ( noo_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
			$args = array( 'posts_per_page' => 4, 'columns' => 4 );
			return $args;
		}
		
		$args = array( 'posts_per_page' => 3, 'columns' => 3 );
		return $args;
	}
	
	// Upsell products
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'noo_woocommerce_upsell_display', 15 );
	if ( ! function_exists( 'noo_woocommerce_upsell_display' ) ) {

		function noo_woocommerce_upsell_display() {
			if ( noo_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
				woocommerce_upsell_display( - 1, 4 );
			} else {
				woocommerce_upsell_display( - 1, 3 );
			}
		}
	}
}
