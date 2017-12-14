<?php
/**
 * general functions used all across TCB
 */

/**
 * @return string the URL to the /editor/css/ dir
 */
function tve_editor_css() {
	return tve_editor_url() . '/editor/css';
}

/**
 * @return string the url to the editor/js folder
 */
function tve_editor_js() {
	return tve_editor_url() . '/editor/js';
}

/**
 * return the absolute path to the plugin folder
 *
 * @param string $file
 *
 * @return string
 */
function tve_editor_path( $file = '' ) {
	return plugin_dir_path( dirname( __FILE__ ) ) . ltrim( $file, '/' );
}

/**
 * get all the style families used by TCB
 *
 * @return array
 */
function tve_get_style_families() {
	return apply_filters( 'tcb_style_families', array(
		'Flat'    => tve_editor_css() . '/thrive_flat.css?ver=' . TVE_VERSION,
		'Classy'  => tve_editor_css() . '/thrive_classy.css?ver=' . TVE_VERSION,
		'Minimal' => tve_editor_css() . '/thrive_minimal.css?ver=' . TVE_VERSION
	) );
}

/**
 *
 * @return string the absolute url to the landing page templates folder
 */
function tve_landing_page_template_url() {
	return tve_editor_url() . '/landing-page/templates';
}

/**
 * notice to be displayed if license not validated - going to load the styles inline because there are so few lines and not worth an extra server hit.
 */
function tve_license_notice() {
	include dirname( dirname( __FILE__ ) ) . '/inc/license_notice.php';
}

/**
 * register thrive visual editor global settings
 */
function tve_global_options_init() {
	/* register the "lightbox" post type */
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	register_post_type( 'tcb_lightbox', array(
		'labels'              => array(
			'name'          => __( 'Thrive Lightboxes', "thrive-cb" ),
			'singular_name' => __( 'Thrive Lightbox', 'thrive-cb' ),
			'add_new_item'  => __( 'Add New Thrive Lightbox', 'thrive-cb' ),
			'edit_item'     => __( 'Edit Thrive Lightbox', 'thrive-cb' )
		),
		'exclude_from_search' => true,
		'public'              => true,
		'has_archive'         => false,
		'rewrite'             => false,
		'show_in_nav_menus'   => false,
		'show_in_menu'        => is_plugin_active( 'thrive-visual-editor/thrive-visual-editor.php' )
	) );

	$plugin_db_version = get_option( 'tve_version' );
	if ( ! $plugin_db_version || $plugin_db_version != TVE_VERSION ) {
		tve_run_plugin_upgrade( $plugin_db_version, TVE_VERSION );
		update_option( 'tve_version', TVE_VERSION );
	}

	/**
	 * check and run any database migrations
	 */
	require_once dirname( dirname( __FILE__ ) ) . '/database/Manager.php';
	Thrive_TCB_Database_Manager::check();

}

/**
 * output TCB editor button in the admin area
 */
function tve_admin_button() {
	$post_type      = get_post_type();
	$post_id        = get_the_ID();
	$page_for_posts = get_option( 'page_for_posts' );

	if ( ! tve_is_post_type_editable( $post_type ) || ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( 'page' == $post_type && $page_for_posts && $post_id == $page_for_posts ) {
		echo '<div class="update-nag">' . __( 'This page is setup as a "Blog Page" from Wordpress Reading Settings. Thrive Content Builder cannot be loaded in such instances.', "thrive-cb" ) . '</div>';

		return;
	}

	$url = tcb_get_editor_url( get_the_ID() );
	echo '<br/><a class="button" href="' . $url . '" id="thrive_preview_button" target="_blank"><span class="thrive-adminbar-icon"></span>' . __( "Edit with Thrive Content Builder", 'thrive-cb' ) . '</a><br/><br/>';
	?>
	<style type="text/css">
		.thrive-adminbar-icon {
			background: url('<?php echo tve_editor_css(); ?>/images/admin-bar-logo.png') no-repeat 0 0;
			padding-left: 25px !important;
			width: 20px !important;
			height: 20px !important;
		}
	</style>
	<?php
	$post_id = get_the_ID();
	if ( tve_post_is_landing_page( $post_id ) ) {
		?>
		<script type="text/javascript">
			function tve_confirm_revert_to_theme() {
				if ( confirm( "<?php echo __( 'Are you sure you want to DELETE all of the content that was created in this landing page and revert to the theme page?\nIf you click OK, any custom content you added to the landing page will be deleted.', 'thrive-cb' ) ?>" ) ) {
					location.href = location.href + '&tve_revert_theme=1';
				}
				return false;
			}
		</script>
		<div class="postbox" style="text-align: center;">
			<div class="inside">
				<?php echo __( "You are currently using a Content Builder landing page to display this piece of content.", 'thrive-cb' ) ?>
				<br/>
				<?php echo __( "If you'd like to revert back to your theme template then click the button below:", 'thrive-cb' ) ?>
				<br/><br/>
				<a href="javascript:void(0)" onclick="tve_confirm_revert_to_theme()"
				   class="button"><?php echo __( "Revert to theme template", "thrive-cb" ) ?></a>
			</div>
		</div>
		<?php
	}
}

/**
 * Returns the url for closing the TCB editing screen.
 *
 * If no post id is set then will use native WP functions to get the editing URL for the piece of content that's currently being edited
 *
 * @param bool $post_id
 *
 * @return string
 */
function tcb_get_editor_close_url( $post_id = false ) {
	/**
	 * we need to make sure that if the admin is https, then the editor link is also https, otherwise any ajax requests through wp ajax api will not work
	 */
	$admin_ssl = strpos( admin_url(), 'https' ) === 0;
	$post_id   = ( $post_id ) ? $post_id : get_the_ID();

	$editor_link = set_url_scheme( get_permalink( $post_id ) );

	return $admin_ssl ? str_replace( 'http://', 'https://', $editor_link ) : $editor_link;
}

/**
 * Returns the url for the TCB editing screen.
 *
 * If no post id is set then will use native WP functions to get the editing URL for the piece of content that's currently being edited
 *
 * @param bool $post_id
 * @param bool $main_frame whether or not to get the main frame Editor URL or the child frame one
 *
 * @return string
 */
function tcb_get_editor_url( $post_id = false, $main_frame = true ) {
	/**
	 * we need to make sure that if the admin is https, then the editor link is also https, otherwise any ajax requests through wp ajax api will not work
	 */
	$admin_ssl = strpos( admin_url(), 'https' ) === 0;
	$post_id   = ( $post_id ) ? $post_id : get_the_ID();
	/*
     * We need the post to complete the full arguments for the preview_post_link filter
     */
	$post        = get_post( $post_id );
	$editor_link = set_url_scheme( get_permalink( $post_id ) );
	$params      = array();
	if ( $main_frame ) {
		$params[ TVE_EDITOR_FLAG ] = 'true';
	} else {
		$params[ TVE_FRAME_FLAG ] = wp_create_nonce( TVE_FRAME_FLAG . $post_id );
	}
	$editor_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( apply_filters( 'tcb_editor_edit_link_query_args', $params, $post_id ), $editor_link ), $post ) );

	return $admin_ssl ? str_replace( 'http://', 'https://', $editor_link ) : $editor_link;
}

/**
 * Returns the preview URL for any given post/page
 *
 * If no post id is set then will use native WP functions to get the editing URL for the piece of content that's currently being edited
 *
 * @param bool $post_id
 *
 * @return string
 */
function tcb_get_preview_url( $post_id = false ) {
	$post_id = ( $post_id ) ? $post_id : get_the_ID();
	/*
     * We need the post to complete the full arguments for the preview_post_link filter
     */
	$post         = get_post( $post_id );
	$preview_link = set_url_scheme( get_permalink( $post_id ) );
	$preview_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( apply_filters( 'tcb_editor_preview_link_query_args', array( 'preview' => 'true' ), $post_id ), $preview_link ), $post ) );

	return $preview_link;
}

/**
 *
 * checks whether the $post_type is editable using the TCB
 *
 * @param string $post_type
 * @param int $post_id
 *
 * @return bool true if the post type is editable
 */
function tve_is_post_type_editable( $post_type, $post_id = null ) {
	/* post types that are not editable using the content builder - handled as a blacklist */
	$blacklist_post_types = array(
		'focus_area',
		'thrive_optin',
		'tvo_shortcode'
	);

	$blacklist_post_types = apply_filters( 'tcb_post_types', $blacklist_post_types );

	if ( isset( $blacklist_post_types['force_whitelist'] ) && is_array( $blacklist_post_types['force_whitelist'] ) ) {
		return in_array( $post_type, $blacklist_post_types['force_whitelist'] );
	}

	if ( in_array( $post_type, $blacklist_post_types ) ) {
		return false;
	}

	if ( $post_id === null ) {
		$post_id = get_the_ID();
	}

	return apply_filters( 'tcb_post_editable', true, $post_type, $post_id );
}

/**
 * Sometimes the only way to make the plugin work with other scripts is by deregistering them on the editor page
 */
function tve_remove_conflicting_scripts() {
	if ( is_editor_page() ) {
		/**  Genesis framework - Media Child theme contains a script that prevents users from being able to close the media library */
		wp_dequeue_script( 'yt-embed' );
		wp_deregister_script( 'yt-embed' );

		/** Member player loads jquery tools which conflicts with jQuery UI */
		wp_dequeue_script( 'mpjquerytools' );
		wp_deregister_script( 'mpjquerytools' );

	}
}

/**
 * adds a div element to the page used to manipulate editor html before writing to database
 */
function pre_save_filter_wrapper() {
	if ( isset( $_GET[ TVE_EDITOR_FLAG ] ) && ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) ) {
		echo '<div id="pre_save_filter_wrapper" style="display:none"></div>';
	}
}

/**
 * load admin scripts on post add / edit page
 *
 * @param string $hook_suffix
 */
function tve_edit_page_scripts( $hook_suffix ) {
	$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

	if ( tve_is_post_type_editable( get_post_type( get_the_ID() ) ) ) {
		if ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			tve_enqueue_script( 'tve_post_ready', tve_editor_js() . '/tve_admin_post_ready' . $js_suffix );
			wp_localize_script( 'tve_post_ready', 'TCB_Post_Edit_Data', array(
				'post_id' => get_the_ID()
			) );
		}
	}
}

/**
 * Adds TCB editing URL to underneath the post title in the Wordpress post listings view
 *
 * @param $actions
 * @param $page_object
 *
 * @return mixed
 */
function thrive_page_row_buttons( $actions, $page_object ) {
	// don't add url to blacklisted content types
	if ( ! tve_is_post_type_editable( $page_object->post_type ) || ! current_user_can( 'edit_posts' ) ) {
		return $actions;
	}

	$page_for_posts = get_option( 'page_for_posts' );
	if ( $page_for_posts && $page_object->ID == $page_for_posts ) {
		return $actions;
	}

	?>
	<style type="text/css">
		.thrive-adminbar-icon {
			background: url('<?php echo tve_editor_css(); ?>/images/admin-bar-logo.png') no-repeat 0 0;
			padding-left: 25px !important;
			width: 20px !important;
			height: 20px !important;
		}
	</style>
	<?php

	$url            = tcb_get_editor_url( $page_object->ID );
	$actions['tcb'] = "<span class='thrive-adminbar-icon'></span><a target='_blank' href='" . $url . "'>" . __( "Edit with Thrive Content Builder", "thrive-cb" ) . "</a>";

	return $actions;
}

/**
 * Load meta tags for social media and others
 *
 * @param int $post_id
 */
function tve_load_meta_tags( $post_id = 0 ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$globals = tve_get_post_meta( $post_id, 'tve_globals' );
	if ( ! empty( $globals['fb_comment_admins'] ) ) {
		$fb_admins = json_decode( $globals['fb_comment_admins'] );
		if ( ! empty( $fb_admins ) && is_array( $fb_admins ) ) {
			foreach ( $fb_admins as $admin ) {
				echo '<meta property="fb:admins" content="' . $admin . '"/>';
			}
		}
	}
}


/**
 * it's a hook on the wp_head WP action
 *
 * outputs the CSS needed for the custom fonts
 */
function tve_load_font_css() {
	/* if it's a Thrive Theme, all of that is loaded from the theme */
	/* this is causing some issues in the editor menus for the custom fonts, we need to include them as !important */
	/* TODO: see what would imply to setup !important in the themes also */
//    if (tve_check_if_thrive_theme()) {
//        return;
//    }

	do_action( 'tcb_extra_fonts_css' );

	$all_fonts = tve_get_all_custom_fonts();
	if ( empty( $all_fonts ) ) {
		return;
	}
	echo '<style type="text/css">';

	/** @var array $css prepare and array of css classes what will have as value an array of css rules */
	$css = array();
	foreach ( $all_fonts as $font ) {
		$css[ $font->font_class ] = array(
			"font-family: " . tve_prepare_font_family( $font->font_name ) . " !important;",
		);
		$fontWeight               = preg_replace( '/[^0-9]/', "", $font->font_style );
		$fontStyle                = preg_replace( '/[0-9]/', "", $font->font_style );
		if ( ! empty( $font->font_color ) ) {
			$css[ $font->font_class ][] = "color: {$font->font_color} !important;";
		}
		if ( ! empty( $fontWeight ) ) {
			$css[ $font->font_class ][] = "font-weight: {$fontWeight} !important;";
		}
		if ( ! empty( $fontStyle ) ) {
			$css[ $font->font_class ][] = "font-style: {$fontStyle};";
		}
		if ( ! empty( $font->font_bold ) ) {
			$css["{$font->font_class}.bold_text,.{$font->font_class} .bold_text,.{$font->font_class} b,.{$font->font_class} strong"] = array(
				"font-weight: {$font->font_bold} !important;"
			);
		}
	}

	/**
	 * Loop through font classes and display their css properties
	 *
	 * @var string $font_class
	 * @var array $rules
	 */
	foreach ( $css as $font_class => $rules ) {
		/** add font css rules to the page */
		echo ".{$font_class}{" . implode( "", $rules ) . "}";
		/** set the font css rules for inputs also */
		echo ".{$font_class} input, .{$font_class} select, .{$font_class} textarea, .{$font_class} button {" . implode( "", $rules ) . "}";
	}

	echo '</style>';

}

/**
 * output the css for the $fonts array
 *
 * @param array $fonts
 */
function tve_output_custom_font_css( $fonts ) {
	echo '<style type="text/css">';

	/** @var array $css prepare and array of css classes what will have as value an array of css rules */
	$css = array();
	foreach ( $fonts as $font ) {
		$font                     = (object) $font;
		$css[ $font->font_class ] = array(
			"font-family: " . ( strpos( $font->font_name, "," ) === false ? "'" . $font->font_name . "'" : $font->font_name ) . " !important;",
		);

		$fontWeight = preg_replace( '/[^0-9]/', "", $font->font_style );
		$fontStyle  = preg_replace( '/[0-9]/', "", $font->font_style );
		if ( ! empty( $font->font_color ) ) {
			$css[ $font->font_class ][] = "color: {$font->font_color} !important;";
		}
		if ( ! empty( $fontWeight ) ) {
			$css[ $font->font_class ][] = "font-weight: {$fontWeight} !important;";
		}
		if ( ! empty( $fontStyle ) ) {
			$css[ $font->font_class ][] = "font-style: {$fontStyle};";
		}
		if ( ! empty( $font->font_bold ) ) {
			$css["{$font->font_class}.bold_text,.{$font->font_class} .bold_text,.{$font->font_class} b,.{$font->font_class} strong"] = array(
				"font-weight: {$font->font_bold} !important;"
			);
		}
	}

	/**
	 * Loop through font classes and display their css properties
	 *
	 * @var string $font_class
	 * @var array $rules
	 */
	foreach ( $css as $font_class => $rules ) {
		/** add font css rules to the page */
		echo ".{$font_class}{" . implode( "", $rules ) . "}";
		/** set the font css rules for inputs also */
		echo ".{$font_class} input, .{$font_class} select, .{$font_class} textarea, .{$font_class} button {" . implode( "", $rules ) . "}";
	}

	echo '</style>';
}

/**
 * Prepare font family name to be added to css rule
 *
 * @param $font_family
 */
function tve_prepare_font_family( $font_family ) {
	$chunks = explode( ",", $font_family );
	$length = count( $chunks );
	$font   = "";
	foreach ( $chunks as $key => $value ) {
		$font .= "'" . trim( $value ) . "'";
		$font .= ( $key + 1 ) < $length ? ", " : "";
	}

	return $font;
}

/**
 * adds an icon and link to the admin bar for quick access to the editor. Only shows when not already in thrive content builder
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function thrive_editor_admin_bar( $wp_admin_bar ) {
	$theme = wp_get_theme();
	// SUPP-1408 Hive theme leaves the query object in an unknown state
	if ( 'Hive' == $theme->name || 'Hive' == $theme->parent_theme ) {
		wp_reset_query();
	}
	$post_id = get_the_ID();
	if ( is_admin_bar_showing() && ( is_single() || is_page() ) && tve_is_post_type_editable( get_post_type() ) && current_user_can( 'edit_post', $post_id ) ) {

		if ( ! isset( $_GET[ TVE_EDITOR_FLAG ] ) ) {
			$editor_link = tcb_get_editor_url( $post_id );
			$args        = array(
				'id'    => 'tve_button',
				'title' => '<span class="thrive-adminbar-icon"></span>' . __( "Edit with Thrive Content Builder", 'thrive-cb' ),
				'href'  => $editor_link,
				'meta'  => array(
					'class' => 'thrive-admin-bar'
				)
			);
		} elseif ( get_post_type() == 'post' || get_post_type() == 'page' ) {
			$close_editor_link = tcb_get_editor_close_url( $post_id );
			$args              = array(
				'id'    => 'tve_button',
				'title' => '<span class="thrive-adminbar-icon"></span>' . __( "Close Thrive Content Builder", 'thrive-cb' ),
				'href'  => $close_editor_link,
				'meta'  => array(
					'class' => 'thrive-admin-bar'
				)
			);
		} else {
			return;
		}


		$wp_admin_bar->add_node( $args );
	}
}

/**
 * Ajax listener to save the post in database.  Handles "Save" and "Update" buttons together.
 * If either button pressed, then write to saved field.
 * If publish button pressed, then write to both save and published fields
 */
