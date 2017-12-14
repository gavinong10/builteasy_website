<?php

/**
 * Users submissions
 *
 * @param null $user_id
 *
 * @return array|bool
 */
function aviators_submission_get_user_submissions( $user_id = NULL, $return_wp_query = FALSE ) {
	if ( $user_id == NULL ) {
		$user = wp_get_current_user();
	}
	else {
		$user = get_user_by( 'id', $user_id );
	}

	$type           = aviators_settings_get_value( 'submission', 'common', 'post_type' );
	$posts_per_page = aviators_settings_get_value( 'submission', 'common', 'posts_per_page' );

	if ( empty( $type ) ) {
		aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'There are no defined custom post types for submission system.', 'aviators' ) );
		return wp_redirect( home_url() );
	}


	$query = new WP_Query( array(
		'post_type'      => $type,
		'posts_per_page' => $posts_per_page,
		'post_status'    => 'any',
		'author'         => $user->ID,
		'paged'          => get_query_var( 'paged' ),
	) );

	if ( $return_wp_query ) {
		return $query;
	}


	return $query->posts;
}

/**
 * Payment gateway
 */
function aviators_submission_paypal_payment_gateway() {
	$payment_gateway = aviators_settings_get_value( 'submission', 'common', 'payment_gateway' );

	if ( $payment_gateway == 'paypal' ) {
		require_once AVIATORS_DIR . "/libraries/paypal-digital-goods/paypal-digital-goods.class.php";
		require_once AVIATORS_DIR . "/libraries/paypal-digital-goods/paypal-configuration.class.php";
		require_once AVIATORS_DIR . "/libraries/paypal-digital-goods/paypal-purchase.class.php";

		if ( aviators_settings_get_value( 'submission', 'paypal', 'sandbox' ) == 'on' ) {
			$username  = aviators_settings_get_value( 'submission', 'paypal', 'sandbox_username' );
			$password  = aviators_settings_get_value( 'submission', 'paypal', 'sandbox_password' );
			$signature = aviators_settings_get_value( 'submission', 'paypal', 'sandbox_signature' );
		}
		else {
			$username  = aviators_settings_get_value( 'submission', 'paypal', 'username' );
			$password  = aviators_settings_get_value( 'submission', 'paypal', 'password' );
			$signature = aviators_settings_get_value( 'submission', 'paypal', 'signature' );
			PayPal_Digital_Goods_Configuration::environment( 'live' );
		}

		PayPal_Digital_Goods_Configuration::username( $username );
		PayPal_Digital_Goods_Configuration::password( $password );
		PayPal_Digital_Goods_Configuration::signature( $signature );
                PayPal_Digital_Goods_Configuration::currency(aviators_settings_get_value( 'submission', 'pay_per_post', 'currency_code' ));
		PayPal_Digital_Goods_Configuration::business_name( get_bloginfo( 'name' ) );
	}
}

/**
 * Create PayPal purchase
 *
 * @param int $post_id
 */
function aviators_submission_create_paypal_purchase( $post_id ) {
	aviators_submission_paypal_payment_gateway();

	PayPal_Digital_Goods_Configuration::return_url( get_template_directory_uri() . '/aviators/plugins/submission/return.php?paypal=paid&post_id=' . $post_id );
	PayPal_Digital_Goods_Configuration::cancel_url( get_template_directory_uri() . '/aviators/plugins/submission/return.php?paypal=cancel&post_id=' . $post_id );
	PayPal_Digital_Goods_Configuration::notify_url( get_template_directory_uri() . '/aviators/plugins/submission/return.php?paypal=notify&post_id=' . $post_id );

	$post        = get_post( $post_id );
	$price       = aviators_settings_get_value( 'submission', 'pay_per_post', 'price' );
	$tax         = aviators_settings_get_value( 'submission', 'pay_per_post', 'tax' );
	$description = aviators_settings_get_value( 'submission', 'pay_per_post', 'description' );
	$currency    = aviators_settings_get_value( 'submission', 'pay_per_post', 'currency_code' );

	$purchase_details = array(
		'name'        => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'amount'      => $price + $tax,
		'tax_amount'  => $tax,
		'currency'    => $currency,
		'items'       => array(
			array(
				'item_name'        => $post->post_title,
				'item_description' => $description,
				'item_amount'      => $price,
				'item_tax'         => $tax,
				'item_quantity'    => 1,
				'item_number'      => $post_id,
			),
		),
	);
	return new PayPal_Purchase( $purchase_details );
}


