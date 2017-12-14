<?php 
	global $homeland_class; 
?>

<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class($homeland_class) ); ?>>
	<div class="property-mask">
		<?php 
			if(!empty($homeland_featured)) :
				?><div class="featured-ribbon"><i class="fa fa-star" title="Featured"></i></div><?php
			endif;

			if ( post_password_required() ) :
				?><div class="password-protect-thumb password-4cols"><i class="fa fa-lock fa-2x"></i></div><?php
			else :
				?>
					<figure class="pimage">
						<a href="<?php the_permalink(); ?>">
							<?php 
								if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_4cols'); 
								else :
									echo '<img src="' . get_template_directory_uri() . '/img/no-property-image-4cols.png" title="" alt="" />';
								endif; 
							?>
						</a>
						<figcaption><a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a></figcaption>				
					</figure>
				<?php
			endif;
		?>			
	</div>
	<div class="property-desc portfolio-desc">
		<?php the_title( '<h4><a href="' . get_permalink() . '">', '</a></h4>' ); ?>	
		<h5><?php the_excerpt(); ?></h5>
	</div>	
</li>