function tve_save_post() {
	@ini_set( "memory_limit", "512M" );
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( isset( $_POST['post_id'] ) && current_user_can( 'edit_post', $_POST['post_id'] ) ) {
		if ( ob_get_contents() ) {
			ob_clean();
		}

		$landing_page_template = empty( $_POST['tve_landing_page'] ) ? 0 : $_POST['tve_landing_page'];

		if ( ! empty( $_POST['custom_action'] ) ) {
			switch ( $_POST['custom_action'] ) {
				case 'landing_page': //change or remove the landing page template for this post
					tve_change_landing_page_template( $_POST['post_id'], $landing_page_template );
					break;
				case 'cloud_landing_page':
					$valid = tve_get_cloud_template_config( $landing_page_template );
					if ( $valid === false ) { /* this is not a valid cloud landing page template - most likely, some of the files were deleted */
						$current  = tve_post_is_landing_page( $_POST['post_id'] );
						$response = array(
							'current_template' => $current,
							'error'            => __( 'Some of the required files were not found. Please try re-downloading this template', 'thrive-cb' ),
						);
						wp_send_json( $response );
					}
					/* if valid, go on with the regular change of template */
					tve_change_landing_page_template( $_POST['post_id'], $landing_page_template );
					break;
				case 'landing_page_reset':
					/* clear the contents of the current landing page */
					if ( ! ( $landing_page_template = tve_post_is_landing_page( $_POST['post_id'] ) ) ) {
						break;
					}
					tve_landing_page_reset( $_POST['post_id'], $landing_page_template );
					break;
				case 'landing_page_delete':
					$template_index = intval( str_replace( 'user-saved-template-', '', $landing_page_template ) );
					$contents       = get_option( 'tve_saved_landing_pages_content' );
					$meta           = get_option( 'tve_saved_landing_pages_meta' );

					unset( $contents[ $template_index ], $meta[ $template_index ] );
					/* array_values - reorganize indexes */
					update_option( 'tve_saved_landing_pages_content', array_values( $contents ) );
					update_option( 'tve_saved_landing_pages_meta', array_values( $meta ) );

					tve_landing_pages_load();
					break;
			}

			/** trigger also a post / page update for the caching plugins to know there has been a save */
			if ( ! empty( $_POST['tve_content'] ) ) {
				wp_update_post( array(
					'ID'                => $_POST['post_id'],
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql' ),
					'post_title'        => get_the_title( $_POST['post_id'] )
				) );
			}

			$tve_get_last_revision_id = tve_get_last_revision_id( $_POST['post_id'] );
			exit( $tve_get_last_revision_id ? (string) $tve_get_last_revision_id : '1' );
		}

		if ( ! empty( $_POST['tve_default_tooltip_settings'] ) ) {

			if ( ! empty( $_POST['tve_default_tooltip_settings']['event_tooltip_text'] ) ) {
				$_POST['tve_default_tooltip_settings']['event_tooltip_text'] = stripslashes( $_POST['tve_default_tooltip_settings']['event_tooltip_text'] );
			}
			update_option( "tve_default_tooltip_settings", $_POST['tve_default_tooltip_settings'] );
		}

		$key           = $landing_page_template ? ( '_' . $landing_page_template ) : '';
		$content_split = tve_get_extended( $_POST['tve_content_more'] );
		update_post_meta( $_POST['post_id'], "tve_content_before_more{$key}", $content_split['main'] );
		update_post_meta( $_POST['post_id'], "tve_content_more_found{$key}", $content_split['more_found'] );
		update_post_meta( $_POST['post_id'], "tve_save_post{$key}", $_POST['tve_content'] );
		update_post_meta( $_POST['post_id'], "tve_custom_css{$key}", $_POST['inline_rules'] );
		/* user defined Custom CSS rules here, had to use different key because tve_custom_css was already used */
		update_post_meta( $_POST['post_id'], "tve_user_custom_css{$key}", $_POST['tve_custom_css'] );
		update_option( "thrv_custom_colours", isset( $_POST['custom_colours'] ) ? $_POST['custom_colours'] : array() );
		tve_update_post_meta( $_POST['post_id'], 'tve_page_events', empty( $_POST['page_events'] ) ? array() : $_POST['page_events'] );

		if ( $_POST['update'] == "true" ) {
			update_post_meta( $_POST['post_id'], "tve_updated_post{$key}", $_POST['tve_content'] );
		}

		/* global options for a post that are not included in the editor */
		$tve_globals             = empty( $_POST['tve_globals'] ) ? array() : array_filter( $_POST['tve_globals'] );
		$tve_globals['font_cls'] = empty( $_POST['custom_font_classes'] ) ? array() : $_POST['custom_font_classes'];
		update_post_meta( $_POST['post_id'], "tve_globals{$key}", $tve_globals );
		/* custom fonts used for this post */
		tve_update_post_custom_fonts( $_POST['post_id'], $tve_globals['font_cls'] );

		if ( $landing_page_template ) {
			update_post_meta( $_POST['post_id'], 'tve_landing_page', $_POST['tve_landing_page'] );
			/* global Scripts for landing pages */
			update_post_meta( $_POST['post_id'], 'tve_global_scripts', ! empty( $_POST['tve_global_scripts'] ) ? $_POST['tve_global_scripts'] : array() );
			if ( ! empty( $_POST['tve_landing_page_save'] ) ) {

				/* save the contents of the current landing page for later use */
				$template_content = array(
					'before_more'        => $content_split['main'],
					'more_found'         => $content_split['more_found'],
					'content'            => $_POST['tve_content'],
					'inline_css'         => $_POST['inline_rules'],
					'custom_css'         => $_POST['tve_custom_css'],
					'tve_globals'        => empty( $_POST['tve_globals'] ) ? array() : array_filter( $_POST['tve_globals'] ),
					'tve_global_scripts' => empty( $_POST['tve_global_scripts'] ) ? array() : $_POST['tve_global_scripts']
				);
				$template_meta    = array(
					'name'     => $_POST['tve_landing_page_save'],
					'template' => $landing_page_template,
					'date'     => date( 'Y-m-d' ),
				);
				/**
				 * if this is a cloud template, we need to store the thumbnail separately, as it has a different location
				 */
				$config = tve_get_cloud_template_config( $landing_page_template, false );
				if ( $config !== false && ! empty( $config['thumb'] ) ) {
					$template_meta['thumbnail'] = $config['thumb'];
				}
				if ( empty( $template_content['more_found'] ) ) { // save some space
					unset( $template_content['before_more'] ); // this is the same as the tve_save_post field
					unset( $template_content['more_found'] );
				}
				$templates_content = get_option( 'tve_saved_landing_pages_content' ); // this should get unserialized automatically
				$templates_meta    = get_option( 'tve_saved_landing_pages_meta' ); // this should get unserialized automatically
				if ( empty( $templates_content ) ) {
					$templates_content = array();
					$templates_meta    = array();
				}
				$templates_content [] = $template_content;
				$templates_meta []    = $template_meta;

				// make sure these are not autoloaded, as it is a potentially huge array
				add_option( 'tve_saved_landing_pages_content', null, '', 'no' );

				update_option( 'tve_saved_landing_pages_content', $templates_content );
				update_option( 'tve_saved_landing_pages_meta', $templates_meta );
			}
		} else {
			delete_post_meta( $_POST['post_id'], 'tve_landing_page' );
		}
		tve_update_post_meta( $_POST['post_id'], 'thrive_icon_pack', empty( $_POST['has_icons'] ) ? 0 : 1 );
		tve_update_post_meta( $_POST['post_id'], 'tve_has_masonry', empty( $_POST['tve_has_masonry'] ) ? 0 : 1 );
		tve_update_post_meta( $_POST['post_id'], 'tve_has_typefocus', empty( $_POST['tve_has_typefocus'] ) ? 0 : 1 );
		tve_update_post_meta( $_POST['post_id'], 'tve_has_wistia_popover', empty( $_POST['tve_has_wistia_popover'] ) ? 0 : 1 );
		if ( ! empty( $_POST['social_fb_app_id'] ) ) {
			update_option( 'tve_social_fb_app_id', $_POST['social_fb_app_id'] );
		}

		/**
		 * trigger also a post / page update for the caching plugins to know there has been a save
		 * update post here so we can have access to its meta when a revision of it is saved
		 *
		 * @see tve_save_post_callback
		 */
		if ( ! empty( $_POST['tve_content'] ) ) {
			if ( $landing_page_template ) {
				remove_all_filters( 'save_post' );
				add_action( 'save_post', 'tve_save_post_callback' );
			}
			wp_update_post( array(
				'ID'                => $_POST['post_id'],
				'post_modified'     => current_time( 'mysql' ),
				'post_modified_gmt' => current_time( 'mysql' ),
				'post_title'        => get_the_title( $_POST['post_id'] )
			) );
		}

		$tve_get_last_revision_id = tve_get_last_revision_id( $_POST['post_id'] );
		exit( $tve_get_last_revision_id ? (string) $tve_get_last_revision_id : '1' );
	} else {

		exit( '-2' );
	}
}

/**
 * add the editor content to $content, but at priority 101 so not affected by custom theme shortcode functions that are common with some theme developers
 *
 * @param string $content the post content
 * @param null|string $use_case used to control the output, e.g. it can be used to return just TCB content, not full content
 *
 * @return string
 */
function tve_editor_content( $content, $use_case = null ) {
	global $post;

	$post_id = get_the_ID();

	if ( isset( $GLOBALS['TVE_CONTENT_SKIP_ONCE'] ) ) {
		unset( $GLOBALS['TVE_CONTENT_SKIP_ONCE'] );

		return $content;
	}

	/**
	 * check if current post is protected by a membership plugin
	 */
	if ( ! tve_membership_plugin_can_display_content() ) {
		return $content;
	}

	/* this will hold the html for the tinymce editor instantiation, only if we're on the editor page */
	$tinymce_editor = $page_loader = '';

	if ( ! tve_is_post_type_editable( get_post_type( $post_id ) ) ) {
		return $content;
	}

	$is_landing_page = tve_post_is_landing_page( $post_id );

	if ( $use_case !== 'tcb_content' && post_password_required( $post ) ) {
		return $is_landing_page ? '<div class="tve-lp-pw-form">' . get_the_password_form( $post ) . '</div>' : $content;
	}

	if ( is_editor_page() ) {

		// this is an editor page
//		print_r(tve_get_post_meta( $post_id, "tve_save_post", true ));

		$tve_saved_content = stripslashes( tve_get_post_meta( $post_id, "tve_save_post", true ) );

		/*
         * this was completely destroying the page when using wordpress SEO plugin
         * it called get_the_excerpt early in the head section, which in turn called apply_filters('the_content')
         * the wp_editor function should only be called once per request
         */
		if ( in_the_loop() || $is_landing_page ) {
			add_action( 'wp_footer', 'tve_output_wysiwyg_editor' );
		}

		$page_loader
			= '<div id="tve_page_loader" class="tve_page_loader"><div class="tve_loader_inner">
                <img src="' . tve_editor_css() . '/images/loader.gif" alt=""/></div></div>';

	} else {
		// this is the frontend - check to see if on blog and find tve-more excerpt if available
		if ( ! tve_check_in_loop( $post_id ) ) {
			tve_load_custom_css( $post_id );
		}

		if ( $use_case !== 'tcb_content' ) { // do not trucate the contents if we require it all
			/**
			 * do not truncate the post content if the current page is a feed and the option for the feed display is "Full text"
			 */
			$rss_use_excerpt = false;
			if ( is_feed() ) {
				$rss_use_excerpt = (bool) get_option( 'rss_use_excerpt' );
			}
			$tcb_force_excerpt = apply_filters( 'tcb_force_excerpt', false );
			if ( $rss_use_excerpt || ! is_singular() || ( class_exists( 'PostGridHelper' ) && PostGridHelper::$render_post_grid === false ) || $tcb_force_excerpt ) {
				$more_found          = tve_get_post_meta( get_the_ID(), "tve_content_more_found", true );
				$content_before_more = tve_get_post_meta( get_the_ID(), "tve_content_before_more", true );
				if ( ! empty( $content_before_more ) && $more_found ) {
					if ( is_feed() ) {
						$more_link = ' [&#8230;]';
					} elseif ( class_exists( 'PostGridHelper' ) && PostGridHelper::$render_post_grid === false ) {
						$more_link = '';
					} else {
						$more_link = apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . '#more-' . $post->ID . '" class="more-link">Continue Reading</a>', 'Continue Reading' );
					}

					$tve_saved_content = stripslashes( $content_before_more ) . $more_link;
					$content           = ''; /* clear out anything else after this point */
				} elseif ( is_feed() && $rss_use_excerpt ) {
					$rss_content = tve_get_post_meta( get_the_ID(), "tve_updated_post", true ) . $content;
					if ( $rss_content ) {
						$tve_saved_content = wp_trim_excerpt( $rss_content );
					}
				}
			}
		}

		if ( ! isset( $tve_saved_content ) ) {
			$tve_saved_content = tve_get_post_meta( get_the_ID(), "tve_updated_post", true );
			$tve_saved_content = tve_restore_script_tags( $tve_saved_content );
			$tve_saved_content = stripslashes( $tve_saved_content );
		}
		if ( empty( $tve_saved_content ) ) {
			// return empty content if nothing is inserted in the editor - this is to make sure that first page section on the page will actually be displayed ok
			return $use_case === 'tcb_content' ? '' : $content;
		}

		$tve_saved_content = tve_compat_content_filters_before_shortcode( $tve_saved_content );

		/* prepare Events configuration */
		if ( ! is_feed() && ( in_the_loop() || $is_landing_page ) ) {
			// append lightbox HTML to the end of the body
			tve_parse_events( $tve_saved_content );
		}
	}

	$tve_saved_content = tve_thrive_shortcodes( $tve_saved_content, is_editor_page() );

	/* render the content added through WP Editor (element: "WordPress Content") */
	$tve_saved_content = tve_do_wp_shortcodes( $tve_saved_content, is_editor_page() );

	if ( ! is_editor_page() ) {
		//for the case when user put a shortcode inside a "p" element
		$tve_saved_content = shortcode_unautop( $tve_saved_content );
	}

	if ( ! is_editor_page() ) {
		if ( $is_landing_page ) {
			$tve_saved_content = do_shortcode( $tve_saved_content );
			$tve_saved_content = tve_compat_content_filters_after_shortcode( $tve_saved_content );
		} else {
			$theme = wp_get_theme();
			/**
			 * Stendhal theme removes the default WP do_shortcode on the_content filter and adds their own. not sure why
			 */
			if ( $theme->name === 'Stendhal' || $theme->parent_theme === 'Stendhal' ) {
				$tve_saved_content = do_shortcode( $tve_saved_content );
			}
		}
	}

	$style_family_class = tve_get_style_family_class( $post_id );

	$style_family_id = is_singular() ? ' id="' . $style_family_class . '" ' : ' ';

	$wrap = array(
		'start' => '<div' . $style_family_id . 'class="' . $style_family_class . '"><div id="tve_editor" class="tve_shortcode_editor">',
		'end'   => '</div></div>'
	);

	if ( is_feed() ) {
		$wrap['start'] = $wrap['end'] = '';
	} elseif ( is_editor_page() && get_post_type( $post_id ) == 'tcb_lightbox' ) {
		$wrap['start'] .= '<div class="tve_p_lb_control tve_editable tve_editor_main_content tve_content_save tve_empty_dropzone">';
		$wrap['end'] .= '</div>';
	}

	if ( tve_get_post_meta( $post_id, 'thrive_icon_pack' ) ) {
		tve_enqueue_icon_pack();
	}

	tve_enqueue_extra_resources( $post_id );

	/**
	 * fix for LG errors being included in the page
	 */
	$tve_saved_content = preg_replace_callback( '/__CONFIG_lead_generation__(.+?)__CONFIG_lead_generation__/s', 'tcb_lg_err_inputs', $tve_saved_content );

	if ( ! is_editor_page() ) {
		$tve_saved_content = apply_filters( 'tcb_clean_frontend_content', $tve_saved_content );
	}

	if ( $use_case === 'tcb_content' ) {
		return $tve_saved_content;
	}

	return $wrap['start'] . $tve_saved_content . $wrap['end'] . $content . $tinymce_editor . $page_loader;
}

/**
 * check if there are any extra icon packs needed on the current page / post
 *
 * @param $post_id
 */
function tve_enqueue_extra_resources( $post_id ) {
	$globals = tve_get_post_meta( $post_id, 'tve_globals' );

	if ( ! empty( $globals['used_icon_packs'] ) && ! empty( $globals['extra_icons'] ) ) {
		$used_icons_font_family = $globals['used_icon_packs'];

		foreach ( $globals['extra_icons'] as $icon_pack ) {
			if ( ! in_array( $icon_pack['font-family'], $used_icons_font_family ) ) {
				continue;
			}
			wp_enqueue_style( md5( $icon_pack['css'] ), tve_url_no_protocol( $icon_pack['css'] ) );
		}
	}

	/* any of the extra imported fonts - only in case of imported landing pages */
	if ( ! empty( $globals['extra_fonts'] ) ) {
		foreach ( $globals['extra_fonts'] as $font ) {
			if ( empty( $font['ignore'] ) ) {
				wp_enqueue_style( md5( $font['font_url'] ), tve_url_no_protocol( $font['font_url'] ) );
			}
		}
	}
}

/**
 * Fix added by Paul McCarthy - 25th September 2014.
 * This is a fix for the theme called "Pitch" that applies a filter to wordpress media gallery that runs a backend only native Wordpress function get_current_screen()
 * As we're loading the media library in the front end, the function that's called doesn't exist and causes a fatal error
 * This function removes the filter so that it isn't processed while in Thrive Editor mode.
 */
function tve_turn_off_get_current_screen() {
	if ( is_editor_page() ) {
		remove_filter( 'media_view_strings', 'siteorigin_settings_media_view_strings', 10, 2 );
	}
}

/**
 * wrapper over the wp enqueue_style function
 * it will append the TVE_VERSION as a query string parameter to the $src if $ver is left empty
 *
 * @param       $handle
 * @param       $src
 * @param array $deps
 * @param bool $ver
 * @param       $media
 */
function tve_enqueue_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	if ( $ver === false ) {
		$ver = TVE_VERSION;
	}
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}

/**
 * wrapper over the wp_enqueue_script functions
 * it will add the plugin version to the script source if no version is specified
 *
 * @param        $handle
 * @param string $src
 * @param array $deps
 * @param bool $ver
 * @param bool $in_footer
 */
function tve_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {
	if ( $ver === false ) {
		$ver = TVE_VERSION;
	}
	wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

/**
 * enqueue the CSS for the icon pack used by the user
 */
function tve_enqueue_icon_pack() {
	if ( ! wp_style_is( 'thrive_icon_pack', 'enqueued' ) ) {
		$icon_pack = get_option( 'thrive_icon_pack' );
		if ( empty( $icon_pack['css'] ) ) {
			return '';
		}
		$_url = tve_url_no_protocol( $icon_pack['css'] );
		wp_enqueue_style( 'thrive_icon_pack', $_url, array(), isset( $icon_pack['css_version'] ) ? $icon_pack['css_version'] : TVE_VERSION );

		return $_url . '?ver=' . isset( $icon_pack['css_version'] ) ? $icon_pack['css_version'] : TVE_VERSION;
	}

	return '';
}

/**
 * Fix added by Paul McCarthy 16th October 2014
 * Check to see if the theme is thesis or not so that we can load the the Thesis child theme stylesheet on tcb_lightbox custom post types
 *
 * @return bool
 */
function tve_is_thesis() {
	return ( wp_get_theme() == "Thesis" ) ? true : false;
}

/**
 * some features in the editor can only be displayed if we have knowledge about the theme and thus should only display on a thrive theme (borderless content for instance)
 * this function checks the global variable that's set in all thrive themes to check if the user is using a thrive theme or not
 **/
function tve_check_if_thrive_theme() {
	global $is_thrive_theme;
	if ( isset( $is_thrive_theme ) && $is_thrive_theme == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Hides thrive editor custom fields from being modified in the standard WP post / page edit screen
 *
 * @param $protected
 * @param $meta_key
 *
 * @return bool
 */
function tve_hide_custom_fields( $protected, $meta_key ) {
	if ( strpos( $meta_key, 'tve_revision_' ) === 0 ) {
		return true;
	}

	$keys                   = array(
		'tve_save_post',
		'tve_updated_post',
		'tve_content_before_more_shortcoded',
		'tve_content_before_more',
		'tve_style_family',
		'tve_updated_post_shortcoded',
		'tve_user_custom_css',
		'tve_custom_css',
		'tve_content_more_found',
		'tve_landing_page',
		'thrive_post_fonts',
		'thrive_tcb_post_fonts',
		'tve_globals',
		'tve_special_lightbox',
		'thrive_icon_pack',
		'tve_global_scripts',
		'tve_has_masonry',
		'tve_page_events',
		'tve_typefocus',
		'tve_has_wistia_popover'
	);
	$landing_page_templates = array_keys( include dirname( dirname( __FILE__ ) ) . '/landing-page/templates/_config.php' );

	foreach ( $keys as $key ) {
		if ( $key == $meta_key || strpos( $meta_key, $key ) === 0 ) {
			return true;
		}
		foreach ( $landing_page_templates as $suffix ) {
			if ( $key . '_' . $suffix == $meta_key ) {
				return true;
			}
		}
	}

	return $protected;
}

/**
 * This is a replica of the WP function get_extended
 * The returned array has 'main', 'extended', and 'more_text' keys. Main has the text before
 * the <code><!--tvemore--></code>. The 'extended' key has the content after the
 * <code><!--tvemore--></code> comment. The 'more_text' key has the custom "Read More" text.
 *
 * @param string $post Post content.
 *
 * @return array Post before ('main'), after ('extended'), and custom readmore ('more_text').
 */
function tve_get_extended( $post ) {
	//Match the new style more links
	if ( preg_match( '/<!--tvemore(.*?)?-->/', $post, $matches ) ) {
		list( $main, $extended ) = explode( $matches[0], $post, 2 );
		$more_text  = $matches[1];
		$more_found = true;
	} else {
		$main       = $post;
		$extended   = '';
		$more_text  = '';
		$more_found = false;
	}

	// ` leading and trailing whitespace
	$main      = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $main );
	$extended  = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $extended );
	$more_text = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $more_text );

	return array(
		'main'       => $main,
		'extended'   => $extended,
		'more_text'  => $more_text,
		'more_found' => $more_found
	);
}

