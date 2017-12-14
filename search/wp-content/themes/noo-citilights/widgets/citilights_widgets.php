<?php
/**
 * This file create the widgets used in this theme.
 *
 *
 * @package    NOO CitiLights
 * @subpackage CitiLights Widgets
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

class NooPropertyFeaturedWidget extends WP_Widget{
	public function __construct() {
		parent::__construct (
					'noo_property_featured', 							// Base ID
					__('Recent Properties','noo'), 		    // Name
					array ('description' => __ ( 'Display Widget Recent Properties', 'noo' ) )
					);
	}
	public function widget($args, $instance) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$number = empty($instance['number']) ? '5' : $instance['number'];
		$show =  empty($instance['show']) ? 'all' : $instance['show'];
		$category = empty($instance['category']) ? '': (array) explode(',',$instance['category']);
		$status = empty($instance['status']) ? '': (array) explode(',',$instance['status']);
		$label = empty($instance['label']) ? '': (array) explode(',',$instance['label']);
		$location = empty($instance['location']) ? '': (array) explode(',',$instance['location']);
		$sub_location = empty($instance['sub_location']) ? '': (array) explode(',',$instance['sub_location']);
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$query_args = array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post_type'			  =>'noo_property',
		);
		$query_args['tax_query'] = array('relation' => 'AND');
		if(!empty($category)){
			$query_args['tax_query'][] = array(
				'taxonomy'     => 'property_category',
				'field'        => 'slug',
				'terms'        => $category
			);
		}
		if( !empty($status)){
			$query_args['tax_query'][] = array(
				'taxonomy'     => 'property_status',
				'field'        => 'slug',
				'terms'        => $status
			);
		}
		if( !empty($location)){
			$query_args['tax_query'][] = array(
				'taxonomy'     => 'property_location',
				'field'        => 'slug',
				'terms'        => $location
			);
		}
		if( !empty($sub_location)){
			$query_args['tax_query'][] = array(
				'taxonomy'     => 'property_sub_location',
				'field'        => 'slug',
				'terms'        => $sub_location
			);
		}
		if(!empty($label)){
			$query_args['meta_query'][] = array(
				'key'   => '_label',
				'value' => $label,
				'compare'=>'IN'
			);
		}
		if($show == 'featured'){
			$query_args['meta_query'][] = array(
				'key'   => '_featured',
				'value' => 'yes'
				);
		}
		$r = new WP_Query($query_args);
		if($r->have_posts()):
			?>
		<div class="property-featured">
			<ul>
				<?php while ($r->have_posts()): $r->the_post(); ?>
					<li>
						<?php if(has_post_thumbnail()):?>
							<div class="featured-image">
								<a href="<?php echo the_permalink()?>"><?php the_post_thumbnail('property-infobox')?></a>
							</div>
						<?php endif;?>
						<span class="featured-status">
							<?php 
							$terms = get_the_terms( get_the_ID(), 'property_status' );
							if($terms && !is_wp_error($terms)){
								foreach ( $terms as $term ) {
									$link = get_term_link( $term, 'property_status' );
									if ( is_wp_error( $link ) )
										continue;
									echo '<a href="' . esc_url( $link ) . '">' .sprintf(__('For %s','noo'), $term->name ). '</a>';
									break;
								}
							}
							?>
						</span>
						<?php //get_the_term_list( $this->id, 'product_cat') ?>
						<h4><a href="<?php the_permalink()?>"><?php the_title() ?></a></h4>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php
		endif;
		wp_reset_query();
		wp_reset_postdata();
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'number'=>'5',
			'show'=>'',
			'category'=>'',
			'status'=>'',
			'label'=>'',
			'location'=>'',
			'sub_location'=>'',
		));
		
		$title = $instance['title'];
		$number = $instance['number'];
		$show = $instance['show'];
		$category = (array) explode(',',$instance['category']);
		$status = (array) explode(',',$instance['status']);
		$label = (array) explode(',',$instance['label']);
		$location = (array) explode(',',$instance['location']);
		$sub_location = (array) explode(',',$instance['sub_location']);
		
		//type
		$property_categories = array();
		foreach ((array) get_terms('property_category',array('hide_empty'=>0)) as $cat){
			$property_categories[ $cat->slug ] = $cat->name;
		}
		//status
		$property_statuses = array();
		foreach ((array) get_terms('property_status',array('hide_empty'=>0)) as $stat){
			$property_statuses[$stat->slug] = $stat->name;
		}
		//label
		$property_labels = array();
		foreach ((array) get_terms('property_label',array('hide_empty'=>0)) as $lab){
			$property_labels[$lab->term_id] = $lab->name;
		}
		//location
		$property_locations = array();
		foreach ((array) get_terms('property_location',array('hide_empty'=>0)) as $loc){
			$property_locations[$loc->slug] = $loc->name;
		}
		//sub-location
		$property_sub_locations = array();
		foreach ((array) get_terms('property_sub_location',array('hide_empty'=>0)) as $sub_loc){
			$property_sub_locations[$sub_loc->slug] = $sub_loc->name;
		}
		
		
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','noo'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','noo'); ?> <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>
		<p>
			<label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('Show:','noo'); ?> 
				<select id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>">
					<option value="all" <?php selected($show,'all')?>><?php _e('All','noo')?></option>
					<option value="featured" <?php selected($show,'featured')?>><?php _e('Featured','noo')?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Type (do not select to show all)','noo'); ?></label><br/>
			<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>[]" multiple="multiple" style="width: 99%">
				<?php foreach ($property_categories as $slug=>$property_category):?>
					<option value="<?php echo $slug ?>" <?php if (in_array($slug, $category)):?> selected<?php endif;?>><?php echo $property_category ?></option>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('status'); ?>"><?php _e('Status (do not select to show all)','noo'); ?></label><br/>
			<select id="<?php echo $this->get_field_id('status'); ?>" name="<?php echo $this->get_field_name('status'); ?>[]" multiple="multiple" style="width: 99%">
				<?php foreach ($property_statuses as $slug=>$property_status):?>
					<option value="<?php echo $slug ?>" <?php if (in_array($slug, $status)):?> selected<?php endif;?>><?php echo $property_status ?></option>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Label (do not select to show all)','noo'); ?></label><br/>
			<select id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>[]" multiple="multiple" style="width: 99%">
				<?php foreach ($property_labels as $id=>$property_label):?>
					<option value="<?php echo $id ?>" <?php if (in_array($id, $label)):?> selected<?php endif;?>><?php echo $property_label ?></option>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('location'); ?>"><?php _e('Location (do not select to show all)','noo'); ?></label><br/>
			<select id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>[]" multiple="multiple" style="width: 99%">
				<?php foreach ($property_locations as $slug=>$property_location):?>
					<option value="<?php echo $slug ?>" <?php if (in_array($slug, $location)):?> selected<?php endif;?>><?php echo $property_location ?></option>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sub_location'); ?>"><?php _e('Sub-Location (do not select to show all)','noo'); ?></label><br/>
			<select id="<?php echo $this->get_field_id('sub_location'); ?>" name="<?php echo $this->get_field_name('sub_location'); ?>[]" multiple="multiple" style="width: 99%">
				<?php foreach ($property_sub_locations as $slug=>$property_sub_location):?>
					<option value="<?php echo $slug ?>" <?php if (in_array($slug, $sub_location)):?> selected<?php endif;?>><?php echo $property_sub_location ?></option>
				<?php endforeach;?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array(
			'title' => '',
			'category'=>'',
			'status'=>'',
			'label'=>'',
			'location'=>'',
			'sub_location'=>'',
			'number'=>'',
			'show'=>'',
			'orderby'=>'date',
			'order'=>'DESC',
			
		));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['show'] = $new_instance['show'];
		$instance[ 'category' ] = implode ( ',', $new_instance ['category'] );
		$instance[ 'status' ] = implode ( ',', $new_instance ['status'] );
		$instance[ 'label' ] = implode ( ',', $new_instance ['label'] );
		$instance[ 'location' ] = implode ( ',', $new_instance ['location'] );
		$instance[ 'sub_location' ] = implode ( ',', $new_instance ['sub_location'] );
		return $instance;
	}
}
register_widget( 'NooPropertyFeaturedWidget' );

class NooPropertyAdvancedSearchWidget extends WP_Widget {
	public function __construct() {
		parent::__construct (
					'noo_property_advanced_search', 		// Base ID
					__('Advanced Search Property','noo'), 		// Name
					array ('description' => __ ( 'Display Advanced Search Property', 'noo' ) )
					);
	}
	public function widget($args, $instance) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		NooProperty::enqueue_gmap_js();
		// Use current theme search form if it exists
		// NooProperty::advanced_map(false,__('Search', 'noo'),false,'search-vertical');
		$args = array(
			'gmap' => false,
			'btn_label' => __('Search', 'noo'),
			'show_status' => false,
			'map_class' => 'search-vertical'
		);
		NooProperty::advanced_map($args);

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','noo'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
}
register_widget( 'NooPropertyAdvancedSearchWidget' );

class NooSinglePropertyWidget extends  WP_Widget {
	public function __construct() {
		parent::__construct (
			'noo_single_property_widget', 		// Base ID
			__('Single Property','noo'), 		// Name
			array ('description' => __ ( 'Display Single Property', 'noo' ) )
		);
	}
	public function widget($args, $instance) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$property_id = $instance['property_id'];
		$display_type = $instance['display_type'];
		$property = get_post($property_id);
		if($property):
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
			?>
			<div class="properties <?php echo $display_type?>">
				<div class="properties-content">
					
				</div>
			</div>
			<?php
			
			echo $args['after_widget'];
		endif;
	}
	
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, 
			array( 
				'title' => '',
				'property_id'=>0,
				'display_type'=>'grid'
		) );
		$title = $instance['title'];
		$property_id = $instance['property_id'];
		$display_type = $instance['display_type'];
		$properties =  get_posts(array(
			'post_type'=>'noo_property',
			'posts_per_page'=>-1,
			'display_type'=>'grid'
		));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','noo'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('property_id'); ?>"><?php _e('Property:','noo'); ?>
				<select id="<?php echo $this->get_field_id('property_id'); ?>" name="<?php echo $this->get_field_name('property_id'); ?>">
					<?php foreach ((array)$properties as $property):?>
					<option value="<?php echo esc_attr($property->ID)?>" <?php selected($property_id,$property->ID)?>><?php echo $property->post_title?></option>
					<?php endforeach;?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('display_type'); ?>"><?php _e('Display Type:','noo'); ?>
				<select id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>">
					<?php foreach (array('grid'=>__('Grid','noo'),'list'=>__('List','noo')) as $k=>$v):?>
					<option value="<?php echo esc_attr($k)?>" <?php selected($display_type,$k)?>><?php echo $v?></option>
					<?php endforeach;?>
				</select>
			</label>
		</p>
		<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '','property_id'=>'','display_type'=>''));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['property_id'] = $new_instance['property_id'];
		$instance['display_type'] = $new_instance['display_type'];
		return $instance;
	}
}
//register_widget( 'NooSinglePropertyWidget' );