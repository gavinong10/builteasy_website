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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_thirteen_set_vms_step1 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_page_section" data-tve-style="1" style="">
			<div class="out "
			     style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set13_pattern.png' ?>'); background-repeat: repeat;">
				<div class="in">
					<div class="cck clearfix">
						<h2 class="tve_p_center rfs rft"
						    style="color: #fff; font-size: 80px;margin-top: 50px;margin-bottom: 20px;">
                            <span style="background: rgba(7, 7, 7, 0.42);">
                                <font color="#d1dbbd">ONLINE MARKETING</font> STUDY 2.0
                            </span>
						</h2>

						<h3 style="color: #fff; font-size: 52px;margin-top: 0;margin-bottom: 40px;"
						    class="tve_p_center rft">
							The Most Comprehensive Study of What Works
							and What Doesn't in Online Business
						</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper" style="margin-top: -10px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<div class="thrv_wrapper thrv_icon aligncenter"
		     style="margin-bottom: 30px;">
            <span data-tve-icon="thirteen-set-icon-file" class="tve_sc_icon thirteen-set-icon-file"
                  style="color: #d1dbbd;font-size: 150px;"></span>
		</div>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 930px;min-width:50px; min-height: 2em;">
				<p class="tve_p_center" style="color: #666666; font-size: 24px;margin-top: 0;margin-bottom: 0;">
					<span class="bold_text"><font color="#60808b">By reading this Guide</font></span>, you will learn
					the most <span class="bold_text">profitable yet the least risky business model</span>
					for you to right now in just <span class="bold_text">30 minutes.</span>
				</p>

				<div class="thrv_wrapper thrv_columns">
					<div class="tve_colm tve_twc">
						<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
							<div class="tve_btn tve_btn5 tve_nb tve_blue tve_bigBtn">
								<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
								   data-tcb-events="|open_state_2|">
                                    <span class="tve_left tve_btn_im">
                                        <i class="tve_sc_icon thirteen-set-icon-check"
                                           data-tve-icon="thrirteen-set-icon-check"></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
									<span class="tve_btn_txt">Yes, send me the study</span>
								</a>
							</div>
						</div>
					</div>
					<div class="tve_colm tve_twc tve_lst">
						<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
							<div class="tve_btn tve_btn5 tve_nb tve_white tve_bigBtn">
								<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
								   data-tcb-events="|close_screen_filler|">
                                    <span class="tve_left tve_btn_im">
                                        <i class="tve_sc_icon thirteen-set-icon-reject"
                                           data-tve-icon="thrirteen-set-icon-reject"></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
									<span class="tve_btn_txt">No, I reject the study</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
