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
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_oth">
		<div class="thrv_wrapper thrv_contentbox_shortcode set_53_cb" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_teal" style="margin-top: -20px;margin-bottom: -20px;margin-left: -41px;">
				<div class="tve_cb_cnt">
					<div style="width: 270px;margin-top: 20px;margin-bottom: 20px;margin-right: -20px;"
					     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_53_image.png' ?>"
                                         style="width: 270px;"/>
                                </span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_colm tve_tth tve_lst">
		<h2 style="color: #333333; font-size: 40px;margin-top: 30px;margin-bottom: 30px;" class="rft">
			Build <font color="#009999">GREAT Looking</font>
			Landing Pages
			in Less Than 10 Minutes
		</h2>
		<p style="color: #3f3f3f; font-size: 22px;margin-top: 0;margin-bottom: 40px;">
			Learn how to build an effective landing page
			using my personal hand-picked selection
			of tools
		</p>
		<div data-tve-style="1"
		     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
		     draggable="true">
			<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_teal">
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
	</div>
</div>