<?php
$can_render = true;

if ( ! is_plugin_active( 'thrive-ultimatum/thrive-ultimatum.php' ) ) {
	$can_render = false;
}

$design_id   = ! empty( $_POST['tve_ult_shortcode'] ) ? intval( $_POST['tve_ult_shortcode'] ) : 0;

if ( $can_render && empty( $design_id ) ) {
	$can_render = false;
}

if ( ! $can_render ) {
	echo '<p>' . __( 'Thrive Ultimatum Plugin is not activated !', 'thrive-cb' ) . '</p>';

	return;
}

$design = tve_ult_get_design( $design_id );

$config = array(
	'tve_ult_campaign'  => $design['post_parent'],
	'tve_ult_shortcode' => $design_id,
);
?>

<?php if ( empty( $_POST['nowrap'] ) ) : ?>
<div class="thrv_wrapper thrive_ultimatum_shortcode" data-tve-style="1">
	<?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_ultimatum_shortcode__' . json_encode( $config ) . '__CONFIG_ultimatum_shortcode__' ?></div>
	<?php echo str_replace( 'tve_editor', '', tve_ult_render_shortcode( $config ) ) ?>
	<?php if ( empty( $_POST['nowrap'] ) ) : ?>
</div>
<?php endif ?>
