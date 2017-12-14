<?php 
	$homeland_property_status = get_the_term_list ( $post->ID, 'homeland_property_status', ' ', ', ', '' );
	$homeland_price = get_post_meta( $post->ID, 'homeland_price', true );
	$homeland_area = esc_attr( get_post_meta($post->ID, 'homeland_area', true) );
	$homeland_area_unit = get_post_meta( $post->ID, 'homeland_area_unit', true );
	$homeland_floor_area = get_post_meta( $post->ID, 'homeland_floor_area', true );
	$homeland_floor_area_unit = get_post_meta( $post->ID, 'homeland_floor_area_unit', true );
	$homeland_bedroom = esc_attr( get_post_meta($post->ID, 'homeland_bedroom', true) );
	$homeland_bathroom = esc_attr( get_post_meta($post->ID, 'homeland_bathroom', true) );
	$homeland_featured = esc_attr( get_post_meta($post->ID, 'homeland_featured', true) );
	$homeland_garage = esc_attr( get_post_meta($post->ID, 'homeland_garage', true) );
	$homeland_property_excerpt = esc_attr( get_option('homeland_property_excerpt') );
?>

<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('property-cols') ); ?>>
	<div class="property-mask">
		<?php 
			if(!empty($homeland_featured)) :
				?><div class="featured-ribbon"><i class="fa fa-star" title="Featured"></i></div><?php
			endif;

			if ( post_password_required() ) :
				?><div class="password-protect-thumb password-2cols"><i class="fa fa-lock fa-2x"></i></div><?php
			else :
				?>
					<figure class="pimage">
						<a href="<?php the_permalink(); ?>">
							<?php 
								if ( has_post_thumbnail() ) : the_post_thumbnail( 'homeland_portfolio_large' ); 
								else :
									echo '<img src="' . get_template_directory_uri() . '/img/no-property-image-2cols.png" title="" alt="" />';
								endif; 
							?>
						</a>
						<figcaption><a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a></figcaption>
						<?php
							if(!empty( $homeland_property_status )) : ?><h4><?php echo $homeland_property_status; ?></h4><?php
							endif; 

							if( !empty($homeland_price) ) : ?>
								<div class="property-price clear">
									<div class="cat-price"><?php homeland_property_price_format(); ?></div>
									<span class="picon"><i class="fa fa-tag"></i></span>
								</div><?php
							endif;
						?>	
					</figure>
				<?php
			endif;
		?>			
	</div>
	<div class="property-info">
		<?php
			if(!empty($homeland_area)) :
				?><span><i class="fa fa-expand"></i><?php echo $homeland_area; echo "&nbsp;" . $homeland_area_unit; ?></span><?php
			endif;
			if(!empty($homeland_floor_area)) :
				?><span><i class="fa fa-arrows-alt"></i><?php echo $homeland_floor_area; echo "&nbsp;" . $homeland_floor_area_unit; ?></span><?php
			endif;	
			if(!empty($homeland_bedroom)) : ?>
				<span>
					<i class="fa fa-bed"></i>
					<?php echo $homeland_bedroom; ?>
					<?php echo esc_attr( _e( 'Bedrooms', 'codeex_theme_name' ) ); ?>
				</span><?php
			endif;
			if(!empty($homeland_bathroom)) : ?>
				<span>
					<i class="fa fa-male"></i>
					<?php echo $homeland_bathroom; ?> 
					<?php esc_attr( _e( 'Bathrooms', 'codeex_theme_name' ) ); ?>
				</span><?php
			endif;
			if(!empty($homeland_garage)) : ?>
				<span>
					<i class="fa fa-car"></i>
					<?php echo $homeland_garage; ?> 
					<?php esc_attr( _e( 'Garage', 'codeex_theme_name' ) ); ?>
				</span><?php
			endif;
		?>
	</div>
	<div class="property-desc">
		<?php 
			the_title( '<h4><a href="' . get_permalink() . '">', '</a></h4>' ); 
			if(empty($homeland_property_excerpt)) : the_excerpt(); endif;
		?>	
	</div>
</li>