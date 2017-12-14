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
<div class="thrv_wrapper thrv_icon alignleft tve_brdr_solid"
     style="margin-left: -60px;margin-top: 10px;">
                     <span data-tve-icon="set-58-arrow" class="tve_sc_icon set-58-arrow tve_purple"
                           style="font-size: 53px;"></span>
</div>
<h2 class="rft" style="color: #444349; font-size: 33px;margin-bottom: 15px;margin-top: 0;">
	<span class="bold_text">SKYROCKET COURSE STUDY:</span>
</h2>
<h3 style="color: #444349; font-size:  29px;margin-top: 0; margin-bottom: 30px;">
	"Learn How To Fly Right Now in 12 steps"
</h3>
<p class="" style="color: #505050; font-size: 17px;margin-top: 0;margin-bottom: 20px;">
	Leave your email below to get right now the course study for free:
</p>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_purple tve_2"
		data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg"
	       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Write your email here"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
				<button type="Submit">CLICK AND GET IT NOW</button>
			</div>
		</div>
	</div>
</div>
<p class="" style="color: #686868; font-size: 13px;margin-top: 20px;margin-bottom: 10px;">
	<span class="italic_text">
		Don’t worry, I guarantee 100% privacy. Your email address is safe with me.
	</span>
</p>