<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_seven_set_v2 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_top_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-top: -20px; margin-left: -20px; margin-right: -20px; border-radius: 5px; margin-bottom: -20px !important;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px; margin-bottom: 10px !important;">
					<span data-tve-icon="icon-lock" class="tve_sc_icon seven-set-icon-lock" style="padding: 0px; border-radius: 0px; font-size: 38px; width: 38px; height: 38px;"></span>
				</div>
				<p style="color: #666666; font-size: 20px; margin-top: 0;margin-bottom: 20px;" class="tve_p_center" contenteditable="false"><span class="bold_text">THIS CONTENT IS LOCKED!</span></p>

				<p data-default="Enter your text here..." class="tve_p_center" contenteditable="false">Get free &amp; instant access by entering your details below:-</p>

				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_3" data-inputs-count="3" data-tve-style="1" style="margin-top: 0px; margin-bottom: 17px;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<div style="display: none;" class="thrv_lead_generation_errors">
						__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__
					</div>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
								<input data-placeholder="" value="" name="name" placeholder="name*" type="text">
							</div>
							<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
								<input data-placeholder="" value="" name="email" placeholder="email*" type="text">
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
								<button type="Submit">UNLOCK</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>