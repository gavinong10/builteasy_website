<!DOCTYPE html>
<!--[if lte IE 8]>              <html class="ie8 no-js" <?php language_attributes(); ?>>     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" <?php language_attributes(); ?>>  <!--<![endif]-->
<head>

	<!-- Basic Page Needs
    ==================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Favicon
	==================================================== -->

	<?php $mad_favicon = mad_custom_get_option("favicon"); ?>

	<?php if ($mad_favicon): ?>
		<link rel="shortcut icon" href="<?php echo esc_url($mad_favicon); ?>">
	<?php endif; ?>

	<!-- Mobile Specific Metas
	==================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php wp_head(); ?>

</head>

<?php
	$mad_post_id = mad_post_id();
	$mad_color_scheme = mad_custom_get_option('color_scheme');
?>

<body data-spy="scroll" data-target="#navigation" <?php body_class($mad_color_scheme); ?>>

<?php do_action('body_prepend'); ?>

<!-- - - - - - - - - - - - - - Layout - - - - - - - - - - - - - - - - -->

<div class="<?php echo esc_attr(MAD_HELPER::page_layout()) ?>">

	<!-- - - - - - - - - - - - - - Header - - - - - - - - - - - - - - - - -->

	<header id="header" data-shrink="<?php echo (mad_custom_get_option('sticky_navigation')) ? 1 : 0; ?>" class="<?php echo esc_attr(MAD_HELPER::header_layout()) ?> <?php echo esc_attr(MAD_HELPER::header_full_width()); ?>">

		<?php
			/**
			 * header_in_before hook
			 *
			 * @hooked mad_header_type_3_top_part - 10
			 * @hooked mad_header_type_4_top_part - 10
			 */

			do_action('header_in_before');
		?>

		<div class="header-in">
			<div class="container">
				<div class="row">

					<?php
						/**
						 * header_in_prepend hook
						 *
						 * @hooked mad_header_logo - 10
						 * @hooked mad_header_logo_type_2 - 10
						 * @hooked mad_header_in_prepend_type_4 - 10
						 * @hooked mad_header_logo_type_4 - 10
						 */

						do_action('header_in_prepend');
					?>

					<?php
						/**
						 * header_in_append hook
						 *
						 * @hooked TemplatesHooks::header_cart_dropdown - 10
						 * @hooked mad_searchform_type_3 - 10
						 * @hooked TemplatesHooks::header_cart_dropdown_type_2 - 10
						 * @hooked TemplatesHooks::header_cart_dropdown_type_4 - 10
						 */

						do_action('header_in_append');
					?>

				</div><!--/ .row -->
			</div><!--/ .container-->
		</div><!--/ .header-in -->

		<?php
			/**
			 *
			 * header_in_after hook
			 *
			 * @hooked header_before_container  - 10
			 */

			do_action('header_in_after');
		?>

		<?php $mad_header_layout = rwmb_meta('mad_header_layout', '', $mad_post_id);
		if (empty($mad_header_layout)) {
			$mad_header_layout = mad_custom_get_option('header_layout');
		}
		?>

		<?php if ($mad_header_layout !== 'type-2'): ?>

			<div class="menu_wrap clearfix t_xs_align_c">

				<?php
					/**
					 * navigation_after hook
					 *
					 * @hooked mad_navigation_default - 10
					 * @hooked mad_navigation_type_4 - 10
					 */

					do_action('navigation_after');
				?>

			</div><!--/ .menu_wrap -->

		<?php endif; ?>

		<?php
			/**
			 * menu_wrap_after hook
			 *
			 * @hooked header_after_container - 10
			 */

			do_action('menu_wrap_after');
		?>

	</header><!--/ #header -->

	<!-- - - - - - - - - - - - - - / Header - - - - - - - - - - - - - - -->

	<?php
		/**
		 * header_after hook
		 *
		 * @hooked mad_header_after_breadcrumbs - 10
		 * @hooked mad_portfolio_flex_slider - 10
		 * @hooked mad_layer_slider - 11
		 * @hooked mad_header_after_page_content - 10
		 */

		do_action('header_after');
	?>

	<!-- - - - - - - - - - - - - Page Content - - - - - - - - - - - - - -->

	<?php $mad_sidebar_position = MAD_HELPER::template_layout_class('sidebar_position'); ?>

	<div id="content" class="page_content_offset <?php echo esc_attr($mad_sidebar_position); ?> ">

		<?php if ($mad_sidebar_position != 'no_sidebar'): ?>

			<div class="container">

				<div class="row">

					<?php if (mad_custom_get_option('position_sidebar_mobile') == 'top'): ?>

						<?php get_sidebar(); ?>

					<?php endif; ?>

					<main id="main" class="col-sm-8 col-md-9">

		<?php else: ?>

				<div class="container">

					<div class="row">

						<div class="col-sm-12">

		<?php endif; ?>