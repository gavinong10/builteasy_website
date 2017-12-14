<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	class Mega_Main_Menu_Frontend_Walker extends Walker_Nav_Menu {
		
		/**
		 * default_menu_item 
		 */
		function default_menu_item( &$output, $args, $item, $depth ) {
			global $mega_main_menu;
			$args = (object)$args;
			$item = (object)$item;
			$indent = str_repeat("\t", $depth);
			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$args->_submenu_type = ( substr_count( $args->_submenu_type,  $mega_main_menu->constant['MM_WARE_PREFIX'] . '_menu_widgets_area_' ) == 1 ) 
				? 'widgets_dropdown' 
				: $args->_submenu_type;
			$class_names .= ' ' . implode(' ', array( $args->_submenu_type, $args->_item_style, $args->_submenu_drops_side, /*$args->_submenu_disable_icons, */$args->_submenu_enable_full_width, 'columns' . $args->_submenu_columns ) );
			$_pull_to_other_side = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_pull_to_other_side', true );
			$class_names .= ( is_array( $_pull_to_other_side ) && in_array( 'true', $_pull_to_other_side ) ) ? ' pull_to_other_side' : '';
			$class_names = str_replace( ' dropdown ', ' ', $class_names );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			if ( get_post_meta( $args->menu_item_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) == 'multicolumn_dropdown' ) {
				$columns = get_post_meta( $args->menu_item_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) 
					? get_post_meta( $args->menu_item_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) 
					: 1;
				$item_width = ' style="width:' . ( 100 / $columns ) . '%;"'; 
			} else {
				$item_width = '';
			}

			$output .= mad_mm_common::ntab( $depth ) . '<li' . $id . $value . $class_names . $item_width .'>';


			$_disable_text = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_disable_text', true );
			$link_class = ( is_array( $_disable_text ) && in_array( 'true', $_disable_text ) ) ? ' menu_item_without_text' : '';

//            $link_before = '<span>' . $args->link_before;
//            $link_after = $args->link_after . '</span>';

			$item->icon = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_icon', true)
				? get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_icon', true)
				: '';

			$item->descr = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_descr', true );
//			$_disable_icon = ( empty( $item->icon ) ? true : false );
			$_disable_link = ( is_array( get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_disable_link', true ) ) && in_array( 'true', get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_disable_link', true ) ) ) ? true : false ;
			$link_class .= ( empty( $item->icon ) ) ? ' disable_icon' : ' with_icon';

			$item_icon = '<i class="' . $item->icon . '"></i> ';

			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
//            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ( !empty( $item->url ) && $_disable_link !== true ) ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			$attributes .= ! empty( $link_class ) ? ' class="item_link ' . $link_class . '"' : '';

			$item_output = '';
//            $item_output .= $args->before;
			$item_output .= mad_mm_common::ntab( $depth + 1 ) . '<' . ( $_disable_link !== true ? 'a' : 'span' ) . $attributes .' tabindex="0">';
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . $item_icon;
//            $item_output .= $link_before;
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '<span class="link_content">';
			$item_output .= mad_mm_common::ntab( $depth + 3 ) . '<span class="link_text">'. apply_filters( 'the_title', $item->title, $item->ID ) .'</span>';

			if ( !empty( $item->descr ) ) {
				$item_output .= mad_mm_common::ntab( $depth + 4 ) . '<span class="link_descr">';
				$item_output .= mad_mm_common::excerpt( $item->descr );
				$item_output .= mad_mm_common::ntab( $depth + 4 ) . '</span>';
			}

			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '</span>';
//            $item_output .= '<span class="link_text">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>';
//            $item_output .= $link_after;
			$item_output .= mad_mm_common::ntab( $depth + 1 ) . '</' . ( $_disable_link !== true ? 'a' : 'span' ) . '>';
//            $item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * grid_dropdown 
		 */
		function grid_dropdown( &$output, $args, $item, $depth ) {
			global $mega_main_menu;
			$args = (object)$args;
			$item = (object)$item;
//			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$class_names .= ' ' . implode(' ', array( $args->_submenu_type, $args->_submenu_drops_side, /*$args->_submenu_disable_icons, */'columns' . $args->_submenu_columns ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$columns = get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) 
				? get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) 
				: 2;
			$enable_full_width = get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true);
			$item->descr = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_descr', true );
			$_submenu_enable_full_width = is_array( get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) ) 
				? get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) 
				: array();
			$dropdown_width = ( in_array( 'true', $_submenu_enable_full_width ) ) 
				? 1140
				: 450;
			$item_width_height = 100 / $columns;
			$img_width_height = floor( 1140 / $columns ); 
			$details_height = floor( $dropdown_width / 3 );
			$item->icon = get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_icon', true)
				? get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_icon', true)
				: '';

			$output .= mad_mm_common::ntab( $depth ) . '<li' . $id . $value . $class_names .' style="width:' . $item_width_height . '%;">';

			if ( get_the_post_thumbnail( $item->object_id, 'thumbnail' ) != false ) {
				$item_icon = mad_mm_image_pro::processed_image( $img_args = array( 'post_id' => $item->object_id, 'width'=> $img_width_height, 'height' => $img_width_height, 'permalink' => get_permalink( $item->object_id ), 'icon' => $item->icon, 'cover' => 'icon' ) );
			} else {
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
//                $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ( !empty( $item->url ) && get_post_meta( $item->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_disable_link', true) != '1' ) ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ' class="item_link ' . ( !empty( $link_class ) ? $link_class : '' ) . ' witout_img"';

				$item_icon = mad_mm_common::ntab( $depth + 1 ) . '<a'. $attributes .'>';
				$item_icon .= mad_mm_common::ntab( $depth + 2 ) . '<i class="' . $item->icon . '"></i> ';
				$item_icon .= mad_mm_common::ntab( $depth + 2 ) . '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABdJREFUeNpi/P//PwM6YGLAAigUBAgwADZQAwcsn51XAAAAAElFTkSuQmCC" alt="placeholder"/>';
				$item_icon .= mad_mm_common::ntab( $depth + 1 ) . '</a>';
			}

			$item_output = '';
