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
<h3 style="color: #fff; font-size: 50px;margin-top: 0;margin-bottom: 40px;" class="tve_p_center rft">
	Sign Up to Get Started with
	the 1st Lesson in the <span class="bold_text">Beginner
    Course</span> Right Away:
</h3>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_2"
	data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 40px;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Your Email Address"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
				<button type="Submit">SEND ME LESSON #1</button>
			</div>
		</div>
	</div>
</div>

