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
<h2 style="color: #000000; font-size: 48px;margin-top: 0;margin-bottom: 0;" class="rft tve_p_center">Do You Want to <span class="bold_text">Double Your Traffic</span> in the Next 30 Days?</h2>
<div class="thrv_wrapper thrv_columns" style="margin-top: 80px;margin-bottom: 0;">
	<div class="tve_colm tve_twc">
		<div data-tve-style="1" class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn" draggable="true">
			<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_green">
				<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
				   data-tcb-events="|open_state_2|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
					<span class="tve_btn_txt">YES, LET'S MAKE IT HAPPEN!</span>
				</a>
			</div>
		</div>
	</div>
	<div class="tve_colm tve_twc tve_lst">
		<div data-tve-style="1" class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn" draggable="true">
			<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_red">
				<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
				   data-tcb-events="|close_lightbox|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
					<span class="tve_btn_txt">NO, I DON'T LIKE TRAFFIC.</span>
				</a>
			</div>
		</div>
	</div>
</div>


