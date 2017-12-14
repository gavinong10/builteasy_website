<?php 
	global $post; 
	$homeland_services_button = esc_attr( get_option('homeland_services_button') );
	$homeland_custom_link = esc_url( get_post_meta( $post->ID, 'homeland_custom_link', true ) );	
	$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
	$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	
	$homeland_services_button_label = !empty( $homeland_services_button ) ? $homeland_services_button : __( 'More Details', 'codeex_theme_name' );
?>

<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('services-page-list clear') ); ?>>
	<div class="services-page-icon">
		<?php
			if(!empty($homeland_icon)) : ?><i class="fa <?php echo $homeland_icon; ?> fa-4x"></i><?php
			else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
			endif;
		?>
	</div>						
	<div class="services-page-desc">
		<?php 
			if(!empty($homeland_custom_link)) : the_title( '<h5><a href="' . $homeland_custom_link . '" target="_blank">', '</a></h5>' );
			else : the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' );
			endif;

			the_excerpt(); 

			if(!empty($homeland_custom_link)) : ?><a href="<?php echo $homeland_custom_link; ?>" class="read-more" target="_blank"><?php
			else : ?><a href="<?php the_permalink(); ?>" class="read-more"><?php
			endif;
				echo $homeland_services_button_label;
		?>
		</a>
	</div>
</div>