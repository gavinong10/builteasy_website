<?php

$blog_name            = get_bloginfo( 'name' );
$blog_desc            = get_bloginfo( 'description' );
$image_logo           = '';
$retina_logo           = '';
if ( noo_get_option( 'noo_header_use_image_logo', false ) ) {
	$image_logo = noo_get_image_option( 'noo_header_logo_image', '' );
	$retina_logo = noo_get_image_option( 'noo_header_logo_retina_image', $image_logo );
}

$nav_phone_number = noo_get_option( 'noo_header_nav_phone_number', '' );
$noo_header_nav_icon_cart_woo = noo_get_option('noo_header_nav_icon_cart_woo', true);

?>
<div class="navbar-wrapper">
	<div class="navbar <?php echo noo_navbar_class(); ?>" role="navigation">
		<div class="container-boxed max">
			<div class="noo-navbar">
				<div class="navbar-header">
					<?php if ( is_front_page() ) : echo '<h1 class="sr-only">' . $blog_name . '</h1>'; endif; ?>
					<a class="navbar-toggle collapsed" data-toggle="collapse" data-target=".noo-navbar-collapse">
						<span class="sr-only"><?php echo __( 'Navigation', 'noo' ); ?></span>
						<i class="nooicon-bars"></i>
					</a>
					<a href="<?php echo home_url( '/' ); ?>" class="navbar-brand" title="<?php echo $blog_desc      ; ?>">
						<?php echo ( $image_logo == '' ) ? $blog_name : '<img class="noo-logo-img noo-logo-normal" src="' . $image_logo . '" alt="' . $blog_desc . '">'; ?>
						<?php echo ( $retina_logo == '' ) ? '' : '<img class="noo-logo-retina-img noo-logo-normal" src="' . $retina_logo . '" alt="' . $blog_desc . '">'; ?>
					</a>
					<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $noo_header_nav_icon_cart_woo ) : ?>
						<!-- <a href="http://wpthemes.noothemes.com/noopress/dreamer/cart/" title="View Cart" class="mobile-minicart-icon"><i class="fa fa-shopping-cart"></i><span>1</span></a> -->
						<?php echo noo_minicart_mobile(); ?>
					<?php endif; ?>
				</div> <!-- / .nav-header -->

				<?php if( !empty($nav_phone_number) ) : ?>
				<div class="calling-info">
					<div class="calling-content">
						<i class="nooicon-mobile"></i>
						<div class="calling-desc">
							<?php _e('CALL US NOW', 'noo'); ?><br>
							<span><a href="tel:<?php echo $nav_phone_number; ?>"><?php echo $nav_phone_number; ?></a></span>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<nav class="collapse navbar-collapse noo-navbar-collapse<?php echo (is_one_page_enabled() ? ' navbar-scrollspy':'')?>" role="navigation">
		        <?php
		        if ( is_one_page_enabled() ) {
		        	$onepage_menu = get_one_page_menu();
		        	if(  ! empty( $onepage_menu ) ) {
		        		wp_nav_menu( array(
		        			'menu'           => $onepage_menu,
							'container'      => false,
							'menu_class'     => 'navbar-nav nav sf-menu'
							) );
		        	}
		        } else {
					if ( has_nav_menu( 'primary' ) ) :
						wp_nav_menu( array(
							'theme_location' => 'primary',
							
							'container'      => false,
							'menu_class'     => 'navbar-nav sf-menu'
							) );
					else :
						echo '<ul class="navbar-nav nav"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __( 'No menu assigned!', 'noo' ) . '</a></li></ul>';
					endif;
		        }
				?>
				</nav> <!-- /.navbar-collapse -->
			</div>
		</div> <!-- /.container-boxed -->
	</div> <!-- / .navbar -->
</div>
