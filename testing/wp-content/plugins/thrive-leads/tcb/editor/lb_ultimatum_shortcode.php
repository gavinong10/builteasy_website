<h4><?php echo __( 'Thrive Ultimatum Campaign Shortcodes', 'thrive-cb' ) ?></h4>

<?php
$is_thrive_ultimatum_active = is_plugin_active( 'thrive-ultimatum/thrive-ultimatum.php' );

if ( ! $is_thrive_ultimatum_active ) {
	return;
}
$tu_campaigns = tve_ult_get_campaign_with_shortcodes();

if ( empty( $tu_campaigns ) ) {
	echo '<p>' . __( 'No Countdown available' ) . '</p>';

	return;
}

$selected_campaign  = ! empty( $_POST['tve_ult_campaign'] ) ? intval( $_POST['tve_ult_campaign'] ) : 0;
$selected_shortcode = ! empty( $_POST['tve_ult_shortcode'] ) ? intval( $_POST['tve_ult_shortcode'] ) : 0;
?>
<div id="tve_thrive_ultimatum_shortcode_lightbox">

	<input type="hidden" name="tve_lb_type" value="tve_ultimatum_shortcode">

	<hr class="tve_lightbox_line"/>

	<p>
		<?php echo __( "Select a Campaign and a Shortcode design you want to be displayed in your content. All the logic of selected Campaign will be applied on selected design too. So please make sure you do the right settings for your Campaign.", 'thrive-cb' ) ?>
	</p>

	<div class="tve_options_wrapper tve_clearfix">
		<div class="tve_option_container tve_clearfix">
			<label class="lblOption"><?php echo __( "Campaign Name", "thrive-cb" ) ?>:</label>
			<div class="tve_fields_container">
				<div class="tve_lightbox_select_holder">
					<select id="tve_ult_campaign" name="tve_ult_campaign">
						<?php foreach ( $tu_campaigns as $campaign ) : ?>
							<option <?php echo $campaign->ID === $selected_campaign ? 'selected="selected"' : '' ?>
								value="<?php echo $campaign->ID ?>"><?php echo $campaign->post_title ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="tve_option_container tve_clearfix">
			<label class="lblOption"><?php echo __( "Choose shortcode", "thrive-cb" ) ?>:</label>
			<div class="tve_fields_container">
				<div class="tve_lightbox_select_holder">
					<select id="tve_ult_shortcode" name="tve_ult_shortcode" data-selected="<?php echo $selected_shortcode ?>">
						<option><?php echo __( 'Select campaign' ) ?></option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>