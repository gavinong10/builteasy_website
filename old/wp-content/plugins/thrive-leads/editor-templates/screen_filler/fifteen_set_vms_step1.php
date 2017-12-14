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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_fifteen_set_vms_step1 tve_white" style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set15_bg.jpg" ?>');background-size:cover;background-position: center center;">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper" style="margin-top: 90px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<h2 class="tve_p_center rfs rft" style="color: #fff; font-size: 60px;margin-top: 0;margin-bottom: 25px;">
			CHOOSE YOUR PERFECT <span class="bold_text">BODY PLAN</span>
		</h2>
		<h5 class="tve_p_center" style="color: #fff; font-size: 28px;margin-top: 0;margin-bottom: 80px; ">
			<span class="bold_text">What's your dream goal?</span> Select one below and we'll send you a personalized
			training plan to <span class="bold_text">get you there in 12 weeks or less!</span>
		</h5>
		<div class="thrv_wrapper thrv_columns tve_clearfix">
			<div class="tve_colm tve_oth">
				<div class="thrv_wrapper thrv_icon aligncenter tve_brdr_solid"
				     style="margin-bottom: 30px;">
                        <span data-tve-icon="fifteen-set-icon-sixpack" class="tve_sc_icon fifteen-set-icon-sixpack tve_teal"
                              style="font-size: 100px;"></span>
				</div>
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="symbol">
					<div class="tve_cb tve_cb_symbol tve_orange" style="margin-top: -60px;;">
						<div class="thrv_wrapper thrv_icon thrv_cb_text aligncenter tve_no_drag tve_no_icons" style="font-size: 40px;">
							<span class="tve_sc_text tve_sc_icon">COURSE 1</span>
						</div>
						<div class="tve_cb_cnt">
							<h3 style="color: #fff; font-size: 36px;margin-top: 30px;margin-bottom: 0;" class="tve_p_center rft">GET A SIX-PACK</h3>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_teal tve_normalBtn">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_2|">
                            <span class="tve_left tve_btn_im">
                                <i></i>
                                <span class="tve_btn_divider"></span>
                            </span>
							<span class="tve_btn_txt">GET THIS BODY PLAN</span>
						</a>
					</div>
				</div>
				<div style="width: 99px;" class="thrv_wrapper tve_image_caption image_set15_ad">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set15_ad.png' ?>"
                             style="width: 99px"/>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_oth">
				<div class="thrv_wrapper thrv_icon aligncenter tve_brdr_solid"
				     style="margin-bottom: 30px;">
                    <span data-tve-icon="fifteen-set-icon-burnfat" class="tve_sc_icon fifteen-set-icon-burnfat tve_teal"
                          style="font-size: 100px;"></span>
				</div>
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="symbol">
					<div class="tve_cb tve_cb_symbol tve_orange" style="margin-top: -60px;;">
						<div class="thrv_wrapper thrv_icon thrv_cb_text aligncenter tve_no_drag tve_no_icons" style="font-size: 40px;">
							<span class="tve_sc_text tve_sc_icon">COURSE 2</span>
						</div>
						<div class="tve_cb_cnt">
							<h3 style="color: #fff; font-size: 36px;margin-top: 30px;margin-bottom: 0;" class="tve_p_center rft">BURN BODY FAT</h3>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_teal tve_normalBtn">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_3|">
                            <span class="tve_left tve_btn_im">
                                <i></i>
                                <span class="tve_btn_divider"></span>
                            </span>
							<span class="tve_btn_txt">GET THIS BODY PLAN</span>
						</a>
					</div>
				</div>
				<div style="width: 99px;" class="thrv_wrapper tve_image_caption image_set15_ad">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set15_ad.png' ?>"
                             style="width: 99px"/>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_thc tve_lst">
				<div class="thrv_wrapper thrv_icon aligncenter tve_brdr_solid"
				     style="margin-bottom: 30px;">
                        <span data-tve-icon="fifteen-set-icon-getstrong" class="tve_sc_icon fifteen-set-icon-getstrong tve_teal"
                              style="font-size: 100px;"></span>
				</div>
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="symbol">
					<div class="tve_cb tve_cb_symbol tve_orange" style="margin-top: -60px;;">
						<div class="thrv_wrapper thrv_icon thrv_cb_text aligncenter tve_no_drag tve_no_icons" style="font-size: 40px;">
							<span class="tve_sc_text tve_sc_icon">COURSE 3</span>
						</div>
						<div class="tve_cb_cnt">
							<h3 style="color: #fff; font-size: 36px;margin-top: 30px; margin-bottom: 0;" class="tve_p_center rft">GET STRONG</h3>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_teal tve_normalBtn">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_4|">
                            <span class="tve_left tve_btn_im">
                                <i></i>
                                <span class="tve_btn_divider"></span>
                            </span>
							<span class="tve_btn_txt">GET THIS BODY PLAN</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
