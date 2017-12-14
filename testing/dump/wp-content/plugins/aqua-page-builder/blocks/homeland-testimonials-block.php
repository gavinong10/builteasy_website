<?php
/** Testimonial block **/
class Homeland_Testimonials_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Testimonial', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_testimonials_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
			'homeland_background' => ''
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
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order); 
				?>
				<small><?php _e( 'Select your testimonials order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' );
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for testimonials', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of testimonial to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_background'); ?>">
				<?php 
					_e( 'Background', 'aqpb-l10n' );
					echo aq_field_upload('homeland_background', $block_id, $homeland_background, $media_type = 'image'); 
				?>  
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		if(!empty($homeland_background)) :
			?><style>.testimonial-pb-block { background: url('<?php echo $homeland_background; ?>') !important; }</style><?php
		endif;

		global $post;

		$args = array( 
			'post_type' => 'homeland_testimonial', 
			'posts_per_page' => $homeland_limit,
			'orderby' => $homeland_sort, 
			'order' => $homeland_order
		);

		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--TESTIMONIALS-->
			<div class="testimonial-pb-block">
				<div class="inside">
					<h3>&quot;<?php echo $title; ?>&quot;</h3>
					<div class="testimonial-flexslider">	
						<ul class="slides">
							<?php
								while ($wp_query->have_posts()) : 
									$wp_query->the_post(); 
									$homeland_position = esc_attr( get_post_meta( $post->ID, 'homeland_position', true ) );

								?>
									<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?>>
										<?php 
											the_content();
											if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_theme_thumb'); endif;
											the_title( '<h4>', '</h4>' ); 
										?>	
										<h5><?php echo $homeland_position; ?></h5>
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