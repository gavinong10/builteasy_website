<?php
/**
 * this file handles known compatibility issues with other plugins / themes
 */

/**
 * general admin conflict notifications
 */
add_action( 'admin_notices', 'tve_admin_notices' );

/**
 * filter for including wp affiliates scripts and styles if the shortcode is found in TCB content
 */
add_filter( 'affwp_force_frontend_scripts', 'tve_compat_wp_affiliate_scripts' );

add_filter( 'fp5_filter_has_shortcode', 'tve_compat_flowplayer5_has_shortcode' );

/**
 *
 * Compatibility with S2Member plugin - it fails to include CSS / JS on pages / posts created with TCB
 */
add_filter( 'ws_plugin__s2member_lazy_load_css_js', '__return_true' );

/**
 *
 * Compatibility with Survey Funnel plugin - it fails to include CSS / JS on pages / posts created with TCB
 */
if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'surveyfunnel/survey_funnel.php' ) ) {
	add_action( 'wp_enqueue_scripts', 'tve_compat_survey_funnel', 11 );
}

/**
 * Compatibility with Total Themes.
 */
if ( isset( $_GET['tve'] ) && 'true' == $_GET['tve'] ) {
	add_filter( 'wpex_toggle_bar_active', '__return_false' );
}

/**
 * Checks if a post / page has a shortcode in TCB content
 *
 * @param string                  $shortcode
 * @param int|string|null|WP_Post $post_id
 * @param bool                    $use_wp_shortcode_check whether or not to use has_shortcode() or strpos
 *
 * @return bool
 */
function tve_compat_has_shortcode( $shortcode, $post_id = null, $use_wp_shortcode_check = false ) {
	if ( is_null( $post_id ) ) {
		$post_id = get_the_ID();
	} else {
		$post_id = is_a( $post_id, 'WP_Post' ) ? $post_id->ID : $post_id;
	}
	$content = tve_get_post_meta( $post_id, 'tve_updated_post' );
	if ( ! $use_wp_shortcode_check ) {
		return strpos( $content, $shortcode ) !== false;
	}
	if ( $post_id ) {
		return has_shortcode( $content, '[' . str_replace( array( '[', ']' ), '', $shortcode ) . ']' );
	}

	return false;
}

/**
 * display any possible conflicts with other plugins / themes as error notification in the admin panel
 */
function tve_admin_notices() {
	$has_wp_seo_conflict = tve_has_wordpress_seo_conflict();

	if ( $has_wp_seo_conflict ) {
		$link    = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wpseo_advanced&tab=permalinks' ), __( 'Wordpress SEO settings', 'thrive-cb' ) );
		$message = sprintf( __( 'Thrive Content Builder and Thrive Leads cannot work with the current configuration of Wordpress SEO. Please go to %s and disable the %s"Redirect ugly URL\'s to clean permalinks"%s option', 'thrive-cb' ), $link, '<strong>', '</strong>' );
		echo sprintf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
	}
}

/**
 * check if the user has a known "Coming soon" or "Membership protection" plugin installed
 * our landing pages seem to overwrite their "Coming soon" functionality
 * this would check for any coming soon plugins that use the template_redirect hook
 */
