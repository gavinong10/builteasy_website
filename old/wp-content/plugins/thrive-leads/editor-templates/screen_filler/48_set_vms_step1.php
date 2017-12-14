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
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_48_set_vms_step1 tve_green">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_white" style="margin-top: 100px;">
				<div class="tve_cb_cnt">
					<div class="thrv_wrapper thrv_columns tve_clearfix">
						<div class="tve_colm tve_oth">
							<div style="width: 206px;margin-top: 70px;margin-bottom: 70px;"
							     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/48_set_image.png' ?>"
                                         style="width: 206px;"/>
                                </span>
							</div>
						</div>
						<div class="tve_colm tve_tth tve_lst">
							<h2 style="color: #000000; font-size: 32px; margin-top: 30px;margin-bottom: 25px;" class="rft">
								Optimize your content for a
								<span class="bold_text">SALES FOCUSED</span> Website
							</h2>
							<p style="color: #5e5e5e; font-size: 20px;margin-top: 0;margin-bottom: 30px;">
								My 10 step guide for optimizing those
								small details that will end up making
								<span class="bold_text">a big difference in your sales.</span>
							</p>
							<div data-tve-style="1"
							     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
							     draggable="true">
								<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_green">
									<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
									   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
										<span class="tve_btn_txt">Send me the FREE Download</span>
									</a>
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
