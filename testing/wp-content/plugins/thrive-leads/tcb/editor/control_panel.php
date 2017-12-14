<?php

require_once ABSPATH . 'wp-admin/includes/plugin.php';

global $page_section_patterns, $template_uri;

$menu_path         = dirname( __FILE__ ) . '/inc/menu/';
$side_menu_path    = dirname( __FILE__ ) . '/inc/side-menu/';
$is_thrive_theme   = tve_check_if_thrive_theme();
$template_uri      = rtrim( get_template_directory_uri(), '/' );
$editor_dir        = TVE_TCB_ROOT_PATH;
$landing_page_dir  = dirname( dirname( $editor_dir ) ) . '/landing-page/templates';
$cpanel_attributes = tve_cpanel_attributes();
$cpanel_position   = $cpanel_attributes['position'] === 'left' ? 'tve_cpanelFlip' : '';
$cpanel_color      = $cpanel_attributes['color'] === 'dark' ? 'tve_is_dark' : '';

$thrive_optins         = $thrive_optin_colors = $posts_categories = array(); // TODO: deprecated ?
$current_theme         = wp_get_theme(); // TODO: deprecated ?
$current_theme_name    = $current_theme->get( 'Name' ); // TODO: deprecated ?
$banned_themes_names[] = 'Performag'; // TODO: deprecated ?

if ( $is_thrive_theme ) {
	$thrive_optins       = tve_get_thrive_optins();
	$thrive_optin_colors = _thrive_get_color_scheme_options( 'optin' );
	$posts_categories    = tve_get_post_categories();
}

$landing_page_template = tve_post_is_landing_page( get_the_ID() );
$fonts                 = tve_get_all_custom_fonts( true );
$extra_custom_fonts    = apply_filters( 'tcb_extra_custom_fonts', array(), get_the_ID() );
$post_type             = get_post_type();
$menus                 = tve_get_custom_menus();

$last_revision_id = tve_get_last_revision_id( get_the_ID() );

$page_section_patterns = array();
if ( function_exists( '_thrive_get_patterns_from_directory' ) ) {
	$page_section_patterns = _thrive_get_patterns_from_directory();
	array_shift( $page_section_patterns );
}

$tve_display_save_notification = get_option( 'tve_display_save_notification', 1 );

$tve_disqus_shortname = get_option( 'tve_comments_disqus_shortname' );
$tve_facebook_admins  = get_option( 'tve_comments_facebook_admins' );

if ( is_array( $tve_facebook_admins ) ) {
	$tve_facebook_admins = implode( ';', $tve_facebook_admins );
}

$web_safe_fonts = tve_dash_font_manager_get_safe_fonts();

$show_thrive_theme_elements = $is_thrive_theme || $landing_page_template;

$show_page_sections         = apply_filters( 'tcb_show_page_sections_menu', $show_thrive_theme_elements );
$show_thrive_theme_elements = $show_page_sections;
$preview_url                = tcb_get_preview_url( get_the_ID() );
$cpanel_config              = apply_filters( 'tcb_main_cpanel_config', array() );

$timezone_offset = get_option( 'gmt_offset' );
$sign            = ( $timezone_offset < 0 ? '-' : '+' );
$min             = abs( $timezone_offset ) * 60;
$hour            = floor( $min / 60 );
$tzd             = $sign . str_pad( $hour, 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $min % 60, 2, '0', STR_PAD_LEFT );


/*Define the variables that are used in the control panel*/

/**
 * the constant should be defined somewhere in wp-config.php file
 */
$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

/**
 * we need to enforce this check here, so that we don't make http requests from https pages
 */
$admin_base_url = admin_url( '/', is_ssl() ? 'https' : 'admin' );
// for some reason, the above line does not work in some instances
if ( is_ssl() ) {
	$admin_base_url = str_replace( 'http://', 'https://', $admin_base_url );
}

$font_settings_url = $admin_base_url . 'admin.php?page=tve_dash_font_manager';
$wp_timezone       = $tzd;

