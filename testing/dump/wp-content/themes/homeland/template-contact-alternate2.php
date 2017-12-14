<?php
/*
Template Name: Contact 3
*/
?>

<?php get_header(); ?>

	<!--CONTACT US-->

	<?php
		$homeland_hide_gmap = esc_attr( get_option('homeland_hide_gmap') );
		$homeland_phone_number = esc_attr( get_option('homeland_phone_number') );
		$homeland_fax = esc_attr( get_option('homeland_fax') );
		$homeland_contact_address = esc_attr( get_option('homeland_contact_address') );
		$homeland_email_address = esc_attr( get_option('homeland_email_address') );
		$homeland_working_hours = esc_attr( get_option('homeland_working_hours') );
		$homeland_subtitle = esc_attr( get_post_meta( $post->ID, "homeland_subtitle", true ) );

		if( empty( $homeland_hide_gmap ) ) : echo "<section id='map'></section>";
		else : echo "<div class='empty-div'>&nbsp;</div>";
		endif;
	?>

	<section class="theme-pages">

		<div class="inside">

			<!--CONTACT INFO-->
			<div class="contact-info clear">
				<?php
					if(!empty($homeland_ptitle)) : 
						echo '<h2>' . $homeland_ptitle . '</h2>';
					else : the_title('<h2>', '</h2>'); endif; 

					if(!empty($homeland_subtitle)) : 
						echo '<p>' . stripslashes ( $homeland_subtitle ) . '</p>';
					endif;
				?>
			</div>

			<!-- Contact Alternate -->
			<div class="contact-alternate-two clear">
				<div class="contact-alternate-main">
					<?php
						if(!empty($homeland_contact_address)) : ?>
							<label class="contact-address">
								<i class="fa fa-map-marker"></i>
								<span><?php esc_attr( _e( 'Address', 'codeex_theme_name' ) ); echo ":"; ?></span>
								<?php echo $homeland_contact_address; ?>
							</label><?php
						endif;

						if(!empty($homeland_working_hours)) : ?>
							<label class="working-hours">
								<i class="fa fa-clock-o"></i>
								<span><?php esc_attr( _e( 'Working Hours', 'codeex_theme_name' ) ); echo ":"; ?></span>
								<?php echo $homeland_working_hours; ?>
							</label><?php
						endif;

						if(!empty($homeland_phone_number)) : ?>
							<label>
								<i class="fa fa-phone"></i>
								<span><?php esc_attr( _e( 'Phone', 'codeex_theme_name' ) ); echo ":"; ?></span>
								<?php echo $homeland_phone_number; ?>
							</label><?php
						endif;

						if(!empty($homeland_email_address)) : ?>
							<label>
								<i class="fa fa-envelope"></i>
								<span><?php esc_attr( _e( 'Email', 'codeex_theme_name' ) ); echo ":"; ?></span>
								<a href="mailto: <?php echo $homeland_email_address; ?>"><?php echo $homeland_email_address; ?></a>
							</label><?php
						endif;

						if(!empty($homeland_fax)) : ?>
							<label>
								<i class="fa fa-print"></i>
								<span><?php esc_attr( _e( 'Fax', 'codeex_theme_name' ) ); echo ":"; ?></span>
								<?php echo $homeland_fax; ?>
							</label><?php
						endif;
					?>
				</div>

				<!--CONTACT FORM-->
				<div class="contact-form">
					<?php
						if (have_posts()) : 
							while (have_posts()) : the_post(); 
								the_content(); 								
							endwhile; 
						endif;
					?>
				</div>
			</div>
		</div>

	</section>

<?php get_footer(); ?>