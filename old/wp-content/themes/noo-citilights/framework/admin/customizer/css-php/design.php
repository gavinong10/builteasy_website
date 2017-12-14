<?php
// Variables
$noo_site_skin = noo_get_option( 'noo_site_skin', 'light' );

$default_link_color = noo_default_primary_color();
$default_secondary_color = '#f0e797';

$noo_site_link_color = noo_get_option( 'noo_site_link_color', $default_link_color );
$noo_site_link_hover_color = noo_get_option( 'noo_site_link_hover_color',  darken( $noo_site_link_color, '15%' ) );

$noo_site_secondary_bg = noo_get_option( 'noo_site_secondary_color', $default_secondary_color );
$noo_site_secondary_color = desaturate(darken($noo_site_secondary_bg, '25%'), '25%');

$noo_site_link_color_lighten_10 = lighten( $noo_site_link_color, '10%' );
$noo_site_link_color_darken_5   = darken( $noo_site_link_color, '5%' );
$noo_site_link_color_darken_10   = darken( $noo_site_link_color, '10%' );
$noo_site_link_color_darken_15   = darken( $noo_site_link_color, '15%' );

$default_font_color = noo_default_text_color();
$default_headings_color =darken( $default_font_color, '10%' );

$noo_typo_use_custom_fonts_color = noo_get_option( 'noo_typo_use_custom_fonts_color', false );
$noo_typo_body_font_color = $noo_typo_use_custom_fonts_color ? noo_get_option( 'noo_typo_body_font_color', $default_font_color ) : $default_font_color;
$noo_typo_headings_font_color = $noo_typo_use_custom_fonts_color ? noo_get_option( 'noo_typo_headings_font_color', $default_headings_color ) : $default_headings_color; 

$noo_header_custom_nav_font = noo_get_option( 'noo_header_custom_nav_font', false );
$noo_header_nav_link_color = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_link_color', $noo_typo_body_font_color ) : $noo_typo_body_font_color;
$noo_header_nav_link_hover_color = $noo_header_custom_nav_font ? noo_get_option( 'noo_header_nav_link_hover_color', $noo_site_link_hover_color ) : $noo_site_link_hover_color;

$noo_header_nav_dropdown_custom = noo_get_option( 'noo_header_nav_dropdown_custom', false );
$noo_header_nav_dropdown_link_color = $noo_header_nav_dropdown_custom ? noo_get_option( 'noo_header_nav_dropdown_link_color', $noo_header_nav_link_color ) : $noo_header_nav_link_color;
$noo_header_nav_dropdown_link_hover_color = $noo_header_nav_dropdown_custom ? noo_get_option( 'noo_header_nav_dropdown_link_hover_color', $noo_header_nav_link_hover_color ) : $noo_header_nav_link_hover_color;

?>
body {
	color: <?php echo $noo_typo_body_font_color; ?>;
}

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6,
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a,
.h1 a, .h2 a, .h3 a, .h4 a, .h5 a, .h6 a {
	color: <?php echo $noo_typo_headings_font_color; ?>;
}

h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover,
.h1 a:hover, .h2 a:hover, .h3 a:hover, .h4 a:hover, .h5 a:hover, .h6 a:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

