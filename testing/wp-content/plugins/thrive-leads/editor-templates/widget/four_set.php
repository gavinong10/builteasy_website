<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_four_set tve_brdr_solid" style="background-image: url(<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set4_widget_bg.jpg' ?>);">
	<h2 style="color: #492c3b; font-size: 30px;line-height: 1.2em;margin-top: 0;margin-bottom: 300px;" class="tve_p_center tve_set4_widget_h2">Sign up to Win a royal London experience worth up to $ 12,000</h2>
	<div data-tve-style="1"
	     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_blue tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3"
	     draggable="true" style="margin-top: 0;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="Full Name"
					       placeholder="Full Name">
				</div>
				<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="Email"
					       placeholder="Email">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">SIGN UP</button>
				</div>
			</div>
		</div>
	</div>
</div>