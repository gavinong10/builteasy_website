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
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
	<div class="tve_cb tve_cb6 tve_red" style="margin-bottom: 30px;">
		<div class="tve_cb_cnt">
			<div style="width: 230px;margin-top: 0;margin-bottom: -30px;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/45_set_image.png' ?>"
                             style="width: 230px;"/>
                    </span>
			</div>
		</div>
	</div>
</div>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 655px;min-width:50px; min-height: 2em;">
		<div style="width: 38px;margin-top: 0;margin-bottom: 0;"
		     class="thrv_wrapper tve_image_caption aligncenter arrow-set-45">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/45_set_arrow.png' ?>"
                             style="width: 38px;"/>
                    </span>
		</div>
		<h2 class="tve_p_center rft" style="color: #000000; font-size: 30px;margin-top: 0;margin-bottom: 10px;">
			Sign up below to get access to your <span class="bold_text">FREE Product</span>
		</h2>
		<div
			class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_black tve_3"
			data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 20px;">
			<div class="thrv_lead_generation_code" style="display: none;"></div>
			<input type="hidden" class="tve-lg-err-msg"
			       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

			<div class="thrv_lead_generation_container tve_clearfix">
				<div class="tve_lead_generated_inputs_container tve_clearfix">
					<div class="tve_lead_fields_overlay"></div>
					<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
						<input type="text" data-placeholder="" value="" name="name"
						       placeholder="Your name"/>
					</div>
					<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
						<input type="text" data-placeholder="" value="" name="email"
						       placeholder="Your Email Address"/>
					</div>
					<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
						<button type="Submit">Yes, Send me My FREE Download</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>



