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
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_blue" style="margin: -20px -1px 50px;">
		<div class="tve_cb_cnt">
			<h2 class="tve_p_center rft" style="color: #fff; font-size: 45px;margin-top: 20px;margin-bottom: 30px;">
				The <font color="#87c3ff">10 </font><span class="bold_text">Essential</span> Ingredients
				of Successful Sales Pages
			</h2>

			<div style="width: 140px;margin-top: 0;margin-bottom: -50px;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/52_set_image.png' ?>"
                                         style="width: 140px;"/>
                                </span>
			</div>
		</div>
	</div>
</div>
<p class="tve_p_center" style="color: #666666; font-size: 24px;margin-top: 0;margin-bottom: 20px;">
	The Insider’s Guide to Better Lead Generating Sale Pages
</p>
<div data-tve-style="1"
     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
     draggable="true">
	<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_red">
		<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
		   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
			<span class="tve_btn_txt">Yes, I want this FREE DOWNLOAD</span>
		</a>
	</div>
</div>