class Submission_MetaBox extends WPAlchemy_MetaBox {
	function force_save( $post_id ) {
		/**
		 * note: the "save_post" action fires for saving revisions and post/pages,
		 * when saving a post this function fires twice, once for a revision save,
		 * and again for the post/page save ... the $post_id is different for the
		 * revision save, this means that "get_post_meta()" will not work if trying
		 * to get values for a revision (as it has no post meta data)
		 * see http://alexking.org/blog/2008/09/06/wordpress-26x-duplicate-custom-field-issue
		 *
		 * why let the code run twice? wordpress does not currently save post meta
		 * data per revisions (I think it should, so users can do a complete revert),
		 * so in the case that this functionality changes, let it run twice
		 */

		$real_post_id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : NULL;

//        // check autosave
//        if (defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE AND !$this->autosave) return $post_id;
//
//        // make sure data came from our meta box, verify nonce
//        $nonce = isset($_POST[$this->id.'_nonce']) ? $_POST[$this->id.'_nonce'] : NULL ;
//        if (!wp_verify_nonce($nonce, $this->id)) return $post_id;
//
//        // check user permissions
//        if ($_POST['post_type'] == 'page')
//        {
//            if (!current_user_can('edit_page', $post_id)) return $post_id;
//        }
//        else
//        {
//            if (!current_user_can('edit_post', $post_id)) return $post_id;
//        }

		// authentication passed, save data

		$new_data = isset( $_POST[$this->id] ) ? $_POST[$this->id] : NULL;

		WPAlchemy_MetaBox::clean( $new_data );

		if ( empty( $new_data ) ) {
			$new_data = NULL;
		}

		// filter: save
		if ( $this->has_filter( 'save' ) ) {
			$new_data = $this->apply_filters( 'save', $new_data, $real_post_id );

			/**
			 * halt saving
			 * @since 1.3.4
			 */
			if ( FALSE === $new_data ) return $post_id;

			WPAlchemy_MetaBox::clean( $new_data );
		}

		// get current fields, use $real_post_id (checked for in both modes)
		$current_fields = get_post_meta( $real_post_id, $this->id . '_fields', TRUE );

		if ( $this->mode == WPALCHEMY_MODE_EXTRACT ) {
			$new_fields = array();

			if ( is_array( $new_data ) ) {
				foreach ( $new_data as $k => $v ) {
					$field = $this->prefix . $k;

					array_push( $new_fields, $field );

					$new_value = $new_data[$k];

					if ( is_null( $new_value ) ) {
						delete_post_meta( $post_id, $field );
					}
					else {
						update_post_meta( $post_id, $field, $new_value );
					}
				}
			}

			$diff_fields = array_diff( (array) $current_fields, $new_fields );

			if ( is_array( $diff_fields ) ) {
				foreach ( $diff_fields as $field ) {
					delete_post_meta( $post_id, $field );
				}
			}

			delete_post_meta( $post_id, $this->id . '_fields' );

			if ( ! empty( $new_fields ) ) {
				add_post_meta( $post_id, $this->id . '_fields', $new_fields, TRUE );
			}

			// keep data tidy, delete values if previously using WPALCHEMY_MODE_ARRAY
			delete_post_meta( $post_id, $this->id );
		}
		else {
			if ( is_null( $new_data ) ) {
				delete_post_meta( $post_id, $this->id );
			}
			else {
				update_post_meta( $post_id, $this->id, $new_data );
			}

			// keep data tidy, delete values if previously using WPALCHEMY_MODE_EXTRACT
			if ( is_array( $current_fields ) ) {
				foreach ( $current_fields as $field ) {
					delete_post_meta( $post_id, $field );
				}

				delete_post_meta( $post_id, $this->id . '_fields' );
			}
		}

		// action: save
		if ( $this->has_action( 'save' ) ) {
			$this->do_action( 'save', $new_data, $real_post_id );
		}

		return $post_id;
	}
}


