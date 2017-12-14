<?php
/**
 * Mega Menu.
 * This file create and manager addtional options for Menu items, option which enable mega menu function.
 *
 * @package    NOO Framework
 * @subpackage Mega Menu
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Enqueue script for Mega Menu admin
if ( ! function_exists( 'noo_enqueue_mega_menu_js' ) ) :
	function noo_enqueue_mega_menu_js( $hook ) {
		if ( $hook != 'nav-menus.php' ) {
			return;
		}

		// Enqueue style for Mega Menu admin
		wp_register_style( 'noo-mega-menu-admin-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-mega-menu-admin.css' );
		wp_enqueue_style( 'noo-mega-menu-admin-css' );

		wp_register_script( 'noo-mega-menu-admin-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-mega-menu-admin.js', null, null, true );
		wp_enqueue_script( 'noo-mega-menu-admin-js' );
	}
endif;
add_action( 'admin_enqueue_scripts', 'noo_enqueue_mega_menu_js' );

// Function for adding mega menu class to menu items.
if( ! function_exists( 'noo_mega_menu_get_nav_menu_items' ) ) :
	function noo_mega_menu_nav_class( $classes, $item ) {
		$noo_header_nav_position = noo_get_option( 'noo_header_nav_position', 'fixed_top' );
		$is_vertical_menu = ( $noo_header_nav_position == 'fixed_left' || $noo_header_nav_position == 'fixed_right' );

	    $noo_mega_menu_enable  = noo_get_post_meta( $item->ID, '_noo_mega_menu_enable', 0 );
		$noo_submenu_alignment = noo_get_post_meta( $item->ID, '_noo_submenu_alignment', '' );
		$noo_submenu_direction = noo_get_post_meta( $item->ID, '_noo_submenu_direction', '' );
		$noo_menu_visibility   = noo_get_post_meta( $item->ID, '_noo_menu_visibility', '' );

		if( $noo_mega_menu_enable ) {
			$classes[] = 'megamenu';
			$classes[] = noo_get_post_meta( $item->ID, '_noo_mega_menu_columns', 'columns-3' );
			$classes[] = ( noo_get_post_meta( $item->ID, '_noo_mega_menu_fullheight', 0 ) ? 'fullheight' : '' );
		}

		if( ! $is_vertical_menu ) {
			$noo_submenu_alignment = ( empty( $noo_submenu_alignment ) ||  $noo_submenu_alignment == 'full-height' ) ? '' : $noo_submenu_alignment;
			if(  !empty( $noo_submenu_alignment ) ) {
				$classes[] = $noo_submenu_alignment;
			}

			if(  !empty( $noo_submenu_direction ) ) {
				$classes[] = $noo_submenu_direction;
			}
		}

		switch ($noo_menu_visibility) {
		    case 'hidden-phone':
		        $classes[] = ' hidden-xs';
		        break;
		    case 'hidden-tablet':
		        $classes[] = ' hidden-sm hidden-md';
		        break;
		    case 'hidden-pc':
		        $classes[] = ' hidden-lg';
		        break;
		    case 'visible-phone':
		        $classes[] = ' visible-xs-block visible-xs-inline visible-xs-inline-block';
		        break;
		    case 'visible-tablet':
		        $classes[] = ' visible-sm-block visible-sm-inline visible-sm-inline-block visible-md-block visible-md-inline visible-md-inline-block';
		        break;
		    case 'visible-phone':
		        $classes[] = ' visible-lg-block visible-lg-inline visible-lg-inline-block';
		        break;
		}

	    return $classes;
	}
endif;
add_filter( 'nav_menu_css_class', 'noo_mega_menu_nav_class', 10, 2 );

// Function for update additional setting when user save.
if( ! function_exists( 'noo_mega_menu_update_nav_menu_item' ) ) :
	function noo_mega_menu_update_nav_menu_item( $menu_id, $menu_item_db_id ) {
		$noo_mega_menu_enable = isset( $_POST['menu-item-noo-mega-menu-enable'][$menu_item_db_id] ) && $_POST['menu-item-noo-mega-menu-enable'][$menu_item_db_id] == 1;
		update_post_meta( $menu_item_db_id, '_noo_mega_menu_enable', $noo_mega_menu_enable ? 1 : 0 );

		if( $noo_mega_menu_enable ) {
			$noo_mega_menu_columns = isset($_POST['menu-item-noo-mega-menu-columns'][$menu_item_db_id]) ? $_POST['menu-item-noo-mega-menu-columns'][$menu_item_db_id] : '';
			update_post_meta( $menu_item_db_id, '_noo_mega_menu_columns', $noo_mega_menu_columns );
			$noo_mega_menu_fullwidth = isset($_POST['menu-item-noo-mega-menu-fullwidth'][$menu_item_db_id]) ? $_POST['menu-item-noo-mega-menu-fullwidth'][$menu_item_db_id] : '';
			update_post_meta( $menu_item_db_id, '_noo_mega_menu_fullwidth', $noo_mega_menu_fullwidth );
		}
		$noo_submenu_alignment = isset( $_POST['menu-item-noo-submenu-alignment'][$menu_item_db_id] ) ? $_POST['menu-item-noo-submenu-alignment'][$menu_item_db_id] : '';
		update_post_meta( $menu_item_db_id, '_noo_submenu_alignment', $noo_submenu_alignment);
		$noo_submenu_direction = isset( $_POST['menu-item-noo-submenu-direction'][$menu_item_db_id] ) ? $_POST['menu-item-noo-submenu-direction'][$menu_item_db_id] : '';
		update_post_meta( $menu_item_db_id, '_noo_submenu_direction', $noo_submenu_direction );
		$noo_menu_visibility = isset( $_POST['menu-item-noo-menu-visibility'][$menu_item_db_id] ) ? $_POST['menu-item-noo-menu-visibility'][$menu_item_db_id] : '';
		update_post_meta( $menu_item_db_id, '_noo_menu_visibility', $noo_menu_visibility );
	}
endif;
add_action( 'wp_update_nav_menu_item', 'noo_mega_menu_update_nav_menu_item', 10, 2 );

/**
 * Walker Class for adding mega menu settings.
 */

