<?php

/**
 * appends the WordPress tables prefix and the default tve_leads prefix to the table name
 *
 * @param string $table
 *
 * @return string the modified table name
 */
function tve_leads_table_name( $table ) {
	global $wpdb;

	return $wpdb->prefix . TVE_LEADS_DB_PREFIX . $table;
}

/**
 * return a list with all available form types
 * the keys in the array are internally used codes, and the values are the translations of the form type names
 *
 * @param bool $include_extra whether or not to include the shortcode type and 2-step type in the returned result
 *
 * @return array
 */
function tve_leads_get_default_form_types( $include_extra = false ) {
	$main = array(
		'in_content'    => array(
			'post_title'    => __( 'In content', 'thrive-leads' ),
			'tve_form_type' => 'in_content',
			'edit_selector' => '.thrv-leads-in-content', // selector for the element settings in editing mode
			'wp_hook'       => 'the_content',
			'priority'      => 20,
			'video_link'    => '//fast.wistia.net/embed/iframe/vp8pi64ss2?popover=true'
		),
		'lightbox'      => array(
			'post_title'    => __( 'Lightbox', 'thrive-leads' ),
			'tve_form_type' => 'lightbox',
			'edit_selector' => '.tve_p_lb_control', // selector for the element settings in editing mode
			'wp_hook'       => 'wp_footer',
			'video_link'    => '//fast.wistia.net/embed/iframe/eodv48v1qz?popover=true'
		),
		'post_footer'   => array(
			'post_title'    => __( 'Post Footer', 'thrive-leads' ),
			'tve_form_type' => 'post_footer',
			'edit_selector' => '.thrv-leads-form-box', // selector for the element settings in editing mode
			'wp_hook'       => 'the_content',
			'video_link'    => '//fast.wistia.net/embed/iframe/nizwf1uccw?popover=true'
		),
		'ribbon'        => array(
			'post_title'    => __( 'Ribbon', 'thrive-leads' ),
			'tve_form_type' => 'ribbon',
			'edit_selector' => '.thrv-ribbon', // selector for the element settings in editing mode
			'wp_hook'       => 'wp_footer',
			'video_link'    => '//fast.wistia.net/embed/iframe/p8a5uowels?popover=true'
		),
		'screen_filler' => array(
			'post_title'    => __( 'Screen filler Lightbox', 'thrive-leads' ),
			'tve_form_type' => 'screen_filler',
			'edit_selector' => '.thrv-leads-screen-filler', // selector for the element settings in editing mode
			'wp_hook'       => 'wp_footer',
			'video_link'    => '//fast.wistia.net/embed/iframe/abpv5so4uq?popover=true'
		),
		'greedy_ribbon' => array(
			'post_title'    => __( 'Scroll Mat', 'thrive-leads' ),
			'tve_form_type' => 'greedy_ribbon',
			'edit_selector' => '.thrv-greedy-ribbon', // selector for the element settings in editing mode
			'wp_hook'       => 'wp_footer',
			'video_link'    => '//fast.wistia.net/embed/iframe/2vg13bctud?popover=true'
		),
		'slide_in'      => array(
			'post_title'    => __( 'Slide in', 'thrive-leads' ),
			'tve_form_type' => 'slide_in',
			'edit_selector' => '.thrv-leads-slide-in', // selector for the element settings in editing mode
			'wp_hook'       => 'wp_footer',
			'video_link'    => '//fast.wistia.net/embed/iframe/1p5u2b9rmd?popover=true'
		),
		'widget'        => array(
			'post_title'    => __( 'Widget', 'thrive-leads' ),
			'edit_selector' => '.thrv-leads-widget', // selector for the element settings in editing mode
			'tve_form_type' => 'widget',
			'video_link'    => '//fast.wistia.net/embed/iframe/3luamnx1va?popover=true'
		),
		/**
		 * no wp_hook for php_inserts, these are to be added by the (advanced) user - as pieces of PHP code that can be directly inserted into
		 * the theme / sidebar etc
		 */
		'php_insert'    => array(
			'post_title'    => __( 'PHP Insert', 'thrive-leads' ),
			'tve_form_type' => 'php_insert',
			'edit_selector' => '.thrv-leads-form-box',
		),
	);

	if ( $include_extra ) {
		$main['shortcode'] = array(
			'post_title'    => __( 'Shortcode', 'thrive-leads' ),
			'tve_form_type' => 'shortcode',
			'edit_selector' => '.thrv-leads-form-box', // selector for the element settings in editing mode
			'wp_hook'       => ''
		);

		$main['two_step_lightbox'] = array(
			'post_title'    => __( 'ThriveBox', 'thrive-leads' ),
			'tve_form_type' => 'two_step_lightbox',
			'edit_selector' => '.tve_p_lb_control', // selector for the element settings in editing mode
			'wp_hook'       => ''
		);
	}

	return $main;
}

/**
 * prepare the default form types for the backbone application
 *
 * @return array()
 */
function tve_leads_prepare_default_form_types() {
	$types = tve_leads_get_default_form_types();

	return array_values( $types );
}

/**
 * return a formatted conversion rate based on $impressions and $conversions
 *
 * @param int $impressions
 * @param int $conversions
 * @param string $suffix
 * @param string $decimals
 *
 * @return string $rate the calculated conversion rate
 */
function tve_leads_conversion_rate( $impressions, $conversions, $suffix = '%', $decimals = '2' ) {
	if ( $conversions == 0 || $impressions == 0 ) {
		return 'N/A';
	}

	return round( 100 * ( $conversions / $impressions ), $decimals ) . $suffix;
}

/**
 * get the configuration array for a specific editor template for a form variation
 *
 * @param string $key
 *
 * @return array
 */
function tve_leads_get_editor_template_config( $key ) {
	if ( strpos( $key, '|' ) === false ) {
		return array();
	}
	list( $type, $key ) = explode( '|', $key );
	$config = require dirname( dirname( __FILE__ ) ) . '/editor-templates/_config.php';

	$type = Thrive_Leads_Template_Manager::tpl_type_map( $type );

	$cloud_config = tve_leads_get_downloaded_templates( $type );

	if ( ! empty( $cloud_config ) ) {
		$config[ $type ] = array_merge_recursive( $config[ $type ], $cloud_config );
	}

	return isset( $config[ $type ][ $key ] ) ? $config[ $type ][ $key ] : array();
}

/**
 * get configuration for a specific variation (form) type
 *
 * @param string $variation_type ribbon, lightbox etc
 *
 * @return array
 */
function tve_leads_get_editor_template_type_config( $variation_type ) {
	$config         = require dirname( dirname( __FILE__ ) ) . '/editor-templates/_config.php';
	$variation_type = Thrive_Leads_Template_Manager::tpl_type_map( $variation_type );

	$cloud_config = tve_leads_get_downloaded_templates( $variation_type );

	if ( ! empty( $cloud_config ) ) {
		$config[ $variation_type ] = array_merge_recursive( $config[ $variation_type ], $cloud_config );
	}

	return isset( $config[ $variation_type ] ) ? $config[ $variation_type ] : array();
}

/**
 *
 * get the TCB editor EDIT URL for a form variation
 *
 * @param int $post_id
 * @param int $variation_key
 *
 * @return string the url to open the editor for this variation
 */
function tve_leads_get_editor_url( $post_id, $variation_key ) {
	$cache = isset( $GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] ) ? $GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] : array();
	if ( ! isset( $cache[ $post_id ] ) ) {
		$cache[ $post_id ]                     = set_url_scheme( get_permalink( $post_id ) );
		$GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] = $cache;
	}
	/*
	 * We need the post to complete the full arguments
	 */
	$post        = get_post( $post_id );
	$editor_link = $cache[ $post_id ];
	$editor_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( array(
		'tve'  => 'true',
		'_key' => $variation_key,
		'r'    => uniqid()
	), $editor_link ), $post ) );

	/**
	 * we need to make sure that if the admin is https, then the editor link is also https, otherwise any ajax requests through wp ajax api will not work
	 */
	$admin_ssl = strpos( admin_url(), 'https' ) === 0;

	return $admin_ssl ? str_replace( 'http://', 'https://', $editor_link ) : $editor_link;
}