/**
 * Adds inline script to hide more tag from the front end display
 */
function tve_hide_more_tag() {
	echo '<style type="text/css">.tve_more_tag {display: none !important}</style>';
}

/**
 * if the current post is a landing page created with TCB, forward the control over to the landing page layout.php file
 *
 * if the current post is a Thrive CB Lightbox, display it on a page that will mimic it's behaviour (semi-transparent background, close button etc)
 *
 * if there is a hook registered for displaying content, call that hook
 *
 * @return bool
 */
function tcb_custom_editable_content() {
	// don't apply template redirects unless single post / page is being displayed.
	if ( ! is_singular() || is_feed() || is_comment_feed() ) {
		return false;
	}

	$tcb_inactive = defined( 'EXTERNAL_TCB' ) && EXTERNAL_TCB === 0;

	$post_id   = get_the_ID();
	$post_type = get_post_type( $post_id );

	/**
	 * the filter should append its own custom templates based on the post ID / type
	 * if this array is not empty, it will use the first found file from this array as the post content template
	 */
	$custom_post_layouts = apply_filters( 'tcb_custom_post_layouts', array(), $post_id, $post_type );

	/* For TCB, we only have tcb_lightbox and landing pages editable with a separate layout */
	if ( $post_type != 'tcb_lightbox' && ! ( $lp_template = tve_post_is_landing_page( $post_id ) ) && empty( $custom_post_layouts ) ) {
		return false;
	}

	$landing_page_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page';

	if ( ! $tcb_inactive && $post_type == 'tcb_lightbox' ) {
		is_editor_page() && tve_enqueue_style( 'tve_lightbox_post', tve_editor_css() . '/editor_lightbox.css' );

		/**
		 * Fix added by Paul McCarthy 16th October 2014 - added to solve THesis Child themes not loading CSS in Thrive lightboxes
		 * Thesis v 2.1.9 loads style sheets for their child themes with this:- add_filter('template_include', array($this, '_skin'));
		 * The filter isn't applied when the content builder lightbox is loaded because of our template_redirect filter
		 * This function checks if the theme is Thesis, if so it checks for the existance of the css.css file that all Thesis child themes should have
		 * If the file is found, it enqueuest the stylesheet in both editor and front end mode.
		 */
		if ( tve_is_thesis() ) {
			if ( defined( 'THESIS_USER_SKIN_URL' ) && file_exists( THESIS_USER_SKIN . '/css.css' ) ) {
				wp_enqueue_style( 'tve_thesis_css', THESIS_USER_SKIN_URL . '/css.css' );
			}
		}

		if ( $for_landing_page = get_post_meta( $post_id, 'tve_lp_lightbox', true ) ) {

			if ( tve_is_cloud_template( $for_landing_page ) ) {
				$config   = tve_get_cloud_template_config( $for_landing_page, false );
				$css_file = 'templates/css/' . $for_landing_page . '_lightbox.css';
				if ( is_file( trailingslashit( $config['base_dir'] ) . $css_file ) ) {
					/* load up the lightbox style for this landing page */
					tve_enqueue_style( 'thrive_landing_page_lightbox', trailingslashit( $config['base_url'] ) . $css_file );
				}
			} else {
				if ( is_file( $landing_page_dir . '/templates/css/' . $for_landing_page . '_lightbox.css' ) ) {
					/* load up the lightbox style for this landing page */
					tve_enqueue_style( 'thrive_landing_page_lightbox', TVE_LANDING_PAGE_TEMPLATE . '/css/' . $for_landing_page . '_lightbox.css' );
				}
			}
		}

		include plugin_dir_path( dirname( __FILE__ ) ) . 'lightbox/layout-edit.php';
		exit();
	}

	if ( ! $tcb_inactive && ! empty( $lp_template ) ) {
		/**
		 * first, check if a membership plugin is protecting this page and, if the user does not have access, just proceed with the regular page content
		 */
		if ( ! tve_membership_plugin_can_display_content() ) {
			return false;
		}
//        tve_landing_page_reset($post_id, $lp_template);

		/* instantiate the $tcb_landing_page object - this is used throughout the layout.php for the landing page */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page/inc/TCB_Landing_Page.php';
		$tcb_landing_page = new TCB_Landing_Page( $post_id, $lp_template );

		$GLOBALS['tcb_lp_template']  = $lp_template;
		$GLOBALS['tcb_landing_page'] = $tcb_landing_page;

		/* base CSS file for all Page Templates */
		if ( ! tve_check_if_thrive_theme() ) {
			tve_enqueue_style( 'tve_landing_page_base_css', TVE_LANDING_PAGE_TEMPLATE . '/css/base.css', 99 );
		}

		$tcb_landing_page->enqueueCss();
		$tcb_landing_page->ensure_external_assets();

		include_once ABSPATH . '/wp-admin/includes/plugin.php';
		if ( is_editor_page() || ! tve_hooked_in_template_redirect() ) {

			/**
			 * added this here, because setting up a Landing Page as the homepage of your site would cause WP to not redirect properly non-www homepage to www homepage
			 */
			redirect_canonical();

			/* give the control over to the landing page template */
			include $landing_page_dir . '/layout.php';
			exit();
		}
		/**
		 * temporarily remove the_content filter for landing pages (just to not output anything in the head) - it caused issues on some shortcodes.
		 * this is re-added from the landing page layout.php file
		 */
		remove_filter( 'the_content', 'tve_editor_content' );
		/**
		 * remove thrive_template_redirect filter from the themes
		 */
		remove_filter( 'template_redirect', 'thrive_template_redirect' );

		/**
		 * this is a fix for conflicts appearing with various membership / coming soon plugins that use the template_redirect hook
		 */
		remove_all_filters( 'template_include' );
		add_filter( 'template_include', 'tcb_get_landing_page_template_layout' );
		/**
		 * make sure we'll have at least one of these fired
		 */
		add_filter( 'page_template', 'tcb_get_landing_page_template_layout' );

	} elseif ( $post_type != 'post' && $post_type != 'page' && ! empty( $custom_post_layouts ) && is_array( $custom_post_layouts ) ) {

		/**
		 * loop through each of the post_custom_layouts files array to find the first valid one
		 *
		 * TODO: we need to enforce the checks we perform here
		 */
		foreach ( $custom_post_layouts as $file ) {
			$file = @realpath( $file );
			if ( ! is_file( $file ) ) {
				continue;
			}
			include $file;
			exit();
		}
	}
}

/**
 * @param string $template
 *
 * @return string the full path to the landing page layout template
 */
function tcb_get_landing_page_template_layout( $template ) {
	return plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page/layout.php';
}

/**
 * parse and prepare all the required configuration for the different events
 *
 * @param string $content TCB - meta post content
 */
function tve_parse_events( $content ) {
	list( $start, $end ) = array(
		'__TCB_EVENT_',
		'_TNEVE_BCT__'
	);
	if ( strpos( $content, $start ) === false ) {
		return;
	}
	$triggers = tve_get_event_triggers();
	$actions  = tve_get_event_actions();

	/**
	 * append to the content
	 */
	$append = '';

	$event_pattern = "#data-tcb-events=('|\"){$start}(.+?){$end}('|\")#";

	/* hold all the javascript callbacks required for the identified actions */
	$javascript_callbacks = isset( $GLOBALS['tve_event_manager_callbacks'] ) ? $GLOBALS['tve_event_manager_callbacks'] : array();
	/* holds all the Global JS required by different actions and event triggers on page load */
	$registered_javascript_globals = isset( $GLOBALS['tve_event_manager_global_js'] ) ? $GLOBALS['tve_event_manager_global_js'] : array();

	/* hold all instances of the Action classes in order to output stuff in the footer, we need to get out of the_content filter */
	$registered_actions = isset( $GLOBALS['tve_event_manager_actions'] ) ? $GLOBALS['tve_event_manager_actions'] : array();

	/*
     * match all instances for Event Configurations
     */
	if ( preg_match_all( $event_pattern, $content, $matches, PREG_OFFSET_CAPTURE ) !== false ) {

		foreach ( $matches[2] as $i => $data ) {
			$m = htmlspecialchars_decode( $data[0] ); // the actual matched regexp group
			if ( ! ( $_params = @json_decode( $m, true ) ) ) {
				$_params = array();
			}
			if ( empty( $_params ) ) {
				continue;
			}

			foreach ( $_params as $index => $event_config ) {
				if ( empty( $event_config['t'] ) || empty( $event_config['a'] ) || ! isset( $triggers[ $event_config['t'] ] ) || ! isset( $actions[ $event_config['a'] ] ) ) {
					continue;
				}
				/** @var TCB_Event_Action_Abstract $action */
				$action                = clone $actions[ $event_config['a'] ];
				$registered_actions [] = array(
					'class'        => $action,
					'event_config' => $event_config
				);

				if ( ! isset( $javascript_callbacks[ $event_config['a'] ] ) ) {
					$javascript_callbacks[ $event_config['a'] ] = $action->getJsActionCallback();
				}
				if ( ! isset( $registered_javascript_globals[ 'action_' . $event_config['a'] ] ) ) {
					$registered_javascript_globals[ 'action_' . $event_config['a'] ] = $action;
				}
				if ( ! isset( $registered_javascript_globals[ 'trigger_' . $event_config['t'] ] ) ) {
					$registered_javascript_globals[ 'trigger_' . $event_config['t'] ] = $triggers[ $event_config['t'] ];
				}
			}
		}
	}

	if ( empty( $javascript_callbacks ) ) {
		return;
	}

	/* we need to add all the javascript callbacks into the page */
	/* this cannot be done using wp_localize_script WP function, as each if the callback will actually be JS code */
	///euuuughhh

	//TODO: how could we handle this in a more elegant fashion ?
	$GLOBALS['tve_event_manager_callbacks'] = $javascript_callbacks;
	$GLOBALS['tve_event_manager_global_js'] = $registered_javascript_globals;
	$GLOBALS['tve_event_manager_actions']   = $registered_actions;

	/* execute the mainPostCallback on all of the related actions, some of them might need to register stuff (e.g. lightboxes) */
	foreach ( $GLOBALS['tve_event_manager_actions'] as $key => $item ) {
		if ( empty( $item['main_post_callback_'] ) ) {
			$GLOBALS['tve_event_manager_actions'][ $key ]['main_post_callback_'] = true;
			$result                                                              = $item['class']->mainPostCallback( $item['event_config'] );
			if ( is_string( $result ) ) {
				$append .= $result;
			}
		}
	}

	/* remove previously assigned callback, if any - in case of list pages */
	remove_action( 'wp_print_footer_scripts', 'tve_print_footer_events' );
	add_action( 'wp_print_footer_scripts', 'tve_print_footer_events' );

	return $append;
}

/**
 * load up all event manager callbacks into the page
 */
function tve_print_footer_events() {
	if ( ! empty( $GLOBALS['tve_event_manager_callbacks'] ) ) {
		echo '<script type="text/javascript">var TVE_Event_Manager_Registered_Callbacks = TVE_Event_Manager_Registered_Callbacks || {};';
		foreach ( $GLOBALS['tve_event_manager_callbacks'] as $key => $js_function ) {
			echo 'TVE_Event_Manager_Registered_Callbacks.' . $key . ' = ' . $js_function . ';';
		}
		echo '</script>';
	}

	if ( ! empty( $GLOBALS['tve_event_manager_triggers'] ) ) {
		echo '<script type="text/javascript">';
		foreach ( $GLOBALS['tve_event_manager_triggers'] as $data ) {
			if ( ! empty( $data['class'] ) && $data['class'] instanceof TCB_Event_Trigger_Abstract ) {
				$js_code = $data['class']->getInstanceJavascript( $data['event_config'] );
				if ( ! $js_code ) {
					continue;
				}
				echo '(function(){' . $js_code . '})();';
			}
		}
		echo '</script>';
	}

	if ( ! empty( $GLOBALS['tve_event_manager_global_js'] ) ) {
		foreach ( $GLOBALS['tve_event_manager_global_js'] as $object ) {
			$object->outputGlobalJavascript();
		}
	}

	if ( ! empty( $GLOBALS['tve_event_manager_actions'] ) ) {
		foreach ( $GLOBALS['tve_event_manager_actions'] as $data ) {
			if ( ! empty( $data['class'] ) && $data['class'] instanceof TCB_Event_Action_Abstract ) {
				echo $data['class']->applyContentFilter( $data['event_config'] );
			}
		}
	}
}

/**
 * transform the tve_globals meta field into css / html properties and rules
 *
 * @param $post_id
 *
 * @return array
 */
function tve_get_lightbox_globals( $post_id ) {
	$config = get_post_meta( $post_id, 'tve_globals', true );

	$html = array(
		'overlay' => array(
			'css'          => empty( $config['l_oo'] ) ? '' : 'opacity:' . $config['l_oo'],
			'custom_color' => empty( $config['l_ob'] ) ? '' : ' data-tve-custom-colour="' . $config['l_ob'] . '"'
		),
		'content' => array(
			'custom_color' => empty( $config['l_cb'] ) ? '' : ' data-tve-custom-colour="' . $config['l_cb'] . '"',
			'class'        => empty( $config['l_ccls'] ) ? '' : ' ' . $config['l_ccls'],
			'css'          => ''
		),
		'inner'   => array(
			'css' => ''
		),
		'close'   => array(
			'class' => '',
			'css'   => ''
		)
	);

	if ( ! empty( $config['l_cimg'] ) ) { // background image
		$html['content']['css'] .= "background-image:url('{$config['l_cimg']}');background-repeat:no-repeat;background-size:cover;";
	} elseif ( ! empty( $config['l_cpat'] ) ) {
		$html['content']['css'] .= "background-image:url('{$config['l_cpat']}');background-repeat:repeat;";
	}

	if ( ! empty( $config['l_cbs'] ) ) { // content border style
		$html['content']['class'] .= ' ' . $config['l_cbs'];
		$html['close']['class'] .= ' ' . $config['l_cbs'];
	}

	if ( ! empty( $config['l_cbw'] ) ) { // content border width
		$html['content']['css'] .= "border-width:{$config['l_cbw']};";
		$html['close']['css'] .= "border-width:{$config['l_cbw']};";
	}

	if ( ! empty( $config['l_cmw'] ) ) { // content max width
		$html['content']['css'] .= "max-width:{$config['l_cmw']}";
	}

	// Close Custom Color settings
	$html['close']['custom_color'] = empty( $config['l_ccc'] ) ? '' : ' data-tve-custom-colour="' . $config['l_ccc'] . '"';

	/**
	 * @deprecated - to be removed
	 */
	if ( ! empty( $config['l_cmh'] ) ) { // content max height - this is
		if ( ! is_editor_page() ) {
			$_height = intval( $config['l_cmh'] );
			/* we need to substract 30px, the padding of the lightbox - when not in editing mode */
			$config['l_cmh'] = ( $_height - 30 ) . 'px';
		}
		$html['inner']['css'] .= "max-height:{$config['l_cmh']}";
	}

	return $html;
}

/**
 * fills in some default font data and adds the custom font to the custom fonts list
 *
 * @return array the full array for the added font
 */
function tve_add_custom_font( $font_data ) {
	$custom_fonts = tve_get_all_custom_fonts();

	if ( ! isset( $font_data['font_id'] ) ) {
		$font_data['font_id'] = count( $custom_fonts ) + 1;
	}

	if ( ! isset( $font_data['font_class'] ) ) {
		$font_data['font_class'] = 'ttfm' . $font_data['font_id'];
	}
	if ( ! isset( $font_data['custom_css'] ) ) {
		$font_data['custom_css'] = '';
	}
	if ( ! isset( $font_data['font_color'] ) ) {
		$font_data['font_color'] = '';
	}
	if ( ! isset( $font_data['font_height'] ) ) {
		$font_data['font_height'] = '1.6em';
	}
	if ( ! isset( $font_data['font_size'] ) ) {
		$font_data['font_size'] = '1.6em';
	}
	if ( ! isset( $font_data['font_character_set'] ) ) {
		$font_data['font_character_set'] = 'latin';
	}

	$custom_fonts [] = $font_data;

	update_option( 'thrive_font_manager_options', json_encode( $custom_fonts ) );

	return $font_data;
}

/**
 * run any necessary code that would be required during an upgrade
 *
 * @param $old_version
 * @param $new_version
 */
function tve_run_plugin_upgrade( $old_version, $new_version ) {
	if ( version_compare( $old_version, '1.74', '<' ) ) {
		/**
		 * refactoring of user templates
		 */
		$user_templates = get_option( 'tve_user_templates', array() );
		$css            = get_option( 'tve_user_templates_styles' );
		$new_templates  = array();
		if ( ! empty( $user_templates ) ) {
			foreach ( $user_templates as $name => $content ) {
				if ( is_array( $content ) ) {
					continue;
				}
				$found            = true;
				$new_templates [] = array(
					'name'    => urldecode( stripslashes( $name ) ),
					'content' => stripslashes( $content ),
					'css'     => isset( $css[ $name ] ) ? trim( stripslashes( $css[ $name ] ) ) : ''
				);
			}
		}

		if ( isset( $found ) ) {
			usort( $new_templates, 'tve_tpl_sort' );
			update_option( 'tve_user_templates', $new_templates );
			delete_option( 'tve_user_templates_styles' );
		}
	}
}

/**
 * ajax listener - saves control panel display configuration when user updates in front end.  Options are saved globally, rather than at post level
 */
function tve_editor_display_config() {
	$attribute = $_POST['attribute'];
	$value     = $_POST['value'];
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
		$options               = tve_cpanel_attributes();
		$options[ $attribute ] = $value;
		update_option( 'tve_cpanel_config', $options );

		return 1;
	}
}

/**
 * determine whether the user is on the editor page or not (also takes into account edit capabilities)
 *
 * @return bool
 */
