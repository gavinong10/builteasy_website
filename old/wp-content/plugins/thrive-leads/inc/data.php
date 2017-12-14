<?php
/**
 * contains all data-related functions for the plugin
 *
 */

/**
 *
 * get all the existing lead groups
 *
 * @param array $filter {
 *                         Optional. Arguments to retrieve lead groups. Available options:
 *
 * @type bool $full_data - whether to retrieve or not the full data (including form_types)
 * }
 * @return array list of all saved groups
 */
function tve_leads_get_groups( $filter = array() ) {
	global $tvedb;
	$defaults = array(
		'full_data'       => true,
		'tracking_data'   => true,
		'completed_tests' => true,
		'active_tests'    => true,
		'no_content'      => false,
	);

	$filter = array_merge( $defaults, $filter );

	$posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => TVE_LEADS_POST_GROUP_TYPE,
		'meta_key'       => 'tve_group_order',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC'
	) );

	foreach ( $posts as $post ) {
		if ( ! empty( $filter['tracking_data'] ) ) {
			$post->impressions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
			$post->conversions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );
			$post->conversion_rate = tve_leads_conversion_rate( $post->impressions, $post->conversions );
		}
		if ( ! empty( $filter['active_tests'] ) ) {
			$post->active_tests = tve_leads_get_group_active_tests( $post->ID );
		}
		if ( ! empty( $filter['completed_tests'] ) ) {
			$post->completed_tests = tve_leads_get_group_completed_tests( $post->ID );
		}
		$post->order = intval( get_post_meta( $post->ID, 'tve_group_order', true ) );
		if ( ! empty( $filter['full_data'] ) ) {
			$post->form_types = tve_leads_get_form_types( array(
				'lead_group_id'  => $post->ID,
				'tracking_data'  => $filter['tracking_data'],
				'get_variations' => true,
				'no_content'     => $filter['no_content']
			) );

			$total_form_types = 0;
			$display_on_mobile = 0;
			$display_status = 0;
			foreach ( $post->form_types as $form ) {
				$total_form_types += empty( $form->ID ) ? 0 : 1;
				$display_on_mobile += isset( $form->display_on_mobile ) ? $form->display_on_mobile : 0;
				$display_status += isset( $form->display_status ) ? $form->display_status : 0;
			}

			if ( $display_on_mobile == 0 ) {
				$post->display_on_mobile = __( 'No', 'thrive-leads' );
			} else if ( $display_on_mobile == $total_form_types ) {
				$post->display_on_mobile = __( 'Yes', 'thrive-leads' );
			} else {
				$post->display_on_mobile = __( 'Custom', 'thrive-leads' );
			}

			if ( $display_status == 0 ) {
				$post->display_status = __( 'No', 'thrive-leads' );
			} else if ( $display_status == $total_form_types ) {
				$post->display_status = __( 'Yes', 'thrive-leads' );
			} else {
				$post->display_status = __( 'Custom', 'thrive-leads' );
			}
		}
		$post->has_display_settings = $tvedb->has_display_settings( $post->ID ) == null ? 0 : 1;
	}

	return $posts;
}

/**
 * get a list of ids from all the existing lead groups
 *
 * @return array
 */
function tve_leads_get_group_ids() {
	$groups          = array();
	$all_lead_groups = tve_leads_get_groups( array(
		'full_data'       => false,
		'tracking_data'   => false,
		'active_tests'    => false,
		'completed_tests' => false
	) );
	foreach ( $all_lead_groups as $lead_group ) {
		array_push( $groups, $lead_group->ID );
	}

	return $groups;
}

/**
 *
 * get one lead group by id
 *
 * @param int $ID id of the group
 * @param array $filter {
 *                         Optional. Arguments to retrieve lead groups. Available options:
 *
 * @type bool $full_data - whether to retrieve or not the full data (including form_types)
 * }
 * @return WP_Post|null
 */
function tve_leads_get_group( $ID, $filter = array() ) {
	global $tvedb;
	$defaults = array(
		'full_data'       => true,
		'tracking_data'   => false,
		'completed_tests' => false,
		'active_tests'    => false
	);

	$filter = array_merge( $defaults, $filter );
	$post   = get_post( $ID );
	if ( empty( $post ) ) {
		return null;
	}

	if ( ! empty( $filter['tracking_data'] ) ) {
		$post->impressions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
		$post->conversions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );
		$post->conversion_rate = tve_leads_conversion_rate( $post->impressions, $post->conversions );
	}
	if ( ! empty( $filter['active_tests'] ) ) {
		$post->active_tests = tve_leads_get_group_active_tests( $post->ID );
	}
	if ( ! empty( $filter['completed_tests'] ) ) {
		$post->completed_tests = tve_leads_get_group_completed_tests( $post->ID );
	}
	$post->order = intval( get_post_meta( $post->ID, 'tve_group_order', true ) );
	if ( ! empty( $filter['full_data'] ) ) {
		$post->form_types  = tve_leads_get_form_types( array(
			'lead_group_id'  => $post->ID,
			'tracking_data'  => $filter['tracking_data'],
			'get_variations' => true
		) );
		$display_on_mobile = 0;
		foreach ( $post->form_types as $form ) {
			$display_on_mobile += isset( $form->display_on_mobile ) ? $form->display_on_mobile : 1;
		}
		if ( $display_on_mobile == 0 ) {
			$post->display_on_mobile = __( 'No', 'thrive-leads' );
		} else if ( $display_on_mobile == 5 ) {
			$post->display_on_mobile = __( 'Yes', 'thrive-leads' );
		} else {
			$post->display_on_mobile = __( 'Custom', 'thrive-leads' );
		}
	}

	return $post;
}

/**
 * Gets the tve_leads_shortcode posts from database and returns them
 *
 * @return array
 */
function tve_leads_get_shortcodes( $filter = array() ) {

	$defaults = array(
		'active_test'    => false,
		'tracking_data'  => false,
		'get_variations' => false
	);

	$filter = array_merge( $defaults, $filter );

	$posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => TVE_LEADS_POST_SHORTCODE_TYPE,
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC'
	) );

	foreach ( $posts as $post ) {
		if ( ! empty( $filter['active_test'] ) ) {
			$post->active_test = tve_leads_get_form_active_test( $post->ID, array(
				'test_type' => TVE_LEADS_SHORTCODE_TEST_TYPE
			) );
		}
		if ( ! empty( $filter['tracking_data'] ) ) {
			$post->impressions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
			$post->conversions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );
			$post->conversion_rate = tve_leads_conversion_rate( $post->impressions, $post->conversions );
		}
		if ( ! empty( $filter['get_variations'] ) ) {
			$post->variations = tve_leads_get_form_variations( $post->ID, array(
				'tracking_data' => false
			) );
		}

		$post->content_locking = get_post_meta( $post->ID, 'tve_content_locking', true );
		$post->content_locking = $post->content_locking == '' ? 0 : intval( $post->content_locking );
		$post->shortcode_code  = ( $post->content_locking == 1 ) ? '[thrive_lead_lock id=\'' . $post->ID . '\']Hidden Content[/thrive_lead_lock]' : '[thrive_leads id=\'' . $post->ID . '\']';
	}

	return $posts;
}

/**
 * Gets the tve_leads_two_step_lightbox posts from database and returns them
 *
 * @param array $filter allows a way to control the output
 *
 * @return array
 */
function tve_leads_get_two_step_lightboxes( $filter = array() ) {
	$defaults = array(
		'active_test'    => false,
		'tracking_data'  => false,
		'get_variations' => false
	);

	$filter = array_merge( $defaults, $filter );

	$posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => TVE_LEADS_POST_TWO_STEP_LIGHTBOX,
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC'
	) );

	foreach ( $posts as $post ) {
		if ( ! empty( $filter['active_test'] ) ) {
			$post->active_test = tve_leads_get_form_active_test( $post->ID, array(
				'test_type' => TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE
			) );
		}
		if ( ! empty( $filter['tracking_data'] ) ) {
			$post->impressions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
			$post->conversions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );
			$post->conversion_rate = tve_leads_conversion_rate( $post->impressions, $post->conversions );
		}
		if ( ! empty( $filter['get_variations'] ) ) {
			$post->variations = tve_leads_get_form_variations( $post->ID, array(
				'tracking_data' => false
			) );
		}
	}

	return $posts;
}

/**
 * Gets the tve_leads_two_step_lightbox posts from database and returns them
 *
 * @param array $filter allows a way to control the output
 *
 * @return array
 */
function tve_leads_get_asset_groups( $filter = array() ) {
	$defaults = array(
		'active_test' => false
	);

	$filter = array_merge( $defaults, $filter );

	$posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => TVE_LEADS_POST_ASSET_GROUP,
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC'
	) );

	foreach ( $posts as $post ) {
		$post->order        = intval( get_post_meta( $post->ID, 'tve_asset_group_order', true ) );
		$post->files        = get_post_meta( $post->ID, 'tve_asset_group_files', true );
		$post->post_subject = get_post_meta( $post->ID, 'tve_asset_group_subject', true );
	}

	return $posts;
}

/**
 * Gets the tve_leads_one_click_signup posts from database and returns them
 *
 * @return array
 */
function tve_leads_get_one_click_signups() {
	$posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => TVE_LEADS_POST_ONE_CLICK_SIGNUP,
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC'
	) );

	foreach ( $posts as $post ) {
		$post->post_link       = get_permalink( $post->ID );
		$post->redirect_url    = (object) get_post_meta( $post->ID, 'tve_leads_redirect_url', true );
		$post->api_connections = get_post_meta( $post->ID, 'tve_leads_api_connections', true );
		$post->signups         = get_post_meta( $post->ID, 'tve_leads_signups', true );
	}

	return $posts;
}

/**
 * Get shortcode
 *
 * @param       $ID
 * @param array $filters
 *
 * @return WP_Post shortcode
 */
function tve_leads_get_shortcode( $ID, $filters = array() ) {
	$defaults = array(
		'get_variations'      => false,
		'variations_archived' => false,
		'completed_tests'     => false
	);

	$filters = array_merge( $defaults, $filters );

	$shortcode = get_post( $ID );

	if ( ! $shortcode || ! $shortcode->ID || get_post_type( $shortcode ) !== TVE_LEADS_POST_SHORTCODE_TYPE ) {
		return null;
	}

	if ( ! empty( $filters['get_variations'] ) ) {
		$shortcode->variations = tve_leads_get_form_variations( $ID, array(
			'tracking_data' => true
		) );
	}

	if ( ! empty( $filters['variations_archived'] ) ) {
		$shortcode->variations_archived = tve_leads_get_form_variations( $ID, array(
			'tracking_data' => true,
			'post_status'   => TVE_LEADS_STATUS_ARCHIVED
		) );
	}

	if ( ! empty( $filters['completed_tests'] ) ) {
		$shortcode->completed_tests = tve_leads_get_completed_form_test( $shortcode, TVE_LEADS_SHORTCODE_TEST_TYPE );
	}

	$shortcode->content_locking = get_post_meta( $shortcode->ID, 'tve_content_locking', true );
	$shortcode->content_locking = empty( $shortcode->content_locking ) ? 0 : intval( $shortcode->content_locking );

	return $shortcode;
}


/**
 * @param       $type
 * @param array $filter {
 *                      pass in additional filters for retrieving the data
 *                      }
 *
 * TODO: I think we'll need to cache this somehow
 *
 * @return mixed the value
 */
function tve_leads_get_tracking_data( $type, $filter = array() ) {
	global $tvedb;

	$event_log_filter = array(
		'event_type' => $type,
		'archived'   => 0
	);

	$event_log_filter = array_merge( $event_log_filter, $filter );

	return $tvedb->count_events( $event_log_filter );
}

/**
 * get all "form_type" posts based on a $params filter
 * usually, $params should hold the parent Lead Group id
 *
 * @param $params
 *
 * @return mixed
 */
function tve_leads_get_form_types( $params = array() ) {
	$defaults = array(
		'tracking_data'  => true,
		'get_variations' => false,
		'no_content'     => false,
	);

	$posts = get_posts( array(
		'numberposts'      => - 1,
		'post_type'        => TVE_LEADS_POST_FORM_TYPE,
		'post_parent'      => isset( $params['lead_group_id'] ) ? $params['lead_group_id'] : 0,
		'meta_key'         => 'tve_form_type',
		'suppress_filters' => false
	) );

	$params = array_merge( $defaults, $params );

	$available = tve_leads_get_default_form_types();
	$existing  = array();

	foreach ( $posts as $post ) {
		if ( ! empty( $params['tracking_data'] ) ) {
			$post->impressions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
			$post->conversions     = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );
			$post->conversion_rate = tve_leads_conversion_rate( $post->impressions, $post->conversions );
			$post->active_test     = tve_leads_get_form_active_test( $post->ID );

			//by default, when an option is not set, a form type is displayed on mobile
			$display_on_mobile       = get_post_meta( $post->ID, 'display_on_mobile', true );
			$post->display_on_mobile = $display_on_mobile == '' ? 1 : intval( $display_on_mobile );

			//by default, when an option is not set, a form type status is enabled
			$display_status       = get_post_meta( $post->ID, 'display_status', true );
			$post->display_status = $display_status == '' ? 1 : intval( $display_status );
		}
		$post->tve_form_type = get_post_meta( $post->ID, 'tve_form_type', true );
		if ( ! empty( $params['get_variations'] ) ) {
			$post->variations = tve_leads_get_form_variations( $post->ID, array(
				'tracking_data' => false,
				'no_content'    => $params['no_content']
			) );
		}

		if ( isset( $available[ $post->tve_form_type ]['video_link'] ) ) {
			$post->video_link = $available[ $post->tve_form_type ]['video_link'];
		}

		$existing[ $post->tve_form_type ] = $post;
	}

	$actual = array_merge( $available, $existing );

	return array_values( $actual );
}

/**
 * get all published form variations for a FormType
 *
 * @param int $ID the FormType id
 * @param array $filters
 *
 * @return mixed
 */
