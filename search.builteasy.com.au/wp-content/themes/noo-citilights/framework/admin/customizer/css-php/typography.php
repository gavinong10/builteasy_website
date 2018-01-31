<?php
// Variables
$default_font_family = noo_default_font_family();
$default_font_size = noo_default_font_size();

$noo_typo_use_custom_fonts = noo_get_option( 'noo_typo_use_custom_fonts', false );

$noo_typo_headings_font = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_headings_font', noo_default_headings_font_family() ) : noo_default_headings_font_family();
$noo_typo_headings_font_style = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_headings_font_style', 'normal' ) : 'normal';
$noo_typo_headings_font_weight = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_headings_font_weight', '300' ) : '300';
$noo_typo_headings_font_subset = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_headings_font_subset', 'latin' ) : 'latin';
$noo_typo_headings_uppercase = noo_get_option( 'noo_typo_headings_uppercase', false );

$noo_typo_body_font_size = noo_get_option( 'noo_typo_body_font_size', $default_font_size );
$noo_typo_body_font = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_body_font', $default_font_family ) : $default_font_family; 
$noo_typo_body_font_style = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_body_font_style', 'normal' ) : 'normal';
$noo_typo_body_font_weight = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_body_font_weight', noo_default_font_weight() ) : noo_default_font_weight();
$noo_typo_body_font_subset = $noo_typo_use_custom_fonts ? noo_get_option( 'noo_typo_body_font_subset', 'latin' ) : 'latin';

// Font size computed
$font_size_base       = $noo_typo_body_font_size;
$font_size_large      = ceil( $font_size_base * 1.25 );
$font_size_small      = ceil(($font_size_base * 0.85));

$font_size_h6         = $font_size_base;
$font_size_h5         = ceil( $font_size_base * 1.28571 );
$font_size_h4         = ceil( $font_size_base * 1.4285 );
$font_size_h3         = ceil( $font_size_base * 1.7 );
$font_size_h2         = floor( $font_size_base * 2.15 );
$font_size_h1         = floor( $font_size_base * 2.6 );

$line_height_computed = floor(($font_size_base * 1.5));

?>

/* Body style */
/* ===================== */
body {
	font-family: "<?php echo $noo_typo_body_font; ?>", "Open Sans", sans-serif;
	font-size: <?php echo $font_size_base . 'px'; ?>;
	font-style: <?php echo $noo_typo_body_font_style; ?>;
	font-weight: <?php echo $noo_typo_body_font_weight; ?>;
}

/* Headings */
/* ====================== */
h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
	font-family: "<?php echo $noo_typo_headings_font; ?>", "Open Sans", sans-serif;
	font-style: <?php echo $noo_typo_headings_font_style; ?>;
	font-weight: <?php echo $noo_typo_headings_font_weight; ?>;	
	<?php if ( !empty( $noo_typo_headings_uppercase ) ) : ?>
	text-transform: uppercase;
	<?php endif; ?>
}

/* Font Size */
/* ====================== */
/* ====================== */

/* Heading Font size */
h1, .h1 { font-size: <?php echo $font_size_h1 . 'px'; ?>; }
h2, .h2 { font-size: <?php echo $font_size_h2 . 'px'; ?>; }
h3, .h3 { font-size: <?php echo $font_size_h3 . 'px'; ?>; }
h4, .h4 { font-size: <?php echo $font_size_h4 . 'px'; ?>; }
h5, .h5 { font-size: <?php echo $font_size_h5 . 'px'; ?>; }
h6, .h6 { font-size: <?php echo $font_size_h6 . 'px'; ?>; }

/* Scaffolding */
/* ====================== */
select {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}

/* Bootstrap */
.btn,
.dropdown-menu,
.input-group-addon,
.popover-title
output,
.form-control {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}
legend,
.close {
	font-size: <?php echo floor($font_size_base * 1.5) . 'px'; ?>;
}
.lead {
	font-size: <?php echo floor($font_size_base * 1.15) . 'px'; ?>;
}
@media (min-width: 768px) {
	.lead {
		font-size: <?php echo floor($font_size_base * 1.5) . 'px'; ?>;
	}
}
pre {
	padding: <?php echo (($line_height_computed - 1) / 2) . 'px'; ?>;
	margin: 0 0 <?php echo ($line_height_computed / 2) . 'px'; ?>;
	font-size: <?php echo ($font_size_base - 1) . 'px'; ?>;
}
.panel-title {
	font-size: <?php echo ceil($font_size_base * 1.125) . 'px'; ?>;
}

@media screen and (min-width: 768px) {
	.jumbotron h1, .h1 {
		font-size: <?php echo ceil($font_size_base * 4.5) . 'px'; ?>;
	}
}

.badge,
.btn-sm,
.btn-xs,
.dropdown-header,
.input-sm,
.input-group-addon.input-sm,
.pagination-sm,
.tooltip {
	<?php echo $font_size_small . 'px'; ?>;
}

.btn-lg,
.input-lg,
.input-group-addon.input-lg,
 pagination-lg {
	font-size: <?php echo $font_size_large . 'px'; ?>;
}