/**
 *
 * get the TCB editor PREVIEW URL for a form variation
 *
 * @param int $post_id
 * @param int $variation_key
 *
 * @return string the url to open the editor for this variation
 */
function tve_leads_get_preview_url( $post_id, $variation_key ) {
	$cache = isset( $GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] ) ? $GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] : array();
	if ( ! isset( $cache[ $post_id ] ) ) {
		$cache[ $post_id ]                     = set_url_scheme( get_permalink( $post_id ) );
		$GLOBALS['TVE_LEADS_CACHE_PERMALINKS'] = $cache;
	}
	/*
	 * We need the post to complete the full arguments
	 */
	$post        = get_post( $post_id );
	$editor_link = $cache[ $post_id ];
	$editor_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( array( '_key' => $variation_key, 'r' => uniqid() ), $editor_link ), $post ) );

	return $editor_link;
}

/**
 * generate a random number between 0 and $total-1
 *
 * @param int $total
 * @param int $multiplier for smaller values, it's better to extend the interval by a number of times,
 * example: to choose between 0 and 1 -> we think it's better to have a random number between 0 and 10000 and split that into halves
 *
 * @return int
 */
function tve_leads_get_random_index( $total, $multiplier = 1000 ) {
	$_rand = function_exists( 'mt_rand' ) ? mt_rand( 0, $total * $multiplier - 1 ) : rand( 0, $total * $multiplier - 1 );

	return intval( floor( $_rand / $multiplier ) );
}

/**
 * get a list of all available triggers (optional) grouped by a specific form type
 *
 * @param string|null $form_type , if present, it will only return triggers that apply to that specific form type
 *
 * @param bool $get_as_array whether or not to get the results as array instead of Trigger objects
 *
 * @return array the list of triggers
 */
function tve_leads_get_available_triggers( $form_type = null, $get_as_array = false ) {
	$return = array();
	foreach ( TVE_Leads_Trigger_Abstract::$AVAILABLE as $trigger ) {
		$item = TVE_Leads_Trigger_Abstract::factory( $trigger );
		if ( ! $item ) {
			continue;
		}
		if ( $form_type && ! $item->appliesTo( $form_type ) ) {
			continue;
		}
		$return[ $trigger ] = $get_as_array ? $item->to_array() : $item;
	}

	return $return;
}

/**
 * get a list of all available animations (optional) grouped by a specific form type
 *
 * @param bool $get_as_array whether or not to get the results as array instead of Animation objects
 *
 * @return array the list of animations
 */
function tve_leads_get_available_animations( $get_as_array = false ) {
	$return = array();
	foreach ( TVE_Leads_Animation_Abstract::$AVAILABLE as $animation ) {
		$item = TVE_Leads_Animation_Abstract::factory( $animation );
		if ( ! $item ) {
			continue;
		}

		$return[ $animation ] = $get_as_array ? $item->to_array() : $item;
	}

	return $return;
}

/**
 * get a list of all available positions
 *
 * @param String $form_type
 *
 * @return array the list of positions
 */
function tve_leads_get_available_positions( $form_type ) {
	switch ( $form_type ) {
		case 'slide_in':
			return array(
				'label'    => __( 'Position', 'thrive-leads' ),
				'position' => array(
					'bot_left'  => __( 'Bottom Left', 'thrive-leads' ),
					'bot_right' => __( 'Bottom Right', 'thrive-leads' ),
					'top_left'  => __( 'Top Left', 'thrive-leads' ),
					'top_right' => __( 'Top Right', 'thrive-leads' )
				)
			);
		case 'ribbon':
			return array(
				'label'    => __( 'Position', 'thrive-leads' ),
				'position' => array(
					'top'    => __( 'Top', 'thrive-leads' ),
					'bottom' => __( 'Bottom', 'thrive-leads' )
				)
			);
		case 'in_content':
			return array(
				'label'    => __( 'Show after how many paragraphs?', 'thrive-leads' ),
				'position' => array( 0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10 )
			);
		default:
			return array();
	}
}

/**
 * get a unique cookie key based on a form that's being displayed now
 *
 * @param $main_group
 * @param $form_type
 * @param $variation
 * @param string $type
 *
 * @return string
 */
function tve_leads_get_form_cookie_key( $main_group, $form_type, $variation, $type = 'impression' ) {

	$group_id      = is_object( $main_group ) ? $main_group->ID : $main_group;
	$form_type_id  = is_object( $form_type ) ? $form_type->ID : $form_type;
	$variation_key = is_array( $variation ) ? $variation['key'] : $variation;

	$key = 'tl' . ( $type ? '_' . $type : '' );
	if ( $group_id ) {
		$key .= '_' . $group_id;
	}
	if ( $form_type_id ) {
		$key .= '_' . $form_type_id;
	}
	$key .= '_' . $variation_key;

	return $key;
}

/**
 * flush and send the output buffer, for faster responses during the form submit
 * headers and some data are send and after this the main processing of data can be done without users knowing the difference
 *
 * thanks to http://stackoverflow.com/questions/10579116/how-to-flush-data-to-browser-but-continue-executing
 */
function tve_leads_send_output() {
	ob_start();

	/* generate some dummy output */
	echo '1' . str_pad( '', 4096 ) . "\n";

	/* Ignore connection-closing by the client */
	ignore_user_abort( true );

	/* send the contents */
	$content = ob_get_contents();        // Get the content of the output buffer
	ob_end_clean();                      // Close current output buffer
	$len = strlen( $content );             // Get the length
	header( 'Connection: close' );         // Tell the client to close connection
	header( "Content-Length: $len" );      // Close connection after $size characters
	echo $content;                       // Output content
	flush();                             // Force php-output-cache to flush to browser.

	// Kill all other output buffering
	while ( ob_get_level() > 0 ) {
		ob_end_clean();
	}
}

/**
 * Calculate the chance of a variation to beat the original during a test
 *
 * @param $variation_conversion_rate
 * @param $variation_unique_impressions
 * @param $control_conversion_rate
 * @param $control_unique_impressions
 *
 * @return string $confidence_level
 */
function tve_leads_test_item_beat_original( $variation_conversion_rate, $variation_unique_impressions, $control_conversion_rate, $control_unique_impressions, $suffix = '%' ) {
	if ( $variation_unique_impressions == 0 || $control_unique_impressions == 0 ) {
		return 'N/A';
	}

	$variation_conversion_rate = $variation_conversion_rate / 100;
	$control_conversion_rate   = $control_conversion_rate / 100;

	//standard deviation = sqrt((conversionRate*(1-conversionRate)/uniqueImpressions)
	$variation_standard_deviation = sqrt( ( $variation_conversion_rate * ( 1 - $variation_conversion_rate ) / $variation_unique_impressions ) );
	$control_standard_deviation   = sqrt( ( $control_conversion_rate * ( 1 - $control_conversion_rate ) / $control_unique_impressions ) );

	if ( ( $variation_standard_deviation == 0 && $control_standard_deviation == 0 ) || ( is_nan( $variation_standard_deviation ) || is_nan( $control_standard_deviation ) ) ) {
		return 'N/A';
	}
	//z-score = (control_conversion_rate - variation_conversion_rate) / sqrt((controlStandardDeviation^2)+(variationStandardDeviation^2))
	$z_score = ( $control_conversion_rate - $variation_conversion_rate ) / sqrt( pow( $control_standard_deviation, 2 ) + pow( $variation_standard_deviation, 2 ) );

	if ( is_nan( $z_score ) ) {
		return 'N/A';
	}

	//Confidence_level (which is synonymous with “chance to beat original”)  = normdist(z-score)
	$confidence_level = tve_leads_norm_dist( $z_score );

	return number_format( round( ( 1 - $confidence_level ) * 100, 2 ), 2 ) . $suffix;
}

/**
 * Function that will generate a cumulative normal distribution and return the confidence level as a number between 0 and 1
 *
 * @param $x
 *
 * @return float
 */