function tve_leads_get_form_variations( $ID, $filters = array() ) {
	global $tvedb;

	$defaults = array(
		'tracking_data'      => true,
		'post_status'        => TVE_LEADS_STATUS_PUBLISH,
		'get_control'        => false,
		'active_for_test_id' => false,
	);

	$filters = array_merge( $defaults, $filters );

	$variations = $tvedb->get_form_variations( array(
		'post_parent'        => $ID,
		'post_status'        => $filters['post_status'],
		'limit'              => empty( $filters['get_control'] ) ? null : 1,
		'offset'             => 0,
		'order'              => 'key ASC',
		'active_for_test_id' => $filters['active_for_test_id'],
	), false );

	foreach ( $variations as $k => $variation ) {
		if ( ! empty( $filters['tracking_data'] ) ) {
			$variations[ $k ]['impressions'] = $impressions = tve_leads_get_variation_tracking_data( $variation, TVE_LEADS_UNIQUE_IMPRESSION );
			$variations[ $k ]['conversions'] = $conversions = tve_leads_get_variation_tracking_data( $variation, TVE_LEADS_CONVERSION );
			if ( ! empty( $variation['save_flag'] ) ) {
				$tvedb->update_variation_fields( $variations[ $k ], array(
					'cache_impressions' => $impressions,
					'cache_conversions' => $conversions
				) );
			}
			$variations[ $k ]['conversion_rate'] = tve_leads_conversion_rate( $impressions, $conversions );
		}

		if ( ! empty( $filters['no_content'] ) ) {
			$variations[ $k ]['content'] = ! empty( $variations[ $k ]['content'] );
		}

		$variations[ $k ]['tcb_edit_url']    = tve_leads_get_editor_url( $ID, $variation['key'] );
		$variations[ $k ]['tcb_preview_url'] = tve_leads_get_preview_url( $ID, $variation['key'] );

		if ( empty( $variation['trigger'] ) ) {
			$variation['trigger'] = $variations[ $k ]['trigger'] = 'page_load';
		}

		$variations[ $k ]['trigger_nice_name']           = tve_leads_trigger_nice_name( $variation );
		$variations[ $k ]['display_frequency_nice_name'] = tve_leads_frequency_nice_name( $variation );
		$variations[ $k ]['display_position_nice_name']  = tve_leads_position_nice_name( $variation );
		$variations[ $k ]['display_animation_nice_name'] = tve_leads_animation_nice_name( $variation );
		$variations[ $k ]['content_lock_display']        = in_array( $variation['display_animation'], array(
			'hide',
			'blur'
		) ) ? $variation['display_animation'] : 'hide';

		$variations[ $k ]['is_control'] = ! isset( $control ); // the first one is always the control
		if ( ! empty( $filters['get_control'] ) ) {
			return $variations[ $k ];
		}
		$control = false;
	}

	return $variations;
}

/**
 * Return an array of variation keys that do don't have a template selected
 *
 * @param $form_id
 *
 * @return array ids
 */
function tve_leads_get_form_empty_variations( $form_id ) {
	$variations = tve_leads_get_form_variations( $form_id, array( 'tracking_data' => false ) );
	$ids        = array();
	foreach ( $variations as $v ) {
		if ( ! empty( $v[ TVE_LEADS_FIELD_TEMPLATE ] ) ) {
			continue;
		}
		$ids [] = (int) $v['key'];
	}

	return $ids;
}

/**
 * Return an array of the form_types that don't have a design in the specified group
 *
 * @param $group_id The group id where we search for forms with no design
 *
 * @return array
 */
function tve_leads_get_group_empty_form_variations( $group_id ) {

	$form_types = tve_leads_get_form_types( array(
		'lead_group_id'  => $group_id,
		'tracking_data'  => false,
		'get_variations' => true
	) );

	$ids = array();
	foreach ( $form_types as $form ) {
		if ( isset( $form->variations ) ) {
			foreach ( $form->variations as $variation ) {
				if ( $variation['is_control'] && empty( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ) {
					$ids [] = (int) $form->ID;
					break;
				}
			}
		}
	}

	return $ids;
}

/**
 * Delete posts like Group, Shortcode, 2 Step Lightbox and Asset Group (new name: ThriveBox)
 *
 * @param $group_id
 *
 * @return mixed
 */
function tve_leads_delete_post( $group_id ) {
	$post = get_post( $group_id );
	if ( empty( $post ) ) {
		return $group_id;
	}

	global $tvedb;

	if ( $post->post_type === TVE_LEADS_POST_GROUP_TYPE ) {
		$tvedb->delete_display_settings( array( 'group' => $group_id ) );

		// also delete Form Types under this group
		foreach ( tve_leads_get_form_types( array( 'lead_group_id' => $group_id, 'tracking_data' => false ) ) as $form_type ) {
			if ( is_a( $form_type, 'WP_Post' ) && ! empty( $form_type->ID ) ) {
				tve_leads_delete_post( $form_type->ID );
			}
		}
	}

	$tvedb->delete_tests( array( 'main_group_id' => $group_id ), array( 'delete_items' => true ) );
	$tvedb->delete_logs( array( 'main_group_id' => $group_id ) );
	$post->post_status = 'trash';

	return wp_update_post( $post );
}

/**
 * Delete post meta (File relations) form Asset Groups (new name: ThriveBox)
 *
 * @param $post_id
 * @param $meta_id
 *
 * @return mixed
 */
function tve_leads_delete_asset_file( $post_id, $meta_id ) {
	$post_meta = get_post_meta( $post_id, 'tve_asset_group_files', true );

	foreach ( $post_meta as $k => $v ) {
		if ( $v["ID"] == $meta_id ) {
			unset( $post_meta[ $k ] );
		}
	}
	$new_meta = array_values( $post_meta );

	update_post_meta( $post_id, 'tve_asset_group_files', $new_meta );

	return $meta_id;
}

/**
 * create or update a Lead Group post
 *
 * can also be used to delete a lead group "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_group( $model ) {
	if ( ! empty( $model['ID'] ) ) {
		$lead_group = get_post( $model['ID'] );
		if ( $lead_group && get_post_type( $lead_group ) === TVE_LEADS_POST_GROUP_TYPE ) {
			wp_update_post( $model );
		}
		$ID = $model['ID'];
	} else {
		$default = array(
			'post_type'   => TVE_LEADS_POST_GROUP_TYPE,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
		/**
		 * save these from here, as they will be 0 for new Lead Groups
		 */
		update_post_meta( $ID, 'tve_leads_impressions', 0 );
		update_post_meta( $ID, 'tve_leads_conversions', 0 );
	}

	if ( isset( $model['order'] ) ) {
		update_post_meta( $ID, 'tve_group_order', (int) $model['order'] );
	}

	return $ID;
}

/**
 * create or update a Lead Shortcode post
 *
 * can also be used to delete a lead group "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_shortcode( $model ) {
	if ( ! empty( $model['ID'] ) ) {
		$lead_shortcode = get_post( $model['ID'] );
		if ( $lead_shortcode && get_post_type( $lead_shortcode ) === TVE_LEADS_POST_SHORTCODE_TYPE ) {
			wp_update_post( $model );
		}
		$ID = $model['ID'];
	} else {
		$default = array(
			'post_type'   => TVE_LEADS_POST_SHORTCODE_TYPE,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
		/**
		 * save these from here, as they will be 0 for new Shortcodes
		 */
		update_post_meta( $ID, 'tve_leads_impressions', 0 );
		update_post_meta( $ID, 'tve_leads_conversions', 0 );
	}

	if ( isset( $ID ) ) {
		update_post_meta( $ID, 'tve_form_type', 'shortcode' );
		update_post_meta( $ID, 'tve_content_locking', $model['content_locking'] );
	}

	return $ID;
}

/**
 * create or update a 2 Step Lightbox post (new name: ThriveBox)
 *
 * can also be used to delete a lead group "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_two_step_lightbox( $model ) {
	if ( ! empty( $model['ID'] ) ) {
		$two_step_lightbox = get_post( $model['ID'] );
		if ( $two_step_lightbox && get_post_type( $two_step_lightbox ) === TVE_LEADS_POST_TWO_STEP_LIGHTBOX ) {
			wp_update_post( $model );
		}
		$ID = $model['ID'];
	} else {
		$default = array(
			'post_type'   => TVE_LEADS_POST_TWO_STEP_LIGHTBOX,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
		/**
		 * save these from here, as they will be 0 for newly created 2-step Lightboxes
		 */
		update_post_meta( $ID, 'tve_leads_impressions', 0 );
		update_post_meta( $ID, 'tve_leads_conversions', 0 );
	}

	if ( isset( $ID ) ) {
		update_post_meta( $ID, 'tve_form_type', 'two_step_lightbox' );
	}

	return $ID;
}

/**
 * create or update a Asset Group post (new name: ThriveBox)
 *
 * can also be used to delete a lead group "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_asset_group( $model ) {

	if ( ! empty( $model['ID'] ) ) {
		$asset_group = get_post( $model['ID'] );
		if ( $asset_group && get_post_type( $asset_group ) === TVE_LEADS_POST_ASSET_GROUP ) {
			wp_update_post( $model );
			if ( ! empty( $model['post_subject'] ) ) {
				update_post_meta( $model['ID'], 'tve_asset_group_subject', $model['post_subject'] );
			}
		}
		$ID = $model['ID'];
	} else {
		$default = array(
			'post_type'   => TVE_LEADS_POST_ASSET_GROUP,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
	}

	return $ID;
}

function tve_leads_update_asset_files( $model ) {
	if ( ! empty( $model['ID'] ) || $model['ID'] == "0" ) {
		$ID          = $model['parent_ID'];
		$asset_group = get_post( $ID );
		if ( $asset_group && get_post_type( $asset_group ) === TVE_LEADS_POST_ASSET_GROUP ) {
			$existing_meta = get_post_meta( $ID, 'tve_asset_group_files', true );
			unset( $model['parent_ID'] );
			foreach ( $existing_meta as $k => $v ) {
				if ( $v['ID'] == $model['ID'] ) {
					$existing_meta[ $k ]['name']        = $model['name'];
					$existing_meta[ $k ]['link_anchor'] = $model['link_anchor'];
				}
			}
			update_post_meta( $ID, 'tve_asset_group_files', $existing_meta );
		}

		$index = $model['ID'];
	} else {
		$ID          = $model['parent_ID'];
		$asset_group = get_post( $ID );
		if ( $asset_group && get_post_type( $asset_group ) === TVE_LEADS_POST_ASSET_GROUP ) {
			$existing_meta = get_post_meta( $ID, 'tve_asset_group_files', true );
			unset( $model['parent_ID'] );
			if ( empty( $existing_meta ) ) {
				$index       = 0;
				$model["ID"] = $index;
				update_post_meta( $ID, 'tve_asset_group_files', array( $model ) );
			} else {
				$max   = 0;
				$index = 0;
				foreach ( $existing_meta as $k => $v ) {

					if ( $v['ID'] > $max ) {
						$max   = $v['ID'];
						$index = $v['ID'];
					}
				}
				$index ++;
				$model['ID'] = $index;
				$new_meta    = array_merge( $existing_meta, array( $model ) );

				update_post_meta( $ID, 'tve_asset_group_files', $new_meta );
			}
		}
	}

	return $index;
}

function tve_leads_add_wizard_group( $model ) {
	$default = array(
		'post_type'   => TVE_LEADS_POST_ASSET_GROUP,
		'post_status' => 'publish'
	);
	$ID      = wp_insert_post( array_merge( $default, $model ) );
	update_post_meta( $ID, 'tve_asset_group_files', $model['files'] );

	return ( $ID );
}

/**
 * create or update a One Click Signup post (new name: Signup Segue)
 *
 * can also be used to delete a lead group "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_one_click_signup( $model ) {
	if ( ! empty( $model['ID'] ) ) {
		$one_click_signup = get_post( $model['ID'] );
		if ( $one_click_signup && get_post_type( $one_click_signup ) === TVE_LEADS_POST_ONE_CLICK_SIGNUP ) {
			wp_update_post( $model );
		}
		$ID = $model['ID'];
		foreach ( $model['api_connections'] as $key => $api_connection ) {
			if ( isset ( $model['api_connections'][ $key ]['activecampaign_tags'] ) ) {
				$model['api_connections'][ $key ]['activecampaign_tags'] = urldecode( $api_connection['activecampaign_tags'] );
			}
		}
		update_post_meta( $ID, 'tve_leads_redirect_url', $model['redirect_url'] );
		update_post_meta( $ID, 'tve_leads_api_connections', $model['api_connections'] );
	} else {
		$default = array(
			'post_type'   => TVE_LEADS_POST_ONE_CLICK_SIGNUP,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
		/**
		 * save these from here, as they will be 0 for newly created one click signup (new name: Signup Segue)
		 */
		update_post_meta( $ID, 'tve_leads_signups', 0 );
		update_post_meta( $ID, 'tve_leads_redirect_url', new stdClass() );
		update_post_meta( $ID, 'tve_leads_api_connections', array() );
	}


	if ( isset( $ID ) ) {
		update_post_meta( $ID, 'tve_form_type', 'one_click_signup' );
	}
	$post_link = get_permalink( $ID );

	return json_encode( array( "ID" => $ID, "post_link" => $post_link ) );
}

/**
 * create or update a Form Type post
 *
 * can also be used to delete a form type "internally", by setting the post_status to 'trash'
 *
 * @param $model
 *
 * @return int|WP_Error
 */
function tve_leads_save_form_type( $model ) {
	if ( ! empty( $model['ID'] ) ) {
		$form_type = get_post( $model['ID'] );
		if ( $form_type && get_post_type( $form_type ) === TVE_LEADS_POST_FORM_TYPE ) {
			wp_update_post( $model );
			$ID = $model['ID'];
		}
	} else {
		/*
         * check if already exists a form type of the same type added elsewhere(new window)
         */
		if ( ! empty( $model['post_parent'] ) ) {
			$q = new WP_Query( array(
				'post_parent__in' => array( $model['post_parent'] ),
				'post_type'       => TVE_LEADS_POST_FORM_TYPE,
				'meta_key'        => 'tve_form_type',
				'meta_value'      => $model['tve_form_type']
			) );

			$posts = $q->get_posts();
			if ( ! empty( $posts ) ) {
				$existing = $posts[0];

				return $existing->ID;
			}
		}

		$default = array(
			'post_type'   => TVE_LEADS_POST_FORM_TYPE,
			'post_status' => 'publish'
		);
		$ID      = wp_insert_post( array_merge( $default, $model ) );
		/**
		 * these will be 0 for new form types
		 */
		update_post_meta( $ID, 'tve_leads_impressions', 0 );
		update_post_meta( $ID, 'tve_leads_conversions', 0 );
	}

	if ( isset( $ID ) ) {
		update_post_meta( $ID, 'tve_form_type', $model['tve_form_type'] );
		update_post_meta( $ID, 'display_on_mobile', $model['display_on_mobile'] );
		update_post_meta( $ID, 'display_status', $model['display_status'] );
	}

	return isset( $ID ) ? $ID : 0;
}

