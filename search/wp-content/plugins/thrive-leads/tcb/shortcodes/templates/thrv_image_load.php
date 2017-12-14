<?php
$width     = isset( $_POST['width'] ) ? $_POST['width'] : '';
$height    = isset( $_POST['height'] ) ? $_POST['height'] : '';
$max_width = isset( $_POST['max_width'] ) ? $_POST['max_width'] : '';
if ( $max_width && $max_width < $width ) {
	if ( $height ) {
		$height = intval( ( $max_width / $width ) * $height );
	}
	$width = $max_width;
}

?>
<div style="<?php echo $width ? "width: {$width}px" : '' ?>" class="<?php echo empty( $_POST['caption'] ) ? '' : 'wp-caption ' ?>thrv_wrapper tve_image_caption">
    <span class="tve_image_frame">
        <img class="tve_image" alt="<?php echo $_POST['alt_text']; ?>" style="<?php echo $width ? "width: {$width}px" : '' ?>" src="<?php echo $_POST['url']; ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
    </span>

	<?php if ( ! empty( $_POST['caption'] ) ): ?>
		<p class="wp-caption-text"><?php echo $_POST['caption']; ?></p>
	<?php endif; ?>
</div>