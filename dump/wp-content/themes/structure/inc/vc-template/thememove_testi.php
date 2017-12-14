<?php
extract( shortcode_atts( array(
	'number'          => '',
	'size'            => '',
	'orderby'         => '',
	'order'           => '',
	'display_author'  => '',
	'display_avatar'  => '',
	'display_url'     => '',
	'display_bullets' => '',
	'enable_carousel' => '',
	'enable_autoplay' => '',
	'el_class'        => 'thememove_testimonials',
), $atts ) );
$el_class = $this->getExtraClass( $el_class );
?>
	<div <?php echo 'class="' . $el_class . '"'; ?> >
		<?php do_action( 'woothemes_testimonials', array(
			'limit'           => $number,
			'size'            => $size,
			'orderby'         => $orderby,
			'order'           => $order,
			'display_author'  => $display_author,
			'display_avatar'  => $display_avatar,
			'display_url'     => $display_url,
			'display_bullets' => $display_bullets,
		) ); ?>
	</div>

<?php if ( $enable_carousel == 'true' ) { ?>
	<script>
		jQuery( document ).ready( function ( $ ) {
			$( ".wpb_row .testimonials-list" ).owlCarousel(
				{
					items: 1,
					navigation: false,
					margin: 30,
					<?php if($display_bullets == 'true'){ ?>
					dots: true,
					<?php }else { ?>
					dots: false,
					<?php } ?>
					<?php if($enable_autoplay == 'true'){ ?>
					autoplay: true,
					<?php }else { ?>
					autoplay: false,
					<?php } ?>
					autoplayHoverPause: true
				}
			);
		} );
	</script>
<?php } ?>