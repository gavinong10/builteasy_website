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
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_eleven_set_vms_step1 tve_white tve_brdr_solid"
	style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/eleven_set_bg.png" ?>');background-size:cover;background-position: center center;">
	<h2 style="color: #fff; font-size: 60px;;margin-top: 0;margin-bottom: 70px;" class="tve_p_center rft">
		Join Our <span class="bold_text">Free</span> Interactive Programming Course
	</h2>
	<h4 class="tve_p_center" style="color: #fff; font-size: 24px;margin-top: 0;margin-bottom: 20px;">Choose the options
		that suits your level of skill:</h4>

	<div class="thrv_wrapper thrv_columns">
		<div class="tve_colm tve_twc">
			<div style="width: 32px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter ten_set_multistep_option">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/eleven_set_left_arrow.png' ?>"
                     style="width: 32px;"/>
            </span>
			</div>
			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn5 tve_normalBtn tve_green">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_2|">
                        <span class="tve_left tve_btn_im">
                            <i class="tve_sc_icon eleven-set-icon-beginner" style="background-image: none;"
                               data-tve-icon="eleven-set-icon-beginner"></i>
                            <span class="tve_btn_divider"></span>
                        </span>
						<span class="tve_btn_txt">BEGINNER</span>
					</a>
				</div>
			</div>
			<p class="tve_p_center" style="color: #ffffff; font-size: 18px;margin-top: 0;margin-bottom: 0;">
				Choose this if you have no experience with
				programming and want to get started.
			</p>
		</div>
		<div class="tve_colm tve_twc tve_lst">
			<div style="width: 32px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter ten_set_multistep_option">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/eleven_set_right_arrow.png' ?>"
                     style="width: 32px;"/>
            </span>
			</div>
			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn5 tve_normalBtn tve_green">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_3|">
                        <span class="tve_left tve_btn_im">
                            <i class="tve_sc_icon eleven-set-icon-advanced" style="background-image: none;"
                               data-tve-icon="eleven-set-icon-advanced"></i>
                            <span class="tve_btn_divider"></span>
                        </span>
						<span class="tve_btn_txt">INTERMEDIATE</span>
					</a>
				</div>
			</div>
			<p class="tve_p_center" style="color: #ffffff; font-size: 18px;margin-top: 0;margin-bottom: 0;">
				Choose this if you have some experience with
				programming and want to improve.
			</p>
		</div>
	</div>
</div>
