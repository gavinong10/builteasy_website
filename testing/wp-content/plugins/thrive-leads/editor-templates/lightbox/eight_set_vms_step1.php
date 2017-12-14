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
<h2 style="color: #252525; font-size: 46px; margin-top: 0;margin-bottom: 25px;" class="rft tve_p_center">
	DO YOU WANT TO GET <font color="#1c81a5">MORE CUSTOMERS</font>?
</h2>
<h4 style="color: #464646; font-size: 35px;margin-top: 0;margin-bottom: 0;" class="rft tve_p_center">
	(Without Needing More Traffic)
</h4>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 850px;min-width:50px; min-height: 2em;margin-top: 50px;">
		<div class="thrv_wrapper thrv_columns" style="margin-top: 0;margin-bottom: 0;">
			<div class="tve_colm tve_twc">
				<div data-tve-style="1" class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn" draggable="true">
					<div class="tve_btn tve_nb tve_btn1 tve_bigBtn tve_green">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_2|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
							<span class="tve_btn_txt">Yes!</span>
						</a>
					</div>
				</div>
				<p style="color:#464646; font-size: 17px;margin-top: 0;margin-bottom: 0; " class="tve_p_center">
					I want to turn my website into a slick conversion machine and generate more revenue.
				</p>
			</div>
			<div class="tve_colm tve_twc tve_lst">
				<div data-tve-style="1" class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn" draggable="true">
					<div class="tve_btn tve_nb tve_btn1 tve_bigBtn tve_white">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|close_lightbox|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
							<span class="tve_btn_txt">No.</span>
						</a>
					</div>
				</div>
				<p style="color:#464646; font-size: 17px;margin-top: 0;margin-bottom: 0; " class="tve_p_center">
					I don’t want to improve my website, even if I could do so in 40 minutes or less.
				</p>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>