function is_editor_page() {
	/**
	 * during AJAX calls, we need to apply a filter to get this value, we cannot rely on the traditional detection
	 */
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$is_editor_page = apply_filters( 'tcb_is_editor_page_ajax', false );
		if ( $is_editor_page ) {
			return true;
		}
	}

	if ( ! is_singular() || ( class_exists( 'PostGridHelper' ) && PostGridHelper::$render_post_grid === false ) ) {
		return false;
	}

	if ( isset( $_GET[ TVE_EDITOR_FLAG ] ) && ( current_user_can( 'edit_post', get_the_ID() ) ) && tve_membership_plugin_can_display_content() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * check if there is a valid activated license for the TCB plugin
 *
 * @return bool
 */
function tve_tcb__license_activated() {
	return TVE_Dash_Product_LicenseManager::getInstance()->itemActivated( TVE_Dash_Product_LicenseManager::TCB_TAG );
}

/**
 * determine whether the user is on the editor page or not based just on a $_GET parameter
 * modification: WP 4 removed the "preview" parameter
 *
 * @return bool
 */
function is_editor_page_raw() {
	/**
	 * during AJAX calls, we need to apply a filter to get this value, we cannot rely on the traditional detection
	 */
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$is_editor_page = apply_filters( 'tcb_is_editor_page_raw_ajax', false );
		if ( $is_editor_page ) {
			return true;
		}
	}
	if ( isset( $_GET[ TVE_EDITOR_FLAG ] ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * only enqueue scripts on our own editor pages
 */
function tve_enqueue_editor_scripts() {
	if ( is_editor_page() && tve_is_post_type_editable( get_post_type( get_the_ID() ) ) ) {

		/**
		 * the constant should be defined somewhere in wp-config.php file
		 */
		$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

		/**
		 * this is to handle the following case: an user who has the TL plugin (or others) installed, TCB installed and enabled, but TCB license is expired
		 * in this case, users should still be able to edit stuff from outside the TCB plugin, such as forms
		 */
		if ( tve_tcb__license_activated() || apply_filters( 'tcb_skip_license_check', false ) ) {
			$post_id = get_the_ID();

			/**
			 * apply extra filters that should check if the user can actually use the editor to edit this particular piece of content
			 */
			if ( apply_filters( 'tcb_user_can_edit', true, $post_id ) ) {

				//set nonce for security
				$ajax_nonce = wp_create_nonce( "tve-le-verify-sender-track129" );

				global $tve_shortcode_colours;
				global $tve_style_family_classes;

				// thrive content builder javascript file (loaded both frontend and backend).
				tve_enqueue_script( 'tve_frontend', tve_editor_js() . '/thrive_content_builder_frontend' . $js_suffix, array(
					'jquery',
					'editor'
				), false, true );
				/**
				 * enqueue resizable for older WP versions
				 */
				wp_enqueue_script( 'jquery-ui-resizable' );
				tve_enqueue_script( "tve_drag", tve_editor_js() . '/util/drag' . $js_suffix, array(
					'jquery',
					'jquery-ui-resizable',
				) );
				tve_enqueue_script( "tve_event_manager", tve_editor_js() . '/util/events' . $js_suffix, array( 'jquery' ) );
				tve_enqueue_script( "tve_controls", tve_editor_js() . '/util/controls' . $js_suffix, array(
					'jquery',
					'tve-rangy-core'
				) );
				tve_enqueue_script( "tve_colors", tve_editor_js() . '/util/colors' . $js_suffix, array( 'jquery' ) );
				tve_enqueue_script( "tve_auto_responder", tve_editor_js() . '/util/auto-responder' . $js_suffix, array( 'jquery' ) );
				tve_enqueue_script( "tve_social", tve_editor_js() . '/util/social' . $js_suffix, array( 'jquery' ) );

				/** control panel scripts and dependencies */
				tve_enqueue_script( "tve_editor", tve_editor_js() . '/editor' . $js_suffix,
					array(
						'jquery',
						'tve_drag',
						'tve_event_manager',
						'tve_controls',
						'tve_colors',
						'tve_auto_responder',
						'jquery-ui-autocomplete',
						'jquery-ui-slider',
						'jquery-ui-datepicker',
						'iris',
						'tve_clean_html',
						'tve_undo_manager',
						'tve-rangy-core',
						'tve-rangy-css-module',
						'tve-rangy-save-restore-module',
					), false, true );

				// jQuery UI stuff
				// no need to append TVE_VERSION for these scripts
				wp_enqueue_script( "jquery" );
				wp_enqueue_script( 'jquery-serialize-object' );
				wp_enqueue_script( "jquery-ui-core", array( 'jquery' ) );
				wp_enqueue_script( 'jquery-ui-autocomplete' );
				wp_enqueue_script( "jquery-ui-slider", array( 'jquery', 'jquery-ui-core' ) );
				tve_enqueue_script( "jquery-ui-datepicker", array( 'jquery', 'jquery-ui-core' ) );

				wp_enqueue_script( "jquery-masonry", array( 'jquery' ) );

				wp_enqueue_script( "jquery-ui-datepicker", array( 'jquery', 'jquery-ui-core' ) );

				if ( tve_check_if_thrive_theme() ) {
					wp_enqueue_script( 'thrive-datetime-picker', get_template_directory_uri() . '/inc/js/jquery-ui-timepicker.js', array( 'jquery-ui-datepicker' ) );
				}
				wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
					'jquery',
					'jquery-ui-draggable',
					'jquery-ui-droppable',
					'jquery-ui-slider',
					'jquery-touch-punch'
				), false, 1 );

				// WP colour picker - this is now needed only if a Thrive Theme is used - to allow colorpicker options on the WordPress Content element
				wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array(
					'jquery',
					'iris'
				), false, 1 );
				wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
					'clear'         => __( 'Clear', 'thrive-cb' ),
					'defaultString' => __( 'Default', 'thrive-cb' ),
					'pick'          => __( 'Select Color', 'thrive-cb' ),
					'current'       => __( 'Current Color', 'thrive-cb' ),
				) );
				// WP colour picker
				wp_enqueue_style( 'wp-color-picker' );

				// helper scripts for various functions
				wp_enqueue_script( "tve_clean_html", tve_editor_js() . '/jquery.htmlClean.min.js', array( 'jquery' ), '1.0.0', true );
				wp_enqueue_script( "tve_undo_manager", tve_editor_js() . '/tve_undo_manager' . $js_suffix, array( 'jquery' ), '1.0.0', true );

				// rangy for selection
				wp_enqueue_script( "tve-rangy-core", tve_editor_js() . '/rangy-core.js', array( 'jquery' ) );
				wp_enqueue_script( "tve-rangy-css-module", tve_editor_js() . '/rangy-cssclassapplier.js', array(
					'jquery',
					'tve-rangy-core'
				) );
				wp_enqueue_script( "tve-rangy-save-restore-module", tve_editor_js() . '/rangy-selectionsaverestore.js', array(
					'jquery',
					'tve-rangy-core'
				) );
				wp_enqueue_style( 'jquery-ui-datepicker', tve_editor_css() . '/jquery-ui-1.10.4.custom.min.css' );

				// now enqueue the styles
				tve_enqueue_style( "tve_editor_style", tve_editor_css() . '/editor.css' );

				if ( tve_check_if_thrive_theme() ) {
					/* include the css needed for the shortcodes popup (users are able to insert Thrive themes shortcode inside the WP editor on frontend) - using the "Insert WP Shortcode" element */
					tve_enqueue_style( 'tve_shortcode_popups', tve_editor_css() . '/thrive_shortcodes_popup.css' );
				}

				if ( is_rtl() ) {
					tve_enqueue_style( "tve_rtl", tve_editor_css() . '/editor_rtl.css' );
				}

				// load style family
				$loaded_style_family = tve_get_style_family( $post_id );

				// load any custom css
				tve_load_custom_css();

				/**
				 * scan templates directory and build array of template file names
				 */
				$template_files = array();
				$templates_path = dirname( dirname( __FILE__ ) ) . '/shortcodes/templates/';
				if ( function_exists( 'scandir' ) ) {
					$template_files = scandir( $templates_path, 1 );

				} else if ( $handle = opendir( $templates_path ) ) { //some servers have scandir function disabled for security reasons

					while ( false !== ( $entry = readdir( $handle ) ) ) {
						array_push( $template_files, $entry );
					}
					closedir( $handle );
				}

				$shortcode_files = array_diff( $template_files, array(
					"..",
					".",
					"css",
					"fonts",
					"images",
					"html",
					"js"
				) );
				$shortcode_files = array_values( $shortcode_files );//take only values to make sure it will be json encoded as array not object

				// list of credit cards for cc widget
				$tve_cc_icons = array( "cc_amex, cc_discover, cc_mc, cc_paypal, cc_visa" );

				// check if this is a thrive theme or not
				$tve_is_thrive_theme = tve_check_if_thrive_theme();

				// get mapping for custom colour controls
				$tve_colour_mapping = include dirname( dirname( __FILE__ ) ) . '/custom_colour_mappings.php';

				// variables for custom colours used for post
				$tve_remembered_colours       = get_option( "thrv_custom_colours", null );
				$tve_default_tooltip_settings = get_option( "tve_default_tooltip_settings", null );
				if ( ! empty( $tve_default_tooltip_settings['event_tooltip_text'] ) ) {
					$tve_default_tooltip_settings['event_tooltip_text'] = stripslashes( $tve_default_tooltip_settings['event_tooltip_text'] );
				}
				if ( ! $tve_remembered_colours ) {
					$tve_remembered_colours = array();
				}

				$timezone_offset = get_option( 'gmt_offset' );
				$sign            = ( $timezone_offset < 0 ? '-' : '+' );
				$min             = abs( $timezone_offset ) * 60;
				$hour            = floor( $min / 60 );
				$tzd             = $sign . str_pad( $hour, 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $min % 60, 2, '0', STR_PAD_LEFT );

				// if the post is a TCB landing page, get the landing page configuration and send it to javascript
				$landing_page_config = array();
				if ( ( $template = tve_post_is_landing_page( get_the_ID() ) ) !== false ) {
					$landing_page_config = tve_get_landing_page_config( $template );
					if ( ! empty( $landing_page_config['custom_color_mappings'] ) ) {
						$tve_colour_mapping = array_merge_recursive( $tve_colour_mapping, $landing_page_config['custom_color_mappings'] );
						unset( $landing_page_config['custom_color_mappings'] ); // clean it up, we don't want this in our js
					}
					/* if we have specific editor JS for the landing page, include that also */
					if ( ! empty( $landing_page_config['cloud'] ) ) {
						if ( is_file( trailingslashit( $landing_page_config['base_dir'] ) . "js/editor_{$template}{$js_suffix}" ) ) {
							tve_enqueue_script( 'tve_landing_page_editor', trailingslashit( $landing_page_config['base_url'] ) . "js/editor_{$template}{$js_suffix}", array( 'tve_editor' ) );
						}
					} else {
						if ( is_file( plugin_dir_path( dirname( __FILE__ ) ) . "/landing-page/js/editor_{$template}{$js_suffix}" ) ) {
							tve_enqueue_script( 'tve_landing_page_editor', tve_editor_url() . "/landing-page/js/editor_{$template}{$js_suffix}", array( 'tve_editor' ) );
						}
					}
					tve_enqueue_script( 'tve_landing_fonts', tve_editor_url() . '/editor/js/util/landing-fonts' . $js_suffix, array( 'tve_editor' ) );
				}

				// custom fonts from Font Manager
				$all_fonts         = tve_get_all_custom_fonts();
				$all_fonts_enqueue = apply_filters( 'tve_filter_custom_fonts_for_enqueue_in_editor', $all_fonts );
				tve_enqueue_fonts( $all_fonts_enqueue );

				$tve_post_globals = tve_get_post_meta( get_the_ID(), 'tve_globals', true );
				if ( empty( $tve_post_globals ) || ( isset( $tve_post_globals[0] ) && empty( $tve_post_globals[0] ) ) ) {
					$tve_post_globals = array( 'e' => 1 );
				}

				/* landing page template - we need to allow the user to setup head and footer scripts */
				$tve_global_scripts = get_post_meta( get_the_ID(), 'tve_global_scripts', true );
				if ( empty( $template ) || empty( $tve_global_scripts ) ) {
					$tve_global_scripts = array( 'head' => '', 'footer' => '' );
				}
				$tve_global_scripts['head']   = preg_replace( '#<style(.+?)</style>#s', '', $tve_global_scripts['head'] );
				$tve_global_scripts['footer'] = preg_replace( '#<style(.+?)</style>#s', '', $tve_global_scripts['footer'] );

				$post_type = get_post_type( get_the_ID() );

				$page_events = tve_get_post_meta( $post_id, 'tve_page_events' );
				$page_events = empty( $page_events ) ? array() : $page_events;

				$icon_pack     = get_option( 'thrive_icon_pack' );
				$icon_pack_css = ! empty( $icon_pack['css'] ) ? tve_url_no_protocol( $icon_pack['css'] ) . '?ver=' . ( isset( $icon_pack['css_version'] ) ? $icon_pack['css_version'] : TVE_VERSION ) : '';

				/**
				 * we need to enforce this check here, so that we don't make http requests from https pages
				 */
				$admin_base_url = admin_url( '/', is_ssl() ? 'https' : 'admin' );
				// for some reason, the above line does not work in some instances
				if ( is_ssl() ) {
					$admin_base_url = str_replace( 'http://', 'https://', $admin_base_url );
				}

				// pass variables needed to client side
				$tve_path_params = array(
					'admin_url'                     => $admin_base_url,
					'cpanel_dir'                    => tve_editor_url() . '/editor',
					'shortcodes_dir'                => tve_editor_url() . '/shortcodes/templates/',
					'editor_dir'                    => tve_editor_css(),
					'shortcodes_array'              => $shortcode_files,
					'shortcode_colours'             => $tve_shortcode_colours,
					'style_families'                => tve_get_style_families(),
					'style_classes'                 => $tve_style_family_classes,
					'loaded_style'                  => $loaded_style_family,
					'post_id'                       => get_the_ID(),
					'post_url'                      => get_permalink( get_the_ID() ),
					'tve_ajax_nonce'                => $ajax_nonce,
					'tve_version'                   => TVE_VERSION,
					'tve_cc_icons'                  => $tve_cc_icons,
					'tve_colour_mapping'            => $tve_colour_mapping,
					'tve_loaded_stylesheet'         => $loaded_style_family,
					'tve_colour_picker_colours'     => $tve_remembered_colours,
					'ajax_url'                      => $admin_base_url . 'admin-ajax.php',
					//					'font_settings_url'             => $admin_base_url . 'admin.php?page=tve_dash_font_manager', MOVED TO control_panel
					//					'wp_timezone'                   => $tzd,  MOVED TO control_panel
					'is_rtl'                        => (int) is_rtl(),
					'landing_page'                  => $template,
					//					'landing_page_config'           => $landing_page_config,  MOVED TO control_panel
					'custom_fonts'                  => $all_fonts,
					'post_type'                     => $post_type,
					'tve_globals'                   => $tve_post_globals,
					'landing_page_lightbox'         => $post_type === 'tcb_lightbox' ? get_post_meta( get_the_ID(), 'tve_lp_lightbox', true ) : '',
					'tve_global_scripts'            => $tve_global_scripts,
					'extra_body_class'              => version_compare( get_bloginfo( 'version' ), '4.0', '>=' ) ? 'tve_mce_fixed' : '',
					'page_events'                   => $page_events,
					'icon_pack_css'                 => $icon_pack_css,
					'save_post_action'              => 'tve_save_post',
					// this is to allow overriding the default save_post action ajax callback,
					'tve_display_save_notification' => (int) get_option( 'tve_display_save_notification', 1 ),
					'social_default_html'           => tve_social_networks_default_html( null, array(), is_editor_page() ),
					'social_fb_app_id'              => tve_get_social_fb_app_id(),
					'tve_default_tooltip_settings'  => $tve_default_tooltip_settings,
					'cpanel_attr'                   => tve_cpanel_attributes(),
					'translations'                  => array(
						'RequiredValue'                        => __( "Required value", 'thrive-cb' ),
						'CorrectTheMarkedErrors'               => __( 'Please correct the marked errors and try again.', 'thrive-cb' ),
						'NullOrNotDefined'                     => __( "this is null or not defined", 'thrive-cb' ),
						"NotFunction"                          => __( "is not a function", "thrive-cb" ),
						"LeavePageUnsavedChanges"              => __( "If you leave this page then any unsaved changes will be lost", 'thrive-cb' ),
						"TweetLimitAcceptsMax"                 => __( "Tweet text limit accepts maximum 140 characters !", "thrive-cb" ),
						"ErrorOnThisPage"                      => __( "There was an error on this page.", "thrive-cb" ),
						"ErrorDescription"                     => __( "Error description", "thrive-cb" ),
						"ClickOkToContinue"                    => __( "Click OK to continue.", 'thrive-cb' ),
						'ChangesCouldNotBeSaved'               => __( 'The changes could not be saved. Maybe you are logged out from the administration section ? Please open a new tab and check to see if you are logged in', 'thrive-cb' ),
						'NotSufficientPermissions'             => __( 'You do not have sufficient permissions to edit this post', 'thrive-cb' ),
						'YouHaveBeenLoggedOut'                 => __( 'It seems you have been logged out. Please open a new tab and re-login to the admin panel. After that, you should be able to save this content (without refreshing the page).', 'thrive-cb' ),
						'AllChangesSaved'                      => __( "All changes saved!", 'thrive-cb' ),
						"SomethingWentWrong"                   => __( "Something went wrong! You might not have the required user capability.", 'thrive-cb' ),
						"LeadGenerationEmail"                  => __( "Please enter a valid email address", 'thrive-cb' ),
						'LeadGenerationPhone'                  => __( "Please enter a valid phone number", 'thrive-cb' ),
						"LeadGenerationRequired"               => __( "Please fill in all of the required fields", 'thrive-cb' ),
						"ClickToReplaceTheAnchorText"          => __( "Click to replace anchor text", 'thrive-cb' ),
						'NewTemplateAdded'                     => __( "New template added!", 'thrive-cb' ),
						'YouAreTryingToChangeTheSizeOfIcon'    => __( 'You are trying to change the size of an icon, however the button currently contains an image. Click on the "Use Image" button to modify the size', 'thrive-cb' ),
						"OnlyNumbersAreAccepted"               => __( "Only numbers are accepted !", 'thrive-cb' ),
						'FontSizeShouldBeBetween'              => __( 'Font size should be between 6 and 200 pixels !', 'thrive-cb' ),
						"RedirectionUrlInvalid"                => __( "The redirection will not take place because the URL you've entered is not valid !", 'thrive-cb' ),
						'OnlyDigitsAccepted'                   => __( 'Only digits are accepted or "none" string value !', 'thrive-cb' ),
						'MaxWidthCannotBeSmaller'              => __( 'Max width cannot be smaller than 100 pixels !', 'thrive-cb' ),
						'OnlyDigitsAreAccepted'                => __( 'Only digits are accepted !', 'thrive-cb' ),
						'MinWidthLowerZero'                    => __( 'Min width cannot be lower than zero !', 'thrive-cb' ),
						'OnlyThreeHeadingsSelected'            => __( 'Only three headings can be selected at once !', 'thrive-cb' ),
						'DeleteContentInLandingPage'           => __( "Are you sure you want to DELETE all of the content that was created in this landing page and revert to the theme page?\nIf you click OK, any custom content you added to the landing page will be deleted.", 'thrive-cb' ),
						'SelectTemplate'                       => __( 'First, you must select a template from the available ones.', 'thrive-cb' ),
						'ClearContentOfLandingPage'            => __( 'Are you sure you want to CLEAR all content from this Landing Page? This action cannot be undone', 'thrive-cb' ),
						'EnterTemplateName'                    => __( 'Please enter a template name, it will be easier to reload it after.', 'thrive-cb' ),
						'SelectSavedTemplate'                  => __( 'Please select a saved template first', 'thrive-cb' ),
						'DeleteSavedTemplate'                  => __( 'Are you sure you want to delete this saved template? This action cannot be undone', 'thrive-cb' ),
						'ChooseExistingAPI'                    => __( 'Please choose an existing API connection and a mailing list!', 'thrive-cb' ),
						'RemoveCustomFields'                   => __( 'Remove', 'thrive-cb' ),
						'RemoveContentTemplate'                => __( 'Are you sure you want to remove this Content Template? This action cannot be undone', 'thrive-cb' ),
						'RemoveHighlightedColumn'              => __( 'Remove highlighted column', 'thrive-cb' ),
						'AddHighlightedColumn'                 => __( 'Add highlighted column', 'thrive-cb' ),
						'EventAlreadyRegisteredForThisTrigger' => __( 'An Event is already registered for this Trigger and Action. Please choose a different configuration.' ),
						'ErrorValidatingID'                    => __( 'Error validating the ID. Please check your input and try again', 'thrive-cb' ),
						'PleaseSetCorrectAppID'                => __( 'Please set a correct App ID and validate it using the "Validate App ID" button', 'thrive-cb' ),
						'TweetContainsTooManyCharacters'       => __( 'The tweet contains too many characters. Please shorten your message', 'thrive-cb' ),
						'UsernameRequired'                     => __( 'Username is required', 'thrive-cb' ),
						'ExportFileNameRequired'               => __( 'Template name is required', 'thrive-cb' ),
						'UnknownError'                         => __( 'An unknown error has occured. Response was: ', 'thrive-cb' ),
						'LPImportConfirm'                      => __( 'Importing a landing page will overwrite the current contents of this page. Are you sure you want to continue ?', 'thrive-cb' ),
						'InvalidImageSelected'                 => __( 'Invalid file selected. Please select an image.', 'thrive-cb' ),
						'AddTextVariation'                     => __( "Please add text for last variation !", 'thrive-cb' ),
						'TypeFocusVariationSpeed'              => __( "You cannot set the slide speed below 1000 ms !", "thrive-cb" ),
						'TwitterShareCountDisabled'            => __( 'The total share count cannot be displayed if only Twitter share is selected. Twitter has discontinued support for the public count API' ),
						'WistiaVideoPlaceholder'               => __( 'Wistia Popover Video placeholder' ),
						'ValidWistiaUrl'                       => __( "Please enter a valid Wistia URL", 'thrive-cb' ),
						'Downloading'                          => __( 'Downloading...', 'thrive-cb' ),
						'Template_updated'                     => __( 'Template updated', 'thrive-cb' ),
					)
				);

				$tve_path_params = apply_filters( 'tcb_editor_javascript_params', $tve_path_params, $post_id, $post_type );

				wp_localize_script( 'tve_editor', 'tve_path_params', $tve_path_params );

				/* some params will be needed also for the frontend script */
				$frontend_options = array(
					'is_editor_page'   => true,
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'social_fb_app_id' => tve_get_social_fb_app_id(),
				);
				wp_localize_script( 'tve_frontend', 'tve_frontend_options', $frontend_options );

				/** some themes have hooks defined here, which rely on functions defined only in the admin part - these will not be defined on frontend */
				remove_all_filters( 'media_view_settings' );
				// enqueue scripts for tapping into media thickbox
				wp_enqueue_media();
			}

		} else {
			add_action( 'wp_print_footer_scripts', 'tve_license_notice' );
		}
	}

}

