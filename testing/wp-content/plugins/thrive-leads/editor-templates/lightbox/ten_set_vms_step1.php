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
<h2 style="color: #fff; font-size: 53px; margin-top: 0;margin-bottom: 40px;" class="tve_p_center rft">Want More <span class="bold_text">Email Subscribers?</span></h2>
<div class="thrv_wrapper thrv_columns" style="margin-top: 140px;margin-bottom: 0;">
	<div class="tve_colm tve_twc">
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_left tve_content_inner" style="width: 361px;min-width:50px; min-height: 2em;">
				<div data-tve-style="1"
				     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
				     draggable="true">
					<div class="tve_btn tve_nb tve_btn3 tve_bigBtn tve_orange">
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
				<p class="tve_p_center" style="color: #464646; font-size: 19px;margin-top: 0;margin-bottom: 0;">
					I want to have a rapidly growing, super
					-profitable mailing list for my business.
				</p>
			</div>
			<div class="tve_clear"></div>
		</div>
		<div style="width: 397px; margin-top: 0;margin-bottom: 0;"
		     class="thrv_wrapper tve_image_caption aligncenter ten_set_multistep_option">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/ten_set_multistep_option.png' ?>"
                     style="width: 397px;"/>
            </span>
		</div>
	</div>
	<div class="tve_colm tve_twc tve_lst">
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_right tve_content_inner" style="width: 320px;min-width:50px; min-height: 2em;">
				<div data-tve-style="1"
				     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
				     draggable="true">
					<div class="tve_btn tve_nb tve_btn3 tve_bigBtn tve_white">
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
				<p class="tve_p_center" style="color: #464646; font-size: 19px;margin-top: 0;margin-bottom: 0;">
					I'd rather keep struggling instead of doing things the easy way.
				</p>
			</div>
			<div class="tve_clear"></div>
		</div>
	</div>
</div>