<?php if (NOO_SUPPORT_PORTFOLIO) : ?>
/* Portfolio */
/* ====================== */
.masonry-filters ul li a,
.masonry-style-elevated .masonry-portfolio.no-gap .masonry-container .content-wrap .content-title-portfolio,
.masonry-style-elevated .masonry-portfolio .masonry-container .content-wrap .content-category-portfolio a,
.masonry-style-vibrant .masonry-portfolio .masonry-container .content-wrap .content-title-portfolio a,
.masonry-style-vibrant .masonry-portfolio .masonry-container .content-wrap .content-category-portfolio a,
	font-size: <?php echo $font_size_base . 'px'; ?>;
}
.masonry-style-elevated .masonry-portfolio .masonry-container .content-wrap .content-title-portfolio {
	font-size: <?php echo $font_size_large . 'px'; ?>;
}
<?php endif; ?>

/* WordPress Element */
/* ====================== */
.content-link,
.content-cite,
.comment-form-author input,
.comment-form-email input,
.comment-form-url input,
.comment-form-comment textarea,
.pagination .page-numbers,
.widget ul li a, .widget ol li a,
.widget.widget_recent_entries li a,
.widget.widget_recent_comments li,
.widget.widget_recent_entries li,
.widget.widget_rss li,
.widget_calendar #wp-calendar,
.widget_calendar #wp-calendar caption,
.widget_calendar #wp-calendar > thead > tr > th {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}

.breadcrumb, .breadcrumb > li,
.ispostauthor,
.comment-meta,
.comment-reply-link,
.comment-notes,
.logged-in-as,
.comment-form-comment label,
.form-submit input[type="submit"],
.wpcf7-not-valid-tip,
div.wpcf7-response-output,
.post-password-form input[type="submit"],
.content-meta,
.noo-topbar .topbar-inner .topbar-content,
.colophon.site-info .footer-more .noo-bottom-bar-content.
.masonry-portfolio .content-date-portfolio,
.label,
.widget.widget_recent_entries li .post-date,
.recent-tweets .twitter_time {
	font-size: <?php echo $font_size_small . 'px'; ?>;
}

.content-footer .content-tags,
.widget_tag_cloud .tagcloud a,
.widget_product_tag_cloud .tagcloud a {
	font-size: <?php echo $font_size_small . 'px !important'; ?>;
}

.noo-member .member-avatar .member-social a,
.noo-member .member-info .team-meta,
.wigetized .widget .widget-title,
.masonry-style-elevated .masonry-portfolio .masonry-container .content-wrap .content-title-portfolio,
.content-title,
.content-sub-title,
.read-more,
.content-share > a,
.attribute-list,
.panel-title,
.nav-tabs > li > a,
.widget.widget_rss li a {
	font-size: <?php echo $font_size_large . 'px'; ?>;
}

.breadcrumb {
	margin-bottom: <?php echo $line_height_computed . 'px'; ?>;
}

.noo-page-heading {
	padding: <?php echo ( $line_height_computed / 2 ) . 'px'; ?> 0;
}
.pagination {
	margin: <?php echo ( $line_height_computed ) . 'px'; ?> auto;
}
.content-featured {
	margin-bottom: <?php echo ( $line_height_computed ) . 'px'; ?>;
}
.content-footer .content-tags {
	margin-top: <?php echo ( $line_height_computed ) . 'px'; ?>;
	margin-bottom: <?php echo ( $line_height_computed / 2 ) . 'px'; ?>;
}

<?php if( NOO_WOOCOMMERCE_EXIST ) : ?>
/* WooCommerce */
/* ====================== */
.woocommerce span.onsale,
.woocommerce .cart .button,
.woocommerce .cart input.button,
.woocommerce p.stars a,
.navbar .navbar-header .mobile-minicart-icon span {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}

.woocommerce ul.products li.product .price del,
.noo-menu-item-cart .noo-minicart .minicart-header,
.noo-menu-item-cart .noo-minicart .minicart-body .cart-product .cart-product-details,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-total,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-actions .button {
	font-size: <?php echo $font_size_small . 'px'; ?>;
}

.woocommerce div.product .woocommerce-tabs .nav-tabs > li > a,
.woocommerce ul.products li.product h3 {
	font-size: <?php echo $font_size_large . 'px'; ?>;
}

.woocommerce .woocommerce-product-rating {
	margin-top: <?php echo ( $line_height_computed ) . 'px'; ?>;
	margin-bottom: <?php echo ( $line_height_computed / 2 ) . 'px'; ?>;
}
<?php endif; ?>

<?php if( defined('DSIDXPRESS_PLUGIN_VERSION') ) : ?>
/* IDX plugin */
.dsidx-details blockquote,
.dsidx-shortcode-item blockquote {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}
<?php endif; ?>

/* Shortcode */
/* ====================== */
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-header .pricing-value,
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-info ul li,
.noo-pricing-table.ascending .noo-pricing-column .pricing-info ul li,
.noo-member .member-info .team-meta small,
.panel-body,
.tab-content > .tab-pane,
.alert,
.noo-progress-bar.lean-bars .progress .progress-bar .progress_title,
.noo-progress-bar.lean-bars .progress .progress-bar .progress_label,
.noo-progress-bar.thick-bars .progress .progress-bar {
	font-size: <?php echo $font_size_base . 'px'; ?>;
}

.noo-pricing-table.ascending .noo-pricing-column .pricing-header .pricing-title,
.noo-pricing-table.ascending .noo-pricing-column .pricing-header .pricing-value .noo-price {
	font-size: <?php echo ceil($font_size_base * 1.7) . 'px'; ?>;
}