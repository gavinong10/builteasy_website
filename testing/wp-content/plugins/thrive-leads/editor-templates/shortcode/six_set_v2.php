<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_six_set tve_white tve_brdr_dashed" style="border-width: 3px; border-color: rgb(150, 12, 12)">
	<h3 style="color: #505050; font-size: 24px;margin-top: 0;margin-bottom: 17px;" class="tve_p_center" contenteditable="false">
		100 BEST RECIPES EVER: <font color="#de4e40">FOOD &amp; WINE</font>
	</h3>

	<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px; margin-bottom: 20px !important;">
		<span data-tve-icon="icon-lock" class="tve_sc_icon six-set-icon-lock" style="padding: 0px; border-radius: 0px;"></span>
	</div>
	<h5 style="color: rgb(80, 80, 80); font-size: 25px; margin-top: 0px; margin-bottom: 15px;" class="tve_p_center" contenteditable="false">
		This Content is Locked
	</h5>

	<p style="color: rgb(80, 80, 80); font-size: 18px; margin-top: 0px; margin-bottom: 20px;" class="tve_p_center" contenteditable="false">Enter your details below to get <span class="bold_text">free and instant </span><br><span class="bold_text">access</span> to our premium content:-</p>

	<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_red tve_3" data-inputs-count="3" data-tve-style="1" style="margin-top: 20px; margin-bottom: 0;">
		<div class="thrv_lead_generation_code" style="display: none;"></div>
		<div style="display: none;" class="thrv_lead_generation_errors">
			__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__
		</div>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
					<input data-placeholder="" value="" name="name" placeholder="Full Name" type="text">
				</div>
				<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
					<input data-placeholder="" value="" name="email" placeholder="Your email address" type="text">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
					<button type="Submit">Get Instant Access</button>
				</div>
			</div>
		</div>
	</div>
</div>