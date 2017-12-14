<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
if (! function_exists( 'mmm_get_skin' ) ) {
	function mmm_get_skin () {
	global $mega_main_menu;
	$mega_menu_locations = is_array( $mega_main_menu->get_option( 'mega_menu_locations' ) ) 
		? $mega_main_menu->get_option( 'mega_menu_locations' ) 
		: array();
/* empty */
$out = '.empty{}/* empty */';
$out .= '
#mega_main_menu .nav_logo > .logo_link > img 
{
	max-height: ' . $mega_main_menu->get_option( 'logo_height', '90' ) . '%;
}
';
/* mega_menu_locations */
/*		array_shift( $mega_menu_locations ); */
				if ( in_array( 'is_checkbox', $mega_menu_locations) ) {
					$is_checkbox_key = array_search( 'is_checkbox', $mega_menu_locations );
					unset( $mega_menu_locations[ $is_checkbox_key ] );
				}
				foreach ( $mega_menu_locations as $key => $location_name ) {
		if ( is_array( $mega_main_menu->get_option( 'indefinite_location_mode' ) ) && in_array( 'true', $mega_main_menu->get_option( 'indefinite_location_mode' ) ) ) {
				$location_class = '';
		} else {
				$location_class = '.' . $location_name;
		}

		$out .= '/* ' . $location_name . ' */
/* initial_height */
#mega_main_menu' . $location_class . '
{
	min-height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height' ) . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > .nav_logo > .logo_link, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link > .link_content, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_search_box,
#mega_main_menu' . $location_class . '.icons-left > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.icons-right > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.disable_icon > .link_content,
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.menu_item_without_text > i, 
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link > i.ci-icon-buddypress-user
{
	height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height' ) . 'px;
	line-height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height' ) . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link > .link_content > .link_text
{
	height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height' ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] / 2 ) . 'px;
	line-height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.with_icon > .link_content > .link_text
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i
{
	padding-top:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] / 3 / 2 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
{
	padding-bottom:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] / 3 / 2 ) . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link > i:before
{	
	width:' . ( $mega_main_menu->get_option( $location_name . '_first_level_item_height' ) * 0.6 ) . 'px;
}

#mega_main_menu' . $location_class . ' > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link > .link_content > .link_text 
{
	height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.icons-top > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link > .link_content
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 2 ) . 'px;
	line-height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link.with_icon > .link_content > .link_text
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link > i
{
	padding-top:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 / 2 ) . 'px;
}
#mega_main_menu' . $location_class . '.icons-top > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link > .link_content
{
	padding-bottom:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 / 2 ) . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder.sticky_container > .menu_inner > ul > li.nav_buddypress > .item_link > i:before
{	
	width:' . ( $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) * 0.6 ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link 
{
	margin:' . ( ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] - $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] ) / 2 ) . 'px 4px;
}

/* initial_height_mobile */
@media (max-width: 767px) { /* DO NOT CHANGE THIS LINE (See = Specific Options -> Responsive Resolution) */
	#mega_main_menu' . $location_class . '
	{
		min-height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > .nav_logo > .logo_link, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li > .item_link, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li > .item_link > .link_content, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li.nav_search_box,
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-left > .menu_holder > .menu_inner > ul > li > .item_link > i,
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-right > .menu_holder > .menu_inner > ul > li > .item_link > i,
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.disable_icon > .link_content,
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.menu_item_without_text > i, 
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link > i.ci-icon-buddypress-user
	{
		height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) . 'px;
		line-height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li > .item_link > .link_content > .link_text 
	{
		height:' . $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i,
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
	{
		height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 2 ) . 'px;
		line-height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i
	{
		padding-top:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 / 2 ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
	{
		padding-bottom:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] / 3 / 2 ) . 'px;
	}
	#mega_main_menu' . $location_class . '.mobile_minimized-enable > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link > i:before
	{	
		width:' . ( $mega_main_menu->get_option( $location_name . '_first_level_item_height_sticky' ) * 0.6 ) . 'px;
	}
	#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link 
	{
		margin:' . ( ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height_sticky' ] - $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] ) / 2 ) . 'px 4px;
	}
}
/* style-buttons */
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link, 
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link > .link_content, 
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-left > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-right > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.disable_icon > .link_content,
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.menu_item_without_text > i, 
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link > i.ci-icon-buddypress-user
{
	height:' . $mega_main_menu->get_option( $location_name . '_first_level_button_height' ) . 'px;
	line-height:' . $mega_main_menu->get_option( $location_name . '_first_level_button_height' ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link > .link_content > .link_text 
{
	height:' . $mega_main_menu->get_option( $location_name . '_first_level_button_height' ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link 
{
	margin:' . ( ( $mega_main_menu->saved_options[ $location_name . '_first_level_item_height' ] - $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] ) / 2 ) . 'px 4px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i,
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] / 2 ) . 'px;
	line-height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link.with_icon > .link_content > .link_text 
{
	height:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] / 3 ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > i
{
	padding-top:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] / 3 / 2 ) . 'px;
}
#mega_main_menu' . $location_class . '.primary_style-buttons.icons-top > .menu_holder > .menu_inner > ul > li > .item_link > .link_content
{
	padding-bottom:' . ( $mega_main_menu->saved_options[ $location_name . '_first_level_button_height' ] / 3 / 2 ) . 'px;
}
/* color_scheme */
#mega_main_menu' . $location_class . ' > .menu_holder > .mmm_fullwidth_container
{
	' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_bg_gradient' ] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .mmm_fullwidth_container
{
	' . mad_mm_common::css_bg_image( $mega_main_menu->saved_options[ $location_name . '_menu_bg_image' ] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link .link_text,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_search_box *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .post_details > .post_title,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .post_details > .post_title > .item_link
{
	' . mad_mm_common::css_font( $mega_main_menu->saved_options[ $location_name . '_menu_first_level_link_font'] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link > i
{
	' . mad_mm_common::css_font( $mega_main_menu->saved_options[ $location_name . '_menu_first_level_icon_font'] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link > i:before
{	
	width:' . $mega_main_menu->saved_options[ $location_name . '_menu_first_level_icon_font']['font_size'] . 'px;
}
/*
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link *
{
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_first_level_link_color'] . ';
}
*/

@media only screen and (min-width: 993px) {

	#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle,
	#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link
	{
		' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_first_level_link_bg'] ) . '
	}

}


#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li:hover > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link:hover,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link:focus,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-item > .item_link
{
	' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_first_level_link_bg_hover'] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_search_box > #mega_main_menu_searchform
{
	background-color:' . $mega_main_menu->saved_options[ $location_name . '_menu_search_bg'] . ';
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_search_box .field,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.nav_search_box *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .icosearch
{
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_search_color'] . ';
}


/*
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li:hover > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link:hover,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .item_link:focus,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li:hover > .item_link *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-item > .item_link *
{
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_first_level_link_color_hover'] . ';
}
*/

#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.default_dropdown .mega_dropdown,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li > .mega_dropdown,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .mega_dropdown > li .post_details
{
	' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_wrapper_gradient'] ) . '
}

#mega_main_menu' . $location_class . ' ul li .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' ul li .mega_dropdown > li > .item_link .link_text,
#mega_main_menu' . $location_class . ' ul li .mega_dropdown,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .post_details > .post_description
{
	' . mad_mm_common::css_font( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_font'] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li .mega_dropdown > li > .item_link.with_icon
{
	line-height: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
	min-height: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
}
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > .menu-item > .item_link > i,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > .menu-item > .item_link > i,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > .menu-item > .item_link > i,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > .menu-item > .item_link > i
{
	width: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
	height: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
	line-height: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
	font-size: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] . 'px;
	margin-top: -' . ( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] / 2 ) . 'px;
}
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content
{
	margin-left: ' . ( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] + 8 ) . 'px;
}
#mega_main_menu' . $location_class . '.language_direction-rtl li.default_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . '.language_direction-rtl li.tabs_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . '.language_direction-rtl li.widgets_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content,
#mega_main_menu' . $location_class . '.language_direction-rtl li.multicolumn_dropdown > .mega_dropdown > .menu-item > .item_link.with_icon > .link_content
{
	margin-right: ' . ( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_icon_font']['font_size'] + 8 ) . 'px;
}
#mega_main_menu' . $location_class . ' ul li.default_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' ul li.widgets_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' ul li.multicolumn_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' ul li.grid_dropdown .mega_dropdown > li > .item_link
{
	' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_bg'] ) . '
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_color'] . ';
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .post_details > .post_icon > i,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .mega_dropdown a.item_link *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .mega_dropdown a,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .mega_dropdown a *,
#mega_main_menu' . $location_class . ' ul li.default_dropdown .mega_dropdown > li > a.item_link *,
#mega_main_menu' . $location_class . ' ul li.widgets_dropdown .mega_dropdown > li > a.item_link *
#mega_main_menu' . $location_class . ' ul li.multicolumn_dropdown .mega_dropdown > li > a.item_link *
#mega_main_menu' . $location_class . ' ul li.grid_dropdown .mega_dropdown > li > a.item_link *,
#mega_main_menu' . $location_class . ' ul li li .post_details a
{
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_color'] . ';
}
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li > .item_link
{
	border-color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_border_color'] . ';
}
#mega_main_menu' . $location_class . ' .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' .mega_dropdown > li > a.item_link:focus,
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li:hover > a.item_link,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li:hover > .processed_image,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li:hover > a.item_link,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li > a.item_link:hover,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li.current-menu-item > a.item_link,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li > .processed_image:hover
{
	' . mad_mm_common::css_gradient( $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_bg_hover'] ) . '
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_color_hover'] . ';
}
#mega_main_menu' . $location_class . ' .mega_dropdown > li > a.item_link:hover *,
#mega_main_menu' . $location_class . ' .mega_dropdown > li > a.item_link:focus *,
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li > a.item_link:hover *,
#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > li > a.item_link:hover *,
#mega_main_menu' . $location_class . ' li.widgets_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > li > a.item_link:hover *,
#mega_main_menu' . $location_class . ' li.tabs_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > li > a.item_link:hover *,
#mega_main_menu' . $location_class . ' li.multicolumn_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li:hover > a.item_link *,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li:hover > a.item_link *,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li a:hover *,
#mega_main_menu' . $location_class . ' li.grid_dropdown > .mega_dropdown > li.current-menu-item > a.item_link *,
#mega_main_menu' . $location_class . ' li.post_type_dropdown > .mega_dropdown > li > .processed_image:hover > .cover > a > i
{
	color: ' . $mega_main_menu->saved_options[ $location_name . '_menu_dropdown_link_color_hover'] . ';
}
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > ul > li > .item_link,
#mega_main_menu' . $location_class . '.primary_style-buttons > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle,
#mega_main_menu' . $location_class . '.primary_style-buttons.direction-vertical > .menu_holder > .menu_inner > ul > li:first-child > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .mmm_fullwidth_container,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li .post_details
{
	border-radius: ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > span.nav_logo,
#mega_main_menu' . $location_class . '.primary_style-flat.direction-horizontal.first-lvl-align-left.no-logo > .menu_holder > .menu_inner > ul > li:first-child > .item_link,
#mega_main_menu' . $location_class . '.primary_style-flat.direction-horizontal.first-lvl-align-center.no-logo.no-search.no-woo_cart > .menu_holder > .menu_inner > ul > li:first-child > .item_link
{
	border-radius: ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px 0px 0px ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px;
}
#mega_main_menu' . $location_class . '.direction-horizontal.no-search > .menu_holder > .menu_inner > ul > li.nav_woo_cart > .item_link,
#mega_main_menu' . $location_class . '.direction-horizontal.no-search.no-woo_cart > .menu_holder > .menu_inner > ul > li.nav_buddypress > .item_link,
#mega_main_menu' . $location_class . '.direction-horizontal.first-lvl-align-right.no-search.no-woo_cart > .menu_holder > .menu_inner > ul > li:last-child > .item_link,
#mega_main_menu' . $location_class . '.primary_style-flat.direction-horizontal.first-lvl-align-center.no-search.no-woo_cart > .menu_holder > .menu_inner > ul > li:last-child > .item_link
{
	border-radius: 0px ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px 0px;
}

#mega_main_menu' . $location_class . ' li.default_dropdown > .mega_dropdown > li:last-child > .item_link
{
	border-radius: 0px 0px ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px ' . $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] . 'px;
}
#mega_main_menu' . $location_class . ' .widgets_dropdown > .mega_dropdown > li.default_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .multicolumn_dropdown > .mega_dropdown > li.default_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' ul .nav_search_box #mega_main_menu_searchform,
#mega_main_menu' . $location_class . ' .tabs_dropdown .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .tabs_dropdown .mega_dropdown > li > .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .widgets_dropdown > .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .multicolumn_dropdown > .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .grid_dropdown > .mega_dropdown > li > .item_link,
#mega_main_menu' . $location_class . ' .grid_dropdown > .mega_dropdown > li .processed_image,
#mega_main_menu' . $location_class . ' .post_type_dropdown > .mega_dropdown > li .item_link,
#mega_main_menu' . $location_class . ' .post_type_dropdown > .mega_dropdown > li .processed_image
{
	border-radius: ' . ( $mega_main_menu->saved_options[ $location_name . '_corners_rounding'] / 2 ) . 'px;
}
';
			if ( isset( $mega_main_menu->saved_options[ 'additional_styles_presets' ] ) && is_array( $mega_main_menu->saved_options[ 'additional_styles_presets' ] ) && count( $mega_main_menu->saved_options[ 'additional_styles_presets' ] ) > 0 ) {
				$out .= '/* additional_styles */ ';
				foreach ( $mega_main_menu->saved_options[ 'additional_styles_presets' ] as $key => $value ) {
					$out .= '
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link
{
	' . mad_mm_common::css_gradient( $value['bg_gradient'] ) . '
	color: ' . $value['text_color'] . ';
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link > i
{
	color: ' . $value['text_color'] . ';
	font-size: ' . $value[ 'icon' ]['font_size'] . 'px;
}
#mega_main_menu' . $location_class . ' ul li .mega_dropdown li.additional_style_' . $key . ' > .item_link > i
{
	width: ' . $value[ 'icon' ]['font_size'] . 'px;
	height: ' . $value[ 'icon' ]['font_size'] . 'px;
	line-height: ' . $value[ 'icon' ]['font_size'] . 'px;
	font-size: ' . $value[ 'icon' ]['font_size'] . 'px;
	margin-top: -' . ( $value[ 'icon' ]['font_size'] / 2 ) . 'px;
}
#mega_main_menu' . $location_class . ' ul li .mega_dropdown > li.additional_style_' . $key . ' > .item_link.with_icon > span
{
	margin-left: ' . ( $value[ 'icon' ]['font_size'] + 8 ) . 'px;
}
#mega_main_menu' . $location_class . '.language_direction-rtl ul li .mega_dropdown > li.additional_style_' . $key . ' > .item_link.with_icon > span
{
	margin-right: ' . ( $value[ 'icon' ]['font_size'] + 8 ) . 'px;
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link .link_content
{
	color: ' . $value['text_color'] . ';
	' . mad_mm_common::css_font( $value[ 'font' ] ) . '
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul > li.current-menu-ancestor.additional_style_' . $key . ' > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.current-menu-item.additional_style_' . $key . ' > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ':hover > .item_link,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link:hover,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link:focus
{
	' . mad_mm_common::css_gradient( $value['bg_gradient_hover'] ) . '
	color: ' . $value['text_color_hover'] . ';
}
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ' > .item_link:focus > *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ':hover > .item_link > i,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ':hover > .item_link *,
#mega_main_menu' . $location_class . ' > .menu_holder > .menu_inner > ul li.additional_style_' . $key . ':hover > .item_link .link_content
{
	color: ' . $value['text_color_hover'] . ';
}
';
				}
			}
		}/* foreach ( $mega_menu_locations as $key => $location_name ) { */
/* set_of_custom_icons */
$out .= '/* set_of_custom_icons */ ';
$set_of_custom_icons = isset( $mega_main_menu->saved_options[ 'set_of_custom_icons' ] ) ? $mega_main_menu->saved_options[ 'set_of_custom_icons' ] : '';
if ( is_array( $set_of_custom_icons ) && count( $set_of_custom_icons ) > 0 ) {
	foreach ( $set_of_custom_icons as $value ) {
		$icon_name = str_replace( array( '/', strrchr( $value[ 'custom_icon' ], '.' ) ), '', strrchr( $value[ 'custom_icon' ], '/' ) );
		$out .= '
i.ci-icon-' . $icon_name . ':before
{
	background-image: url(' . $value[ 'custom_icon' ] . ');
}
';
	}
}
/* skin extend */
if ( has_filter( 'mmm_skin_extend' ) ) {
	$out .= '/* skin extend */' . apply_filters( 'mmm_skin_extend', $skin_extend = '' );
}
/* custom css */
$out .= ( isset( $mega_main_menu->saved_options[ 'custom_css' ] ) && !empty( $mega_main_menu->saved_options[ 'custom_css' ] ) ) 
	? '/* custom css */ ' . $mega_main_menu->saved_options[ 'custom_css' ] 
	: '';
$out .= ' /*' . date("Y-m-d H:i") . '*/';
/* RETURN */
	return $out;
	}
}
?>