<?php
$errors = array(
	'email'    => __( 'Please enter a valid email address', 'thrive-cb' ),
	'phone'    => __( 'Please enter a valid phone number', 'thrive-cb' ),
	'required' => __( 'Highlighted fields are required', 'thrive-cb' ),
);
?>
<div class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red" data-tve-style="1" data-tve-version="1">
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $errors ) ) ?>"/>

	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_input">
				<input type="text" data-placeholder="" value="" name="name"/>
			</div>
			<div class="tve_lg_input_container tve_lg_input">
				<input type="text" data-placeholder="" value="" name="email"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve_lg_submit" tve-data-style="1">
				<button type="Submit"><?php echo __( "Sign Up", "thrive-cb" ) ?></button>
			</div>
		</div>
	</div>
</div>