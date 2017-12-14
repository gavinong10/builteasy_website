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
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_49_set_vms_step1 tve_teal tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-top: -20px;margin-left: -40px;margin-right: -40px;">
			<div class="tve_cb_cnt">
				<h2 class="tve_p_center rft" style="color: #333333; font-size: 45px;margin-top: 0;margin-bottom: 40px;">
					<span class="bold_text">The 10 Essential Ingredients</span>
					of Successful Sales Pages
				</h2>
				<div class="thrv_wrapper thrv_columns tve_clearfix">
					<div class="tve_colm tve_tth">
						<p style="color: #5e5e5e; font-size: 20px;margin-top: 30px;margin-bottom: 20px;">
							Understand the reasoning behind the
							10 most important "ingredients" that go into
							an optimized sales page.
						</p>
					</div>
					<div class="tve_colm tve_oth tve_lst">
						<div style="width: 226px;margin-top: 0;margin-bottom: -100px;"
						     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/49_set_image.png' ?>"
                                         style="width: 206px;"/>
                                </span>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper" style="margin: 0 -40px;">
					<hr class="tve_sep tve_sep1"/>
				</div>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_tth">
			<div data-tve-style="1"
			     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn set-49-btn"
			     draggable="true">
				<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_black">
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
			<div style="width: 181px;margin-top: -80px;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption alignright">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/49_set_arrow.png' ?>"
                                         style="width: 181px;"/>
                                </span>
			</div>
		</div>
		<div class="tve_colm tve_oth tve_lst">
			<p>&nbsp;</p>
		</div>
	</div>
	<p class="tve-form-close tve_evt_manager_listen" data-tcb-events="|close_form|">
		X
	</p>
</div>




