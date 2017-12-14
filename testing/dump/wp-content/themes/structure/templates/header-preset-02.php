<div class="header-wrapper">
	<?php
	global $thememove_header_top, $wc_thememove_header_top;
	if ( ( get_theme_mod( 'header_top_enable', header_top_enable ) || $thememove_header_top == 'enable' || $wc_thememove_header_top == 'enable' ) && $thememove_header_top != 'disable' && $wc_thememove_header_top != 'disable' ) {
		?>
		<div class="top-area">
			<div class="container">
				<div class="row">
					<?php if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) ) { ?>
						<div class="col-md-7 hidden-xs hidden-sm">
							<?php dynamic_sidebar( 'top-area' ); ?>
						</div>
						<div class="col-md-2 col-xs-5">
							<?php dynamic_sidebar( 'lang-area' ); ?>
						</div>
						<div class="col-md-3 col-xs-7 social">
							<?php wp_nav_menu( array( 'theme_location' => 'social', 'fallback_cb' => false ) ); ?>
						</div>
					<?php } else { ?>
						<div class="col-md-9 hidden-xs hidden-sm">
							<?php dynamic_sidebar( 'top-area' ); ?>
						</div>
						<div class="col-md-3 col-xs-7 social">
							<?php wp_nav_menu( array( 'theme_location' => 'social', 'fallback_cb' => false ) ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<header <?php header_class(); ?> role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="site-branding">
						<?php
						global $thememove_custom_logo;
						if ( $thememove_custom_logo ) {
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img src="<?php echo $thememove_custom_logo; ?>" alt="logo"/>
							</a>
						<?php } else { ?>
							<?php if ( get_theme_mod( 'normal_logo_image', normal_logo_image ) ) { ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<img src="<?php echo get_theme_mod( 'normal_logo_image', normal_logo_image ); ?>"
									     alt="logo"/>
								</a>
							<?php } else { ?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
								                          rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<?php if ( get_theme_mod( 'header_search_enable', header_search_enable ) || get_theme_mod( 'header_cart_enable', header_cart_enable ) ) { ?>
					<?php $class = 'col-md-8 hidden-xs hidden-sm header-right'; ?>
				<?php } else { ?>
					<?php $class = 'col-md-9 hidden-xs hidden-sm header-right'; ?>
				<?php } ?>
				<div class="<?php echo esc_attr( $class ); ?>">
					<?php dynamic_sidebar( 'header-right' ); ?>
				</div>
				<?php if ( get_theme_mod( 'header_search_enable', header_search_enable ) || get_theme_mod( 'header_cart_enable', header_cart_enable ) ) { ?>
					<div class="col-md-1 col-sm-1 hidden-xs">
						<?php if ( get_theme_mod( 'header_search_enable', header_search_enable ) ) { ?>
							<div class="search-box hidden-xs hidden-sm">
								<?php get_search_form(); ?>
								<i class="fa fa-search"></i>
							</div>
						<?php } ?>
						<?php if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'header_cart_enable', header_cart_enable ) ) { ?>
							<div class="mini-cart">
								<?php echo thememove__minicart(); ?>
								<div class="widget_shopping_cart_content"></div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</header>
	<div class="nav">
		<div class="container">
			<span class="menu-link"><i class="fa fa-navicon"></i></span>
			<nav class="navigation" role="navigation">
				<?php if ( class_exists( 'TM_Walker_Nav_Menu' ) && has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'menu_id'         => 'primary-menu',
						'container_class' => 'primary-menu',
						'walker'          => new TM_Walker_Nav_Menu
					) );
				} else {
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'menu_id'         => 'primary-menu',
						'container_class' => 'primary-menu',
						'before'          => '<i class="sub-menu-toggle fa fa-chevron-down"></i>'
					) );
				} ?>
			</nav>
			<!-- .site-navigation -->
		</div>
	</div>
</div>
