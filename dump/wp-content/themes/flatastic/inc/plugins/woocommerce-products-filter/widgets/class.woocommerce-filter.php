<?php

if (!class_exists('MAD_WOOF_Widget')) {

	class MAD_WOOF_Widget extends WP_Widget {

		function __construct() {
			$settings  = array( 'classname' => 'widget-woof-filter woocommerce', 'description' => __( 'WooCommerce Products Filter', MAD_BASE_TEXTDOMAIN ) );
			parent::__construct( 'widget-woof-filter', strtoupper(MAD_BASE_TEXTDOMAIN).' ' .__('WooCommerce Products Filter', MAD_BASE_TEXTDOMAIN), $settings );
		}

		function widget($args, $instance) {

			global $_attributes_array;

			extract($args);

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( $_attributes_array, array( 'product_cat', 'product_tag' ) ) ) ) {
				return;
			}

			$title = apply_filters( 'widget_title', ( isset( $instance['title'] ) ? $instance['title'] : ''), $instance, $this->id_base );

			$args['instance'] = $instance;
			$args['sidebar_id'] = $args['id'];
			$args['sidebar_name'] = $args['name'];

			ob_start();
			echo $before_widget . $before_title . $title . $after_title;
			echo do_shortcode('[woof]');
			echo $after_widget;
			echo ob_get_clean();
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title' => __('Products Filter', MAD_BASE_TEXTDOMAIN)
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', MAD_BASE_TEXTDOMAIN) ?>:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
		<?php
		}

	}

}
