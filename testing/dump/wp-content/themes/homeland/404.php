<?php get_header(); ?>

	<?php homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"... ?>

	<section class="page-block">
		<div class="inside">

			<?php
				$homeland_not_found_large_text = get_option('homeland_not_found_large_text');
				$homeland_not_found_small_text = esc_attr( get_option('homeland_not_found_small_text') );
				$homeland_not_found_button = esc_attr( get_option('homeland_not_found_button') );
				$homeland_not_found_brief_text = !empty($homeland_not_found_small_text) ? $homeland_not_found_small_text : __('Looks like the file you are looking for is no longer available', 'codeex_theme_name');
				$homeland_not_found_button_label = !empty($homeland_not_found_button) ? $homeland_not_found_button : __('Back to Homepage', 'codeex_theme_name');
			?>

			<!--CONTENT-->
			<div class="page-not-found">
				<h2>
					<?php
						if(!empty( $homeland_not_found_large_text )) : echo stripslashes( $homeland_not_found_large_text );
						else :
							esc_attr( _e( 'Error', 'codeex_theme_name' ) ); ?>
							<span><?php sanitize_title( _e( '404', 'codeex_theme_name' ) ); ?></span><?php
						endif;
					?>
				</h2>
				<h5><?php echo $homeland_not_found_brief_text; ?></h5>
				<a href="<?php echo esc_url( home_url('/') ); ?>" class="back-home"><?php echo $homeland_not_found_button_label; ?></a>
			</div>
		</div>
	</section>
	
<?php get_footer(); ?>