/**
 * enqueue the associated style family for a post / page
 *
 * this also gets called in archive (list) pages, there we need to load style families for each post from the list
 *
 * @param null $post_id optional this will only come filled in when calling it from a lightbox
 */
function tve_enqueue_style_family( $post_id = null ) {
	global $tve_style_family_classes, $wp_query;
	$tve_style_families = tve_get_style_families();

	if ( null === $post_id ) {
		$posts_to_load = $wp_query->posts;
		if ( empty( $posts_to_load ) || ! is_array( $posts_to_load ) ) {
			return;
		}
		$post_id = array();
		foreach ( $posts_to_load as $post ) {
			$post_id [] = $post->ID;
		}
	} else {
		$post_id = array( $post_id );
	}

	foreach ( $post_id as $p_id ) {
		$current_post_style = tve_get_style_family( $p_id );

		$style_key = 'tve_style_family_' . strtolower( $tve_style_family_classes[ $current_post_style ] );
		if ( ! wp_style_is( $style_key ) ) {
			tve_enqueue_style( $style_key, $tve_style_families[ $current_post_style ] );
		}
	}
}

/**
 * retrieve the style family used for a specific post / page
 *
 * @param        $post_id
 * @param string $default
 */
function tve_get_style_family( $post_id, $default = 'Flat' ) {
	$tve_style_families = tve_get_style_families();
	$current_post_style = get_post_meta( $post_id, "tve_style_family", true );

	// Flat is default style family if nothing set
	$current_post_style = empty( $current_post_style ) || ! isset( $tve_style_families[ $current_post_style ] ) ? $default : $current_post_style;

	return $current_post_style;
}

/**
 * get the css class for a style family
 *
 * @param int $post_id
 *
 * @return string
 */
function tve_get_style_family_class( $post_id ) {
	global $tve_style_family_classes;
	$style_family = get_post_meta( $post_id, 'tve_style_family', true );

	return ! empty( $style_family ) && isset( $tve_style_family_classes[ $style_family ] ) ? $tve_style_family_classes[ $style_family ] : $tve_style_family_classes['Flat'];
}

/**
 * ajax function for updating post meta with the current style family
 */
function tve_change_style_family() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
		if ( ob_get_contents() ) {
			ob_clean();
		}
		// style family should remain the same when switching over to landing page and back
		update_post_meta( $_POST['post_id'], "tve_style_family", $_POST['style_family'] );
		die;
	}
}

/**
 * prepare the notification bar to be displayed in the footer on editor pages
 */
function tve_add_notification_box() {
	echo '<div id="tve_notification_box"></div>';
}

/**
 * save a user-defined template, which can be either the whole page contents, or a custom element
 */
function tve_save_user_template() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );

	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
		$new_template       = $_POST;
		$existing_templates = get_option( "tve_user_templates" );

		// check if template name exists.  If it does, return error. If not save to database and return new list of template names
		if ( $existing_templates && is_array( $existing_templates ) ) {
			foreach ( $existing_templates as $tpl ) {
				if ( is_array( $tpl ) && ! empty( $tpl['name'] ) && $tpl['name'] == $new_template['template_name'] ) {
					$response = array(
						"success" => 0,
						"error"   => __( "That template name already exists, please use another name", 'thrive-cb' )
					);
				}
			}
		}
		if ( ! isset( $response ) ) {
			if(!is_array( $existing_templates )){
				$existing_templates = array();
			}
			$existing_templates [] = array(
				'name'      => $new_template['template_name'],
				'content'   => $new_template['template_content'],
				'css'       => $new_template['custom_css'],
				'media_css' => $new_template['media_rules'],
			);

			usort( $existing_templates, 'tve_tpl_sort' );
			update_option( "tve_user_templates", $existing_templates );

			$html = '';
			foreach ( $existing_templates as $tpl_key => $tpl ) {
				$html .= '<li id="tve_user_template" class="cp_draggable user_template_item tve_clearfix" draggable="true" data-key="' . $tpl_key . '" data-usertpl="1">' .
				         '<input type="hidden" class="tpl_key" value="' . $tpl_key . '" />' .
				         '<span class="tve_left">' . urldecode( stripslashes( $tpl['name'] ) ) . '</span>' .
				         '<div class="tve_icm tve-ic-close tve_right tve_click" style="float: right; font-size: 11px; color: #bd362f" data-ctrl="controls.click.remove_user_template"></div>' .
				         '</li>';
			}
			$html .= '<li class="hide-on-tpl-save"' . ( $html ? ' style="display: none"' : '' ) . '>No Content Templates yet</li>';

			$response = array(
				"success" => 1,
				"html"    => $html
			);
		}
		if ( ob_get_contents() ) {
			ob_clean();
		}
		$response = json_encode( $response );
		echo $response;
	}
	die;
}

/**
 * output a JSON-encoded list of user-saved templates
 *
 */
function tve_load_user_template() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
		$templates = get_option( "tve_user_templates" );
		$key       = (int) $_POST['template_key'];

		$response = array(
			'html_code' => stripslashes( $templates[ $key ]['content'] ),
			'css_code'  => stripslashes( $templates[ $key ]['css'] ),
			'media_css' => isset( $templates[ $key ]['media_css'] ) ? array_map( 'stripslashes', $templates[ $key ]['media_css'] ) : null,

		);
		if ( ob_get_contents() ) {
			ob_clean();
		}
		exit( json_encode( $response ) );
	}
}

/**
 * delete a user-saved template from the db
 */
function tve_remove_user_template() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
		$templates = get_option( "tve_user_templates" );
		$key       = (int) $_POST['template_key'];

		unset( $templates[ $key ] );

		usort( $templates, 'tve_tpl_sort' );
		update_option( "tve_user_templates", $templates );

		$html = '';
		foreach ( $templates as $tpl_key => $tpl ) {
			$html .= '<li id="tve_user_template" class="cp_draggable user_template_item tve_clearfix" draggable="true">' .
			         '<input type="hidden" class="tpl_key" value="' . $tpl_key . '" />' .
			         '<span class="tve_left">' . urldecode( stripslashes( $tpl['name'] ) ) . '</span>' .
			         '<div class="tve_icm tve-ic-close tve_right tve_click" style="float: right; font-size: 11px; color: #bd362f" data-ctrl="controls.click.remove_user_template"></div>' .
			         '</li>';
		}
		$html .= '<li class="hide-on-tpl-save"' . ( $html ? ' style="display: none"' : '' ) . '>' . __( "No Content Templates yet", "thrive-cb" ) . '</li>';
		exit( $html );
	}
}

/**
 * Loads user defined custom css in the header to override style family css
 * If called with $post_id != null, it will load the custom css and user custom css from inside the loop (in case of homepage consisting of other pages, for example)
 */
function tve_load_custom_css( $post_id = null ) {
	if ( is_feed() ) {
		return;
	}
	if ( ! is_null( $post_id ) ) {
		$custom_css = trim( tve_get_post_meta( $post_id, "tve_custom_css", true ) . tve_get_post_meta( $post_id, 'tve_user_custom_css', true ) );
		if ( $custom_css ) {
			echo sprintf(
				'<style type="text/css" class="tve_custom_style">%s</style>',
				$custom_css
			);
		}

		return;
	}
	global $wp_query;
	$posts_to_load = $wp_query->posts;

	global $css_loaded_post_id;
	$css_loaded_post_id = array();

	/* user-defined css from the Custom CSS content element */
	$user_custom_css = '';
	if ( $posts_to_load ) {

		$inline_styles = '';
		foreach ( $posts_to_load as $post ) {
			$inline_styles .= tve_get_post_meta( $post->ID, "tve_custom_css", true );
			$user_custom_css .= tve_get_post_meta( $post->ID, 'tve_user_custom_css', true );
			array_push( $css_loaded_post_id, $post->ID );
		}

		if ( ! empty( $inline_styles ) ) {
			?>
			<style type="text/css"
			       class="tve_custom_style"><?php echo $inline_styles ?></style><?php
		}
		/* also check for user-defined custom CSS inserted via the "Custom CSS" content editor element */
		echo $user_custom_css ? sprintf( '<style type="text/css" id="tve_head_custom_css" class="tve_user_custom_style">%s</style>', $user_custom_css ) : '';
	}
}

/**
 * checks to see if content being loaded is actually being loaded from within the loop (correctly) or being pulled
 * incorrectly to make up another page (for instance, a homepage that pulls different sections from pieces of content)
 */
function tve_check_in_loop( $post_id ) {
	global $css_loaded_post_id;
	if ( ! empty( $css_loaded_post_id ) && in_array( $post_id, $css_loaded_post_id ) ) {
		return true;
	}

	return false;
}

/**
 * replace [tcb-script] with script tags
 *
 * @param array $matches
 *
 * @return string
 */
function tve_restore_script_tags_replace( $matches ) {
	$matches[2] = str_replace( '<\\/script', '<\\\\/script', $matches[2] );

	return '<script' . $matches[1] . '>' . html_entity_decode( $matches[2] ) . '</script>';
}

/**
 * replace [tcb-noscript] with <noscript> tags
 *
 * @param array $matches
 *
 * @return string
 */
function tve_restore_script_tags_noscript_replace( $matches ) {
	return '<noscript' . $matches[1] . '>' . html_entity_decode( $matches[2] ) . '</noscript>';
}

/**
 * restore all script tags from custom html controls. script tags are replaced with <code class="tve_js_placeholder">
 *
 * @param string $content
 *
 * @return string having all <code class="tve_js_placeholder">..</code> replaced with their script tag equivalent
 */
function tve_restore_script_tags( $content ) {
	$shortcode_js_pattern = '/\[tcb-script(.*?)\](.*?)\[\/tcb-script\]/s';
	$content              = preg_replace_callback( $shortcode_js_pattern, 'tve_restore_script_tags_replace', $content );

	$shortcode_nojs_pattern = '/\[tcb-noscript(.*?)\](.*?)\[\/tcb-noscript\]/s';
	$content                = preg_replace_callback( $shortcode_nojs_pattern, 'tve_restore_script_tags_noscript_replace', $content );

	return $content;
}

/**
 * get a list of all published Thrive Opt-Ins post types
 *
 * @return array pairs id => title
 */
function tve_get_thrive_optins() {
	$optins = array();

	$args = array(
		'posts_per_page' => null,
		'numberposts'    => null,
		'post_type'      => 'thrive_optin'
	);

	foreach ( get_posts( $args ) as $post ) {
		$optins[ $post->ID ] = $post->post_title;
	}

	return $optins;
}

/**
 * Thrive Shortcode callback that will call apply_filters on "tve_additional_fields" tag
 *
 * @see tve_thrive_shortcodes
 *
 * @param array $data with [group_id, form_type_id, variation_id]
 *
 * @return mixed|void
 */
function tve_leads_additional_fields_filters( $data ) {
	$group     = $data['group_id'];
	$form_type = $data['form_type_id'];
	$variation = $data['variation_id'];

	if ( ! empty( $form_type ) && function_exists( 'tve_leads_get_form_type' ) ) {
		$form_type = tve_leads_get_form_type( $form_type, array( 'get_variations' => false ) );
		if ( $form_type && $form_type->post_parent ) {
			$group = get_post( $form_type->post_parent );
		}
	}

	if ( ! empty( $variation ) && function_exists( 'tve_leads_get_form_variation' ) ) {
		$variation = tve_leads_get_form_variation( null, $variation );
		if ( ! empty( $variation['parent_id'] ) ) {
			$variation = tve_leads_get_form_variation( null, $variation['parent_id'] );
		}
	}

	return apply_filters( 'tve_additional_fields', '', $group, $form_type, $variation );
}

/**
 * parse content for configuration that belongs to theme-equivalent shortcodes, e.g. Opt-in shortcode
 *
 * for each key from $tve_thrive_shortcodes, it will search the content string for __CONFIG_{$key}__(.+)__CONFIG_{$key}__
 * if elements are found, the related callback will be called with the contents from between the two flags (this is a json_encoded string)
 *
 * shortcode configuration is held in JSON-encoded format inside a hidden div
 * these contents will get deleted if we're currently NOT in editor mode
 *
 * @param string $content
 * @param bool $keepConfig
 */
function tve_thrive_shortcodes( $content, $keepConfig = false ) {
	global $tve_thrive_shortcodes;

	$shortcode_pattern = '#>__CONFIG_%s__(.+?)__CONFIG_%s__</div>#';

	foreach ( $tve_thrive_shortcodes as $shortcode => $callback ) {
		if ( ! tve_check_if_thrive_theme() && $shortcode !== 'widget' && $shortcode !== 'post_grid' && $shortcode !== 'widget_menu' && $shortcode !== 'leads_shortcode' && $shortcode !== 'tve_leads_additional_fields_filters' && $shortcode !== 'social_default' && $shortcode !== 'tvo_shortcode' && $shortcode != 'ultimatum_shortcode' ) {
			continue;
		}

		if ( ! function_exists( $callback ) ) {
			continue;
		}

		/**
		 * we dont want to apply this shortcode if $keepConfig is true => is_editor
		 */
		if ( $shortcode === 'tve_leads_additional_fields_filters' && $keepConfig === true ) {
			continue;
		}

		/*
         * match all instances of the current shortcode
         */
		if ( preg_match_all( sprintf( $shortcode_pattern, $shortcode, $shortcode ), $content, $matches, PREG_OFFSET_CAPTURE ) !== false ) {
			/* as we go over the $content and replace each shortcode, we must take into account the differences of replacement length and the length of the part getting replaced */
			$position_delta = 0;
			foreach ( $matches[1] as $i => $data ) {
				$m          = $data[0]; // the actual matched regexp group
				$position   = $matches[0][ $i ][1] + $position_delta; //the index at which the whole group starts in the string, at the current match
				$wholeGroup = $matches[0][ $i ][0];
				$json_safe  = tve_json_utf8_slashit( $m );
				if ( ! ( $_params = @json_decode( $json_safe, true ) ) ) {
					$_params = array();
				}
				$replacement = call_user_func( $callback, $_params, $keepConfig );
				if ( $callback === 'tve_do_post_grid_shortcode' && PostGridHelper::$render_post_grid === false ) {
					$keepConfig = false;
				}
				$replacement = ( $keepConfig ? ">__CONFIG_{$shortcode}__{$m}__CONFIG_{$shortcode}__</div>" : "></div>" ) . $replacement;

				$content = substr_replace( $content, $replacement, $position, strlen( $wholeGroup ) );
				/* increment the positioning offsets for the string with the difference between replacement and original string length */
				$position_delta += strlen( $replacement ) - strlen( $wholeGroup );

			}
		}
	}

	// we include the wistia js only if wistia popover responsive video is added to the content (div with class tve_wistia_popover)
	if ( ! $keepConfig && strpos( $content, "tve_wistia_popover" ) !== false ) {
		wp_script_is( 'tl-wistia-popover' ) || wp_enqueue_script( 'tl-wistia-popover', '//fast.wistia.com/assets/external/E-v1.js', array(), '', true );
	}

	return $content;
}

/**
 * Render post grid shortcode
 * Called from shortcode parser and when user drags element into page
 */
function tve_do_post_grid_shortcode( $config ) {
	if ( defined( 'DOING_AJAX' ) && isset( $_POST['tve_lb_type'] ) ) {
		//user drags new element
		check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	}

	require_once dirname( dirname( __FILE__ ) ) . '/editor/inc/helpers/post_grid.php';

	$post_grid_helper = new PostGridHelper( empty( $config ) ? $_POST : $config );

	if ( defined( 'DOING_AJAX' ) && isset( $_POST['tve_lb_type'] ) ) {
		//user drags new element
		echo $post_grid_helper->render();
		die;
	}

	$post_grid_helper->wrap = false;
	$html                   = $post_grid_helper->render();

	return $html;
}

/**
 * handle the Opt-In shortcode from the themes
 *
 * at this point this just forwards the call to the theme's Opt-In shortcode
 *
 * TODO: perhaps we can use the call to thrive_shortcode_optin (and other shortcodes) directly (?)
 *
 * @param array $attrs
 */
function tve_do_optin_shortcode( $attrs ) {
	return '<div class="thrive-shortcode-html">' . thrive_shortcode_optin( $attrs, '' ) . '</div>';
}

/**
 * handle the posts lists shortcode from the themes.  Full docs in function tve_do_optin_shortcode comments
 *
 * @param $attrs
 *
 * @return string
 */
function tve_do_posts_list_shortcode( $attrs ) {
	return '<div class="thrive-shortcode-html">' . thrive_shortcode_posts_list( $attrs, '' ) . '</div>';
}

/**
 * handle the leads shortcode
 *
 * @param $attr
 *
 * @return string
 */
function tve_do_leads_shortcode( $attrs ) {
	$error_content = '<div class="thrive-shortcode-html"><p>Thrive Leads Shortcode could not be rendered, please check it in Thrive Leads Section!</p></div>';
	if ( ! function_exists( 'tve_leads_shortcode_render' ) ) {
		return $error_content;
	}

	if ( is_editor_page() ) {
		$attrs['for_editor'] = true;
		$content             = tve_leads_shortcode_render( $attrs );
		$content             = ! empty( $content['html'] ) ? $content['html'] : '';
	} else {
		$content = tve_leads_shortcode_render( $attrs );
	}

	if ( empty( $content ) ) {
		return $error_content;
	}

	return '<div class="thrive-shortcode-html">' . str_replace( 'tve_editor_main_content', '', $content ) . '</div>';
}

/**
 * handle the custom menu shortcode
 *
 * @param $atts
 *
 * @return string
 */
function tve_do_custom_menu_shortcode( $atts ) {
	return '<div class="thrive-shortcode-html">' . thrive_shortcode_custom_menu( $atts, '' ) . '</div>';
}

/**
 * handle the custom phone shortcode
 *
 * @param $atts
 *
 * @return string
 */
function tve_do_custom_phone_shortcode( $atts ) {
	return '<div class="thrive-shortcode-html">' . thrive_shortcode_custom_phone( $atts, '' ) . '</div>';
}

/**
 * process and display wp editor contents
 * used in "Insert Shortcode" element
 */
function tve_render_shortcode() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	if ( empty( $_POST['content'] ) ) {
		exit( '' );
	}

	echo tcb_render_wp_shortcode( stripslashes( $_POST['content'] ) );
	exit();
}

/**
 * mimics all wordpress called functions when rendering a shortcode
 *
 * @param $content
 */
function tcb_render_wp_shortcode( $content ) {
	$content = wptexturize( stripslashes( $content ) );
	$content = convert_smilies( $content );
	$content = convert_chars( $content );
	$content = wpautop( $content );
	$content = shortcode_unautop( $content );
	$content = shortcode_unautop( wptexturize( $content ) );

	return is_editor_page() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? do_shortcode( $content ) : $content;
}

