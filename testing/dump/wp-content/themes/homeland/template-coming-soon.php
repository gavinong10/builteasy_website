<?php
/*
	Template Name: Coming Soon
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

<body class="coming-soon-page">
	<!--COMING SOON-->
	<section class="coming-soon">

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
			<h2><span><?php _e( 'Our Site is Almost Ready', 'codeex_theme_name' ); ?></span></h2>
			<h5>
				<?php 
					_e( 'Hello, It\'s nice to meet you. We\'ll be back soon once we\'re done building this site.', 'codeex_theme_name' ); 
				?>
			</h5>
			<h3><?php _e( 'Time left until launching', 'codeex_theme_name' ); ?></h3>
			<div id="defaultCountdown"></div>
			<?php homeland_social_share_header(); ?>
		</div>

	</section>

<?php wp_footer(); ?>

</body>
</html>