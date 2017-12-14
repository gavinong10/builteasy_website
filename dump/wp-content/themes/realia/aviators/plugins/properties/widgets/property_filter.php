<?php

class PropertyFilter_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'PropertyFilter_Widget',
			__( 'Aviators:Property Filter', 'aviators' ),
			array(
				'classname'   => 'enquire',
				'description' => __( 'Property Filter', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Property Filter', 'aviators' );
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		global $post;
		extract( $args );


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

		echo View::render( 'properties/filter.twig', array(
			'id'            => $this->id,
			'title'         => apply_filters( 'widget_title', $instance['title'] ),
			'price_from'    => $price_from,
			'price_to'      => $price_to,
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}