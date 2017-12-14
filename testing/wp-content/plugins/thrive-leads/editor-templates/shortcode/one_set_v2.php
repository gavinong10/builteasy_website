<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_one_set tve_green">
	<h2 style="color: #fff; font-size: 36px;margin-top: 0;margin-bottom: 0;" class="tve_p_center" contenteditable="false">Premium Content Locked!</h2>

	<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px; margin-top: 10px !important; margin-bottom: 10px !important;">
		<span data-tve-icon="icon-lock" class="tve_sc_icon one-set-icon-lock" data-tve-custom-colour="38232015" style="padding: 0px; border-radius: 0px; font-size: 95px; width: 95px; height: 95px;"></span>
	</div>
	<p data-default="Enter your text here..." class="tve_p_center" style="font-size: 22px;" contenteditable="false"><font color="#f4f4f4">Enter your details below to instantly <br>reveal the premium content</font></p>

	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red thrv_lead_generation_horizontal tve_2" data-inputs-count="2" style="margin-top: 40px;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<div style="display: none;" class="thrv_lead_generation_errors">__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__</div>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
					<input name="email" value="" data-placeholder="" type="text" placeholder="Email">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
					<button type="Submit">Unlock Content Instantly!</button>
				</div>
			</div>
		</div>
	</div>
</div>