function tve_leads_norm_dist( $x ) {
	$b1 = 0.319381530;
	$b2 = - 0.356563782;
	$b3 = 1.781477937;
	$b4 = - 1.821255978;
	$b5 = 1.330274429;
	$p  = 0.2316419;
	$c  = 0.39894228;

	if ( $x >= 0.0 ) {
		if ( ( 1.0 + $p * $x ) == 0 ) {
			return 'N/A';
		}
		$t = 1.0 / ( 1.0 + $p * $x );

		return ( 1.0 - $c * exp( - $x * $x / 2.0 ) * $t * ( $t * ( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ) );
	} else {
		if ( ( 1.0 - $p * $x ) == 0 ) {
			return 'N/A';
		}
		$t = 1.0 / ( 1.0 - $p * $x );

		return ( $c * exp( - $x * $x / 2.0 ) * $t * ( $t * ( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ) );
	}
}

/**
 * Render the form included in the shortcode
 * the received parameter should contain one element: the id of the TL shortcode
 *
 * @param array $attributes
 *
 * @return string the rendered form
 */
function tve_leads_shortcode_render( $attributes = array() ) {
	if ( class_exists( 'PostGridHelper' ) && PostGridHelper::$render_post_grid === false ) {
		return '';
	}

	if ( is_feed() ) {
		return '';
	}
	// $attributes must be always array and from the parameter it can come with the empty string.
	if ( empty( $attributes ) ) {
		$attributes = array();
	}

	$defaults = array(
		'id'         => null,
		'for_editor' => false
	);

	$content    = '';
	$attributes = array_merge( $defaults, $attributes );

	if ( empty( $attributes['id'] ) ) {
		return __( 'Invalid shortcode parameters', 'thrive-leads' );
	}

	$ajax_load_forms = tve_leads_get_option( 'ajax_load' );

	$shortcode = tve_leads_get_shortcode( $attributes['id'] );
	if ( ! $shortcode || $shortcode->post_status != TVE_LEADS_STATUS_PUBLISH ) {
		return '';
	}

	/**
	 * enqueue the default scripts and CSS (required for all forms)
	 */
	tve_leads_enqueue_default_scripts();

	/**
	 * hold the main shortcode ID in the page, in a javascript var to be able to load it afterwards
	 */
	$GLOBALS['tve_leads_form_config']['shortcode_ids']   = empty( $GLOBALS['tve_leads_form_config']['shortcode_ids'] ) ? array() : $GLOBALS['tve_leads_form_config']['shortcode_ids'];
	$GLOBALS['tve_leads_form_config']['shortcode_ids'][] = $attributes['id'];

	if ( get_post_meta( $attributes['id'], 'tve_leads_masonry', true ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( get_post_meta( $attributes['id'], 'tve_leads_typefocus', true ) ) {
		tve_enqueue_script( 'tve_typed', tve_editor_js() . '/typed.min.js', array(), false, true );
	}

	if ( ! empty( $attributes['for_editor'] ) || ! $ajax_load_forms ) {
		$variation = tve_leads_determine_variation( $shortcode, ! empty( $attributes['for_editor'] ) ); // if we render it in the TCB editor => do not use/store cookies
		if ( empty( $variation ) ) {
			return '';
		}
		$GLOBALS['tve_leads_detected_variation'] = $variation;
		/**
		 * prepare data for triggers
		 */
		$GLOBALS['tve_lead_shortcodes'][ $attributes['id'] ] = $variation;
	}

	$type_key = 'shortcode_' . $attributes['id'];
	if ( empty( $attributes['for_editor'] ) && $ajax_load_forms ) {
		/**
		 * return at this point, as we will load this via AJAX
		 * append just a placeholder to the $content
		 */
		$content .= sprintf(
			'<span style="display:none" class="tl-placeholder-f-type-%s"></span>',
			$type_key
		);

		return $content;
	}

	list( $type, $key ) = explode( '|', $variation[ TVE_LEADS_FIELD_TEMPLATE ] );
	$key = preg_replace( '#_v(\d)+#', '', $key );

	/**
	 * and the specific styles / fonts etc for this particular form variation
	 * TCB editor page -> ajax request to get the variation
	 */
	$variation_config = tve_leads_enqueue_variation_scripts( $variation );
	if ( ! empty( $attributes['for_editor'] ) ) {
		return array_merge(
			$variation_config, array(
			'html' => sprintf(
				'<div class="tve-leads-conversion-object" id="%s" data-tl-type="%s"><div class="tve-leads-shortcode %s tve-leads-track-%s">%s</div></div>',
				'tve_' . $key,
				$type_key,
				'tl-anim-' . $variation['display_animation'],
				$type_key,
				str_replace( array( "\n", "\r" ), '', tve_editor_custom_content( $variation ) )
			),
		) );
	}

	$GLOBALS['tve_leads_form_config']['forms'][ 'shortcode_' . $attributes['id'] ] = array(
		'_key'           => $variation['key'],
		'trigger'        => $variation['trigger'],
		'trigger_config' => ! empty( $variation['trigger_config'] ) ? $variation['trigger_config'] : new stdClass(),
		'form_type_id'   => $attributes['id'],
		'main_group_id'  => $attributes['id'],
		'active_test_id' => empty( $variation['test_model'] ) ? '' : $variation['test_model']->id
	);

	/**
	 * register form impression
	 * the Lead_Group parameter is set to null
	 */

	$GLOBALS['tve_lead_impressions'][ 'shortcode_' . $attributes['id'] ] = array(
		'group_id'       => $attributes['id'],
		'form_type_id'   => $attributes['id'],
		'variation_key'  => $variation['key'],
		'active_test_id' => empty( $variation['test_model'] ) ? null : $variation['test_model']->id,
	);

	$content .= tve_leads_display_form_shortcode( '__return_content', tve_editor_custom_content( $variation ), $variation );

	/**
	 * we need to set the cookies inline using a <script> tag
	 */
	if ( ! empty( $variation['set_cookies'] ) ) {
		//$content .= tve_leads_inline_cookies($variation['set_cookies']);
		$GLOBALS['tve_leads_set_cookies'] = empty( $GLOBALS['tve_leads_set_cookies'] ) ? array() : $GLOBALS['tve_leads_set_cookies'];
		/**
		 * stack variation's cookies to be used later in print_footer_scripts action
		 */
		foreach ( $variation['set_cookies'] as $key => $value ) {
			$GLOBALS['tve_leads_set_cookies'][ $key ] = $value;
		}
	}

	tve_leads_display_js_impression_data( 'shortcode_' . $attributes['id'] );

	return $content;
}

/**
 * Display content locking shortcode.
 *
 * @param $attributes
 * @param $content
 *
 * @return String shortcode content
 */
function tve_leads_shortcode_lock_render( $attributes, $content ) {
	$content = do_shortcode( $content );
	if ( tve_leads_check_conversion_cookie( $attributes['id'] ) ) {
		return $content;
	}
	$shortcode = tve_leads_shortcode_render( $attributes );

	if ( ! isset( $GLOBALS['tve_leads_detected_variation'] ) ) {
		/**
		 * this means ajax_load is enabled - we need to output just the placeholder
		 */
		return sprintf(
			'<div class="tve_content_lock tve_lock_hide tve_lead_lock">
                <div class="tve_lead_lock_shortcode">%s</div>
                <div class="tve_lead_locked_content"><div class="tve_lead_locked_overlay"></div>%s</div>
            </div>',
			$shortcode,
			$content
		);
	}

	$variation = $GLOBALS['tve_leads_detected_variation'];

	$main_class = isset( $variation['display_animation'] ) && $variation['display_animation'] == 'blur' ? 'tve_lock_blur' : 'tve_lock_hide';

	$html = sprintf(
		'<div class="tve_content_lock %s tve_lead_lock">
            <div class="tve_lead_lock_shortcode">%s</div>
            <div class="tve_lead_locked_content"><div class="tve_lead_locked_overlay"></div>%s</div>
        </div>',
		$main_class,
		$shortcode,
		$content
	);

	return $html;
}

/**
 * Render the button / link etc contents that will trigger the opening of the lightbox
 * register the lightbox to be output in the footer (or just a placeholder in case AJAX-loading of forms is enabled)
 *
 * @param array $attributes
 * @param string $content
 *
 * @return string
 */
function tve_leads_two_step_render( $attributes, $content ) {
	if ( class_exists( 'PostGridHelper' ) && PostGridHelper::$render_post_grid === false ) {
		return '';
	}

	if ( empty( $attributes['id'] ) ) {
		return __( 'Invalid shortcode attributes', 'thrive-leads' );
	}

	$two_step = tve_leads_get_form_type( $attributes['id'], array( 'get_variations' => false ) );
	if ( ! $two_step || $two_step->post_status != TVE_LEADS_STATUS_PUBLISH ) {
		return '';
	}

	$ajax_load_forms = tve_leads_get_option( 'ajax_load' );

	if ( ! $ajax_load_forms ) {
		$variation = tve_leads_determine_variation( $two_step );
		if ( empty( $variation ) ) {
			return __( 'No form found', 'thrive-leads' );
		}

		/* If the subscribed state is hidden, we don't display the trigger for the two step lightbox */
		$already_subscribed_html = tve_leads_get_already_subscribed_html( $variation, 'lightbox', true );
		if ( $already_subscribed_html === TVE_ALREADY_SUBSCRIBED_HIDDEN ) {
			return '';
		}
	}

	/**
	 * hold the main shortcode ID in the page, in a javascript var to be able to load it afterwards
	 */
	$GLOBALS['tve_leads_form_config']['two_step_ids']   = empty( $GLOBALS['tve_leads_form_config']['two_step_ids'] ) ? array() : $GLOBALS['tve_leads_form_config']['two_step_ids'];
	$exists_on_page                                     = in_array( $attributes['id'], $GLOBALS['tve_leads_form_config']['two_step_ids'] );
	$GLOBALS['tve_leads_form_config']['two_step_ids'][] = $attributes['id'];

	/**
	 * enqueue the default scripts and CSS (required for all forms)
	 */
	tve_leads_enqueue_default_scripts();

	if ( get_post_meta( $attributes['id'], 'tve_leads_masonry', true ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( get_post_meta( $attributes['id'], 'tve_leads_typefocus', true ) ) {
		tve_enqueue_script( 'tve_typed', tve_editor_js() . '/typed.min.js', array(), false, true );
	}

	if ( $ajax_load_forms ) {
		$GLOBALS['tve_leads_footer_html'] = empty( $GLOBALS['tve_leads_footer_html'] ) ? '' : $GLOBALS['tve_leads_footer_html'];
		if ( ! $exists_on_page ) {
			$GLOBALS['tve_leads_footer_html'] .= sprintf( '<span style="display:none" class="tl-placeholder-f-type-%s"></span>', 'two_step_' . $attributes['id'] );
		}

		return sprintf(
			'<span class="tve-leads-two-step-trigger tl-2step-trigger-%s">%s</span>',
			$attributes['id'],
			do_shortcode( $content )
		);
	}

	$variation['trigger']        = 'click';
	$variation['trigger_config'] = array( 'c' => 'tl-2step-trigger-' . $attributes['id'] );

	/**
	 * prepare data for triggers
	 */
	$GLOBALS['tve_leads_two_step'][ $variation['key'] ] = $variation;

	/**
	 * and the specific styles / fonts etc for this particular form variation
	 */
	tve_leads_enqueue_variation_scripts( $variation );

	/**
	 * append javascript needed for triggers to the global JS variable
	 */
	$GLOBALS['tve_leads_form_config']['forms'][ 'two_step_' . $attributes['id'] ] = array(
		'_key'           => $variation['key'],
		'trigger'        => 'click', // ALWAYS click
		'trigger_config' => array( 'c' => 'tl-2step-trigger-' . $attributes['id'] ),
		'form_type_id'   => $attributes['id'],
		'main_group_id'  => $attributes['id'],
		'active_test_id' => ! empty( $variation['test_model'] ) ? $variation['test_model']->id : null
	);

	$GLOBALS['tve_leads_two_step'][ $variation['key'] ]['form_output'] = tve_editor_custom_content( $variation );

	$type = 'two_step_' . $attributes['id'];

	$GLOBALS['tve_lead_impressions'][ 'two_step_' . $attributes['id'] ] = array(
		'group_id'       => $two_step->ID,
		'form_type_id'   => $two_step->ID,
		'variation_key'  => $variation['key'],
		'active_test_id' => ! empty( $variation['test_model'] ) ? $variation['test_model']->id : null
	);

	$content = sprintf(
		'<span class="tve-leads-two-step-trigger tl-2step-trigger-%s">%s</span>',
		$attributes['id'],
		do_shortcode( $content )
	);

	/**
	 * we need to set the cookies inline using a <script> tag
	 */
	if ( ! empty( $variation['set_cookies'] ) ) {
		//$content .= tve_leads_inline_cookies($variation['set_cookies']);
		$GLOBALS['tve_leads_set_cookies'] = empty( $GLOBALS['tve_leads_set_cookies'] ) ? array() : $GLOBALS['tve_leads_set_cookies'];
		/**
		 * stack variation's cookies to be used later in print_footer_scripts action
		 */
		foreach ( $variation['set_cookies'] as $key => $value ) {
			$GLOBALS['tve_leads_set_cookies'][ $key ] = $value;
		}
	}

	tve_leads_display_js_impression_data( $type );

	return $content;

}

/**
 * get a human-friendly name for the trigger setup in $variation, based on trigger and trigger_config fields
 *
 * @param array $variation
 *
 * @return String
 */
function tve_leads_trigger_nice_name( $variation ) {
	$trigger = TVE_Leads_Trigger_Abstract::factory( $variation['trigger'], $variation['trigger_config'] );

	return $trigger ? $trigger->get_display_name() : '';
}

/**
 * check if there is a valid activated license for the TL plugin
 *
 * @return bool
 */
function tve_leads_license_activated() {
	return TVE_Dash_Product_LicenseManager::getInstance()->itemActivated( TVE_Dash_Product_LicenseManager::TL_TAG );
}

/**
 * check if the current TCB version is the one required by Thrive Leads
 *
 * @return bool
 */
function tve_leads_check_tcb_version() {
	if ( ! EXTERNAL_TCB ) { // the internal TCB code will always be up to date
		return true;
	}
	if ( ! defined( 'TVE_VERSION' ) || TVE_VERSION != TVE_REQUIRED_TCB_VERSION ) {
		return false;
	}

	return true;
}

/**
 * show a box with a warning message and a link to take the user to the license activation page
 * this will be called only when no valid / activated license has been found
 *
 * @return mixed
 */
function tve_leads_license_warning() {
	return include dirname( dirname( __FILE__ ) ) . '/admin/views/license_inactive.php';
}

/**
 * show a box with a warning message notifying the user to update the TCB plugin to the latest version
 * this will be shown only when the TCB version is lower than a minimum required version
 */
function tve_leads_tcb_version_warning() {
	return include dirname( dirname( __FILE__ ) ) . '/admin/views/tcb_version_incompatible.php';
}

/**
 * returns whether or not a form_type allows frequency settings - how many days should pass before displaying it in the same browser
 *
 * @param int|WP_Post $form_type
 *
 * @return bool
 */
function tve_leads_form_type_has_frequency_settings( $form_type ) {
	if ( is_null( $form_type ) ) {
		return false;
	}

	if ( is_numeric( $form_type ) ) {
		$form_type = tve_leads_get_form_type( $form_type );
	}

	if ( is_object( $form_type ) ) {
		if ( empty( $form_type->tve_form_type ) ) {
			return false;
		}
		$form_type = $form_type->tve_form_type;
	}

	if ( empty( $form_type ) ) {
		return false;
	}

	return in_array( $form_type, array(
		'ribbon',
		'slide_in',
		'lightbox',
		'screen_filler',
		'greedy_ribbon'
	) );
}

/**
 * returns whether or not a form_type allows position settings
 * currently, position are enabled just for slide in
 *
 * @param int|WP_Post $form_type
 *
 * @return bool
 */
function tve_leads_form_type_has_position_settings( $form_type ) {
	if ( is_null( $form_type ) ) {
		return false;
	}

	if ( is_numeric( $form_type ) ) {
		$form_type = tve_leads_get_form_type( $form_type );
	}

	if ( is_object( $form_type ) ) {
		if ( empty( $form_type->tve_form_type ) ) {
			return false;
		}
		$form_type = $form_type->tve_form_type;
	}

	if ( empty( $form_type ) ) {
		return false;
	}

	return in_array( $form_type, array(
		'slide_in',
		'ribbon',
		'in_content'
	) );
}

/**
 * returns whether or not a form_type allows animation settings
 * currently, animations are enabled just for lightboxes and screen fillers
 *
 * @param int|WP_Post $form_type
 *
 * @return bool
 */
function tve_leads_form_type_has_animation_settings( $form_type ) {
	if ( is_null( $form_type ) ) {
		return false;
	}

	if ( is_numeric( $form_type ) ) {
		$form_type = tve_leads_get_form_type( $form_type );
	}

	if ( is_object( $form_type ) ) {
		if ( empty( $form_type->tve_form_type ) ) {
			return false;
		}
		$form_type = $form_type->tve_form_type;
	}

	if ( empty( $form_type ) ) {
		return false;
	}

	return in_array( $form_type, array(
		'lightbox',
		'screen_filler'
	) );
}

/**
 * returns whether or not a form_type allows trigger settings
 * currently, trigger settings are enabled for all form types, except for the Scroll Mat form type
 *
 * @param int|WP_Post $form_type
 *
 * @return bool
 */
function tve_leads_form_type_has_trigger_settings( $form_type ) {
	if ( is_null( $form_type ) ) {
		return false;
	}

	if ( is_numeric( $form_type ) ) {
		$form_type = tve_leads_get_form_type( $form_type );
	}

	if ( is_object( $form_type ) ) {
		if ( empty( $form_type->tve_form_type ) ) {
			return false;
		}
		$form_type = $form_type->tve_form_type;
	}

	if ( empty( $form_type ) ) {
		return false;
	}

	return ! in_array( $form_type, array(
		'greedy_ribbon'
	) );
}

/**
 * get a human-friendly name for the Display Frequency setup for the $variation
 *
 * @param array $variation
 *
 * @return String
 */
function tve_leads_frequency_nice_name( $variation ) {
	return (int) $variation['display_frequency'] == 0 ?
		__( 'All the time', 'thrive-leads' ) :
		sprintf( _n( 'Every %s day', 'Every %s days', $variation['display_frequency'], 'thrive-leads' ), $variation['display_frequency'] );
}

/**
 * get a human-friendly name for the Position setup for the $variation
 *
 * @param array $variation
 *
 * @return String
 */
function tve_leads_position_nice_name( $variation ) {
	switch ( $variation['position'] ) {
		case 'bot_right':
			return __( 'Bottom Right', 'thrive-leads' );
		case 'bot_left':
			return __( 'Bottom Left', 'thrive-leads' );
		case 'top_left':
			return __( 'Top Left', 'thrive-leads' );
		case 'top_right':
			return __( 'Top Right', 'thrive-leads' );
		case 'top':
			return __( 'Top', 'thrive-leads' );
		case 'bottom':
			return __( 'Bottom', 'thrive-leads' );
		default:
			if ( is_numeric( $variation['position'] ) ) {
				if ( $variation['position'] == '0' ) {
					return __( 'At the beginning of the post', 'thrive-leads' );
				} else if ( $variation['position'] == '1' ) {
					return __( 'After the first paragraph', 'thrive-leads' );
				} else {
					return __( 'After ' . $variation['position'] . ' paragraphs', 'thrive-leads' );
				}

			} else {
				return $variation['position'];
			}
	}

}

/**
 * get a human-friendly name for the Display Animation setup for the $variation
 *
 * @param array $variation
 *
 * @return String
 */
function tve_leads_animation_nice_name( $variation ) {
	$animation = TVE_Leads_Animation_Abstract::factory( $variation['display_animation'] );

	return $animation == null ? $variation['display_animation'] : $animation->get_title();
}

/**
 * get the default display frequency based on $form_type
 *
 * @param string $form_type
 *
 * @return int
 */
function tve_leads_get_default_display_frequency( $form_type ) {
	switch ( $form_type ) {
		case 'lightbox':
		case 'screen_filler':
		case 'slide_in':
			return 7;
		default:
			return 0;
	}
}

/**
 * get the default display frequency based on $form_type
 *
 * @param string $form_type
 *
 * @return int
 */
function tve_leads_get_default_position( $form_type ) {
	switch ( $form_type ) {
		case 'slide_in':
			return 'bot_right';
		case 'ribbon':
			return 'top';
		case 'in_content':
			return 2;
		default:
			return 'bot_right';
	}
}


/**
 * get the default display animation based on $form_type
 *
 * @param string $form_type
 *
 * @return int
 */
function tve_leads_get_default_animation( $form_type ) {
	switch ( $form_type ) {
		case 'ribbon':
			return TVE_Leads_Animation_Abstract::ANIM_SLIDE_IN_TOP;
			break;
		case 'lightbox':
		case 'screen_filler':
			return TVE_Leads_Animation_Abstract::ANIM_ZOOM_IN;
			break;
		case 'widget':
		case 'post_footer':
		case 'in_content':
		case 'shortcode':
			return TVE_Leads_Animation_Abstract::ANIM_INSTANT;
			break;
		case 'slide_in':
			return TVE_Leads_Animation_Abstract::ANIM_SLIDE_IN_RIGHT;
			break;
		default:
			return TVE_Leads_Animation_Abstract::ANIM_INSTANT;
	}
}

/**
 * wrapper over the wp_enqueue_script function
 * it will add the plugin version to the script source if no version is specified
 *
 * @param $handle
 * @param string $src
 * @param array $deps
 * @param bool $ver
 * @param bool $in_footer
 */
function tve_leads_enqueue_script( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
	if ( $ver === false ) {
		$ver = TVE_LEADS_VERSION;
	}

	if ( defined( 'TVE_DEBUG' ) && TVE_DEBUG ) {
		$src = str_replace( '/js-min/', '/js/', $src );
		$src = preg_replace( '#\.min\.js$#', '.js', $src );
	}

	wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

/**
 * wrapper over the wp_enqueue_style function
 * it will add the plugin version to the style link if no version is specified
 *
 * @param $handle
 * @param string $src
 * @param array $deps
 * @param bool|string $ver
 * @param string $media
 */
function tve_leads_enqueue_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
	if ( $ver === false ) {
		$ver = TVE_LEADS_VERSION;
	}
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}


/**
 *
 * gets the nice name for a form type
 *
 * @param WP_Post|int|string $form_type can either be a WP_Post object, a form type ID or a string in the form of 'ribbon' for example
 *
 * @return string the nice post name
 */
function tve_leads_get_form_type_name( $form_type ) {
	if ( is_numeric( $form_type ) || $form_type instanceof WP_Post ) {
		$form_type = get_post_meta( $form_type, 'tve_form_type', true );
	}

	if ( ! is_string( $form_type ) ) {
		return '';
	}

	$all = tve_leads_get_default_form_types( true );

	if ( ! isset( $all[ $form_type ] ) ) {
		return '';
	}

	return $all[ $form_type ]['post_title'];
}

/**
 *
 * get the equivalent test type based on post type of the form
 *
 * @param string $post_type
 *
 * @return string the test_type constant
 */
function tve_leads_get_test_type_from_post_type( $post_type ) {
	return $post_type == TVE_LEADS_POST_FORM_TYPE ? TVE_LEADS_VARIATION_TEST_TYPE :
		( $post_type == TVE_LEADS_POST_SHORTCODE_TYPE ? TVE_LEADS_SHORTCODE_TEST_TYPE :
			( $post_type == TVE_LEADS_POST_TWO_STEP_LIGHTBOX ? TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE : TVE_LEADS_GROUP_TEST_TYPE ) );
}

/**
 * wrapper over the wp get_option function - it appends the tve_leads_ prefix to the option name
 *
 * @param $name
 * @param bool $default
 *
 * @return mixed|void
 */
function tve_leads_get_option( $name, $default = false ) {
	$name  = 'tve_leads_' . preg_replace( '/^tve_leads_/', '', $name );
	$value = get_option( $name, $default );
	if ( $name == 'tve_leads_ajax_load' ) {
		return (int) $value;
	}

	return $value;
}

/**
 * gets the page data - used to build the the breadcrumbs
 * @return mixed
 */
function tve_leads_get_screen_data() {
	$current_screen = get_current_screen();
	if ( strpos( $current_screen->base, 'toplevel_page_' ) !== false ) {
		$url = "#dashboard";
	} else {
		$path = str_replace( 'thrive-leads_page_', '', $current_screen->base );
		$url  = menu_page_url( $path, false );
	}

	$screen_data[0]['name'] = "Thrive Dashboard";
	$screen_data[0]['url']  = menu_page_url( "tve_dash_section", false );
	$screen_data[1]['name'] = "Thrive Leads";
	$screen_data[1]['url']  = menu_page_url( "thrive_leads_dashboard", false );
	$screen_data[2]['name'] = str_replace( 'Thrive Leads ', '', get_admin_page_title() );
	$screen_data[2]['url']  = $url;
	$screen_data[2]['last'] = 1;

	return $screen_data;
}

/**
 * wrapper over the wp update_option() function
 * it ensures the option is prefixed with "tve_leads_" prefix
 *
 * @param string $name
 * @param mixed $value
 *
 * @return bool
 */
function tve_leads_update_option( $name, $value ) {
	$name = 'tve_leads_' . preg_replace( '/^tve_leads_/', '', $name );

	return update_option( $name, $value );
}

/**
 * get all form types that are to be shown on a page based on the $lead_group
 *
 * @param WP_Post $lead_group
 * @param bool $skip_group_tests whether or not to take into account any tests that are running at group level
 *
 * @return array
 */
function tve_leads_get_targeted_form_types( $lead_group, $skip_group_tests = false ) {
	$form_types_to_be_shown = array();

	$group_level_tests = $skip_group_tests ? array() : tve_leads_get_group_active_tests( $lead_group->ID );

	/* gather first all the available form types */
	foreach ( $lead_group->form_types as $form_type ) {
		if ( is_array( $form_type ) || ! $form_type->ID ) {
			continue;
		}
		$display_on_mobile = get_post_meta( $form_type->ID, 'display_on_mobile', true );
		$display_on_mobile = (string) $display_on_mobile === '' ? 1 : intval( $display_on_mobile );
		if ( wp_is_mobile() && $display_on_mobile === 0 ) {
			continue;
		}

		$display_status = get_post_meta( $form_type->ID, 'display_status', true );
		$display_status = (string) $display_status === '' ? 1 : intval( $display_status );
		/* since 05.09.2016 display status is display on desktop so we just check if this is not a mobile */
		if ( ! wp_is_mobile() && $display_status === 0 ) {
			continue;
		}
		/**
		 * eliminate screenfillers, lightboxes, ribbons and slideins from the customize preview screens
		 */
		if ( tve_leads_is_customize_preview() && in_array( $form_type->tve_form_type, array( 'lightbox', 'screen_filler', 'ribbon', 'slide_in' ) ) ) {
			continue;
		}
		$form_types_to_be_shown[ $form_type->ID ] = $form_type;
	}
	/* bail early if no form type has been found */
	if ( empty( $form_types_to_be_shown ) ) {
		return array();
	}

	if ( empty( $group_level_tests ) ) {
		/* we need to show all form types, so there's nothing to be done here */

	} else {
		/* take every form_type that's not included in a test and ALL OTHER form types */
		foreach ( $group_level_tests as $test ) {
			$random_index = tve_leads_get_random_index( count( $test->item_ids ) );
			foreach ( $test->item_ids as $index => $_id ) {
				/* remove all other form types that are included in the test and have a different index in the test_items array */
				if ( $random_index != $index ) {
					unset( $form_types_to_be_shown[ $_id ] );
					continue;
				}
				if ( ! isset( $form_types_to_be_shown[ $_id ] ) ) {
					continue;
				}
				$form_types_to_be_shown[ $_id ]->group_level_test = $test;
			}
		}
	}

	return $form_types_to_be_shown;
}

/**
 * output inline setcookies using inline <script>s
 *
 * @param array $cookies
 *
 * @return string the inline javascript
 */
function tve_leads_inline_cookies( $cookies ) {
	if ( empty( $cookies ) ) {
		return '';
	}
	$output = '<script type="text/javascript">var _now = new Date(), sExpires;';
	foreach ( $cookies as $key => $cookie ) {
		$output .= '_now.setTime(_now.getTime() + (' . intval( $cookie['expires'] ) . ' * 24 * 3600 * 1000));sExpires = _now.toUTCString();';
		$output .= "document.cookie=encodeURIComponent(" . json_encode( $key ) . ")+'='+encodeURIComponent(" . json_encode( $cookie['value'] ) . ")+';expires='+sExpires+';path=/';";
	}
	$output .= '</script>';

	return $output;
}

/**
 * output the JS code required for a trigger associated with a variation
 *
 * @param array $variation
 * @param string $form_id
 * @param string $form_type
 */
function tve_leads_output_trigger_js( $variation, $form_id, $form_type ) {
	$trigger = TVE_Leads_Trigger_Abstract::factory( $variation['trigger'], $variation['trigger_config'] );
	if ( is_null( $trigger ) ) {
		return;
	}
	$trigger->output_js( array(
		'form_id'   => 'tve-leads-track-' . $form_id,
		'form_type' => $form_type
	) );
}

/**
 * determine which form variation to display from a $form_type
 * takes into account also the (possible) running tests between variations from the form type
 *
 * if a test is running and the user has visited the page before, we need to show him the same form
 * we do this by dropping a cookie with the generated variation index from the ones included in the test
 *
 * also, if we detect a mobile browser (using wp_is_mobile) and the user did not setup the form to be displayed on mobile, then the result will be empty
 *
 * for some forms, there is a display_frequency setting which prevents the form for displaying for a set amount of time (days)
 * this is also solved with a cookie, and also handled here
 *
 * @param WP_Post $form_type
 * @param bool $skip_cookie_check whether or not to skip the cookie check in case of an active test running
 *
 * @return array|null the form variation or empty for failure
 */
function tve_leads_determine_variation( $form_type, $skip_cookie_check = false ) {
	$_type      = $form_type->tve_form_type;
	$test_model = ! empty( $form_type->group_level_test ) ? $form_type->group_level_test : false;
	/**
	 * IF there is no active test at group level -> take into account tests at form type level
	 *
	 * If there is a test running at variation level, only get active test items for that test
	 */
	if ( empty( $test_model ) ) {
		$variation_active_test = tve_leads_get_form_active_test( $form_type->ID, array( 'test_type' => null, 'get_items' => false ) );
	}

	$variations = tve_leads_get_form_variations( $form_type->ID, array(
		'tracking_data'      => false,
		'post_status'        => TVE_LEADS_STATUS_PUBLISH,
		'active_for_test_id' => ! empty( $variation_active_test ) && ! empty( $variation_active_test->id ) ? $variation_active_test->id : false,
	) );

	/* no active variation, nothing to do here */
	if ( empty( $variations ) ) {
		return array();
	}

	$cookies_to_set = array();
	/* if there's a test running at form type level and no test at group level -> choose a random form variation */
	if ( empty( $test_model ) && ! empty( $variation_active_test ) ) {
		$test_model = $variation_active_test;

		if ( ! $skip_cookie_check ) {
			/**
			 * if there's a previous cookie key setup for this variation and a test is running, then we should show the same variation to the user
			 */
			$same_variation_key = 't_' . $test_model->id . '_f_' . $form_type->ID;
			if ( isset( $_COOKIE[ $same_variation_key ] ) && isset( $variations[ $_COOKIE[ $same_variation_key ] ] ) ) {
				$variationIndex = $_COOKIE[ $same_variation_key ];
			} else {
				$variationIndex = tve_leads_get_random_index( count( $variations ) );
			}
			if ( ! headers_sent() ) {
				setcookie( $same_variation_key, $variationIndex, time() + 3600 * 30, '/' );
			} else {
				$cookies_to_set[ $same_variation_key ] = array(
					'value'   => $variationIndex,
					'expires' => 30
				);
			}
		} else {
			$variationIndex = tve_leads_get_random_index( count( $variations ) );
		}
	} else {
		/* else always choose the first published variation = the control */
		$variationIndex = 0;
	}

	//just in case it does not find that index of variation
	$variation = isset( $variations[ $variationIndex ] ) ? $variations[ $variationIndex ] : $variations[0];
	/* some sanity checks - check if we have content / templates for the variation */
	if ( empty( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) || ! ( $config = tve_leads_get_editor_template_config( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ) ) {
		return array();
	}

	/**
	 * if the user has setup a display frequency (in days) for this particular form, we need to check if this form has been displayed
	 * in the past x days on this browser. We do this by checking the value of a cookie.
	 */
	if ( $variation['display_frequency'] && tve_leads_form_type_has_frequency_settings( $_type ) ) {

		$cookie_key = 'tlf_' . $variation['key'];
		/**
		 * if the cookie is set, then there's nothing more to do here
		 */
		if ( isset( $_COOKIE[ $cookie_key ] ) ) {
			unset( $GLOBALS['tve_lead_forms'][ $_type ] );

			return array();
		}

		/**
		 * otherwise, set the cookie to expire after the number of days setup for the form
		 */
		if ( ! headers_sent() ) {
			setcookie( $cookie_key, 1, time() + ( 3600 * 24 * (int) $variation['display_frequency'] ), '/' );
		} else {
			$cookies_to_set[ $cookie_key ] = array(
				'value'   => 1,
				'expires' => (int) $variation['display_frequency']
			);
		}
	}


	$variation['test_model']  = $test_model;
	$variation['set_cookies'] = $cookies_to_set;

	return $variation;
}

/**
 * set a cookie (flag) that marks just that a user converted a form from $main_group_id
 * another cookie is set containing also the email address, that's a separate one, and allows us to track multiple conversions from the same browser
 * (if the emails are different)
 *
 * @param int $main_group_id the id for the main group
 */
function tve_leads_set_conversion_cookie( $main_group_id ) {
	setcookie( 'tl_conversion_' . $main_group_id, '1', time() + ( 365 * 24 * 3600 ), '/' );
}

/**
 * Check if a user has converted on a form from a lead group / shortcode / 2-step
 *
 * @param int $main_group_id the id for the main group
 *
 * @return bool
 */
function tve_leads_check_conversion_cookie( $main_group_id ) {
	return isset( $_COOKIE[ 'tl_conversion_' . $main_group_id ] );
}

/**
 * check if we are previewing a TL form
 */
function tve_leads_is_preview_page() {
	$post_type = get_post_type( get_the_ID() );
	if ( ! in_array( $post_type, array( TVE_LEADS_POST_FORM_TYPE, TVE_LEADS_POST_TWO_STEP_LIGHTBOX, TVE_LEADS_POST_SHORTCODE_TYPE ) ) ) {
		return false;
	}

	global $variation;
	if ( empty( $variation ) ) {
		return false;
	}

	return true;
}

/**
 * check if we are editing a TL form
 */
function tve_leads_is_editor_page() {
	$post_type = get_post_type( get_the_ID() );
	if ( ! in_array( $post_type, array( TVE_LEADS_POST_FORM_TYPE, TVE_LEADS_POST_TWO_STEP_LIGHTBOX, TVE_LEADS_POST_SHORTCODE_TYPE ) ) ) {
		return false;
	}

	global $variation;
	if ( empty( $variation ) ) {
		return false;
	}

	return isset( $_GET[ TVE_EDITOR_FLAG ] );
}

/**
 * check if a conversion has been registered for this variation and, if so, we need to check if there is an "Already subscribed" state defined and show that instead
 *
 * @param array $variation the main variation where the "Already Subscribed" state should have been setup
 * @param string $type type of form
 * @param bool $skip_inbound_link_check whether or not to skip the check for 'already_subscribed' state from the inbound link params
 *
 * @return string
 */
function tve_leads_get_already_subscribed_html( $variation, $type, $skip_inbound_link_check = false ) {
	$show_already_subscribed = $skip_inbound_link_check ? false : tve_leads_force_subscribed_state();

	if ( empty( $variation ) || ! empty( $variation['parent_id'] ) || ( ! isset( $_COOKIE[ 'tl-conv-' . $variation['key'] ] ) && ! $show_already_subscribed ) ) {
		return '';
	}

	$done = tve_leads_get_already_subscribed_state( $variation );

	if ( empty( $done ) ) {
		return '';
	}

	/* Return empty when the content is hidden */
	if ( tve_leads_check_variation_visibility( $done ) ) {
		return TVE_ALREADY_SUBSCRIBED_HIDDEN;
	}

	/**
	 * enqueue the scripts / styles for the already subscribed state
	 */
	tve_leads_enqueue_variation_scripts( $done );

	$ajax_load_forms = tve_leads_get_option( 'ajax_load' );
	if ( $ajax_load_forms && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$GLOBALS['tve_leads_subscribed_states']   = isset( $GLOBALS['tve_leads_subscribed_states'] ) ? $GLOBALS['tve_leads_subscribed_states'] : array();
		$GLOBALS['tve_leads_subscribed_states'][] = $done;
		remove_filter( 'tve_leads_append_states_ajax', 'tve_leads_ajax_already_subscribed_state' );
		add_filter( 'tve_leads_append_states_ajax', 'tve_leads_ajax_already_subscribed_state' );
	}

	$fn = 'tve_leads_display_form_' . $type;

	return $fn( '__return_content', tve_editor_custom_content( $done ), $done, array(
		'wrap' => false,
	) );
}

/**
 * append any possible already_subscribed state to the list of variations that are to be processed and returned when lazy-loading is enabled
 *
 * @param array $output_variations
 *
 * @return array
 */
function tve_leads_ajax_already_subscribed_state( $output_variations ) {
	if ( empty( $GLOBALS['tve_leads_subscribed_states'] ) ) {
		return $output_variations;
	}

	if ( empty( $output_variations ) || ! is_array( $output_variations ) ) {
		$output_variations = array();
	}

	$output_variations = array_merge( $output_variations, $GLOBALS['tve_leads_subscribed_states'] );

	return $output_variations;
}

/**
 * return the html for a variation state
 * used in all form types except for lightboxes
 *
 * @param string $form_html
 * @param array $variation
 * @param array $control used to control pieces of html
 *
 * @return string
 */
function tve_leads_state_html( $form_html, $variation, $control ) {
	return sprintf(
		'<div%s class="tl-style" id="%s" data-state="%s">%s</div>',
		! empty( $control['hide'] ) ? ' style="display:none"' : '',
		'tve_' . tve_leads_get_variation_style_key( $variation ),
		$variation['key'],
		$form_html
	);
}

/**
 *
 * gets the CSS style key needed for a variation, based on the template
 *
 * @param array $variation
 *
 * @return string the style key
 */
function tve_leads_get_variation_style_key( & $variation ) {
	if ( ! isset( $variation['style_key'] ) ) {
		$parts                  = explode( '|', $variation[ TVE_LEADS_FIELD_TEMPLATE ] );
		$key                    = end( $parts );
		$variation['style_key'] = preg_replace( '#_v(.+)$#', '', $key );;
	}

	return $variation['style_key'];
}

/**
 * WP wrapper / shim over the WP is_customize_preview function (if available)
 * @return bool
 */
function tve_leads_is_customize_preview() {
	if ( function_exists( 'is_customize_preview' ) ) {
		return is_customize_preview();
	}

	global $wp_customize;

	return is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview();
}

/**
 * main entry-point for displaying a PHP_INSERT form type
 * users are able to use this php code to insert a form anywhere in the site
 *
 * @param string $main_group_id
 * @param string $form_type_or_shortcode_id
 */
function tve_leads_form_display( $main_group_id = null, $form_type_or_shortcode_id = null ) {
	if ( empty( $main_group_id ) && empty( $form_type_or_shortcode_id ) ) {
		echo __( 'There is a problem with this PHP insert: missing both parameters', 'thrive-leads' );

		return;
	}

	/**
	 * if ths form is a PHP insert and the main group is not targeted, bail early
	 */
	if ( ! empty( $main_group_id ) ) {
		global $tve_lead_group;
		if ( empty( $tve_lead_group ) || $tve_lead_group->ID != $main_group_id ) {
			return;
		}
	}

	$form_type = tve_leads_get_form_type( $form_type_or_shortcode_id, array( 'get_variations' => false ) );

	switch ( $form_type->tve_form_type ) {
		case 'shortcode':
			echo tve_leads_shortcode_render( array( 'id' => $form_type_or_shortcode_id ) );
			break;
		case 'php_insert':
			if ( empty( $GLOBALS['tve_lead_forms']['php_insert'] ) ) {
				return;
			}
			echo tve_leads_display_form_php_insert();
			break;
	}

}

/**
 * Checks to see if the current screen represents a page, post, blog, index, front and so on.
 * Returns an array with the ID and type.
 * @return array
 */
function tve_get_current_screen() {
	$ID = get_the_ID();
	if ( is_front_page() ) {
		$data = array(
			'screen_type' => TVE_SCREEN_HOMEPAGE,
			'screen_id'   => 0
		);
	} else if ( is_home() ) {
		$data = array(
			'screen_type' => TVE_SCREEN_BLOG,
			'screen_id'   => 0
		);
	} else if ( is_singular() ) {
		switch ( get_post_type( $ID ) ) {
			case 'post':
				$type = TVE_SCREEN_POST;
				break;
			case 'page':
				$type = TVE_SCREEN_PAGE;
				break;
			default:
				$type = TVE_SCREEN_CUSTOM_POST;
		}
		$data = array(
			'screen_type' => $type,
			'screen_id'   => $ID
		);
	} else if ( is_archive() ) {
		$data = array(
			'screen_type' => TVE_SCREEN_ARCHIVE,
			'screen_id'   => 0
		);
	} else {
		$data = array(
			'screen_type' => TVE_SCREEN_OTHER,
			'screen_id'   => 0
		);
	}

	return $data;
}


/**
 * Returns readable information for reporting table of the screen from where the log was made
 * @see tve_get_current_screen()
 *
 * @param $screen_type
 * @param $screen_id
 *
 * @return array Array with url, type and name of the page
 */
function tve_get_current_screen_for_reporting_table( $screen_type, $screen_id ) {
	global $wp_rewrite;
	$wp_rewrite = new WP_Rewrite();
	switch ( $screen_type ) {
		case TVE_SCREEN_HOMEPAGE:
			return array(
				get_home_url(),
				__( 'Homepage', 'thrive-leads' ),
				__( 'Homepage', 'thrive-leads' )
			);
			break;
		case TVE_SCREEN_BLOG:
			return array(
				get_permalink( get_option( 'page_for_posts' ) ),
				__( 'Blog', 'thrive-leads' ),
				__( 'Blog', 'thrive-leads' )
			);
			break;
		case TVE_SCREEN_POST:
		case TVE_SCREEN_PAGE:
		case TVE_SCREEN_CUSTOM_POST:
			return array(
				get_permalink( $screen_id ),
				get_post_type( $screen_id ) ? get_post_type( $screen_id ) : '',
				get_the_title( $screen_id ),
			);
			break;
		case TVE_SCREEN_ARCHIVE:
			return array(
				'',
				__( 'Archive', 'thrive-leads' ),
				__( 'Archive', 'thrive-leads' )
			);
			break;
		case TVE_SCREEN_OTHER:
			return array(
				'',
				__( 'Other', 'thrive-leads' ),
				__( 'Other', 'thrive-leads' )
			);
			break;
		default:
			return array(
				'',
				__( 'Unknown', 'thrive-leads' ),
				__( 'Unknown', 'thrive-leads' )
			);
	}
}

/**
 * Return an array with all the fields that we want to ignore when storing contacts in the database.
 * Email is already stored in another variable so we don't need it again
 * @return array
 */
function tve_get_lead_generation_ignored_fields() {
	return array(
		'email',
		'_captcha_size',
		'_captcha_theme',
		'_captcha_type',
		'_submit_option',
		'_use_captcha',
		'g-recaptcha-response',
		'__tcb_lg_fc',
		'__tcb_lg_msg',
		'_state',
		'_form_type',
		'_error_message_option',
		'_back_url',
		'_submit_option',
		'url',
		'_asset_group',
		'_asset_option',
		'mailchimp_optin'
	);
}

/**
 * check if there is a cookie set that forces the "Already Subscribed" state to be displayed.
 * this can only come from the inbound link functionality
 *
 * @return bool
 */
function tve_leads_force_subscribed_state() {
	global $tve_lead_group;
	if ( empty( $tve_lead_group ) ) {
		return false;
	}
	$group_cookie_name = 'tl_inbound_link_params_' . $tve_lead_group->ID;

	$show_already_subscribed = false;
	if ( isset( $_COOKIE[ $group_cookie_name ] ) ) {
		$inbound_link_params     = unserialize( stripslashes( $_COOKIE[ $group_cookie_name ] ) );
		$show_already_subscribed = ! empty( $inbound_link_params['tl_form_type'] );
	}

	return $show_already_subscribed;
}

/**
 * Check if the current state of the variation is visible
 *
 * @param array $variation
 *
 * @return bool
 */
function tve_leads_check_variation_visibility( $variation = array() ) {
	return isset( $variation[ TVE_LEADS_FIELD_STATE_VISIBILITY ] ) && empty( $variation[ TVE_LEADS_FIELD_STATE_VISIBILITY ] );
}

/**
 * get a reliable timezone string, even if it's set to a manual time difference (UTC+/-)
 *
 * @return string
 */
function tve_get_timezone_string() {
	// if site timezone string exists, return it
	if ( $timezone = get_option( 'timezone_string' ) ) {
		return $timezone;
	}

	// get UTC offset, if it isn't set then return UTC
	if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
		return 'UTC';
	}

	// adjust UTC offset from hours to seconds
	$utc_offset *= 3600;

	// attempt to guess the timezone string from the UTC offset
	if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
		return $timezone;
	}

	// last try, guess timezone string manually
	$is_dst = date( 'I' );

	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
				return $city['timezone_id'];
			}
		}
	}

	// fallback to UTC
	return 'UTC';
}

/**
 * get a list of all templates downloaded from the cloud based on form type
 *
 * @param string $form_type
 *
 * @return array
 */
function tve_leads_get_downloaded_templates( $form_type ) {
	return get_option( 'tve_leads_' . $form_type . '_downloaded_templates', array() );
}

/**
 * save the list of downloaded templates into the wp_option used for these
 *
 * @param string $form_type
 * @param array $templates
 */
function tve_leads_save_downloaded_templates( $form_type, $templates ) {
	update_option( 'tve_leads_' . $form_type . '_downloaded_templates', $templates );
}

function tve_leads_is_cloud_template( $form_type, $template ) {

	if ( strpos( $template, '|' ) !== false ) {
		$template = substr( $template, strpos( $template, '|' ) + 1 );
	}

	$cloud = tve_leads_get_downloaded_templates( $form_type, $template );

	return ! empty( $cloud[ $template ] );
}

/**
 * Displays notices of inconclusive tests
 *
 * @param $id
 */
function tve_inconclusive_tests_notice() {
	$running_tests = tve_get_running_inconclusive_tests();

	$inconclusive_tests_option = get_option( 'tve_inconclusive_tests' );
	$inconclusive_tests        = array();
	if ( ! is_array( $inconclusive_tests_option ) ) {
		$inconclusive_tests = explode( ',', get_option( 'tve_inconclusive_tests' ) );
	}
	foreach ( $running_tests as $tests ) {
		$id = $tests->id;

		if ( ! in_array( $id, $inconclusive_tests ) ) {
			?>
			<div data-test-id="<?php echo $id; ?>" class="notice-error notice tve_error_inconclusive_test_notice is-dismissible">
				<p><?php echo __( 'One of your active A/B tests in Thrive Leads appears to be inconclusive. The test has reached double the threshold time and conversion numbers, but no clear winner has been found. <a href="javascript:void(0);" onclick="ThriveLeadsInconclusive.inconclusive_tests.trigger_dismiss_notice(' . $id . ')">Click here to view the test</a>.', THO_TRANSLATE_DOMAIN ); ?></p>
			</div>
			<?php
		}
	}
}