// if the post is a TCB landing page, get the landing page configuration and send it to javascript
$tve_colour_mapping  = include TVE_TCB_ROOT_PATH . 'custom_colour_mappings.php';
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


?>
<?php if ( empty( $inner_frame ) ) : ?>
	<div class="tve-admin-padding tve_wrapper <?php echo esc_attr( $cpanel_position . ' ' . $cpanel_color ) ?>" id="tve_cpanel">
		<div class="tve_editor ">

			<div class="tve_cpanel_sec tve_control_btns">
				<div class="tve_btn_success tve_left" title="Save">
					<div class="tve_update" title="<?php echo __( 'Save Changes', 'thrive-cb' ) ?>" id="tve_update_content">
						<span class="tve_expanded"><?php echo __( 'Save Changes', 'thrive-cb' ) ?></span>
						<span class="tve_icm tve-ic-disk tve_collapsed"></span>
					</div>
				</div>
				<div class="tve_btn_default tve_expanded tve_left" title="<?php echo __( 'Publish', 'thrive-cb' ) ?>">
					<a class="tve_preview" title="Publish" id="tve_preview_content" target="_blank"
					   href="<?php echo $preview_url; ?>">
						<span class=""><?php echo __( 'Preview', 'thrive-cb' ) ?></span>
					</a>
				</div>
				<div class="tve_clear"></div>
			</div>

			<?php if ( $post_type === 'page' ) : ?>
				<?php include $side_menu_path . 'landing_pages.php' ?>
			<?php elseif ( $post_type === 'tcb_lightbox' ) : ?>
				<?php include $side_menu_path . 'lightboxes.php' ?>
			<?php endif ?>

			<?php include $side_menu_path . 'page_actions.php' ?>

			<div class="tve_cpanel_options">

				<?php include $side_menu_path . 'simple_content_elements.php' ?>

				<?php include $side_menu_path . 'multi_style_elements.php' ?>

				<?php include $side_menu_path . 'advanced_elements.php' ?>

				<?php if ( $show_thrive_theme_elements ) : ?>
					<?php include $side_menu_path . 'thrive_theme_elements.php' ?>
				<?php endif; ?>

			</div>
			<?php include $side_menu_path . 'editor_settings.php' ?>
		</div>
	</div>

	<?php return; ?>
<?php endif /* empty ( $inner_frame ) */ ?>
<?php /* else, we just output the elements control panel and lightboxes - INNER FRAME */ ?>

<?php /** static elements **/ ?>
<?php include plugin_dir_path( __FILE__ ) . 'inc/elements/onpage-elements.php' ?>

<!--lightbox stuff-->
<div class="tve_lightbox_overlay" id="tve_lightbox_overlay"></div>
<div class="tve_lightbox_frame" id="tve_lightbox_frame">
	<a class="tve-lightbox-close" href="javascript:void(0)" title="<?php echo __( "Close", "thrive-cb" ) ?>"><span
			class="tve_lightbox_close tve_click"
			data-ctrl="controls.lb_close"></span></a>

	<div class="tve_lightbox_content" id="tve_lightbox_content"></div>
	<div class="tve-sp"></div>
	<div class="tve_lightbox_buttons tve_clearfix" id="tve_lightbox_buttons">
		<input type="button"
		       class="tve_save_lightbox tve_mousedown tve_editor_button tve_editor_button_success tve_right"
		       value="<?php echo __( "Save", "thrive-cb" ) ?>" data-ctrl="controls.lb_save">
	</div>
</div>

<div id="tve_undo_manager" class="tve-disabled"></div>
<div id="tve_redo_manager" class="tve-disabled"></div>
<div id="delete_icon" class="tve_icm tve-ic-close"></div>
<div id="duplicate_icon" class="tve_icm tve-ic-copy"></div>

