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
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_46_set_vms_step1 tve_white tve_brdr_solid" style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/46_set_bg.png" ?>');background-repeat: no-repeat; background-size: auto; background-position: top left;">
	<div class="thrv_wrapper thrv_columns tve_clearfix">
		<div class="tve_colm tve_oth">
			<div style="width: 206px;margin-top: 25px;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/46_set_image.png' ?>"
                             style="width: 206px;"/>
                    </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h2 style="color: #000000; font-size: 32px;margin-top: 20px;margin-bottom: 20px;" class="rft">
				Get <span class="bold_text">MORE CONVERSIONS</span> with these <span class="bold_text">10 Easy Rules</span>
			</h2>
			<p style="color: #5e5e5e; font-size: 20px;margin-top: 0;margin-bottom: 40px;">
				Increase your conversion rate by following these tested rules.
			</p>
			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_blue">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
						<span class="tve_btn_txt">Yes, I want this FREE Download</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<p class="tve-form-close tve_evt_manager_listen" data-tcb-events="|close_form|">
		X
	</p>
</div>




