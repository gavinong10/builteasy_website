<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<h3 class="tve_p_center" style="color: #333;font-size: 28px; margin-top: 0px;margin-bottom: 0;">
	Enter your Name and Email Address below to Get Started right away!
</h3>
<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 35px;margin-bottom: 0;">
	<div style="display: none;" class="thrv_lead_generation_code"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
				<input type="text" name="name" value="" data-placeholder="Name" placeholder="Name">
			</div>
			<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
				<input type="text" name="email" value="" data-placeholder="Email" placeholder="Email">
			</div>
			<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
				<button type="Submit">Subscribe Now</button>
			</div>
		</div>
	</div>
</div>




