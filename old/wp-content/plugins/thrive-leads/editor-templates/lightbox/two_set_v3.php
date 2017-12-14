<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_green" style="margin: -21px -41px 50px;padding: 20px;">
		<div class="tve_cb_cnt">
			<h2 class="tve_p_center" style="color: #fff;font-size: 40px; margin-top: 0;margin-bottom: 0;">
				Fill out the form below to get your <span class="bold_text">free report</span>
			</h2>
		</div>
	</div>
</div>

<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_blue tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
	<div style="display: none;" class="thrv_lead_generation_code"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
				<input type="text" name="name" value="" data-placeholder="Enter Name" placeholder="Enter Name">
			</div>
			<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
				<input type="text" name="email" value="" data-placeholder="Enter Email" placeholder="Enter Email">
			</div>
			<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
				<button type="Submit">Subscribe Now</button>
			</div>
		</div>
	</div>
</div>
<p style="color: #999999; font-size: 16px;margin-top: 10px;margin-bottom: 20px;" class="tve_p_center">Your Privacy is protected.</p>




