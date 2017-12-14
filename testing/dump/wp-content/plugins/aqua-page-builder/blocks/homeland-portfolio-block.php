<?php
/** Portfolio block **/
class Homeland_Portfolio_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Portfolio', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_portfolio_block', $block_options);
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

		<p class="description half">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' ); 
					echo aq_field_input('title', $block_id, $title); 
				?> 
				<small><?php _e(' Enter your portfolio header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of portfolio to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' );
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order); 
				?>
				<small><?php _e( 'Select your portfolio order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort);
				?>
				<small><?php _e( 'Select your sort by parameter for portfolio', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post, $homeland_class;

		$args = array( 
			'post_type' => 'homeland_portfolio', 
			'orderby' => $homeland_sort, 
			'order' => $homeland_order, 
			'posts_per_page' => $homeland_limit
		);

		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--PROPERTY-->
			<section class="property-block">
				<div class="inside property-list-box clear">
					<h2><span><?php echo $title; ?></span></h2>
					<div class="grid cs-style-3 masonry">	
						<ul class="clear">
							<?php
								for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
									$wp_query->the_post();			
									$homeland_columns = 3;	
									$homeland_class = 'property-home masonry-item ';
									$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
									
									get_template_part( 'loop', 'portfolio' );
								}
							?>
						</ul>
					</div>
				</div>
			</section><?php	
		endif;
	}
	
}