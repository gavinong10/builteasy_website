<?php
// Variables
$default_link_color = noo_default_primary_color();
$default_link_hover_color = darken( $default_link_color, '15%' ); // #0696C6

$default_header_bg_color = noo_default_header_bg();
$default_nav_font_size = '14';
$default_font_color = noo_default_text_color();
$default_font = noo_default_font_family();
$default_logo_font_color = noo_default_logo_color();

$noo_header_bg_color = noo_get_option( 'noo_header_bg_color', $default_header_bg_color );

$noo_site_link_color = noo_get_option( 'noo_site_link_color', $default_link_color );
$noo_site_link_hover_color = noo_get_option( 'noo_site_link_hover_color',  darken( $noo_site_link_color, '15%' ) );
$noo_typo_body_font_color = noo_get_option( 'noo_typo_use_custom_fonts_color', false ) ? noo_get_option( 'noo_typo_body_font_color', $default_font_color ) : $default_font_color;

$noo_header_nav_position = noo_get_option( 'noo_header_nav_position', 'fixed_top' );

$noo_header_nav_icon_search = noo_get_option( 'noo_header_nav_icon_search', true );
$noo_header_nav_icon_cart_woo   = noo_get_option( 'noo_header_nav_icon_cart_woo', false );

$noo_header_custom_nav_font = noo_get_option( 'noo_header_custom_nav_font', false );

$noo_header_nav_font = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_font', $default_font ) : $default_font;
$noo_header_nav_font_style = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_font_style', 'normal' ) : 'normal';
$noo_header_nav_font_weight = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_font_weight', '300' ) : '300';
$noo_header_nav_font_subset = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_font_subset', 'latin' ) : 'latin';
$noo_header_nav_font_size = noo_get_option( 'noo_header_nav_font_size', $default_nav_font_size );
$noo_header_nav_uppercase = noo_get_option( 'noo_header_nav_uppercase', false );

$noo_header_use_image_logo = noo_get_option( 'noo_header_use_image_logo', false );

$noo_header_logo_font = noo_get_option( 'noo_header_logo_font', noo_default_logo_font_family() );
$noo_header_logo_font_size = noo_get_option( 'noo_header_logo_font_size', '30' );
$noo_header_logo_font_color = noo_get_option( 'noo_header_logo_font_color', noo_default_logo_color() );
$noo_header_logo_font_style = noo_get_option( 'noo_header_logo_font_style', 'normal' );
$noo_header_logo_font_weight = noo_get_option( 'noo_header_logo_font_weight', '700' );
$noo_header_logo_font_subset = noo_get_option( 'noo_header_logo_font_subset', 'latin' );
$noo_header_logo_uppercase = noo_get_option( 'noo_header_logo_uppercase', true );

$noo_header_logo_image_height = noo_get_option( 'noo_header_logo_image_height', '30' );

$noo_header_nav_dropdown_custom = noo_get_option( 'noo_header_nav_dropdown_custom', false );
$noo_header_nav_dropdown_bg_color = $noo_header_nav_dropdown_custom ? noo_get_option( 'noo_header_nav_dropdown_bg_color', '#ffffff' ) : '#ffffff';
$noo_header_nav_dropdown_font_size = $noo_header_nav_dropdown_custom ? noo_get_option( 'noo_header_nav_dropdown_font_size', $default_nav_font_size ) : $default_nav_font_size;

$noo_header_nav_height = noo_get_option( 'noo_header_nav_height', '70' );
$noo_header_nav_link_spacing = noo_get_option( 'noo_header_nav_link_spacing', '12' );

$noo_header_nav_toggle_size = noo_get_option( 'noo_header_nav_toggle_size', '25' );
$noo_header_nav_toggle_margin_top = noo_get_option( 'noo_header_nav_toggle_margin_top', '32' );

$noo_header_side_nav_width = noo_get_option( 'noo_header_side_nav_width', '240' );
$noo_header_side_nav_alignment = noo_get_option( 'noo_header_side_nav_alignment', 'center' );
$noo_header_side_nav_link_height = noo_get_option( 'noo_header_side_nav_link_height', '40' );
$noo_header_side_logo_margin_top = noo_get_option( 'noo_header_side_logo_margin_top', '30' );

// Compute alignment
$toggle_margin_top = floor( ( $noo_header_nav_height - $noo_header_nav_toggle_size ) / 2 );

$side_nav_link_vertical_padding = floor( ( $noo_header_side_nav_link_height - $noo_header_nav_font_size ) / 2 );

?>

/* Header */
/* ====================== */
<?php if ( $noo_header_bg_color != '' ) : ?>
.noo-topbar,
.noo-header,
.navbar-fixed-left,
.navbar-fixed-right {
	background-color: <?php echo $noo_header_bg_color; ?>;
}
<?php endif; ?>

/* Navigation Typography */
/* ====================== */