require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );

if( ! class_exists( 'NOO_Mega_Menu_Nav_Menu_Edit' ) ) :
	class NOO_Mega_Menu_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
		static $in_mega_menu = false;

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			parent::start_el( $output, $item, $depth, $args, $id );

			// Input the option right before Submit Button
			$desc_snipp = '<div class="menu-item-actions description-wide submitbox">';
			$pos = strrpos( $output, $desc_snipp );
			if( $pos !== false ) {
				$output = substr_replace($output, $this->noo_mega_menu_settings( $item, ( $depth == 0 ) ) . $desc_snipp, $pos, strlen( $desc_snipp ) );
			}
		}

		function noo_mega_menu_settings( $item, $firstlevel = false ) {
			$noo_header_nav_position = noo_get_option( 'noo_header_nav_position', 'fixed_top' );
			$is_vertical_menu      = ( $noo_header_nav_position == 'fixed_left' || $noo_header_nav_position == 'fixed_right' );

			$noo_mega_menu_enable  = noo_get_post_meta( $item->ID, '_noo_mega_menu_enable', 0 );
			$noo_mega_menu_columns = noo_get_post_meta( $item->ID, '_noo_mega_menu_columns', 'columns-3' );
			$noo_mega_menu_fullheight  = noo_get_post_meta( $item->ID, '_noo_mega_menu_fullheight', 0 );

			$noo_submenu_alignment = noo_get_post_meta( $item->ID, '_noo_submenu_alignment', '' );
			$noo_submenu_direction = noo_get_post_meta( $item->ID, '_noo_submenu_direction', '' );
			$noo_menu_visibility   = noo_get_post_meta( $item->ID, '_noo_menu_visibility', '' );

			$html = '<div class="noo-menu-form" >';
			if( $firstlevel ) {
				// reset mega menu status
				self::$in_mega_menu = $noo_mega_menu_enable;
				if( empty($noo_submenu_alignment) && ( ! $is_vertical_menu )) {
					$noo_submenu_alignment = 'align-left';
				}

				ob_start();
				?>

				<p class="noo-mega-menu-enable description description-wide">
					<label>
						<input <?php checked( $noo_mega_menu_enable, 1 ) ?> type="checkbox" value="1" class="menu-item-noo-mega-menu-enable" name="menu-item-noo-mega-menu-enable[<?php echo $item->ID; ?>]" />
						<?php _e( 'Enable Mega Menu', 'noo' ) ?>
					</label>
				</p>
				<p class="noo-mega-menu-columns description description-wide megamenu-child-options" style="display: <?php echo $noo_mega_menu_enable ? 'block' : 'none' ?>">
					<label for="menu-item-noo-mega-menu-columns-<?php echo $item->ID; ?>">
						<?php echo ( $is_vertical_menu ? __( 'Number of Rows', 'noo' ) : __( 'Number of Columns', 'noo' ) ); ?>
						<br/>
						<select id="menu-item-noo-mega-menu-columns-<?php echo $item->ID; ?>" name="menu-item-noo-mega-menu-columns[<?php echo $item->ID; ?>]" class="noo-mega-menu-columns">
							<option <?php selected( 'columns-2', $noo_mega_menu_columns ) ?> value="columns-2"><?php _e( 'Two', 'noo' ) ?></option>
							<option <?php selected( 'columns-3', $noo_mega_menu_columns ) ?> value="columns-3"><?php _e( 'Three', 'noo' ) ?></option>
							<option <?php selected( 'columns-4', $noo_mega_menu_columns ) ?> value="columns-4"><?php _e( 'Four', 'noo' ) ?></option>
							<option <?php selected( 'columns-5', $noo_mega_menu_columns ) ?> value="columns-5"><?php _e( 'Five', 'noo' ) ?></option>
							<option <?php selected( 'columns-6', $noo_mega_menu_columns ) ?> value="columns-6"><?php _e( 'Six', 'noo' ) ?></option>
						</select>
					</label>
				</p>

				<?php if( ! $is_vertical_menu ) : ?>
				<p class="noo-submenu-alignment description description-wide">
					<label for="menu-item-noo-submenu-alignment-<?php echo $item->ID; ?>">
						<?php _e( 'Sub-Menu Alignment', 'noo' ); ?>
						<br/>
						<select id="menu-item-noo-submenu-alignment-<?php echo $item->ID; ?>" name="menu-item-noo-submenu-alignment[<?php echo $item->ID; ?>]">
							<option <?php selected( 'full-width', $noo_submenu_alignment ) ?> value="full-width" class="megamenu-child-options" style="display: <?php echo $noo_mega_menu_enable ? 'inline' : 'none' ?>;"><?php _e( 'Full-width', 'noo' ); ?></option>
							<option <?php selected( 'align-left', $noo_submenu_alignment ) ?> value="align-left"><?php _e( 'Align Left', 'noo' ) ?></option>
							<option <?php selected( 'align-right', $noo_submenu_alignment ) ?> value="align-right"><?php _e( 'Align Right', 'noo' ) ?></option>
							<option <?php selected( 'align-center', $noo_submenu_alignment ) ?> value="align-center"><?php _e( 'Align Center', 'noo' ) ?></option>
						</select>
					</label>
				</p>
				<?php else: ?>
				<p class="noo-mega-menu-fullheight description description-wide" class="megamenu-child-options" style="display: <?php echo $noo_mega_menu_enable ? 'block' : 'none' ?>">
					<label>
						<input <?php checked( $noo_mega_menu_fullheight, 1 ) ?> type="checkbox" value="1" class="menu-item-noo-mega-menu-fullheight" name="menu-item-noo-mega-menu-fullwidth[<?php echo $item->ID; ?>]" />
						<?php _e( 'Full-height Mega Menu', 'noo' ); ?>
					</label>
				</p>
				<?php endif; ?>
				<p class="noo-menu-visibility description description-wide">
					<label for="menu-item-noo-menu-visibility-<?php echo $item->ID; ?>">
						<?php _e( 'Visibility', 'noo' ); ?>
						<br/>
						<select id="menu-item-noo-menu-visibility-<?php echo $item->ID; ?>" name="menu-item-noo-menu-visibility[<?php echo $item->ID; ?>]">
							<option <?php selected( '', $noo_menu_visibility ) ?> value="" ><?php _e('All Devices', 'noo'); ?></option>
							<option <?php selected( 'hidden-phone', $noo_menu_visibility ) ?> value="hidden-phone"><?php _e('Hidden Phone', 'noo'); ?></option>
							<option <?php selected( 'hidden-tablet', $noo_menu_visibility ) ?> value="hidden-tablet"><?php _e('Hidden Tablet', 'noo'); ?></option>
							<option <?php selected( 'hidden-pc', $noo_menu_visibility ) ?> value="hidden-pc"><?php _e('Hidden PC', 'noo'); ?></option>
							<option <?php selected( 'visible-phone', $noo_menu_visibility ) ?> value="visible-phone"><?php _e('Visible Phone', 'noo'); ?></option>
							<option <?php selected( 'visible-tablet', $noo_menu_visibility ) ?> value="visible-tablet"><?php _e('Visible Tablet', 'noo'); ?></option>
							<option <?php selected( 'visible-pc', $noo_menu_visibility ) ?> value="visible-pc"><?php _e('Visible PC', 'noo'); ?></option>
						</select>
					</label>
				</p>
				<input type="hidden" name="menu-item-noo-submenu-direction[<?php echo $item->ID; ?>]" value="" />

				<?php
				$html .= ob_get_clean();
			} else {
				if( empty($noo_submenu_direction) && ( ! $is_vertical_menu ) ) {
					$noo_submenu_direction = 'fly-right';
				}
				ob_start();
				?>

				<?php if( self::$in_mega_menu || $is_vertical_menu ) : ?>
				<input type="hidden" value="" name="menu-item-noo-submenu-direction[<?php echo $item->ID; ?>]" />
				<?php else : ?>
				<p class="noo-submenu-direction description description-wide">
					<label for="menu-item-noo-submenu-direction-<?php echo $item->ID; ?>">
						<?php _e( 'Flying Direction for Sub-Menu of this Item', 'noo' ); ?>
						<br/>
						<select id="menu-item-noo-submenu-direction-<?php echo $item->ID; ?>" name="menu-item-noo-submenu-direction[<?php echo $item->ID; ?>]">
							<option <?php selected( 'fly-left', $noo_submenu_direction ) ?> value="fly-left"><?php _e( 'Fly Left', 'noo' ) ?></option>
							<option <?php selected( 'fly-right', $noo_submenu_direction ) ?> value="fly-right"><?php _e( 'Fly Right', 'noo' ) ?></option>
						</select>
					</label>
				</p>
				<?php endif; ?>
				<p class="noo-menu-visibility description description-wide">
					<label for="menu-item-noo-menu-visibility-<?php echo $item->ID; ?>">
						<?php _e( 'Visibility', 'noo' ); ?>
						<br/>
						<select id="menu-item-noo-menu-visibility-<?php echo $item->ID; ?>" name="menu-item-noo-menu-visibility[<?php echo $item->ID; ?>]">
							<option <?php selected( '', $noo_menu_visibility ) ?> value="" ><?php _e('All Devices', 'noo'); ?></option>
							<option <?php selected( 'hidden-phone', $noo_menu_visibility ) ?> value="hidden-phone"><?php _e('Hidden Phone', 'noo'); ?></option>
							<option <?php selected( 'hidden-tablet', $noo_menu_visibility ) ?> value="hidden-tablet"><?php _e('Hidden Tablet', 'noo'); ?></option>
							<option <?php selected( 'hidden-pc', $noo_menu_visibility ) ?> value="hidden-pc"><?php _e('Hidden PC', 'noo'); ?></option>
							<option <?php selected( 'visible-phone', $noo_menu_visibility ) ?> value="visible-phone"><?php _e('Visible Phone', 'noo'); ?></option>
							<option <?php selected( 'visible-tablet', $noo_menu_visibility ) ?> value="visible-tablet"><?php _e('Visible Tablet', 'noo'); ?></option>
							<option <?php selected( 'visible-pc', $noo_menu_visibility ) ?> value="visible-pc"><?php _e('Visible PC', 'noo'); ?></option>
						</select>
					</label>
				</p>
				<input type="hidden" value="0" name="menu-item-noo-mega-menu-enable[<?php echo $item->ID; ?>]" />
				<input type="hidden" value="" name="menu-item-noo-mega-menu-columns[<?php echo $item->ID; ?>]" />
				<input type="hidden" value="" name="menu-item-noo-mega-menu-fullwidth[<?php echo $item->ID; ?>]" />
				<input type="hidden" value="" name="menu-item-noo-submenu-alignment[<?php echo $item->ID; ?>]" />

				<?php
				$html .= ob_get_clean();
			}

			$html .= '</div>';

			return $html;
		}
	}
endif;

add_filter( 'wp_edit_nav_menu_walker', create_function( '', 'return "NOO_Mega_Menu_Nav_Menu_Edit";' ) );