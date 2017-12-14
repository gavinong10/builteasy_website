<?php
if ( ! tve_check_if_thrive_theme() ) {
	return;
}
$attributes = array(
	'phone_no'          => isset( $_POST['phone_no'] ) ? $_POST['phone_no'] : "555-555-555",
	'phone_text'        => isset( $_POST['phone_text'] ) ? $_POST['phone_text'] : "Call us",
	'mobile_phone_text' => isset( $_POST['mobile_phone_text'] ) ? $_POST['mobile_phone_text'] : "Call us",
	'color'             => isset( $_POST['phone_color'] ) ? $_POST['phone_color'] : "default",
);

$attributes                      = stripslashes_deep( $attributes );
$attributes['phone_text']        = wptexturize( $attributes['phone_text'] );
$attributes['mobile_phone_text'] = wptexturize( $attributes['mobile_phone_text'] );

?>

<?php if ( empty( $_POST['nowrap'] ) ) : ?>
	<div class="thrv_wrapper thrv_custom_phone">
<?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_custom_phone__' . tve_json_utf8_unslashit( json_encode( $attributes ) ) . '__CONFIG_custom_phone__' ?></div>
	<div class="thrive-shortcode-html">
		<?php echo thrive_shortcode_custom_phone( $attributes, '' ) ?>
	</div>
<?php if ( empty( $_POST['nowrap'] ) ) : ?></div><?php endif ?>