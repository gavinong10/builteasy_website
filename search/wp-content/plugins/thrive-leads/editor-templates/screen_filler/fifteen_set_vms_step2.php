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
 * -close screen filler:
 * <a href="#" data-tcb-events="|close_screen_filler|" class="tve_evt_manager_listen tve_et_click">CLOSE THIS SCREEN FILLER</a>
 *
 * -state switch example ( open_state_x, where x is the index in the _config / multi_step / states array:
 * <a href="#" data-tcb-events="|open_state_2|" class="tve_evt_manager_listen tve_et_click">open state 2</a>
 */
?>

<div
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_fifteen_set_vms_step2 tve_white"
	style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set15_bg.jpg" ?>');background-size:cover;background-position: center center;">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper" style="margin-top: 90px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<h2 class="tve_p_center rfs rft" style="color: #fff; font-size: 60px;margin-top: 0;margin-bottom: 0;">
			<span class="bold_text">COURSE 1:</span> GET A SIX-PACK
		</h2>

		<div class="thrv_wrapper">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<h5 class="tve_p_center" style="color: #fff; font-size: 28px;margin-top: 0;margin-bottom: 20px; ">
			Enter your email address below and <span class="bold_text">Start Building Your Own SIX-PACK!!!</span>
		</h5>

		<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 50px;margin-bottom: 50px;">
			<div class="tve_colm tve_oth">
				<div class="thrv_wrapper thrv_icon aligncenter tve_brdr_solid"
				     style="">
                        <span data-tve-icon="fifteen-set-icon-sixpack"
                              class="tve_sc_icon fifteen-set-icon-sixpack tve_teal"
                              style="font-size: 100px;"></span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_teal tve_2"
					data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg"
					       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="email"
								       placeholder="Email Address"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
								<button type="Submit">SUBSCRIBE AND START THE COURSE</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_teal">
				<div class="tve_cb_cnt">
					<p class="tve_p_center" style="color: #fff; font-size: 22px;margin-top: 0;margin-bottom: 0;">
                        <span class="italic_text">
                            " Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis id elit. "  <span class="bold_text">-Marc Stevens,</span> <font color="#1dc7c5">Developer</font>
                        </span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
