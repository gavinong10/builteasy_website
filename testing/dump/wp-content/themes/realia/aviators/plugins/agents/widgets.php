<?php

class Agents_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'Agents_Widget',
			__( 'Aviators: Agents', 'aviators' ),
			array(
				'classname'   => 'our-agents',
				'description' => __( 'Agents', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Agents', 'aviators' );
		}

		if ( isset( $instance['count'] ) ) {
			$count = $instance['count'];
		}
		else {
			$count = 3;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo __( 'Count', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );

		echo View::render( 'agents/widget.twig', array(
			'title'         => apply_filters( 'widget_title', $instance['title'] ),
			'count'         => $instance['count'],
			'agents'        => aviators_agents_get( $instance['count'] ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}


class AssignedAgents_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'AssignedAgents_Widget',
			__( 'Aviators: Assigned Agents', 'aviators' ),
			array(
				'classname'   => 'our-agents',
				'description' => __( 'Displayable only on property detail page.', 'aviators' ),
			) );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Agents', 'aviators' );
		}

		if ( isset( $instance['count'] ) ) {
			$count = $instance['count'];
		}
		else {
			$count = 3;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo __( 'Count', 'aviators' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		global $post;
		extract( $args );

		if ( ! is_singular( 'property' ) ) {
			return;
		}

		echo View::render( 'agents/widget.twig', array(
			'title'         => apply_filters( 'widget_title', $instance['title'] ),
			'count'         => $instance['count'],
			'agents'        => aviators_agents_get_assigned( $post, $instance['count'] ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		) );
	}
}