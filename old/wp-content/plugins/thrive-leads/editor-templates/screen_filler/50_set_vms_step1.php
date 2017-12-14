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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_50_set_vms_step1 tve_black">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div data-tve-style="1" class="thrv_wrapper thrv_page_section" style="margin-top: 100px;">
			<div style="background-color: #fff" class="out">
				<div class="in darkSec">
					<div class="cck tve_clearfix">
						<h2 class="tve_p_center rft"
						    style="color: #666; font-size: 45px;margin-top: 20px;margin-bottom: 30px;">
							Solutions for <span class="bold_text">Smarter</span> Content Marketing
						</h2>

						<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 50px;">
							<div class="tve_colm tve_oth">
								<div style="width: 197px;margin-top: 0;margin-bottom: -120px;"
								     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/50_set_image.png' ?>"
                                         style="width: 197px;"/>
                                </span>
								</div>
							</div>
							<div class="tve_colm tve_tth tve_lst">
								<p style="color: #5e5e5e; font-size: 20px;margin-top: 35px;margin-bottom: 80px;">
									Understand the reasoning behind the 10 most important
									"ingredients" that go into an optimized sales page.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div data-tve-style="1" class="thrv_wrapper thrv_page_section">
			<div style="background-color: #f0f0f0" class="out">
				<div class="in darkSec">
					<div class="cck tve_clearfix">
						<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
							<div class="tve_colm tve_oth">
								<p>&nbsp;</p>
							</div>
							<div class="tve_colm tve_tth tve_lst">
								<div data-tve-style="1"
								     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn set-50-btn"
								     draggable="true">
									<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_orange">
										<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
										   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
											<span class="tve_btn_txt">Yes, I want this FREE Download</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