/* Global Link */
/* ====================== */
a,
.text-primary {
	color: <?php echo $noo_site_link_color; ?>;
}
a:hover,
a:focus,
a.text-primary:hover {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

.bg-primary {
	background-color: <?php echo $noo_site_link_color; ?>;
}
a.bg-primary:hover {
	background-color: <?php echo $noo_site_link_hover_color; ?>;
}
.bg-primary-overlay {
  background: <?php echo fade($noo_site_link_hover_color, '90%'); ?>;
}

/* Navigation Color */
/* ====================== */

/* Default menu style */
.noo-menu li > a {
	color: <?php echo $noo_header_nav_link_color; ?>;
}
.noo-menu li > a:hover,
.noo-menu li > a:active,
.noo-menu li.current-menu-item > a {
	color: <?php echo $noo_header_nav_link_hover_color; ?>;
}

/* NavBar: Link */
.navbar-nav li > a,
.navbar-nav ul.sub-menu li > a {
	color: <?php echo $noo_header_nav_link_color; ?>;
}

.navbar-nav li > a:hover,
.navbar-nav li > a:focus,
.navbar-nav li:hover > a,
.navbar-nav li.sfHover > a,
.navbar-nav li.current-menu-item > a,
/* Topbar Color */
.noo-topbar .topbar-inner .topbar-content a:hover,
.noo-topbar .topbar-inner .noo-social a i:hover {
	color: <?php echo $noo_header_nav_link_hover_color; ?>;
}

/* Dropdown Color */
<?php if( !$noo_header_nav_dropdown_custom ) : ?>
	.navbar-nav ul.sub-menu li > a:hover,
	.navbar-nav ul.sub-menu li > a:focus,
	.navbar-nav ul.sub-menu li:hover > a,
	.navbar-nav ul.sub-menu li.sfHover > a,
	.navbar-nav ul.sub-menu li.current-menu-item > a {
		color: <?php echo $noo_header_nav_link_hover_color; ?>;
	}
<?php else : ?>
	.navbar-nav ul.sub-menu li > a {
		color: <?php echo $noo_header_nav_dropdown_link_color; ?>;
	}
	.navbar-nav ul.sub-menu li > a:hover,
	.navbar-nav ul.sub-menu li > a:focus,
	.navbar-nav ul.sub-menu li:hover > a,
	.navbar-nav ul.sub-menu li.sfHover > a,
	.navbar-nav ul.sub-menu li.current-menu-item > a {
		color: <?php echo $noo_header_nav_dropdown_link_hover_color; ?>;
	}
<?php endif; ?>

/* Calling info */
.calling-info i {
	color: <?php echo $noo_site_link_color; ?>;
}

/* Border color */
@media (min-width: 992px) {
	.navbar-nav.sf-menu > li > ul.sub-menu {
		border-top-color: <?php echo $noo_site_link_color; ?>;
	}
	.navbar-nav.sf-menu > li > ul.sub-menu:before,
	.navbar-nav.sf-menu > li.align-center > ul.sub-menu:before,
	.navbar-nav.sf-menu > li.align-right > ul.sub-menu:before,
	.navbar-nav.sf-menu > li.align-left > ul.sub-menu:before,
	.navbar-nav.sf-menu > li.full-width.sfHover > a:before {
		border-bottom-color: <?php echo $noo_site_link_color; ?>;
	}
}

/* WordPress Element */
/* ====================== */

/* Comment */
.comment-author a,
.comment-pending {
	color: <?php echo $noo_typo_body_font_color; ?>;
}

.comment-author a:hover {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

.comment-reply-link:hover,
#respond .required {
	color: <?php echo $noo_site_link_color; ?>;
}

.form-submit input[type="submit"]:hover,
.form-submit input[type="submit"]:focus,
.post-password-form input[type="submit"]:hover,
.post-password-form input[type="submit"]:focus {
	background: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

/* Post */
.comment-reply-title span,
.content-meta > span > a:hover,
.hentry.format-quote a:hover,
.hentry.format-link a:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

.hentry.format-quote > .content-wrap,
.hentry.format-link > .content-wrap {
	background: <?php echo $noo_site_link_color; ?>;
}

.hentry.format-quote > .content-wrap:hover,
.hentry.format-link > .content-wrap:hover {
	background: <?php echo $noo_site_secondary_bg; ?>;
}

.content-footer .content-tags a:hover {
	background: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

.read-more,
.form-submit input[type="submit"] {
	color: <?php echo $noo_site_secondary_color; ?>;
	background: <?php echo $noo_site_secondary_bg; ?>;
	border-color: <?php echo $noo_site_secondary_bg; ?>;
}

.read-more:hover,
.read-more:focus,
.read-more:active,
.read-more.active,
.open > .dropdown-toggle.read-more {
	background: <?php echo $noo_site_link_color; ?>;
}

/* Pagination */
.pagination .page-numbers {
	border: 1px solid <?php echo $noo_site_link_color; ?>;
	color: <?php echo $noo_site_link_color; ?>;
}
.pagination .page-numbers.current {
	background: <?php echo $noo_site_link_color; ?>;
}
.pagination .page-numbers.dots {
	color: <?php echo $noo_typo_body_font_color; ?>;
}
.pagination a.page-numbers {
	color: <?php echo $noo_site_link_color; ?>;
}
.pagination a.page-numbers:hover {
	background: <?php echo $noo_site_link_color; ?>;
}

/* Widget */
.wigetized .widget ul li a:hover,
.wigetized .widget ol li a:hover {
	color: <?php echo $noo_site_secondary_bg; ?>;
}

.widget.widget_recent_entries li a {
	color: <?php echo $noo_typo_body_font_color; ?>;
}
.widget.widget_recent_entries li a:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

.widget_calendar #wp-calendar > tbody > tr > td > a {
	background: <?php echo $noo_site_link_color; ?>;
}

.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover {
	background: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

.recent-tweets .twitter_time,
.recent-tweets .twitter_time:hover {
	color: <?php echo $noo_site_link_color_lighten_10; ?>;
}

.jp-controls a:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

.jp-play-bar, .jp-volume-bar-value {
	background-color: <?php echo $noo_site_link_color; ?>;
}

/* Footer */
.noo-map.no-map .gsearch,
.colophon.wigetized:before {
	background: <?php echo fade($noo_site_link_color, '95%'); ?>;
}
.colophon.site-info .footer-more {
	background-color: <?php echo fade( darken( $noo_site_link_color, '25%' ), '80%' ); ?>;
}

a.go-to-top {
	color: <?php echo $noo_site_link_color; ?>;
}

/* Shortcode */
/* ====================== */

/* Citilights */

.noo-map .gmap-loading .gmap-loader > div {
	background: none repeat scroll 0 0 <?php echo $noo_site_link_color; ?>;
}

.noo-map .gsearch .gsearch-title i,
.noo_advanced_search_property.vertical .gsearch .gsearch-wrap .gsearch-content .gsearch-action .gsubmit button:hover,
.noo-map .gsearch .gsearch-content .gsearch-field > div.gprice .ui-slider .ui-slider-range,
.noo-map .gsearch .gsearch-content .gsearch-field > div.garea .ui-slider .ui-slider-range,
.agents.grid .hentry .agent-wrap .agent-desc .agent-social a:hover,
.noo-agent .agent-social a:hover,
.property .property-share a:hover,
.recent-properties .caroufredsel-next,
.recent-properties .caroufredsel-prev,
.recent-agents .caroufredsel-next,
.recent-agents .caroufredsel-prev,
.recent-agents .hentry .agent-wrap .agent-social a:hover,
.noo-map .gsearch .gsearch-content .gsearch-field>.form-group.gprice .ui-slider .ui-slider-range,
.noo-map .gsearch .gsearch-content .gsearch-field>.form-group.garea .ui-slider .ui-slider-range,
.noo-map .gmap-infobox .info-close,
.noo-map .gmap-infobox .info-more .info-action,
.form-group .dropdown .dropdown-menu > li > a:focus,
.form-group .dropdown .dropdown-menu > li > a:hover{
	background: <?php echo $noo_site_link_color; ?>;
}
.noo-map .gmap-infobox .info-more .info-action:hover{
	background: <?php echo darken($noo_site_link_color, '10%'); ?>;
}
.noo-map .gsearch .gsearch-title {
	background: <?php echo fade($noo_site_link_color, '70%'); ?>;
}

.noo-map .gmap-zoom a,
.noo-map .gmap-control a {
	background: <?php echo fade($noo_site_link_color, '75%'); ?>;
}
.noo-map .gmap-zoom a:hover,
.noo-map .gmap-control a:hover {
	background: <?php echo fade($noo_site_link_hover_color, '80%'); ?>;
}

.recent-properties .caroufredsel-next:hover,
.recent-properties .caroufredsel-prev:hover,
.recent-agents .caroufredsel-next:hover,
.recent-agents .caroufredsel-prev:hover {
	background: <?php echo $noo_site_link_color_darken_10; ?>;
}
.properties-header .properties-toolbar a.selected,
.properties-header .properties-toolbar a:hover,
.properties .hentry .property-featured .property-category a:hover,
.recent-properties .recent-properties-content .property-row .hentry:hover .property-title a,
.recent-properties .recent-properties-content .property-row .hentry .property-category a:hover {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

.recent-properties.recent-properties-featured .property-featured .property-category {
	background: <?php echo fade($noo_site_link_color, '50%'); ?>;
}

.btn-secondary,
.btn-thirdary,
.wpcf7-submit,
.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a,
.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button,
.properties .hentry .property-info .property-action a,
.page-fullwidth .properties.list .property-fullwidth-action.property-action a,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a,
.recent-properties.recent-properties-featured .property-info .property-action a,
.agents.grid .hentry .agent-wrap .agent-desc .agent-action a {
	background-color: <?php echo $noo_site_secondary_bg; ?>;
	color: <?php echo $noo_site_secondary_color; ?>;
	border-color: <?php echo $noo_site_secondary_bg; ?>;
}
.properties:not(.my-properties) .hentry .property-info .property-action {
	background-color: <?php echo $noo_site_secondary_bg; ?>;
}
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action:hover,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action:hover a,
.properties:not(.my-properties) .hentry .property-info .property-action:hover,
.properties:not(.my-properties) .hentry .property-info .property-action:hover a {
	color: #FFF;
	background-color: <?php echo $noo_site_link_color; ?>;
}

.btn-secondary:hover,
.btn-secondary:focus,
.btn-secondary:active,
.btn-secondary.active,
.open > .dropdown-toggle.btn-secondary,
.btn-default:hover,
.btn-default:focus,
.btn-default:active,
.btn-default.active,
.open > .dropdown-toggle.btn-default,
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active,
.open > .dropdown-toggle.btn-success,
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open > .dropdown-toggle.btn-info,
.btn-warning:hover,
.btn-warning:focus,
.btn-warning:active,
.btn-warning.active,
.open > .dropdown-toggle.btn-warning,
.btn-danger:hover,
.btn-danger:focus,
.btn-danger:active,
.btn-danger.active,
.open > .dropdown-toggle.btn-danger,
.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a:hover,
.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a:focus,
.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a:active,
.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a.active,
.open > .dropdown-toggle.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide .slide-caption .slide-caption-action a,
.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button:hover,
.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button:focus,
.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button:active,
.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button.active,
.open > .dropdown-toggle.noo-map .gsearch .gsearch-content .gsearch-action .gsubmit button,
.properties .hentry .property-info .property-action a:hover,
.properties .hentry .property-info .property-action a:focus,
.properties .hentry .property-info .property-action a:active,
.properties .hentry .property-info .property-action a.active,
.open > .dropdown-toggle.properties .hentry .property-info .property-action a,
.page-fullwidth .properties.list .property-fullwidth-action.property-action a:hover,
.page-fullwidth .properties.list .property-fullwidth-action.property-action a:focus,
.page-fullwidth .properties.list .property-fullwidth-action.property-action a:active,
.page-fullwidth .properties.list .property-fullwidth-action.property-action a.active,
.open > .dropdown-toggle.page-fullwidth .properties.list .property-fullwidth-action.property-action a,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a:hover,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a:focus,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a:active,
.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a.active,
.open > .dropdown-toggle.recent-properties .recent-properties-content .property-row .hentry .property-summary .property-info .property-action a,
.recent-properties.recent-properties-featured .property-info .property-action a:hover,
.recent-properties.recent-properties-featured .property-info .property-action a:focus,
.recent-properties.recent-properties-featured .property-info .property-action a:active,
.recent-properties.recent-properties-featured .property-info .property-action a.active,
.open > .dropdown-toggle.recent-properties.recent-properties-featured .property-info .property-action a,
.agents.grid .hentry .agent-wrap .agent-desc .agent-action a:hover,
.agents.grid .hentry .agent-wrap .agent-desc .agent-action a:focus,
.agents.grid .hentry .agent-wrap .agent-desc .agent-action a:active,
.agents.grid .hentry .agent-wrap .agent-desc .agent-action a.active,
.open > .dropdown-toggle.agents.grid .hentry .agent-wrap .agent-desc .agent-action a,
.agent-property .agents .conact-agent .form-action button:hover,
.wpcf7-submit:hover,
.wpcf7-submit:focus,
.wpcf7-submit:active,
.wpcf7-submit.active,
.noo-map.no-map.search-vertical .gsearch .gsearch-content .gsearch-action .gsubmit button:hover,
.open > .dropdown-toggle.wpcf7-submit {
	background-color: <?php echo $noo_site_link_color; ?>;
}

/* IDX */
<?php if( defined('DSIDXPRESS_PLUGIN_VERSION') ) : ?>
.dsidx-search-widget .dsidx-search-button .submit:hover,
.dsidx-controls a,
.dsidx-results li.dsidx-prop-summary .dsidx-prop-title,
#dsidx-listings .dsidx-listing .dsidx-primary-data {
	background-color: <?php echo $noo_site_link_color; ?>;
}

.dsidx-results li.dsidx-prop-summary .dsidx-prop-features div:before,
#dsidx-listings .dsidx-listing .dsidx-secondary-data div:before {
	color: <?php echo $noo_site_secondary_color; ?>;
}

.dsidx-contact-form .dsidx-contact-form-submit {
	background-color: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

.dsidx-contact-form .dsidx-contact-form-submit:hover {
	background-color: <?php echo $noo_site_secondary_color; ?>;
	border-color: <?php echo $noo_site_secondary_color; ?>;
}
<?php endif; ?>

.btn-thirdary:hover,
.btn-thirdary:focus,
.btn-thirdary:active,
.btn-thirdary.active,
.open > .dropdown-toggle.btn-thirdary {
	background-color: <?php echo darken($noo_site_secondary_bg, '10%'); ?>;
	color: <?php echo $noo_site_secondary_color; ?>;
	border-color: <?php echo darken($noo_site_secondary_bg, '10%'); ?>;;
}

.noo-slider.testimonial-slide .slider-indicators a.selected {
	border: 2px solid <?php echo $noo_site_link_color; ?>;
	background: <?php echo $noo_site_link_color; ?>;
}

input[type="file"]:focus,
input[type="radio"]:focus,
input[type="checkbox"]:focus {
	-webkit-box-shadow: 0 0 2px <?php echo fade($noo_site_link_color, '80%'); ?>;
	box-shadow: 0 0 2px <?php echo fade($noo_site_link_color, '80%'); ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

input[type="checkbox"]:checked:before {
	color: <?php echo $noo_site_link_color; ?>;
}

input[type="radio"]:checked:before {
	background: <?php echo $noo_site_link_color; ?>;
}

.btn-primary,
.widget_newsletterwidget .newsletter-submit {
	background-color: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color_darken_5; ?>;
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary,
.widget_newsletterwidget .newsletter-submit:hover,
.widget_newsletterwidget .newsletter-submit:focus,
.widget_newsletterwidget .newsletter-submit:active,
.widget_newsletterwidget .newsletter-submit.active,
.open > .dropdown-toggle.widget_newsletterwidget .newsletter-submit {
	background-color: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

.btn-primary.pressable {
	-webkit-box-shadow: 0 4px 0 0 <?php echo $noo_site_link_color_darken_15; ?>,0 4px 9px rgba(0,0,0,0.75) !important;
	box-shadow: 0 4px 0 0  <?php echo $noo_site_link_color_darken_15; ?>,0 4px 9px rgba(0,0,0,0.75) !important;
}

.btn-link,
.btn.btn-white:hover,
.wpcf7-submit.btn-white:hover,
.widget_newsletterwidget .newsletter-submit.btn-white:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

.btn-link:hover,
.btn-link:focus {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

.label-primary,
.progress-bar-primary,
.noo-vc-accordion.panel-group .panel-heading:hover,
.noo-vc-accordion.panel-group .panel-heading.active,
.noo-vc-accordion.panel-group .panel-heading:hover .panel-title,
.noo-vc-accordion.panel-group .panel-heading.active .panel-title,
.noo-vc-accordion.panel-group .panel-heading:hover:hover,
.noo-vc-accordion.panel-group .panel-heading.active:hover,
.nav-tabs > li > a:hover,
.tabs-left > .nav-tabs > li.active > a,
.tabs-left > .nav-tabs > li.active > a:hover,
.tabs-left > .nav-tabs > li.active > a:focus {
	background-color: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?>;
}

.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus,
.tabs-left > .nav-tabs > li > a:hover {
	color: <?php echo $noo_site_link_color; ?>;
}

.noo-social a {
	color: <?php echo $noo_typo_body_font_color; ?>;
}
.noo-social a:hover {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

.noo-member .member-avatar .member-social {
	background-color: <?php echo $noo_site_link_color; ?>;
}

.noo-service:hover .service-icon {
	background-color: <?php echo $noo_site_link_color; ?>;
	border-color: <?php echo $noo_site_link_color; ?> !important; 
}

.noo-pricing-table.classic .noo-pricing-column.featured .pricing-header,
.noo-pricing-table.ascending .noo-pricing-column.featured .pricing-content .pricing-value {
	background-color: <?php echo $noo_site_link_color; ?>;
}

.noo-pricing-table .noo-pricing-column .pricing-content .pricing-header .pricing-title,
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-header .pricing-value .noo-price,
.noo-pricing-table.ascending .noo-pricing-column .pricing-content .pricing-header .pricing-title,
.noo-pricing-table.ascending .noo-pricing-column .pricing-value .noo-price {
	color: <?php echo $noo_site_link_color; ?>;
}

.noo-pricing-table.ascending .noo-pricing-column .pricing-value {
	color: <?php echo $noo_typo_body_font_color; ?>;
}

.noo-pricing-table .noo-pricing-column .pricing-content .pricing-footer .btn:hover,
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-footer .wpcf7-submit:hover,
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-footer .agents.grid .hentry .agent-wrap .agent-desc .agent-action a:hover,
.noo-pricing-table .noo-pricing-column .pricing-content .pricing-footer .widget_newsletterwidget .newsletter-submit:hover,
.noo-pricing-table.ascending .noo-pricing-column.featured .pricing-content .pricing-footer .btn,
.noo-pricing-table.ascending .noo-pricing-column.featured .pricing-content .pricing-footer .wpcf7-submit,
.noo-pricing-table.ascending .noo-pricing-column.featured .pricing-content .pricing-footer .agents.grid .hentry .agent-wrap .agent-desc .agent-action a,
.noo-pricing-table.ascending .noo-pricing-column.featured .pricing-content .pricing-footer .widget_newsletterwidget .newsletter-submit {
	color: <?php echo $noo_site_link_color; ?> !important;
	border-color: <?php echo $noo_site_link_color; ?> !important; 
}

.noo-member .member-avatar .member-social a:hover {
	color: <?php echo $noo_site_link_hover_color; ?>;
}

/* Form */
.form-control:focus,
.wpcf7-form-control:not(.wpcf7-submit):focus,
.widget_newsletterwidget .newsletter-email:focus {
	border-color: <?php echo $noo_site_link_color_lighten_10; ?>;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 4px <?php echo $noo_site_link_color; ?>;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 4px <?php echo $noo_site_link_color; ?>;
}

/* Other */
/* ====================== */
.our-service:hover .service-icon i {
	background: <?php echo fade( $noo_site_secondary_bg, '30%' ); ?>;
}

/* Style woocommerce */

.woocommerce ul.products li.product .onsale, 
.woocommerce span.onsale {
  background: <?php echo $noo_site_link_color; ?>;
}

.woocommerce ul.products li.product .onsale:hover, 
.woocommerce span.onsale:hover {
  background: <?php echo $noo_site_link_hover_color; ?>;
}

.woocommerce ul.products li.product .button, 
.woocommerce div.product form.cart .button,
.woocommerce #respond input#submit, 
.woocommerce a.button, 
.woocommerce button.button, 
.woocommerce input.button,
.woocommerce #respond input#submit.alt, 
.woocommerce a.button.alt, 
.woocommerce button.button.alt, 
.woocommerce input.button.alt,
.woocommerce .widget_price_filter .price_slider_amount .button,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .button,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .shop-loop-quickview,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions a,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .add_to_wishlist:before,
.woocommerce .yith-wcwl-add-button > a,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-actions .button.checkout-button,
.woocommerce #payment #place_order,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-actions .button,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:before, .woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:before,
.woocommerce.widget_shopping_cart .buttons .button, 
.woocommerce .widget_shopping_cart .buttons .button,
.woocommerce .cart .button,
.woocommerce .cart input.button {
	background: <?php echo $noo_site_link_color_lighten_10; ?>;
	color: #fff;
}

.woocommerce ul.products li.product .button:hover, 
.woocommerce div.product form.cart .button:hover,
.woocommerce #respond input#submit:hover, 
.woocommerce a.button:hover, 
.woocommerce button.button:hover, 
.woocommerce input.button:hover,
.woocommerce #respond input#submit.alt:hover, 
.woocommerce a.button.alt:hover, 
.woocommerce button.button.alt:hover, 
.woocommerce input.button.alt:hover,
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .button:hover,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .shop-loop-quickview:hover,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions a:hover,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .add_to_wishlist:hover:before,
.woocommerce .yith-wcwl-add-button > a:hover,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-actions .button.checkout-button:hover,
.noo-menu-item-cart .noo-minicart .minicart-footer .minicart-actions .button:hover,
.woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover:before, .woocommerce ul.products li.product figure .product-wrap .shop-loop-actions .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover:before {
	background: <?php echo $noo_site_secondary_color; ?> !important;
	color: #fff;
}

.woocommerce div.product p.price, 
.woocommerce div.product span.price,
.woocommerce ul.products li.product .price,
.woocommerce ul.products li.product figcaption .product_title a:hover {
	color: <?php echo $noo_site_link_color_darken_15; ?>;
}

.woocommerce nav.woocommerce-pagination ul li a:focus, 
.woocommerce nav.woocommerce-pagination ul li a:hover, 
.woocommerce nav.woocommerce-pagination ul li span.current {
	background: <?php echo $noo_site_link_color; ?>;
	color: #fff;
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-range {
	background: <?php echo $noo_site_link_color; ?>;
}

.woocommerce .star-rating span:before, 
.woocommerce .star-rating:before,
.woocommerce p.stars a,
.noo-menu-item-cart .cart-item span,
.fa-shopping-cart:before {
	color: <?php echo $noo_site_secondary_color; ?>;
}

.woocommerce p.stars a:hover,
.noo-menu-item-cart .cart-item span:hover,
.fa-shopping-cart:hover,
.woocommerce .woocommerce-info:before,
.woocommerce .woocommerce-message:before {
	color: <?php echo $noo_site_link_color_darken_15; ?>;
}

.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus,
.woocommerce form .form-row .input-text:focus,
.woocommerce.widget_product_search input[name="s"]:focus,
.woocommerce #review_form #respond textarea:focus {
	border-color: <?php echo $noo_site_link_color; ?>;
}

.woocommerce .woocommerce-info,
.woocommerce .woocommerce-message {
  	border-top: 3px solid <?php echo $noo_site_link_color; ?>;
  	border-right: 1px solid <?php echo $noo_site_link_color; ?>;
  	border-left: 1px solid <?php echo $noo_site_link_color; ?>;
  	border-bottom: 1px solid <?php echo $noo_site_link_color; ?>;
}

.woocommerce .woocommerce-info a.button,
.woocommerce .woocommerce-message a.button,
.woocommerce .wc-proceed-to-checkout a.button,
.woocommerce .wc-proceed-to-checkout a.button.alt
{
	background: transparent !important;
	color: #2e2e2e !important;
}