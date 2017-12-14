<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package marketingresults
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
<!-- This needs to go as high in the head tags as possible -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-52DH64');</script>
<!-- End Google Tag Manager -->
<!-- This needs to go as high in the body tags as possible -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-52DH64"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
</head>

<body <?php body_class(); ?>>
<!--div class="top-bar dark-bg hidden-xs">
	<div class="container">
		<div class="row valign">
			<div class="col-sm-8">
				<ul class="list-inline">
					<li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php the_field('site_timing','options'); ?></a></li>
					<li><a href="#"><i class="fa fa-phone mr-1" aria-hidden="true"></i> <?php the_field('site_phone','options'); ?></a></li>
					<li><a href="#"><i class="fa fa-envelope mr-1" aria-hidden="true"></i> <?php the_field('site_email','options'); ?></a></li>
				</ul>
			</div>
			<div class="col-sm-4 text-right pr-0">
				<a href="<?php the_field('search_new_home','options'); ?>" type="button" class="btn btn-default btn-danger move"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>Search New Homes</a>
			</div>
		</div>
	</div>
</div-->
<header class="site-header" data-spy="affix" data-offset-top="55" >
	<div id="header-wrap" class="clearfix white-bg">
		<div id="menu" class="navigation-wrap">
		  
			<div id="navbar" class="navbar-collapse" aria-expanded="false" style="height: 0px;">
				<?php $defaults = array(
						'menu'  =>'Main menu',
						'container' => 'ul',
						'menu_class'=>'nav navbar-nav',						
					);
					?>
					<?php wp_nav_menu( $defaults ); ?>
			  </div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-4 col-xs-3"> 
					<div id="menu-toggle">
						<div id="menu-icon">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>

							<div class="text-menu">
								MENU 
							</div> 
						</div>


					</div>

				</div>

				<div class="col-sm-4 col-xs-5 text-center">
					<div class="logo">
						<a href="<?php echo site_url(); ?>"><img class="img-responsive" src="<?php echo get_template_directory_uri();?>/images/logo.png" alt=""></a>
					</div> 
				</div>

				<div class="col-sm-4 col-xs-4">
					<div class="controls-icon">
						<ul class="list-inline pull-right">

							<li><a href="tel:<?php the_field('site_phone','options'); ?>" title="Phone"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
							<li><a href="mailto:<?php the_field('site_email','options'); ?>" title="Email"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
							<li><a href="<?php the_field('home_link','options'); ?>" target="_blank" title="Search New Homes"><i class="fa fa-home" aria-hidden="true"></i></a></li>

						</ul>
					</div>
				</div>
			</div>

		</div> 
	</div>            
</header>

<?php 
	get_template_part( 'template-parts/header', 'banner' );
?>

<script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110992315-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-110992315-1');
</script>

