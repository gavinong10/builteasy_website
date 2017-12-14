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
<h2 style="color: #fff; font-size: 53px;margin-top: 0;margin-bottom: 50px;" class=" rft tve_p_center">Just one more step...</h2>
<div class="thrv_wrapper thrv_icon tve_brdr_solid aligncenter" style="margin-bottom: 30px;">
	<span class="tve_sc_icon ten-set-icon-mail tve_white" data-tve-icon="icon-mail" style="font-size: 130px;"></span>
</div>
<p style="color: #fff; font-size: 20px;margin-top: 0;margin-bottom: 30px;" class="tve_p_center">Enter your name and email address below to get instant access to the simplest list-building system ever created:</p>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_orange tve_3"
	data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<input type="text" data-placeholder="" value="" name="name"
				       placeholder="Name"/>
			</div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Email Address"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
				<button type="Submit">YES, I WANT TO START IMMEDIATELY.</button>
			</div>
		</div>
	</div>
</div>


