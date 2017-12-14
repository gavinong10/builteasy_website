<?php
global $post;
			
$status = get_the_terms($post->ID, 'property_status');
$categories =get_the_terms( $post->ID, 'property_category' );
$num = noo_get_option( 'noo_property_similar_num', 2 );

$args = array(
		'posts_per_page' => absint( $num ),
		'post__not_in' => array($post->ID),
		'orderby' => 'rand',
		'post_type'=>'noo_property',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' 	=> 'property_category',
				'terms' 	=> wp_get_object_terms($post->ID, 'property_category', array('fields' => 'ids')),
				'field' 	=> 'id'
			),
			array(
				'taxonomy' 	=> 'property_status',
				'terms' 	=> wp_get_object_terms($post->ID, 'property_status', array('fields' => 'ids')),
				'field' 	=> 'id'
			),
		)
);

$similar = new WP_Query($args);
if($similar->have_posts()):
?>
	<div class="similar-property">
		<div class="similar-property-title">
			<h3><?php echo __('Similar Properties','noo')?></h3>
		</div>
		<div class="similar-property-content">
			<div class="properties grid">
			<?php while ($similar->have_posts()): $similar->the_post(); global $post;?>
				<article id="property-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="property-featured">
				        <a class="content-thumb" href="<?php the_permalink() ?>">
							<?php echo get_the_post_thumbnail(get_the_ID(),'property-thumb') ?>
						</a>
				    </div>
				    <div class="property-wrap">
						<h2 class="property-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="property-summary">
							<?php echo self::get_property_summary( array('container_class'=>'property-detail')); ?>
							<div class="property-info">
								<div class="property-price">
									<span><?php echo self::get_price_html(get_the_ID())?></span>
								</div>
								<div class="property-action">
									<a href="<?php the_permalink()?>"><?php echo __('More Details','noo')?></a>
								</div>
							</div>
						</div>
					</div>
				</article>
			<?php endwhile;?>
			</div>
		</div>
	</div>
<?php endif;