/**
 * get form type by ID and load any necessary extra data, such as form variations
 *
 * @param       $ID
 *
 * @param array $filters offers a way to control the returned value
 *
 * @return null|WP_Post
 */
function tve_leads_get_form_type( $ID, $filters = array() ) {
	$defaults = array(
		'active_test'     => false,
		'completed_tests' => false,
		'get_variations'  => true,
	);
	$filters  = array_merge( $defaults, $filters );

	$form_type = get_post( $ID );
	$post_type = get_post_type( $ID );
	if ( ! $form_type || ! $form_type->ID
	     || ! in_array( $post_type, array(
			TVE_LEADS_POST_FORM_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX,
			TVE_LEADS_POST_ONE_CLICK_SIGNUP
		) )
	) {
		return null;
	}

	if ( ! empty( $filters['get_variations'] ) ) {
		$form_type->variations          = tve_leads_get_form_variations( $ID );
		$form_type->variations_archived = tve_leads_get_form_variations( $ID, array(
			'post_status' => 'archived'
		) );
	}

	if ( ! empty( $filters['active_test'] ) ) {
		$form_type->active_test = tve_leads_get_form_active_test( $form_type->ID, array(
			'test_type' => tve_leads_get_test_type_from_post_type( $post_type )
		) );
	}

	$form_type->tve_form_type = get_post_meta( $ID, 'tve_form_type', true );

	return $form_type;
}

/**
 * get a form variation based on $form_type_id and $variation_key
 *
 * @param int $form_type_id
 * @param int $variation_key
 *
 * @param array $filter allows fetching tracking data and other (possible) required data
 *
 * @return array|null the variation or null if it couldn't be found
 */
function tve_leads_get_form_variation( $form_type_id, $variation_key, $filter = array() ) {
	global $tvedb;

	$defaults = array(
		'tracking_data' => false
	);
	$filter   = array_merge( $defaults, $filter );

	$variation = $tvedb->get_form_variation( $variation_key );

	if ( empty( $variation ) ) {
		return null;
	}

	if ( ! empty( $filter['tracking_data'] ) ) {
		$variation['impressions'] = $impressions = tve_leads_get_variation_tracking_data( $variation, TVE_LEADS_UNIQUE_IMPRESSION );
		$variation['conversions'] = $conversions = tve_leads_get_variation_tracking_data( $variation, TVE_LEADS_CONVERSION );
		if ( ! empty( $variation['save_flag'] ) ) {
			$tvedb->update_variation_fields( $variation, array(
				'cache_impressions' => $impressions,
				'cache_conversions' => $conversions
			) );
		}
		$variation['conversion_rate'] = tve_leads_conversion_rate( $impressions, $conversions );
	}
	if ( empty( $variation['trigger'] ) ) {
		$variation['trigger'] = 'page_load';
	}
	$variation['trigger_nice_name']           = tve_leads_trigger_nice_name( $variation );
	$variation['display_frequency_nice_name'] = tve_leads_frequency_nice_name( $variation );
	$variation['display_position_nice_name']  = tve_leads_position_nice_name( $variation );
	$variation['display_animation_nice_name'] = tve_leads_animation_nice_name( $variation );

	return $variation;
}

/**
 * Insert new split test in database
 *
 * @param $model
 *
 * @return int
 */
function tve_leads_save_test( $model ) {
	global $tvedb;

	$defaults = array(
		'status'       => 'running',
		'date_added'   => date( "Y-m-d H:i:s" ),
		'date_started' => date( "Y-m-d H:i:s" ),
	);

	if ( isset( $model['item_ids'] ) ) {
		$item_ids = $model['item_ids'];
		unset( $model['item_ids'] );
	}

	if ( isset( $model['items'] ) ) {
		$items = $model['items'];
		unset( $model['items'] );
	}

	if ( ! empty( $model['id'] ) ) {
		return $tvedb->save_test( $model );
	}

	$model = array_merge( $defaults, $model );

	//insert into DB group test
	if ( isset( $model['form_types'] ) ) {
		$form_types = $model['form_types'];
		unset( $model['form_types'] );
	}

	$test_id = $tvedb->save_test( $model );

	if ( $test_id ) {
		$model['id']         = $test_id;
		$model['form_types'] = isset( $form_types ) ? $form_types : null;
		$test_items_ids      = tve_leads_save_test_items( $model );
	}

	return $model;
}

function tve_leads_delete_test( $id ) {
	global $tvedb;
	$tvedb->delete( tve_leads_table_name( 'split_test' ), array( 'ID' => $id ) );

	return $tvedb->delete( tve_leads_table_name( 'split_test_items' ), array( 'test_id' => $id ) );

}

/**
 * Insert new slit test ITEMS base on test_model
 *
 * @param $test_model array
 *
 * @return item_ids array of integers
 */
function tve_leads_save_test_items( $test_model ) {
	global $tvedb;

	$item_ids = array();

	switch ( $test_model['test_type'] ) {
		//insert test items for each variation
		case     TVE_LEADS_VARIATION_TEST_TYPE:
			$form_type         = tve_leads_get_form_type( $test_model['main_group_id'] );
			$default_test_item = array(
				'test_id'       => $test_model['id'],
				'main_group_id' => $form_type->post_parent,
				'form_type_id'  => $test_model['main_group_id'],
			);

			foreach ( $form_type->variations as $variation ) {
				$test_item  = array_merge( $default_test_item, array(
					'variation_key' => $variation['key'],
					'is_control'    => intval( $variation['is_control'] ),
				) );
				$item_ids[] = $tvedb->save_test_item( $test_item );
			}

			return $item_ids;
			break;

		//insert test items for each form type in test_model
		case TVE_LEADS_GROUP_TEST_TYPE:
			if ( empty( $test_model['form_types'] ) ) {
				return;
			}

			//insert test items for each form type
			$default_test_item = array(
				'test_id'       => $test_model['id'],
				'main_group_id' => $test_model['main_group_id'],
			);

			foreach ( $test_model['form_types'] as $key => $form_id ) {
				$test                  = $default_test_item;
				$test['form_type_id']  = $form_id;
				$control_variation     = tve_leads_get_form_variations( $form_id, array(
					'tracking_data' => false,
					'get_control'   => true,
				) );
				$test['variation_key'] = $control_variation['key'];
				$test['is_control']    = $key === 0 ? 1 : 0;
				$item_ids[]            = $tvedb->save_test_item( $test );
			}

			return $item_ids;
			break;

		case TVE_LEADS_SHORTCODE_TEST_TYPE:
		case TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE:
			$variations        = tve_leads_get_form_variations( $test_model['main_group_id'] );
			$default_test_item = array(
				'test_id'       => $test_model['id'],
				'main_group_id' => $test_model['main_group_id'],
				'form_type_id'  => $test_model['main_group_id'],
			);
			foreach ( $variations as $variation ) {
				$test_item  = array_merge( $default_test_item, array(
					'variation_key' => $variation['key'],
					'is_control'    => intval( $variation['is_control'] ),
				) );
				$item_ids[] = $tvedb->save_test_item( $test_item );
			}

			return $item_ids;
			break;
	}

	return false;

}

/**
 * Save test item and if the test item is_winner
 * do_action for setting up a winner is called
 *
 * @param array $test_item
 *
 * @return bool
 */
function tve_leads_save_test_item( $test_item ) {
	global $tvedb;

	if ( $saved = $tvedb->save_test_item( $test_item ) && $test_item['is_winner'] ) {
		$test_model       = tve_leads_get_test( $test_item['test_id'], array( 'load_test_items' => true ) );
		$winner_test_item = (object) $test_item;

		/*If there are stop variations merge them into the items array to archive them.*/
		if ( ! empty( $test_model->stopped_items ) ) {
			$test_model->items = array_merge( $test_model->items, $test_model->stopped_items );
		}

		foreach ( $test_model->items as $_item ) {
			if ( $_item->id == $test_item['id'] ) {
				/* this might be cleaner */
				$winner_test_item = $_item;
				break;
			}
		}
		/**
		 * in case of a group level test, we need to archive all the variations that are not related to the winner form type
		 * in case of a form type level test, we need to archive all other variations
		 */
		$test        = clone $test_model;
		$winner_item = clone $winner_test_item;

		$test->url              = admin_url( 'admin.php?page=thrive_leads_dashboard' ) . '#test/' . $test->id;
		$test->trigger_source   = 'leads';
		$winner_item->variation = $tvedb->get_form_variation( $winner_item->variation_key );

		do_action( TVE_LEADS_ACTION_SET_TEST_ITEM_WINNER, $winner_item, $test );
	}

	return $saved;
}

/**
 * save the form variation as a meta field for the FormType
 *
 * @return false|array $model the saved variation array, false in case of invalid parameters
 */
function tve_leads_save_form_variation( $model ) {
	global $tvedb;

	if ( empty( $model['post_parent'] ) ) {
		return 0;
	}

	$form_type = get_post( $model['post_parent'] );
	$post_type = get_post_type( $form_type );

	if ( ! $form_type || ! $form_type->ID
	     || ! in_array( $post_type, array(
			TVE_LEADS_POST_FORM_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX
		) )
	) {
		return false;
	}

	$_type = get_post_meta( $form_type->ID, 'tve_form_type', true );

	if ( ! isset( $model['display_frequency'] ) ) {
		$model['display_frequency'] = tve_leads_get_default_display_frequency( $_type );
	}
	if ( ! isset( $model['position'] ) ) {
		$model['position'] = tve_leads_get_default_position( $_type );
	}
	if ( empty( $model['display_animation'] ) ) {
		$model['display_animation'] = tve_leads_get_default_animation( $_type );
	}

	if ( empty( $model['key'] ) ) { /* add as new one */

		$data = array(
			'post_status'       => 'publish',
			'post_parent'       => $form_type->ID,
			'post_title'        => '',
			'trigger'           => 'page_load',
			'trigger_config'    => array(),
			'tcb_fields'        => array(),
			'content'           => '',
			'cache_impressions' => 0,
			'cache_conversions' => 0
		);

	} else {
		$data = tve_leads_get_form_variation( $form_type->ID, $model['key'] );
	}

	if ( isset( $data ) ) {

		/**
		 * at first, when a new form is created, we'll have to leave the template and content fields empty,
		 * so that the template chooser will open by default
		 */

		//TODO: find a better way of handling these
		$data['post_title']        = $model['post_title'];
		$data['trigger']           = $model['trigger'];
		$data['trigger_config']    = $model['trigger_config'];
		$data['post_status']       = $model['post_status'];
		$data['display_frequency'] = $model['display_frequency'];
		$data['position']          = $model['position'];
		$data['display_animation'] = $model['display_animation'];
		if ( isset( $model['parent_id'] ) ) {
			$data['parent_id'] = $model['parent_id'];
		}
		if ( isset( $model['state_order'] ) ) {
			$data['state_order'] = $model['state_order'];
		}
		if ( isset( $model['form_state'] ) ) {
			$data['form_state'] = $model['form_state'];
		}

		foreach ( tve_leads_get_editor_fields() as $field ) {
			if ( ! isset( $model[ $field ] ) ) {
				continue;
			}
			if ( $field == TVE_LEADS_FIELD_SAVED_CONTENT ) { // the content is saved directly in the variation array
				$data[ $field ] = $model[ $field ];
			} else { // everything else goes into the 'tcb_fields' array
				$data['tcb_fields'][ $field ] = $model[ $field ];
			}
		}

		if ( isset( $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_MASONRY ] ) ) {
			/**
			 * update the masonry option also in the parent post, because if the lazy-load option is set, we need to include masonry in the main page
			 */
			update_post_meta( $data['post_parent'], 'tve_leads_masonry', $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_MASONRY ] );
			if ( ! empty( $form_type->post_parent ) ) {
				update_post_meta( $form_type->post_parent, 'tve_leads_masonry', $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_MASONRY ] );
			}
		}

		if ( isset( $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_TYPEFOCUS ] ) ) {
			/**
			 * update the typefocus option also in the parent post, because if the lazy-load option is set, we need to include typist in the main page
			 */
			update_post_meta( $data['post_parent'], 'tve_leads_typefocus', $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_TYPEFOCUS ] );
			if ( ! empty( $form_type->post_parent ) ) {
				update_post_meta( $form_type->post_parent, 'tve_leads_typefocus', $data['tcb_fields'][ TVE_LEADS_FIELD_HAS_TYPEFOCUS ] );
			}
		}

		$parent = $tvedb->save_form_variation( $data );

		if ( ! empty( $model['form_child_states'] ) ) {
			/**
			 * recreate all child states for the new form - used for cloning and re-adding of archived forms
			 */
			tve_leads_clone_form_states( $parent, $model['form_child_states'] );
		}

		return $parent;
	}

	return false;
}

/**
 * clone an array of form variation states (children) of the $original_parent_key and add them as states of the $parent_form
 *
 * @param array $parent_form the new parent form
 * @param array $original_children
 */
function tve_leads_clone_form_states( $parent_form, $original_children ) {
	if ( empty( $original_children ) ) {
		return;
	}

	$original_parent_key = $original_children[0]['parent_id'];

	/**
	 * we need this for parsing each of the form contents and replace event configuration IDs
	 */
	$form_key_map = array(
		$original_parent_key => $parent_form['key'],
	);

	$to_replace = array( $parent_form );

	foreach ( $original_children as $child ) {
		$original_child_key = $child['key'];
		unset( $child['key'] );
		$child['post_title']                 = $parent_form['post_title'];
		$child['parent_id']                  = $parent_form['key'];
		$child                               = tve_leads_save_form_variation( $child );
		$form_key_map[ $original_child_key ] = $child['key'];

		$to_replace [] = $child;
	}

	$event_config_pattern = '#__TCB_EVENT_\[\{(.+?):(&quot;)?____old_ID____(&quot;)?(.*?)\}\]_TNEVE_BCT__#ms';
	$event_replacement    = '__TCB_EVENT_[{$1:${2}____new_ID____${3}$4}]_TNEVE_BCT__';

	/**
	 * another pass through all forms and replace the old state ID with the new one in the event manager configuration
	 */
	foreach ( $to_replace as $form ) {
		foreach ( $form_key_map as $original_key => $new_key ) {
			$pattern         = str_replace( '____old_ID____', $original_key, $event_config_pattern );
			$replacement     = str_replace( '____new_ID____', $new_key, $event_replacement );
			$form['content'] = preg_replace( $pattern, $replacement, $form['content'], - 1, $count );
			if ( $count ) {
				tve_leads_save_form_variation( $form );
			}
		}
	}
}

