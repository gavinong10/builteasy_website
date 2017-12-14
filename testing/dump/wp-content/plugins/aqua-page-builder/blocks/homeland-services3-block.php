<?php
/** Services block **/
class Homeland_Services3_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Services 3', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_services3_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
			'homeland_button_text' => '',
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

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' );
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order);
				?>
				<small><?php _e( 'Select your services order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for services', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' );
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of services to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_button_text'); ?>">
				<?php 
					_e( 'Button Label', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_button_text', $block_id, $homeland_button_text, $size = 'full'); 
				?>  
				<small><?php _e( ' Add label of your services button', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post;

		$args = array( 
			'post_type' => 'homeland_services', 
			'orderby' => $homeland_sort, 
			'order' => $homeland_order, 
			'posts_per_page' => $homeland_limit 
		);

		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--SERVICES-->
			<section class="services-block-two">
				<div class="inside services-list-box clear"><?php
					for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
						$wp_query->the_post();		

						$homeland_custom_link = get_post_meta( $post->ID, 'homeland_custom_link', true );	
						$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
						$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	

						$homeland_columns = 3;	
						$homeland_class = 'services-list clear ';
						$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : ''; ?>
						
						<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class($homeland_class) ); ?>>
							<div class="services-icon">
								<?php
									if(!empty($homeland_icon)) : ?><i class="fa <?php echo $homeland_icon; ?> fa-4x"></i><?php
									else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
									endif;
								?>
							</div>
							<div class="services-desc">
								<?php 
									the_title( '<h5>', '</h5>' ); 
									the_excerpt();

									if(!empty($homeland_custom_link)) :
										?><a href="<?php echo $homeland_custom_link; ?>" class="more" target="_blank"><?php
									else :
										?><a href="<?php the_permalink(); ?>" class="more"><?php
									endif;
										
										echo $homeland_button_text;
									?>
								</a>
							</div>
						</div><?php
					} ?>				
				</div>
			</section><?php
		endif;

	}
	
}