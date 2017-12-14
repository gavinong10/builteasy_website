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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_52_set_vms_step1 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div data-tve-style="1" class="thrv_wrapper thrv_page_section">
			<div style="background-color: #f5f5f7" class="out">
				<div class="in darkSec">
					<div class="cck tve_clearfix">
						<h2 style="color: #333333; font-size: 40px;margin-top: 5px;margin-bottom: 0;" class="rft">
							Get the <font color="#e60000">FREE</font> Insider’s Guide to
							Building Landing Pages that Convert
						</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper" style="margin: 0;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<div class="thrv_wrapper thrv_columns tve_clearfix">
			<div class="tve_colm tve_oth">
				<div style="width: 185px;margin-top: 0;margin-bottom: 0;"
				     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/51_set_image.png' ?>"
                                         style="width: 185px;"/>
                                </span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<p style="color: #3f3f3f; font-size: 22px;margin-top: 20px;margin-bottom: 35px;">
					Learn how to build an effective landing page
					using my personal hand-picked selection
					of tools
				</p>

				<div data-tve-style="1"
				     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
				     draggable="true">
					<div class="tve_btn tve_nb tve_btn5 tve_normalBtn tve_red">
						<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
						   data-tcb-events="|open_state_2|">
                    <span class="tve_left tve_btn_im">
	                    <i class="tve_sc_icon set-52-icon" data-tve-icon="set-52-icon"
	                       style="background-image: none;"></i>
                        <span class="tve_btn_divider"></span>
                    </span>
							<span class="tve_btn_txt">Yes, I want this FREE DOWNLOAD</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