/**
 * Get active test from a form group
 * $param $ID - id of the group
 *
 */

function tve_leads_get_form_active_test( $ID, $filter = array() ) {
	$defaults = array(
		'test_type'     => TVE_LEADS_VARIATION_TEST_TYPE,
		'status'        => 'running',
		'main_group_id' => $ID,
		'get_items'     => true
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb;
	$test = $tvedb->tve_leads_get_test( $filter );

	//set variation keys to $test->item_ids
	if ( $test && ! empty( $filter['get_items'] ) ) {
		$test_items = tve_leads_get_test_items( array(
			'test_id'      => $test->id,
			'form_type_id' => $ID,
		) );
		foreach ( $test_items as $item ) {
			$test->item_ids[] = $item->variation_key;
		}
	}

	return $test;
}

function tve_leads_get_group_active_tests( $group_id ) {

	$filter = array(
		'test_type'     => TVE_LEADS_GROUP_TEST_TYPE,
		'status'        => TVE_LEADS_STATUS_RUNNING,
		'main_group_id' => $group_id
	);

	global $tvedb;

	$active_tests = $tvedb->tve_leads_get_tests( $filter );

	//set form type ids to each test
	foreach ( $active_tests as $test ) {
		$test_items = tve_leads_get_test_items( array(
			'test_id' => $test->id
		) );
		foreach ( $test_items as $item ) {
			$test->item_ids[] = $item->form_type_id;
		}
	}

	return $active_tests;
}

/**
 * Return active test for a shortcode id
 *
 * @param       $main_group_id
 * @param array $filters
 *
 * @return StdClass test
 */
function tve_leads_get_shortcode_active_test( $main_group_id, $filters = array() ) {
	$defaults = array(
		'test_type'     => TVE_LEADS_SHORTCODE_TEST_TYPE,
		'main_group_id' => $main_group_id,
		'status'        => TVE_LEADS_STATUS_RUNNING
	);
	$filters  = array_merge( $defaults, $filters );

	global $tvedb;

	$test = $tvedb->tve_leads_get_test( $filters );

	return $test;
}

/**
 * Just return 1 running test by main_group_id
 *
 * @param       $main_group_id
 * @param array $filters
 *
 * @return mixed
 */
function tve_leads_get_running_tests( $main_group_id, $filters = array() ) {
	$defaults = array(
		'main_group_id' => $main_group_id,
		'status'        => TVE_LEADS_STATUS_RUNNING
	);
	$filters  = array_merge( $defaults, $filters );

	global $tvedb;

	$test = $tvedb->tve_leads_get_test( $filters );

	return $test;
}

function tve_leads_get_group_completed_tests( $group_id ) {
	$filter = array(
		'test_type'     => TVE_LEADS_GROUP_TEST_TYPE,
		'status'        => TVE_LEADS_STATUS_ARCHIVED,
		'main_group_id' => $group_id
	);

	global $tvedb;

	$completed_tests = $tvedb->tve_leads_get_tests( $filter );

	//set form type ids to each test
	foreach ( $completed_tests as $test ) {
		$test_items = tve_leads_get_test_items( array(
			'test_id' => $test->id
		) );
		foreach ( $test_items as $item ) {
			$test->item_ids[] = $item->form_type_id;
		}
	}

	return $completed_tests;
}

/**
 * Return data for the conversion report chart and table
 *
 * @param $filter
 *
 * @return array
 */
function tve_leads_get_conversion_report_data( $filter ) {
	$defaults = array(
		'group_by'     => array( 'main_group_id', 'date_interval' ),
		'data_group'   => 'main_group_id',
		'event_type'   => TVE_LEADS_CONVERSION,
		'unique_email' => 0
	);

	$filter = array_merge( $defaults, $filter );

	global $tvedb, $tve_leads_chart_colors;

	//get raw report data
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );
	$lead_groups = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => array(
			TVE_LEADS_POST_GROUP_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX
		)
	) );

	//store group name and id
	$group_names = array();
	$colors      = array();
	$count       = count( $tve_leads_chart_colors );
	foreach ( $lead_groups as $i => $group ) {
		$group_names[ $group->ID ] = $group->post_title;
		$colors[ $group->ID ]      = $tve_leads_chart_colors[ $i % $count ];
	}

	//generate interval to fill empty dates.
	$dates = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );

	$chart_data_temp = array();
	foreach ( $report_data as $interval ) {
		//Group all report data by main_group_id
		if ( ! isset( $chart_data_temp[ $interval->data_group ] ) ) {
			$chart_data_temp[ $interval->data_group ]['id']   = intval( $interval->data_group );
			$chart_data_temp[ $interval->data_group ]['name'] = $group_names[ intval( $interval->data_group ) ];
			$chart_data_temp[ $interval->data_group ]['data'] = array();
			if ( $filter['unique_email'] == 1 ) {
				$chart_data_temp[ $interval->data_group ]['new_leads'] = array();
			}
		}

		if ( $filter['interval'] == 'day' ) {
			$interval->date_interval = date( "d M, Y", strtotime( $interval->date_interval ) );
		}

		$chart_data_temp[ $interval->data_group ]['data'][ $interval->date_interval ] = intval( $interval->log_count );

		if ( $filter['unique_email'] == 1 ) {
			$chart_data_temp[ $interval->data_group ]['new_leads'][ $interval->date_interval ] = intval( $interval->leads );
		}
	}

	$chart_data = array();
	foreach ( $group_names as $key => $name ) {
		//when user selects only one group, we don't display the other ones.
		if ( $filter['main_group_id'] > 0 && $filter['main_group_id'] != $key ) {
			continue;
		}
		if ( ! isset( $chart_data[ $key ] ) ) {
			$chart_data[ $key ]['id']    = $key;
			$chart_data[ $key ]['name']  = $name;
			$chart_data[ $key ]['color'] = $colors[ $key ];
			$chart_data[ $key ]['data']  = array();

			if ( $filter['unique_email'] == 1 ) {
				$chart_data[ $key ]['new_leads'] = array();
			}
		}
		foreach ( $dates as $date ) {
			//complete missing data with zero
			$chart_data[ $key ]['data'][] = isset( $chart_data_temp[ $key ]['data'][ $date ] ) ? $chart_data_temp[ $key ]['data'][ $date ] : 0;

			if ( $filter['unique_email'] == 1 ) {
				$chart_data[ $key ]['new_leads'][] = isset( $chart_data_temp[ $key ]['new_leads'][ $date ] ) ? $chart_data_temp[ $key ]['new_leads'][ $date ] : 0;
			}
		}
	}

	$filter['select_fields'] = array(
		'user',
		'date',
		'main_group_id',
		'form_type_id',
		'variation_key'
	);
	$count_table_data        = $tvedb->tve_leads_get_log_data_info( $filter, true );

	return array(
		'chart_title'  => __( 'Number of lead generation conversions over time', 'thrive-leads' ),
		'chart_data'   => $chart_data,
		'chart_x_axis' => $dates,
		'chart_y_axis' => __( 'Conversions', 'thrive-leads' ),
		'table_data'   => array( 'count_table_data' => $count_table_data )
	);
}

/**
 * Get data for Growth Chart with the option to select cumulative or not.
 * We already have those function implemented for the conversion report so what we'll is just get
 * the data from them and add the data from all the groups
 *
 * @param array $filter
 * @param boolean $cumulative
 *
 * @return array $data
 */
function tve_leads_get_list_growth( $filter, $cumulative = false ) {
	//we select the data from all groups
	$filter['main_group_id'] = - 1;
	$filter['unique_email']  = 1;

	if ( $cumulative === true ) {
		$data = tve_leads_get_cumulative_conversion_report_data( $filter );
	} else {
		$data = tve_leads_get_conversion_report_data( $filter );
	}

	global $tve_leads_chart_colors;

	$conversions_data = array(
		'id'    => $cumulative ? 1 : 2, //this is more or less useless
		'name'  => $cumulative ? __( 'Total conversions since start date', 'thrive-leads' ) : __( 'Conversions Growth', 'thrive-leads' ),
		'color' => $tve_leads_chart_colors[0], //we just use the first color
		'data'  => array_fill( 0, count( $data['chart_x_axis'] ), 0 ) //fill array with 0
	);

	$lead_data = array(
		'id'    => $cumulative ? 3 : 4, //just to differentiate between the data series for the chart
		'name'  => $cumulative ? __( 'Total leads since start date', 'thrive-leads' ) : __( 'Lead Growth', 'thrive-leads' ),
		'color' => $tve_leads_chart_colors[1], //we use the second color
		'data'  => array_fill( 0, count( $data['chart_x_axis'] ), 0 ) //fill array with 0
	);

	foreach ( $data['chart_data'] as $group ) {
		foreach ( $group['data'] as $key => $growth ) {
			$conversions_data['data'][ $key ] += $growth;
			$lead_data['data'][ $key ] += $group['new_leads'][ $key ];
		}
	}

	$data['chart_data'] = array( $conversions_data, $lead_data );

	if ( $filter['load_annotations'] ) {
		$flag_data            = tve_leads_get_chart_annotations( $filter, $conversions_data['data'] );
		$data['chart_data'][] = $flag_data;
	}

	$title                = __( 'Total number of opt-ins across all forms and lead groups', 'thrive-leads' ) . ( $cumulative ? __( '(cumulative)', 'thrive-leads' ) : '' );
	$data['chart_title']  = __( $title, 'thrive-leads' );
	$data['chart_y_axis'] = __( 'Leads', 'thrive-leads' );

	return $data;
}

/**
 * Return table data for the conversion report table
 *
 * @param $filter
 *
 * @return Requested
 */
function tve_leads_get_conversion_report_table_data( $filter ) {
	global $tvedb;
	global $tve_leads_chart_colors;
	$defaults = array(
		'itemsPerPage'  => 10,
		'page'          => 1,
		'select_fields' => array( 'user', 'date', 'main_group_id', 'form_type_id', 'variation_key', 'referrer' ),
		'event_type'    => TVE_LEADS_CONVERSION
	);

	$lead_groups = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => array(
			TVE_LEADS_POST_GROUP_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX
		)
	) );

	//we store the colors that were used in the chart so we can match the group id
	$colors      = array();
	$color_count = count( $tve_leads_chart_colors );
	foreach ( $lead_groups as $key => $group ) {
		$colors[ $group->ID ] = $tve_leads_chart_colors[ $key % $color_count ];
	}

	$filter = array_merge( $defaults, $filter );

	$table_data = $tvedb->tve_leads_get_log_data_info( $filter );

	$group_titles = array();
	$form_titles  = array();
	$preview_urls = array();

	foreach ( $table_data as $key => $value ) {

		if ( ! isset( $group_titles[ $value->main_group_id ] ) ) {
			$group_titles[ $value->main_group_id ] = get_the_title( $value->main_group_id );
		}
		if ( ! isset( $form_titles[ $value->form_type_id ] ) ) {
			$form_titles[ $value->form_type_id ] = get_the_title( $value->form_type_id );
		}
		if ( ! isset( $preview_urls[ $value->variation_key ] ) ) {
			$preview_urls[ $value->variation_key ] = tve_leads_get_preview_url( $value->form_type_id, $value->variation_key );
		}

		$table_data[ $key ]->date       = date( "d M, Y", strtotime( $value->date ) );
		$table_data[ $key ]->link       = $preview_urls[ $value->variation_key ];
		$table_data[ $key ]->lead_group = $group_titles[ $value->main_group_id ];
		$table_data[ $key ]->form_type  = $form_titles[ $value->form_type_id ];
		$table_data[ $key ]->color      = $colors[ intval( $value->main_group_id ) ];

		//we unset those values so we don't send too much data.
		unset( $table_data[ $key ]->form_type_id );
		unset( $table_data[ $key ]->main_group_id );
		unset( $table_data[ $key ]->variation_key );
	}

	return $table_data;
}


/**
 * Return data for the cumulative report chart and table
 *
 * @param $filter
 *
 * @return array
 */
function tve_leads_get_cumulative_conversion_report_data( $filter ) {
	$defaults = array(
		'group_by'     => array( 'main_group_id', 'date_interval' ),
		'data_group'   => 'main_group_id',
		'event_type'   => TVE_LEADS_CONVERSION,
		'unique_email' => 0
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb, $tve_leads_chart_colors;
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );

	$lead_groups = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => array(
			TVE_LEADS_POST_GROUP_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX
		)
	) );
	//store group names and id
	$group_names = $colors = array();
	$count       = count( $tve_leads_chart_colors );
	foreach ( $lead_groups as $i => $group ) {
		$group_names[ $group->ID ] = $group->post_title;
		$colors[ $group->ID ]      = $tve_leads_chart_colors[ $i % $count ];
	}

	//generate interval to fill empty dates.
	$dates = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );

	$chart_data_temp = array();
	foreach ( $report_data as $interval ) {
		//Group all report data by main_group_id
		if ( ! isset( $chart_data_temp[ $interval->data_group ] ) ) {
			$chart_data_temp[ $interval->data_group ]['id']   = intval( $interval->data_group );
			$chart_data_temp[ $interval->data_group ]['name'] = $group_names[ intval( $interval->data_group ) ];
			$chart_data_temp[ $interval->data_group ]['data'] = array();
			if ( $filter['unique_email'] == 1 ) {
				$chart_data_temp[ $interval->data_group ]['new_leads'] = array();
			}
		}

		if ( $filter['interval'] == 'day' ) {
			$interval->date_interval = date( "d M, Y", strtotime( $interval->date_interval ) );
		}

		$chart_data_temp[ $interval->data_group ]['data'][ $interval->date_interval ] = $interval->log_count;
		if ( $filter['unique_email'] == 1 ) {
			$chart_data_temp[ $interval->data_group ]['new_leads'][ $interval->date_interval ] = intval( $interval->leads );
		}
	}

	$chart_data = array();
	foreach ( $group_names as $key => $name ) {
		//when user selects only one group, we don't display the other ones.
		if ( $filter['main_group_id'] > 0 && $filter['main_group_id'] != $key ) {
			continue;
		}
		if ( ! isset( $chart_data[ $key ] ) ) {
			$chart_data[ $key ]['id']    = $key;
			$chart_data[ $key ]['name']  = $name;
			$chart_data[ $key ]['color'] = $colors[ $key ];
			$chart_data[ $key ]['data']  = array();
			$last_val                    = 0;

			if ( $filter['unique_email'] == 1 ) {
				$last_lead_val                   = 0;
				$chart_data[ $key ]['new_leads'] = array();
			}
		}
		foreach ( $dates as $date ) {
			//complete missing data with zero
			$last_val += isset( $chart_data_temp[ $key ]['data'][ $date ] ) ? $chart_data_temp[ $key ]['data'][ $date ] : 0;
			$chart_data[ $key ]['data'][] = $last_val;

			if ( $filter['unique_email'] == 1 ) {
				$last_lead_val += isset( $chart_data_temp[ $key ]['new_leads'][ $date ] ) ? $chart_data_temp[ $key ]['new_leads'][ $date ] : 0;
				$chart_data[ $key ]['new_leads'][] = $last_lead_val;
			}
		}
	}
	$filter['select_fields'] = array(
		'user',
		'date',
		'main_group_id',
		'form_type_id',
		'variation_key'
	);
	$count_table_data        = $tvedb->tve_leads_get_log_data_info( $filter, true );

	return array(
		'chart_title'  => __( 'Cumulative lead generation conversions over time', 'thrive-leads' ),
		'chart_data'   => $chart_data,
		'chart_x_axis' => $dates,
		'chart_y_axis' => __( 'Signups', 'thrive-leads' ),
		'table_data'   => array( 'count_table_data' => $count_table_data )
	);
}