function tve_hooked_in_template_redirect() {
	include_once ABSPATH . '/wp-admin/includes/plugin.php';

	$hooked_in_template_redirect = array(
		'wishlist-member/wpm.php',
		'ultimate-coming-soon-page/ultimate-coming-soon-page.php',
		'easy-pie-coming-soon/easy-pie-coming-soon.php',
		'coming-soon-page/coming_soon.php',
		'cc-coming-soon/cc-coming-soon.php',
		'wordpress-seo/wp-seo.php',
		'wordpress-seo-premium/wp-seo-premium.php',
		'membermouse/index.php',
		'ultimate-member/index.php',
	);

	foreach ( $hooked_in_template_redirect as $plugin ) {
		if ( is_plugin_active( $plugin ) ) {
			return true;
		}
	}

	/**
	 * SUPP-1749 if the domain mapping plugin is installed, Landing Pages will not be redirected to the corresponding domain. This ensures that the redirection will take place
	 */
	if ( is_plugin_active( 'wordpress-mu-domain-mapping/domain_mapping.php' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if the user has the Wordpress SEO plugin installed and the "Redirect to clean URLs" option checked
 *
 * @return bool
 */
function tve_has_wordpress_seo_conflict() {
	return is_plugin_active( 'wordpress-seo/wp-seo.php' ) && ( $wpseo_options = get_option( 'wpseo_permalinks' ) ) && ! empty( $wpseo_options['cleanpermalinks'] );
}


/**
 * called inside the 'init' hook
 *
 * this is used to fix any plugin conflicts that might appear
 *
 * 1. YARPP - we need to disable their the_content filter when in editing mode,
 *      - they apply the_content filter automatically when querying the database for related posts
 *      - they have a filter for blacklisting a filters the_content, but that does not solve the issue - wp will never call our filter anymore
 *
 * 2. TheRetailer theme - they remove the WP media js files for some reason (??)
 */
function tve_fix_plugin_conflicts() {
	global $yarpp;
	if ( is_editor_page_raw() ) {
		if ( $yarpp ) {
			remove_filter( 'the_content', array( $yarpp, 'the_content' ), 1200 );
		}
		/**
		 * Theretailer theme deregisters the mediaelement for some reason
		 */
		if ( function_exists( 'theretailer_deregister' ) ) {
			remove_action( 'wp_enqueue_scripts', 'theretailer_deregister' );
		}
	}
}

/**
 * apply some of currently known 3rd party filters to the TCB saved_content
 *
 * Digital Access Pass: dap_*
 *
 * @param string $content
 *
 * @return string
 */
function tve_compat_content_filters_before_shortcode( $content ) {
	/**
	 * Digital Access Pass %% links in the content, e.g.: %%LOGIN_FORM%%
	 */
	if ( function_exists( 'dap_login' ) ) {
		$content = dap_login( $content );
	}

	if ( function_exists( 'dap_personalize' ) ) {
		$content = dap_personalize( $content );
	}

	if ( function_exists( 'dap_personalize_error' ) ) {
		$content = dap_personalize_error( $content );
	}

	if ( function_exists( 'dap_product_links' ) ) {
		$content = dap_product_links( $content );
	}

	/**
	 * s3 amazon links - they don't handle shortcodes in the "WP" way
	 */
	if ( function_exists( 's3mv' ) ) {
		$content = s3mv( $content );
	}

	/**
	 * FD Footnotes plugin does not have standard shortcodes
	 */
	if ( function_exists( 'fdfootnote_convert' ) ) {
		$content = fdfootnote_convert( $content );
	}

	if ( function_exists( 'ec' ) ) {
		$content = ec( $content );
	}

	/**
	 * A3 Lazy Load plugin
	 * This plugin adds a filter on "the_content" inside of "wp" action callback -> the same as TCB does
	 * Its "the_content" filter callback is executed first because of its name -> A3
	 * We call its filter implementation on TCB content
	 */
	if ( class_exists( 'A3_Lazy_Load' ) && method_exists( 'A3_Lazy_Load', 'filter_content_images' ) ) {
		global $a3_lazy_load_global_settings;
		if ( $a3_lazy_load_global_settings['a3l_apply_image_to_content'] ) {
			$content = A3_Lazy_Load::filter_content_images( $content );
		}
	}

	/**
	 * EduSearch plugin not handling shortcodes in the "WP" way
	 * they search for [edu-search] strings and process those
	 */
	if ( function_exists( 'esn_filter_content' ) ) {
		$content = esn_filter_content( $content );
	}

	/**
	 * Paid Memberships pro has a really strange way of defining shortcodes
	 */
	if ( function_exists( 'pmpro_wp' ) ) {
		global $post;
		$o_content          = $post->post_content;
		$post->post_content = $content;
		pmpro_wp();
		$post->post_content = $o_content;
	}

	return $content;
}


/**
 * apply some of currently known 3rd party filters to the TCB saved_content - after do_shortcode is being called
 *
 * FormMaker: Form_maker_fornt_end_main
 *
 * @param string $content
 *
 * @return string
 */
function tve_compat_content_filters_after_shortcode( $content ) {
	/**
	 * FormMaker does not use WP shortcode as they should
	 */
	if ( function_exists( 'Form_maker_fornt_end_main' ) ) {
		$content = Form_maker_fornt_end_main( $content );
	}

	/**
	 * in case they will ever correct the function name
	 */
	if ( function_exists( 'Form_maker_front_end_main' ) ) {
		$content = Form_maker_front_end_main( $content );
	}

	return $content;
}

/**
 * check if we are on a page / post and there is a [affiliate_area] shortcode in TCB content
 *
 * @param bool $bool current value
 *
 * @return bool
 */
function tve_compat_wp_affiliate_scripts( $bool ) {
	if ( $bool || ! is_singular() || is_editor_page() ) {
		return $bool;
	}

	$tve_saved_content = tve_get_post_meta( get_the_ID(), 'tve_updated_post' );

	return has_shortcode( $tve_saved_content, 'affiliate_area' ) || has_shortcode( $tve_saved_content, 'affiliate_creatives' );

}

/**
 * checks if the current post is protected by a membership plugin and cannot be displayed
 *
 * @return bool
 */
function tve_membership_plugin_can_display_content() {

	global $post;

	/**
	 * we should not apply this during the_excerpt filter
	 */
	if ( doing_filter( 'get_the_excerpt' ) ) {
		return true;
	}

	/**
	 *
	 * WooCommerce Membership compatibility - hide TCB content for non-members
	 */
	if ( function_exists( 'wc_memberships_is_post_content_restricted' ) && wc_memberships_is_post_content_restricted() && ! doing_filter( 'get_the_excerpt' ) ) {
		if ( ! current_user_can( 'wc_memberships_view_restricted_post_content', $post->ID ) || ! current_user_can( 'wc_memberships_view_delayed_post_content', $post->ID ) ) {
			return false;
		}
	}

	/**
	 * Simple Membership plugin compatibility - hide TCB content for non members
	 */
	if ( class_exists( 'BAccessControl' ) ) {
		$control = SwpmAccessControl::get_instance();

		if ( ! $control->can_i_read_post( $post ) ) {
			return false;
		}
	}

	/**
	 * Paid Memberships Pro plugin
	 */
	if ( function_exists( 'pmpro_has_membership_access' ) ) {
		$has_access = pmpro_has_membership_access();
		if ( ! $has_access ) {
			return false;
		}
	}

	/**
	 * Filter hook that allows plugins to hook into TCB and prevent TCB content from being displayed if e.g. the user does not have access to this content
	 *
	 * @since 1.200.3
	 *
	 * @param bool $can_display
	 */
	return apply_filters( 'tcb_can_display_content', true );

}

/**
 * compatibility with flowplayer 5 shortcodes
 *
 * @param bool $has_shortcode
 */
function tve_compat_flowplayer5_has_shortcode( $has_shortcode ) {
	if ( is_editor_page_raw() ) {
		return $has_shortcode;
	}

	return tve_compat_has_shortcode( 'flowplayer' );
}

/**
 * compatibility with Survey Funnel
 */
function tve_compat_survey_funnel() {
	global $is_survey_page, $post;
	if ( $is_survey_page === true ) {
		return;
	}
	$content_saved   = tve_get_post_meta( $post->ID, 'tve_save_post' );
	$content_updated = tve_get_post_meta( $post->ID, 'tve_updated_post' );

	if ( stristr( $content_saved, '[survey_funnel' ) || stristr( $content_updated, '[survey_funnel' ) ) {
		$is_survey_page = true;
		wp_script_is( 'survey_funnel_ajax' ) || wp_enqueue_script( 'survey_funnel_ajax', SF_PLUGIN_URL . '/js/ajax.js', array( 'jquery' ), '1.0', false );
		wp_script_is( 'survey_funnel' ) || wp_enqueue_script( 'survey_funnel', SF_PLUGIN_URL . '/js/survey_funnel.js', array( 'jquery' ), '1.0', false );
		wp_script_is( 'survey_funnel_fancybox' ) || wp_enqueue_script( 'survey_funnel_fancybox', SF_PLUGIN_URL . '/jquery/fancyBox-2.1.5/source/jquery.fancybox.pack.js', array( 'jquery' ), '1.0', false );

		wp_style_is( 'survey_funnel_styles' ) || wp_enqueue_style( 'survey_funnel_styles', SF_PLUGIN_URL . '/css/styles.css' );
		wp_style_is( 'survey_funnel_client_styles' ) || wp_enqueue_style( 'survey_funnel_client_styles', SF_PLUGIN_URL . '/css/survey_funnel.css' );
		wp_style_is( 'survey_funnel_client_styles_fancybox' ) || wp_enqueue_style( 'survey_funnel_client_styles_fancybox', SF_PLUGIN_URL . '/jquery/fancyBox-2.1.5/source/jquery.fancybox.css' );
	}

}

/**
 * Fix thrive visual editor conflicts before footer
 */
function tve_fix_page_conflicts_before_footer() {

	/**
	 *  For SlickQuiz plugin
	 */
	if ( class_exists( 'SlickQuiz' ) ) {

		remove_filter( 'the_content', 'tve_editor_content', 10 );

		if ( is_editor_page() ) {
			remove_filter( 'the_content', 'tve_editor_content', PHP_INT_MAX );
		}
	}
}

add_action( 'wp_footer', 'tve_fix_page_conflicts_before_footer', 2000 );

/**
 * Remove the content filter for sensei plugin
 */
function tve_wc_sensei_no_content_filter() {

	if ( class_exists( 'Sensei_Course' ) ) {

		if ( ! is_editor_page() ) {
			remove_filter( 'the_content', 'tve_editor_content', 10 );
		}
	}

}

add_action( 'wc_sensei_no_content_filter', 'tve_wc_sensei_no_content_filter', 2000 );