/**
 * render any shortcodes that might be included in the post meta-data using the Insert Shortcode element
 * raw shortcode texts are saved between 2 flags: ___TVE_SHORTCODE_RAW__ AND __TVE_SHORTCODE_RAW___
 *
 * @param string $content
 * @param bool $is_editor_page
 */
function tve_do_wp_shortcodes( $content, $is_editor_page = false ) {
	/**
	 * replace all the {tcb_post_} shortcodes with actual values
	 */
	if ( ! $is_editor_page ) {
		/**
		 * if we are currently redering a TCB lightbox, we still need to have the main post title, url etc
		 */
		if ( ! empty( $GLOBALS['tcb_main_post_lightbox'] ) ) {
			$post_id = $GLOBALS['tcb_main_post_lightbox']->ID;
		} else {
			$post_id = get_the_ID();
		}
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		$permalink      = get_permalink( $post_id ); // TODO: I think get_the_permalink is slow, we need to cache this somehow
		$content        = str_replace( array(
			'{tcb_post_url}',
			'{tcb_encoded_post_url}',
			'{tcb_post_title}',
			'{tcb_post_image}',
			'{tcb_current_year}'
		), array(
			$permalink,
			urlencode( $permalink ),
			get_the_title( $post_id ),
			! empty( $featured_image ) && ! empty( $featured_image[0] ) ? $featured_image[0] : '',
			date( 'Y' )
		), $content );
	}

	list( $start, $end ) = array(
		'___TVE_SHORTCODE_RAW__',
		'__TVE_SHORTCODE_RAW___'
	);

	if ( strpos( $content, $start ) === false ) {
		return $content;
	}
	if ( ! preg_match_all( "/{$start}((<p>)?(.+?)(<\/p>)?){$end}/s", $content, $matches, PREG_OFFSET_CAPTURE ) ) {
		return $content;
	}

	$position_delta = 0;
	foreach ( $matches[1] as $i => $data ) {
		$raw_shortcode = $data[0]; // the actual matched regexp group
		$position      = $matches[0][ $i ][1] + $position_delta; //the index at which the whole group starts in the string, at the current match
		$wholeGroup    = $matches[0][ $i ][0];

		$replacement = tcb_render_wp_shortcode( $raw_shortcode );
		$replacement = ( $is_editor_page ? $wholeGroup : '' ) . ( '</div><div class="tve_shortcode_rendered">' . $replacement );

		$content = substr_replace( $content, $replacement, $position, strlen( $wholeGroup ) );
		/* increment the positioning offsets for the string with the difference between replacement and original string length */
		$position_delta += strlen( $replacement ) - strlen( $wholeGroup );
	}

	return $content;
}

/**
 * check if post having id $id is a landing page created with TCB
 *
 * @param $id
 */
function tve_post_is_landing_page( $id ) {
	$is_landing_page = get_post_meta( $id, 'tve_landing_page', true );

	if ( ! $is_landing_page ) {
		return false;
	}

	return $is_landing_page; // this is the actual landing page template
}

/**
 * get post meta key. Also takes into account whether or not this post is a landing page
 * each regular meta key from the editor has the associated meta key for the landing page constructed by appending a "_{template_name}" after the key
 *
 * @param int $post_id
 * @param string $meta_key
 */
function tve_get_post_meta( $post_id, $meta_key, $single = true ) {
	if ( ( $template = tve_post_is_landing_page( $post_id ) ) !== false ) {
		$meta_key = $meta_key . '_' . $template;
	}

	$value = get_post_meta( $post_id, $meta_key, $single );

	/**
	 * I'm not sure why this is happening, but we had some instances where these meta values were being serialized twice
	 */
	if ( $single ) {
		$value = maybe_unserialize( $value );
	}

	return $value;
}

/**
 * update a post meta key. Also takes into account whether or not this post is a landing page
 * each regular meta key from the editor has the associated meta key for the landing page constructed by appending a "_{template_name}" after the key
 *
 * @param $post_id
 * @param $meta_key
 * @param $value
 */
function tve_update_post_meta( $post_id, $meta_key, $meta_value ) {
	if ( ( $template = tve_post_is_landing_page( $post_id ) ) !== false ) {
		$meta_key = $meta_key . '_' . $template;
	}

	return update_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * loads the landing pages configuration file and returns the item in that array corresponding to the template passed in as parameter
 *
 * @param $template_name
 */
function tve_get_landing_page_config( $template_name ) {
	if ( tve_is_cloud_template( $template_name ) ) {
		$config = tve_get_cloud_template_config( $template_name, false );

		return $config === false ? array() : $config;
	}

	$config = include plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page/templates/_config.php';

	return isset( $config[ $template_name ] ) ? $config[ $template_name ] : array();
}

/**
 * returns the default template content for a landing page post
 *
 * if the landing page template is a local one - the contents are stored in a php file template inside the landing-page folder in the plugin
 * if the landing page template is a "Cloud" template (previously downloaded from the API) - the contents are stored in a corresponding file in the wp-uploads folder
 *
 * @param string $template_name
 * @param string $default possibility to use a template as default
 */
function tve_landing_page_default_content( $template_name, $default = 'blank' ) {
	if ( tve_is_cloud_template( $template_name ) ) {
		$data = tve_get_cloud_template_config( $template_name );

		/* if $data === false => this is not a valid template - this means either some files got deleted, either the wp_options entry is corrupted */
		if ( $data !== false ) {
			$content = file_get_contents( trailingslashit( $data['base_dir'] ) . 'templates/' . $template_name . '.tpl' );
		}
	}

	if ( empty( $content ) ) {
		$landing_page_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page';

		if ( empty( $template_name ) || ! is_file( $landing_page_dir . '/templates/' . $template_name . ".php" ) ) {
			$template_name = $default;
		}

		ob_start();
		include $landing_page_dir . '/templates/' . $template_name . '.php';
		$content = ob_get_contents();
		ob_end_clean();
	}

	return $content;
}

/**
 * get all the available landing page templates
 * this function reads in the landing page config file and returns an array with names, thumbnail images, and template codes
 *
 */
function tve_get_landing_page_templates() {
	$templates  = array();
	$config     = include plugin_dir_path( dirname( __FILE__ ) ) . '/landing-page/templates/_config.php';
	$downloaded = tve_get_downloaded_templates();
	foreach ( $downloaded as $key => $template ) {
		$downloaded[ $key ]['downloaded'] = true;
	}
	$config = array_merge( $downloaded, $config );
	foreach ( $config as $code => $template ) {
		$templates[ $code ] = array(
			'name'       => $template['name'],
			'tags'       => isset( $template['tags'] ) ? $template['tags'] : array(),
			'downloaded' => isset( $template['downloaded'] ) ? $template['downloaded'] : false
		);
	}
	if ( ! empty( $templates['blank'] ) ) {
		$blank = array( 'blank' => $templates['blank'] );
		unset( $templates['blank'] );
		$templates = $blank + $templates;
	}

	return $templates;
}

/**
 * remove or change the current landing page template for the post with a default landing page, or a previously saved landing page
 * this also updates the post meta fields related to the selected template
 *
 * if it's a default template, then it will not change anything related to post content, as it will try to load it from the saved template
 *
 * each template will have it's own fields saved for the post, this helps users to not lose any content when switching back and forth various templates
 *
 * @param int $post_id
 * @param     $landing_page_template
 */
function tve_change_landing_page_template( $post_id, $landing_page_template ) {
	if ( ! $landing_page_template ) {
		delete_post_meta( $post_id, 'tve_landing_page' );

		return;
	}
	/* if it's a user-saved template, the incoming param will start with 'user-saved-template-' */
	if ( strpos( $landing_page_template, 'user-saved-template-' ) !== 0 ) {
		/* default landing page template: load in the default template content - this can also be a template downloaded from the cloud */
		update_post_meta( $post_id, 'tve_landing_page', $landing_page_template );
		/* 2014-09-19: reset the landing page contents, the whole page will reload using the clear new template */
		tve_landing_page_reset( $post_id, $landing_page_template, false );
		$skip_update = true;
	} else {
		/* at this point, the template is one of the previously saved templates (saved by the user) - it holds the index from the tve_saved_landing_pages_content which needs to be loaded */
		$contents       = get_option( 'tve_saved_landing_pages_content' );
		$meta           = get_option( 'tve_saved_landing_pages_meta' );
		$template_index = intval( str_replace( 'user-saved-template-', '', $landing_page_template ) );

		/* make sure we don't mess anything up */
		if ( empty( $contents ) || empty( $meta ) || ! isset( $contents[ $template_index ] ) ) {
			return;
		}
		$content               = $contents[ $template_index ];
		$landing_page_template = $meta[ $template_index ]['template'];
	}

	if ( empty( $content['more_found'] ) ) {
		$content['more_found']  = false;
		$content['before_more'] = $content['content'];
	}

	$key = '_' . $landing_page_template;

	if ( ! isset( $skip_update ) ) {
		update_post_meta( $post_id, "tve_content_before_more{$key}", $content['before_more'] );
		update_post_meta( $post_id, "tve_content_more_found{$key}", $content['more_found'] );
		update_post_meta( $post_id, "tve_save_post{$key}", $content['content'] );
		update_post_meta( $post_id, "tve_custom_css{$key}", $content['inline_css'] );
		/* user defined Custom CSS rules here, had to use different key because tve_custom_css was already used */
		update_post_meta( $post_id, "tve_user_custom_css{$key}", $content['custom_css'] );
		update_post_meta( $post_id, "tve_updated_post{$key}", $content['content'] );
		update_post_meta( $post_id, "tve_globals{$key}", ! empty( $content['tve_globals'] ) ? $content['tve_globals'] : array() );
		/* global scripts */
		update_post_meta( $post_id, 'tve_global_scripts', ! empty( $content['tve_global_scripts'] ) ? $content['tve_global_scripts'] : array() );
	}

	update_post_meta( $post_id, 'tve_landing_page', $landing_page_template );
}

/**
 * reset landing page to its default content
 * this assumes that the tve_landing_page post meta field is set to the value of the correct landing page template
 *
 * @param int $post_id
 * @param string $landing_page_template - from where we should take the default LP content
 */
function tve_landing_page_reset( $post_id, $landing_page_template, $reset_global_scripts = true ) {
	$post_content = tve_landing_page_default_content( $landing_page_template );
	$globals      = tve_get_post_meta( $_POST['post_id'], 'tve_globals' );

	tve_update_post_meta( $post_id, 'tve_globals', array( 'lightbox_id' => isset( $globals['lightbox_id'] ) ? $globals['lightbox_id'] : 0 ) );
	tve_update_post_meta( $post_id, 'tve_save_post', $post_content );
	tve_update_post_meta( $post_id, 'tve_updated_post', $post_content );
	tve_update_post_meta( $post_id, 'tve_custom_css', '' );
	tve_update_post_meta( $post_id, 'tve_user_custom_css', '' );
	tve_update_post_meta( $post_id, 'thrive_icon_pack', '' );
	tve_update_post_meta( $post_id, 'tve_page_events', array() );

	if ( $reset_global_scripts ) {
		update_post_meta( $post_id, 'tve_global_scripts', array() );
	}

	/* check to see if a default lightbox exists for this and if neccessary, create it */
	require_once plugin_dir_path( dirname( __FILE__ ) ) . '/landing-page/inc/TCB_Landing_Page.php';
	$tcb_landing_page = new TCB_Landing_Page( $post_id, $landing_page_template );

	/* make sure the associated lightbox exists and is setup in the event manager */
	$tcb_landing_page->checkLightbox();

	tve_update_post_custom_fonts( $post_id, array() );
}

/**
 * return a list with the current saved Landing Page templates
 */
function tve_landing_pages_load() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	$templates = get_option( 'tve_saved_landing_pages_meta' );
	$templates = empty( $templates ) ? array() : array_reverse( $templates, true ); // order by date DESC

	$html = '';

	$input   = '<input type="hidden" class="lp_code" value="user-saved-template-%s"/>';
	$img     = '<img src="%s" width="178" height="150"/>';
	$caption = '<span class="tve_cell_caption_holder"><span class="tve_cell_caption">%s</span></span><span class="tve_cell_check tve_icm tve-ic-checkmark"></span>';

	$item = '<span class="tve_grid_cell tve_landing_page_template tve_click%s" title="Choose %s">%s</span>';

	foreach ( $templates as $index => $template ) {
		if ( ! empty( $_POST['template'] ) && $_POST['template'] != $template['template'] ) {
			continue;
		}

		$thumb = empty( $template['thumbnail'] ) ? ( TVE_LANDING_PAGE_TEMPLATE . '/thumbnails/' . $template['template'] . '.png' ) : $template['thumbnail'];

		$_content = sprintf( $input, $index ) .
		            sprintf( $img, $thumb ) .
		            sprintf( $caption, $template['name'] . ' (' . strftime( '%d.%m.%y', strtotime( $template['date'] ) ) . ')' );
		$html .= sprintf( $item, ( isset( $template['tags'] ) ? ' ' . $template['tags'] : ' simple-content' ), $template['name'], $_content );
	}
	echo $html ? $html : '<p>No saved Templates found</p>';
	exit();
}

function tve_widget_options() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );

	if ( ! isset( $_POST['widget'] ) || empty( $_POST['widget'] ) ) {
		die;
	}

	$widget_id_base = $_POST['widget'];
	$widget         = tve_get_widget_class( $widget_id_base );

	if ( $widget ) {
		call_user_func_array( $widget->_get_form_callback(), array( array( 'number' => - 1, ) ) );
	}
	exit();
}

function tve_widgets_list() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );

	global $wp_widget_factory;

	foreach ( $wp_widget_factory->widgets as $class_name => $widget ) {
		if ( strpos( $class_name, "WP_" ) !== false || 1 ) {
			echo '<option value="' . $widget->id_base . '">' . $widget->name . '</option>';
		}
	}
	die;
}

function tve_display_widget() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );

	if ( ! isset( $_POST['widget'] ) || empty( $_POST['widget'] ) ) {
		die;
	}

	$widget = tve_get_widget_class( $_POST['widget'] );
	$keys   = array_keys( $_POST['options'] );

	if ( ! isset( $keys[1] ) || empty( $keys[1] ) ) {
		die;
	}

	$instance = isset( $_POST['options'][ $keys[1] ] ) && ! empty( $_POST['options'][ $keys[1] ] ) ? $_POST['options'][ $keys[1] ] : array();
	if ( count( $instance ) ) {
		foreach ( $instance as $key => $value ) {
			if ( $value === 'false' ) {
				$instance[ $key ] = 0;
			}
		}
	}
	the_widget( get_class( $widget ), $instance );

	exit();
}


function tve_do_widget_shortcode( $atts ) {
	$widget = tve_get_widget_class( $atts['widget'] );

	$instance = array();

	foreach ( $atts['options'] as $key => $value ) {
		$pattern = '/^(.+?)\[(.+?)\]\[(.+?)\]$/';
		if ( preg_match( $pattern, $key, $var ) ) {
			$instance[ $var[3] ] = $value;
		}
	}

	if ( ! $widget ) {
		return '<div class="tve_widget_container"></div>';
	}

	ob_start();
	the_widget( get_class( $widget ), $instance );
	$html = ob_get_contents();
	ob_end_clean();

	return '<div class="tve_widget_container">' . $html . '</div>';
}

function tve_get_widget_class( $id_base ) {
	global $wp_widget_factory;
	$widget = null;

	foreach ( $wp_widget_factory->widgets as $widgetObj ) {
		if ( $widgetObj->id_base === $id_base ) {
			$widget = $widgetObj;
		}
	}

	return $widget;
}

function tve_widget_form_callback( $instance ) {
	if ( isset( $_POST['options'] ) && ! empty( $_POST['options'] ) ) {
		$keys = array_keys( $_POST['options'] );
		if ( ! isset( $keys[1] ) || empty( $keys[1] ) ) {
			return $instance;
		}
		$instance = $_POST['options'][ $keys[1] ];
		if ( count( $instance ) ) {
			foreach ( $instance as $key => $value ) {
				if ( $value === 'false' ) {
					$instance[ $key ] = 0;
				} else if ( $value === 'true' ) {
					$instance[ $key ] = 1;
				}
			}
		}
	}

	return $instance;
}

/**
 * get the link to the google font based on $font
 *
 * @param array|object $font
 */
function tve_custom_font_get_link( $font ) {
	if ( is_array( $font ) ) {
		$font = (object) $font;
	}

	if ( Tve_Dash_Font_Import_Manager::isImportedFont( $font ) ) {
		return Tve_Dash_Font_Import_Manager::getCssFile();
	}

	return "//fonts.googleapis.com/css?family=" . str_replace( " ", "+", $font->font_name ) . ( $font->font_style ? ":" . $font->font_style : "" ) . ( $font->font_bold ? "," . $font->font_bold : "" ) . ( $font->font_italic ? $font->font_italic : "" ) . ( $font->font_character_set ? "&subset=" . $font->font_character_set : "" );
}

/**
 * get all fonts created with the font manager
 *
 * @param bool $assoc whether to decode as array or object
 *
 * @return array
 */
function tve_get_all_custom_fonts( $assoc = false ) {
	$all_fonts = get_option( 'thrive_font_manager_options' );
	if ( empty( $all_fonts ) ) {
		$all_fonts = array();
	} else {
		$all_fonts = json_decode( $all_fonts, $assoc );
	}

	return (array) $all_fonts;
}

/**
 *
 * @param $post_id
 * @param $custom_font_classes array containing all the custom font css classes
 */
function tve_update_post_custom_fonts( $post_id, $custom_font_classes ) {
	$all_fonts = tve_get_all_custom_fonts();

	$post_fonts = array();
	foreach ( array_unique( $custom_font_classes ) as $cls ) {
		foreach ( $all_fonts as $font ) {
			if ( Tve_Dash_Font_Import_Manager::isImportedFont( $font->font_name ) ) {
				$post_fonts[] = Tve_Dash_Font_Import_Manager::getCssFile();
			} else if ( $font->font_class == $cls && ! tve_is_safe_font( $font ) ) {
				$post_fonts[] = tve_custom_font_get_link( $font );
				break;
			}
		}
	}

	$post_fonts = array_unique( $post_fonts );

	tve_update_post_meta( $post_id, 'thrive_tcb_post_fonts', $post_fonts );
}

/**
 * get all custom fonts used for a post
 *
 * @param      $post_id
 * @param bool $include_thrive_fonts - whether or not to include Thrive Themes fonts for this post in the list.
 *                                   By default it will return all the fonts that are used in TCB but are not already used from the Theme (admin WP editor)
 *
 * @return array with index => href link
 */
function tve_get_post_custom_fonts( $post_id, $include_thrive_fonts = false ) {
	$post_fonts = tve_get_post_meta( $post_id, 'thrive_tcb_post_fonts' );
	$post_fonts = empty( $post_fonts ) ? array() : $post_fonts;

	if ( empty( $post_fonts ) && ! $include_thrive_fonts ) {
		return array();
	}

	$all_fonts       = tve_get_all_custom_fonts();
	$all_fonts_links = array();
	foreach ( $all_fonts as $f ) {
		if ( Tve_Dash_Font_Import_Manager::isImportedFont( $f->font_name ) ) {
			$all_fonts_links[] = Tve_Dash_Font_Import_Manager::getCssFile();
		} else if ( ! tve_is_safe_font( $f ) ) {
			$all_fonts_links [] = tve_custom_font_get_link( $f );
		}
	}

	if ( empty( $all_fonts ) ) {
		// all fonts have been deleted - delete the saved fonts too for this post
		tve_update_post_meta( $post_id, 'thrive_tcb_post_fonts', array() );
	} else {
		$fixed = array_intersect( $post_fonts, $all_fonts_links );
		if ( count( $fixed ) != count( $post_fonts ) ) {
			$post_fonts = $fixed;
			tve_update_post_meta( $post_id, 'thrive_tcb_post_fonts', $post_fonts );
		}
	}

	$theme_post_fonts = get_post_meta( $post_id, 'thrive_post_fonts', true );
	$theme_post_fonts = empty( $theme_post_fonts ) ? array() : json_decode( $theme_post_fonts, true );

	$post_fonts = empty( $post_fonts ) || ! is_array( $post_fonts ) ? array() : $post_fonts;

	/* return just fonts that will not be loaded from any possible theme shortcodes */

	return $include_thrive_fonts ? array_values( array_unique( array_merge( $post_fonts, $theme_post_fonts ) ) ) : array_diff( $post_fonts, $theme_post_fonts );
}

