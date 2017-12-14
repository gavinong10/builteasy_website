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
<h2 style="color: #333; font-size: 60px;margin-top: 0;margin-bottom: 10px;" class="rft settwo_ms_heading tve_p_center">
	Get the Whitepaper in Your Inbox:
</h2>
<div style="width: 163px; margin-top: 0;margin-bottom: 0;"
     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set2_multistep_book_small.png' ?>"
                     style="width: 163px;"/>
            </span>
</div>
<p class="tve_p_center settwo_ms_paragraph" style="color: #999; font-size: 26px;margin-top: 0;margin-bottom: 30px;">
	Please enter your name and email address below to receive our whitepaper - “The Useless Data Problem” - sent to your inbox for instant downloading.
</p>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_3"
	data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<div class="thrv_wrapper thrv_icon tve_brdr_solid">
                    <span class="tve_sc_icon two-set-user tve_white" data-tve-icon="two-set-user"
                          style="color: #b1b1b1;"></span>
				</div>
				<input type="text" data-placeholder="" value="" name="name"
				       placeholder="Your name"/>
			</div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<div class="thrv_wrapper thrv_icon tve_brdr_solid">
                    <span class="tve_sc_icon two-set-envelope tve_white"
                          data-tve-icon="two-set-envelope" style="color: #b1b1b1;"></span>
				</div>
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Your email address"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
				<button type="Submit">Download the Whitepaper</button>
			</div>
		</div>
	</div>
</div>



