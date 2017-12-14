<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- Favicon-->
<?php 
	$favicon = noo_get_image_option('noo_custom_favicon', '');
	if ($favicon != ''): ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" />
<?php
endif; ?>

<?php 
$status_url= $_SERVER["REQUEST_URI"];
$exp_url= explode('/', $status_url);
//print_r($exp_url);

?>
<?php if( defined('WPSEO_VERSION') ){ ?>
<title><?php wp_title(''); ?></title>
<?php }else if($exp_url[1]=='listings'){ ?>
<title><?php bloginfo('name'); ?> | New Home</title>
<?php }else if($exp_url[1]=='status'){ ?>
<title><?php bloginfo('name'); ?> | <?php echo ucwords($exp_url[2]) ?></title>
<?php }else{ ?>
<title><?php bloginfo('name'); ?> | <?php the_title(); ?></title>
<?php } ?>

<?php if (is_singular( 'noo_property' ) && get_post_type()== 'noo_property') :
    $image_id       =   get_post_thumbnail_id();
    $social_share_img = wp_get_attachment_image_src( $image_id, 'full');
    if( !empty( $social_share_img ) && isset($social_share_img[0]) ) : 
    ?>
    <meta property="og:image" content="<?php echo $social_share_img[0]; ?>"/>
    <meta property="og:image:secure_url" content="<?php echo $social_share_img[0]; ?>" />
<?php endif;
endif;
?>
<!--[if lt IE 9]>
<script src="<?php echo NOO_FRAMEWORK_URI . '/vendor/respond.min.js'; ?>"></script>
<![endif]-->
<?php wp_head(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70446851-1', 'auto');
  ga('send', 'pageview');

</script>

</head>

<body <?php body_class(); ?>>

	<div class="site">

	<?php
	$rev_slider_pos = home_slider_position(); ?>
	<?php
		if($rev_slider_pos == 'above') {
			noo_get_layout( 'slider-revolution');
		}
	?>
	<?php noo_get_layout('topbar'); ?>
	<header class="noo-header <?php noo_header_class(); ?>" role="banner">
		<?php noo_get_layout('navbar'); ?>
		
	</header>

	<?php
		if($rev_slider_pos == 'below') {
			noo_get_layout( 'slider-revolution');
		}
	?>
