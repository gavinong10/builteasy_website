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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_thirteen_set_vms_step2 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_page_section" data-tve-style="1" style="">
			<div class="out "
			     style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set13_pattern.png' ?>'); background-repeat: repeat;">
				<div class="in">
					<div class="cck clearfix">
						<h2 class="tve_p_center rfs rft"
						    style="color: #fff; font-size: 80px;margin-top: 50px;margin-bottom: 20px;">
                            <span style="background: rgba(7, 7, 7, 0.42);">
                                <font color="#d1dbbd">ONLINE MARKETING</font> STUDY 2.0
                            </span>
						</h2>

						<h3 style="color: #fff; font-size: 52px;margin-top: 0;margin-bottom: 40px;"
						    class="tve_p_center rft">
							The Most Comprehensive Study of What Works
							and What Doesn't in Online Business
						</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper" style="margin-top: -10px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 685px;min-width:50px; min-height: 2em;margin-top: 90px;">
				<h5 class="tve_p_center rft" style="color: #666666; font-size: 32px;margin-top: 0;margin-bottom: 30px;">
					Get This Guide and <span class="bold_text">Start Making Money!</span>
				</h5>
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_3"
					data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg"
					       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="name"
								       placeholder="Name"/>
							</div>
							<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="email"
								       placeholder="Email"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
								<button type="Submit">GET INSTANT ACCESS</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>

	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
