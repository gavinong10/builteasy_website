<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/**
 * STATE EVENTS examples (important bits: data-tcb-events and the "tve_evt_manager_listen tve_et_click" classes
 *
 * -close lb:
 * <a href="#" data-tcb-events="|close_lightbox|" class="tve_evt_manager_listen tve_et_click">CLOSE THIS LIGHTBOX</a>
 *
 * -state switch example ( open_state_x, where x is the index in the _config / multi_step / states array:
 * <a href="#" data-tcb-events="|open_state_2|" class="tve_evt_manager_listen tve_et_click">open state 2</a>
 */
?>
<div data-elem="sc_progress_bar">
	<div class="thrv_wrapper thrv_progress_bar tve_orange thrv_data_element" data-tve-style="1">
		<div class="tve_progress_bar">
			<div class="tve_progress_bar_fill_wrapper" style="width: 50%;" data-fill="50">
				<div class="tve_progress_bar_fill"></div>
				<div class="tve_data_element_label">
					50%
				</div>
			</div>
		</div>
	</div>
</div>
<p class="tve_p_center" style="color: #e67e22; font-size: 14px;margin-top: 0;margin-bottom: 20px;">
	Almost there! Enter your email and click the button below to gain instant access.
</p>
<h2 class="tve_p_center rft" style="color: #5d5d5d; font-size: 34px;margin-top: 40px;margin-bottom: 30px;">
	Never Run Out of Ideas for Delightful,
	Highly Shareable Blog Content!
</h2>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2"
	data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg"
	       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Email Address"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
				<button type="Submit">DOWNLOAD THE GUIDE</button>
			</div>
		</div>
	</div>
</div>
<p class="tve_p_center" style="color: #a3a3a3; font-size: 14px;margin-top: 20px;margin-bottom: 10px;">
	Don't worry, we don't like spam either, and you email address will be safe.
</p>