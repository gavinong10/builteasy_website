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

<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 560px;min-width:50px; min-height: 2em;">
		<div data-elem="sc_progress_bar">
			<div class="thrv_wrapper thrv_progress_bar tve_blue thrv_data_element" data-tve-style="1">
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
	</div>
	<div class="tve_clear"></div>
</div>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 30px;">
	<div class="tve_colm tve_oth">
		<div style="width: 214px;margin-top: 0;margin-bottom: 0;"
		     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/56_set_img.png' ?>"
                                         style="width: 214px;"/>
                                </span>
		</div>
	</div>
	<div class="tve_colm tve_tth tve_lst">
		<h2 style="color: #5d5d5d; font-size: 24px;margin-top: 0;margin-bottom: 10px;">
			Where would you like us to send your
			<font color="#71b6d5">free web-icons</font> set?
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
						       placeholder="Enter your email address"/>
					</div>
					<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
						<button type="Submit">Download the Icon Set</button>
					</div>
				</div>
			</div>
		</div>
		<p style="color: #a3a3a3;font-size: 14px;margin-bottom: 0;margin-top: 10px;" class="tve_p_center">
			We hate spam as much as you. Your email is safe with us.
		</p>
	</div>
</div>