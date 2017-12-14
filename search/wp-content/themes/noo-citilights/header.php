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
<?php if ( defined('WPSEO_VERSION') ) : ?>
<title><?php wp_title(''); ?></title>
<?php else : ?>
<title><?php wp_title(' - ', true, 'left'); ?></title>
<?php endif; ?>
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
<!-- Google Code for Download property info Conversion Page -->
<script type="text/javascript">
/ <![CDATA[ /
var google_conversion_id = 924196194;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "QP-RCOf_9mQQ4rrYuAM";
var google_remarketing_only = false;
/ ]]> /
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/924196194/?label=QP-RCOf_9mQQ4rrYuAM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>