<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

//	add_action( 'init', 'mmm_register_sidebars', 20 );
//	if ( !function_exists( 'mmm_register_sidebars' ) ) {
//		function mmm_register_sidebars () {
//			global $mega_main_menu;
//			$number_of_widgets = $mega_main_menu->get_option( 'number_of_widgets', '1' );
//			if ( is_numeric( $number_of_widgets ) && $number_of_widgets > 0 ) {
//				for ( $i=1; $i <= $number_of_widgets; $i++ ) {
//					register_sidebar(
//						array(
//							'name' => __( $mega_main_menu->constant[ 'MM_WARE_NAME' ] . ' dropdown ' . $i, $mega_main_menu->constant[ 'MM_TEXTDOMAIN_ADMIN' ]  ),
//							'id'=> $mega_main_menu->constant[ 'MM_WARE_PREFIX' ] . '_menu_widgets_area_' . $i,
//							'before_widget' => '<div id="%1$s" class="widget %2$s">',
//							'after_widget' => '</div>',
//							'before_title' => '<div class="widgettitle">',
//							'after_title' => '</div>',
//						)
//					);
//				}
//			}
//		}
//	}


	/**
	 * Menu widget
	 *
	 * @since 1.0.8
	 */
//	 class mega_main_sidebar_menu extends WP_Widget {
//
//		function __construct() {
//			$widget_ops = array( 'description' => __('Add a "Mega Main Menu" to your sidebar.') );
//			parent::__construct( 'mega_main_sidebar_menu', __('Mega Main Sidebar Menu'), $widget_ops );
//		}
//
//		function widget($args, $instance) {
//			// Get menu
//			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
//			if ( !$nav_menu ) {
//				return;
//			}
//			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
//			echo $args['before_widget'];
//			if ( !empty($instance['title']) ) {
//				echo $args['before_title'] . $instance['title'] . $args['after_title'];
//			}
//			wp_nav_menu( array( 'theme_location' => 'mega_main_sidebar_menu', 'menu' => $nav_menu ) );
//			echo $args['after_widget'];
//		}
//
//		function update( $new_instance, $old_instance ) {
//			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
//			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
//			return $instance;
//		}
//
//		function form( $instance ) {
//			$title = isset( $instance['title'] ) ? $instance['title'] : '';
//			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
//
//			// Get menus
//			$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
//
//			// If no menus exists, direct the user to go and create some.
//			if ( !$menus ) {
//				echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
//				return;
//			}
//			?>
<!--			<p>-->
<!--				<label for="--><?php //echo $this->get_field_id('title'); ?><!--">--><?php //_e('Title:') ?><!--</label>-->
<!--				<input type="text" class="widefat" id="--><?php //echo $this->get_field_id('title'); ?><!--" name="--><?php //echo $this->get_field_name('title'); ?><!--" value="--><?php //echo $title; ?><!--" />-->
<!--			</p>-->
<!--			<p>-->
<!--				<label for="--><?php //echo $this->get_field_id('nav_menu'); ?><!--">--><?php //_e('Select Menu:'); ?><!--</label>-->
<!--				<select id="--><?php //echo $this->get_field_id('nav_menu'); ?><!--" name="--><?php //echo $this->get_field_name('nav_menu'); ?><!--">-->
<!--			--><?php
//				foreach ( $menus as $menu ) {
//					echo '<option value="' . $menu->term_id . '"'
//						. selected( $nav_menu, $menu->term_id, false )
//						. '>'. $menu->name . '</option>';
//				}
//			?>
<!--				</select>-->
<!--			</p>-->
<!--			--><?php
//		}
//	}

//	add_action('widgets_init', 'mmm_mega_main_menu_widget' );
//	if ( !function_exists( 'mmm_mega_main_menu_widget' ) ) {
//		function mmm_mega_main_menu_widget () {
//			register_nav_menu( 'mega_main_sidebar_menu', 'Sidebar Menu by Mega Main' );
//			register_widget("mega_main_sidebar_menu");
//		}
//	}
?>