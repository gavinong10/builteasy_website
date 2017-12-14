<?php
if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'noo_property' ) :
?>
<?php 
global $wp_query;

$show_map = noo_get_option('noo_property_listing_map',1);
$show_search = noo_get_option('noo_property_listing_search',1);
$disable_map = ( ! $show_map && $show_search ) ? ' disable_map="true"' : '';
$disable_search_form = ( $show_map && ! $show_search )  ? ' disable_search_form="true"' : '';
$search_layout = noo_get_option('noo_property_listing_map_layout','horizontal');
$advanced_search = ($show_search && noo_get_option('noo_property_listing_advanced_search',0)) ? ' advanced_search="true"' : '';

$title = __('Properties matching your search', 'noo');
?>
<?php get_header(); ?>
<div class="container-wrap">
	<?php if(!empty($show_map) || !empty($show_search)):?>
	<?php echo do_shortcode('[noo_advanced_search_property style="'.$search_layout.'"' . $disable_map . $disable_search_form . $advanced_search . ']');?>
	<?php endif;?>
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="<?php noo_main_class(); ?>" role="main">
				<?php if ( have_posts() ) : ?>
					<?php NooProperty::display_content($wp_query,$title,true,'',true,false,noo_get_option('noo_property_listing_orderby', 1))?>
				<?php else : ?>
					<?php noo_get_layout( 'no-content' ); ?>
				<?php endif; ?>
				<?php 
					wp_reset_query();
					wp_reset_postdata();
				?>
			</div> <!-- /.main -->
			<?php get_sidebar(); ?>
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->
<?php get_footer(); ?>

<?php else :
?>
<?php get_header(); ?>

<div class="container-wrap">
	
	<div class="main-content container-boxed max offset">
		
		<div class="row">
			
			<div class="<?php noo_main_class(); ?> <?php noo_page_class(); ?>" role="main">
				<h1><?php _e('Results For', 'noo'); ?><span>"<?php the_search_query(); ?>"</span></h1>
				
				<div id="search-results">
						
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
							
							<?php if( get_post_type($post->ID) == 'post' ){ ?>
								<article class="result">
									<div class="content-featured">
										<?php noo_featured_content( $post->ID ); ?>
									</div>
									<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo __('Blog Post', 'noo'); ?></small></h2>
									<?php if(get_the_excerpt()) the_excerpt(); ?>
									<hr/>
								</article><!--/search-result-->	
							<?php }
							
							else if( get_post_type($post->ID) == 'page' ){ ?>
								<article class="result">
									<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo __('Page', 'noo'); ?></small></h2>	
								
									<?php if(get_the_excerpt()) the_excerpt(); ?>
									<hr/>
								</article><!--/search-result-->	
							<?php }
							
							else if( get_post_type($post->ID) == 'product' ){ ?>
								<article class="result">
									<?php if(has_post_thumbnail( $post->ID )) {	
										echo '<a href="'.get_permalink().'">'. get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
									} ?>
									<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo __('Product', 'noo'); ?></small></h2>	
									<?php if(get_the_excerpt()) the_excerpt(); ?>
									<hr/>
								</article><!--/search-result-->	
							<?php } else if( get_post_type($post->ID) == 'noo_property' ){ ?>
								<article class="result">
									<?php if(has_post_thumbnail( $post->ID )) {	
										echo '<a href="'.get_permalink().'">'. get_the_post_thumbnail($post->ID, 'property-image', array('title' => '')).'</a>'; 
									} ?>
									<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo __('Property', 'noo'); ?></small></h2>	
									<?php if(get_the_excerpt()) the_excerpt(); ?>
									<hr/>
								</article><!--/search-result-->	
							<?php } else { ?>
								<article class="result">
									<?php if(has_post_thumbnail( $post->ID )) {	
										echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
									} ?>
									<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

									<?php if(get_the_excerpt()) the_excerpt(); ?>
									<hr/>
								</article><!--/search-result-->	
							<?php } ?>
						
					<?php endwhile; 
					
					else: echo "<p>" . __('No results found', 'noo') . "</p>"; endif;?>
				
				</div><!--/search-results-->
			
				<?php noo_pagination(); ?>
			</div> <!-- /.main -->

			<?php get_sidebar(); ?>
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->

<?php get_footer(); ?>
<?php endif; ?>
