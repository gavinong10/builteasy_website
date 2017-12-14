
<?php if ( noo_get_option( 'noo_header_top_bar', false ) ) :
$topbar_content = 'html'; // noo_get_option( 'noo_top_bar', 'html' );
$topbar_social = noo_get_option( 'noo_top_bar_social', true ) ;
$topbar_layout = $topbar_social != '' ? noo_get_option( 'noo_top_bar_social_layout', 'content_left' ) : noo_get_option( 'noo_top_bar_layout', 'content_left' );

$topbar_email = $topbar_content == 'html' ? esc_html( noo_get_option( 'noo_top_bar_email', '' ) ) : '';

$current_url = noo_current_url();

$topbar_show_register = $topbar_content == 'html' ? noo_get_option( 'noo_top_bar_show_register', true ) : false;
$topbar_register_page = !empty( $topbar_show_register ) ? noo_get_option( 'noo_top_bar_register_page', '' ) : '';
$topbar_register_page = !empty( $topbar_register_page ) ? get_permalink( $topbar_register_page ) : '';
$topbar_register_page = !empty( $topbar_register_page ) ? $topbar_register_page : wp_registration_url();

$topbar_show_login = $topbar_content == 'html' ? noo_get_option( 'noo_top_bar_show_login', true ) : false;
$topbar_login_page = !empty( $topbar_show_login ) ? noo_get_option( 'noo_top_bar_login_page', '' ) : '';
$topbar_login_page = !empty( $topbar_login_page ) ? get_permalink( $topbar_login_page ) : '';
$topbar_login_page = !empty( $topbar_login_page ) ? esc_url( add_query_arg('redirect_to', urlencode($current_url), $topbar_login_page) ) : wp_login_url($current_url);

$topbar_logout_url = wp_logout_url( $current_url );
?>

<div class="noo-topbar">
	<div class="topbar-inner <?php echo $topbar_layout; ?> container-boxed max">
		<?php if ( $topbar_social != '' ) : ?>
			<?php noo_social_icons( 'topbar' ); ?>				
		<?php endif; ?>
		<?php if ( $topbar_content == 'menu' ) : // Top Menu ?>
			<div class="topbar-content">
			<?php if ( has_nav_menu( 'top-menu' ) ) :
				wp_nav_menu( array(
					'theme_location'    => 'top-menu',
					'container'         => false,
					'depth'				=> 1,
					'menu_class'        => 'noo-menu'
					) );
				else :
					echo '<ul class="noo-menu"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __( 'Assign a menu', 'noo' ) . '</a></li></ul>';
				endif; ?>
			</div>
		<?php elseif( $topbar_content == 'html' ) : // HTML content ?>
			<p class="topbar-content"><?php echo noo_get_option( 'noo_top_bar_content', '' ); ?></p>
			<?php if( !empty($topbar_email) || $topbar_show_register || $topbar_show_login ) : ?>
			<ul class="topbar-content">
				<?php if( !empty($topbar_email) ) : ?>
				<li class="noo-li-icon"><a href="mailto:<?php echo $topbar_email; ?>"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo __('Email:', 'noo') . $topbar_email; ?></a></li>
				<?php endif; ?>
				<?php if( !empty($topbar_show_register) || !empty($topbar_show_login) ) : ?>
					<?php if( !is_user_logged_in() ) : ?>
						<?php if( !empty($topbar_show_register) ) : ?>
						<li class="noo-li-icon"><a href="<?php echo $topbar_register_page; ?>"><i class="fa fa-key"></i>&nbsp;<?php echo __('Register', 'noo'); ?></a></li>
						<?php endif; ?>
						<?php if( !empty($topbar_show_login) ) : ?>
						<li class="noo-li-icon"><a href="<?php echo $topbar_login_page; ?>"><i class="fa fa-sign-in"></i>&nbsp;<?php echo __('Login', 'noo'); ?></a></li>
						<?php endif; ?>
					<?php else : ?>
						<li class="noo-li-icon"><a href="<?php echo $topbar_logout_url; ?>"><i class="fa fa-sign-out"></i>&nbsp;<?php echo __('Logout', 'noo'); ?></a></li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>
				
			<?php endif; ?>
		<?php endif; ?>
	</div> <!-- /.topbar-inner -->
</div> <!-- /.noo-topbar -->

<?php endif; ?>
