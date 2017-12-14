<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_blank_set tve_brdr_solid">
	<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2" data-inputs-count="2" data-tve-style="1" style="margin-top: 30px; margin-bottom: 0;">
		<div class="thrv_lead_generation_code" style="display: none;"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
					<input type="text" data-placeholder="" value="" name="email" placeholder="Your Email Address"/>
				</div>
				<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
					<button type="Submit">Yes, Send me the FREE Offer</button>
				</div>
			</div>
		</div>
	</div>
</div>