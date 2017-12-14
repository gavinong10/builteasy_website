<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

	/** 
	 * actions what we need for call all functions in this file.
	 * @return void
	 */
	add_action( 'wp_update_nav_menu_item', 'mmm_nav_update', 2014, 5 );
	add_filter( 'wp_setup_nav_menu_item','mmm_nav_item', 2014, 5 );
	add_filter( 'wp_edit_nav_menu_walker', 'mega_main_menu_backend_walker', 2014, 5 );

	/** 
	 * Update menu post_meta fields.
	 * @return void
	 */
	if ( !function_exists( 'mmm_nav_update' ) ) {
		function mmm_nav_update( $menu_id, $menu_item_db_id, $args ) {
			global $mega_main_menu;
			$options_array = mmm_menu_options_array();
			foreach ( $options_array as $value ) {
				if ( isset ( $_REQUEST[ 'menu-item-' . $value['key'] ][ $menu_item_db_id ] ) ) {
					$variable = $_REQUEST[ 'menu-item-' . $value['key'] ][ $menu_item_db_id ];
					/* Clean submenu options if menu-item-parent-id not 0 (if depth > 0) */
/*
since - v2.0.3
					if ( $_REQUEST[ 'menu-item-parent-id' ][ $menu_item_db_id ] != '0' && substr_count( $value['key'], 'submenu' ) ) {
						$variable = '';
					}
*/
					update_post_meta( $menu_item_db_id, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $value['key'], $variable );
				} else {
					update_post_meta( $menu_item_db_id, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $value['key'], '' );
				}
			}
		}
	}

	/** 
	 * Create menu item objekt and add extended meta options.
	 * @return $item
	 */
	if ( !function_exists( 'mmm_nav_item' ) ) {
		function mmm_nav_item( $item ) {
			global $mega_main_menu;
			$options_array = mmm_menu_options_array();

			foreach ( $options_array as $option ) {
				$post_meta = ( get_post_meta( $item->ID, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $option['key'], true ) !== false )
					? get_post_meta( $item->ID, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $option['key'], true )
					: get_post_meta( $item->ID, 'mmpm' . '_' . $option['key'], true ); // migrator
				if ( $post_meta != false && $post_meta != '' ) {
					$item->$option['key'] = $post_meta;
				}
			}
			return $item;
		}
	}
	/** 
	 * Return RS edit walker class name.
	 * @return string
	 */
	if ( !function_exists( 'mega_main_menu_backend_walker' ) ) {
		function mega_main_menu_backend_walker( $walker, $menu_id ) {
			return 'Mega_Main_Menu_Backend_Walker';
		}
	}

	/**
	 * Copied from Walker_Nav_Menu_Edit class in WordPress core.
	 * Create HTML list of nav menu input items.
	 *
	 * @package WordPress
	 * @since 3.0.0
	 * @uses Walker_Nav_Menu
	 */
	if ( !class_exists( 'Mega_Main_Menu_Backend_Walker' ) ) {
		class Mega_Main_Menu_Backend_Walker extends Walker_Nav_Menu {
			/**
			 * @see Walker_Nav_Menu::start_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 */
			function start_lvl( &$output, $depth = 0, $args = array() ) {}

			/**
			 * @see Walker_Nav_Menu::end_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 */
			function end_lvl( &$output, $depth = 0, $args = array() ) {}

			/**
			 * @see Walker::start_el()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param object $item Menu item data object.
			 * @param int $depth Depth of menu item. Used for padding.
			 * @param object $args
			 */
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				global $_wp_nav_menu_max_depth;
				$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				ob_start();
				$item_id = esc_attr( $item->ID );
				$removed_args = array(
					'action',
					'customlink-tab',
					'edit-menu-item',
					'menu-item',
					'page-tab',
					'_wpnonce',
				);

				$original_title = '';
				if ( 'taxonomy' == $item->type ) {
					$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
					if ( is_wp_error( $original_title ) )
						$original_title = false;
				} elseif ( 'post_type' == $item->type ) {
					$original_object = get_post( $item->object_id );
					$original_title = $original_object->post_title;
				}

				$classes = array(
					'menu-item menu-item-depth-' . $depth,
					'menu-item-' . esc_attr( $item->object ),
					'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
				);

				$title = $item->title;

				if ( ! empty( $item->_invalid ) ) {
					$classes[] = 'menu-item-invalid';
					/* translators: %s: title of menu item which is invalid */
					$title = sprintf( __( '%s (Invalid)' ), $item->title );
				} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
					$classes[] = 'pending';
					/* translators: %s: title of menu item in draft status */
					$title = sprintf( __('%s (Pending)'), $item->title );
				}

				$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

				$submenu_text = '';
				if ( 0 == $depth )
					$submenu_text = 'style="display: none;"';

				?>
			<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item' ); ?></span></span>
							<span class="item-controls">
								<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
								<span class="item-order hide-if-js">
									<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-up-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
									?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
									|
									<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-down-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
									?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
								</span>
								<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
								?>"><?php _e( 'Edit Menu Item' ); ?></a>
							</span>
					</dt>
				</dl>

				<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo $item_id; ?>">
								<?php _e( 'URL' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo $item_id; ?>">
							<?php _e( 'Navigation Label' ); ?><br />
							<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
<!--					<p class="description description-thin">-->
<!--						<label for="edit-menu-item-attr-title---><?php //echo $item_id; ?><!--">-->
<!--							--><?php //_e( 'Title Attribute' ); ?><!--<br />-->
<!--							<input type="text" id="edit-menu-item-attr-title---><?php //echo $item_id; ?><!--" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[--><?php //echo $item_id; ?><!--]" value="--><?php //echo esc_attr( $item->post_excerpt ); ?><!--" />-->
<!--						</label>-->
<!--					</p>-->
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo $item_id; ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php _e( 'Open link in a new window/tab' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
							<?php _e( 'CSS Classes (optional)' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
							<?php _e( 'Link Relationship (XFN)' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<?php
					/*
										<p class="field-description description description-wide">
											<label for="edit-menu-item-description-<?php echo $item_id; ?>">
												<?php _e( 'Description' ); ?><br />
												<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
												<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
											</label>
										</p>
					*/
					?>
					<?php
					/* START build extended menu options */
					$out = '';
					$out .= '<div class="clearboth"></div>';
					global $mega_main_menu;
					foreach ( mmm_menu_options_array() as $option ) {
						$option_status = $mega_main_menu->get_option( $option['key'], array() );
						if ( $option['key'] == 'submenu_type' && in_array( 'disable', $option_status ) ) {
							$submenu_type_status = 'disable';
						}
						if ( !in_array( 'disable', $option_status ) || ( !isset( $submenu_type_status ) && $option['key'] == 'submenu_post_type' ) ) {
							$mmm_saved_value = ( get_post_meta( $item->ID, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $option['key'], true ) != false )
								? get_post_meta( $item->ID, $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_' . $option['key'], true )
								: ''; //get_post_meta( $item->ID, 'mmpm' . '_' . $option['key'], true ); // migrator
							$option['key'] = 'menu-item-' . $option['key'] . '[' . $item->ID . ']';
							$out .= $mega_main_menu->options_generator( $option, $mmm_saved_value );
						}
					}
					echo $out;
					/* END build extended menu options */
					?>
					<p class="field-move hide-if-no-js description description-wide">
						<label>
							<span><?php _e( 'Move' ); ?></span>
							<a href="#" class="menus-move-up"><?php _e( 'Up one' ); ?></a>
							<a href="#" class="menus-move-down"><?php _e( 'Down one' ); ?></a>
							<a href="#" class="menus-move-left"></a>
							<a href="#" class="menus-move-right"></a>
							<a href="#" class="menus-move-top"><?php _e( 'To the top' ); ?></a>
						</label>
					</p>

					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
							<p class="link-to-original">
								<?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
						echo wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								admin_url( 'nav-menus.php' )
							),
							'delete-menu_item_' . $item_id
						); ?>"><?php _e( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
				<?php
				$output .= ob_get_clean();
			}
		}
	}

?>