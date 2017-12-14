<?php

class CallToAction_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'CallToAction_Widget',
			__( 'Aviators: Call to Action', 'aviators' ),
			array(
				'classname'   => 'call-to-action',
				'description' => __( 'Call to Action', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Call to action', 'aviators' );
		}

		if ( isset( $instance['link'] ) ) {
			$link = $instance['link'];
		}

		if ( isset( $instance['text'] ) ) {
			$text = $instance['text'];
		}

		if ( isset( $instance['class'] ) ) {
			$class = $instance['class'];
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php echo __( 'Text', 'aviators' ); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_attr( $text ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php echo __( 'Link', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php echo __( 'Class', 'aviators' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'class' ); ?>" id="<?php echo $this->get_field_id( 'class' ); ?>" class="widefat">
				<option value="address" <?php if (esc_attr( $class ) == 'address'): ?>selected<?php endif; ?>><?php echo __( 'Address', 'aviators' ); ?></option>
				<option value="gps" <?php if (esc_attr( $class ) == 'gps'): ?>selected<?php endif; ?>><?php echo __( 'GPS', 'aviators' ); ?></option>
				<option value="key" <?php if (esc_attr( $class ) == 'key'): ?>selected<?php endif; ?>><?php echo __( 'Key', 'aviators' ); ?></option>
			</select>
		</p>

	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text']  = strip_tags( $new_instance['text'] );
		$instance['link']  = strip_tags( $new_instance['link'] );
		$instance['class'] = strip_tags( $new_instance['class'] );

		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );

		echo View::render( 'call-to-action/widget.twig', array(
			'title'         => apply_filters( 'widget_title', $instance['title'] ),
			'text'          => $instance['text'],
			'link'          => $instance['link'],
			'class'         => $instance['class'],
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}