function aviators_dropdown_categories( $args = '' ) {
    $defaults = array(
        'show_option_all' => '', 'show_option_none' => '',
        'orderby' => 'id', 'order' => 'ASC',
        'show_count' => 0,
        'hide_empty' => 1, 'child_of' => 0,
        'exclude' => '', 'echo' => 1,
        'selected' => 0, 'hierarchical' => 0,
        'name' => 'cat', 'id' => '',
        'class' => 'postform', 'depth' => 0,
        'tab_index' => 0, 'taxonomy' => 'category',
        'hide_if_empty' => false,
        'parent' => '',
    );



    // Back compat.
    if ( isset( $args['type'] ) && 'link' == $args['type'] ) {
        _deprecated_argument( __FUNCTION__, '3.0', '' );
        $args['taxonomy'] = 'link_category';
    }

    $r = wp_parse_args( $args, $defaults );

    if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
        $r['pad_counts'] = true;
    }

    extract( $r );

    $tab_index_attribute = '';
    if ( (int) $tab_index > 0 )
        $tab_index_attribute = " tabindex=\"$tab_index\"";

    $categories = get_terms( $taxonomy, $r );
    $name = esc_attr( $name );
    $class = esc_attr( $class );
    $id = $id ? esc_attr( $id ) : $name;

    if ( ! $r['hide_if_empty'] || ! empty($categories) )
        $output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
    else
        $output = '';

    if ( empty($categories) && ! $r['hide_if_empty'] && !empty($show_option_none) ) {

        /**
         * Filter a taxonomy drop-down display element.
         *
         * A variety of taxonomy drop-down display elements can be modified
         * just prior to display via this filter. Filterable arguments include
         * 'show_option_none', 'show_option_all', and various forms of the
         * term name.
         *
         * @since 1.2.0
         *
         * @see wp_dropdown_categories()
         *
         * @param string $element Taxonomy element to list.
         */
        $show_option_none = apply_filters( 'list_cats', $show_option_none );
        $output .= "\t<option value='-1' selected='selected'>$show_option_none</option>\n";
    }

    if ( ! empty( $categories ) ) {

        if ( $show_option_all ) {

            /** This filter is documented in wp-includes/category-template.php */
            $show_option_all = apply_filters( 'list_cats', $show_option_all );
            $selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
            $output .= "\t<option value='0'$selected>$show_option_all</option>\n";
        }

        if ( $show_option_none ) {

            /** This filter is documented in wp-includes/category-template.php */
            $show_option_none = apply_filters( 'list_cats', $show_option_none );
            $selected = ( '-1' === strval($r['selected']) ) ? " selected='selected'" : '';
            $output .= "\t<option value='-1'$selected>$show_option_none</option>\n";
        }

        $output .= walk_category_dropdown_tree( $categories, $depth, $r );
    }

    if ( ! $r['hide_if_empty'] || ! empty($categories) )
        $output .= "</select>\n";

    /**
     * Filter the taxonomy drop-down output.
     *
     * @since 2.1.0
     *
     * @param string $output HTML output.
     * @param array  $r      Arguments used to build the drop-down.
     */
    $output = apply_filters( 'wp_dropdown_cats', $output, $r );

    if ( $echo )
        echo $output;

    return $output;
}

/**
 * Output an unordered list of checkbox <input> elements labelled
 * with term names. Taxonomy independent version of wp_category_checklist().
 *
 * @since 3.0.0
 *
 * @param int   $post_id
 * @param array $args
 */
function aviators_terms_checklist( $post_id = 0, $args = array() ) {
	$defaults = array(
		'descendants_and_self' => 0,
		'selected_cats'        => false,
		'popular_cats'         => false,
		'walker'               => null,
		'taxonomy'             => 'category',
		'checked_ontop'        => true
	);
	$args     = apply_filters( 'wp_terms_checklist_args', $args, $post_id );

	extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

	if ( empty( $walker ) || ! is_a( $walker, 'Walker' ) )
		$walker = new Walker_Category_Checklist;

	$descendants_and_self = (int) $descendants_and_self;

	$args = array( 'taxonomy' => $taxonomy );

	$tax = get_taxonomy( $taxonomy );
//    $args['disabled'] = !current_user_can($tax->cap->assign_terms);

	if ( is_array( $selected_cats ) )
		$args['selected_cats'] = $selected_cats;
	elseif ( $post_id )
		$args['selected_cats'] = wp_get_object_terms( $post_id, $taxonomy, array_merge( $args, array( 'fields' => 'ids' ) ) );
	else
		$args['selected_cats'] = array();

	if ( is_array( $popular_cats ) )
		$args['popular_cats'] = $popular_cats;
	else
		$args['popular_cats'] = get_terms( $taxonomy, array( 'fields' => 'ids', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );

	if ( $descendants_and_self ) {
		$categories = (array) get_terms( $taxonomy, array( 'child_of' => $descendants_and_self, 'hierarchical' => 0, 'hide_empty' => 0 ) );
		$self       = get_term( $descendants_and_self, $taxonomy );
		array_unshift( $categories, $self );
	}
	else {
		$categories = (array) get_terms( $taxonomy, array( 'get' => 'all' ) );
	}

	if ( $checked_ontop ) {
		// Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
		$checked_categories = array();
		$keys               = array_keys( $categories );

		foreach ( $keys as $k ) {
			if ( in_array( $categories[$k]->term_id, $args['selected_cats'] ) ) {
				$checked_categories[] = $categories[$k];
				unset( $categories[$k] );
			}
		}

		// Put checked cats on top

		echo call_user_func_array( array( &$walker, 'walk' ), array( $checked_categories, 0, $args ) );
	}
	// Then the rest of them
	echo call_user_func_array( array( &$walker, 'walk' ), array( $categories, 0, $args ) );
}