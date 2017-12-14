<?php

if ( ! is_plugin_active( 'thrive-leads/thrive-leads.php' ) ) {
	echo '<p>Thrive Leads Plugin is not activated !</p>';

	return;
}

if ( ! function_exists( 'tve_leads_shortcode_render' ) ) {
	echo '<p>Required function for rendering the shortcode does not exist !</p>';

	return;
}

$shortcode_id = $_POST['thrive_leads_shortcode_id'];

$design_data = tve_leads_shortcode_render( array(
	'id'         => $shortcode_id,
	'for_editor' => true
) );

if ( empty( $design_data ) || ! is_array( $design_data ) || empty( $design_data['html'] ) ) {
	echo '<p>Shortcode could not be rendered!</p>';
	die;
}

?>
<?php if ( empty( $_POST['nowrap'] ) ) : ?>
<div class="thrv_wrapper thrive_leads_shortcode" data-tve-style="1">
	<?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_leads_shortcode__' . json_encode( array( 'id' => $shortcode_id ) ) . '__CONFIG_leads_shortcode__' ?></div>
	<div class="thrive-shortcode-html">
		<?php echo str_replace( 'tve_editor_main_content', '', $design_data['html'] ) ?>
		<?php foreach ( $design_data['fonts'] as $font ): ?>
			<link href="<?php echo $font ?>"/>
		<?php endforeach; ?>
		<?php foreach ( $design_data['css'] as $css ): ?>
			<link href="<?php echo $css ?>" type="text/css" rel="stylesheet"/>
		<?php endforeach; ?>
	</div>
	<?php if ( empty( $_POST['nowrap'] ) ) : ?>
</div>
<?php endif ?>
