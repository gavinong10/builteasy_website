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
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_47_set_vms_step2 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
		<div class="tve_colm tve_oth">
			<p>
				&nbsp;
			</p>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h2 style="color: #333333; font-size: 32px;margin-top: 20px;margin-bottom: 10px;" class="rft">
				Sign up below to get access to your <span class="bold_text">FREE Product</span>
			</h2>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_blue" style="margin-bottom: 40px;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
					<div class="tve_colm tve_oth">
						<div style="width: 206px;margin-top: -140px;margin-bottom: -30px;"
						     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/47_set_image.png' ?>"
                                         style="width: 206px;"/>
                                </span>
						</div>
					</div>
					<div class="tve_colm tve_tth tve_lst">
						<div
							class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_black tve_3"
							data-inputs-count="3" data-tve-style="1" style="margin-top: -12px; margin-bottom: 0;">
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
										<button type="Submit">Yes, Send me the FREE Download</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p class="tve-form-close tve_evt_manager_listen" data-tcb-events="|close_form|">
		X
	</p>
</div>