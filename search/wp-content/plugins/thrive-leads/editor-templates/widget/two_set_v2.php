<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_two_set_v2 tve_green tve_brdr_solid">
	<h4 style="color: #fff; font-size: 34px;line-height: 40px;margin-top: 0;margin-bottom: 0;" class="tve_p_center">Download our <span class="bold_text">free product</span> instantly!</h4>
	<div style=" color: #1e99c9; margin-top: 15px;margin-bottom: 15px;" class="thrv_wrapper thrv_icon aligncenter">
		<span style="font-size: 110px;" class="tve_sc_icon two-set-mail" data-tve-icon="two-set-mail"></span>
	</div>
	<p style="color: #fff; font-size: 16px;margin-top: 0;margin-bottom: 20px;" class="tve_p_center">This is Photoshop's version dolor of lorem ipsum gravida ad nibh.</p>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_blue tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="name" value="" data-placeholder="Your Name:" placeholder="Your Name:">
				</div>
				<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="Your Email:" placeholder="Your Email:">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">Subscribe Now</button>
				</div>
			</div>
		</div>
	</div>
</div>