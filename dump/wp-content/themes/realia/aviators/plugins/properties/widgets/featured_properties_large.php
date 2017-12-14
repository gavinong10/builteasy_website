<?php

class FeaturedPropertiesLarge_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'FeaturedPropertiesLarge_Widget',
			__( 'Aviators: Featured Properties Large', 'aviators' ),
			array(
				'classname'   => 'properties-large',
				'description' => __( 'Featured Properties Large', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Featured Properties', 'aviators' );
		}

		if ( isset( $instance['count'] ) ) {
			$count = $instance['count'];
		}
		else {
			$count = 3;
		}

		if ( isset( $instance['shuffle'] ) ) {
			$shuffle = $instance['shuffle'];
		}
		else {
			$shuffle = FALSE;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>


		<p>
			<label for="<?php echo $this->get_field_id( 'shuffle' ); ?>"><?php echo __( 'Shuffle', 'aviators' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'shuffle' ); ?>" name="<?php echo $this->get_field_name( 'shuffle' ); ?>" value="1" <?php checked( $shuffle ); ?>>
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['shuffle'] = strip_tags( $new_instance['shuffle'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$do_shuffle = FALSE;
		if ( ! empty( $instance['shuffle'] ) && $instance['shuffle'] ) {
			$do_shuffle = TRUE;
		}

		echo View::render( 'properties/widget-large.twig', array(
			'title'         => apply_filters( 'widget_title', $instance['title'] ),
			'properties'    => aviators_properties_get_featured( 3, $do_shuffle ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}