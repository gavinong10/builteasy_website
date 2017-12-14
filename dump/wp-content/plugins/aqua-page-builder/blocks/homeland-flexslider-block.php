<?php
/*Flexslider Block*/
class Homeland_Flexslider_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Default Slider', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_flexslider_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_hide' => 0,
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
			'homeland_button_text' => '',
			'homeland_layout' => '',
			'homeland_list_view' => ''
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

		$homeland_layout_options = array(
			'default' => 'Default',
			'small' => 'Small',
		);

		$homeland_view_options = array(
			'properties' => 'Properties',
			'default' => 'Featured Properties',
			'blog' => 'Blog',
			'portfolio' => 'Portfolio',
		);
		
		?>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_hide'); ?>">
				<?php 
					_e( 'Hide Details', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide', $block_id, $homeland_hide); 
				?>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_layout'); ?>">
				<?php 
					_e( 'Layout', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_layout', $block_id, $homeland_layout_options, $homeland_layout); 
				?>
				<small><?php _e( 'Select your layout type for slider', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_list_view'); ?>">
				<?php 
					_e( 'Display List', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_list_view', $block_id, $homeland_view_options, $homeland_list_view); 
				?>
				<small><?php _e( 'Select your list view type for slider', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order); 
				?>
				<small><?php _e( 'Select your slider order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for slider', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' );
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of posts to be display for slider', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_button_text'); ?>">
				<?php 
					_e( 'Button Label', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_button_text', $block_id, $homeland_button_text, $size = 'full'); 
				?>  
				<small><?php _e( ' Add label of your slider button', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post;
		$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
		$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 

		if($homeland_list_view == 'properties') :
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit
			);
		elseif($homeland_list_view == 'blog') :
			$args = array( 
				'post_type' => 'post', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit
			);
		elseif($homeland_list_view == 'portfolio') :
			$args = array( 
				'post_type' => 'homeland_portfolio', 
				'orderby' => $homeland_sort, 
				'order' => $homeland_order, 
				'posts_per_page' => $homeland_limit
			);
		else :
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
		endif;

		$wp_query = new WP_Query( $args );

		if ($wp_query->have_posts()) : ?>
			<!--SLIDER-->
			<?php
				if($homeland_layout == "small") :
					?><section class="slider-block-thumb"><div class="home-flexslider flex-loading"><?php
				else :
					?><section class="slider-block"><div class="home-flexslider flex-loading"><?php
				endif;
			?>
					<ul class="slides"><?php
						while ($wp_query->have_posts()) : 
							$wp_query->the_post(); 
							$homeland_price_per = get_post_meta( $post->ID, 'homeland_price_per', true );
							$homeland_property_currency = get_post_meta( $post->ID, 'homeland_property_currency', true );
							$homeland_price = get_post_meta($post->ID, 'homeland_price', true );
							$homeland_price_format = get_option('homeland_price_format');
							$homeland_thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'homeland_property_thumb' );  


							if($homeland_layout == "small") : ?>
								<li id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-thumb="<?php echo $homeland_thumb_image_url[0]; ?>">
									<div class="slide-image">
										<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'homeland_portfolio_large' ); endif; ?>
									</div>
								<?php
							else : ?>
								<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<div class="slide-image">
										<?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_slider'); endif; ?>
									</div>
								<?php
							endif; 

									if(empty( $homeland_hide )) : ?>
										<div class="inside">
											<div class="slider-actions">
												<div class="portfolio-slide-desc">
													<?php 
														the_title( '<h2>', '</h2>' ); 
														the_excerpt(); 
													?>
												</div>	
												<div class="pactions clear">
													<?php 
														if(!empty($homeland_price)) : ?>
															<label>
																<i class="fa fa-tag"></i>
																<span><?php homeland_property_price_format(); ?></span>
															</label><?php
														endif;
													?>
													<a href="<?php the_permalink(); ?>">
														<span><?php echo $homeland_button_text; ?></span><i class="fa fa-plus-circle"></i>
													</a>
												</div>
											</div>
										</div><?php
									endif; 
								?>	
							</li><?php
						endwhile; ?>
					</ul>	
				</div>	
			</section><?php
		endif;

	}
	
}