//			$item_output .= $args->before;
			$item_output .= $item_icon;
//			$item_output .= $args->after;
			$item_output .= mad_mm_common::ntab( $depth + 1 ) . '<div class="post_details">';
			if ( get_the_post_thumbnail( $item->object_id, 'thumbnail' ) != false ) {
				$item_output .= mad_mm_image_pro::processed_image( $img_args = array( 'post_id' => $item->object_id, 'width'=> $dropdown_width, 'height' => $details_height, 'permalink' => get_permalink( $item->object_id ), 'icon' => $item->icon, 'cover' => 'icon' ) );
			}
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '<div class="post_icon pull-left">';
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '<i class="' . $item->icon . '"></i>';
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '</div>';
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '<div class="post_title">';
//			$item_output .= mm_common::ntab( $depth + 3 ) . '<a rel="bookmark" href="' . esc_url( get_permalink($item->object_id) ) . '" title="' . esc_attr( apply_filters( 'the_title', $item->title, $item->object_id ) ) . '">' . apply_filters( 'the_title', $item->title, $item->object_id ) . '</a>';
			$item_output .= mad_mm_common::ntab( $depth + 3 ) . apply_filters( 'the_title', $item->title, $item->object_id );
			$item_output .= mad_mm_common::ntab( $depth + 2 ) . '</div>';
			if ( isset( $item->descr ) && $item->descr != '' ) {
				$item_output .= mad_mm_common::ntab( $depth + 2 ) . '<div class="post_description">';
				$item_output .= mad_mm_common::excerpt( $item->descr );
				$item_output .= mad_mm_common::ntab( $depth + 2 ) . '</div>';
			}
			$item_output .= mad_mm_common::ntab( $depth + 1 ) . '</div><!-- /.post_details -->';

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * post_type_dropdown 
		 */
		function post_type_dropdown( &$output, $args, $depth ) {
			global $mega_main_menu;
			$args = (array)$args;
			global $wpdb; //, $shortname 
			$showposts = get_post_meta( $args['menu_item_id'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) * 2;
			$post_type = get_post_meta( $args['menu_item_id'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_post_type', true);
			$query_args = array(
				'post_type' => $post_type,
				'showposts' => $showposts,
				'nopaging' => false,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
				'ignore_sticky_posts' => true,
				'suppress_filters' => false
			);
			if ( strripos( $post_type, '/' ) !== false ) {
				$post_type_taxonomy = explode( '/', $post_type );
				$query_args['post_type'] = $post_type_taxonomy[ 0 ];
				$taxonomy = explode( '=', $post_type_taxonomy[ 1 ] );
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy[ 0 ],
						'field' => 'slug',
						'terms' => $taxonomy[ 1 ],
					)
				);
			} else {
				$query_args['post_type'] = $post_type;
			}

			$recent_query = get_posts( $query_args );

			if ( count( $recent_query ) ) {
				$columns = get_post_meta( $args['menu_item_id'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) ? get_post_meta( $args['menu_item_id'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) : 2;
				$enable_full_width = get_post_meta( $args['menu_main_parent'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true);
				$_submenu_enable_full_width = is_array( get_post_meta( $args['menu_main_parent'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) ) 
					? get_post_meta( $args['menu_main_parent'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) 
					: array();
				$dropdown_width = ( in_array( 'true', $_submenu_enable_full_width ) ) 
					? 1140 
					: 450;
				$item_width_height = 100 / $columns;
				$img_width_height = floor( 1140 / $columns ); 
				$details_height = floor( $dropdown_width / 3 );

				foreach ( $recent_query as $key => $post_object ) {
					$post_icon = get_post_meta( $post_object->ID, 'mm_post_icon', true)
						? get_post_meta( $post_object->ID, 'mm_post_icon', true)
						: 'im-icon-plus-circle';

					$output .= mad_mm_common::ntab( $depth + 1 ) . '<li class="post_item" style="width:' . $item_width_height . '%;">';
					if ( wp_get_attachment_image_src( get_post_thumbnail_id( $post_object->ID ), 'full' ) ) {
						$output .= mad_mm_image_pro::processed_image( $img_args = array( 'post_id' => $post_object->ID, 'width'=> $img_width_height, 'height' => $img_width_height, 'permalink' => get_permalink( $post_object->ID ), 'icon' => $post_icon, 'cover' => 'icon' ) );
					} else {
						$output .= mad_mm_common::ntab( $depth + 2 ) . '<a class="item_link" href="' . get_permalink( $post_object->ID ) . '" title="' . apply_filters( 'the_title', $post_object->title, $post_object->ID ) . '">';
						$output .= mad_mm_common::ntab( $depth + 3 ) . '<i class="' . $post_icon . '"></i>';
						$output .= mad_mm_common::ntab( $depth + 3 ) . '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABdJREFUeNpi/P//PwM6YGLAAigUBAgwADZQAwcsn51XAAAAAElFTkSuQmCC" alt="placeholder"/>';
						$output .= mad_mm_common::ntab( $depth + 2 ) . '</a>';
					}
					$output .= mad_mm_common::ntab( $depth + 2 ) . '<div class="post_details">';
					if ( wp_get_attachment_image_src( get_post_thumbnail_id( $post_object->ID ), 'full' ) ) {
						$output .= mad_mm_image_pro::processed_image( $img_args = array( 'post_id' => $post_object->ID, 'width'=> $dropdown_width, 'height' => $details_height, 'permalink' => get_permalink( $post_object->ID ), 'icon' => $post_icon, 'cover' => false ) );
					}
					$output .= mad_mm_common::ntab( $depth + 3 ) . '<div class="post_icon">';
					$output .= mad_mm_common::ntab( $depth + 4 ) . '<i class="' .$post_icon . '"></i>';
					$output .= mad_mm_common::ntab( $depth + 3 ) . '</div>';
					$output .= mad_mm_common::ntab( $depth + 3 ) . '<div class="post_title">';
					$output .= mad_mm_common::ntab( $depth + 4 ) . apply_filters( 'the_title', $post_object->post_title, $post_object->ID );
					$output .= mad_mm_common::ntab( $depth + 3 ) . '</div>';
					$output .= mad_mm_common::ntab( $depth + 3 ) . '<div class="post_description">';
					$output .= mad_mm_common::ntab( $depth + 4 ) . mad_mm_common::excerpt( $post_object->post_content );
					$output .= mad_mm_common::ntab( $depth + 3 ) . '</div>';
					$output .= mad_mm_common::ntab( $depth + 2 ) . '</div><!-- /.post_details -->';
					$output .= mad_mm_common::ntab( $depth + 1 ) . '</li><!-- /.post_item -->';
				} 
			}
//            $output .= '<span class="clearboth"></span><!-- /.clearboth -->';
		}

		/**
		 * custom_dropdown 
		 */
/* for better times
		function custom_dropdown( &$output, $args ) {
				$output .= '<div class="submenu_custom_content">' . do_shortcode( get_post_meta( $args['menu_main_parent'], $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_custom_content', true) ) . '</div><!-- /.submenu_custom_content -->';
		}
*/
		/**
		 * widgets_dropdown 
		 */
		function widgets_dropdown( &$output, $args ) {
			ob_start();
				dynamic_sidebar( $args['widgets_area_number'] );
				$output .= ob_get_contents();
			ob_end_clean();
		}

		function start_lvl( &$output, $depth = 0, $args = array() ) {
			global $mega_main_menu;
			$args = (object)$args;
			$img = ( get_post_meta( $args->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_bg_image', true) ) 
				? get_post_meta( $args->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_bg_image', true) 
				: 'no-img';
			$style = ( is_array( $img ) && $img['background_image'] != '') ? ' style="background-image:url(' . $img['background_image'] . ');background-repeat:' . $img['background_repeat'] . ';background-attachment:' . $img['background_attachment'] . ';background-position:' . $img['background_position'] . ';background-size:' . $img['background_size'] . ';"': '';
			$output .= mad_mm_common::ntab( $depth + 1 ) . '<ul class="mega_dropdown"' . $style . '>';
		}

		function end_lvl( &$output, $depth = 0, $args = array() ) {
			global $mega_main_menu;
			$args = (object)$args;
			$indent = str_repeat( "\t", $depth );
				$mmm_submenu_type = ( get_post_meta( $args->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) ) ? get_post_meta( $args->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) : 'default_dropdown';
				if ( $mmm_submenu_type == 'post_type_dropdown' ) {
					$args_submenu_type = array( 'menu_item_id' => $args->menu_item_id, 'menu_main_parent' => $args->menu_main_parent );
					call_user_func_array ( array( $this, 'post_type_dropdown' ), array( &$output, $args_submenu_type, $depth ) );
				}
				if ( strpos( $mmm_submenu_type,  $mega_main_menu->constant['MM_WARE_PREFIX'] . '_menu_widgets_area_' ) == 0 /* && $depth == 0 */ ) {
					$args_submenu_type = array( 
						'menu_item_id' => $args->menu_item_id, 
						'menu_main_parent' => $args->menu_main_parent,
						'widgets_area_number' => $mmm_submenu_type,
					);
					call_user_func_array ( array( $this, 'widgets_dropdown' ), array( &$output, $args_submenu_type ) );
				}
/* for better times
				if ( $mmm_submenu_type != 'default_dropdown' && $mmm_submenu_type != 'multicolumn_dropdown' ) {
					$output .= '<div class="submenu_custom_content">' . do_shortcode( get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_custom_content', true) ) . '</div><!-- /.submenu_custom_content -->';
				} elseif ( $mmm_submenu_type == 'multicolumn_dropdown' && $args->menu_main_parent == $args->menu_item_parent && $depth == 0 ) {
					$output .= '<div class="submenu_custom_content">' . do_shortcode( get_post_meta( $args->menu_main_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_custom_content', true) ) . '</div><!-- /.submenu_custom_content -->';
				}
*/
			$output .= mad_mm_common::ntab( $depth + 1 ) . '</ul><!-- /.mega_dropdown -->';
		}

		function start_el( &$output, $item, $depth = 0, $args = '', $id = 0 ) {
			global $mega_main_menu;
			$args = (object)$args;
			$item = (object)$item;
			if ( get_post_meta( $item->menu_item_parent, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) == 'grid_dropdown' ) {
				call_user_func_array ( array( $this, 'grid_dropdown' ), array( &$output, $args, $item, $depth ) );
			} else {
				call_user_func_array ( array( $this, 'default_menu_item' ), array( &$output, $args, $item, $depth ) );
			}
		}

		function end_el( &$output, $item, $depth = 0, $args = '', $id = 0 ) {
			$output .= mad_mm_common::ntab( $depth ) . '</li>';
		}

		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
			global $mega_main_menu;
			$args[0] = (object)$args[0];
			$element = (object)$element;

			if ( !$element and !isset( $args[0]->menu_main_parent ) )
				return;

			$id_field = $this->db_fields['id'];

			//display this element
			if ( is_array( $args[0] ) ) {
				$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
			}

			$args[0]->menu_item_id = $element->ID;
			$args[0]->menu_item_parent = $element->menu_item_parent;
			if ( $element->menu_item_parent == 0 ) {
				$args[0]->menu_main_parent = $element->ID;
			}

/*
				$args[0]->_submenu_drops_side = ( $args[0]->_submenu_type == 'default_dropdown' && $args[0]->_submenu_drops_side == 'drop_to_center' )
					? 'drop_to_right'
					: $args[0]->_submenu_drops_side;
				$_submenu_disable_icons = is_array( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_disable_icons', true ) ) 
					? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_disable_icons', true ) 
					: array();
				$args[0]->_submenu_disable_icons = ( in_array( 'true', $_submenu_disable_icons ) ) 
					? 'submenu_disable_icons' 
					: '';
*/
			$args[0]->_submenu_type = ( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) 
				: 'default_dropdown';
			$args[0]->_submenu_drops_side = ( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_drops_side', true) ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_drops_side', true) 
				: 'drop_to_right';
			$_submenu_enable_full_width = is_array( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_enable_full_width', true ) 
				: array();
			$args[0]->_submenu_enable_full_width = ( in_array( 'true', $_submenu_enable_full_width ) ) 
				? 'submenu_full_width' 
				: 'submenu_default_width';
			$args[0]->_submenu_columns = ( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true) ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_columns', true)
				: '1';

			$_item_visibility = get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_visibility', true ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_visibility', true ) 
				: 'all';
			switch ( $_item_visibility ) {
				case 'logged':
					$visibility_control = is_user_logged_in();
					break;
				case 'visitors':
					$visibility_control = !is_user_logged_in();
					break;
				default:
					$visibility_control = true;
					break;
			}

			$args[0]->_item_style = ( get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_style', true ) != false ) 
				? get_post_meta( $element->ID, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_item_style', true ) 
				: '';
			$mmm_submenu_type = ( get_post_meta( $args[0]->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) ) 
				? get_post_meta( $args[0]->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_type', true) 
				: 'default_dropdown';

			if ( ( $visibility_control === true ) ) {
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array($this, 'start_el'), $cb_args);

				$id = $element->$id_field;

				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) && ( $mmm_submenu_type != 'post_type_dropdown' ) ) {
					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array($this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
					unset( $children_elements[ $id ] );
				} elseif ( substr_count( $mmm_submenu_type,  $mega_main_menu->constant['MM_WARE_PREFIX'] . '_menu_widgets_area_' ) == 1 || $mmm_submenu_type == 'post_type_dropdown' /* || $mmm_submenu_type == 'custom_dropdown' || get_post_meta( $args[0]->menu_item_id, $mega_main_menu->constant['MM_WARE_PREFIX'] . '_submenu_custom_content', true) != '' */ ) {
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array($this, 'start_lvl'), $cb_args);
					call_user_func_array(array($this, 'end_lvl'), $cb_args);
				}

				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array($this, 'end_lvl'), $cb_args);
				}
			} 

			//end this element
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array($this, 'end_el'), $cb_args);
		}
	}
?>
