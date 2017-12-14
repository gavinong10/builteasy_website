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
 * <a href="#" data-tcb-events="|close_form|" class="tve_evt_manager_listen tve_et_click">CLOSE THIS LIGHTBOX</a>
 *
 * -state switch example ( open_state_x, where x is the index in the _config / multi_step / states array:
 * <a href="#" data-tcb-events="|open_state_2|" class="tve_evt_manager_listen tve_et_click">open state 2</a>
 */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_59_set_vms_step1 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_green">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_icon alignleft tve_brdr_solid"
				     style="margin-right: 10px;">
                     <span data-tve-icon="set-59-download" class="tve_sc_icon set-59-download tve_white"
                           style="font-size: 38px;"></span>
				</div>
				<h3 style="color: #444349; font-size: 24px;margin-top: 0;margin-bottom: 0;">
					<span class="bold_text">Bonus offer: </span>
					<a href class="tve_evt_manager_listen tve_et_click"
					   data-tcb-events="|open_state_2|">
						click here to download our full bundle of bonus guides we created for this post!
					</a>
				</h3>
			</div>
		</div>
	</div>
</div>




