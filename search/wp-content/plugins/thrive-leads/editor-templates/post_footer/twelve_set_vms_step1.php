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
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_twelve_set_vms_step1 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_blue" style="margin-top: -20px; margin-left: -20px;margin-right: -20px;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_content_container_shortcode">
					<div class="tve_clear"></div>
					<div class="tve_center tve_content_inner" style="width: 885px;min-width:50px; min-height: 2em;">
						<h2 style="color: #272f32; font-size: 48px; margin-top: 0; margin-bottom: 25px;"
						    class="tve_p_center rft">Get Our <span class="bold_text">Free Guides</span> to Become a
							Happier, More Productive You!</h2>
						<h6 style="color: #333333; font-size: 22px; margin-top: 0; margin-bottom: 20px;"
						    class="tve_p_center">Pick the guide you want to start with, to get your instant
							download:</h6>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 50px;">
		<div class="tve_colm tve_oth">
			<div style="width: 124px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/twelve_set_icon1.png' ?>"
                     style="width: 124px;"/>
            </span>
			</div>
			<h5 class="tve_p_center" style="color: #667b81; font-size: 24px;margin-top: 20px;margin-bottom: 10px;">
				Uncluttering 101
			</h5>

			<p class="tve_p_center" style="color: #666666; font-size: 18px;margin-top: 0;margin-bottom: 20px;">
				Guide to clearing up your work space, inbox and head.
			</p>

			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn7 tve_normalBtn tve_blue">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_2|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
						<span class="tve_btn_txt">Download Now</span>
					</a>
				</div>
			</div>
		</div>
		<div class="tve_colm tve_oth">
			<div style="width: 124px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/twelve_set_icon2.png' ?>"
                     style="width: 124px;"/>
            </span>
			</div>
			<h5 class="tve_p_center" style="color: #667b81; font-size: 24px;margin-top: 20px;margin-bottom: 10px;">
				Morning Kickstart
			</h5>

			<p class="tve_p_center" style="color: #666666; font-size: 18px;margin-top: 0;margin-bottom: 20px;">
				Learn how to adopt the morning routines
				of the world's most productive people.
			</p>

			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn7 tve_normalBtn tve_blue">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_3|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
						<span class="tve_btn_txt">Download Now</span>
					</a>
				</div>
			</div>
		</div>
		<div class="tve_colm tve_thc tve_lst">
			<div style="width: 124px; margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/twelve_set_icon3.png' ?>"
                     style="width: 124px;"/>
            </span>
			</div>
			<h5 class="tve_p_center" style="color: #667b81; font-size: 24px;margin-top: 20px;margin-bottom: 10px;">
				The Focus Key
			</h5>

			<p class="tve_p_center" style="color: #666666; font-size: 18px;margin-top: 0;margin-bottom: 20px;">
				The strategy for discovering what
				you're best at and doing more of it.
			</p>

			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn7 tve_normalBtn tve_blue">
					<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
					   data-tcb-events="|open_state_4|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
						<span class="tve_btn_txt">Download Now</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>