/**
 * enqueue all the custom fonts used on a post (used only on frontend, not on editor page)
 *
 * @param mixed $post_id if null -> use the global wp query; if not, load the fonts for that specific post
 * @param bool $include_thrive_fonts by default thrive themes fonts are included by the theme. for lightboxes for example, we need to include those also
 */
function tve_enqueue_custom_fonts( $post_id = null, $include_thrive_fonts = false ) {
	if ( $post_id === null ) {
		global $wp_query;
		$posts_to_load = $wp_query->posts;
		if ( empty( $posts_to_load ) || ! is_array( $posts_to_load ) ) {
			return;
		}
		$post_id = array();
		foreach ( $posts_to_load as $p ) {
			$post_id [] = $p->ID;
		}
	} else {
		$post_id = array( $post_id );
	}

	foreach ( $post_id as $_id ) {
		tve_enqueue_fonts( tve_get_post_custom_fonts( $_id, $include_thrive_fonts ) );
	}
}

/**
 * Enqueue custom scripts thant need to be loaded on FRONTEND
 */
function tve_enqueue_custom_scripts() {
	global $wp_query;

	$posts_to_load = $wp_query->posts;

	if ( ! is_array( $posts_to_load ) ) {
		return;
	}

	$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

	foreach ( $posts_to_load as $post ) {
		if ( tve_get_post_meta( $post->ID, 'tve_has_masonry' ) ) {
			wp_script_is( "jquery-masonry" ) || wp_enqueue_script( "jquery-masonry", array( 'jquery' ) );
		}
		if ( tve_get_post_meta( $post->ID, 'tve_has_typefocus' ) ) {
			wp_script_is( "tve_typed" ) || wp_enqueue_script( "tve_typed", tve_editor_js() . '/typed' . $js_suffix, array( 'tve_frontend' ) );
		}
		/* include wistia script for popover videos */
		if ( tve_get_post_meta( $post->ID, 'tve_has_wistia_popover' ) && ! wp_script_is( 'tl-wistia-popover' ) ) {
			wp_enqueue_script( 'tl-wistia-popover', '//fast.wistia.com/assets/external/E-v1.js', array(), '', true );
		}
		$globals = tve_get_post_meta( $post->ID, 'tve_globals' );
		if ( ! empty( $globals['js_sdk'] ) ) {
			foreach ( $globals['js_sdk'] as $handle ) {
				wp_script_is( 'tve_js_sdk_' . $handle ) || wp_enqueue_script( 'tve_js_sdk_' . $handle, tve_social_get_sdk_link( $handle ), array(), false );
			}
		}
	}
}

/**
 * Enqueue the javascript for the social sharing elements, if any is required
 * Will throw an event called "tve_socials_init_[network_name]"
 * It will throw an event for Pinterest by default
 * If the event is thrown the enqueue will be skipped
 *
 * @param $do_action_for array of networks.
 */
function tve_enqueue_social_scripts( $do_action_for = array() ) {
	global $wp_query;

	$posts_to_load = $wp_query->posts;

	if ( ! is_array( $posts_to_load ) ) {
		return;
	}

	foreach ( $posts_to_load as $post ) {
		$globals = tve_get_post_meta( $post->ID, 'tve_globals' );
		if ( ! empty( $globals['js_sdk'] ) ) {
			foreach ( $globals['js_sdk'] as $handle ) {
				$link = tve_social_get_sdk_link( $handle );
				if ( ! $link ) {
					continue;
				}
				wp_script_is( 'tve_js_sdk_' . $handle ) || wp_enqueue_script( 'tve_js_sdk_' . $handle, $link, array(), false );
			}
		}
	}
}

/**
 * enqueue all fonts passed in as an array with font links
 *
 * @param array $font_array can either be a list of links to google fonts css or a list with font objects returned from the font manager options
 *
 * @return array
 */
function tve_enqueue_fonts( $font_array ) {
	if ( ! is_array( $font_array ) ) {
		return array();
	}
	$return = array();
	/** @var $font object|array|string */
	foreach ( $font_array as $font ) {
		if ( is_string( $font ) ) {
			$href = $font;
		} else if ( is_array( $font ) || is_object( $font ) ) {
			$font_name = is_array( $font ) ? $font['font_name'] : $font->font_name;
			if ( Tve_Dash_Font_Import_Manager::isImportedFont( $font_name ) ) {
				$href = Tve_Dash_Font_Import_Manager::getCssFile();
			} else {
				$href = tve_custom_font_get_link( $font );
			}
		}
		$font_key            = 'tcf_' . md5( $href );
		$return[ $font_key ] = $href;
		wp_enqueue_style( $font_key, $href );
	}

	return $return;
}

/**
 * output the tinymce editor in the footer area
 */
function tve_output_wysiwyg_editor() {
	if ( isset( $GLOBALS['tve_wysiwyg_output'] ) ) {
		return;
	}

	$lb_before = '<div class="tve_mce_holder_overlay"></div><div id="tve_mce_holder" class="tve_mce_holder" style="display: none">' .
	             '<a class="tve-lightbox-close" href="javascript:void(0)" title="Close"><span class="tve_lightbox_close tve_mce_close tve_click" data-ctrl="controls.lb_close"></span></a>' .
	             '<div class="tve_mce_inner">';
	$lb_after  = '</div><div class="tve_lightbox_buttons"><div class="tve-sp"></div><input type="button" class="tve_save_lightbox tve_editor_button tve_editor_button_success tve_mce_save tve_mousedown tve_right" data-ctrl="controls.lb_save" value="Save"></div></div>';

	echo $lb_before;
	tcb_remove_tinymce_conflicts();
	wp_editor( '', 'tve_tinymce_tpl', array(
		'dfw'               => true,
		'tabfocus_elements' => 'insert-media-button,save-post',
		'editor_height'     => 360,
		'textarea_rows'     => 15,
	) );
	echo $lb_after;

	$GLOBALS['tve_wysiwyg_output'] = true;
}

/**
 * remove tinymce conflicts
 * 1. if 3rd party products include custom versions of jquery UI, those will completely break the 'wplink' plugin
 * 2. MemberMouse adds some media buttons and does not correctly setup links to images
 */
function tcb_remove_tinymce_conflicts() {
	/* Membermouse adds some extra media buttons */
	remove_all_actions( 'media_buttons_context' );
}

/**
 * Removes the "wplink" plugin - this causes conflicts in cases where themes / plugins include custom versions of jquery UI on the page
 *
 * @param array $plugins
 *
 * @return array
 */
function tcb_remove_tinymce_wplink_plugin( $plugins ) {
	$found = array_search( 'wplink', $plugins );
	if ( $found !== false ) {
		array_splice( $plugins, $found, 1 );
	}

	return $plugins;
}

/**
 * @param string $title lightbox title
 * @param string $tcb_content
 * @param array $tve_globals tve_globals array to save for the lightbox
 * @param array $extra_meta_data array of key => value pairs, each will be saved in a meta field for the lightbox
 *
 * @return int the saved lightbox id
 */
function tve_create_lightbox( $title = '', $tcb_content = '', $tve_globals = array(), $extra_meta_data = array() ) {
	/* just to make sure that our content filter does not get applied when inserting a (possible) new lightbox */
	$GLOBALS['TVE_CONTENT_SKIP_ONCE'] = true;
	$lightbox_id                      = wp_insert_post( array(
		'post_content' => '',
		'post_title'   => $title,
		'post_status'  => 'publish',
		'post_type'    => 'tcb_lightbox',
	) );
	foreach ( $extra_meta_data as $meta_key => $meta_value ) {
		update_post_meta( $lightbox_id, $meta_key, $meta_value );
	}

	update_post_meta( $lightbox_id, 'tve_save_post', $tcb_content );
	update_post_meta( $lightbox_id, 'tve_updated_post', $tcb_content );
	update_post_meta( $lightbox_id, 'tve_globals', $tve_globals );

	unset( $GLOBALS['TVE_CONTENT_SKIP_ONCE'] );

	return $lightbox_id;
}

/**
 * render the html for the "Custom Menu" widget element
 *
 * called either from the editor section or from frontend, when rendering everything
 *
 * @param $attributes
 */
function tve_render_widget_menu( $attributes ) {
	$menu_id = $attributes['menu_id'];

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && function_exists( 'Nav_Menu_Roles' ) ) {
		/**
		 * If loading the menu via ajax ( in the TCB editor page ) and the Nav Menu Roles plugin is active, we need to add its filtering function here
		 * in order to show the same menu items in the editor page and in Preview
		 */
		$nav_menu_roles = Nav_Menu_Roles();
		if ( ! empty( $nav_menu_roles ) && $nav_menu_roles instanceof Nav_Menu_Roles ) {
			add_filter( 'wp_get_nav_menu_items', array( $nav_menu_roles, 'exclude_menu_items' ) );
		}
	}

	$items = wp_get_nav_menu_items( $menu_id );
	if ( empty( $items ) ) {
		return '';
	}

	$ul_custom_color       = ! empty( $attributes['ul_attr'] ) ? sprintf( " data-tve-custom-colour='%s'", $attributes['ul_attr'] ) : '';
	$trigger_color         = ! empty( $attributes['trigger_attr'] ) ? sprintf( " data-tve-custom-colour='%s'", $attributes['trigger_attr'] ) : '';
	$link_custom_color     = ! empty( $attributes['link_attr'] ) ? $attributes['link_attr'] : '';
	$top_link_custom_color = ! empty( $attributes['top_link_attr'] ) ? $attributes['top_link_attr'] : '';
	$font_family           = ! empty( $attributes['font_family'] ) ? $attributes['font_family'] : '';
	// Member mouse login / logout links not being shown in the menu
	$is_primary = ! empty( $attributes['primary'] );

	if ( ! empty( $link_custom_color ) || ! empty( $top_link_custom_color ) ) {
		/* ugly ugly solution */
		$GLOBALS['tve_menu_link_custom_color']     = $link_custom_color;
		$GLOBALS['tve_menu_top_link_custom_color'] = $top_link_custom_color;
		add_filter( 'nav_menu_link_attributes', 'tve_menu_custom_color', 10, 3 );
	}

	if ( ! empty( $font_family ) ) {
		$GLOBALS['tve_menu_top_link_custom_font_family'] = $font_family;
		add_filter( 'nav_menu_link_attributes', 'tve_menu_custom_font_family', 10, 3 );
	}

	if ( ! empty( $attributes['font_class'] ) ) {
		$GLOBALS['tve_menu_font_class'] = $attributes['font_class'];
		add_filter( 'nav_menu_css_class', 'tve_widget_menu_li_classes' );
	}

	$menu_html = '<div class="thrive-shortcode-html tve_clearfix"><a' . $trigger_color . ' class="tve-m-trigger t_' . $attributes['dir'] . ' ' .
	             $attributes['color'] . '" href="javascript:void(0)"><span class="thrv-icon thrv-icon-align-justify"></span></a>' . wp_nav_menu( array(
			'echo'           => false,
			'menu'           => $menu_id,
			'container'      => false,
			'theme_location' => ! empty( $is_primary ) ? 'primary' : '',
			'items_wrap'     => '<ul' . $ul_custom_color . ' id="%1$s" class="%2$s"' . ( ! empty( $attributes['font_size'] ) ? ' style="font-size:' . $attributes['font_size'] . '"' : '' ) . '>%3$s</ul>',
			'menu_class'     => 'tve_w_menu ' . $attributes['dir'] . ' ' . ( ! empty( $attributes['font_class'] ) ? $attributes['font_class'] . ' ' : '' ) . $attributes['color'],
		) ) . '</div>';

	/* clear out the global variable */
	unset( $GLOBALS['tve_menu_link_custom_color'], $GLOBALS['tve_menu_top_link_custom_color'], $GLOBALS['tve_menu_font_class'], $GLOBALS['tve_menu_top_link_custom_font_family'] );
	remove_filter( 'nav_menu_link_attributes', 'tve_menu_custom_color' );
	remove_filter( 'nav_menu_link_attributes', 'tve_menu_custom_font_family' );
	remove_filter( 'nav_menu_css_class', 'tve_widget_menu_li_classes' );

	return $menu_html;
}

/**
 * append font classes also to the <li> menu elements
 *
 * @param array $classes
 *
 * @return array
 */
function tve_widget_menu_li_classes( $classes ) {
	if ( empty( $GLOBALS['tve_menu_font_class'] ) ) {
		return $classes;
	}

	$classes [] = $GLOBALS['tve_menu_font_class'];

	return $classes;
}

/**
 * append custom color attributes to the link items from the menu
 *
 * @param $attrs
 *
 * @return mixed
 */
function tve_menu_custom_color( $attrs, $menu_item ) {
	$custom_color = $menu_item->menu_item_parent ? 'tve_menu_link_custom_color' : 'tve_menu_top_link_custom_color';
	$value        = isset( $GLOBALS[ $custom_color ] ) ? $GLOBALS[ $custom_color ] : '';

	if ( ! $value ) {
		return $attrs;
	}
	$attrs['data-tve-custom-colour'] = $value;

	return $attrs;
}

function tve_menu_custom_font_family( $attrs, $menu_item ) {
	$font_family = $GLOBALS['tve_menu_top_link_custom_font_family'];
	$style       = 'font-family: ' . $font_family . ';';

	if ( isset( $attrs['style'] ) && ! empty( $attrs['style'] ) ) {
		$style = trim( ";", $attrs['style'] ) . ";" . $style;
	}

	$attrs['style'] = $style;

	return $attrs;
}

/**
 * custom call of an action hook - this will forward the call to the WP do_action function
 * it will inject parameters read from $_GET based on the filter that others might use
 *
 * @param string $hook required. The action hook to be called
 * @param mixed $_args arguments that will be passed on to the do_action call
 */
function tve_do_action() {
	/**
	 * filter to allow passing variables from $_GET into the various actions
	 * this is used only on editor page
	 */
	$_get_fields = apply_filters( 'tcb_required_get_fields', array() );
	$args        = func_get_args();

	if ( ! is_array( $_get_fields ) ) {
		$_get_fields = array();
	}

	foreach ( $_get_fields as $field ) {
		$args [] = isset( $_GET[ $field ] ) ? $_GET[ $field ] : null;
	}

	return call_user_func_array( 'do_action', $args );
}

function tve_categories_list() {
	$taxonomies = array( 'category' );

	if ( taxonomy_exists( 'apprentice' ) ) {
		$taxonomies[] = 'apprentice';
	}

	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';
	$terms       = get_terms( $taxonomies, array( 'search' => $search_term ) );

	$response = array();
	foreach ( $terms as $item ) {
		$term          = array();
		$term['label'] = $item->name;
		$term['value'] = $item->name;
		$response[]    = $term;
	}
	wp_send_json( $response );
}

function tve_tags_list() {
	$taxonomies = array(
		'post_tag'
	);

	if ( taxonomy_exists( 'apprentice' ) ) {
		$taxonomies[] = 'apprentice-tag';
	}

	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';
	$terms       = get_terms( $taxonomies, array( 'search' => $search_term ) );
	$response    = array();
	foreach ( $terms as $item ) {
		$term          = array();
		$term['label'] = $item->name;
		$term['value'] = $item->name;
		$response[]    = $term;
	}
	wp_send_json( $response );
}

function tve_custom_taxonomies_list() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );
	$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';

	function filter_taxonomies( $value ) {
		$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';
		$banned      = array( 'category', 'post_tag' );
		if ( in_array( $value, $banned ) ) {
			return false;
		}
		if ( ! $search_term ) {
			return true;
		}

		return strpos( $value, $search_term ) !== false;
	}

	$taxonomies = get_taxonomies();
	$terms      = array_filter( $taxonomies, 'filter_taxonomies' );

	$response = array();
	foreach ( $terms as $item ) {
		$term          = array();
		$term['label'] = $item;
		$term['value'] = $item;
		$response[]    = $term;
	}
	wp_send_json( $response );
}

function tve_authors_list() {
	check_ajax_referer( 'tve-le-verify-sender-track129', 'security' );

	$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';
	$users       = get_users( array( 'search' => "*$search_term*" ) );

	$response = array();
	foreach ( $users as $item ) {
		$term          = array();
		$term['label'] = $item->data->user_nicename;
		$term['value'] = $item->data->user_nicename;
		$response[]    = $term;
	}
	wp_send_json( $response );
}

function tve_posts_list() {
	check_ajax_referer( 'tve-le-verify-sender-track129', 'security' );
	$search_term = isset( $_POST['term'] ) ? $_POST['term'] : '';
	$args        = array(
		'order_by'    => 'post_title',
		'post_type'   => array( 'page', 'post' ),
		'post_status' => array( 'publish' ),
		's'           => $search_term
	);
	$results     = new WP_Query( $args );

	$response = array();
	foreach ( $results->get_posts() as $post ) {
		$term          = array();
		$term['label'] = $post->post_title;
		$term['value'] = $post->ID;
		$response[]    = $term;
	}
	wp_send_json( $response );
}

/**
 * sort the user-defined templates alphabetically by name
 *
 * @param $a
 * @param $b
 *
 * @return int
 */
function tve_tpl_sort( $a, $b ) {
	return strcasecmp( $a['name'], $b['name'] );
}

/**
 *
 * transform any url into a protocol-independent url
 *
 * @param string $raw_url
 *
 * @return string
 */
function tve_url_no_protocol( $raw_url ) {
	return preg_replace( '#http(s)?://#', '//', $raw_url );
}

/**
 * called via AJAX, it will load a file from a list of allowed files from the editor
 * designed to work
 */
function tve_ajax_load() {

	if ( ob_get_contents() ) {
		ob_clean();
	}
	if ( empty( $_POST['ajax_load'] ) ) {
		return;
	}
	$file = $_POST['ajax_load'];

	switch ( $file ) {
		case 'control_panel':
		case 'lb_icon':
		case 'lb_lead_generation_code':
		case 'lb_post_grid':
		case 'lb_revision_manager':
		case 'lb_social':
		case 'lb_custom_css':
		case 'lb_custom_html':
		case 'lb_full_html':
		case 'lb_global_scripts':
		case 'lb_google_map':
		case 'lb_image_link':
		case 'lb_landing_pages':
		case 'lb_save_user_template':
		case 'lb_table':
		case 'lb_text_link':
		case 'lb_text_link_settings':
		case 'lb_ultimatum_shortcode':
			include plugin_dir_path( dirname( __FILE__ ) ) . 'editor/' . $file . '.php';
			break;
		case 'sc_thrive_custom_menu':
		case 'sc_thrive_custom_phone':
		case 'sc_thrive_leads_shortcode':
		case 'sc_thrive_ultimatum_shortcode':
		case 'sc_thrive_optin':
		case 'sc_thrive_posts_list':
		case 'sc_widget_menu':
		case 'sc_icon':
			include plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/templates/' . $file . '.php';
			break;
		default:
			do_action( 'tcb_ajax_load', $file );
			break;
	}

	exit();
}

/**
 * Fields that will be displayed with differences in revisions page(admin section)
 *
 * @param $fields
 *
 * @return mixed
 */
function tve_post_revision_fields( $fields ) {
	$fields['tve_revision_tve_updated_post']    = __( 'Thrive Visual Editor Content', 'thrive-cb' );
	$fields['tve_revision_tve_user_custom_css'] = __( 'Thrive Visual Editor Custom CSS', 'thrive-cb' );
	$fields['tve_revision_tve_landing_page']    = __( 'Landing Page', 'thrive-cb' );

	return $fields;
}

/**
 * At this moment post is reverted to required revision.
 * This means the post is saved and a new revision is already created.
 * When a revision is created all metas are assigned to revision;
 *
 * @see tve_save_post_callback
 *
 * Get all the metas of the revision received as parameter and set it for the newly revision created.
 * Set all revision metas to post received as parameter
 *
 * @param $post_id
 * @param $revision_id
 *
 * @return bool
 */
