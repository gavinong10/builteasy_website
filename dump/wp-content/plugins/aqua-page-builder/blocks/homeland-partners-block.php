<?php
/** Partners block **/
class Homeland_Partners_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Partners', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_partners_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => ''
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
				<small><?php _e('Enter your properties header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' );
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order); 
				?>
				<small><?php _e( 'Select your partners order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for partners', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of partners to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post;
								
		$args = array( 
			'post_type' => 'homeland_partners', 
			'order' => $homeland_order,
			'orderby' => $homeland_sort,
			'posts_per_page' => $homeland_limit 
		);

		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--PARTNERS-->
			<div class="partners-block">
				<div class="inside">
					<h3><?php echo $title; ?></h3>
					<div class="partners-flexslider clear">	
						<ul class="slides">
							<?php
								while ($wp_query->have_posts()) : 
									$wp_query->the_post(); 
									$homeland_url = esc_url( get_post_meta( $post->ID, 'homeland_url', true ) );

									?>
									<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?>>
										<a href="<?php echo $homeland_url; ?>" target="_blank">
											<?php  if ( has_post_thumbnail() ) : the_post_thumbnail('full'); endif; ?>	
										</a>
									</li><?php							
								endwhile;								
							?>
						</ul>	
					</div>
				</div>
			</div><?php
		endif;
	}
	
}