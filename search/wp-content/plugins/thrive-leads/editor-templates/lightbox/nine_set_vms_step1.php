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
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_teal" style="margin: -15px -15px 0 -15px;">
		<div class="tve_cb_cnt">
			<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 40px;">
				<div class="tve_colm tve_oth">
					<div style="width: 230px;margin-top: 0;margin-bottom: 0;"
					     class="thrv_wrapper tve_image_caption aligncenter">
                         <span class="tve_image_frame">
                            <img class="tve_image"
                                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/nine_set_image.png' ?>"
                                 style="width: 230px"/>
                        </span>
					</div>
				</div>
				<div class="tve_colm tve_tth tve_lst">
					<h2 style="color: #fff; font-size: 55px;margin-top: 0;margin-bottom: 30px;" class="rft">
						The <span class="bold_text">Best Plugin</span> for Building Your Mailing List Faster
					</h2>

					<p style="color: #fff; font-size: 20px;margin-top: 0;margin-bottom: 20px;">Discover how <span class="bold_text">Thrive Leads</span> can skyrocket your mailing list growth and transform your website into a formidable
						list-building machine (without the need for technical know-how).</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_blue" style="margin: 0 -15px -40px -15px;">
		<div class="tve_cb_cnt">
			<div class="thrv_wrapper thrv_content_container_shortcode">
				<div class="tve_clear"></div>
				<div class="tve_center tve_content_inner" style="width: 770px;min-width:50px; min-height: 2em;">
					<div class="thrv_wrapper thrv_columns" style="margin-top: 0;margin-bottom: 0;">
						<div class="tve_colm tve_twc">
							<div data-tve-style="1"
							     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
							     draggable="true">
								<div class="tve_btn tve_nb tve_btn1 tve_bigBtn tve_orange">
									<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
									   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
										<span class="tve_btn_txt">Tell me more!</span>
									</a>
								</div>
							</div>
							<div style="width: 31px;margin-top: 0;margin-bottom: 0;"
							     class="thrv_wrapper tve_image_caption set_nine_option">
                                     <span class="tve_image_frame">
                                        <img class="tve_image"
                                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_nine_option.png' ?>"
                                             style="width: 31px;"/>
                                    </span>
							</div>
						</div>
						<div class="tve_colm tve_twc tve_lst">
							<div data-tve-style="1"
							     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_centerBtn"
							     draggable="true">
								<div class="tve_btn tve_nb tve_btn1 tve_bigBtn tve_orange">
									<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href="http://thrivethemes.com/leads/">
                                <span class="tve_left tve_btn_im">
                                <i></i>
                                <span class="tve_btn_divider"></span>
                                </span>
										<span class="tve_btn_txt">Get the plugin</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tve_clear"></div>
			</div>
		</div>
	</div>
</div>



