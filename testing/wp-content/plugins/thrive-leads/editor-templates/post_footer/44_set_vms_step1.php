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
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_44_set_vms_step1 tve_green tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin: -20px -20px 0 -20px;">
			<div class="tve_cb_cnt">
				<h2 class="tve_p_center rft" style="color: #333333; font-size: 30px;margin-top: 20px;margin-bottom: 30px;">
					Build a <font color="#08932b">Lead Generating</font> Landing Page in Minutes
				</h2>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_content_container_shortcode">
		<div class="tve_clear"></div>
		<div class="tve_center tve_content_inner" style="width: 650px;min-width:50px; min-height: 2em;">
			<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;margin-top: 40px;">
				<div class="tve_colm tve_tth">
					<p style="color: #bae5c5; font-size: 20px;margin-top: 0;margin-bottom: 20px;">
						Learn how FAST and EASY it is to build a great looking landing page, that generates more leads by using my favourite tools and techniques.
					</p>
					<div data-tve-style="1"
					     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
					     draggable="true">
						<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_orange" style="margin-top: 30px;">
							<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
							   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
								<span class="tve_btn_txt">Yes, I Want the FREE REPORT</span>
							</a>
						</div>
					</div>
				</div>
				<div class="tve_colm tve_oth tve_lst">
					<div style="width: 205px;margin-top: -60px;margin-bottom: 0;"
					     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_44_image.png' ?>"
                             style="width: 205px;"/>
                    </span>
					</div>
				</div>
			</div>
		</div>
		<div class="tve_clear"></div>
	</div>
	<p class="tve-form-close tve_evt_manager_listen" data-tcb-events="|close_form|">
		X
	</p>
</div>




