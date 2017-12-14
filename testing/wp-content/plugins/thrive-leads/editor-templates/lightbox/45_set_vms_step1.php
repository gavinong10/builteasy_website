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
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
	<div class="tve_cb tve_cb6 tve_red" style="margin-bottom: 30px;">
		<div class="tve_cb_cnt">
			<div style="width: 230px;margin-top: 0;margin-bottom: -30px;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/45_set_image.png' ?>"
                             style="width: 230px;"/>
                    </span>
			</div>
		</div>
	</div>
</div>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 655px;min-width:50px; min-height: 2em;">
		<h2 class="tve_p_center rft" style="color: #000000; font-size: 30px;margin-top: 0;margin-bottom: 10px;">
			The <span class="bold_text">FASTEST</span> Method for <span class="bold_text">MORE CONVERSIONS</span>
		</h2>
		<p class="tve_p_center" style="color:#5e5e5e; font-size: 20px;margin-top: 0;margin-bottom: 20px;">
			<span class="bold_text">The 10 rules and tools</span> that will lead you to a better converting landing page
		</p>
		<div data-tve-style="1"
		     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
		     draggable="true">
			<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_black" style="margin-top: 10px;">
				<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
				   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
					<span class="tve_btn_txt">Yes, I Want This FREE REPORT</span>
				</a>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>
