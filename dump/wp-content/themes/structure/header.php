<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package ThemeMove
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_theme_mod( 'favicon_image', favicon_image ); ?>">
	<link rel="apple-touch-icon" href="<?php echo get_theme_mod( 'apple_touch_icon', apple_touch_icon ); ?>"/>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site animsition">
<?php
global $thememove_header_preset;
if ( $thememove_header_preset == 'header-preset-01' ) {
	include get_template_directory() . '/templates/header-preset-01.php';
} elseif ( $thememove_header_preset == 'header-preset-02' ) {
	include get_template_directory() . '/templates/header-preset-02.php';
} elseif ( $thememove_header_preset == 'header-preset-03' ) {
	include get_template_directory() . '/templates/header-preset-03.php';
} elseif ( $thememove_header_preset == 'header-preset-04' ) {
	include get_template_directory() . '/templates/header-preset-04.php';
} elseif ( $thememove_header_preset == 'header-preset-05' ) {
	include get_template_directory() . '/templates/header-preset-05.php';
} elseif ( $thememove_header_preset == 'header-preset-06' ) {
	include get_template_directory() . '/templates/header-preset-06.php';
} elseif ( $thememove_header_preset == 'header-preset-07' ) {
	include get_template_directory() . '/templates/header-preset-07.php';
} elseif ( $thememove_header_preset == 'header-preset-08' ) {
	include get_template_directory() . '/templates/header-preset-08.php';
} elseif ( $thememove_header_preset == '' || $thememove_header_preset == 'default' ) {
	include get_template_directory() . '/templates/' . get_theme_mod( 'header_preset', header_preset ) . '.php';
}
?>