/**
 * Return data for the test chart
 *
 * @param $filter
 *
 * @return array
 */
function tve_leads_get_conversion_rate_test_data( $filter ) {
	$defaults = array(
		'group_by'   => array( 'main_group_id', 'date_interval', 'event_type' ),
		'data_group' => 'main_group_id'
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb;
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );

	//set names for the series in the chart - variation, form type or group
	if ( empty( $filter['group_names'] ) ) {
		$lead_groups = get_posts( array(
			'posts_per_page' => - 1,
			'post_type'      => array(
				TVE_LEADS_POST_GROUP_TYPE,
				TVE_LEADS_POST_SHORTCODE_TYPE,
				TVE_LEADS_POST_TWO_STEP_LIGHTBOX
			)
		) );
		$group_names = array();
		foreach ( $lead_groups as $group ) {
			$group_names[ $group->ID ] = $group->post_title;
		}
	} else {
		$group_names = $filter['group_names'];
	}

	//generate interval to fill empty dates.
	$dates = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );

	$chart_data_temp = array();
	foreach ( $report_data as $interval ) {
		//Group all report data by main_group_id
		if ( ! isset( $chart_data_temp[ $interval->data_group ] ) ) {
			$chart_data_temp[ $interval->data_group ]['id']                          = intval( $interval->data_group );
			$chart_data_temp[ $interval->data_group ]['name']                        = $group_names[ intval( $interval->data_group ) ];
			$chart_data_temp[ $interval->data_group ][ TVE_LEADS_CONVERSION ]        = array();
			$chart_data_temp[ $interval->data_group ][ TVE_LEADS_UNIQUE_IMPRESSION ] = array();
			$chart_data_temp[ $interval->data_group ]['data']                        = array();
		}

		//store the date interval so we can add it as X Axis in the chart
		if ( $filter['interval'] == 'day' ) {
			$interval->date_interval = date( "d M, Y", strtotime( $interval->date_interval ) );
		}

		$chart_data_temp[ $interval->data_group ][ intval( $interval->event_type ) ][ $interval->date_interval ] = intval( $interval->log_count );
	}

	$chart_data = array();
	foreach ( $group_names as $key => $name ) {
		//when user selects only one group, we don't display the other ones.
		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 && $filter['main_group_id'] != $key ) {
			continue;
		}
		if ( ! isset( $chart_data[ $key ] ) ) {
			$chart_data[ $key ]['id']               = $key;
			$chart_data[ $key ]['name']             = $name;
			$chart_data[ $key ]['data']             = array();
			$chart_data[ $key ]['impression_count'] = array();
			$chart_data[ $key ]['conversion_count'] = array();
		}
		//complete missing data with zero
		foreach ( $dates as $date ) {
			if ( ! isset( $chart_data_temp[ $key ][ TVE_LEADS_UNIQUE_IMPRESSION ][ $date ] )
			     ||
			     ! isset( $chart_data_temp[ $key ][ TVE_LEADS_CONVERSION ][ $date ] )
			     || $chart_data_temp[ $key ][ TVE_LEADS_UNIQUE_IMPRESSION ][ $date ] == 0
			) {
				$chart_data[ $key ]['data'][] = 0;
			} else {
				$chart_data[ $key ]['data'][] = (float) tve_leads_conversion_rate( $chart_data_temp[ $key ][ TVE_LEADS_UNIQUE_IMPRESSION ][ $date ], $chart_data_temp[ $key ][ TVE_LEADS_CONVERSION ][ $date ], '', 2 );
			}
			/**
			 * count impressions and conversions so we can use those values in the "cumulative" report shown on the test screen
			 */
			$chart_data[ $key ]['impression_count'][] = isset( $chart_data_temp[ $key ][ TVE_LEADS_UNIQUE_IMPRESSION ][ $date ] ) ? $chart_data_temp[ $key ][ TVE_LEADS_UNIQUE_IMPRESSION ][ $date ] : 0;
			$chart_data[ $key ]['conversion_count'][] = isset( $chart_data_temp[ $key ][ TVE_LEADS_CONVERSION ][ $date ] ) ? $chart_data_temp[ $key ][ TVE_LEADS_CONVERSION ][ $date ] : 0;
		}
	}

	$conversions = 0;
	$impressions = 0;
	foreach ( $chart_data_temp as $key ) {
		$conversions += array_sum( $key[ TVE_LEADS_CONVERSION ] );
		$impressions += array_sum( $key[ TVE_LEADS_UNIQUE_IMPRESSION ] );
	}
	$average_rate = (float) tve_leads_conversion_rate( $impressions, $conversions, '', 2 );

	return array(
		'chart_title'  => __( 'Lead generation conversion rate over time', 'thrive-leads' ),
		'chart_data'   => $chart_data,
		'chart_x_axis' => $dates,
		'chart_y_axis' => __( 'Conversion Rate', 'thrive-leads' ) . ' (%)',
		'table_data'   => array(
			'count_table_data' => count( $dates ),
			'average_rate'     => $average_rate
		)
	);
}

/**
 * Return conversion rate chart data for reporting view.
 * Conversion Rate is calculated related to days so unique user impressions are used instead of forms unique impressions
 *
 * @param $filter
 *
 * @return array
 */
function tve_leads_get_conversion_rate_report_chart( $filter ) {
	$defaults = array(
		'group_by'      => array( 'date_interval', 'event_type' ),
		'data_group'    => 'main_group_id',
		'is_unique'     => 1,
		'main_group_id' => - 1
	);

	$filter                  = array_merge( $defaults, $filter );
	$filter['main_group_id'] = - 1;

	global $tvedb;
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );

	$temp_data = array();
	$dates     = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );
	foreach ( $dates as $date ) {
		$temp_data[ $date ]['date']                        = $date;
		$temp_data[ $date ][ TVE_LEADS_CONVERSION ]        = 0;
		$temp_data[ $date ][ TVE_LEADS_UNIQUE_IMPRESSION ] = 0;

	}

	$count[ TVE_LEADS_CONVERSION ]        = 0;
	$count[ TVE_LEADS_UNIQUE_IMPRESSION ] = 0;
	foreach ( $report_data as $interval ) {
		if ( $filter['interval'] == 'day' ) {
			$interval->date_interval = date( 'd M, Y', strtotime( $interval->date_interval ) );
		}
		if ( intval( $interval->event_type ) !== TVE_LEADS_CONVERSION ) {
			$temp_data[ $interval->date_interval ][ TVE_LEADS_UNIQUE_IMPRESSION ] += intval( $interval->log_count );
		} else {
			$temp_data[ $interval->date_interval ][ TVE_LEADS_CONVERSION ] += intval( $interval->log_count );
		}
		$count[ $interval->event_type ] += $interval->log_count;
	}

	$chart_data         = array();
	$chart_data['id']   = - 1;
	$chart_data['data'] = array();
	$chart_data['name'] = __( 'Conversion Rate', 'thrive-leads' );
	foreach ( $temp_data as $interval ) {
		if ( ! isset( $interval[ TVE_LEADS_UNIQUE_IMPRESSION ] ) || ! isset( $interval[ TVE_LEADS_CONVERSION ] ) || $interval[ TVE_LEADS_UNIQUE_IMPRESSION ] == 0 ) {
			$chart_data['data'][] = 0;
		} else {
			$chart_data['data'][] = (float) tve_leads_conversion_rate( $interval[ TVE_LEADS_UNIQUE_IMPRESSION ], $interval[ TVE_LEADS_CONVERSION ], '', 2 );
		}
	}

	$average_rate = (float) tve_leads_conversion_rate( $count[ TVE_LEADS_UNIQUE_IMPRESSION ], $count[ TVE_LEADS_CONVERSION ], '', 2 );

	return array(
		'chart_title'  => __( 'Lead generation conversion rate over time', 'thrive-leads' ),
		'chart_data'   => array( $chart_data ),
		'chart_x_axis' => $dates,
		'chart_y_axis' => __( 'Conversion Rate', 'thrive-leads' ) . ' (%)',
		'table_data'   => array(
			'count_table_data' => count( $dates ),
			'average_rate'     => $average_rate
		)
	);

}


/**
 * Return table data for the conversion rate report
 *
 * @param $filter
 *
 * @return mixed
 */
function tve_leads_get_conversion_rate_report_table_data( $filter ) {
	$defaults = array(
		'group_by'     => array( 'date_interval', 'event_type' ),
		'data_group'   => 'main_group_id',
		'itemsPerPage' => 10,
		'page'         => 1,
		'is_unique'    => 1
	);

	$filter = array_merge( $defaults, $filter );

	global $tvedb;
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );

	$temp_data = array();
	$dates     = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );
	foreach ( $dates as $date ) {
		$temp_data[ $date ]['date']                        = $date;
		$temp_data[ $date ][ TVE_LEADS_CONVERSION ]        = 0;
		$temp_data[ $date ][ TVE_LEADS_UNIQUE_IMPRESSION ] = 0;

	}

	foreach ( $report_data as $interval ) {
		if ( $filter['interval'] == 'day' ) {
			$interval->date_interval = date( 'd M, Y', strtotime( $interval->date_interval ) );
		}
		if ( intval( $interval->event_type ) !== TVE_LEADS_CONVERSION ) {
			$temp_data[ $interval->date_interval ][ TVE_LEADS_UNIQUE_IMPRESSION ] += intval( $interval->log_count );
		} else {
			$temp_data[ $interval->date_interval ][ TVE_LEADS_CONVERSION ] += intval( $interval->log_count );
		}
	}

	$table_data = array();
	foreach ( $temp_data as $interval ) {
		if ( ! isset( $interval[ TVE_LEADS_UNIQUE_IMPRESSION ] ) || ! isset( $interval[ TVE_LEADS_CONVERSION ] ) || $interval[ TVE_LEADS_UNIQUE_IMPRESSION ] == 0 ) {
			$rate = 0;
		} else {

			$rate = (float) tve_leads_conversion_rate( $interval[ TVE_LEADS_UNIQUE_IMPRESSION ], $interval[ TVE_LEADS_CONVERSION ], '', 2 );
		}

		$table_data[] = array(
			'date' => $interval['date'],
			'rate' => $rate
		);
	}
	if ( ! empty( $table_data ) ) {
		$table_pages = array_chunk( $table_data, $filter['itemsPerPage'] );

		return array_reverse( $table_pages[ $filter['page'] - 1 ] );
	} else {
		return array();
	}
}

/**
 * Return data for the comparison report pie data and table
 *
 * @param $filter
 *
 * @return array
 */
function tve_leads_get_comparison_report_data( $filter ) {
	$filter['main_group_id'] = - 1;
	$defaults                = array(
		'group_by'   => array( 'main_group_id' ),
		'data_group' => 'main_group_id',
		'event_type' => TVE_LEADS_CONVERSION,
	);
	$filter                  = array_merge( $defaults, $filter );

	global $tvedb;
	$report_data = $tvedb->tve_leads_get_report_data_count_event_type( $filter );

	$lead_groups = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => array(
			TVE_LEADS_POST_GROUP_TYPE,
			TVE_LEADS_POST_SHORTCODE_TYPE,
			TVE_LEADS_POST_TWO_STEP_LIGHTBOX
		)
	) );
	//store group name and id
	$group_names = array();
	foreach ( $lead_groups as $group ) {
		$group_names[ $group->ID ] = $group->post_title;
	}

	$chart_data  = array();
	$table_data  = array();
	$conversions = 0;
	foreach ( $report_data as $interval ) {
		$chart_data[] = array( $group_names[ intval( $interval->data_group ) ], intval( $interval->log_count ) );
		$conversions += intval( $interval->log_count );
	}

	foreach ( $report_data as $interval ) {
		$table_data[] = array(
			'lead_group' => $group_names[ intval( $interval->data_group ) ],
			'percentage' => round( 100 * intval( $interval->log_count ) / $conversions, 1 ) . '%'
		);
		unset( $group_names[ intval( $interval->data_group ) ] );
	}
	//fill the table data with the empty groups
	foreach ( $group_names as $names ) {
		$table_data[] = array(
			'lead_group' => $names,
			'percentage' => '0%'
		);
	}

	return array(
		'chart_title'  => __( 'How the total number of opt ins is distributed between Lead Groups and individual forms', 'thrive-leads' ),
		'chart_data'   => $chart_data,
		'chart_y_axis' => '',
		'chart_x_axis' => '',
		'table_data'   => $table_data
	);
}

/**
 * Return data for the Lead Referral Report table
 *
 * @param $filter Array containing parameters for filtering the data logs
 *
 * @return array
 */
