<?php
/** Blog block **/
class Homeland_Blog_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Blog', 'aqpb-l10n'),
			'size' => 'span4',
		);
		
		//create the block
		parent::__construct('homeland_blog_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_category' => '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		//Location

		$args_category = array( 'hide_empty' => 1, 'hierarchical' => 1, 'parent' => 0 );
		$homeland_bcategory = get_terms('category', $args_category);

		$homeland_category_options = array();

		foreach ($homeland_bcategory as $homeland_blog_category) :
			$homeland_category_options[0] = '-Any-';
			$homeland_category_options[$homeland_blog_category->slug] = $homeland_blog_category->name;
		endforeach;
		
		?>

		<p class="description">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' ); 
					echo aq_field_input('title', $block_id, $title); 
				?>
				<small><?php _e(' Enter your blog header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of blog to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_category'); ?>">
				<?php 
					_e( 'Style', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_category', $block_id, $homeland_category_options, $homeland_category); 
				?>
				<small><?php _e(' Select your blog category to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post; 
		
		$args = array( 'post_type' => 'post', 'posts_per_page' => $homeland_limit, 'category_name' => $homeland_category );
			
		$wp_query = new WP_Query( $args );	

		if ($wp_query->have_posts()) : ?>
			<!--BLOG-->
			<div class="blog-block">
				<h3><span><?php echo $title; ?></span></h3>
				<div class="grid cs-style-3">	
					<ul>
						<?php
							while ($wp_query->have_posts()) : $wp_query->the_post(); 
								$homeland_video = esc_attr( get_post_meta( $post->ID, "homeland_video", true ) );

								if ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) )  ) : 
									echo get_post_meta( $post->ID, 'homeland_audio', TRUE );
								elseif ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) )  ) : ?>
									<li <?php sanitize_html_class( post_class('latest-list') ); ?>>
										<iframe width="340" height="200" src="<?php echo $homeland_video; ?>" frameborder="0" allowfullscreen></iframe>
									</li><?php
								else : ?>
									<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('latest-list clear') ); ?>>
										<div class="bimage">
											<a href="<?php the_permalink(); ?>">
												<?php if ( has_post_thumbnail() ) { the_post_thumbnail('homeland_news_thumb'); } ?>
											</a>
										</div>
										<div class="bdesc">
											<?php the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' );  ?>
											<label>
												<?php esc_attr( _e( 'Posted by:', 'aqpb-l10n' ) ); echo "&nbsp;"; the_author_meta('user_firstname'); echo "&nbsp;|&nbsp;"; the_time(get_option('date_format')); ?>
											</label>
										</div>
									</li><?php	
								endif;						
							endwhile;								
						?>
					</ul>	
				</div>
			</div><?php
		else :
			_e( 'You have no blog post yet!', 'aqpb-l10n' );
		endif;	

	}
	
}