/* NavBar: Typo */
.navbar-nav li > a {
	font-family: "<?php echo $noo_header_nav_font; ?>", "Open Sans", sans-serif;
	font-size: <?php echo $noo_header_nav_font_size . 'px'; ?>;
	font-style: <?php echo $noo_header_nav_font_style; ?>;
	font-weight: <?php echo $noo_header_nav_font_weight; ?>;
	<?php if ( !empty( $noo_header_nav_uppercase ) ) : ?>
	text-transform: uppercase;
	<?php endif; ?>
}

.navbar {
	height: <?php echo $noo_header_nav_height . 'px'; ?>;
}
.calling-info {
	height: <?php echo $noo_header_nav_height . 'px'; ?>;
}

<?php if ( $noo_header_nav_position == 'fixed_top' || $noo_header_nav_position == 'static_top' ) : ?>
@media (min-width: 992px) {
	.navbar-nav > li > a {
		padding-left: <?php echo $noo_header_nav_link_spacing . 'px'; ?>;
		padding-right: <?php echo $noo_header_nav_link_spacing . 'px'; ?>;
	}
	.navbar:not(.navbar-shrink) .navbar-nav > li > a {
		line-height: <?php echo $noo_header_nav_height . 'px'; ?>;
	}
}

.navbar-toggle {
	height: <?php echo $noo_header_nav_height . 'px'; ?>;
}
.navbar:not(.navbar-shrink) .navbar-brand {
	line-height: <?php echo $noo_header_nav_height . 'px'; ?>;
	height: <?php echo $noo_header_nav_height . 'px'; ?>;
}
<?php elseif ( $noo_header_nav_position == 'fixed_left' || $noo_header_nav_position == 'fixed_right' ) : ?>
@media (min-width: 992px) {
	.navbar-fixed-left,
	.navbar-fixed-right {
		width: <?php echo $noo_header_side_nav_width . 'px'; ?>;
	}
	<?php if( $noo_header_nav_position == 'fixed_left' ) : ?>
	body.navbar-fixed-left-layout {
		padding-left: <?php echo $noo_header_side_nav_width . 'px'; ?>;
	}
	<?php endif; ?>
	<?php if( $noo_header_nav_position == 'fixed_right' ) : ?>
	body.navbar-fixed-right-layout {
		padding-right: <?php echo $noo_header_side_nav_width . 'px'; ?>;
	}
	<?php endif; ?>

	.navbar-fixed-left .navbar-header,
	.navbar-fixed-right .navbar-header {
		margin-top: <?php echo $noo_header_side_logo_margin_top . 'px'; ?>;
	}

	.navbar-fixed-left .navbar-nav > li > a,
	.navbar-fixed-right .navbar-nav > li > a {
		line-height: <?php echo $noo_header_side_nav_link_height . 'px'; ?>;
	}
}
<?php endif; ?>

/* Navigation Icons: search, cart */
<?php if ( ! $noo_header_nav_icon_search ) : ?>
#nav-menu-item-search {
	display: none;
}
<?php endif; ?>
<?php if ( ! $noo_header_nav_icon_cart_woo ) : ?>
#nav-menu-item-cart {
	display: none;
}
@media (max-width: 767px) {
	.navbar .navbar-header .mobile-minicart-icon {
		display: none;
	}
}
<?php endif; ?>

/* Dropdown Style */
<?php if( $noo_header_nav_dropdown_custom ) : ?>
	.navbar-nav.sf-menu > li ul.sub-menu {
		background-color: <?php echo $noo_header_nav_dropdown_bg_color; ?>;
	}
	.navbar-nav ul.sub-menu li > a {
		font-size: <?php echo $noo_header_nav_dropdown_font_size . 'px'; ?>;
	}
<?php endif; ?>

/* Logo */
/* ====================== */
<?php if( ! $noo_header_use_image_logo ) : ?>
.navbar-brand {
	color: <?php echo $noo_header_logo_font_color; ?>;
	font-family: "<?php echo $noo_header_logo_font; ?>", "Open Sans", sans-serif;
	font-size: <?php echo $noo_header_logo_font_size . 'px'; ?>;
	font-style: <?php echo $noo_header_logo_font_style; ?>;
	font-weight: <?php echo $noo_header_logo_font_weight; ?>;
	<?php if ( !empty( $noo_header_logo_uppercase ) ) : ?>
	text-transform: uppercase;
	<?php endif; ?>
}
<?php else : ?>
.navbar-brand .noo-logo-img,
.navbar-brand .noo-logo-retina-img {
	height: <?php echo $noo_header_logo_image_height . 'px'; ?>;
}
<?php endif; ?>

/* Mobile Icons */
/* ====================== */
.navbar-toggle, .mobile-minicart-icon {
	font-size: <?php echo $noo_header_nav_toggle_size . 'px'; ?>;
}