function tve_restore_post_to_revision( $post_id, $revision_id ) {
	$revisions     = wp_get_post_revisions( $post_id );
	$last_revision = array_shift( $revisions );

	if ( ! $last_revision ) {
		return false;
	}

	$meta_keys = tve_get_used_meta_keys();
	foreach ( $meta_keys as $meta_key ) {
		$revision_content = get_metadata( 'post', $revision_id, 'tve_revision_' . $meta_key, true );
		update_metadata( 'post', $last_revision->ID, 'tve_revision_' . $meta_key, $revision_content );

		if ( $meta_key === 'tve_landing_page' ) {
			update_post_meta( $post_id, $meta_key, $revision_content );
		} else {
			tve_update_post_meta( $post_id, $meta_key, $revision_content );
		}

	}
}

/**
 * Filter called from wp_save_post_revision. If this logic returns true a post revision will be added by WP
 * If there are any changes in meta then we need a revision to be made
 *
 * @see wp_save_post_revision
 *
 * @param $post_has_changed
 * @param $last_revision
 * @param $post
 *
 * @return bool
 */
function tve_post_has_changed( $post_has_changed, $last_revision, $post ) {
	$meta_keys = tve_get_used_meta_keys();

	/**
	 * check the meta
	 * if there is any meta differences a revision should be made
	 */
	foreach ( $meta_keys as $meta_key ) {
		if ( $meta_key === 'tve_landing_page' ) {
			$post_content = get_post_meta( $post->ID, $meta_key, true );
		} else {
			$post_content = tve_get_post_meta( $post->ID, $meta_key );
		}
		$revision_content = get_post_meta( $last_revision->ID, "tve_revision_" . $meta_key, true );
		$post_has_changed = $revision_content !== $post_content;
		if ( $post_has_changed ) {
			return true;
		}
	}

	/** @var $total_fields array fields that are tracked for versioning */
	$total_fields = array_keys( _wp_post_revision_fields() );

	/** @var $tve_custom_fields array fields that are pushed to be tracked by this plugin */
	$tve_custom_fields = array_keys( tve_post_revision_fields( array() ) );

	/** @var $to_be_checked array remove additional plugin tracking fields */
	$to_be_checked = array();
	foreach ( $total_fields as $total ) {
		if ( in_array( $total, $tve_custom_fields ) ) {
			continue;
		}
		$to_be_checked[] = $total;
	}

	foreach ( $to_be_checked as $field ) {
		if ( normalize_whitespace( $post->$field ) != normalize_whitespace( $last_revision->$field ) ) {
			$post_has_changed = true;
			break;
		}
	}

	return $post_has_changed;
}

/**
 * Return an array with meta keys that are used for custom content on posts
 *
 * @see tve_save_post_callback, tve_post_has_changed, tve_restore_post_to_revision
 *
 * @return array
 */
function tve_get_used_meta_keys() {
	$meta_keys = array(
		'tve_landing_page',
		'tve_content_before_more',
		'tve_content_more_found',
		'tve_save_post',
		'tve_custom_css',
		'tve_user_custom_css',
		'tve_page_events',
		'tve_globals',
		'tve_global_scripts',
		'thrive_icon_pack',
		'thrive_tcb_post_fonts',
		'tve_has_masonry',
		'tve_has_typefocus',
		'tve_updated_post',
		'tve_has_wistia_popover'
	);

	return $meta_keys;
}

function tve_get_last_revision_id( $post_id ) {
	$revisions = wp_get_post_revisions( $post_id, array( 'numberposts' => 1 ) );

	$last_revision = reset( $revisions );

	if ( $last_revision ) {
		return $last_revision->ID;
	}

	return null;
}

/**
 * Called when post is loaded and tve_revert_theme exists in get request
 * Redirects the user to post edit form
 */
function tve_revert_page_to_theme() {
	if ( ! isset( $_GET['tve_revert_theme'] ) ) {
		return;
	}
	if ( ! isset( $_GET['post'] ) || ! isset( $_GET['action'] ) ) {
		return;
	}
	$post_id = $_GET['post'];

	if ( tve_post_is_landing_page( $_GET['post'] ) ) {
		delete_post_meta( $post_id, 'tve_landing_page' );
		//force save, a revision needs to be created
		wp_update_post( array(
			'ID'                => $post_id,
			'post_modified'     => current_time( 'mysql' ),
			'post_modified_gmt' => current_time( 'mysql' ),
			'post_title'        => get_the_title( $post_id )
		) );
		wp_redirect( get_edit_post_link( $post_id, 'url' ) );
		exit();
	}
}

/**
 * function to be called through ajax call to update an option
 * all the required settings are set on $_POST: $_POST['option_name'] and $_POST['option_value']
 */
function tve_ajax_update_option() {
	check_ajax_referer( "tve-le-verify-sender-track129", "security" );

	$option_name  = $_POST['option_name'];
	$option_value = $_POST['option_value'];

	$allowed = array(
		'tve_display_save_notification',
		'tve_social_fb_app_id',
		'tve_comments_disqus_shortname',
		'tve_comments_facebook_admins'
	);
	if ( ! in_array( $option_name, $allowed ) ) {
		exit();
	}

	if ( $option_name == "tve_comments_facebook_admins" ) {
		$tve_comments_facebook_admins_arr = explode( ';', $option_value );
		$result                           = update_option( $option_name, $tve_comments_facebook_admins_arr );
	} else {
		$result = update_option( $option_name, $option_value );
	}


	echo (int) $result;
	exit();
}

/**
 * strip out any un-necessary stuff from the content before displaying it on frontend
 *
 * @param string $tve_saved_content
 *
 * @return string the clean content
 */
function tcb_clean_frontend_content( $tve_saved_content ) {
	/**
	 * strip out the lead generation code
	 *
	 * TODO: we also need to move the error messages inside a data-errors attribute of the LG element
	 */
	if ( strpos( $tve_saved_content, '__CONFIG_lead_generation' ) !== false ) {
		$tve_saved_content = preg_replace( '/__CONFIG_lead_generation_code__(.+?)__CONFIG_lead_generation_code__/s', '', $tve_saved_content );
	}

	return $tve_saved_content;
}

/**
 * create a hidden input containing the error messages instead of holding them in the html content
 *
 * @param array $match
 *
 * @return string
 */
function tcb_lg_err_inputs( $match ) {
	return '<input type="hidden" class="tve-lg-err-msg" value="' . htmlspecialchars( $match[1] ) . '">';
}

/**
 * Used to get through AJX a nonce for the logged user
 * If the current user has "edit_posts" capability
 */
function tve_logged_user_nonce() {
	if ( current_user_can( 'edit_posts' ) ) {
		$ajax_nonce = wp_create_nonce( "tve-le-verify-sender-track129" );
		echo json_encode( array( "nonce" => $ajax_nonce ) );
		die;
	}
	exit( "0" );
}

/**
 * One place to rule them all
 * Please use this function to read the FB AppID used in Social Sharing Element
 *
 * @return string
 */
function tve_get_social_fb_app_id() {
	return get_option( 'tve_social_fb_app_id', '' );
}

/**
 * Set the path where the translation files are being kept
 */
function tve_load_plugin_textdomain() {
	$domain = 'thrive-cb';
	$locale = $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	$path = 'thrive-visual-editor/languages/';
	$path = apply_filters( 'tve_filter_plugin_languages_path', $path );

	load_textdomain( $domain, WP_LANG_DIR . '/thrive/' . $domain . "-" . $locale . ".mo" );
	load_plugin_textdomain( $domain, false, $path );
}

/**
 * AJAX for searching posts by a quicklink key
 */
function tve_find_quick_link_contents() {
	$s = wp_unslash( $_REQUEST['q'] );
	$s = trim( $s );

	$all_post_types = get_post_types( array(
		'public' => true
	) );

	$post_types = get_option( 'tve_hyperlink_settings', false );
	function_exists( 'tve_leads_get_groups' ) ? $leads = array( 'tve_lead_2s_lightbox' ) : $leads = array();

	if ( ! $post_types ) {
		$post_types = array( 'post', 'page' );

	} else {
		$accepted = unserialize( $post_types );

		if ( ! $accepted ) {
			$accepted = array();
		}

		$post_types = $accepted;
	}

	$postList = array();
	if ( $post_types !== null ) {
		$args           = array(
			'post_type'   => $post_types,
			'post_status' => 'publish',
			's'           => $s,
			'numberposts' => 5,
			'sort_order'  => 'asc',
		);
		$accepted_array = get_posts( $args );

		$lightbox = array( 'tcb_lightbox' );
		$boxes    = array_merge( $lightbox, $leads );

		$lightbox_args  = array(
			'post_type'   => $boxes,
			'post_status' => 'publish',
			's'           => $s,
			'numberposts' => 5,
			'sort_order'  => 'asc',
		);
		$lightbox_array = get_posts( $lightbox_args );

		$posts_array = array_merge( $lightbox_array, $accepted_array );

		foreach ( $posts_array as $id => $item ) {
			if ( ! empty( $s ) ) {
				$item->post_title = preg_replace( "#($s)#i", "<b>$0</b>", $item->post_title );
			}
			$postList [] = array(
				'label' => $item->post_title,
				'id'    => $item->ID,
				'value' => $item->post_title,
				'url'   => get_permalink( $item->ID ),
				'type'  => $item->post_type
			);
		}
	}
	include dirname( __FILE__ ) . '/views/quick-links-table.php';
	exit;
}

/**
 * Check the Object font sent as param if it's web sef font
 *
 * @param $font array|StdClass
 *
 * @return bool
 */
function tve_is_safe_font( $font ) {
	foreach ( tve_dash_font_manager_get_safe_fonts() as $safe_font ) {
		if ( ( is_object( $font ) && $safe_font['family'] === $font->font_name )
		     || ( is_array( $font ) && $safe_font['family'] === $font['font_name'] )
		) {
			return true;
		}
	}

	return false;
}

/**
 * Remove the web safe fonts from the list cos we don't want them to import them from google
 * They already exists loaded in browser from user's computer
 *
 * @param $fonts_saved
 *
 * @return mixed
 */
function tve_filter_custom_fonts_for_enqueue_in_editor( $fonts_saved ) {
	$safe_fonts = tve_dash_font_manager_get_safe_fonts();
	foreach ( $safe_fonts as $safe ) {
		foreach ( $fonts_saved as $key => $font ) {
			if ( is_object( $font ) && $safe['family'] === $font->font_name ) {
				unset( $fonts_saved[ $key ] );
			} else if ( is_array( $font ) && $safe['family'] === $font['font_name'] ) {
				unset( $fonts_saved[ $key ] );
			}
		}
	}

	return $fonts_saved;
}

/**
 * includes a message in the media uploader window about the allowed file types
 */
function tve_media_restrict_filetypes() {
	$file_types = array(
		'zip',
		'jpg',
		'gif',
		'png',
		'pdf'
	);
	foreach ( $file_types as $file_type ) {
		echo '<p class="tve-media-message tve-media-allowed-' . $file_type . '" style="display: none"><strong>' . sprintf( __( 'Only %s files are accepted' ), '.' . $file_type ) . '</strong></p>';
	}
}

function tve_json_utf8_slashit( $value ) {
	return str_replace( array( '_tveutf8_', '_tve_quote_' ), array( '\u', '\"' ), $value );
}

function tve_json_utf8_unslashit( $value ) {
	return str_replace( array( '\u', '\"' ), array( '_tveutf8_', '_tve_quote_' ), $value );
}

/**
 * Loads dashboard's version file
 */
function tve_load_dash_version() {
	$tve_dash_path      = dirname( dirname( __FILE__ ) ) . '/thrive-dashboard';
	$tve_dash_file_path = $tve_dash_path . '/version.php';

	if ( is_file( $tve_dash_file_path ) ) {
		$version                                  = require_once( $tve_dash_file_path );
		$GLOBALS['tve_dash_versions'][ $version ] = array(
			'path'   => $tve_dash_path . '/thrive-dashboard.php',
			'folder' => '/thrive-visual-editor',
			'from'   => 'plugins'
		);
	}
}

/**
 * make sure the TCB product is shown in the dashboard product list
 *
 * @param array $items
 *
 * @return array
 */
function tve_add_to_dashboard( $items ) {
	$items[] = new TCB_Product();

	return $items;
}

/**
 * make sure all the features required by TCB are shown in the dashboard
 *
 * @param array $features
 *
 * @return array
 */
function tve_dashboard_add_features( $features ) {
	$features['font_manager']     = true;
	$features['icon_manager']     = true;
	$features['api_connections']  = true;
	$features['general_settings'] = true;

	return $features;
}

/**
 * handles all api-related AJAX calls made when editing a Lead Generation element
 */
function tve_api_editor_actions() {
	$controller = new Thrive_Dash_List_Editor_Controller();
	$controller->run();
}

function tve_custom_form_submit() {

	$post = $_POST;
	/**
	 * action filter -  allows hooking into the form submission event
	 *
	 * @param array $post the full _POST data
	 *
	 */
	do_action( 'tcb_api_form_submit', $post );
}

/**
 * AJAX call on a Lead Generation form that's connected to an api
 */
function tve_api_form_submit() {
	$data = $_POST;

	if ( isset( $data['_use_captcha'] ) && $data['_use_captcha'] == '1' ) {
		$CAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';
		$captcha_api = Thrive_Dash_List_Manager::credentials( 'recaptcha' );

		$_capthca_params = array(
			'response' => $data['g-recaptcha-response'],
			'secret'   => empty( $captcha_api['secret_key'] ) ? '' : $captcha_api['secret_key'],
			'remoteip' => $_SERVER['REMOTE_ADDR']
		);

		$request  = tve_dash_api_remote_post( $CAPTCHA_URL, array( 'body' => $_capthca_params ) );
		$response = json_decode( wp_remote_retrieve_body( $request ) );
		if ( empty( $response ) || $response->success === false ) {
			exit( json_encode( array(
				'error' => __( 'Please prove us that you are not a robot!!!', 'thrive-cb' ),
			) ) );
		}
	}


	if ( empty( $data['email'] ) ) {
		exit( json_encode( array(
			'error' => __( 'The email address is required', 'thrive-cb' ),
		) ) );
	}

	if ( ! is_email( $data['email'] ) ) {
		exit( json_encode( array(
			'error' => __( 'The email address is invalid', 'thrive-cb' ),
		) ) );
	}

	$post = $data;
	unset( $post['action'], $post['__tcb_lg_fc'], $post['_back_url'] );

	/**
	 * action filter -  allows hooking into the form submission event
	 *
	 * @param array $post the full _POST data
	 *
	 */
	do_action( 'tcb_api_form_submit', $post );

	if ( empty( $data['__tcb_lg_fc'] ) || ! ( $connections = Thrive_Dash_List_Manager::decodeConnectionString( $data['__tcb_lg_fc'] ) ) ) {
		exit( json_encode( array(
			'error' => __( 'No connection for this form', 'thrive-cb' ),
		) ) );
	}

	//these are not needed anymore
	unset( $data['__tcb_lg_fc'], $data['_back_url'], $data['action'] );

	$result        = array();
	$data['name']  = ! empty( $data['name'] ) ? $data['name'] : '';
	$data['phone'] = ! empty( $data['phone'] ) ? $data['phone'] : '';

	/**
	 * filter - allows modifying the data before submitting it to the API
	 *
	 * @param array $data
	 */
	$data = apply_filters( 'tcb_api_subscribe_data', $data );

	if ( isset( $data['__tcb_lg_msg'] ) ) {
		$result['form_messages'] = Thrive_Dash_List_Manager::decodeConnectionString( $data['__tcb_lg_msg'] );
	}

	$available = Thrive_Dash_List_Manager::getAvailableAPIs( true );
	foreach ( $available as $key => $connection ) {
		if ( ! isset( $connections[ $key ] ) ) {
			continue;
		}
		if ( $key == 'klicktipp' && $data['_submit_option'] == 'klicktipp-redirect' ) {
			$result['redirect'] = tve_api_add_subscriber( $connection, $connections[ $key ], $data );
			if ( filter_var( $result['redirect'], FILTER_VALIDATE_URL ) !== false ) {
				$result[ $key ] = true;
			}
		} else {
			// Not sure how we can perform validations / mark errors here
			$result[ $key ] = tve_api_add_subscriber( $connection, $connections[ $key ], $data );
		}
	}

	/**
	 * $result will contain boolean 'true' or string error messages for each connected api
	 * these error messages will literally have no meaning for the user - we'll just store them in a db table and show them in admin somewhere
	 */
	echo json_encode( $result );
	die;
}


/**
 * make an api call to a subscribe a user
 *
 * @param string|Thrive_Dash_List_Connection_Abstract $connection
 * @param mixed $list_identifier the list identifier
 * @param array $data submitted data
 * @param bool $log_error whether or not to log errors in a DB table
 *
 * @return result mixed
 */
function tve_api_add_subscriber( $connection, $list_identifier, $data, $log_error = true ) {

	if ( is_string( $connection ) ) {
		$connection = Thrive_Dash_List_Manager::connectionInstance( $connection );
	}

	$key = $connection->getKey();

	/**
	 * filter - allows modifying the sent data to each individual API instance
	 *
	 * @param array $data data to be sent to the API instance
	 * @param Thrive_List_Connection_Abstract $connection the connection instance
	 * @param mixed $list_identifier identifier for the list which will receive the new email
	 */
	$data = apply_filters( 'tcb_api_subscribe_data_instance', $data, $connection, $list_identifier );

	/** @var Thrive_Dash_List_Connection_Abstract $connection */
	$result = $connection->addSubscriber( $list_identifier, $data );

	if ( ! $log_error || true === $result || ( $key == 'klicktipp' && filter_var( $result, FILTER_VALIDATE_URL ) !== false ) ) {
		return $result;
	}

	global $wpdb;

	/**
	 * at this point, we need to log the error in a DB table, so that the user can see all these error later on and (maybe) re-subscribe the user
	 */
	$log_data = array(
		'date'          => date( 'Y-m-d H:i:s' ),
		'error_message' => $result,
		'api_data'      => serialize( $data ),
		'connection'    => $connection->getKey(),
		'list_id'       => maybe_serialize( $list_identifier )
	);

	$wpdb->insert( $wpdb->prefix . 'tcb_api_error_log', $log_data );

	return $result;
}

/**
 * ajax call saves an option for the hyperlinks settings
 */
function tve_update_link_settings() {
	foreach ( $_POST as $setting => $v ) {
		if ( $v == "true" ) {
			$settings[] = $setting;
		}
	}

	$settings = serialize( $settings );

	$result = update_option( 'tve_hyperlink_settings', $settings );

	return $result;

}

/**
 * gets the post ID by Slug
 */
function tve_get_post_id_by_slug() {
	global $wpdb;
	$slug = $_POST['slug'];

	echo( $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'" ) );

	exit;

}

/**
 * called on the 'init' hook
 *
 * load all classes and files needed for TCB
 */
function tve_load_tcb_classes() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'landing-page/inc/TCB_Landing_Page_Transfer.php';
}

/**
 * @return TCB_Editor
 */
function tcb_editor() {
	return TCB_Editor::instance();
}

/**
 * Get the global cpanel configuration attributes (position, side, minimized etc)
 *
 * @return array
 */
function tve_cpanel_attributes() {
	$defaults = array(
		'position' => 'position',
		'color'    => 'light'
	);

	return get_option( 'tve_cpanel_config', $defaults );
}

/**
 * Get the post categories
 *
 * @return array
 */
function tve_get_post_categories() {
	$categories = array( 0 => __( 'All categories', 'thrive-cb' ) );
	foreach ( get_categories() as $cat ) {
		$categories[ $cat->cat_ID ] = $cat->cat_name;
	}

	return $categories;
}

/**
 * Get all defined menus
 *
 * @return array
 */
function tve_get_custom_menus() {
	$menu_items = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	$all_menus  = array();
	foreach ( $menu_items as $menu ) {
		$all_menus[] = array(
			'id'   => $menu->term_id,
			'name' => $menu->name,
		);
	}

	return $all_menus;
}