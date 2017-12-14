<?php
/** Properties block **/
class Homeland_Properties_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Properties', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_properties_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
			'homeland_location' => '',
			'homeland_status' => '',
			'homeland_type' => '',
			'homeland_tax_relation' => '',
			'homeland_layout' => '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		$homeland_order_options = array(
			'DESC' => 'Descending',
			'ASC' => 'Ascending',
		);

		$homeland_sort_options = array(
			'ID' => 'ID',
			'author' => 'Author',
			'title' => 'Title',
			'name' => 'Name',
			'date' => 'Date',
			'modified' => 'Modified',
			'parent' => 'Parent',
			'rand' => 'Random',
			'comment_count' => 'Comment Count',
			'menu_order' => 'Menu Order',
		);

		$homeland_relation_options = array(
			'None' => '-None-',
			'AND' => 'And',
			'OR' => 'Or',
		);

		$homeland_layout_options = array(
			'carousel' => 'Carousel',
			'grid' => 'Grid',
		);


		//Location

		$args_location = array( 'hide_empty' => 1, 'hierarchical' => 0, 'parent' => 0 );
		$homeland_terms_location = get_terms('homeland_property_location', $args_location);

		$homeland_location_options = array();

		foreach ($homeland_terms_location as $homeland_plocation) :
			$homeland_location_options[0] = '-Any-';
			$homeland_location_options[$homeland_plocation->slug] = $homeland_plocation->name;
		endforeach;


		//Status

		$args_status = array( 'hide_empty' => 1, 'hierarchical' => 0, 'parent' => 0 );
		$homeland_terms_status = get_terms('homeland_property_status', $args_status);

		$homeland_status_options = array();

		foreach ($homeland_terms_status as $homeland_pstatus) :
			$homeland_status_options[0] = '-Any-';
			$homeland_status_options[$homeland_pstatus->slug] = $homeland_pstatus->name;
		endforeach;


		//Type

		$args_type = array( 'hide_empty' => 1, 'hierarchical' => 0, 'parent' => 0 );
		$homeland_terms_type = get_terms('homeland_property_type', $args_type);

		$homeland_type_options = array();

		foreach ($homeland_terms_type as $homeland_ptype) :
			$homeland_type_options[0] = '-Any-';
			$homeland_type_options[$homeland_ptype->slug] = $homeland_ptype->name;
		endforeach;
		
		?>

		<p class="description">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' ); 
					echo aq_field_input('title', $block_id, $title); 
				?> 
				<small><?php _e(' Enter your properties header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of properties to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_layout'); ?>">
				<?php 
					_e( 'Style', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_layout', $block_id, $homeland_layout_options, $homeland_layout);
				?>
				<small><?php _e(' Select your property style to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' );
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order); 
				?>
				<small><?php _e( 'Select your properties order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort);
				?>
				<small><?php _e( 'Select your sort by parameter for properties', 'aqpb-l10n' ); ?></small>
			</label>
		</p>

		<fieldset style="padding: 20px; border: 1px solid #CCC; background: #EEE;">
			<legend><?php _e( 'Taxonomy', 'aqpb-l10n' ); ?></legend>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('homeland_tax_relation'); ?>">
					<?php 
						_e( 'Relation', 'aqpb-l10n' ); 
						echo aq_field_select('homeland_tax_relation', $block_id, $homeland_relation_options, $homeland_tax_relation); 
					?>
					<small><?php _e(' Select your property taxonomy relation to be display', 'aqpb-l10n'); ?></small>
				</label>
			</p>
			<p class="description half last">
				<label for="<?php echo $this->get_field_id('homeland_location'); ?>">
					<?php 
						_e( 'Location', 'aqpb-l10n' ); 
						echo aq_field_select('homeland_location', $block_id, $homeland_location_options, $homeland_location); 
					?>
					<small><?php _e(' Select your property location to be display', 'aqpb-l10n'); ?></small>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('homeland_status'); ?>">
					<?php 
						_e( 'Status', 'aqpb-l10n' ); 
						echo aq_field_select('homeland_status', $block_id, $homeland_status_options, $homeland_status); 
					?>
					<small><?php _e(' Select your property status to be display', 'aqpb-l10n'); ?></small>
				</label>
			</p>
			<p class="description half last">
				<label for="<?php echo $this->get_field_id('homeland_type'); ?>">
					<?php 
						_e( 'Type', 'aqpb-l10n' ); 
						echo aq_field_select('homeland_type', $block_id, $homeland_type_options, $homeland_type); 
					?>
					<small><?php _e(' Select your property type to be display', 'aqpb-l10n'); ?></small>
				</label>
			</p>
		</fieldset>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post, $homeland_class;

		if($homeland_tax_relation == "AND") :
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit,
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'homeland_property_location',
						'field'    => 'slug',
						'terms'    => $homeland_location,
					),
					array(
						'taxonomy' => 'homeland_property_status',
						'field'    => 'slug',
						'terms'    => $homeland_status,
					),
					array(
						'taxonomy' => 'homeland_property_type',
						'field'    => 'slug',
						'terms'    => $homeland_type,
					),
				),
			);
		elseif($homeland_tax_relation == "OR") :
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit,
				'tax_query' => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'homeland_property_location',
						'field'    => 'slug',
						'terms'    => array( $homeland_location ),
					),
					array(
						'taxonomy' => 'homeland_property_status',
						'field'    => 'slug',
						'terms'    => array( $homeland_status ),
					),
					array(
						'taxonomy' => 'homeland_property_type',
						'field'    => 'slug',
						'terms'    => array( $homeland_type ),
					),
				),
			);
		else :
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit
			);
		endif;

		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--PROPERTY-->
			<section class="property-block">
				<div class="inside property-list-box clear">
					<h2><span><?php echo $title; ?></span></h2>

					<?php
						if($homeland_layout == "grid") : ?>
							<div class="grid cs-style-3 masonry">	
								<ul class="clear">
									<?php
										for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
											$wp_query->the_post();			
											$homeland_columns = 3;	
											$homeland_class = 'property-home masonry-item ';
											$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
											
											get_template_part( 'loop', 'property-home' );
										}
									?>
								</ul>
							</div><?php
						else : ?>
							<div id="carousel" class="es-carousel-wrapper">
								<div class="es-carousel">
									<div class="grid cs-style-3">	
										<ul class="clear">
											<?php
												while ($wp_query->have_posts()) : $wp_query->the_post();
													$homeland_class = 'property-home';
													get_template_part( 'loop', 'property-home' );
												endwhile;								
											?>
										</ul>
									</div>
								</div>	
							</div><?php
						endif;
					?>		
				</div>
			</section><?php	
		endif;
	}
	
}