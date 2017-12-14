<?php
function thememove_js_custom_code() {
	?>

	<?php if ( get_theme_mod( 'custom_js_enable' ) ): ?>
		<?php echo html_entity_decode( get_theme_mod( 'custom_js' ) ); ?>
	<?php endif ?>

	<script>
		(function ($) {
			jQuery(window).on('resize', function () {
				if ($(window).width() >= 992) {
					$('#page').css('padding-bottom', $('.uncover .bottom-wrapper').outerHeight());
				}
			});

			jQuery(window).on('load', function () {
				jQuery(window).trigger('resize');
			});
		})(jQuery);
	</script>

	<?php if ( get_theme_mod( 'page_transition' ) == 'type1' ) { ?>
		<script>
			jQuery(document).ready(function ($) {
				//loading effect
				NProgress.start();
				NProgress.done();
			});
		</script>
	<?php } elseif ( get_theme_mod( 'page_transition' ) == 'type2' ) { ?>
		<script>
			jQuery(document).ready(function ($) {
				$(".animsition").animsition({

					inClass: 'fade-in',
					outClass: 'fade-out',
					inDuration: 1000,
					outDuration: 400,
					linkElement: '.navigation a',
					// e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
					loading: true,
					loadingParentElement: 'body', //animsition wrapper element
					loadingClass: 'animsition-loading',
					unSupportCss: [
						'animation-duration',
						'-webkit-animation-duration',
						'-o-animation-duration'
					],
					//"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
					//The default setting is to disable the "animsition" in a browser that does not support "animation-duration".

					overlay: false,

					overlayClass: 'animsition-overlay-slide',
					overlayParentElement: 'body'
				});

			});
		</script>
	<?php } ?>
	<?php
	global $thememove_sticky_header, $wc_thememove_sticky_header;
	if ( ( get_theme_mod( 'header_sticky_enable', header_sticky_enable ) || $thememove_sticky_header == 'enable' || $wc_thememove_sticky_header == 'enable' ) && $thememove_sticky_header != 'disable' && $wc_thememove_sticky_header != 'disable' ) {
		?>
		<script>
			jQuery(document).ready(function ($) {
				<?php global $thememove_header_preset; if($thememove_header_preset == 'header-preset-02' || get_theme_mod( 'header_preset') == 'header-preset-02'||$thememove_header_preset == 'header-preset-03' || get_theme_mod( 'header_preset') == 'header-preset-03'||$thememove_header_preset == 'header-preset-05' || get_theme_mod( 'header_preset') == 'header-preset-05'){ ?>
				$(".nav").headroom(
					<?php }else { ?>
					$(".header").headroom(
						<?php } ?>
						{
							<?php
							global $thememove_header_preset;
							if($thememove_header_preset == 'header-preset-02' || get_theme_mod( 'header_preset') == 'header-preset-02'|| get_theme_mod( 'header_preset') == 'header-preset-05'|| $thememove_header_preset == 'header-preset-05' || get_theme_mod( 'header_preset') == 'header-preset-03' || $thememove_header_preset == 'header-preset-03'){ ?>
							offset: 146
							<?php }elseif($thememove_header_preset == 'header-preset-06' || get_theme_mod( 'header_preset') == 'header-preset-06') { ?>
							offset: 600
							<?php }elseif($thememove_header_preset == 'header-preset-08' || get_theme_mod( 'header_preset') == 'header-preset-08') { ?>
							offset: 900
							<?php }else { ?>
							offset: 44
							<?php } ?>
						}
					);
			});
		</script>
	<?php } ?>
	<?php if ( get_theme_mod( 'enable_back_to_top', enable_back_to_top ) ) { ?>
		<script>
			jQuery(document).ready(function ($) {
				var $window = $(window);
				// Scroll up
				var $scrollup = $('.scrollup');

				$window.scroll(function () {
					if ($window.scrollTop() > 100) {
						$scrollup.addClass('show');
					} else {
						$scrollup.removeClass('show');
					}
				});

				$scrollup.on('click', function (evt) {
					$("html, body").animate({scrollTop: 0}, 600);
					evt.preventDefault();
				});
			});
		</script>
	<?php } ?>
	<?php if ( is_page_template( 'template-contact.php' ) ) { ?>
		<script>
			jQuery(document).ready(function ($) {
				var gmMapDiv = $("#map-canvas");

				(function ($) {

					if (gmMapDiv.length) {

						var gmCenterAddress = gmMapDiv.attr("data-address");
						var gmMarkerAddress = gmMapDiv.attr("data-address");


						gmMapDiv.gmap3({
							action: "init",
							marker: {

								address: gmMarkerAddress,
								options: {
									icon: "<?php echo THEME_ROOT ?>/images/map-marker.png"
								}
							},
							map: {
								options: {
									zoom: 16,
									zoomControl: true,
									zoomControlOptions: {
										style: google.maps.ZoomControlStyle.SMALL
									},
									mapTypeControl: false,
									scaleControl: false,
									scrollwheel: false,
									streetViewControl: false,
									draggable: true,
									navigationControl: false,
									panControl: false,
									styles: [
										{
											"featureType": "water",
											"elementType": "geometry.fill",
											"stylers": [{"color": "#d3d3d3"}]
										},
										{
											"featureType": "transit",
											"stylers": [{"color": "#808080"}, {"visibility": "off"}]
										},
										{
											"featureType": "road.highway",
											"elementType": "geometry.stroke",
											"stylers": [{"visibility": "on"}, {"color": "#b3b3b3"}]
										},
										{
											"featureType": "road.highway",
											"elementType": "geometry.fill",
											"stylers": [{"color": "#ffffff"}]
										},
										{
											"featureType": "road.local",
											"elementType": "geometry.fill",
											"stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"weight": 1.8}]
										},
										{
											"featureType": "road.local",
											"elementType": "geometry.stroke",
											"stylers": [{"color": "#d7d7d7"}]
										},
										{
											"featureType": "poi",
											"elementType": "geometry.fill",
											"stylers": [{"visibility": "on"}, {"color": "#ebebeb"}]
										},
										{
											"featureType": "administrative",
											"elementType": "geometry",
											"stylers": [{"color": "#a7a7a7"}]
										},
										{
											"featureType": "road.arterial",
											"elementType": "geometry.fill",
											"stylers": [{"color": "#ffffff"}]
										},
										{
											"featureType": "road.arterial",
											"elementType": "geometry.fill",
											"stylers": [{"color": "#ffffff"}]
										},
										{
											"featureType": "landscape",
											"elementType": "geometry.fill",
											"stylers": [{"visibility": "on"}, {"color": "#efefef"}]
										},
										{
											"featureType": "road",
											"elementType": "labels.text.fill",
											"stylers": [{"color": "#696969"}]
										},
										{
											"featureType": "administrative",
											"elementType": "labels.text.fill",
											"stylers": [{"visibility": "on"}, {"color": "#737373"}]
										},
										{
											"featureType": "poi",
											"elementType": "labels.icon",
											"stylers": [{"visibility": "off"}]
										},
										{
											"featureType": "poi",
											"elementType": "labels",
											"stylers": [{"visibility": "off"}]
										},
										{
											"featureType": "road.arterial",
											"elementType": "geometry.stroke",
											"stylers": [{"color": "#d6d6d6"}]
										},
										{
											"featureType": "road",
											"elementType": "labels.icon",
											"stylers": [{"visibility": "off"}]
										},
										{
											"featureType": "poi",
											"elementType": "geometry.fill",
											"stylers": [{"color": "#dadada"}]
										}
									]
								}
							}
						})
						;
					}
				})
				(jQuery);
			})
			;
		</script>
	<?php } ?>
<?php
}

add_action( 'wp_footer', 'thememove_js_custom_code' );
