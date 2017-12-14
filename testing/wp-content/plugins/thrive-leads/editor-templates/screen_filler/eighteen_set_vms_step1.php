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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_eighteen_set_vms_step1 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<h4 class="tve_p_center rft" style="color: #a84983; font-size: 36px;margin-top: 0;margin-bottom: 35px;">
			ATTENTION BLOGGERS:
		</h4>
		<h2 class="tve_p_center rft" style="color: #333; font-size: 48px;margin-top: 0;margin-bottom: 0;">
			DO YOU WANT TO DISCOVER THE 4 SIMPLE SECRETS
			TO DOUBLING YOUR BLOG TRAFFIC?
		</h2>
		<h5 class="tve_p_center rft" style="color: #333; font-size: 32px;margin-top: 50px;margin-bottom: 0;">
			Make your choice:
		</h5>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 860px;min-width:50px; min-height: 2em;margin-top: 80px;">
				<div class="thrv_wrapper thrv_columns">
					<div class="tve_colm tve_twc">
						<div class="thrv_wrapper thrv_button_shortcode tve_fullwidthBtn" data-tve-style="1">
							<div class="tve_btn tve_btn3 tve_nb tve_purple tve_normalBtn">
								<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
								   data-tcb-events="|open_state_2|">
                                    <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
									<span class="tve_btn_txt">Show Me the 4 Secrets!</span>
								</a>
							</div>
						</div>
					</div>
					<div class="tve_colm tve_twc tve_lst">
						<div class="thrv_wrapper thrv_button_shortcode tve_fullwidthBtn" data-tve-style="1">
							<div class="tve_btn tve_btn3 tve_nb tve_white tve_normalBtn">
								<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
								   data-tcb-events="|close_screen_filler|">
                                    <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
									<span class="tve_btn_txt">I Don't Want to Know.</span>
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