function tve_leads_get_lead_referral_report_data( $filter ) {
	$defaults = array(
		'count'         => true,
		'itemsPerPage'  => 10,
		'page'          => 1,
		'event_type'    => TVE_LEADS_CONVERSION,
		'referral_type' => 'domain'
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb;
	$lead_referral = $tvedb->tve_leads_get_top_referring_links( $filter, $filter['count'] );

	if ( $filter['referral_type'] == 'domain' && $filter['count'] == false ) {
		$temp = array();
		foreach ( $lead_referral as $referrer ) {
			$domain = parse_url( $referrer->referring_url, PHP_URL_HOST );
			$domain = str_replace( 'www.', '', $domain );
			if ( empty( $temp[ $domain ] ) ) {
				$temp[ $domain ]                = new stdClass();
				$temp[ $domain ]->conversions   = 0;
				$temp[ $domain ]->referring_url = $domain;
			}
			$temp[ $domain ]->conversions += $referrer->conversions;
		}
		$lead_referral = array_values( $temp );
	}

	/* sort the result */
	if ( $filter['count'] == false && ! empty( $filter['order_dir'] ) ) {
		for ( $i = 0; $i < count( $lead_referral ) - 1; $i ++ ) {
			for ( $j = $i + 1; $j < count( $lead_referral ); $j ++ ) {
				if ( ( $filter['order_by'] == 'url' && strcmp( $lead_referral[ $i ]->referring_url, $lead_referral[ $j ]->referring_url ) > 0 )
				     || ( $filter['order_by'] == 'conversions' && ( $lead_referral[ $i ]->conversions - $lead_referral[ $j ]->conversions > 0 ) )
				) {
					$aux                 = $lead_referral[ $i ];
					$lead_referral[ $i ] = $lead_referral[ $j ];
					$lead_referral[ $j ] = $aux;
				}
			}
		}
		if ( $filter['order_dir'] == 'DESC' ) {
			$lead_referral = array_reverse( $lead_referral );
		}
	}

	if ( $filter['count'] == true ) {
		return array( 'table_data' => array( 'count_table_data' => $lead_referral ) );
	} else {
		return $lead_referral;
	}
}

/**
 * Return data for the Lead Referral Report table
 *
 * @param $filter Array containing parameters for filtering the data logs
 *
 * @return array
 */
function tve_leads_get_lead_source_report_data( $filter ) {
	$defaults = array(
		'count'        => true,
		'itemsPerPage' => 500,
		'page'         => 1,
		'source_type'  => 0,
		'order_by'     => '',
		'order_dir'    => ''
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb;
	$lead_report = $tvedb->tve_leads_get_lead_source_data( $filter, $filter['count'] );

	$result = array();
	foreach ( $lead_report as $row ) {
		list( $url, $type, $name ) = tve_get_current_screen_for_reporting_table( $row->screen_type, $row->screen_id );
		$result[] = array(
			'url'             => $url,
			'type'            => $type,
			'name'            => $name,
			'conversions'     => $row->conversions,
			'impressions'     => $row->impressions,
			'leads'           => $row->leads,
			'conversion_rate' => tve_leads_conversion_rate( $row->impressions, $row->conversions ),
		);
	}

	if ( $filter['count'] == true ) {
		return array( 'table_data' => array( 'count_table_data' => count( $result ) ) );
	} else {
		return $result;
	}
}

/**
 * Return data for the Lead Referral Report table
 *
 * @param $filter Array containing parameters for filtering the data logs
 *
 * @return array
 */
function tve_leads_get_lead_tracking_report_data( $filter ) {
	$defaults = array(
		'count'         => true,
		'itemsPerPage'  => 10,
		'page'          => 1,
		'tracking_type' => 'all',
		'event_type'    => TVE_LEADS_CONVERSION
	);
	$filter   = array_merge( $defaults, $filter );

	global $tvedb;
	$lead_tracking = $tvedb->tve_leads_get_tracking_links( $filter, $filter['count'] );
	if ( $filter['count'] == true ) {
		return array( 'table_data' => array( 'count_table_data' => $lead_tracking ) );
	} else {
		return $lead_tracking;
	}


}

/**
 * @param $test_id  ID of the test we want to get data from
 * @param $interval can be 'day', 'week', or 'month' - the date interval of the chart
 *
 * @return array
 */
function tve_leads_get_test_chart_data( $test_id, $interval ) {
	$filter = array(
		'ID' => $test_id
	);
	global $tvedb;
	$test = $tvedb->tve_leads_get_test( $filter );
	list( $test_items, $test_item_ids ) = tve_leads_get_test_items_with_names( $test_id );

	//set the group by for the log data depending on the test type
	switch ( $test->test_type ) {
		case TVE_LEADS_SHORTCODE_TEST_TYPE:
		case TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE:
		case TVE_LEADS_VARIATION_TEST_TYPE:
			$group_by   = array( 'variation_key', 'date_interval', 'event_type' );
			$data_group = 'variation_key';
			break;
		case TVE_LEADS_GROUP_TEST_TYPE:
			$group_by   = array( 'form_type_id', 'date_interval', 'event_type' );
			$data_group = 'form_type_id';
			break;

	}

	$filter = array(
		'interval'    => $interval,
		'group_names' => $test_items,
		'data_group'  => $data_group,
		'group_ids'   => array_keys( $test_items ),
		'start_date'  => $test->date_started,
		'end_date'    => $test->date_completed && $test->status == 'archived' ? $test->date_completed : date( "Y-m-d H:i:s" ),
		'group_by'    => $group_by
	);

	$chart_data = tve_leads_get_conversion_rate_test_data( $filter );

	foreach ( $chart_data['chart_data'] as $main_id => $item ) {
		foreach ( $item['data'] as $index => $conversion_rate ) {
			// calculate the new conversion rate as a sum of the total numbers from the beginning of the test until at this point
			$impressions = $conversions = 0;
			for ( $i = 0; $i <= $index; $i ++ ) {
				$impressions += $item['impression_count'][ $i ];
				$conversions += $item['conversion_count'][ $i ];
			}
			$chart_data['chart_data'][ $main_id ]['data'][ $index ] = (double) tve_leads_conversion_rate( $impressions, $conversions, '' );
			unset( $chart_data['chart_data'][ $main_id ]['impression_count'], $chart_data['chart_data'][ $main_id ]['conversion_count'] );
		}
	}

	$temp = array();
	asort( $test_item_ids );
	foreach ( $test_item_ids as $main_id => $order ) {
		if ( ! isset( $chart_data['chart_data'][ $main_id ] ) ) {
			continue;
		}
		$temp [] = $chart_data['chart_data'][ $main_id ];
	}
	$chart_data['chart_data'] = $temp;

	unset( $chart_data['table_data'] );

	return $chart_data;
}

function tve_leads_get_test( $test_id, $filters = array() ) {
	$defaults = array(
		'load_test_items' => false,
		'load_form_data'  => false,
	);

	$filters = array_merge( $defaults, $filters );

	global $tvedb;
	$test             = $tvedb->tve_leads_get_test( array( 'ID' => $test_id ) );
	$test->form_title = get_the_title( $test->main_group_id );
	if ( ! $test ) {
		return;
	}

	//load items
	if ( ! empty( $filters['load_test_items'] ) ) {
		$test->items         = tve_leads_get_test_items( array(
			'test_id' => $test_id
		) );
		$test->stopped_items = array();
		//load item names
		foreach ( $test->items as $index => $item ) {
			$item->index = $index;
			//we need the conversion rate in both cases
			$item->conversion_rate = tve_leads_conversion_rate( $item->unique_impressions, $item->conversions, '' );

			if ( ! empty( $filters['load_form_data'] ) ) {
				if ( $test->test_type == TVE_LEADS_VARIATION_TEST_TYPE || $test->test_type == TVE_LEADS_SHORTCODE_TEST_TYPE || $test->test_type == TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE ) {
					$variation            = tve_leads_get_form_variation( $item->form_type_id, $item->variation_key );
					$item->name           = $variation['post_title'];
					$item->trigger_name   = tve_leads_trigger_nice_name( $variation );
					$item->animation_name = tve_leads_animation_nice_name( $variation );
					$item->preview_url    = tve_leads_get_preview_url( $item->form_type_id, $item->variation_key );
					$item->edit_url       = tve_leads_get_editor_url( $item->form_type_id, $item->variation_key );
				} else {
					$variation_control    = tve_leads_get_form_variation( $item->form_type_id, $item->variation_key );
					$form_type            = tve_leads_get_form_type( $item->form_type_id );
					$item->name           = $variation_control['post_title'];
					$item->trigger_name   = tve_leads_trigger_nice_name( $variation_control );
					$item->animation_name = tve_leads_animation_nice_name( $variation_control );
					$item->preview_url    = tve_leads_get_preview_url( $item->form_type_id, $variation_control['key'] );
					$item->form_type      = $form_type->post_title;
				}
			}

			$item->conversion_rate = tve_leads_conversion_rate( $item->unique_impressions, $item->conversions, '' );
			//we don't calculate for the control item
			if ( $index > 0 ) {
				//Percentage improvement = conversion rate of variation - conversion rate of control
				if ( is_numeric( $test->items[0]->conversion_rate ) ) {
					$item->percentage_improvement = round( ( ( $item->conversion_rate - $test->items[0]->conversion_rate ) * 100 ) / $test->items[0]->conversion_rate, 2 );
				} else {
					$item->percentage_improvement = 'N/A';
				}

				$item->beat_original = tve_leads_test_item_beat_original( $item->conversion_rate, $item->unique_impressions, $test->items[0]->conversion_rate, $test->items[0]->unique_impressions );
			}

			if ( $item->active == 0 && $item->is_control == 0 ) {
				$item->stopped_date    = date( 'd-m-Y', strtotime( $item->stopped_date ) );
				$test->stopped_items[] = $item;
				unset( $test->items[ $index ] );
			}
		}
	}

	/*Reset the test items IDS*/
	$test->items = array_values( $test->items );

	return $test;
}

/**
 * Get an array with all the items of a test having the ID as a key and the name as value
 *
 * @param $test_id
 *
 * @return array
 */
function tve_leads_get_test_items_with_names( $test_id ) {
	global $tvedb;
	$test       = $tvedb->tve_leads_get_test( array( 'ID' => $test_id ) );
	$test_items = $tvedb->get_test_items( array( 'test_id' => $test_id ) );

	$test_item_names = array();
	$test_item_ids   = array();
	switch ( $test->test_type ) {
		case TVE_LEADS_VARIATION_TEST_TYPE:
		case TVE_LEADS_SHORTCODE_TEST_TYPE:
		case TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE:
			$test_items_id = array();
			foreach ( $test_items as $item ) {
				$test_items_id[]                       = $item->variation_key;
				$test_item_ids[ $item->variation_key ] = $item->is_control ? - 1 : $item->id;
			}
			$variations = tve_leads_get_form_variations( $test->main_group_id, array(
				'tracking_data' => false,
				'post_status'   => null
			) );
			foreach ( $variations as $variation ) {
				if ( in_array( $variation['key'], $test_items_id ) ) {
					$test_item_names[ $variation['key'] ] = $variation['post_title'];
				}
			}

			return array( $test_item_names, $test_item_ids );
			break;
		case TVE_LEADS_GROUP_TEST_TYPE:
			$test_items_id = array();
			foreach ( $test_items as $item ) {
				$test_items_id[]                      = $item->form_type_id;
				$test_item_ids[ $item->form_type_id ] = $item->is_control ? - 1 : $item->id;
			}

			$form_types = tve_leads_get_form_types( array(
				'lead_group_id' => $test->main_group_id,
				'tracking_data' => false
			) );
			foreach ( $form_types as $form_type ) {
				if ( ! isset( $form_type->ID ) ) {
					continue;
				}
				if ( in_array( $form_type->ID, $test_items_id ) ) {
					$test_item_names[ $form_type->ID ] = $form_type->post_title;
				}
			}

			return array( $test_item_names, $test_item_ids );
			break;
	}

	return array();
}

/**
 * General function that reads DB and returns test items by filters
 *
 * @param $filters
 *
 * @return test Items
 */
function tve_leads_get_test_items( $filters ) {
	global $tvedb;

	$defaults = array(
		'test_id'       => null,
		'main_group_id' => null,
		'form_type_id'  => null
	);

	$filters = array_merge( $defaults, $filters );

	$test_items = $tvedb->get_test_items( $filters );

	return $test_items;
}

function tve_leads_get_completed_form_test( WP_Post $form_type, $test_type = null ) {
	global $tvedb;

	$filters = array(
		'test_type'     => $test_type,
		'main_group_id' => $form_type->ID,
		'status'        => TVE_LEADS_STATUS_ARCHIVED
	);

	$tests = $tvedb->tve_leads_get_tests( $filters );

	return $tests;
}

/**
 * Stops underperforming variations
 *
 * @param $test_id
 */
function tve_leads_stop_underperforming_variations( $test_id ) {
	if ( empty( $test_id ) ) {
		return;
	}

	$test_model = tve_leads_get_test( $test_id, array( 'load_test_items' => true ) );
	if ( empty( $test_model ) || empty( $test_model->auto_win_enabled ) || $test_model->status != TVE_LEADS_TEST_STATUS_RUNNING ) {
		return;
	}

	if ( ! empty( $test_model->auto_win_min_duration ) ) {
		/* check if this amount of time has passed -> if not, no need for further processing */
		if ( time() < strtotime( $test_model->date_started . ' +' . $test_model->auto_win_min_duration . 'days' ) ) {
			return;
		} /* The time interval has passed, we can check the other conditions */
	}

	/*Minimum conversion check*/
	global $tvedb;
	$total_test_data = $tvedb->get_total_test_data( $test_id );

	if ( intval( $total_test_data->total_conversions ) < intval( $test_model->auto_win_min_conversions ) ) {
		return;
	}

	foreach ( $test_model->items as $test_item ) {
		if ( $test_item->is_control ) {
			$control = $test_item;
			break;
		}
	}

	/*Stop if there are no conversions on control*/
	if ( empty( $control ) ) {
		return;
	}

	if ( empty( $control->conversions ) ) {
		return;
	}

	$variations_beat_original = 100.0 - (float) $test_model->auto_win_chance_original;
	foreach ( $test_model->items as $test_item ) {
		if ( $test_item->is_control ) {
			continue;
		}

		if ( (float) $test_item->beat_original < $variations_beat_original ) {
			tve_leads_stop_test_item( $test_item->id, $test_id );
		}
	}

}

/**
 * check if the automatic winner settings are enabled for a test and automatically
 * detect the winner item if the conditions are met
 *
 * @param int $test_id
 */
function tve_leads_test_check_winner( $test_id ) {
	if ( empty( $test_id ) ) {
		return;
	}

	$test_model = tve_leads_get_test( $test_id, array( 'load_test_items' => true ) );
	if ( empty( $test_model ) || empty( $test_model->auto_win_enabled ) || $test_model->status != TVE_LEADS_TEST_STATUS_RUNNING ) {
		return;
	}
	if ( ! empty( $test_model->auto_win_min_duration ) ) {
		/* check if this amount of time has passed -> if not, no need for further processing */
		if ( time() < strtotime( $test_model->date_started . ' +' . $test_model->auto_win_min_duration . 'days' ) ) {
			return;
		} /* The time interval has passed, we can check the other conditions */
	}

	/*MINIMUM CONVERSION CHECK*/
	global $tvedb;
	$total_test_data = $tvedb->get_total_test_data( $test_id );
	if ( intval( $total_test_data->total_conversions ) < intval( $test_model->auto_win_min_conversions ) ) {
		return;
	}

	/* check the number of conversions of each item, and the chance to beat original */
	$test_item_win_array = array();
	foreach ( $test_model->items as $test_item ) {
//		if ( $minimum_conversions > $test_item->conversions ) {
//			continue;
//		}

		if ( $test_item->is_control ) {
			$variations_beat_original = 100.0 - (float) $test_model->auto_win_chance_original;
			$control_win              = true;

			foreach ( $test_model->items as $var ) {
				if ( $var->is_control ) {
					continue;
				}

				if ( $variations_beat_original < floatval( $var->beat_original ) || empty( $var->beat_original ) ) {
					$control_win = false;
				}
			}

			if ( $control_win ) {
				$test_item->is_winner = 1;
				tve_leads_save_test_item( (array) $test_item );
				break;
			}
		} else {
			if ( (float) $test_item->beat_original > (float) $test_model->auto_win_chance_original ) {
				$test_item_win_array[] = $test_item;
//				$test_item->is_winner = 1;
//				tve_leads_save_test_item( (array) $test_item );
//				break;
			}
		}

	}
	if ( ! empty( $test_item_win_array ) ) {
		$winner_test_item = $test_item_win_array[0];
		foreach ( $test_item_win_array as $var_win_arr ) {
			if ( $winner_test_item->auto_win_chance_original <= $var_win_arr->auto_win_chance_original ) {
				$winner_test_item = $var_win_arr;
			}
		}
		/*Set the winner to the highest beat original*/
		$winner_test_item->is_winner = 1;
		tve_leads_save_test_item( (array) $winner_test_item );
	}
}


/**
 * in case of a group level test, we need to archive all the variations that are not related to the winner form type
 * in case of a form type level test, we need to archive all other variations
 *
 * @param stdClass $winner_test_item
 *
 * @return stdClass $test_model
 */
function tve_leads_set_test_item_winner( $winner_test_item, $test_model ) {
	global $tvedb;

	$to_be_updated = array();

	foreach ( $test_model->items as $test_item ) {
		if ( $test_item->id == $winner_test_item->id ) {
			continue;
		}
		$variations = tve_leads_get_form_variations( $test_item->form_type_id, array(
			'tracking_data' => false
		) );

		/* set all variations as archived, except for the one that's either the winner, or the control of the form type winner */
		foreach ( $variations as $i => $variation ) {
			if ( $variation['key'] != $winner_test_item->variation_key ) {
				$to_be_updated [] = $variation['key'];
			}
		}
	}

	$tvedb->mass_update_field( 'form_variations', 'post_status', TVE_LEADS_STATUS_ARCHIVED, $to_be_updated, 'key' );

	/* finally, mark the test as completed */
	$test_model->status         = TVE_LEADS_STATUS_ARCHIVED;
	$test_model->date_completed = date( 'Y-m-d H:i:s' );

	$tvedb->save_test( $test_model );

}

/**
 * get all the child states for a variation key (it does not include the default state)
 *
 * @param int $variation_key
 *
 * @return array
 */
function tve_leads_get_form_child_states( $variation_key ) {
	global $tvedb;

	return $tvedb->get_form_variations( array(
		'parent_id' => $variation_key,
		'order'     => 'state_order ASC'
	) );
}

/**
 * get all the related states for a form variation
 * $variation can be either a state or the main form variation
 *
 * @param array $variation
 *
 * @return array all the related states
 */
function tve_leads_get_form_related_states( $variation ) {
	if ( is_numeric( $variation ) ) {
		$variation = tve_leads_get_form_variation( null, $variation );
	}

	if ( empty( $variation['parent_id'] ) ) {
		$parent = $variation;
	} else {
		$parent = tve_leads_get_form_variation( null, $variation['parent_id'] );
	}

	$variations = array( $parent );
	$variations = array_merge( $variations, tve_leads_get_form_child_states( $parent['key'] ) );

	/**
	 * Nice-names for each of the possible states
	 */
	$state_names = array(
		'lightbox'           => __( 'Lightbox', 'thrive-leads' ),
		'already_subscribed' => __( 'Already Subscribed', 'thrive-leads' ),
		'default'            => __( 'State', 'thrive-leads' )
	);
	$indexes     = array();
	foreach ( $variations as $k => $variation ) {
		if ( ! isset( $indexes[ $variation['form_state'] ] ) ) {
			$indexes[ $variation['form_state'] ] = 1;
		}
		$state_name = empty( $variation['parent_id'] ) ? __( 'Default State' ) : $state_names[ $variation['form_state'] ];
		if ( $variation['form_state'] && $variation['form_state'] != 'already_subscribed' ) {
			$state_name .= " {$indexes[$variation['form_state']]}";
		}
		$variations[ $k ]['state_name'] = $state_name;

		$indexes[ $variation['form_state'] ] ++;
	}

	return $variations;
}

/**
 * get the form type string for a variation
 * if the variation is a state, then we first check if it's state === 'lightbox' and if true, the form_type returned will be lightbox
 * by default it will return the form type of the variation parent (tve_form_type or shortcode etc)
 *
 * @param int|array $variation
 * @param bool $get_from_parent optional, allows forcing the function to return the form_type of the variation parent form (or shortcode etc)
 * @param bool $map_type if true, it will match the type with its template association (e.g. two_step => lightbox)
 *
 * @return string
 */
function tve_leads_get_form_type_from_variation( $variation, $get_from_parent = false, $map_type = true ) {
	if ( is_numeric( $variation ) ) {
		$variation = tve_leads_get_form_variation( null, $variation );
	}

	if ( ! $get_from_parent && ! empty( $variation['form_state'] ) && $variation['form_state'] == 'lightbox' ) {
		return 'lightbox';
	}

	$form_type = get_post_meta( $variation['post_parent'], 'tve_form_type', true );
	if ( $map_type ) {
		$form_type = Thrive_Leads_Template_Manager::tpl_type_map( $form_type );
	}

	return $form_type;
}

/**
 * finds and returns the 'already_subscribed' state for a variation, if present (there can only be 1 subscribed state / veriation)
 *
 * @param array $default_state the parent (default state) variation
 *
 * @return array|null
 */
function tve_leads_get_already_subscribed_state( $default_state ) {
	global $tvedb;

	return $tvedb->get_variation_already_subscribed_state( $default_state['key'] );
}

/**
 * get the tracking data for a post from the post-meta option
 * if no value is present there, count the logs and update the meta option value with that number
 *
 * applies to: Lead Groups, Form Types, Shortcodes, 2-step Shortcodes
 *
 * @param WP_Post|int $post
 * @param int $event_type
 * @param bool $fetch_if_not_found whether or not to count the logs if there is no entry in the cache
 *
 * @return int
 */
function tve_leads_get_post_tracking_data( $post, $event_type = TVE_LEADS_UNIQUE_IMPRESSION, $fetch_if_not_found = true ) {
	$post_id = $post;
	if ( is_array( $post ) ) {
		$post_id = $post['ID'];
	} elseif ( is_a( $post, 'WP_Post' ) ) {
		$post_id = $post->ID;
	}

	$meta_key = 'tve_leads_' . ( $event_type === TVE_LEADS_UNIQUE_IMPRESSION ? 'impressions' : 'conversions' );

	$value = get_post_meta( $post_id, $meta_key, true );

	if ( $value === '' && $fetch_if_not_found === true ) {
		$value = tve_leads_get_tracking_data( $event_type, array( $post->post_type === TVE_LEADS_POST_FORM_TYPE ? 'form_type_id' : 'main_group_id' => $post->ID ) );
		update_post_meta( $post_id, $meta_key, $value );
	}

	return $value;
}

/**
 * get tracking data for a form variation (design). Form variations are stored in a separate table, so we cannot use the WP post_meta API
 *
 * applies to: Form Variations
 *
 * @param array $variation
 * @param int $event_type
 *
 * @return int
 */
function tve_leads_get_variation_tracking_data( &$variation, $event_type = TVE_LEADS_UNIQUE_IMPRESSION ) {
	$key = 'cache_' . ( $event_type === TVE_LEADS_UNIQUE_IMPRESSION ? 'impressions' : 'conversions' );

	if ( $variation[ $key ] === null ) {
		$variation[ $key ]      = (int) tve_leads_get_tracking_data( $event_type, array( 'variation_key' => $variation['key'] ) );
		$variation['save_flag'] = true;
	}

	return $variation[ $key ];
}

/**
 * update the cached impression or conversion count for a post
 *
 * applies to: Lead Groups, Form Types, Shortcodes, 2-step Lightboxes
 *
 * @param mixed $post
 * @param int $value
 * @param int $event_type
 *
 * @return int|bool
 */
function tve_leads_set_post_tracking_data( $post, $value, $event_type = TVE_LEADS_UNIQUE_IMPRESSION ) {
	$post_id = $post;
	if ( is_array( $post ) ) {
		$post_id = $post['ID'];
	} elseif ( is_a( $post, 'WP_Post' ) ) {
		$post_id = $post->ID;
	}

	$meta_key = 'tve_leads_' . ( $event_type === TVE_LEADS_UNIQUE_IMPRESSION ? 'impressions' : 'conversions' );

	return update_post_meta( $post_id, $meta_key, $value );
}

/**
 * reset all cached impression and conversion count for a post (Lead Group / Form Type / Shortcode / 2-step Lightbox
 *
 * @param WP_Post $post
 *
 * @return bool
 */
function tve_leads_reset_post_tracking_data( $post ) {
	global $tvedb;

	if ( $post->post_parent ) {
		/**
		 * if this is a Form Type, we need to also update the parent cached impression count
		 */
		$impressions = tve_leads_get_post_tracking_data( $post, TVE_LEADS_UNIQUE_IMPRESSION );
		$conversions = tve_leads_get_post_tracking_data( $post, TVE_LEADS_CONVERSION );

		$parent_impressions = tve_leads_get_post_tracking_data( $post->post_parent, TVE_LEADS_UNIQUE_IMPRESSION, false );
		$parent_conversions = tve_leads_get_post_tracking_data( $post->post_parent, TVE_LEADS_CONVERSION, false );

		if ( $parent_impressions !== '' ) {
			$parent_impressions -= $impressions;
			tve_leads_set_post_tracking_data( $post->post_parent, $parent_impressions, TVE_LEADS_UNIQUE_IMPRESSION );
		}

		if ( $parent_conversions !== '' ) {
			$parent_conversions -= $conversions;
			tve_leads_set_post_tracking_data( $post->post_parent, $parent_conversions, TVE_LEADS_CONVERSION );
		}
	}

	tve_leads_set_post_tracking_data( $post, 0, TVE_LEADS_UNIQUE_IMPRESSION );
	tve_leads_set_post_tracking_data( $post, 0, TVE_LEADS_CONVERSION );

	/**
	 * also, we need to reset the data for all variations that have $post as post_parent
	 */
	$variations = tve_leads_get_form_variations( $post->ID, array(
		'tracking_data' => false,
		'post_status'   => array( TVE_LEADS_STATUS_PUBLISH, TVE_LEADS_STATUS_ARCHIVED )
	) );
	foreach ( $variations as $v ) {
		$tvedb->update_variation_fields( $v, array(
			'cache_impressions' => 0,
			'cache_conversions' => 0
		) );
	}

	return true;
}

/**
 * reset the cached tracking data for a variation and also update the cached tracking data for its parents (the Form Type and the Lead Group, if any)
 *
 * @param array $variation
 */
function tve_leads_reset_variation_tracking_data( $variation ) {
	global $tvedb;

	/**
	 * reset the cached variation logs
	 */
	$tvedb->update_variation_fields( $variation['key'], array(
		'cache_impressions' => 0,
		'cache_conversions' => 0
	) );

	/**
	 * decrease the number of impressions and conversions from the parent cached variations (if any)
	 */
	$parent_impressions = tve_leads_get_post_tracking_data( $variation['post_parent'], TVE_LEADS_UNIQUE_IMPRESSION, false );
	$parent_conversions = tve_leads_get_post_tracking_data( $variation['post_parent'], TVE_LEADS_CONVERSION, false );

	/**
	 * update only if there actually is some cached data
	 */
	if ( $parent_impressions !== '' ) {
		$parent_impressions -= $variation['impressions'];
		tve_leads_set_post_tracking_data( $variation['post_parent'], $parent_impressions, TVE_LEADS_UNIQUE_IMPRESSION );
	}

	if ( $parent_conversions !== '' ) {
		$parent_conversions -= $variation['conversions'];
		tve_leads_set_post_tracking_data( $variation['post_parent'], $parent_conversions, TVE_LEADS_CONVERSION );
	}

	/**
	 * go a level higher, and change the cached data for the Lead Group, if any is found
	 */
	$parent = get_post( $variation['post_parent'] );
	if ( $parent && $parent->post_parent ) {
		$parent_impressions = tve_leads_get_post_tracking_data( $parent->post_parent, TVE_LEADS_UNIQUE_IMPRESSION, false );
		$parent_conversions = tve_leads_get_post_tracking_data( $parent->post_parent, TVE_LEADS_CONVERSION, false );

		if ( $parent_impressions !== '' ) {
			$parent_impressions -= $variation['impressions'];
			tve_leads_set_post_tracking_data( $parent->post_parent, $parent_impressions, TVE_LEADS_UNIQUE_IMPRESSION );
		}

		if ( $parent_conversions !== '' ) {
			$parent_conversions -= $variation['conversions'];
			tve_leads_set_post_tracking_data( $parent->post_parent, $parent_conversions, TVE_LEADS_CONVERSION );
		}
	}
}

/**
 * Send email with the contact
 *
 * @param     $contact_id
 * @param     $email
 * @param int $save
 *
 * @return array
 */
function tve_send_contacts_email( $contact_id, $email, $save = 0 ) {
	global $tvedb;

	if ( empty( $email ) || ! is_email( $email ) ) {
		return array(
			'response' => __( 'Invalid Email.', 'thrive-leads' ),
			'type'     => 'error'
		);
	}
	if ( $save ) {
		tve_leads_update_option( 'contacts_send_email', $email );
	} else {
		tve_leads_update_option( 'contacts_send_email', '' );
	}

	$contact                = $tvedb->tve_get_contact( 'id', $contact_id );
	$contact->custom_fields = json_decode( $contact->custom_fields, true );

	$subject = __( 'You have a New Signup', 'thrive-leads' );

	ob_start();
	include dirname( dirname( __FILE__ ) ) . '/admin/views/contacts/email_template.php';
	$message = ob_get_contents();
	ob_end_clean();

	$subject = apply_filters( 'tve_leads_new_contact_body_subject', $subject, $contact );
	$message = apply_filters( 'tve_leads_new_contact_email_body', $message, $contact );

	$result = wp_mail( $email, $subject, $message );

	if ( $result ) {
		$return = array(
			'response' => __( 'Email sent successfully!', 'thrive-leads' ),
			'type'     => 'success'
		);
	} else {
		$return = array(
			'response' => __( 'An error occurred while sending the email!', 'thrive-leads' ),
			'type'     => 'error'
		);
	}

	return $return;
}

/**
 * Prepare the file for download.
 *
 * @param $source
 * @param $type
 * @param $params
 *
 * @return array|mixed|object
 */
function tve_leads_process_contact_download( $source, $type, $params ) {
	require_once dirname( dirname( __FILE__ ) ) . '/admin/inc/classes/Thrive_Leads_Export.php';

	$upload_dir          = wp_upload_dir();
	$contact_upload_path = $upload_dir['basedir'] . "/thrive-contacts";
	$contact_upload_url  = $upload_dir['baseurl'] . "/thrive-contacts";

	/* if we can't create a thrive contact folder, we just use the uploads one */
	if ( ! wp_mkdir_p( $contact_upload_path ) ) {
		$contact_upload_path = $upload_dir['basedir'];
		$contact_upload_url  = $upload_dir['baseurl'];
	}

	$filename = "contacts-export-" . date( 'Y-m-d_H-i-s' );

	switch ( $type ) {
		case 'excel':
			$filename .= ".xls";
			$exporter = new ThriveLeadsExportDataExcel( 'file', $contact_upload_path . '/' . $filename );
			break;

		case 'csv':
			$filename .= ".csv";
			$exporter = new ThriveLeadsExportDataCSV( 'file', $contact_upload_path . '/' . $filename );
			break;

		default:
			$filename = $exporter = '';
	}

	$exporter->initialize();

	if ( empty( $filename ) || empty( $exporter ) ) {
		return array(
			'response' => __( 'Invalid export type.', 'thrive-leads' )
		);
	}

	global $tvedb;
	/* get contacts needed for export */
	$contacts = $tvedb->tve_leads_get_contacts_stored( $source, $params );
	/* store the download in the database */
	$id = $tvedb->tve_leads_write_contact_download( $source, $contact_upload_url . '/' . $filename, $params );

	/* build file header with custom fields */
	$contacts_header = array(
		__( "Name", "thrive-leads" ),
		__( "Email", "thrive-leads" ),
		__( "Date and Time", "thrive-leads" )
	);
	$custom_header   = array();
	foreach ( $contacts as $contact ) {
		$custom_fields = json_decode( $contact->custom_fields );
		foreach ( $custom_fields as $k => $v ) {
			if ( ! in_array( $k, $custom_header ) ) {
				$custom_header [] = $k;
			}
		}
	}
	$exporter->addRow( array_merge( $contacts_header, $custom_header ) );

	foreach ( $contacts as $contact ) {
		$fields = array( $contact->name, $contact->email, date( 'd M, Y G:i', strtotime( $contact->date ) ) );

		$custom_fields = json_decode( $contact->custom_fields, true );
		foreach ( $custom_header as $field ) {
			if ( isset( $custom_fields[ $field ] ) ) {
				$fields[] = $custom_fields[ $field ];
			} else {
				$fields[] = "";
			}
		}
		$exporter->addRow( $fields );
	}

	$exporter->finalize();
	/* mark the download as completed */
	$tvedb->tve_leads_update_contacts_download_status( $id, 'complete' );

	$result = array(
		'status'   => 'complete',
		'response' => __( 'Export Completed', 'thrive-leads' ),
		'link'     => $contact_upload_url . '/' . $filename,
		'id'       => $id
	);

	return $result;
}

/**
 * Return chart data for annotations
 *
 * @param $filter
 * @param $chart_data
 *
 * @return array
 */
function tve_leads_get_chart_annotations( $filter, $chart_data ) {
	$grow        = 0;
	$grows_count = 0;
	/* Calculate the medium growth so we can set a threshold for which to display annotations */
	for ( $i = 1; $i < count( $chart_data ); $i ++ ) {
		if ( $chart_data[ $i - 1 ] < $chart_data[ $i ] ) {
			$grow += $chart_data[ $i ] - $chart_data[ $i - 1 ];
			$grows_count ++;
		}
	}

	$data = array(
		'type'     => 'scatter',
		'id'       => 'flags',
		'zIndex'   => 2,
		'name'     => __( 'Marketing Events', 'thrive-leads' ),
		'color'    => '#800080',
		'onSeries' => 'dataseries',
		'shape'    => 'triangle',
		'data'     => array()
	);

	if ( $grows_count ) {
		$medium_growth = $grow / $grows_count;
	} else {
		return $data;
	}

	$dates = tve_leads_generate_dates_interval( $filter['start_date'], $filter['end_date'], $filter['interval'] );

	/* We find the date interval where we have a growth bigger than the medium and we search events that we want to display */
	for ( $i = 1; $i < count( $chart_data ); $i ++ ) {
		if ( $chart_data[ $i ] - $chart_data[ $i - 1 ] > $medium_growth ) {

			$current_date = $dates[ $i - 1 ];

			if ( $i == count( $chart_data ) - 1 ) {
				/* If we're at the last value, we set the end date to today. */
				$end_date = date( 'Y-m-d' );
			}

			if ( strpos( $current_date, 'Week' ) !== false ) {
				$current_date = preg_replace( "/Week (\d*), (.\d*)/", "$2W$1", $current_date );
			}
			/* Convert the chart date in mysql date format so we can search posts in that period */
			$filter['start_date'] = date( 'Y-m-d', strtotime( $current_date ) ) . ' 00:00:00';
			$filter['end_date']   = date( 'Y-m-d', strtotime( $current_date ) ) . ' 23:59:59';

			$args = array(
				'post_type'      => array(
					'tve_lead_group',
					'tve_lead_shortcode',
					'tve_lead_2s_lightbox',
					'post',
					'page'
				),
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'ASC',
				'date_query'     => array(
					array(
						'after'     => $filter['start_date'],
						'before'    => $filter['end_date'],
						'inclusive' => true,
					),
				),
			);

			$query = new WP_query();
			$posts = $query->query( $args );

			$events = array(
				'post'                 => array(),
				'page'                 => array(),
				'tests'                => array(),
				'tve_lead_group'       => array(),
				'tve_lead_shortcode'   => array(),
				'tve_lead_2s_lightbox' => array()
			);

			foreach ( $posts as $post ) {
				$_type = get_post_type( $post->ID );
				if ( ! empty( $post->post_title ) && $_type && isset( $events[ $_type ] ) ) {
					$events[ $_type ][] = $post->post_title;
				}
			}

			global $tvedb;
			$tests = $tvedb->tve_leads_get_tests( $filter );

			foreach ( $tests as $test ) {
				$events['tests'][] = $test->title;
			}

			if ( ! empty( $events['post'] ) || ! empty( $events['page'] ) || ! empty( $events['tests'] ) || ! empty( $events['tve_lead_group'] ) || ! empty( $events['tve_lead_shortcode'] ) || ! empty( $events['tve_lead_2s_lightbox'] ) ) {
				$title = '';
				$title .= empty( $events['post'] ) ? '' : '<span class="tve-data-label-posts"><b>' . __( 'Posts Created: ' ) . '</b>' . implode( ', ', $events['post'] ) . '</span><br>';
				$title .= empty( $events['page'] ) ? '' : '<span class="tve-data-label-pages"><b>' . __( 'Pages Created: ' ) . '</b>' . implode( ', ', $events['page'] ) . '</span><br>';
				$title .= empty( $events['tve_lead_group'] ) ? '' : '<span class="tve-data-label-groups"><b>' . __( 'Groups Created: ' ) . '</b>' . implode( ', ', $events['tve_lead_group'] ) . '</span><br>';
				$title .= empty( $events['tve_lead_shortcode'] ) ? '' : '<span class="tve-data-label-shortcodes"><b>' . __( 'Shortcodes Created: ' ) . '</b>' . implode( ', ', $events['tve_lead_shortcode'] ) . '</span><br>';
				$title .= empty( $events['tve_lead_2s_lightbox'] ) ? '' : '<span class="tve-data-label-thriveboxes"><b>' . __( 'ThriveBoxes Created: ' ) . '</b>' . implode( ', ', $events['tve_lead_2s_lightbox'] ) . '</span><br>';
				$title .= empty( $events['tests'] ) ? '' : '<span class="tve-data-label-tests"><b>' . __( 'Tests started: ' ) . '</b>' . implode( ', ', $events['tests'] ) . '</span>';

				/* We display the annotation between the points that indicate a growth at the middle */
				$data['data'][] = array(
					'x'          => $i - 0.5,
					'y'          => ( $chart_data[ $i ] - $chart_data[ $i - 1 ] ) / 2 + $chart_data[ $i - 1 ],
					'dataLabels' => array(
						'useHTML'       => true,
						'enabled'       => true,
						'format'        => $title,
						'verticalAlign' => 'bottom',
						'y'             => - 10
					)
				);
			}
		}
	}


	return $data;

}

/**
 * Get data for asset wizard to decide if the wizard should show, list of connected apis, email templates,  and file/group proprieties
 *
 * @param array $asset_groups
 *
 * @return mixed
 */
function tve_leads_get_wizard_proprieties( $asset_groups = array() ) {
	$connected_apis = Thrive_List_Manager::getAvailableAPIsByType( true, array( 'email' ) );
	if ( empty( $connected_apis ) ) {
		$proprieties['connections'] = 0;
	} else {
		$proprieties['connections'] = 1;
	}
	$template_subject = get_option( 'tve_leads_asset_mail_subject' );
	$template_body    = get_option( 'tve_leads_asset_mail_body' );
	if ( empty( $template_subject ) || empty( $template_body ) ) {
		$proprieties['template'] = 0;
	} else {
		$proprieties['template'] = 1;
	}
	if ( empty( $asset_groups ) ) {
		$proprieties['files'] = 0;
	} else {
		foreach ( $asset_groups as $asset_group ) {
			if ( ! empty( $asset_group->files ) ) {
				$proprieties['files'] = 1;
			} else {
				$proprieties['files'] = 0;
			}
		}
	}

	return $proprieties;
}

/**
 * Fetch the user's full name
 *
 * @return string
 */
function tve_leads_assets_get_admin_name() {
	global $current_user;
	wp_get_current_user();

	return $current_user->user_firstname . " " . $current_user->user_lastname;
}

/**
 * Get the asset email template
 *
 * @return mixed
 */
function tve_leads_assets_get_email_data() {
	$email_data['template_subject'] = get_option( 'tve_leads_asset_mail_subject', '' );
	$email_data['template_body']    = get_option( 'tve_leads_asset_mail_body', '' );

	return $email_data;
}

/**
 * Sets the email template for asset delivery
 *
 * @param $data
 *
 * @return bool
 */
function tve_leads_set_email_template( $data ) {
	update_option( 'tve_leads_asset_mail_subject', stripslashes( $data['post_subject'] ) );
	update_option( 'tve_leads_asset_mail_body', stripslashes( $data['post_content'] ) );

	return true;
}

/**
 * Gets the running tests for inconclusive test check
 *
 * @return array|void
 */
function tve_get_running_inconclusive_tests() {
	/* @var Tho_Db */
	global $tvedb;

	$return       = array();
	$active_tests = $tvedb->tve_leads_get_tests( array(
		'status'           => TVE_LEADS_STATUS_RUNNING,
		'auto_win_enabled' => 1
	) );

	if ( empty( $active_tests ) ) {
		return;
	}

	foreach ( $active_tests as $active_test ) {

		$test_items = $tvedb->get_test_items( array( 'test_id' => $active_test->id ) );

		$conversions = 0;
		foreach ( $test_items as $item ) {
			$conversions += intval( $item->conversions );
		}

		$minimum_duration_doubled = intval( $active_test->auto_win_min_duration ) * 2;
		if ( $active_test->auto_win_min_conversions * 2 <= $conversions && date( 'Y-m-d', strtotime( $active_test->date_started . ' + ' . $minimum_duration_doubled . ' days' ) ) <= date( 'Y-m-d' ) ) {
			$return[] = $active_test;
		}
	}

	return $return;
}

/**
 * Stops a test item and sets the variation as winner if it's the last variation
 *
 * @param $item_id
 * @param $test_id
 *
 * @return bool
 */
function tve_leads_stop_test_item( $item_id, $test_id ) {
	/* @var Tho_Db */
	global $tvedb;

	$return = $tvedb->stop_test_item( $item_id );
	if ( $return ) {
		$test_items = $tvedb->get_test_items( array( 'test_id' => $test_id, 'active' => 1 ) );
		if ( count( $test_items ) == 1 ) {
			$test_items[0]->is_winner = 1;
			tve_leads_save_test_item( (array) $test_items[0] );
		}

		return true;
	}

	return false;
}