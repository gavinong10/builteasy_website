<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 520px;min-width:50px; min-height: 2em;">
		<h2 class="tve_p_center set4_heading" style="color: #492c3b;font-size: 30px; margin-top: 0px;margin-bottom: 280px;">
			Sign up to Win a royal London experience worth up to $ 12,000
		</h2>
	</div>
	<div class="tve_clear"></div>
</div>

<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_horizontal tve_3" data-inputs-count="3" draggable="true" style="margin-top: 35px;margin-bottom: 0;">
	<div style="display: none;" class="thrv_lead_generation_code"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
				<input type="text" name="name" value="" data-placeholder="Full Name" placeholder="Full Name">
			</div>
			<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
				<input type="text" name="email" value="" data-placeholder="Email" placeholder="Email">
			</div>
			<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
				<button type="Submit">SIGN UP</button>
			</div>
		</div>
	</div>
</div>




