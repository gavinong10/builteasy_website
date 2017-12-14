<?php

if ( class_exists( 'RevSlider' ) ) :
	$page_id      = get_the_id();
	$prefix       = '_noo_wp_page';
	$slider       = noo_get_post_meta( $page_id, "{$prefix}_slider_rev", '' );
	$slider_pos   = noo_get_post_meta( $page_id, "{$prefix}_slider_position", 'below' );
	$scroll_bottom_btn  = $slider_pos == 'above' ? noo_get_post_meta( $page_id, "{$prefix}_slider_above_scroll_bottom", false ) : false;
	$bg_video           = '';
	$bg_video_poster    = '';

	$custom_bg_video	= noo_get_post_meta( $page_id, "{$prefix}_slider_custom_bg", false );
	if($custom_bg_video) {
		wp_enqueue_script( 'vendor-bigvideo-bigvideo' );
		$bg_video           = noo_get_post_meta( $page_id, "{$prefix}_slider_bg_video", '' );
		$bg_video_poster    = noo_get_post_meta( $page_id, "{$prefix}_slider_bg_video_poster", '' );
		$bg_video_poster = wp_get_attachment_image_src( $bg_video_poster, 'full');
	}
	
?>

<div class="noo-slider-revolution-container <?php if ( $bg_video != '' ) { echo ' bg-video'; } ?>">
	<?php putRevSlider( $slider ); ?>
	<?php if ( $scroll_bottom_btn ) : ?>
        <a href="#" class="noo-slider-scroll-bottom">
          <i class="fa fa-angle-down"></i>
        </a>
      <?php endif; ?>
</div>

<?php if ( $bg_video ) : ?>
	<script type="text/javascript">
		jQuery(function(){
			var BV = new jQuery.BigVideo(); BV.init();
			if ( Modernizr.touch ) {
				BV.show('<?php echo @$bg_video_poster[0]; ?>');
			} else {
				BV.show('<?php echo $bg_video; ?>',{ ambient : true });
			}
		});
	</script>
<?php endif; ?>

<?php endif; ?>