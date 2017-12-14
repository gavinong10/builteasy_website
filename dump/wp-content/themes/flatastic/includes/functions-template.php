<?php

/* ---------------------------------------------------------------------- */
/*	Template: Share
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_share_product_this' )) {
	function mad_share_product_this() {
		wc_get_template( "share-product.php" );
	}
}

if (!function_exists( 'mad_share_post_this' )) {
	function mad_share_post_this() {
		mad_get_template( "/content/share.php" );
	}
}

if (!function_exists( 'mad_share_portfolio_this' )) {
	function mad_share_portfolio_this() {
		mad_get_template( "/content/share-portfolio.php" );
	}
}


/* ---------------------------------------------------------------------- */
/*	Template: Header Top Part
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_header_default_top_part' )) {
	function mad_header_default_top_part() {
		mad_get_template( '/header/header-default-top-part.php' );
	}
}

if (!function_exists( 'mad_header_type_2_top_part' )) {
	function mad_header_type_2_top_part() {
		mad_get_template( '/header/header-type-2-top-part.php' );
	}
}

if (!function_exists( 'mad_header_type_3_top_part' )) {
	function mad_header_type_3_top_part() {
		mad_get_template( '/header/header-type-3-top-part.php' );
	}
}

if (!function_exists( 'mad_header_type_4_top_part' )) {
	function mad_header_type_4_top_part() {
		mad_get_template( '/header/header-type-4-top-part.php' );
	}
}

if (!function_exists( 'mad_header_type_5_top_part' )) {
	function mad_header_type_5_top_part() {
		mad_get_template( '/header/header-type-5-top-part.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Header In Prepend
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_header_in_prepend_type_4' )) {
	function mad_header_in_prepend_type_4() {
		mad_get_template( '/header/header_in_prepend_type_4.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Header Logo
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_header_logo' )) {
	function mad_header_logo() {
		mad_get_template( '/header/logo.php' );
	}
}


if (!function_exists( 'mad_header_logo_type_2' )) {
	function mad_header_logo_type_2() {
		mad_get_template( '/header/logo-type-2.php' );
	}
}

if (!function_exists( 'mad_header_logo_type_4' )) {
	function mad_header_logo_type_4() {
		mad_get_template( '/header/logo-type-4.php' );
	}
}

if (!function_exists( 'mad_header_logo_type_6' )) {
	function mad_header_logo_type_6() {
		mad_get_template( '/header/logo-type-6.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Searchform
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_searchform_default' )) {
	function mad_searchform_default() {
		mad_get_template( '/header/searchform-default.php' );
	}
}

if (!function_exists( 'mad_searchform_type_2_4' )) {
	function mad_searchform_type_2_4() {
		mad_get_template( '/header/searchform-type-2.php' );
	}
}

if (!function_exists( 'mad_searchform_type_3' )) {
	function mad_searchform_type_3() {
		mad_get_template( '/header/searchform-type-3.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Navigation
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_navigation_default' )) {
	function mad_navigation_default() {
		mad_get_template( '/header/navigation-default.php' );
	}
}

if (!function_exists( 'mad_navigation_type_2' )) {
	function mad_navigation_type_2() {
		mad_get_template( '/header/navigation-type-2.php' );
	}
}

if (!function_exists( 'mad_navigation_type_3' )) {
	function mad_navigation_type_3() {
		mad_get_template( '/header/navigation-type-3.php' );
	}
}

if (!function_exists( 'mad_navigation_type_4' )) {
	function mad_navigation_type_4() {
		mad_get_template( '/header/navigation-type-4.php' );
	}
}

if (!function_exists( 'mad_navigation_type_5' )) {
	function mad_navigation_type_5() {
		mad_get_template( '/header/navigation-type-5.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Header Breadcrumbs
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_header_after_breadcrumbs' )) {
	function mad_header_after_breadcrumbs() {
		mad_get_template( '/header/breadcrumbs.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Portfolio Slider
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_portfolio_flex_slider' )) {
	function mad_portfolio_flex_slider() {
		mad_get_template( '/sliders/portfolio-slider.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Header after page content
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_header_after_page_content' )) {
	function mad_header_after_page_content() {
		mad_get_template( '/content/header-after-page-content.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Footer In Top Part - Widgets
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_footer_in_top_part_widgets' )) {
	function mad_footer_in_top_part_widgets() {
		mad_get_template( '/footer/footer-in-top-part-widgets.php' );
	}
}

/* ---------------------------------------------------------------------- */
/*	Template: Footer In Bottom Part - Widgets
/* ---------------------------------------------------------------------- */

if (!function_exists( 'mad_footer_in_bottom_part' )) {
	function mad_footer_in_bottom_part() {
		mad_get_template( '/footer/footer-in-bottom-part.php' );
	}
}



