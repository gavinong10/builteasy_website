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
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_twelve_set_vms_step1 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_blue" style="margin-top: -20px; margin-left: -20px;margin-right: -20px;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_content_container_shortcode">
					<div class="tve_clear"></div>
					<div class="tve_center tve_content_inner" style="width: 885px;min-width:50px; min-height: 2em;">
						<h2 style="color: #272f32; font-size: 48px; margin-top: 0; margin-bottom: 25px;"
						    class="tve_p_center rft">Get Our <span class="bold_text">Free Guides</span> to Become a
							Happier, More Productive You!</h2>
						<h6 style="color: #333333; font-size: 22px; margin-top: 0; margin-bottom: 20px;"
						    class="tve_p_center">Pick the guide you want to start with, to get your instant
							download:</h6>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 45px;">
		<div class="tve_colm tve_oth">
			<div style="width: 238px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/twelve_set_step1.png' ?>"
                     style="width: 238px;"/>
            </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h4 class="rft" style="color: #667b81; font-size: 36px;margin-top: 20px;margin-bottom: 10px;">Uncluttering
				101:</h4>

			<p style="color: #666666; font-size: 18px; margin-top: 0;margin-bottom: 20px;">Guide to clearing up your
				work space, inbox and head.</p>

			<div
				class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2"
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
							<button type="Submit">Download the Free Guide</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


