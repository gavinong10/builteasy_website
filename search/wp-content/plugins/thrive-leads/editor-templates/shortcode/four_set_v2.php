<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_four_set tve_purple">
	<div class="thrv_wrapper thrv_columns" style="margin-top: 0px !important;">
		<div class="tve_colm tve_foc tve_df tve_fft">
			<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px;">
				<span data-tve-icon="icon-lock" class="tve_sc_icon four-set-icon-lock" style="padding: 0px; border-radius: 0px; font-size: 41px; width: 41px; height: 41px; color: #fff"></span>
			</div>
		</div>
		<div class="tve_colm tve_twc"><h3 style="color: rgb(255, 255, 255); font-size: 24px; margin-top: 0px; margin-bottom: 0px !important;" class="tve_p_center" contenteditable="false">PREMIUM CONTENT LOCKED</h3></div>
		<div class="tve_colm tve_foc tve_df tve_fft tve_lst">
			<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px;">
				<span data-tve-icon="icon-lock" class="tve_sc_icon four-set-icon-lock" style="padding: 0px; border-radius: 0px; color: #fff"></span>
			</div>
		</div>
	</div>
	<p data-default="Enter your text here..." class="tve_p_center" style="color: rgb(255, 255, 255); font-size: 18px;" contenteditable="false"><span class="tve_custom_font_size" style="font-size: 24px;">Enter your details below to get instant access<br>to this premium content post:</span></p>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green thrv_lead_generation_horizontal tve_2" data-inputs-count="2" style="margin-top: 0;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<div style="display: none;" class="thrv_lead_generation_errors">__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__</div>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
					<input name="email" value="" data-placeholder="Email Address" placeholder="Email Address" type="text">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
					<button type="Submit">Get Instant Access</button>
				</div>
			</div>
		</div>
	</div>
</div>