<?php /* static lightboxes */ ?>
<div class="tve-static-lb" style="display: none">
	<div style="display: none" id="lb_static_lp_export">
		<?php include dirname( __FILE__ ) . '/lb_static_lp_export.php' ?>
	</div>
	<div style="display: none;" id="lb_text_link">
		<?php include dirname( __FILE__ ) . '/lb_text_link.php' ?>
	</div>
	<div style="display: none;" id="lb_custom_html">
		<?php include dirname( __FILE__ ) . '/lb_custom_html.php' ?>
	</div>
	<div style="display: none;" id="lb_custom_css">
		<?php include dirname( __FILE__ ) . '/lb_custom_css.php' ?>
	</div>
	<div style="display: none;" id="lb_table">
		<?php include dirname( __FILE__ ) . '/lb_table.php' ?>
	</div>
	<div style="display: none;" id="lb_google_map">
		<?php include dirname( __FILE__ ) . '/lb_google_map.php' ?>
	</div>
	<?php do_action( 'tcb_static_lightbox' ) ?>
</div>

<?php
$menu_elements = array(
	'default_element',
	'typefocus',
	'text',
	'text_inline_only',
	'img',
	'button',
	'contentbox',
	'guarantee',
	'calltoaction',
	'testimonial',
	'bullets',
	'tabs',
	'toggle',
	'custom_html',
	'google_map',
	'feature_grid',
	'cc_icons',
	'pricing_table',
	'content_container',
	'table',
	'table_cell',
	'thrive_optin',
	'content_reveal',
	'tw_qs',
	'lead_generation',
	'lead_generation_input',
	'lead_generation_submit',
	'lead_generation_image_submit',
	'lead_generation_checkbox',
	'lead_generation_dropdown',
	'lead_generation_radio',
	'lead_generation_textarea',
	'post_grid',
	'contents_table',
	'responsive_video',
	'countdown_timer_evergreen',
	'countdown_timer',
	'rating',
	'shortcode',
	'lists',
	'icon',
	'widget_menu',
	'social_default',
	'social_custom',
	'progress_bar',
	'fill_counter',
	'number_counter',
	'facebook_comments',
	'disqus_comments',
	'columns',
	'columns_flex',
);
?>

