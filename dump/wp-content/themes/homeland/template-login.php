<?php
/*
	Template Name: Login
*/
?>

<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />  

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<?php 
	$homeland_favicon = esc_attr( get_option('homeland_favicon') );
	$homeland_logo = esc_attr( get_option('homeland_logo') );

	if(empty( $homeland_favicon )) : ?>
		<link rel="shortcut icon" href="http://themecss.com/img/favicon.ico" /><?php
	else : ?><link rel="shortcut icon" href="<?php echo $homeland_favicon; ?>" /><?php
	endif;
?>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body class="login-page">

	<!--LOGIN FORM-->
	<section class="coming-soon login">

		<div class="inside clear">
			<h1>
				<a href="<?php echo esc_url( home_url() ); ?>">
					<?php 
						if(empty( $homeland_logo )) : ?>
							<img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="<?php esc_attr( bloginfo('name') ); ?>" title="<?php esc_attr( bloginfo('name') ); ?>" /><?php
						else : ?>
							<img src="<?php echo $homeland_logo; ?>" alt="<?php esc_attr( bloginfo('name') ); ?>" title="<?php esc_attr( bloginfo('name') ); ?>" /><?php
						endif;
					?>
				</a>
			</h1>
			<?php
				$homeland_ptitle = esc_attr( get_post_meta( @$post->ID, "homeland_ptitle", true ) );
				$homeland_subtitle = esc_attr( get_post_meta( @$post->ID, "homeland_subtitle", true ) );

				if ( ! is_user_logged_in() ) :
					if(!empty($homeland_ptitle)) : 
						echo '<h2><span>' . $homeland_ptitle . '</span></h2>';
					else : the_title('<h2><span>', '</span></h2>'); endif; 

					if(!empty($homeland_subtitle)) : 
						echo '<h3>' . stripslashes ( $homeland_subtitle ) . '</h3>';
					else : ?>
						<h3><?php _e( 'Already a member? Please login here', 'codeex_theme_name' ); ?></h3><?php
					endif;

					$args = array(
						'redirect' => admin_url(), 
						'form_id' => 'homeland-loginform',
						'label_username' => __( 'Username', 'codeex_theme_name' ),
						'label_password' => __( 'Password', 'codeex_theme_name' ),
						'label_remember' => __( 'Remember Me', 'codeex_theme_name' ),
						'label_log_in' => __( 'Log In', 'codeex_theme_name' ),
						'remember' => true
					);

					wp_login_form( $args );

					?>
						<div class="login-links">
							<a href="<?php echo wp_registration_url(); ?>">
								<?php _e( 'Register', 'codeex_theme_name' ); ?>
							</a> |
							<a href="<?php echo wp_lostpassword_url(); ?>" title="Lost Password">
								<?php _e( 'Lost Password', 'codeex_theme_name' ); ?>
							</a>
						</div>
					<?php
				else :
					$homeland_current_user = wp_get_current_user();
					?>
						<h2>
							<span>
								<?php 
									_e( 'Howdy', 'codeex_theme_name' );
									echo ",&nbsp;" . $homeland_current_user->user_firstname; 
								?>
							</span>
						</h2>
						<div class="login-actions">
							<a href="<?php echo get_edit_user_link(); ?>" target="_blank">
								<?php _e('View Profile', 'codeex_theme_name'); ?>
							</a>
							<a href="<?php echo get_author_posts_url( $homeland_current_user->ID ); ?>" target="_blank">
    							<?php _e('View Properties', 'codeex_theme_name'); ?>
    						</a>
							<a href="<?php echo wp_logout_url( home_url() ); ?>">
								<?php _e('Logout', 'codeex_theme_name'); ?>
							</a>
						</ul>
					<?php
				endif;
			?>
		</div>

	</section>

<?php wp_footer(); ?>

</body>
</html>