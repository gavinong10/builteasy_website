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
<h2 class="tve_p_center rft" style="color: #313131; font-size: 31px;margin-top: 0;margin-bottom: 30px;">
	Get My Detailed <font color="fea62a"><span class="bold_text">CASE STUDY</span></font>:
	"How to Increase Conversions by <span class="bold_text">300%</span>"
</h2>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 420px;min-width:50px; min-height: 2em;">
		<p class="tve_p_center" style="color: #6e6e6e; font-size: 18px;margin-top: 0; margin-bottom: 20px;">
			<span class="italic_text">Enter your email below to get my case study for free:</span>
		</p>

		<div
			class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_orange tve_2"
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
						<button type="Submit">Get Access Now</button>
					</div>
				</div>
			</div>
		</div>
		<p style="color: #a8a8a8; font-size: 12px;margin-top: 10px;margin-bottom: 0;" class="tve_p_center">
                <span class="italic_text">
                    100% Privacy Guarantee. Your email address is safe with me.
                </span>
		</p>
	</div>
	<div class="tve_clear"></div>
</div>
