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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_fourteen_set_vms_step1 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_page_section" data-tve-style="1" style="">
			<div class="out pswr">
				<div class="in pddbg"
				     style="box-sizing: border-box; background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set14_bg.jpg' ?>'); max-width: 1920px; box-shadow: none;"
				     data-width="1600" data-height="399">
					<div class="cck clearfix">
						<h2 class="tve_p_center rfs rft"
						    style="color: #303030; font-size: 60px;margin-top: 100px;margin-bottom: 100px;">
							<font color="#fff">WEBS OF INFLUENCE:</font> THE SECRET STRATEGIES THAT MAKE US CLICK
						</h2>
					</div>
				</div>
			</div>
		</div>
		<div style="width: 162px;margin-top: -50px;margin-bottom: 50px;"
		     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set14_clickicon.png' ?>"
                     style="width: 162px;"/>
            </span>
		</div>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 750px;min-width:50px; min-height: 2em;">
				<p class="tve_p_center" style="color: #666; font-size: 24px;margin-top: 0;margin-bottom: 40px;">
					<span class="bold_text"><font color="#1da165">FREE 30-DAY COURSE !</font></span> Aenean sollicitudin, lorem quis bibendum auctor, <span class="bold_text">nisi elit consequat ipsum</span>, nec
					sagittis sem nibh id elit...
				</p>

				<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_blue tve_bigBtn">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_2|">
                                    <span class="tve_left tve_btn_im">
                                        <i class="tve_sc_icon thirteen-set-icon-check"
                                           data-tve-icon="thrirteen-set-icon-check"></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
							<span class="tve_btn_txt">JOIN OUR COURSE TODAY!</span>
						</a>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
