<?php

class SliderProperties_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'SliderProperties_Widget',
			__( 'Aviators: Property Slider', 'aviators' ),
			array(
				'classname'   => 'property-slider',
				'description' => __( 'Property slider', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['properties'] ) ) {
			$properties = $instance['properties'];
		}

		if ( isset( $instance['show_filter'] ) ) {
			$show_filter = $instance['show_filter'];
		}
		else {
			$show_filter = TRUE;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'properties' ); ?>"><?php echo __( 'Properties - property IDs (eg. 11,25,36)', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'properties' ); ?>" name="<?php echo $this->get_field_name( 'properties' ); ?>" type="text" value="<?php echo esc_attr( $properties ); ?>" />
		</p>

	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                = array();
		$instance['properties']  = strip_tags( $new_instance['properties'] );
		$instance['show_filter'] = strip_tags( $new_instance['show_filter'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		global $post;
		extract( $args );

		$posts = array();
		$parts = explode( ',', $instance['properties'] );

		foreach ( $parts as $part ) {
			$posts[] = trim( $part );
		}

		$args = array(
			'post__in'       => $posts,
			'post_type'      => 'property',
			'posts_per_page' => - 1,
		);

		$price_from       = array();
		$price_from_parts = explode( "\n", aviators_settings_get_value( 'properties', 'filter', 'from' ) );
		foreach ( $price_from_parts as $price ) {
			$price_from[] = trim( $price );
		}

		$price_to       = array();
		$price_to_parts = explode( "\n", aviators_settings_get_value( 'properties', 'filter', 'to' ) );
		foreach ( $price_to_parts as $price ) {
			$price_to[] = trim( $price );
		}

		echo View::render( 'properties/slider-large.twig', array(
			'id'            => $this->id,
			'properties'    => _aviators_properties_prepare( new WP_Query( $args ) ),
			'price_to'      => $price_to,
			'price_from'    => $price_from,
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}