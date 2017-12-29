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
</head>

<body <?php body_class(); ?>>
<div class="top-bar dark-bg hidden-xs">
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
</div>
<header class="site-header" data-spy="affix" data-offset-top="55" >
	<div id="header-wrap" class="clearfix white-bg">
		<div class="container p-0">
			<div class="logo">
				<a href="#"><img class="img-responsive" src="<?php echo get_template_directory_uri();?>/images/logo.png" alt=""></a>
			</div>  
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navigation-wrap">

				<div id="navbar" class="navbar-collapse  collapse" aria-expanded="false" style="height: 0px;">
					<?php $defaults = array(
						'menu'  =>'Main menu',
						'container' => 'ul',
						'menu_class'=>'nav navbar-nav',						
					);
					?>
					<?php wp_nav_menu( $defaults ); ?>
					<!--ul class="nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#">About</a></li>
						<li><a href="#">Problems We Solve</a></li>
						<li class="dropdown"><a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="#">Building in Brisbane <span class="caret"></span></a>


						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="#">FAQ</a></li>
						<li><a href="#">Another Action</a></li>
						<li><a href="#">Other Industries</a></li>

						</ul>

						</li>
						<li><a href="#"> Contact</a></li>
					</ul-->
				</div>
			</div>
		</div> 
	</div>             
</header>
<?php 
	get_template_part( 'template-parts/header', 'banner' );
?>
