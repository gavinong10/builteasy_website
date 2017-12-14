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
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_teal" style="margin: -15px -15px 0 -15px;">
		<div class="tve_cb_cnt">
			<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 40px;">
				<div class="tve_colm tve_oth">
					<div style="width: 230px;margin-top: 0;margin-bottom: 0;"
					     class="thrv_wrapper tve_image_caption aligncenter">
                         <span class="tve_image_frame">
                            <img class="tve_image"
                                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/nine_set_image.png' ?>"
                                 style="width: 230px"/>
                        </span>
					</div>
				</div>
				<div class="tve_colm tve_tth tve_lst">
					<h2 style="color: #fff; font-size: 55px;margin-top: 0;margin-bottom: 30px;" class="rft">
						Sign Up Below to Get Instant Access!
					</h2>

					<p style="color: #fff; font-size: 20px;margin-top: 0;margin-bottom: 20px;">Sign up below to get instant access to our freebie and receive our 5-day list building course for your online business:</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_blue" style="margin: 0 -15px -40px -15px;padding-left: 30px;padding-right: 30px;">
		<div class="tve_cb_cnt">
			<div
				class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_orange tve_2"
				data-inputs-count="2" data-tve-style="1" style="margin-top: 20px; margin-bottom: 20px;">
				<div class="thrv_lead_generation_code" style="display: none;"></div>
				<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
				<div class="thrv_lead_generation_container tve_clearfix">
					<div class="tve_lead_generated_inputs_container tve_clearfix">
						<div class="tve_lead_fields_overlay"></div>
						<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
							<input type="text" data-placeholder="" value="" name="email"
							       placeholder="Your email address"/>
						</div>
						<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
							<button type="Submit">Subscribe</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



