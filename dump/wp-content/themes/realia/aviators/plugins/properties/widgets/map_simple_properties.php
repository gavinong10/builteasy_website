<?php

class MapSimpleProperties_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'MapSimpleProperties_Widget',
			__( 'Aviators: Map Properties', 'aviators' ),
			array(
				'classname'   => 'properties',
				'description' => __( 'Map Properties', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['latitude'] ) ) {
			$latitude = $instance['latitude'];
		}
		else {
			$latitude = '34.019000';
		}

		if ( isset( $instance['longitude'] ) ) {
			$longitude = $instance['longitude'];
		}
		else {
			$longitude = '-118.455458';
		}

		if ( isset( $instance['zoom'] ) ) {
			$zoom = $instance['zoom'];
		}
		else {
			$zoom = '14';
		}

		if ( isset( $instance['height'] ) ) {
			$height = $instance['height'];
		}
		else {
			$height = '485px';
		}

		if ( isset( $instance['enable_geolocation'] ) ) {
			$enable_geolocation = $instance['enable_geolocation'];
		}
		else {
			$enable_geolocation = FALSE;
		}

		if ( isset( $instance['show_filter'] ) ) {
			$show_filter = $instance['show_filter'];
		}
		else {
			$show_filter = TRUE;
		}

		if ( isset( $instance['horizontal_filter'] ) ) {
			$horizontal_filter = $instance['horizontal_filter'];
		}
		else {
			$horizontal_filter = FALSE;
		}

		if ( isset( $instance['map_filtering'] ) ) {
			$map_filtering = $instance['map_filtering'];
		}
		else {
			$map_filtering = FALSE;
		}

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'latitude' ); ?>"><?php echo __( 'Latitude', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'latitude' ); ?>" name="<?php echo $this->get_field_name( 'latitude' ); ?>" type="text" value="<?php echo esc_attr( $latitude ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'longitude' ); ?>"><?php echo __( 'Longitude', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'longitude' ); ?>" name="<?php echo $this->get_field_name( 'longitude' ); ?>" type="text" value="<?php echo esc_attr( $longitude ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'zoom' ); ?>"><?php echo __( 'Zoom', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'zoom' ); ?>" name="<?php echo $this->get_field_name( 'zoom' ); ?>" type="text" value="<?php echo esc_attr( $zoom ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php echo __( 'Height', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_filter' ); ?>"><?php echo __( 'Show filter', 'aviators' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_filter' ); ?>" name="<?php echo $this->get_field_name( 'show_filter' ); ?>" value="1" <?php checked( $show_filter ); ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'enable_geolocation' ); ?>"><?php echo __( 'Enable geolocation', 'aviators' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_geolocation' ); ?>" name="<?php echo $this->get_field_name( 'enable_geolocation' ); ?>" value="1" <?php checked( $enable_geolocation ); ?>>
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                       = array();
		$instance['title']              = strip_tags( $new_instance['title'] );
		$instance['latitude']           = strip_tags( $new_instance['latitude'] );
		$instance['longitude']          = strip_tags( $new_instance['longitude'] );
		$instance['zoom']               = strip_tags( $new_instance['zoom'] );
		$instance['height']             = strip_tags( $new_instance['height'] );
		$instance['show_filter']        = strip_tags( $new_instance['show_filter'] );
		$instance['enable_geolocation'] = strip_tags( $new_instance['enable_geolocation'] );
		$instance['horizontal_filter']  = strip_tags( $new_instance['horizontal_filter'] );
		$instance['map_filtering']      = strip_tags( $new_instance['map_filtering'] );

		return $instance;
	}

	public function widget( $args, $instance ) {
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

		echo View::render( 'properties/map.twig', array(
			'latitude'           => $instance['latitude'],
			'longitude'          => $instance['longitude'],
			'zoom'               => $instance['zoom'],
			'height'             => $instance['height'],
			'properties'         => aviators_properties_get_for_map(),
			'price_from'         => $price_from,
			'price_to'           => $price_to,
			'show_filter'        => $instance['show_filter'],
			'horizontal_filter'  => !empty($instance['horizontal_filter']) ? TRUE : FALSE,
			'map_filtering'      => ! empty( $instance['map_filtering'] ) ? TRUE : FALSE,
			'enable_geolocation' => ! empty( $instance['enable_geolocation'] ) ? TRUE : FALSE,
			'before_widget'      => $before_widget,
			'after_widget'       => $after_widget,
			'before_title'       => $before_title,
			'after_title'        => $after_title,
		) );
	}
}