<div class="tve_cpanel_onpage <?php echo $cpanel_color ?>" style="display: none" id="tve_cpanel_onpage">
	<div class="tve_secondLayer">

		<?php foreach ( $menu_elements as $menu ): ?>
			<div id="<?php echo $menu; ?>_menu">
				<?php include $menu_path . $menu . '.php'; ?>
			</div>
		<?php endforeach; ?>

		<?php if ( $show_page_sections ) : ?>
			<div id="page_section_menu">
				<?php include $menu_path . 'page_section.php' ?>
			</div>
		<?php endif; ?>

		<?php if ( $post_type == 'tcb_lightbox' ) : ?>
			<div id="lightbox_menu">
				<?php include $menu_path . 'lightbox.php' ?>
			</div>
		<?php endif ?>

		<?php if ( $landing_page_template ) : ?>
			<div id="landing_page_menu">
				<?php include $menu_path . 'landing_page.php' ?>
			</div>
			<div id="landing_page_content_menu">
				<?php include $menu_path . 'landing_page_content.php' ?>
			</div>
			<div id="landing_fonts_menu">
				<?php include $menu_path . 'landing_page_fonts.php' ?>
			</div>
		<?php endif ?>

		<div id="cb_text_menu">
			<?php $is_cb_text = true;
			include $menu_path . 'icon.php' ?>
		</div>

		<?php do_action( 'tcb_custom_menus_html', $menu_path ); ?>

		<div id="thrive_posts_list_menu">
			<?php include $menu_path . 'thrive_posts_list.php' ?>
		</div>

		<div id="thrive_custom_phone_menu">
			<?php include $menu_path . 'thrive_custom_phone.php' ?>
		</div>

		<div class="tve_clear"></div>
	</div>
	<a href="javascript:void(0)" id="tve_submenu_close" title="<?php echo __( "Close", "thrive-cb" ) ?>"></a>

	<div class="tve_menu" data-multiple-hide>
		<a href="javascript:void(0)" id="tve_submenu_save"
		   class="tve_click tve_icm tve-ic-toggle-down tve_lb_small tve_btn tve_no_hide"
		   data-ctrl="controls.lb_open"
		   data-load="1"
		   data-lb="lb_save_user_template"
		   title="<?php echo __( "Save this element as a Content Template", "thrive-cb" ) ?>">
			<input type="hidden" name="element" value="1"/>
		</a>
	</div>
	<div id="tve_iris_holder" style="display: none">
		<span class="tve_cp_text tve_cp_title" id="tve_cp_title"><?php echo __( "Text Color", "thrive-cb" ) ?></span>

		<div class="tve_cp_row"></div>

		<div class="tve_cp_row tve_clearfix">
			<span class="tve_cp_text"><?php echo __( "Color", "thrive-cb" ) ?></span>
			<input type="text" size="10" id="tve_cp_color" class="tve_right" style="width: 120px"/>
		</div>
		<div class="tve_cp_row tve_clearfix wp-picker-opacity" id="tve_opacity_ctrl">
			<span class="tve_cp_text tve_left" style=""><?php echo __( "Opacity", "thrive-cb" ) ?></span>
			<input type="text" size="2" id="tve_cp_opacity" class="tve_right" style="width: 36px"/>

			<div class="ui-slider-bg tve_right" style="width: 150px;">
				<div class="wp-opacity-slider" id="tve_cp_opacity_slider"></div>
			</div>
		</div>
		<div class="tve_cp_row tve_cp_actions">
			<div id="tve_cp_save_fav" class="tve_btn_default tve_left">
				<div class="tve_preview"><?php echo __( "Save as Favourite Color", "thrive-cb" ) ?></div>
			</div>
			<div class="tve_btn_success tve_right" id="tve_cp_ok">
				<div class="tve_update"><?php echo __( "OK", "thrive-cb" ) ?></div>
			</div>
		</div>
	</div>
</div>

<div style="display: none" id="tve_table_merge_actions" class="tve_table_merge_cells_actions">
	<?php include $menu_path . 'table_cell_manager.php' ?>
</div>

<div style="display: none" id="tve_social_sort" class="tve_social_sort">
	<?php include $menu_path . 'social_sort.php'; ?>
</div>

<div style="display: none" id="tve_toggle_reorder_menu" class="tve_focused_menu tve_event_root">
	<?php include $menu_path . 'toggle_reorder.php'; ?>
</div>

<?php /** These are all available element templates */ ?>
<div id="tve-elem-social-custom" style="display: none">
	<div class="tve_s_share_count"><span class="tve_s_cnt">0</span> <?php echo __( "shares", "thrive-cb" ); ?></div>
	<div class="tve_s_item tve_s_fb_share" data-s="fb_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span class="tve_s_text">Share</span><span
				class="tve_s_count">0</span></a>
	</div>
	<div class="tve_s_item tve_s_g_share" data-s="g_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span class="tve_s_text">Share +1</span><span
				class="tve_s_count">0</span></a>
	</div>
	<div class="tve_s_item tve_s_t_share" data-s="t_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span class="tve_s_text">Tweet</span><span
				class="tve_s_count">0</span></a>
	</div>
	<div class="tve_s_item tve_s_in_share" data-s="in_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span class="tve_s_text">Share</span><span
				class="tve_s_count">0</span></a>
	</div>
	<div class="tve_s_item tve_s_pin_share" data-s="pin_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span
				class="tve_s_text">Pin</span><span
				class="tve_s_count">0</span></a>
	</div>
	<div class="tve_s_item tve_s_xing_share" data-s="xing_share" data-href="{tcb_post_url}">
		<a href="javascript:void(0)" class="tve_s_link"><span class="tve_s_icon"></span><span class="tve_s_text">Share</span><span
				class="tve_s_count">0</span></a>
	</div>
</div>