<?php
if ( ! tve_check_if_thrive_theme() ) {
	return;
}

$attributes = array(
	'title'      => isset( $_POST['title'] ) ? $_POST['title'] : "",
	'thumbnails' => isset( $_POST['thumbnails'] ) ? $_POST['thumbnails'] : 'off',
	'no_posts'   => isset( $_POST['no_posts'] ) ? $_POST['no_posts'] : 5,
	'filter'     => isset( $_POST['filter'] ) ? $_POST['filter'] : 'recent',
	'category'   => isset( $_POST['category'] ) ? $_POST['category'] : 0,
);
?>

<?php if ( empty( $_POST['nowrap'] ) ) : ?><div class="thrv_wrapper thrv_posts_list" data-tve-style="1"><?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_posts_list__' . json_encode( $attributes ) . '__CONFIG_posts_list__' ?></div>
	<div class="thrive-shortcode-html"><?php echo thrive_shortcode_posts_list( $attributes, '' ) ?></div>
<?php if ( empty( $_POST['nowrap'] ) ) : ?></div><?php endif ?>