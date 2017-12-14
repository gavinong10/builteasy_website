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
<h2 style="color: #252525; font-size: 46px; margin-top: 0;margin-bottom: 35px;" class="rft tve_p_center">
	GET THE <font color="#1c81a5">CONVERSION BOOST</font> REPORT!
</h2>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 815px;min-width:50px; min-height: 2em;">
		<p style="color: #464646; font-size: 17px;margin-top: 0;margin-bottom: 35px;" class="tve_p_center">
			Most websites make at least 3 of the 15 critical conversion mistakes we’ve compiled in a free report for you. Get
			the report to test your site, find the biggest mistakes and boost your conversions in 40 minutes or less.
		</p>
		<p style="color: #464646; font-size: 17px;margin-top: 0;margin-bottom: 35px;" class="tve_p_center">
			Just sign up below and we’ll send you the report right away:
		</p>
	</div>
	<div class="tve_clear"></div>
</div>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 720px;min-width:50px; min-height: 2em;margin-top: 0;">
		<div
			class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_2"
			data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
			<div class="thrv_lead_generation_code" style="display: none;"></div>
			<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
			<div class="thrv_lead_generation_container tve_clearfix">
				<div class="tve_lead_generated_inputs_container tve_clearfix">
					<div class="tve_lead_fields_overlay"></div>
					<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
						<input type="text" data-placeholder="" value="" name="email"
						       placeholder="Email Address"/>
					</div>
					<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
						<button type="Submit">Send Me the Report!</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>



