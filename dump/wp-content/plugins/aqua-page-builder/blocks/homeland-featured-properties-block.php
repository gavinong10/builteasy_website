<?php
/** Featured Properties block **/
class Homeland_Featured_Properties_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Featured Properties', 'aqpb-l10n'),
			'size' => 'span4',
		);
		
		//create the block
		parent::__construct('homeland_featured_properties_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
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
		
		?>

		<p class="description">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' );
					echo aq_field_input('title', $block_id, $title); 
				?>
				<small><?php _e(' Enter your featured properties header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' );
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?> 
				<small><?php _e(' Enter your number of featured properties to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order);
				?>
				<small><?php _e( 'Select your featured property order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for featured property', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post;
		
		$homeland_price_format = get_option('homeland_price_format');
		$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
		$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
		$homeland_preferred_size = esc_attr( get_option('homeland_preferred_size') ); 

		?>
			<div class="featured-block">
				<h3><span><?php echo $title; ?></span></h3>
				<?php
					$args = array( 
						'post_type' => 'homeland_properties', 
						'orderby' => $homeland_sort, 
						'order' => $homeland_order, 
						'posts_per_page' => $homeland_limit, 
						'meta_query' => array( array( 
							'key' => 'homeland_featured', 
							'value' => 'on', 
							'compare' => '==' 
						)) 
					);		

					$wp_query = new WP_Query( $args );

					if ($wp_query->have_posts()) : ?>
						<div class="grid cs-style-3">	
							<ul>
								<?php
									while ($wp_query->have_posts()) : 
										$wp_query->the_post(); 
										$homeland_price_per = get_post_meta( $post->ID, 'homeland_price_per', true );
										$homeland_price = get_post_meta($post->ID, 'homeland_price', true );
										$homeland_property_currency = get_post_meta($post->ID, 'homeland_property_currency', true );
										$homeland_area = esc_attr( get_post_meta($post->ID, 'homeland_area', true) );
										$homeland_area_unit = get_post_meta( $post->ID, 'homeland_area_unit', true );
										$homeland_floor_area = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area', true ) );
										$homeland_floor_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area_unit', true ) );
										$homeland_bedroom = esc_attr( get_post_meta($post->ID, 'homeland_bedroom', true) );
										$homeland_bathroom = esc_attr( get_post_meta($post->ID, 'homeland_bathroom', true) );
										$homeland_garage = esc_attr( get_post_meta($post->ID, 'homeland_garage', true) );
										$homeland_property_status = get_the_term_list( $post->ID, 'homeland_property_status', ' ', ', ', '' );

										?>
										<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('featured-list clear') ); ?>>
											<?php
												if ( post_password_required() ) : ?>
													<div class="password-protect-thumb featured-pass-thumb">
														<i class="fa fa-lock fa-2x"></i>
													</div><?php
												else : ?>
													<figure class="feat-thumb">
														<a href="<?php the_permalink(); ?>">
															<?php 
																if ( has_post_thumbnail() ) : the_post_thumbnail(); 
																else :
																	echo '<img src="' . get_template_directory_uri() . '/img/no-property-image.png" title="" alt="" />';
																endif; 
															?>
														</a>
														<figcaption>
															<a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a>
														</figcaption>
														<?php
															if(!empty( $homeland_property_status )) : ?>
																<h4><?php echo $homeland_property_status; ?></h4><?php
															endif; 
														?>	
													</figure><?php	
												endif;
											?>
											<div class="feat-desc">
												<?php the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' ); ?>
												<span>
													<?php 
														if($homeland_preferred_size == "Floor Area") :
															if(!empty($homeland_floor_area)) :
																echo $homeland_floor_area . "&nbsp;" . $homeland_floor_area_unit . ", "; 
															endif;
														else :
															if(!empty($homeland_area)) :
																echo $homeland_area . "&nbsp;" . $homeland_area_unit . ", "; 
															endif;
														endif;
														if(!empty($homeland_bedroom)) :
															echo $homeland_bedroom . "&nbsp;"; _e( 'Bedrooms', 'aqpb-l10n' ); echo ", "; 
														endif;
														if(!empty($homeland_bathroom)) :
															echo $homeland_bathroom . "&nbsp;"; _e( 'Bathrooms', 'aqpb-l10n' ); echo ", "; 
														endif;
														if(!empty($homeland_garage)) :
															echo $homeland_garage . "&nbsp;"; _e( 'Garage', 'aqpb-l10n' ); 
														endif;
													?>
												</span>
												<?php
													if( !empty($homeland_price) ) : ?>
														<span class="price"><?php homeland_property_price_format(); ?></span><?php
													endif;
												?>
											</div>
										</li><?php
									endwhile;
								?>							
							</ul>
						</div><?php
					endif;
				?>
			</div>
		<?php	

	}
	
}