<?php
if ( ! tve_check_if_thrive_theme() ) {
	return;
}

$attributes         = array(
	'optin'  => isset( $_POST['optin'] ) ? intval( $_POST['optin'] ) : null,
	'color'  => isset( $_POST['color'] ) ? $_POST['color'] : '',
	'size'   => isset( $_POST['size'] ) ? $_POST['size'] : 'vertical',
	'text'   => isset( $_POST['text'] ) ? $_POST['text'] : '',
	'layout' => isset( $_POST['layout'] ) ? $_POST['layout'] : 'vertical',
);
$attributes         = stripslashes_deep( $attributes );
$attributes['text'] = wptexturize( $attributes['text'] );

?>

<?php if ( empty( $_POST['nowrap'] ) ) : ?><div class="thrv_wrapper thrv_thrive_optin" data-tve-style="1"><?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_optin__' . tve_json_utf8_unslashit( json_encode( $attributes ) ) . '__CONFIG_optin__' ?></div>
	<div class="thrive-shortcode-html"><?php echo thrive_shortcode_optin( $attributes, '' ) ?></div>
<?php if ( empty( $_POST['nowrap'] ) ) : ?></div><?php endif ?>